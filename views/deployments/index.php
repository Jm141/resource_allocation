<?php
$page_title = 'Deployments - Resource Allocation System';
$action = 'deployments';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-truck text-info me-2"></i>
        Deployments Management
    </h1>
    <div>
        <a href="index.php?action=deployments&method=create" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Create Deployment
        </a>
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
        <div class="table-responsive">
            <table class="table table-hover datatable" id="deploymentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Incident</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Start Location</th>
                        <th>End Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deployments as $deployment): ?>
                    <tr>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($deployment['deployment_id']) ?></span>
                        </td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($deployment['incident_title']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($deployment['incident_location']) ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($deployment['driver_first_name'] . ' ' . $deployment['driver_last_name']) ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= htmlspecialchars($deployment['vehicle_code']) ?></span>
                            <small class="text-muted d-block"><?= htmlspecialchars($deployment['vehicle_type']) ?></small>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $deployment['status'] ?>">
                                <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 120px;" title="<?= htmlspecialchars($deployment['start_location']) ?>">
                                <?= htmlspecialchars($deployment['start_location']) ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 120px;" title="<?= htmlspecialchars($deployment['end_location']) ?>">
                                <?= htmlspecialchars($deployment['end_location']) ?>
                            </div>
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
    </div>
</div>

<script>
$(document).ready(function() {
    $('#deploymentsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[7, 'desc']]
    });
});

function deleteDeployment(deploymentId) {
    if (confirm('Are you sure you want to delete this deployment?')) {
        window.location.href = 'index.php?action=deployments&id=' + deploymentId + '&method=delete';
    }
}
</script> 