<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-edit text-warning me-2"></i>
        Edit Incident
    </h1>
    <div>
        <a href="index.php?action=admin&method=incidents" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Incidents
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Incident Details
        </h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=admin&method=editIncident&id=<?= $incident['id'] ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Incident Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= htmlspecialchars($incident['title']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        <?= ($category['id'] == $incident['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($incident['description']) ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" <?= ($incident['priority'] == 'low') ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= ($incident['priority'] == 'medium') ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= ($incident['priority'] == 'high') ? 'selected' : '' ?>>High</option>
                            <option value="critical" <?= ($incident['priority'] == 'critical') ? 'selected' : '' ?>>Critical</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="reported" <?= ($incident['status'] == 'reported') ? 'selected' : '' ?>>Reported</option>
                            <option value="assigned" <?= ($incident['status'] == 'assigned') ? 'selected' : '' ?>>Assigned</option>
                            <option value="in_progress" <?= ($incident['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                            <option value="resolved" <?= ($incident['status'] == 'resolved') ? 'selected' : '' ?>>Resolved</option>
                            <option value="closed" <?= ($incident['status'] == 'closed') ? 'selected' : '' ?>>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Update Incident
                </button>
            </div>
        </form>
    </div>
</div> 