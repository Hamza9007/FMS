<?php
include 'config.php';
$id = $_POST['id'];
$file = $_FILES['po'];
$name = basename($file['name']);
$target = "uploads/po/$name";

if (move_uploaded_file($file['tmp_name'], $target)) {
    mysqli_query($conn, "UPDATE inquiries SET po_file='$name' WHERE id=$id");
}
header("Location: adminPanel.php");
?>
