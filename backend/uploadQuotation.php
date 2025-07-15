<?php
include '../config.php';
$id = $_POST['id'];
$file = $_FILES['quotation'];
$name = basename($file['name']);
$target = "../uploads/quotations/$name";

if (move_uploaded_file($file['tmp_name'], $target)) {
    mysqli_query($conn, "UPDATE inquiries SET quotation_file='$name' WHERE id=$id");
}
header("Location: ../userPanel.php");
?>
