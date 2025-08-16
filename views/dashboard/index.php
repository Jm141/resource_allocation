<?php
// Set variables for the layout
$page_title = 'Dashboard - Resource Allocation System';
$action = 'dashboard';

// Get statistics (you can replace these with actual data from your models)
$totalIncidents = 25;
$activeDeployments = 8;
$resolvedIncidents = 17;
$pendingReports = 3;

$recentIncidents = [
    ['id' => 1, 'title' => 'Traffic Accident on Main Street', 'priority' => 'high', 'status' => 'in_progress', 'location' => 'Main Street, Bago City'],
    ['id' => 2, 'title' => 'Medical Emergency at Central Park', 'priority' => 'critical', 'status' => 'assigned', 'location' => 'Central Park, Bago City'],
    ['id' => 3, 'title' => 'Fire Alarm at Shopping Mall', 'priority' => 'critical', 'status' => 'dispatched', 'location' => 'Bago Shopping Mall'],
    ['id' => 4, 'title' => 'Public Safety Concern', 'priority' => 'medium', 'status' => 'reported', 'location' => 'Downtown Area']
];

$recentDeployments = [
    ['id' => 1, 'incident' => 'Traffic Accident on Main Street', 'driver' => 'John Smith', 'vehicle' => 'AMB-001', 'status' => 'en_route'],
    ['id' => 2, 'incident' => 'Medical Emergency at Central Park', 'driver' => 'Maria Garcia', 'vehicle' => 'AMB-002', 'status' => 'on_scene'],
    ['id' => 3, 'incident' => 'Fire Alarm at Shopping Mall', 'driver' => 'Robert Johnson', 'vehicle' => 'FT-001', 'status' => 'dispatched']
];

$content_file = __DIR__ . '/dashboard_content.php';

// Include the main layout
include 'views/layouts/main.php';
?> 