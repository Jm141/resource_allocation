<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        echo "Failed to connect to database!\n";
        exit(1);
    }
    
    echo "Connected to database successfully.\n";
    
    // Check users table structure
    $stmt = $conn->query("DESCRIBE users");
    echo "Users table structure:\n";
    while ($row = $stmt->fetch()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    // Check sample user data
    $stmt = $conn->query("SELECT * FROM users LIMIT 1");
    $sample = $stmt->fetch();
    if ($sample) {
        echo "\nSample user data:\n";
        foreach ($sample as $key => $value) {
            echo "- $key: " . (is_null($value) ? 'NULL' : $value) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 