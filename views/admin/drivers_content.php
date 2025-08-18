<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-user-tie text-success me-2"></i>
        Driver Management
    </h1>
    <div>
        <a href="index.php?action=admin&method=createDriver" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Add Driver
        </a>
    </div>
</div>

<!-- Drivers Table -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Drivers
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($drivers)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-user-tie fa-3x mb-3"></i>
                <h5>No Drivers Found</h5>
                <p>No drivers have been added yet.</p>
                <a href="index.php?action=admin&method=createDriver" class="btn btn-orange">
                    <i class="fas fa-plus-circle me-2"></i>Add First Driver
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Driver ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>License</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drivers as $driver): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($driver['driver_id']) ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($driver['first_name'] . ' ' . $driver['last_name']) ?></div>
                                <small class="text-muted">ID: <?= $driver['id'] ?></small>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($driver['phone'] ?? 'N/A') ?></div>
                                <small class="text-muted"><?= htmlspecialchars($driver['email'] ?? 'N/A') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?= $driver['status'] === 'available' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($driver['status'] ?? 'unknown') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($driver['license_number'] ?? 'N/A') ?></td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y H:i', strtotime($driver['created_at'] ?? 'now')) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=admin&method=editDriver&id=<?= $driver['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteDriver(<?= $driver['id'] ?>)" title="Delete">
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

<script>
function deleteDriver(driverId) {
    if (confirm('Are you sure you want to delete this driver? This action cannot be undone.')) {
        window.location.href = 'index.php?action=admin&method=deleteDriver&id=' + driverId;
    }
}
</script> 