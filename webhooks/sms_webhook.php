<?php
// SMS Webhook Endpoint for Twilio
// This file handles incoming SMS messages to your hotline number

// Include the hotline controller
require_once '../controllers/HotlineController.php';

// Verify this is a POST request from Twilio
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

// Optional: Verify the request is from Twilio (recommended for production)
// You can add Twilio signature verification here for security

try {
    // Initialize the hotline controller
    $hotlineController = new HotlineController();
    
    // Handle the incoming SMS
    $hotlineController->handleIncomingSMS();
    
} catch (Exception $e) {
    // Log the error
    error_log("Error in SMS webhook: " . $e->getMessage());
    
    // Return a simple error response
    http_response_code(500);
    echo "Internal server error";
}
?> 