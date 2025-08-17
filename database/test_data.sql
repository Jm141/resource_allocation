-- Test Data Population Script for Resource Allocation System
-- This script populates the database with realistic test data for development and testing

-- Clear existing test data (optional - uncomment if needed)
-- DELETE FROM deployments WHERE id > 0;
-- DELETE FROM incidents WHERE id > 0;
-- DELETE FROM facilities WHERE id > 0;

-- Insert test incidents
INSERT INTO incidents (incident_id, title, description, category_id, priority, status, location_name, latitude, longitude, reported_by, created_at) VALUES
('INC-2024-001', 'Fire at Central Market', 'Major fire outbreak at the central market area, multiple stalls affected', 1, 'critical', 'reported', 'Central Market, Bago City', 10.5377, 122.8363, 1, NOW()),
('INC-2024-002', 'Traffic Accident on Highway', 'Multi-vehicle collision on the main highway near the city entrance', 3, 'high', 'assigned', 'Main Highway, Bago City', 10.5450, 122.8300, 1, NOW()),
('INC-2024-003', 'Medical Emergency at Park', 'Person collapsed at Central Park, requires immediate medical attention', 2, 'high', 'in_progress', 'Central Park, Bago City', 10.5400, 122.8400, 1, NOW()),
('INC-2024-004', 'Gas Leak in Residential Area', 'Suspected gas leak in residential neighborhood, potential explosion risk', 1, 'critical', 'reported', 'Residential Area, Bago City', 10.5350, 122.8320, 1, NOW()),
('INC-2024-005', 'Power Outage in Business District', 'Large-scale power outage affecting multiple businesses and traffic lights', 4, 'medium', 'assigned', 'Business District, Bago City', 10.5380, 122.8380, 1, NOW()),
('INC-2024-006', 'Flooding in Low-Lying Areas', 'Heavy rainfall causing flooding in low-lying residential areas', 5, 'high', 'reported', 'Low-Lying Area, Bago City', 10.5420, 122.8340, 1, NOW()),
('INC-2024-007', 'Chemical Spill at Factory', 'Chemical spill at industrial factory, potential environmental hazard', 1, 'critical', 'reported', 'Industrial Factory, Bago City', 10.5390, 122.8370, 1, NOW()),
('INC-2024-008', 'Hostage Situation at Bank', 'Armed suspect holding hostages at local bank branch', 6, 'critical', 'assigned', 'Local Bank, Bago City', 10.5410, 122.8390, 1, NOW()),
('INC-2024-009', 'Building Collapse', 'Partial building collapse in downtown area, multiple people trapped', 1, 'critical', 'in_progress', 'Downtown Area, Bago City', 10.5430, 122.8350, 1, NOW()),
('INC-2024-010', 'Wildfire in Forest Area', 'Wildfire spreading in forest area near the city outskirts', 1, 'high', 'reported', 'Forest Area, Bago City', 10.5440, 122.8410, 1, NOW());

-- Insert test deployments
INSERT INTO deployments (deployment_id, incident_id, driver_id, vehicle_id, start_location, start_lat, start_lng, end_location, end_lat, end_lng, status, created_at) VALUES
('DEP-2024-001', 1, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Central Market, Bago City', 10.5377, 122.8363, 'en_route', NOW()),
('DEP-2024-002', 1, 2, 2, 'Bago City General Hospital', 10.5377, 122.8363, 'Central Market, Bago City', 10.5377, 122.8363, 'on_scene', NOW()),
('DEP-2024-003', 2, 3, 3, 'Bago City Police Station', 10.5400, 122.8400, 'Main Highway, Bago City', 10.5450, 122.8300, 'dispatched', NOW()),
('DEP-2024-004', 3, 4, 4, 'Bago City Medical Center', 10.5450, 122.8300, 'Central Park, Bago City', 10.5400, 122.8400, 'en_route', NOW()),
('DEP-2024-005', 4, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Residential Area, Bago City', 10.5350, 122.8320, 'dispatched', NOW()),
('DEP-2024-006', 5, 5, 5, 'Bago City Emergency Response Center', 10.5390, 122.8370, 'Business District, Bago City', 10.5380, 122.8380, 'en_route', NOW()),
('DEP-2024-007', 6, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Low-Lying Area, Bago City', 10.5420, 122.8340, 'dispatched', NOW()),
('DEP-2024-008', 7, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Industrial Factory, Bago City', 10.5390, 122.8370, 'dispatched', NOW()),
('DEP-2024-009', 8, 3, 3, 'Bago City Police Station', 10.5400, 122.8400, 'Local Bank, Bago City', 10.5410, 122.8390, 'on_scene', NOW()),
('DEP-2024-010', 9, 1, 1, 'Bago City Fire Station', 10.5380, 122.8380, 'Downtown Area, Bago City', 10.5430, 122.8350, 'en_route', NOW());

-- Insert test users (if users table exists)
INSERT INTO users (username, email, password, first_name, last_name, role, created_at) VALUES
('admin', 'admin@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 'admin', NOW()),
('dispatcher1', 'dispatcher1@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Dela Cruz', 'dispatcher', NOW()),
('dispatcher2', 'dispatcher2@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria', 'Santos', 'dispatcher', NOW()),
('driver1', 'driver1@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro', 'Garcia', 'driver', NOW()),
('driver2', 'driver2@bagocity.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'Martinez', 'driver', NOW());

-- Insert test drivers
INSERT INTO drivers (driver_id, user_id, license_number, vehicle_class, status, created_at) VALUES
('DRV-001', 4, 'L123456789', 'A', 'available', NOW()),
('DRV-002', 5, 'L987654321', 'B', 'available', NOW()),
('DRV-003', 3, 'L456789123', 'A', 'available', NOW()),
('DRV-004', 2, 'L789123456', 'C', 'available', NOW()),
('DRV-005', 1, 'L321654987', 'B', 'available', NOW());

-- Insert test vehicles
INSERT INTO vehicles (vehicle_id, vehicle_type, model, year, status, created_at) VALUES
('VH-001', 'fire_truck', 'Fire Engine 2000', 2020, 'available', NOW()),
('VH-002', 'ambulance', 'Ambulance XL', 2021, 'available', NOW()),
('VH-003', 'police_car', 'Police Cruiser', 2019, 'available', NOW()),
('VH-004', 'ambulance', 'Ambulance Standard', 2022, 'available', NOW()),
('VH-005', 'rescue_vehicle', 'Rescue Truck', 2021, 'available', NOW());

-- Insert test incident categories (if not exists)
INSERT INTO incident_categories (name, description, created_at) VALUES
('Fire', 'Fire-related emergencies including building fires, wildfires, and explosions', NOW()),
('Medical', 'Medical emergencies requiring immediate attention', NOW()),
('Traffic', 'Traffic accidents and road-related incidents', NOW()),
('Infrastructure', 'Power outages, water issues, and infrastructure problems', NOW()),
('Natural Disaster', 'Floods, earthquakes, and other natural disasters', NOW()),
('Security', 'Security threats, hostage situations, and criminal activities', NOW());

-- Update incident statuses to match deployments
UPDATE incidents SET status = 'assigned' WHERE id IN (1, 2, 3, 5, 8);
UPDATE incidents SET status = 'in_progress' WHERE id IN (3, 9);

-- Insert test route points for deployments (if route_points table exists)
-- This would be used for tracking actual vehicle movements
INSERT INTO route_points (deployment_id, latitude, longitude, speed, heading, timestamp) VALUES
(1, 10.5380, 122.8380, 0, 0, NOW()),
(1, 10.5378, 122.8370, 45, 180, DATE_ADD(NOW(), INTERVAL 2 MINUTE)),
(1, 10.5377, 122.8363, 0, 0, DATE_ADD(NOW(), INTERVAL 5 MINUTE));

-- Insert test emergency contacts
INSERT INTO emergency_contacts (name, phone, email, role, created_at) VALUES
('Emergency Hotline', '+63-34-123-4567', 'emergency@bagocity.gov.ph', 'Main Emergency Line', NOW()),
('Fire Department', '+63-34-123-4571', 'fire@bagocity.gov.ph', 'Fire Emergency', NOW()),
('Police Department', '+63-34-123-4569', 'police@bagocity.gov.ph', 'Police Emergency', NOW()),
('Medical Emergency', '+63-34-123-4567', 'medical@bagocity.gov.ph', 'Medical Emergency', NOW()),
('Traffic Control', '+63-34-123-4570', 'traffic@bagocity.gov.ph', 'Traffic Management', NOW());

-- Insert test system settings
INSERT INTO system_settings (setting_key, setting_value, description, created_at) VALUES
('emergency_response_timeout', '300', 'Emergency response timeout in seconds', NOW()),
('max_deployments_per_incident', '5', 'Maximum number of deployments per incident', NOW()),
('route_optimization_enabled', 'true', 'Enable OSRM route optimization', NOW()),
('incident_avoidance_radius_critical', '0.5', 'Critical incident avoidance radius in km', NOW()),
('incident_avoidance_radius_high', '0.3', 'High priority incident avoidance radius in km', NOW()),
('osrm_api_url', 'http://router.project-osrm.org/route/v1/driving/', 'OSRM API base URL', NOW());

-- Insert test audit logs
INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, created_at) VALUES
(1, 'CREATE', 'incidents', 1, NULL, '{"title":"Fire at Central Market"}', '127.0.0.1', NOW()),
(1, 'CREATE', 'deployments', 1, NULL, '{"deployment_id":"DEP-2024-001"}', '127.0.0.1', NOW()),
(2, 'UPDATE', 'incidents', 1, '{"status":"reported"}', '{"status":"assigned"}', '127.0.0.1', NOW()),
(3, 'UPDATE', 'deployments', 1, '{"status":"dispatched"}', '{"status":"en_route"}', '127.0.0.1', NOW());

-- Insert test notifications
INSERT INTO notifications (user_id, title, message, type, is_read, created_at) VALUES
(1, 'New Incident Reported', 'Fire at Central Market has been reported', 'incident', 0, NOW()),
(2, 'Deployment Assigned', 'You have been assigned to incident INC-2024-001', 'deployment', 0, NOW()),
(3, 'Route Updated', 'Your route has been optimized to avoid traffic', 'route', 0, NOW()),
(4, 'Status Update', 'Deployment DEP-2024-001 status changed to en_route', 'status', 0, NOW());

-- Insert test reports
INSERT INTO reports (title, description, report_type, generated_by, created_at) VALUES
('Daily Incident Summary', 'Summary of all incidents for today', 'daily', 1, NOW()),
('Response Time Analysis', 'Analysis of emergency response times', 'analytics', 1, NOW()),
('Resource Utilization Report', 'Report on resource utilization and efficiency', 'resource', 1, NOW()),
('Route Optimization Report', 'Report on route optimization and incident avoidance', 'route', 1, NOW());

-- Insert test maintenance records
INSERT INTO maintenance_records (vehicle_id, maintenance_type, description, cost, performed_by, performed_at, created_at) VALUES
(1, 'routine', 'Regular engine maintenance and inspection', 5000.00, 'Maintenance Team', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW()),
(2, 'repair', 'Brake system repair and replacement', 15000.00, 'Maintenance Team', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW()),
(3, 'inspection', 'Annual safety inspection and certification', 3000.00, 'Safety Inspector', DATE_SUB(NOW(), INTERVAL 7 DAY), NOW()),
(4, 'routine', 'Oil change and filter replacement', 2000.00, 'Maintenance Team', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(5, 'repair', 'Hydraulic system repair', 25000.00, 'Maintenance Team', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW());

-- Insert test training records
INSERT INTO training_records (user_id, training_type, description, completion_date, expiry_date, created_at) VALUES
(4, 'emergency_driving', 'Emergency vehicle driving certification', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_ADD(NOW(), INTERVAL 300 DAY), NOW()),
(5, 'first_aid', 'First aid and CPR certification', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_ADD(NOW(), INTERVAL 365 DAY), NOW()),
(3, 'fire_safety', 'Fire safety and prevention training', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_ADD(NOW(), INTERVAL 180 DAY), NOW()),
(2, 'incident_management', 'Incident command system training', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_ADD(NOW(), INTERVAL 365 DAY), NOW()),
(1, 'system_administration', 'System administration and security training', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 365 DAY), NOW());

-- Insert test weather data (if weather table exists)
INSERT INTO weather_data (temperature, humidity, wind_speed, wind_direction, conditions, latitude, longitude, recorded_at, created_at) VALUES
(28.5, 75.0, 15.0, 180, 'partly_cloudy', 10.5377, 122.8363, NOW(), NOW()),
(29.0, 70.0, 12.0, 175, 'sunny', 10.5450, 122.8300, NOW(), NOW()),
(27.5, 80.0, 18.0, 185, 'rainy', 10.5400, 122.8400, NOW(), NOW()),
(28.0, 72.0, 10.0, 170, 'clear', 10.5350, 122.8320, NOW(), NOW()),
(29.5, 68.0, 8.0, 165, 'sunny', 10.5380, 122.8380, NOW(), NOW());

-- Insert test traffic data (if traffic table exists)
INSERT INTO traffic_data (location_name, latitude, longitude, traffic_level, congestion_type, recorded_at, created_at) VALUES
('Central Market Intersection', 10.5377, 122.8363, 'high', 'congested', NOW(), NOW()),
('Main Highway Entrance', 10.5450, 122.8300, 'medium', 'flowing', NOW(), NOW()),
('Business District Center', 10.5380, 122.8380, 'low', 'clear', NOW(), NOW()),
('Residential Area Main Street', 10.5350, 122.8320, 'medium', 'moderate', NOW(), NOW()),
('Industrial Zone Access Road', 10.5390, 122.8370, 'low', 'clear', NOW(), NOW());

-- Update some deployments with more realistic data
UPDATE deployments SET 
    dispatched_at = DATE_SUB(NOW(), INTERVAL 10 MINUTE),
    estimated_arrival = DATE_ADD(NOW(), INTERVAL 5 MINUTE)
WHERE id IN (1, 3, 4, 6, 8);

UPDATE deployments SET 
    dispatched_at = DATE_SUB(NOW(), INTERVAL 15 MINUTE),
    estimated_arrival = DATE_SUB(NOW(), INTERVAL 5 MINUTE)
WHERE id IN (2, 9);

UPDATE deployments SET 
    dispatched_at = DATE_SUB(NOW(), INTERVAL 20 MINUTE),
    estimated_arrival = DATE_SUB(NOW(), INTERVAL 10 MINUTE)
WHERE id IN (5, 7, 10);

-- Insert test notes for deployments
UPDATE deployments SET notes = 'Fire truck dispatched to major fire incident. Route optimized to avoid traffic.' WHERE id = 1;
UPDATE deployments SET notes = 'Ambulance dispatched for medical emergency. Patient requires immediate attention.' WHERE id = 2;
UPDATE deployments SET notes = 'Police unit dispatched for traffic accident. Route clear, no incidents to avoid.' WHERE id = 3;
UPDATE deployments SET notes = 'Medical response team dispatched. Route optimized for fastest response time.' WHERE id = 4;
UPDATE deployments SET notes = 'Fire truck dispatched for gas leak. Route adjusted to avoid residential traffic.' WHERE id = 5;

-- Insert test performance metrics
INSERT INTO performance_metrics (metric_name, metric_value, metric_unit, recorded_at, created_at) VALUES
('average_response_time', 8.5, 'minutes', NOW(), NOW()),
('incident_resolution_rate', 95.5, 'percent', NOW(), NOW()),
('route_optimization_success', 98.2, 'percent', NOW(), NOW()),
('resource_utilization', 87.3, 'percent', NOW(), NOW()),
('system_uptime', 99.9, 'percent', NOW(), NOW());

-- Insert test alerts
INSERT INTO alerts (title, message, alert_type, priority, is_active, created_at) VALUES
('High Incident Volume', 'Multiple critical incidents reported in the last hour', 'warning', 'high', 1, NOW()),
('Route Optimization Alert', 'OSRM routing service experiencing high latency', 'info', 'medium', 1, NOW()),
('Resource Shortage', 'Limited ambulance availability in central area', 'warning', 'high', 1, NOW()),
('Weather Warning', 'Heavy rainfall expected, potential flooding risk', 'warning', 'medium', 1, NOW()),
('System Maintenance', 'Scheduled maintenance in 2 hours', 'info', 'low', 1, NOW());

-- Final verification queries
SELECT 'Test Data Population Complete' as status;
SELECT COUNT(*) as total_incidents FROM incidents;
SELECT COUNT(*) as total_deployments FROM deployments;
SELECT COUNT(*) as total_facilities FROM facilities;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_drivers FROM drivers;
SELECT COUNT(*) as total_vehicles FROM vehicles; 