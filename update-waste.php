<?php
// session_start();
// if (!isset($_SESSION['user'])) {
//     header("Location: index.php");
//     exit();
// }
// require('db.php');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $u1 = "wastes.php?succ=";
//     $u2 = "edit-waste.php?id=" . $_POST['id'] . "&err=";

//     $wasteID = $_POST['id'];
//     $branch = $_POST['branch'];
//     $date = $_POST['date'];
//     $wasteAmount = $_POST['amount'];

//     try {
//         $pdo->beginTransaction();

//         // Update data in waste table
//         $updateSql = "UPDATE `waste` SET waste_amount = :waste_amount WHERE id = :id";
//         $stmt = $pdo->prepare($updateSql);
//         $stmt->bindParam(':id', $wasteID);
//         $stmt->bindParam(':waste_amount', $wasteAmount);

//         if ($stmt === false) {
//             throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
//         }

//         if (!$stmt->execute()) {
//             throw new Exception('Something went wrong. Please try again later');
//         }

//         for ($i = 0; $i < count($_POST['pro']); $i++) {
//             $productID = $_POST['pro'][$i];
//             $typeID = $_POST['ty'][$i];
//             $categoryID = $_POST['ca'][$i];
//             $quantity = $_POST['qt'][$i];
//             $cost = $_POST['cost'][$i];
//             $old_wq = $_POST['old_wq'][$i];

//             // Find stock id 
//             $stockquery = "SELECT id FROM `stock` WHERE branchid = $branch";
//             $sqstmt = $pdo->query($stockquery);
//             $sqrow = $sqstmt->fetch(PDO::FETCH_ASSOC);
//             $sid = $sqrow['id'];

//             // Get existing quantity 
//             $exquery = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
//             $exstmt = $pdo->query($exquery);
//             $exrow = $exstmt->fetch(PDO::FETCH_ASSOC);
//             $eqty = $exrow['qty'];

//             // GEt Pro name 
//             $proquery = "SELECT name FROM `product` WHERE id = $productID";
//             $prostmt = $pdo->query($proquery);
//             $prorow = $prostmt->fetch(PDO::FETCH_ASSOC);
//             $proname = $prorow['name'];

//             $old_Qty = $eqty + $old_wq;

//             // Condition check for qty 
//             if ($quantity >= $old_Qty) {
//                 throw new Exception('Not valid qty for ' . $proname);
//             }
//         }

//         $oid = $_POST['oid'];

//         // Delete existing waste items
//         $deleteDaysQuery = "DELETE FROM wasteitem WHERE waste_id = :postID";
//         $stmtDelete = $pdo->prepare($deleteDaysQuery);
//         $stmtDelete->bindParam(':postID', $oid);
//         $stmtDelete->execute();

//         // Insert new waste items
//         for ($i = 0; $i < count($_POST['pro']); $i++) {
//             $productID = $_POST['pro'][$i];
//             $cuisineID = $_POST['cu'][$i];
//             $typeID = $_POST['ty'][$i];
//             $categoryID = $_POST['ca'][$i];
//             $quantity = $_POST['qt'][$i];
//             $cost = $_POST['cost'][$i];
//             $old_wq = $_POST['old_wq'][$i];

//             $wasteItemSql = "INSERT INTO `wasteitem` (waste_id, product_id, cuisine_id, type_id, qty, category_id, cost) VALUES (:waste_id, :product_id, :cuisine_id, :type_id, :qty, :category_id, :cost)";
//             $wasteItemStmt = $pdo->prepare($wasteItemSql);
//             $wasteItemStmt->bindParam(':waste_id', $wasteID);
//             $wasteItemStmt->bindParam(':product_id', $productID);
//             $wasteItemStmt->bindParam(':cuisine_id', $cuisineID);
//             $wasteItemStmt->bindParam(':type_id', $typeID);
//             $wasteItemStmt->bindParam(':category_id', $categoryID);
//             $wasteItemStmt->bindParam(':qty', $quantity);
//             $wasteItemStmt->bindParam(':cost', $cost);

//             if (!$wasteItemStmt->execute()) {
//                 throw new Exception('Error inserting waste item');
//             }

//             // GEt Stock id
//             $sidSql = "SELECT id FROM `stock` WHERE branchid = $branch";
//             $sidStmt = $pdo->query($sidSql);
//             $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
//             $sid = $sidData["id"];

//             // GEt Stock Qty
//             $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
//             $cqStmt = $pdo->query($cqSql);
//             $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
//             $cq = $cqData["qty"];

//             // Find Available Qty 
//             if ($old_wq <= $quantity) {
//                 $updatedQty = $quantity - $old_wq;
//                 $finalQty = $cq - $updatedQty;
//             } else {
//                 $updatedQty = $old_wq - $quantity;
//                 $finalQty = $cq + $updatedQty;
//             }

//             // Create Closing Stock
//             // $dateNow = date("Y-m-d");

//             // GEt Cons id
//             $cidSql = "SELECT id FROM `consumption` WHERE branchid = $branch AND date_created = '$date' AND is_auto = 1";
//             $cidStmt = $pdo->query($cidSql);
//             $cidData = $cidStmt->fetch(PDO::FETCH_ASSOC);
//             $cid = $cidData["id"];

//             // Delete Items 
//             $ciIQuery = "DELETE FROM `consumptionitem` WHERE consumption_id = :cons";
//             $ciIstmt = $pdo->prepare($ciIQuery);
//             $ciIstmt->bindParam(':cons', $cid);

//             // $consumption Item Add 
//             if ($ciIstmt->execute()) {
//                 $clsItemSql = "INSERT INTO `consumptionitem` (consumption_id, type_id, cuisine_id, category_id, product_id, qty, used_qty) VALUES (:ci, :ti, :cui, :cai, :pri, :qi, :ui)";
//                 $clsItemStmt = $pdo->prepare($clsItemSql);
//                 $clsItemStmt->bindParam(':ci', $cid);
//                 $clsItemStmt->bindParam(':ti', $typeID);
//                 $clsItemStmt->bindParam(':cui', $cuisineID);
//                 $clsItemStmt->bindParam(':cai', $categoryID);
//                 $clsItemStmt->bindParam(':pri', $productID);
//                 $clsItemStmt->bindParam(':qi', $quantity);
//                 $clsItemStmt->bindParam(':ui', $usedQty);

//                 if (!$clsItemStmt->execute()) {
//                     throw new Exception('Error inserting consumption item');
//                 }

//                 $upStQ = "UPDATE `stockitem` SET qty = :finalQty WHERE product_id = :productID AND stock_id = :sid";
//                 $upStQStmt = $pdo->prepare($upStQ);
//                 $upStQStmt->bindParam(':finalQty', $finalQty);
//                 $upStQStmt->bindParam(':productID', $productID);
//                 $upStQStmt->bindParam(':sid', $sid);

//                 if (!$upStQStmt->execute()) {
//                     throw new Exception('Error updating stock quantity');
//                 }
//             }
//         }

//         $pdo->commit();
//         header("Location: " . $u1 . urlencode('Wastage Successfully Updated'));
//         exit();
//     } catch (Exception $e) {
//         $pdo->rollBack();
//         header("Location: " . $u2 . urlencode($e->getMessage()));
//         exit();
//     }
// }

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "wastes.php?succ=";
    $u2 = "edit-waste.php?id=" . $_POST['id'] . "&err=";

    $wasteID = $_POST['id'];
    $branch = $_POST['branch'];
    $date = $_POST['date'];
    $wasteAmount = $_POST['amount'];

    try {
        $pdo->beginTransaction();

        // Update data in waste table
        $updateSql = "UPDATE `waste` SET waste_amount = :waste_amount WHERE id = :id";
        $stmt = $pdo->prepare($updateSql);
        $stmt->bindParam(':id', $wasteID);
        $stmt->bindParam(':waste_amount', $wasteAmount);

        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
        }

        if (!$stmt->execute()) {
            throw new Exception('Something went wrong. Please try again later');
        }

        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            $typeID = $_POST['ty'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
            $cost = $_POST['cost'][$i];
            $old_wq = $_POST['old_wq'][$i];

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

            // GEt Pro name 
            $proquery = "SELECT name FROM `product` WHERE id = $productID";
            $prostmt = $pdo->query($proquery);
            $prorow = $prostmt->fetch(PDO::FETCH_ASSOC);
            $proname = $prorow['name'];

            $old_Qty = $eqty + $old_wq;

            // Condition check for qty 
            if ($quantity >= $old_Qty) {
                throw new Exception('Not valid qty for ' . $proname);
            }
        }

        $oid = $_POST['oid'];

        // Delete existing waste items
        $deleteDaysQuery = "DELETE FROM `wasteitem` WHERE waste_id = :postID";
        $stmtDelete = $pdo->prepare($deleteDaysQuery);
        $stmtDelete->bindParam(':postID', $oid);
        $stmtDelete->execute();

        // Insert new waste items
        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            $cuisineID = $_POST['cu'][$i];
            $typeID = $_POST['ty'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
            $cost = $_POST['cost'][$i];
            $old_wq = $_POST['old_wq'][$i];

            // $usedQty = 0;

            $wasteItemSql = "INSERT INTO `wasteitem` (waste_id, product_id, cuisine_id, type_id, qty, category_id, cost) VALUES (:waste_id, :product_id, :cuisine_id, :type_id, :qty, :category_id, :cost)";
            $wasteItemStmt = $pdo->prepare($wasteItemSql);
            $wasteItemStmt->bindParam(':waste_id', $wasteID);
            $wasteItemStmt->bindParam(':product_id', $productID);
            $wasteItemStmt->bindParam(':cuisine_id', $cuisineID);
            $wasteItemStmt->bindParam(':type_id', $typeID);
            $wasteItemStmt->bindParam(':category_id', $categoryID);
            $wasteItemStmt->bindParam(':qty', $quantity);
            $wasteItemStmt->bindParam(':cost', $cost);

            if ($wasteItemStmt->execute()) {

                // // Insert or update consumption item
                // $clsItemSql = "INSERT INTO `consumptionitem` (consumption_id, type_id, cuisine_id, category_id, product_id, qty, used_qty)
                //                 VALUES (:ci, :ti, :cui, :cai, :pri, :qi, :ui)
                //                 ON DUPLICATE KEY UPDATE qty = VALUES(qty), used_qty = VALUES(used_qty)";
                // $clsItemStmt = $pdo->prepare($clsItemSql);
                // $clsItemStmt->bindParam(':ci', $oid);
                // $clsItemStmt->bindParam(':ti', $typeID);
                // $clsItemStmt->bindParam(':cui', $cuisineID);
                // $clsItemStmt->bindParam(':cai', $categoryID);
                // $clsItemStmt->bindParam(':pri', $productID);
                // $clsItemStmt->bindParam(':qi', $quantity);
                // $clsItemStmt->bindParam(':ui', $usedQty);

                // if (!$clsItemStmt->execute()) {
                //     throw new Exception('Error inserting or updating consumption item');
                // }

                // GEt Stock id
                $sidSql = "SELECT id FROM `stock` WHERE branchid = $branch";
                $sidStmt = $pdo->query($sidSql);
                $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
                $sid = $sidData["id"];

                // GEt Stock Qty
                $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
                $cqStmt = $pdo->query($cqSql);
                $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                $cq = $cqData["qty"];

                // Find Available Qty 
                if ($old_wq <= $quantity) {
                    $updatedQty = $quantity - $old_wq;
                    $finalQty = $cq - $updatedQty;
                } else {
                    $updatedQty = $old_wq - $quantity;
                    $finalQty = $cq + $updatedQty;
                }

                // Update or insert stock quantity
                $upStQ = "UPDATE `stockitem` SET qty = :finalQty WHERE product_id = :productID AND stock_id = :sid";
                $upStQStmt = $pdo->prepare($upStQ);
                $upStQStmt->bindParam(':finalQty', $finalQty);
                $upStQStmt->bindParam(':productID', $productID);
                $upStQStmt->bindParam(':sid', $sid);

                if (!$upStQStmt->execute()) {
                    throw new Exception('Error updating or inserting stock quantity');
                }
            }

        }

        $pdo->commit();
        header("Location: " . $u1 . urlencode('Wastage Successfully Updated'));
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: " . $u2 . urlencode($e->getMessage()));
        exit();
    }
}
?>