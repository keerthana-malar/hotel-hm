<?php
require 'vendor/box/spout/src/Spout/Autoloader/autoload.php';
require 'db.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

function updateStockItem($pdo, $stockID, $productID, $cuisineID, $typeID, $categoryID, $quantity, $unit)
{
    if ($productID) {
        // Product already exists, update stock item
        $stockSql = "UPDATE `stockitem` SET unit = :unit WHERE stock_id = :stock_id AND product_id = :product_id";
    } else {
        // Product is new, insert stock item
        $stockSql = "INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id, unit)
            VALUES (:stock_id, :type_id, :cuisine_id, :category_id, :product_id, :unit)";
    }

    $itemStmt = $pdo->prepare($stockSql);
    $itemStmt->bindParam(':stock_id', $stockID);
    $itemStmt->bindValue(':type_id', $typeID);
    $itemStmt->bindValue(':cuisine_id', $cuisineID);
    $itemStmt->bindValue(':category_id', $categoryID);
    $itemStmt->bindValue(':unit', $unit);
    $itemStmt->bindValue(':product_id', $productID);

    if (!$itemStmt->execute()) {
        // If adding/updating stock item fails, delete the product entry
        $pdelSql = "DELETE FROM `product` WHERE id = :proid";
        $pdelstmt = $pdo->prepare($pdelSql);
        $pdelstmt->bindParam(":proid", $productID);
        $pdelstmt->execute();
        return false;
    }

    return true;
}
function importProducts($file, $pdo)
{
    $reader = ReaderEntityFactory::createXLSXReader();

    try {
        $pdo->beginTransaction();

        $reader->open($file);

        // Prepare the SQL statement for insert
        $insertSql = "
            INSERT INTO product (name, unit, price, categoryid, cuisineid, status, typeid)
            VALUES (?, ?, ?, ?, '1', 'Active', 2)
            ON DUPLICATE KEY UPDATE
                unit = VALUES(unit),
                price = VALUES(price),
                categoryid = VALUES(categoryid),
                cuisineid = VALUES(cuisineid),
                status = VALUES(status),
                typeid = VALUES(typeid)
        ";
        $insertStmt = $pdo->prepare($insertSql);

        // Variable to skip the first row
        $firstRowSkipped = false;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // Skip the first row (header row)
                if (!$firstRowSkipped) {
                    $firstRowSkipped = true;
                    continue;
                }

                $data = $row->toArray();

                // Check if the product already exists by name
                $existingProduct = $pdo->prepare("SELECT id FROM product WHERE name = ?");
                $existingProduct->execute([$data[1]]);
                $existingProductId = $existingProduct->fetchColumn();
// Prepare the SQL statement for insert or update
$insertSql = "
INSERT INTO product (name, unit, price, categoryid, cuisineid, status, typeid)
VALUES (?, ?, ?, ?, '1', 'Active', 2)
ON DUPLICATE KEY UPDATE
    unit = VALUES(unit),
    price = VALUES(price),
    categoryid = VALUES(categoryid),
    cuisineid = VALUES(cuisineid),
    status = VALUES(status),
    typeid = VALUES(typeid)
";
                // Always perform an insert (create a new entry)
                $insertStmt->execute($data);

                // Get the last inserted product ID or the existing product ID
                $proId = $existingProductId ?: $pdo->lastInsertId();
                // Add Product in Stock
                $stAccSql = "SELECT id FROM `stock`";
                $stAccStmt = $pdo->query($stAccSql);
                $stAccData = $stAccStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($stAccData as $stRow) {
                    $stockId = $stRow['id'];

                    // Fetch the product data after import
                    $productSql = "SELECT * FROM product WHERE id=$proId";
                    $productData = $pdo->query($productSql);

                    foreach ($productData as $productRow) {
                        $productID = $productRow['id'];
                        $cuisineID = $productRow['cuisineid'];
                        $categoryID = $productRow['categoryid'];
                        $unit = $productRow['unit'];

                        // Prepare the SQL statement for insert or update
                        $stockSql = "
                            INSERT INTO `stockitem` (stock_id, type_id, cuisine_id, category_id, product_id, unit)
                            VALUES (:stock_id, 2, :cuisine_id, :category_id, :product_id, :unit)
                            ON DUPLICATE KEY UPDATE unit = VALUES(unit)
                        ";
                        $itemStmt = $pdo->prepare($stockSql);
                        $itemStmt->bindParam(':stock_id', $stockId);
                        $itemStmt->bindParam(':cuisine_id', $cuisineID);
                        $itemStmt->bindParam(':category_id', $categoryID);
                        $itemStmt->bindParam(':unit', $unit);
                        $itemStmt->bindParam(':product_id', $productID);

                        if (!$itemStmt->execute()) {
                            // If adding/updating stock item fails, delete the product entry
                            $pdelSql = "DELETE FROM `product` WHERE id = :proid";
                            $pdelstmt = $pdo->prepare($pdelSql);
                            $pdelstmt->bindParam(":proid", $productID);
                            $pdelstmt->execute();
                            exit();
                        }
                    }
                }
            }
        }


        // Commit the transaction
        $pdo->commit();
        $reader->close();

        // Set the success message in the session
        // $_SESSION['success_message'] = 'File has been uploaded and processed successfully.';
        // Redirect to foodcatalog.php with the success message in the URL
        $u1 = "stockcatalog.php?succ=";
        $u2 = "stockcatalog.php?err=";
        header("Location: " . $u1 . urlencode('File has been uploaded and processed successfully.'));
        exit();
        // return true;
    } catch (PDOException $e) {
        // Log the error
        error_log('SQL Error: ' . $e->getMessage());

        // Rollback the transaction in case of an error
        $pdo->rollBack();

        // Display the error (for development purposes)
        echo 'SQL Error: ' . $e->getMessage();
        return false;
    }
}

// ... (previous code)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_import']) && isset($_FILES['import_file'])) {
    // File upload path
    $uploadDir = 'import-stockproducts-excel/'; // Set your desired upload directory path

    // Create the directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['import_file']['name']);

    // Check if the file has a valid extension
    $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
    $allowedExtensions = array('xlsx');
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo 'Invalid file format. Only Excel files (xlsx) are allowed.';
        exit;
    }

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['import_file']['tmp_name'], $uploadFile)) {
        // Call the importProducts function with the file path
        if (importProducts($uploadFile, $pdo)) {
            echo 'File has been uploaded and processed successfully.';
        } else {
            echo 'Error processing the file.';
        }
    } else {
        echo 'Error uploading the file.';
    }
}
?>