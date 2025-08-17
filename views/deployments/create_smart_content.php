<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-brain text-primary me-2"></i>
        Smart Deployment Creation
    </h1>
    <div>
        <a href="index.php?action=deployments" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Deployments
        </a>
    </div>
</div>

<!-- Smart Deployment Overview -->
<div class="alert alert-info mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle me-2"></i>
        <div>
            <strong>🚀 Smart Deployment System</strong>
            <br>
            <small class="text-muted">
                The system automatically calculates optimal routes from Bago Headquarters (10.526071, 122.841451) 
                to incident locations, avoiding traffic and providing real-time ETA. Resources are automatically 
                allocated based on incident type and availability.
            </small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-magic me-2"></i>Intelligent Resource Allocation
                </h5>
            </div>
            <div class="card-body">
                <form id="smartDeploymentForm">
                    <div class="mb-3">
                        <label for="incident_id" class="form-label">Select Incident</label>
                        <select class="form-select" id="incident_id" name="incident_id" required onchange="loadOptimalDeployment()">
                            <option value="">Choose an incident...</option>
                            <?php foreach ($incidents as $incident): ?>
                            <option value="<?= $incident['id'] ?>" 
                                    data-type="<?= htmlspecialchars($incident['category_name'] ?? 'medical') ?>"
                                    data-lat="<?= $incident['latitude'] ?>"
                                    data-lng="<?= $incident['longitude'] ?>"
                                    data-location="<?= htmlspecialchars($incident['location_name']) ?>">
                                <?= htmlspecialchars($incident['incident_id'] ?? $incident['id']) ?> - 
                                <?= htmlspecialchars($incident['title']) ?> 
                                (<?= htmlspecialchars($incident['category_name'] ?? 'N/A') ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="driver_id" class="form-label">Select Driver</label>
                        <select class="form-select" id="driver_id" name="driver_id" required>
                            <option value="">Choose a driver...</option>
                            <?php foreach ($drivers as $driver): ?>
                            <option value="<?= $driver['id'] ?>">
                                <?= htmlspecialchars($driver['driver_id']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vehicle_id" class="form-label">Select Vehicle</label>
                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                            <option value="">Choose a vehicle...</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>">
                                <?= htmlspecialchars($vehicle['vehicle_id']) ?> - 
                                <?= htmlspecialchars($vehicle['vehicle_type']) ?> 
                                (<?= htmlspecialchars($vehicle['model'] ?? 'N/A') ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Smart Selection:</strong> The system will automatically determine the optimal emergency facilities 
                        based on the incident type and location. For example, a fire incident will automatically deploy 
                        fire trucks from the nearest fire station and ambulances from the nearest hospital.
                    </div>

                    <div id="deploymentOptions" style="display: none;">
                        <h6 class="mb-3">🚀 Recommended Deployment Strategy</h6>
                        <div id="optionsContainer"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="createDeploymentBtn" disabled>
                            <i class="fas fa-rocket me-2"></i>Create Smart Deployment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>How It Works
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">🔥 Fire Incidents</h6>
                    <small class="text-muted">
                        Automatically deploys:<br>
                        • Fire trucks from nearest fire station<br>
                        • Ambulances from nearest hospital<br>
                        • Police support if needed
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">🚑 Medical Emergencies</h6>
                    <small class="text-muted">
                        Automatically deploys:<br>
                        • Ambulances from nearest hospital<br>
                        • Medical response teams<br>
                        • Emergency equipment
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">🚔 Police Incidents</h6>
                    <small class="text-muted">
                        Automatically deploys:<br>
                        • Police units from nearest station<br>
                        • Ambulances for medical support<br>
                        • Specialized response teams
                    </small>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">🚗 Traffic Accidents</h6>
                    <small class="text-muted">
                        Automatically deploys:<br>
                        • Police for traffic control<br>
                        • Ambulances for medical care<br>
                        • Fire trucks for rescue if needed
                    </small>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Smart Features
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>🚗 Traffic avoidance routing</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>⏰ Real-time ETA calculation</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>🏥 Automatic facility selection</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>📍 Bago HQ coordinate integration</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>🚨 Traffic alert system</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeploymentOptions = [];

function loadOptimalDeployment() {
    const incidentSelect = document.getElementById('incident_id');
    const selectedOption = incidentSelect.options[incidentSelect.selectedIndex];
    
    if (!incidentSelect.value) {
        document.getElementById('deploymentOptions').style.display = 'none';
        document.getElementById('createDeploymentBtn').disabled = true;
        return;
    }
    
    const incidentId = incidentSelect.value;
    
    // Show loading state
    document.getElementById('deploymentOptions').style.display = 'block';
    document.getElementById('optionsContainer').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Calculating optimal deployment with traffic avoidance...</p></div>';
    
    fetch(`index.php?action=deployments&method=getOptimalDeployment&incident_id=${incidentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('optionsContainer').innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                return;
            }
            
            currentDeploymentOptions = data.deployment_options;
            displayDeploymentOptions(data);
            document.getElementById('createDeploymentBtn').disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('optionsContainer').innerHTML = '<div class="alert alert-danger">Error loading deployment options</div>';
        });
}

function displayDeploymentOptions(data) {
    const container = document.getElementById('optionsContainer');
    let html = '';
    
    html += `
        <div class="alert alert-success mb-3">
            <i class="fas fa-map-marker-alt me-2"></i>
            <strong>Incident Location:</strong> ${data.incident.location_name}
            <br><small class="text-muted">Coordinates: ${data.incident.latitude}, ${data.incident.longitude}</small>
        </div>
    `;
    
    data.deployment_options.forEach((option, index) => {
        const facility = option.facility;
        const route = option.route;
        
        html += `
            <div class="card mb-3 border-${getPriorityColor(option.priority)}">
                <div class="card-header bg-${getPriorityColor(option.priority)} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas ${getFacilityIcon(facility.facility_type)} me-2"></i>
                            <strong>${facility.name}</strong>
                        </div>
                        <span class="badge bg-light text-dark">Priority: ${option.priority}/10</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Facility Type</small><br>
                            <strong>${getFacilityTypeName(facility.facility_type)}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Distance</small><br>
                            <strong>${route.distance} km</strong>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <small class="text-muted">Estimated Time</small><br>
                            <strong>${option.estimated_time} minutes</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">ETA</small><br>
                            <strong class="text-success">${calculateETA(option.estimated_time)}</strong>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Route Status</small><br>
                        <strong class="text-success">✅ Traffic-optimized route</strong>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Contact</small><br>
                        <strong>${facility.contact_number || 'N/A'}</strong>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Address</small><br>
                        <strong>${facility.address}</strong>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Available Resources</small><br>
                        <strong>${facility.available_resources || 'Standard equipment'}</strong>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function calculateETA(minutes) {
    const now = new Date();
    const eta = new Date(now.getTime() + (minutes * 60 * 1000));
    return eta.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
}

function getPriorityColor(priority) {
    if (priority >= 9) return 'danger';
    if (priority >= 7) return 'warning';
    if (priority >= 5) return 'info';
    return 'secondary';
}

function getFacilityIcon(facilityType) {
    const icons = {
        'hospital': 'fa-hospital',
        'police_station': 'fa-shield-alt',
        'fire_station': 'fa-fire-extinguisher',
        'emergency_center': 'fa-ambulance',
        'command_center': 'fa-tower-broadcast'
    };
    return icons[facilityType] || 'fa-building';
}

function getFacilityTypeName(facilityType) {
    const names = {
        'hospital': 'Hospital',
        'police_station': 'Police Station',
        'fire_station': 'Fire Station',
        'emergency_center': 'Emergency Response Center',
        'command_center': 'Command & Control Center'
    };
    return names[facilityType] || facilityType;
}

document.getElementById('smartDeploymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentDeploymentOptions.length) {
        alert('Please select an incident first to see deployment options.');
        return;
    }
    
    const formData = new FormData(this);
    
    // Show confirmation
    const confirmMessage = `This will create ${currentDeploymentOptions.length} deployment(s) from the optimal facilities:\n\n` +
        currentDeploymentOptions.map(option => 
            `• ${option.facility.name} (${getFacilityTypeName(option.facility.facility_type)})`
        ).join('\n') + 
        '\n\nDo you want to proceed?';
    
    if (confirm(confirmMessage)) {
        fetch('index.php?action=deployments&method=createSmart', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.text();
            }
        })
        .then(data => {
            if (data) {
                try {
                    const result = JSON.parse(data);
                    if (result.success) {
                        alert('Smart deployment created successfully!');
                        window.location.href = 'index.php?action=deployments';
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (e) {
                    console.log('Response:', data);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the deployment.');
        });
    }
});
</script> 