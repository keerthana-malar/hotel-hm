<?php
include('header.php');
include('menu.php');

$typesql = "SELECT * FROM `type`  WHERE status = 'Active'";
$typedata = $pdo->query($typesql);

// Get type from url 
$typeParam = $_GET['type'];
if ($typeParam == "1") {
    $categorysql = "SELECT * FROM `category`  WHERE status = 'Active' AND typeid = '1'";
    $categorydata = $pdo->query($categorysql);
} else {
    $categorysql = "SELECT * FROM `category`  WHERE status = 'Active' AND typeid = '2'";
    $categorydata = $pdo->query($categorysql);
}
$cuisinesql = "SELECT * FROM `cuisine` WHERE status = 'Active' ";
$cuisinedata = $pdo->query($cuisinesql);
?>
<div class="main-box">
    <h2 class="mb-3">Create
        <?php if ($typeParam == '1') {
            echo "Food";
        } else {
            echo "Stock";
        } ?> Product
    </h2>
    <hr>
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
    <form class="forms-sample" method="post" action="create-product-post.php" enctype="multipart/form-data">
        <div class="row">

            <div class="col-12 col-md-6 col-lg-3">

                <div class="form-group">
                    <label for="exampleInputName1">Product Name <span>*</span></label>
                    <input type="text" class="form-control" name="product" id="exampleInputName1" placeholder="Name"
                        required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Unit <span>*</span></label>
                    <select class="form-control" name="unit" id="exampleInputStatus" required>
                        <option value="g">g</option>
                        <option value="kg">kg</option>
                        <option value="ltr">ltr</option>
                        <option value="pcs">pcs</option>

                    </select>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputName1">Price (Per Unit) <span>*</span></label>
                    <input type="text" class="form-control" name="price" id="exampleInputName1" placeholder="price"
                        required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3" hidden>
                <div class="form-group">
                    <label for="exampleInputStatus">Type <span>*</span></label>
                    <select class="form-control" name="type" id="exampleInputStatus">
                        <option value=""></option>

                        <?php foreach ($typedata as $row): ?>
                            <!-- <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option> -->

                            <option value="1" <?php if ($typeParam == "1") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>>Food</option>
                            <option value="2" <?php if ($typeParam == "2") {
                                echo "selected";
                            } else {
                                echo "";
                            } ?>>Raw Material
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Category <span>*</span></label>
                    <select class="form-control" name="category" id="exampleInputStatus" required>

                        <option value=""></option>

                        <?php foreach ($categorydata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>


                </div>
            </div>
            <?php if ($typeParam == 1) { ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Cuisine <span>*</span></label>
                        <select class="form-control" name="cuisine" id="exampleInputStatus" required>
                            <option value=""></option>

                            <?php foreach ($cuisinedata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } else { ?>
                <input type="text" name="cuisine" value="1" hidden>
            <?php } ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Status <span>*</span></label>
                    <select class="form-control" name="status" id="exampleInputStatus" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">

                <div class="form-group">
                    <label for="exampleInputName1">Image</label>
                    <input type="file" class="form-control" name="img1" id="exampleInputName1" placeholder="Name">
                </div>
            </div>
        </div>




        <button type="submit" class="btn btn-primary mr-2">Submit</button>
    </form>
</div>
</div>
<?php
include('footer.php');
?>