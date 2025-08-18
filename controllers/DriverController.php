<?php
require_once __DIR__ . '/../models/Driver.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../config/database.php';

class DriverController {
    private $driverModel;
    private $deploymentModel;
    private $incidentModel;
    private $vehicleModel;

    public function __construct() {
        $this->driverModel = new Driver();
        $this->deploymentModel = new Deployment();
        $this->incidentModel = new Incident();
        $this->vehicleModel = new Vehicle();
    }

    public function index() {
        // Get driver ID from session or query parameter
        $driverId = $_GET['id'] ?? 1; // Default for testing
        
        // Get driver information
        $driver = $this->driverModel->getById($driverId);
        
        if (!$driver) {
            header('Location: index.php?error=driver_not_found');
            exit;
        }
        
        // Get driver's active deployments
        $activeDeployments = $this->deploymentModel->getByDriver($driverId);
        
        // Get driver's assigned vehicle
        $vehicle = null;
        if (!empty($activeDeployments)) {
            $vehicle = $this->vehicleModel->getById($activeDeployments[0]['vehicle_id']);
        }
        
        // Set variables for the view
        $page_title = 'Driver Dashboard - ' . $driver['first_name'] . ' ' . $driver['last_name'];
        $action = 'driver';
        
        $content_file = __DIR__ . '/../views/driver/dashboard_content.php';
        include 'views/layouts/main.php';
    }

    public function deployment($deploymentId) {
        // Get deployment details
        $deployment = $this->deploymentModel->getById($deploymentId);
        
        if (!$deployment) {
            header('Location: index.php?action=driver&error=deployment_not_found');
            exit;
        }
        
        // Get incident details
        $incident = $this->incidentModel->getById($deployment['incident_id']);
        
        // Get driver information
        $driver = $this->driverModel->getById($deployment['driver_id']);
        
        // Get vehicle information
        $vehicle = $this->vehicleModel->getById($deployment['vehicle_id']);
        
        // Set variables for the view
        $page_title = 'Deployment Details - Driver View';
        $action = 'driver';
        
        $content_file = __DIR__ . '/../views/driver/deployment_content.php';
        include 'views/layouts/main.php';
    }

    public function updateStatus($deploymentId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            if (empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Status is required']);
                return;
            }
            
            if ($this->deploymentModel->updateStatus($deploymentId, $status)) {
                // Add notes if provided
                if ($notes) {
                    $this->deploymentModel->update($deploymentId, ['notes' => $notes]);
                }
                
                // Update driver and vehicle status based on deployment status
                $deployment = $this->deploymentModel->getById($deploymentId);
                if ($deployment) {
                    $this->updateResourceStatus($deployment, $status);
                }
                
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    private function updateResourceStatus($deployment, $status) {
        try {
            $driverModel = new Driver();
            $vehicleModel = new Vehicle();
            $incidentModel = new Incident();
            
            switch ($status) {
                case 'completed':
                case 'cancelled':
                    // Mark driver as available when deployment is completed or cancelled
                    $driverModel->markAsAvailable($deployment['driver_id']);
                    // Mark vehicle as available
                    $vehicleModel->updateStatus($deployment['vehicle_id'], 'available');
                    
                    // Update incident status when deployment is completed
                    if ($status === 'completed' && $deployment['incident_id']) {
                        // Check if this is the only active deployment for this incident
                        $activeDeploymentsForIncident = $this->deploymentModel->getByIncidentAndStatus(
                            $deployment['incident_id'], 
                            ['dispatched', 'en_route', 'on_scene', 'returning']
                        );
                        
                        // If no other active deployments, mark incident as resolved
                        if (empty($activeDeploymentsForIncident)) {
                            $incidentModel->updateStatus($deployment['incident_id'], 'resolved');
                            error_log("DriverController::updateResourceStatus - Incident {$deployment['incident_id']} marked as resolved");
                            
                            // Also check if there are any other deployments for this incident
                            $allDeploymentsForIncident = $this->deploymentModel->getByIncident($deployment['incident_id']);
                            $completedDeployments = array_filter($allDeploymentsForIncident, function($dep) {
                                return $dep['status'] === 'completed';
                            });
                            
                            // If all deployments are completed, mark incident as closed
                            if (count($completedDeployments) === count($allDeploymentsForIncident)) {
                                $incidentModel->updateStatus($deployment['incident_id'], 'closed');
                                error_log("DriverController::updateResourceStatus - Incident {$deployment['incident_id']} marked as closed (all deployments completed)");
                            }
                        } else {
                            error_log("DriverController::updateResourceStatus - Incident {$deployment['incident_id']} has other active deployments, status unchanged");
                        }
                    }
                    break;
                    
                case 'dispatched':
                case 'en_route':
                case 'on_scene':
                case 'returning':
                    // Mark driver as deployed when deployment is active
                    $driverModel->markAsDeployed($deployment['driver_id']);
                    // Mark vehicle as deployed
                    $vehicleModel->updateStatus($deployment['vehicle_id'], 'deployed');
                    
                    // Update incident status to in_progress when deployment is active
                    if ($deployment['incident_id']) {
                        $incidentModel->updateStatus($deployment['incident_id'], 'in_progress');
                        error_log("DriverController::updateResourceStatus - Incident {$deployment['incident_id']} marked as in_progress");
                    }
                    break;
            }
        } catch (Exception $e) {
            error_log("Error updating resource status: " . $e->getMessage());
        }
    }

    public function updateLocation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $driverId = $_POST['driver_id'] ?? '';
            $latitude = $_POST['latitude'] ?? '';
            $longitude = $_POST['longitude'] ?? '';
            $timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');
            
            if (empty($driverId) || empty($latitude) || empty($longitude)) {
                echo json_encode(['success' => false, 'message' => 'Driver ID, latitude, and longitude are required']);
                return;
            }
            
            try {
                // Update driver's current location
                $this->driverModel->updateLocation($driverId, $latitude, $longitude);
                
                // Check if driver has arrived at any deployment location
                $this->checkArrivalStatus($driverId, $latitude, $longitude);
                
                echo json_encode(['success' => true, 'message' => 'Location updated successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to update location: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    private function checkArrivalStatus($driverId, $latitude, $longitude) {
        try {
            // Get driver's active deployments
            $activeDeployments = $this->deploymentModel->getByDriver($driverId);
            
            foreach ($activeDeployments as $deployment) {
                if ($deployment['status'] === 'en_route' && $deployment['end_lat'] && $deployment['end_lng']) {
                    $distance = $this->calculateDistance($latitude, $longitude, $deployment['end_lat'], $deployment['end_lng']);
                    
                    // If driver is within 100 meters of incident location, mark as on scene
                    if ($distance <= 0.1) {
                        $this->deploymentModel->updateStatus($deployment['id'], 'on_scene');
                        error_log("Driver {$driverId} arrived at deployment {$deployment['id']} - status updated to on_scene");
                    }
                }
                
                // Check if driver has returned to office (Bago HQ coordinates)
                if ($deployment['status'] === 'returning') {
                    $distanceToOffice = $this->calculateDistance($latitude, $longitude, 10.5377, 122.8363);
                    
                    // If driver is within 200 meters of office, mark as completed
                    if ($distanceToOffice <= 0.2) {
                        $this->deploymentModel->updateStatus($deployment['id'], 'completed');
                        error_log("Driver {$driverId} returned to office for deployment {$deployment['id']} - status updated to completed");
                        
                        // Update incident status when deployment is automatically completed
                        if ($deployment['incident_id']) {
                            $incidentModel = new Incident();
                            
                            // Check if this is the only active deployment for this incident
                            $activeDeploymentsForIncident = $this->deploymentModel->getByIncidentAndStatus(
                                $deployment['incident_id'], 
                                ['dispatched', 'en_route', 'on_scene', 'returning']
                            );
                            
                            // If no other active deployments, mark incident as resolved
                            if (empty($activeDeploymentsForIncident)) {
                                $incidentModel->updateStatus($deployment['incident_id'], 'resolved');
                                error_log("DriverController::checkArrivalStatus - Incident {$deployment['incident_id']} automatically marked as resolved");
                                
                                // Also check if there are any other deployments for this incident
                                $allDeploymentsForIncident = $this->deploymentModel->getByIncident($deployment['incident_id']);
                                $completedDeployments = array_filter($allDeploymentsForIncident, function($dep) {
                                    return $dep['status'] === 'completed';
                                });
                                
                                // If all deployments are completed, mark incident as closed
                                if (count($completedDeployments) === count($allDeploymentsForIncident)) {
                                    $incidentModel->updateStatus($deployment['incident_id'], 'closed');
                                    error_log("DriverController::checkArrivalStatus - Incident {$deployment['incident_id']} automatically marked as closed (all deployments completed)");
                                }
                            } else {
                                error_log("DriverController::checkArrivalStatus - Incident {$deployment['incident_id']} has other active deployments, status unchanged");
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error checking arrival status: " . $e->getMessage());
        }
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2) {
        $R = 6371; // Earth's radius in kilometers
        $dLat = ($lat2 - $lat1) * M_PI / 180;
        $dLng = ($lng2 - $lng1) * M_PI / 180;
        $a = sin($dLat/2) * sin($dLat/2) +
             cos($lat1 * M_PI / 180) * cos($lat2 * M_PI / 180) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }

    public function getActiveDeployments() {
        $driverId = $_GET['driver_id'] ?? '';
        
        if (empty($driverId)) {
            echo json_encode(['error' => 'Driver ID is required']);
            return;
        }
        
        try {
            $deployments = $this->deploymentModel->getByDriver($driverId);
            echo json_encode(['success' => true, 'deployments' => $deployments]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to get deployments: ' . $e->getMessage()]);
        }
    }

    public function getDeploymentRoute() {
        $deploymentId = $_GET['deployment_id'] ?? '';
        $startLat = $_GET['start_lat'] ?? '';
        $startLng = $_GET['start_lng'] ?? '';
        $endLat = $_GET['end_lat'] ?? '';
        $endLng = $_GET['end_lng'] ?? '';
        
        if (empty($deploymentId) || empty($startLat) || empty($startLng) || empty($endLat) || empty($endLng)) {
            echo json_encode(['error' => 'All coordinates are required']);
            return;
        }
        
        try {
            // Use OSRM to calculate route
            $url = "http://router.project-osrm.org/route/v1/driving/{$startLng},{$startLat};{$endLng},{$endLat}?overview=full&geometries=geojson&steps=true&alternatives=0";
            
            $response = file_get_contents($url);
            $routeData = json_decode($response, true);
            
            if (!$routeData || !isset($routeData['routes'][0])) {
                echo json_encode(['error' => 'Failed to calculate route']);
                return;
            }
            
            $route = $routeData['routes'][0];
            
            echo json_encode([
                'success' => true,
                'distance' => round($route['distance'] / 1000, 2),
                'duration' => round($route['duration'] / 60, 1),
                'geometry' => $route['geometry']
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Route calculation failed: ' . $e->getMessage()]);
        }
    }

    public function getTrafficAlerts() {
        $deploymentId = $_GET['deployment_id'] ?? null;
        
        if (!$deploymentId) {
            echo json_encode(['error' => 'Deployment ID required']);
            return;
        }
        
        try {
            // Get deployment details
            $deployment = $this->deploymentModel->getById($deploymentId);
            if (!$deployment) {
                echo json_encode(['error' => 'Deployment not found']);
                return;
            }
            
            // Get active incidents that might affect the route
            $activeIncidents = $this->incidentModel->getByStatuses(['reported', 'assigned', 'in_progress']);
            
            $trafficAlerts = [];
            foreach ($activeIncidents as $incident) {
                if ($incident['id'] != $deployment['incident_id']) {
                    $distance = $this->calculateDistance(
                        $deployment['end_lat'], $deployment['end_lng'],
                        $incident['latitude'], $incident['longitude']
                    );
                    
                    if ($distance <= 2.0) { // Within 2km
                        $trafficAlerts[] = [
                            'title' => $incident['title'],
                            'location' => $incident['location_name'],
                            'distance' => round($distance, 1),
                            'priority' => $incident['priority'],
                            'type' => $incident['category_name'] ?? 'unknown'
                        ];
                    }
                }
            }
            
            echo json_encode(['success' => true, 'alerts' => $trafficAlerts]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to get traffic alerts: ' . $e->getMessage()]);
        }
    }

    public function getDriverStats() {
        $driverId = $_GET['driver_id'] ?? '';
        
        if (empty($driverId)) {
            echo json_encode(['error' => 'Driver ID is required']);
            return;
        }
        
        try {
            // Get driver statistics
            $totalDeployments = $this->deploymentModel->getCountByDriver($driverId);
            $completedDeployments = $this->deploymentModel->getCountByDriverAndStatus($driverId, 'completed');
            $activeDeployments = $this->deploymentModel->getCountByDriverAndStatus($driverId, ['en_route', 'on_scene', 'returning']);
            
            echo json_encode([
                'success' => true,
                'stats' => [
                    'total_deployments' => $totalDeployments,
                    'completed_deployments' => $completedDeployments,
                    'active_deployments' => $activeDeployments,
                    'completion_rate' => $totalDeployments > 0 ? round(($completedDeployments / $totalDeployments) * 100, 1) : 0
                ]
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to get driver stats: ' . $e->getMessage()]);
        }
    }
}
?> 