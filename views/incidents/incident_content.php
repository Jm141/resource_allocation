<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
        Incident Details
    </h1>
    <div>
        <a href="index.php?action=incidents" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Incidents
        </a>
        <a href="index.php?action=incidents&id=<?= $incident['id'] ?>&method=edit" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit Incident
        </a>
        <a href="index.php?action=deployments&method=createSmart" class="btn btn-success">
            <i class="fas fa-brain me-2"></i>Smart Deployment
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Incident Information Card -->
        <div class="card card-custom mb-4">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Incident Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Incident ID</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-secondary fs-6"><?= htmlspecialchars($incident['incident_id'] ?? $incident['id']) ?></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Status</label>
                        <div class="form-control-plaintext">
                            <span class="status-badge status-<?= $incident['status'] ?> fs-6">
                                <?= ucwords(str_replace('_', ' ', $incident['status'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Category</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-tag me-2 text-primary"></i>
                            <?= htmlspecialchars($incident['category_name'] ?? 'N/A') ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Priority</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-<?= getPriorityColor($incident['priority']) ?> fs-6">
                                <?= ucwords($incident['priority']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Title</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-heading me-2 text-info"></i>
                            <?= htmlspecialchars($incident['title']) ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Reported By</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-user me-2 text-success"></i>
                            <?= htmlspecialchars(($incident['first_name'] ?? '') . ' ' . ($incident['last_name'] ?? '')) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Description</label>
                    <div class="form-control-plaintext">
                        <?= nl2br(htmlspecialchars($incident['description'])) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Location</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            <?= htmlspecialchars($incident['location_name']) ?>
                            <?php if ($incident['latitude'] && $incident['longitude']): ?>
                                <small class="text-muted d-block mt-1">
                                    Coordinates: <?= number_format($incident['latitude'], 6) ?>, <?= number_format($incident['longitude'], 6) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Reported Date</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                            <?= date('M d, Y H:i', strtotime($incident['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Deployments Card -->
        <?php if (!empty($deployments)): ?>
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Related Deployments
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Deployment ID</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deployments as $deployment): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($deployment['deployment_id'] ?? $deployment['id']) ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars(($deployment['driver_first_name'] ?? '') . ' ' . ($deployment['driver_last_name'] ?? '')) ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= htmlspecialchars($deployment['vehicle_code'] ?? 'N/A') ?></span>
                                    <small class="text-muted d-block"><?= htmlspecialchars($deployment['vehicle_type'] ?? 'N/A') ?></small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $deployment['status'] ?>">
                                        <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?action=deployments&id=<?= $deployment['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                    <button type="button" class="btn btn-success" onclick="updateStatus('assigned')">
                        <i class="fas fa-user-check me-2"></i>Assign to Team
                    </button>
                    <button type="button" class="btn btn-warning" onclick="updateStatus('in_progress')">
                        <i class="fas fa-play me-2"></i>Mark as In Progress
                    </button>
                    <button type="button" class="btn btn-info" onclick="updateStatus('resolved')">
                        <i class="fas fa-check me-2"></i>Mark as Resolved
                    </button>
                    <button type="button" class="btn btn-danger" onclick="updateStatus('closed')">
                        <i class="fas fa-times me-2"></i>Close Incident
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
                            <small class="text-muted"><?= date('M d, Y H:i', strtotime($incident['created_at'])) ?></small>
                            <div class="fw-bold">Incident Reported</div>
                        </div>
                    </div>
                    <?php if ($incident['status'] !== 'reported'): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <small class="text-muted">Status Updated</small>
                            <div class="fw-bold">Current: <?= ucwords(str_replace('_', ' ', $incident['status'])) ?></div>
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
                <h5 class="modal-title">Update Incident Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="">Select Status</option>
                            <option value="reported">Reported</option>
                            <option value="assigned">Assigned</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
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

.status-reported { background-color: #cce5ff; color: #004085; }
.status-assigned { background-color: #d4edda; color: #155724; }
.status-in_progress { background-color: #fff3cd; color: #856404; }
.status-resolved { background-color: #d4edda; color: #155724; }
.status-closed { background-color: #e2e3e5; color: #383d41; }
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
    
    fetch('index.php?action=incidents&id=<?= $incident['id'] ?>&method=updateStatus', {
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
    const colors = {
        'critical': 'danger',
        'high': 'warning',
        'medium': 'info',
        'low': 'success'
    };
    return colors[priority] || 'secondary';
}
</script> 