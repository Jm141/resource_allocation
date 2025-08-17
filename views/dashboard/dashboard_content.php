<!-- DASHBOARD CONTENT STARTS HERE -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-tachometer-alt text-warning me-2"></i>
        Dashboard
    </h1>
    <div>
        <button type="button" class="btn btn-outline-primary me-2" onclick="refreshDashboard()">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
        <a href="index.php?action=report" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Report New Incident
        </a>
    </div>
</div>

<!-- Data Source Indicator -->
<div class="alert alert-info mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-database me-2"></i>
        <div>
            <strong>Real-Time Database Dashboard</strong>
            <br>
            <small class="text-muted">
                All statistics and data are fetched directly from the database in real-time. 
                Last updated: <?= date('M d, Y H:i:s') ?>
            </small>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $totalIncidents ?></div>
            <div class="label">Total Incidents</div>
            <div class="trend">
                <small class="text-muted">Database: Real-time</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $activeDeployments ?></div>
            <div class="label">Active Deployments</div>
            <div class="trend">
                <small class="text-muted">Live: <?= date('H:i') ?></small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $resolvedIncidents ?></div>
            <div class="label">Resolved Incidents</div>
            <div class="trend">
                <small class="text-muted">Rate: <?= $totalIncidents > 0 ? round(($resolvedIncidents / $totalIncidents) * 100, 1) : 0 ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $pendingReports ?></div>
            <div class="label">Pending Reports</div>
            <div class="trend">
                <small class="text-muted">Needs attention</small>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $totalUsers ?></div>
            <div class="label">Total Users</div>
            <div class="trend">
                <small class="text-muted">System users</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $totalVehicles ?></div>
            <div class="label">Total Vehicles</div>
            <div class="trend">
                <small class="text-muted">Emergency fleet</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $totalFacilities ?></div>
            <div class="label">Emergency Facilities</div>
            <div class="trend">
                <small class="text-muted">Response centers</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card stats-card-secondary">
            <div class="number"><?= $performanceMetrics['avg_response_time'] ?? 0 ?></div>
            <div class="label">Avg Response Time (min)</div>
            <div class="trend">
                <small class="text-muted">Performance metric</small>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Metrics
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="metric-circle">
                            <div class="metric-value"><?= $performanceMetrics['resolution_rate'] ?? 0 ?>%</div>
                            <div class="metric-label">Incident Resolution Rate</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="metric-circle">
                            <div class="metric-value"><?= $performanceMetrics['deployment_efficiency'] ?? 0 ?>%</div>
                            <div class="metric-label">Deployment Efficiency</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="metric-circle">
                            <div class="metric-value"><?= $performanceMetrics['avg_response_time'] ?? 0 ?> min</div>
                            <div class="metric-label">Average Response Time</div>
                        </div>
                    </div>
                </div>
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
                        <a href="index.php?action=report" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Report Incident
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=deployments&method=create" class="btn btn-outline-success w-100">
                            <i class="fas fa-truck fa-2x mb-2"></i><br>
                            Create Deployment
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=map" class="btn btn-outline-info w-100">
                            <i class="fas fa-map fa-2x mb-2"></i><br>
                            View Live Map
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=deployments&method=createSmart" class="btn btn-outline-warning w-100">
                            <i class="fas fa-brain fa-2x mb-2"></i><br>
                            Smart Deployment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Incidents and Deployments -->
<div class="row">
    <!-- Recent Incidents -->
    <div class="col-lg-6 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Recent Incidents
                    <span class="badge bg-warning ms-2"><?= count($recentIncidents) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentIncidents)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No incidents reported yet</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Incident</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentIncidents as $incident): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($incident['title']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($incident['location']) ?></small>
                                        <br><small class="text-muted"><?= date('M d, H:i', strtotime($incident['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="priority-badge priority-<?= $incident['priority'] ?>">
                                            <?= ucfirst($incident['priority']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $incident['status'] ?>">
                                            <?= ucwords(str_replace('_', ' ', $incident['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=incidents&id=<?= $incident['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-center mt-3">
                    <a href="index.php?action=incidents" class="btn btn-orange">
                        View All Incidents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deployments -->
    <div class="col-lg-6 mb-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Recent Deployments
                    <span class="badge bg-primary ms-2"><?= count($recentDeployments) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentDeployments)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-3x mb-3"></i>
                        <p>No deployments created yet</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Incident</th>
                                    <th>Driver</th>
                                    <th>Vehicle</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDeployments as $deployment): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($deployment['incident']) ?></div>
                                        <small class="text-muted"><?= date('M d, H:i', strtotime($deployment['created_at'])) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($deployment['driver']) ?: 'Not assigned' ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($deployment['vehicle']) ?: 'Not assigned' ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $deployment['status'] ?>">
                                            <?= ucwords(str_replace('_', ' ', $deployment['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-center mt-3">
                    <a href="index.php?action=deployments" class="btn btn-orange">
                        View All Deployments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-server me-2"></i>System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="text-<?= $systemStatus['database'] ? 'success' : 'danger' ?>">
                            <i class="fas fa-<?= $systemStatus['database'] ? 'circle' : 'times-circle' ?> fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Database</strong><br>
                            <small class="text-muted"><?= $systemStatus['database'] ? 'Connected' : 'Disconnected' ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-<?= $systemStatus['map_services'] ? 'success' : 'danger' ?>">
                            <i class="fas fa-<?= $systemStatus['map_services'] ? 'circle' : 'times-circle' ?> fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Map Services</strong><br>
                            <small class="text-muted"><?= $systemStatus['map_services'] ? 'Online' : 'Offline' ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-<?= $systemStatus['osrm_api'] ? 'success' : 'danger' ?>">
                            <i class="fas fa-<?= $systemStatus['osrm_api'] ? 'circle' : 'times-circle' ?> fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>OSRM Routing</strong><br>
                            <small class="text-muted"><?= $systemStatus['osrm_api'] ? 'Available' : 'Unavailable' ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-<?= $systemStatus['overall'] === 'operational' ? 'success' : ($systemStatus['overall'] === 'degraded' ? 'warning' : 'danger') ?>">
                            <i class="fas fa-<?= $systemStatus['overall'] === 'operational' ? 'circle' : 'exclamation-triangle' ?> fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Overall Status</strong><br>
                            <small class="text-muted"><?= ucfirst($systemStatus['overall']) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Facility Status -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-hospital me-2"></i>Emergency Facility Status
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="text-success">
                            <i class="fas fa-hospital fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Operational</strong><br>
                            <small class="text-muted"><?= $facilityStatus['operational'] ?? 0 ?> facilities</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-warning">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Maintenance</strong><br>
                            <small class="text-muted"><?= $facilityStatus['maintenance'] ?? 0 ?> facilities</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-danger">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Offline</strong><br>
                            <small class="text-muted"><?= $facilityStatus['offline'] ?? 0 ?> facilities</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hotline Integration Section -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-phone-alt me-2"></i>Emergency Hotline
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Hotline Status</h6>
                        <p class="text-success">
                            <i class="fas fa-circle me-2"></i>Active and Monitoring
                        </p>
                        <p class="text-muted">
                            Citizens can call or text the emergency hotline for immediate assistance.
                        </p>
                        <a href="index.php?action=hotline" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Open Hotline Dashboard
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h6>Quick Access</h6>
                        <div class="list-group">
                            <a href="index.php?action=hotline" class="list-group-item list-group-item-action">
                                <i class="fas fa-tachometer-alt me-2"></i>Hotline Dashboard
                            </a>
                            <a href="index.php?action=map" class="list-group-item list-group-item-action">
                                <i class="fas fa-map-marked-alt me-2"></i>Live Map
                            </a>
                            <a href="index.php?action=deployments&method=createSmart" class="list-group-item list-group-item-action">
                                <i class="fas fa-brain me-2"></i>Smart Deployment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add some interactivity to the dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all stats cards
    document.querySelectorAll('.stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    // Add click event to quick action buttons
    document.querySelectorAll('.btn-outline-primary, .btn-outline-success, .btn-outline-info, .btn-outline-warning').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });

    // Auto-refresh dashboard every 2 minutes
    setInterval(refreshDashboard, 120000);
});

// Dashboard refresh function
function refreshDashboard() {
    const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
    const originalText = refreshBtn.innerHTML;
    
    // Show loading state
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Reload the page to get fresh data
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Add real-time updates for critical metrics
function updateCriticalMetrics() {
    // Update time stamps
    const timeElements = document.querySelectorAll('.trend small');
    timeElements.forEach(element => {
        if (element.textContent.includes('Live:')) {
            element.textContent = 'Live: ' + new Date().toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        }
    });
}

// Update time every minute
setInterval(updateCriticalMetrics, 60000);
</script>

<style>
/* Dashboard specific styles */
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    pointer-events: none;
}

.stats-card-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

.metric-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.metric-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.metric-label {
    font-size: 0.75rem;
    text-align: center;
    opacity: 0.9;
    line-height: 1.2;
}

.priority-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-critical { background-color: #dc3545; color: white; }
.priority-high { background-color: #fd7e14; color: white; }
.priority-medium { background-color: #ffc107; color: #212529; }
.priority-low { background-color: #28a745; color: white; }

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-reported { background-color: #6c757d; color: white; }
.status-assigned { background-color: #17a2b8; color: white; }
.status-in_progress { background-color: #ffc107; color: #212529; }
.status-resolved { background-color: #28a745; color: white; }
.status-dispatched { background-color: #007bff; color: white; }
.status-en_route { background-color: #28a745; color: white; }
.status-on_scene { background-color: #ffc107; color: #212529; }
.status-returning { background-color: #6c757d; color: white; }
.status-completed { background-color: #28a745; color: white; }

/* Animation for empty states */
.text-center.text-muted i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-card .number {
        font-size: 2rem;
    }
    
    .metric-circle {
        width: 100px;
        height: 100px;
    }
    
    .metric-value {
        font-size: 1.25rem;
    }
}
</style> 