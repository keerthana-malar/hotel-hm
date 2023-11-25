<?php
include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $consumptionID = $_GET['id'];

    // Retrieve the consumption details from the database
    $consumptionSql = "SELECT * FROM `consumption` WHERE id = :id";
    $consumptionStmt = $pdo->prepare($consumptionSql);
    $consumptionStmt->bindParam(':id', $consumptionID);
    $consumptionStmt->execute();
    $consumptionData = $consumptionStmt->fetch(PDO::FETCH_ASSOC);

    $oi = $pdo->query("SELECT * FROM consumptionitem WHERE consumption_id = " . $consumptionID . "");
    $consumptionItem = $oi->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve branch data for dropdown
    $branchSql = "SELECT * FROM branch WHERE status = 'Active'";
    $branchData = $pdo->query($branchSql);
    $typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: consumptions.php");
    exit();
}
?>

<div class="main-box">
    <h2>Edit Closing Stock</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-consumption.php" onsubmit="return handleForm()">
        <div class="row">
            <input type="hidden" name="id" value="<?php echo $consumptionData['id']; ?>">

            <!-- Branch -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="branch">Branch <span>*</span></label>
                    <select class="form-control" id="branch" name="branch" disabled required>
                        <?php foreach ($branchData as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>" <?php if ($consumptionData['branchid'] == $branch['id'])
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
                    <label for="date">Date</label>
                    <input type="text" class="form-control" id="date" name="date" disabled
                        value="<?php echo $consumptionData['date_created']; ?>">
                </div>
            </div>
        </div>
        <!-- Additional product details rows -->
        <div class="row">

            <div class="col-12 col-md-6 col-lg-3">
                <label for="exampleInputStatus">Product</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Type</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Unit</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="">Available_Qty</label>
            </div>
        </div>
        <div class="pro-box">
            <?php foreach ($consumptionItem as $od) { ?>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Product <span>*</span></label> -->
                            <select class="form-control mb-2 uniquePro" name="pro[]">
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
                            <!-- <label for="exampleInputStatus">Type</label> -->
                            <select class="form-control mb-2" name="ty[]" readonly>
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
                            <!-- <label for="">Unit <span>*</span></label> -->
                            <input type="text" class="form-control sz mb-2" name="unit[]" value="<?php echo $od['unit']; ?>"
                                required readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2" hidden>
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Category</label> -->
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
                        <!-- <label for="">Available Qty <span>*</span></label> -->
                        <input class="form-control mb-2" name="qt[]" value="<?php echo $od['qty']; ?>" required>
                        <input type="text" name="old_qty[]" value="<?php echo $od['old_qty']; ?>">
                    </div>

                </div>
            <?php } ?>
        </div>

        <!-- End of additional product details rows -->
        <div class="col-3">
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <input type="hidden" name="oid" value="<?php echo $consumptionID; ?>">

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Closing Stocks</button>
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