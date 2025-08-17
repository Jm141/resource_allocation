-- Deployment Queue Table
-- This table stores deployments that cannot be immediately processed due to resource unavailability

CREATE TABLE IF NOT EXISTS deployment_queue (
    id INT PRIMARY KEY AUTO_INCREMENT,
    incident_id INT NOT NULL,
    facilities JSON NOT NULL,
    priority INT DEFAULT 5,
    status ENUM('queued', 'processing', 'completed', 'cancelled') DEFAULT 'queued',
    assigned_resources JSON NULL,
    estimated_wait_time INT NULL, -- in minutes
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    
    INDEX idx_incident (incident_id),
    INDEX idx_priority (priority),
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    
    FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE
);

-- Insert sample queued deployment
INSERT INTO deployment_queue (incident_id, facilities, priority, notes) VALUES
(1, '[
    {
        "id": 1,
        "name": "Bago City Fire Station",
        "facility_type": "fire_station",
        "distance": 2.5,
        "priority": 8
    }
]', 8, 'Fire incident - waiting for available fire trucks'); 