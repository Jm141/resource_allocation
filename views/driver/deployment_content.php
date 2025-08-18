<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-route text-primary me-2"></i>
        Deployment Details - Driver View
    </h1>
    <div>
        <a href="index.php?action=driver" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
        <span class="badge bg-<?= getStatusColor($deployment['status']) ?> fs-6">
            <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Route Map Card -->
        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>Route & Navigation
                </h5>
            </div>
            <div class="card-body">
                <div id="driverRouteMap" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary w-100" onclick="refreshRoute()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Route
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-warning w-100" onclick="checkTrafficAlerts()">
                                <i class="fas fa-exclamation-triangle me-2"></i>Check Traffic
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Information Card -->
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Route Information
                </h5>
            </div>
            <div class="card-body">
                <div id="routeInfoContainer">
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <p>Calculating route...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Incident Details Card -->
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Incident Details
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Incident</label>
                    <div class="form-control-plaintext">
                        <strong><?= htmlspecialchars($incident['title'] ?? 'N/A') ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Description</label>
                    <div class="form-control-plaintext">
                        <?= htmlspecialchars($incident['description'] ?? 'No description') ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Priority</label>
                    <div class="form-control-plaintext">
                        <span class="badge bg-<?= getPriorityColor($incident['priority'] ?? 'medium') ?>">
                            <?= ucfirst($incident['priority'] ?? 'medium') ?>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Location</label>
                    <div class="form-control-plaintext">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                        <?= htmlspecialchars($incident['location_name'] ?? 'N/A') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Traffic Alerts Card -->
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Traffic Alerts
                </h6>
            </div>
            <div class="card-body">
                <div id="trafficAlertsContainer">
                    <div class="text-center text-muted py-2">
                        <i class="fas fa-spinner fa-spin"></i> Checking...
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-warning w-100 mt-2" onclick="refreshTrafficAlerts()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh Alerts
                </button>
            </div>
        </div>

        <!-- Status Update Card -->
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Update Status
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="updateStatus('en_route')">
                        <i class="fas fa-play me-2"></i>En Route
                    </button>
                    <button type="button" class="btn btn-warning" onclick="updateStatus('on_scene')">
                        <i class="fas fa-map-marker-alt me-2"></i>On Scene
                    </button>
                    <button type="button" class="btn btn-info" onclick="updateStatus('returning')">
                        <i class="fas fa-undo me-2"></i>Returning
                    </button>
                    <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                        <i class="fas fa-check me-2"></i>Completed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Deployment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="">Select Status</option>
                            <option value="en_route">En Route</option>
                            <option value="on_scene">On Scene</option>
                            <option value="returning">Returning</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statusNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" placeholder="Add any notes about the status update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let driverRouteMap;
let currentRouteLayer = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeDriverRouteMap();
    refreshTrafficAlerts();
    
    // Refresh traffic alerts every 30 seconds
    setInterval(refreshTrafficAlerts, 30000);
});

function initializeDriverRouteMap() {
    driverRouteMap = L.map('driverRouteMap').setView([
        (<?= $deployment['start_lat'] ?> + <?= $deployment['end_lat'] ?>) / 2,
        (<?= $deployment['start_lng'] ?> + <?= $deployment['end_lng'] ?>) / 2
    ], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(driverRouteMap);
    
    // Add start marker
    const startMarker = L.marker([<?= $deployment['start_lat'] ?>, <?= $deployment['start_lng'] ?>], {
        icon: L.divIcon({
            html: '<i class="fas fa-play-circle" style="color: #28a745; font-size: 24px;"></i>',
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(driverRouteMap).bindPopup('Start: <?= htmlspecialchars($deployment['start_location']) ?>');
    
    // Add end marker
    const endMarker = L.marker([<?= $deployment['end_lat'] ?>, <?= $deployment['end_lng'] ?>], {
        icon: L.divIcon({
            html: '<i class="fas fa-flag-checkered" style="color: #dc3545; font-size: 24px;"></i>',
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(driverRouteMap).bindPopup('Destination: <?= htmlspecialchars($deployment['end_location']) ?>');
    
    // Fit map to show both markers
    const bounds = L.latLngBounds([startMarker.getLatLng(), endMarker.getLatLng()]);
    driverRouteMap.fitBounds(bounds, { padding: [20, 20] });
    
    // Automatically calculate route
    setTimeout(() => {
        calculateDriverRoute();
    }, 500);
}

function calculateDriverRoute() {
    const startLat = <?= $deployment['start_lat'] ?>;
    const startLng = <?= $deployment['start_lng'] ?>;
    const endLat = <?= $deployment['end_lat'] ?>;
    const endLng = <?= $deployment['end_lng'] ?>;
    
    // Show loading state
    const container = document.getElementById('routeInfoContainer');
    container.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><p>Calculating optimal route...</p></div>';
    
    // Get incident-aware route
    const url = `index.php?action=map&method=getIncidentAvoidanceRoute&start_lat=${startLat}&start_lng=${startLng}&end_lat=${endLat}&end_lng=${endLng}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                container.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
                return;
            }
            
            displayDriverRoute(data);
            showRouteInfo(data);
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="alert alert-danger">Error calculating route. Please try again.</div>';
        });
}

function displayDriverRoute(routeData) {
    // Remove existing route layer
    if (currentRouteLayer) {
        driverRouteMap.removeLayer(currentRouteLayer);
        currentRouteLayer = null;
    }
    
    // Draw new route
    if (routeData.geometry && routeData.geometry.coordinates) {
        const routeCoordinates = routeData.geometry.coordinates.map(coord => [coord[1], coord[0]]);
        
        currentRouteLayer = L.polyline(routeCoordinates, {
            color: '#007bff',
            weight: 5,
            opacity: 0.8
        }).addTo(driverRouteMap);
        
        // Fit map to show entire route
        driverRouteMap.fitBounds(currentRouteLayer.getBounds(), { padding: [20, 20] });
    }
}

function showRouteInfo(routeData) {
    const container = document.getElementById('routeInfoContainer');
    
    let infoHtml = `
        <div class="row">
            <div class="col-6 mb-3">
                <div class="text-center">
                    <div class="h4 text-primary mb-1">${routeData.distance}</div>
                    <small class="text-muted">Distance (km)</small>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-center">
                    <div class="h4 text-success mb-1">${routeData.duration}</div>
                    <small class="text-muted">Duration (min)</small>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="text-center">
                    <div class="h5 text-info mb-1">${routeData.eta}</div>
                    <small class="text-muted">Estimated Arrival</small>
                </div>
            </div>
        </div>
    `;
    
    if (routeData.warnings && routeData.warnings.length > 0) {
        infoHtml += `
            <hr>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Route Adjustments:</strong>
                ${routeData.warnings.map(warning => `<div class="small">• ${warning}</div>`).join('')}
            </div>
        `;
    }
    
    container.innerHTML = infoHtml;
}

function refreshRoute() {
    calculateDriverRoute();
}

function checkTrafficAlerts() {
    refreshTrafficAlerts();
}

function refreshTrafficAlerts() {
    const container = document.getElementById('trafficAlertsContainer');
    container.innerHTML = '<div class="text-center text-muted py-2"><i class="fas fa-spinner fa-spin"></i> Checking...</div>';
    
    fetch(`index.php?action=driver&method=getTrafficAlerts&deployment_id=<?= $deployment['id'] ?>`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                container.innerHTML = '<div class="text-center text-muted py-2"><i class="fas fa-info-circle"></i> No alerts</div>';
                return;
            }
            
            if (data.alerts && data.alerts.length > 0) {
                let alertsHtml = '';
                data.alerts.forEach(alert => {
                    const priorityColor = getPriorityColor(alert.priority);
                    alertsHtml += `
                        <div class="alert alert-${priorityColor} alert-sm mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>${alert.title}</strong><br>
                            <small>${alert.location} - ${alert.distance}km away</small>
                        </div>
                    `;
                });
                container.innerHTML = alertsHtml;
            } else {
                container.innerHTML = '<div class="text-center text-success py-2"><i class="fas fa-check-circle"></i> Clear roads ahead</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="text-center text-muted py-2"><i class="fas fa-exclamation-circle"></i> Error loading alerts</div>';
        });
}

function updateStatus(status) {
    document.getElementById('newStatus').value = status;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('status', document.getElementById('newStatus').value);
    formData.append('notes', document.getElementById('statusNotes').value);
    
    fetch('index.php?action=driver&method=updateStatus&id=<?= $deployment['id'] ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
});

function getPriorityColor(priority) {
    switch (priority) {
        case 'critical': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low': return 'secondary';
        default: return 'info';
    }
}

function getStatusColor(status) {
    switch (status) {
        case 'dispatched': return 'primary';
        case 'en_route': return 'success';
        case 'on_scene': return 'warning';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
</script>

<style>
.alert-sm {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.custom-div-icon {
    background: none;
    border: none;
}

.custom-div-icon i {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}
</style> 