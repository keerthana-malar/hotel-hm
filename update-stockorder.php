<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "stockorders.php?succ=";
    $u2 = "edit-order.php?id=" . $_POST['orderID'] . "&err=";

    $orderID = $_POST['orderID'];
    $branchID = $_POST['branch']; // Updated Branch ID
    $orderDate = $_POST['orderdate']; // Updated Order Date
    $deliveryDate = $_POST['deliverydate']; // Updated Delivery Date
    $priority = $_POST['priority']; // Updated Priority
    $status = $_POST['status']; // Updated Status
    $des = $_POST['des'];
    $orderName = $_POST['orderName'];

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
    $oid = $_POST['oid'];

    $deleteDaysQuery = "DELETE FROM orderitem WHERE order_id = :postID";
    $stmtDelete = $pdo->prepare($deleteDaysQuery);
    $stmtDelete->bindParam(':postID', $oid);
    $stmtDelete->execute();


    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        $cuisineID = $_POST['cu'][$i];
        $typeID = $_POST['ty'][$i];
        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i];
        $priorityy = $_POST['pr'][$i];
        $quantitys = $_POST['deliveryqt'][$i];
        $quantit = $_POST['receivedqt'][$i];

        $oldRecQty = $_POST['oldRecQty'][$i];


        $orderItemSql = "INSERT INTO `orderitem` (order_id, productid, cuisineid, typeid, order_qty, categoryid, priority, delivery_qty, received_qty) VALUES (:order_id, :productid, :cuisineid, :typeid, :order_qty, :categoryid, :priority, :delivery_qty, :received_qty)";
        $orderItemStmt = $pdo->prepare($orderItemSql);
        $orderItemStmt->bindParam(':order_id', $orderID);
        $orderItemStmt->bindParam(':productid', $productID);
        $orderItemStmt->bindParam(':cuisineid', $cuisineID);
        $orderItemStmt->bindParam(':typeid', $typeID);
        $orderItemStmt->bindParam(':categoryid', $categoryID);
        $orderItemStmt->bindParam(':order_qty', $quantity);
        $orderItemStmt->bindParam(':priority', $priorityy);
        $orderItemStmt->bindParam(':received_qty', $quantit);
        $orderItemStmt->bindParam(':delivery_qty', $quantitys);

        if ($orderItemStmt->execute()) {
            if ($status == 'Received') {
                // Get Stock id 
                $sidSql = "SELECT id FROM `stock` WHERE branchid = $branchID";
                $sidStmt = $pdo->query($sidSql);
                $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
                $sid = $sidData["id"];

                // var_dump($sid);
                // exit();

                // Get Current qty 
                $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
                $cqStmt = $pdo->query($cqSql);
                $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                $cq = $cqData["qty"];

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


                

                
                
                // echo "NQ";
                // var_dump($finalstqty);
                // exit();

                // echo "OQ";
                // var_dump($oldRecQty);

                // echo "UQ";
                // var_dump($updatedQty);

                

                // echo "CQ";
                // var_dump($cq);

                

                // echo "FQ";
                // var_dump($finalQty);

                // exit();

                // Update Stock
                $susql = "UPDATE `stockitem` SET qty = :quantity WHERE stock_id = :sid AND product_id = :productID";
                $sustmt = $pdo->prepare($susql);

                $sustmt->bindParam(':quantity', $finalQty, PDO::PARAM_INT);
                $sustmt->bindParam(':sid', $sid, PDO::PARAM_INT);
                $sustmt->bindParam(':productID', $productID, PDO::PARAM_INT);

                $sustmt->execute();

                $mqsql = "UPDATE `stockitem` SET qty = :qqt WHERE stock_id = '1' AND product_id = :pidd";
                $mqstmt = $pdo->prepare($mqsql);

                $mqstmt->bindParam(':qqt', $finalstqty, PDO::PARAM_INT);
                $mqstmt->bindParam(':pidd', $productID, PDO::PARAM_INT);

                $mqstmt->execute();
            }
        }
    }
    }

    header("Location: " . $u1 . urlencode('Order Successfully Updated'));
    exit();

?>