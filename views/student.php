<?php
$content = "student_content.php";
include 'master.php';
?>

<!-- student_content.php -->
<div>
    <h2>Your Attendance</h2>
    <table>
        <tr>
            <th>Class</th>
            <th>Status</th>
        </tr>
        <?php
        include '../includes/db_connection.php';
        $query = "SELECT * FROM `attendance` WHERE `studentid` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $status = $row['isPresent'] ? 'Present' : 'Absent';
            $color = $status == 'Absent' ? 'red' : 'green';
            echo "<tr style='color:$color;'>
                    <td>{$row['classid']}</td>
                    <td>$status</td>
                  </tr>";
        }
        ?>
    </table>
</div>