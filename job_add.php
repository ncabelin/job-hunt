<?php
session_start();
// REDIRECT IF NOT LOGGED IN
if (!$_SESSION['username']) {
  header('Location: index.php');
}

include('includes/validation.php');
include('includes/connection.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = validateData($_SESSION['user_id']);
  $title = validateData($_POST['title']);
  $company_name = validateData($_POST['company_name']);
  $company_site = validateData($_POST['company_site']);
  $content = validateData($_POST['content']);
  $cover_letter = validateData($_POST['cover_letter']);
  $job_site = validateData($_POST['job_site']);
  $applied_date = validateData($_POST['applied_date']);
  $status = validateData($_POST['status']);
  $query = "INSERT INTO jobs(user_id, title, company_name, company_site, content, cover_letter, job_site, applied_date, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('isssssiss', $user_id, $title, $company_name, $company_site, $content, $cover_letter, $job_site, $applied_date, $status);
  if ($stmt->execute()) {
    header('Location: job_application.php?success=Added job application');
  } else {
    header('Location: job_add.php?error=Could not save application');
  }
} else {
  $query = "SELECT * FROM job_sites";
  $results = mysqli_query($conn, $query);
}
include 'includes/header.php';

?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="text-center">Job Add</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form method="POST">
        <div class="form-group">
          <input type="text" name="title" placeholder="Position / Title" class="form-control">
        </div>
        <div class="form-group">
          <input type="text" name="company_name" placeholder="Company Name" class="form-control">
        </div>
        <div class="form-group">
          <input type="text" name="company_site" placeholder="Company Site" class="form-control">
        </div>
        <div class="form-group">
          <textarea name="content" placeholder="Paste Content here" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
          <textarea name="cover_letter" placeholder="Paste Cover Letter here" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
          <select name="job_site">
            <?php
              if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_assoc($results)) {
                  $id = $row['id'];
                  $name = $row['name'];
            ?>
                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php
                }
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Date Applied</label>
          <input type="date" name="applied_date" placeholder="Date (mm/dd/yy)" class="form-control">
        </div>
        <div class="form-group">
          <select name="status">
            <option value="N">No Response (default)</option>
            <option value="C">Called Back</option>
            <option value="I">Interviewed</option>
            <option value="A">Accepted</option>
            <option value="R">Rejected</option>
          </select>
        </div>
        <input type="submit" value="Submit" class="btn btn-default">
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
