<?php
require_once __DIR__ . '/../models/Facility.php';
require_once __DIR__ . '/../config/database.php';

class FacilityController {
    private $facilityModel;

    public function __construct() {
        $this->facilityModel = new Facility();
    }

    public function index() {
        $facilities = $this->facilityModel->getAll();
        $facilityTypes = $this->facilityModel->getFacilityTypes();
        $countByType = $this->facilityModel->getCountByType();
        
        include 'views/facilities/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'facility_type' => $_POST['facility_type'] ?? '',
                'address' => $_POST['address'] ?? '',
                'latitude' => $_POST['latitude'] ?? 0,
                'longitude' => $_POST['longitude'] ?? 0,
                'contact_number' => $_POST['contact_number'] ?? '',
                'capacity' => $_POST['capacity'] ?? 0,
                'available_resources' => $_POST['available_resources'] ?? '',
                'is_active' => $_POST['is_active'] ?? 1
            ];

            if ($this->facilityModel->create($data)) {
                header('Location: index.php?action=facilities&success=created');
            } else {
                header('Location: index.php?action=facilities&error=create_failed');
            }
            exit;
        }
        
        $facilityTypes = $this->facilityModel->getFacilityTypes();
        include 'views/facilities/create.php';
    }

    public function edit($id) {
        $facility = $this->facilityModel->getById($id);
        
        if (!$facility) {
            header('Location: index.php?action=facilities&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'facility_type' => $_POST['facility_type'] ?? '',
                'address' => $_POST['address'] ?? '',
                'latitude' => $_POST['latitude'] ?? 0,
                'longitude' => $_POST['longitude'] ?? 0,
                'contact_number' => $_POST['contact_number'] ?? '',
                'capacity' => $_POST['capacity'] ?? 0,
                'available_resources' => $_POST['available_resources'] ?? '',
                'is_active' => $_POST['is_active'] ?? 1
            ];

            if ($this->facilityModel->update($id, $data)) {
                header('Location: index.php?action=facilities&success=updated');
            } else {
                header('Location: index.php?action=facilities&error=update_failed');
            }
            exit;
        }
        
        $facilityTypes = $this->facilityModel->getFacilityTypes();
        include 'views/facilities/edit.php';
    }

    public function delete($id) {
        if ($this->facilityModel->delete($id)) {
            header('Location: index.php?action=facilities&success=deleted');
        } else {
            header('Location: index.php?action=facilities&error=delete_failed');
        }
        exit;
    }

    public function getNearestFacilities() {
        $latitude = $_GET['lat'] ?? null;
        $longitude = $_GET['lng'] ?? null;
        $type = $_GET['type'] ?? null;
        $limit = $_GET['limit'] ?? 3;
        
        if (!$latitude || !$longitude) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing coordinates']);
            return;
        }
        
        $facilities = $this->facilityModel->getNearestFacilities($latitude, $longitude, $type, $limit);
        
        header('Content-Type: application/json');
        echo json_encode($facilities);
    }

    public function getFacilitiesForIncident() {
        $incidentType = $_GET['incident_type'] ?? null;
        $latitude = $_GET['lat'] ?? null;
        $longitude = $_GET['lng'] ?? null;
        
        if (!$incidentType || !$latitude || !$longitude) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing incident type or coordinates']);
            return;
        }
        
        $facilities = $this->facilityModel->getFacilitiesForIncident($incidentType, $latitude, $longitude);
        
        header('Content-Type: application/json');
        echo json_encode($facilities);
    }

    public function getAllFacilities() {
        $facilities = $this->facilityModel->getActiveFacilities();
        
        header('Content-Type: application/json');
        echo json_encode($facilities);
    }

    public function getFacilityTypes() {
        $types = $this->facilityModel->getFacilityTypes();
        
        header('Content-Type: application/json');
        echo json_encode($types);
    }
}
?> 