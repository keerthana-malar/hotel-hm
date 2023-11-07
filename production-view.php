<?php

include('header.php');
include('menu.php');

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Get the order ID from the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $procId = $_GET['id'];

    // Fetch the order details from the database based on the ID
    $procSql = "SELECT * FROM `pro_chart` WHERE id = :id";
    $procStmt = $pdo->prepare($procSql);
    $procStmt->bindParam(':id', $procId);
    $procStmt->execute();
    $pcData = $procStmt->fetch(PDO::FETCH_ASSOC);

    if ($pcData) {
        // Display the order details
        echo "<h2 class='orderdetails'>Production Details</h2>";
        echo "<ul>";
        echo "<li class='orderdetails'>ID: " . $pcData['id'] . "</li>";
        echo "<li class='orderdetails'>ID: " . $pcData['date'] . "</li>";
        echo "</ul>";
 
        // Fetch and display the order items associated with the order
        echo "<h3>Production</h3>";
        echo "<table>";
        echo "<tr><th>Product</th><th>Category</th><th>Cuisine</th><th>Unit</th><th>Quantity</th></tr>";

        $orderItemSql = "SELECT * FROM `orderitem` WHERE order_id = :order_id";
        $orderItemstmt = $pdo->prepare($orderItemSql);
        if ($orderItemstmt) {
            $orderItemstmt->bindParam(':order_id', $orderId);
            $orderItemstmt->execute();
            $orderItemData = $orderItemstmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($orderItemData as $item) {
                // Fetch category name
                $categorySql = "SELECT name FROM `category` WHERE id = :categoryid";
                $categoryStmt = $pdo->prepare($categorySql);
                $categoryStmt->bindParam(':categoryid', $item['categoryid']);
                $categoryStmt->execute();
                $categoryData = $categoryStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch cuisine name
                $cuisineSql = "SELECT name FROM `cuisine` WHERE id = :cuisineid";
                $cuisineStmt = $pdo->prepare($cuisineSql);
                $cuisineStmt->bindParam(':cuisineid', $item['cuisineid']);
                $cuisineStmt->execute();
                $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch product name
                $productSql = "SELECT name FROM `product` WHERE id = :productid";
                $productStmt = $pdo->prepare($productSql);
                $productStmt->bindParam(':productid', $item['productid']);
                $productStmt->execute();
                $productData = $productStmt->fetch(PDO::FETCH_ASSOC);
        
                echo "<tr>";
                echo "<td><div>{$productData['name']}</div></td>";
                echo "<td><div>{$categoryData['name']}</div></td>";
                echo "<td><div>{$cuisineData['name']}</div></td>";
                echo "<td><div>{$item['unit']}</td>";
                echo "<td><div>{$item['order_qty']}</td>";

                echo "</tr>";
            } 

            echo "</table>";

            // Add a Print button
            echo '<a href="generateorder-pdf.php?id=' . $orderId . '" target="_blank" class="btn btn-primary">print</a>';
        } else {
            echo "Failed to prepare the order item query.";
        }
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid order ID.";
}

include('footer.php');
?>
<script>
// JavaScript code for printing
document.getElementById("printButton").addEventListener("click", function() {
        // Open the PDF in a new tab for printing
        window.open('generateorder-pdf.php?id=<?php echo $orderId; ?>', '_blank');
    });</script>

<style>
    table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
}

table th, table td {
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

/* Style for the Print button */
#printButton {
    margin-top: 20px;
}
    </style>
<style media="print">
    /* Hide menu and other non-essential elements when printing */
    header, nav, footer, .menu {
        display: none;
    }
    @media print {
        /* Increase font size for printed page */
        body {
            font-size: 100px; /* Adjust the font size as needed */
        }
    }
    
</style>