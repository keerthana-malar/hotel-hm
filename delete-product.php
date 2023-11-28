<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if (isset($_GET['id'])) {
    $productID = $_GET['id'];
    $type = $_GET['type'];

    if ($type == 'food') {
        $u1 = "foodcatalog.php?succ=";
        $u2 = "foodcatalog.php?err=";
    } else {
        $u1 = "stockcatalog.php?succ=";
        $u2 = "stockcatalog.php?err=";
    }

    // Prepare the SELECT query
    $sqlDup = "SELECT * FROM `orderitem` WHERE productid = :valueToCheck";

    // Prepare and execute the statement
    $stmtDup = $pdo->prepare($sqlDup);
    $stmtDup->bindParam(':valueToCheck', $productID);
    $stmtDup->execute();

    if ($stmtDup->rowCount() > 0) {
        header("Location:" . $u2 . urlencode("Product already in use"));
        exit();
    } else {
        // Delete the product from the database
        $deleteSql = "DELETE FROM product WHERE id = :id";
        $stmt = $pdo->prepare($deleteSql);
        $stmt->bindParam(':id', $productID);


        if ($stmt->execute()) {
            $delSql = "DELETE FROM `stockitem` WHERE product_id = :pid";
            $stmtp = $pdo->prepare($delSql);
            $stmtp->bindParam(':pid', $productID);
            if ($stmtp->execute()) {
                header("Location:" . $u1 . urlencode('Product Successfully Deleted'));
                exit();
            }
        } else {
            header("Location:" . $u2 . urlencode('Something went wrong. Please try again later'));
            exit();
        }
    }


} else {
    header("Location:" . $u2 . urlencode('Something went wrong. Please try again later'));
    exit();
}
?>