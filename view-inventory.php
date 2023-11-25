<?php
include('header.php');
include('menu.php');
?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }

    table th,
    table td {
        padding: 10px;
        text-align: left;
    }

    table th {
        background-color: #f2f2f2;
    }

    .orderdetails {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    input{
        background-color: #f5f5f5;
    }
    /* Style for the Print button */
</style>

<?php
// Get the stock ID from the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $stockID = $_GET['id'];

    // Fetch the stock details from the database based on the ID
    $stockSql = "SELECT * FROM `stock` WHERE id = :id";
    $stockStmt = $pdo->prepare($stockSql);
    $stockStmt->bindParam(':id', $stockID);
    $stockStmt->execute();
    $stockData = $stockStmt->fetch(PDO::FETCH_ASSOC);

    if ($stockData) {
        // Display the stock details
        echo "<h2 class='stock-details'>Stock Details</h2>";
        echo "<ul>";
        echo "<li>ID: " . $stockData['id'] . "</li>";
        echo "<hr>";

        // Add other stock details here...
        echo "</ul>";

        // Fetch and display the stock item details associated with the stock
        echo "<h3>Stock Items</h3>";

        $stockItemSql = "SELECT * FROM `stockitem` WHERE stock_id = :stock_id AND qty > 0";
        $stockItemStmt = $pdo->prepare($stockItemSql);

        if ($stockItemStmt) {
            $stockItemStmt->bindParam(':stock_id', $stockID);
            $stockItemStmt->execute();
            $stockItemData = $stockItemStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stockItemData) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Product</th><th>Unit</th><th>Type</th><th>Available Qty</th></tr></thead>";
                echo "<tbody>";

                foreach ($stockItemData as $item) {
                    // Fetch category, type, cuisine, and product names (similar to your existing code)

                     // Fetch category, type, cuisine, and product names
                // Fetch category name
                $categorySql = "SELECT name FROM `category` WHERE id = :category_id";
                $categoryStmt = $pdo->prepare($categorySql);
                $categoryStmt->bindParam(':category_id', $item['category_id']);
                $categoryStmt->execute();
                $categoryData = $categoryStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch type name
                $typeSql = "SELECT name FROM `type` WHERE id = :type_id";
                $typeStmt = $pdo->prepare($typeSql);
                $typeStmt->bindParam(':type_id', $item['type_id']);
                $typeStmt->execute();
                $typeData = $typeStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch cuisine name
                $cuisineSql = "SELECT name FROM `cuisine` WHERE id = :cuisine_id";
                $cuisineStmt = $pdo->prepare($cuisineSql);
                $cuisineStmt->bindParam(':cuisine_id', $item['cuisine_id']);
                $cuisineStmt->execute();
                $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);

                // Fetch product name
                $productSql = "SELECT name FROM `product` WHERE id = :product_id";
                $productStmt = $pdo->prepare($productSql);
                $productStmt->bindParam(':product_id', $item['product_id']);
                $productStmt->execute();
                $productData = $productStmt->fetch(PDO::FETCH_ASSOC);
                // Similar to how you did in the previous code

                    echo "<tr>";
                    echo "<td>{$productData['name']}</td>";
                    echo "<td>{$item['unit']}</td>";
                    echo "<td>{$typeData['name']}</td>";
                    echo "<td>{$item['qty']}</td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";

                // Add DataTables initialization script
                
            } else {
                echo '<p class="text-danger">No Products Available in Stock</p>';
            }
        } else {
            echo "Failed to prepare the stock item query.";
        }
    } else {
        echo "Stock not found.";
    }
} else {
    echo "Invalid stock ID.";
}

include('footer.php');
?>