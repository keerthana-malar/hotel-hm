<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $u1 = "orders.php?succ=";
    $u2 = "edit-order.php?id=" . $_POST['orderID'] . "&err=";

    $orderID = $_POST['orderID'];
    $branchID = $_POST['branch']; // Updated Branch ID
    $orderDate = $_POST['orderdate']; // Updated Order Date
    $deliveryDate = $_POST['deliverydate']; // Updated Delivery Date
    $priority = $_POST['priority']; // Updated Priority
    $status = $_POST['status']; // Updated Status
    $des = $_POST['des'];
    $orderName = $_POST['orderName'];

    try {
        $pdo->beginTransaction();

        // Update data in the order table
        $updateSql = "UPDATE `order` SET branchid = :branchid, orderdate = :orderdate, deliverydate = :deliverydate, priority = :priority, status = :status, description = :description, order_name = :order_name WHERE id = :id";
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
            throw new PDOException('Error updating order');
        }

        $oid = $_POST['oid'];

        $deleteDaysQuery = "DELETE FROM orderitem WHERE order_id = :postID";
        $stmtDelete = $pdo->prepare($deleteDaysQuery);
        $stmtDelete->bindParam(':postID', $oid);
        $stmtDelete->execute();

        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            $cuisineID = $_POST['cu'][$i];
            $typeID = $_POST['ty'][$i];
            $unitID = $_POST['unit'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
            // $priorityy = $_POST['pr'][$i];
            $quantitys = $_POST['deliveryqt'][$i];
            $quantit = $_POST['receivedqt'][$i];

            $oldRecQty = $_POST['oldRecQty'][$i];

            $orderItemSql = "INSERT INTO `orderitem` (order_id, productid, cuisineid, typeid, order_qty, categoryid, delivery_qty, received_qty, unit) VALUES (:order_id, :productid, :cuisineid, :typeid, :order_qty, :categoryid, :delivery_qty, :received_qty, :unit)";
            $orderItemStmt = $pdo->prepare($orderItemSql);
            $orderItemStmt->bindParam(':order_id', $orderID);
            $orderItemStmt->bindParam(':productid', $productID);
            $orderItemStmt->bindParam(':cuisineid', $cuisineID);
            $orderItemStmt->bindParam(':typeid', $typeID);
            $orderItemStmt->bindParam(':unit', $unitID);
            $orderItemStmt->bindParam(':categoryid', $categoryID);
            $orderItemStmt->bindParam(':order_qty', $quantity);
            // $orderItemStmt->bindParam(':priority', $priorityy);
            $orderItemStmt->bindParam(':received_qty', $quantit);
            $orderItemStmt->bindParam(':delivery_qty', $quantitys);

            if ($orderItemStmt->execute() && $status == 'Received') {
                // Get Stock id 
                $sidSql = "SELECT id FROM `stock` WHERE branchid = :branchID";
                $sidStmt = $pdo->prepare($sidSql);
                $sidStmt->bindParam(':branchID', $branchID);
                $sidStmt->execute();
                $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
                $sid = $sidData["id"];

                // Get Current qty 
                $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = :sid AND product_id = :productID";
                $cqStmt = $pdo->prepare($cqSql);
                $cqStmt->bindParam(':sid', $sid);
                $cqStmt->bindParam(':productID', $productID);
                $cqStmt->execute();
                $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                $cq = $cqData["qty"];

                // Crnt Qty from Main Stock 
                $msSql = "SELECT qty FROM `stockitem` WHERE stock_id = '1' AND product_id = $productID";
                $msStmt = $pdo->query($msSql);
                $msData = $msStmt->fetch(PDO::FETCH_ASSOC);
                $stqty = $msData["qty"];

                $updatedQty = 0;
                $finalstqty = 0;

                if ($oldRecQty <= $quantit) {
                    $updatedQty = $quantit - $oldRecQty;
                    $finalQty = $cq + $updatedQty;
                    $finalstqty = $stqty - $updatedQty;
                } else {
                    $updatedQty = $oldRecQty - $quantit;
                    $finalQty = $cq - $updatedQty;
                    $finalstqty = $stqty + $updatedQty;
                }

                // Update Stock
                $susql = "UPDATE `stockitem` SET qty = :quantity WHERE stock_id = :sid AND product_id = :productID";
                $sustmt = $pdo->prepare($susql);
                $sustmt->bindParam(':quantity', $finalQty, PDO::PARAM_INT);
                $sustmt->bindParam(':sid', $sid, PDO::PARAM_INT);
                $sustmt->bindParam(':productID', $productID, PDO::PARAM_INT);
                $sustmt->execute();

                // Update Main Stock 
                $mqsql = "UPDATE `stockitem` SET qty = :qqt WHERE stock_id = '1' AND product_id = :pidd";
                $mqstmt = $pdo->prepare($mqsql);

                $mqstmt->bindParam(':qqt', $finalstqty, PDO::PARAM_INT);
                $mqstmt->bindParam(':pidd', $productID, PDO::PARAM_INT);

                $mqstmt->execute();
            }
        }

        $pdo->commit();
        header("Location: " . $u1 . urlencode('Order Successfully Updated'));
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        header("Location: " . $u2 . urlencode('An error occurred. Please try again later'));
        exit();
    }
}
?>