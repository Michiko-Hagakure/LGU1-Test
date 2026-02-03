<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoadAssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadAssistanceController extends Controller
{
    /**
     * Display all road assistance requests from Road and Transportation system
     */
    public function index()
    {
        $requests = RoadAssistanceRequest::orderBy('id', 'desc')->get();
        
        // Count stats
        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'Approved')->count(),
            'rejected' => $requests->where('status', 'Rejected')->count(),
        ];

        // Assistance type options
        $assistanceTypes = [
            'traffic_management' => 'Traffic Management',
            'road_closure' => 'Temporary Road Closure',
            'escort' => 'Vehicle Escort Service',
            'signage' => 'Traffic Signage & Cones',
            'personnel' => 'Traffic Personnel Deployment',
            'rerouting' => 'Traffic Rerouting Plan',
        ];

        return view('admin.road-assistance.index', compact('requests', 'stats', 'assistanceTypes'));
    }

    /**
     * Return road assistance requests as JSON for AJAX polling
     */
    public function getRequestsJson()
    {
        $requests = RoadAssistanceRequest::orderBy('id', 'desc')->get();
        
        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'Approved')->count(),
            'rejected' => $requests->where('status', 'Rejected')->count(),
        ];

        return response()->json(['data' => $requests, 'stats' => $stats]);
    }

    /**
     * Update the status of a road assistance request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'feedback' => 'nullable|string|max:1000',
            'assigned_personnel' => 'nullable|string|max:500',
            'assigned_equipment' => 'nullable|string|max:1000',
            'traffic_plan' => 'nullable|string|max:2000',
            'deployment_date' => 'nullable|date',
            'deployment_start_time' => 'nullable|string|max:10',
            'deployment_end_time' => 'nullable|string|max:10',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $roadRequest = RoadAssistanceRequest::findOrFail($id);
        $roadRequest->status = $validated['status'];
        $roadRequest->feedback = $validated['feedback'] ?? null;
        
        // Store approval details as JSON in response_data field
        if ($validated['status'] === 'Approved') {
            $responseData = [
                'assigned_personnel' => $validated['assigned_personnel'] ?? null,
                'assigned_equipment' => $validated['assigned_equipment'] ?? null,
                'traffic_plan' => $validated['traffic_plan'] ?? null,
                'deployment_date' => $validated['deployment_date'] ?? null,
                'deployment_start_time' => $validated['deployment_start_time'] ?? null,
                'deployment_end_time' => $validated['deployment_end_time'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'approved_at' => now()->toDateTimeString(),
                'approved_by' => auth()->user()->name ?? 'Admin',
            ];
            $roadRequest->response_data = json_encode($responseData);
        }
        
        $roadRequest->save();

        // Send approval/rejection data to Road and Transportation system
        $this->notifyRoadTransportSystem($roadRequest, $validated);

        return redirect()->route('admin.road-assistance.index')
            ->with('success', 'Road assistance request has been ' . strtolower($validated['status']) . '.');
    }

    /**
     * Send approval/rejection notification to Road and Transportation system
     */
    private function notifyRoadTransportSystem(RoadAssistanceRequest $roadRequest, array $validated)
    {
        try {
            $apiUrl = config('services.road_transport.url');
            
            if (empty($apiUrl)) {
                Log::warning('Road and Transportation API URL not configured');
                return;
            }

            $payload = [
                'receive_approval' => '1',
                'road_request_id' => $roadRequest->id,
                'status' => $validated['status'],
                'feedback' => $validated['feedback'] ?? null,
                'assigned_personnel' => $validated['assigned_personnel'] ?? null,
                'assigned_equipment' => $validated['assigned_equipment'] ?? null,
                'traffic_plan' => $validated['traffic_plan'] ?? null,
                'deployment_date' => $validated['deployment_date'] ?? null,
                'deployment_start_time' => $validated['deployment_start_time'] ?? null,
                'deployment_end_time' => $validated['deployment_end_time'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
            ];

            $response = Http::timeout(10)
                ->asForm()
                ->post("{$apiUrl}/request_road_assistance.php", $payload);

            if ($response->successful()) {
                Log::info('Road and Transportation system notified successfully', [
                    'road_request_id' => $roadRequest->id,
                    'status' => $validated['status']
                ]);
            } else {
                Log::error('Failed to notify Road and Transportation system', [
                    'road_request_id' => $roadRequest->id,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception notifying Road and Transportation system', [
                'road_request_id' => $roadRequest->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
