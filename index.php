<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include 'includes/validation.php';
  $user = validateData($_POST['username']);
  $password = validateData($_POST['password']);
  include 'includes/connection.php';
  $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $stmt->bind_result($user_id, $username, $hashed_password);
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    while ($stmt->fetch()) {
      if (sha1($password) === $hashed_password) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_id;
        header('Location: job_application.php');
      }
    }
  }
}
include 'includes/header.php';
?>
<div class="container">
  <div class="col-md-12">
    <div class="jumbotron">
    <h1>Job Hunt</h1>
    <p class="lead">Organize your job hunt</p>
    </div>
  </div>
  <?php if (!$_SESSION['username']) { ?>
    <div class="col-md-4 col-md-offset-2">
      <form method="POST">
        <p class="lead">Login</p>
        <div class="form-group">
          <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <input type="submit" value="LOGIN" class="btn btn-default">
      </form>
    </div>
    <div class="col-md-4">
      <form method="POST" action="register.php">
        <p class="lead">Register</p>
        <div class="form-group">
          <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="re_password" placeholder="Repeat Password">
        </div>
        <input type="submit" value="REGISTER" class="btn btn-default">
      </form>
    </div>
 <?php } ?>
</div>
<?php
include 'includes/scripts.php';
?>
<script>
</script>
<?php
include 'includes/footer.php';
?>
