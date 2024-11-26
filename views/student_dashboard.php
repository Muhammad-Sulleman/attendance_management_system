<?php
include '../includes/db.php';
include '../includes/auth.php';

if (!isAuthenticated() || $_SESSION['user']['role'] !== 'student') {
    header('Location: ../login.php');
    exit;
}

echo "<h1>Welcome, Student: {$_SESSION['user']['fullname']}</h1>";
