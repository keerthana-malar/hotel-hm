<?php
include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $productID = $_GET['id'];
    $typeParam = $_GET['type'];

    // Retrieve the product details from the database
    $productSql = "SELECT * FROM product WHERE id = :id";
    $productStmt = $pdo->prepare($productSql);
    $productStmt->bindParam(':id', $productID);
    $productStmt->execute();
    $productData = $productStmt->fetch(PDO::FETCH_ASSOC);

    $typesql = "SELECT * FROM `type`  WHERE status = 'Active'";
    $typedata = $pdo->query($typesql);
    $categorysql = "SELECT * FROM `category`  WHERE status = 'Active'";
    $categorydata = $pdo->query($categorysql);
    $cuisinesql = "SELECT * FROM `cuisine` WHERE status = 'Active'";
    $cuisinedata = $pdo->query($cuisinesql);
} else {
    header("Location: products.php");
    exit();
}
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
    <h2>Edit Product</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-product.php" enctype="multipart/form-data">
        <div class="row">
            <input type="hidden" name="productID" value="<?php echo $productData['id']; ?>">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputName1">Product Name <span>*</span></label>
                    <input type="text" class="form-control" name="product" id="exampleInputName1" pattern="[A-Za-z ]+" value="<?php echo $productData['name']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Unit <span>*</span></label>
                    <select class="form-control" name="unit" id="exampleInputStatus">
                    <option value="g" <?php if ($productData['unit'] === 'g') echo 'selected'; ?>>g</option>
                        <option value="kg" <?php if ($productData['unit'] === 'kg') echo 'selected'; ?>>kg</option>
                        <option value="ltr" <?php if ($productData['unit'] === 'ltr') echo 'selected'; ?>>ltr</option>
                        <option value="pcs" <?php if ($productData['unit'] === 'pcs') echo 'selected'; ?>>pcs</option>

                    </select>
                </div>
            </div>
          
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputPrice">Price (Per Unit) <span>*</span></label>
                    <input type="number" class="form-control" name="price" step="0.01" min="0"  id="exampleInputPrice" value="<?php echo $productData['price']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3" hidden>
                <div class="form-group">
                    <label for="exampleInputType">Type <span>*</span></label>
                    <select class="form-control" name="type" id="exampleInputType">
                        <?php foreach ($typedata as $row): ?>
                            <option value="<?= $row['id'] ?>" <?php if ($productData['typeid'] === $row['id']) echo 'selected'; ?>><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
            <div class="form-group">
                    <label for="exampleInputCategory">Category <span>*</span></label>
                    <select class="form-control" name="category" id="exampleInputCategory">
                        <?php foreach ($categorydata as $row): ?>
                            <option value="<?= $row['id'] ?>" <?php if ($productData['categoryid'] === $row['id']) echo 'selected'; ?>><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>            </div>
                <?php  if($typeParam == 1) {?>
                <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputCuisine">Cuisine <span>*</span></label>
                    <select class="form-control" name="cuisine" id="exampleInputCuisine">
                        <?php foreach ($cuisinedata as $row): ?>
                            <option value="<?= $row['id'] ?>" <?php if ($productData['cuisineid'] === $row['id']) echo 'selected'; ?>><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div> 
            <?php  }else{?>
                <input type="text" name="cuisine" value="1" hidden>
            <?php  }?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Status <span>*</span></label>
                    <select class="form-control" name="status" id="exampleInputStatus">
                        <option value="Active" <?php if ($productData['status'] === 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if ($productData['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputImage">Image</label>
                 
                    <input type="file" class="form-control" name="img1" id="exampleInputImage">
                    <input type="hidden" name="existing_img" value="<?php echo $productData['img']; ?>">
                </div>
            </div>
        </div>
            <!-- Continue with other form fields using the same structure -->
        <button type="submit" class="btn btn-primary mr-2">Update</button>
    </form>
</div>
</div>

<?php include('footer.php'); ?>


