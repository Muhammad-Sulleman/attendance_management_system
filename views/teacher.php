<?php
$content = "teacher_content.php";
include 'master.php';
?>

<!-- teacher_content.php -->
<div>
    <h2>Attendance Sessions</h2>
    <ul>
        <?php
        include '../includes/db_connection.php';
        $query = "SELECT * FROM `class` WHERE `teacherid` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<li>Class ID: {$row['id']} | Start: {$row['starttime']} | End: {$row['endtime']}</li>";
        }
        ?>
    </ul>
</div>