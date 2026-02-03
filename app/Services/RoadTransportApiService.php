<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadTransportApiService
{
    protected string $apiUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.road_transport.api_url');
        $this->timeout = config('services.road_transport.timeout', 30);
    }

    /**
     * Create a road assistance request to the Road & Transportation system
     */
    public function createRequest(array $data): array
    {
        try {
            $payload = [
                'external_system' => 'PFRS',
                'external_user_id' => $data['user_id'],
                'system_name' => 'Public Facility Reservation System',
                'event_type' => $data['event_type'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'location' => $data['location'],
                'landmark' => $data['landmark'] ?? null,
                'description' => $data['description'],
            ];

            $response = Http::timeout($this->timeout)
                ->asJson()
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Road assistance request sent successfully', [
                    'request_id' => $result['request_id'] ?? null,
                    'user_id' => $data['user_id']
                ]);
                return [
                    'success' => true,
                    'request_id' => $result['request_id'] ?? null,
                    'message' => $result['message'] ?? 'Request submitted successfully'
                ];
            }

            Log::error('Road assistance request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to submit request to Road & Transportation system'
            ];

        } catch (\Exception $e) {
            Log::error('Road assistance API exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all requests for a user from the Road & Transportation system
     */
    public function getRequestsByUser(int $userId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl, ['user_id' => $userId]);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => true,
                    'data' => $result['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to fetch requests',
                'data' => []
            ];

        } catch (\Exception $e) {
            Log::error('Road assistance API get exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Connection error',
                'data' => []
            ];
        }
    }

    /**
     * Get a specific request by ID
     */
    public function getRequestById(int $requestId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl, ['id' => $requestId]);

            if ($response->successful()) {
                $result = $response->json();
                $data = $result['data'] ?? [];
                return [
                    'success' => true,
                    'data' => is_array($data) && count($data) > 0 ? $data[0] : null
                ];
            }

            return [
                'success' => false,
                'error' => 'Request not found'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error'
            ];
        }
    }
}
