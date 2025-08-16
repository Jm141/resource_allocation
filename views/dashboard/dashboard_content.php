<!-- DASHBOARD CONTENT STARTS HERE -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-tachometer-alt text-warning me-2"></i>
        Dashboard
    </h1>
    <div>
        <a href="index.php?action=report" class="btn btn-orange">
            <i class="fas fa-plus-circle me-2"></i>Report New Incident
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $totalIncidents ?></div>
            <div class="label">Total Incidents</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $activeDeployments ?></div>
            <div class="label">Active Deployments</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $resolvedIncidents ?></div>
            <div class="label">Resolved Incidents</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="number"><?= $pendingReports ?></div>
            <div class="label">Pending Reports</div>
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
                            View Map
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="index.php?action=incidents" class="btn btn-outline-warning w-100">
                            <i class="fas fa-list fa-2x mb-2"></i><br>
                            View All Incidents
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
                </h5>
            </div>
            <div class="card-body">
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
                </h5>
            </div>
            <div class="card-body">
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
                                </td>
                                <td><?= htmlspecialchars($deployment['driver']) ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($deployment['vehicle']) ?></span>
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
                        <div class="text-success">
                            <i class="fas fa-circle fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Database</strong><br>
                            <small class="text-muted">Connected</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-circle fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Map Services</strong><br>
                            <small class="text-muted">Online</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-circle fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>Routing API</strong><br>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-success">
                            <i class="fas fa-circle fa-2x"></i>
                        </div>
                        <div class="mt-2">
                            <strong>System</strong><br>
                            <small class="text-muted">Operational</small>
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
                        <a href="views/hotline_dashboard.php" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Open Hotline Dashboard
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h6>Quick Access</h6>
                        <div class="list-group">
                            <a href="views/hotline_dashboard.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-tachometer-alt me-2"></i>Hotline Dashboard
                            </a>
                            <a href="webhooks/sms_webhook.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-sms me-2"></i>SMS Webhook
                            </a>
                            <a href="webhooks/voice_webhook.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-phone me-2"></i>Voice Webhook
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
});
</script> 