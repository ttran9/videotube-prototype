<?php
require_once("includes/header.php");


if(!isset($_POST['uploadButton'])) {
    echo "No file sent to page.";
    exit();
}

// 1) create file upload data.
// 2) Process video data (upload).
// 3) Check if upload was successful.
?>