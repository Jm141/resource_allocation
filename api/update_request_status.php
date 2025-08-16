<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Request ID and status are required']);
    exit;
}

require_once '../controllers/HotlineController.php';

$controller = new HotlineController();
$assignedTo = $_POST['assigned_to'] ?? null;
$notes = $_POST['notes'] ?? null;

echo $controller->updateRequestStatus($_POST['request_id'], $_POST['status'], $assignedTo, $notes);
?> 