<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-plus-circle text-success me-2"></i>
        Report New Incident
    </h1>
    <div>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Incident Details
                </h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=incidents&method=create" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Incident Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <option value="1">Medical Emergency</option>
                                    <option value="2">Fire Emergency</option>
                                    <option value="3">Traffic Accident</option>
                                    <option value="4">Natural Disaster</option>
                                    <option value="5">Public Safety</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority *</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reported_by" class="form-label">Reported By</label>
                                <input type="text" class="form-control" id="reported_by" name="reported_by" value="1" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Location Name *</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude *</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude *</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Traffic Check Section -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Traffic & Route Information</label>
                            <button type="button" class="btn btn-sm btn-info" onclick="checkTrafficAndRoute()">
                                <i class="fas fa-route me-2"></i>Check Traffic & Route
                            </button>
                        </div>
                        <div id="trafficInfo" class="mt-2" style="display: none;">
                            <!-- Traffic information will be displayed here -->
                        </div>
                    </div>
                    
                    <!-- Coordinate Validation -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Coordinate Validation</label>
                            <button type="button" class="btn btn-sm btn-success" onclick="validateCoordinates()">
                                <i class="fas fa-map-marker-alt me-2"></i>Validate Coordinates
                            </button>
                        </div>
                        <div id="coordinateValidation" class="mt-2" style="display: none;">
                            <!-- Coordinate validation results will be displayed here -->
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-orange">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>Select Location
                </h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 300px; width: 100%;"></div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Click on the map to select the incident location. The coordinates will be automatically filled.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([10.5377, 122.8363], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    // Handle map clicks
    map.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Update form fields
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);

        // Update marker
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);

        // Get address from coordinates
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('location_name').value = data.display_name || "Unknown location";
            })
            .catch(error => console.error('Error fetching address:', error));
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!lat || !lng) {
            e.preventDefault();
            alert('Please select a location on the map before submitting.');
        }
    });
});

// Traffic and Route Checking Functions
function checkTrafficAndRoute() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (!lat || !lng) {
        alert('Please enter coordinates first.');
        return;
    }
    
    // Show loading state
    const trafficInfo = document.getElementById('trafficInfo');
    trafficInfo.style.display = 'block';
    trafficInfo.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Checking traffic and calculating route...</div>';
    
    // Bago Headquarters coordinates
    const bagoHQ = { lat: 10.526071, lng: 122.841451 };
    
    // Check for traffic incidents and calculate route
    fetch(`index.php?action=map&method=getIncidentAvoidanceRoute&start_lat=${bagoHQ.lat}&start_lng=${bagoHQ.lng}&end_lat=${lat}&end_lng=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                trafficInfo.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
                return;
            }
            
            displayTrafficInfo(data, bagoHQ);
        })
        .catch(error => {
            console.error('Error:', error);
            trafficInfo.innerHTML = '<div class="alert alert-danger">Error checking traffic and route.</div>';
        });
}

function displayTrafficInfo(data, bagoHQ) {
    const trafficInfo = document.getElementById('trafficInfo');
    
    let html = '<div class="alert alert-info">';
    html += '<h6 class="mb-2"><i class="fas fa-route me-2"></i>Route & Traffic Information</h6>';
    
    // Route details
    html += `<div class="row mb-2">`;
    html += `<div class="col-md-6"><small class="text-muted">Total Distance:</small><br><strong>${data.distance} km</strong></div>`;
    html += `<div class="col-md-6"><small class="text-muted">Estimated Time:</small><br><strong>${data.duration} minutes</strong></div>`;
    html += `</div>`;
    
    // ETA calculation
    const currentTime = new Date();
    const etaTime = new Date(currentTime.getTime() + (data.duration * 60 * 1000));
    html += `<div class="mb-2"><small class="text-muted">ETA from Bago HQ:</small><br><strong class="text-success">${etaTime.toLocaleTimeString()}</strong></div>`;
    
    // Traffic alerts
    if (data.warnings && data.warnings.length > 0) {
        html += '<div class="mt-2"><small class="text-warning fw-bold">🚨 Traffic Alerts:</small>';
        data.warnings.forEach(warning => {
            html += `<div class="small text-muted">• ${warning.message}</div>`;
        });
        html += '</div>';
    } else {
        html += '<div class="mt-2"><small class="text-success">✅ No traffic incidents detected on route</small></div>';
    }
    
    // Route optimization
    if (data.is_shortest_route) {
        html += '<div class="mt-2"><small class="text-success">✅ Shortest route calculated via OSRM</small></div>';
    }
    
    html += '</div>';
    
    trafficInfo.innerHTML = html;
}

function validateCoordinates() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    
    if (!lat || !lng) {
        alert('Please enter coordinates first.');
        return;
    }
    
    const coordinateValidation = document.getElementById('coordinateValidation');
    coordinateValidation.style.display = 'block';
    
    let html = '<div class="alert alert-info">';
    html += '<h6 class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Coordinate Validation</h6>';
    
    // Validate latitude range (-90 to 90)
    if (lat >= -90 && lat <= 90) {
        html += '<div class="text-success">✅ Latitude is valid</div>';
    } else {
        html += '<div class="text-danger">❌ Latitude must be between -90 and 90</div>';
    }
    
    // Validate longitude range (-180 to 180)
    if (lng >= -180 && lng <= 180) {
        html += '<div class="text-success">✅ Longitude is valid</div>';
    } else {
        html += '<div class="text-danger">❌ Longitude must be between -180 and 180</div>';
    }
    
    // Check if coordinates are in Bago City area (approximate bounds)
    const bagoBounds = {
        minLat: 10.4, maxLat: 10.6,
        minLng: 122.7, maxLng: 122.9
    };
    
    if (lat >= bagoBounds.minLat && lat <= bagoBounds.maxLat && 
        lng >= bagoBounds.minLng && lng <= bagoBounds.maxLng) {
        html += '<div class="text-success">✅ Coordinates are within Bago City area</div>';
    } else {
        html += '<div class="text-warning">⚠️ Coordinates are outside Bago City area</div>';
    }
    
    // Calculate distance from Bago HQ
    const bagoHQ = { lat: 10.526071, lng: 122.841451 };
    const distance = haversineDistance(lat, lng, bagoHQ.lat, bagoHQ.lng);
    
    html += `<div class="mt-2"><small class="text-muted">Distance from Bago HQ:</small><br><strong>${distance.toFixed(2)} km</strong></div>`;
    
    html += '</div>';
    
    coordinateValidation.innerHTML = html;
}

// Haversine distance calculation
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