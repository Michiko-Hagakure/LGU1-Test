<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoadAssistanceRequest;
use App\Services\RoadTransportApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RoadAssistanceController extends Controller
{
    protected RoadTransportApiService $roadApi;

    public function __construct(RoadTransportApiService $roadApi)
    {
        $this->roadApi = $roadApi;
    }
    /**
     * Display all road assistance requests from Road and Transportation system
     */
    public function index()
    {
        // Try to get incoming requests, handle gracefully if table doesn't exist
        try {
            $requests = RoadAssistanceRequest::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $requests = collect(); // Empty collection if table doesn't exist
        }
        
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

        // Get upcoming confirmed bookings that might need road assistance
        $upcomingBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.start_time', '>=', Carbon::now())
            ->select(
                'bookings.*',
                'facilities.name as facility_name',
                'facilities.address as facility_address',
                'lgu_cities.city_name'
            )
            ->orderBy('bookings.start_time')
            ->limit(20)
            ->get();

        // Get outgoing requests sent to Road & Transportation
        $outgoingRequests = DB::connection('facilities_db')
            ->table('citizen_road_requests')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.road-assistance.index', compact('requests', 'stats', 'assistanceTypes', 'upcomingBookings', 'outgoingRequests'));
    }

    /**
     * Send a road assistance request TO the Road & Transportation system
     */
    public function sendRequest(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'start_time' => 'required|string',
            'end_date' => 'required|date',
            'end_time' => 'required|string',
            'location' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'description' => 'required|string|max:2000',
            'booking_id' => 'nullable|integer',
        ]);

        $adminId = session('user_id') ?? auth()->id();

        // Format dates with times
        $startDateTime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time'])->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time'])->format('Y-m-d H:i:s');

        // Send to Road & Transportation API
        $result = $this->roadApi->createRequest([
            'user_id' => $adminId,
            'event_type' => $validated['event_type'],
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'location' => $validated['location'],
            'landmark' => $validated['landmark'],
            'description' => $validated['description'],
        ]);

        if ($result['success']) {
            // Store local reference
            DB::connection('facilities_db')->table('citizen_road_requests')->insert([
                'user_id' => $adminId,
                'external_request_id' => $result['request_id'],
                'event_type' => $validated['event_type'],
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'location' => $validated['location'],
                'landmark' => $validated['landmark'],
                'description' => $validated['description'],
                'booking_id' => $validated['booking_id'],
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->route('admin.road-assistance.index')
                ->with('success', 'Road assistance request sent successfully! External Request ID: ' . $result['request_id']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $result['error'] ?? 'Failed to send request to Road & Transportation system.');
    }

    /**
     * Return road assistance requests as JSON for AJAX polling
     */
    public function getRequestsJson()
    {
        try {
            $requests = RoadAssistanceRequest::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $requests = collect();
        }
        
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
