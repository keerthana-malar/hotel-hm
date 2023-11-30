<?php
include('header.php');
include('menu.php');
?>
<div class="main-box">
    <h2 class="mb-3">Create Cuisine</h2>
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
    <form class="forms-sample" method="post" action="create-cuisine-post.php">
        <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
         <div class="form-group">
         <label for="exampleInputName1">Name <span>*</span></label>
        <input type="text" class="form-control" name="cuisinename" id="exampleInputName1" placeholder="Enter cuisine Name" required pattern="[A-Za-z]+">
        </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="exampleInputStatus">Status <span>*</span></label>
                    <select class="form-control" name="status" id="exampleInputStatus" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            </div>   
        <button type="submit" class="btn btn-primary mr-2">Submit</button>
    </form>
</div>

<?php
include('footer.php');
?>
