<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-cogs text-primary me-2"></i>
        Admin Dashboard
    </h1>
    <div>
        <span class="badge bg-success me-2">
            <i class="fas fa-shield-alt me-1"></i>Admin Access
        </span>
        <small class="text-muted">System Administration</small>
    </div>
</div>

<!-- Admin Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $stats['total_users'] ?? 0 ?></div>
                <div class="label">Total Users</div>
            <div class="trend">
                <small class="text-muted">System accounts</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $stats['total_vehicles'] ?? 0 ?></div>
            <div class="label">Total Vehicles</div>
            <div class="trend">
                <small class="text-muted">Emergency fleet</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-info">
            <div class="number"><?= $stats['total_drivers'] ?? 0 ?></div>
            <div class="label">Total Drivers</div>
            <div class="trend">
                <small class="text-muted">Response personnel</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-success">
            <div class="number"><?= $stats['total_facilities'] ?? 0 ?></div>
            <div class="label">Emergency Facilities</div>
            <div class="trend">
                <small class="text-muted">Response centers</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Management Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Management Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=users" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=vehicles" class="btn btn-outline-info w-100">
                            <i class="fas fa-truck me-2"></i>Manage Vehicles
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=drivers" class="btn btn-outline-success w-100">
                            <i class="fas fa-user-tie me-2"></i>Manage Drivers
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=admin&method=settings" class="btn btn-outline-warning w-100">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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