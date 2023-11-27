<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "categories.php?succ=";
    $u2 = "edit-category.php?id=" . $_POST['categoryID'] . "&err=";

    $categoryId = $_POST['categoryId'];
    $categoryName = $_POST['category'];
    $status = $_POST['status'];

    
   
    // Duplicate category name check
    $checkDuplicateQuery = "SELECT COUNT(*) FROM category WHERE name = :name AND id != :id";
    $checkStmt = $pdo->prepare($checkDuplicateQuery);
    $checkStmt->bindParam(':name', $categoryName); // Corrected variable name
    $checkStmt->bindParam(':id', $categoryId);

    $checkStmt->execute();
    $duplicateCount = $checkStmt->fetchColumn();

    if ($duplicateCount > 0) {
        header("Location: " . $u2 . urlencode('Category already exists'));
        exit();
    }


    // Update data in the category table
    $updateSql = "UPDATE category SET name = :name, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->bindParam(':id', $categoryId);
    $stmt->bindParam(':name', $categoryName);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header("Location: categories.php?succ=" . urlencode('Category Successfully Updated'));
    } else {
        header("Location: edit-category.php?id=" . $categoryId . "&err=" . urlencode('Something went wrong. Please try again later'));
    }
}
?>
