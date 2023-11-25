<?php
include('header.php');
include('menu.php');

if (isset($_GET['id'])) {
    $branchID = $_GET['id'];

    // Retrieve the branch details from the database
    $branchSql = "SELECT * FROM branch WHERE id = :id";
    $branchStmt = $pdo->prepare($branchSql);
    $branchStmt->bindParam(':id', $branchID);
    $branchStmt->execute();
    $branchData = $branchStmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: branches.php");
    exit();
}
?>

<div class="main-box">
    <h2>Edit Branch</h2>
    <hr>
            <div class="row">

    <form class="forms-sample" method="post" action="update-branch.php" onsubmit="return validateForm()">
    <div class="row">

    <div class="col-12 col-md-6 col-lg-3">

        <div class="form-group">
            <input type="hidden" name="branchID" value="<?php echo $branchData['id']; ?>">
            <label for="exampleInputName">Branch Name</label>
            <input type="text" class="form-control" name="name" id="exampleInputName" value="<?php echo $branchData['name']; ?>">
        </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            <label for="exampleInputAddress">Address</label>
            <input type="text" class="form-control" name="address" id="exampleInputAddress" value="<?php echo $branchData['address']; ?>">
        </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            <label for="exampleInputPhone">Phone</label>
            <input type="number" class="form-control" name="phone" placeholder="Phone" pattern="[0-9]{10}" id="exampleInputPhone" value="<?php echo $branchData['phone']; ?>">
        </div>
        </div>     
        <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Status</label>
                    <select class="form-control" name="status" id="exampleInputStatus">
                        <option value="Active" <?php if ($branchData['status'] === 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if ($branchData['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>
            </div>
        <div class="col-12 col-md-6 col-lg-3">
        <button type="submit" class="btn btn-primary mr-2">Update</button>
        </div>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
<script>
    function validateForm() {
        var phoneInput = document.getElementById('exampleInputPhone');
        var phoneValue = phoneInput.value.trim();

        // Check if the phone number has exactly 10 digits
        if (!/^\d{10}$/.test(phoneValue)) {
            alert('Please enter a valid 10-digit phone number.');
            return false; // Prevent form submission
        }

        // Other validation logic can be added here

        return true; // Allow form submission
    }
</script>