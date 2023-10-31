<?php
include('header.php');
include('menu.php');
if (isset($_POST['import'])) {
    $file = $_FILES['imported_file']['tmp_name'];
    if (is_uploaded_file($file)) {
        $target_dir = "uploads/";  // Directory where you want to save the uploaded files
        $target_file = $target_dir . basename($_FILES['imported_file']['name']);
        move_uploaded_file($file, $target_file);
        echo "File uploaded successfully.";
    } else {
        echo "Failed to upload the file.";
    }
}
?>