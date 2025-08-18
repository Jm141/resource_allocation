<?php
class Database {
    private $host = "localhost";
    private $dbname = "resource_allocation";
    private $username = "root";
    private $password = "1412";
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch(PDOException $e) {
            // Log error instead of echoing to prevent HTML output
            error_log("Database connection failed: " . $e->getMessage());
            return null;
        }
    }
}
?> 