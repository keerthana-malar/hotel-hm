<?php 
    
require('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 =  "wastes.php?succ=";
    $u2 = "create-waste.php?err=";

    // User Data 
    $branch = $_POST['branch'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];

    // Duplicate product name check
    $checkDuplicateQuery = "SELECT COUNT(*) FROM `waste` WHERE id = :id";
    $checkStmt = $pdo->prepare($checkDuplicateQuery);
    $checkStmt->bindParam(':id', $id);
    $checkStmt->execute();
    $duplicateCount = $checkStmt->fetchColumn();

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

    // if ($duplicateCount > 0) {
    //     header("Location: " . $u2 . urlencode('Order already taken'));         
    //     exit();
    // }

    // Validation
    // if (empty($branch) || empty( $date ) || empty( $amount)) {
    //     header("Location: " . $u2 . urlencode('All Fields Are Required'));
    // exit();
    // }

    // Insert data into product table
    

    
    

    

    // Insert waste item details into the associated table
    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        $cuisineID = $_POST['cu'][$i];
        $typeID = $_POST['ty'][$i];
        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i]; 

   

        $wasteItemSql = "INSERT INTO `wasteitem` (waste_id, product_id, cuisine_id, type_id, category_id, qty) VALUES (:waste_id, :product_id, :cuisine_id, :type_id, :category_id, :qty)";
        $wasteItemStmt = $pdo->prepare($wasteItemSql);
        $wasteItemStmt->bindParam(':waste_id', $wasteID);
        $wasteItemStmt->bindParam(':product_id', $productID);
        $wasteItemStmt->bindParam(':cuisine_id', $cuisineID);
        $wasteItemStmt->bindParam(':type_id', $typeID);
        $wasteItemStmt->bindParam(':category_id', $categoryID);
        $wasteItemStmt->bindParam(':qty', $quantity);

        $wasteItemStmt->execute();
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
