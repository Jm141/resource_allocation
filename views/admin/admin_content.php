<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-cogs text-primary me-2"></i>
        System Administration Dashboard
    </h1>
    <div>
        <span class="badge bg-success fs-6">
            <i class="fas fa-circle me-2"></i>System Online
        </span>
    </div>
</div>

<!-- System Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['users'] ?? 0 ?></div>
                <div class="label">Total Users</div>
                <i class="fas fa-users fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['drivers'] ?? 0 ?></div>
                <div class="label">Active Drivers</div>
                <i class="fas fa-user-tie fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['vehicles'] ?? 0 ?></div>
                <div class="label">Available Vehicles</div>
                <i class="fas fa-truck fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['incidents'] ?? 0 ?></div>
                <div class="label">Active Incidents</div>
                <i class="fas fa-exclamation-triangle fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['deployments'] ?? 0 ?></div>
                <div class="label">Active Deployments</div>
                <i class="fas fa-route fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number"><?= $stats['hotline_requests'] ?? 0 ?></div>
                <div class="label">Hotline Requests</div>
                <i class="fas fa-phone-alt fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=createUser" class="btn btn-orange w-100">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=createVehicle" class="btn btn-orange w-100">
                            <i class="fas fa-truck me-2"></i>Add New Vehicle
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=createDriver" class="btn btn-orange w-100">
                            <i class="fas fa-user-tie me-2"></i>Add New Driver
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=settings" class="btn btn-orange w-100">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Recent Incidents
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($recentIncidents)): ?>
                    <?php foreach ($recentIncidents as $incident): ?>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?= htmlspecialchars($incident['title']) ?></h6>
                                <small class="text-muted">
                                    <?= date('M d, H:i', strtotime($incident['created_at'])) ?> • 
                                    <span class="badge bg-<?= $incident['priority'] === 'high' ? 'danger' : ($incident['priority'] === 'medium' ? 'warning' : 'success') ?>">
                                        <?= ucfirst($incident['priority']) ?>
                                    </span>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No recent incidents</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-route me-2"></i>Recent Deployments
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($recentDeployments)): ?>
                    <?php foreach ($recentDeployments as $deployment): ?>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-truck text-info fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?= htmlspecialchars($deployment['deployment_id']) ?></h6>
                                <small class="text-muted">
                                    <?= date('M d, H:i', strtotime($deployment['created_at'])) ?> • 
                                    <span class="badge bg-<?= $deployment['status'] === 'dispatched' ? 'warning' : ($deployment['status'] === 'en_route' ? 'info' : 'success') ?>">
                                        <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                    </span>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No recent deployments</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-phone-alt me-2"></i>Recent Hotline Requests
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($recentHotlineRequests)): ?>
                    <?php foreach ($recentHotlineRequests as $request): ?>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone text-danger fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?= htmlspecialchars($request['phone_number']) ?></h6>
                                <small class="text-muted">
                                    <?= date('M d, H:i', strtotime($request['created_at'])) ?> • 
                                    <span class="badge bg-<?= $request['priority'] === 'high' ? 'danger' : ($request['priority'] === 'medium' ? 'warning' : 'success') ?>">
                                        <?= ucfirst($request['priority']) ?>
                                    </span>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No recent hotline requests</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- System Health -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-heartbeat me-2"></i>System Health
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <h6>Database</h6>
                            <span class="badge bg-success">Connected</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <h6>Web Server</h6>
                            <span class="badge bg-success">Running</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <h6>Hotline System</h6>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <h6>API Endpoints</h6>
                            <span class="badge bg-success">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 