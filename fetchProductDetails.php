<?php
require("db.php");

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Perform a database query to retrieve product details based on $productId
    // Replace 'your_database_table_name' with your actual table name
    $prosql = "SELECT categoryid, cuisineid, typeid, unit FROM `product` WHERE id = :productId";
    $prostmt = $pdo->prepare($prosql);
    if ($prostmt === false) {
        die('Failed to prepare the query.');
    }
    $prostmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    if ($prostmt->execute()) {
        $productDetails = $prostmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($productDetails);
        $catId = $productDetails['categoryid'];
        $cusId = $productDetails['cuisineid'];
        $typId = $productDetails['typeid'];
        $unit = $productDetails['unit'];
        // echo $catId;
    } else {
        $errorInfo = $prostmt->errorInfo();
        die('Query execution failed: ' . $errorInfo[2]);
    }

    $catsql = "SELECT name FROM `category` WHERE id = :catId";
    $catstmt = $pdo->prepare($catsql);
    if ($catstmt === false) {
        die('Failed to prepare the query.');
    }
    $catstmt->bindParam(':catId', $catId, PDO::PARAM_INT);
    if ($catstmt->execute()) {
        $catDetails = $catstmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($catDetails);
    } else {
        $errorInfo = $catstmt->errorInfo();
        die('Query execution failed: ' . $errorInfo[2]);
    }

    $cussql = "SELECT name FROM `cuisine` WHERE id = :cusId";
    $cusstmt = $pdo->prepare($cussql);
    if ($catstmt === false) {
        die('Failed to prepare the query.');
    }
    $cusstmt->bindParam(':cusId', $cusId, PDO::PARAM_INT);
    if ($cusstmt->execute()) {
        $cusDetails = $cusstmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($cusDetails);
    } else {
        $errorInfo = $cusstmt->errorInfo();
        die('Query execution failed: ' . $errorInfo[2]);
    }

    $typsql = "SELECT name FROM `type` WHERE id = :typId";
    $typstmt = $pdo->prepare($typsql);
    if ($typstmt === false) {
        die('Failed to prepare the query.');
    }
    $typstmt->bindParam(':typId', $typId, PDO::PARAM_INT);
    if ($typstmt->execute()) {
        $typDetails = $typstmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $errorInfo = $typstmt->errorInfo();
        die('Query execution failed: ' . $errorInfo[2]);
    }


    $prodata = [
        "catid"=> $productDetails['categoryid'],
        "catname"=> $catDetails['name'],
        "cusid"=> $productDetails['cuisineid'],
        "cusname"=> $cusDetails['name'],
        "typid"=> $productDetails['typeid'],
        "typname"=> $typDetails['name'],
        "unit" => $unit
    ];

    // Return the product details as JSON
    header('Content-Type: application/json');
    echo json_encode($prodata);
} else {
    // Handle any errors or invalid requests
    echo 'Invalid request';
}
?>