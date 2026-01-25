<?php
/**
 * =============================================================================
 * LGU FACILITY RESERVATION - API INTEGRATION
 * =============================================================================
 * 
 * Endpoint: https://facilities.local-government-unit-1-ph.com/api/facility-reservation
 * 
 * Copy this file to your project and use the LGUFacilityAPI class to make
 * facility reservations with the LGU system.
 * 
 * =============================================================================
 */

class LGUFacilityAPI {
    
    private $baseUrl = 'https://facilities.local-government-unit-1-ph.com/api/facility-reservation';
    
    /**
     * Make a facility reservation
     */
    public function reserve($data) {
        return $this->post('', $data);
    }
    
    /**
     * Get list of available facilities
     */
    public function getFacilities() {
        return $this->get('/facilities');
    }
    
    /**
     * Get list of available equipment
     */
    public function getEquipment() {
        return $this->get('/equipment');
    }
    
    /**
     * Check if time slot is available
     */
    public function checkAvailability($facilityId, $date, $startTime, $endTime) {
        return $this->get('/check-availability?' . http_build_query([
            'facility_id' => $facilityId,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]));
    }
    
    /**
     * Check booking status
     */
    public function getStatus($bookingReference) {
        return $this->get('/status/' . $bookingReference);
    }
    
    private function post($endpoint, $data) {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    
    private function get($endpoint) {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}

// =============================================================================
// HOW TO USE
// =============================================================================

$api = new LGUFacilityAPI();

// Step 1: Get available facilities (to know which facility_id to use)
$facilities = $api->getFacilities();
echo "Available Facilities:\n";
print_r($facilities);

// Step 2: Check if your desired time slot is available
$available = $api->checkAvailability(
    1,              // facility_id (get from Step 1)
    '2026-02-15',   // date (YYYY-MM-DD)
    '09:00',        // start time (HH:MM, 24-hour)
    '12:00'         // end time (HH:MM, 24-hour)
);
echo "\nAvailability Check:\n";
print_r($available);

// Step 3: Make the reservation
$result = $api->reserve([
    // REQUIRED - Your system name
    'source_system' => 'Your System Name Here',
    
    // REQUIRED - Applicant details
    'applicant_name' => 'Juan Dela Cruz',
    'applicant_email' => 'juan@example.com',
    'applicant_phone' => '09171234567',
    
    // REQUIRED - Booking details
    'facility_id' => 1,              // from Step 1
    'booking_date' => '2026-02-15',  // YYYY-MM-DD
    'start_time' => '09:00',         // HH:MM (24-hour), 8AM-10PM only
    'end_time' => '12:00',           // HH:MM (24-hour)
    'purpose' => 'Team Meeting',
    'expected_attendees' => 50,
    
    // OPTIONAL
    'applicant_address' => '123 Main St, City',
    'event_name' => 'Annual Planning',
    'city_of_residence' => 'Valenzuela',  // for 30% resident discount
    'special_requests' => 'Need projector setup'
]);

echo "\nReservation Result:\n";
print_r($result);

// Step 4: Save the booking_reference to check status later
if ($result['status'] === 'success') {
    $bookingRef = $result['data']['booking_reference'];  // e.g., "BK000123"
    echo "\nBooking Reference: " . $bookingRef . "\n";
    
    // Check status anytime
    $status = $api->getStatus($bookingRef);
    print_r($status);
}

// =============================================================================
// RESPONSE EXAMPLES
// =============================================================================
/*
SUCCESS:
{
    "status": "success",
    "message": "Reservation request received and pending approval.",
    "data": {
        "booking_id": 123,
        "booking_reference": "BK000123",
        "facility_name": "Function Hall A",
        "status": "pending"
    }
}

ERROR:
{
    "status": "error",
    "message": "Validation failed.",
    "errors": { "applicant_name": ["The applicant name field is required."] }
}
*/
