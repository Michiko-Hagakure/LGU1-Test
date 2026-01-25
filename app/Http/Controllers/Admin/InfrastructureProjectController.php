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
            'contact_person' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'project_title' => 'required|string|max:500',
            'project_category' => 'required|string|max:255',
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
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'nullable|image|max:5120',
            'map_image' => 'nullable|image|max:5120',
            'resolution_file' => 'nullable|file|mimes:pdf|max:10240',
            'other_files' => 'nullable|array|max:3',
            'other_files.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            // Prepare the request payload
            $payload = $this->preparePayload($validated, $request);

            // Send request to Infrastructure PM API
            $response = $this->sendToInfrastructurePM($payload);

            if ($response['success']) {
                // Log successful submission
                Log::info('Infrastructure project request submitted successfully', [
                    'project_id' => $response['data']['project_id'] ?? null,
                    'user_id' => session('user_id'),
                ]);

                // Store local record for tracking
                $this->storeLocalRecord($validated, $response['data']['project_id'] ?? null);

                return redirect()
                    ->route('admin.infrastructure.project-request')
                    ->with('success', 'Project request submitted successfully! Project ID: ' . ($response['data']['project_id'] ?? 'Pending'));
            }

            return back()
                ->withInput()
                ->with('error', $response['message'] ?? 'Failed to submit project request. Please try again.');

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
     */
    private function storeLocalRecord(array $validated, ?int $externalProjectId): void
    {
        try {
            DB::connection('facilities_db')->table('infrastructure_project_requests')->insert([
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
            // Log but don't fail the request if local storage fails
            Log::warning('Failed to store local infrastructure project record', [
                'error' => $e->getMessage(),
            ]);
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
}
