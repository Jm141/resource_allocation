<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-phone-alt text-danger me-2"></i>
        Hotline Management
    </h1>
</div>

<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Hotline Requests
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['id']) ?></td>
                                <td><?= htmlspecialchars($request['type'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-<?= getRequestStatusColor($request['status']) ?>">
                                        <?= ucwords(str_replace('_', ' ', $request['status'])) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($request['assigned_to'] ?? 'Unassigned') ?></td>
                                <td><?= date('M d, Y H:i', strtotime($request['created_at'])) ?></td>
                                <td>
                                    <a href="index.php?action=admin&method=updateHotlineRequest&id=<?= $request['id'] ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p>No hotline requests found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
function getRequestStatusColor($status) {
    switch ($status) {
        case 'pending': return 'warning';
        case 'assigned': return 'info';
        case 'in_progress': return 'primary';
        case 'resolved': return 'success';
        case 'closed': return 'secondary';
        default: return 'secondary';
    }
}
?> 