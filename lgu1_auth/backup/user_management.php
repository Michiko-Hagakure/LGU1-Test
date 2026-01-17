<?php
/**
 * User Management API
 * Enhanced API for super admin user management operations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
        'error' => 'Database connection not available',
        'code' => 'NO_DATABASE_CONNECTION'
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
 * Generate secure random password
 */
function generateSecurePassword($length = 12) {
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    $password = "";
    for ($i = 0; $i < $length; $i++) {
        $password .= $charset[random_int(0, strlen($charset) - 1)];
    }
    return $password;
}

// Main API handler
$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
$token = $_GET['token'] ?? $_POST['token'] ?? null;

if (!$user_id || !$token) {
    // Debug information for troubleshooting
    $debug_info = [
        'method' => $method,
        'get_params' => $_GET,
        'post_params' => array_keys($_POST), // Don't log sensitive data
        'has_post_user_id' => isset($_POST['user_id']),
        'has_post_token' => isset($_POST['token']),
        'has_get_user_id' => isset($_GET['user_id']),
        'has_get_token' => isset($_GET['token'])
    ];
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters: user_id and token',
        'code' => 'MISSING_PARAMETERS',
        'debug' => $debug_info
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

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if (!$action) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Action parameter is required',
        'code' => 'MISSING_ACTION'
    ]);
    exit;
}

try {
    switch ($action) {
        case 'create':
        case 'create_user':
            // Handle both JSON and FormData submissions
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Prefer FormData ($_POST) if available, fallback to JSON
            if (!empty($_POST)) {
                $source = $_POST;
            } elseif ($input && is_array($input)) {
                $source = $input;
            } else {
                throw new Exception('No valid data received for user creation');
            }
            
            $data = [
                'username' => trim($source['username'] ?? ''),
                'email' => trim($source['email'] ?? ''),
                'full_name' => trim($source['full_name'] ?? ''),
                'password' => $source['password'] ?? generateSecurePassword(),
                'birthdate' => $source['birthdate'] ?? null,
                'mobile_number' => trim($source['mobile_number'] ?? ''),
                'gender' => $source['gender'] ?? null,
                'civil_status' => $source['civil_status'] ?? null,
                'nationality' => trim($source['nationality'] ?? 'Filipino'),
                'district_id' => $source['district_id'] ?? null,
                'barangay_id' => $source['barangay_id'] ?? null,
                'current_address' => trim($source['current_address'] ?? ''),
                'zip_code' => trim($source['zip_code'] ?? ''),
                'status' => $source['status'] ?? 'active',
                'role' => $source['role'] ?? null, // Single role name
                'roles' => $source['roles'] ?? [], // Array of role IDs (fallback)
                'subsystems' => $source['subsystems'] ?? [], // Array of subsystem IDs
                'permissions' => $source['permissions'] ?? [] // Array of permission IDs
            ];
            
            // Validation
            if (empty($data['username']) || empty($data['email']) || empty($data['full_name'])) {
                throw new Exception('Username, email, and full name are required');
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email address');
            }
            
            // Check for existing user
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$data['username'], $data['email']]);
            if ($stmt->fetch()) {
                throw new Exception('Username or email already exists');
            }
            
            // Hash password
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Create user
            $stmt = $pdo->prepare('
                INSERT INTO users (
                    username, email, full_name, password_hash, birthdate, mobile_number, 
                    gender, civil_status, nationality, district_id, barangay_id, 
                    current_address, zip_code, status, is_email_verified, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
            ');
            
            $stmt->execute([
                $data['username'], $data['email'], $data['full_name'], $password_hash,
                $data['birthdate'], $data['mobile_number'], $data['gender'], $data['civil_status'],
                $data['nationality'], $data['district_id'], $data['barangay_id'],
                $data['current_address'], $data['zip_code'], $data['status']
            ]);
            
            $new_user_id = $pdo->lastInsertId();
            
            // Assign roles
            if (!empty($data['role'])) {
                // Handle single role by name
                $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = ?');
                $stmt->execute([$data['role']]);
                $role = $stmt->fetch();
                if ($role) {
                    $stmt = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
                    $stmt->execute([$new_user_id, $role['id']]);
                }
            } elseif (!empty($data['roles'])) {
                // Handle array of role IDs (fallback)
                $stmt = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
                foreach ($data['roles'] as $role_id) {
                    $stmt->execute([$new_user_id, $role_id]);
                }
            }
            
            // Assign subsystems
            if (!empty($data['subsystems'])) {
                $stmt = $pdo->prepare('INSERT IGNORE INTO user_subsystems (user_id, subsystem_id) VALUES (?, ?)');
                foreach ($data['subsystems'] as $subsystem_id) {
                    if (is_numeric($subsystem_id)) {
                        $stmt->execute([$new_user_id, (int)$subsystem_id]);
                    }
                }
            }
            
            // Assign permissions
            if (!empty($data['permissions'])) {
                $stmt = $pdo->prepare('INSERT IGNORE INTO user_permissions (user_id, permission_id) VALUES (?, ?)');
                foreach ($data['permissions'] as $permission_id) {
                    if (is_numeric($permission_id)) {
                        $stmt->execute([$new_user_id, (int)$permission_id]);
                    }
                }
            }
            
            // Log the action
            $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $user_id,
                'user_created: ' . $data['username'] . ' (ID: ' . $new_user_id . ')',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'User created successfully',
                'user_id' => $new_user_id,
                'temp_password' => $data['password']
            ]);
            break;
            
        case 'get':
        case 'get_user':
            $target_user_id = $_GET['target_user_id'] ?? null;
            if (!$target_user_id) {
                throw new Exception('Target user ID is required');
            }
            
            $stmt = $pdo->prepare('
                SELECT u.*, d.name as district_name, b.name as barangay_name,
                       GROUP_CONCAT(DISTINCT r.id) as role_ids,
                       GROUP_CONCAT(DISTINCT r.name) as roles,
                       GROUP_CONCAT(DISTINCT s.id) as subsystem_ids,
                       GROUP_CONCAT(DISTINCT s.name) as subsystems,
                       GROUP_CONCAT(DISTINCT p.id) as permission_ids,
                       GROUP_CONCAT(DISTINCT p.name) as permissions
                FROM users u
                LEFT JOIN districts d ON u.district_id = d.id
                LEFT JOIN barangays b ON u.barangay_id = b.id
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                LEFT JOIN user_subsystems us ON u.id = us.user_id
                LEFT JOIN subsystems s ON us.subsystem_id = s.id
                LEFT JOIN user_permissions up ON u.id = up.user_id
                LEFT JOIN permissions p ON up.permission_id = p.id
                WHERE u.id = ?
                GROUP BY u.id
            ');
            $stmt->execute([$target_user_id]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user_data) {
                throw new Exception('User not found');
            }
            
            // Format the data
            $user_data['role_ids'] = $user_data['role_ids'] ? explode(',', $user_data['role_ids']) : [];
            $user_data['roles'] = $user_data['roles'] ? explode(',', $user_data['roles']) : [];
            $user_data['subsystem_ids'] = $user_data['subsystem_ids'] ? explode(',', $user_data['subsystem_ids']) : [];
            $user_data['subsystems'] = $user_data['subsystems'] ? explode(',', $user_data['subsystems']) : [];
            $user_data['permission_ids'] = $user_data['permission_ids'] ? explode(',', $user_data['permission_ids']) : [];
            $user_data['permissions'] = $user_data['permissions'] ? explode(',', $user_data['permissions']) : [];
            
            echo json_encode([
                'success' => true,
                'data' => $user_data
            ]);
            break;
            
        case 'update_user':
            $target_user_id = $_POST['target_user_id'] ?? null;
            if (!$target_user_id) {
                throw new Exception('Target user ID is required');
            }
            
            // Get current user data and check if target is super admin
            $stmt = $pdo->prepare('
                SELECT u.username, 
                       (SELECT COUNT(*) FROM user_roles ur 
                        JOIN roles r ON ur.role_id = r.id 
                        WHERE ur.user_id = u.id AND r.name = "super admin") as is_super_admin
                FROM users u WHERE u.id = ?
            ');
            $stmt->execute([$target_user_id]);
            $current_user = $stmt->fetch();
            if (!$current_user) {
                throw new Exception('User not found');
            }
            
            // Prevent editing super admin accounts unless it's self-edit
            if ($current_user['is_super_admin'] && $target_user_id != $user_id) {
                throw new Exception('Super admin accounts can only be edited by themselves');
            }
            
            // Prepare update data
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'birthdate' => $_POST['birthdate'] ?? null,
                'mobile_number' => trim($_POST['mobile_number'] ?? ''),
                'gender' => $_POST['gender'] ?? null,
                'civil_status' => $_POST['civil_status'] ?? null,
                'nationality' => trim($_POST['nationality'] ?? ''),
                'district_id' => $_POST['district_id'] ?? null,
                'barangay_id' => $_POST['barangay_id'] ?? null,
                'current_address' => trim($_POST['current_address'] ?? ''),
                'zip_code' => trim($_POST['zip_code'] ?? ''),
                'status' => $_POST['status'] ?? 'active',
                'new_password' => $_POST['new_password'] ?? null,
                'roles' => $_POST['roles'] ?? [],
                'subsystems' => $_POST['subsystems'] ?? [],
                'permissions' => $_POST['permissions'] ?? []
            ];
            
            // Check for duplicate username/email (excluding current user)
            $stmt = $pdo->prepare('SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?');
            $stmt->execute([$data['username'], $data['email'], $target_user_id]);
            if ($stmt->fetch()) {
                throw new Exception('Username or email already exists');
            }
            
            // Update user
            if (!empty($data['new_password'])) {
                // Update with new password
                $password_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('
                    UPDATE users SET 
                        username = ?, email = ?, full_name = ?, birthdate = ?, mobile_number = ?,
                        gender = ?, civil_status = ?, nationality = ?, district_id = ?, barangay_id = ?,
                        current_address = ?, zip_code = ?, status = ?, password_hash = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                
                $stmt->execute([
                    $data['username'], $data['email'], $data['full_name'], $data['birthdate'],
                    $data['mobile_number'], $data['gender'], $data['civil_status'], $data['nationality'],
                    $data['district_id'], $data['barangay_id'], $data['current_address'], $data['zip_code'],
                    $data['status'], $password_hash, $target_user_id
                ]);
            } else {
                // Update without password change
                $stmt = $pdo->prepare('
                    UPDATE users SET 
                        username = ?, email = ?, full_name = ?, birthdate = ?, mobile_number = ?,
                        gender = ?, civil_status = ?, nationality = ?, district_id = ?, barangay_id = ?,
                        current_address = ?, zip_code = ?, status = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                
                $stmt->execute([
                    $data['username'], $data['email'], $data['full_name'], $data['birthdate'],
                    $data['mobile_number'], $data['gender'], $data['civil_status'], $data['nationality'],
                    $data['district_id'], $data['barangay_id'], $data['current_address'], $data['zip_code'],
                    $data['status'], $target_user_id
                ]);
            }
            
            // Update roles
            $pdo->prepare('DELETE FROM user_roles WHERE user_id = ?')->execute([$target_user_id]);
            if (!empty($data['roles'])) {
                $stmt = $pdo->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)');
                foreach ($data['roles'] as $role_id) {
                    $stmt->execute([$target_user_id, $role_id]);
                }
            }
            
            // Update subsystems
            $pdo->prepare('DELETE FROM user_subsystems WHERE user_id = ?')->execute([$target_user_id]);
            if (!empty($data['subsystems'])) {
                $stmt = $pdo->prepare('INSERT INTO user_subsystems (user_id, subsystem_id) VALUES (?, ?)');
                foreach ($data['subsystems'] as $subsystem_id) {
                    $stmt->execute([$target_user_id, $subsystem_id]);
                }
            }
            
            // Update permissions
            $pdo->prepare('DELETE FROM user_permissions WHERE user_id = ?')->execute([$target_user_id]);
            if (!empty($data['permissions'])) {
                $stmt = $pdo->prepare('INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)');
                foreach ($data['permissions'] as $permission_id) {
                    $stmt->execute([$target_user_id, $permission_id]);
                }
            }
            
            // Log the action
            $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $user_id,
                'user_updated: ' . $current_user['username'] . ' (ID: ' . $target_user_id . ')',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
            break;
            
        case 'delete':
        case 'delete_user':
            $input = json_decode(file_get_contents('php://input'), true);
            $source = $input ?: $_POST;
            $target_user_id = $source['target_user_id'] ?? null;
            if (!$target_user_id) {
                throw new Exception('Target user ID is required');
            }
            
            // Get user info for logging
            $stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
            $stmt->execute([$target_user_id]);
            $target_user = $stmt->fetch();
            if (!$target_user) {
                throw new Exception('User not found');
            }
            
            // Prevent deleting super admin
            $stmt = $pdo->prepare('
                SELECT r.name FROM user_roles ur 
                JOIN roles r ON ur.role_id = r.id 
                WHERE ur.user_id = ? AND r.name = "super admin"
            ');
            $stmt->execute([$target_user_id]);
            if ($stmt->fetch()) {
                throw new Exception('Cannot delete super admin users');
            }
            
            // Delete user (cascading deletes will handle related records)
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$target_user_id]);
            
            // Log the action
            $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $user_id,
                'user_deleted: ' . $target_user['username'] . ' (ID: ' . $target_user_id . ')',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
            break;
            
        case 'ban':
        case 'unban':
        case 'ban_user':
            $input = json_decode(file_get_contents('php://input'), true);
            $source = $input ?: $_POST;
            $target_user_id = $source['target_user_id'] ?? null;
            $ban_reason = trim($source['ban_reason'] ?? '');
            
            if (!$target_user_id) {
                throw new Exception('Target user ID is required');
            }
            
            // Check if target user is super admin and prevent banning
            $stmt = $pdo->prepare('
                SELECT u.username, u.status,
                       (SELECT COUNT(*) FROM user_roles ur 
                        JOIN roles r ON ur.role_id = r.id 
                        WHERE ur.user_id = u.id AND r.name = "super admin") as is_super_admin
                FROM users u WHERE u.id = ?
            ');
            $stmt->execute([$target_user_id]);
            $target_user = $stmt->fetch();
            if (!$target_user) {
                throw new Exception('User not found');
            }
            
            // Prevent banning super admin accounts
            if ($target_user['is_super_admin']) {
                throw new Exception('Super admin accounts cannot be banned');
            }
            
            // Determine new status based on action
            if ($action === 'ban_user') {
                // Toggle ban status for ban_user action
                $new_status = $target_user['status'] === 'banned' ? 'active' : 'banned';
            } else {
                // Use specific action for ban/unban
                $new_status = ($action === 'ban') ? 'banned' : 'active';
            }
            $action_text = $new_status === 'banned' ? 'banned' : 'unbanned';
            
            $stmt = $pdo->prepare('UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$new_status, $target_user_id]);
            
            // Log the action
            $log_message = 'user_' . $action_text . ': ' . $target_user['username'] . ' (ID: ' . $target_user_id . ')';
            if ($new_status === 'banned' && $ban_reason) {
                $log_message .= ' - Reason: ' . $ban_reason;
            }
            
            $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $user_id,
                $log_message,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'User ' . $action_text . ' successfully',
                'new_status' => $new_status
            ]);
            break;
            
        case 'reset_password':
            $target_user_id = $_POST['target_user_id'] ?? null;
            if (!$target_user_id) {
                throw new Exception('Target user ID is required');
            }
            
            // Get user info
            $stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
            $stmt->execute([$target_user_id]);
            $target_user = $stmt->fetch();
            if (!$target_user) {
                throw new Exception('User not found');
            }
            
            // Generate new password
            $new_password = generateSecurePassword();
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password
            $stmt = $pdo->prepare('UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$password_hash, $target_user_id]);
            
            // Log the action
            $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $user_id,
                'password_reset: ' . $target_user['username'] . ' (ID: ' . $target_user_id . ')',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Password reset successfully',
                'new_password' => $new_password
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action',
                'code' => 'INVALID_ACTION'
            ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code' => 'OPERATION_FAILED'
    ]);
}
?>
