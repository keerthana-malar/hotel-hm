<?php
require('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pass = $_POST["password"];

    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
        $stmt->execute([$username, $pass]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["user"] = $user;
            header("Location: dashboard1.php"); 
            exit();
        } else {
            $errorMessage = "Invalid Username or Password";
            
            header("Location: index.php?err=" . urlencode($errorMessage));
            exit();
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>