<?php
session_start();

// Simple routing system
$action = $_GET['action'] ?? 'dashboard';
$id = $_GET['id'] ?? null;

// Include necessary files
require_once 'controllers/IncidentController.php';
require_once 'controllers/DeploymentController.php';

// Route to appropriate controller
switch ($action) {
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
        include 'views/map/index.php';
        break;
        
    case 'dashboard':
    default:
        include 'views/dashboard/index.php';
        break;
}
?> 