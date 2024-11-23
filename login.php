<?php
session_start();
include 'includes/db_connection.php';


if ($conn) {
    echo "Database connected successfully!";
}
?>
