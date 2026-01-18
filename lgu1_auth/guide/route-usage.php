<?php
/**
 * ROUTE.PHP USAGE GUIDE
 * How to use the API routing system
 */

// ========== BASIC USAGE ==========

// 1. Direct URL Access
// https://local-government-unit-1-ph.com/api/route.php?path=barangays

// 2. JavaScript Fetch
/*
fetch('api/route.php?path=users')
  .then(response => response.json())
  .then(data => console.log(data));
*/

// 3. jQuery AJAX
/*
$.get('api/route.php?path=subsystem-roles', function(data) {
    console.log(data);
});
*/

// 4. PHP cURL
/*
$response = file_get_contents('https://local-government-unit-1-ph.com/api/route.php?path=barangays');
$data = json_decode($response, true);
*/

// ========== AVAILABLE ENDPOINTS ==========
$endpoints = [
    'barangays' => 'Get barangay data',
    'districts' => 'Get district data',
    'subsystem-roles' => 'Get subsystem roles',
    'infrastructure-users' => 'Get infrastructure users',
    'utility-users' => 'Get utility users',
    'transportation-users' => 'Get transportation users',
    'facilities-users' => 'Get facilities users',
    'community-users' => 'Get community users',
    'planning-users' => 'Get planning users',
    'land-users' => 'Get land users',
    'housing-users' => 'Get housing users',
    'renewable-users' => 'Get renewable users',
    'energy-users' => 'Get energy users'
];

// ========== ADDING NEW ROUTES ==========
/*
To add new endpoint in route.php:

case 'new-endpoint':
    require_once 'new-endpoint.php';
    break;
*/

// ========== SPECIFIC DATA REQUESTS ==========

// Get districts and barangays only
/*
fetch('api/route.php?path=barangays')
  .then(response => response.json())
  .then(data => console.log(data));
*/

// Get housing users only
/*
fetch('api/route.php?path=housing-users')
  .then(response => response.json())
  .then(data => console.log(data));
*/

// PHP examples for specific data
/*
$barangays = file_get_contents('https://local-government-unit-1-ph.com/api/route.php?path=barangays');
$barangay_data = json_decode($barangays, true);

$housing_users = file_get_contents('https://local-government-unit-1-ph.com/api/route.php?path=housing-users');
$housing_data = json_decode($housing_users, true);
*/

// ========== RESPONSE FORMAT ==========
// All endpoints return JSON format
// Success: {"success": true, "data": [...]}
// Error: {"success": false, "message": "Error description"}

echo "Route.php usage guide loaded successfully!";
?>