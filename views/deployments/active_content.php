<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-route text-primary me-2"></i>
        Active Deployments
    </h1>
    <div>
        <a href="index.php?action=deployments" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to All Deployments
        </a>
        <a href="index.php?action=deployments&method=create" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Create Deployment
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Currently Active Deployments
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
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
                    <?php if (!empty($deployments)): ?>
                        <?php foreach ($deployments as $deployment): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary fs-6">
                                        <?= htmlspecialchars($deployment['deployment_id']) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($deployment['incident_title'] ?? 'N/A') ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($deployment['incident_location'] ?? 'Location not specified') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (isset($deployment['driver_first_name']) && isset($deployment['driver_last_name'])): ?>
                                        <i class="fas fa-user me-2 text-primary"></i>
                                        <?= htmlspecialchars($deployment['driver_first_name'] . ' ' . $deployment['driver_last_name']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Driver not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($deployment['vehicle_code'])): ?>
                                        <i class="fas fa-truck me-2 text-info"></i>
                                        <?= htmlspecialchars($deployment['vehicle_code']) ?>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($deployment['vehicle_type'] ?? 'N/A') ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Vehicle not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $deployment['status'] ?> fs-6">
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
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?action=deployments&id=<?= $deployment['id'] ?>&method=edit" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=map" class="btn btn-sm btn-info">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p>No active deployments found</p>
                                <small>All deployments are either completed, cancelled, or not yet started.</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
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
.status-cancelled { background-color: #f8d7da; color: #721c24; }
</style> 