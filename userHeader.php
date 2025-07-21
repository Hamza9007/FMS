<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title class="family"><?= $_SESSION['username'];?></title>
    <link rel="stylesheet" href="css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header class="app-header">
  <div class="header-content">
    <div class="app-logo">
      <span class="logo-text">FMS</span>
      <span class="divider">/</span>
      <span class="page-title">Dashboard</span>
    </div>
    
    <div class="user-actions">
      <div class="user-profile" tabindex="0">
        <span class="welcome-text family">Welcome, <?= htmlspecialchars(explode(' ', $_SESSION['username'])[0]) ?></span>
        <div class="user-avatar">
          <?= strtoupper(substr(htmlspecialchars($_SESSION['username']), 0, 1)) ?>
        </div>
        <div class="dropdown-menu">
          <a href="backend/logout.php" class="dropdown-item family" onclick="event.stopPropagation()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V3.33333C2 2.97971 2.14048 2.64057 2.39052 2.39052C2.64057 2.14048 2.97971 2 3.33333 2H6M10.6667 11.3333L14 8M14 8L10.6667 4.66667M14 8H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Sign Out
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>