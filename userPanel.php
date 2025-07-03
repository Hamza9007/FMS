<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: index.php");
  exit();
}
include 'config.php';
$user = $_SESSION['username'];
$res = mysqli_query($conn, "SELECT * FROM inquiries WHERE assigned_to='$user'");
?>

<?php include 'userHeader.php'; ?>

<div class="border border-1 p-4 m-5">
<h1 class="family text-center">My Inquiries</h1>
<table class="family table table-striped border border-1">
  <tr class="text-center">
    <th>File</th>
    <th>Actions</th>
    <th>Quotation</th>
    <th>Client Response</th>
    <th>Case Status</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($res)) {
    echo "<tr class='text-center'>
      <td><a href='uploads/inquiries/{$row['file_name']}' download>{$row['file_name']}</a></td>
      <td>";
    if ($row['status'] === 'pending') {
      echo "<a href='updateStatus.php?id={$row['id']}&status=accepted'>Accept</a> |
            <a href='updateStatus.php?id={$row['id']}&status=rejected'>Reject</a>";
    } else if ($row['status'] === 'accepted') {
      // after upload quotation only show file upladed successfully text
      if($row['quotation_file']){
        echo "Uploaded File";
      }else{
      echo "<form action='uploadQuotation.php' method='POST' enctype='multipart/form-data'>
              <input class='col-3' type='hidden' name='id' value='{$row['id']}'>
              <input class='col-3' type='file' name='quotation' required>
              <button type='submit' class='btn btn-primary col-3'>Quotation</button>
            </form>";
      }
    } else if ($row['status'] === 'rejected') {
      echo "Rejected";
    }
    echo "</td>
    <td>" .($row['quotation_file'] ? "<a href='uploads/quotations/{$row['quotation_file']}' download>{$row['quotation_file']}</a>" : "Not Uploaded") . "</td>
    <td>";
    if ($row['quotation_file']) {
      if($row['client_status'] === 'pending'){
        echo "<a href='clientStatus.php?id={$row['id']}&client_status=accepted'>Accept</a> |
              <a href='clientStatus.php?id={$row['id']}&client_status=rejected'>Reject</a>";
      }else if($row['client_status'] === 'accepted'){
        echo "Accepted";
      }else{
        echo "Rejected";
      }
    }
    echo"</td>
    <td>";
if($row['payment_status'] === 'received'){
  echo "Closed";
}else{
  echo "Pending";
}
echo "</td>
    </tr>";
  }
  ?>
</table>
</div>



