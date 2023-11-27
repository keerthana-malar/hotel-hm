<?php
require 'vendor/autoload.php';
require 'vendor/tecnickcom/tcpdf/tcpdf.php';
require 'db.php';

// Get the consumption ID 
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $consumptionId = $_GET['id'];

    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Magizham');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Consumption Details');
    $pdf->SetSubject('Consumption Details');
    $pdf->SetKeywords('TCPDF, PDF, Consumption Details');

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
        th, td { border: 1px solid #E0E0E0; font-family: helvetica; font-size: 12px; }
        th { background-color: #CCCCCC; }
    </style>';
    $pdf->writeHTML($companyDetails);

    // Fetch consumption details
    $consumptionSql = "SELECT * FROM `consumption` WHERE id = :id";
    $consumptionStmt = $pdo->prepare($consumptionSql);
    $consumptionStmt->bindParam(':id', $consumptionId);
    $consumptionStmt->execute();
    $consumptionData = $consumptionStmt->fetch(PDO::FETCH_ASSOC);

    if ($consumptionData) {
        // Output the HTML content to the PDF
        $pdf->writeHTML('<div style="display: flex; justify-content: space-between;">');

        // Fetch and display the consumption item details associated with the consumption
        $consumptionItemSql = "SELECT pci.qty, p.name AS product_name, p.unit, c.name AS category_name, cu.name AS cuisine_name
                    FROM `consumptionitem` pci
                    JOIN `product` p ON pci.product_id = p.id
                    JOIN `category` c ON pci.category_id = c.id
                    JOIN `cuisine` cu ON pci.cuisine_id = cu.id
                    WHERE pci.consumption_id = :consumption_id";

        $consumptionItemStmt = $pdo->prepare($consumptionItemSql);

        if ($consumptionItemStmt) {
            $consumptionItemStmt->bindParam(':consumption_id', $consumptionId);
            $consumptionItemStmt->execute();
            $consumptionItemData = $consumptionItemStmt->fetchAll(PDO::FETCH_ASSOC);
// Debugging: Output consumption details
var_dump($consumptionItemData);
            $cuisineTables = [];

            // Group items by cuisine
            foreach ($consumptionItemData as $item) {
                $cuisineName = $item['cuisine_name'];

                if (!isset($cuisineTables[$cuisineName])) {
                    $cuisineTables[$cuisineName] = [];
                }

                $cuisineTables[$cuisineName][] = $item;
            }

            // Generate a table for each cuisine
            foreach ($cuisineTables as $cuisineName => $cuisineItems) {
                // Consumption Chart Column
                $pdf->writeHTML('<div style="width: 48%;">');
                $pdf->writeHTML('<h2 style="color: #336699;">Consumption Chart</h2>');

                // Include the date in the PDF
                $pdf->writeHTML('<p style="font-size: 14px; font-weight: bold; color: #555;">Date: ' . $consumptionData['date'] . '</p>');

                $html = '<h3>' . $cuisineName . ' </h3>';
                $html .= '<table border="1" cellpadding="4" cellspacing="0">';
                $html .= '<tr><th>Product</th><th>Category</th><th>Unit</th><th>Qty</th></tr>';

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

                // Close the div for this cuisine
                $pdf->writeHTML('</div>');
            }
        }

        // Close the main flex div
        $pdf->writeHTML('</div>');
    }

    // Close the PDF document
    $pdfContent = $pdf->Output('', 'S'); // Capture the PDF content

    // End output buffering
    ob_end_clean();

    // Send the PDF content to the browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="consumption_details.pdf"');
    echo $pdfContent;

    // Exit to prevent additional content from being appended
    exit;
} else {
    echo "Invalid consumption ID.";
}
?>
