-- Resource Allocation System Database Schema
-- Created for incident management and resource deployment

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS resource_allocation;
USE resource_allocation;

-- Users table for authentication
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'operator', 'driver') NOT NULL DEFAULT 'operator',
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vehicles table
CREATE TABLE vehicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_id VARCHAR(20) UNIQUE NOT NULL,
    vehicle_type ENUM('ambulance', 'fire_truck', 'police_car', 'rescue_vehicle') NOT NULL,
    model VARCHAR(100),
    year INT,
    license_plate VARCHAR(20),
    capacity INT,
    status ENUM('available', 'deployed', 'maintenance', 'out_of_service') DEFAULT 'available',
    current_location_lat DECIMAL(10, 8),
    current_location_lng DECIMAL(11, 8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Drivers table
CREATE TABLE drivers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    driver_id VARCHAR(20) UNIQUE NOT NULL,
    user_id INT,
    license_number VARCHAR(50),
    license_type ENUM('A', 'B', 'C', 'D') NOT NULL,
    experience_years INT DEFAULT 0,
    status ENUM('available', 'deployed', 'off_duty', 'suspended') DEFAULT 'available',
    current_location_lat DECIMAL(10, 8),
    current_location_lng DECIMAL(11, 8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Incident categories
CREATE TABLE incident_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    priority_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    response_time_minutes INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Incidents table
CREATE TABLE incidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    incident_id VARCHAR(20) UNIQUE NOT NULL,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    location_name VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    reported_by INT,
    status ENUM('reported', 'assigned', 'in_progress', 'resolved', 'closed') DEFAULT 'reported',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES incident_categories(id),
    FOREIGN KEY (reported_by) REFERENCES users(id)
);

-- Deployments table
CREATE TABLE deployments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    deployment_id VARCHAR(20) UNIQUE NOT NULL,
    incident_id INT NOT NULL,
    driver_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    start_location VARCHAR(255) NOT NULL,
    start_lat DECIMAL(10, 8),
    start_lng DECIMAL(11, 8),
    end_location VARCHAR(255) NOT NULL,
    end_lat DECIMAL(10, 8),
    end_lng DECIMAL(11, 8),
    status ENUM('dispatched', 'en_route', 'on_scene', 'returning', 'completed') DEFAULT 'dispatched',
    dispatched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estimated_arrival TIMESTAMP NULL,
    actual_arrival TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (incident_id) REFERENCES incidents(id),
    FOREIGN KEY (driver_id) REFERENCES drivers(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

-- Route points for tracking
CREATE TABLE route_points (
    id INT PRIMARY KEY AUTO_INCREMENT,
    deployment_id INT NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    speed DECIMAL(5, 2),
    heading INT,
    FOREIGN KEY (deployment_id) REFERENCES deployments(id) ON DELETE CASCADE
);

-- Deployment resources (multiple vehicles/drivers per incident)
CREATE TABLE deployment_resources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    deployment_id INT NOT NULL,
    resource_type ENUM('vehicle', 'driver', 'equipment') NOT NULL,
    resource_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (deployment_id) REFERENCES deployments(id) ON DELETE CASCADE
);

-- System logs
CREATE TABLE system_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default data
INSERT INTO incident_categories (name, description, priority_level, response_time_minutes) VALUES
('Medical Emergency', 'Medical emergencies requiring immediate response', 'critical', 15),
('Fire Emergency', 'Fire incidents requiring fire department response', 'critical', 10),
('Traffic Accident', 'Road traffic accidents requiring emergency response', 'high', 20),
('Natural Disaster', 'Natural disasters and severe weather events', 'critical', 30),
('Public Safety', 'General public safety concerns', 'medium', 45);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, role, first_name, last_name) VALUES
('admin', 'admin@resourceallocation.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System', 'Administrator');

-- Create indexes for better performance
CREATE INDEX idx_incidents_status ON incidents(status);
CREATE INDEX idx_incidents_priority ON incidents(priority);
CREATE INDEX idx_incidents_location ON incidents(latitude, longitude);
CREATE INDEX idx_deployments_status ON deployments(status);
CREATE INDEX idx_deployments_incident ON deployments(incident_id);
CREATE INDEX idx_vehicles_status ON vehicles(status);
CREATE INDEX idx_drivers_status ON drivers(status);
CREATE INDEX idx_route_points_deployment ON route_points(deployment_id);
CREATE INDEX idx_route_points_timestamp ON route_points(timestamp); 