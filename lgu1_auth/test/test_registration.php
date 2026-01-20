<?php
require_once __DIR__ . '/../config/config.php';

echo "<h2>Registration Role Test</h2>";

// Test 1: Check what role ID 8 corresponds to
echo "<h3>Test 1: Role ID 8 Details</h3>";
$stmt = $conn->prepare('SELECT * FROM roles WHERE id = 8');
$stmt->execute();
$role8 = $stmt->fetch();
if ($role8) {
    echo "Role ID 8: " . $role8['name'] . "<br>";
} else {
    echo "Role ID 8 not found<br>";
}

// Test 2: Check what role ID 7 corresponds to
echo "<h3>Test 2: Role ID 7 Details</h3>";
$stmt = $conn->prepare('SELECT * FROM roles WHERE id = 7');
$stmt->execute();
$role7 = $stmt->fetch();
if ($role7) {
    echo "Role ID 7: " . $role7['name'] . "<br>";
} else {
    echo "Role ID 7 not found<br>";
}

// Test 3: Show all roles
echo "<h3>Test 3: All Available Roles</h3>";
$stmt = $conn->query('SELECT * FROM roles ORDER BY id');
while ($role = $stmt->fetch()) {
    echo "ID: " . $role['id'] . " - Name: " . $role['name'] . "<br>";
}

// Test 4: Simulate registration type selection
echo "<h3>Test 4: Registration Type Logic</h3>";
$test_types = ['citizen', 'applicant'];
foreach ($test_types as $type) {
    $role_id = ($type === 'applicant') ? 8 : 7;
    echo "Registration Type: $type → Role ID: $role_id<br>";
}

// Test 5: Check recent user registrations
echo "<h3>Test 5: Recent User Registrations</h3>";
$stmt = $conn->query('
    SELECT u.username, u.email, r.name as role_name, ur.role_id, u.created_at 
    FROM users u 
    LEFT JOIN user_roles ur ON u.id = ur.user_id 
    LEFT JOIN roles r ON ur.role_id = r.id 
    ORDER BY u.created_at DESC 
    LIMIT 5
');
while ($user = $stmt->fetch()) {
    echo "User: " . $user['username'] . " | Role: " . $user['role_name'] . " (ID: " . $user['role_id'] . ") | Created: " . $user['created_at'] . "<br>";
}
?>