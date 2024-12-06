<?php
session_start();
include '../includes/db_connection.php';
include './partials/header.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}

// Get the class ID from the query string
$class_id = $_GET['classid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mark attendance for the students in the class
    foreach ($_POST['attendance'] as $student_id => $is_present) {
        // Insert or update attendance
        $query = "INSERT INTO `attendance` (`classid`, `studentid`, `isPresent`) 
                  VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE isPresent = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiii', $class_id, $student_id, $is_present, $is_present);
        $stmt->execute();
    }

    // Redirect back to teacher's session page after marking attendance
    header("Location: teacher.php");
    exit;
}

// Query to fetch students for this class
$query = "SELECT user.id, user.fullname FROM user
          JOIN class ON class.id = ?
          WHERE user.role = 'student'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $class_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="mark-attendance">
    <form method="POST" action="mark_attendance.php?classid=<?php echo $class_id; ?>">
        <h3>Mark Attendance</h3>
        <ul>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <label>
                        <?php echo $row['fullname']; ?>

                    </label>
                    <input type="checkbox" name="attendance[<?php echo $row['id']; ?>]" value="1">
                </li>
            <?php } ?>
        </ul>
        <button type="submit">Submit </button>
    </form>

</div> <?php include './partials/footer.php'; ?>