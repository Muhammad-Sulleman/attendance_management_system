<?php
// Database connection parameters
$servername = "localhost";   // Usually 'localhost' unless the database is hosted remotely
$username = "root";          // Your MySQL username (default is 'root')
$password = "";              // Your MySQL password (default is empty)
$dbname = "attendance_management_system"; // Name of the database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    error_log("This is a debug message");

}
