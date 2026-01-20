<?php
/**
 * DataTables Users API
 * Specialized endpoint for DataTables user management
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
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Database configuration error: ' . $e->getMessage()
    ]);
    exit;
}

// Check if database connection is available
if (!isset($pdo)) {
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Main authentication database connection not available'
    ]);
    exit;
}

$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
$token = $_GET['token'] ?? $_POST['token'] ?? null;

if (!$user_id || !$token) {
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Missing authentication parameters'
    ]);
    exit;
}

// Validate super admin access
$auth = new LGU1Auth();
$validation = $auth->validateAccess($user_id, $token, null, 'super admin');

if (!$validation['success']) {
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Super admin access required'
    ]);
    exit;
}

try {
    // Get users with all related data
    $query = "
        SELECT u.id, u.username, u.email, u.full_name, u.status, 
               u.is_email_verified, u.last_login, u.created_at, u.birthdate,
               u.mobile_number, u.gender, u.civil_status, u.nationality,
               d.name as district_name, b.name as barangay_name,
               u.id_verification_status,
               GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') as roles,
               GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR ', ') as subsystems
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
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $total_stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $total_count = $total_stmt->fetch()['total'];
    
    // Format data for DataTables
    $data = [];
    foreach ($users as $user) {
        // Create user avatar/info column
        $user_column = [
            'avatar' => strtoupper(substr($user['full_name'], 0, 1)),
            'name' => $user['full_name'],
            'username' => $user['username'],
            'verified' => $user['is_email_verified'] == 1
        ];
        
        // Format status
        $status_column = [
            'status' => $user['status'],
            'icon' => $user['status'] === 'active' ? 'check-circle-fill' : 'x-circle-fill',
            'class' => $user['status'] === 'active' ? 'success' : 'danger'
        ];
        
        // Format roles
        $roles_column = [];
        if ($user['roles']) {
            $roles = explode(', ', $user['roles']);
            foreach ($roles as $role) {
                $roles_column[] = [
                    'name' => $role,
                    'class' => strpos($role, 'super admin') !== false ? 'danger' : 
                              (strpos($role, 'admin') !== false ? 'warning' : 
                              ($role === 'citizen' ? 'success' : 'secondary'))
                ];
            }
        }
        
        // Format last login
        $last_login = $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never';
        $created_date = date('M j, Y', strtotime($user['created_at']));
        
        $data[] = [
            'DT_RowId' => 'user_' . $user['id'],
            'user_info' => $user_column,
            'email' => $user['email'],
            'status' => $status_column,
            'roles' => $roles_column,
            'last_login' => $last_login,
            'created_at' => $created_date,
            'actions' => $user['id'], // Will be formatted by DataTables
            // Raw data for filtering/sorting
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'status_raw' => $user['status'],
            'roles_raw' => $user['roles'] ?? '',
            'verified_raw' => $user['is_email_verified'],
            'last_login_raw' => $user['last_login'],
            'created_raw' => $user['created_at']
        ];
    }
    
    // Return DataTables format
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => intval($total_count),
        'recordsFiltered' => intval($total_count), // For now, no server-side filtering
        'data' => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'draw' => intval($_GET['draw'] ?? 1),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}

// Log API access
try {
    $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $user_id,
        'datatables_users_access',
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
} catch (Exception $e) {
    // Ignore logging errors
}
?>
