<?php
include 'config.php';
$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));
?>

<?php include 'adminHeader.php'; ?>
<div class="container add-user-form mt-5 mb-5">
<h2 class="family heading text-center">Edit User</h2>
<form action="backend/updateUser.php" method="POST" class="text-center modern-form">
  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <input type="text" name="username" value="<?= $user['username'] ?>" required class="form-control mb-3">
  <input type="password" name="password" value="<?= $user['password'] ?>" required class="form-control mb-3">
  <select name="role" class="form-control mb-3">
    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
  </select>
  <button type="submit" class="mt-2 btn-custom p-2 rounded family text-white">Update</button>
</form>
</div>
