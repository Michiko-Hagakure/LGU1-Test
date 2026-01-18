<?php
/**
 * API Configuration for LGU1 Microservices
 * Update these URLs for production deployment
 */

// Environment configuration
$environment = $_ENV['LGU1_ENVIRONMENT'] ?? 'development'; // development, staging, production

$config = [
    'development' => [
        'base_url' => 'http://localhost/LGU1-LOGIN',
        'subsystems' => [
            'infrastructure' => [
                'name' => 'Infrastructure Project Management',
                'url' => 'http://localhost/LGU1-InfrastructureProjectManagement',
                'api_url' => 'http://localhost/LGU1-InfrastructureProjectManagement/api',
                'dashboard_url' => 'http://localhost/LGU1-InfrastructureProjectManagement/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_infrastructure_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'utility' => [
                'name' => 'Utility Billing and Monitoring Management (Water, Electricity)',
                'url' => 'http://localhost/LGU1-UtilityBillingMonitoring',
                'api_url' => 'http://localhost/LGU1-UtilityBillingMonitoring/api',
                'dashboard_url' => 'http://localhost/LGU1-UtilityBillingMonitoring/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_utility_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'transportation' => [
                'name' => 'Road and Transportation Infrastructure Monitoring',
                'url' => 'http://localhost/LGU1-TransportationInfrastructure',
                'api_url' => 'http://localhost/LGU1-TransportationInfrastructure/api',
                'dashboard_url' => 'http://localhost/LGU1-TransportationInfrastructure/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_transportation_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'facilities' => [
                'name' => 'Public Facilities Reservation System',
                'url' => 'http://localhost/LGU1-PublicFacilitiesReservation',
                'api_url' => 'http://localhost/LGU1-PublicFacilitiesReservation/api',
                'dashboard_url' => 'http://localhost/LGU1-PublicFacilitiesReservation/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_facilities_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'maintenance' => [
                'name' => 'Community Infrastructure Maintenance Management',
                'url' => 'http://localhost/LGU1-InfrastructureMaintenance',
                'api_url' => 'http://localhost/LGU1-InfrastructureMaintenance/api',
                'dashboard_url' => 'http://localhost/LGU1-InfrastructureMaintenance/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_maintenance_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'planning' => [
                'name' => 'Urban Planning and Development',
                'url' => 'http://localhost/LGU1-UrbanPlanningDevelopment',
                'api_url' => 'http://localhost/LGU1-UrbanPlanningDevelopment/api',
                'dashboard_url' => 'http://localhost/LGU1-UrbanPlanningDevelopment/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_planning_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'land' => [
                'name' => 'Land Registration and Titling System',
                'url' => 'http://localhost/LGU1-LandRegistrationTitling',
                'api_url' => 'http://localhost/LGU1-LandRegistrationTitling/api',
                'dashboard_url' => 'http://localhost/LGU1-LandRegistrationTitling/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_land_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'housing' => [
                'name' => 'Housing and Resettlement Management',
                'url' => 'http://localhost/LGU1-HousingAndResettlementManagement',
                'api_url' => 'http://localhost/LGU1-HousingAndResettlementManagement/api',
                'dashboard_url' => 'http://localhost/LGU1-HousingAndResettlementManagement/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_housing_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'renewable' => [
                'name' => 'Renewable Energy Project Management',
                'url' => 'http://localhost/LGU1-RenewableEnergyProject',
                'api_url' => 'http://localhost/LGU1-RenewableEnergyProject/api',
                'dashboard_url' => 'http://localhost/LGU1-RenewableEnergyProject/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_renewable_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ],
            'efficiency' => [
                'name' => 'Energy Efficiency and Conservative Management',
                'url' => 'http://localhost/LGU1-EnergyEfficiencyConservative',
                'api_url' => 'http://localhost/LGU1-EnergyEfficiencyConservative/api',
                'dashboard_url' => 'http://localhost/LGU1-EnergyEfficiencyConservative/dashboard.php',
                'db_config' => [
                    'host' => 'localhost',
                    'dbname' => 'lgu1_efficiency_db',
                    'username' => 'root',
                    'password' => ''
                ]
            ]
        ]
    ],
    'staging' => [
        'base_url' => 'https://staging.lgu1.gov.ph/auth',
        'subsystems' => [
            // Copy development config and update URLs for staging
            // Example:
            // 'housing' => [
            //     'name' => 'Housing and Resettlement Management',
            //     'url' => 'https://staging.lgu1.gov.ph/housing',
            //     'api_url' => 'https://staging.lgu1.gov.ph/housing/api',
            //     'dashboard_url' => 'https://staging.lgu1.gov.ph/housing/dashboard.php',
            //     'db_config' => [
            //         'host' => 'staging-db.lgu1.gov.ph',
            //         'dbname' => 'lgu1_housing_db',
            //         'username' => 'staging_user',
            //         'password' => 'staging_password'
            //     ]
            // ]
        ]
    ],
    'production' => [
        'base_url' => 'https://lgu1.gov.ph/auth',
        'subsystems' => [
            // Production URLs
            // Example:
            // 'housing' => [
            //     'name' => 'Housing and Resettlement Management',
            //     'url' => 'https://housing.lgu1.gov.ph',
            //     'api_url' => 'https://housing.lgu1.gov.ph/api',
            //     'dashboard_url' => 'https://housing.lgu1.gov.ph/dashboard.php',
            //     'db_config' => [
            //         'host' => 'prod-db.lgu1.gov.ph',
            //         'dbname' => 'lgu1_housing_db',
            //         'username' => 'prod_user',
            //         'password' => 'prod_password'
            //     ]
            // ]
        ]
    ]
];

// Get current environment configuration
function getApiConfig() {
    global $config, $environment;
    return $config[$environment] ?? $config['development'];
}

// Get specific subsystem configuration
function getSubsystemConfig($subsystem_key) {
    $config = getApiConfig();
    return $config['subsystems'][$subsystem_key] ?? null;
}

// Get all subsystems
function getAllSubsystems() {
    $config = getApiConfig();
    return $config['subsystems'] ?? [];
}

// Get base URL
function getBaseUrl() {
    $config = getApiConfig();
    return $config['base_url'];
}

// Helper function to build URLs with parameters
function buildUrl($base_url, $params = []) {
    if (empty($params)) {
        return $base_url;
    }
    return $base_url . '?' . http_build_query($params);
}

// Example usage:
// $housing_config = getSubsystemConfig('housing');
// $dashboard_url = buildUrl($housing_config['dashboard_url'], ['user_id' => 123, 'token' => 'abc']);
?>
