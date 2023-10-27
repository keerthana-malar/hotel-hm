<?php
include('header.php');
include('menu.php');

$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
$typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="main-box">
    <h2 class="mb-3">Create Waste</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-waste-post.php">
        <div class="row">
        
        <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="exampleInputStatus">Branch <span>*</span></label>
                    <select class="form-control" name="branch" id="exampleInputStatus" required>
       
                <?php foreach ($branchdata as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                 
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
    <div class="form-group">
        <label for="exampleInputDate">Date <span>*</span></label>
        <input type="date" class="form-control" name="date" id="exampleInputDate" required>
    </div>
</div>

<div class="col-12 col-md-6 col-lg-3">

<div class="form-group">
    <label for="exampleInputName1">Waste Amount <span>*</span></label>
    <input type="text" class="form-control" name="amount" id="exampleInputName1" placeholder="Enter amount" required>
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
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Type</label>
                        <select class="form-control mb-2" name="ty[]">
                            <?php foreach ($typedata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Category</label>
                        <select class="form-control mb-2" name="ca[]">
                            <?php foreach ($categorydata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Cuisine</label>
                        <select class="form-control mb-2" name="cu[]">
                            <?php foreach ($cuisinedata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Waste Qty <span>*</span></label>
                    <input class="form-control mb-2" name="qt[]" required>
                </div>
            </div>
        </div>
        <!-- End of additional product details rows -->

        <div>
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>

            </div>
          </div>
    </form>
</div>

<?php
include('footer.php');
?>
