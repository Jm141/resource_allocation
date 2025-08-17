<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-truck text-info me-2"></i>
        Create New Deployment
    </h1>
    <div>
        <a href="index.php?action=deployments" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Deployments
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Deployment Details
                </h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=deployments&method=create" method="POST">
                    <div class="mb-3">
                        <label for="incident_id" class="form-label">Select Incident *</label>
                        <select class="form-select" id="incident_id" name="incident_id" required>
                            <option value="">Choose an incident...</option>
                            <?php if (isset($incidents) && is_array($incidents)): ?>
                                <?php foreach ($incidents as $incident): ?>
                                <option value="<?= $incident['id'] ?>">
                                    <?= htmlspecialchars($incident['title']) ?> - <?= htmlspecialchars($incident['location_name'] ?? 'N/A') ?>
                                </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="driver_id" class="form-label">Select Driver *</label>
                                <select class="form-select" id="driver_id" name="driver_id" required>
                                    <option value="">Choose a driver...</option>
                                    <?php if (isset($drivers) && is_array($drivers)): ?>
                                        <?php foreach ($drivers as $driver): ?>
                                        <option value="<?= $driver['id'] ?>">
                                            <?= htmlspecialchars($driver['driver_id']) ?> - Driver ID: <?= $driver['id'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Select Vehicle *</label>
                                <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                    <option value="">Choose a vehicle...</option>
                                    <?php if (isset($vehicles) && is_array($vehicles)): ?>
                                        <?php foreach ($vehicles as $vehicle): ?>
                                        <option value="<?= $vehicle['id'] ?>">
                                            <?= htmlspecialchars($vehicle['vehicle_id']) ?> - <?= htmlspecialchars(ucfirst($vehicle['vehicle_type'])) ?> (<?= htmlspecialchars($vehicle['model']) ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="start_location" class="form-label">Start Location</label>
                        <input type="text" class="form-control" id="start_location" name="start_location" 
                               value="Bago City Headquarters" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="end_location" class="form-label">Incident Location</label>
                        <input type="text" class="form-control" id="end_location" name="end_location" readonly>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_lat" class="form-label">Start Latitude</label>
                                <input type="number" step="any" class="form-control" id="start_lat" name="start_lat" 
                                       value="10.5377" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_lng" class="form-label">Start Longitude</label>
                                <input type="number" step="any" class="form-control" id="start_lng" name="start_lng" 
                                       value="122.8363" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_lat" class="form-label">End Latitude</label>
                                <input type="number" step="any" class="form-control" id="end_lat" name="end_lat" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_lng" class="form-label">End Longitude</label>
                                <input type="number" step="any" class="form-control" id="end_lng" name="end_lng" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-orange">
                            <i class="fas fa-paper-plane me-2"></i>Create Deployment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Deployment Information
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>How it works:</h6>
                    <ol class="mb-0">
                        <li>Select an incident from the list</li>
                        <li>Choose an available driver</li>
                        <li>Select an appropriate vehicle</li>
                        <li>System will automatically calculate the route</li>
                        <li>Deployment will be created and tracked</li>
                    </ol>
                </div>
                
                <div class="alert alert-warning">
                    <h6>Note:</h6>
                    <p class="mb-0">The start location is automatically set to Bago City Headquarters. The incident location coordinates will be filled automatically when you select an incident.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const incidentSelect = document.getElementById('incident_id');
    const endLocationInput = document.getElementById('end_location');
    const endLatInput = document.getElementById('end_lat');
    const endLngInput = document.getElementById('end_lng');
    
    // Update incident location when incident is selected
    incidentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            // In a real application, you would fetch the incident details via AJAX
            // For now, we'll simulate it with the incident data
            const incidents = <?= isset($incidents) ? json_encode($incidents) : '[]' ?>;
            const selectedIncident = incidents.find(inc => inc.id == this.value);
            
            if (selectedIncident) {
                endLocationInput.value = selectedIncident.location_name || 'N/A';
                endLatInput.value = selectedIncident.latitude || '';
                endLngInput.value = selectedIncident.longitude || '';
            }
        } else {
            endLocationInput.value = '';
            endLatInput.value = '';
            endLngInput.value = '';
        }
    });
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const incidentId = incidentSelect.value;
        const driverId = document.getElementById('driver_id').value;
        const vehicleId = document.getElementById('vehicle_id').value;
        
        if (!incidentId || !driverId || !vehicleId) {
            e.preventDefault();
            alert('Please fill in all required fields before submitting.');
        }
    });
});
</script>