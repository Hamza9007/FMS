<?php
include 'config.php';
session_start();

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id = $id");
$_SESSION['success'] = "User deleted.";
header("Location: allUsers.php");
exit();
?>
