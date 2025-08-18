<?php
require_once __DIR__ . '/../config/database.php';

class Driver {
    private $conn;
    private $table = 'drivers';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($limit = null) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  ORDER BY d.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByStatus($status) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.status = ? 
                  ORDER BY d.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailable() {
        return $this->getByStatus('available');
    }

    public function getDeployed() {
        return $this->getByStatus('deployed');
    }

    public function getOffDuty() {
        return $this->getByStatus('off_duty');
    }

    public function getSuspended() {
        return $this->getByStatus('suspended');
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (driver_id, user_id, license_number, license_type, experience_years, status, 
                   current_location_lat, current_location_lng, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            $data['driver_id'],
            $data['user_id'] ?? null,
            $data['license_number'] ?? null,
            $data['license_type'] ?? 'B',
            $data['experience_years'] ?? 0,
            $data['status'] ?? 'available',
            $data['current_location_lat'] ?? null,
            $data['current_location_lng'] ?? null
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        $fields[] = "updated_at = NOW()";
        $values[] = $id;
        
        $query = "UPDATE " . $this->table . " SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute($values);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$status, $id]);
    }

    /**
     * Mark driver as deployed (in use)
     */
    public function markAsDeployed($id) {
        return $this->updateStatus($id, 'deployed');
    }

    /**
     * Mark driver as available (not in use)
     */
    public function markAsAvailable($id) {
        return $this->updateStatus($id, 'available');
    }

    /**
     * Mark driver as off duty
     */
    public function markAsOffDuty($id) {
        return $this->updateStatus($id, 'off_duty');
    }

    /**
     * Mark driver as suspended
     */
    public function markAsSuspended($id) {
        return $this->updateStatus($id, 'suspended');
    }

    /**
     * Update driver's current location
     */
    public function updateLocation($id, $latitude, $longitude) {
        $query = "UPDATE " . $this->table . " 
                  SET current_location_lat = ?, current_location_lng = ?, updated_at = NOW() 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$latitude, $longitude, $id]);
    }

    public function getCurrentLocation($id) {
        $query = "SELECT current_location_lat, current_location_lng, updated_at 
                  FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNearbyDrivers($latitude, $longitude, $radius = 5.0) {
        $query = "SELECT d.*, u.first_name, u.last_name,
                         (6371 * acos(cos(radians(?)) * cos(radians(current_location_lat)) * 
                          cos(radians(current_location_lng) - radians(?)) + 
                          sin(radians(?)) * sin(radians(current_location_lat)))) AS distance
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.status = 'available' 
                    AND d.current_location_lat IS NOT NULL 
                    AND d.current_location_lng IS NOT NULL
                  HAVING distance <= ?
                  ORDER BY distance";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$latitude, $longitude, $latitude, $radius]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get driver's current deployment status
     */
    public function getDeploymentStatus($id) {
        $query = "SELECT d.status as driver_status, d.current_location_lat, d.current_location_lng,
                         dep.status as deployment_status, dep.id as deployment_id,
                         i.title as incident_title, i.location_name as incident_location
                  FROM " . $this->table . " d
                  LEFT JOIN deployments dep ON d.id = dep.driver_id AND dep.status IN ('dispatched', 'en_route', 'on_scene')
                  LEFT JOIN incidents i ON dep.incident_id = i.id
                  WHERE d.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if driver is currently available for deployment
     */
    public function isAvailable($id) {
        $driver = $this->getById($id);
        return $driver && $driver['status'] === 'available';
    }

    /**
     * Get drivers by license type
     */
    public function getByLicenseType($licenseType) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.license_type = ? AND d.status = 'available'
                  ORDER BY d.experience_years DESC, d.created_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$licenseType]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get drivers by experience level
     */
    public function getByExperienceLevel($minYears) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.experience_years >= ? AND d.status = 'available'
                  ORDER BY d.experience_years DESC, d.created_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$minYears]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$id]);
    }

    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }

    public function getCountByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE status = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }

    public function getStatusDistribution() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($term) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone 
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR d.driver_id LIKE ? 
                        OR d.license_number LIKE ?
                  ORDER BY d.created_at DESC";
        
        $searchTerm = "%$term%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get drivers with their current deployment information
     */
    public function getAllWithDeploymentInfo() {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone,
                         dep.id as deployment_id, dep.status as deployment_status,
                         i.title as incident_title, i.location_name as incident_location
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  LEFT JOIN deployments dep ON d.id = dep.driver_id AND dep.status IN ('dispatched', 'en_route', 'on_scene')
                  LEFT JOIN incidents i ON dep.incident_id = i.id
                  ORDER BY d.status, d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get driver's deployment history
     */
    public function getDeploymentHistory($id, $limit = 10) {
        $query = "SELECT dep.*, i.title as incident_title, i.location_name as incident_location,
                         i.priority as incident_priority, i.category_name as incident_category
                  FROM deployments dep
                  LEFT JOIN incidents i ON dep.incident_id = i.id
                  WHERE dep.driver_id = ?
                  ORDER BY dep.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get driver's current active deployment
     */
    public function getCurrentDeployment($id) {
        $query = "SELECT dep.*, i.title as incident_title, i.location_name as incident_location,
                         i.priority as incident_priority, i.category_name as incident_category
                  FROM deployments dep
                  LEFT JOIN incidents i ON dep.incident_id = i.id
                  WHERE dep.driver_id = ? AND dep.status IN ('dispatched', 'en_route', 'on_scene', 'returning')
                  ORDER BY dep.created_at DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get driver statistics
     */
    public function getDriverStats($id) {
        $query = "SELECT 
                    COUNT(*) as total_deployments,
                    COUNT(CASE WHEN dep.status = 'completed' THEN 1 END) as completed_deployments,
                    COUNT(CASE WHEN dep.status IN ('dispatched', 'en_route', 'on_scene', 'returning') THEN 1 END) as active_deployments,
                    AVG(CASE WHEN dep.status = 'completed' THEN TIMESTAMPDIFF(MINUTE, dep.dispatched_at, dep.completed_at) END) as avg_response_time
                  FROM deployments dep
                  WHERE dep.driver_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all drivers with current deployment and availability status
     */
    public function getAllWithCurrentStatus() {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone,
                         dep.id as current_deployment_id, dep.status as deployment_status,
                         dep.incident_id, i.title as incident_title, i.location_name as incident_location,
                         i.priority as incident_priority
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  LEFT JOIN deployments dep ON d.id = dep.driver_id AND dep.status IN ('dispatched', 'en_route', 'on_scene', 'returning')
                  LEFT JOIN incidents i ON dep.incident_id = i.id
                  ORDER BY 
                    CASE d.status 
                        WHEN 'deployed' THEN 1
                        WHEN 'available' THEN 2
                        WHEN 'off_duty' THEN 3
                        WHEN 'suspended' THEN 4
                        ELSE 5
                    END,
                    d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get drivers by status with user information
     */
    public function getByStatusWithUserInfo($status) {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone
                  FROM " . $this->table . " d
                  LEFT JOIN users u ON d.user_id = u.id
                  WHERE d.status = ? 
                  ORDER BY u.first_name, u.last_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 