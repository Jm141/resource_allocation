<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Resource Allocation System' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom CSS with Orange Theme -->
    <style>
        :root {
            --primary-orange: #FF6B35;
            --secondary-orange: #FF8C42;
            --accent-orange: #FFA726;
            --light-orange: #FFE0B2;
            --dark-orange: #E65100;
        }

        body {
            background-color: #F8F9FA;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background: white;
            border-right: 3px solid var(--primary-orange);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            padding: 25px 20px;
            text-align: center;
            border-bottom: 3px solid var(--dark-orange);
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .sidebar-header .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .sidebar .nav-link {
            color: #2C3E50;
            padding: 15px 25px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
            border-radius: 0 25px 25px 0;
            margin: 5px 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--light-orange);
            color: var(--dark-orange);
            border-left-color: var(--primary-orange);
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            margin-right: 15px;
            color: var(--primary-orange);
            width: 20px;
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-top: 20px;
            margin-right: 20px;
            min-height: calc(100vh - 40px);
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            font-weight: 600;
        }

        .btn-orange {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            border: none;
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--primary-orange));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85em;
        }

        .status-reported { background-color: #FFF3E0; color: #E65100; }
        .status-assigned { background-color: #E3F2FD; color: #1565C0; }
        .status-in_progress { background-color: #E8F5E8; color: #2E7D32; }
        .status-resolved { background-color: #F3E5F5; color: #7B1FA2; }
        .status-closed { background-color: #F5F5F5; color: #616161; }

        .priority-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8em;
        }

        .priority-critical { background-color: #FFEBEE; color: #C62828; }
        .priority-high { background-color: #FFF3E0; color: #E65100; }
        .priority-medium { background-color: #FFF8E1; color: #F57F17; }
        .priority-low { background-color: #F1F8E9; color: #558B2F; }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stats-card .label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .table-custom {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .table-custom thead th {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table-custom tbody tr:hover {
            background-color: var(--light-orange);
            transition: all 0.3s ease;
        }

        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success { background-color: #E8F5E8; color: #2E7D32; }
        .alert-danger { background-color: #FFEBEE; color: #C62828; }
        .alert-warning { background-color: #FFF3E0; color: #E65100; }
        .alert-info { background-color: #E3F2FD; color: #1565C0; }

        .footer {
            background: #2C3E50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            margin-left: 280px;
        }

        /* Responsive sidebar */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 20px;
                margin-right: 20px;
            }
            
            .footer {
                margin-left: 20px;
            }
            
            .sidebar-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: var(--primary-orange);
                color: white;
                border: none;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
        
        .sidebar-toggle {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-shield-alt me-2"></i>Resource Allocation</h4>
            <div class="subtitle">Emergency Response System</div>
        </div>
        
        <nav class="nav flex-column mt-3">
            <a class="nav-link <?= $action == 'dashboard' ? 'active' : '' ?>" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link <?= $action == 'incidents' ? 'active' : '' ?>" href="index.php?action=incidents">
                <i class="fas fa-exclamation-triangle"></i> Incidents
            </a>
            <a class="nav-link <?= $action == 'deployments' ? 'active' : '' ?>" href="index.php?action=deployments">
                <i class="fas fa-truck"></i> Deployments
            </a>
            <a class="nav-link <?= $action == 'map' ? 'active' : '' ?>" href="index.php?action=map">
                <i class="fas fa-map-marked-alt"></i> Live Map
            </a>
            <a class="nav-link <?= $action == 'report' ? 'active' : '' ?>" href="index.php?action=report">
                <i class="fas fa-plus-circle"></i> Report Incident
            </a>
            <a class="nav-link <?= $action == 'hotline' ? 'active' : '' ?>" href="index.php?action=hotline">
                <i class="fas fa-phone-alt"></i>
                <span>Hotline Dashboard</span>
            </a>
            
            <a class="nav-link <?= $action == 'driver' ? 'active' : '' ?>" href="index.php?action=driver">
                <i class="fas fa-user-tie"></i>
                <span>Driver Dashboard</span>
            </a>
            
            <!-- Admin Section -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="text-muted text-uppercase small px-3 mb-2">Administration</h6>
                <a class="nav-link <?= $action == 'admin' ? 'active' : '' ?>" href="index.php?action=admin">
                    <i class="fas fa-cogs"></i> System Admin
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'users' ? 'active' : '' ?>" href="index.php?action=admin&method=users">
                    <i class="fas fa-users"></i> User Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'vehicles' ? 'active' : '' ?>" href="index.php?action=admin&method=vehicles">
                    <i class="fas fa-truck"></i> Vehicle Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'drivers' ? 'active' : '' ?>" href="index.php?action=admin&method=drivers">
                    <i class="fas fa-user-tie"></i> Driver Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'incidents' ? 'active' : '' ?>" href="index.php?action=admin&method=incidents">
                    <i class="fas fa-exclamation-triangle"></i> Incident Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'deployments' ? 'active' : '' ?>" href="index.php?action=admin&method=deployments">
                    <i class="fas fa-route"></i> Deployment Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'hotline' ? 'active' : '' ?>" href="index.php?action=admin&method=hotline">
                    <i class="fas fa-phone-alt"></i> Hotline Management
                </a>
                <a class="nav-link <?= $action == 'admin' && $method == 'settings' ? 'active' : '' ?>" href="index.php?action=admin&method=settings">
                    <i class="fas fa-cog"></i> System Settings
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php
                $success = $_GET['success'];
                switch($success) {
                    case 'created': echo 'Record created successfully!'; break;
                    case 'updated': echo 'Record updated successfully!'; break;
                    case 'deleted': echo 'Record deleted successfully!'; break;
                    default: echo 'Operation completed successfully!';
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php
                $error = $_GET['error'];
                switch($error) {
                    case 'not_found': echo 'Record not found!'; break;
                    case 'create_failed': echo 'Failed to create record!'; break;
                    case 'update_failed': echo 'Failed to update record!'; break;
                    case 'delete_failed': echo 'Failed to delete record!'; break;
                    default: echo 'An error occurred!';
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- PAGE CONTENT GOES HERE -->
        <?php include $content_file; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>
                Resource Allocation System &copy; <?= date('Y') ?> - Emergency Response Management
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html> 