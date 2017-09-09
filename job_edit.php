<?php
session_start();
// REDIRECT IF NOT LOGGED IN
if (!$_SESSION['username']) {
  header('Location: index.php');
}

include('includes/validation.php');
include('includes/connection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_SESSION['user_id'];
  $id = validateData($_POST['id']);
  $title = validateData($_POST['title']);
  $company_name = validateData($_POST['company_name']);
  $company_site = validateData($_POST['company_site']);
  $content = validateData($_POST['content']);
  $cover_letter = validateData($_POST['cover_letter']);
  $job_site = validateData($_POST['job_site']);
  $applied_date = validateData($_POST['applied_date']);
  $status = validateData($_POST['status']);
  $query = "UPDATE jobs SET title = ?, company_name = ?, company_site = ?, content = ?, cover_letter = ?, job_site = ?, applied_date = ?, status = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('sssssissi', $title, $company_name, $company_site, $content, $cover_letter, $job_site, $applied_date, $status, $id);
  if ($stmt->execute()) {
    header('Location: job_application.php?success=Edit job application');
  } else {
    header('Location: job_add.php?error=Could not edit job application');
  }
} else {
  $id = validateData($_GET['id']);
  $sql = "SELECT title, company_name, company_site, content, cover_letter, job_site, applied_date, status FROM jobs WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
  if ($stmt->execute()) {
    $stmt->bind_result($title, $company_name, $company_site, $content, $cover_letter, $job_site, $applied_date, $status);
    $stmt->store_result();
  }
  $query = "SELECT * FROM job_sites";
  $results = mysqli_query($conn, $query);
}
include 'includes/header.php';

?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Job Edit</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form method="POST">
        <?php if ($stmt->num_rows > 0) {
          while ($stmt->fetch()) {
        ?>
        <div class="form-group">
          <input type="text" name="title" placeholder="Position / Title" class="form-control" value="<?php echo $title; ?>">
        </div>
        <div class="form-group">
          <input type="text" name="company_name" placeholder="Company Name" class="form-control" value="<?php echo $company_name; ?>">
        </div>
        <div class="form-group">
          <input type="text" name="company_site" placeholder="Company Site" class="form-control" value="<?php echo $company_site; ?>">
        </div>
        <div class="form-group">
          <textarea name="content" placeholder="Paste Content here" class="form-control" rows="5"><?php echo $content; ?></textarea>
        </div>
        <div class="form-group">
          <textarea name="cover_letter" placeholder="Paste Cover Letter here" class="form-control" rows="5"><?php echo $cover_letter; ?></textarea>
        </div>
        <div class="form-group">
          <select name="job_site">
            <?php
              if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_assoc($results)) {
                  $jobsite_id = $row['id'];
                  $jobsite_name = $row['name'];
                  if ($jobsite_id == $job_site) {
                    echo "<option value='$jobsite_id' selected>$jobsite_name</option>";
                  } else {
                    echo "<option value='$jobsite_id'>$jobsite_name</option>";
                  }
                }
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Date Applied</label>
          <input type="date" name="applied_date" placeholder="Date (mm/dd/yy)" class="form-control" value="<?php echo $applied_date; ?>">
        </div>
        <div class="form-group">
          <select name="status">
            <?php
              $options = array(
                'N' => 'No Response',
                'C' => 'Called Back',
                'I' => 'Interviewed',
                'A' => 'Accepted',
                'R' => 'Rejected'
              );

              foreach($options as $key => $value) {
                if ($key == $status) {
                  echo "<option value='$key' selected>$value</option>";
                } else {
                  echo "<option value='$key'>$value</option>";
                }
              }
            ?>
          </select>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="Submit" class="btn btn-default">
        <?php
          }
        }
        ?>
      </form>
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
