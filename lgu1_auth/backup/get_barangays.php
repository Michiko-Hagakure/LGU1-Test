<?php
// get_barangays.php
require_once __DIR__ . '/../config/db-connection.php';

header('Content-Type: application/json');

if (!isset($_GET['district_id']) || empty($_GET['district_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'District ID is required']);
    exit;
}

$district_id = (int)$_GET['district_id'];

try {
    $stmt = $pdo->prepare('SELECT id, name, alternate_name FROM barangays WHERE district_id = ? ORDER BY name ASC');
    $stmt->execute([$district_id]);
    $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($barangays);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>
