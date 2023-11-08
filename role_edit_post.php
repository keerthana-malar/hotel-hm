<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Include your database connection code here
require('db.php'); 

$u1 = "role_view.php?succ=";
$u2 = "role_view.php?err=";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from the form
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];
    $fo_access = $_POST['fo_access'];
    $view_fo = isset($_POST['view_fo']) ? 1 : 0;
    $edit_fo = isset($_POST['edit_fo']) ? 1 : 0;
    $create_fo = isset($_POST['create_fo']) ? 1 : 0;
    $delete_fo = isset($_POST['delete_fo']) ? 1 : 0;
    $so_access = $_POST['so_access'];
    $view_so = isset($_POST['view_so']) ? 1 : 0;
    $edit_so = isset($_POST['edit_so']) ? 1 : 0;
    $create_so = isset($_POST['create_so']) ? 1 : 0;
    $delete_so = isset($_POST['delete_so']) ? 1 : 0;
    $odo_access = $_POST['odo_access'];
    $view_odo = isset($_POST['view_odo']) ? 1 : 0;
    $edit_odo = isset($_POST['edit_odo']) ? 1 : 0;
    $create_odo = isset($_POST['create_odo']) ? 1 : 0;
    $delete_odo = isset($_POST['delete_odo']) ? 1 : 0;
    $fc_access = $_POST['fc_access'];
    $view_fc = isset($_POST['view_fc']) ? 1 : 0;
    $edit_fc = isset($_POST['edit_fc']) ? 1 : 0;
    $create_fc = isset($_POST['create_fc']) ? 1 : 0;
    $delete_fc = isset($_POST['delete_fc']) ? 1 : 0;
    $sc_access = $_POST['sc_access'];
    $view_sc = isset($_POST['view_sc']) ? 1 : 0;
    $edit_sc = isset($_POST['edit_sc']) ? 1 : 0;
    $create_sc = isset($_POST['create_sc']) ? 1 : 0;
    $delete_sc = isset($_POST['delete_sc']) ? 1 : 0;
    $cs_access = $_POST['cs_access'];
    $view_cs = isset($_POST['view_cs']) ? 1 : 0;
    $edit_cs = isset($_POST['edit_cs']) ? 1 : 0;
    $create_cs = isset($_POST['create_cs']) ? 1 : 0;
    $delete_cs = isset($_POST['delete_cs']) ? 1 : 0;
    $w_access = $_POST['w_access'];
    $create_w = isset($_POST['create_w']) ? 1 : 0;
    $view_w = isset($_POST['view_w']) ? 1 : 0;
    $edit_w = isset($_POST['edit_w']) ? 1 : 0;
    $delete_w = isset($_POST['delete_w']) ? 1 : 0;
    $cc_access = $_POST['cc_access'];
    $create_cc = isset($_POST['create_cc']) ? 1 : 0;
    $view_cc = isset($_POST['view_cc']) ? 1 : 0;
    $edit_cc = isset($_POST['edit_cc']) ? 1 : 0;
    $delete_cc = isset($_POST['delete_cc']) ? 1 : 0;
    $user_access = $_POST['user_access'];
    $create_user = isset($_POST['create_user']) ? 1 : 0;
    $view_user = isset($_POST['view_user']) ? 1 : 0;
    $edit_user = isset($_POST['edit_user']) ? 1 : 0;
    $delete_user = isset($_POST['delete_user']) ? 1 : 0;
    $r_access = $_POST['r_access'];
    $d_access = $_POST['d_access'];
    $p_access = $_POST['p_access'];
    $role_access = $_POST['role_access'];
    $create_role = isset($_POST['create_role']) ? 1 : 0;
    $view_role = isset($_POST['view_role']) ? 1 : 0;
    $edit_role = isset($_POST['edit_role']) ? 1 : 0;
    $delete_role = isset($_POST['delete_role']) ? 1 : 0;
    $b_access = $_POST['b_access'];
    $create_b = isset($_POST['create_b']) ? 1 : 0;
    $view_b = isset($_POST['view_b']) ? 1 : 0;
    $edit_b = isset($_POST['edit_b']) ? 1 : 0;
    $delete_b = isset($_POST['delete_b']) ? 1 : 0;
    $pc_access = $_POST['pc_access'];


    $sql = "UPDATE role
    SET
        role_name =  :role_name,
        fo_access = :fo_access,
        view_fo = :view_fo,
        edit_fo = :edit_fo,
        create_fo = :create_fo,
        delete_fo = :delete_fo,
        so_access = :so_access,
        view_so = :view_so,
        edit_so = :edit_so,
        create_so = :create_so,
        delete_so = :delete_so,
        odo_access = :odo_access,
        view_odo = :view_odo,
        edit_odo = :edit_odo,
        create_odo = :create_odo,
        delete_odo = :delete_odo,
        fc_access = :fc_access,
        view_fc = :view_fc,
        edit_fc = :edit_fc,
        create_fc = :create_fc,
        delete_fc = :delete_fc,
        sc_access = :sc_access,
        view_sc = :view_sc,
        edit_sc = :edit_sc,
        create_sc = :create_sc,
        delete_sc = :delete_sc,
        cs_access = :cs_access,
        view_cs = :view_cs,
        edit_cs = :edit_cs,
        create_cs = :create_cs,
        delete_cs = :delete_cs,
        w_access = :w_access,
        create_waste = :create_w,
        view_waste = :view_w,
        edit_waste = :edit_w,
        delete_waste = :delete_w,
        cc_access = :cc_access,
        create_cc = :create_cc,
        view_cc = :view_cc,
        edit_cc = :edit_cc,
        delete_cc = :delete_cc,
        user_access = :user_access,
        create_user = :create_user,
        view_user = :view_user,
        edit_user = :edit_user,
        delete_user = :delete_user,
        r_access = :r_access,
        d_access = :d_access,
        p_access = :p_access,
        role_access = :role_access,
        create_role = :create_role,
        view_role = :view_role,
        edit_role = :edit_role,
        delete_role = :delete_role,
        b_access = :b_access,
        create_b = :create_b,
        view_b = :view_b,
        edit_b = :edit_b,
        delete_b = :delete_b,
        pc_access = :pc_access

    WHERE
        role_id = :role_id;
";

    $stmt = $pdo->prepare($sql);

    if ($stmt) {
        // Continue binding other form fields to the prepared statement

$stmt->bindParam(':role_name', $role_name);
$stmt->bindParam(':fo_access', $fo_access);
$stmt->bindParam(':view_fo', $view_fo);
$stmt->bindParam(':edit_fo', $edit_fo);
$stmt->bindParam(':create_fo', $create_fo);
$stmt->bindParam(':delete_fo', $delete_fo);
$stmt->bindParam(':so_access', $so_access);
$stmt->bindParam(':view_so', $view_so);
$stmt->bindParam(':edit_so', $edit_so);
$stmt->bindParam(':create_so', $create_so);
$stmt->bindParam(':delete_so', $delete_so);
$stmt->bindParam(':odo_access', $odo_access);
$stmt->bindParam(':view_odo', $view_odo);
$stmt->bindParam(':edit_odo', $edit_odo);
$stmt->bindParam(':create_odo', $create_odo);
$stmt->bindParam(':delete_odo', $delete_odo);
$stmt->bindParam(':fc_access', $fc_access);
$stmt->bindParam(':view_fc', $view_fc);
$stmt->bindParam(':edit_fc', $edit_fc);
$stmt->bindParam(':create_fc', $create_fc);
$stmt->bindParam(':delete_fc', $delete_fc);
$stmt->bindParam(':sc_access', $sc_access);
$stmt->bindParam(':view_sc', $view_sc);
$stmt->bindParam(':edit_sc', $edit_sc);
$stmt->bindParam(':create_sc', $create_sc);
$stmt->bindParam(':delete_sc', $delete_sc);
$stmt->bindParam(':cs_access', $cs_access);
$stmt->bindParam(':view_cs', $view_cs);
$stmt->bindParam(':edit_cs', $edit_cs);
$stmt->bindParam(':create_cs', $create_cs);
$stmt->bindParam(':delete_cs', $delete_cs);
$stmt->bindParam(':w_access', $w_access);
$stmt->bindParam(':create_w', $create_w);
$stmt->bindParam(':view_w', $view_w);
$stmt->bindParam(':edit_w', $edit_w);
$stmt->bindParam(':delete_w', $delete_w);
$stmt->bindParam(':cc_access', $cc_access);
$stmt->bindParam(':create_cc', $create_cc);
$stmt->bindParam(':view_cc', $view_cc);
$stmt->bindParam(':edit_cc', $edit_cc);
$stmt->bindParam(':delete_cc', $delete_cc);
$stmt->bindParam(':user_access', $user_access);
$stmt->bindParam(':create_user', $create_user);
$stmt->bindParam(':view_user', $view_user);
$stmt->bindParam(':d_access', $d_access);
$stmt->bindParam(':edit_user', $edit_user);
$stmt->bindParam(':delete_user', $delete_user);
$stmt->bindParam(':r_access', $r_access);
$stmt->bindParam(':p_access', $p_access);
$stmt->bindParam(':role_id', $role_id);
$stmt->bindParam(':role_access', $role_access);
$stmt->bindParam(':create_role', $create_role);
$stmt->bindParam(':view_role', $view_role);
$stmt->bindParam(':edit_role', $edit_role);
$stmt->bindParam(':delete_role', $delete_role);
$stmt->bindParam(':b_access', $b_access);
$stmt->bindParam(':create_b', $create_b);
$stmt->bindParam(':view_b', $view_b);
$stmt->bindParam(':edit_b', $edit_b);
$stmt->bindParam(':delete_b', $delete_b);
$stmt->bindParam(':pc_access', $pc_access);



if (!$stmt->execute()) {
    header("Location: " . $u2 . urlencode('Something Wrong please try again later'));
} else {
    header("Location: " . $u1 . urlencode('Role Updated Successfully'));
}
    } else {
        // Handle the statement preparation error
        echo "Error: " . $pdo->errorInfo()[2];
    }
}
?>
