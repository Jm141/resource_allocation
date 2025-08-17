<?php
require_once 'config/database.php';

class Facility {
    private $conn;
    private $table = 'facilities';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY facility_type, name";
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
        $query = "SELECT * FROM " . $this->table . " WHERE facility_type = ? AND is_active = 1 ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function getActiveFacilities() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY facility_type, name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (name, facility_type, address, latitude, longitude, contact_number, 
                   capacity, available_resources, is_active) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['facility_type'],
            $data['address'],
            $data['latitude'],
            $data['longitude'],
            $data['contact_number'],
            $data['capacity'],
            $data['available_resources'],
            $data['is_active'] ?? 1
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = ?, facility_type = ?, address = ?, latitude = ?, longitude = ?, 
                      contact_number = ?, capacity = ?, available_resources = ?, 
                      is_active = ?, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['facility_type'],
            $data['address'],
            $data['latitude'],
            $data['longitude'],
            $data['contact_number'],
            $data['capacity'],
            $data['available_resources'],
            $data['is_active'],
            $id
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getNearestFacilities($latitude, $longitude, $type = null, $limit = 3) {
        $typeFilter = $type ? "AND facility_type = ?" : "";
        $typeParam = $type ? [$type] : [];
        
        $query = "SELECT *, 
                  (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                   cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                   sin(radians(latitude)))) AS distance 
                  FROM " . $this->table . " 
                  WHERE is_active = 1 $typeFilter
                  ORDER BY distance 
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $params = array_merge([$latitude, $longitude, $latitude], $typeParam, [$limit]);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFacilitiesForIncident($incidentType, $latitude, $longitude) {
        $facilities = [];
        
        // Determine which facility types are needed based on incident type
        $requiredTypes = $this->getRequiredFacilityTypes($incidentType);
        
        foreach ($requiredTypes as $type) {
            $nearest = $this->getNearestFacilities($latitude, $longitude, $type, 2);
            $facilities = array_merge($facilities, $nearest);
        }
        
        return $facilities;
    }

    private function getRequiredFacilityTypes($incidentType) {
        $typeMapping = [
            'fire' => ['fire_station', 'hospital'],
            'medical' => ['hospital'],
            'police' => ['police_station', 'hospital'],
            'traffic_accident' => ['police_station', 'hospital', 'fire_station'],
            'natural_disaster' => ['fire_station', 'hospital', 'police_station'],
            'chemical_spill' => ['fire_station', 'hospital'],
            'bomb_threat' => ['police_station', 'fire_station', 'hospital'],
            'hostage_situation' => ['police_station', 'hospital'],
            'gas_leak' => ['fire_station', 'hospital'],
            'power_outage' => ['fire_station'],
            'flooding' => ['fire_station', 'hospital'],
            'earthquake' => ['fire_station', 'hospital', 'police_station']
        ];
        
        return $typeMapping[$incidentType] ?? ['hospital'];
    }

    public function getFacilityTypes() {
        return [
            'hospital' => 'Hospital',
            'police_station' => 'Police Station',
            'fire_station' => 'Fire Station',
            'emergency_center' => 'Emergency Response Center',
            'command_center' => 'Command & Control Center'
        ];
    }

    public function getCountByType() {
        $query = "SELECT facility_type, COUNT(*) as count FROM " . $this->table . " WHERE is_active = 1 GROUP BY facility_type";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?> 