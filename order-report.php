<?php
include('header.php');
include('menu.php');

$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
$productdata = $pdo->query("SELECT * FROM `product`")->fetchAll(PDO::FETCH_ASSOC);
$typedata = $pdo->query("SELECT * FROM `type`")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-box">
    <h2>Order Report</h2>
    <hr>
    <form class="forms-sample" method="post" action="generate-report.php">
        <div class="row">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="inputStartDate">Start Date</label>
                    <input type="text" autocomplete="off" placeholder="Choose From Date" class="form-control datepicc" name="startDate" id="inputStartDate">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="inputEndDate">End Date</label>
                    <input type="text" autocomplete="off" placeholder="Choose To Date" class="form-control datepicc" name="endDate" id="inputEndDate">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="inputBranch">Select Branch</label>
                    <select class="form-control" name="selectedBranch" id="inputBranch">
                        <option value="">All Branches</option>
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
                    <label for="inputType">Select Type</label>
                    <select class="form-control" name="selectedType" id="inputType">
                        <option value="">All Types</option>
                        <?php foreach ($typedata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="inputCategory">Select Category</label>
                    <select class="form-control" name="selectedCategory" id="inputCategory">
                        <option value="">All Categories</option>
                        <?php foreach ($categorydata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="inputStatus">Select Status</label>
                    <select class="form-control" name="selectedStatus" id="inputStatus">
                        <option value="">All Statuses</option>
                        <option value="Created">Created</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Received">Received</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

            </div>
            </div>
            <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
            <button type="submit" class="btn btn-primary mr-2">Generate Report</button>
            </div>
            </div>
    </form>

</div>

<?php
include('footer.php');
?>