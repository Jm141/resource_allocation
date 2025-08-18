<?php
require_once __DIR__ . '/../models/Incident.php';
require_once __DIR__ . '/../models/Deployment.php';
require_once __DIR__ . '/../config/database.php';

class HotlineController {
    private $incidentModel;
    private $deploymentModel;
    
    public function __construct() {
        $this->incidentModel = new Incident();
        $this->deploymentModel = new Deployment();
    }

    public function index() {
        // Get hotline statistics
        $stats = $this->getHotlineStats();
        
        // Set variables for the view
        $page_title = 'Emergency Hotline Dashboard - Resource Allocation System';
        $action = 'hotline';
        
        $content_file = __DIR__ . '/../views/hotline_dashboard_content.php';
        include 'views/layouts/main.php';
    }

    private function getHotlineStats() {
        try {
            return [
                'total_requests' => $this->incidentModel->getCount(),
                'pending_requests' => $this->incidentModel->getCountByStatus('reported'),
                'urgent_requests' => $this->incidentModel->getCountByStatus('assigned'),
                'resolved_requests' => $this->incidentModel->getCountByStatus('resolved')
            ];
        } catch (Exception $e) {
            return [
                'total_requests' => 0,
                'pending_requests' => 0,
                'urgent_requests' => 0,
                'resolved_requests' => 0
            ];
        }
    }
}
?> 