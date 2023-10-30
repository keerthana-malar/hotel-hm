<?php
include('header.php');
include('menu.php');
?>

<div class="main-box">
    <h2 class="mb-3">Create Branch</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-branch-post.php">
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
                    <input type="text" class="form-control" name="phone" pattern="[0-9]+" placeholder="Phone" required>
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
