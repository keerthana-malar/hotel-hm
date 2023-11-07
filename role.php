<?php

include('header.php');
include('menu.php');

?>

<div class="container">
    <h3>Roles & Permission</h3><br>
    <div class="main-box">
        <table class="table table-bordered table-responsive-lg table-striped ">
            <form action="role_post.php"  method="POST">
                <label for="role_id">Role Name</label>
                <input type="text" id="role_id" class="form-control" name="role_name" require><br>
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
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="ena">Enable</option></select></td>
                    <td><input type="checkbox" name="create_fo"></td>
                    <td><input type="checkbox" name="view_fo"></td>
                    <td><input type="checkbox" name="edit_fo"></td>
                    <td><input type="checkbox" name="delete_fo"></td>
                </tr>
                <tr>
                    <td>Stock Order</td>
                    <td><select class="form-control" name="so_access" onchange="handleAccessChange('so')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="ena">Enable</option></select></td>
                    <td><input type="checkbox" name="create_so"></td>
                    <td><input type="checkbox" name="view_so"></td>
                    <td><input type="checkbox" name="edit_so"></td>
                    <td><input type="checkbox" name="delete_so"></td>
                </tr>
                <tr>
                    <td>Outdoor Order</td>
                    <td><select class="form-control" name="odo_access" onchange="handleAccessChange('odo')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_odo"></td>
                    <td><input type="checkbox" name="view_odo"></td>
                    <td><input type="checkbox" name="edit_odo"></td>
                    <td><input type="checkbox" name="delete_odo"></td>
                </tr>
                <tr>
                    <td>Food Catalog</td>
                    <td><select class="form-control" name="fc_access" onchange="handleAccessChange('fc')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_fc"></td>
                    <td><input type="checkbox" name="view_fc"></td>
                    <td><input type="checkbox" name="edit_fc"></td>
                    <td><input type="checkbox" name="delete_fc"></td>
                </tr>
                <tr>
                    <td>Stock Catalog</td>
                    <td><select class="form-control" name="sc_access"  onchange="handleAccessChange('sc')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_sc"></td>
                    <td><input type="checkbox" name="view_sc"></td>
                    <td><input type="checkbox" name="edit_sc"></td>
                    <td><input type="checkbox" name="delete_sc"></td>
                </tr>
                <tr>
                    <td>Closing Stock</td>
                    <td><select class="form-control" name="cs_access"  onchange="handleAccessChange('cs')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_cs"></td>
                    <td><input type="checkbox" name="view_cs"></td>
                    <td><input type="checkbox" name="edit_cs"></td>
                    <td><input type="checkbox" name="delete_cs"></td>
                </tr>
                <tr>
                    <td>Wastage</td>
                    <td><select class="form-control" name="w_access" onchange="handleAccessChange('w')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_w"></td>
                    <td><input type="checkbox" name="view_w"></td>
                    <td><input type="checkbox" name="edit_w"></td>
                    <td><input type="checkbox" name="delete_w"></td>
                </tr>
                <tr>
                    <td>Counter Closing</td>
                    <td><select class="form-control" name="cc_access"  onchange="handleAccessChange('cc')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_cc"></td>
                    <td><input type="checkbox" name="view_cc"></td>
                    <td><input type="checkbox" name="edit_cc"></td>
                    <td><input type="checkbox" name="delete_cc"></td>
                </tr>
                <tr>
                    <td>User</td>
                    <td><select class="form-control" name="user_access"  onchange="handleAccessChange('user')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_user"><input name="cf" type="hidden"></td>
                    <td><input type="checkbox" name="view_user"></td>
                    <td><input type="checkbox" name="edit_user"></td>
                    <td><input type="checkbox" name="delete_user"></td>
                </tr>
                <tr'<?php if($rdata["d_access"]!=="1"){ echo "hidden"; } ?>'>
                    <td>Role</td>
                    <td><select class="form-control" name="role_access"  onchange="handleAccessChange('role')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_role"><input name="cf" type="hidden"></td>
                    <td><input type="checkbox" name="view_role"></td>
                    <td><input type="checkbox" name="edit_role"></td>
                    <td><input type="checkbox" name="delete_role"></td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td><select class="form-control" name="b_access"  onchange="handleAccessChange('b')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td><input type="checkbox" name="create_b"><input name="cf" type="hidden"></td>
                    <td><input type="checkbox" name="view_b"></td>
                    <td><input type="checkbox" name="edit_b"></td>
                    <td><input type="checkbox" name="delete_b"></td>
                </tr>
                <tr>
                    <td>Product Configuration</td>
                    <td><select class="form-control" name="p_access" onchange="handleAccessChange('p')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Production Chart</td>
                    <td><select class="form-control" name="pc_access" onchange="handleAccessChange('pc')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Report</td>
                    <td><select class="form-control" name="r_access" onchange="handleAccessChange('r')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Dashboard</td>
                    <td><select class="form-control" name="d_access" onchange="handleAccessChange('d')">
                    <option value="0" name="dis">Disable</option>
                    <option value="1" name="enb">Enable</option></select></td>
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
let callAccess = ["fo","so","odo","fc","sc","cs","w","cc","user","role","b","r","d","role","pc"]
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


