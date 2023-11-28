<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['delete_id'])) {
    $orderID = $_GET['delete_id'];
    $type = $_GET['type'];

    // Urls 
    if ($type == 'food') {
        $u1 = "foodorders.php?succ=";
        $u2 = "foodorders.php?err=";
    } elseif ($type == 'stock') {
        $u1 = "stockorders.php?succ=";
        $u2 = "stockorders.php?err=";
    } else {
        $u1 = "outdoororders.php?succ=";
        $u2 = "outdoororders.php?err=";
    }


    // Prepare the SELECT query
    $sqlDup = "SELECT status FROM `order` WHERE id = :valueToCheck";

    // Prepare and execute the statement
    $stmtDup = $pdo->prepare($sqlDup);
    $stmtDup->bindParam(':valueToCheck', $orderID);
    $stmtDup->execute();
    $data = $stmtDup->fetch(PDO::FETCH_ASSOC);

    if ($data['status'] != 'Created') {
        header("Location:" . $u2 . urlencode("Can't Delete Accepted Order"));
        exit();
    } else {
        // Delete the order from the database
        $deleteSql = "DELETE FROM `order` WHERE id = :id";
        $stmt = $pdo->prepare($deleteSql);
        $stmt->bindParam(':id', $orderID);

        $deleteSql1 = "DELETE FROM `orderitem` WHERE order_id = :id";
        $stmt1 = $pdo->prepare($deleteSql1);
        $stmt1->bindParam(':id', $orderID);

        if ($stmt->execute()) {
            if ($stmt1->execute()) {
                header("Location:" . $u1 . urlencode('Order Successfully Deleted'));
                exit();
            }
        } else {
            header("Location: " . $u2 . urlencode('Something went wrong. Please try again later'));
            exit();
        }
    }

} else {
    header("Location: " . $u2 . urlencode('Something went wrong. Please try again later'));
    exit();
}
?>