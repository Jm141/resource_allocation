<?php
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Request ID is required']);
    exit;
}

require_once '../controllers/HotlineController.php';

$controller = new HotlineController();
echo $controller->getRequestDetails($_GET['id']);
?> 