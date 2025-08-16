<?php
$page_title = 'Live Map - Resource Allocation System';
$action = 'map';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-map-marked-alt text-primary me-2"></i>
        Live Map & Tracking
    </h1>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card card-custom">
            <div class="card-body p-0">
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="card card-custom mb-3">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Active Incidents
                </h6>
            </div>
            <div class="card-body">
                <div id="activeIncidents">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>
        
        <div class="card card-custom">
            <div class="card-header-custom">
                <h6 class="mb-0">
                    <i class="fas fa-truck me-2"></i>Active Deployments
                </h6>
            </div>
            <div class="card-body">
                <div id="activeDeployments">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;

document.addEventListener('DOMContentLoaded', function() {
    initializeMap();
    loadMapData();
});

function initializeMap() {
    map = L.map('map').setView([10.5377, 122.8363], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

function loadMapData() {
    // Simulate loading data
    setTimeout(() => {
        updateIncidentsPanel();
        updateDeploymentsPanel();
    }, 1000);
}

function updateIncidentsPanel() {
    const container = document.getElementById('activeIncidents');
    container.innerHTML = `
        <div class="mb-2 p-2 border rounded">
            <div class="fw-bold small">Traffic Accident on Main Street</div>
            <div class="text-muted small">Main Street, Bago City</div>
            <div class="mt-1">
                <span class="badge bg-warning small">High Priority</span>
            </div>
        </div>
    `;
}

function updateDeploymentsPanel() {
    const container = document.getElementById('activeDeployments');
    container.innerHTML = `
        <div class="mb-2 p-2 border rounded">
            <div class="fw-bold small">Medical Emergency at Central Park</div>
            <div class="text-muted small">John Smith - AMB-001</div>
            <div class="mt-1">
                <span class="badge bg-primary small">En Route</span>
            </div>
        </div>
    `;
}
</script> 