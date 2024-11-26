<?php
include '../includes/db.php';
include '../includes/auth.php';

if (!isAuthenticated() || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../login.php');
    exit;
}

$teacher_id = $_SESSION['user']['id'];
$class_id = $_GET['classid'] ?? null;
$error = '';
$success = '';

// Fetch enrolled students for the class
if ($class_id) {
    $students_query = "
        SELECT `user`.`id`, `user`.`fullname` 
        FROM `user`
        JOIN `class` ON `user`.`class` = `class`.`id`
        WHERE `class`.`id` = ? AND `class`.`teacherid` = ?
    ";
    $stmt = $conn->prepare($students_query);
    $stmt->bind_param('ii', $class_id, $teacher_id);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $error = "Invalid class ID.";
}

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['attendance'] as $student_id => $is_present) {
        $comment = $_POST['comments'][$student_id] ?? null;
        $is_present = $is_present ? 1 : 0;

        // Insert or update attendance
        $query = "
            INSERT INTO `attendance` (`classid`, `studentid`, `isPresent`, `comments`) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE `isPresent` = VALUES(`isPresent`), `comments` = VALUES(`comments`)
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiis', $class_id, $student_id, $is_present, $comment);
        $stmt->execute();
    }
    $success = "Attendance marked successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mark Attendance</title>
</head>

<body>
    <h1>Mark Attendance for Class ID: <?= $class_id ?></h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php else: ?>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['fullname']) ?></td>
                            <td>
                                <input type="radio" name="attendance[<?= $student['id'] ?>]" value="1" required>
                            </td>
                            <td>
                                <input type="radio" name="attendance[<?= $student['id'] ?>]" value="0">
                            </td>
                            <td>
                                <input type="text" name="comments[<?= $student['id'] ?>]" placeholder="Comments">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Submit Attendance</button>
        </form>
        <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
    <?php endif; ?>
</body>

</html>