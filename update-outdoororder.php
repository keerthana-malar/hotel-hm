<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "outdoororders.php?succ=";
    $u2 = "edit-order.php?id=" . $_POST['orderID'] . "&err=";
    $oid = $_POST['oid'];
    $orderID = $_POST['orderID'];
    $branchID = $_POST['branch']; // Updated Branch ID
    $orderDate = $_POST['orderdate']; // Updated Order Date
    $deliveryDate = $_POST['deliverydate']; // Updated Delivery Date
    $priority = $_POST['priority']; // Updated Priority
    $status = $_POST['status']; // Updated Status
    $des = $_POST['des'];
    $orderName = $_POST['orderName'];

    $orderOldDataSql = "SELECT status FROM `order` WHERE id = :id";
    $oodStmt = $pdo->prepare($orderOldDataSql);
    $oodStmt->bindParam(":id", $oid);
    $oodStmt->execute();
    $orderOdata = $oodStmt->fetch(PDO::FETCH_ASSOC);
    $oldstatus = $orderOdata['status'];

    if ($oldstatus == 'Accepted'){
        header("Location: " . $u2 . urlencode('Order Already Accepted'));
        exit();
    }

    // Update data in the order table
    $updateSql = "UPDATE `order` SET branchid = :branchid, orderdate = :orderdate, deliverydate = :deliverydate, priority = :priority, status = :status, description = :description, status = :status, order_name = :order_name WHERE id = :id";
    $stmt = $pdo->prepare($updateSql);
    $stmt->bindParam(':id', $orderID);
    $stmt->bindParam(':branchid', $branchID);
    $stmt->bindParam(':orderdate', $orderDate);
    $stmt->bindParam(':deliverydate', $deliveryDate);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':description', $des);
    $stmt->bindParam(':order_name', $orderName);

    if (!$stmt->execute()) {
        header("Location: " . $u2 . urlencode('Something went wrong. Please try again later'));
        exit();
    }
   

    $deleteDaysQuery = "DELETE FROM orderitem WHERE order_id = :postID";
    $stmtDelete = $pdo->prepare($deleteDaysQuery);
    $stmtDelete->bindParam(':postID', $oid);
    $stmtDelete->execute();

    // Create or update production chart
    $chartSql = "SELECT id FROM `pro_chart` WHERE date = :deliveryDate";
    $chartstmt = $pdo->prepare($chartSql);
    $chartstmt->bindParam(':deliveryDate', $deliveryDate);
    $chartstmt->execute();
    $chartRow = $chartstmt->fetch(PDO::FETCH_ASSOC);

    if ($chartRow) {
        $chartid = $chartRow["id"];
    } else {
        $chatcSql = "INSERT INTO `pro_chart` (date) VALUES (:date)";
        $chstmt = $pdo->prepare($chatcSql);
        $chstmt->bindParam(":date", $deliveryDate);
        if ($chstmt->execute()) {
            $chartid = $pdo->lastInsertId();
        }
    }


    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        $cuisineID = $_POST['cu'][$i];
        $typeID = $_POST['ty'][$i];
        $unit = $_POST['unit'][$i];

        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i];
        // $priorityy = $_POST['pr'][$i];
        $quantitys = $_POST['deliveryqt'][$i];
        // $quantit = $_POST['receivedqt'][$i];
    

        $orderItemSql = "INSERT INTO `orderitem` (order_id, productid, cuisineid, unit, typeid, order_qty, categoryid, delivery_qty) VALUES (:order_id, :productid, :cuisineid, :unit, :typeid, :order_qty, :categoryid, :delivery_qty)";
        $orderItemStmt = $pdo->prepare($orderItemSql);
        $orderItemStmt->bindParam(':order_id', $orderID);
        $orderItemStmt->bindParam(':productid', $productID);
        $orderItemStmt->bindParam(':cuisineid', $cuisineID);
        $orderItemStmt->bindParam(':typeid', $typeID);
        $orderItemStmt->bindParam(':unit', $unit);
        $orderItemStmt->bindParam(':categoryid', $categoryID);
        $orderItemStmt->bindParam(':order_qty', $quantity);
        // $orderItemStmt->bindParam(':priority', $priorityy);
        // $orderItemStmt->bindParam(':received_qty', $quantit);
        $orderItemStmt->bindParam(':delivery_qty', $quantitys);



        $orderItemStmt->execute();

        if ($status == "Accepted") {
            // Check Chart already exists
            $chartitemFind = "SELECT * FROM `pro_chart_item` WHERE chart_id = :chartid AND product_id = :productID";
            $chitstmt = $pdo->prepare($chartitemFind);
            $chitstmt->bindParam(':chartid', $chartid);
            $chitstmt->bindParam(':productID', $productID);
            $chitstmt->execute();
            
            $chartItemData = $chitstmt->fetch(PDO::FETCH_ASSOC);
        
            if ($chartItemData) {
                // Find New Qty 
                $newQty = $chartItemData['qty'] + $quantity;
        
                // Update Chart
                $chupSql = "UPDATE `pro_chart_item` SET qty = :quantity WHERE chart_id = :chartid AND product_id = :productID";
                $chupstmt = $pdo->prepare($chupSql);
                $chupstmt->bindParam(':quantity', $newQty);
                $chupstmt->bindParam(':chartid', $chartid);
                $chupstmt->bindParam(':productID', $productID);
                $chupstmt->execute();
            } else {
                // Insert new Chart record
                $cchupSql = "INSERT INTO `pro_chart_item` (chart_id, product_id, type_id, category_id, cuisine_id, qty) VALUES (:chartid, :productID, :typeID, :categoryID, :cuisineID, :quantity)";
                $cchupstmt = $pdo->prepare($cchupSql);
                $cchupstmt->bindParam(':chartid', $chartid);
                $cchupstmt->bindParam(':productID', $productID);
                $cchupstmt->bindParam(':typeID', $typeID);
                $cchupstmt->bindParam(':categoryID', $categoryID);
                $cchupstmt->bindParam(':cuisineID', $cuisineID);
                $cchupstmt->bindParam(':quantity', $quantity);
                $cchupstmt->execute();
            }
        }
    }

    header("Location: " . $u1 . urlencode('Order Successfully Updated'));
    exit();
}
?>