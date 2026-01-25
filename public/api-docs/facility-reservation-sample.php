<?php
/**
 * =============================================================================
 * FACILITY RESERVATION API - INTEGRATION SAMPLE CODE
 * =============================================================================
 * 
 * API Endpoint: https://facilities.local-government-unit-1-ph.com/api/facility-reservation
 * 
 * This is sample PHP code for external systems to integrate with the
 * LGU Facility Reservation System. Copy and modify this code for your system.
 * 
 * =============================================================================
 */

// =============================================================================
// CONFIGURATION
// =============================================================================

define('FACILITY_API_URL', 'https://facilities.local-government-unit-1-ph.com/api/facility-reservation');

// =============================================================================
// FUNCTION: Submit Facility Reservation
// =============================================================================

/**
 * Submit a facility reservation request to the LGU system
 * 
 * @param array $reservationData The reservation details
 * @return array Response from the API
 */
function submitFacilityReservation($reservationData) {
    $url = FACILITY_API_URL;
    
    // Prepare the JSON payload
    $jsonData = json_encode($reservationData);
    
    // Initialize cURL
    $ch = curl_init($url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    // Handle errors
    if ($error) {
        return [
            'success' => false,
            'message' => 'cURL Error: ' . $error,
            'http_code' => 0
        ];
    }
    
    // Parse response
    $result = json_decode($response, true);
    $result['http_code'] = $httpCode;
    
    return $result;
}

// =============================================================================
// FUNCTION: Check Facility Availability
// =============================================================================

/**
 * Check if a time slot is available for booking
 * 
 * @param int $facilityId The facility ID
 * @param string $date Date in YYYY-MM-DD format
 * @param string $startTime Start time in HH:MM format (24-hour)
 * @param string $endTime End time in HH:MM format (24-hour)
 * @return array Response from the API
 */
function checkFacilityAvailability($facilityId, $date, $startTime, $endTime) {
    $url = FACILITY_API_URL . '/check-availability?' . http_build_query([
        'facility_id' => $facilityId,
        'booking_date' => $date,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =============================================================================
// FUNCTION: Get Available Facilities
// =============================================================================

/**
 * Get list of all available facilities
 * 
 * @return array List of facilities
 */
function getAvailableFacilities() {
    $url = FACILITY_API_URL . '/facilities';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =============================================================================
// FUNCTION: Get Available Equipment
// =============================================================================

/**
 * Get list of all available equipment for rent
 * 
 * @return array List of equipment
 */
function getAvailableEquipment() {
    $url = FACILITY_API_URL . '/equipment';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =============================================================================
// FUNCTION: Check Booking Status
// =============================================================================

/**
 * Check the status of a booking by reference number
 * 
 * @param string $bookingReference The booking reference (e.g., BK000123)
 * @return array Booking status details
 */
function checkBookingStatus($bookingReference) {
    $url = FACILITY_API_URL . '/status/' . $bookingReference;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =============================================================================
// EXAMPLE USAGE
// =============================================================================

// Example: Submit a reservation
$reservationData = [
    // Required: Identify your system
    'source_system' => 'Your System Name',
    'external_reference_id' => 'YOUR-REF-123', // Your internal reference (optional)
    
    // Required: Applicant Information
    'applicant_name' => 'Juan Dela Cruz',
    'applicant_email' => 'juan@example.com',
    'applicant_phone' => '09171234567',
    'applicant_address' => '123 Main Street, Valenzuela City', // Optional
    
    // Required: Facility and Timing
    'facility_id' => 1,                    // Get from getAvailableFacilities()
    'booking_date' => '2026-02-15',        // YYYY-MM-DD format
    'start_time' => '09:00',               // HH:MM 24-hour format
    'end_time' => '12:00',                 // HH:MM 24-hour format
    
    // Required: Event Details
    'purpose' => 'Company Team Building Activity',
    'expected_attendees' => 50,
    
    // Optional: Additional Details
    'event_name' => 'Annual Team Building',
    'event_description' => 'Annual team building activity for employees',
    'city_of_residence' => 'Valenzuela',   // For resident discount
    'special_discount_type' => null,       // 'senior', 'pwd', or 'student'
    'special_requests' => 'Need wheelchair access',
    
    // Optional: Equipment (get IDs from getAvailableEquipment())
    'equipment' => [
        ['equipment_id' => 1, 'quantity' => 2],
        ['equipment_id' => 3, 'quantity' => 1]
    ]
];

// Uncomment below to test:
// $result = submitFacilityReservation($reservationData);
// print_r($result);

// =============================================================================
// RESPONSE EXAMPLES
// =============================================================================

/*
SUCCESS RESPONSE (HTTP 201):
{
    "status": "success",
    "message": "Reservation request received and pending approval.",
    "data": {
        "booking_id": 123,
        "booking_reference": "BK000123",
        "facility_name": "Function Hall A",
        "booking_date": "2026-02-15",
        "start_time": "09:00 AM",
        "end_time": "12:00 PM",
        "status": "pending",
        "pricing": {
            "base_rate": "5,000.00",
            "extension_rate": "0.00",
            "equipment_total": "500.00",
            "subtotal": "5,500.00",
            "resident_discount": "1,650.00",
            "special_discount": "0.00",
            "total_amount": "3,850.00"
        }
    }
}

VALIDATION ERROR RESPONSE (HTTP 422):
{
    "status": "error",
    "message": "Validation failed.",
    "errors": {
        "applicant_name": ["The applicant name field is required."],
        "facility_id": ["The selected facility id is invalid."]
    }
}

CONFLICT ERROR RESPONSE (HTTP 409):
{
    "status": "error",
    "message": "Time slot is not available. Please choose a different date/time."
}

SERVER ERROR RESPONSE (HTTP 500):
{
    "status": "error",
    "message": "An error occurred while processing your request."
}
*/

// =============================================================================
// OPERATING HOURS & RULES
// =============================================================================

/*
IMPORTANT NOTES:
1. Operating hours: 8:00 AM to 10:00 PM only
2. Minimum booking: 3 hours
3. 2-hour buffer between bookings for cleanup
4. Bookings are created with "pending" status and require staff approval
5. Resident discount: 30% off for city residents
6. Special discounts: 20% off for Senior/PWD/Student (with valid ID)
*/

?>
