<?php
/*
HOW TO USE THE MAIN LAYOUT SYSTEM
==================================

This file shows you the pattern for using the main layout in your views.

STEP 1: Set the required variables at the top of your view file
STEP 2: Include the main layout
STEP 3: Write your page content after the include

*/

// STEP 1: Set these variables BEFORE including the layout
$page_title = 'Your Page Title - Resource Allocation System';
$action = 'your_action_name'; // This determines which sidebar link is active

// STEP 2: Include the main layout (this will render the header, navbar, sidebar, etc.)
include 'views/layouts/main.php';

// STEP 3: Your page content goes here (everything after the include)
?>

<!-- YOUR PAGE CONTENT STARTS HERE -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-star text-warning me-2"></i>
        Your Page Title
    </h1>
    <div>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Your content here -->
<div class="card card-custom">
    <div class="card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Your Content
        </h5>
    </div>
    <div class="card-body">
        <p>This is your page content. The layout system will automatically:</p>
        <ul>
            <li>Show the navigation bar</li>
            <li>Display the sidebar with active highlighting</li>
            <li>Apply the orange theme and styling</li>
            <li>Include Bootstrap, DataTables, and Font Awesome</li>
            <li>Show success/error messages if they exist</li>
        </ul>
    </div>
</div>

<!-- You can add more content, forms, tables, etc. here --> 