<?php
include('header.php');
include('menu.php');
if ($udata["role"] == "1") {
    $branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
} else {
    $branchsql = "SELECT * FROM `branch` WHERE status = 'Active' AND id = $userBranch";
}
// $branchsql = "SELECT * FROM `branch` WHERE status = 'Active' AND id = $userBranch";
$branchdata = $pdo->query($branchsql);
$typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active' AND typeid = '2'")->fetchAll(PDO::FETCH_ASSOC);
$productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active' AND typeid = '2'")->fetchAll(PDO::FETCH_ASSOC);
$currentDate = date('Y-m-d');
?>

<div class="main-box">
    <h2 class="mb-3">Create Stock Orders</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-stockorder-post.php">
        <!-- Branch, Order Date, Delivery Date, Priority, Status fields ... -->
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="orderName">Order Name</label>
                    <input type="text" class="form-control" value=<?php echo $logName ?> readonly name="orderName"
                        id="orderName">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Branch </label>
                    <select class="form-control" name="branch" id="exampleInputStatus">


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
                    <label for="exampleInputDate">Order Date</label>
                    <input type="text" class="form-control" name="orderDate" id="exampleInputDate"
                        value="<?= $currentDate ?>" readonly>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="deliveryDateinput">Delivery Date <span>*</span></label>
                    <input type="text" autocomplete="off" placeholder="Choose Date" class="form-control datepic"
                        name="deliveryDate" id="deliveryDateinput" required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Priority</label>
                    <select class="form-control" name="priority" id="exampleInputStatus" required>
                        <option value="Normal">Normal</option>
                        <option value="Low">Low</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Status <span>*</span></label>
                    <select class="form-control" name="status" id="exampleInputStatus" required readonly>
                        <option value="Created">Created</option>
                        <!-- <option value="Accepted">Accepted</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Received">Received</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Rejected</option> -->
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label for="">Description</label>
                <textarea class="form-control mb-2" name="des" id="description"></textarea>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="form-group">
                <select class="form-control" name="orderType" id="orderType" hidden>
                    <option value="1">Food Order</option>
                    <option value="2" selected>Stock Order</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
        </div>



        <!-- Additional product details rows -->
        <div class="pro-box">
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Product <span>*</span></label>
                        <select class="form-control mb-2" name="pro[]" required>
                            <option value="">Select</option>
                            <?php foreach ($productdata as $row): ?>
                                <option value="<?= $row['id'] ?>" data-unit="<?= $row['unit'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-2" hidden>
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

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Category</label>
                        <select class="form-control mb-2" name="ca[]" readonly>
                            <?php foreach ($categorydata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- hidden Cuisine  -->
                <select class="form-control mb-2" name="cu[]" readonly hidden>
                    <?php foreach ($cuisinedata as $row): ?>
                        <option value="<?= $row['id'] ?>">
                            <?= $row['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Unit</label>
                    <input type="text" class="form-control mb-2" name="unit[]" readonly>
                </div>


                <!-- <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputStatus">Priority</label>
                    <select class="form-control" name="pr[]" id="exampleInputStatus">
                    <option value="High">High</option>
                        <option value="Low">Low</option>
                        <option value="Normal">Normal</option>
                        <option value="Urgent">Urgent</option>


                    </select>
                </div>
            </div> -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Qty</label>
                    <input type="number" class="form-control mb-2" name="qt[]" step="0.01" min="0" required>
                </div>

                <input type="hidden" name="ty[]" value="2">

            </div>
        </div>
        <!-- End of additional product details rows -->

        <div>
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>

        <button type="submit" class="btn btn-primary mr-2">Submit</button>
    </form>
</div>




<?php
include('footer.php');
?>
<script>
    // Function to update the unit field based on the selected product
    function updateUnit(selectElement) {
        const unitInput = selectElement.parentElement.parentElement.nextElementSibling.querySelector('input[name="unit[]"]');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit');

        unitInput.value = unit;
    }
</script>