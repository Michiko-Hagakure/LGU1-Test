<?php
/**
 * Housing & Resettlement API Test Script
 * 
 * Access: https://facilities.local-government-unit-1-ph.com/test-housing-api.php
 * 
 * WARNING: Delete this file after testing!
 */

// Security key - change this or remove after testing
$securityKey = 'test-hr-2026';

if (!isset($_GET['key']) || $_GET['key'] !== $securityKey) {
    die('Access denied. Add ?key=' . $securityKey . ' to the URL');
}

$baseUrl = 'https://facilities.local-government-unit-1-ph.com/api/housing-resettlement';

// First, get list of available facilities
function getFacilities($baseUrl) {
    $ch = curl_init($baseUrl . '/facilities');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$facilitiesResponse = getFacilities($baseUrl);
$facilities = $facilitiesResponse['data']['facilities'] ?? [];
$firstFacility = $facilities[0] ?? null;
$facilityId = $firstFacility['facility_id'] ?? 1;

// Test data with valid facility_id
$testData = [
    'facility_id' => $facilityId,
    'event_name' => 'Beneficiary Orientation - Test Batch',
    'event_description' => 'Test request from Housing and Resettlement Management',
    'requested_date' => date('Y-m-d', strtotime('+14 days')),
    'start_time' => '09:00',
    'end_time' => '12:00',
    'expected_attendees' => 50,
    'contact_person' => 'Test HR Admin',
    'contact_email' => 'hradmin@housing.gov.ph',
    'contact_phone' => '09171234567',
    'department' => 'Housing and Resettlement Management',
    'special_requests' => 'Projector and sound system needed'
];

echo "<html><head><title>Housing API Test</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{background:#d1fae5;border:1px solid #6ee7b7;padding:20px;border-radius:10px;margin:20px 0;}";
echo ".error{background:#fee2e2;border:1px solid #fca5a5;padding:20px;border-radius:10px;margin:20px 0;}";
echo ".info{background:#dbeafe;border:1px solid #93c5fd;padding:20px;border-radius:10px;margin:20px 0;}";
echo "pre{background:#1f2937;color:#f9fafb;padding:15px;border-radius:8px;overflow-x:auto;}";
echo "h1{color:#0d9488;}button{background:#0d9488;color:white;border:none;padding:15px 30px;font-size:16px;border-radius:8px;cursor:pointer;}";
echo "button:hover{background:#0f766e;}</style></head><body>";

echo "<h1>🏠 Housing & Resettlement API Test</h1>";

// Show facilities debug info
echo "<div class='info'><strong>Step 1: Fetching Available Facilities...</strong><br>";
echo "API: " . $baseUrl . "/facilities<br>";
echo "<strong>Raw API Response:</strong><pre>" . json_encode($facilitiesResponse, JSON_PRETTY_PRINT) . "</pre>";
echo "<strong>Facilities Found:</strong> " . count($facilities) . "<br>";
if ($firstFacility) {
    echo "<strong>Using Facility:</strong> ID " . $facilityId . " - " . ($firstFacility['name'] ?? 'Unknown') . "</div>";
} else {
    echo "<span style='color:red;'><strong>⚠️ No facilities found! Check if facilities have is_available = true</strong></span></div>";
}

if (isset($_POST['submit'])) {
    $apiUrl = $baseUrl . '/request';
    echo "<div class='info'><strong>Sending POST request to:</strong><br>" . $apiUrl . "</div>";
    echo "<div class='info'><strong>Request Data:</strong><pre>" . json_encode($testData, JSON_PRETTY_PRINT) . "</pre></div>";
    
    // Make the API call
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<div class='error'><strong>cURL Error:</strong> " . htmlspecialchars($error) . "</div>";
    } else {
        $responseData = json_decode($response, true);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            echo "<div class='success'>";
            echo "<h2>✅ SUCCESS! (HTTP $httpCode)</h2>";
            echo "<pre>" . json_encode($responseData, JSON_PRETTY_PRINT) . "</pre>";
            echo "<p><strong>Next:</strong> Check the <a href='/admin/housing-resettlement' target='_blank'>Housing & Resettlement Admin Page</a> to see the request!</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h2>❌ Error (HTTP $httpCode)</h2>";
            echo "<pre>" . json_encode($responseData, JSON_PRETTY_PRINT) . "</pre>";
            echo "</div>";
        }
    }
    
    echo "<br><a href='?key=$securityKey'><button>← Back</button></a>";
} else {
    echo "<div class='info'>";
    echo "<h3>Test Data Preview:</h3>";
    echo "<pre>" . json_encode($testData, JSON_PRETTY_PRINT) . "</pre>";
    echo "</div>";
    
    echo "<form method='POST'>";
    echo "<button type='submit' name='submit' value='1'>🚀 Send Test Request</button>";
    echo "</form>";
    
    echo "<br><br>";
    echo "<div class='error'><strong>⚠️ IMPORTANT:</strong> Delete this file after testing!</div>";
}

echo "</body></html>";
