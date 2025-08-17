<?php
require_once 'config/database.php';

class Incident {
    private $conn;
    private $table = 'incidents';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($limit = null, $offset = null) {
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  ORDER BY i.created_at DESC";
        
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
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  WHERE i.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (incident_id, category_id, title, description, location_name, latitude, longitude, reported_by, priority) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $incident_id = 'INC' . date('Ymd') . rand(1000, 9999);
        
        return $stmt->execute([
            $incident_id,
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['location_name'],
            $data['latitude'],
            $data['longitude'],
            $data['reported_by'],
            $data['priority']
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = ?, description = ?, location_name = ?, latitude = ?, longitude = ?, 
                      category_id = ?, priority = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['location_name'],
            $data['latitude'],
            $data['longitude'],
            $data['category_id'],
            $data['priority'],
            $data['status'],
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

    public function getByStatus($status) {
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  WHERE i.status = ? 
                  ORDER BY i.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function getByStatuses($statuses) {
        if (empty($statuses)) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  WHERE i.status IN ($placeholders)
                  ORDER BY i.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($statuses);
        return $stmt->fetchAll();
    }

    public function getByPriority($priority) {
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  WHERE i.priority = ? 
                  ORDER BY i.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$priority]);
        return $stmt->fetchAll();
    }

    public function getCountByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM " . $this->table . " GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
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

    public function search($search_term) {
        $query = "SELECT i.*, ic.name as category_name, u.first_name, u.last_name 
                  FROM " . $this->table . " i 
                  LEFT JOIN incident_categories ic ON i.category_id = ic.id 
                  LEFT JOIN users u ON i.reported_by = u.id 
                  WHERE i.title LIKE ? OR i.description LIKE ? OR i.location_name LIKE ? 
                  ORDER BY i.created_at DESC";
        
        $search_term = "%$search_term%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$search_term, $search_term, $search_term]);
        return $stmt->fetchAll();
    }
}
?> 