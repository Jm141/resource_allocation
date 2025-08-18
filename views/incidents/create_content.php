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
                <form action="index.php?action=incidents&method=create" method="POST" id="incidentForm">
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
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Report Incident
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

    // Simple form validation
    document.getElementById('incidentForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const location = document.getElementById('location_name').value.trim();
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!title || !description || !location || !lat || !lng) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        // Basic coordinate validation
        if (lat < -90 || lat > 90) {
            e.preventDefault();
            alert('Latitude must be between -90 and 90.');
            return;
        }
        
        if (lng < -180 || lng > 180) {
            e.preventDefault();
            alert('Longitude must be between -180 and 180.');
            return;
        }
    });
});
</script> 