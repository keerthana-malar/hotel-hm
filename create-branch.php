<?php
include('header.php');
include('menu.php');


?>

<div class="main-box">
    <h2 class="mb-3">Create Branch</h2>
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
    <form class="forms-sample" method="post" action="create-branch-post.php" onsubmit="return validateForm()">
        <div class="row">
        
        <div class="col-12 col-md-6 col-lg-3">

                <div class="form-group">
                    <label for="exampleInputName1">Branch Name <span>*</span></label>
                    <input type="text" class="form-control" name="branch" id="exampleInputName1" placeholder="Name" required>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputEmail3">Address <span>*</span></label>
                    <input type="text" class="form-control" name="address" id="exampleInputEmail3" placeholder="Address" required>
                </div>

            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputPassword4">phone <span>*</span></label>
                    <input type="number" class="form-control" name="phone"  pattern="[0-9]{12}" id="exampleInputPhone"  placeholder="Phone"   required>
                </div>

            </div>
          
            <div class="col-12 col-md-6 col-lg-3">
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
