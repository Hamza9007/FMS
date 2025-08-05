<?php
include '../config.php';
session_start();

$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

mysqli_query($conn, "UPDATE users SET password='$password', role='$role' WHERE id=$id");
$_SESSION['success'] = "User updated.";
header("Location: ../allUsers.php");
exit();
?>
