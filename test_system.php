<?php
/**
 * System Integrity Test Script
 * This script tests all major components of the Resource Allocation System
 */

echo "<h1>🔍 Resource Allocation System - Integrity Test</h1>\n";
echo "<hr>\n";

// Test 1: Database Connection
echo "<h2>📊 Test 1: Database Connection</h2>\n";
try {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "✅ Database connection successful<br>\n";
        
        // Test if hotline table exists
        $stmt = $conn->query("SHOW TABLES LIKE 'hotline_requests'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Hotline requests table exists<br>\n";
        } else {
            echo "⚠️ Hotline requests table not found - run database/hotline_schema.sql<br>\n";
        }
        
    } else {
        echo "❌ Database connection failed<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 2: File Structure
echo "<h2>📁 Test 2: File Structure</h2>\n";
$requiredFiles = [
    'views/layouts/main.php',
    'views/dashboard/index.php',
    'views/dashboard/dashboard_content.php',
    'views/incidents/index.php',
    'views/incidents/incidents_content.php',
    'views/incidents/create.php',
    'views/incidents/create_content.php',
    'views/deployments/index.php',
    'views/deployments/deployments_content.php',
    'views/deployments/create.php',
    'views/deployments/create_content.php',
    'views/map/index.php',
    'views/map/map_content.php',
    'views/hotline_dashboard.php',
    'controllers/HotlineController.php',
    'models/HotlineModel.php',
    'webhooks/sms_webhook.php',
    'webhooks/voice_webhook.php',
    'api/hotline_requests.php',
    'api/hotline_request_details.php',
    'api/update_request_status.php',
    'api/hotline_statistics.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>\n";
    } else {
        echo "❌ $file missing<br>\n";
    }
}

echo "<hr>\n";

// Test 3: Controller Classes
echo "<h2>🎮 Test 3: Controller Classes</h2>\n";
try {
    require_once 'controllers/HotlineController.php';
    $hotlineController = new HotlineController();
    echo "✅ HotlineController loaded successfully<br>\n";
    
    require_once 'models/HotlineModel.php';
    $hotlineModel = new HotlineModel();
    echo "✅ HotlineModel loaded successfully<br>\n";
    
} catch (Exception $e) {
    echo "❌ Controller error: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 4: Webhook Endpoints
echo "<h2>🔗 Test 4: Webhook Endpoints</h2>\n";
$webhookUrls = [
    'webhooks/sms_webhook.php',
    'webhooks/voice_webhook.php'
];

foreach ($webhookUrls as $webhook) {
    if (file_exists($webhook)) {
        echo "✅ $webhook exists<br>\n";
    } else {
        echo "❌ $webhook missing<br>\n";
    }
}

echo "<hr>\n";

// Test 5: API Endpoints
echo "<h2>🌐 Test 5: API Endpoints</h2>\n";
$apiEndpoints = [
    'api/hotline_requests.php',
    'api/hotline_request_details.php',
    'api/update_request_status.php',
    'api/hotline_statistics.php'
];

foreach ($apiEndpoints as $api) {
    if (file_exists($api)) {
        echo "✅ $api exists<br>\n";
    } else {
        echo "❌ $api missing<br>\n";
    }
}

echo "<hr>\n";

// Test 6: Configuration Files
echo "<h2>⚙️ Test 6: Configuration Files</h2>\n";
$configFiles = [
    'config/database.php',
    'config/twilio_config.php'
];

foreach ($configFiles as $config) {
    if (file_exists($config)) {
        echo "✅ $config exists<br>\n";
    } else {
        echo "❌ $config missing<br>\n";
    }
}

echo "<hr>\n";

// Test 7: Page Routing Test
echo "<h2>🛣️ Test 7: Page Routing Test</h2>\n";
$testPages = [
    'index.php' => 'Dashboard',
    'index.php?action=incidents' => 'Incidents',
    'index.php?action=deployments' => 'Deployments',
    'index.php?action=map' => 'Map',
    'index.php?action=report' => 'Report Incident',
    'index.php?action=hotline' => 'Hotline Dashboard'
];

foreach ($testPages as $url => $name) {
    echo "🔗 <a href='$url' target='_blank'>Test $name</a><br>\n";
}

echo "<hr>\n";

// Test 8: Hotline Integration Test
echo "<h2>📞 Test 8: Hotline Integration Test</h2>\n";
echo "🔗 <a href='views/hotline_dashboard.php' target='_blank'>Test Hotline Dashboard</a><br>\n";
echo "🔗 <a href='webhooks/sms_webhook.php' target='_blank'>Test SMS Webhook</a><br>\n";
echo "🔗 <a href='webhooks/voice_webhook.php' target='_blank'>Test Voice Webhook</a><br>\n";

echo "<hr>\n";

// Test 9: Database Schema Test
echo "<h2>🗄️ Test 9: Database Schema Test</h2>\n";
if (file_exists('database/hotline_schema.sql')) {
    echo "✅ Hotline schema file exists<br>\n";
    echo "📋 <a href='database/hotline_schema.sql' target='_blank'>View Schema</a><br>\n";
} else {
    echo "❌ Hotline schema file missing<br>\n";
}

echo "<hr>\n";

// Test 10: Setup Documentation
echo "<h2>📚 Test 10: Setup Documentation</h2>\n";
if (file_exists('HOTLINE_SETUP.md')) {
    echo "✅ Setup documentation exists<br>\n";
    echo "📖 <a href='HOTLINE_SETUP.md' target='_blank'>View Setup Guide</a><br>\n";
} else {
    echo "❌ Setup documentation missing<br>\n";
}

echo "<hr>\n";

// Summary
echo "<h2>📋 Test Summary</h2>\n";
echo "<p><strong>Next Steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Run the database schema: <code>database/hotline_schema.sql</code></li>\n";
echo "<li>Configure Twilio credentials in <code>config/twilio_config.php</code></li>\n";
echo "<li>Test all pages by clicking the links above</li>\n";
echo "<li>Verify hotline dashboard functionality</li>\n";
echo "<li>Test webhook endpoints with Twilio</li>\n";
echo "</ol>\n";

echo "<p><strong>System Status:</strong> ";
if (file_exists('config/database.php') && file_exists('views/layouts/main.php')) {
    echo "🟢 <strong>READY FOR TESTING</strong></p>\n";
} else {
    echo "🔴 <strong>SYSTEM INCOMPLETE</strong></p>\n";
}

echo "<hr>\n";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
?> 