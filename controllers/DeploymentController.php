<?php
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../models/Driver.php';
require_once __DIR__ . '/../models/Facility.php';
require_once __DIR__ . '/../config/database.php';

class DeploymentController {
    private $deploymentModel;
    private $incidentModel;
    private $facilityModel;

    public function __construct() {
        $this->deploymentModel = new Deployment();
        $this->incidentModel = new Incident();
        $this->facilityModel = new Facility();
    }

    public function index() {
        $deployments = $this->deploymentModel->getAll();
        $statusCounts = $this->deploymentModel->getCountByStatusGrouped();
        
        include 'views/deployments/index.php';
    }

    public function show($id) {
        $deployment = $this->deploymentModel->getById($id);
        
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
                // Mark driver as deployed (in use)
                $driverModel = new Driver();
                $driverModel->markAsDeployed($data['driver_id']);
                
                // Mark vehicle as deployed (in use)
                $vehicleModel = new Vehicle();
                $vehicleModel->updateStatus($data['vehicle_id'], 'deployed');
                
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
        
        // Get available drivers and vehicles
        $database = new Database();
        $conn = $database->getConnection();
        
        $drivers = [];
        $vehicles = [];
        
        if ($conn) {
            // Get available drivers
            $stmt = $conn->query("SELECT id, driver_id, user_id FROM drivers WHERE status = 'available'");
            $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get available vehicles
            $stmt = $conn->query("SELECT id, vehicle_id, vehicle_type, model FROM vehicles WHERE status = 'available'");
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        include 'views/deployments/create.php';
    }

    public function createSmart() {
        // Get all incidents, drivers, and vehicles for the form
        $incidents = $this->incidentModel->getAll();
        $drivers = $this->getAllDrivers();
        $vehicles = $this->getAllVehicles();
        
        // Set variables for the view
        $page_title = 'Smart Deployment Creation - Resource Allocation System';
        $action = 'deployments';
        
        $content_file = __DIR__ . '/../views/deployments/create_smart.php';
        include 'views/layouts/main.php';
    }

    public function getOptimalDeployment() {
        $incidentId = $_GET['incident_id'] ?? null;
        
        if (!$incidentId) {
            http_response_code(400);
            echo json_encode(['error' => 'Incident ID is required']);
            return;
        }
        
        try {
            $incident = $this->incidentModel->getById($incidentId);
            if (!$incident) {
                echo json_encode(['error' => 'Incident not found']);
                return;
            }
            
            // Bago Headquarters coordinates
            $bagoHQ = [
                'lat' => 10.526071,
                'lng' => 122.841451,
                'name' => 'Bago City Emergency Response Headquarters'
            ];
            
            // Get optimal facilities based on incident type and location
            $facilities = $this->facilityModel->getFacilitiesForIncident(
                $incident['category_name'] ?? 'medical',
                $incident['latitude'],
                $incident['longitude']
            );
            
            $deploymentOptions = [];
            
            foreach ($facilities as $facility) {
                // Calculate route from Bago HQ to incident via facility
                $route = $this->calculateSmartRoute(
                    $bagoHQ['lat'], 
                    $bagoHQ['lng'], 
                    $facility['latitude'], 
                    $facility['longitude'],
                    $incident['latitude'], 
                    $incident['longitude']
                );
                
                if (isset($route['error'])) {
                    continue; // Skip if route calculation failed
                }
                
                // Check resource availability
                $resourceStatus = $this->checkResourceAvailability($facility, $incident);
                
                $deploymentOptions[] = [
                    'facility' => $facility,
                    'route' => $route,
                    'estimated_time' => $this->estimateSmartTravelTime($route),
                    'priority' => $this->calculateSmartPriority($facility, $incident, $route),
                    'resource_status' => $resourceStatus,
                    'traffic_alerts' => $this->generateTrafficAlerts($route),
                    'eta' => $this->calculateSmartETA($route)
                ];
            }
            
            // Sort by priority (highest first)
            usort($deploymentOptions, function($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });
            
            // Filter out options with no available resources
            $availableOptions = array_filter($deploymentOptions, function($option) {
                return $option['resource_status']['available'];
            });
            
            // If no resources available, add to queue
            if (empty($availableOptions)) {
                $this->queueDeployment($incident, $facilities);
                echo json_encode([
                    'incident' => $incident,
                    'deployment_options' => [],
                    'message' => 'No resources available. Deployment has been queued.',
                    'queued' => true
                ]);
                return;
            }
            
            echo json_encode([
                'incident' => $incident,
                'deployment_options' => array_values($availableOptions),
                'bago_hq' => $bagoHQ
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to calculate optimal deployment: ' . $e->getMessage()]);
        }
    }

    private function calculateSmartRoute($hqLat, $hqLng, $facilityLat, $facilityLng, $incidentLat, $incidentLng) {
        try {
            // Calculate route from HQ to facility to incident
            $route1 = $this->getOSRMRouteWithTrafficAvoidance($hqLat, $hqLng, $facilityLat, $facilityLng);
            $route2 = $this->getOSRMRouteWithTrafficAvoidance($facilityLat, $facilityLng, $incidentLat, $incidentLng);
            
            if (isset($route1['error']) || isset($route2['error'])) {
                return ['error' => 'Route calculation failed'];
            }
            
            // Combine routes
            $totalDistance = $route1['distance'] + $route2['distance'];
            $totalDuration = $route1['duration'] + $route2['duration'];
            
            return [
                'distance' => round($totalDistance, 2),
                'duration' => round($totalDuration, 1),
                'geometry' => $route1['geometry'], // Assuming route1 is HQ to facility
                'route1' => $route1, // HQ to facility
                'route2' => $route2, // Facility to incident
                'total_distance' => $totalDistance,
                'total_duration' => $totalDuration,
                'traffic_status' => $this->assessTrafficStatus($route1, $route2)
            ];
            
        } catch (Exception $e) {
            return ['error' => 'Route calculation error: ' . $e->getMessage()];
        }
    }

    private function getOSRMRouteWithTrafficAvoidance($startLat, $startLng, $endLat, $endLng) {
        try {
            // Use OSRM with traffic avoidance
            $url = "http://router.project-osrm.org/route/v1/driving/{$startLng},{$startLat};{$endLng},{$endLat}?overview=full&geometries=geojson&steps=true&alternatives=0";
            
            $response = file_get_contents($url);
            $routeData = json_decode($response, true);
            
            if (!$routeData || !isset($routeData['routes'][0])) {
                return ['error' => 'Failed to get route from OSRM'];
            }
            
            $route = $routeData['routes'][0];
            
            // Check for traffic incidents along the route
            $trafficIncidents = $this->checkTrafficIncidents($route);
            
            return [
                'distance' => round($route['distance'] / 1000, 2),
                'duration' => round($route['duration'] / 60, 1),
                'geometry' => $route['geometry'],
                'traffic_incidents' => $trafficIncidents,
                'traffic_delay' => $this->calculateTrafficDelay($trafficIncidents),
                'recommended_route' => $this->isRouteOptimal($route, $trafficIncidents)
            ];
            
        } catch (Exception $e) {
            return ['error' => 'OSRM error: ' . $e->getMessage()];
        }
    }

    private function checkTrafficIncidents($route) {
        try {
            // Get active incidents that might affect this route
            $activeIncidents = $this->incidentModel->getByStatuses(['reported', 'assigned', 'in_progress']);
            $trafficIncidents = [];
            
            foreach ($activeIncidents as $incident) {
                if ($this->routeIntersectsIncident($route, $incident)) {
                    $trafficIncidents[] = [
                        'id' => $incident['id'],
                        'title' => $incident['title'],
                        'type' => $incident['category_name'],
                        'priority' => $incident['priority'],
                        'location' => $incident['location_name'],
                        'coordinates' => [$incident['latitude'], $incident['longitude']],
                        'avoidance_radius' => $this->getIncidentAvoidanceRadius($incident['priority'])
                    ];
                }
            }
            
            return $trafficIncidents;
            
        } catch (Exception $e) {
            return [];
        }
    }

    private function routeIntersectsIncident($route, $incident) {
        try {
            $routeGeometry = $route['geometry']['coordinates'] ?? [];
            $incidentLat = $incident['latitude'];
            $incidentLng = $incident['longitude'];
            $avoidanceRadius = $this->getIncidentAvoidanceRadius($incident['priority']);
            
            foreach ($routeGeometry as $coord) {
                $lng = $coord[0];
                $lat = $coord[1];
                
                $distance = $this->haversineDistance($lat, $lng, $incidentLat, $incidentLng);
                
                if ($distance <= $avoidanceRadius) {
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }

    private function getIncidentAvoidanceRadius($priority) {
        $radiusMap = [
            'critical' => 0.5,  // 500m for critical incidents
            'high' => 0.3,       // 300m for high priority
            'medium' => 0.2,     // 200m for medium priority
            'low' => 0.1         // 100m for low priority
        ];
        
        return $radiusMap[$priority] ?? 0.2;
    }

    private function calculateTrafficDelay($trafficIncidents) {
        $totalDelay = 0;
        
        foreach ($trafficIncidents as $incident) {
            $priority = $incident['priority'];
            $delayMap = [
                'critical' => 15,  // 15 minutes delay
                'high' => 10,      // 10 minutes delay
                'medium' => 5,     // 5 minutes delay
                'low' => 2         // 2 minutes delay
            ];
            
            $totalDelay += $delayMap[$priority] ?? 5;
        }
        
        return $totalDelay;
    }

    private function isRouteOptimal($route, $trafficIncidents) {
        // Check if route is optimal (no significant traffic delays)
        $trafficDelay = $this->calculateTrafficDelay($trafficIncidents);
        $baseDuration = $route['duration'] / 60; // Convert to minutes
        
        // If traffic delay is more than 20% of base duration, route is not optimal
        return ($trafficDelay / $baseDuration) <= 0.2;
    }

    private function checkResourceAvailability($facility, $incident) {
        try {
            // Check if facility has available resources for this incident type
            $requiredResources = $this->getRequiredResources($incident['category_name']);
            $availableResources = $facility['available_resources'] ?? '';
            
            $hasResources = true;
            $resourceDetails = [];
            
            foreach ($requiredResources as $resource) {
                if (stripos($availableResources, $resource) === false) {
                    $hasResources = false;
                    $resourceDetails[] = "Missing: $resource";
                }
            }
            
            return [
                'available' => $hasResources,
                'details' => $resourceDetails,
                'required' => $requiredResources,
                'available_list' => $availableResources
            ];
            
        } catch (Exception $e) {
            return ['available' => false, 'details' => ['Error checking resources']];
        }
    }

    private function getRequiredResources($incidentType) {
        $resourceMap = [
            'fire' => ['fire_truck', 'fire_extinguisher', 'rescue_equipment'],
            'medical' => ['ambulance', 'medical_supplies', 'defibrillator'],
            'police' => ['police_vehicle', 'communication_equipment', 'protective_gear'],
            'traffic' => ['traffic_control', 'tow_truck', 'medical_support'],
            'rescue' => ['rescue_vehicle', 'specialized_tools', 'medical_equipment']
        ];
        
        return $resourceMap[$incidentType] ?? ['basic_equipment'];
    }

    private function calculateSmartPriority($facility, $incident, $route) {
        $priority = 5; // Base priority
        
        // Distance factor (closer = higher priority)
        $distanceFactor = max(0, 10 - ($route['distance'] * 2));
        $priority += $distanceFactor;
        
        // Resource availability factor
        if (isset($route['resource_status']) && $route['resource_status']['available']) {
            $priority += 3;
        }
        
        // Incident priority factor
        $incidentPriorityMap = ['critical' => 3, 'high' => 2, 'medium' => 1, 'low' => 0];
        $priority += $incidentPriorityMap[$incident['priority']] ?? 0;
        
        // Traffic factor (less traffic = higher priority)
        if (isset($route['traffic_status']) && $route['traffic_status']['status'] === 'clear') {
            $priority += 2;
        }
        
        return min(10, max(1, $priority));
    }

    private function estimateSmartTravelTime($route) {
        $baseTime = $route['total_duration'];
        $trafficDelay = $route['traffic_delay'] ?? 0;
        
        return round($baseTime + $trafficDelay);
    }

    private function calculateSmartETA($route) {
        $totalMinutes = $this->estimateSmartTravelTime($route);
        $eta = new DateTime();
        $eta->add(new DateInterval("PT{$totalMinutes}M"));
        
        return $eta->format('H:i');
    }

    private function generateTrafficAlerts($route) {
        $alerts = [];
        
        if (!empty($route['traffic_incidents'])) {
            foreach ($route['traffic_incidents'] as $incident) {
                $alerts[] = [
                    'type' => 'traffic_incident',
                    'message' => "🚨 Traffic incident ahead: {$incident['title']}",
                    'severity' => $incident['priority'],
                    'location' => $incident['location'],
                    'recommendation' => $this->getTrafficRecommendation($incident)
                ];
            }
        }
        
        if ($route['traffic_delay'] > 10) {
            $alerts[] = [
                'type' => 'traffic_delay',
                'message' => "⏰ Significant traffic delay expected: +{$route['traffic_delay']} minutes",
                'severity' => 'high',
                'recommendation' => 'Consider alternative route or adjust ETA'
            ];
        }
        
        return $alerts;
    }

    private function getTrafficRecommendation($incident) {
        $recommendations = [
            'critical' => 'Route should be avoided if possible. Use alternative route.',
            'high' => 'Expect significant delays. Consider route adjustment.',
            'medium' => 'Minor delays expected. Proceed with caution.',
            'low' => 'Minimal impact. Proceed normally.'
        ];
        
        return $recommendations[$incident['priority']] ?? 'Proceed with caution.';
    }

    private function queueDeployment($incident, $facilities) {
        try {
            // Get database connection from one of the models
            $database = new Database();
            $conn = $database->getConnection();
            
            // Add deployment to queue table
            $query = "INSERT INTO deployment_queue (incident_id, facilities, priority, created_at) 
                      VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
            
            $facilitiesJson = json_encode($facilities);
            $priority = $this->getIncidentPriorityScore($incident['priority']);
            
            $stmt = $conn->prepare($query);
            $stmt->execute([$incident['id'], $facilitiesJson, $priority]);
            
            // Log the queuing
            error_log("Deployment queued for incident {$incident['id']} - no resources available");
            
        } catch (Exception $e) {
            error_log("Failed to queue deployment: " . $e->getMessage());
        }
    }

    private function getIncidentPriorityScore($priority) {
        $scoreMap = ['critical' => 10, 'high' => 8, 'medium' => 6, 'low' => 4];
        return $scoreMap[$priority] ?? 5;
    }

    private function assessTrafficStatus($route1, $route2) {
        $totalIncidents = count($route1['traffic_incidents']) + count($route2['traffic_incidents']);
        $totalDelay = ($route1['traffic_delay'] ?? 0) + ($route2['traffic_delay'] ?? 0);
        
        if ($totalIncidents === 0) {
            return ['status' => 'clear', 'message' => 'No traffic incidents detected'];
        } elseif ($totalDelay <= 5) {
            return ['status' => 'minor', 'message' => 'Minor traffic delays expected'];
        } elseif ($totalDelay <= 15) {
            return ['status' => 'moderate', 'message' => 'Moderate traffic delays expected'];
        } else {
            return ['status' => 'severe', 'message' => 'Severe traffic delays expected'];
        }
    }

    private function haversineDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
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
            
            // Add debugging
            error_log("DeploymentController::updateStatus - ID: $id, Status: $status");
            error_log("POST data: " . print_r($_POST, true));
            error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
            
            if (empty($status)) {
                error_log("DeploymentController::updateStatus - Status is empty");
                echo json_encode(['success' => false, 'message' => 'Status is required']);
                return;
            }
            
            if ($this->deploymentModel->updateStatus($id, $status)) {
                // Get deployment details to update driver, vehicle, and incident status
                $deployment = $this->deploymentModel->getById($id);
                if ($deployment) {
                    $driverModel = new Driver();
                    $vehicleModel = new Vehicle();
                    $incidentModel = new Incident();
                    
                    // Update driver and vehicle status based on deployment status
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
                                    error_log("DeploymentController::updateStatus - Incident {$deployment['incident_id']} marked as resolved");
                                    
                                    // Also check if there are any other deployments for this incident
                                    $allDeploymentsForIncident = $this->deploymentModel->getByIncident($deployment['incident_id']);
                                    $completedDeployments = array_filter($allDeploymentsForIncident, function($dep) {
                                        return $dep['status'] === 'completed';
                                    });
                                    
                                    // If all deployments are completed, mark incident as closed
                                    if (count($completedDeployments) === count($allDeploymentsForIncident)) {
                                        $incidentModel->updateStatus($deployment['incident_id'], 'closed');
                                        error_log("DeploymentController::updateStatus - Incident {$deployment['incident_id']} marked as closed (all deployments completed)");
                                    }
                                } else {
                                    error_log("DeploymentController::updateStatus - Incident {$deployment['incident_id']} has other active deployments, status unchanged");
                                }
                            }
                            break;
                            
                        case 'dispatched':
                        case 'en_route':
                        case 'on_scene':
                        case 'returning':
                            // Mark driver as deployed when deployment is active
                            $driverModel->markAsDeployed($deployment['driver_id']);
                            // Mark vehicle as deployed (in use)
                            $vehicleModel->updateStatus($deployment['vehicle_id'], 'deployed');
                            
                            // Update incident status to in_progress when deployment is active
                            if ($deployment['incident_id']) {
                                $incidentModel->updateStatus($deployment['incident_id'], 'in_progress');
                                error_log("DeploymentController::updateStatus - Incident {$deployment['incident_id']} marked as in_progress");
                            }
                            break;
                    }
                }
                
                error_log("DeploymentController::updateStatus - Success");
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                error_log("DeploymentController::updateStatus - Failed");
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } else {
            error_log("DeploymentController::updateStatus - Invalid request method: " . $_SERVER['REQUEST_METHOD']);
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

    public function autoDeployUnreportedIncidents() {
        try {
            // Get all incidents that haven't been deployed yet
            $unreportedIncidents = $this->incidentModel->getByStatus('reported');
            
            if (empty($unreportedIncidents)) {
                echo json_encode(['message' => 'No unreported incidents found', 'deployments_created' => 0]);
                return;
            }
            
            $deploymentsCreated = 0;
            $deploymentResults = [];
            
            foreach ($unreportedIncidents as $incident) {
                $deploymentResult = $this->createAutoDeployment($incident);
                if ($deploymentResult['success']) {
                    $deploymentsCreated++;
                    $deploymentResults[] = $deploymentResult;
                }
            }
            
            echo json_encode([
                'success' => true,
                'message' => "Auto-deployment completed. {$deploymentsCreated} deployments created.",
                'deployments_created' => $deploymentsCreated,
                'results' => $deploymentResults
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Auto-deployment failed: ' . $e->getMessage()]);
        }
    }

    private function getAllDrivers() {
        try {
            $driverModel = new Driver();
            return $driverModel->getAll();
        } catch (Exception $e) {
            error_log("Failed to get all drivers: " . $e->getMessage());
            return [];
        }
    }

    private function getAllVehicles() {
        try {
            $vehicleModel = new Vehicle();
            return $vehicleModel->getAll();
        } catch (Exception $e) {
            error_log("Failed to get all vehicles: " . $e->getMessage());
            return [];
        }
    }

    private function createAutoDeployment($incident) {
        try {
            // Get optimal facilities for this incident
            $facilities = $this->facilityModel->getFacilitiesForIncident(
                $incident['category_name'] ?? 'medical',
                $incident['latitude'],
                $incident['longitude']
            );
            
            if (empty($facilities)) {
                return ['success' => false, 'message' => 'No suitable facilities found'];
            }
            
            // Get available drivers and vehicles
            $availableDrivers = $this->getAvailableDrivers();
            $availableVehicles = $this->getAvailableVehicles();
            
            if (empty($availableDrivers) || empty($availableVehicles)) {
                return ['success' => false, 'message' => 'No available drivers or vehicles'];
            }
            
            // Select best driver and vehicle
            $selectedDriver = $this->selectBestDriver($availableDrivers, $incident);
            $selectedVehicle = $this->selectBestVehicle($availableVehicles, $incident);
            
            if (!$selectedDriver || !$selectedVehicle) {
                return ['success' => false, 'message' => 'Could not select optimal driver/vehicle'];
            }
            
            // Create deployment data
            $deploymentData = [
                'incident_id' => $incident['id'],
                'driver_id' => $selectedDriver['id'],
                'vehicle_id' => $selectedVehicle['id'],
                'start_location' => 'Bago City Emergency Response Headquarters',
                'start_lat' => 10.526071,
                'start_lng' => 122.841451,
                'end_location' => $incident['location_name'],
                'end_lat' => $incident['latitude'],
                'end_lng' => $incident['longitude']
            ];
            
            // Create the deployment
            if ($this->deploymentModel->create($deploymentData)) {
                // Update incident status to assigned
                $this->incidentModel->updateStatus($incident['id'], 'assigned');
                
                // Mark driver as deployed (in use)
                $driverModel = new Driver();
                $driverModel->markAsDeployed($selectedDriver['id']);
                
                // Mark vehicle as deployed (in use)
                $vehicleModel = new Vehicle();
                $vehicleModel->updateStatus($selectedVehicle['id'], 'deployed');
                
                return [
                    'success' => true,
                    'deployment_id' => $deploymentData['deployment_id'] ?? 'AUTO_' . time(),
                    'incident' => $incident['title'],
                    'driver' => $selectedDriver['driver_id'],
                    'vehicle' => $selectedVehicle['vehicle_id'],
                    'facility' => $facilities[0]['name'] ?? 'Nearest facility'
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create deployment'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    private function getAvailableDrivers() {
        try {
            $driverModel = new Driver();
            return $driverModel->getAvailable();
        } catch (Exception $e) {
            error_log("Failed to get available drivers: " . $e->getMessage());
            return [];
        }
    }

    private function getAvailableVehicles() {
        try {
            $vehicleModel = new Vehicle();
            return $vehicleModel->getAvailable();
        } catch (Exception $e) {
            error_log("Failed to get available vehicles: " . $e->getMessage());
            return [];
        }
    }

    private function selectBestDriver($drivers, $incident) {
        // Simple selection logic - can be enhanced with more sophisticated algorithms
        if (empty($drivers)) return null;
        
        // For now, just return the first available driver
        // Future: implement driver selection based on:
        // - Distance from incident
        // - Driver experience with incident type
        // - Current workload
        // - Specialization
        return $drivers[0];
    }

    private function selectBestVehicle($vehicles, $incident) {
        if (empty($vehicles)) return null;
        
        // Select vehicle based on incident type
        $incidentType = $incident['category_name'] ?? 'medical';
        $priorityVehicleTypes = [
            'fire' => ['fire_truck', 'rescue_vehicle'],
            'medical' => ['ambulance', 'medical_vehicle'],
            'police' => ['police_vehicle', 'patrol_car'],
            'traffic_accident' => ['ambulance', 'police_vehicle', 'tow_truck'],
            'natural_disaster' => ['fire_truck', 'rescue_vehicle', 'ambulance']
        ];
        
        $preferredTypes = $priorityVehicleTypes[$incidentType] ?? ['ambulance'];
        
        // Try to find preferred vehicle type first
        foreach ($preferredTypes as $type) {
            foreach ($vehicles as $vehicle) {
                if (stripos($vehicle['vehicle_type'], $type) !== false) {
                    return $vehicle;
                }
            }
        }
        
        // Fallback to first available vehicle
        return $vehicles[0];
    }

    private function updateResourceAvailability($driverId, $vehicleId) {
        try {
            $driverModel = new Driver();
            $vehicleModel = new Vehicle();
            
            // Update driver status to deployed (in use)
            $driverModel->markAsDeployed($driverId);
            
            // Update vehicle status to deployed (in use)
            $vehicleModel->updateStatus($vehicleId, 'deployed');
            
            return true;
        } catch (Exception $e) {
            error_log("Failed to update resource availability: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Release resources (driver and vehicle) when deployment is completed or cancelled
     */
    private function releaseResources($deploymentId) {
        try {
            $deployment = $this->deploymentModel->getById($deploymentId);
            if (!$deployment) {
                return false;
            }
            
            $driverModel = new Driver();
            $vehicleModel = new Vehicle();
            
            // Mark driver as available
            $driverModel->markAsAvailable($deployment['driver_id']);
            
            // Mark vehicle as available
            $vehicleModel->updateStatus($deployment['vehicle_id'], 'available');
            
            error_log("Resources released for deployment {$deploymentId}");
            return true;
            
        } catch (Exception $e) {
            error_log("Failed to release resources for deployment {$deploymentId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if driver and vehicle are available for deployment
     */
    private function checkDriverVehicleAvailability($driverId, $vehicleId) {
        try {
            $driverModel = new Driver();
            $vehicleModel = new Vehicle();
            
            $driverAvailable = $driverModel->isAvailable($driverId);
            $vehicleAvailable = $vehicleModel->isAvailable($vehicleId);
            
            return [
                'driver_available' => $driverAvailable,
                'vehicle_available' => $vehicleAvailable,
                'both_available' => $driverAvailable && $vehicleAvailable
            ];
            
        } catch (Exception $e) {
            error_log("Failed to check driver/vehicle availability: " . $e->getMessage());
            return [
                'driver_available' => false,
                'vehicle_available' => false,
                'both_available' => false
            ];
        }
    }
}
?> 