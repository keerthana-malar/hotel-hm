<?php

include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Retrieve the user details from the database, joining with the role table to get the role name
    $userSql = "SELECT u.*, r.role_name FROM user u
LEFT JOIN role r ON u.role = r.role_id
WHERE u.id = :id";
    $userStmt = $pdo->prepare($userSql);
    $userStmt->bindParam(':id', $userID);
    $userStmt->execute();
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Retrieve roles data for the dropdown
    $roleSql = "SELECT * FROM `role`";
    $roleData = $pdo->query($roleSql);

} else {
    header("Location: users.php");
    exit();
}
$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
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
    <h2>Edit User</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-user.php">
        <div class="row">
            <input type="hidden" name="userID" value="<?php echo $userData['id']; ?>">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputName">Name</label>
                    <input type="text" class="form-control" name="name" id="exampleInputName"
                        value="<?php echo $userData['name']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputUsername">Username</label>
                    <input type="text" class="form-control" name="username" id="exampleInputUsername"
                        value="<?php echo $userData['username']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleSelectGender">Branch</label>
                    <select class="form-control" id="exampleSelectGender" name="branch"
                        value="<?php echo $userData['name']; ?>">

                        <?php foreach ($branchdata as $row): ?>
                            <option <?php if($row['id'] == $userData['branch']){echo "Selected";}else{echo "";} ?> value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputRole">Role</label>
                    <select class="form-control" name="role" id="exampleInputRole">
                        <?php foreach ($roleData as $r) { ?>
                            <option value="<?php echo $r['role_id'] ?>" <?php echo ($r['role_name'] === $userData['role_name']) ? 'selected' : ''; ?>>
                                <?php echo $r['role_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mr-2">Update</button>
    </form>
</div>

<?php include('footer.php'); ?>