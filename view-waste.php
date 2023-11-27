<?php
include('header.php');
include('menu.php');


// Function to get product unit based on product_id
function getProductUnit($productId) {
    global $pdo;
    $productSql = "SELECT unit FROM `product` WHERE id = :productid";
    $productStmt = $pdo->prepare($productSql);
    $productStmt->bindParam(':productid', $productId);
    $productStmt->execute();
    $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

    return $productData['unit'];
}
if (isset($_GET['id'])) {
    $wasteID = $_GET['id'];

    // Retrieve the waste details from the database
    $wasteSql = "SELECT * FROM `waste` WHERE id = :id";
    $wasteStmt = $pdo->prepare($wasteSql);
    $wasteStmt->bindParam(':id', $wasteID);
    $wasteStmt->execute();
    $wasteData = $wasteStmt->fetch(PDO::FETCH_ASSOC);

    // Retrieve the branch details for the waste
    $branchSql = "SELECT * FROM `branch` WHERE id = :branchid";
    $branchStmt = $pdo->prepare($branchSql);
    $branchStmt->bindParam(':branchid', $wasteData['branchid']);
    $branchStmt->execute();
    $branchData = $branchStmt->fetch(PDO::FETCH_ASSOC);

    // Retrieve the waste item details from the database
    // Retrieve the waste item details from the database
$wasteItemSql = "SELECT
wi.qty,
p.id AS product_id,
p.name AS product_name,
t.name AS type_name,
cu.name AS cuisine_name,
cat.name AS category_name,
wi.cost 
FROM `wasteitem` wi
INNER JOIN `product` p ON wi.product_id = p.id
INNER JOIN `type` t ON wi.type_id = t.id
INNER JOIN `cuisine` cu ON wi.cuisine_id = cu.id
INNER JOIN `category` cat ON wi.category_id = cat.id
WHERE wi.waste_id = :wasteid";
$wasteItemStmt = $pdo->prepare($wasteItemSql);
$wasteItemStmt->bindParam(':wasteid', $wasteID);
$wasteItemStmt->execute();
$wasteItemData = $wasteItemStmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    header("Location: waste.php");
    exit();
}
?>

<div class="main-box">
    <h2>View Waste Details</h2>
    <hr>

    <p><strong>Waste ID:</strong> <?php echo $wasteData['id']; ?></p>
    <p><strong>Branch:</strong> <?php echo $branchData['name']; ?></p>
    <p><strong>Date:</strong> <?php echo $wasteData['date']; ?></p>
    <p><strong>Waste Amount:</strong> <?php echo $wasteData['waste_amount']; ?></p>

    <!-- Display waste item details in a table -->
    <h4>Waste Items:</h4>
    <?php if (!empty($wasteItemData)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <!-- <th>Cuisine</th> -->
                    <!-- <th>Category</th> -->
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Waste Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wasteItemData as $item): ?>
                    <tr>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['type_name']; ?></td>
                        <!-- <td><?php echo $item['cuisine_name']; ?></td> -->
                        <!-- <td><?php echo $item['category_name']; ?></td> -->
                        <td><?php echo getProductUnit($item['product_id']); ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td><?php echo $item['cost']; ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="waste-pdf.php?id=<?php echo $wasteID ?>" target="_blank"><button class="btn btn-primary">Print</button></a>
    <?php else: ?>
        <p>No waste items found.</p>
    <?php endif; ?>

</div>

<?php
include('footer.php');
?>
<script>
    // JavaScript code for printing
    document.getElementById("printButton").addEventListener("click", function () {
        // Open the PDF in a new tab for printing
        window.open('generateorder-pdf.php?id=<?php echo $wasteID; ?>', '_blank');
    });</script>

<style>