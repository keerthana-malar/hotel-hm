<?php 
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u1 = "consumptions.php?succ=";
    $u2 = "create-consumption.php?err=";

    // User Data 
    $branch = $_POST['branch'];
    $date = $_POST['date'];

    // Duplicate product name check
    // ...

    
    // Validation
    if (empty($branch) || empty($date)) {
        echo "Error: All fields are required.";
        exit();
    }

    // Insert data into stock table
    $sql = "INSERT INTO `consumption` (branchid, date_created) VALUES (:branchid, :date_created)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':branchid', $branch);
    $stmt->bindParam(':date_created', $date);

    if (!$stmt->execute()) {
        header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
        exit();
    }else{
        $consumptionID = $pdo->lastInsertId();
    }


    



    // Fetch branch and date from the POST request
    $branch = isset($_POST['branch']) ? $_POST['branch'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;

    if ($branch !== null && $date !== null) {
        // Check for duplicate date
        $cdate = date('Y-m-d');
        
        if ($cdate == $date) {
            // Duplicate date found
            echo "Per day one record only create for this branch.";
        } else {
            // No duplicate date, proceed with the insert
            $sql_check = "SELECT COUNT(*) FROM `consumption` WHERE branchid = :branchid AND date_created = :date_created";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':branchid', $branch);
            $stmt_check->bindParam(':date_created', $date);
            $stmt_check->execute();

            if ($stmt_check->fetchColumn() == 0) {
                $sql = "INSERT INTO `consumption` (branchid, date_created) VALUES (:branchid, :date_created)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':branchid', $branch);   
                $stmt->bindParam(':date_created', $date);

                if ($stmt->execute()) {
                    header('Location: ' . $u2 . urlencode('Record inserted successfully.'));
                    exit();
                } else {
                    // Handle the case when the SQL statement fails to execute
                    echo "Error: " . $stmt->errorInfo()[2];
                }
            } else {
                // Duplicate record for the branch and date found
                echo "A record already exists for this branch on the specified date.";
            }
        }
    } else {
        // Handle the case when the branch or date is missing in the POST request
        echo "Branch and/or date values are missing in the POST request.";
    }



    

    // Insert stock item details into the associated table
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
        if($quantity >= $eqty ){
            // delete consumption
            $delQ = "DELETE FROM `consumption` WHERE id = $consumptionID";

            $delQstmt = $pdo->query($delQ);
            if($delQstmt->execute()){
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
}
?>
