<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-edit text-warning me-2"></i>
        Edit Deployment
    </h1>
    <div>
        <a href="index.php?action=admin&method=deployments" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Deployments
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Deployment Details
        </h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=admin&method=editDeployment&id=<?= $deployment['id'] ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="dispatched" <?= ($deployment['status'] == 'dispatched') ? 'selected' : '' ?>>Dispatched</option>
                            <option value="en_route" <?= ($deployment['status'] == 'en_route') ? 'selected' : '' ?>>En Route</option>
                            <option value="on_scene" <?= ($deployment['status'] == 'on_scene') ? 'selected' : '' ?>>On Scene</option>
                            <option value="returning" <?= ($deployment['status'] == 'returning') ? 'selected' : '' ?>>Returning</option>
                            <option value="completed" <?= ($deployment['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= ($deployment['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($deployment['notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Update Deployment
                </button>
            </div>
        </form>
    </div>
</div> 