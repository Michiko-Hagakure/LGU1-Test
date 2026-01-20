# LGU1 API Route Guide

## Base URL
```
https://local-government-unit-1-ph.com/api/route.php
```

## Available Endpoints

### 1. All Users
```
GET /api/route.php?path=users
```

### 2. Subsystem Users

#### Infrastructure Project Management
```
GET /api/route.php?path=infrastructure-users
```

#### Utility Billing and Monitoring
```
GET /api/route.php?path=utility-users
```

#### Road and Transportation Infrastructure
```
GET /api/route.php?path=transportation-users
```

#### Public Facilities Reservation
```
GET /api/route.php?path=facilities-users
```

#### Community Infrastructure Maintenance
```
GET /api/route.php?path=community-users
```

#### Urban Planning and Development
```
GET /api/route.php?path=planning-users
```

#### Land Registration and Titling
```
GET /api/route.php?path=land-users
```

#### Housing and Resettlement Management
```
GET /api/route.php?path=housing-users
```

#### Renewable Energy Project Management
```
GET /api/route.php?path=renewable-users
```

#### Energy Efficiency and Conservation
```
GET /api/route.php?path=energy-users
```

### 3. Other Endpoints

#### Barangays by District
```
GET /api/route.php?path=barangays&district_id=1
```

#### Subsystem Roles
```
POST /api/route.php?path=subsystem-roles
Content-Type: application/x-www-form-urlencoded
subsystem_id=8
```

## Response Format
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "username": "user1",
      "email": "user@example.com",
      "full_name": "User Name",
      "subsystem_role_name": "Admin",
      "status": "active",
      "created_at": "2025-01-01 12:00:00"
    }
  ]
}
```

## PHP Usage Examples

### Basic Request
```php
<?php
$url = 'https://local-government-unit-1-ph.com/api/route.php?path=housing-users';
$response = file_get_contents($url);
$data = json_decode($response, true);

foreach ($data['data'] as $user) {
    echo $user['full_name'] . "\n";
}
?>
```

### Using cURL
```php
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://local-government-unit-1-ph.com/api/route.php?path=users');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
?>
```

### JavaScript/Fetch
```javascript
fetch('https://local-government-unit-1-ph.com/api/route.php?path=housing-users')
  .then(response => response.json())
  .then(data => console.log(data.data));
```

## Error Handling
```json
{
  "success": false,
  "message": "Endpoint not found"
}
```