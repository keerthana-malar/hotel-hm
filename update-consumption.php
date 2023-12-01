<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "consumptions.php?succ=";
    $u2 = "edit-consumption.php?id=" . $_POST['id'] . "&err=";

    $consumptionID = $_POST['id'];
    $branch = $_POST['branch'];
    $date = $_POST['date'];

    // Update data in consumption table
    $updateSql = "UPDATE `consumption` SET branchid = :branchid, date_created = :date_created WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->bindParam(':id', $consumptionID);
    $stmt->bindParam(':branchid', $branch);
    $stmt->bindParam(':date_created', $date);

    if ($stmt === false) {
        // Error handling for preparing the statement
        die("Error preparing statement: " . $pdo->errorInfo()[2]);
    }

    if ($stmt->execute()) {

        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            // $cuisineID = $_POST['cu'][$i];
            $typeID = $_POST['ty'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
            $units = $_POST['unit'][$i];
            $old_qty = $_POST['old_qty'][$i];

            // Find stock id 
            $stockquery = "SELECT id FROM `stock` WHERE branchid = $branch";
            $sqstmt = $pdo->query($stockquery);
            $sqrow = $sqstmt->fetch(PDO::FETCH_ASSOC);
            $sid = $sqrow['id'];

            // Get existing quantity 
            $exquery = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
            $exstmt = $pdo->query($exquery);
            $exrow = $exstmt->fetch(PDO::FETCH_ASSOC);
            $eqty = $exrow['qty'];

            // GEt Pro Data 
            $proquery = "SELECT name FROM `product` WHERE id = $productID";
            $prostmt = $pdo->query($proquery);
            $prorow = $prostmt->fetch(PDO::FETCH_ASSOC);
            $proname = $prorow['name'];

            // $old_Qty1 = 0;
            // if ($eqty < $old_qty) {
            //     $old_Qty1 = $old_wq - $eqty;
            // } else {
            //     $old_Qty1 = $eqty - $old_wq;
            // }

            $old_Qty1 = $eqty + $old_wq;

            // Condition check for qty 
            if ($quantity >= $old_Qty1) {
                header("Location: " . $u2 . urlencode('Not valid qty for ' . $proname));
                exit();
            }
        }
    }

    $oid = $_POST['oid'];

    // $deleteDaysQuery = "DELETE FROM consumptionitem WHERE consumption_id = :postID";
    // $stmtDelete = $pdo->prepare($deleteDaysQuery);
    // $stmtDelete->bindParam(':postID', $oid);
    // $stmtDelete->execute();


    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        // $cuisineID = $_POST['cu'][$i];
        $typeID = $_POST['ty'][$i];
        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i];
        $unit = $_POST['unit'][$i];
        $old_qty = $_POST['old_qty'][$i];

        // Find stock id 
        $stockquery = "SELECT id FROM `stock` WHERE branchid = $branch";
        $sqstmt = $pdo->query($stockquery);
        $sqrow = $sqstmt->fetch(PDO::FETCH_ASSOC);
        $sid = $sqrow['id'];


        // Get existing quantity 
        $exquery = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
        $exstmt = $pdo->query($exquery);
        $exrow = $exstmt->fetch(PDO::FETCH_ASSOC);
        $eqty = $exrow['qty'];

        // Find used quantity 
        $uqty = $old_qty - $quantity;

        // Update quantity in stock 
        $suq = "UPDATE stockitem SET qty = $quantity WHERE product_id = $productID AND stock_id = $sid";
        $sus = $pdo->query($suq);
        $sus->execute();

        // Define the SQL query for checking if a record exists
        $checkItemSql = "SELECT id FROM `consumptionitem` WHERE consumption_id = :consumption_id AND product_id = :product_id";
        $checkItemStmt = $pdo->prepare($checkItemSql);
        $checkItemStmt->bindParam(':consumption_id', $consumptionID);
        $checkItemStmt->bindParam(':product_id', $productID);
        $checkItemStmt->execute();
        $existingItemId = $checkItemStmt->fetchColumn();

        if ($existingItemId) {
            // If the record exists, update it
            $updateItemSql = "UPDATE `consumptionitem` SET qty = :qty, used_qty = :used_qty WHERE id = :existing_item_id";
            $updateItemStmt = $pdo->prepare($updateItemSql);
            $updateItemStmt->bindParam(':existing_item_id', $existingItemId);
            $updateItemStmt->bindParam(':qty', $quantity);
            $updateItemStmt->bindParam(':used_qty', $uqty);
        } else {
            // If the record doesn't exist, insert a new one
            $updateItemSql = "INSERT INTO `consumptionitem` (consumption_id, product_id, unit, type_id, qty, old_qty, category_id, used_qty) VALUES (:consumption_id, :product_id, :unit, :type_id, :qty, :old_qty, :category_id, :used_qty)";
            $updateItemStmt = $pdo->prepare($updateItemSql);
            $updateItemStmt->bindParam(':consumption_id', $consumptionID);
            $updateItemStmt->bindParam(':product_id', $productID);
            $updateItemStmt->bindParam(':unit', $unit);
            $updateItemStmt->bindParam(':type_id', $typeID);
            $updateItemStmt->bindParam(':qty', $quantity);
            $updateItemStmt->bindParam(':used_qty', $uqty);
            $updateItemStmt->bindParam(':old_qty', $old_qty);
            $updateItemStmt->bindParam(':category_id', $categoryID);
        }

        // Bind parameters and execute the statement

        $updateItemStmt->execute();

    }


    header("Location: " . $u1 . urlencode('Closing Stock Successfully Updated'));
    exit();
} else {
    // Execution failed, handle the error
    header("Location: " . $u2 . urlencode('Something went wrong. Please try again later'));
    exit();
}

?>