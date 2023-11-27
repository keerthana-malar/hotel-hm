<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['delete_id'])) {
    $cuisineID = $_GET['delete_id'];

    
    // Prepare the SELECT query
    $sqlDup = "SELECT cuisineid FROM `product` WHERE cuisineid = :valueToCheck";

    // Prepare and execute the statement
    $stmtDup = $pdo->prepare($sqlDup);
    $stmtDup->bindParam(':valueToCheck', $cuisineID);
    $stmtDup->execute();

    if ($stmtDup->rowCount() > 0) {
        header("Location: cuisines.php?err=" . urlencode('This Cuisine already in use'));
        exit();
    } else {
         // Delete the cuisine from the database
    $deleteSql = "DELETE FROM cuisine WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $cuisineID);

    if ($stmt->execute()) {
        header("Location: cuisines.php?succ=" . urlencode('Cuisine Successfully Deleted'));
        exit();
    } else {
        header("Location: cuisines.php?err=" . urlencode('Something went wrong. Please try again later'));
        exit();
    }
    }
  
} else {
    header("Location: cuisines.php");
    exit();
}
?>
