<?php
/**
 * Simple User Creation API
 * Alternative endpoint for user creation with simplified authentication
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Only POST method is allowed'
    ]);
    exit;
}

try {
    require_once __DIR__ . '/../config/db-connection.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database configuration error: ' . $e->getMessage()
    ]);
    exit;
}

// Check if database connection is available
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection not available'
    ]);
    exit;
}

try {
    // Get form data
    $data = [
        'username' => trim($_POST['username'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'mobile_number' => trim($_POST['mobile_number'] ?? ''),
        'role' => $_POST['role'] ?? 'citizen',
        'status' => $_POST['status'] ?? 'active',
        'subsystems' => $_POST['subsystems'] ?? [],
        'permissions' => $_POST['permissions'] ?? []
    ];
    
    // Debug logging
    error_log("Simple User Create - Data received: " . json_encode([
        'username' => $data['username'],
        'email' => $data['email'],
        'role' => $data['role'],
        'subsystems_count' => is_array($data['subsystems']) ? count($data['subsystems']) : 'not_array',
        'permissions_count' => is_array($data['permissions']) ? count($data['permissions']) : 'not_array',
        'subsystems' => $data['subsystems'],
        'permissions' => $data['permissions']
    ]));
    
    // Basic validation
    if (empty($data['username']) || empty($data['email']) || empty($data['full_name']) || empty($data['password'])) {
        throw new Exception('Username, email, full name, and password are required');
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
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Create user
    $stmt = $pdo->prepare('
        INSERT INTO users (
            username, email, full_name, password_hash, mobile_number, 
            status, is_email_verified, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
    ');
    
    $stmt->execute([
        $data['username'], 
        $data['email'], 
        $data['full_name'], 
        $password_hash,
        $data['mobile_number'], 
        $data['status']
    ]);
    
    $new_user_id = $pdo->lastInsertId();
    
    // Assign role
    if (!empty($data['role'])) {
        $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = ?');
        $stmt->execute([$data['role']]);
        $role = $stmt->fetch();
        if ($role) {
            // Use INSERT IGNORE to prevent duplicate key errors
            $stmt = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)');
            $stmt->execute([$new_user_id, $role['id']]);
        }
    }
    
    // Assign subsystems
    if (!empty($data['subsystems']) && is_array($data['subsystems'])) {
        $stmt = $pdo->prepare('INSERT IGNORE INTO user_subsystems (user_id, subsystem_id) VALUES (?, ?)');
        foreach ($data['subsystems'] as $subsystem_id) {
            if (is_numeric($subsystem_id)) {
                try {
                    $stmt->execute([$new_user_id, (int)$subsystem_id]);
                } catch (PDOException $e) {
                    // Log but don't fail on duplicate subsystem assignments
                    error_log("Subsystem assignment error for user $new_user_id, subsystem $subsystem_id: " . $e->getMessage());
                }
            }
        }
    }
    
    // Assign permissions
    if (!empty($data['permissions']) && is_array($data['permissions'])) {
        $stmt = $pdo->prepare('INSERT IGNORE INTO user_permissions (user_id, permission_id) VALUES (?, ?)');
        foreach ($data['permissions'] as $permission_id) {
            if (is_numeric($permission_id)) {
                try {
                    $stmt->execute([$new_user_id, (int)$permission_id]);
                } catch (PDOException $e) {
                    // Log but don't fail on duplicate permission assignments
                    error_log("Permission assignment error for user $new_user_id, permission $permission_id: " . $e->getMessage());
                }
            }
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'User created successfully',
        'user_id' => $new_user_id,
        'temp_password' => $data['password']
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
