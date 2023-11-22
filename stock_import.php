<?php
require 'vendor/box/spout/src/Spout/Autoloader/autoload.php';
require 'db.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

function importProducts($file, $pdo)
{
    $reader = ReaderEntityFactory::createXLSXReader();

    try {
        $pdo->beginTransaction();

        $reader->open($file);

        // Prepare the SQL statement for insert
        $insertSql = "
            INSERT INTO product (name, unit, price, categoryid, cuisineid, status, typeid)
            VALUES (?, ?, ?, ?, 1, 'Active', 2)
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

                // Always perform an insert (create a new entry)
                $insertStmt->execute($data);

                // If the product already exists, update specific fields
                if ($existingProductId) {
                    $pdo->prepare("UPDATE product SET unit = ?, price = ?, categoryid = ?, cuisineid = 1?, status = 'Active', typeid = 2 WHERE name = ?")
                        ->execute([$data[2], $data[3], $data[4], $data[5], $data[6], $data[1]]);
                }
            }
        }

        // Commit the transaction
        $pdo->commit();
        $reader->close();

        return true;
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

// Check if the form is submitted and the "import_file" key is set in the $_FILES array
if (isset($_POST['submit_import']) && isset($_FILES['import_file'])) {
    // File upload path
    $uploadDir = 'path_to_your_upload_directory/'; // Set your desired upload directory path

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
