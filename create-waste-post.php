<?php

require('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Redirection 
    $u1 = "wastes.php?succ=";
    $u2 = "create-waste.php?err=";

    // User Data 
    $branch = $_POST['branch'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];

    // Duplicate product name check
    // $checkDuplicateQuery = "SELECT COUNT(*) FROM `waste` WHERE id = :id";
    // $checkStmt = $pdo->prepare($checkDuplicateQuery);
    // $checkStmt->bindParam(':id', $id);
    // $checkStmt->execute();
    // $duplicateCount = $checkStmt->fetchColumn();

    // Duplicate date and branch check 
    $sql_check = "SELECT COUNT(*) FROM `waste` WHERE branchid = :branchid AND date = :date";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':branchid', $branch);
    $stmt_check->bindParam(':date', $date);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() == 0) {
        // No duplicate record found, proceed with the insert
        $sql = "INSERT INTO `waste` (branchid, date, waste_amount) VALUES (:branchid, :date, :waste_amount)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':branchid', $branch);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':waste_amount', $amount);

        if (!$stmt->execute()) {
            header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
            exit();
        } else {
            $wasteID = $pdo->lastInsertId();
        }

        // GEt Stock id
        $sidSql = "SELECT id FROM `stock` WHERE branchid = $branch";
        $sidStmt = $pdo->query($sidSql);
        $sidData = $sidStmt->fetch(PDO::FETCH_ASSOC);
        $sid = $sidData["id"];

        // Create Closing Stock
        $dateNow = date("d-m-Y");
        $auto = 1;

        // Create Closing Stock Acc 
        $clsSql = "INSERT INTO `consumption` (date_created, branchid, is_auto) VALUES (:dc, :bi, :au)";
        $clsStmt = $pdo->prepare($clsSql);
        $clsStmt->bindParam(':dc', $dateNow);
        $clsStmt->bindParam(':bi', $branch);
        $clsStmt->bindParam(':au', $auto);
        if ($clsStmt->execute()) {
            $clsId = $pdo->lastInsertId();
        }


        // Insert waste item details into the associated table
        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            $cuisineID = $_POST['cu'][$i];
            $typeID = $_POST['ty'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
            $cost = $_POST['cost'][$i];
            $stQtyCur = "0";

            $wasteItemSql = "INSERT INTO `wasteitem` (waste_id, product_id, cuisine_id, type_id, category_id, qty, cost) VALUES (:waste_id, :product_id, :cuisine_id, :type_id, :category_id, :qty, :cost)";
            $wasteItemStmt = $pdo->prepare($wasteItemSql);
            $wasteItemStmt->bindParam(':waste_id', $wasteID);
            $wasteItemStmt->bindParam(':product_id', $productID);
            $wasteItemStmt->bindParam(':cuisine_id', $cuisineID);
            $wasteItemStmt->bindParam(':type_id', $typeID);
            $wasteItemStmt->bindParam(':category_id', $categoryID);
            $wasteItemStmt->bindParam(':qty', $quantity);
            $wasteItemStmt->bindParam(':cost', $cost);

            if ($wasteItemStmt->execute()) {
                if ($typeID == '1') {
                    // GEt Stock Qty
                    $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
                    $cqStmt = $pdo->query($cqSql);
                    $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                    $cq = $cqData["qty"];

                    // Find Used Qty 
                    $usedQty = $cq - $quantity;

                    // $consumption Item Add 
                    $clsItemSql = "INSERT INTO `consumptionitem` (consumption_id, type_id, cuisine_id, category_id, product_id, qty, used_qty) VALUES (:ci, :ti, :cui, :cai, :pri, :qi, :ui)";
                    $clsItemStmt = $pdo->prepare($clsItemSql);
                    $clsItemStmt->bindParam(':ci', $clsId);
                    $clsItemStmt->bindParam(':ti', $typeID);
                    $clsItemStmt->bindParam(':cui', $cuisineID);
                    $clsItemStmt->bindParam(':cai', $categoryID);
                    $clsItemStmt->bindParam(':pri', $productID);
                    $clsItemStmt->bindParam(':qi', $stQtyCur);
                    $clsItemStmt->bindParam(':ui', $usedQty);

                    if ($clsItemStmt->execute()) {
                        $upStQ = "UPDATE `stockitem` SET qty = '0' WHERE product_id = $productID";
                        $upStQStmt = $pdo->prepare($upStQ);
                        $upStQStmt->execute();
                    }
                }else{
                    // GEt Stock Qty
                    $cqSql = "SELECT qty FROM `stockitem` WHERE stock_id = $sid AND product_id = $productID";
                    $cqStmt = $pdo->query($cqSql);
                    $cqData = $cqStmt->fetch(PDO::FETCH_ASSOC);
                    $cq = $cqData["qty"];

                    // Find Used Qty 
                    $AvlQty = $cq - $quantity;

                    // $consumption Item Add 
                    $clsItemSql = "INSERT INTO `consumptionitem` (consumption_id, type_id, cuisine_id, category_id, product_id, qty, used_qty) VALUES (:ci, :ti, :cui, :cai, :pri, :qi, :ui)";
                    $clsItemStmt = $pdo->prepare($clsItemSql);
                    $clsItemStmt->bindParam(':ci', $clsId);
                    $clsItemStmt->bindParam(':ti', $typeID);
                    $clsItemStmt->bindParam(':cui', $cuisineID);
                    $clsItemStmt->bindParam(':cai', $categoryID);
                    $clsItemStmt->bindParam(':pri', $productID);
                    $clsItemStmt->bindParam(':qi', $AvlQty);
                    $clsItemStmt->bindParam(':ui', $stQtyCur);

                    

                    if ($clsItemStmt->execute()) {
                        $upStQ = "UPDATE `stockitem` SET qty = $AvlQty WHERE product_id = $productID";
                        $upStQStmt = $pdo->prepare($upStQ);
                        $upStQStmt->execute();
                    }
                }
            }

        }
        header("Location: " . $u1 . urlencode('Waste Successfully Created'));
        exit();
    } else {
        /// Duplicate record for the branch and date found
        header("Location: " . $u2 . urlencode('A record already exists for this branch on the specified date'));
        exit();
    }
}
?>