<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $branch = $_POST['branch'];
    $role = $_POST['role'];
    $u1 =  "users.php?succ=";
    $u2 = "create-user.php?err=";

    // Duplicate  username check (excluding the current user being edited)
    $checkDuplicateQuery = "SELECT COUNT(*) FROM user WHERE username = :username AND id != :id";
    $checkStmt = $pdo->prepare($checkDuplicateQuery);
    $checkStmt->bindParam(':username', $username);
    $checkStmt->bindParam(':id', $userID);
    $checkStmt->execute();
    $duplicateCount = $checkStmt->fetchColumn();

    if ($duplicateCount > 0) {
        header("Location: " . $u2 . urlencode('Username already taken'));         
        exit();
    }
    // Update user data in the database
    $updateSql = "UPDATE user SET name = :name, username = :username, branch = :branch, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->bindParam(':id', $userID);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':branch', $branch);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: $u1 User Successfully Updated");
    } else {
        header("Location:  $u2 edit-user.php?id=$userID&err=Something went wrong. Please try again later");
    }
}
?>
