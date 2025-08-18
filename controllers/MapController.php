<?php
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../models/Facility.php';
require_once __DIR__ . '/../config/database.php';

class MapController {
    private $incidentModel;
    private $deploymentModel;
    private $facilityModel;
    private $osrmBaseUrl = 'http://router.project-osrm.org/route/v1/driving/';

    public function __construct() {
        // Start output buffering to prevent accidental output
        if (ob_get_level() == 0) {
            ob_start();
        }
        
        $this->incidentModel = new Incident();
        $this->deploymentModel = new Deployment();
        $this->facilityModel = new Facility();
    }

    public function index() {
        include 'views/map/index.php';
    }

    public function getMapData() {
        try {
            // Set JSON header first to prevent HTML output
            header('Content-Type: application/json');
            
            // Check if models are properly initialized
            if (!$this->incidentModel || !$this->deploymentModel || !$this->facilityModel) {
                throw new Exception('Models not properly initialized');
            }
            
            // Test database connection for each model
            $database = new Database();
            $conn = $database->getConnection();
            if (!$conn) {
                throw new Exception('Database connection failed');
            }
            
            // Get active incidents (reported, assigned, in_progress)
            $activeIncidents = $this->incidentModel->getByStatuses(['reported', 'assigned', 'in_progress']);
            
            // Get active deployments
            $activeDeployments = $this->deploymentModel->getActiveDeployments();
            
            // Get all emergency facilities
            $facilities = $this->facilityModel->getActiveFacilities();
            
            // Debug logging
            error_log("MapController::getMapData - Active Incidents: " . count($activeIncidents));
            error_log("MapController::getMapData - Active Deployments: " . count($activeDeployments));
            error_log("MapController::getMapData - Facilities: " . count($facilities));
            
            // Format data for map
            $mapData = [
                'incidents' => $this->formatIncidentsForMap($activeIncidents),
                'deployments' => $this->formatDeploymentsForMap($activeDeployments),
                'facilities' => $this->formatFacilitiesForMap($facilities)
            ];
            
            // Ensure no output before JSON
            if (ob_get_length()) ob_clean();
            
            echo json_encode($mapData);
            
        } catch (Exception $e) {
            // Log the error
            error_log("MapController::getMapData Error: " . $e->getMessage());
            error_log("MapController::getMapData Stack Trace: " . $e->getTraceAsString());
            
            // Return error as JSON
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to load map data: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    public function getRouteOptimization() {
        $startLat = $_GET['start_lat'] ?? null;
        $startLng = $_GET['start_lng'] ?? null;
        $endLat = $_GET['end_lat'] ?? null;
        $endLng = $_GET['end_lng'] ?? null;
        
        if (!$startLat || !$startLng || !$endLat || !$endLng) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing coordinates']);
            return;
        }
        
        // Get incidents that might affect the route
        $nearbyIncidents = $this->getNearbyIncidents($startLat, $startLng, $endLat, $endLng);
        
        // Calculate optimized route using OSRM with incident avoidance
        $optimizedRoute = $this->calculateOSRMRoute($startLat, $startLng, $endLat, $endLng, $nearbyIncidents);
        
        header('Content-Type: application/json');
        echo json_encode($optimizedRoute);
    }

    public function getOSRMRoute() {
        $startLat = $_GET['start_lat'] ?? null;
        $startLng = $_GET['start_lng'] ?? null;
        $endLat = $_GET['end_lat'] ?? null;
        $endLng = $_GET['end_lng'] ?? null;
        $avoidIncidents = $_GET['avoid_incidents'] ?? 'true';
        
        if (!$startLat || !$startLng || !$endLat || !$endLng) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing coordinates']);
            return;
        }
        
        $route = $this->getOSRMRouteData($startLat, $startLng, $endLat, $endLng, $avoidIncidents === 'true');
        
        header('Content-Type: application/json');
        echo json_encode($route);
    }

    public function getIncidentAvoidanceRoute() {
        $startLat = $_GET['start_lat'] ?? null;
        $startLng = $_GET['start_lng'] ?? null;
        $endLat = $_GET['end_lat'] ?? null;
        $endLng = $_GET['end_lng'] ?? null;
        
        if (!$startLat || !$startLng || !$endLat || !$endLng) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing coordinates']);
            return;
        }
        
        // Validate coordinate ranges
        if (!is_numeric($startLat) || !is_numeric($startLng) || !is_numeric($endLat) || !is_numeric($endLng)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid coordinate format']);
            return;
        }
        
        if ($startLat < -90 || $startLat > 90 || $endLat < -90 || $endLat > 90 ||
            $startLng < -180 || $startLng > 180 || $endLng < -180 || $endLng > 180) {
            http_response_code(400);
            echo json_encode(['error' => 'Coordinates out of valid range']);
            return;
        }
        
        // Get ONLY active incidents for route analysis (no resolved/completed)
        $activeIncidents = $this->incidentModel->getByStatuses(['reported', 'assigned', 'in_progress']);
        
        // Calculate route with incident avoidance
        $route = $this->calculateIncidentAvoidanceRoute($startLat, $startLng, $endLat, $endLng, $activeIncidents);
        
        // Ensure we only return one route
        if (isset($route['error'])) {
            echo json_encode($route);
            return;
        }
        
        // Add flag to ensure single route display
        $route['is_single_route'] = true;
        $route['route_count'] = 1;
        
        // Log for debugging
        error_log("Incident avoidance route calculated - Single route returned");
        
        header('Content-Type: application/json');
        echo json_encode($route);
    }

    private function formatIncidentsForMap($incidents) {
        $formatted = [];
        foreach ($incidents as $incident) {
            $formatted[] = [
                'id' => $incident['id'],
                'incident_id' => $incident['incident_id'],
                'title' => $incident['title'],
                'description' => $incident['description'],
                'latitude' => $incident['latitude'],
                'longitude' => $incident['longitude'],
                'location_name' => $incident['location_name'],
                'priority' => $incident['priority'],
                'status' => $incident['status'],
                'created_at' => $incident['created_at'],
                'icon' => $this->getIncidentIcon($incident['priority'], $incident['status'])
            ];
        }
        return $formatted;
    }

    private function formatDeploymentsForMap($deployments) {
        $formatted = [];
        foreach ($deployments as $deployment) {
            $formatted[] = [
                'id' => $deployment['id'],
                'deployment_id' => $deployment['deployment_id'],
                'incident_title' => $deployment['incident_title'] ?? 'N/A',
                'driver_name' => ($deployment['driver_first_name'] ?? '') . ' ' . ($deployment['driver_last_name'] ?? ''),
                'vehicle_code' => $deployment['vehicle_code'] ?? 'N/A',
                'vehicle_type' => $deployment['vehicle_type'] ?? 'N/A',
                'start_location' => $deployment['start_location'] ?? 'N/A',
                'end_location' => $deployment['end_location'] ?? 'N/A',
                'start_lat' => $deployment['start_lat'],
                'start_lng' => $deployment['start_lng'],
                'end_lat' => $deployment['end_lat'],
                'end_lng' => $deployment['end_lng'],
                'status' => $deployment['status'],
                'created_at' => $deployment['created_at'],
                'icon' => $this->getDeploymentIcon($deployment['vehicle_type'] ?? 'truck', $deployment['status'])
            ];
        }
        return $formatted;
    }

    private function formatFacilitiesForMap($facilities) {
        $formatted = [];
        foreach ($facilities as $facility) {
            $formatted[] = [
                'id' => $facility['id'],
                'facility_id' => $facility['id'], // Use id as facility_id
                'name' => $facility['name'],
                'type' => $facility['facility_type'], // Use facility_type field
                'latitude' => $facility['latitude'],
                'longitude' => $facility['longitude'],
                'address' => $facility['address'],
                'status' => $facility['is_active'] ? 'operational' : 'offline', // Derive status from is_active
                'icon' => $this->getFacilityIcon($facility['facility_type'], $facility['is_active'] ? 'operational' : 'offline')
            ];
        }
        return $formatted;
    }

    private function getIncidentIcon($priority, $status) {
        $iconMap = [
            'critical' => 'fas fa-exclamation-triangle',
            'high' => 'fas fa-exclamation-circle',
            'medium' => 'fas fa-info-circle',
            'low' => 'fas fa-info'
        ];
        
        $colorMap = [
            'critical' => '#dc3545',
            'high' => '#fd7e14',
            'medium' => '#ffc107',
            'low' => '#28a745'
        ];
        
        return [
            'icon' => $iconMap[$priority] ?? 'fas fa-exclamation-triangle',
            'color' => $colorMap[$priority] ?? '#dc3545',
            'size' => [25, 25]
        ];
    }

    private function getDeploymentIcon($vehicleType, $status) {
        $iconMap = [
            'ambulance' => 'fas fa-ambulance',
            'fire_truck' => 'fas fa-fire-truck',
            'police_car' => 'fas fa-car',
            'rescue_vehicle' => 'fas fa-truck'
        ];
        
        $colorMap = [
            'dispatched' => '#007bff',
            'en_route' => '#28a745',
            'on_scene' => '#ffc107',
            'returning' => '#6c757d',
            'completed' => '#28a745'
        ];
        
        return [
            'icon' => $iconMap[$vehicleType] ?? 'fas fa-truck',
            'color' => $colorMap[$status] ?? '#007bff',
            'size' => [30, 30]
        ];
    }

    private function getFacilityIcon($facilityType, $status) {
        $iconMap = [
            'hospital' => 'fas fa-hospital',
            'police_station' => 'fas fa-building',
            'fire_station' => 'fas fa-fire',
            'emergency_center' => 'fas fa-info-circle'
        ];
        
        $colorMap = [
            'operational' => '#28a745',
            'maintenance' => '#ffc107',
            'offline' => '#dc3545',
            'operational' => '#28a745'
        ];
        
        return [
            'icon' => $iconMap[$facilityType] ?? 'fas fa-info-circle',
            'color' => $colorMap[$status] ?? '#28a745',
            'size' => [25, 25]
        ];
    }

    private function getNearbyIncidents($startLat, $startLng, $endLat, $endLng) {
        // Get incidents within a bounding box around the route
        $minLat = min($startLat, $endLat) - 0.01; // ~1km buffer
        $maxLat = max($startLat, $endLat) + 0.01;
        $minLng = min($startLng, $endLng) - 0.01;
        $maxLng = max($startLng, $endLng) + 0.01;
        
        // This would need to be implemented in the Incident model
        // For now, return all active incidents
        return $this->incidentModel->getByStatus(['reported', 'assigned', 'in_progress']);
    }

    private function getOSRMRouteData($startLat, $startLng, $endLat, $endLng, $avoidIncidents = true) {
        $coordinates = "{$startLng},{$startLat};{$endLng},{$endLat}";
        // Add alternatives=0 to get only the shortest route, overview=full for detailed geometry
        $url = $this->osrmBaseUrl . $coordinates . "?overview=full&geometries=geojson&steps=true&alternatives=0";
        
        $response = file_get_contents($url);
        $routeData = json_decode($response, true);
        
        if (!$routeData || !isset($routeData['routes'][0])) {
            return ['error' => 'Failed to get route from OSRM'];
        }
        
        $route = $routeData['routes'][0];
        
        // If avoiding incidents, check for intersections and reroute if needed
        if ($avoidIncidents) {
            $activeIncidents = $this->incidentModel->getByStatuses(['reported', 'assigned', 'in_progress']);
            $route = $this->checkAndRerouteAroundIncidents($route, $activeIncidents, $startLat, $startLng, $endLat, $endLng);
        }
        
        // Calculate ETA based on route duration and current time
        $eta = $this->calculateETA($route['duration']);
        
        return [
            'route' => $route,
            'distance' => round($route['distance'] / 1000, 2), // Convert to km
            'duration' => round($route['duration'] / 60, 1), // Convert to minutes
            'geometry' => $route['geometry'],
            'steps' => $route['legs'][0]['steps'] ?? [],
            'warnings' => $route['warnings'] ?? [],
            'eta' => $eta,
            'is_shortest_route' => true // OSRM always returns shortest route by default
        ];
    }

    private function calculateIncidentAvoidanceRoute($startLat, $startLng, $endLat, $endLng, $incidents) {
        // Validate that we have valid coordinates
        if (!$this->validateCoordinates($startLat, $startLng, $endLat, $endLng)) {
            return ['error' => 'Invalid coordinates provided'];
        }
        
        // First, get the direct route
        $directRoute = $this->getOSRMRouteData($startLat, $startLng, $endLat, $endLng, false);
        
        if (isset($directRoute['error'])) {
            return $directRoute;
        }
        
        // Check if route intersects with any ACTIVE incidents only
        $intersectingIncidents = $this->checkRouteIncidentIntersection($directRoute, $incidents);
        
        if (empty($intersectingIncidents)) {
            // No incidents to avoid, return direct route
            return $directRoute;
        }
        
        // Route intersects with incidents, calculate alternative routes
        $alternativeRoutes = $this->calculateAlternativeRoutes($startLat, $startLng, $endLat, $endLng, $intersectingIncidents);
        
        if (empty($alternativeRoutes)) {
            // If no alternative routes found, return direct route with warning
            $directRoute['warnings'] = ['Unable to find alternative route, proceeding with direct route'];
            return $directRoute;
        }
        
        // Select the BEST alternative route (only one)
        $bestRoute = $this->selectBestAlternativeRoute($alternativeRoutes, $directRoute);
        
        // Ensure we only return one route with proper validation
        if (isset($bestRoute['error'])) {
            return $directRoute; // Fallback to direct route
        }
        
        return $bestRoute;
    }
    
    private function validateCoordinates($startLat, $startLng, $endLat, $endLng) {
        // Check if coordinates are numeric
        if (!is_numeric($startLat) || !is_numeric($startLng) || !is_numeric($endLat) || !is_numeric($endLng)) {
            return false;
        }
        
        // Check coordinate ranges
        if ($startLat < -90 || $startLat > 90 || $endLat < -90 || $endLat > 90 ||
            $startLng < -180 || $startLng > 180 || $endLng < -180 || $endLng > 180) {
            return false;
        }
        
        // Check if start and end are different
        if ($startLat == $endLat && $startLng == $endLng) {
            return false;
        }
        
        return true;
    }

    private function checkRouteIncidentIntersection($route, $incidents) {
        $intersectingIncidents = [];
        $routeGeometry = $route['geometry']['coordinates'] ?? [];
        
        foreach ($incidents as $incident) {
            if ($this->routeIntersectsIncident($routeGeometry, $incident)) {
                $intersectingIncidents[] = $incident;
            }
        }
        
        return $intersectingIncidents;
    }

    private function routeIntersectsIncident($routeCoordinates, $incident) {
        $incidentLat = $incident['latitude'];
        $incidentLng = $incident['longitude'];
        $incidentRadius = $this->getIncidentAvoidanceRadius($incident['priority']);
        
        // Check if any point on the route is within the incident avoidance radius
        foreach ($routeCoordinates as $coord) {
            $lng = $coord[0];
            $lat = $coord[1];
            
            $distance = $this->haversineDistance($lat, $lng, $incidentLat, $incidentLng);
            
            if ($distance <= $incidentRadius) {
                return true;
            }
        }
        
        return false;
    }

    private function getIncidentAvoidanceRadius($priority) {
        // Define avoidance radius based on incident priority (in km)
        $radiusMap = [
            'critical' => 0.5,  // 500m for critical incidents
            'high' => 0.3,       // 300m for high priority
            'medium' => 0.2,     // 200m for medium priority
            'low' => 0.1         // 100m for low priority
        ];
        
        return $radiusMap[$priority] ?? 0.2;
    }

    private function calculateAlternativeRoutes($startLat, $startLng, $endLat, $endLng, $intersectingIncidents) {
        $alternativeRoutes = [];
        
        // Calculate multiple waypoints to avoid incidents
        $waypoints = $this->calculateAvoidanceWaypoints($startLat, $startLng, $endLat, $endLng, $intersectingIncidents);
        
        foreach ($waypoints as $waypoint) {
            $route = $this->getOSRMRouteWithWaypoints($startLat, $startLng, $waypoint['lat'], $waypoint['lng'], $endLat, $endLng);
            if (!isset($route['error'])) {
                $alternativeRoutes[] = $route;
            }
        }
        
        return $alternativeRoutes;
    }

    private function calculateAvoidanceWaypoints($startLat, $startLng, $endLat, $endLng, $incidents) {
        $waypoints = [];
        
        foreach ($incidents as $incident) {
            $incidentLat = $incident['latitude'];
            $incidentLng = $incident['longitude'];
            $avoidanceRadius = $this->getIncidentAvoidanceRadius($incident['priority']);
            
            // Calculate only ONE optimal waypoint per incident (not two perpendicular offsets)
            $optimalWaypoint = $this->calculateOptimalAvoidanceWaypoint($startLat, $startLng, $endLat, $endLng, $incidentLat, $incidentLng, $avoidanceRadius);
            
            if ($optimalWaypoint) {
                $waypoints[] = [
                    'lat' => $optimalWaypoint['lat'],
                    'lng' => $optimalWaypoint['lng'],
                    'incident_id' => $incident['id'],
                    'priority' => $incident['priority']
                ];
            }
        }
        
        return $waypoints;
    }

    private function calculateOptimalAvoidanceWaypoint($startLat, $startLng, $endLat, $endLng, $incidentLat, $incidentLng, $avoidanceRadius) {
        $optimalWaypoint = null;
        $minDistance = PHP_INT_MAX;

        // Define a grid of potential waypoints to check
        $gridSize = 10; // Number of points to check along the route
        $stepLat = ($endLat - $startLat) / $gridSize;
        $stepLng = ($endLng - $startLng) / $gridSize;

        for ($i = 0; $i <= $gridSize; $i++) {
            for ($j = 0; $j <= $gridSize; $j++) {
                $lat = $startLat + ($i * $stepLat);
                $lng = $startLng + ($j * $stepLng);

                // Ensure the waypoint is not too close to the incident
                $distanceToIncident = $this->haversineDistance($lat, $lng, $incidentLat, $incidentLng);
                if ($distanceToIncident > $avoidanceRadius) {
                    // Calculate distance from start to the waypoint
                    $distance = $this->haversineDistance($startLat, $startLng, $lat, $lng);
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $optimalWaypoint = ['lat' => $lat, 'lng' => $lng];
                    }
                }
            }
        }

        return $optimalWaypoint;
    }

    private function getOSRMRouteWithWaypoints($startLat, $startLng, $waypointLat, $waypointLng, $endLat, $endLng) {
        $coordinates = "{$startLng},{$startLat};{$waypointLng},{$waypointLat};{$endLng},{$endLat}";
        // Ensure we get the shortest route with waypoints
        $url = $this->osrmBaseUrl . $coordinates . "?overview=full&geometries=geojson&steps=true&alternatives=0";
        
        $response = file_get_contents($url);
        $routeData = json_decode($response, true);
        
        if (!$routeData || !isset($routeData['routes'][0])) {
            return ['error' => 'Failed to get alternative route from OSRM'];
        }
        
        $route = $routeData['routes'][0];
        
        // Calculate ETA for this route
        $eta = $this->calculateETA($route['duration']);
        
        return [
            'route' => $route,
            'distance' => round($route['distance'] / 1000, 2),
            'duration' => round($route['duration'] / 60, 1),
            'geometry' => $route['geometry'],
            'steps' => $route['legs'][0]['steps'] ?? [],
            'waypoint' => ['lat' => $waypointLat, 'lng' => $waypointLng],
            'eta' => $eta,
            'is_shortest_route' => true
        ];
    }

    private function selectBestAlternativeRoute($alternativeRoutes, $directRoute) {
        if (empty($alternativeRoutes)) {
            return $directRoute;
        }
        
        // Score routes based on distance, duration, and incident avoidance
        $scoredRoutes = [];
        
        foreach ($alternativeRoutes as $route) {
            $score = $this->calculateRouteScore($route, $directRoute);
            $scoredRoutes[] = [
                'route' => $route,
                'score' => $score
            ];
        }
        
        // Sort by score (higher is better) and return the best
        usort($scoredRoutes, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Return only the best route (single route)
        $bestRoute = $scoredRoutes[0]['route'];
        $bestRoute['is_single_route'] = true;
        $bestRoute['route_type'] = 'incident_avoidance';
        
        return $bestRoute;
    }

    private function calculateRouteScore($route, $directRoute) {
        $score = 100;
        
        // Penalize for longer distance
        $distanceRatio = $route['distance'] / $directRoute['distance'];
        $score -= ($distanceRatio - 1) * 30;
        
        // Penalize for longer duration
        $durationRatio = $route['duration'] / $directRoute['duration'];
        $score -= ($durationRatio - 1) * 40;
        
        // Bonus for avoiding incidents
        $score += 20;
        
        return max(0, $score);
    }

    private function checkAndRerouteAroundIncidents($route, $incidents, $startLat, $startLng, $endLat, $endLng) {
        $intersectingIncidents = $this->checkRouteIncidentIntersection($route, $incidents);
        
        if (!empty($intersectingIncidents)) {
            // Route intersects with incidents, calculate avoidance route
            $avoidanceRoute = $this->calculateIncidentAvoidanceRoute($startLat, $startLng, $endLat, $endLng, $intersectingIncidents);
            
            if (!isset($avoidanceRoute['error'])) {
                $route = $avoidanceRoute;
                $route['warnings'] = $this->generateRouteWarnings($intersectingIncidents);
            }
        }
        
        return $route;
    }

    private function generateRouteWarnings($incidents) {
        $warnings = [];
        
        foreach ($incidents as $incident) {
            $warnings[] = [
                'type' => 'incident_avoidance',
                'message' => "Route adjusted to avoid {$incident['priority']} priority incident: {$incident['title']}",
                'priority' => $incident['priority'],
                'location' => [
                    'lat' => $incident['latitude'],
                    'lng' => $incident['longitude']
                ]
            ];
        }
        
        return $warnings;
    }

    private function calculateOSRMRoute($startLat, $startLng, $endLat, $endLng, $incidents) {
        // Get OSRM route with incident avoidance
        $route = $this->getOSRMRouteData($startLat, $startLng, $endLat, $endLng, true);
        
        if (isset($route['error'])) {
            // Fallback to simple calculation if OSRM fails
            return $this->calculateSimpleRoute($startLat, $startLng, $endLat, $endLng, $incidents);
        }
        
        return $route;
    }

    private function calculateSimpleRoute($startLat, $startLng, $endLat, $endLng, $incidents) {
        // Fallback route calculation
        $distance = $this->haversineDistance($startLat, $startLng, $endLat, $endLng);
        
        return [
            'route' => [
                'start' => [$startLat, $startLng],
                'end' => [$endLat, $endLng],
                'distance' => round($distance, 2),
                'route_points' => [
                    [$startLat, $startLng],
                    [$endLat, $endLng]
                ]
            ],
            'warnings' => $this->generateRouteWarnings($incidents),
            'estimated_duration' => $this->estimateTravelTime($distance),
            'distance' => round($distance, 2)
        ];
    }

    private function estimateTravelTime($distance) {
        // Estimate travel time: 1 minute per km + 5 minutes for setup
        return round($distance + 5);
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

    private function calculateETA($durationInSeconds) {
        // Convert duration from seconds to minutes
        $minutes = round($durationInSeconds / 60);
        
        // Add a buffer for setup time
        $estimatedMinutes = $minutes + 5; // 5 minutes for setup
        
        return $estimatedMinutes;
    }
}
?> 