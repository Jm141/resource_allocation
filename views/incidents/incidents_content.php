<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
        Incidents Management
    </h1>
    <div>
        <a href="index.php?action=report" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Report New Incident
        </a>
    </div>
</div>

<!-- Incidents Table -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Incidents
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable" id="incidentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($incidents) && is_array($incidents)): ?>
                        <?php foreach ($incidents as $incident): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($incident['incident_id'] ?? $incident['id'] ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($incident['title']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars(substr($incident['description'] ?? '', 0, 100)) ?>...</small>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= htmlspecialchars($incident['category_name'] ?? 'N/A') ?></span>
                            </td>
                            <td>
                                <span class="priority-badge priority-<?= $incident['priority'] ?>">
                                    <?= ucfirst($incident['priority']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $incident['status'] ?>">
                                    <?= ucwords(str_replace('_', ' ', $incident['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($incident['location_name'] ?? 'N/A') ?>">
                                    <?= htmlspecialchars($incident['location_name'] ?? 'N/A') ?>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y H:i', strtotime($incident['created_at'] ?? 'now')) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=incidents&id=<?= $incident['id'] ?? $incident['incident_id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?action=incidents&id=<?= $incident['id'] ?? $incident['incident_id'] ?>&method=edit" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteIncident(<?= $incident['id'] ?? $incident['incident_id'] ?>)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>No incidents found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#incidentsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']]
    });
});

function deleteIncident(incidentId) {
    if (confirm('Are you sure you want to delete this incident?')) {
        window.location.href = 'index.php?action=incidents&id=' + incidentId + '&method=delete';
    }
}
</script> 