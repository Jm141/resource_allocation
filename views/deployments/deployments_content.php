<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-truck text-info me-2"></i>
        Deployments Management
    </h1>
    <div>
        <a href="index.php?action=deployments&method=createSmart" class="btn btn-success me-2">
            <i class="fas fa-brain me-2"></i>Smart Deployment
        </a>
        <a href="index.php?action=deployments&method=create" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Create Deployment
        </a>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= count($deployments) ?></div>
            <div class="label">Total Deployments</div>
            <div class="trend">
                <small class="text-muted">All time</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= count(array_filter($deployments, function($d) { return in_array($d['status'], ['dispatched', 'en_route', 'on_scene']); })) ?></div>
            <div class="label">Active Deployments</div>
            <div class="trend">
                <small class="text-muted">Currently responding</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= count(array_filter($deployments, function($d) { return $d['status'] === 'completed'; })) ?></div>
            <div class="label">Completed</div>
            <div class="trend">
                <small class="text-muted">Successfully resolved</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= count(array_filter($deployments, function($d) { return $d['status'] === 'dispatched'; })) ?></div>
            <div class="label">Dispatched</div>
            <div class="trend">
                <small class="text-muted">Awaiting response</small>
            </div>
        </div>
    </div>
</div>

<!-- Deployments Table -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Deployments
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($deployments)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-truck fa-3x mb-3"></i>
                <h5>No Deployments Found</h5>
                <p>No deployments have been created yet.</p>
                <a href="index.php?action=deployments&method=create" class="btn btn-orange">
                    <i class="fas fa-plus-circle me-2"></i>Create First Deployment
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Deployment ID</th>
                            <th>Incident</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deployments as $deployment): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?= htmlspecialchars($deployment['deployment_id'] ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($deployment['incident_title'] ?? 'Incident not specified') ?></div>
                                <small class="text-muted"><?= htmlspecialchars($deployment['incident_location'] ?? 'Location not specified') ?></small>
                            </td>
                            <td>
                                <?php if (!empty($deployment['driver_first_name']) || !empty($deployment['driver_last_name'])): ?>
                                    <?= htmlspecialchars(trim($deployment['driver_first_name'] . ' ' . $deployment['driver_last_name'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">Not assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($deployment['vehicle_code'])): ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($deployment['vehicle_code']) ?></span>
                                    <br><small class="text-muted"><?= htmlspecialchars($deployment['vehicle_type'] ?? '') ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Not assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $deployment['status'] ?>">
                                    <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y H:i', strtotime($deployment['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=deployments&id=<?= $deployment['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?action=deployments&id=<?= $deployment['id'] ?>&method=edit" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteDeployment(<?= $deployment['id'] ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Status Legend -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Status Legend
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <span class="status-badge status-dispatched">Dispatched</span>
                        <small class="text-muted d-block">Deployment created and assigned</small>
                    </div>
                    <div class="col-md-3">
                        <span class="status-badge status-en_route">En Route</span>
                        <small class="text-muted d-block">Responding to incident</small>
                    </div>
                    <div class="col-md-3">
                        <span class="status-badge status-on_scene">On Scene</span>
                        <small class="text-muted d-block">At incident location</small>
                    </div>
                    <div class="col-md-3">
                        <span class="status-badge status-completed">Completed</span>
                        <small class="text-muted d-block">Response finished</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDeployment(deploymentId) {
    if (confirm('Are you sure you want to delete this deployment? This action cannot be undone.')) {
        fetch(`index.php?action=deployments&id=${deploymentId}&method=delete`, {
            method: 'POST'
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
                        location.reload();
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
            alert('An error occurred while deleting the deployment.');
        });
    }
}

// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Add click event to status badges for filtering (future enhancement)
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            const status = this.classList[1].replace('status-', '');
            console.log('Filter by status:', status);
            // Future: Implement status filtering
        });
    });
});
</script>

<style>
/* Status badge styles */
.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

.status-dispatched { background-color: #007bff; color: white; }
.status-en_route { background-color: #28a745; color: white; }
.status-on_scene { background-color: #ffc107; color: #212529; }
.status-returning { background-color: #6c757d; color: white; }
.status-completed { background-color: #28a745; color: white; }
.status-cancelled { background-color: #dc3545; color: white; }

/* Stats card styles */
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-card .number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.stats-card .label {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.stats-card .trend {
    font-size: 0.8rem;
    opacity: 0.8;
}

/* Table enhancements */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style> 