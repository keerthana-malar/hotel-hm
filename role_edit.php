<?php

include('header.php');
include('menu.php');

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['role_id'])) {
    // Retrieve role details from the database based on role_id
    $role_id = $_GET['role_id'];
    $stmt = $pdo->prepare("SELECT * FROM role WHERE role_id = ?");
    $stmt->execute([$role_id]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container">
    <h3>Edit Roles & Permission</h3><br>
    <div class="main-box">
        <table class="table table-bordered table-responsive-lg table-striped">
            <form action="role_edit_post.php" method="POST">
                <input type="hidden" name="role_id" value="<?php echo $role_id ?>">
                <label for="role_id">Role Name</label>
                <input type="text" id="role_id" class="form-control" name="role_name" value="<?php echo $role['role_name']; ?>" require><br>
            <thead>
            <tr>
                <th>Role</th>
                <th>Access</th>
                <th>Create</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Food Order</td>
                    <td><select class="form-control" name="fo_access" onchange="handleAccessChange('fo')">
                    <option value="0" name="dis" <?php echo ($role['fo_access'] == 0) ? 'selected' : ''; ?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['fo_access'] == 1) ? 'selected' : ''; ?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_fo" <?php echo ($role['create_fo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_fo" <?php echo ($role['view_fo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_fo" <?php echo ($role['edit_fo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_fo" <?php echo ($role['delete_fo'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Stock Order</td>
                    <td><select class="form-control" name="so_access" onchange="handleAccessChange('so')">
                    <option value="0" name="dis" <?php echo ($role['so_access'] == 0) ? 'selected' : ''; ?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['so_access'] == 1) ? 'selected' : ''; ?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_so" <?php echo ($role['create_so'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_so" <?php echo ($role['view_so'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_so" <?php echo ($role['edit_so'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_so" <?php echo ($role['delete_so'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Outdoor Order</td>
                    <td><select class="form-control" name="odo_access" onchange="handleAccessChange('odo')">
                    <option value="0" name="dis" <?php echo ($role['odo_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['odo_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_odo" <?php echo ($role['create_odo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_odo" <?php echo ($role['view_odo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_odo" <?php echo ($role['edit_odo'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_odo" <?php echo ($role['delete_odo'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Food Catalog</td>
                    <td><select class="form-control" name="fc_access" onchange="handleAccessChange('fc')">
                    <option value="0" name="dis" <?php echo ($role['fc_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['fc_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_fc" <?php echo ($role['create_fc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_fc" <?php echo ($role['view_fc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_fc" <?php echo ($role['edit_fc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_fc" <?php echo ($role['delete_fc'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Stock Catalog</td>
                    <td><select class="form-control" name="sc_access" onchange="handleAccessChange('sc')">
                    <option value="0" name="dis" <?php echo ($role['sc_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['sc_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_sc" <?php echo ($role['create_sc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_sc" <?php echo ($role['view_sc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_sc" <?php echo ($role['edit_sc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_sc" <?php echo ($role['delete_sc'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Closing Stock</td>
                    <td><select class="form-control" name="cs_access" onchange="handleAccessChange('cs')">
                    <option value="0" name="dis" <?php echo ($role['cs_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['cs_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_cs" <?php echo ($role['create_cs'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_cs" <?php echo ($role['view_cs'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_cs" <?php echo ($role['edit_cs'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_cs" <?php echo ($role['delete_cs'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Wastage</td>
                    <td><select class="form-control" name="w_access" onchange="handleAccessChange('w')">
                    <option value="0" name="dis" <?php echo ($role['w_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['w_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_w" <?php echo ($role['create_waste'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_w" <?php echo ($role['view_waste'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_w" <?php echo ($role['edit_waste'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_w" <?php echo ($role['delete_waste'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Counter Closing</td>
                    <td><select class="form-control" name="cc_access" onchange="handleAccessChange('cc')">
                    <option value="0" name="dis" <?php echo ($role['cc_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['cc_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_cc" <?php echo ($role['create_cc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_cc" <?php echo ($role['view_cc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_cc" <?php echo ($role['edit_cc'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_cc" <?php echo ($role['delete_cc'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>User</td>
                    <td><select class="form-control" name="user_access" onchange="handleAccessChange('user')">
                    <option value="0" name="dis" <?php echo ($role['user_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['user_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_user" <?php echo ($role['create_user'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_user" <?php echo ($role['view_user'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_user" <?php echo ($role['edit_user'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_user" <?php echo ($role['delete_user'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td><select class="form-control" name="role_access" onchange="handleAccessChange('role')">
                    <option value="0" name="dis" <?php echo ($role['role_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['role_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_role" <?php echo ($role['create_role'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_role" <?php echo ($role['view_role'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_role" <?php echo ($role['edit_role'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_role" <?php echo ($role['delete_role'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td><select class="form-control" name="b_access" onchange="handleAccessChange('b')">
                    <option value="0" name="dis" <?php echo ($role['b_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['b_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td><input type="checkbox" name="create_b" <?php echo ($role['create_b'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="view_b" <?php echo ($role['view_b'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="edit_b" <?php echo ($role['edit_b'] == 1) ? 'checked' : ''; ?>></td>
                    <td><input type="checkbox" name="delete_b" <?php echo ($role['delete_b'] == 1) ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <td>Product Configuration</td>
                    <td><select class="form-control" name="p_access" onchange="handleAccessChange('p')">
                    <option value="0" name="dis" <?php echo ($role['p_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['p_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>      
                </tr>
                <tr>
                    <td>Report</td>
                    <td><select class="form-control" name="r_access" onchange="handleAccessChange('r')">
                    <option value="0" name="dis" <?php echo ($role['r_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="1" name="enb" <?php echo ($role['r_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>      
                </tr>
                <tr>
                    <td>Dashboard</td>
                    <td><select class="form-control" name="d_access" onchange="handleAccessChange('d')">
                    <option value="1" name="dis"<?php echo ($role['d_access'] == 0) ? 'selected' : ''?>>Disable</option>
                    <option value="0" name="enb"<?php echo ($role['d_access'] == 1) ? 'selected' : ''?>>Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table><br>
        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
    </div>
</div>

<!-- footer section -->
</div>
<!-- <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://www.infygain.com/" target="_blank">Infygain</a> 2023</span>
    </div>
</footer> -->

</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>

<!-- Access enable and disable function controlled  -->
<script>
function handleAccessChange(e) {
    
    // Get the "fo_access" dropdown element
    var foAccessDropdown = document.querySelector(`[name='${e}_access']`);

    console.log(foAccessDropdown)

    // Get all the checkboxes
    var viewCheckbox = document.getElementsByName(`view_${e}`)[0];
    var editCheckbox = document.getElementsByName(`edit_${e}`)[0];
    var createCheckbox = document.getElementsByName(`create_${e}`)[0];
    var deleteCheckbox = document.getElementsByName(`delete_${e}`)[0];

    // Check the selected option of the "fo_access" dropdown
    if (foAccessDropdown.value === "0") { // "0" represents "Disable"
        // Disable all the checkboxes
        viewCheckbox.disabled = true;
        editCheckbox.disabled = true;
        createCheckbox.disabled = true;
        deleteCheckbox.disabled = true;
    } else {
        // Enable all the checkboxes
        viewCheckbox.disabled = false;
        editCheckbox.disabled = false;
        createCheckbox.disabled = false;
        deleteCheckbox.disabled = false;
    }
}
let callAccess = ["fo","so","odo","fc","sc","cs","w","cc","user","r","d","role","p","b"]
callAccess.forEach((acc)=>{
    handleAccessChange(acc)
})
</script>

<script src="vendors/js/vendor.bundle.base.js"></script>

<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="vendors/progressbar.js/progressbar.min.js"></script>
<script src="vendors/chart.js/Chart.min.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="js/dashboard.js"></script>
<!-- End custom js for this page-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>


</body>

</html>
