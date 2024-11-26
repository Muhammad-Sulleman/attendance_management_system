<?php
session_start();
include 'includes/db_connection.php';
include 'views/partials/header.php';

// Handle Registration
if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = $_POST['role'];
    $class = $_POST['class'] ?? ''; // Class is required only for students.

    // Check if email already exists
    $checkQuery = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Email is already registered!";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO `user` (`fullname`, `email`, `password`, `class`, `role`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sssss', $fullname, $email, $password, $class, $role);
        if ($stmt->execute()) {
            $register_success = "Registration successful! Please log in.";
        } else {
            $register_error = "Registration failed. Please try again.";
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) { // Verify password
            $_SESSION['user'] = $user;

            // Redirect based on role
            if ($user['role'] == 'teacher') {
                header("Location: views/teacher.php");
            } elseif ($user['role'] == 'student') {
                header("Location: views/student.php");
            }
            exit;
        } else {
            $login_error = "Incorrect password!";
        }
    } else {
        $login_error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>

    <head>
        <title>Login and Register</title>
        <link rel="stylesheet" href="css/styles.css">
        <script defer src="js/script.js"></script>
    </head>

</head>

<body>
    <div class="container">
        <!-- Navigation Buttons -->

        <div class="tabs">
            <button id="showLogin">Login</button>
            <button id="showRegister">Register</button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="form-container active">
            <h2>Login</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button ctype="submit" name="login" class="login-submit-btn">Login</button>
                <?php if (isset($login_error)) echo "<p style='color:red;'>$login_error</p>"; ?>
            </form>
        </div>

        <!-- Registration Form -->
        <div id="registerForm" class="form-container">
            <h2>Register</h2>
            <form method="POST" action="">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
                <input type="text" name="class" placeholder="Class (Required for Students)">
                <button type="submit" name="register" class="login-submit-btn">Register</button>
                <?php if (isset($register_error)) echo "<p style='color:red;'>$register_error</p>"; ?>
                <?php if (isset($register_success)) echo "<p style='color:green;'>$register_success</p>"; ?>
            </form>
        </div>
    </div>
   <?php include 'views/partials/footer.php';?>