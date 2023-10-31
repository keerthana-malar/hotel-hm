<?php

include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $stockID = $_GET['id'];

    // Retrieve the stock details from the database
    $stockSql = "SELECT * FROM `stock` WHERE id = :id";
    $stockStmt = $pdo->prepare($stockSql);
    $stockStmt->bindParam(':id', $stockID);
    $stockStmt->execute();
    $stockData = $stockStmt->fetch(PDO::FETCH_ASSOC);

    $oi = $pdo->query("SELECT * FROM stockitem WHERE stock_id = " . $stockID . "");
    $stockItem = $oi->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve branch data for dropdown
    $branchSql = "SELECT * FROM branch WHERE status = 'Active'";
    $branchData = $pdo->query($branchSql);
    // $typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $currentDate = date('Y-m-d');

} else {
    header("Location: stocks.php");
    exit();
}
?>

<div class="main-box">
    <h2>Edit Stock</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-stock.php" onsubmit="return handleForm()">
        <div class="row">
            <input type="hidden" name="id" value="<?php echo $stockData['id']; ?>">

            <!-- Branch -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <select class="form-control" id="branch" name="branch" disabled>
                        <?php foreach ($branchData as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>" <?php if ($stockData['branchid'] == $branch['id'])
                                   echo 'selected'; ?>>
                                <?php echo $branch['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- Date -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="date">Date Modified</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo $stockData['date_created']; ?>" value="<?= $currentDate ?>" disabled>
                </div>
            </div>
        </div>
        <!-- Additional product details rows -->
        <div class="pro-box">
            <?php foreach ($stockItem as $od) { ?>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <label for="exampleInputStatus">Product</label>
                            <select class="form-control mb-2" name="pro[]">
                                <?php foreach ($productdata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['product_id']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <label for="exampleInputStatus">Type</label>
                            <select class="form-control mb-2" name="ty[]">
                                <?php foreach ($typedata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['type_id']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <label for="exampleInputStatus">Category</label>
                            <select class="form-control mb-2" name="ca[]">
                                <?php foreach ($categorydata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['category_id']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <label for="exampleInputStatus">Cuisine</label>
                            <select class="form-control mb-2" name="cu[]">
                                <?php foreach ($cuisinedata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['cuisine_id']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="">Stock-Qty</label>
                        <input class="form-control mb-2" name="qt[]" value="<?php echo $od['qty']; ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <input type="hidden" name="ty[]" value="12">
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- End of additional product details rows -->
        <div class="col-3">
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <input type="hidden" name="oid" value="<?php echo $stockID ?>">

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Stock</button>
</div>
</form>
</div>
<script>
    function handleForm() {
        var branch = document.getElementById("branch");
        var date = document.getElementById("date");

        branch.disabled = false;
        date.disabled = false;

        return true;
    }
</script>
<?php include('footer.php'); ?>