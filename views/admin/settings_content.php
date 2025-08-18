<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-cog text-secondary me-2"></i>
        System Settings
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>System Logs
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Level</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= getLogLevelColor($log['level']) ?>">
                                                <?= ucfirst($log['level']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($log['message']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <p>No system logs found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-tools me-2"></i>System Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning" onclick="clearLogs()">
                        <i class="fas fa-trash me-2"></i>Clear Old Logs
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportLogs()">
                        <i class="fas fa-download me-2"></i>Export Logs
                    </button>
                    <button type="button" class="btn btn-success" onclick="backupSystem()">
                        <i class="fas fa-save me-2"></i>Backup System
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearLogs() {
    if (confirm('Are you sure you want to clear old system logs?')) {
        // Implementation for clearing logs
        alert('Log clearing functionality will be implemented here.');
    }
}

function exportLogs() {
    // Implementation for exporting logs
    alert('Log export functionality will be implemented here.');
}

function backupSystem() {
    // Implementation for system backup
    alert('System backup functionality will be implemented here.');
}
</script>

<?php
function getLogLevelColor($level) {
    switch ($level) {
        case 'error': return 'danger';
        case 'warning': return 'warning';
        case 'info': return 'info';
        case 'debug': return 'secondary';
        default: return 'info';
    }
}
?> 