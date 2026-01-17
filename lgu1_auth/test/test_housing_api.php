<?php
require_once '../config/config.php';

// Initialize database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "<h2>LGU1-LOGIN to Housing API Test</h2>";

// Test with actual user data from LGU1-LOGIN database
echo "<h3>1. Getting Test User Data</h3>";

try {
    $stmt = $pdo->prepare("
        SELECT u.*, d.name as district_name, b.name as barangay_name 
        FROM users u 
        LEFT JOIN districts d ON u.district_id = d.id 
        LEFT JOIN barangays b ON u.barangay_id = b.id 
        WHERE u.status = 'active' AND u.is_email_verified = 1
        LIMIT 1
    ");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<span style='color: green;'>✅ Test user found: {$user['full_name']}</span><br>";
        echo "<strong>User Details:</strong><br>";
        echo "- ID: {$user['id']}<br>";
        echo "- Username: {$user['username']}<br>";
        echo "- Email: {$user['email']}<br>";
        echo "- Mobile: {$user['mobile_number']}<br>";
        echo "- Address: {$user['current_address']}<br>";
        echo "- ID Status: {$user['id_verification_status']}<br>";
        
        // Check for uploaded documents
        $uploadDir = '../uploads/id_images/';
        $documents = [];
        
        if ($user['valid_id_front_image'] && file_exists($uploadDir . $user['valid_id_front_image'])) {
            $documents[] = 'ID Front: ' . $user['valid_id_front_image'];
        }
        if ($user['valid_id_back_image'] && file_exists($uploadDir . $user['valid_id_back_image'])) {
            $documents[] = 'ID Back: ' . $user['valid_id_back_image'];
        }
        if ($user['selfie_with_id_image'] && file_exists($uploadDir . $user['selfie_with_id_image'])) {
            $documents[] = 'Selfie: ' . $user['selfie_with_id_image'];
        }
        
        if ($documents) {
            echo "<strong>Available Documents:</strong><br>";
            foreach ($documents as $doc) {
                echo "- $doc<br>";
            }
        } else {
            echo "<span style='color: orange;'>⚠️ No documents found for this user</span><br>";
        }
        
    } else {
        echo "<span style='color: red;'>❌ No active verified users found</span><br>";
        exit;
    }
    
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Database error: " . $e->getMessage() . "</span><br>";
    exit;
}

// Test 2: Send data to housing system
echo "<h3>2. Sending Data to Housing System</h3>";

try {
    // Prepare citizen data
    $citizenData = [
        'username' => $user['username'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'birthdate' => $user['birthdate'],
        'mobile_number' => $user['mobile_number'],
        'gender' => $user['gender'],
        'civil_status' => $user['civil_status'],
        'current_address' => $user['current_address'],
        'district_id' => $user['district_id'],
        'barangay_id' => $user['barangay_id'],
        'valid_id_type' => $user['valid_id_type'],
        'id_verification_status' => $user['id_verification_status']
    ];
    
    // Add documents if available
    $documents = [];
    if ($user['valid_id_front_image'] && file_exists($uploadDir . $user['valid_id_front_image'])) {
        $documents[] = [
            'type' => 'valid_id_front',
            'original_filename' => $user['valid_id_front_image'],
            'image_data' => base64_encode(file_get_contents($uploadDir . $user['valid_id_front_image']))
        ];
    }
    
    if ($user['valid_id_back_image'] && file_exists($uploadDir . $user['valid_id_back_image'])) {
        $documents[] = [
            'type' => 'valid_id_back',
            'original_filename' => $user['valid_id_back_image'],
            'image_data' => base64_encode(file_get_contents($uploadDir . $user['valid_id_back_image']))
        ];
    }
    
    if ($user['selfie_with_id_image'] && file_exists($uploadDir . $user['selfie_with_id_image'])) {
        $documents[] = [
            'type' => 'selfie_with_id',
            'original_filename' => $user['selfie_with_id_image'],
            'image_data' => base64_encode(file_get_contents($uploadDir . $user['selfie_with_id_image']))
        ];
    }
    
    $citizenData['documents'] = $documents;
    
    echo "<strong>Sending " . count($documents) . " documents...</strong><br>";
    
    // Send to housing system
    $housingApiUrl = 'http://localhost/LGU1-HousingAndResettlementManagement/api/receive_citizen_data.php';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $housingApiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($citizenData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    // Add API key to data
    $citizenData['api_key'] = 'LGU1-HOUSING-API-KEY-2025';
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<span style='color: red;'>❌ cURL Error: $error</span><br>";
    } else {
        echo "<strong>HTTP Code:</strong> $httpCode<br>";
        echo "<strong>Response:</strong><br>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if ($result && $result['success']) {
                echo "<span style='color: green;'>✅ Data sent successfully!</span><br>";
                echo "<strong>Beneficiary ID:</strong> {$result['beneficiary_id']}<br>";
                echo "<strong>Documents Processed:</strong> " . count($result['documents_processed']) . "<br>";
                
                foreach ($result['documents_processed'] as $doc) {
                    echo "- {$doc['type']}: {$doc['status']}<br>";
                }
            } else {
                echo "<span style='color: red;'>❌ API Error: " . ($result['error'] ?? 'Unknown') . "</span><br>";
            }
        } else {
            echo "<span style='color: red;'>❌ HTTP Error: $httpCode</span><br>";
        }
    }
    
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Exception: " . $e->getMessage() . "</span><br>";
}

echo "<h3>Test Complete</h3>";
?>