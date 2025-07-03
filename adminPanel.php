<?php
include 'config.php';
?>

<?php include 'adminHeader.php'; ?>

<!-- HTML Upload Inquiry and Assign to User -->
 <h1 class="family text-center mt-5">Assign Inquiry</h1>
<form action="assignUser.php" method="POST" enctype="multipart/form-data">
<div class="container p-4 border border-1 d-flex justify-content-center mt-3 mb-4">
  <input class="form-control family border-0 col-4" type="file" name="inquiry_file" required>
  <select class="form-control family col-3" name="assigned_to" required>
    <option class="family" value="">Select User</option>
    <?php
    $users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user'");
    while ($u = mysqli_fetch_assoc($users)) {
      echo "<option value='{$u['username']}'>{$u['username']}</option>";
    }
    ?>
  </select>
  <button class="ml-2 family btn btn-primary col-2" type="submit">Assign Inquiry</button>
</div></form>

<!-- Show All Inquiries -->
 <div class=" border border-1 p-4 m-5">
<h3 class="family mb-3">All Inquiries</h3>

<!-- Show Success and Error Messages -->
<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-success text-center">Inquiry and related files deleted successfully.</div>
<?php endif; ?>

<!-- Error Messages -->
<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger text-center">Error deleting inquiry and related files.</div>
<?php endif; ?>

<!-- File Name Already Exists Error Messages -->
<?php
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger text-center'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>
<table class="family table text-center table-striped border">
  <tr>
    <th>File</th>
    <th>Assigned To</th>
    <th>Status</th>
    <th>Quotation</th>
    <th>Client Response</th>
    <th>Client P.O</th>
    <th>Invoice</th>
    <th>Payment Status</th>
    <th>Case Status</th>
    <?php if ($_SESSION['role'] === 'admin') echo '<th>Action</th>'; ?>
</tr>
  
  <?php

  $res = mysqli_query($conn, "SELECT * FROM inquiries");
  while ($row = mysqli_fetch_assoc($res)) {
    echo "<tr class='text-center'>
      <td><a href='uploads/inquiries/{$row['file_name']}' download>{$row['file_name']}</a></td>
      <td>{$row['assigned_to']}</td>
      <td>{$row['status']}</td>
      <td>" . ($row['quotation_file'] ? "<a href='uploads/quotations/{$row['quotation_file']}' download>{$row['quotation_file']}</a>" : "Not Uploaded") . "</td>
      <td>" . ($row['client_status'] === 'pending' ? 'Pending' : $row['client_status']) . "</td>
      <td>";
      if($row['client_status'] === 'accepted'){
        if($row['po_file']){
          echo "<a href='uploads/po/{$row['po_file']}' download>{$row['po_file']}</a>";
        }else{
        echo "<form action='uploadPo.php' method='POST' enctype='multipart/form-data'>
                <input class='col-5' type='hidden' name='id' value='{$row['id']}'>
                <input class='col-5' type='file' name='po' required>
                <button type='submit' class='btn btn-primary col-2'>P.O</button>
              </form>";
        }
      }else if($row['client_status'] === 'rejected'){
        echo "File Closed";
      }else{
        echo "Pending";
      }
      echo "</td>
      <td>";
      if($row['po_file']){
        if($row['invoice_file']){
          echo "<a href='uploads/invoices/{$row['invoice_file']}' download>{$row['invoice_file']}</a>";
        }else{
        echo "<form action='uploadInvoice.php' method='POST' enctype='multipart/form-data'>
                <input class='col-5' type='hidden' name='id' value='{$row['id']}'>
                <input class='col-5' type='file' name='invoice' required>
                <button type='submit' class='btn btn-primary col-2'>Invoice</button>
              </form>";
        }
      }else{
        echo "Pending";
      }
      echo "</td>
     <td>";
if ($row['invoice_file'] && $row['payment_status'] === 'pending') {
    echo "<a href='paymentStatus.php?id={$row['id']}&payment_status=received'>Received</a> |
          <a href='paymentStatus.php?id={$row['id']}&payment_status=rejected'>Reject</a>";
  } else if($row['payment_status'] === 'received'){
    echo "Received";
  }else if($row['payment_status'] === 'rejected'){
    echo "Rejected";
  }else{
    echo "Pending";
  }
echo "</td>
<td>";
if($row['payment_status'] === 'received'){
  echo "Closed";
}else{
  echo "Pending";
}
echo "</td>
      <td>";
    if ($_SESSION['role'] === 'admin') {
      echo "
        <form action='deleteInquiry.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this inquiry and its files?');\">
          <input type='hidden' name='id' value='{$row['id']}'>
          <button class='btn btn-danger btn-sm' type='submit'>Delete</button>
        </form>";
    }
    echo "</td>
    </tr>";
    
  }
  ?>
</table>
</div>



