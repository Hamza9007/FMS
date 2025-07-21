<?php
session_start();
// TEMP: Show session ID for debugging
// echo '<!-- Session ID: ' . session_id() . ' -->';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: index.php");
  exit();
}
include 'config.php';
$user = $_SESSION['username'];
// Pagination setup
$perPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Build search condition
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$where = "WHERE assigned_to='$user'";

if (!empty($search)) {
    $where .= " AND file_name LIKE '%$search%'";
}

// Get total count for pagination with search
$countQuery = "SELECT COUNT(*) as total FROM inquiries $where";
$countRes = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countRes);
$totalRows = $countRow['total'];
$totalPages = ceil($totalRows / $perPage);

// Get current page data with search
$query = "SELECT * FROM inquiries $where ORDER BY id DESC LIMIT $start, $perPage";
$res = mysqli_query($conn, $query);
?>

<?php include 'userHeader.php'; ?>

<div class="p-4 mx-5">
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="family subheading mb-0">My Inquiries</h1>
  <!-- Search Form -->
  <div class="d-flex">
    <form method="get" class="d-flex align-items-center gap-2">
      <div class="position-relative">
        <input type="text" 
               name="search" 
               class="form-control family" 
               placeholder="Search Inquiries" 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               >
      </div>
      <button type="submit" class="btn-custom px-3 ml-2 py-1 text-white family rounded">
        Search  
      </button>
      <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
        <a href="userPanel.php" class="logout-btn ml-2 text-white family rounded">
          Clear
        </a>
      <?php endif; ?>
  </form>
  </div>
</div>

<!-- error display on user panel -->
<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger family text-center"><?php echo $_SESSION['error']; ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>
<table class="family table text-center">
  <tr class="table-header">
    <th class="table-title">File</th>
    <th class="table-title">Actions</th>
    <th class="table-title">Quotation</th>
    <th class="table-title">Client Response</th>
    <th class="table-title">Case Status</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($res)) {
    echo "<tr class='text-center'>
      <td><a class='link-text' href='download.php?type=inquiries&file={$row['file_name']}'>{$row['file_name']}</a></td>
      <td>";
    if ($row['status'] === 'Pending') {
      echo "<a class='link-text' href='backend/updateStatus.php?id={$row['id']}&status=accepted'>Accept</a> |
            <a class='link-text' href='backend/updateStatus.php?id={$row['id']}&status=rejected'>Reject</a>";
    } else if ($row['status'] === 'Accepted') {
      // after upload quotation only show file upladed successfully text
      if($row['quotation_file']){
        echo "Uploaded File";
      }else{
        echo "<form action='backend/uploadQuotation.php' method='POST' enctype='multipart/form-data' class='d-flex align-items-center gap-3 flex-wrap'>
        <input class='poInput' type='hidden' name='id' value='{$row['id']}'>
      
        <label class='custom-po-label mt-2' id='chooseQuotationLabel_{$row['id']}'>
          Choose File
          <input type='file' name='quotation' accept='.pdf' required hidden onchange='handleQuotationSelect(this, {$row['id']})'>
        </label>
      
        <span class='po-file-name' id='quotationFileName_{$row['id']}' style='display: none;'>No file chosen</span>
      
        <button type='submit' class='poBtn'>Quotation</button>
      </form>";
      }
    } else if ($row['status'] === 'Rejected') {
      echo "Rejected";
    }
    echo "</td>
    <td>" .($row['quotation_file'] ? "<a class='link-text' href='download.php?type=quotations&file={$row['quotation_file']}'>{$row['quotation_file']}</a>" : "Not Uploaded") . "</td>
    <td>";
    if ($row['quotation_file']) {
      if($row['client_status'] === 'Pending'){
        echo "<a class='link-text' href='backend/clientStatus.php?id={$row['id']}&client_status=Accepted'>Accept</a> |
              <a class='link-text' href='backend/clientStatus.php?id={$row['id']}&client_status=Rejected'>Reject</a>";
      }else if($row['client_status'] === 'Accepted'){
        echo "Accepted";
      }else{
        echo "Rejected";
      }
    }
    echo"</td>
    <td>";
if($row['payment_status'] === 'Received'){
  echo "Closed";
}else if($row['payment_status'] === 'Rejected'){
  echo "Closed";
}else{
  echo "Pending";
}
echo "</td>
    </tr>";
  }
  ?>
</table>

<!-- Pagination Controls -->
<?php if ($totalPages > 1): ?>

<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link family" href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Previous</a></li>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
        <a class="page-link family" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <li class="page-item"><a class="page-link family" href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Next</a></li>
    <?php endif; ?>
  </ul>
</nav>
<?php endif; ?>
</div>

<!-- Chat Widget Start -->
<div id="chatWidget" style="position:fixed;bottom:20px;right:20px;width:340px;max-width:90vw;z-index:1000;background:#fff;border:1px solid #ccc;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
  <div style="background:#2957A4;color:#fff;padding:10px 16px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;">
    <b>Chat with Admin</b>
    <button id="minimizeChatBtn" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;">&#8211;</button>
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
window.sender = "<?php echo $_SESSION['username']; ?>";
window.receiver = "admin";
</script>
<script src="js/script.js"></script>
<script src="js/chat-widget.js"></script>
  
