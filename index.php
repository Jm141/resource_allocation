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
        include 'views/hotline_dashboard.php';
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