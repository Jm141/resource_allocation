<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Get statistics from the view
    $sql = "SELECT * FROM hotline_statistics";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stats = $stmt->fetch();
    
    echo json_encode($stats);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load statistics']);
}
?> 