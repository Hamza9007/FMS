<?php
include 'config.php';
$id = $_POST['id'];
$file = $_FILES['invoice'];
$name = basename($file['name']);
$target = "uploads/invoices/$name";

if (move_uploaded_file($file['tmp_name'], $target)) {
    mysqli_query($conn, "UPDATE inquiries SET invoice_file='$name' WHERE id=$id");
}
header("Location: adminPanel.php");
?>