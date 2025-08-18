<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-truck text-info me-2"></i>
        Vehicle Management
    </h1>
    <div>
        <a href="index.php?action=admin&method=createVehicle" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Add Vehicle
        </a>
    </div>
</div>

<!-- Vehicles Table -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Vehicles
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($vehicles)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-truck fa-3x mb-3"></i>
                <h5>No Vehicles Found</h5>
                <p>No vehicles have been added yet.</p>
                <a href="index.php?action=admin&method=createVehicle" class="btn btn-orange">
                    <i class="fas fa-plus-circle me-2"></i>Add First Vehicle
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vehicle ID</th>
                            <th>Type</th>
                            <th>Model</th>
                            <th>Status</th>
                            <th>Capacity</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($vehicle['vehicle_id']) ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($vehicle['vehicle_type']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($vehicle['model'] ?? 'N/A') ?></small>
                            </td>
                            <td><?= htmlspecialchars($vehicle['model'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge bg-<?= $vehicle['status'] === 'available' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($vehicle['status'] ?? 'unknown') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($vehicle['capacity'] ?? 'N/A') ?></td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y H:i', strtotime($vehicle['created_at'] ?? 'now')) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=admin&method=editVehicle&id=<?= $vehicle['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteVehicle(<?= $vehicle['id'] ?>)" title="Delete">
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
function deleteVehicle(vehicleId) {
    if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
        window.location.href = 'index.php?action=admin&method=deleteVehicle&id=' + vehicleId;
    }
}
</script> 