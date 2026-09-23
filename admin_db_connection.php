<?php
$servername = "127.0.0.1";   // Use IP, not localhost
$username   = "root";        // MySQL username
$password   = "";            // Empty password (XAMPP default)
$dbname     = "userinfo1";   // Your database name
$port       = 3307;          // IMPORTANT: Your MySQL port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>