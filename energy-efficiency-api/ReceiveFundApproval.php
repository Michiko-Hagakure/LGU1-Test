<?php
/**
 * Energy Efficiency - Receive Fund Approval API
 * 
 * This file should be deployed to: energy.local-government-unit-1-ph.com/api/ReceiveFundApproval.php
 * 
 * Receives approval/rejection data from Public Facilities system
 * and updates the local my_fund_requests and facility_booking_confirmations tables
 */

// CORS headers for Public Facilities system
header('Access-Control-Allow-Origin: https://facilities.local-government-unit-1-ph.com');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database connection - adjust credentials as needed
$host = 'localhost';
$dbname = 'ener_nova_capri';
$username = 'root'; // Update with actual credentials
$password = '';     // Update with actual credentials

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Required fields
$governmentId = $input['government_id'] ?? null;
$status = $input['status'] ?? null;

if (!$governmentId || !$status) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'government_id and status are required']);
    exit;
}

try {
    $conn->beginTransaction();

    // 1. Update my_fund_requests table
    $updateFundRequest = $conn->prepare("
        UPDATE my_fund_requests 
        SET status = ?, feedback = ?
        WHERE government_id = ?
    ");
    $updateFundRequest->execute([
        $status,
        $input['feedback'] ?? null,
        $governmentId
    ]);

    // 2. If approved, create/update facility_booking_confirmations
    if ($status === 'Approved' && isset($input['seminar_id'])) {
        // Check if confirmation already exists
        $checkStmt = $conn->prepare("SELECT confirmation_id FROM facility_booking_confirmations WHERE seminar_id = ?");
        $checkStmt->execute([$input['seminar_id']]);
        $existingConfirmation = $checkStmt->fetch();

        $trackingId = $input['tracking_id'] ?? ('GPR-' . date('Y') . '-' . str_pad($governmentId, 6, '0', STR_PAD_LEFT));
        
        if ($existingConfirmation) {
            // Update existing confirmation
            $updateStmt = $conn->prepare("
                UPDATE facility_booking_confirmations SET
                    public_facilities_tracking_id = ?,
                    request_status = 'confirmed',
                    assigned_facility_id = ?,
                    assigned_facility_name = ?,
                    assigned_facility_address = ?,
                    assigned_facility_capacity = ?,
                    confirmed_date = ?,
                    confirmed_start_time = ?,
                    confirmed_end_time = ?,
                    equipment_provided = ?,
                    requested_amount = ?,
                    approved_amount = ?,
                    fund_approval_status = 'approved',
                    coordination_notes = ?,
                    confirmed_at = NOW(),
                    updated_at = NOW()
                WHERE seminar_id = ?
            ");
            $updateStmt->execute([
                $trackingId,
                $input['facility_id'] ?? null,
                $input['facility_name'] ?? null,
                $input['facility_address'] ?? null,
                $input['facility_capacity'] ?? null,
                $input['scheduled_date'] ?? null,
                $input['scheduled_time'] ?? null,
                $input['end_time'] ?? null,
                isset($input['equipment']) ? json_encode($input['equipment']) : null,
                $input['requested_amount'] ?? null,
                $input['approved_amount'] ?? null,
                $input['admin_notes'] ?? 'Facility assigned by LGU Public Facilities System',
                $input['seminar_id']
            ]);
        } else {
            // Insert new confirmation
            $insertStmt = $conn->prepare("
                INSERT INTO facility_booking_confirmations (
                    seminar_id, public_facilities_tracking_id, request_status,
                    assigned_facility_id, assigned_facility_name, assigned_facility_address,
                    assigned_facility_capacity, confirmed_date, confirmed_start_time,
                    confirmed_end_time, equipment_provided, requested_amount,
                    approved_amount, fund_approval_status, coordination_notes,
                    admin_contact_name, received_at, confirmed_at, created_at, updated_at
                ) VALUES (?, ?, 'confirmed', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', ?, ?, NOW(), NOW(), NOW(), NOW())
            ");
            $insertStmt->execute([
                $input['seminar_id'],
                $trackingId,
                $input['facility_id'] ?? null,
                $input['facility_name'] ?? null,
                $input['facility_address'] ?? null,
                $input['facility_capacity'] ?? null,
                $input['scheduled_date'] ?? null,
                $input['scheduled_time'] ?? null,
                $input['end_time'] ?? null,
                isset($input['equipment']) ? json_encode($input['equipment']) : null,
                $input['requested_amount'] ?? null,
                $input['approved_amount'] ?? null,
                $input['admin_notes'] ?? 'Facility assigned by LGU Public Facilities System',
                $input['approved_by'] ?? 'LGU Admin'
            ]);
        }
    }

    // 3. If rejected, update facility_booking_confirmations if exists
    if ($status === 'Rejected' && isset($input['seminar_id'])) {
        $rejectStmt = $conn->prepare("
            UPDATE facility_booking_confirmations 
            SET request_status = 'rejected', 
                fund_approval_status = 'rejected',
                coordination_notes = ?,
                updated_at = NOW()
            WHERE seminar_id = ?
        ");
        $rejectStmt->execute([
            $input['feedback'] ?? 'Request rejected by LGU',
            $input['seminar_id']
        ]);
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Fund request ' . strtolower($status) . ' successfully',
        'government_id' => $governmentId
    ]);

} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to process approval: ' . $e->getMessage()
    ]);
}
?>
