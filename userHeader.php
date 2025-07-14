<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="family">User Panel</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar p-4">
  <a class="navbar-brand family nav-user-name" href="#">
    <?= $_SESSION['username']; ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#userNav" aria-controls="userNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="userNav">
    <a href="logout.php" class="btn family col-1 text-white logout-btn">Logout</a>
  </div>
</nav>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>