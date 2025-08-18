<?php
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../config/database.php';

class IncidentController {
    private $incidentModel;
    private $deploymentModel;

    public function __construct() {
        $this->incidentModel = new Incident();
        $this->deploymentModel = new Deployment();
    }

    public function index() {
        $incidents = $this->incidentModel->getAll();
        $statusCounts = $this->incidentModel->getCountByStatusGrouped();
        
        include 'views/incidents/index.php';
    }

    public function show($id) {
        $incident = $this->incidentModel->getById($id);
        $deployments = $this->deploymentModel->getByIncident($id);
        
        if (!$incident) {
            header('Location: index.php?action=incidents&error=not_found');
            exit;
        }
        
        include 'views/incidents/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'location_name' => $_POST['location_name'] ?? '',
                'latitude' => $_POST['latitude'] ?? 0,
                'longitude' => $_POST['longitude'] ?? 0,
                'category_id' => $_POST['category_id'] ?? 1,
                'priority' => $_POST['priority'] ?? 'medium',
                'reported_by' => $_POST['reported_by'] ?? 1 // Default user ID
            ];

            if ($this->incidentModel->create($data)) {
                header('Location: index.php?action=incidents&success=created');
            } else {
                header('Location: index.php?action=incidents&error=create_failed');
            }
            exit;
        }
        
        include 'views/incidents/create.php';
    }

    public function edit($id) {
        $incident = $this->incidentModel->getById($id);
        
        if (!$incident) {
            header('Location: index.php?action=incidents&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'location_name' => $_POST['location_name'] ?? '',
                'latitude' => $_POST['latitude'] ?? 0,
                'longitude' => $_POST['longitude'] ?? 0,
                'category_id' => $_POST['category_id'] ?? 1,
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => $_POST['status'] ?? 'reported'
            ];

            if ($this->incidentModel->update($id, $data)) {
                header('Location: index.php?action=incidents&success=updated');
            } else {
                header('Location: index.php?action=incidents&error=update_failed');
            }
            exit;
        }
        
        include 'views/incidents/edit.php';
    }

    public function delete($id) {
        if ($this->incidentModel->delete($id)) {
            header('Location: index.php?action=incidents&success=deleted');
        } else {
            header('Location: index.php?action=incidents&error=delete_failed');
        }
        exit;
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if ($this->incidentModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function search() {
        $search_term = $_GET['q'] ?? '';
        $incidents = $this->incidentModel->search($search_term);
        
        if (isset($_GET['ajax'])) {
            echo json_encode($incidents);
        } else {
            include 'views/incidents/search.php';
        }
    }

    public function getByStatus($status) {
        $incidents = $this->incidentModel->getByStatus($status);
        
        if (isset($_GET['ajax'])) {
            echo json_encode($incidents);
        } else {
            include 'views/incidents/index.php';
        }
    }

    public function getByPriority($priority) {
        $incidents = $this->incidentModel->getByPriority($priority);
        
        if (isset($_GET['ajax'])) {
            echo json_encode($incidents);
        } else {
            include 'views/incidents/index.php';
        }
    }
}
?> 