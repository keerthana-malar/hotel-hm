<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['delete_id'])) {
    $categoryId = $_GET['delete_id'];

    // Prepare the SELECT query 
    $sqlDup = "SELECT * FROM `product` WHERE categoryid = :valueToCheck";

    // Prepare and execute the statement
    $stmtDup = $pdo->prepare($sqlDup);
    $stmtDup->bindParam(':valueToCheck', $categoryId);
    $stmtDup->execute();

    // Prevent the delete 
    if ($stmtDup->rowCount() > 0) {
        header("Location: categories.php?err=" . urlencode('This Category is already in use'));
        exit();
    } else {
       // Delete the category from the database
    $deleteSql = "DELETE FROM category WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $categoryId);

    if ($stmt->execute()) {
        header("Location: categories.php?succ=" . urlencode('Category Successfully Deleted'));
        exit();
    } else {
        header("Location: categories.php?err=" . urlencode('Something went wrong. Please try again later'));
        exit();
    }
    }   
} else {
    header("Location: categories.php");
    exit();
}
?>
