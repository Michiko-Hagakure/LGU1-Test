<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InfrastructureProjectController extends Controller
{
    /**
     * Display the infrastructure project request form
     */
    public function create()
    {
        // Get priority levels from database if available, otherwise use defaults
        $priorityLevels = $this->getPriorityLevels();
        
        // Get project categories
        $projectCategories = $this->getProjectCategories();
        
        return view('admin.infrastructure.project-request', compact(
            'priorityLevels',
            'projectCategories'
        ));
    }

    /**
     * Submit the infrastructure project request to the external API
     */
    public function store(Request $request)
    {
        // Validate required fields
        $validated = $request->validate([
            'requesting_office' => 'required|string|max:255',
            'requesting_office_other' => 'nullable|required_if:requesting_office,Other|string|max:255',
            'contact_person' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'position_other' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'project_title' => 'required|string|max:500',
            'project_category' => 'required|string|max:255',
            'project_category_other' => 'nullable|required_if:project_category,Other|string|max:255',
            'project_location' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'problem_identified' => 'required|string',
            'scope_item1' => 'nullable|string|max:500',
            'scope_item2' => 'nullable|string|max:500',
            'scope_item3' => 'nullable|string|max:500',
            'estimated_budget' => 'nullable|numeric|min:0',
            'priority_level' => 'nullable|string|in:low,medium,high',
            'requested_start_date' => 'nullable|date|after:today',
            'prepared_by' => 'nullable|string|max:255',
            'prepared_position' => 'nullable|string|max:255',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'nullable|image|max:2048',
            'map_image' => 'nullable|image|max:2048',
            'resolution_file' => 'nullable|file|mimes:pdf|max:2048',
            'other_files' => 'nullable|array|max:2',
            'other_files.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle "Other" option for requesting_office
        if ($validated['requesting_office'] === 'Other' && !empty($validated['requesting_office_other'])) {
            $validated['requesting_office'] = $validated['requesting_office_other'];
        }
        
        // Handle "Other" option for position
        if (($validated['position'] ?? '') === 'Other' && !empty($validated['position_other'] ?? '')) {
            $validated['position'] = $validated['position_other'];
        }
        
        // Handle "Other" option for project_category
        if ($validated['project_category'] === 'Other' && !empty($validated['project_category_other'] ?? '')) {
            $validated['project_category'] = $validated['project_category_other'];
        }

        try {
            // FIRST: Store local record to ensure we never lose track
            $localId = $this->storeLocalRecord($validated, null);
            
            if (!$localId) {
                return back()
                    ->withInput()
                    ->with('error', 'Failed to create local tracking record. Please try again.');
            }

            // Prepare the request payload
            $payload = $this->preparePayload($validated, $request);

            // Send request to Infrastructure PM API
            $response = $this->sendToInfrastructurePM($payload);

            if ($response['success']) {
                $externalId = $response['data']['project_id'] ?? null;
                
                // Update local record with external project ID
                if ($externalId) {
                    DB::connection('facilities_db')
                        ->table('infrastructure_project_requests')
                        ->where('id', $localId)
                        ->update(['external_project_id' => $externalId, 'updated_at' => now()]);
                }

                // Log successful submission
                Log::info('Infrastructure project request submitted successfully', [
                    'local_id' => $localId,
                    'project_id' => $externalId,
                    'user_id' => session('user_id'),
                ]);

                return redirect()
                    ->route('admin.infrastructure.project-request')
                    ->with('success', 'Project request submitted successfully! Project ID: ' . ($externalId ?? 'PR-' . $localId));
            }

            // API failed but local record exists - update status
            DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->where('id', $localId)
                ->update(['status' => 'api_error', 'updated_at' => now()]);

            return back()
                ->withInput()
                ->with('error', ($response['message'] ?? 'Failed to submit to Infrastructure PM.') . ' Your request has been saved locally and can be resubmitted.');

        } catch (\Exception $e) {
            Log::error('Infrastructure project request failed', [
                'error' => $e->getMessage(),
                'user_id' => session('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the project request: ' . $e->getMessage());
        }
    }

    /**
     * Prepare the payload for the API request
     */
    private function preparePayload(array $validated, Request $request): array
    {
        $payload = [
            'requesting_office' => $validated['requesting_office'],
            'contact_person' => $validated['contact_person'],
            'project_title' => $validated['project_title'],
            'project_category' => $validated['project_category'],
            'problem_identified' => $validated['problem_identified'],
        ];

        // Add optional fields
        $optionalFields = [
            'position', 'contact_number', 'contact_email', 'project_location',
            'latitude', 'longitude', 'scope_item1', 'scope_item2', 'scope_item3',
            'estimated_budget', 'priority_level', 'requested_start_date',
            'prepared_by', 'prepared_position'
        ];

        foreach ($optionalFields as $field) {
            if (!empty($validated[$field])) {
                $payload[$field] = $validated[$field];
            }
        }

        // Process attachments
        $attachments = [];

        // Handle photos
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $this->fileToBase64($photo);
            }
            $attachments['photos'] = $photos;
        }

        // Handle map image
        if ($request->hasFile('map_image')) {
            $attachments['map'] = $this->fileToBase64($request->file('map_image'));
        }

        // Handle resolution file
        if ($request->hasFile('resolution_file')) {
            $attachments['resolution'] = $this->fileToBase64($request->file('resolution_file'));
        }

        // Handle other files
        if ($request->hasFile('other_files')) {
            $others = [];
            foreach ($request->file('other_files') as $file) {
                $others[] = $this->fileToBase64($file);
            }
            $attachments['others'] = $others;
        }

        if (!empty($attachments)) {
            $payload['attachments'] = $attachments;
        }

        return $payload;
    }

    /**
     * Convert file to base64 encoded string
     */
    private function fileToBase64($file): string
    {
        $mimeType = $file->getMimeType();
        $contents = file_get_contents($file->getRealPath());
        return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
    }

    /**
     * Send request to Infrastructure PM API
     */
    private function sendToInfrastructurePM(array $payload): array
    {
        $baseUrl = config('services.infrastructure_pm.base_url');
        $timeout = config('services.infrastructure_pm.timeout', 30);
        $apiKey = config('services.infrastructure_pm.api_key');

        $url = rtrim($baseUrl, '/') . '/api/integrations/ProjectRequest.php';

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $response = Http::timeout($timeout)
            ->withHeaders($headers)
            ->post($url, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        // Handle error response
        $errorData = $response->json();
        
        return [
            'success' => false,
            'message' => $errorData['message'] ?? 'Request failed with status: ' . $response->status(),
        ];
    }

    /**
     * Store a local record of the submission for tracking
     * @return int|null The ID of the inserted record, or null on failure
     */
    private function storeLocalRecord(array $validated, ?int $externalProjectId): ?int
    {
        try {
            return DB::connection('facilities_db')->table('infrastructure_project_requests')->insertGetId([
                'external_project_id' => $externalProjectId,
                'requesting_office' => $validated['requesting_office'],
                'contact_person' => $validated['contact_person'],
                'project_title' => $validated['project_title'],
                'project_category' => $validated['project_category'],
                'problem_identified' => $validated['problem_identified'],
                'estimated_budget' => $validated['estimated_budget'] ?? null,
                'priority_level' => $validated['priority_level'] ?? 'medium',
                'status' => 'submitted',
                'submitted_by_user_id' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store local infrastructure project record', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get priority levels
     */
    private function getPriorityLevels(): array
    {
        try {
            $levels = DB::connection('facilities_db')
                ->table('priority_levels')
                ->where('is_active', true)
                ->orderBy('level_value')
                ->get();

            if ($levels->isEmpty()) {
                return $this->getDefaultPriorityLevels();
            }

            return $levels->map(function ($level) {
                return [
                    'value' => strtolower($level->level_name),
                    'label' => $level->level_name,
                    'color' => $level->color_code ?? 'gray',
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDefaultPriorityLevels();
        }
    }

    /**
     * Get default priority levels
     */
    private function getDefaultPriorityLevels(): array
    {
        return [
            ['value' => 'low', 'label' => 'Low', 'color' => 'green'],
            ['value' => 'medium', 'label' => 'Medium', 'color' => 'yellow'],
            ['value' => 'high', 'label' => 'High', 'color' => 'red'],
        ];
    }

    /**
     * Get project categories
     */
    private function getProjectCategories(): array
    {
        return [
            'Infrastructure',
            'Road Construction',
            'Building Construction',
            'Drainage System',
            'Water System',
            'Electrical System',
            'Parks and Recreation',
            'Public Facilities',
            'Rehabilitation',
            'Maintenance',
            'Other',
        ];
    }

    /**
     * Display list of submitted project requests
     */
    public function index()
    {
        try {
            $requests = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } catch (\Exception $e) {
            $requests = collect();
        }

        return view('admin.infrastructure.index', compact('requests'));
    }

    /**
     * Import a single project by ID from Infrastructure PM API
     */
    public function importProjects(Request $request)
    {
        $projectId = $request->input('project_id');
        
        if (!$projectId) {
            return back()->with('error', 'Please enter a Project ID to import.');
        }

        // Clean the project ID (remove PR- prefix if present)
        $projectId = preg_replace('/^PR-?0*/i', '', $projectId);
        
        try {
            // Check if already exists locally
            $exists = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->where('external_project_id', $projectId)
                ->exists();

            if ($exists) {
                return back()->with('info', 'This project is already imported. Use "Sync All Statuses" to update.');
            }

            // Fetch project details from Infrastructure PM
            $statusData = $this->fetchProjectStatus($projectId);
            
            if (!$statusData['success']) {
                return back()->with('error', 'Project not found in Infrastructure PM: ' . ($statusData['message'] ?? 'Unknown error'));
            }

            $project = $statusData['data'];
            
            // Use the actual ID from API response, not the PR number we searched with
            $actualProjectId = $project['id'] ?? $projectId;
            
            // Insert the project locally
            DB::connection('facilities_db')->table('infrastructure_project_requests')->insert([
                'external_project_id' => $actualProjectId,
                'requesting_office' => $project['requesting_office'] ?? $project['department'] ?? 'Unknown',
                'contact_person' => $project['contact_person'] ?? $project['requester'] ?? session('name'),
                'project_title' => $project['project_title'] ?? $project['title'] ?? 'Project #' . $projectId,
                'project_category' => $project['project_category'] ?? $project['category'] ?? 'Other',
                'problem_identified' => $project['problem_identified'] ?? $project['description'] ?? '',
                'estimated_budget' => $project['estimated_budget'] ?? $project['budget'] ?? null,
                'priority_level' => strtolower($project['priority_level'] ?? $project['priority'] ?? 'medium'),
                'status' => $this->mapApiStatus($project['status'] ?? $project['overall_status'] ?? 'submitted'),
                'submitted_by_user_id' => session('user_id'),
                'created_at' => $project['created_at'] ?? $project['date_submitted'] ?? now(),
                'updated_at' => now(),
            ]);

            $title = $project['project_title'] ?? $project['title'] ?? 'Project #' . $projectId;
            return back()->with('success', "Successfully imported: {$title}");

        } catch (\Exception $e) {
            Log::error('Failed to import project', ['error' => $e->getMessage(), 'project_id' => $projectId]);
            return back()->with('error', 'Failed to import project: ' . $e->getMessage());
        }
    }

    /**
     * Sync all project statuses from Infrastructure PM API
     */
    public function syncAllStatuses()
    {
        try {
            $projects = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->whereNotNull('external_project_id')
                ->get();

            $synced = 0;
            $failed = 0;
            $deleted = [];
            $errors = [];

            foreach ($projects as $project) {
                try {
                    $statusData = $this->fetchProjectStatus($project->external_project_id);
                    
                    Log::info('Infrastructure PM API response', [
                        'external_project_id' => $project->external_project_id,
                        'response' => $statusData,
                    ]);
                    
                    if ($statusData['success']) {
                        // Use status field (old API) - shows approved/rejected
                        $apiStatus = $statusData['data']['status'] 
                            ?? $statusData['data']['overall_status'] 
                            ?? $statusData['data']['project_status'] 
                            ?? null;
                        
                        Log::info('Sync status mapping', [
                            'project_id' => $project->external_project_id,
                            'status' => $statusData['data']['status'] ?? 'NOT SET',
                            'overall_status' => $statusData['data']['overall_status'] ?? 'NOT SET',
                            'project_status' => $statusData['data']['project_status'] ?? 'NOT SET',
                            'chosen' => $apiStatus,
                        ]);
                        
                        if ($apiStatus) {
                            $newStatus = $this->mapApiStatus($apiStatus);
                            
                            // Get bid status from API response
                            $bidStatus = $statusData['data']['bid_information']['bid_status'] ?? null;
                            
                            DB::connection('facilities_db')
                                ->table('infrastructure_project_requests')
                                ->where('external_project_id', $project->external_project_id)
                                ->update([
                                    'status' => $newStatus,
                                    'bid_status' => $bidStatus,
                                    'updated_at' => now(),
                                ]);
                            
                            $synced++;
                        } else {
                            $failed++;
                            $errors[] = "Project #{$project->external_project_id}: No status in API response";
                        }
                    } else {
                        // Check if project was deleted from Infrastructure PM
                        $message = $statusData['message'] ?? 'Unknown error';
                        if (stripos($message, 'not found') !== false) {
                            // Delete local record since it no longer exists in Infrastructure PM
                            DB::connection('facilities_db')
                                ->table('infrastructure_project_requests')
                                ->where('external_project_id', $project->external_project_id)
                                ->delete();
                            
                            $deleted[] = $project->external_project_id;
                        } else {
                            $failed++;
                            $errors[] = "Project #{$project->external_project_id}: " . $message;
                        }
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Project #{$project->external_project_id}: " . $e->getMessage();
                }
            }

            $message = "Synced {$synced} project(s).";
            if (count($deleted) > 0) {
                $message .= " Removed " . count($deleted) . " deleted project(s): #" . implode(', #', $deleted);
            }
            if ($failed > 0) {
                $message .= " {$failed} failed: " . implode('; ', array_slice($errors, 0, 3));
            }

            return back()->with(($synced > 0 || count($deleted) > 0) ? 'success' : 'error', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync statuses: ' . $e->getMessage());
        }
    }

    /**
     * Get project status from Infrastructure PM API
     */
    public function getStatus($projectId)
    {
        try {
            $statusData = $this->fetchProjectStatus($projectId);

            if ($statusData['success']) {
                // Update local record with latest status
                $this->updateLocalStatus($projectId, $statusData['data']);

                return response()->json($statusData);
            }

            return response()->json($statusData, 400);

        } catch (\Exception $e) {
            Log::error('Failed to fetch project status', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch project status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX endpoint to fetch all project statuses for live updates
     */
    public function getStatusesAjax()
    {
        try {
            $projects = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->whereNotNull('external_project_id')
                ->get();

            $statuses = [];

            foreach ($projects as $project) {
                try {
                    $statusData = $this->fetchProjectStatus($project->external_project_id);
                    
                    if ($statusData['success']) {
                        $apiStatus = $statusData['data']['status'] 
                            ?? $statusData['data']['overall_status'] 
                            ?? $statusData['data']['project_status'] 
                            ?? null;
                        
                        $bidStatus = $statusData['data']['bid_information']['bid_status'] ?? null;
                        
                        if ($apiStatus) {
                            $newStatus = $this->mapApiStatus($apiStatus);
                            
                            // Update local record
                            DB::connection('facilities_db')
                                ->table('infrastructure_project_requests')
                                ->where('id', $project->id)
                                ->update([
                                    'status' => $newStatus,
                                    'bid_status' => $bidStatus,
                                    'updated_at' => now(),
                                ]);
                            
                            $statuses[$project->id] = [
                                'status' => $newStatus,
                                'bid_status' => $bidStatus,
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    // Skip failed individual requests
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'statuses' => $statuses,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh status for a project and return updated view
     */
    public function refreshStatus($projectId)
    {
        try {
            $statusData = $this->fetchProjectStatus($projectId);

            if ($statusData['success']) {
                $this->updateLocalStatus($projectId, $statusData['data']);

                return back()->with('success', 'Project status updated successfully.');
            }

            return back()->with('error', $statusData['message'] ?? 'Failed to refresh status.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to refresh status: ' . $e->getMessage());
        }
    }

    /**
     * Show detailed project status
     */
    public function show($id)
    {
        try {
            // Get local record
            $project = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->where('id', $id)
                ->first();

            if (!$project) {
                return redirect()
                    ->route('admin.infrastructure.projects.index')
                    ->with('error', 'Project not found.');
            }

            // Fetch latest status from API if we have an external ID
            $apiStatus = null;
            if ($project->external_project_id) {
                $statusResponse = $this->fetchProjectStatus($project->external_project_id);
                if ($statusResponse['success']) {
                    $apiStatus = $statusResponse['data'];
                    $this->updateLocalStatus($project->external_project_id, $apiStatus);
                }
            }

            return view('admin.infrastructure.show', compact('project', 'apiStatus'));

        } catch (\Exception $e) {
            Log::error('Failed to show project details', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.infrastructure.projects.index')
                ->with('error', 'Failed to load project details.');
        }
    }

    /**
     * Fetch project status from Infrastructure PM API
     */
    private function fetchProjectStatus($projectId): array
    {
        $baseUrl = config('services.infrastructure_pm.base_url');
        $timeout = config('services.infrastructure_pm.timeout', 30);
        $apiKey = config('services.infrastructure_pm.api_key');

        $url = rtrim($baseUrl, '/') . '/api/integrations/ProjectRequestStatus.php';

        $headers = [
            'Accept' => 'application/json',
        ];

        if ($apiKey) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $response = Http::timeout($timeout)
            ->withHeaders($headers)
            ->get($url, ['project_id' => $projectId]);

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
     * Update local record with status from API
     * Only updates if API provides a valid status - never downgrades
     */
    private function updateLocalStatus($externalProjectId, array $statusData): void
    {
        try {
            // Get the API status - use status field (old API) first
            $apiStatus = $statusData['status'] ?? $statusData['overall_status'] ?? $statusData['project_status'] ?? null;
            
            // Don't update if no valid status returned
            if (!$apiStatus) {
                return;
            }

            $newStatus = $this->mapApiStatus($apiStatus);
            
            // Get current local status
            $currentRecord = DB::connection('facilities_db')
                ->table('infrastructure_project_requests')
                ->where('external_project_id', $externalProjectId)
                ->first();

            if (!$currentRecord) {
                return;
            }

            // Define status priority (higher = more progress)
            $statusPriority = [
                'submitted' => 1,
                'received' => 2,
                'under_review' => 3,
                'approved' => 4,
                'in_progress' => 5,
                'completed' => 6,
                'rejected' => 0,
            ];

            $currentPriority = $statusPriority[$currentRecord->status] ?? 0;
            $newPriority = $statusPriority[$newStatus] ?? 0;

            // Only update if new status is same or higher priority (don't downgrade)
            // Exception: rejected can override anything
            if ($newStatus === 'rejected' || $newPriority >= $currentPriority) {
                DB::connection('facilities_db')
                    ->table('infrastructure_project_requests')
                    ->where('external_project_id', $externalProjectId)
                    ->update([
                        'status' => $newStatus,
                        'updated_at' => now(),
                    ]);
            }

        } catch (\Exception $e) {
            Log::warning('Failed to update local project status', [
                'external_project_id' => $externalProjectId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Map API status to local status enum
     */
    private function mapApiStatus(string $apiStatus): string
    {
        $statusMap = [
            'pending' => 'submitted',
            'pending_review' => 'under_review',
            'pending_pm_assignment' => 'received',
            'pm_assigned' => 'under_review',
            'received' => 'received',
            'under_review' => 'under_review',
            'engineer_review' => 'under_review',
            'treasurer_review' => 'under_review',
            'for_review' => 'under_review',
            'approved' => 'approved',
            'approved_for_bidding' => 'approved',
            'rejected' => 'rejected',
            'contractor_assigned' => 'in_progress',
            'project_started' => 'in_progress',
            'in_progress' => 'in_progress',
            'under_construction' => 'in_progress',
            'for_implementation' => 'in_progress',
            'documents_prepared' => 'approved',
            'project_completed' => 'completed',
            'completed' => 'completed',
            'done' => 'completed',
            'finished' => 'completed',
        ];

        return $statusMap[strtolower($apiStatus)] ?? $apiStatus;
    }
}
