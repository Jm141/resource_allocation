<?php
$page_title = 'Deployment Details - ' . htmlspecialchars($deployment['deployment_id']);
$content_file = __DIR__ . '/deployment_content.php';
include 'views/layouts/main.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-truck text-primary me-2"></i>
        Deployment Details
    </h1>
    <div>
        <a href="index.php?action=deployments" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Deployments
        </a>
        <a href="index.php?action=deployments&id=<?= $deployment['id'] ?>&method=edit" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit Deployment
        </a>
        <a href="index.php?action=map" class="btn btn-orange">
            <i class="fas fa-map-marked-alt me-2"></i>View on Map
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Deployment Information Card -->
        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Deployment Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Deployment ID</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-secondary fs-6"><?= htmlspecialchars($deployment['deployment_id']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Status</label>
                        <div class="form-control-plaintext">
                            <span class="status-badge status-<?= $deployment['status'] ?> fs-6">
                                <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Driver</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <?php if (isset($deployment['driver_first_name']) && isset($deployment['driver_last_name'])): ?>
                                <?= htmlspecialchars($deployment['driver_first_name'] . ' ' . $deployment['driver_last_name']) ?>
                            <?php else: ?>
                                <span class="text-muted">Driver not assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Vehicle</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-truck me-2 text-info"></i>
                            <?php if (isset($deployment['vehicle_code'])): ?>
                                <?= htmlspecialchars($deployment['vehicle_code']) ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($deployment['vehicle_type'] ?? 'N/A') ?></small>
                            <?php else: ?>
                                <span class="text-muted">Vehicle not assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Incident</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            <?= htmlspecialchars($deployment['incident_title'] ?? 'N/A') ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Created Date</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                            <?= date('M d, Y H:i', strtotime($deployment['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($deployment['dispatched_at']) && $deployment['dispatched_at']): ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Dispatched At</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-clock me-2 text-success"></i>
                            <?= date('M d, Y H:i', strtotime($deployment['dispatched_at'])) ?>
                        </div>
                    </div>
                    <?php if (isset($deployment['estimated_arrival']) && $deployment['estimated_arrival']): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Estimated Arrival</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-hourglass-half me-2 text-info"></i>
                            <?= date('M d, Y H:i', strtotime($deployment['estimated_arrival'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($deployment['notes']) && $deployment['notes']): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Notes</label>
                    <div class="form-control-plaintext">
                        <?= nl2br(htmlspecialchars($deployment['notes'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Route Information Card -->
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-route me-2"></i>Route Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Start Location</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-map-marker-alt me-2 text-success"></i>
                            <?= htmlspecialchars($deployment['start_location']) ?>
                            <?php if ($deployment['start_lat'] && $deployment['start_lng']): ?>
                                <small class="text-muted d-block mt-1">
                                    Coordinates: <?= number_format($deployment['start_lat'], 6) ?>, <?= number_format($deployment['start_lng'], 6) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">End Location</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            <?= htmlspecialchars($deployment['end_location']) ?>
                            <?php if ($deployment['end_lat'] && $deployment['end_lng']): ?>
                                <small class="text-muted d-block mt-1">
                                    Coordinates: <?= number_format($deployment['end_lat'], 6) ?>, <?= number_format($deployment['end_lng'], 6) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($deployment['start_lat'] && $deployment['start_lng'] && $deployment['end_lat'] && $deployment['end_lng']): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Route Map</label>
                    <div id="routeMap" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="updateStatus('en_route')">
                        <i class="fas fa-play me-2"></i>Mark as En Route
                    </button>
                    <button type="button" class="btn btn-warning" onclick="updateStatus('on_scene')">
                        <i class="fas fa-map-marker-alt me-2"></i>Mark as On Scene
                    </button>
                    <button type="button" class="btn btn-info" onclick="updateStatus('returning')">
                        <i class="fas fa-undo me-2"></i>Mark as Returning
                    </button>
                    <button type="button" class="btn btn-success" onclick="updateStatus('completed')">
                        <i class="fas fa-check me-2"></i>Mark as Completed
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Timeline Card -->
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>Status Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <small class="text-muted"><?= date('M d, Y H:i', strtotime($deployment['created_at'])) ?></small>
                            <div class="fw-bold">Deployment Created</div>
                        </div>
                    </div>
                    <?php if ($deployment['status'] !== 'dispatched'): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Status Updated</small>
                            <div class="fw-bold">Current: <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
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
                            <option value="dispatched">Dispatched</option>
                            <option value="en_route">En Route</option>
                            <option value="on_scene">On Scene</option>
                            <option value="returning">Returning</option>
                            <option value="completed">Completed</option>
                        </select>
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

<?php if ($deployment['start_lat'] && $deployment['start_lng'] && $deployment['end_lat'] && $deployment['end_lng']): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
let routeMap;

document.addEventListener('DOMContentLoaded', function() {
    initializeRouteMap();
});

function initializeRouteMap() {
    routeMap = L.map('routeMap').setView([
        (<?= $deployment['start_lat'] ?> + <?= $deployment['end_lat'] ?>) / 2,
        (<?= $deployment['start_lng'] ?> + <?= $deployment['end_lng'] ?>) / 2
    ], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(routeMap);
    
    // Add start marker
    const startMarker = L.marker([<?= $deployment['start_lat'] ?>, <?= $deployment['start_lng'] ?>], {
        icon: L.divIcon({
            html: '<i class="fas fa-play-circle" style="color: #28a745; font-size: 24px;"></i>',
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(routeMap).bindPopup('Start Location: <?= htmlspecialchars($deployment['start_location']) ?>');
    
    // Add end marker
    const endMarker = L.marker([<?= $deployment['end_lat'] ?>, <?= $deployment['end_lng'] ?>], {
        icon: L.divIcon({
            html: '<i class="fas fa-flag-checkered" style="color: #dc3545; font-size: 24px;"></i>',
            className: 'custom-div-icon',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    }).addTo(routeMap).bindPopup('End Location: <?= htmlspecialchars($deployment['end_location']) ?>');
    
    // Draw route line
    const routeLine = L.polyline([
        [<?= $deployment['start_lat'] ?>, <?= $deployment['start_lng'] ?>],
        [<?= $deployment['end_lat'] ?>, <?= $deployment['end_lng'] ?>]
    ], {
        color: '#007bff',
        weight: 4,
        opacity: 0.8,
        dashArray: '10, 10'
    }).addTo(routeMap);
    
    // Fit map to show entire route
    routeMap.fitBounds(routeLine.getBounds(), { padding: [20, 20] });
}
</script>
<?php endif; ?>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -24px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #dee2e6;
}

.timeline-content {
    padding-left: 10px;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.status-dispatched { background-color: #cce5ff; color: #004085; }
.status-en_route { background-color: #d4edda; color: #155724; }
.status-on_scene { background-color: #fff3cd; color: #856404; }
.status-returning { background-color: #e2e3e5; color: #383d41; }
.status-completed { background-color: #d4edda; color: #155724; }

.custom-div-icon {
    background: none;
    border: none;
}

.custom-div-icon i {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}
</style>

<script>
function updateStatus(status) {
    document.getElementById('newStatus').value = status;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('status', document.getElementById('newStatus').value);
    
    fetch('index.php?action=deployments&id=<?= $deployment['id'] ?>&method=updateStatus', {
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
</script>

<?php include 'views/layouts/footer.php'; ?> 