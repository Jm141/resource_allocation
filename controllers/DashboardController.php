<?php
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Facility.php';
require_once __DIR__ . '/../config/database.php';

class DashboardController {
    private $incidentModel;
    private $deploymentModel;
    private $userModel;
    private $vehicleModel;
    private $facilityModel;

    public function __construct() {
        $this->incidentModel = new Incident();
        $this->deploymentModel = new Deployment();
        $this->userModel = new User();
        $this->vehicleModel = new Vehicle();
        $this->facilityModel = new Facility();
    }

    public function index() {
        // Check for unreported incidents and auto-deploy if possible
        $this->checkAndAutoDeploy();
        
        // Get real-time statistics from database
        $stats = $this->getDashboardStats();
        
        // Get recent incidents
        $recentIncidents = $this->getRecentIncidents();
        
        // Get recent deployments
        $recentDeployments = $this->getRecentDeployments();
        
        // Get system status
        $systemStatus = $this->getSystemStatus();
        
        // Get emergency facility status
        $facilityStatus = $this->getFacilityStatus();
        
        // Get performance metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Set variables for the layout
        $page_title = 'Dashboard - Resource Allocation System';
        $action = 'dashboard';
        
        // Extract statistics
        $totalIncidents = $stats['total_incidents'];
        $activeDeployments = $stats['active_deployments'];
        $resolvedIncidents = $stats['resolved_incidents'];
        $pendingReports = $stats['pending_reports'];
        $totalUsers = $stats['total_users'];
        $totalVehicles = $stats['total_vehicles'];
        $totalFacilities = $stats['total_facilities'];
        
        $content_file = __DIR__ . '/../dashboard/dashboard_content.php';
        
        // Include the main layout
        include 'views/layouts/main.php';
    }

    private function checkAndAutoDeploy() {
        try {
            // Get unreported incidents
            $unreportedIncidents = $this->incidentModel->getByStatus('reported');
            
            if (empty($unreportedIncidents)) {
                return; // No incidents to deploy
            }
            
            // Check if there are available resources
            $availableDrivers = $this->getAvailableDrivers();
            $availableVehicles = $this->getAvailableVehicles();
            
            if (empty($availableDrivers) || empty($availableVehicles)) {
                // Log that auto-deployment is queued due to no resources
                error_log("Auto-deployment queued: No available drivers or vehicles");
                return;
            }
            
            // Auto-deploy incidents
            $deploymentController = new DeploymentController();
            $deploymentController->autoDeployUnreportedIncidents();
            
        } catch (Exception $e) {
            error_log("Auto-deployment check failed: " . $e->getMessage());
        }
    }

    private function getAvailableDrivers() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "SELECT COUNT(*) as count FROM drivers WHERE status = 'available'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getAvailableVehicles() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "SELECT COUNT(*) as count FROM vehicles WHERE status = 'available'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getDashboardStats() {
        try {
            // Get incident statistics
            $totalIncidents = $this->incidentModel->getCount();
            $resolvedIncidents = $this->incidentModel->getCountByStatus('resolved');
            $pendingReports = $this->incidentModel->getCountByStatuses(['reported', 'pending']);
            
            // Get deployment statistics
            $activeDeployments = $this->deploymentModel->getCountByStatuses(['dispatched', 'en_route', 'on_scene']);
            
            // Get user and vehicle statistics
            $totalUsers = $this->userModel->getCount();
            $totalVehicles = $this->vehicleModel->getCount();
            $totalFacilities = $this->facilityModel->getCount();
            
            return [
                'total_incidents' => $totalIncidents,
                'active_deployments' => $activeDeployments,
                'resolved_incidents' => $resolvedIncidents,
                'pending_reports' => $pendingReports,
                'total_users' => $totalUsers,
                'total_vehicles' => $totalVehicles,
                'total_facilities' => $totalFacilities
            ];
        } catch (Exception $e) {
            // Return default values if database error occurs
            return [
                'total_incidents' => 0,
                'active_deployments' => 0,
                'resolved_incidents' => 0,
                'pending_reports' => 0,
                'total_users' => 0,
                'total_vehicles' => 0,
                'total_facilities' => 0
            ];
        }
    }

    private function getRecentIncidents($limit = 5) {
        try {
            $incidents = $this->incidentModel->getAll($limit);
            $formatted = [];
            
            foreach ($incidents as $incident) {
                $formatted[] = [
                    'id' => $incident['id'],
                    'title' => $incident['title'],
                    'priority' => $incident['priority'],
                    'status' => $incident['status'],
                    'location' => $incident['location_name'] ?? 'Location not specified',
                    'created_at' => $incident['created_at'],
                    'category' => $incident['category_name'] ?? 'Uncategorized'
                ];
            }
            
            return $formatted;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getRecentDeployments($limit = 5) {
        try {
            $deployments = $this->deploymentModel->getAll($limit);
            $formatted = [];
            
            foreach ($deployments as $deployment) {
                $formatted[] = [
                    'id' => $deployment['id'],
                    'incident' => $deployment['incident_title'] ?? 'Incident not specified',
                    'driver' => ($deployment['driver_first_name'] ?? '') . ' ' . ($deployment['driver_last_name'] ?? ''),
                    'vehicle' => $deployment['vehicle_code'] ?? 'Vehicle not assigned',
                    'status' => $deployment['status'],
                    'created_at' => $deployment['created_at']
                ];
            }
            
            return $formatted;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getSystemStatus() {
        try {
            // Check database connection
            $dbStatus = $this->checkDatabaseConnection();
            
            // Check OSRM API status
            $osrmStatus = $this->checkOSRMStatus();
            
            // Check map services
            $mapStatus = $this->checkMapServices();
            
            return [
                'database' => $dbStatus,
                'osrm_api' => $osrmStatus,
                'map_services' => $mapStatus,
                'overall' => ($dbStatus && $osrmStatus && $mapStatus) ? 'operational' : 'degraded'
            ];
        } catch (Exception $e) {
            return [
                'database' => false,
                'osrm_api' => false,
                'map_services' => false,
                'overall' => 'error'
            ];
        }
    }

    private function checkDatabaseConnection() {
        try {
            $this->incidentModel->getCount();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function checkOSRMStatus() {
        try {
            $url = 'http://router.project-osrm.org/route/v1/driving/10.5377,122.8363;10.5450,122.8300?overview=false';
            $response = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 5]]));
            return $response !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function checkMapServices() {
        try {
            $url = 'https://tile.openstreetmap.org/13/4096/4096.png';
            $headers = @get_headers($url);
            return $headers && strpos($headers[0], '200') !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getFacilityStatus() {
        try {
            $facilities = $this->facilityModel->getAll();
            $statusCounts = [
                'operational' => 0,
                'maintenance' => 0,
                'offline' => 0
            ];
            
            foreach ($facilities as $facility) {
                $status = $facility['status'] ?? 'operational';
                $statusCounts[$status]++;
            }
            
            return $statusCounts;
        } catch (Exception $e) {
            return ['operational' => 0, 'maintenance' => 0, 'offline' => 0];
        }
    }

    private function getPerformanceMetrics() {
        try {
            // Get response time metrics
            $avgResponseTime = $this->calculateAverageResponseTime();
            
            // Get incident resolution rate
            $resolutionRate = $this->calculateResolutionRate();
            
            // Get deployment efficiency
            $deploymentEfficiency = $this->calculateDeploymentEfficiency();
            
            return [
                'avg_response_time' => $avgResponseTime,
                'resolution_rate' => $resolutionRate,
                'deployment_efficiency' => $deploymentEfficiency
            ];
        } catch (Exception $e) {
            return [
                'avg_response_time' => 0,
                'resolution_rate' => 0,
                'deployment_efficiency' => 0
            ];
        }
    }

    private function calculateAverageResponseTime() {
        try {
            $deployments = $this->deploymentModel->getByStatus('completed');
            $totalTime = 0;
            $count = 0;
            
            foreach ($deployments as $deployment) {
                if (isset($deployment['created_at']) && isset($deployment['updated_at'])) {
                    $created = strtotime($deployment['created_at']);
                    $updated = strtotime($deployment['updated_at']);
                    $totalTime += ($updated - $created);
                    $count++;
                }
            }
            
            return $count > 0 ? round($totalTime / $count / 60, 1) : 0; // Return in minutes
        } catch (Exception $e) {
            return 0;
        }
    }

    private function calculateResolutionRate() {
        try {
            $totalIncidents = $this->incidentModel->getCount();
            $resolvedIncidents = $this->incidentModel->getCountByStatus('resolved');
            
            return $totalIncidents > 0 ? round(($resolvedIncidents / $totalIncidents) * 100, 1) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function calculateDeploymentEfficiency() {
        try {
            $totalDeployments = $this->deploymentModel->getCount();
            $completedDeployments = $this->deploymentModel->getCountByStatus('completed');
            
            return $totalDeployments > 0 ? round(($completedDeployments / $totalDeployments) * 100, 1) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}
?> 