<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-edit text-warning me-2"></i>
        Edit Hotline Request
    </h1>
    <div>
        <a href="index.php?action=admin&method=hotline" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Hotline
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Request Details
        </h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=admin&method=updateHotlineRequest&id=<?= $request['id'] ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?= ($request['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="assigned" <?= ($request['status'] == 'assigned') ? 'selected' : '' ?>>Assigned</option>
                            <option value="in_progress" <?= ($request['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                            <option value="resolved" <?= ($request['status'] == 'resolved') ? 'selected' : '' ?>>Resolved</option>
                            <option value="closed" <?= ($request['status'] == 'closed') ? 'selected' : '' ?>>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <input type="text" class="form-control" id="assigned_to" name="assigned_to" 
                               value="<?= htmlspecialchars($request['assigned_to'] ?? '') ?>" 
                               placeholder="Enter assignee name or ID">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="4" 
                          placeholder="Add any notes about this request..."><?= htmlspecialchars($request['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Update Request
                </button>
            </div>
        </form>
    </div>
</div> 