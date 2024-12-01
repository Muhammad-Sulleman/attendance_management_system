<?php
// Start the session first to avoid "session already started" error
session_start();

// Include the necessary files
include '../includes/db_connection.php';
include 'partials/header.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Get the logged-in student's user ID
$user_id = $_SESSION['user']['id'];

// Set the content file to include in the master layout
$content = "student.php";  // This could be the content that is loaded in the master layout
 // Make sure the master.php path is correct and it includes $content
?>

<!-- student_content.php -->
<div>
    <h2>Your Attendance</h2>
    <table border="1">
        <tr>
            <th>Class</th>
            <th>Total Sessions</th>
            <th>Attended</th>
            <th>Attendance (%)</th>
        </tr>
        <?php
        // Database query to fetch attendance data for the student
        $query = "
            SELECT class.id AS class_id, 
                   COUNT(attendance.classid) AS total_sessions,
                   SUM(attendance.isPresent) AS attended
            FROM class
            LEFT JOIN attendance ON class.id = attendance.classid
            WHERE attendance.studentid = ?
            GROUP BY class.id
        ";

        // Prepare and execute the query
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id); // Bind the student ID from the session
        $stmt->execute();
        $result = $stmt->get_result();

        // Loop through the results and display the attendance
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $attendance_percentage = ($row['attended'] / $row['total_sessions']) * 100;

                // Determine the background color based on attendance percentage
                if ($attendance_percentage < 75) {
                    $color = 'red';
                } elseif ($attendance_percentage < 85) {
                    $color = 'yellow';
                } else {
                    $color = 'green';
                }

                // Display the class attendance in a table row
                echo "<tr style='background-color:$color;'>
                        <td>{$row['class_id']}</td>
                        <td>{$row['total_sessions']}</td>
                        <td>{$row['attended']}</td>
                        <td>" . number_format($attendance_percentage, 2) . "%</td>
                      </tr>";
            }
        } else {
            // Display message if no attendance data is found
            echo "<tr><td colspan='4'>No attendance records found for this student.</td></tr>";
        }
        ?>
    </table>
</div>