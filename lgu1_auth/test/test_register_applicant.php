<?php
require_once __DIR__ . '/../config/config.php';

echo "<h2>Test Applicant Registration</h2>";

// Simulate form data for applicant registration
$test_data = [
    'registration_type' => 'applicant',
    'username' => 'test_applicant_' . time(),
    'email' => 'test_applicant_' . time() . '@test.com',
    'full_name' => 'Test Applicant User',
    'password' => 'password123',
    'confirm_password' => 'password123',
    'birthdate' => '1990-01-01',
    'mobile_number' => '09123456789',
    'gender' => 'male',
    'civil_status' => 'single',
    'nationality' => 'Filipino',
    'district_id' => '1',
    'barangay_id' => '1',
    'current_address' => 'Test Address',
    'zip_code' => '1100',
    'valid_id_type' => 'National ID (PhilSys)'
];

echo "<h3>Test Data:</h3>";
foreach ($test_data as $key => $value) {
    echo "$key: $value<br>";
}

// Test role assignment logic
$registration_type = $test_data['registration_type'];
$role_id = ($registration_type === 'applicant') ? 8 : 7;

echo "<h3>Role Assignment Test:</h3>";
echo "Registration Type: $registration_type<br>";
echo "Assigned Role ID: $role_id<br>";

// Check what role name this corresponds to
$stmt = $conn->prepare('SELECT name FROM roles WHERE id = ?');
$stmt->execute([$role_id]);
$role = $stmt->fetch();
echo "Role Name: " . ($role ? $role['name'] : 'Not found') . "<br>";

// Test if username/email already exists
$stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
$stmt->execute([$test_data['username'], $test_data['email']]);
$existing = $stmt->fetch();
echo "<h3>Duplicate Check:</h3>";
echo "Username/Email exists: " . ($existing ? 'YES (ID: ' . $existing['id'] . ')' : 'NO') . "<br>";

echo "<h3>Registration Form Test:</h3>";
echo '<form method="post" action="../public/register.php">';
echo '<input type="hidden" name="registration_type" value="applicant">';
echo '<input type="text" name="username" value="' . $test_data['username'] . '" placeholder="Username"><br><br>';
echo '<input type="email" name="email" value="' . $test_data['email'] . '" placeholder="Email"><br><br>';
echo '<input type="text" name="full_name" value="' . $test_data['full_name'] . '" placeholder="Full Name"><br><br>';
echo '<input type="password" name="password" value="' . $test_data['password'] . '" placeholder="Password"><br><br>';
echo '<input type="password" name="confirm_password" value="' . $test_data['confirm_password'] . '" placeholder="Confirm Password"><br><br>';
echo '<input type="date" name="birthdate" value="' . $test_data['birthdate'] . '"><br><br>';
echo '<input type="text" name="mobile_number" value="' . $test_data['mobile_number'] . '" placeholder="Mobile"><br><br>';
echo '<select name="gender"><option value="male" selected>Male</option></select><br><br>';
echo '<select name="civil_status"><option value="single" selected>Single</option></select><br><br>';
echo '<input type="text" name="nationality" value="' . $test_data['nationality'] . '" placeholder="Nationality"><br><br>';
echo '<select name="district_id"><option value="1" selected>District 1</option></select><br><br>';
echo '<select name="barangay_id"><option value="1" selected>Barangay 1</option></select><br><br>';
echo '<textarea name="current_address">' . $test_data['current_address'] . '</textarea><br><br>';
echo '<input type="text" name="zip_code" value="' . $test_data['zip_code'] . '" placeholder="Zip Code"><br><br>';
echo '<select name="valid_id_type"><option value="National ID (PhilSys)" selected>National ID</option></select><br><br>';
echo '<button type="submit">Test Register as Applicant</button>';
echo '</form>';
?>