<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-edit text-warning me-2"></i>
        Edit Deployment
    </h1>
    <div>
        <a href="index.php?action=deployments" class="btn btn-outline-secondary">
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
        <form action="index.php?action=deployments&method=edit&id=<?= $deployment['id'] ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_location" class="form-label">Start Location</label>
                        <input type="text" class="form-control" id="start_location" name="start_location" 
                               value="<?= htmlspecialchars($deployment['start_location']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_location" class="form-label">End Location</label>
                        <input type="text" class="form-control" id="end_location" name="end_location" 
                               value="<?= htmlspecialchars($deployment['end_location']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_lat" class="form-label">Start Latitude</label>
                        <input type="number" step="any" class="form-control" id="start_lat" name="start_lat" 
                               value="<?= htmlspecialchars($deployment['start_lat']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_lng" class="form-label">Start Longitude</label>
                        <input type="number" step="any" class="form-control" id="start_lng" name="start_lng" 
                               value="<?= htmlspecialchars($deployment['start_lng']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
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
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_lat" class="form-label">End Latitude</label>
                        <input type="number" step="any" class="form-control" id="end_lat" name="end_lat" 
                               value="<?= htmlspecialchars($deployment['end_lat']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_lng" class="form-label">End Longitude</label>
                        <input type="number" step="any" class="form-control" id="end_lng" name="end_lng" 
                               value="<?= htmlspecialchars($deployment['end_lng']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="4" 
                          placeholder="Add any notes about this deployment..."><?= htmlspecialchars($deployment['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Update Deployment
                </button>
            </div>
        </form>
    </div>
</div> 