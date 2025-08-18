<?php
require_once __DIR__ . '/../config/database.php';

class Deployment {
    private $conn;
    private $table = 'deployments';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($limit = null, $offset = null) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  ORDER BY d.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . $limit;
            if ($offset) {
                $query .= " OFFSET " . $offset;
            }
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (deployment_id, incident_id, driver_id, vehicle_id, start_location, start_lat, start_lng, 
                   end_location, end_lat, end_lng, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $deployment_id = 'DEP' . date('Ymd') . rand(1000, 9999);
        
        return $stmt->execute([
            $deployment_id,
            $data['incident_id'],
            $data['driver_id'],
            $data['vehicle_id'],
            $data['start_location'],
            $data['start_lat'],
            $data['start_lng'],
            $data['end_location'],
            $data['end_lat'],
            $data['end_lng'],
            'dispatched'
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET start_location = ?, start_lat = ?, start_lng = ?, 
                      end_location = ?, end_lat = ?, end_lng = ?, 
                      status = ?, notes = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['start_location'],
            $data['start_lat'],
            $data['start_lng'],
            $data['end_location'],
            $data['end_lat'],
            $data['end_lng'],
            $data['status'],
            $data['notes'],
            $id
        ]);
    }

    public function updateStatus($id, $status) {
        try {
            $query = "UPDATE " . $this->table . " SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$status, $id]);
            
            // Add debugging
            error_log("Deployment::updateStatus - ID: $id, Status: $status, Result: " . ($result ? 'true' : 'false'));
            if (!$result) {
                error_log("Deployment::updateStatus - PDO Error: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Deployment::updateStatus - Exception: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getByStatus($status) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.status = ?
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function getByIncident($incident_id) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.incident_id = ?
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$incident_id]);
        return $stmt->fetchAll();
    }

    public function getByDriver($driver_id) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.driver_id = ? AND d.status IN ('dispatched', 'en_route', 'on_scene', 'returning')
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$driver_id]);
        return $stmt->fetchAll();
    }

    public function getActiveDeployments() {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.status IN ('dispatched', 'en_route', 'on_scene')
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addRoutePoint($deployment_id, $latitude, $longitude, $speed = null, $heading = null) {
        $query = "INSERT INTO route_points (deployment_id, latitude, longitude, speed, heading) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$deployment_id, $latitude, $longitude, $speed, $heading]);
    }

    public function getRoutePoints($deployment_id) {
        $query = "SELECT * FROM route_points WHERE deployment_id = ? ORDER BY timestamp ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$deployment_id]);
        return $stmt->fetchAll();
    }

    public function getCountByStatusGrouped() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
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

    public function getCountByStatuses($statuses) {
        if (empty($statuses)) {
            return 0;
        }
        
        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE status IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($statuses);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    public function getCountByDriver($driverId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE driver_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$driverId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    public function getCountByDriverAndStatus($driverId, $status) {
        if (is_array($status)) {
            $placeholders = str_repeat('?,', count($status) - 1) . '?';
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE driver_id = ? AND status IN ($placeholders)";
            $params = array_merge([$driverId], $status);
        } else {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE driver_id = ? AND status = ?";
            $params = [$driverId, $status];
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    public function getDeploymentsWithRouteInfo($driverId) {
        $query = "SELECT d.*, i.title as incident_title, i.location_name as incident_location, i.priority,
                         u.first_name as driver_first_name, u.last_name as driver_last_name,
                         v.vehicle_id as vehicle_code, v.vehicle_type,
                         (SELECT COUNT(*) FROM route_points rp WHERE rp.deployment_id = d.id) as route_points_count
                  FROM " . $this->table . " d
                  LEFT JOIN incidents i ON d.incident_id = i.id
                  LEFT JOIN drivers dr ON d.driver_id = dr.id
                  LEFT JOIN users u ON dr.user_id = u.id
                  LEFT JOIN vehicles v ON d.vehicle_id = v.id
                  WHERE d.driver_id = ? AND d.status IN ('dispatched', 'en_route', 'on_scene', 'returning')
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$driverId]);
        return $stmt->fetchAll();
    }

    public function getByIncidentAndStatus($incidentId, $statuses) {
        if (empty($statuses)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $query = "SELECT d.* FROM " . $this->table . " d 
                  WHERE d.incident_id = ? AND d.status IN ($placeholders)";
        
        $params = array_merge([$incidentId], $statuses);
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?> 