<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Attendance Management</title>
    <!-- Corrected path to styles.css -->
    <link rel="stylesheet" href="/attendance_management_system/css/styles.css">
</head>

<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
        <a href="../logout.php">Logout</a>
    </header>
    <main>
        <?php
        // Safely include the content file
        if (isset($content) && file_exists($content)) {
            include $content;
        } else {
            echo "<p style='color: red;'>Content file not found or not set!</p>";
        }
        ?>
    </main>
    <footer>
        <p>&copy; 2024 Attendance Management System</p>
    </footer>
</body>

</html>