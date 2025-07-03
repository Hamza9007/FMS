<?php
include 'config.php';
session_start();

$file = $_FILES['inquiry_file'];
$name = basename($file['name']);
$assigned_to = $_POST['assigned_to'];
$target = "uploads/inquiries/$name";

// Check if file name already exists in the database
$check = mysqli_query($conn, "SELECT * FROM inquiries WHERE file_name = '$name'");

if (mysqli_num_rows($check) > 0) {
    // File name already in use
    $_SESSION['error'] = "Inquiry File name already exists. Please rename your file.";
    header("Location: adminPanel.php");
    exit();
}

if (move_uploaded_file($file['tmp_name'], $target)) {
    mysqli_query($conn, "INSERT INTO inquiries (file_name, assigned_to) VALUES ('$name', '$assigned_to')");
    header("Location: adminPanel.php");
    exit();
} else {
    $_SESSION['error'] = "Inquiry File upload failed.";
    header("Location: adminPanel.php");
    exit();
}
?>
