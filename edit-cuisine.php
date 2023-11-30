<?php
include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $cuisineID = $_GET['id'];

    // Retrieve the cuisine details from the database
    $cuisineSql = "SELECT * FROM cuisine WHERE id = :id";
    $cuisineStmt = $pdo->prepare($cuisineSql);
    $cuisineStmt->bindParam(':id', $cuisineID);
    $cuisineStmt->execute();
    $cuisineData = $cuisineStmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: cuisines.php");
    exit();
}
?>

<div class="main-box">
    <h2>Edit Cuisine</h2>
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
    <form class="forms-sample" method="post" action="update-cuisine.php">
    <div class="row">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            <input type="hidden" name="cuisineID" value="<?php echo $cuisineData['id']; ?>">
            <label for="cuisineName">Cuisine Name <span>*</span></label>
            <input type="text" class="form-control" id="cuisineName" name="cuisine_name" value="<?php echo $cuisineData['name']; ?>" pattern="[A-Za-z ]+">
        </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            <label for="status">Status <span>*</span></label>
            <select class="form-control" id="status" name="status">
                <option value="Active" <?php if ($cuisineData['status'] === 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($cuisineData['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>
        </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Cuisine</button>
    </form>
</div>

<?php include('footer.php'); ?>
