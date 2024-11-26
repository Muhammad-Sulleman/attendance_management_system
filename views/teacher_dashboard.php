<?php
include '../includes/db.php';
include '../includes/auth.php';

if (!isAuthenticated() || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../login.php');
    exit;
}

$teacher_id = $_SESSION['user']['id'];

// Fetch classes
$query = "SELECT * FROM `class` WHERE `teacherid` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Teacher Dashboard</title>
</head>

<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></h1>

    <h2>Your Classes</h2>
    <ul>
        <?php foreach ($classes as $class): ?>
            <li>
                Class ID: <?= $class['id'] ?> |
                Start: <?= $class['starttime'] ?> |
                End: <?= $class['endtime'] ?> |
                Date: <?= $class['date'] ?> |
                <a href="mark_attendance.php?classid=<?= $class['id'] ?>">Mark Attendance</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>