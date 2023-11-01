<?php
include('header.php');
include('menu.php');

$branchsql = "SELECT * FROM `branch`WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
$typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$currentDate = date('Y-m-d');
?>
<div class="main-box">
    <?php if (!empty($_GET['succ'])): ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>
                <?php echo $_GET['succ'] ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>
    <?php if (!empty($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>
                <?php echo $_GET['err'] ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>

    <h2 class="mb-3">Create Closing Stock</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-consumption-post.php">
        <div class="row">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Branch <span>*</span></label>
                    <select class="form-control" name="branch" id="exampleInputStatus" required>

                        <?php foreach ($branchdata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputDate">Date</label>
                    <input type="date" class="form-control" name="date" id="" value="<?= $currentDate ?>" required
                        readonly>
                </div>
            </div>

        </div>

        <!-- Additional product details rows -->
        <div class="pro-box">
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Product <span>*</span></label>
                        <select class="form-control mb-2" name="pro[]" required>
                            <?php foreach ($productdata as $row): ?>
                                <option value="<?= $row['id'] ?>">
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
                                <option value="<?= $row['id'] ?>">
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
                                <option value="<?= $row['id'] ?>">
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
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Available Qty <span>*</span></label>
                    <input class="form-control mb-2" type="number" name="qt[]" required>
                </div>
                <input class="form-control mb-2" type="number" name="uqt[]" readonly hidden>


            </div>
        </div>
        <!-- End of additional product details rows -->

        <div>
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>

</div>
</form>
</div>


<?php
include('footer.php');
?>