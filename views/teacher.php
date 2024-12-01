<?php
session_start();
include '../includes/db_connection.php';
include 'partials/header.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}

// Get teacher ID from session or URL
$teacher_id = $_GET['teacherid'] ?? $_SESSION['user']['id'];

// Query to fetch all classes taught by the teacher
$query = "SELECT * FROM `class` WHERE `teacherid` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div>
    <h2>Your Attendance Sessions</h2>

    <!-- Button to create a new session -->
    <button id="create-session">
        <a href="create_session.php?teacherid=<?= $teacher_id ?>" class="create-session">Create Session</a>
    </button>
    <div class="available-sessions">

        <!-- Display all classes taught by the teacher -->
        <ul>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<li>
                    Class ID: {$row['id']}  <br>  Start: {$row['starttime']} <br>   End: {$row['endtime']}
                    <a href='mark_attendance.php?classid={$row['id']}'>Mark Attendance</a>
                  </li>";
            }
            ?>
        </ul>
    </div>
</div>

<?php include 'partials/footer.php'; ?>