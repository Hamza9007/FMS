<?php include 'config.php'; ?>
<?php include 'adminHeader.php'; ?>

<!-- Chat Widget Start -->
<div id="chatWidget" class="family" style="position:fixed;bottom:20px;right:20px;width:380px;max-width:95vw;z-index:1000;background:#fff;border:1px solid #ccc;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
  <div style="background:#2957A4;color:#fff;padding:10px 16px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;">
    <b>Chat with Users</b>
    <button id="minimizeChatBtn" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;">&#8211;</button>
  </div>
  <div style="padding:8px 10px 0 10px;">
    <select id="chatUserSelect" style="width:100%;padding:6px 8px;border-radius:6px;border:1px solid #ccc;">
      <option value="">Select User</option>
      <?php 
      $users = mysqli_query($conn, "SELECT username FROM users WHERE role='user'");
      while ($u = mysqli_fetch_assoc($users)) {
        echo "<option value='{$u['username']}' id='userOpt_{$u['username']}'>{$u['username']}</option>";
      }
      ?>
    </select>
  </div>
  <div id="chatMessages" style="height:300px;overflow-y:auto;padding:10px;background:#f9f9f9;"></div>
  <div style="display:flex;border-top:1px solid #eee;">
    <input type="text" id="chatInput" placeholder="Type a message..." style="flex:1;padding:8px;border:none;border-radius:0 0 0 8px;outline:none;">
    <button id="sendChatBtn" style="background:#2957A4;color:#fff;border:none;padding:8px 16px;border-radius:0 0 8px 0;">Send</button>
  </div>
</div>
<!-- Minimized Chat Icon -->
<div id="chatWidgetMin" style="display:none;position:fixed;bottom:20px;right:20px;z-index:1001;">
  <div style="position:relative;">
    <button id="openChatBtn" style="background:#2957A4;color:#fff;border:none;border-radius:50%;width:56px;height:56px;font-size:28px;box-shadow:0 2px 8px rgba(0,0,0,0.2);cursor:pointer;">
      <span id="chatNotifBadge" style="display:none;position:absolute;top:2px;right:2px;background:#ee2724;color:#fff;border-radius:50%;width:28px;height:28px;font-size:18px;line-height:28px;text-align:center;"></span>
    <img src="svg-icons/chat.svg" class="pb-1" alt="Chat" style="width:34px;height:34px;">
    </button>
  </div>
</div>
<!-- Chat Widget End -->
<script>
window.sender = "admin";
</script>
<script src="js/script.js"></script>
<script src="js/chat-widget.js"></script>

<!-- HTML Upload Inquiry and Assign to User -->

 <h1 class="family heading display-6 text-center mt-5">Assign Inquiry</h1>
<form action="backend/assignUser.php" method="POST" enctype="multipart/form-data">
<div class="container assignInquiryForm mt-3 mb-3">
  <input class="assignInquiryInput family border-0 col-4" type="file" name="inquiry_file" required>
  <select class="family  col-4 assignInquiryDropdown" name="assigned_to" required>
    <option class="family" value="">Select User</option>
    <?php
    $users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user'");
    while ($u = mysqli_fetch_assoc($users)) {
      echo "<option value='{$u['username']}'>{$u['username']}</option>";
    }
    ?>
  </select>
  <button class="btn-custom p-3 text-white family" type="submit">Assign Inquiry</button>
</div></form>
<!-- Show All Inquiries -->
 <div class="p-4 mx-5">
<h3 class="family subheading">All Inquiries</h3>

<!-- Show Success and Error Messages -->
<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-primary family text-center">Inquiry and related files deleted successfully.</div>
<?php endif; ?>

<!-- Error Messages -->
<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger family text-center">Error deleting inquiry and related files.</div>
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
    echo "<tr class='table-row-admin'>
      <td><a class='link-text' href='uploads/inquiries/{$row['file_name']}' download>{$row['file_name']}</a></td>
      <td>{$row['assigned_to']}</td>
      <td>";
      if($row['status'] === 'Pending'){
        echo "<b class='orange'>Pending</b>";
      }else if($row['status'] === 'Accepted'){
        echo "<b class='green'>Accepted</b>";
      }else{
        echo "<b class='red'>Rejected</b>";
      }
      echo "</td>
      <td>" . ($row['quotation_file'] ? "<a class='link-text' href='uploads/quotations/{$row['quotation_file']}' download>{$row['quotation_file']}</a>" : "Not Uploaded") . "</td>
      <td>";
      if($row['status'] === 'Rejected'){
        echo "";
      }else if($row['client_status'] === 'Accepted'){
        echo "<b class='green'>Accepted</b>";
      }else if($row['client_status'] === 'Rejected'){
        echo "<b class='red'>Rejected</b>";
      }else{
        echo "<b class='orange'>Pending</b>";
      }
      echo "</td>
      <td>";
      if($row['client_status'] === 'Accepted'){
        if($row['po_file']){
          echo "<a class='link-text' href='uploads/po/{$row['po_file']}' download>{$row['po_file']}</a>";
        }else{
          echo "<form action='backend/uploadPo.php' method='POST' enctype='multipart/form-data' class='d-flex align-items-center '>
          <input class='col-5 poInput' type='hidden' name='id' value='" . $row["id"] . "'>
        
          <label class='custom-po-label mt-2 text-center' id='chooseLabel_" . $row["id"] . "'>
            Choose File
            <input type='file' name='po' required hidden onchange='handlePOFileSelect(this, " . $row["id"] . ")'>
          </label>
        
          <span class='po-file-name mr-2' id='fileNameDisplay_" . $row["id"] . "' style='display: none;'>No file chosen</span>
        
          <button type='submit' class='poBtn'>P.O</button>
        </form>";
        }
      }else if($row['client_status'] === 'Rejected'){
        echo "";
      }else if($row['status'] === 'Rejected'){
        echo "";
      }else{
        echo "<b class='orange'>Pending</b>";
      }
      echo "</td>
      <td>";
      if($row['po_file']){
        if($row['invoice_file']){
          echo "<a class='link-text' href='uploads/invoices/{$row['invoice_file']}' download>{$row['invoice_file']}</a>";
        }else{
          echo "<form action='backend/uploadInvoice.php' method='POST' enctype='multipart/form-data' class='d-flex align-items-center gap-3 flex-wrap'>
          <input class='col-5' type='hidden' name='id' value='" . $row['id'] . "'>
        
          <label class='custom-po-label mt-2' id='chooseInvoiceLabel_" . $row["id"] . "'>
            Choose File
            <input type='file' name='invoice' required hidden onchange='handleInvoiceSelect(this, " . $row["id"] . ")'>
          </label>
        
          <span class='po-file-name' id='invoiceNameDisplay_" . $row["id"] . "' style='display: none;'>No file chosen</span>
        
          <button type='submit' class='poBtn'>Invoice</button>
        </form>";
        
        }
      }else if($row['status'] === 'Rejected'){
        echo "";
      }else if($row['client_status'] === 'Rejected'){
        echo "";
      }else{
        echo "<b class='orange'>Pending</b>";
      }
      echo "</td>
     <td>";
if ($row['invoice_file'] && $row['payment_status'] === 'Pending') {
    echo "<a class='link-text' href='backend/paymentStatus.php?id={$row['id']}&payment_status=Received'>Received</a> |
          <a class='link-text' href='#'>Not Received</a>";
  } else if($row['payment_status'] === 'Received'){
    echo "<b class='green'>Received</b>";
  }else if($row['payment_status'] === 'Rejected'){
    echo "<b class='red'>Not Received</b>";
  }else if($row['status'] === 'Rejected'){
    echo "";
  }else if($row['client_status'] === 'Rejected'){
    echo "";
  }else{
    echo "<b class='orange'>Pending</b>";
  }
echo "</td>
<td>";
if($row['payment_status'] === 'Received'){
  echo "<b class='green'>Closed</b>";
}else if($row['payment_status'] === 'Rejected'){
  echo "Rejected";
}else if($row['status'] === 'Rejected'){
  echo "";
}else if($row['client_status'] === 'Rejected'){
  echo "";
}else{
  echo "<b class='orange'>Pending</b>";
}
echo "</td>
      <td>";
    if ($_SESSION['role'] === 'admin') {
      echo "
        <form action='backend/deleteInquiry.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this inquiry and its files?');\">
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



