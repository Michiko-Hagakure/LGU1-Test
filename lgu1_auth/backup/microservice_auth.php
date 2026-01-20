<?php
/**
 * Microservice Authentication Helper
 * This file should be included in other subsystem projects for authentication
 */

class LGU1Auth {
    private $auth_api_url;
    
    public function __construct($base_url = 'http://localhost/LGU1-LOGIN') {
        $this->auth_api_url = $base_url . '/api/dashboard_validation.php';
    }
    
    /**
     * Validate user access for microservice dashboard
     * 
     * @param int $user_id User ID
     * @param string $token Authentication token
     * @param string $subsystem Required subsystem name
     * @param string $required_role Required role (optional)
     * @return array Validation result
     */
    public function validateAccess($user_id, $token, $subsystem = null, $required_role = null) {
        $params = [
            'user_id' => $user_id,
            'token' => $token
        ];
        
        if ($subsystem) {
            $params['subsystem'] = $subsystem;
        }
        
        if ($required_role) {
            $params['required_role'] = $required_role;
        }
        
        $url = $this->auth_api_url . '?' . http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
                'header' => [
                    'Content-Type: application/json',
                    'User-Agent: LGU1-Microservice-Client/1.0'
                ]
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return [
                'success' => false,
                'error' => 'Failed to connect to authentication service',
                'code' => 'AUTH_SERVICE_UNAVAILABLE'
            ];
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid response from authentication service',
                'code' => 'INVALID_AUTH_RESPONSE'
            ];
        }
        
        return $data;
    }
    
    /**
     * Check if user has specific permission
     * 
     * @param array $user_data User data from validation
     * @param string $permission Permission name
     * @return bool
     */
    public function hasPermission($user_data, $permission) {
        return in_array($permission, $user_data['permissions'] ?? []);
    }
    
    /**
     * Check if user has specific role
     * 
     * @param array $user_data User data from validation
     * @param string $role Role name
     * @return bool
     */
    public function hasRole($user_data, $role) {
        return in_array($role, $user_data['roles'] ?? []);
    }
    
    /**
     * Check if user can access specific subsystem
     * 
     * @param array $user_data User data from validation
     * @param string $subsystem Subsystem name
     * @return bool
     */
    public function canAccessSubsystem($user_data, $subsystem) {
        return in_array($subsystem, $user_data['subsystems'] ?? []) || 
               $this->hasRole($user_data, 'super admin');
    }
    
    /**
     * Get user dashboard redirect URL based on roles
     * 
     * @param array $roles User roles
     * @param int $user_id User ID
     * @param string $token Auth token
     * @return string Redirect URL
     */
    public function getDashboardUrl($roles, $user_id, $token) {
        if (in_array('super admin', $roles)) {
            return 'http://localhost/LGU1-LOGIN/admin/dashboard.php';
        }
        
        if (in_array('housing admin', $roles) || 
            in_array('eligibility officer', $roles) || 
            in_array('resettlement coordinator', $roles)) {
            return 'http://localhost/LGU1-HousingAndResettlementManagement/dashboard.php?user_id=' . 
                   $user_id . '&token=' . urlencode($token);
        }
        
        if (in_array('citizen', $roles)) {
            return 'http://localhost/LGU1-LOGIN/citizen/portal.php';
        }
        
        // Default dashboard
        return 'http://localhost/LGU1-LOGIN/dashboard.php';
    }
}

// Example usage for Housing subsystem dashboard:
/*
<?php
require_once 'path/to/microservice_auth.php';

session_start();

$auth = new LGU1Auth();
$user_id = $_GET['user_id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$user_id || !$token) {
    header('Location: http://localhost/LGU1-LOGIN/public/login.php');
    exit;
}

$validation = $auth->validateAccess(
    $user_id, 
    $token, 
    'Housing and Resettlement Management',
    'housing admin' // Optional: require specific role
);

if (!$validation['success']) {
    echo "Access Denied: " . $validation['error'];
    exit;
}

$user = $validation['user'];
$dashboard_config = $validation['dashboard_config'];

// User is authenticated and authorized
echo "Welcome " . $user['full_name'] . "!";
// Show dashboard content based on user roles and permissions
?>
*/
?>
