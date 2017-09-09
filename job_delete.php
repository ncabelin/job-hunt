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
  $job_id = validateData($_POST['job_id']);
  $query = "DELETE FROM jobs WHERE id = ? AND user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('ii', $job_id, $user_id);
  if ($stmt->execute()) {
    header('Location: job_application.php?success=Deleted job application');
  } else {
    header('Location: job_add.php?error=Could not delete job application');
  }
}
