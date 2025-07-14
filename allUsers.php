
<?php include 'config.php'; ?>
<?php include 'adminHeader.php'; ?>


<div class="container add-user-form mt-5">
  <h3 class="family heading text-center">Add New User</h3>
  <form action="addUser.php" method="POST" class="mb-4 text-center modern-form">
    <input type="text" name="username" placeholder="Username" required class="form-control mb-3">
    <input type="password" name="password" placeholder="Password" required class="form-control mb-3">
    <select name="role" class="form-control mb-3">
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
    <button type="submit" class="btn-custom p-2 rounded family text-white">Add User</button>
  </form>
</div>

<div class="p-4 mx-5">
<h3 class="family subheading">Manage Users</h3>
<table class="table">
  <thead>
    <tr class="table-header family">
      <th class="table-title">Username</th>
      <th class="table-title">Password</th>
      <th class="table-title">Role</th>
      <th class="table-title">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $users = mysqli_query($conn, "SELECT * FROM users");
    while ($user = mysqli_fetch_assoc($users)) {
      echo "<tr class='family'>
        <td class='link-text'>{$user['username']}</td>
        <td>{$user['password']}</td>
        <td>{$user['role']}</td>
        <td>
          <a href='editUser.php?id={$user['id']}' class='btn-custom p-2 rounded text-white'>Edit</a>
          <a href='deleteUser.php?id={$user['id']}' onclick=\"return confirm('Delete this user?');\" class='btn-custom p-2 rounded text-white'>Delete</a>
        </td>
      </tr>";
    }
    ?>
  </tbody>
</table>
</div>

