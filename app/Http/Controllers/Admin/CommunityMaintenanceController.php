<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommunityMaintenanceController extends Controller
{
    /**
     * Display the facility maintenance request form
     */
    public function create()
    {
        // Get facilities for dropdown
        $facilities = $this->getFacilities();
        
        // Get report types
        $reportTypes = $this->getReportTypes();
        
        // Get priority levels
        $priorityLevels = $this->getPriorityLevels();
        
        return view('admin.community-maintenance.create', compact(
            'facilities',
            'reportTypes',
            'priorityLevels'
        ));
    }

    /**
     * Submit the facility maintenance request to the Community Infrastructure API
     */
    public function store(Request $request)
    {
        // Validate required fields
        $validated = $request->validate([
            'facility_id' => 'required|integer',
            'resident_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'subject' => 'required|string|max:500',
            'description' => 'required|string',
            'unit_number' => 'nullable|string|max:255',
            'report_type' => 'nullable|string|in:maintenance,complaint,suggestion,emergency',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
        ]);

        try {
            $payloadMode = $request->input('payload_mode', 'standard');

            // Get facility name for unit_number context
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $validated['facility_id'])
                ->first();

            $facilityName = $facility ? $facility->name : 'Unknown Facility';

            // Prepare the request payload (only fields specified in API documentation)
            // Use facility name only for unit_number, remove special characters (CIM API has issues)
            $unitNumber = preg_replace('/[^a-zA-Z0-9\s]/', '', $facilityName);
            
            // Use original resident name (don't append timestamp - causes sync issues)
            $payload = [
                'resident_name' => $validated['resident_name'],
                'contact_info' => $validated['contact_info'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'unit_number' => $unitNumber,
                'report_type' => $validated['report_type'] ?? 'maintenance',
                'priority' => $validated['priority'] ?? 'medium',
            ];

            // Send request to Community Infrastructure Maintenance API
            $response = $this->sendToCommunityCIM($payload, $payloadMode);

            if ($response['success']) {
                // Log successful submission
                Log::info('Community maintenance request submitted successfully', [
                    'report_id' => $response['report_id'] ?? null,
                    'user_id' => session('user_id'),
                    'facility_id' => $validated['facility_id'],
                ]);

                // Store local record for tracking
                $this->storeLocalRecord($validated, $response['report_id'] ?? null, $facilityName);

                return redirect()
                    ->route('admin.community-maintenance.create')
                    ->with('success', 'Facility maintenance report submitted successfully! Report ID: ' . ($response['report_id'] ?? 'Pending'));
            }

            return back()
                ->withInput()
                ->with('error', $response['message'] ?? 'Failed to submit maintenance report. Please try again.');

        } catch (\Exception $e) {
            Log::error('Community maintenance request failed', [
                'error' => $e->getMessage(),
                'user_id' => session('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the maintenance report: ' . $e->getMessage());
        }
    }

    /**
     * Display list of submitted maintenance requests
     */
    public function index()
    {
        try {
            $requests = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } catch (\Exception $e) {
            $requests = collect();
            Log::warning('Failed to fetch community maintenance requests', [
                'error' => $e->getMessage(),
            ]);
        }

        return view('admin.community-maintenance.index', compact('requests'));
    }

    /**
     * Get maintenance requests as JSON for AJAX polling
     */
    public function getRequestsJson()
    {
        try {
            // First, sync statuses from Community API
            $this->syncStatusesFromApi();
            
            $requests = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $stats = [
                'pending' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'submitted')
                    ->count(),
                'in_progress' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->whereIn('status', ['reviewed', 'in_progress'])
                    ->count(),
                'resolved' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->whereIn('status', ['resolved', 'closed'])
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $requests,
                'stats' => $stats,
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync statuses from Community API silently
     */
    private function syncStatusesFromApi(): void
    {
        try {
            $residentNames = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->whereNotIn('status', ['resolved', 'closed'])
                ->distinct()
                ->pluck('resident_name');

            foreach ($residentNames as $residentName) {
                try {
                    $statusData = $this->fetchReportStatus($residentName);
                    if ($statusData['success'] && !empty($statusData['data'])) {
                        $this->updateLocalStatuses($statusData['data']);
                    }
                } catch (\Exception $e) {
                    // Continue with next resident
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to sync statuses from API', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check status of reports from the Community Infrastructure API
     */
    public function checkStatus($residentName)
    {
        try {
            $statusData = $this->fetchReportStatus($residentName);

            if ($statusData['success']) {
                // Update local records with latest status
                $this->updateLocalStatuses($statusData['data']);

                return response()->json($statusData);
            }

            return response()->json($statusData, 400);

        } catch (\Exception $e) {
            Log::error('Failed to fetch report status', [
                'resident_name' => $residentName,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch report status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh status for all reports and return updated view
     */
    public function refreshStatuses()
    {
        try {
            // Get unique resident names from local records
            $residentNames = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->whereNotIn('status', ['resolved', 'closed'])
                ->distinct()
                ->pluck('resident_name');

            $synced = 0;
            $failed = 0;

            foreach ($residentNames as $residentName) {
                try {
                    $statusData = $this->fetchReportStatus($residentName);
                    
                    if ($statusData['success'] && !empty($statusData['data'])) {
                        $this->updateLocalStatuses($statusData['data']);
                        $synced++;
                    }
                } catch (\Exception $e) {
                    $failed++;
                }
            }

            $message = "Synced {$synced} resident(s).";
            if ($failed > 0) {
                $message .= " {$failed} failed.";
            }

            return back()->with($synced > 0 ? 'success' : 'info', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync statuses: ' . $e->getMessage());
        }
    }

    /**
     * Send request to Community Infrastructure Maintenance API
     */
    private function sendToCommunityCIM(array $payload, string $payloadMode = 'standard'): array
    {
        $baseUrl = config('services.community_cim.base_url');
        $timeout = config('services.community_cim.timeout', 30);

        $url = rtrim($baseUrl, '/') . '/api/integration/RequestFacilityMaintenance.php';

        // Use PHP curl - confirmed working via diagnostic
        $jsonPayload = json_encode($payload);
        
        Log::info('Community CIM request starting', [
            'url' => $url,
            'payload_length' => strlen($jsonPayload),
            'payload_json' => $jsonPayload,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonPayload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        Log::info('Community CIM response received', [
            'http_code' => $httpCode,
            'response' => $responseBody,
        ]);
        
        if ($curlError) {
            Log::error('Community CIM curl error', ['error' => $curlError]);
            return ['success' => false, 'message' => 'Connection error: ' . $curlError];
        }

        $responseData = json_decode($responseBody, true) ?? [];

        if ($responseData['success'] ?? false) {
            return $responseData;
        }

        Log::warning('Community CIM request failed', [
            'payload_mode' => $payloadMode,
            'body' => $responseBody,
            'payload' => [
                'resident_name' => $payload['resident_name'] ?? null,
                'subject' => $payload['subject'] ?? null,
                'report_type' => $payload['report_type'] ?? null,
                'priority' => $payload['priority'] ?? null,
                'unit_number' => $payload['unit_number'] ?? null,
                'contact_info' => empty($payload['contact_info']) ? null : '[redacted]',
                'description' => empty($payload['description']) ? null : '[redacted]',
            ],
        ]);

        return [
            'success' => false,
            'message' => $responseData['message'] ?? 'Request failed',
        ];
    }

    /**
     * Fetch report status from Community Infrastructure Maintenance API
     */
    private function fetchReportStatus(string $residentName): array
    {
        $baseUrl = config('services.community_cim.base_url');
        $timeout = config('services.community_cim.timeout', 30);

        $url = rtrim($baseUrl, '/') . '/api/integration/RequestFacilityMaintenance.php';

        $response = Http::timeout($timeout)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get($url, ['resident_name' => $residentName]);

        if ($response->successful()) {
            return $response->json();
        }

        $errorData = $response->json();
        
        return [
            'success' => false,
            'message' => $errorData['message'] ?? 'Failed to fetch status: ' . $response->status(),
        ];
    }

    /**
     * Store a local record of the submission for tracking
     */
    private function storeLocalRecord(array $validated, ?int $externalReportId, string $facilityName): void
    {
        try {
            DB::connection('facilities_db')->table('community_maintenance_requests')->insert([
                'external_report_id' => $externalReportId,
                'facility_id' => $validated['facility_id'],
                'facility_name' => $facilityName,
                'resident_name' => $validated['resident_name'],
                'contact_info' => $validated['contact_info'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'unit_number' => $validated['unit_number'] ?? $facilityName,
                'report_type' => $validated['report_type'] ?? 'maintenance',
                'priority' => $validated['priority'] ?? 'medium',
                'status' => 'submitted',
                'submitted_by_user_id' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log but don't fail the request if local storage fails
            Log::warning('Failed to store local community maintenance record', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update local records with statuses from API
     */
    private function updateLocalStatuses(array $reports): void
    {
        try {
            foreach ($reports as $report) {
                DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('external_report_id', $report['id'])
                    ->update([
                        'status' => $report['status'] ?? 'submitted',
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update local maintenance statuses', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get facilities from database
     */
    private function getFacilities(): array
    {
        try {
            $facilities = DB::connection('facilities_db')
                ->table('facilities')
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['facility_id', 'name', 'address', 'full_address']);

            return $facilities->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get report types
     */
    private function getReportTypes(): array
    {
        return [
            ['value' => 'maintenance', 'label' => 'Maintenance Issue', 'description' => 'Facility maintenance issues'],
            ['value' => 'complaint', 'label' => 'Complaint', 'description' => 'Complaints about facilities or services'],
            ['value' => 'suggestion', 'label' => 'Suggestion', 'description' => 'Suggestions for improvement'],
            ['value' => 'emergency', 'label' => 'Emergency', 'description' => 'Emergency situations requiring immediate attention'],
        ];
    }

    /**
     * Get priority levels
     */
    private function getPriorityLevels(): array
    {
        return [
            ['value' => 'low', 'label' => 'Low', 'color' => 'green', 'description' => 'Non-urgent, can wait'],
            ['value' => 'medium', 'label' => 'Medium', 'color' => 'yellow', 'description' => 'Should be addressed soon'],
            ['value' => 'high', 'label' => 'High', 'color' => 'orange', 'description' => 'Needs prompt attention'],
            ['value' => 'urgent', 'label' => 'Urgent', 'color' => 'red', 'description' => 'Immediate attention required'],
        ];
    }
}
