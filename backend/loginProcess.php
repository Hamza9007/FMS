<?php
session_start();
include '../config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['login_time'] = time();

    if ($row['role'] == 'admin') {
        header("Location: ../adminPanel.php");
    } else {
        header("Location: ../userPanel.php");
    }
} else {
    header("Location: ../index.php?error=Invalid credentials");
}
?>

