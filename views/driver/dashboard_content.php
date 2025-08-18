<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-user-tie text-success me-2"></i>
        Driver Dashboard
    </h1>
    <div>
        <span class="badge bg-success me-2">
            <i class="fas fa-circle me-1"></i>Driver Online
        </span>
        <small class="text-muted"><?= htmlspecialchars($driver['first_name'] . ' ' . $driver['last_name']) ?></small>
    </div>
</div>

<!-- Driver Information Row -->
<div class="row mb-4">
    <div class="col-lg-3 mb-3">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2"></i>Driver Info
                </h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-user-tie fa-3x text-success mb-2"></i>
                <h6><?= htmlspecialchars($driver['first_name'] . ' ' . $driver['last_name']) ?></h6>
                <span class="badge bg-secondary"><?= htmlspecialchars($driver['driver_id']) ?></span>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Status</small><br>
                        <span class="badge bg-<?= $driver['status'] === 'available' ? 'success' : 'warning' ?>">
                            <?= ucfirst($driver['status'] ?? 'unknown') ?>
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">License</small><br>
                        <strong><?= htmlspecialchars($driver['license_number'] ?? 'N/A') ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Vehicle
                </h6>
            </div>
            <div class="card-body text-center">
                <?php if ($vehicle): ?>
                    <i class="fas fa-truck fa-3x text-info mb-2"></i>
                    <h6><?= htmlspecialchars($vehicle['vehicle_id']) ?></h6>
                    <span class="badge bg-info"><?= htmlspecialchars($vehicle['vehicle_type']) ?></span>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Model</small><br>
                            <strong><?= htmlspecialchars($vehicle['model'] ?? 'N/A') ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status</small><br>
                            <span class="badge bg-<?= $vehicle['status'] === 'available' ? 'success' : 'warning' ?>">
                                <?= ucfirst($vehicle['status'] ?? 'unknown') ?>
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-truck fa-2x mb-2"></i>
                        <p>No vehicle assigned</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-route me-2"></i>Active Deployments
                </h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-route fa-3x text-primary mb-2"></i>
                <h2 class="text-primary mb-0"><?= count($activeDeployments) ?></h2>
                <p class="mb-0">Active Deployments</p>
                <hr>
                <small class="text-muted">Click on map to view details</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>Location
                </h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-map-marker-alt fa-3x text-danger mb-2"></i>
                <div id="currentLocation">
                    <small class="text-muted">Getting location...</small>
                </div>
                <hr>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="getCurrentLocation()">
                    <i class="fas fa-crosshairs me-2"></i>Update Location
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Map Section -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>Active Deployments Map
                </h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="centerOnDriver()">
                        <i class="fas fa-crosshairs me-2"></i>Center on Driver
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="refreshDeployments()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="driverMap" style="height: 500px; width: 100%;">
                    <div class="text-center py-5">
                        <i class="fas fa-map fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Loading map...</h6>
                        <small class="text-muted">Please wait while the map initializes</small>
                        <br><br>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="manualMapInit()" id="manualInitBtn" style="display: none;">
                            <i class="fas fa-play me-2"></i>Initialize Map Manually
                        </button>
                    </div>
                </div>
                <div id="mapError" class="text-center py-5" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h6 class="text-warning">Map could not be loaded</h6>
                    <small class="text-muted">Please refresh the page or check your internet connection</small>
                    <br>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="retryMapInitialization()">
                        <i class="fas fa-redo me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Deployments List -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Deployment Details
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($activeDeployments)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-route fa-3x mb-3"></i>
                        <h5>No Active Deployments</h5>
                        <p>You currently have no active deployments.</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($activeDeployments as $deployment): ?>
                        <div class="col-lg-6 mb-3">
                            <div class="card border-<?= getStatusColor($deployment['status']) ?> h-100">
                                <div class="card-header bg-<?= getStatusColor($deployment['status']) ?> text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-truck me-2"></i>
                                            <strong>Deployment #<?= htmlspecialchars($deployment['deployment_id']) ?></strong>
                                        </div>
                                        <span class="badge bg-light text-dark">
                                            <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Incident</small><br>
                                            <strong><?= htmlspecialchars($deployment['incident_title'] ?? 'N/A') ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Priority</small><br>
                                            <span class="badge bg-<?= getPriorityColor($deployment['priority'] ?? 'medium') ?>">
                                                <?= ucfirst($deployment['priority'] ?? 'medium') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Destination</small><br>
                                            <strong><?= htmlspecialchars($deployment['end_location'] ?? 'N/A') ?></strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">ETA</small><br>
                                            <strong id="eta-<?= $deployment['id'] ?>">Calculating...</strong>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Distance</small><br>
                                            <strong id="distance-<?= $deployment['id'] ?>">Calculating...</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Status</small><br>
                                            <span class="badge bg-<?= getStatusColor($deployment['status']) ?>">
                                                <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary btn-sm w-100 mb-2" 
                                                onclick="centerOnDeployment(<?= $deployment['id'] ?>)">
                                            <i class="fas fa-map-marked-alt me-2"></i>Center on Map
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm w-100" 
                                                onclick="updateDeploymentStatus(<?= $deployment['id'] ?>, '<?= $deployment['status'] ?>')">
                                            <i class="fas fa-edit me-2"></i>Update Status
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
                        <textarea class="form-control" id="statusNotes" name="notes" rows="3" 
                                  placeholder="Add any additional notes..."></textarea>
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

<!-- Map and Location Scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Fallback for Leaflet if CDN fails -->
<script>
// Fallback mechanism for Leaflet
window.addEventListener('error', function(e) {
    if (e.target.src && e.target.src.includes('leaflet')) {
        console.error('Leaflet CDN failed to load:', e.target.src);
        // Try alternative CDN
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js';
        script.onload = function() {
            console.log('Leaflet loaded from fallback CDN');
            // Trigger map initialization if DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeMapWhenReady);
            } else {
                initializeMapWhenReady();
            }
        };
        script.onerror = function() {
            console.error('All Leaflet CDNs failed');
            showMapError('Leaflet library could not be loaded. Please check your internet connection.');
        };
        document.head.appendChild(script);
        
        // Also try alternative CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css';
        document.head.appendChild(link);
    }
});

function initializeMapWhenReady() {
    console.log('Map initialization triggered from fallback');
    if (typeof L !== 'undefined') {
        simpleMapInit();
    }
}
</script>

<script>
// Wait for Leaflet to be fully loaded before proceeding
function waitForLeaflet(callback) {
    if (typeof L !== 'undefined' && L.map) {
        console.log('Leaflet is ready, proceeding...');
        callback();
    } else {
        console.log('Waiting for Leaflet to load...');
        setTimeout(() => waitForLeaflet(callback), 100);
    }
}

// Basic debugging - this should show up immediately
console.log('=== DRIVER DASHBOARD SCRIPT LOADED ===');
console.log('Current time:', new Date().toISOString());
console.log('Active deployments:', <?= json_encode($activeDeployments) ?>);

let driverMap;
let driverMarker;
let deploymentMarkers = {};
let deploymentRoutes = {};
let currentLocation = null;
let activeDeployments = <?= json_encode($activeDeployments) ?>;

// Test if we can access DOM elements
console.log('Testing DOM access...');
console.log('Map container exists:', !!document.getElementById('driverMap'));
console.log('Status modal exists:', !!document.getElementById('statusModal'));

// Simple test function
function testBasicFunctionality() {
    console.log('=== TESTING BASIC FUNCTIONALITY ===');
    
    // Test 1: DOM elements
    const mapContainer = document.getElementById('driverMap');
    console.log('Map container:', mapContainer);
    
    if (mapContainer) {
        console.log('Map container innerHTML length:', mapContainer.innerHTML.length);
        console.log('Map container style:', mapContainer.style.cssText);
    }
    
    // Test 2: Leaflet availability
    console.log('Leaflet available:', typeof L !== 'undefined');
    if (typeof L !== 'undefined') {
        console.log('Leaflet version:', L.version);
    }
    
    // Test 3: Simple DOM manipulation
    try {
        const testDiv = document.createElement('div');
        testDiv.innerHTML = '<strong>TEST DIV</strong>';
        testDiv.style.cssText = 'background: red; color: white; padding: 10px; margin: 10px;';
        document.body.appendChild(testDiv);
        console.log('DOM manipulation test: SUCCESS');
        
        // Remove test div after 3 seconds
        setTimeout(() => {
            if (testDiv.parentNode) {
                testDiv.remove();
            }
        }, 3000);
        
    } catch (error) {
        console.error('DOM manipulation test: FAILED', error);
    }
}

// Initialize map and functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CONTENT LOADED EVENT FIRED ===');
    
    // Run basic test first
    testBasicFunctionality();
    
    // Show manual button after 5 seconds if map hasn't loaded
    setTimeout(() => {
        const manualBtn = document.getElementById('manualInitBtn');
        if (manualBtn && !driverMap) {
            manualBtn.style.display = 'inline-block';
            console.log('Manual initialization button shown');
        }
    }, 5000);
    
    // Wait for Leaflet to be ready, then initialize
    waitForLeaflet(() => {
        console.log('=== LEAFLET READY, STARTING MAP INIT ===');
        try {
            console.log('Attempting to initialize map...');
            simpleMapInit();
        } catch (error) {
            console.error('Simple init failed:', error);
            showMapError();
        }
    });
});

function simpleMapInit() {
    try {
        console.log('=== STARTING SIMPLE MAP INITIALIZATION ===');
        
        // Check if map container exists
        const mapContainer = document.getElementById('driverMap');
        if (!mapContainer) {
            throw new Error('Map container not found');
        }
        
        console.log('Map container found:', mapContainer);
        console.log('Container dimensions:', mapContainer.offsetWidth, 'x', mapContainer.offsetHeight);
        
        // Visual test - add a colored border to see the container
        mapContainer.style.border = '3px solid red';
        mapContainer.style.backgroundColor = '#f0f0f0';
        
        // Wait a moment to ensure container is visible
        setTimeout(() => {
            console.log('Container dimensions after delay:', mapContainer.offsetWidth, 'x', mapContainer.offsetHeight);
            
            if (mapContainer.offsetWidth === 0 || mapContainer.offsetHeight === 0) {
                throw new Error('Map container has zero dimensions - CSS issue');
            }
            
            // Remove test styling
            mapContainer.style.border = '';
            mapContainer.style.backgroundColor = '';
            
            // Continue with map creation
            createMap();
        }, 500);
        
    } catch (error) {
        console.error('Error in simple map init:', error);
        showMapError();
    }
}

function createMap() {
    try {
        console.log('=== CREATING MAP ===');
        
        const mapContainer = document.getElementById('driverMap');
        
        // Check if Leaflet is available
        if (typeof L === 'undefined') {
            throw new Error('Leaflet library not loaded');
        }
        
        console.log('Leaflet available, creating map...');
        
        // Clear loading message
        mapContainer.innerHTML = '';
        console.log('Cleared container content');
        
        // Create map with simple configuration
        driverMap = L.map('driverMap', {
            center: [10.5377, 122.8363],
            zoom: 13,
            zoomControl: true
        });
        
        console.log('Map created successfully:', driverMap);
        
        // Add basic tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(driverMap);
        
        console.log('Tile layer added');
        
        // Add simple driver marker
        driverMarker = L.marker([10.5377, 122.8363]).addTo(driverMap);
        driverMarker.bindPopup('Driver Location');
        
        console.log('Driver marker added');
        
        // Add deployment markers if available
        if (activeDeployments && activeDeployments.length > 0) {
            addDeploymentMarkers();
        }
        
        console.log('Map initialization complete!');
        
        // Start other functionality
        startBasicFunctionality();
        
    } catch (error) {
        console.error('Error creating map:', error);
        showMapError();
    }
}

function addDeploymentMarkers() {
    console.log('Adding deployment markers...');
    
    activeDeployments.forEach(deployment => {
        if (deployment.end_lat && deployment.end_lng) {
            console.log(`Adding marker for deployment ${deployment.id} at ${deployment.end_lat}, ${deployment.end_lng}`);
            
            const marker = L.marker([deployment.end_lat, deployment.end_lng]).addTo(driverMap);
            
            marker.bindPopup(`
                <strong>Deployment #${deployment.deployment_id}</strong><br>
                <strong>Incident:</strong> ${deployment.incident_title || 'N/A'}<br>
                <strong>Status:</strong> ${deployment.status}<br>
                <strong>Location:</strong> ${deployment.end_location}<br>
                <button class="btn btn-sm btn-primary mt-2" onclick="centerOnDeployment(${deployment.id})">
                    Center on Map
                </button>
            `);
            
            deploymentMarkers[deployment.id] = marker;
        }
    });
    
    console.log(`Added ${Object.keys(deploymentMarkers).length} deployment markers`);
}

function startBasicFunctionality() {
    console.log('Starting basic functionality...');
    
    // Get current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                console.log('Got current location:', position.coords);
                updateDriverLocation(position.coords.latitude, position.coords.longitude);
            },
            function(error) {
                console.log('Location error:', error);
                // Use default location
                updateDriverLocation(10.5377, 122.8363);
            }
        );
    } else {
        console.log('Geolocation not supported');
        updateDriverLocation(10.5377, 122.8363);
    }
}

function updateDriverLocation(latitude, longitude) {
    console.log('Updating driver location to:', latitude, longitude);
    
    currentLocation = { lat: latitude, lng: longitude };
    
    if (driverMarker) {
        driverMarker.setLatLng([latitude, longitude]);
        console.log('Driver marker updated');
    }
    
    // Update location display
    const locationElement = document.getElementById('currentLocation');
    if (locationElement) {
        locationElement.innerHTML = `
            <strong>${latitude.toFixed(6)}</strong><br>
            <strong>${longitude.toFixed(6)}</strong>
        `;
    }
}

function centerOnDriver() {
    if (driverMap && currentLocation) {
        driverMap.setView([currentLocation.lat, currentLocation.lng], 15);
        console.log('Centered on driver');
    } else {
        console.log('Cannot center on driver - map or location not available');
    }
}

function centerOnDeployment(deploymentId) {
    if (driverMap && deploymentMarkers[deploymentId]) {
        const marker = deploymentMarkers[deploymentId];
        const latlng = marker.getLatLng();
        driverMap.setView(latlng, 15);
        console.log('Centered on deployment:', deploymentId);
    } else {
        console.log('Cannot center on deployment:', deploymentId);
    }
}

function refreshDeployments() {
    console.log('Refreshing deployments...');
    location.reload();
}

function showMapError(message = 'Map could not be loaded. Please refresh the page or check your internet connection.') {
    console.log('Showing map error...');
    
    const mapContainer = document.getElementById('driverMap');
    const errorDiv = document.getElementById('mapError');
    
    if (mapContainer) {
        mapContainer.style.display = 'none';
    }
    
    if (errorDiv) {
        errorDiv.style.display = 'block';
        errorDiv.querySelector('h6').textContent = message;
    }
}

function retryMapInitialization() {
    console.log('Retrying map initialization...');
    
    const mapContainer = document.getElementById('driverMap');
    const errorDiv = document.getElementById('mapError');
    
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
    
    if (mapContainer) {
        mapContainer.style.display = 'block';
        mapContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h6 class="text-primary">Retrying map initialization...</h6>
                <small class="text-muted">Please wait</small>
            </div>
        `;
    }
    
    setTimeout(() => {
        simpleMapInit();
    }, 1000);
}

function manualMapInit() {
    console.log('=== MANUAL MAP INITIALIZATION TRIGGERED ===');
    
    // Hide the manual button
    const manualBtn = document.getElementById('manualInitBtn');
    if (manualBtn) {
        manualBtn.style.display = 'none';
    }
    
    // Try to initialize map
    if (typeof L !== 'undefined') {
        simpleMapInit();
    } else {
        console.log('Leaflet not available, trying to load it...');
        // Try to load Leaflet manually
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js';
        script.onload = function() {
            console.log('Leaflet loaded manually, initializing map...');
            simpleMapInit();
        };
        script.onerror = function() {
            console.error('Failed to load Leaflet manually');
            showMapError('Failed to load map library. Please refresh the page.');
        };
        document.head.appendChild(script);
    }
}

// Status update functions
function updateDeploymentStatus(deploymentId, currentStatus) {
    console.log('Updating deployment status:', deploymentId, currentStatus);
    
    // Set the current status in the modal
    const statusSelect = document.getElementById('newStatus');
    if (statusSelect) {
        statusSelect.value = currentStatus;
    }
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
    
    // Store the deployment ID for form submission
    const form = document.getElementById('statusForm');
    if (form) {
        form.setAttribute('data-deployment-id', deploymentId);
    }
}

// Handle status form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('statusForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const deploymentId = this.getAttribute('data-deployment-id');
            const status = document.getElementById('newStatus').value;
            const notes = document.getElementById('statusNotes').value;
            
            if (!status) {
                alert('Please select a status');
                return;
            }
            
            const formData = new FormData();
            formData.append('status', status);
            if (notes) {
                formData.append('notes', notes);
            }
            
            fetch(`index.php?action=driver&method=updateStatus&id=${deploymentId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
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
    }
});

function getStatusColor(status) {
    switch (status) {
        case 'dispatched': return 'primary';
        case 'en_route': return 'success';
        case 'on_scene': return 'warning';
        case 'returning': return 'info';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}

function getPriorityColor(priority) {
    switch (priority) {
        case 'critical': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low': return 'secondary';
        default: return 'info';
    }
}

// Final confirmation that script loaded
console.log('=== DRIVER DASHBOARD SCRIPT COMPLETE ===');
</script>

<style>
.custom-div-icon {
    background: none;
    border: none;
}

.custom-div-icon i {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.border-primary { border-color: #007bff !important; }
.border-success { border-color: #28a745 !important; }
.border-warning { border-color: #ffc107 !important; }
.border-info { border-color: #17a2b8 !important; }
.border-danger { border-color: #dc3545 !important; }
.border-secondary { border-color: #6c757d !important; }

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
    color: #155724;
}

.alert-success .btn-close {
    color: #155724;
    opacity: 0.7;
}

.alert-success .btn-close:hover {
    opacity: 1;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.alert-success {
    animation: slideInRight 0.3s ease-out;
}
</style> 