<?php

require('db.php');

if (isset($_POST)) {
    $u1 = "branchs.php?succ=";
    $u2 = "create-branch.php?err=";

    // User Data 
    $branch = $_POST['branch'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    // Duplicate username check
    $checkDuplicateQuery = "SELECT COUNT(*) FROM branch WHERE name = :name";
    $checkStmt = $pdo->prepare($checkDuplicateQuery);
    $checkStmt->bindParam(':name', $branch);
    $checkStmt->execute();
    $duplicateCount = $checkStmt->fetchColumn();
    if ($duplicateCount > 0) {
        header("Location: " . $u2 . urlencode('Branch already exists'));
        exit();
    }

    // Validation
    // if (empty($branch) || empty($address) || empty($phone)) {
    //     header("Location: " . $u2 . urlencode('All fields must be filled'));
    //     exit();
    // }

    $sql = "INSERT INTO branch (name, address, phone, status) VALUES ( :name, :address, :phone, :status)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $branch);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':status', $status);

    if (!$stmt->execute()) {
        header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
        exit();
    } else {
        $branchid = $pdo->lastInsertId();

        // Stock Account Creation
        $stSql = 'INSERT INTO stock (branchid) VALUES ( :branchid)';
        $stStmt = $pdo->prepare($stSql);
        $stStmt->bindParam(':branchid', $branchid);

        // Clone Product Data 
        if ($stStmt->execute()) {
            $stockid = $pdo->lastInsertId();

            $cloneSql = "INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id, unit)
            SELECT :stock_id, typeid, cuisineid, categoryid, id, unit FROM `product`";
            $cloneStmt = $pdo->prepare($cloneSql);
            $cloneStmt->bindParam(':stock_id', $stockid);

            if ($cloneStmt->execute()) {
                header("Location: " . $u1 . urlencode('Branch Successfully Created'));
                exit();
            } else {
                // Delete Branch and Stock
                $bdelSql = "DELETE FROM `branch` WHERE id = :branchid";
                $bdelstmt = $pdo->prepare($bdelSql);
                $bdelstmt->bindParam(":branchid", $branchid);
                $bdelstmt->execute();

                $sdelSql = "DELETE FROM `stock` WHERE id = :stockid";
                $sdelstmt = $pdo->prepare($sdelSql);
                $sdelstmt->bindParam(":stockid", $stockid);
                $sdelstmt->execute();

                header('' . $u2 . urlencode('Something Went Wrong'));
                exit();
            }
        }
    }
}

?>