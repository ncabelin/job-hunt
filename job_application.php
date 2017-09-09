<?php
session_start();
// REDIRECT IF NOT LOGGED IN
if (!$_SESSION['username']) {
  header('Location: index.php');
}
include 'includes/header.php';
include 'includes/connection.php';
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM jobs WHERE user_id = $user_id ORDER BY created_date";
$result = mysqli_query($conn, $query);
$query_sites = "SELECT * FROM job_sites";
$result_sites = mysqli_query($conn, $query_sites);
$sites = array();
$sites_link = array();
if (mysqli_num_rows($result_sites) > 0) {
  while ($row = mysqli_fetch_assoc($result_sites)) {
    $id = $row['id'];
    $name = $row['name'];
    $link = $row['link'];
    $sites[$id] = $name;
    $sites_link[$id] = $link;
  }
}
$status_text = array(
  'N' => 'No Response',
  'C' => 'Called Back',
  'I' => 'Interviewed',
  'A' => 'Accepted',
  'R' => 'Rejected'
)
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1>Job Applications</h1><a class="btn btn-default" href="job_add.php">Add</a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>Actions</th>
            <th>Title</th>
            <th>Company</th>
            <th>Content</th>
            <th>Cover letter</th>
            <th>Job Site</th>
            <th>Applied</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) {
          ?>
            <tr>
              <td><a class="btn btn-default btn-xs" href="job_edit.php?id=<?php echo $row['id']; ?>">edit</a><span class="btn btn-default btn-xs delete-btn" data-delete="<?php echo $row['id']; ?>">delete</span></td>
              <td><?php echo $row['title']; ?></td>
              <td><a href="<?php echo $row['company_site']; ?>" target="_blank"><?php echo $row['company_name']; ?></a></td>
              <td>
                <span class="hidden content" data-content="<?php echo $row['id']; ?>"><?php echo $row['content']; ?></span>
                <button class="btn btn-default content-btn" data-id="<?php echo $row['id']; ?>"><i class="fa fa-search"></i></button>
              </td>
              <td>
                  <?php
                    if ($row['cover_letter']) {
                      $id = $row['id'];
                      $cover_letter = $row['cover_letter'];
                      echo '<button class="btn btn-default cover-btn" data-id="'.$id.'"><i class="fa fa-book"></i></button>';
                      echo "<span class='cover_letter hidden' data-cover='$id'>$cover_letter</span>";
                    } else {
                      echo 'None';
                    }
                  ?>
                </button>
              </td>
              <td><?php
                $job_id = $row['job_site'];
                echo '<a href="'.$sites_link[$job_id].'" target="_blank">'.$sites[$job_id].'</a>';
              ?></td>
              <td><?php
                $date = date_create($row['applied_date']);
                echo date_format($date,"m/d/Y");
              ?></td>
              <td><?php echo $status_text[$row['status']]; ?></td>
            </tr>
          <?php
              }
            }
          ?>
        </tbody>
      </table>
  </div>

  <!-- Content / Cover Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>

<form method="POST" action="job_delete.php" id="delete">
  <input type="hidden" name="job_id" id="job_delete_id">
</form>
<?php
include 'includes/scripts.php';
?>
<script>
$(function() {
  $('.delete-btn').click(function() {
    var id = $(this).data('delete');
    var ask = confirm("Are you sure you want to delete this item?");
    if (ask) {
      console.log('deleting id #' + id);
      $('#job_delete_id').val(id);
      $('#delete').submit();
    }
  });

  $('.cover-btn').click(function() {
    var id = $(this).data('id');
    var cover = $('span[data-cover=' + id + ']').text();
    $('.modal-body').text(cover);
    $('.modal-title').text('Cover Letter');
    $('#myModal').modal();
  });

  $('.content-btn').click(function() {
    var id = $(this).data('id');
    var content = $('span[data-content=' + id + ']').text();
    $('.modal-body').text(content);
    $('.modal-title').text('Content');
    $('#myModal').modal();
  });
});
</script>
<?php
include 'includes/footer.php';
?>
