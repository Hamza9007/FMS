<?php
include 'config.php';
$id = $_GET['id'];
$client_status = $_GET['client_status'];
mysqli_query($conn, "UPDATE inquiries SET client_status='$client_status' WHERE id=$id");
header("Location: userPanel.php");
?>