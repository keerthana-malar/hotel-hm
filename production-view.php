<?php

include('header.php');
include('menu.php');

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Get the chart ID 
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $chartId = $_GET['id'];

    // Fetch  chart details 
    $chartSql = "SELECT * FROM `pro_chart` WHERE id = :id";
    $chartStmt = $pdo->prepare($chartSql);
    $chartStmt->bindParam(':id', $chartId);
    $chartStmt->execute();
    $chartData = $chartStmt->fetch(PDO::FETCH_ASSOC);

    if ($chartData) {
        
        // Chart Render
        echo "<h2 class='chartdetails'>Production Chart</h2>";
        echo "<ul>";
        echo "<li class='chartdetails'>ID: " . $chartData['id'] . "</li>";
        echo "<li class='chartdetails'>Date: " . $chartData['date'] . "</li>";
        echo "</ul>";
 
        // Fetch items
        echo "<h3>Production List</h3>";
        echo "<table>";
        echo "<tr><th>Product</th><th>Category</th><th>Cuisine</th><th>Qty</th></tr>";

        $chartItemSql = "SELECT * FROM `pro_chart_item` WHERE chart_id = :chart_id";
        $chartItemstmt = $pdo->prepare($chartItemSql);
        if ($chartItemstmt) {
            $chartItemstmt->bindParam(':chart_id', $chartId);
            $chartItemstmt->execute();
            $chartItemData = $chartItemstmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($chartItemData as $item) {
                // Fetch category name
                $categorySql = "SELECT name FROM `category` WHERE id = :categoryid";
                $categoryStmt = $pdo->prepare($categorySql);
                $categoryStmt->bindParam(':categoryid', $item['category_id']);
                $categoryStmt->execute();
                $categoryData = $categoryStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch type name
                $typeSql = "SELECT name FROM `type` WHERE id = :typeid";
                $typeStmt = $pdo->prepare($typeSql);
                $typeStmt->bindParam(':typeid', $item['type_id']);
                $typeStmt->execute();
                $typeData = $typeStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch cuisine name
                $cuisineSql = "SELECT name FROM `cuisine` WHERE id = :cuisineid";
                $cuisineStmt = $pdo->prepare($cuisineSql);
                $cuisineStmt->bindParam(':cuisineid', $item['cuisine_id']);
                $cuisineStmt->execute();
                $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);
        
                // Fetch product name
                $productSql = "SELECT name FROM `product` WHERE id = :productid";
                $productStmt = $pdo->prepare($productSql);
                $productStmt->bindParam(':productid', $item['product_id']);
                $productStmt->execute();
                $productData = $productStmt->fetch(PDO::FETCH_ASSOC);
        
                echo "<tr>";
                echo "<td><div>{$productData['name']}</div></td>";
                echo "<td><div>{$categoryData['name']}</div></td>";
                echo "<td><div>{$cuisineData['name']}</div></td>";
                echo "<td><div>{$item['qty']}</td>";

                echo "</tr>";
            } 

            echo "</table>";

            // Add a Print button
            echo '<a href="generate-production-pdf.php?id=' . $chartId . '" target="_blank" class="btn btn-primary">print</a>';
        } else {
            echo "Failed to prepare the chart item query.";
        }
    } else {
        echo "chart not found.";
    }
} else {
    echo "Invalid chart ID.";
}

include('footer.php');
?>
<script>
// JavaScript code for printing
document.getElementById("printButton").addEventListener("click", function() {
        // Open the PDF in a new tab for printing
        window.open('generatechart-pdf.php?id=<?php echo $chartId; ?>', '_blank');
    });</script>

<style>
    table {
    bchart-collapse: collapse;
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