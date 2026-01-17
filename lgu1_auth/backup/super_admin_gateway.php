<?php
/**
 * Super Admin API Gateway
 * Provides access to all subsystem databases for super admin users
 */

// Prevent any output before JSON response
ob_start();

// Suppress PHP errors/warnings that could break JSON
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Register shutdown function to handle fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean();
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Fatal error occurred: ' . $error['message'],
            'code' => 'FATAL_ERROR',
            'file' => basename($error['file']),
            'line' => $error['line']
        ]);
    }
});

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

/**
 * Subsystem database configurations
 * Update these URLs for production deployment
 */
$subsystem_configs = [
    'infrastructure' => [
        'name' => 'Infrastructure Project Management',
        'api_url' => 'http://localhost/LGU1-InfrastructureProjectManagement/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_infrastructure_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'utility' => [
        'name' => 'Utility Billing and Monitoring Management (Water, Electricity)',
        'api_url' => 'http://localhost/LGU1-UtilityBillingMonitoring/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_utility_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'transportation' => [
        'name' => 'Road and Transportation Infrastructure Monitoring',
        'api_url' => 'http://localhost/LGU1-TransportationInfrastructure/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_transportation_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'facilities' => [
        'name' => 'Public Facilities Reservation System',
        'api_url' => 'http://localhost/LGU1-PublicFacilitiesReservation/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_facilities_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'maintenance' => [
        'name' => 'Community Infrastructure Maintenance Management',
        'api_url' => 'http://localhost/LGU1-InfrastructureMaintenance/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_maintenance_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'planning' => [
        'name' => 'Urban Planning and Development',
        'api_url' => 'http://localhost/LGU1-UrbanPlanningDevelopment/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_planning_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'land' => [
        'name' => 'Land Registration and Titling System',
        'api_url' => 'http://localhost/LGU1-LandRegistrationTitling/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_land_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'housing' => [
        'name' => 'Housing and Resettlement Management',
        'api_url' => 'http://localhost/LGU1-HousingAndResettlementManagement/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_housing_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'renewable' => [
        'name' => 'Renewable Energy Project Management',
        'api_url' => 'http://localhost/LGU1-RenewableEnergyProject/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_renewable_db',
            'username' => 'root',
            'password' => ''
        ]
    ],
    'efficiency' => [
        'name' => 'Energy Efficiency and Conservative Management',
        'api_url' => 'http://localhost/LGU1-EnergyEfficiencyConservative/api',
        'db_config' => [
            'host' => 'localhost',
            'dbname' => 'lgu1_efficiency_db',
            'username' => 'root',
            'password' => ''
        ]
    ]
];

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
 * Connect to subsystem database
 */
function connectToSubsystemDB($db_config) {
    try {
        // First try to connect without specifying database to check if database exists
        $dsn = "mysql:host={$db_config['host']};charset=utf8mb4";
        $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        // Check if database exists
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
        $stmt->execute([$db_config['dbname']]);
        
        if (!$stmt->fetch()) {
            return [
                'success' => false,
                'error' => 'Database does not exist: ' . $db_config['dbname'],
                'code' => 'DB_NOT_FOUND'
            ];
        }
        
        // Now connect to the specific database
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        return ['success' => true, 'connection' => $pdo];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => 'Database connection failed: ' . $e->getMessage(),
            'code' => 'DB_CONNECTION_FAILED'
        ];
    }
}

/**
 * Execute query on subsystem database
 */
function executeSubsystemQuery($pdo, $query, $params = []) {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        if (stripos($query, 'SELECT') === 0) {
            return [
                'success' => true,
                'data' => $stmt->fetchAll(),
                'count' => $stmt->rowCount()
            ];
        } else {
            return [
                'success' => true,
                'affected_rows' => $stmt->rowCount(),
                'last_insert_id' => $pdo->lastInsertId()
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

/**
 * Get all subsystem statistics
 */
function getAllSubsystemStats($subsystem_configs) {
    $stats = [];
    
    foreach ($subsystem_configs as $key => $config) {
        try {
            $db_result = connectToSubsystemDB($config['db_config']);
            
            if ($db_result['success']) {
                $pdo = $db_result['connection'];
                
                // Get table statistics
                $table_stats = executeSubsystemQuery($pdo, "SHOW TABLES");
                $tables = [];
                
                if ($table_stats['success'] && is_array($table_stats['data'])) {
                    foreach ($table_stats['data'] as $table) {
                        $table_name = array_values($table)[0];
                        try {
                            $count_result = executeSubsystemQuery($pdo, "SELECT COUNT(*) as count FROM `$table_name`");
                            $tables[$table_name] = ($count_result['success'] && isset($count_result['data'][0]['count'])) 
                                ? intval($count_result['data'][0]['count']) : 0;
                        } catch (Exception $e) {
                            $tables[$table_name] = 0; // Skip problematic tables
                        }
                    }
                }
                
                $stats[$key] = [
                    'name' => $config['name'],
                    'status' => 'connected',
                    'tables' => $tables,
                    'total_records' => array_sum($tables)
                ];
            } else {
                $stats[$key] = [
                    'name' => $config['name'],
                    'status' => 'disconnected',
                    'error' => $db_result['error'],
                    'code' => $db_result['code'] ?? 'UNKNOWN_ERROR'
                ];
            }
        } catch (Exception $e) {
            $stats[$key] = [
                'name' => $config['name'],
                'status' => 'error',
                'error' => 'Unexpected error: ' . $e->getMessage(),
                'code' => 'EXCEPTION_ERROR'
            ];
        }
    }
    
    return $stats;
}

// Check if database connection is available
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection not available. Please ensure the database is set up correctly.',
        'code' => 'NO_DATABASE_CONNECTION'
    ]);
    exit;
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

$action = $_GET['action'] ?? $_POST['action'] ?? 'stats';
$subsystem = $_GET['subsystem'] ?? $_POST['subsystem'] ?? null;

switch ($action) {
    case 'list_subsystems':
        echo json_encode([
            'success' => true,
            'subsystems' => array_map(function($key, $config) {
                return [
                    'key' => $key,
                    'name' => $config['name'],
                    'api_url' => $config['api_url']
                ];
            }, array_keys($subsystem_configs), $subsystem_configs)
        ]);
        break;
        
    case 'stats':
        $stats = getAllSubsystemStats($subsystem_configs);
        
        // Clean any output buffer content before sending JSON
        ob_clean();
        echo json_encode([
            'success' => true,
            'timestamp' => date('Y-m-d H:i:s'),
            'stats' => $stats
        ]);
        break;
        
    case 'query':
        if (!$subsystem || !isset($subsystem_configs[$subsystem])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid or missing subsystem parameter',
                'code' => 'INVALID_SUBSYSTEM'
            ]);
            exit;
        }
        
        $query = $_POST['query'] ?? null;
        $params = json_decode($_POST['params'] ?? '[]', true);
        
        if (!$query) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Missing query parameter',
                'code' => 'MISSING_QUERY'
            ]);
            exit;
        }
        
        // Security: Only allow SELECT queries for safety
        if (!preg_match('/^\s*SELECT\s+/i', $query)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Only SELECT queries are allowed',
                'code' => 'INVALID_QUERY_TYPE'
            ]);
            exit;
        }
        
        $config = $subsystem_configs[$subsystem];
        $db_result = connectToSubsystemDB($config['db_config']);
        
        if (!$db_result['success']) {
            http_response_code(500);
            echo json_encode($db_result);
            exit;
        }
        
        $query_result = executeSubsystemQuery($db_result['connection'], $query, $params);
        echo json_encode($query_result);
        break;
        
    case 'tables':
        if (!$subsystem || !isset($subsystem_configs[$subsystem])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid or missing subsystem parameter',
                'code' => 'INVALID_SUBSYSTEM'
            ]);
            exit;
        }
        
        $config = $subsystem_configs[$subsystem];
        $db_result = connectToSubsystemDB($config['db_config']);
        
        if (!$db_result['success']) {
            http_response_code(500);
            echo json_encode($db_result);
            exit;
        }
        
        $tables_result = executeSubsystemQuery($db_result['connection'], "SHOW TABLES");
        
        if ($tables_result['success']) {
            $tables = [];
            foreach ($tables_result['data'] as $table) {
                $table_name = array_values($table)[0];
                
                // Get table structure
                $structure_result = executeSubsystemQuery(
                    $db_result['connection'], 
                    "DESCRIBE `$table_name`"
                );
                
                // Get row count
                $count_result = executeSubsystemQuery(
                    $db_result['connection'], 
                    "SELECT COUNT(*) as count FROM `$table_name`"
                );
                
                $tables[] = [
                    'name' => $table_name,
                    'structure' => $structure_result['success'] ? $structure_result['data'] : [],
                    'row_count' => $count_result['success'] ? $count_result['data'][0]['count'] : 0
                ];
            }
            
            echo json_encode([
                'success' => true,
                'subsystem' => $config['name'],
                'tables' => $tables
            ]);
        } else {
            echo json_encode($tables_result);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid action parameter',
            'code' => 'INVALID_ACTION',
            'available_actions' => ['list_subsystems', 'stats', 'query', 'tables']
        ]);
}

// Log super admin API access
$stmt = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)');
$stmt->execute([
    $user_id,
    'super_admin_api_access: ' . $action . ($subsystem ? " ($subsystem)" : ''),
    $_SERVER['REMOTE_ADDR'] ?? '',
    $_SERVER['HTTP_USER_AGENT'] ?? ''
]);
?>
