<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "stockorders.php?succ=";
    $u2 = "edit-stockorder.php?id=" . $_POST['orderID'] . "&err=";
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

    if ($oldstatus == 'Accepted' && $status == 'Accepted') {
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


    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        $cuisineID = $_POST['cu'][$i];
        $typeID = 2;
        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i];
        // $priorityy = $_POST['pr'][$i];
        $quantitys = $_POST['deliveryqt'][$i];
        $quantit = $_POST['receivedqt'][$i];
        $unit = $_POST['unit'][$i];

        $oldRecQty = $_POST['oldRecQty'][$i];

        if (intval($quantity) > 0) {

            // Check if the product already exists
            $existingItemSql = "SELECT * FROM `orderitem` WHERE order_id = :order_id AND productid = :productid";
            $existingItemStmt = $pdo->prepare($existingItemSql);
            $existingItemStmt->bindParam(':order_id', $orderID);
            $existingItemStmt->bindParam(':productid', $productID);
            $existingItemStmt->execute();

            if ($existingItemRow = $existingItemStmt->fetch(PDO::FETCH_ASSOC)) {
                // Product already exists, update the quantity
                $updatedQuantity = $existingItemRow['order_qty'] + $quantity;
                $orderItemSql = "UPDATE `orderitem` SET order_qty = :order_qty WHERE order_id = :order_id AND productid = :productid";
                $orderItemStmt = $pdo->prepare($orderItemSql);
                $orderItemStmt->bindParam(':order_qty', $updatedQuantity);
                $orderItemStmt->bindParam(':order_id', $orderID);
                $orderItemStmt->bindParam(':productid', $productID);
            } else {

                $orderItemSql = "INSERT INTO `orderitem` (order_id, productid, cuisineid, typeid, order_qty, categoryid, delivery_qty, received_qty, unit) VALUES (:order_id, :productid, :cuisineid, :typeid, :order_qty, :categoryid, :delivery_qty, :received_qty, :unit)";
                $orderItemStmt = $pdo->prepare($orderItemSql);
                $orderItemStmt->bindParam(':order_id', $orderID);
                $orderItemStmt->bindParam(':productid', $productID);
                $orderItemStmt->bindParam(':cuisineid', $cuisineID);
                $orderItemStmt->bindParam(':typeid', $typeID);
                $orderItemStmt->bindParam(':categoryid', $categoryID);
                $orderItemStmt->bindParam(':order_qty', $quantity);
                // $orderItemStmt->bindParam(':priority', $priorityy);
                $orderItemStmt->bindParam(':received_qty', $quantit);
                $orderItemStmt->bindParam(':delivery_qty', $quantitys);
                $orderItemStmt->bindParam(':unit', $unit);
            }

            if ($orderItemStmt->execute()) {
                // if ($status == 'Received') {
                //     // Get Stock id 
                //     $sidSql = "SELECT id FROM `stock` WHERE branchid = $branchID";
                //     $sidStmt = $pdo->query($sidSql);
                //     $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
                //     $sid = $sidData["id"];

                //     // var_dump($sid);
                //     // exit();

                //     // Get Current qty 
                //     $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
                //     $cqStmt = $pdo->query($cqSql);
                //     $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                //     $cq = $cqData["qty"];

                //     // Get current quantity from main stock 
                //     // $msSql = "SELECT qty FROM `stockitem` WHERE stock_id = '1' AND product_id = $productID";
                //     // $msStmt = $pdo->query($msSql);
                //     // $msData = $msStmt->fetch(PDO::FETCH_ASSOC);
                //     // $stqty = $msData["qty"];

                //     $updatedQty = 0;
                //     $finalstqty = 0;

                //     if ($oldRecQty <= $quantit) {
                //         $updatedQty = $quantit - $oldRecQty;
                //         $finalQty = $cq + $updatedQty;
                //         // $finalstqty = $stqty - $updatedQty;

                //     } else {
                //         $updatedQty = $oldRecQty - $quantit;
                //         $finalQty = $cq - $updatedQty;
                //         // $finalstqty = $stqty + $updatedQty;
                //     }

                //     // Update Stock
                //     $susql = "UPDATE `stockitem` SET qty = :quantity WHERE stock_id = :sid AND product_id = :productID";
                //     $sustmt = $pdo->prepare($susql);

                //     $sustmt->bindParam(':quantity', $finalQty, PDO::PARAM_INT);
                //     $sustmt->bindParam(':sid', $sid, PDO::PARAM_INT);
                //     $sustmt->bindParam(':productID', $productID, PDO::PARAM_INT);

                //     $sustmt->execute();

                //     // Update Main Stock 
                //     // $mqsql = "UPDATE `stockitem` SET qty = :qqt WHERE stock_id = '1' AND product_id = :pidd";
                //     // $mqstmt = $pdo->prepare($mqsql);

                //     // $mqstmt->bindParam(':qqt', $finalstqty, PDO::PARAM_INT);
                //     // $mqstmt->bindParam(':pidd', $productID, PDO::PARAM_INT);

                //     // $mqstmt->execute();
                // }
                if ($status == 'Received') {
                    // Get Stock id 
                    $sidSql = "SELECT id FROM `stock` WHERE branchid = $branchID";
                    $sidStmt = $pdo->query($sidSql);
                    $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
                    $stockId = $sidData["id"];

                    // Get Stock Data 
                    $stockDataSql = "SELECT * FROM `stockitem` WHERE stock_id = $stockId AND product_id = $productID";
                    $stockDataStmt = $pdo->query($stockDataSql);
                    $stockDataStmt->execute();

                    // var_dump($stockDataStmt->rowCount());
                    // exit();

                    if ($stockDataStmt->rowCount() > 0) {
                        // Get Current qty 
                        $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $stockId AND product_id = $productID";
                        $cqStmt = $pdo->query($cqSql);
                        $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                        $cq = $cqData["qty"];

                        $updatedQty = 0;
                        $finalstqty = 0;

                        if ($oldRecQty <= $quantit) {
                            $updatedQty = $quantit - $oldRecQty;
                            $finalQty = $cq + $updatedQty;

                        } else {
                            $updatedQty = $oldRecQty - $quantit;
                            $finalQty = $cq - $updatedQty;
                        }

                        // Update Stock
                        $susql = "UPDATE `stockitem` SET qty = :quantity WHERE stock_id = :sid AND product_id = :productID";
                        $sustmt = $pdo->prepare($susql);

                        $sustmt->bindParam(':quantity', $finalQty);
                        $sustmt->bindParam(':sid', $stockId);
                        $sustmt->bindParam(':productID', $productID);

                        $sustmt->execute();
                    } else {
                        $susql = "INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id, qty, unit) VALUES (:stock_id, :type_id, :cuisine_id, :category_id, :product_id, :qty, :unit)";
                        $sustmt = $pdo->prepare($susql);

                        $sustmt->bindParam(':qty', $quantit);
                        $sustmt->bindParam(':stock_id', $stockId);
                        $sustmt->bindParam(':product_id', $productID);
                        $sustmt->bindParam(':type_id', $typeID);
                        $sustmt->bindParam(':cuisine_id', $cuisineID);
                        $sustmt->bindParam(':category_id', $categoryID);
                        $sustmt->bindParam(':unit', $unit);

                        $sustmt->execute();
                    }


                    // foreach ($stAccData as $row) {
                    //     $stockId = $row['id'];
                    //     $stockSql = "INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id, unit)
                    //     VALUES (:stock_id, :type_id, :cuisine_id, :category_id, :product_id, :unit)";
                    //     $itemStmt = $pdo->prepare($stockSql);
                    //     $itemStmt->bindParam(':stock_id', $stockId);
                    //     $itemStmt->bindParam(':type_id', $typeid);
                    //     $itemStmt->bindParam(':cuisine_id', $cuisineid);
                    //     $itemStmt->bindParam(':category_id', $categoryid);
                    //     $itemStmt->bindParam(':unit', $unit);
                    //     $itemStmt->bindParam(':product_id', $proId);

                    //     if (!$itemStmt->execute()) {
                    //         $pdelSql = "DELETE FROM `product` WHERE id = :proid";
                    //         $pdelstmt = $pdo->prepare($pdelSql);
                    //         $pdelstmt->bindParam(":proid", $proId);
                    //         $pdelstmt->execute();
                    //         header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
                    //         exit();
                    //     }
                    // }



                    // Get current quantity from main stock 
                    // $msSql = "SELECT qty FROM `stockitem` WHERE stock_id = '1' AND product_id = $productID";
                    // $msStmt = $pdo->query($msSql);
                    // $msData = $msStmt->fetch(PDO::FETCH_ASSOC);
                    // $stqty = $msData["qty"];



                    // Update Main Stock 
                    // $mqsql = "UPDATE `stockitem` SET qty = :qqt WHERE stock_id = '1' AND product_id = :pidd";
                    // $mqstmt = $pdo->prepare($mqsql);

                    // $mqstmt->bindParam(':qqt', $finalstqty, PDO::PARAM_INT);
                    // $mqstmt->bindParam(':pidd', $productID, PDO::PARAM_INT);

                    // $mqstmt->execute();
                }
            }
        }
    }
}

header("Location: " . $u1 . urlencode('Order Successfully Updated'));
exit();

?>