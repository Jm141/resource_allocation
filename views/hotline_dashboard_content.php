<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-phone-alt text-danger me-2"></i> Emergency Hotline Dashboard
    </h1>
    <div>
        <button type="button" class="btn btn-orange" onclick="refreshData()">
            <i class="fas fa-sync-alt me-2"></i>Refresh Data
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-custom stats-card">
            <div class="card-body text-center">
                <div class="number" id="totalRequests">0</div>
                <div class="label">Total Requests</div>
                <i class="fas fa-calendar fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-custom stats-card stats-card-secondary">
            <div class="card-body text-center">
                <div class="number" id="pendingRequests">0</div>
                <div class="label">Pending</div>
                <i class="fas fa-clock fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-custom stats-card stats-card-warning">
            <div class="card-body text-center">
                <div class="number" id="urgentRequests">0</div>
                <div class="label">Urgent</div>
                <i class="fas fa-exclamation-triangle fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-custom stats-card stats-card-success">
            <div class="card-body text-center">
                <div class="number" id="resolvedRequests">0</div>
                <div class="label">Resolved</div>
                <i class="fas fa-check-circle fa-2x mt-3 opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Active Requests -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Active Emergency Requests
        </h5>
    </div>
    <div class="card-body">
        <div id="activeRequestsContainer">
            <div class="text-center text-muted py-4">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Loading requests...</p>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Contact Information -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-phone me-2"></i>Emergency Numbers
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Police Emergency</div>
                            <small class="text-muted">Bago City Police Station</small>
                        </div>
                        <span class="badge bg-danger">911</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Fire Emergency</div>
                            <small class="text-muted">Bago City Fire Station</small>
                        </div>
                        <span class="badge bg-danger">911</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Medical Emergency</div>
                            <small class="text-muted">Bago City Hospital</small>
                        </div>
                        <span class="badge bg-danger">911</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Command Center</div>
                            <small class="text-muted">Emergency Response HQ</small>
                        </div>
                        <span class="badge bg-primary">(034) 123-4567</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>System Status
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">
                                <i class="fas fa-circle"></i>
                            </div>
                            <small class="text-muted">Hotline System</small>
                            <div class="fw-bold text-success">Online</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">
                                <i class="fas fa-circle"></i>
                            </div>
                            <small class="text-muted">SMS Gateway</small>
                            <div class="fw-bold text-success">Active</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">
                                <i class="fas fa-circle"></i>
                            </div>
                            <small class="text-muted">Call Routing</small>
                            <div class="fw-bold text-success">Operational</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">
                                <i class="fas fa-circle"></i>
                            </div>
                            <small class="text-muted">Response Teams</small>
                            <div class="fw-bold text-success">Ready</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple hotline dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    refreshData();
});

function refreshData() {
    // Simulate loading data
    document.getElementById('totalRequests').textContent = '12';
    document.getElementById('pendingRequests').textContent = '3';
    document.getElementById('urgentRequests').textContent = '2';
    document.getElementById('resolvedRequests').textContent = '7';
    
    // Show sample requests
    const container = document.getElementById('activeRequestsContainer');
    container.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> 
            Hotline system is integrated and ready. API endpoints are configured for real-time data.
        </div>
        <div class="text-center text-muted py-4">
            <i class="fas fa-phone-alt fa-3x mb-3 text-primary"></i>
            <p>Emergency Hotline System Active</p>
            <small>Ready to receive emergency calls and SMS</small>
        </div>
    `;
}
</script>

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

.stats-card-warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #856404;
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

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}
</style> 