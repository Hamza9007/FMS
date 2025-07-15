<?php
session_start();
include '../config.php';

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

// Validate ID
if (isset($_POST['id'])) {
  $id = intval($_POST['id']);

  // Get file names from DB
  $res = mysqli_query($conn, "SELECT * FROM inquiries WHERE id = '$id'");
  $row = mysqli_fetch_assoc($res);

  if ($row) {
    // Delete files if they exist
    if (!empty($row['file_name']) && file_exists("../uploads/inquiries/{$row['file_name']}")) {
      unlink("../uploads/inquiries/{$row['file_name']}");
    }
    if (!empty($row['quotation_file']) && file_exists("../uploads/quotations/{$row['quotation_file']}")) {
      unlink("../uploads/quotations/{$row['quotation_file']}");
    }
    if (!empty($row['invoice_file']) && file_exists("../uploads/invoices/{$row['invoice_file']}")) {
      unlink("../uploads/invoices/{$row['invoice_file']}");
    }
    if (!empty($row['po_file']) && file_exists("../uploads/po/{$row['po_file']}")) {
      unlink("../uploads/po/{$row['po_file']}");
    }

    // Delete from DB
    mysqli_query($conn, "DELETE FROM inquiries WHERE id = '$id'");
  }

  header("Location: ../adminPanel.php?deleted=1");
  exit();
} else {
  header("Location: ../adminPanel.php?error=invalid");
  exit();
}
