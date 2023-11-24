<?php
require 'vendor/autoload.php';
require 'vendor/tecnickcom/tcpdf/tcpdf.php';
require 'db.php';

// Check if the order ID is provided in the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $wasteID = $_GET['id'];

    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Magizham');
    // $pdf->SetAuthor('Your Name');
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
    $orderSql = "SELECT * FROM `waste` WHERE id = :id";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->bindParam(':id', $wasteID);
    $orderStmt->execute();
    $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if ($orderData) {
        $branchSql = "SELECT name FROM `branch` WHERE id = :branchid";
        $branchStmt = $pdo->prepare($branchSql);
        $branchStmt->bindParam(':branchid', $orderData['branchid']);
        $branchStmt->execute();
        $branchData = $branchStmt->fetch(PDO::FETCH_ASSOC);
        // Output the HTML content to the PDF
        $pdf->writeHTML('<h2>Waste Details</h2>');

        // Create an Excel-style table for order details
        $html = '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#ECECEC"><th>ID</th><th>Branch</th><th>Date</th><th>Total Waste Cost</th></tr>';
        $html .= '<tr>';
        $html .= '<td>' . $orderData['id'] . '</td>';
        // $html .= '<td>' . $orderData['order_name'] . '</td>';
        $html .= '<td>' . $branchData['name'] . '</td>';
        $html .= '<td>' . $orderData['date'] . '</td>';
        // $html .= '<td>' . $orderData['deliverydate'] . '</td>';
        $html .= '<td>' . $orderData['waste_amount'] . '</td>';

        $html .= '</tr>';
        $html .= '</table>';
        // Fetch and display the order items associated with the order, sorted by cuisine
        $html .= '<h3>Wasted Items</h3>';
        $html .= '<table border="1" cellpadding="4" cellspacing="0">';
        $html .= '<tr bgcolor="#ECECEC"><th>Product</th><th>Unit</th><th>Waste Qty</th><th>Waste Cost</th></tr>';

    //     // Modify the SQL query to join the necessary tables and sort by cuisine
    //     $orderItemSql = "SELECT p.name AS product_name, p.unit AS product_unit, cost, qty 
    // FROM `wasteitem` AS oi
    // -- INNER JOIN `category` AS c ON oi.categoryid = c.id
    // -- INNER JOIN `cuisine` AS cu ON oi.cuisineid = cu.id
    // INNER JOIN `product` AS p ON oi.productid = p.id
    // WHERE waste_id = :order_id
    // ORDER BY cu.name"; // Sort by cuisine name

    //     $orderItemstmt = $pdo->prepare($orderItemSql);
    //     $orderItemstmt->bindParam(':order_id', $wasteID);
    //     $orderItemstmt->execute();
    //     $orderItemData = $orderItemstmt->fetchAll(PDO::FETCH_ASSOC);

    $orderItemSql = "SELECT * FROM wasteitem WHERE waste_id = :waste_id";
    $orderItemSt = $pdo->prepare($orderItemSql);
    $orderItemSt->bindParam(':waste_id', $wasteID);
    $orderItemSt->execute();
    $orderItemData = $orderItemSt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orderItemData as $item) {
            $orderItemSqll = "SELECT name, unit FROM `product` WHERE id = :id";
            $orderItemStt = $pdo->prepare($orderItemSqll);
            $orderItemStt->bindParam(':id', $item['product_id']);
            $orderItemStt->execute();
            $ItemData = $orderItemStt->fetch(PDO::FETCH_ASSOC);

            $html .= '<tr>';
            // $html .= '<td>' . $item['category_name'] . '</td>';
            // $html .= '<td>' . $item['cuisine_name'] . '</td>';
            $html .= '<td>' . $ItemData['name'] . '</td>';
            $html .= '<td>' . $ItemData['unit'] . '</td>';
            $html .= '<td>' . $item['qty'] . '</td>';
            $html .= '<td>' . $item['cost'] . '</td>';

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
        echo "Waste not found.";
    }
} else {
    echo "Invalid Waste ID.";
}
?>