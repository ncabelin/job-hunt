<?php
  function validateData($formData) {
    $formData = trim(stripslashes(htmlspecialchars($formData)));
    return $formData;
  }
?>
