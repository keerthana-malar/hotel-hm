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

    // Add company details with enhanced styling
    $companyDetails = '
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="background-color: yellow; padding: 10px; text-align: center; font-size: 18px; font-weight: bold;" colspan="2">Magizham</td>
        </tr>
    </table>';

    // Apply CSS styles to the whole table
    $companyDetails .= '<style>
        table { width: 100%; border-collapse: collapse; }
        td { border: 1px solid #E0E0E0; }
    </style>';
    $pdf->writeHTML($companyDetails);

    // Fetch chart details 
    $chartSql = "SELECT * FROM `pro_chart` WHERE id = :id";
    $chartStmt = $pdo->prepare($chartSql);
    $chartStmt->bindParam(':id', $chartId);
    $chartStmt->execute();
    $chartData = $chartStmt->fetch(PDO::FETCH_ASSOC);

    if ($chartData) {
        // Output the HTML content to the PDF
     // Output the HTML content to the PDF
$pdf->writeHTML('<div style="display: flex; justify-content: space-between;">');


        // Fetch and display the order items associated with the order
        $chartItemSql = "SELECT pci.qty, p.name AS product_name, p.unit, c.name AS category_name, cu.name AS cuisine_name
                        FROM `pro_chart_item` pci
                        JOIN `product` p ON pci.product_id = p.id
                        JOIN `category` c ON pci.category_id = c.id
                        JOIN `cuisine` cu ON pci.cuisine_id = cu.id
                        WHERE pci.chart_id = :chart_id";
        $chartItemstmt = $pdo->prepare($chartItemSql);

        if ($chartItemstmt) {
            $chartItemstmt->bindParam(':chart_id', $chartId);
            $chartItemstmt->execute();
            $chartItemData = $chartItemstmt->fetchAll(PDO::FETCH_ASSOC);

            $cuisineTables = [];

            // Group items by cuisine
            foreach ($chartItemData as $item) {
                $cuisineName = $item['cuisine_name'];

                if (!isset($cuisineTables[$cuisineName])) {
                    $cuisineTables[$cuisineName] = [];
                }

                $cuisineTables[$cuisineName][] = $item;
            }

            // Generate a table for each cuisine
            foreach ($cuisineTables as $cuisineName => $cuisineItems) {
                // Production Chart Column
$pdf->writeHTML('<div style="width: 48%;">');
$pdf->writeHTML('<h2 style="color: #336699;">Production Chart</h2>');

// Include the date in the PDF
$pdf->writeHTML('<p style="font-size: 14px; font-weight: bold; color: #555;">Date: ' . $chartData['date'] . '</p>');
                $html = '<h3>' . $cuisineName . ' </h3>';
                $html .= '<table border="1" cellpadding="4" cellspacing="0">';
                $html .= '<tr bgcolor="#CCCCCC"><th>Product</th><th>Category</th><th>Unit</th><th>Qty</th></tr>';

                foreach ($cuisineItems as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['product_name'] . '</td>';
                    $html .= '<td>' . $item['category_name'] . '</td>';
                    $html .= '<td>' . $item['unit'] . '</td>'; 
                    $html .= '<td>' . $item['qty'] . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }

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
