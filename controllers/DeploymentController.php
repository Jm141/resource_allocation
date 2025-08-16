<?php
require_once 'models/Deployment.php';
require_once 'models/Incident.php';

class DeploymentController {
    private $deploymentModel;
    private $incidentModel;

    public function __construct() {
        $this->deploymentModel = new Deployment();
        $this->incidentModel = new Incident();
    }

    public function index() {
        $deployments = $this->deploymentModel->getAll();
        $statusCounts = $this->deploymentModel->getCountByStatus();
        
        include 'views/deployments/index.php';
    }

    public function show($id) {
        $deployment = $this->deploymentModel->getById($id);
        $routePoints = $this->deploymentModel->getRoutePoints($id);
        
        if (!$deployment) {
            header('Location: index.php?action=deployments&error=not_found');
            exit;
        }
        
        include 'views/deployments/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'incident_id' => $_POST['incident_id'] ?? '',
                'driver_id' => $_POST['driver_id'] ?? '',
                'vehicle_id' => $_POST['vehicle_id'] ?? '',
                'start_location' => $_POST['start_location'] ?? 'Bago City Headquarters',
                'start_lat' => $_POST['start_lat'] ?? 10.5377,
                'start_lng' => $_POST['start_lng'] ?? 122.8363,
                'end_location' => $_POST['end_location'] ?? '',
                'end_lat' => $_POST['end_lat'] ?? 0,
                'end_lng' => $_POST['end_lng'] ?? 0
            ];

            if ($this->deploymentModel->create($data)) {
                // Update incident status to 'assigned'
                $this->incidentModel->updateStatus($data['incident_id'], 'assigned');
                
                header('Location: index.php?action=deployments&success=created');
            } else {
                header('Location: index.php?action=deployments&error=create_failed');
            }
            exit;
        }
        
        // Get available incidents for deployment
        $incidents = $this->incidentModel->getByStatus('reported');
        include 'views/deployments/create.php';
    }

    public function edit($id) {
        $deployment = $this->deploymentModel->getById($id);
        
        if (!$deployment) {
            header('Location: index.php?action=deployments&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'start_location' => $_POST['start_location'] ?? '',
                'start_lat' => $_POST['start_lat'] ?? 0,
                'start_lng' => $_POST['start_lng'] ?? 0,
                'end_location' => $_POST['end_location'] ?? '',
                'end_lat' => $_POST['end_lat'] ?? 0,
                'end_lng' => $_POST['end_lng'] ?? 0,
                'status' => $_POST['status'] ?? 'dispatched',
                'notes' => $_POST['notes'] ?? ''
            ];

            if ($this->deploymentModel->update($id, $data)) {
                header('Location: index.php?action=deployments&success=updated');
            } else {
                header('Location: index.php?action=deployments&error=update_failed');
            }
            exit;
        }
        
        include 'views/deployments/edit.php';
    }

    public function delete($id) {
        if ($this->deploymentModel->delete($id)) {
            header('Location: index.php?action=deployments&success=deleted');
        } else {
            header('Location: index.php?action=deployments&error=delete_failed');
        }
        exit;
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if ($this->deploymentModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function addRoutePoint($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $latitude = $_POST['latitude'] ?? 0;
            $longitude = $_POST['longitude'] ?? 0;
            $speed = $_POST['speed'] ?? null;
            $heading = $_POST['heading'] ?? null;
            
            if ($this->deploymentModel->addRoutePoint($id, $latitude, $longitude, $speed, $heading)) {
                echo json_encode(['success' => true, 'message' => 'Route point added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add route point']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function getRoutePoints($id) {
        $routePoints = $this->deploymentModel->getRoutePoints($id);
        echo json_encode($routePoints);
    }

    public function getActive() {
        $deployments = $this->deploymentModel->getActiveDeployments();
        
        if (isset($_GET['ajax'])) {
            echo json_encode($deployments);
        } else {
            include 'views/deployments/active.php';
        }
    }

    public function getByStatus($status) {
        $deployments = $this->deploymentModel->getByStatus($status);
        
        if (isset($_GET['ajax'])) {
            echo json_encode($deployments);
        } else {
            include 'views/deployments/index.php';
        }
    }
}
?> 