-- Hotline Requests Table Schema
-- This table stores all incoming hotline requests from citizens

CREATE TABLE IF NOT EXISTS `hotline_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `request_type` enum('fire_emergency','medical_emergency','police_emergency','natural_disaster','general_emergency','voice_call') NOT NULL DEFAULT 'general_emergency',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('pending','assigned','in_progress','resolved','closed') NOT NULL DEFAULT 'pending',
  `assigned_to` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resolved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_phone_number` (`phone_number`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_request_type` (`request_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Create a view for active requests
CREATE OR REPLACE VIEW `active_hotline_requests` AS
SELECT 
    hr.*,
    CASE 
        WHEN hr.status = 'pending' THEN 1
        WHEN hr.status = 'assigned' THEN 2
        WHEN hr.status = 'in_progress' THEN 3
        ELSE 4
    END as sort_order
FROM `hotline_requests` hr
WHERE hr.status IN ('pending', 'assigned', 'in_progress')
ORDER BY hr.priority DESC, sort_order ASC, hr.created_at ASC;

-- Optional: Create a view for request statistics
CREATE OR REPLACE VIEW `hotline_statistics` AS
SELECT 
    COUNT(*) as total_requests,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_requests,
    SUM(CASE WHEN priority = 'high' OR priority = 'critical' THEN 1 ELSE 0 END) as urgent_requests,
    AVG(CASE WHEN status = 'resolved' THEN TIMESTAMPDIFF(MINUTE, created_at, resolved_at) END) as avg_resolution_time_minutes
FROM `hotline_requests`; 