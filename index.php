<?php
session_start();

// Simple routing system
$action = $_GET['action'] ?? 'dashboard';
$id = $_GET['id'] ?? null;
$method = $_GET['method'] ?? null;

// Include necessary files
require_once 'controllers/IncidentController.php';
require_once 'controllers/DeploymentController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/MapController.php';
require_once 'controllers/FacilityController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/DriverController.php';
require_once 'controllers/HotlineController.php';

// Route to appropriate controller
switch ($action) {
    case 'admin':
        $controller = new AdminController();
        if ($method) {
            if ($id) {
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    header('Location: index.php?action=admin&error=method_not_found');
                    exit;
                }
            } else {
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    header('Location: index.php?action=admin&error=method_not_found');
                    exit;
                }
            }
        } else {
            $controller->index();
        }
        break;
        
    case 'incidents':
        $controller = new IncidentController();
        if ($id) {
            if (isset($_GET['method'])) {
                $method = $_GET['method'];
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    $controller->show($id);
                }
            } else {
                $controller->show($id);
            }
        } else {
            if (isset($_GET['method'])) {
                $method = $_GET['method'];
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    $controller->index();
                }
            } else {
                $controller->index();
            }
        }
        break;
        
    case 'deployments':
        $controller = new DeploymentController();
        if ($id) {
            if (isset($_GET['method'])) {
                $method = $_GET['method'];
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    $controller->show($id);
                }
            } else {
                $controller->show($id);
            }
        } else {
            if (isset($_GET['method'])) {
                $method = $_GET['method'];
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    $controller->index();
                }
            } else {
                $controller->index();
            }
        }
        break;
        
    case 'report':
        include 'views/incidents/create.php';
        break;
        
    case 'map':
        $controller = new MapController();
        if ($method) {
            if ($id) {
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    header('Location: index.php?action=map&error=method_not_found');
                    exit;
                }
            } else {
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    header('Location: index.php?action=map&error=method_not_found');
                    exit;
                }
            }
        } else {
            $controller->index();
        }
        break;
        
    case 'facilities':
        $controller = new FacilityController();
        if ($method) {
            if ($id) {
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    header('Location: index.php?action=facilities&error=method_not_found');
                    exit;
                }
            } else {
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    header('Location: index.php?action=facilities&error=method_not_found');
                    exit;
                }
            }
        } else {
            $controller->index();
        }
        break;
        
    case 'hotline':
        $hotlineController = new HotlineController();
        $hotlineController->index();
        break;
        
    case 'driver':
        $driverController = new DriverController();
        $method = $_GET['method'] ?? 'index';
        
        if ($method === 'deployment' && isset($_GET['id'])) {
            $driverController->deployment($_GET['id']);
        } elseif ($method === 'updateStatus' && isset($_GET['id'])) {
            $driverController->updateStatus($_GET['id']);
        } elseif ($method === 'updateLocation') {
            $driverController->updateLocation();
        } elseif ($method === 'getActiveDeployments') {
            $driverController->getActiveDeployments();
        } elseif ($method === 'getDeploymentRoute') {
            $driverController->getDeploymentRoute();
        } elseif ($method === 'getTrafficAlerts') {
            $driverController->getTrafficAlerts();
        } elseif ($method === 'getDriverStats') {
            $driverController->getDriverStats();
        } else {
            $driverController->index();
        }
        break;
        
    case 'dashboard':
    default:
        $controller = new DashboardController();
        if ($method) {
            if ($id) {
                if (method_exists($controller, $method)) {
                    $controller->$method($id);
                } else {
                    header('Location: index.php?action=dashboard&error=method_not_found');
                    exit;
                }
            } else {
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    header('Location: index.php?action=dashboard&error=method_not_found');
                    exit;
                }
            }
        } else {
            $controller->index();
        }
        break;
}
?> 