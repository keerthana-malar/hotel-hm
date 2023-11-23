<?php 
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 =  "outdoororders.php?succ=";
    $u2 = "create-outdoororder.php?err=";
    
    // User Data 
    $branch = $_POST['branch'];
    $orderdate = $_POST['orderDate'];
    $deliverydate = $_POST['deliveryDate'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $des = $_POST['des'];
   
    $orderName = $_POST['orderName'];
    $orderType = $_POST['orderType'];
  // Default product type to "food"
//   $productType = "food";

    // Validation
    if (empty($branch) || empty($orderdate) || empty($priority) || empty($status)) {
        echo "Error: All fields are required.";
        exit();
    }

    // Insert data into the order table
    $orderSql = "INSERT INTO `order` (branchid, orderdate, deliverydate, priority, status, description, order_name, ordertype) VALUES (:branchid, :orderdate, :deliverydate, :priority, :status, :description, :order_name, :ordertype)";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->bindParam(':branchid', $branch);
    $orderStmt->bindParam(':orderdate', $orderdate);
    $orderStmt->bindParam(':deliverydate', $deliverydate);
    $orderStmt->bindParam(':priority', $priority);
    $orderStmt->bindParam(':status', $status);
    $orderStmt->bindParam(':description', $des);
    $orderStmt->bindParam(':order_name', $orderName); 
    $orderStmt->bindParam(':ordertype', $orderType);

    if (!$orderStmt->execute()) {
        header("Location: " . $u2 . urlencode('Something went wrong. Please try again later.'));
        exit();
    }else{
        $orderID = $pdo->lastInsertId();
    }
    // Deduct ordered quantities from stock items
// for ($i = 0; $i < count($_POST['pro']); $i++) {
//     $productID = $_POST['pro'][$i];
//     $quantity = $_POST['qt'][$i];
    
//     // Update stock item quantity
//     $updateStockSql = "UPDATE `stockitem` SET qty = qty - :order_qty WHERE product_id = :product_id AND stock_id = :stock_id";
//     $updateStockStmt = $pdo->prepare($updateStockSql);
//     $updateStockStmt->bindParam(':order_qty', $quantity);
//     $updateStockStmt->bindParam(':product_id', $productID);
//     $updateStockStmt->bindParam(':stock_id', $stockID);
//     $updateStockStmt->execute();
// }

    // Insert order item details into the associated table
    for ($i = 0; $i < count($_POST['pro']); $i++) {
        $productID = $_POST['pro'][$i];
        $cuisineID = $_POST['cu'][$i];
        $unit = $_POST['unit'][$i];
        $type = $_POST['ty'][$i];
        $categoryID = $_POST['ca'][$i];
        $quantity = $_POST['qt'][$i];

        // $priorityy = $_POST['pr'][$i];

        // Check if the product already exists
        $existingItemSql = "SELECT * FROM `orderitem` WHERE order_id = :order_id AND productid = :productid";
        $existingItemStmt = $pdo->prepare($existingItemSql);
        $existingItemStmt->bindParam(':order_id', $orderID);
        $existingItemStmt->bindParam(':productid', $productID);
        $existingItemStmt->execute();

        if ($existingItemRow = $existingItemStmt->fetch(PDO::FETCH_ASSOC)) {
            // Product already exists, update the quantity
            $updatedQuantity = $existingItemRow['order_qty'] + $quantity;

            $updateItemSql = "UPDATE `orderitem` SET order_qty = :order_qty WHERE order_id = :order_id AND productid = :productid";
            $updateItemStmt = $pdo->prepare($updateItemSql);
            $updateItemStmt->bindParam(':order_qty', $updatedQuantity);
            $updateItemStmt->bindParam(':order_id', $orderID);
            $updateItemStmt->bindParam(':productid', $productID);
            $updateItemStmt->execute();
        } else {

        $orderItemSql = "INSERT INTO `orderitem` (order_id, productid, cuisineid, unit, typeid, order_qty, categoryid) VALUES (:order_id, :productid, :cuisineid, :unit, :typeid, :order_qty, :categoryid)";
        $orderItemStmt = $pdo->prepare($orderItemSql);
        $orderItemStmt->bindParam(':order_id', $orderID);
        $orderItemStmt->bindParam(':productid', $productID);
        $orderItemStmt->bindParam(':cuisineid', $cuisineID);
        $orderItemStmt->bindParam(':unit', $unit);

        $orderItemStmt->bindParam(':typeid', $type);

        $orderItemStmt->bindParam(':categoryid', $categoryID);
        $orderItemStmt->bindParam(':order_qty', $quantity);
        // $orderItemStmt->bindParam(':priority', $priorityy);

        $orderItemStmt->execute();
    }
    }
    header("Location: " . $u1 . urlencode('Order Successfully Created'));
    exit();
}
?>
