<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Hotline Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .status-pending { background-color: #fff3cd; }
        .status-assigned { background-color: #d1ecf1; }
        .status-in_progress { background-color: #d4edda; }
        .status-resolved { background-color: #c3e6cb; }
        .emergency-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .emergency-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#">
                                <i class="fas fa-phone-alt"></i> Hotline Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-list"></i> All Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-chart-bar"></i> Statistics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Emergency Hotline Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshData()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stats-card border-0 shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                                            Total Requests</div>
                                        <div class="h5 mb-0 font-weight-bold text-white" id="totalRequests">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-white-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingRequests">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Urgent</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="urgentRequests">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Resolved</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="resolvedRequests">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Requests -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Active Emergency Requests</h6>
                            </div>
                            <div class="card-body">
                                <div id="activeRequestsContainer">
                                    <!-- Requests will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="requestModalBody">
                    <!-- Request details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="assignRequestBtn">Assign to Me</button>
                    <button type="button" class="btn btn-success" id="resolveRequestBtn">Mark Resolved</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRequestId = null;

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            refreshData();
            // Auto-refresh every 30 seconds
            setInterval(refreshData, 30000);
        });

        function refreshData() {
            loadStatistics();
            loadActiveRequests();
        }

        function loadStatistics() {
            fetch('api/hotline_statistics.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalRequests').textContent = data.total_requests || 0;
                    document.getElementById('pendingRequests').textContent = data.pending_requests || 0;
                    document.getElementById('urgentRequests').textContent = data.urgent_requests || 0;
                    document.getElementById('resolvedRequests').textContent = data.resolved_requests || 0;
                })
                .catch(error => console.error('Error loading statistics:', error));
        }

        function loadActiveRequests() {
            fetch('api/hotline_requests.php')
                .then(response => response.json())
                .then(requests => {
                    const container = document.getElementById('activeRequestsContainer');
                    container.innerHTML = '';

                    if (requests.length === 0) {
                        container.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-3x mb-3"></i><p>No active requests at the moment</p></div>';
                        return;
                    }

                    requests.forEach(request => {
                        const card = createRequestCard(request);
                        container.appendChild(card);
                    });
                })
                .catch(error => console.error('Error loading requests:', error));
        }

        function createRequestCard(request) {
            const card = document.createElement('div');
            card.className = `card emergency-card mb-3 priority-${request.priority} status-${request.status}`;
            card.onclick = () => showRequestDetails(request.id);

            const priorityIcon = getPriorityIcon(request.priority);
            const statusBadge = getStatusBadge(request.status);
            const timeAgo = getTimeAgo(request.created_at);

            card.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="card-title">
                                <i class="fas fa-phone me-2"></i>
                                ${request.phone_number}
                                ${priorityIcon}
                            </h6>
                            <p class="card-text text-truncate">${request.message}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>${timeAgo} • 
                                <i class="fas fa-tag me-1"></i>${request.request_type.replace('_', ' ')}
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            ${statusBadge}
                            <br>
                            <small class="text-muted">ID: ${request.id}</small>
                        </div>
                    </div>
                </div>
            `;

            return card;
        }

        function getPriorityIcon(priority) {
            const icons = {
                'high': '<span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle"></i> High</span>',
                'medium': '<span class="badge bg-warning ms-2"><i class="fas fa-exclamation"></i> Medium</span>',
                'low': '<span class="badge bg-success ms-2"><i class="fas fa-info-circle"></i> Low</span>'
            };
            return icons[priority] || icons['medium'];
        }

        function getStatusBadge(status) {
            const badges = {
                'pending': '<span class="badge bg-warning">Pending</span>',
                'assigned': '<span class="badge bg-info">Assigned</span>',
                'in_progress': '<span class="badge bg-primary">In Progress</span>',
                'resolved': '<span class="badge bg-success">Resolved</span>'
            };
            return badges[status] || badges['pending'];
        }

        function getTimeAgo(timestamp) {
            const now = new Date();
            const created = new Date(timestamp);
            const diffMs = now - created;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffMins < 1440) return `${Math.floor(diffMins / 60)}h ago`;
            return `${Math.floor(diffMins / 1440)}d ago`;
        }

        function showRequestDetails(requestId) {
            currentRequestId = requestId;
            fetch(`api/hotline_request_details.php?id=${requestId}`)
                .then(response => response.json())
                .then(request => {
                    const modalBody = document.getElementById('requestModalBody');
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Request Information</h6>
                                <p><strong>ID:</strong> ${request.id}</p>
                                <p><strong>Phone:</strong> ${request.phone_number}</p>
                                <p><strong>Type:</strong> ${request.request_type.replace('_', ' ')}</p>
                                <p><strong>Priority:</strong> ${request.priority}</p>
                                <p><strong>Status:</strong> ${request.status}</p>
                                <p><strong>Created:</strong> ${new Date(request.created_at).toLocaleString()}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Message</h6>
                                <div class="border p-3 bg-light">
                                    ${request.message}
                                </div>
                                ${request.notes ? `<h6 class="mt-3">Notes</h6><p>${request.notes}</p>` : ''}
                            </div>
                        </div>
                    `;
                    
                    const modal = new bootstrap.Modal(document.getElementById('requestModal'));
                    modal.show();
                })
                .catch(error => console.error('Error loading request details:', error));
        }

        // Event handlers for modal buttons
        document.getElementById('assignRequestBtn').onclick = function() {
            if (currentRequestId) {
                updateRequestStatus(currentRequestId, 'assigned', 'Current User');
            }
        };

        document.getElementById('resolveRequestBtn').onclick = function() {
            if (currentRequestId) {
                updateRequestStatus(currentRequestId, 'resolved');
            }
        };

        function updateRequestStatus(requestId, status, assignedTo = null) {
            const formData = new FormData();
            formData.append('request_id', requestId);
            formData.append('status', status);
            if (assignedTo) formData.append('assigned_to', assignedTo);

            fetch('api/update_request_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('requestModal')).hide();
                    refreshData();
                } else {
                    alert('Error updating request status');
                }
            })
            .catch(error => console.error('Error updating status:', error));
        }
    </script>
</body>
</html> 