<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Delete the product from the database
    $deleteSql = "DELETE FROM product WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $productID);
    

    if ($stmt->execute()) {
        $delSql = "DELETE FROM `stockitem` WHERE product_id = :pid";
        $stmtp = $pdo->prepare($delSql);
        $stmtp->bindParam(':pid', $productID);
        if ($stmtp->execute()) {
        header("Location: products.php?succ=" . urlencode('Product Successfully Deleted'));
        }
    } else {
        header("Location: products.php?err=" . urlencode('Something went wrong. Please try again later'));
    }
} else {
    header("Location: products.php");
    exit();
}
?>
