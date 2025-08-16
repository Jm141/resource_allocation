<?php
require_once 'models/HotlineModel.php';

class HotlineController {
    private $hotlineModel;
    
    public function __construct() {
        $this->hotlineModel = new HotlineModel();
    }
    
    /**
     * Handle incoming SMS webhook from Twilio
     */
    public function handleIncomingSMS() {
        // Get POST data from Twilio webhook
        $from = $_POST['From'] ?? '';
        $body = $_POST['Body'] ?? '';
        $messageSid = $_POST['MessageSid'] ?? '';
        
        if (empty($from) || empty($body)) {
            http_response_code(400);
            echo "Missing required parameters";
            return;
        }
        
        // Clean phone number (remove +1 if present)
        $phoneNumber = $this->cleanPhoneNumber($from);
        
        // Determine request type and priority based on message content
        $requestType = $this->classifyRequest($body);
        $priority = $this->determinePriority($body);
        
        // Create hotline request
        $requestId = $this->hotlineModel->createRequest($phoneNumber, $body, $requestType, $priority);
        
        if ($requestId) {
            // Send confirmation SMS back to citizen
            $this->sendConfirmationSMS($phoneNumber, $requestId);
            
            // Log the request
            error_log("Hotline request created: ID $requestId from $phoneNumber");
            
            // Return TwiML response
            $this->sendTwiMLResponse("Thank you for contacting the emergency hotline. Your request has been received and will be processed shortly.");
        } else {
            $this->sendTwiMLResponse("We're sorry, but we couldn't process your request at this time. Please try again later.");
        }
    }
    
    /**
     * Handle incoming voice call webhook from Twilio
     */
    public function handleIncomingCall() {
        $from = $_POST['From'] ?? '';
        $callSid = $_POST['CallSid'] ?? '';
        
        if (empty($from)) {
            http_response_code(400);
            return;
        }
        
        $phoneNumber = $this->cleanPhoneNumber($from);
        
        // Create a voice-based hotline request
        $requestId = $this->hotlineModel->createRequest($phoneNumber, "Voice call received", "voice_call", "high");
        
        // Return TwiML for voice response
        $this->sendVoiceTwiMLResponse($requestId);
    }
    
    /**
     * Get all pending hotline requests (for admin dashboard)
     */
    public function getPendingRequests() {
        $requests = $this->hotlineModel->getPendingRequests();
        return json_encode($requests);
    }
    
    /**
     * Update request status (for admin actions)
     */
    public function updateRequestStatus($requestId, $status, $assignedTo = null, $notes = null) {
        $result = $this->hotlineModel->updateRequestStatus($requestId, $status, $assignedTo, $notes);
        return json_encode(['success' => $result]);
    }
    
    /**
     * Get request details
     */
    public function getRequestDetails($requestId) {
        $request = $this->hotlineModel->getRequestById($requestId);
        return json_encode($request);
    }
    
    /**
     * Clean phone number format
     */
    private function cleanPhoneNumber($phone) {
        // Remove +1 prefix if present
        $phone = preg_replace('/^\+1/', '', $phone);
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return $phone;
    }
    
    /**
     * Classify request type based on message content
     */
    private function classifyRequest($message) {
        $message = strtolower($message);
        
        if (strpos($message, 'fire') !== false || strpos($message, 'smoke') !== false) {
            return 'fire_emergency';
        } elseif (strpos($message, 'medical') !== false || strpos($message, 'ambulance') !== false || strpos($message, 'injury') !== false) {
            return 'medical_emergency';
        } elseif (strpos($message, 'police') !== false || strpos($message, 'crime') !== false || strpos($message, 'theft') !== false) {
            return 'police_emergency';
        } elseif (strpos($message, 'flood') !== false || strpos($message, 'storm') !== false || strpos($message, 'weather') !== false) {
            return 'natural_disaster';
        } else {
            return 'general_emergency';
        }
    }
    
    /**
     * Determine priority based on message content and keywords
     */
    private function determinePriority($message) {
        $message = strtolower($message);
        $urgentKeywords = ['urgent', 'immediate', 'now', 'asap', 'critical', 'emergency', 'help'];
        
        foreach ($urgentKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return 'high';
            }
        }
        
        return 'medium';
    }
    
    /**
     * Send confirmation SMS (placeholder - integrate with your SMS service)
     */
    private function sendConfirmationSMS($phoneNumber, $requestId) {
        // TODO: Integrate with your SMS service (Twilio, etc.)
        // For now, just log it
        error_log("SMS confirmation sent to $phoneNumber for request $requestId");
    }
    
    /**
     * Send TwiML response for SMS
     */
    private function sendTwiMLResponse($message) {
        header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>\n";
        echo "    <Message>$message</Message>\n";
        echo "</Response>";
    }
    
    /**
     * Send TwiML response for voice calls
     */
    private function sendVoiceTwiMLResponse($requestId) {
        header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>\n";
        echo "    <Say>Thank you for calling the emergency hotline. Your call has been received and assigned request number $requestId. An operator will be with you shortly. Please stay on the line.</Say>\n";
        echo "    <Pause length=\"2\"/>\n";
        echo "    <Say>If this is a life-threatening emergency, please hang up and dial 911 immediately.</Say>\n";
        echo "    <Pause length=\"1\"/>\n";
        echo "    <Say>Please continue to hold for the next available operator.</Say>\n";
        echo "</Response>";
    }
}
?> 