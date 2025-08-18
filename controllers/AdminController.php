<?php
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Driver.php';
require_once __DIR__ . '/../config/database.php';

class AdminController {
    private $deploymentModel;
    private $incidentModel;
    private $userModel;
    private $vehicleModel;
    private $driverModel;

    public function __construct() {
        $this->deploymentModel = new Deployment();
        $this->incidentModel = new Incident();
        $this->userModel = new User();
        $this->vehicleModel = new Vehicle();
        $this->driverModel = new Driver();
    }

    public function index() {
        // Get system overview statistics
        $database = new Database();
        $conn = $database->getConnection();
        
        $stats = [];
        
        if ($conn) {
            // Get counts for dashboard
            $tables = ['users', 'drivers', 'vehicles', 'incidents', 'deployments', 'hotline_requests'];
            foreach ($tables as $table) {
                $stmt = $conn->query("SELECT COUNT(*) as count FROM $table");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats[$table] = $result['count'];
            }
            
            // Get recent activities
            $recentIncidents = $conn->query("SELECT * FROM incidents ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            $recentDeployments = $conn->query("SELECT * FROM deployments ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            $recentHotlineRequests = $conn->query("SELECT * FROM hotline_requests ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/index.php';
    }

    // User Management
    public function users() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $users = [];
        if ($conn) {
            $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/users.php';
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'user',
                'status' => $_POST['status'] ?? 'active'
            ];
            
            if ($this->userModel->create($userData)) {
                header('Location: index.php?action=admin&method=users&success=created');
            } else {
                header('Location: index.php?action=admin&method=users&error=create_failed');
            }
            exit;
        }
        
        $page_title = 'Create User - Admin';
        $action = 'admin';
        $method = 'createUser';
        
        $content_file = __DIR__ . '/../views/admin/create_user_content.php';
        include 'views/layouts/main.php';
    }

    public function editUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'user',
                'status' => $_POST['status'] ?? 'active'
            ];
            
            if ($this->userModel->update($id, $userData)) {
                header('Location: index.php?action=admin&method=users&success=updated');
            } else {
                header('Location: index.php?action=admin&method=users&error=update_failed');
            }
            exit;
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            header('Location: index.php?action=admin&method=users&error=not_found');
            exit;
        }
        
        $page_title = 'Edit User - Admin';
        $action = 'admin';
        $method = 'editUser';
        
        $content_file = __DIR__ . '/../views/admin/edit_user_content.php';
        include 'views/layouts/main.php';
    }

    public function deleteUser($id) {
        if ($this->userModel->delete($id)) {
            header('Location: index.php?action=admin&method=users&success=deleted');
        } else {
            header('Location: index.php?action=admin&method=users&error=delete_failed');
        }
        exit;
    }

    // Vehicle Management
    public function vehicles() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $vehicles = [];
        if ($conn) {
            $stmt = $conn->query("SELECT * FROM vehicles ORDER BY created_at DESC");
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/vehicles.php';
    }

    public function createVehicle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleData = [
                'vehicle_id' => $_POST['vehicle_id'] ?? '',
                'vehicle_type' => $_POST['vehicle_type'] ?? '',
                'model' => $_POST['model'] ?? '',
                'capacity' => $_POST['capacity'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            if ($this->vehicleModel->create($vehicleData)) {
                header('Location: index.php?action=admin&method=vehicles&success=created');
            } else {
                header('Location: index.php?action=admin&method=vehicles&error=create_failed');
            }
            exit;
        }
        
        $page_title = 'Create Vehicle - Admin';
        $action = 'admin';
        $method = 'createVehicle';
        
        $content_file = __DIR__ . '/../views/admin/create_vehicle_content.php';
        include 'views/layouts/main.php';
    }

    public function editVehicle($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleData = [
                'vehicle_id' => $_POST['vehicle_id'] ?? '',
                'vehicle_type' => $_POST['vehicle_type'] ?? '',
                'model' => $_POST['model'] ?? '',
                'capacity' => $_POST['capacity'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            if ($this->vehicleModel->update($id, $vehicleData)) {
                header('Location: index.php?action=admin&method=vehicles&success=updated');
            } else {
                header('Location: index.php?action=admin&method=vehicles&error=update_failed');
            }
            exit;
        }
        
        $vehicle = $this->vehicleModel->getById($id);
        if (!$vehicle) {
            header('Location: index.php?action=admin&method=vehicles&error=not_found');
            exit;
        }
        
        $page_title = 'Edit Vehicle - Admin';
        $action = 'admin';
        $method = 'editVehicle';
        
        $content_file = __DIR__ . '/../views/admin/edit_vehicle_content.php';
        include 'views/layouts/main.php';
    }

    public function deleteVehicle($id) {
        if ($this->vehicleModel->delete($id)) {
            header('Location: index.php?action=admin&method=vehicles&success=deleted');
        } else {
            header('Location: index.php?action=admin&method=vehicles&error=delete_failed');
        }
        exit;
    }

    // Driver Management
    public function drivers() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $drivers = [];
        if ($conn) {
            $stmt = $conn->query("SELECT d.*, u.first_name, u.last_name, u.email FROM drivers d LEFT JOIN users u ON d.user_id = u.id ORDER BY d.created_at DESC");
            $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/drivers.php';
    }

    public function createDriver() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $driverData = [
                'driver_id' => $_POST['driver_id'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'license_number' => $_POST['license_number'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            if ($this->driverModel->create($driverData)) {
                header('Location: index.php?action=admin&method=drivers&success=created');
            } else {
                header('Location: index.php?action=admin&method=drivers&error=create_failed');
            }
            exit;
        }
        
        $page_title = 'Create Driver - Admin';
        $action = 'admin';
        $method = 'createDriver';
        
        $content_file = __DIR__ . '/../views/admin/create_driver_content.php';
        include 'views/layouts/main.php';
    }

    public function editDriver($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $driverData = [
                'driver_id' => $_POST['driver_id'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'license_number' => $_POST['license_number'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            if ($this->driverModel->update($id, $driverData)) {
                header('Location: index.php?action=admin&method=drivers&success=updated');
            } else {
                header('Location: index.php?action=admin&method=drivers&error=update_failed');
            }
            exit;
        }
        
        $driver = $this->driverModel->getById($id);
        if (!$driver) {
            header('Location: index.php?action=admin&method=drivers&error=not_found');
            exit;
        }
        
        $page_title = 'Edit Driver - Admin';
        $action = 'admin';
        $method = 'editDriver';
        
        $content_file = __DIR__ . '/../views/admin/edit_driver_content.php';
        include 'views/layouts/main.php';
    }

    public function deleteDriver($id) {
        if ($this->driverModel->delete($id)) {
            header('Location: index.php?action=admin&method=drivers&success=deleted');
        } else {
            header('Location: index.php?action=admin&method=drivers&error=delete_failed');
        }
        exit;
    }

    // Incident Management
    public function incidents() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $incidents = [];
        if ($conn) {
            $stmt = $conn->query("SELECT i.*, ic.name as category_name FROM incidents i LEFT JOIN incident_categories ic ON i.category_id = ic.id ORDER BY i.created_at DESC");
            $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/incidents.php';
    }

    public function editIncident($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $priority = $_POST['priority'] ?? '';
            $status = $_POST['status'] ?? '';
            
            $stmt = $conn->prepare("UPDATE incidents SET title = ?, description = ?, category_id = ?, priority = ?, status = ? WHERE id = ?");
            
            if ($stmt->execute([$title, $description, $categoryId, $priority, $status, $id])) {
                header('Location: index.php?action=admin&method=incidents&success=updated');
            } else {
                header('Location: index.php?action=admin&method=incidents&error=update_failed');
            }
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM incidents WHERE id = ?");
        $stmt->execute([$id]);
        $incident = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$incident) {
            header('Location: index.php?action=admin&method=incidents&error=not_found');
            exit;
        }
        
        // Get categories
        $categories = [];
        if ($conn) {
            $stmt = $conn->query("SELECT * FROM incident_categories");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/edit_incident.php';
    }

    // Deployment Management
    public function deployments() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $deployments = [];
        if ($conn) {
            $stmt = $conn->query("SELECT d.*, i.title as incident_title, dr.driver_id, v.vehicle_id FROM deployments d LEFT JOIN incidents i ON d.incident_id = i.id LEFT JOIN drivers dr ON d.driver_id = dr.id LEFT JOIN vehicles v ON d.vehicle_id = v.id ORDER BY d.created_at DESC");
            $deployments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/deployments.php';
    }

    public function editDeployment($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            $stmt = $conn->prepare("UPDATE deployments SET status = ?, notes = ? WHERE id = ?");
            
            if ($stmt->execute([$status, $notes, $id])) {
                header('Location: index.php?action=admin&method=deployments&success=updated');
            } else {
                header('Location: index.php?action=admin&method=deployments&error=update_failed');
            }
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM deployments WHERE id = ?");
        $stmt->execute([$id]);
        $deployment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$deployment) {
            header('Location: index.php?action=admin&method=deployments&error=not_found');
            exit;
        }
        
        include 'views/admin/edit_deployment.php';
    }

    // System Settings
    public function settings() {
        $database = new Database();
        $conn = $database->getConnection();
        
        // Get system logs
        $logs = [];
        if ($conn) {
            $stmt = $conn->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 100");
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/settings.php';
    }

    // Hotline Management
    public function hotline() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $requests = [];
        if ($conn) {
            $stmt = $conn->query("SELECT * FROM hotline_requests ORDER BY created_at DESC");
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/hotline.php';
    }

    public function updateHotlineRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $conn = $database->getConnection();
            
            $status = $_POST['status'] ?? '';
            $assignedTo = $_POST['assigned_to'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            $stmt = $conn->prepare("UPDATE hotline_requests SET status = ?, assigned_to = ?, notes = ? WHERE id = ?");
            
            if ($stmt->execute([$status, $assignedTo, $notes, $id])) {
                header('Location: index.php?action=admin&method=hotline&success=updated');
            } else {
                header('Location: index.php?action=admin&method=hotline&error=update_failed');
            }
            exit;
        }
        
        $database = new Database();
        $conn = $database->getConnection();
        
        $stmt = $conn->prepare("SELECT * FROM hotline_requests WHERE id = ?");
        $stmt->execute([$id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            header('Location: index.php?action=admin&method=hotline&error=not_found');
            exit;
        }
        
        include 'views/admin/edit_hotline_request.php';
    }
}
?> 