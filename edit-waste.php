<?php
include('header.php');
include('menu.php');

if ($udata["role"] == "1") {
    $branchSql = "SELECT * FROM `branch` WHERE status = 'Active'";
} else {
    $branchSql = "SELECT * FROM `branch` WHERE status = 'Active' AND id = $userBranch";
}

if (isset($_GET['id'])) {
    $wasteID = $_GET['id'];

    // Retrieve the waste details from the database
    $wasteSql = "SELECT * FROM `waste` WHERE id = :id";
    $wasteStmt = $pdo->prepare($wasteSql);
    $wasteStmt->bindParam(':id', $wasteID);
    $wasteStmt->execute();
    $wasteData = $wasteStmt->fetch(PDO::FETCH_ASSOC);

    $oi = $pdo->query("SELECT * FROM wasteitem WHERE waste_id = " . $wasteID . "");
    $wasteItem = $oi->fetchAll(PDO::FETCH_ASSOC);
    // Retrieve branch data for dropdown
    // $branchSql = "SELECT * FROM branch WHERE status = 'Active'";
    $branchData = $pdo->query($branchSql);
    $typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: wastes.php");
    exit();
}
?>
<div class="main-box">
    <h2>Edit Waste</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-waste.php" onsubmit="handleSubmit(this)">
        <div class="row">
            <input type="hidden" name="id" value="<?php echo $wasteData['id']; ?>">
            <!-- Branch -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <select class="form-control" id="branch" name="branch">
                        <?php foreach ($branchData as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>" <?php if ($wasteData['branchid'] == $branch['id'])
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
                    <input type="text" class="form-control" id="date" name="date"
                        value="<?php echo $wasteData['date']; ?>">
                </div>
            </div>
            <!-- Waste Amount -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="waste_amount">Waste Amount</label>
                    <input type="number" class="form-control" id="waste_amount" name="amount"
                        value="<?php echo $wasteData['waste_amount']; ?>" readonly>
                </div>
            </div>
        </div>
        <!-- Additional product details rows -->
        <div class="row">

            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Product</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Type</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Cuisine</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="">Category <span>*</span></label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="">Waste_Qty</label>
            </div>
        </div>
        <div class="pro-box">
            <?php foreach ($wasteItem as $od) { ?>
                <div class="row mb-4">
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Product</label> -->
                            <select class="form-control mb-2 uniquePro" name="pro[]" onchange="handleQty(this)">
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
                            <!-- <label for="exampleInputStatus">Category</label> -->
                            <select class="form-control mb-2" name="ca[]" readonly>
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
                            <!-- <label for="exampleInputStatus">Cuisine</label> -->
                            <select class="form-control mb-2" name="cu[]" readonly>
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
                        <!-- <label for="">Waste Qty</label> -->
                        <input class="form-control mb-2" name="qt[]" oninput="handleCost(this)"
                            value="<?php echo $od['qty']; ?>">
                        <input name="old_wq[]" type="text" value="<?php echo $od['qty']; ?>">
                    </div>

                    <input class="form-control mb-2" type="number" name="cost[]" value="<?php echo $od['cost']; ?>" readonly
                        hidden>
                    <input class="form-control mb-2" type="number" name="oldWasteQty[]" value="<?php echo $od['cost']; ?>"
                        readonly hidden>
                </div>
            <?php } ?>
        </div>
        <!-- End of additional product details rows -->
        <div class="col-12">
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <input type="hidden" name="oid" value="<?php echo $wasteID ?>">
        <br><br><br>
        <!-- Submit Button -->
        <div>
            <button type="submit" class="btn btn-primary">Update waste</button>
        </div>
    </form>
</div>
<script>
    function handleCost(e) {
        var proData = [];
        proData = <?php echo json_encode($productdata); ?>;

        var proId = e.closest(".row").querySelector('[name="pro[]"]').value;
        var proCost = e.closest(".row").querySelector('[name="cost[]"]');

        var product = proData.find(function (product) {
            return product.id === proId;
        });

        if (product) {
            var Cost = product.price * e.value;
            proCost.value = Cost;
        }
    }

    function handleSubmit(e) {
        e.preventDefault;
        let costFields = document.querySelectorAll('[name="cost[]"]');
        let totalCostField = document.querySelector('[name="amount"]');
        let totalCost = 0;
        costFields.forEach((cost) => {
            totalCost += parseInt(cost.value);
            totalCostField.value = totalCost;
        })
        return true;
    }

    function handleQty(e) {
        var qtyField = e.closest(".row").querySelector('[name="qt[]"]');
        var costField = e.closest(".row").querySelector('[name="cost[]"]');
        var oldCostField = e.closest(".row").querySelector('[name="oldWasteQty[]"]');
        qtyField.value = "";
        costField.value = "";
        oldCostField.value = "";
    }
</script>
<?php include('footer.php'); ?>