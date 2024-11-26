<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $class = $_POST['class'] ?? NULL;

    $query = "INSERT INTO `user` (`fullname`, `email`, `password`, `class`, `role`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $fullname, $email, $password, $class, $role);

    if ($stmt->execute()) {
        header('Location: login.php');
    } else {
        echo "Registration failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h2>Register</h2>
    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="fullname" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Role:</label>
        <select name="role">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>
        <label>Class (if student):</label>
        <input type="text" name="class">
        <button type="submit">Register</button>
    </form>
</body>

</html>