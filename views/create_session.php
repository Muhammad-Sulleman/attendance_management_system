<?php
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}

// Handle the form submission to create a new class
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $credit_hours = $_POST['credit_hours'];
    $date = $_POST['date'] ?: date('Y-m-d'); // Use current date if not provided

    // Insert the new class into the database
    $query = "INSERT INTO `class` (teacherid, starttime, endtime, credit_hours, date) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issis', $_SESSION['user']['id'], $start_time, $end_time, $credit_hours, $date);

    if ($stmt->execute()) {
        $success_message = "Class created successfully!";
    } else {
        $error_message = "Error creating class: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create New Class</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="time"],
        input[type="number"],
        input[type="date"],
        button {
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="time"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus {
            border-color: #66afe9;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin: 10px 0;
        }

        .message p {
            font-size: 16px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Create New Class</h1>

        <!-- Show success or error messages -->
        <?php if (isset($success_message)): ?>
            <div class="message success">
                <p><?= $success_message ?></p>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error">
                <p><?= $error_message ?></p>
            </div>
        <?php endif; ?>

        <form action="create_session.php" method="POST">
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required><br>

            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required><br>

            <label for="credit_hours">Credit Hours:</label>
            <input type="number" id="credit_hours" name="credit_hours" required><br>

            <label for="date">Class Date:</label>
            <input type="date" id="date" name="date" required><br>

            <button type="submit">Create Class</button>
        </form>

        <div class="back-link">
            <a href="teacher.php">Back to Your Classes</a>
        </div>
    </div>

</body>

</html>