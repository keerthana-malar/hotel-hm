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

    // Add company details with enhanced styling
    $companyDetails = '
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="background-color:#FEDD00; padding: 10px; text-align: center; font-size: 18px; font-weight: bold;" colspan="2">Magizham</td>
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
        $branchSql = "SELECT name FROM `branch` WHERE id = :branchid";
        $branchStmt = $pdo->prepare($branchSql);
        $branchStmt->bindParam(':branchid', $orderData['branchid']);
        $branchStmt->execute();
        $branchData = $branchStmt->fetch(PDO::FETCH_ASSOC);
        // Output the HTML content to the PDF
        $pdf->writeHTML('<h2>Order Details</h2>');

        // Create an Excel-style table for order details
        $html = '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#ECECEC"><th>ID</th><th>Order Name</th><th>Branch</th><th>Order Date</th><th>Delivery Date</th><th>Priority</th></tr>';
        $html .= '<tr>';
        $html .= '<td>' . $orderData['id'] . '</td>';
        $html .= '<td>' . $orderData['order_name'] . '</td>';
        $html .= '<td>' . $branchData['name'] . '</td>';
        $html .= '<td>' . $orderData['orderdate'] . '</td>';
        $html .= '<td>' . $orderData['deliverydate'] . '</td>';
        $html .= '<td>' . $orderData['priority'] . '</td>';

        $html .= '</tr>';
        $html .= '</table>';
        // Fetch and display the order items associated with the order, sorted by cuisine
        $html .= '<h3>Ordered Products</h3>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#ECECEC"><th>Category</th><th>Cuisine</th><th>Product</th><th>Unit</th><th>Quantity</th></tr>';

        // Modify the SQL query to join the necessary tables and sort by cuisine
        $orderItemSql = "SELECT oi.order_qty, c.name AS category_name, cu.name AS cuisine_name, p.name AS product_name, p.unit AS product_unit 
    FROM `orderitem` AS oi
    INNER JOIN `category` AS c ON oi.categoryid = c.id
    INNER JOIN `cuisine` AS cu ON oi.cuisineid = cu.id
    INNER JOIN `product` AS p ON oi.productid = p.id
    WHERE oi.order_id = :order_id
    ORDER BY cu.name"; // Sort by cuisine name

        $orderItemstmt = $pdo->prepare($orderItemSql);
        $orderItemstmt->bindParam(':order_id', $orderId);
        $orderItemstmt->execute();
        $orderItemData = $orderItemstmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orderItemData as $item) {
            $html .= '<tr>';
            $html .= '<td>' . $item['category_name'] . '</td>';
            $html .= '<td>' . $item['cuisine_name'] . '</td>';
            $html .= '<td>' . $item['product_name'] . '</td>';
            $html .= '<td>' . $item['product_unit'] . '</td>';
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