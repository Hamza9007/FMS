<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: error.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark headerBg">
  <a class="navbar-brand family HeaderText" href="adminPanel.php"><?= $_SESSION['username'];?> (Admin)</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="adminNav">
  <div class="d-flex justify-content-center w-100">
  <ul class="navbar-nav flex-row family">
  <li class="nav-item">
    <a class="nav-link menuText" href="adminPanel.php">Dashboard</a>
  </li>
  <li class="nav-item">
    <a class="nav-link ml-3 menuText" href="allUsers.php">All Users</a>
  </li>
  </ul>
</div>
    <a href="logout.php" class="btn family p-2 col-1 text-white logout-btn">Logout</a>
  </div>
</nav>


<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>