<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | File Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <section class="h-100 gradient-form ">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center mb-4">
                  <img src="https://interlink.com.pk/img/Logo-HD-PNG.jpg"
                    style="width: 185px;" alt="logo">
                </div>

                <?php if (isset($_GET['error'])) echo "<p class='family error'>{$_GET['error']}</p>"; ?>
                <form action="backend/loginProcess.php" method="POST" class="modern-form">

                  <div data-mdb-input-init class="form-outline family mb-4">
                    <input class="form-control mb-3" type="text" name="username" placeholder="Username" required />
                  </div>

                  <div data-mdb-input-init class="form-outline family mb-4">
                    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required />
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                  <button class="btn-custom p-2 col-4 rounded family text-white" type="submit">Login</button>
                  </div>

                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex rounded-0 align-items-center btn-custom">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4 family">We are more than just a company</h4>
                <p class="small mb-0 family">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                  exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<!-- Bootstrap JS CDN -->
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
