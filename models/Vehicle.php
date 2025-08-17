<?php
require_once 'config/database.php';

class Vehicle {
    private $conn;
    private $table = 'vehicles';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
            if ($offset) {
                $query .= " OFFSET " . (int)$offset;
            }
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByType($type) {
        $query = "SELECT * FROM " . $this->table . " WHERE vehicle_type = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getAvailable() {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'available' ORDER BY vehicle_type, vehicle_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (vehicle_id, vehicle_type, model, year, license_plate, status, capacity, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['vehicle_id'],
            $data['vehicle_type'],
            $data['model'],
            $data['year'],
            $data['license_plate'],
            $data['status'],
            $data['capacity']
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET vehicle_id = ?, vehicle_type = ?, model = ?, year = ?, 
                      license_plate = ?, status = ?, capacity = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['vehicle_id'],
            $data['vehicle_type'],
            $data['model'],
            $data['year'],
            $data['license_plate'],
            $data['status'],
            $data['capacity'],
            $id
        ]);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
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

    public function getCountByType($type) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE vehicle_type = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$type]);
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

    public function getVehicleTypes() {
        $query = "SELECT DISTINCT vehicle_type FROM " . $this->table . " ORDER BY vehicle_type";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?> 