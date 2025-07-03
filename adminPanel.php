<?php
include 'config.php';
?>

<?php include 'adminHeader.php'; ?>

<!-- HTML Upload Inquiry and Assign to User -->

 <h1 class="family heading display-6 text-center mt-5">Assign Inquiry</h1>
<form action="assignUser.php" method="POST" enctype="multipart/form-data">
<div class="container assignInquiryForm mt-3 mb-3">
  <input class="form-control assignInquiryInput family border-0 col-4" type="file" name="inquiry_file" required>
  <select class="form-control family col-3 assignInquiryDropdown" name="assigned_to" required>
    <option class="family" value="">Select User</option>
    <?php
    $users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user'");
    while ($u = mysqli_fetch_assoc($users)) {
      echo "<option value='{$u['username']}'>{$u['username']}</option>";
    }
    ?>
  </select>
  <button class="gradient-custom-2 family text-white p-2 rounded col-2" type="submit">Assign Inquiry</button>
</div></form>
<!-- Show All Inquiries -->
 <div class="all-inquiries-section">
<h3 class="family subheading">All Inquiries</h3>

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
<table class="family table text-center table-striped table-border">
  <tr class="table-header">
    <th class="table-title">File</th>
    <th class="table-title">Assigned To</th>
    <th class="table-title">Status</th>
    <th class="table-title">Quotation</th>
    <th class="table-title">Client Response</th>
    <th class="table-title">Client P.O</th>
    <th class="table-title">Invoice</th>
    <th class="table-title">Payment Status</th>
    <th class="table-title">Case Status</th>
    <?php if ($_SESSION['role'] === 'admin') echo '<th class="table-title">Action</th>'; ?>
</tr>
  
  <?php

  $res = mysqli_query($conn, "SELECT * FROM inquiries");
  while ($row = mysqli_fetch_assoc($res)) {
    echo "<tr>
      <td><a class='link-text' href='uploads/inquiries/{$row['file_name']}' download>{$row['file_name']}</a></td>
      <td>{$row['assigned_to']}</td>
      <td>{$row['status']}</td>
      <td>" . ($row['quotation_file'] ? "<a class='link-text' href='uploads/quotations/{$row['quotation_file']}' download>{$row['quotation_file']}</a>" : "Not Uploaded") . "</td>
      <td>" . ($row['client_status'] === 'Pending' ? 'Pending' : $row['client_status']) . "</td>
      <td>";
      if($row['client_status'] === 'Accepted'){
        if($row['po_file']){
          echo "<a class='link-text' href='uploads/po/{$row['po_file']}' download>{$row['po_file']}</a>";
        }else{
        echo "<form action='uploadPo.php' method='POST' enctype='multipart/form-data'>
                <input class='col-5' type='hidden' name='id' value='{$row['id']}'>
                <input class='col-5' type='file' name='po' required>
                <button type='submit' class='btn btn-primary col-2'>P.O</button>
              </form>";
        }
      }else if($row['client_status'] === 'Rejected'){
        echo "File Closed";
      }else{
        echo "Pending";
      }
      echo "</td>
      <td>";
      if($row['po_file']){
        if($row['invoice_file']){
          echo "<a class='link-text' href='uploads/invoices/{$row['invoice_file']}' download>{$row['invoice_file']}</a>";
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
if ($row['invoice_file'] && $row['payment_status'] === 'Pending') {
    echo "<a href='paymentStatus.php?id={$row['id']}&payment_status=Received'>Received</a> |
          <a href='paymentStatus.php?id={$row['id']}&payment_status=Rejected'>Reject</a>";
  } else if($row['payment_status'] === 'Received'){
    echo "Received";
  }else if($row['payment_status'] === 'Rejected'){
    echo "Rejected";
  }else{
    echo "Pending";
  }
echo "</td>
<td>";
if($row['payment_status'] === 'Received'){
  echo "Closed";
}else if($row['payment_status'] === 'Rejected'){
  echo "Rejected";
}
else{
  echo "Pending";
}
echo "</td>
      <td>";
    if ($_SESSION['role'] === 'admin') {
      echo "
        <form action='deleteInquiry.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this inquiry and its files?');\">
          <input type='hidden' name='id' value='{$row['id']}'>
          <button class='btn-custom p-2 rounded text-white' type='submit'>Delete</button>
        </form>";
    }
    echo "</td>
    </tr>";
    
  }
  ?>
</table>
</div>



