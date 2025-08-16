# Emergency Hotline System Setup Guide

This guide will help you set up a complete emergency hotline system that integrates with your resource allocation system.

## 🚀 System Overview

The hotline system provides:
- **SMS Hotline**: Citizens can text emergency messages
- **Voice Hotline**: Citizens can call and leave voice messages
- **Auto-Classification**: System automatically categorizes emergency types
- **Priority Assignment**: Urgent keywords trigger high-priority alerts
- **Admin Dashboard**: Real-time management of all requests
- **Integration**: Seamlessly connects with your existing resource allocation system

## 📋 Prerequisites

1. **XAMPP/WAMP** (already installed)
2. **Twilio Account** (free tier available)
3. **Public Domain** (for webhook endpoints)
4. **SSL Certificate** (required for Twilio webhooks)

## 🔧 Step 1: Database Setup

1. **Create the database table:**
   ```sql
   -- Run the SQL from database/hotline_schema.sql
   -- This creates the hotline_requests table and views
   ```

2. **Verify the table was created:**
   ```sql
   SHOW TABLES LIKE 'hotline_requests';
   ```

## 📱 Step 2: Twilio Setup

### 2.1 Create Twilio Account
1. Go to [https://www.twilio.com](https://www.twilio.com)
2. Sign up for a free account
3. Verify your phone number
4. Get your Account SID and Auth Token from the console

### 2.2 Purchase a Phone Number
1. In Twilio Console, go to "Phone Numbers" → "Manage" → "Buy a number"
2. Choose a local number (this will be your hotline number)
3. Note down the phone number

### 2.3 Configure Webhooks
1. Go to your phone number settings
2. Set SMS webhook URL: `https://yourdomain.com/webhooks/sms_webhook.php`
3. Set Voice webhook URL: `https://yourdomain.com/webhooks/voice_webhook.php`
4. Set HTTP method to POST for both

## ⚙️ Step 3: Configuration

### 3.1 Update Twilio Config
Edit `config/twilio_config.php`:
```php
define('TWILIO_ACCOUNT_SID', 'your_actual_account_sid');
define('TWILIO_AUTH_TOKEN', 'your_actual_auth_token');
define('TWILIO_PHONE_NUMBER', '+1234567890'); // Your Twilio number
```

### 3.2 Update Webhook URLs
```php
define('SMS_WEBHOOK_URL', 'https://yourdomain.com/webhooks/sms_webhook.php');
define('VOICE_WEBHOOK_URL', 'https://yourdomain.com/webhooks/voice_webhook.php');
```

## 🌐 Step 4: Webhook Setup

### 4.1 Make Webhooks Publicly Accessible
Your webhook endpoints must be accessible from the internet:
- Use a service like ngrok for testing
- Deploy to a server with public IP for production
- Ensure HTTPS is enabled

### 4.2 Test Webhooks
1. Send a test SMS to your Twilio number
2. Check your webhook logs
3. Verify the request appears in your database

## 🎯 Step 5: Integration with Resource Allocation

### 5.1 Link Hotline Requests to Resources
The system automatically creates hotline requests that can be:
- Assigned to specific emergency responders
- Linked to resource deployments
- Tracked through resolution

### 5.2 Customize Request Types
Edit the classification logic in `HotlineController.php`:
```php
private function classifyRequest($message) {
    // Add your custom emergency types
    // Link to your resource allocation categories
}
```

## 📊 Step 6: Admin Dashboard

### 6.1 Access the Dashboard
Navigate to: `views/hotline_dashboard.php`

### 6.2 Dashboard Features
- Real-time request monitoring
- Priority-based sorting
- Status updates
- Assignment management
- Statistics and reporting

## 🔒 Step 7: Security & Production

### 7.1 Enable Twilio Signature Verification
```php
define('VERIFY_TWILIO_SIGNATURE', true);
```

### 7.2 IP Restriction (Optional)
```php
define('ALLOWED_IP_RANGES', [
    '54.240.0.0/13',  // Twilio IP ranges
    '54.236.0.0/15'
]);
```

### 7.3 Rate Limiting
```php
define('MAX_REQUESTS_PER_MINUTE', 10);
define('MAX_REQUESTS_PER_HOUR', 100);
```

## 🧪 Testing the System

### Test SMS Flow
1. Send SMS to your Twilio number: "Fire emergency at 123 Main St"
2. Check webhook logs
3. Verify request appears in dashboard
4. Check auto-classification (should be "fire_emergency" with "high" priority)

### Test Voice Flow
1. Call your Twilio number
2. Verify voice message is recorded
3. Check request appears in dashboard
3. Verify status updates correctly

## 📈 Monitoring & Maintenance

### 7.1 Log Files
- Check `logs/twilio_requests.log` for webhook activity
- Monitor error logs for issues

### 7.2 Database Maintenance
- Regularly backup the `hotline_requests` table
- Archive old resolved requests
- Monitor table size and performance

### 7.3 Twilio Console
- Monitor usage and costs
- Check webhook delivery status
- Review phone number settings

## 🚨 Emergency Procedures

### 7.1 System Failure
If the hotline system fails:
1. Check webhook endpoints are accessible
2. Verify Twilio configuration
3. Check database connectivity
4. Review error logs

### 7.2 High Volume
During emergencies:
1. Monitor request volume
2. Adjust rate limits if needed
3. Scale resources as required
4. Provide backup contact methods

## 💰 Cost Considerations

### Twilio Pricing (US)
- **SMS**: $0.0079 per message
- **Voice**: $0.0085 per minute
- **Phone Number**: $1.00 per month

### Estimated Monthly Costs
- 100 SMS messages: $0.79
- 50 voice calls (2 min each): $0.85
- Phone number: $1.00
- **Total**: ~$2.64/month for low volume

## 🔗 Integration Examples

### Link to Resource Allocation
```php
// In your IncidentController.php
public function createFromHotline($hotlineRequestId) {
    $hotlineModel = new HotlineModel();
    $request = $hotlineModel->getRequestById($hotlineRequestId);
    
    // Create incident from hotline request
    $incident = $this->createIncident([
        'type' => $request['request_type'],
        'priority' => $request['priority'],
        'description' => $request['message'],
        'source' => 'hotline'
    ]);
    
    // Update hotline request status
    $hotlineModel->updateRequestStatus($hotlineRequestId, 'linked_to_incident');
    
    return $incident;
}
```

## 📞 Support & Troubleshooting

### Common Issues
1. **Webhook not receiving requests**
   - Check URL accessibility
   - Verify HTTPS is enabled
   - Check firewall settings

2. **Database connection errors**
   - Verify database credentials
   - Check table exists
   - Test connection manually

3. **Twilio authentication errors**
   - Verify Account SID and Auth Token
   - Check account status
   - Verify phone number ownership

### Getting Help
- Check Twilio documentation
- Review error logs
- Test with simple webhook first
- Use Twilio's webhook testing tools

## 🎉 Success Metrics

Your hotline system is working when:
- ✅ SMS messages are received and stored
- ✅ Voice calls are processed
- ✅ Requests appear in admin dashboard
- ✅ Priority classification works correctly
- ✅ Status updates function properly
- ✅ Integration with resource allocation works

---

**Next Steps:**
1. Complete the database setup
2. Configure Twilio
3. Test with a simple SMS
4. Verify dashboard functionality
5. Integrate with your existing system

Need help? Check the logs and verify each step before moving to the next! 