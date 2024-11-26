<?php
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Set the content file to include in the master layout
$content = "student.php";
include 'master.php';
?>


<!-- student_content.php -->
<div>
    <h2>Your Attendance</h2>
    <table>
        <tr>
            <th>Class</th>
            <th>Total Sessions</th>
            <th>Attended</th>
            <th>Attendance (%)</th>
        </tr>
        <?php
        $query = "
            SELECT class.id AS class_id, COUNT(attendance.id) AS total_sessions,
                   SUM(attendance.isPresent) AS attended
            FROM class
            LEFT JOIN attendance ON class.id = attendance.classid
            WHERE attendance.studentid = ?
            GROUP BY class.id
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $attendance_percentage = ($row['attended'] / $row['total_sessions']) * 100;
            if ($attendance_percentage < 75) {
                $color = 'red';
            } elseif ($attendance_percentage < 83) {
                $color = 'yellow';
            } else {
                $color = 'green';
            }
            echo "<tr style='background-color:$color;'>
                    <td>{$row['class_id']}</td>
                    <td>{$row['total_sessions']}</td>
                    <td>{$row['attended']}</td>
                    <td>{$attendance_percentage}%</td>
                  </tr>";
        }
        ?>
    </table>
</div>