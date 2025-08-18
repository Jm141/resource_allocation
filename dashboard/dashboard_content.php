<?php
// Dashboard Content
?>

<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-tachometer-alt text-primary me-2"></i>
        Emergency Response Dashboard
    </h1>
    <div>
        <span class="badge bg-success me-2">
            <i class="fas fa-circle me-1"></i>System Online
        </span>
        <small class="text-muted">Last updated: <?= date('M d, Y H:i:s') ?></small>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $totalIncidents ?></div>
            <div class="label">Total Incidents</div>
            <div class="trend">
                <small class="text-muted">All time</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $activeDeployments ?></div>
            <div class="label">Active Deployments</div>
            <div class="trend">
                <small class="text-muted">Currently responding</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $resolvedIncidents ?></div>
            <div class="label">Resolved Incidents</div>
            <div class="trend">
                <small class="text-muted">Successfully handled</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $pendingReports ?></div>
            <div class="label">Pending Reports</div>
            <div class="trend">
                <small class="text-muted">Awaiting response</small>
            </div>
        </div>
    </div>
</div>

<!-- System Resources -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-info">
            <div class="number"><?= $totalUsers ?></div>
            <div class="label">Available Personnel</div>
            <div class="trend">
                <small class="text-muted">Drivers & responders</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-info">
            <div class="number"><?= $totalVehicles ?></div>
            <div class="label">Available Vehicles</div>
            <div class="trend">
                <small class="text-muted">Ambulances, fire trucks, etc.</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-info">
            <div class="number"><?= $totalFacilities ?></div>
            <div class="label">Emergency Facilities</div>
            <div class="trend">
                <small class="text-muted">Hospitals, stations, centers</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-success">
            <div class="number">100%</div>
            <div class="label">System Uptime</div>
            <div class="trend">
                <small class="text-muted">24/7 operation</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Recent Incidents
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentIncidents)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentIncidents as $incident): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($incident['title']) ?></div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($incident['location']) ?> • 
                                    <?= ucfirst($incident['category']) ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= getPriorityColor($incident['priority']) ?>">
                                    <?= ucfirst($incident['priority']) ?>
                                </span>
                                <br>
                                <small class="text-muted">
                                    <?= date('M d, H:i', strtotime($incident['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <p>No recent incidents</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-truck text-info me-2"></i>Recent Deployments
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentDeployments)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentDeployments as $deployment): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($deployment['incident']) ?></div>
                                <small class="text-muted">
                                    Driver: <?= htmlspecialchars($deployment['driver']) ?> • 
                                    Vehicle: <?= htmlspecialchars($deployment['vehicle']) ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= getStatusColor($deployment['status']) ?>">
                                    <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                </span>
                                <br>
                                <small class="text-muted">
                                    <?= date('M d, H:i', strtotime($deployment['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-truck fa-2x mb-2 text-muted"></i>
                        <p>No recent deployments</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=incidents&method=create" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Report Incident
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=deployments&method=createSmart" class="btn btn-outline-success w-100">
                            <i class="fas fa-brain me-2"></i>Smart Deployment
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=map" class="btn btn-outline-info w-100">
                            <i class="fas fa-map-marked-alt me-2"></i>Live Map
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=hotline" class="btn btn-outline-danger w-100">
                            <i class="fas fa-phone-alt me-2"></i>Hotline Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Helper functions
function getPriorityColor($priority) {
    switch ($priority) {
        case 'critical': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low': return 'secondary';
        default: return 'secondary';
    }
}

function getStatusColor($status) {
    switch ($status) {
        case 'dispatched': return 'primary';
        case 'en_route': return 'success';
        case 'on_scene': return 'warning';
        case 'completed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
?>

<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-card-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-card-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-card .number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.stats-card .label {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.stats-card .trend {
    font-size: 0.8rem;
    opacity: 0.8;
}

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}
</style> 