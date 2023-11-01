<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['delete_id'])) {
    $branchID = $_GET['delete_id'];
// Check if the branch being deleted is the one you want to protect (e.g., branch with ID 1)
if ($branchID ==1 ) {
    header("Location: branchs.php?err=" . urlencode('You cannot delete Main branch.'));
    exit();
}
    // Delete the branch from the database
    $deleteSql = "DELETE FROM branch WHERE id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $branchID);

    if ($stmt->execute()) {
        $delSqlb = "DELETE FROM stock WHERE branchid = :bid";
        $stmtb = $pdo->prepare($delSqlb);
        $stmtb->bindParam(':bid', $productID);
        if ($stmtb->execute()) {
        header("Location: branchs.php?succ=" . urlencode('Branch Successfully Deleted'));
    }
    } else {
        header("Location: branchs.php?err=" . urlencode('Something went wrong. Please try again later'));
    }
} else {
    header("Location: branchs.php");
    exit();
}
?>
