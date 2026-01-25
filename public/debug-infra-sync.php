<?php
/**
 * Debug tool for Infrastructure PM API Sync
 * Access via: /debug-infra-sync.php?project_id=3
 * DELETE THIS FILE AFTER DEBUGGING
 */

header('Content-Type: application/json');

$projectId = $_GET['project_id'] ?? 3;

// Infrastructure PM API URL
$apiUrl = "https://infra-pm.local-government-unit-1-ph.com/api/integrations/ProjectRequestStatus.php?project_id=" . $projectId;

echo json_encode([
    'step_1_request' => [
        'url' => $apiUrl,
        'project_id' => $projectId,
    ],
], JSON_PRETTY_PRINT);

// Make API call
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

$apiData = json_decode($response, true);

// Database check
try {
    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbName = getenv('FACILITIES_DB_DATABASE') ?: 'lgu1_facilities';
    $dbUser = getenv('DB_USERNAME') ?: 'root';
    $dbPass = getenv('DB_PASSWORD') ?: '';
    
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT id, external_project_id, project_title, status FROM infrastructure_project_requests WHERE external_project_id = ?");
    $stmt->execute([$projectId]);
    $localRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $dbStatus = 'connected';
    $dbError = null;
} catch (Exception $e) {
    $localRecord = null;
    $dbStatus = 'error';
    $dbError = $e->getMessage();
}

// Output full debug info
$output = [
    'debug_info' => [
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
    ],
    'step_1_api_request' => [
        'url' => $apiUrl,
        'project_id' => $projectId,
    ],
    'step_2_api_response' => [
        'http_code' => $httpCode,
        'curl_error' => $curlError ?: null,
        'raw_response' => $response,
        'parsed_response' => $apiData,
    ],
    'step_3_status_extraction' => [
        'overall_status' => $apiData['data']['overall_status'] ?? 'NOT FOUND',
        'status' => $apiData['data']['status'] ?? 'NOT FOUND',
        'engineer_status' => $apiData['data']['engineer_status'] ?? 'NOT FOUND',
        'treasurer_status' => $apiData['data']['treasurer_status'] ?? 'NOT FOUND',
    ],
    'step_4_database' => [
        'connection_status' => $dbStatus,
        'connection_error' => $dbError,
        'local_record' => $localRecord,
    ],
    'step_5_what_should_happen' => [
        'api_overall_status' => $apiData['data']['overall_status'] ?? 'unknown',
        'should_map_to' => mapStatus($apiData['data']['overall_status'] ?? $apiData['data']['status'] ?? 'submitted'),
        'current_local_status' => $localRecord['status'] ?? 'no record found',
    ],
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

function mapStatus($apiStatus) {
    $statusMap = [
        'pending' => 'submitted',
        'received' => 'received',
        'under_review' => 'under_review',
        'engineer_review' => 'under_review',
        'treasurer_review' => 'under_review',
        'for_review' => 'under_review',
        'approved' => 'approved',
        'rejected' => 'rejected',
        'in_progress' => 'in_progress',
        'completed' => 'completed',
    ];
    
    return $statusMap[strtolower($apiStatus)] ?? $apiStatus;
}
