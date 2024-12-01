<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
