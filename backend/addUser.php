<?php
include '../config.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Check for duplicate username
$check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
if (mysqli_num_rows($check) > 0) {
    $_SESSION['error'] = "Username already exists.";
    header("Location: ../allUsers.php");
    exit();
}

// Insert new user
mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
$_SESSION['success'] = "User added successfully.";
header("Location: ../allUsers.php");
exit();
?>
