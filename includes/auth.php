<?php
session_start();

function isAuthenticated()
{
    return isset($_SESSION['user']);
}

function loginUser($email, $password)
{
    global $conn;

    $query = "SELECT * FROM `user` WHERE `email` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
    }
    return false;
}
