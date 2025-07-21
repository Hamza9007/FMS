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
    <title><?= $_SESSION['username'];?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg headerBg">
    <div class="container-fluid">
        <a class="navbar-brand" href="adminPanel.php">
            <i class="fas fa-tachometer-alt me-auto"></i>FMS Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link family" href="adminPanel.php"><i class="fas fa-home me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link family" href="allUsers.php"><i class="fas fa-users me-1"></i> All Users</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="backend/logout.php" class="btn btn-sm family logout-btn">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>


<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>