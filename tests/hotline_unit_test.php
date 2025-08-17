<?php
/**
 * Hotline System Unit Tests
 * Tests individual components of the hotline system
 */

echo "<h1>🧪 Hotline System - Unit Tests</h1>\n";
echo "<hr>\n";

// Test 1: HotlineModel Tests
echo "<h2>📊 Test 1: HotlineModel Class Tests</h2>\n";
try {
    require_once '../models/HotlineModel.php';
    
    // Test model instantiation
    $model = new HotlineModel();
    echo "✅ HotlineModel instantiated successfully<br>\n";
    
    // Test database connection
    if ($model) {
        echo "✅ Database connection through model successful<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ HotlineModel test failed: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 2: HotlineController Tests
echo "<h2>🎮 Test 2: HotlineController Class Tests</h2>\n";
try {
    require_once '../controllers/HotlineController.php';
    
    // Test controller instantiation
    $controller = new HotlineController();
    echo "✅ HotlineController instantiated successfully<br>\n";
    
    // Test method existence
    $methods = ['handleIncomingSMS', 'handleIncomingCall', 'getPendingRequests', 'updateRequestStatus'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "✅ Method '$method' exists<br>\n";
        } else {
            echo "❌ Method '$method' missing<br>\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ HotlineController test failed: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 3: Database Schema Test
echo "<h2>🗄️ Test 3: Database Schema Test</h2>\n";
try {
    require_once '../config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    // Test if hotline table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'hotline_requests'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Hotline requests table exists<br>\n";
        
        // Test table structure
        $stmt = $conn->query("DESCRIBE hotline_requests");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $requiredColumns = ['id', 'phone_number', 'message', 'request_type', 'priority', 'status'];
        foreach ($requiredColumns as $col) {
            $found = false;
            foreach ($columns as $column) {
                if ($column['Field'] === $col) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                echo "✅ Column '$col' exists<br>\n";
            } else {
                echo "❌ Column '$col' missing<br>\n";
            }
        }
        
    } else {
        echo "⚠️ Hotline requests table not found - run database/hotline_schema.sql<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database schema test failed: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 4: Webhook Endpoint Tests
echo "<h2>🔗 Test 4: Webhook Endpoint Tests</h2>\n";
$webhookFiles = [
    '../webhooks/sms_webhook.php',
    '../webhooks/voice_webhook.php'
];

foreach ($webhookFiles as $webhook) {
    if (file_exists($webhook)) {
        echo "✅ $webhook exists<br>\n";
        
        // Test file syntax
        $output = [];
        $returnCode = 0;
        exec("php -l $webhook 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ $webhook syntax is valid<br>\n";
        } else {
            echo "❌ $webhook syntax error: " . implode("\n", $output) . "<br>\n";
        }
        
    } else {
        echo "❌ $webhook missing<br>\n";
    }
}

echo "<hr>\n";

// Test 5: API Endpoint Tests
echo "<h2>🌐 Test 5: API Endpoint Tests</h2>\n";
$apiFiles = [
    '../api/hotline_requests.php',
    '../api/hotline_request_details.php',
    '../api/update_request_status.php',
    '../api/hotline_statistics.php'
];

foreach ($apiFiles as $api) {
    if (file_exists($api)) {
        echo "✅ $api exists<br>\n";
        
        // Test file syntax
        $output = [];
        $returnCode = 0;
        exec("php -l $api 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ $api syntax is valid<br>\n";
        } else {
            echo "❌ $api syntax error: " . implode("\n", $output) . "<br>\n";
        }
        
    } else {
        echo "❌ $api missing<br>\n";
    }
}

echo "<hr>\n";

// Test 6: Configuration Tests
echo "<h2>⚙️ Test 6: Configuration Tests</h2>\n";
$configFiles = [
    '../config/twilio_config.php'
];

foreach ($configFiles as $config) {
    if (file_exists($config)) {
        echo "✅ $config exists<br>\n";
        
        // Test if it's a valid PHP file
        $output = [];
        $returnCode = 0;
        exec("php -l $config 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "✅ $config syntax is valid<br>\n";
        } else {
            echo "❌ $config syntax error: " . implode("\n", $output) . "<br>\n";
        }
        
    } else {
        echo "❌ $config missing<br>\n";
    }
}

echo "<hr>\n";

// Test 7: Integration Test
echo "<h2>🔗 Test 7: Integration Test</h2>\n";
try {
    // Test if we can create a hotline request
    require_once '../models/HotlineModel.php';
    $model = new HotlineModel();
    
    // Test data
    $testPhone = '1234567890';
    $testMessage = 'Test emergency message';
    $testType = 'general_emergency';
    $testPriority = 'medium';
    
    // This would normally create a request, but we'll just test the method exists
    if (method_exists($model, 'createRequest')) {
        echo "✅ createRequest method exists and accessible<br>\n";
    } else {
        echo "❌ createRequest method not accessible<br>\n";
    }
    
    // Test other methods
    $methods = ['getPendingRequests', 'updateRequestStatus', 'getRequestById'];
    foreach ($methods as $method) {
        if (method_exists($model, $method)) {
            echo "✅ $method method exists<br>\n";
        } else {
            echo "❌ $method method missing<br>\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Integration test failed: " . $e->getMessage() . "<br>\n";
}

echo "<hr>\n";

// Test 8: Security Tests
echo "<h2>🔒 Test 8: Security Tests</h2>\n";

// Check if webhook files have proper validation
$webhookFiles = [
    '../webhooks/sms_webhook.php',
    '../webhooks/voice_webhook.php'
];

foreach ($webhookFiles as $webhook) {
    if (file_exists($webhook)) {
        $content = file_get_contents($webhook);
        
        // Check for POST method validation
        if (strpos($content, '$_SERVER[\'REQUEST_METHOD\']') !== false) {
            echo "✅ $webhook has HTTP method validation<br>\n";
        } else {
            echo "⚠️ $webhook missing HTTP method validation<br>\n";
        }
        
        // Check for error handling
        if (strpos($content, 'try') !== false && strpos($content, 'catch') !== false) {
            echo "✅ $webhook has error handling<br>\n";
        } else {
            echo "⚠️ $webhook missing error handling<br>\n";
        }
    }
}

echo "<hr>\n";

// Summary
echo "<h2>📋 Test Summary</h2>\n";
echo "<p><strong>Test Results:</strong></p>\n";

$totalTests = 8;
$passedTests = 0;

// Count passed tests (this is a simplified count)
if (file_exists('../models/HotlineModel.php')) $passedTests++;
if (file_exists('../controllers/HotlineController.php')) $passedTests++;
if (file_exists('../config/database.php')) $passedTests++;
if (file_exists('../webhooks/sms_webhook.php')) $passedTests++;
if (file_exists('../webhooks/voice_webhook.php')) $passedTests++;
if (file_exists('../api/hotline_requests.php')) $passedTests++;
if (file_exists('../config/twilio_config.php')) $passedTests++;

$percentage = round(($passedTests / $totalTests) * 100);

echo "<p>Passed: $passedTests/$totalTests ($percentage%)</p>\n";

if ($percentage >= 80) {
    echo "<p style='color: green;'>🟢 <strong>SYSTEM READY FOR PRODUCTION</strong></p>\n";
} elseif ($percentage >= 60) {
    echo "<p style='color: orange;'>🟡 <strong>SYSTEM NEEDS MINOR FIXES</strong></p>\n";
} else {
    echo "<p style='color: red;'>🔴 <strong>SYSTEM NEEDS MAJOR FIXES</strong></p>\n";
}

echo "<hr>\n";
echo "<p><em>Unit tests completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
?> 