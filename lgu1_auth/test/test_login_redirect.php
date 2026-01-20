<?php
require_once __DIR__ . '/../config/config.php';

echo "<h2>Login Redirect Test</h2>";

// Test login redirection logic
function testRedirect($roles) {
    if (in_array('super admin', $roles)) {
        return 'http://localhost/LGU1-SuperAdmin/main/dashboard.php';
    } elseif (in_array('Admin', $roles)) {
        return 'http://localhost/LGU1-HousingAndResettlementManagement/public/dashboard.php';
    } elseif (in_array('applicant', $roles)) {
        return 'http://localhost/LGU1-Applicant-HousingAndResettlement/public/dashboard.php';
    } elseif (in_array('Census & Planning Staff', $roles) || in_array('MIS Officer', $roles) || 
             in_array('Administrative & Records Staff', $roles) || in_array('Housing & Resettlement Staff', $roles) || in_array('Support Services Staff', $roles)) {
        return 'http://localhost/LGU1-HousingAndResettlementManagement/public/dashboard.php';
    } else {
        return 'citizen_portal.php';
    }
}

// Test different role combinations
$testCases = [
    'Citizen' => ['citizen'],
    'Housing Applicant' => ['applicant'],
    'Admin' => ['Admin'],
    'Super Admin' => ['super admin']
];

echo "<h3>Redirect Test Results:</h3>";
foreach ($testCases as $description => $roles) {
    $redirect = testRedirect($roles);
    echo "$description: $redirect<br>";
}

// Check actual users and their roles
echo "<h3>Actual User Roles in Database:</h3>";
$stmt = $conn->query('
    SELECT u.username, GROUP_CONCAT(r.name) as roles
    FROM users u
    LEFT JOIN user_roles ur ON u.id = ur.user_id  
    LEFT JOIN roles r ON ur.role_id = r.id
    WHERE u.status = "active"
    GROUP BY u.id
    ORDER BY u.created_at DESC
    LIMIT 10
');

while ($user = $stmt->fetch()) {
    $userRoles = explode(',', $user['roles'] ?? '');
    $redirect = testRedirect($userRoles);
    echo "User: " . $user['username'] . " | Roles: " . $user['roles'] . " | Redirect: $redirect<br>";
}
?>