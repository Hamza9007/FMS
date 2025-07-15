<?php
include '../config.php';
$id = $_GET['id'];
$status = $_GET['status'];
mysqli_query($conn, "UPDATE inquiries SET status='$status' WHERE id=$id");
header("Location: ../userPanel.php");
?>
