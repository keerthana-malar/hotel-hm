<?php
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "consumptions.php?succ=";
    $u2 = "create-consumption.php?err=";

    // User Data 
    $branch = $_POST['branch'];
    $date = $_POST['date'];

    // Get consumption data 
    $sql_check = "SELECT COUNT(*) FROM `consumption` WHERE branchid = :branchid AND date_created = :date_created";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':branchid', $branch);
    $stmt_check->bindParam(':date_created', $date);
    $stmt_check->execute();

    if ($stmt_check->fetchColumn() == 0) { 
        // Insert data into stock table
        $sql = "INSERT INTO `consumption` (branchid, date_created) VALUES (:branchid, :date_created)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':branchid', $branch);
        $stmt->bindParam(':date_created', $date);

        if (!$stmt->execute()) {
            header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
            exit();
        } else {
            $consumptionID = $pdo->lastInsertId();
        }

        for ($i = 0; $i < count($_POST['pro']); $i++) {
            $productID = $_POST['pro'][$i];
            $cuisineID = $_POST['cu'][$i];
            $typeID = $_POST['ty'][$i];
            $categoryID = $_POST['ca'][$i];
            $quantity = $_POST['qt'][$i];
    
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
    
            // Condition check for qty 
            if ($quantity >= $eqty) {
                // delete consumption
                $delQ = "DELETE FROM `consumption` WHERE id = $consumptionID";
    
                $delQstmt = $pdo->query($delQ);
                if ($delQstmt->execute()) {
                    header("Location: " . $u2 . urlencode('Not valid qty'));
                    exit();
                }
            }
    
            // Find used quantity 
            $uqty = $eqty - $quantity;
    
    
    
            // Update quantity in stock 
            $suq = "UPDATE stockitem SET qty = $quantity WHERE product_id = $productID AND stock_id = $sid";
            $sus = $pdo->query($suq);
            $sus->execute();
    
    
            // var_dump($uqty);
            //     exit();
    
    
            $consumptionItemSql = "INSERT INTO `consumptionitem` (consumption_id, product_id, cuisine_id, type_id, category_id, qty, used_qty) VALUES (:consumption_id, :product_id, :cuisine_id, :type_id, :category_id, :qty, :uqty)";
            $consumptionItemStmt = $pdo->prepare($consumptionItemSql);
            $consumptionItemStmt->bindParam(':consumption_id', $consumptionID);
            $consumptionItemStmt->bindParam(':product_id', $productID);
            $consumptionItemStmt->bindParam(':cuisine_id', $cuisineID);
            $consumptionItemStmt->bindParam(':type_id', $typeID);
            $consumptionItemStmt->bindParam(':category_id', $categoryID);
            $consumptionItemStmt->bindParam(':qty', $quantity);
            $consumptionItemStmt->bindParam(':uqty', $uqty);
    
            $consumptionItemStmt->execute();
        }
    
        header("Location: " . $u1 . urlencode('Consumption Successfully Created'));
        exit();
    } else {
        // Duplicate record for the branch and date found
        header("Location: " . $u2 . urlencode('A record already exists for this branch on the specified date'));
            exit();
    }
    
}
?>