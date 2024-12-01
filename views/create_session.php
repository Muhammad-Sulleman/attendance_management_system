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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission to create a new session
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $credit_hours = $_POST['credit_hours'];

    // Insert the new session into the database
    $query = "INSERT INTO `class` (`teacherid`, `starttime`, `endtime`, `credit_hours`) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issi', $teacher_id, $starttime, $endtime, $credit_hours);
    $stmt->execute();

    // Redirect to teacher.php with the teacher ID in the URL
    header("Location: teacher.php?teacherid=" . $teacher_id);
    exit;
}
?>

<div class="container">
    <h2>Create a New Session</h2>
    <form method="POST" action="create_session.php?teacherid=<?= $teacher_id ?>">
        <label for="starttime">Start Time:</label>
        <input type="time" id="time-input" value="12:00" name="starttime" list="time-options" required><br>


        <label for="endtime">End Time:</label>
        <input type="time" value="12:00" name="endtime" required><br>

        <label for="credit_hours">Credit Hours:</label>
        <input type="number" name="credit_hours"  value="1" required><br>

        <button type="submit">Create Session</button>
    </form>


</div>

<?php include 'partials/footer.php'; ?>