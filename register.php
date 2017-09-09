<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include 'includes/validation.php';
  $username = validateData($_POST['username']);
  $password = validateData($_POST['password']);
  $re_password = validateData($_POST['re_password']);
  if ($password != $re_password) {
    header('Location: index.php?error=Passwords must match');
    exit();
  }
  include 'includes/connection.php';
  $stmt = $conn->prepare("INSERT INTO user(username, password) VALUES(?, ?)");
  $stmt->bind_param("ss", $username, sha1($password));
  if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $stmt->insert_id;
    header('Location: job_application.php');
  } else {
    header('Location: index.php?error=Unable to register');
  }
}
