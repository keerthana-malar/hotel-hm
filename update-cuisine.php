<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "cuisines.php?succ=";
    $u2 = "edit-cuisine.php?id=" . $_POST['cuisineID'] . "&err=";


    $cuisineID = $_POST['cuisineID'];
    $cuisineName = $_POST['cuisine_name'];
    $status = $_POST['status'];

   // Duplicate cuisine name check
   $checkDuplicateQuery = "SELECT COUNT(*) FROM cuisine WHERE name = :name AND id != :id";
   $checkStmt = $pdo->prepare($checkDuplicateQuery);
   $checkStmt->bindParam(':name', $cuisineName); // Corrected variable name
   $checkStmt->bindParam(':id', $cuisineID);

   $checkStmt->execute();
   $duplicateCount = $checkStmt->fetchColumn();

   if ($duplicateCount > 0) {
       header("Location: " . $u2 . urlencode('Cuisine already exists'));
       exit();
   }


    // Update data in cuisine table
    $updateSql = "UPDATE cuisine SET name = :name, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->bindParam(':id', $cuisineID);
    $stmt->bindParam(':name', $cuisineName);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header("Location: cuisines.php?succ=" . urlencode('Cuisine Successfully Updated'));
    } else {
        header("Location: edit-cuisine.php?id=" . $cuisineID . "&err=" . urlencode('Something went wrong. Please try again later'));
    }
}
?>
