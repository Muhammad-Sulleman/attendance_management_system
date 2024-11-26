<?php
// Check if session is already started before calling session_start
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

include __DIR__ . '/partials/header.php'; // Include your header (relative path may vary)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Adjust the path if needed -->
</head>

<body>
    <div class="container">
        <?php
        if (isset($content)) {
            include __DIR__ . '/' . $content;
        } else {
            echo "<p style='color:red;'>Content file not found or not set!</p>";
        }
        ?>
    </div>
</body>

</html>