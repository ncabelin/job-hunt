<?php
session_start();
// REDIRECT IF NOT LOGGED IN
if (!$_SESSION['username']) {
  header('Location: index.php');
}

include 'includes/header.php';
include 'includes/connection.php';
$query = "SELECT * FROM job_sites";
$results = mysqli_query($conn, $query);
?>
<div class="container">
  <div class="col-md-12">
    <h1>Job Sites</h1>
    <?php
      if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
          $name = $row['name'];
          $link = $row['link'];
    ?>
      <a href="<?php echo $link; ?>" target="_blank" class="btn btn-default btn-lg"><?php echo $name; ?></a>
    <?php
        }
      }
    ?>
  </div>
</div>
<?php
include 'includes/scripts.php';
?>
<script>
</script>
<?php
include 'includes/footer.php';
?>
