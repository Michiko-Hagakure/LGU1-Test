<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class FundRequestController extends Controller
{
    /**
     * Display all fund requests from Energy Efficiency
     */
    public function index()
    {
        $requests = FundRequest::orderBy('id', 'desc')->get();
        
        // Count stats
        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'Approved')->count(),
            'rejected' => $requests->where('status', 'Rejected')->count(),
            'total_amount' => $requests->sum('amount'),
            'approved_amount' => $requests->where('status', 'Approved')->sum('amount'),
        ];

        // Fetch available facilities
        $facilities = DB::connection('facilities_db')
            ->table('facilities')
            ->where('is_available', true)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->select('facility_id', 'name', 'capacity')
            ->get();

        // Equipment options for seminars/events
        $equipment = [
            ['id' => 'projector', 'name' => 'LCD Projector'],
            ['id' => 'screen', 'name' => 'Projector Screen'],
            ['id' => 'microphone', 'name' => 'Wireless Microphone'],
            ['id' => 'speaker', 'name' => 'Sound System / Speakers'],
            ['id' => 'laptop', 'name' => 'Laptop / Computer'],
            ['id' => 'whiteboard', 'name' => 'Whiteboard with Markers'],
            ['id' => 'flipchart', 'name' => 'Flipchart Stand'],
            ['id' => 'extension', 'name' => 'Extension Cords'],
            ['id' => 'chairs', 'name' => 'Additional Chairs'],
            ['id' => 'tables', 'name' => 'Additional Tables'],
            ['id' => 'aircon', 'name' => 'Air Conditioning'],
            ['id' => 'podium', 'name' => 'Podium / Lectern'],
        ];

        return view('admin.fund-requests.index', compact('requests', 'stats', 'facilities', 'equipment'));
    }

    /**
     * Update the status of a fund request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'feedback' => 'nullable|string|max:1000',
            'assigned_facility' => 'nullable|string|max:255',
            'assigned_equipment' => 'nullable|string|max:1000',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string|max:10',
            'approved_amount' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $fundRequest = FundRequest::findOrFail($id);
        $fundRequest->status = $validated['status'];
        $fundRequest->feedback = $validated['feedback'] ?? null;
        
        // Store approval details as JSON in a response_data field or individual fields
        if ($validated['status'] === 'Approved') {
            $responseData = [
                'approved_amount' => $validated['approved_amount'] ?? $fundRequest->amount,
                'assigned_facility' => $validated['assigned_facility'] ?? null,
                'assigned_equipment' => $validated['assigned_equipment'] ?? null,
                'scheduled_date' => $validated['scheduled_date'] ?? null,
                'scheduled_time' => $validated['scheduled_time'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'approved_at' => now()->toDateTimeString(),
                'approved_by' => auth()->user()->name ?? 'Admin',
            ];
            $fundRequest->response_data = json_encode($responseData);
        }
        
        $fundRequest->save();

        // Send approval/rejection data to Energy Efficiency system
        $this->notifyEnergyEfficiency($fundRequest, $validated);

        return redirect()->to(URL::signedRoute('admin.fund-requests.index'))
            ->with('success', 'Fund request ' . strtolower($validated['status']) . ' successfully.');
    }

    /**
     * Send approval/rejection notification to Energy Efficiency system
     */
    private function notifyEnergyEfficiency(FundRequest $fundRequest, array $validated): void
    {
        try {
            $apiUrl = config('services.energy_efficiency.base_url', 'https://energy.local-government-unit-1-ph.com');
            
            // Get facility details if assigned
            $facilityData = [];
            if (!empty($validated['assigned_facility'])) {
                $facility = DB::connection('facilities_db')
                    ->table('facilities')
                    ->where('facility_id', $validated['assigned_facility'])
                    ->first();
                
                if ($facility) {
                    $facilityData = [
                        'facility_id' => $facility->facility_id,
                        'facility_name' => $facility->name,
                        'facility_address' => $facility->address ?? $facility->location ?? null,
                        'facility_capacity' => $facility->capacity ?? null,
                    ];
                }
            }

            // Parse equipment if provided
            $equipment = [];
            if (!empty($validated['assigned_equipment'])) {
                $equipmentIds = explode(',', $validated['assigned_equipment']);
                $equipmentNames = [
                    'projector' => 'LCD Projector',
                    'screen' => 'Projector Screen',
                    'microphone' => 'Wireless Microphone',
                    'speaker' => 'Sound System / Speakers',
                    'laptop' => 'Laptop / Computer',
                    'whiteboard' => 'Whiteboard with Markers',
                    'flipchart' => 'Flipchart Stand',
                    'extension' => 'Extension Cords',
                    'chairs' => 'Additional Chairs',
                    'tables' => 'Additional Tables',
                    'aircon' => 'Air Conditioning',
                    'podium' => 'Podium / Lectern',
                ];
                foreach ($equipmentIds as $index => $eqId) {
                    $equipment[] = [
                        'name' => $equipmentNames[trim($eqId)] ?? trim($eqId),
                        'quantity' => '1'
                    ];
                }
            }

            // Build payload for Energy Efficiency
            $payload = [
                'government_id' => $fundRequest->id,
                'status' => $validated['status'],
                'feedback' => $validated['feedback'] ?? null,
                'requested_amount' => $fundRequest->amount,
                'approved_amount' => $validated['approved_amount'] ?? $fundRequest->amount,
                'scheduled_date' => $validated['scheduled_date'] ?? null,
                'scheduled_time' => $validated['scheduled_time'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'approved_by' => auth()->user()->name ?? 'LGU Admin',
                'equipment' => $equipment,
            ];

            // Merge facility data
            $payload = array_merge($payload, $facilityData);

            // Include seminar_id for linking back to their seminar
            if ($fundRequest->seminar_id) {
                $payload['seminar_id'] = $fundRequest->seminar_id;
            }
            if ($fundRequest->seminar_info) {
                $payload['seminar_info'] = $fundRequest->seminar_info;
            }

            $response = Http::timeout(10)
                ->post("{$apiUrl}/api/ReceiveFundApproval.php", $payload);

            if ($response->successful()) {
                Log::info('Energy Efficiency notification sent successfully', [
                    'fund_request_id' => $fundRequest->id,
                    'status' => $validated['status'],
                    'response' => $response->json(),
                ]);
            } else {
                Log::warning('Energy Efficiency notification failed', [
                    'fund_request_id' => $fundRequest->id,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the approval process
            Log::error('Energy Efficiency notification error', [
                'fund_request_id' => $fundRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
