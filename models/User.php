<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'users';

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

    public function getByRole($role) {
        $query = "SELECT * FROM " . $this->table . " WHERE role = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (username, email, first_name, last_name, role, password_hash, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['role'],
            $data['password_hash']
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET username = ?, email = ?, first_name = ?, last_name = ?, 
                      role = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['role'],
            $id
        ]);
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

    public function getCountByRole($role) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE role = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$role]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    public function authenticate($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ? OR email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
}
?> 