# Registration API Documentation

## Overview
The Registration API automatically creates user accounts and syncs them to specific subsystem databases based on registration type.

## Endpoint
```
POST /api/register
```

## Request Format
```json
{
    "username": "string (required)",
    "email": "string (required, valid email)",
    "full_name": "string (required)",
    "password": "string (required)",
    "registration_type": "string (required)",
    "birthdate": "string (optional, YYYY-MM-DD)",
    "mobile_number": "string (optional)",
    "gender": "string (optional)",
    "civil_status": "string (optional)",
    "nationality": "string (optional, default: Filipino)",
    "district_id": "integer (optional)",
    "barangay_id": "integer (optional)",
    "current_address": "string (optional)",
    "zip_code": "string (optional)"
}
```

## Registration Types
- `applicant` - Housing & Resettlement programs
- `utility_customer` - Water & electricity billing
- `facility_user` - Public facilities reservation
- `resident` - Community infrastructure maintenance

## Response Format
```json
{
    "success": true,
    "data": {
        "user_id": 123,
        "subsystem_sync": [
            {
                "endpoint": "http://localhost/LGU1-Applicant-HousingAndResettlement/api/sync-user.php",
                "success": true,
                "message": "User created in subsystem database"
            }
        ]
    },
    "message": "User registered successfully",
    "timestamp": "2024-01-01 12:00:00"
}
```

## Automatic Subsystem Sync
When a user registers, their data is automatically sent to relevant subsystem databases:

### Applicant Registration
- Syncs to: Housing & Resettlement Management systems
- Endpoints:
  - `https://qcitizen-management.local-government-unit-1-ph.com/api/sync-user.php`
  - `https://housing.local-government-unit-1-ph.com/api/sync-user.php`

### Utility Customer Registration
- Syncs to: Utility Management system
- Endpoint: `https://billing.local-government-unit-1-ph.com/api/sync-user.php`

### Facility User Registration
- Syncs to: Public Facilities Management system
- Endpoint: `https://facilities.local-government-unit-1-ph.com/api/sync-user.php`

### Resident Registration
- Syncs to: Community Infrastructure Management system
- Endpoint: `https://community.local-government-unit-1-ph.com/api/sync-user.php`

## Subsystem Implementation
Each subsystem should implement a `/api/sync-user.php` endpoint that accepts user data and creates/updates local user records. See `api/subsystem-sync-template.php` for implementation template.

## Error Responses
```json
{
    "success": false,
    "data": null,
    "message": "Error description",
    "timestamp": "2024-01-01 12:00:00"
}
```

## Usage Example
```javascript
fetch('/api/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        username: 'john_doe',
        email: 'john@example.com',
        full_name: 'John Doe',
        password: 'secure123',
        registration_type: 'applicant',
        mobile_number: '09123456789',
        current_address: '123 Main St, Barangay 1'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```