<?php
/**
 * Test Runner Script for Resource Allocation System
 * Run this script to execute all unit tests
 */

// Set error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering for clean test output
ob_start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Resource Allocation System - Unit Tests</title>
    <style>
        body { font-family: 'Courier New', monospace; margin: 20px; background: #f5f5f5; }
        .test-output { background: #000; color: #00ff00; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .test-results { background: #fff; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .pass { color: #28a745; }
        .fail { color: #dc3545; }
        .summary { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0; }
        h1 { color: #007bff; }
        .status { font-weight: bold; }
    </style>
</head>
<body>
    <h1>🧪 Resource Allocation System - Unit Test Suite</h1>
    <p>Running comprehensive tests for all CRUD operations and system functionality...</p>
    
    <div class='test-output'>
        <pre>";

// Include and run the test suite
require_once 'tests/UnitTestSuite.php';

try {
    $testSuite = new UnitTestSuite();
    $testSuite->runAllTests();
} catch (Exception $e) {
    echo "❌ Test Suite Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

$testOutput = ob_get_contents();
ob_end_clean();

// Extract test results from output
preg_match_all('/✅ PASS|❌ FAIL/', $testOutput, $matches);
$passCount = count(array_filter($matches[0], function($match) { return $match === '✅ PASS'; }));
$failCount = count(array_filter($matches[0], function($match) { return $match === '❌ FAIL'; }));
$totalCount = $passCount + $failCount;
$successRate = $totalCount > 0 ? round(($passCount / $totalCount) * 100, 2) : 0;

echo $testOutput;

echo "</pre>
    </div>

    <div class='test-results'>
        <h2>📊 Test Results Summary</h2>
        <div class='summary'>
            <p><strong>Total Tests:</strong> $totalCount</p>
            <p><strong>Passed:</strong> <span class='pass'>$passCount</span></p>
            <p><strong>Failed:</strong> <span class='fail'>$failCount</span></p>
            <p><strong>Success Rate:</strong> <span class='status'>$successRate%</span></p>
        </div>
        
        <div class='status'>";

if ($failCount === 0 && $totalCount > 0) {
    echo "🎉 <strong>All tests passed! System is ready for production.</strong>";
} elseif ($totalCount > 0) {
    echo "⚠️ <strong>Some tests failed. Please review the issues above.</strong>";
} else {
    echo "❌ <strong>No tests were executed. Please check the test suite.</strong>";
}

echo "
        </div>
    </div>

    <div class='test-results'>
        <h2>🔧 Next Steps</h2>
        <ul>
            <li>Review any failed tests above</li>
            <li>Check database connectivity and permissions</li>
            <li>Verify all required models and controllers exist</li>
            <li>Test OSRM API connectivity</li>
            <li>Run the test data population script</li>
        </ul>
        
        <h3>Quick Actions:</h3>
        <p><a href='database/test_data.sql' target='_blank'>📥 Download Test Data Script</a></p>
        <p><a href='index.php?action=map'>🗺️ Test Live Map</a></p>
        <p><a href='index.php?action=deployments'>🚚 Test Deployments</a></p>
        <p><a href='index.php?action=incidents'>📋 Test Incidents</a></p>
        <p><a href='index.php?action=facilities'>🏥 Test Facilities</a></p>
        <p><a href='index.php?action=admin'>⚙️ Test Admin Panel</a></p>
    </div>

    <script>
        // Auto-scroll to test results
        window.onload = function() {
            document.querySelector('.test-results').scrollIntoView({ behavior: 'smooth' });
        };
    </script>
</body>
</html>";
?> 