<?php
require 'vendor/autoload.php';
require 'vendor/tecnickcom/tcpdf/tcpdf.php';
require 'db.php';

// Get the chart ID 
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $chartId = $_GET['id'];

    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Magizham');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Order Details');
    $pdf->SetSubject('Order Details');
    $pdf->SetKeywords('TCPDF, PDF, Order Details');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);
    // // Add company logo
    // $logoImage = 'images/Magizham Logo.png'; // Change to the actual path and filename of your logo
    // $pdf->Image($logoImage, 10, 10, 40, 0, '', '', '', false, 300);
    // Add company details with enhanced styling
    $companyDetails = '
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="background-color: yellow; padding: 10px; text-align: center; font-size: 18px; font-weight: bold;" colspan="2">Magizham</td>
    </tr>
    <tr>
        <td style="background-color: #F5F5F5; padding: 10px; font-weight: bold;">Address</td>
        <td style="background-color: #F9F9F9; padding: 10px;">123 Main St, City, Country</td>
    </tr>
    <tr>
        <td style="background-color: #F5F5F5; padding: 10px; font-weight: bold;">Phone</td>
        <td style="background-color: #F9F9F9; padding: 10px;">+1 123-456-7890</td>
    </tr>
    <tr>
        <td style="background-color: #F5F5F5; padding: 10px; font-weight: bold;">Email</td>
        <td style="background-color: #F9F9F9; padding: 10px;">info@yourcompany.com</td>
    </tr>
</table>';

    // Apply CSS styles to the whole table
    $companyDetails .= '<style>
    table { width: 100%; border-collapse: collapse; }
    td { border: 1px solid #E0E0E0; }
</style>';
    $pdf->writeHTML($companyDetails);

    // Fetch  chart details 
    $chartSql = "SELECT * FROM `pro_chart` WHERE id = :id";
    $chartStmt = $pdo->prepare($chartSql);
    $chartStmt->bindParam(':id', $chartId);
    $chartStmt->execute();
    $chartData = $chartStmt->fetch(PDO::FETCH_ASSOC);

    if ($chartData) {
        // Output the HTML content to the PDF
        $pdf->writeHTML('<h2>Production Chart</h2>');

        // Create an Excel-style table
        $html = '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#CCCCCC"><th>ID</th><th>Order Name</th></tr>';
        $html .= '<tr>';
        $html .= '<td>' . $chartData['id'] . '</td>';
        $html .= '<td>' . $chartData['date'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Fetch and display the order items associated with the order
        $html .= '<h3>Production List</h3>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#CCCCCC"><th>Product</th><th>Category</th><th>Cuisine</th><th>Qty</th></tr>';

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

                //   // Fetch cuisine name
//   $cuisineSql = "SELECT name FROM `cuisine` WHERE id = :cuisineid";
//   $cuisineStmt = $pdo->prepare($cuisineSql);
//   $cuisineStmt->bindParam(':cuisineid', $item['cuisineid']);
//   $cuisineStmt->execute();
//   $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);
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
                $html .= '<tr>';
                $html .= '<td>' . $productData['name'] . '</td>';
                $html .= '<td>' . $categoryData['name'] . '</td>';
                $html .= '<td>' . $cuisineData['name'] . '</td>';

                // echo "<td><div>{$cuisineData['name']}</div></td>";
                $html .= '<td>' . $item['qty'] . '</td>';

                $html .= '</tr>';
            }
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Close the PDF document
        $pdfContent = $pdf->Output('', 'S'); // Capture the PDF content

        // End output buffering
        ob_end_clean();

        // Send the PDF content to the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="order_details.pdf"');
        echo $pdfContent;

        // Exit to prevent additional content from being appended
        exit;
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid order ID.";
}
?>