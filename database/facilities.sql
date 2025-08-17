-- Facilities table for emergency response locations
CREATE TABLE IF NOT EXISTS facilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    facility_type ENUM('hospital', 'police_station', 'fire_station', 'emergency_center', 'command_center') NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    contact_number VARCHAR(20),
    capacity INT DEFAULT 0,
    available_resources TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_facility_type (facility_type),
    INDEX idx_location (latitude, longitude),
    INDEX idx_active (is_active)
);

-- Insert default emergency facilities for Bago City
INSERT INTO facilities (name, facility_type, address, latitude, longitude, contact_number, capacity, available_resources) VALUES
-- Hospitals
('Bago City General Hospital', 'hospital', 'Bago City, Negros Occidental', 10.5377, 122.8363, '+63-34-123-4567', 100, 'Emergency Room, ICU, Operating Rooms, Ambulances'),
('Bago City Medical Center', 'hospital', 'Bago City, Negros Occidental', 10.5450, 122.8300, '+63-34-123-4568', 50, 'Emergency Room, Outpatient Services, Ambulances'),

-- Police Stations
('Bago City Police Station', 'police_station', 'Bago City, Negros Occidental', 10.5400, 122.8400, '+63-34-123-4569', 50, 'Patrol Cars, SWAT Team, Investigation Units'),
('Bago City Police Substation', 'police_station', 'Bago City, Negros Occidental', 10.5350, 122.8320, '+63-34-123-4570', 25, 'Patrol Cars, Community Police'),

-- Fire Stations
('Bago City Fire Station', 'fire_station', 'Bago City, Negros Occidental', 10.5380, 122.8380, '+63-34-123-4571', 30, 'Fire Trucks, Rescue Equipment, Hazmat Units'),
('Bago City Fire Substation', 'fire_station', 'Bago City, Negros Occidental', 10.5420, 122.8340, '+63-34-123-4572', 20, 'Fire Trucks, Basic Rescue Equipment'),

-- Emergency Response Center
('Bago City Emergency Response Center', 'emergency_center', 'Bago City, Negros Occidental', 10.5390, 122.8370, '+63-34-123-4573', 100, 'Coordination Center, Emergency Vehicles, Communication Systems'),

-- Command & Control Center
('Bago City Command Center', 'command_center', 'Bago City, Negros Occidental', 10.5410, 122.8390, '+63-34-123-4574', 50, 'Central Command, Communication Hub, Emergency Planning');

-- Create indexes for better performance
CREATE INDEX idx_facility_type_active ON facilities(facility_type, is_active);
CREATE INDEX idx_location_active ON facilities(latitude, longitude, is_active); 