<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['delete_id'])) {
    $userID = $_GET['delete_id'];

    // Delete the user from the database
    $deleteSql = "DELETE FROM role WHERE role_id = :id";
    $stmt = $pdo->prepare($deleteSql);
    $stmt->bindParam(':id', $userID);

    if ($stmt->execute()) {
        header("Location: role_view.php?succ=" . urlencode('Role Successfully Deleted'));
    } else {
        header("Location: role_view.php?err=" . urlencode('Something went wrong. Please try again later'));
    }
} else {
    header("Location: role_view.php");
    exit();
}
?>
