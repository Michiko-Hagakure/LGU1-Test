<?php
/**
 * Dashboard Validation API
 * Validates user access and role-based permissions for microservice dashboards
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/db-connection.php';

/**
 * Validate user authentication and permissions
 */
function validateUserAccess($pdo, $user_id, $token, $required_subsystem = null, $required_role = null) {
    try {
        // Verify user exists and is active
        $stmt = $pdo->prepare('
            SELECT u.id, u.username, u.full_name, u.email, u.status, u.is_email_verified,
                   GROUP_CONCAT(DISTINCT r.name) as roles,
                   GROUP_CONCAT(DISTINCT s.name) as subsystems,
                   GROUP_CONCAT(DISTINCT p.name) as permissions
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id
            LEFT JOIN user_subsystems us ON u.id = us.user_id
            LEFT JOIN subsystems s ON us.subsystem_id = s.id
            LEFT JOIN role_permissions rp ON r.id = rp.role_id
            LEFT JOIN permissions p ON rp.permission_id = p.id
            WHERE u.id = ? AND u.status = "active" AND u.is_email_verified = 1
            GROUP BY u.id
        ');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return [
                'success' => false,
                'error' => 'User not found or inactive',
                'code' => 'USER_NOT_FOUND'
            ];
        }
        
        // Verify token (simple base64 username check for now)
        $expected_token = base64_encode($user['username']);
        if ($token !== $expected_token) {
            return [
                'success' => false,
                'error' => 'Invalid authentication token',
                'code' => 'INVALID_TOKEN'
            ];
        }
        
        $user_roles = array_filter(explode(',', $user['roles'] ?? ''));
        $user_subsystems = array_filter(explode(',', $user['subsystems'] ?? ''));
        $user_permissions = array_filter(explode(',', $user['permissions'] ?? ''));
        
        // Check if user has required subsystem access
        if ($required_subsystem && !in_array($required_subsystem, $user_subsystems) && !in_array('super admin', $user_roles)) {
            return [
                'success' => false,
                'error' => 'Access denied to subsystem: ' . $required_subsystem,
                'code' => 'SUBSYSTEM_ACCESS_DENIED'
            ];
        }
        
        // Check if user has required role
        if ($required_role && !in_array($required_role, $user_roles) && !in_array('super admin', $user_roles)) {
            return [
                'success' => false,
                'error' => 'Insufficient role permissions. Required: ' . $required_role,
                'code' => 'ROLE_ACCESS_DENIED'
            ];
        }
        
        // Log successful access
        $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $user_id,
            'dashboard_access_granted',
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'roles' => $user_roles,
                'subsystems' => $user_subsystems,
                'permissions' => $user_permissions
            ]
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage(),
            'code' => 'DATABASE_ERROR'
        ];
    }
}

/**
 * Get user dashboard configuration based on roles
 */
function getUserDashboardConfig($roles, $subsystems) {
    $dashboard_config = [
        'modules' => [],
        'navigation' => [],
        'widgets' => []
    ];
    
    // Super admin gets access to everything
    if (in_array('super admin', $roles)) {
        $dashboard_config['modules'] = [
            'user_management',
            'system_administration',
            'all_subsystems'
        ];
        $dashboard_config['navigation'] = [
            ['label' => 'User Management', 'url' => '/admin/users'],
            ['label' => 'System Settings', 'url' => '/admin/settings'],
            ['label' => 'Audit Logs', 'url' => '/admin/logs'],
            ['label' => 'All Subsystems', 'url' => '/admin/subsystems']
        ];
    }
    
    // Housing subsystem roles
    if (in_array('housing admin', $roles) || in_array('eligibility officer', $roles) || in_array('resettlement coordinator', $roles)) {
        $dashboard_config['modules'][] = 'housing_management';
        $dashboard_config['navigation'][] = ['label' => 'Housing Dashboard', 'url' => '/housing/dashboard'];
        
        if (in_array('housing admin', $roles)) {
            $dashboard_config['modules'][] = 'housing_reports';
            $dashboard_config['navigation'][] = ['label' => 'Housing Reports', 'url' => '/housing/reports'];
        }
        
        if (in_array('eligibility officer', $roles)) {
            $dashboard_config['modules'][] = 'beneficiary_verification';
            $dashboard_config['navigation'][] = ['label' => 'Beneficiary Verification', 'url' => '/housing/verification'];
        }
        
        if (in_array('resettlement coordinator', $roles)) {
            $dashboard_config['modules'][] = 'resettlement_planning';
            $dashboard_config['navigation'][] = ['label' => 'Resettlement Planning', 'url' => '/housing/resettlement'];
        }
    }
    
    // Finance officer
    if (in_array('finance officer', $roles)) {
        $dashboard_config['modules'][] = 'financial_management';
        $dashboard_config['navigation'][] = ['label' => 'Financial Dashboard', 'url' => '/finance/dashboard'];
    }
    
    // Clerk
    if (in_array('clerk', $roles)) {
        $dashboard_config['modules'][] = 'document_processing';
        $dashboard_config['navigation'][] = ['label' => 'Document Processing', 'url' => '/clerk/documents'];
    }
    
    // Citizen
    if (in_array('citizen', $roles)) {
        $dashboard_config['modules'][] = 'citizen_services';
        $dashboard_config['navigation'] = [
            ['label' => 'My Profile', 'url' => '/citizen/profile'],
            ['label' => 'Services', 'url' => '/citizen/services'],
            ['label' => 'Applications', 'url' => '/citizen/applications']
        ];
    }
    
    return $dashboard_config;
}

// Main API endpoint
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' || $method === 'POST') {
    $user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
    $token = $_GET['token'] ?? $_POST['token'] ?? null;
    $subsystem = $_GET['subsystem'] ?? $_POST['subsystem'] ?? null;
    $required_role = $_GET['required_role'] ?? $_POST['required_role'] ?? null;
    
    if (!$user_id || !$token) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required parameters: user_id and token',
            'code' => 'MISSING_PARAMETERS'
        ]);
        exit;
    }
    
    $validation_result = validateUserAccess($pdo, $user_id, $token, $subsystem, $required_role);
    
    if ($validation_result['success']) {
        $user = $validation_result['user'];
        $dashboard_config = getUserDashboardConfig($user['roles'], $user['subsystems']);
        
        echo json_encode([
            'success' => true,
            'user' => $user,
            'dashboard_config' => $dashboard_config,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        // Log failed access attempt
        $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $user_id,
            'dashboard_access_denied: ' . ($validation_result['code'] ?? 'unknown'),
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        http_response_code(403);
        echo json_encode($validation_result);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed',
        'code' => 'METHOD_NOT_ALLOWED'
    ]);
}
?>
