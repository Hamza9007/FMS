<?php
include 'config.php';
$id = $_GET['id'];
$payment_status = $_GET['payment_status'];
mysqli_query($conn, "UPDATE inquiries SET payment_status='$payment_status' WHERE id=$id");
header("Location: adminPanel.php");
?>
