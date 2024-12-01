<?php
session_start();
include 'includes/db_connection.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Determine which content to include based on the user's role
$content = ($_SESSION['user']['role'] == 'student') ? 'student.php' : 'teacher.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="student.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php if ($_SESSION['user']['role'] == 'teacher') : ?>
                <li><a href="teacher.php">Teacher Dashboard</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="container">
        <?php include($content); ?>
    </div>

</body>

</html>