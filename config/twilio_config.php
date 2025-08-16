<?php
// Twilio Configuration
// Get your credentials from https://www.twilio.com/console

// Twilio Account Credentials
define('TWILIO_ACCOUNT_SID', 'your_account_sid_here');
define('TWILIO_AUTH_TOKEN', 'your_auth_token_here');

// Your Twilio Phone Number (the hotline number)
define('TWILIO_PHONE_NUMBER', '+1234567890'); // Replace with your actual Twilio number

// Webhook URLs (update these with your actual domain)
define('SMS_WEBHOOK_URL', 'https://yourdomain.com/webhooks/sms_webhook.php');
define('VOICE_WEBHOOK_URL', 'https://yourdomain.com/webhooks/voice_webhook.php');

// Optional: Enable/disable features
define('ENABLE_SMS', true);
define('ENABLE_VOICE', true);
define('ENABLE_SMS_CONFIRMATIONS', true);

// SMS Templates
define('SMS_CONFIRMATION_TEMPLATE', 'Thank you for contacting the emergency hotline. Your request #{request_id} has been received and will be processed shortly.');
define('SMS_STATUS_UPDATE_TEMPLATE', 'Your emergency request #{request_id} status has been updated to: {status}');

// Voice Call Settings
define('VOICE_WELCOME_MESSAGE', 'Thank you for calling the emergency hotline. Your call has been received and will be processed shortly.');
define('VOICE_HOLD_MESSAGE', 'Please continue to hold for the next available operator.');
define('VOICE_EMERGENCY_MESSAGE', 'If this is a life-threatening emergency, please hang up and dial 911 immediately.');

// Security Settings
define('VERIFY_TWILIO_SIGNATURE', true); // Recommended for production
define('ALLOWED_IP_RANGES', []); // Optional: restrict to Twilio IP ranges

// Logging
define('LOG_TWILIO_REQUESTS', true);
define('LOG_FILE_PATH', '../logs/twilio_requests.log');

// Rate Limiting
define('MAX_REQUESTS_PER_MINUTE', 10);
define('MAX_REQUESTS_PER_HOUR', 100);

// Emergency Keywords for Auto-Priority
define('URGENT_KEYWORDS', [
    'urgent', 'immediate', 'now', 'asap', 'critical', 
    'emergency', 'help', 'fire', 'smoke', 'injury', 
    'bleeding', 'unconscious', 'heart attack', 'stroke'
]);

// Request Type Classification Keywords
define('REQUEST_TYPE_KEYWORDS', [
    'fire_emergency' => ['fire', 'smoke', 'burning', 'explosion'],
    'medical_emergency' => ['medical', 'ambulance', 'injury', 'bleeding', 'unconscious', 'heart', 'stroke'],
    'police_emergency' => ['police', 'crime', 'theft', 'robbery', 'assault', 'suspicious'],
    'natural_disaster' => ['flood', 'storm', 'tornado', 'hurricane', 'earthquake', 'tsunami']
]);
?> 