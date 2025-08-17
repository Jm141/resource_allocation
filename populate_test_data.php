<?php
/**
 * Test Data Population Script for Resource Allocation System
 * This script populates the database with realistic test data
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Resource Allocation System - Test Data Population</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .section { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #e9ecef; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007bff; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🏗️ Resource Allocation System - Test Data Population</h1>
            <p>Populating database with comprehensive test data for development and testing</p>
        </div>";

try {
    // Initialize database connection
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }
    
    echo "<div class='section'>
        <h2>🔌 Database Connection</h2>
        <p class='success'>✅ Database connected successfully</p>
    </div>";
    
    // Check if test data already exists
    $stmt = $conn->query("SELECT COUNT(*) as count FROM incidents");
    $incidentCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM deployments");
    $deploymentCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM facilities");
    $facilityCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "<div class='section'>
        <h2>📊 Current Database Status</h2>
        <div class='stats'>
            <div class='stat-card'>
                <div class='stat-number'>$incidentCount</div>
                <div>Incidents</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>$deploymentCount</div>
                <div>Deployments</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>$facilityCount</div>
                <div>Facilities</div>
            </div>
        </div>
    </div>";
    
    // Action buttons
    echo "<div class='section'>
        <h2>🎯 Actions</h2>";
    
    if ($incidentCount > 0 || $deploymentCount > 0 || $facilityCount > 0) {
        echo "<p class='warning'>⚠️ Test data already exists in the database.</p>
        <button class='btn btn-danger' onclick='clearTestData()'>🗑️ Clear All Test Data</button>
        <button class='btn btn-success' onclick='addMoreTestData()'>➕ Add More Test Data</button>";
    } else {
        echo "<p class='info'>ℹ️ No test data found. Ready to populate database.</p>
        <button class='btn btn-success' onclick='populateTestData()'>🚀 Populate Database</button>";
    }
    
    echo "</div>";
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'populate':
                    populateTestData($conn);
                    break;
                case 'clear':
                    clearTestData($conn);
                    break;
                case 'add_more':
                    addMoreTestData($conn);
                    break;
            }
        }
    }
    
    // Display test data if exists
    if ($incidentCount > 0) {
        displayTestData($conn);
    }
    
} catch (Exception $e) {
    echo "<div class='section'>
        <h2>❌ Error</h2>
        <p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>
        <p>Please check your database configuration and try again.</p>
    </div>";
}

echo "
    </div>
    
    <script>
        function populateTestData() {
            if (confirm('Are you sure you want to populate the database with test data?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type=\"hidden\" name=\"action\" value=\"populate\">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function clearTestData() {
            if (confirm('⚠️ This will DELETE ALL test data from the database. Are you sure?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type=\"hidden\" name=\"action\" value=\"clear\">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function addMoreTestData() {
            if (confirm('Add more test data to existing records?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type=\"hidden\" name=\"action\" value=\"add_more\">';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>";

/**
 * Populate database with test data
 */
function populateTestData($conn) {
    echo "<div class='section'>
        <h2>🚀 Populating Database with Test Data</h2>";
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Insert test incidents
        insertTestIncidents($conn);
        
        // Insert test deployments
        insertTestDeployments($conn);
        
        // Insert test facilities
        insertTestFacilities($conn);
        
        // Insert test users and drivers
        insertTestUsers($conn);
        
        // Insert test vehicles
        insertTestVehicles($conn);
        
        // Insert test categories
        insertTestCategories($conn);
        
        // Commit transaction
        $conn->commit();
        
        echo "<p class='success'>✅ Database populated successfully!</p>
        <p>Test data has been added to all tables.</p>";
        
        // Refresh page to show new data
        echo "<script>setTimeout(() => location.reload(), 2000);</script>";
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p class='error'>❌ Error populating database: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

/**
 * Clear all test data
 */
function clearTestData($conn) {
    echo "<div class='section'>
        <h2>🗑️ Clearing Test Data</h2>";
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Clear tables in reverse dependency order
        $tables = ['deployments', 'incidents', 'facilities', 'vehicles', 'drivers', 'users'];
        
        foreach ($tables as $table) {
            $stmt = $conn->prepare("DELETE FROM $table WHERE id > 0");
            $stmt->execute();
            $count = $stmt->rowCount();
            echo "<p>🗑️ Cleared $count records from $table</p>";
        }
        
        // Commit transaction
        $conn->commit();
        
        echo "<p class='success'>✅ All test data cleared successfully!</p>";
        
        // Refresh page
        echo "<script>setTimeout(() => location.reload(), 2000);</script>";
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p class='error'>❌ Error clearing data: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

/**
 * Add more test data
 */
function addMoreTestData($conn) {
    echo "<div class='section'>
        <h2>➕ Adding More Test Data</h2>";
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Add more incidents
        insertAdditionalIncidents($conn);
        
        // Add more deployments
        insertAdditionalDeployments($conn);
        
        // Commit transaction
        $conn->commit();
        
        echo "<p class='success'>✅ Additional test data added successfully!</p>";
        
        // Refresh page
        echo "<script>setTimeout(() => location.reload(), 2000);</script>";
        
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p class='error'>❌ Error adding data: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

/**
 * Display existing test data
 */
function displayTestData($conn) {
    echo "<div class='section'>
        <h2>📋 Current Test Data</h2>";
    
    // Display incidents
    $stmt = $conn->query("SELECT * FROM incidents ORDER BY created_at DESC LIMIT 5");
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($incidents)) {
        echo "<h3>🚨 Recent Incidents</h3>
        <div style='overflow-x: auto;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr style='background: #f8f9fa;'>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>ID</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Title</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Priority</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Status</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Location</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($incidents as $incident) {
            $priorityColor = getPriorityColor($incident['priority']);
            $statusColor = getStatusColor($incident['status']);
            
            echo "<tr>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$incident['incident_id']}</td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$incident['title']}</td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'><span style='color: $priorityColor; font-weight: bold;'>{$incident['priority']}</span></td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'><span style='color: $statusColor; font-weight: bold;'>{$incident['status']}</span></td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$incident['location_name']}</td>
            </tr>";
        }
        
        echo "</tbody></table>
        </div>";
    }
    
    // Display deployments
    $stmt = $conn->query("SELECT * FROM deployments ORDER BY created_at DESC LIMIT 5");
    $deployments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($deployments)) {
        echo "<h3>🚚 Recent Deployments</h3>
        <div style='overflow-x: auto;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr style='background: #f8f9fa;'>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>ID</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Incident</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Status</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>Start</th>
                        <th style='padding: 8px; border: 1px solid #dee2e6;'>End</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($deployments as $deployment) {
            $statusColor = getStatusColor($deployment['status']);
            
            echo "<tr>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$deployment['deployment_id']}</td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$deployment['incident_id']}</td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'><span style='color: $statusColor; font-weight: bold;'>{$deployment['status']}</span></td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$deployment['start_location']}</td>
                <td style='padding: 8px; border: 1px solid #dee2e6;'>{$deployment['end_location']}</td>
            </tr>";
        }
        
        echo "</tbody></table>
        </div>";
    }
    
    echo "</div>";
}

/**
 * Helper methods for inserting test data
 */
function insertTestIncidents($conn) {
    $incidents = [
        ['INC-2024-001', 'Fire at Central Market', 'Major fire outbreak at the central market area', 1, 'critical', 'reported', 'Central Market, Bago City', 10.5377, 122.8363],
        ['INC-2024-002', 'Traffic Accident on Highway', 'Multi-vehicle collision on the main highway', 3, 'high', 'assigned', 'Main Highway, Bago City', 10.5450, 122.8300],
        ['INC-2024-003', 'Medical Emergency at Park', 'Person collapsed at Central Park', 2, 'high', 'in_progress', 'Central Park, Bago City', 10.5400, 122.8400]
    ];
    
    $stmt = $conn->prepare("INSERT INTO incidents (incident_id, title, description, category_id, priority, status, location_name, latitude, longitude, reported_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
    
    foreach ($incidents as $incident) {
        $stmt->execute($incident);
    }
    
    echo "<p>✅ Added " . count($incidents) . " test incidents</p>";
}

function insertTestDeployments($conn) {
    $deployments = [
        ['DEP-2024-001', 1, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Central Market, Bago City', 10.5377, 122.8363, 'en_route'],
        ['DEP-2024-002', 1, 2, 2, 'Bago City General Hospital', 10.5377, 122.8363, 'Central Market, Bago City', 10.5377, 122.8363, 'on_scene'],
        ['DEP-2024-003', 2, 3, 3, 'Bago City Police Station', 10.5400, 122.8400, 'Main Highway, Bago City', 10.5450, 122.8300, 'dispatched']
    ];
    
    $stmt = $conn->prepare("INSERT INTO deployments (deployment_id, incident_id, driver_id, vehicle_id, start_location, start_lat, start_lng, end_location, end_lat, end_lng, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($deployments as $deployment) {
        $stmt->execute($deployment);
    }
    
    echo "<p>✅ Added " . count($deployments) . " test deployments</p>";
}

function insertTestFacilities($conn) {
    $facilities = [
        ['Bago City General Hospital', 'hospital', 'Bago City, Negros Occidental', 10.5377, 122.8363, '+63-34-123-4567', 100, 'Emergency Room, ICU, Operating Rooms, Ambulances'],
        ['Bago City Police Station', 'police_station', 'Bago City, Negros Occidental', 10.5400, 122.8400, '+63-34-123-4569', 50, 'Patrol Cars, SWAT Team, Investigation Unit'],
        ['Bago City Fire Station', 'fire_station', 'Bago City, Negros Occidental', 10.5380, 122.8380, '+63-34-123-4571', 75, 'Fire Trucks, Rescue Equipment, Hazmat Team']
    ];
    
    $stmt = $conn->prepare("INSERT INTO facilities (name, facility_type, address, latitude, longitude, contact_number, capacity, available_resources, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($facilities as $facility) {
        $stmt->execute($facility);
    }
    
    echo "<p>✅ Added " . count($facilities) . " test facilities</p>";
}

function insertTestUsers($conn) {
    $users = [
        ['admin', 'admin@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 'admin'],
        ['dispatcher1', 'dispatcher1@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Dela Cruz', 'dispatcher'],
        ['driver1', 'driver1@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro', 'Garcia', 'driver']
    ];
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    
    echo "<p>✅ Added " . count($users) . " test users</p>";
}

function insertTestVehicles($conn) {
    $vehicles = [
        ['VH-001', 'fire_truck', 'Fire Engine 2000', 2020, 'available'],
        ['VH-002', 'ambulance', 'Ambulance XL', 2021, 'available'],
        ['VH-003', 'police_car', 'Police Cruiser', 2019, 'available']
    ];
    
    $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_id, vehicle_type, model, year, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    foreach ($vehicles as $vehicle) {
        $stmt->execute($vehicle);
    }
    
    echo "<p>✅ Added " . count($vehicles) . " test vehicles</p>";
}

function insertTestCategories($conn) {
    $categories = [
        ['Fire', 'Fire-related emergencies including building fires, wildfires, and explosions'],
        ['Medical', 'Medical emergencies requiring immediate attention'],
        ['Traffic', 'Traffic accidents and road-related incidents']
    ];
    
    $stmt = $conn->prepare("INSERT INTO incident_categories (name, description, created_at) VALUES (?, ?, NOW())");
    
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    echo "<p>✅ Added " . count($categories) . " test categories</p>";
}

function insertAdditionalIncidents($conn) {
    $incidents = [
        ['INC-2024-011', 'Power Outage in Business District', 'Large-scale power outage affecting multiple businesses', 4, 'medium', 'reported', 'Business District, Bago City', 10.5380, 122.8380],
        ['INC-2024-012', 'Flooding in Low-Lying Areas', 'Heavy rainfall causing flooding in residential areas', 5, 'high', 'reported', 'Low-Lying Area, Bago City', 10.5420, 122.8340]
    ];
    
    $stmt = $conn->prepare("INSERT INTO incidents (incident_id, title, description, category_id, priority, status, location_name, latitude, longitude, reported_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
    
    foreach ($incidents as $incident) {
        $stmt->execute($incident);
    }
    
    echo "<p>✅ Added " . count($incidents) . " additional incidents</p>";
}

function insertAdditionalDeployments($conn) {
    $deployments = [
        ['DEP-2024-011', 11, 1, 1, 'Bago City Emergency Response Center', 10.5390, 122.8370, 'Business District, Bago City', 10.5380, 122.8380, 'dispatched'],
        ['DEP-2024-012', 12, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Low-Lying Area, Bago City', 10.5420, 122.8340, 'dispatched']
    ];
    
    $stmt = $conn->prepare("INSERT INTO deployments (deployment_id, incident_id, driver_id, vehicle_id, start_location, start_lat, start_lng, end_location, end_lat, end_lng, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($deployments as $deployment) {
        $stmt->execute($deployment);
    }
    
    echo "<p>✅ Added " . count($deployments) . " additional deployments</p>";
}

function getPriorityColor($priority) {
    $colors = [
        'critical' => '#dc3545',
        'high' => '#fd7e14',
        'medium' => '#ffc107',
        'low' => '#28a745'
    ];
    return $colors[$priority] ?? '#6c757d';
}

function getStatusColor($status) {
    $colors = [
        'reported' => '#17a2b8',
        'assigned' => '#28a745',
        'in_progress' => '#ffc107',
        'resolved' => '#28a745',
        'closed' => '#6c757d',
        'dispatched' => '#007bff',
        'en_route' => '#28a745',
        'on_scene' => '#ffc107',
        'returning' => '#6c757d',
        'completed' => '#28a745'
    ];
    return $colors[$status] ?? '#6c757d';
}
?> 