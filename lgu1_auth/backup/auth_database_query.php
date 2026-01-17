<?php
/**
 * Auth Database Query API
 * Specialized API for querying the main authentication database
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Error handling for missing database connection
try {
    require_once __DIR__ . '/../config/db-connection.php';
    require_once __DIR__ . '/microservice_auth.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database configuration error: ' . $e->getMessage(),
        'code' => 'CONFIG_ERROR'
    ]);
    exit;
}

// Check if database connection is available
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Main authentication database connection not available. Please ensure lgu1_auth_db is set up correctly.',
        'code' => 'NO_AUTH_DATABASE_CONNECTION'
    ]);
    exit;
}

/**
 * Validate super admin access
 */
function validateSuperAdmin($pdo, $user_id, $token) {
    $auth = new LGU1Auth();
    $validation = $auth->validateAccess($user_id, $token, null, 'super admin');
    
    if (!$validation['success']) {
        return [
            'success' => false,
            'error' => 'Super admin access required',
            'code' => 'SUPER_ADMIN_REQUIRED'
        ];
    }
    
    return ['success' => true, 'user' => $validation['user']];
}

/**
 * Execute query on main auth database
 */
function executeAuthQuery($pdo, $query, $params = []) {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        // Trim whitespace and check if query starts with allowed commands
        $trimmed_query = trim($query);
        if (stripos($trimmed_query, 'SELECT') === 0 || stripos($trimmed_query, 'SHOW') === 0 || stripos($trimmed_query, 'DESCRIBE') === 0) {
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'count' => $stmt->rowCount()
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Only SELECT, SHOW, and DESCRIBE queries are allowed',
                'code' => 'INVALID_QUERY_TYPE'
            ];
        }
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => 'Query execution failed: ' . $e->getMessage(),
            'code' => 'QUERY_EXECUTION_FAILED'
        ];
    }
}

// Main API handler
$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
$token = $_GET['token'] ?? $_POST['token'] ?? null;

if (!$user_id || !$token) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters: user_id and token',
        'code' => 'MISSING_PARAMETERS'
    ]);
    exit;
}

// Validate super admin access
$auth_result = validateSuperAdmin($pdo, $user_id, $token);
if (!$auth_result['success']) {
    http_response_code(403);
    echo json_encode($auth_result);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? 'query';

switch ($action) {
    case 'query':
        $query = $_POST['query'] ?? $_GET['query'] ?? null;
        $params = json_decode($_POST['params'] ?? $_GET['params'] ?? '[]', true) ?: [];
        
        // Check if this is a DataTables request (has DataTables parameters)
        if (isset($_GET['draw']) || isset($_POST['draw'])) {
            // This is a DataTables request, redirect to users_list
            $action = 'users_list';
            // Don't process as a query, fall through to users_list case
        } else {
            if (!$query) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Missing query parameter',
                    'code' => 'MISSING_QUERY'
                ]);
                exit;
            }
            
            // Security: Only allow SELECT, SHOW, DESCRIBE queries
            if (!preg_match('/^\s*(SELECT|SHOW|DESCRIBE)\s+/i', trim($query))) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Only SELECT, SHOW, and DESCRIBE queries are allowed for security reasons',
                    'code' => 'INVALID_QUERY_TYPE'
                ]);
                exit;
            }
            
            $result = executeAuthQuery($pdo, $query, $params);
            echo json_encode($result);
            break;
        }
        // Fall through to users_list if it's a DataTables request
        
    case 'users_list':
        // Specialized endpoint for user management
        $query = "
            SELECT u.id, u.username, u.email, u.full_name, u.status, 
                   u.is_email_verified, u.last_login, u.created_at, u.birthdate,
                   u.mobile_number, u.gender, u.civil_status, u.nationality,
                   d.name as district_name, b.name as barangay_name,
                   u.id_verification_status,
                   GROUP_CONCAT(DISTINCT r.name ORDER BY r.name) as roles,
                   GROUP_CONCAT(DISTINCT s.name ORDER BY s.name) as subsystems
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id
            LEFT JOIN user_subsystems us ON u.id = us.user_id
            LEFT JOIN subsystems s ON us.subsystem_id = s.id
            LEFT JOIN districts d ON u.district_id = d.id
            LEFT JOIN barangays b ON u.barangay_id = b.id
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ";
        
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'user_stats':
        // Get user statistics
        $query = "SELECT 
            (SELECT COUNT(*) FROM users) as total_users,
            (SELECT COUNT(*) FROM users WHERE status = 'active') as active_users,
            (SELECT COUNT(*) FROM users WHERE status = 'inactive') as inactive_users,
            (SELECT COUNT(*) FROM users WHERE status = 'banned') as banned_users,
            (SELECT COUNT(*) FROM users WHERE is_email_verified = 1) as verified_users,
            (SELECT COUNT(*) FROM users WHERE is_email_verified = 0) as unverified_users,
            (SELECT COUNT(*) FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as recent_logins,
            (SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as recent_registrations";
        
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'roles_list':
        // Get all available roles
        $query = "SELECT id, name FROM roles ORDER BY name";
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'subsystems_list':
        // Get all available subsystems
        $query = "SELECT id, name FROM subsystems ORDER BY name";
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'districts_list':
        // Get all districts
        $query = "SELECT id, district_number, name FROM districts ORDER BY district_number";
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'barangays_list':
        // Get barangays by district
        $district_id = $_GET['district_id'] ?? $_POST['district_id'] ?? null;
        
        if ($district_id) {
            $query = "SELECT id, name, alternate_name FROM barangays WHERE district_id = ? ORDER BY name";
            $result = executeAuthQuery($pdo, $query, [$district_id]);
        } else {
            $query = "SELECT id, name, alternate_name, district_id FROM barangays ORDER BY district_id, name";
            $result = executeAuthQuery($pdo, $query);
        }
        
        echo json_encode($result);
        break;
        
    case 'permissions_list':
        // Get all available permissions
        $query = "SELECT id, name, description FROM permissions ORDER BY name";
        $result = executeAuthQuery($pdo, $query);
        echo json_encode($result);
        break;
        
    case 'test_connection':
        // Test database connection and basic queries
        try {
            $tests = [];
            
            // Test users table
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $tests['users_count'] = $stmt->fetch()['count'];
            
            // Test subsystems table
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM subsystems");
            $tests['subsystems_count'] = $stmt->fetch()['count'];
            
            // Test permissions table
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM permissions");
            $tests['permissions_count'] = $stmt->fetch()['count'];
            
            // Test roles table
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM roles");
            $tests['roles_count'] = $stmt->fetch()['count'];
            
            echo json_encode([
                'success' => true,
                'data' => $tests,
                'message' => 'Database connection and tables are working'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Database test failed: ' . $e->getMessage()
            ]);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action parameter',
            'code' => 'INVALID_ACTION',
            'available_actions' => ['query', 'users_list', 'user_stats', 'roles_list', 'subsystems_list', 'districts_list', 'barangays_list', 'permissions_list', 'test_connection']
        ]);
}

// Log API access
try {
    $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $user_id,
        'auth_database_api_access: ' . $action,
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
} catch (Exception $e) {
    // Log error but don't fail the request
    error_log('Failed to log audit: ' . $e->getMessage());
}
?>
