<?php
require_once 'models/Deployment.php';
require_once 'models/Incident.php';

class AdminController {
    private $deploymentModel;
    private $incidentModel;

    public function __construct() {
        $this->deploymentModel = new Deployment();
        $this->incidentModel = new Incident();
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
            $database = new Database();
            $conn = $database->getConnection();
            
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'operator';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $passwordHash, $role, $firstName, $lastName, $phone])) {
                header('Location: index.php?action=admin&method=users&success=created');
            } else {
                header('Location: index.php?action=admin&method=users&error=create_failed');
            }
            exit;
        }
        
        include 'views/admin/create_user.php';
    }

    public function editUser($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'operator';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            
            $stmt = $conn->prepare("UPDATE users SET email = ?, role = ?, first_name = ?, last_name = ?, phone = ?, is_active = ? WHERE id = ?");
            
            if ($stmt->execute([$email, $role, $firstName, $lastName, $phone, $isActive, $id])) {
                header('Location: index.php?action=admin&method=users&success=updated');
            } else {
                header('Location: index.php?action=admin&method=users&error=update_failed');
            }
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            header('Location: index.php?action=admin&method=users&error=not_found');
            exit;
        }
        
        include 'views/admin/edit_user.php';
    }

    public function deleteUser($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        
        if ($stmt->execute([$id])) {
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
            $database = new Database();
            $conn = $database->getConnection();
            
            $vehicleId = $_POST['vehicle_id'] ?? '';
            $vehicleType = $_POST['vehicle_type'] ?? '';
            $model = $_POST['model'] ?? '';
            $year = $_POST['year'] ?? '';
            $licensePlate = $_POST['license_plate'] ?? '';
            $capacity = $_POST['capacity'] ?? '';
            $status = $_POST['status'] ?? 'available';
            
            $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_id, vehicle_type, model, year, license_plate, capacity, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$vehicleId, $vehicleType, $model, $year, $licensePlate, $capacity, $status])) {
                header('Location: index.php?action=admin&method=vehicles&success=created');
            } else {
                header('Location: index.php?action=admin&method=vehicles&error=create_failed');
            }
            exit;
        }
        
        include 'views/admin/create_vehicle.php';
    }

    public function editVehicle($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vehicleId = $_POST['vehicle_id'] ?? '';
            $vehicleType = $_POST['vehicle_type'] ?? '';
            $model = $_POST['model'] ?? '';
            $year = $_POST['year'] ?? '';
            $licensePlate = $_POST['license_plate'] ?? '';
            $capacity = $_POST['capacity'] ?? '';
            $status = $_POST['status'] ?? 'available';
            
            $stmt = $conn->prepare("UPDATE vehicles SET vehicle_id = ?, vehicle_type = ?, model = ?, year = ?, license_plate = ?, capacity = ?, status = ? WHERE id = ?");
            
            if ($stmt->execute([$vehicleId, $vehicleType, $model, $year, $licensePlate, $capacity, $status, $id])) {
                header('Location: index.php?action=admin&method=vehicles&success=updated');
            } else {
                header('Location: index.php?action=admin&method=vehicles&error=update_failed');
            }
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vehicle) {
            header('Location: index.php?action=admin&method=vehicles&error=not_found');
            exit;
        }
        
        include 'views/admin/edit_vehicle.php';
    }

    public function deleteVehicle($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
        
        if ($stmt->execute([$id])) {
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
            $database = new Database();
            $conn = $database->getConnection();
            
            $driverId = $_POST['driver_id'] ?? '';
            $userId = $_POST['user_id'] ?? '';
            $licenseNumber = $_POST['license_number'] ?? '';
            $licenseType = $_POST['license_type'] ?? '';
            $experienceYears = $_POST['experience_years'] ?? '';
            $status = $_POST['status'] ?? 'available';
            
            $stmt = $conn->prepare("INSERT INTO drivers (driver_id, user_id, license_number, license_type, experience_years, status) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$driverId, $userId, $licenseNumber, $licenseType, $experienceYears, $status])) {
                header('Location: index.php?action=admin&method=drivers&success=created');
            } else {
                header('Location: index.php?action=admin&method=drivers&error=create_failed');
            }
            exit;
        }
        
        // Get available users for driver assignment
        $database = new Database();
        $conn = $database->getConnection();
        $users = [];
        if ($conn) {
            $stmt = $conn->query("SELECT id, username, first_name, last_name FROM users WHERE role = 'driver'");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/create_driver.php';
    }

    public function editDriver($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $driverId = $_POST['driver_id'] ?? '';
            $userId = $_POST['user_id'] ?? '';
            $licenseNumber = $_POST['license_number'] ?? '';
            $licenseType = $_POST['license_type'] ?? '';
            $experienceYears = $_POST['experience_years'] ?? '';
            $status = $_POST['status'] ?? 'available';
            
            $stmt = $conn->prepare("UPDATE drivers SET driver_id = ?, user_id = ?, license_number = ?, license_type = ?, experience_years = ?, status = ? WHERE id = ?");
            
            if ($stmt->execute([$driverId, $userId, $licenseNumber, $licenseType, $experienceYears, $status, $id])) {
                header('Location: index.php?action=admin&method=drivers&success=updated');
            } else {
                header('Location: index.php?action=admin&method=drivers&error=update_failed');
            }
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM drivers WHERE id = ?");
        $stmt->execute([$id]);
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$driver) {
            header('Location: index.php?action=admin&method=drivers&error=not_found');
            exit;
        }
        
        // Get available users for driver assignment
        $users = [];
        if ($conn) {
            $stmt = $conn->query("SELECT id, username, first_name, last_name FROM users WHERE role = 'driver'");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/admin/edit_driver.php';
    }

    public function deleteDriver($id) {
        $database = new Database();
        $conn = $database->getConnection();
        
        $stmt = $conn->prepare("DELETE FROM drivers WHERE id = ?");
        
        if ($stmt->execute([$id])) {
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