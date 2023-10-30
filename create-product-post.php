<?php

require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "products.php?succ=";
    $u2 = "create-product.php?err=";
    $u3 = "foodcatalog.php?succ=";

    // User Data 
    $productname = $_POST['product'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    $typeid = $_POST['type'];
    $categoryid = $_POST['category'];
    $cuisineid = $_POST['cuisine'];
    $status = $_POST['status'];

    // image uploads
    $img1 = $_FILES["img1"];
    $img1FileName = $img1["name"];
    $img1TmpName = $img1["tmp_name"];

    $uploadPath = "uploads/";

    if (!empty($img1FileName)) {
        // Move the uploaded image to the specified directory
        if (move_uploaded_file($img1TmpName, $uploadPath . $img1FileName)) {
            // Image uploaded successfully
        } else {
            // Handle image upload error
            header("Location: " . $u2 . urlencode('Image upload failed'));
            exit();
        }
    } else {
        // Handle case where no image was uploaded
        header("Location: " . $u2 . urlencode('Please upload an image'));
        exit();
    }

    // Duplicate product name check
    $checkDuplicateQuery = "SELECT COUNT(*) FROM product WHERE name = :name";
    $checkStmt = $pdo->prepare($checkDuplicateQuery);
    $checkStmt->bindParam(':name', $productname);
    $checkStmt->execute();
    $duplicateCount = $checkStmt->fetchColumn();

    if ($duplicateCount > 0) {
        header("Location: " . $u2 . urlencode('Product already taken'));
        exit();
    }

    // Insert data into product table
    $sql = "INSERT INTO product (name, unit, price, typeid, categoryid, cuisineid, status,  img ) VALUES (:name, :unit,  :price, :typeid, :categoryid, :cuisineid, :status, :img )";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $productname);
    $stmt->bindParam(':unit', $unit);
    // $stmt->bindParam(':stock_qty', $stock_qty);

    $stmt->bindParam(':price', $price);

    $stmt->bindParam(':typeid', $typeid);

    $stmt->bindParam(':categoryid', $categoryid);
    $stmt->bindParam(':cuisineid', $cuisineid);

    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':img', $img1FileName);

    if ($stmt->execute()) {

        $proId = $pdo->lastInsertId();

        // Add Product in Stock
        $stAccSql = "SELECT id FROM `stock`";
        $stAccStmt = $pdo->query($stAccSql);
        $stAccData = $stAccStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stAccData as $row) {
            $stockId = $row['id'];
            $stockSql = "INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id)
            VALUES (:stock_id, :type_id, :cuisine_id, :category_id, :product_id)";
            $itemStmt = $pdo->prepare($stockSql);
            $itemStmt->bindParam(':stock_id', $stockId);
            $itemStmt->bindParam(':type_id', $typeid);
            $itemStmt->bindParam(':cuisine_id', $cuisineid);
            $itemStmt->bindParam(':category_id', $categoryid);
            $itemStmt->bindParam(':product_id', $proId);

            if (!$itemStmt->execute()) {
                $pdelSql = "DELETE FROM `product` WHERE id = :proid";
                $pdelstmt = $pdo->prepare($pdelSql);
                $pdelstmt->bindParam(":proid", $proId);
                $pdelstmt->execute();
                header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
                exit();
            }
        }
        if ($typeid == 1) {
            header("Location: " . $u3 . urlencode('Product Successfully Created'));
            exit();
        } else {
            header("Location: " . $u1 . urlencode('Product Successfully Created'));
            exit();
        }
    } else {
        header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
        exit();
    }
}
?>