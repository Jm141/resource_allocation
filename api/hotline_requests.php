<?php
header('Content-Type: application/json');
require_once '../controllers/HotlineController.php';

$controller = new HotlineController();
echo $controller->getPendingRequests();
?> 