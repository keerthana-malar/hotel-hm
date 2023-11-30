<?php
include('header.php');
include('menu.php');
$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
// Roles Data 
$roleSql = "SELECT * FROM `role`";
$roleData = $pdo->query($roleSql);
?>
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
<div class="main-box">
    <h2 class="mb-3">Create User</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-user-post.php">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">

                <div class="form-group">
                    <label for="exampleInputName1">Name <span>*</span></label>
                    <input type="text" class="form-control" name="name" id="exampleInputName1" placeholder="Name"
                        required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputEmail3">Username <span>*</span></label>
                    <input type="text" class="form-control" name="username" id="exampleInputEmail3" required
                        placeholder="Username">
                </div>

            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputPassword4">Password <span>*</span></label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword4" required
                        placeholder="Password">
                </div>

            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleSelectGender">Role <span>*</span></label>
                    
                    <select class="form-control" id="exampleSelectGender" name="role" required>
                    <option value="">Select</option>

                        <?php foreach ($roleData as $r) { ?>
                            <option value="<?php echo $r['role_id'] ?>">
                                <?php echo $r['role_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleSelectGender">Branch <span>*</span></label>
                    <select class="form-control" id="exampleSelectGender" name="branch" required>
                    <option value="">Select</option>

                        <?php foreach ($branchdata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
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