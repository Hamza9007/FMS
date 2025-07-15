<?php
include '../config.php';
$id = $_POST['id'];
$file = $_FILES['po'];
$name = basename($file['name']);
$target = "E:/uploads/po/$name";

// Only allow PDF
if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'pdf' || $file['type'] !== 'application/pdf') {
    header('Location: ../adminPanel.php?error=invalid_file_type');
    exit();
}

// Check if file name already exists in the database (case-insensitive, exclude current inquiry)
$escapedName = mysqli_real_escape_string($conn, $name);
$check = mysqli_query($conn, "SELECT * FROM inquiries WHERE LOWER(po_file) = LOWER('$escapedName') AND id != $id");

if (mysqli_num_rows($check) > 0) {
    session_start();
    $_SESSION['error'] = "PO File name already exists. Please change file name.";
    header("Location: ../adminPanel.php");
    exit();
}

if (move_uploaded_file($file['tmp_name'], $target)) {
    mysqli_query($conn, "UPDATE inquiries SET po_file='$name' WHERE id=$id");
}
header("Location: ../adminPanel.php");
?>
