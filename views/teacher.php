<?php
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}

// Set the content file to include in the master layout
$content = "teacher.php";
include 'master.php';
?>

<!-- teacher_content.php -->
<div>
    <h2>Your Attendance Sessions</h2>

    <!-- Button to create new session -->
    <a href="create_session.php">Create New Session</a>

    <ul>
        <?php
        // Get teacher's classes from the database
        $teacher_id = $_SESSION['user']['id'];  // Get teacher's ID from the session
        $query = "SELECT * FROM `class` WHERE `teacherid` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<li>
                    Class ID: {$row['id']} | Start: {$row['starttime']} | End: {$row['endtime']}
                    <a href='mark_attendance.php?classid={$row['id']}'>Mark Attendance</a>
                  </li>";
        }
        ?>
    </ul>
</div>