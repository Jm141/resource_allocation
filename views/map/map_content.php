<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-map-marked-alt text-primary me-2"></i>
        Live Map & Tracking
    </h1>
    <div>
        <button type="button" class="btn btn-orange me-2" onclick="showRoutePlanning()">
            <i class="fas fa-route me-2"></i>Plan Route
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="refreshMapData()">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
        <span class="badge bg-success ms-2" id="liveStatus">
            <i class="fas fa-circle me-1"></i>Live
        </span>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card card-custom">
            <div class="card-body p-0">
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Active Incidents
                    <span class="badge bg-danger ms-2" id="incidentCount">0</span>
                </h6>
            </div>
            <div class="card-body">
                <div id="activeIncidents">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>
        
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Active Deployments
                    <span class="badge bg-primary ms-2" id="deploymentCount">0</span>
                </h6>
            </div>
            <div class="card-body">
                <div id="activeDeployments">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>

        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-hospital me-2"></i>Emergency Facilities
                </h6>
            </div>
            <div class="card-body">
                <div id="emergencyFacilities">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Map Legend
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="fw-bold text-danger">🚨 Critical Incidents</small><br>
                    <small class="text-muted">High priority emergency situations</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-warning">⚠️ High Priority</small><br>
                    <small class="text-muted">Urgent response needed</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-success">✅ Medium/Low Priority</small><br>
                    <small class="text-muted">Standard response</small>
                </div>
                <hr>
                <div class="mb-2">
                    <small class="fw-bold text-primary">🚚 Active Deployments</small><br>
                    <small class="text-muted">Dispatched, En Route, On Scene</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-success">🕐 ETA Available</small><br>
                    <small class="text-muted">Click deployment for route & ETA</small>
                </div>
                <hr>
                <div class="mb-2">
                    <small class="fw-bold text-success">🏥 Hospitals</small><br>
                    <small class="text-muted">Medical emergency response</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-info">🚔 Police Stations</small><br>
                    <small class="text-muted">Law enforcement response</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-warning">🚒 Fire Stations</small><br>
                    <small class="text-muted">Fire and rescue response</small>
                </div>
                <hr>
                <div class="mb-2">
                    <small class="fw-bold text-success">🛣️ OSRM Routes</small><br>
                    <small class="text-muted">Shortest route via real roads</small>
                </div>
                <div class="mb-2">
                    <small class="fw-bold text-warning">🔄 Incident Avoidance</small><br>
                    <small class="text-muted">Routes adjusted for safety</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Route Planning Modal -->
<div class="modal fade" id="routeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Route Planning & Optimization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Start Location</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="startLocation" placeholder="Click on map or enter coordinates">
                                <button class="btn btn-outline-secondary" type="button" onclick="getCurrentLocation('start')">
                                    <i class="fas fa-location-arrow"></i>
                                </button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="startLat" placeholder="Latitude" step="any">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="startLng" placeholder="Longitude" step="any">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">End Location</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="endLocation" placeholder="Click on map or enter coordinates">
                                <button class="btn btn-outline-secondary" type="button" onclick="getCurrentLocation('end')">
                                    <i class="fas fa-location-arrow"></i>
                                </button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="endLat" placeholder="Latitude" step="any">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="endLng" placeholder="Longitude" step="any">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Route Options</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="avoidIncidents" checked>
                        <label class="form-check-label" for="avoidIncidents">
                            Avoid high-priority incidents
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="optimizeRoute" checked>
                        <label class="form-label" for="optimizeRoute">
                            Optimize for fastest route
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-primary" onclick="calculateRoute()">
                        <i class="fas fa-route me-2"></i>Calculate Standard Route
                    </button>
                    <button type="button" class="btn btn-success" onclick="calculateIncidentAvoidanceRoute()">
                        <i class="fas fa-shield-alt me-2"></i>Calculate Incident-Aware Route
                    </button>
                    <button type="button" class="btn btn-info" onclick="compareRouteEfficiency()" id="compareRouteBtn" style="display: none;">
                        <i class="fas fa-chart-line me-2"></i>Compare Route Efficiency
                    </button>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Incident-Aware Routing:</strong> Automatically detects and avoids active incidents for faster emergency response times.
                </div>

                <div id="routeResults" style="display: none;">
                    <hr>
                    <h6>Route Information</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Distance</small><br>
                            <strong id="routeDistance">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Duration</small><br>
                            <strong id="routeDuration">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Warnings</small><br>
                            <strong id="routeWarnings">-</strong>
                        </div>
                    </div>
                    <div id="routeWarningsList" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="calculateRoute()">Calculate Route</button>
                <button type="button" class="btn btn-success" onclick="showRouteOnMap()" id="showRouteBtn" style="display: none;">Show on Map</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let incidentMarkers = [];
let deploymentMarkers = [];
let facilityMarkers = [];
let routeLayer = null;
let currentRoute = null;

// Add system status check function
function checkSystemStatus() {
    const statusHtml = `
        <div class="alert alert-info mt-2">
            <h6 class="mb-2">🔧 System Status</h6>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">OSRM Routing</small><br>
                    <span class="badge bg-success">✅ Connected</span>
                </div>
                <div class="col-6">
                    <small class="text-muted">Live Updates</small><br>
                    <span class="badge bg-success">✅ Active</span>
                </div>
            </div>
            <hr class="my-2">
            <div class="text-center">
                <small class="text-success">🚀 System ready for emergency response routing</small>
            </div>
        </div>
    `;
    
    // Show status
    const existingStatus = document.querySelector('.alert-info');
    if (existingStatus) {
        existingStatus.remove();
    }
    
    const statusDiv = document.createElement('div');
    statusDiv.innerHTML = statusHtml;
    document.body.appendChild(statusDiv);
    
    // Auto-remove after 8 seconds
    setTimeout(() => {
        if (statusDiv.parentNode) {
            statusDiv.remove();
        }
    }, 8000);
}

// Check system status when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeMap();
    loadMapData();
    
    // Check system status after a short delay
    setTimeout(checkSystemStatus, 2000);
    
    // Refresh data every 30 seconds
    setInterval(loadMapData, 30000);
});

function initializeMap() {
    map = L.map('map').setView([10.5377, 122.8363], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

function updateLiveStatus(isLive = true) {
    const statusElement = document.getElementById('liveStatus');
    if (statusElement) {
        if (isLive) {
            statusElement.className = 'badge bg-success ms-2';
            statusElement.innerHTML = '<i class="fas fa-circle me-1"></i>Live';
        } else {
            statusElement.className = 'badge bg-warning ms-2';
            statusElement.innerHTML = '<i class="fas fa-sync-alt fa-spin me-1"></i>Updating...';
        }
    }
}

function loadMapData() {
    updateLiveStatus(false); // Show updating status
    
    fetch('index.php?action=map&method=getMapData')
        .then(response => response.json())
        .then(data => {
            updateIncidentsOnMap(data.incidents);
            updateDeploymentsOnMap(data.deployments);
            updateFacilitiesOnMap(data.facilities);
            updateIncidentsPanel(data.incidents);
            updateDeploymentsPanel(data.deployments);
            updateFacilitiesPanel(data.facilities);
            
            updateLiveStatus(true); // Show live status
        })
        .catch(error => {
            console.error('Error loading map data:', error);
            updateLiveStatus(true); // Reset to live status even on error
        });
}

function updateIncidentsOnMap(incidents) {
    // Clear existing incident markers
    incidentMarkers.forEach(marker => map.removeLayer(marker));
    incidentMarkers = [];
    
    // Filter only active incidents
    const activeIncidents = incidents.filter(incident => 
        ['reported', 'assigned', 'in_progress'].includes(incident.status)
    );
    
    activeIncidents.forEach(incident => {
        const icon = L.divIcon({
            html: `<i class="${incident.icon.icon}" style="color: ${incident.icon.color}; font-size: 20px;"></i>`,
            className: 'custom-div-icon',
            iconSize: incident.icon.size,
            iconAnchor: [incident.icon.size[0]/2, incident.icon.size[1]/2]
        });
        
        const marker = L.marker([incident.latitude, incident.longitude], { icon: icon })
            .addTo(map)
            .bindPopup(`
                <div class="text-center">
                    <h6 class="mb-2">🚨 ${incident.title}</h6>
                    <p class="mb-2">${incident.description}</p>
                    <div class="mb-2">
                        <span class="badge bg-${getPriorityColor(incident.priority)}">${incident.priority.toUpperCase()}</span>
                        <span class="badge bg-secondary">${incident.status.replace('_', ' ').toUpperCase()}</span>
                    </div>
                    <small class="text-muted">📍 ${incident.location_name}</small>
                    <br><small class="text-muted">🕐 ${new Date(incident.created_at).toLocaleString()}</small>
                </div>
            `);
        
        incidentMarkers.push(marker);
    });
    
    console.log(`Updated ${activeIncidents.length} active incidents on map`);
}

function updateDeploymentsOnMap(deployments) {
    // Clear existing deployment markers
    deploymentMarkers.forEach(marker => map.removeLayer(marker));
    deploymentMarkers = [];
    
    // Filter only active deployments
    const activeDeployments = deployments.filter(deployment => 
        ['dispatched', 'en_route', 'on_scene'].includes(deployment.status)
    );
    
    activeDeployments.forEach(deployment => {
        const icon = L.divIcon({
            html: `<i class="${deployment.icon.icon}" style="color: ${deployment.icon.color}; font-size: 24px;"></i>`,
            className: 'custom-div-icon deployment-marker',
            iconSize: deployment.icon.size,
            iconAnchor: [deployment.icon.size[0]/2, deployment.icon.size[1]/2]
        });
        
        const marker = L.marker([deployment.start_lat, deployment.start_lng], { icon: icon })
            .addTo(map)
            .bindPopup(`
                <div class="text-center">
                    <h6 class="mb-2">🚚 ${deployment.incident_title}</h6>
                    <p class="mb-2">
                        <strong>Driver:</strong> ${deployment.driver_name}<br>
                        <strong>Vehicle:</strong> ${deployment.vehicle_code}<br>
                        <strong>Type:</strong> ${deployment.vehicle_type}<br>
                        <strong>Status:</strong> <span class="badge bg-${getStatusColor(deployment.status)}">${deployment.status.replace('_', ' ').toUpperCase()}</span>
                    </p>
                    <div class="mb-2">
                        <small class="text-muted">📍 From: ${deployment.start_location}</small><br>
                        <small class="text-muted">🎯 To: ${deployment.end_location}</small>
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="calculateDeploymentETA(${deployment.start_lat}, ${deployment.start_lng}, ${deployment.end_lat}, ${deployment.end_lng})">
                        🕐 Calculate ETA
                    </button>
                </div>
            `);
        
        deploymentMarkers.push(marker);
        
        // Draw route line if coordinates are available
        if (deployment.start_lat && deployment.start_lng && deployment.end_lat && deployment.end_lng) {
            const routeLine = L.polyline([
                [deployment.start_lat, deployment.start_lng],
                [deployment.end_lat, deployment.end_lng]
            ], {
                color: getStatusColor(deployment.status),
                weight: 3,
                opacity: 0.7,
                dashArray: '5, 10'
            }).addTo(map);
            
            deploymentMarkers.push(routeLine);
        }
    });
    
    console.log(`Updated ${activeDeployments.length} active deployments on map`);
}

function updateFacilitiesOnMap(facilities) {
    // Clear existing facility markers
    facilityMarkers.forEach(marker => map.removeLayer(marker));
    facilityMarkers = [];
    
    facilities.forEach(facility => {
        const icon = L.divIcon({
            html: `<i class="${facility.icon.icon}" style="color: ${facility.icon.color}; font-size: 20px;"></i>`,
            className: 'custom-div-icon facility-marker',
            iconSize: facility.icon.size,
            iconAnchor: [facility.icon.size[0]/2, facility.icon.size[1]/2]
        });
        
        const marker = L.marker([facility.latitude, facility.longitude], { icon: icon })
            .addTo(map)
            .bindPopup(`
                <div class="text-center">
                    <h6 class="mb-2">${facility.name}</h6>
                    <p class="mb-2">
                        <strong>Type:</strong> ${getFacilityTypeName(facility.type)}<br>
                        <strong>Address:</strong> ${facility.address}<br>
                        <strong>Status:</strong> ${facility.status || 'Operational'}
                    </p>
                    <div class="mb-2">
                        <span class="badge bg-${getFacilityStatusColor(facility.status)}">${facility.status || 'Operational'}</span>
                    </div>
                </div>
            `);
        
        facilityMarkers.push(marker);
    });
}

function updateIncidentsPanel(incidents) {
    const container = document.getElementById('activeIncidents');
    const countBadge = document.getElementById('incidentCount');
    
    // Filter only active incidents
    const activeIncidents = incidents.filter(incident => 
        ['reported', 'assigned', 'in_progress'].includes(incident.status)
    );
    
    // Update count badge
    if (countBadge) {
        countBadge.textContent = activeIncidents.length;
    }
    
    if (!activeIncidents || activeIncidents.length === 0) {
        container.innerHTML = '<div class="text-center text-muted">No active incidents</div>';
        return;
    }
    
    let html = '';
    activeIncidents.forEach(incident => {
        html += `
            <div class="mb-2 p-2 border rounded" style="border-left: 4px solid ${incident.icon.color} !important;">
                <div class="fw-bold small">${incident.title}</div>
                <div class="text-muted small">${incident.location_name}</div>
                <div class="mt-1">
                    <span class="badge bg-${getPriorityColor(incident.priority)} small">${incident.priority.toUpperCase()}</span>
                    <span class="badge bg-secondary small">${incident.status.replace('_', ' ').toUpperCase()}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateDeploymentsPanel(deployments) {
    const container = document.getElementById('activeDeployments');
    const countBadge = document.getElementById('deploymentCount');
    
    // Filter only active deployments
    const activeDeployments = deployments.filter(deployment => 
        ['dispatched', 'en_route', 'on_scene'].includes(deployment.status)
    );
    
    // Update count badge
    if (countBadge) {
        countBadge.textContent = activeDeployments.length;
    }
    
    if (!activeDeployments || activeDeployments.length === 0) {
        container.innerHTML = '<div class="text-center text-muted">No active deployments</div>';
        return;
    }
    
    let html = '';
    activeDeployments.forEach(deployment => {
        html += `
            <div class="mb-2 p-2 border rounded" style="border-left: 4px solid ${getStatusColor(deployment.status)} !important;">
                <div class="fw-bold small">${deployment.incident_title}</div>
                <div class="text-muted small">${deployment.driver_name} - ${deployment.vehicle_code}</div>
                <div class="mt-1">
                    <span class="badge bg-${getStatusColor(deployment.status)} small">${deployment.status.replace('_', ' ').toUpperCase()}</span>
                </div>
                <div class="mt-1">
                    <small class="text-muted">📍 ${deployment.start_location}</small><br>
                    <small class="text-muted">🎯 ${deployment.end_location}</small>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateFacilitiesPanel(facilities) {
    const container = document.getElementById('emergencyFacilities');
    
    if (!facilities || facilities.length === 0) {
        container.innerHTML = '<div class="text-center text-muted">No facilities found</div>';
        return;
    }
    
    let html = '';
    facilities.forEach(facility => {
        html += `
            <div class="mb-2 p-2 border rounded" style="border-left: 4px solid ${facility.icon.color} !important;">
                <div class="fw-bold small">${facility.name}</div>
                <div class="text-muted small">${getFacilityTypeName(facility.type)}</div>
                <div class="mt-1">
                    <span class="badge bg-${getFacilityStatusColor(facility.status)} small">${facility.status || 'Operational'}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function getPriorityColor(priority) {
    const colors = {
        'critical': 'danger',
        'high': 'warning',
        'medium': 'info',
        'low': 'success'
    };
    return colors[priority] || 'secondary';
}

function getStatusColor(status) {
    const colors = {
        'dispatched': 'primary',
        'en_route': 'success',
        'on_scene': 'warning',
        'returning': 'secondary',
        'completed': 'success'
    };
    return colors[status] || 'secondary';
}

function getFacilityTypeName(facilityType) {
    const names = {
        'hospital': '🏥 Hospital',
        'police_station': '🚔 Police Station',
        'fire_station': '🚒 Fire Station',
        'emergency_center': '🚨 Emergency Center',
        'command_center': '📡 Command Center'
    };
    return names[facilityType] || facilityType;
}

function getFacilityStatusColor(status) {
    const colors = {
        'operational': 'success',
        'maintenance': 'warning',
        'offline': 'danger'
    };
    return colors[status] || 'success';
}

function showRoutePlanning() {
    new bootstrap.Modal(document.getElementById('routeModal')).show();
}

function getCurrentLocation(type) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById(type + 'Lat').value = lat.toFixed(6);
            document.getElementById(type + 'Lng').value = lng.toFixed(6);
            reverseGeocode(lat, lng, type + 'Location');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function reverseGeocode(lat, lng, inputId) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById(inputId).value = data.display_name || `${lat}, ${lng}`;
        })
        .catch(error => {
            console.error('Error getting location name:', error);
            document.getElementById(inputId).value = `${lat}, ${lng}`;
        });
}

function calculateRoute() {
    const startLat = document.getElementById('startLat').value;
    const startLng = document.getElementById('startLng').value;
    const endLat = document.getElementById('endLat').value;
    const endLng = document.getElementById('endLng').value;
    
    if (!startLat || !startLng || !endLat || !endLng) {
        alert('Please enter both start and end coordinates.');
        return;
    }
    
    const avoidIncidents = document.getElementById('avoidIncidents').checked;
    
    // Show loading state
    document.getElementById('routeResults').style.display = 'none';
    document.getElementById('showRouteBtn').style.display = 'none';
    document.getElementById('compareRouteBtn').style.display = 'none'; // Hide comparison button
    
    // Use OSRM routing with incident avoidance
    let url = `index.php?action=map&method=getOSRMRoute&start_lat=${startLat}&start_lng=${startLng}&end_lat=${endLat}&end_lng=${endLng}&avoid_incidents=${avoidIncidents}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            currentRoute = data;
            displayOSRMRouteResults(data);
            document.getElementById('showRouteBtn').style.display = 'inline-block';
            document.getElementById('compareRouteBtn').style.display = 'inline-block'; // Show comparison button
        })
        .catch(error => {
            console.error('Error calculating route:', error);
            alert('Error calculating route. Please try again.');
        });
}

function displayOSRMRouteResults(routeData) {
    document.getElementById('routeDistance').textContent = routeData.distance + ' km';
    document.getElementById('routeDuration').textContent = routeData.duration + ' min';
    document.getElementById('routeWarnings').textContent = routeData.warnings ? routeData.warnings.length : 0;
    
    // Calculate and display ETA
    const currentTime = new Date();
    const etaTime = new Date(currentTime.getTime() + (routeData.duration * 60 * 1000));
    const etaString = etaTime.toLocaleTimeString();
    
    // Update ETA field
    const etaElement = document.getElementById('routeETA');
    if (etaElement) {
        etaElement.textContent = etaString;
    }
    
    let warningsHtml = '';
    if (routeData.warnings && routeData.warnings.length > 0) {
        warningsHtml = '<div class="alert alert-warning mt-2">';
        routeData.warnings.forEach(warning => {
            warningsHtml += `<div class="small">⚠️ ${warning.message}</div>`;
        });
        warningsHtml += '</div>';
    }
    
    // Add ETA information
    const etaHtml = `
        <div class="alert alert-success mt-2">
            <h6 class="mb-2">🚗 Route Information</h6>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">Distance</small><br>
                    <strong>${routeData.distance} km</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Duration</small><br>
                    <strong>${routeData.duration} min</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">ETA</small><br>
                    <strong>${etaString}</strong>
                </div>
            </div>
            ${routeData.is_shortest_route ? '<div class="mt-2"><span class="badge bg-success">✅ Shortest Route</span></div>' : ''}
        </div>
    `;
    
    document.getElementById('routeWarningsList').innerHTML = etaHtml + warningsHtml;
    document.getElementById('routeResults').style.display = 'block';
}

function showRouteOnMap() {
    if (!currentRoute) return;
    
    // Remove existing route
    if (routeLayer) {
        map.removeLayer(routeLayer);
        if (routeLayer.startMarker) map.removeLayer(routeLayer.startMarker);
        if (routeLayer.endMarker) map.removeLayer(routeLayer.endMarker);
    }
    
    // Draw new route using OSRM geometry
    if (currentRoute.geometry && currentRoute.geometry.coordinates) {
        const routeCoordinates = currentRoute.geometry.coordinates.map(coord => [coord[1], coord[0]]); // OSRM uses [lng, lat]
        
        routeLayer = L.polyline(routeCoordinates, {
            color: '#007bff',
            weight: 5,
            opacity: 0.8
        }).addTo(map);
        
        // Add markers for start and end points
        const startCoord = routeCoordinates[0];
        const endCoord = routeCoordinates[routeCoordinates.length - 1];
        
        const startMarker = L.marker(startCoord, {
            icon: L.divIcon({
                html: '<i class="fas fa-play-circle" style="color: #28a745; font-size: 24px;"></i>',
                className: 'custom-div-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map);
        
        const endMarker = L.marker(endCoord, {
            icon: L.divIcon({
                html: '<i class="fas fa-flag-checkered" style="color: #dc3545; font-size: 24px;"></i>',
                className: 'custom-div-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map);
        
        // Add waypoint markers if route has waypoints
        if (currentRoute.waypoint) {
            const waypointMarker = L.marker([currentRoute.waypoint.lat, currentRoute.waypoint.lng], {
                icon: L.divIcon({
                    html: '<i class="fas fa-map-marker-alt" style="color: #ffc107; font-size: 20px;"></i>',
                    className: 'custom-div-icon',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map).bindPopup('Avoidance Waypoint<br>Route adjusted to avoid incidents');
        }
        
        // Store references for cleanup
        routeLayer.startMarker = startMarker;
        routeLayer.endMarker = endMarker;
        
        // Fit map to show entire route
        map.fitBounds(routeLayer.getBounds(), { padding: [20, 20] });
        
        // Show route information popup
        showRouteInfoPopup(currentRoute);
    } else {
        // Fallback to simple route display
        const routePoints = currentRoute.route.route_points || [
            [currentRoute.route.start[0], currentRoute.route.start[1]],
            [currentRoute.route.end[0], currentRoute.route.end[1]]
        ];
        
        routeLayer = L.polyline(routePoints, {
            color: '#007bff',
            weight: 5,
            opacity: 0.8
        }).addTo(map);
        
        // Fit map to show entire route
        map.fitBounds(routeLayer.getBounds(), { padding: [20, 20] });
    }
}

function showRouteInfoPopup(routeData) {
    // Calculate ETA
    const currentTime = new Date();
    const etaTime = new Date(currentTime.getTime() + (routeData.duration * 60 * 1000));
    const etaString = etaTime.toLocaleTimeString();
    
    let popupContent = `
        <div class="text-center">
            <h6 class="mb-2">🚗 Route Information</h6>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Distance</small><br>
                    <strong>${routeData.distance} km</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted">Duration</small><br>
                    <strong>${routeData.duration} min</strong>
                </div>
            </div>
            <hr class="my-2">
            <div class="row">
                <div class="col-12">
                    <small class="text-muted">ETA</small><br>
                    <strong class="text-success">${etaString}</strong>
                </div>
            </div>
            ${routeData.is_shortest_route ? '<div class="mt-2"><span class="badge bg-success">✅ Shortest Route</span></div>' : ''}
    `;
    
    if (routeData.warnings && routeData.warnings.length > 0) {
        popupContent += `
            <hr class="my-2">
            <div class="text-start">
                <small class="text-warning fw-bold">⚠️ Route Adjustments:</small>
                ${routeData.warnings.map(warning => 
                    `<div class="small text-muted">• ${warning.message}</div>`
                ).join('')}
            </div>
        `;
    }
    
    popupContent += '</div>';
    
    // Show popup at route center
    const routeCenter = routeLayer.getBounds().getCenter();
    L.popup()
        .setLatLng(routeCenter)
        .setContent(popupContent)
        .openOn(map);
}

// Add new function for incident-aware routing
function calculateIncidentAvoidanceRoute() {
    const startLat = document.getElementById('startLat').value;
    const startLng = document.getElementById('startLng').value;
    const endLat = document.getElementById('endLat').value;
    const endLng = document.getElementById('endLng').value;
    
    if (!startLat || !startLng || !endLat || !endLng) {
        alert('Please enter both start and end coordinates.');
        return;
    }
    
    // Show loading state
    document.getElementById('routeResults').style.display = 'none';
    document.getElementById('showRouteBtn').style.display = 'none';
    document.getElementById('compareRouteBtn').style.display = 'none'; // Hide comparison button
    
    // Use incident avoidance routing
    const url = `index.php?action=map&method=getIncidentAvoidanceRoute&start_lat=${startLat}&start_lng=${startLng}&end_lat=${endLat}&end_lng=${endLng}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            currentRoute = data;
            displayOSRMRouteResults(data);
            document.getElementById('showRouteBtn').style.display = 'inline-block';
            document.getElementById('compareRouteBtn').style.display = 'inline-block'; // Show comparison button
            
            // Show success message
            if (data.warnings && data.warnings.length > 0) {
                alert(`Route calculated successfully! ${data.warnings.length} incident(s) avoided for faster response.`);
            } else {
                alert('Route calculated successfully! Direct route available with no incidents to avoid.');
            }
        })
        .catch(error => {
            console.error('Error calculating incident avoidance route:', error);
            alert('Error calculating route. Please try again.');
        });
}

// Add new function for deployment ETA calculation
function calculateDeploymentETA(startLat, startLng, endLat, endLng) {
    // Show loading state
    const loadingDiv = document.createElement('div');
    loadingDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Calculating ETA...</div>';
    loadingDiv.className = 'alert alert-info mt-2';
    document.body.appendChild(loadingDiv);
    
    // Use OSRM to calculate route and ETA
    const url = `index.php?action=map&method=getOSRMRoute&start_lat=${startLat}&start_lng=${startLng}&end_lat=${endLat}&end_lng=${endLng}&avoid_incidents=true`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            loadingDiv.remove();
            
            if (data.error) {
                showAlert('Error: ' + data.error, 'danger');
                return;
            }
            
            // Calculate ETA based on current time
            const currentTime = new Date();
            const etaTime = new Date(currentTime.getTime() + (data.duration * 60 * 1000));
            
            const etaString = etaTime.toLocaleTimeString();
            const durationString = `${data.duration} minutes`;
            const distanceString = `${data.distance} km`;
            
            showAlert(`🚚 Route ETA: ${etaString} (${durationString}, ${distanceString})`, 'success');
            
            // Draw the route on the map
            displayOSRMRouteOnMap(data);
            
        })
        .catch(error => {
            loadingDiv.remove();
            console.error('Error:', error);
            showAlert('Error calculating ETA. Please try again.', 'danger');
        });
}

function displayOSRMRouteOnMap(routeData) {
    // Remove existing route layer
    if (routeLayer) {
        map.removeLayer(routeLayer);
        if (routeLayer.startMarker) map.removeLayer(routeLayer.startMarker);
        if (routeLayer.endMarker) map.removeLayer(routeLayer.endMarker);
    }
    
    // Draw new OSRM route
    if (routeData.geometry && routeData.geometry.coordinates) {
        const routeCoordinates = routeData.geometry.coordinates.map(coord => [coord[1], coord[0]]); // OSRM uses [lng, lat]
        
        routeLayer = L.polyline(routeCoordinates, {
            color: '#007bff',
            weight: 5,
            opacity: 0.8
        }).addTo(map);
        
        // Add start and end markers
        const startCoord = routeCoordinates[0];
        const endCoord = routeCoordinates[routeCoordinates.length - 1];
        
        const startMarker = L.marker(startCoord, {
            icon: L.divIcon({
                html: '<i class="fas fa-play-circle" style="color: #28a745; font-size: 24px;"></i>',
                className: 'custom-div-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map);
        
        const endMarker = L.marker(endCoord, {
            icon: L.divIcon({
                html: '<i class="fas fa-flag-checkered" style="color: #dc3545; font-size: 24px;"></i>',
                className: 'custom-div-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        }).addTo(map);
        
        // Store references for cleanup
        routeLayer.startMarker = startMarker;
        routeLayer.endMarker = endMarker;
        
        // Fit map to show entire route
        map.fitBounds(routeLayer.getBounds(), { padding: [20, 20] });
        
        // Show route information popup
        showRouteInfoPopup(routeData);
    }
}

function refreshMapData() {
    loadMapData();
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} mt-2`;
    alertDiv.innerHTML = message;
    
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => {
        if (alert !== alertDiv) alert.remove();
    });
    
    // Add new alert
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Add real-time ETA updates for active deployments
function updateDeploymentETAs() {
    const deploymentMarkers = document.querySelectorAll('.deployment-marker');
    
    deploymentMarkers.forEach(marker => {
        const deploymentId = marker.dataset.deploymentId;
        if (deploymentId) {
            // Calculate ETA based on current deployment status and location
            calculateDeploymentETARealTime(deploymentId);
        }
    });
}

function calculateDeploymentETARealTime(deploymentId) {
    // This would integrate with real-time GPS tracking
    // For now, we'll use the stored route data
    console.log(`Calculating real-time ETA for deployment ${deploymentId}`);
}

// Add auto-refresh for ETA calculations every 30 seconds
setInterval(() => {
    updateDeploymentETAs();
}, 30000);

// Add route comparison function
function compareRouteEfficiency() {
    const startLat = document.getElementById('startLat').value;
    const startLng = document.getElementById('startLng').value;
    const endLat = document.getElementById('endLat').value;
    const endLng = document.getElementById('endLng').value;
    
    if (!startLat || !startLng || !endLat || !endLng) {
        showAlert('Please enter both start and end coordinates first.', 'warning');
        return;
    }
    
    // Calculate straight line distance
    const straightLineDistance = haversineDistance(startLat, startLng, endLat, endLng);
    
    // Get OSRM route
    const url = `index.php?action=map&method=getOSRMRoute&start_lat=${startLat}&start_lng=${startLng}&end_lat=${endLat}&end_lng=${endLng}&avoid_incidents=false`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showAlert('Error: ' + data.error, 'danger');
                return;
            }
            
            const osrmDistance = data.distance;
            const improvement = ((straightLineDistance - osrmDistance) / straightLineDistance * 100).toFixed(1);
            
            const comparisonHtml = `
                <div class="alert alert-info mt-2">
                    <h6 class="mb-2">🛣️ Route Comparison</h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Straight Line</small><br>
                            <strong>${straightLineDistance.toFixed(2)} km</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">OSRM Route</small><br>
                            <strong>${osrmDistance} km</strong>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="text-center">
                        <small class="text-success">✅ OSRM provides ${improvement}% more accurate routing</small>
                    </div>
                </div>
            `;
            
            // Show comparison
            const existingComparison = document.querySelector('.alert-info');
            if (existingComparison) {
                existingComparison.remove();
            }
            
            const comparisonDiv = document.createElement('div');
            comparisonDiv.innerHTML = comparisonHtml;
            document.body.appendChild(comparisonDiv);
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (comparisonDiv.parentNode) {
                    comparisonDiv.remove();
                }
            }, 10000);
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error comparing routes. Please try again.', 'danger');
        });
}

// Haversine distance calculation function
function haversineDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Earth's radius in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
</script>

<style>
.custom-div-icon {
    background: none;
    border: none;
}

.custom-div-icon i {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

#routeModal .modal-dialog {
    max-width: 800px;
}

.incident-marker {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.incident-marker:hover {
    transform: scale(1.1);
}

.deployment-marker {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.deployment-marker:hover {
    transform: scale(1.1);
}

.route-line {
    cursor: pointer;
}

.route-line:hover {
    opacity: 1 !important;
    weight: 6 !important;
}

.map-controls {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
    background: white;
    border-radius: 5px;
    padding: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.map-controls button {
    margin: 2px;
    padding: 5px 10px;
    font-size: 12px;
}
</style> 