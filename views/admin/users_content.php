<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-users text-primary me-2"></i>
        User Management
    </h1>
    <div>
        <a href="index.php?action=admin&method=createUser" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Create User
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Users
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>No Users Found</h5>
                <p>No users have been created yet.</p>
                <a href="index.php?action=admin&method=createUser" class="btn btn-orange">
                    <i class="fas fa-plus-circle me-2"></i>Create First User
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($user['username'] ?? 'N/A') ?></small>
                            </td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge bg-<?= ($user['role'] === 'admin') ? 'danger' : (($user['role'] === 'driver') ? 'warning' : 'info') ?>">
                                    <?= ucfirst($user['role'] ?? 'operator') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= (isset($user['is_active']) && $user['is_active']) ? 'success' : 'secondary' ?>">
                                    <?= (isset($user['is_active']) && $user['is_active']) ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y H:i', strtotime($user['created_at'] ?? 'now')) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?action=admin&method=editUser&id=<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteUser(<?= $user['id'] ?>)" title="Delete">
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
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = 'index.php?action=admin&method=deleteUser&id=' + userId;
    }
}
</script> 