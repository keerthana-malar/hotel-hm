<?php
require 'vendor/autoload.php';
require 'vendor/tecnickcom/tcpdf/tcpdf.php';
require 'db.php';

// Check if the order ID is provided in the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $orderId = $_GET['id'];

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

    // Fetch and display the order details from the database
    $orderSql = "SELECT * FROM `order` WHERE id = :id";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->bindParam(':id', $orderId);
    $orderStmt->execute();
    $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if ($orderData) {
        // Output the HTML content to the PDF
        $pdf->writeHTML('<h2>Order Details</h2>');

        // Create an Excel-style table
        $html = '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#CCCCCC"><th>ID</th><th>Order Name</th></tr>';
        $html .= '<tr>';
        $html .= '<td>' . $orderData['id'] . '</td>';
        $html .= '<td>' . $orderData['order_name'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Fetch and display the order items associated with the order
        $html .= '<h3>Ordered Products</h3>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#CCCCCC"><th>Category</th><th>Product</th><th>Quantity</th></tr>';

        $orderItemSql = "SELECT * FROM `orderitem` WHERE order_id = :order_id";
        $orderItemstmt = $pdo->prepare($orderItemSql);
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
//   // Fetch cuisine name
//   $cuisineSql = "SELECT name FROM `cuisine` WHERE id = :cuisineid";
//   $cuisineStmt = $pdo->prepare($cuisineSql);
//   $cuisineStmt->bindParam(':cuisineid', $item['cuisineid']);
//   $cuisineStmt->execute();
//   $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);
            // Fetch product name
            $productSql = "SELECT name FROM `product` WHERE id = :productid";
            $productStmt = $pdo->prepare($productSql);
            $productStmt->bindParam(':productid', $item['productid']);
            $productStmt->execute();
            $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

            $html .= '<tr>';
            $html .= '<td>' . $categoryData['name'] . '</td>';
            $html .= '<td>' . $productData['name'] . '</td>';
            // echo "<td><div>{$cuisineData['name']}</div></td>";
            $html .= '<td>' . $item['order_qty'] . '</td>';

            $html .= '</tr>';
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