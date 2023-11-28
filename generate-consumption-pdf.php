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

    // Output the company name as the heading
    $htmlHeading = '<h1 style="text-align: center; color: #336699;">Magizham</h1>';
    $pdf->writeHTML($htmlHeading, true, false, true, false, '');

    // Fetch consumption information from the consumption table
    $consumptionInfoSql = "SELECT date_created, branchid FROM `consumption` WHERE id = :consumption_id";
    $consumptionInfoStmt = $pdo->prepare($consumptionInfoSql);

    if ($consumptionInfoStmt) {
        $consumptionInfoStmt->bindParam(':consumption_id', $consumptionId);
        $consumptionInfoStmt->execute();
        $consumptionInfo = $consumptionInfoStmt->fetch(PDO::FETCH_ASSOC);

        // Output consumption information
        $htmlConsumptionInfo = '<h2>Consumption Information</h2>';
        // $htmlConsumptionInfo .= '<p>Consumption ID: ' . $consumptionId . '</p>';
        $htmlConsumptionInfo .= '<p>Date: ' . $consumptionInfo['date_created'] . '</p>';
        $htmlConsumptionInfo .= '<p>Branch Name: ' . getBranchName($consumptionInfo['branchid']) . '</p>';
        $pdf->writeHTML($htmlConsumptionInfo, true, false, true, false, '');

        // Set padding for the last cell in the "Branch Information" section
$pdf->SetCellPaddings(0, 0, 0, 10);
        // Fetch and display the consumption items associated with the consumption
        $consumptionItemSql = "SELECT pci.qty, pci.used_qty, p.name AS product_name, c.name AS category_name, t.name AS type_name, p.unit 
                    FROM `consumptionitem` pci
                    JOIN `product` p ON pci.product_id = p.id
                    JOIN `category` c ON pci.category_id = c.id
                    JOIN `type` t ON pci.type_id = t.id
                    WHERE pci.consumption_id = :consumption_id";

        $consumptionItemStmt = $pdo->prepare($consumptionItemSql);

        if ($consumptionItemStmt) {
            $consumptionItemStmt->bindParam(':consumption_id', $consumptionId);
            $consumptionItemStmt->execute();
            $consumptionItemData = $consumptionItemStmt->fetchAll(PDO::FETCH_ASSOC);

            // Group items by type
            $typeTables = [];

            foreach ($consumptionItemData as $item) {
                $typeName = $item['type_name'];

                if (!isset($typeTables[$typeName])) {
                    $typeTables[$typeName] = [];
                }

                $typeTables[$typeName][] = $item;
            }

            // Generate a table for each type
            foreach ($typeTables as $typeName => $typeItems) {
                // Output the HTML content to the PDF
                $pdf->writeHTML('<h2>' . $typeName . ' Stock</h2>');

                $html = '<table border="1" cellpadding="4" cellspacing="0">';
                $html .= '<tr bgcolor="#ECECEC"><th>Product</th><th>Category</th><th>Unit</th><th>Qty</th><th>Used Qty</th></tr>';

                foreach ($typeItems as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['product_name'] . '</td>';
                    $html .= '<td>' . $item['category_name'] . '</td>';
                    $html .= '<td>' . $item['unit'] . '</td>';
                    $html .= '<td>' . $item['qty'] . '</td>';
                    $html .= '<td>' . $item['used_qty'] . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            // Close the PDF document
            $pdfContent = $pdf->Output('', 'S');

            // End output buffering
            ob_end_clean();

            // Send the PDF content to the browser
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="consumption_details.pdf"');
            echo $pdfContent;

            // Exit to prevent additional content from being appended
            exit;
        } else {
            echo "Failed to prepare the consumption item query.";
        }
    } else {
        echo "Failed to prepare the consumption information query.";
    }
} else {
    echo "Invalid consumption ID.";
}

// Function to get branch name based on branch ID
function getBranchName($branchId) {
    global $pdo; // Add this line to access the global $pdo variable

    $branchInfoSql = "SELECT name FROM `branch` WHERE id = :branch_id";
    $branchInfoStmt = $pdo->prepare($branchInfoSql);
    $branchInfoStmt->bindParam(':branch_id', $branchId);
    $branchInfoStmt->execute();
    $branchInfo = $branchInfoStmt->fetch(PDO::FETCH_ASSOC);

    if ($branchInfo) {
        return $branchInfo['name'];
    }

    return "Unknown Branch"; // Replace this with your default branch name
}
?>
