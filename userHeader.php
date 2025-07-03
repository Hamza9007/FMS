<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <a class="navbar-brand family text-white" href="#">User Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#userNav" aria-controls="userNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="userNav">
    <ul class="navbar-nav ml-auto">
    </ul>
    <span class="navbar-text mr-3 family text-white">Welcome, <?= $_SESSION['username']; ?></span>
    <a href="logout.php" class="btn btn-outline-light family btn-sm">Logout</a>
  </div>
</nav>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>