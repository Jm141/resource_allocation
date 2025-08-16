<?php
require_once 'config/database.php';

class HotlineModel {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Create a new hotline request
     */
    public function createRequest($phoneNumber, $message, $requestType = 'general', $priority = 'medium') {
        try {
            $sql = "INSERT INTO hotline_requests (phone_number, message, request_type, priority, status, created_at) 
                    VALUES (:phone_number, :message, :request_type, :priority, 'pending', NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':phone_number', $phoneNumber);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':request_type', $requestType);
            $stmt->bindParam(':priority', $priority);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating hotline request: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all pending hotline requests
     */
    public function getPendingRequests() {
        try {
            $sql = "SELECT * FROM hotline_requests WHERE status = 'pending' ORDER BY priority DESC, created_at ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting pending requests: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update request status
     */
    public function updateRequestStatus($requestId, $status, $assignedTo = null, $notes = null) {
        try {
            $sql = "UPDATE hotline_requests SET status = :status, assigned_to = :assigned_to, notes = :notes, updated_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':assigned_to', $assignedTo);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':id', $requestId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating request status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get request by ID
     */
    public function getRequestById($requestId) {
        try {
            $sql = "SELECT * FROM hotline_requests WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $requestId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error getting request: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get requests by phone number
     */
    public function getRequestsByPhone($phoneNumber) {
        try {
            $sql = "SELECT * FROM hotline_requests WHERE phone_number = :phone_number ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':phone_number', $phoneNumber);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting requests by phone: " . $e->getMessage());
            return [];
        }
    }
}
?> 