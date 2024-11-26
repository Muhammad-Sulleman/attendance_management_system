<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}

$class_id = $_GET['classid'];
$teacher_id = $_SESSION['user']['id'];
$content = "mark_attendance_content.php";
?>

<!-- mark_attendance_content.php -->
<div>
    <h2>Mark Attendance</h2>
    <?php
    $students_query = "
        SELECT `user`.`id`, `user`.`fullname` 
        FROM `user`
        JOIN `class` ON `user`.`class` = `class`.`id`
        WHERE `class`.`id` = ? AND `class`.`teacherid` = ?
    ";
    $stmt = $conn->prepare($students_query);
    $stmt->bind_param('ii', $class_id, $teacher_id);
    $stmt->execute();
    $students = $stmt->get_result();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST['attendance'] as $student_id => $is_present) {
            $query = "
                INSERT INTO `attendance` (`classid`, `studentid`, `isPresent`) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE `isPresent` = VALUES(`isPresent`)
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iii', $class_id, $student_id, $is_present);
            $stmt->execute();
        }
        echo "<p style='color:green;'>Attendance marked successfully!</p>";
    }
    ?>

    <form method="POST">
        <table>
            <tr>
                <th>Student Name</th>
                <th>Present</th>
                <th>Absent</th>
            </tr>
            <?php while ($student = $students->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['fullname']) ?></td>
                    <td><input type="radio" name="attendance[<?= $student['id'] ?>]" value="1" required></td>
                    <td><input type="radio" name="attendance[<?= $student['id'] ?>]" value="0"></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="submit">Submit</button>
    </form>
</div>