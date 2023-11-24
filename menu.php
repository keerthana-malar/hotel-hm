<?php
$logUser = $_SESSION['user'];
$logName = $logUser['name'];
$logid = $logUser['id'];
$logbranch = $logUser['branch'];

// $logbranch = 1;
// $logid = 1;
// $logName = "Admin";
// $logUser = 1;
$currentPage = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
function menuActive($val, $val1){
    
    $currentPage = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
    if(strpos($currentPage, $val) !== false && strpos($currentPage, $val1) === false){
        echo "active";
    }
}
function menuShow($val, $val1){
    $currentPage = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
    if(strpos($currentPage, $val) !== false && strpos($currentPage, $val1) === false){
        echo 'show';
    }
}
function menuActive1($val){
    
    $currentPage = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
    if(strpos($currentPage, $val) !== false){
        echo "active";
    }
}
function menuShow1($val){
    $currentPage = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
    if(strpos($currentPage, $val) !== false){
        echo 'show';
    }
}

$type = $_GET['type'];

if($logbranch == '1') {
    $logbranchQ = "";
}else{
    $logbranchQ = "AND branchid = ". $logbranch;
}

if($logbranch == '1') {
    $logbranchA = "";
}else{
    $logbranchA = "WHERE branchid = ". $logbranch;
}

require("db.php");

// User Data 
$userSql = "SELECT * FROM user WHERE id= :id";
$ustmt = $pdo->prepare($userSql);
$ustmt->bindParam(':id', $logid);
$ustmt->execute();
$udata = $ustmt->fetch(PDO::FETCH_ASSOC);

// Role 
$roleSql = "SELECT * FROM role WHERE role_id = :r_id";
$rstmt = $pdo->prepare($roleSql);
$rstmt->bindParam(':r_id', $udata['role']);
$rstmt->execute();
$rdata = $rstmt->fetch(PDO::FETCH_ASSOC);

$userBranch = $udata ["branch"];
 $branchSql3 = "SELECT * FROM branch WHERE status = 'Active' AND id=$userBranch";
 $brancData = $pdo->query($branchSql3);
 $brancData = $brancData->fetch(PDO::FETCH_ASSOC);
?>

<style>
    .user-icon{
        display: flex;
        justify-content: center;align-items: center;
    }
    .user-icon span{
        font-size: 18px;
    }
    .user-icon p{
        margin-top: 25px;
        font-weight: bold;
    }
    .user-box{
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }
</style>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="" href="index.html"><img src="images/Magizham Logo.png" alt="logo" height="30"/></a>
                <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/Magizham_Logo.svg" alt="logo" /></a>
                <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize">
                    <span class="typcn typcn-th-menu"></span>
                </button>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle  pl-0 pr-0" href="#" data-toggle="dropdown" id="profileDropdown">
                            <div class="user-icon">
                            <span class="typcn typcn-user-outline mr-0"></span>
                            <div><p><?php echo  $udata['name'] ?></p></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <div class="user-box">
                            <form action="logout.php" method="get">
                                <button class="btn btn-danger" type="submit">Logout</button>
                            </form>
                            </div>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="typcn typcn-th-menu"></span>
                </button>
            </div>
        </nav> 
        <div class="container-fluid page-body-wrapper">

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <div class="d-flex sidebar-profile">
                            <div class="sidebar-profile-image">
                                <img src="images/user.png" alt="image">
                                <span class="sidebar-status-indicator"></span>
                            </div>
                            <div class="sidebar-profile-name">
                                <p class="sidebar-name">
                                   <?php echo $logName ?>
                                </p>
                                <p class="sidebar-designation">
                                <?php echo $brancData["name"] ?>
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item" <?php if($rdata["d_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" href="dashboard1.php">
                            <i class="typcn typcn-device-desktop menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    
                    
                    <li class="nav-item set <?php menuActive('food','catalog'); menuActive('stock', 'catalog'); menuActive1('outdoor'); menuActive1('production'); ?>" <?php if($rdata["fo_access"]!=="1" && $rdata["so_access"]!=="1" && $rdata["odo_access"]!=="1" && $rdata["pc_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" data-toggle="collapse" href="#us" aria-expanded="false" aria-controls="">
                            <i class=" typcn typcn-media-eject-outline menu menu-icon"></i>
                            <span class="menu-title">Orders</span>
                            <i class="typcn typcn-chevron-right menu-arrow"></i>
                        </a>
                        <div class="collapse <?php menuShow('food', 'catalog'); menuShow('stock', 'catalog'); menuShow1('outdoor'); menuShow1('production'); ?>" id="us">
                            <ul class="nav flex-column sub-menu">
                                <li <?php if($rdata["fo_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive('food', 'catalog'); ?>" href="foodorders.php">Food Order</a></li>
                                <li <?php if($rdata["so_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive('stock', 'catalog'); ?>" href="stockorders.php">Stock Order</a></li>
                                <li <?php if($rdata["odo_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('outdoor'); ?>" href="outdoororders.php">Outdoor Order</a></li>
                                <li <?php if($rdata["pc_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('production'); ?>" href="production.php">Production Chart</a></li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item <?php if($type == 1){echo 'active';} ?>" <?php if($rdata["fc_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" href="foodcatalog.php">
                            <i class="typcn typcn-th-list-outline menu-icon"></i>
                            <span class="menu-title">Food Catalog</span>
                        </a>
                    </li>
                    <li class="nav-item set <?php if($type == 2){echo 'active';} menuActive1('inventory'); menuActive1('consumption'); ?>" <?php if($rdata["sc_access"]!=="1" && $rdata["cc_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" data-toggle="collapse" href="#use" aria-expanded="false" aria-controls="">
                            <i class="  typcn typcn-folder-open menu-icon"></i>
                            <span class="menu-title">Stock Management</span>
                            <i class="typcn typcn-chevron-right menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if($type == 2){echo 'show';} menuShow1('inventory'); menuShow1('consumption'); ?>" id="use">
                            <ul class="nav flex-column sub-menu">
                                <li <?php if($rdata["sc_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php if($type == 2){echo 'active';} ?>" href="stockcatalog.php">Stock Catalog</a></li>
                                <li <?php if($rdata["vsc_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('inventory'); ?>" href="inventory.php">View Stock</a></li>
                                <li <?php if($rdata["cs_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('consumption'); ?>" href="consumptions.php">Closing stock</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item <?php menuActive1('waste'); ?>" <?php if($rdata["w_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" href="wastes.php">
                            <i class=" typcn typcn-document-delete menu-icon"></i>
                            <span class="menu-title">Wastages</span>
                        </a>
                    </li>
                    <li class="nav-item <?php menuActive1('counter'); ?>" <?php if($rdata["cc_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" href="counter.php">
                            <i class="typcn typcn-calculator menu-icon"></i>
                            <span class="menu-title">Counter Closing</span>
                        </a>
                    </li>
                    <li class="nav-item set" <?php if($rdata["r_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" data-toggle="collapse" href="#manane" aria-expanded="false" aria-controls="">
                            <i class="  typcn typcn-folder-open menu-icon"></i>
                            <span class="menu-title">Reports</span>
                            <i class="typcn typcn-chevron-right menu-arrow"></i>
                        </a>
                        <div class="collapse" id="manane">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link sub" href="order-report.php">Order Reports</a></li>
                                <li class="nav-item"> <a class="nav-link sub" href="stock-report.php">Stock Reports</a></li>
                                <li class="nav-item"> <a class="nav-link sub" href="waste-report.php">Waste Reports</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item set <?php menuActive1('role'); menuActive1('user'); ?>" <?php if($rdata["user_access"]!=="1" && $rdata["role_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" data-toggle="collapse" href="#user-manane" aria-expanded="false" aria-controls="">
                            <i class="  typcn typcn-user-add menu-icon"></i>
                            <span class="menu-title">User Management</span>
                            <i class="typcn typcn-chevron-right menu-arrow"></i>
                        </a>
                        <div class="collapse <?php menuShow1('role'); menuShow1('user'); ?>" id="user-manane">
                            <ul class="nav flex-column sub-menu">
                                <li <?php if($rdata["user_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('user'); ?>" href="users.php">Users</a></li>
                                <li <?php if($rdata["role_access"]!=="1"){echo "hidden";} ?> class="nav-item"> <a class="nav-link sub <?php menuActive1('role'); ?>" href="role_view.php">Roles</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item set <?php menuActive1('category'); menuActive1('cuisine'); ?>" <?php if($rdata["p_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" data-toggle="collapse" href="#user" aria-expanded="false" aria-controls="">
                            <i class="  typcn typcn-upload menu-icon"></i>
                            <span class="menu-title">products Configuration</span>
                            <i class="typcn typcn-chevron-right menu-arrow"></i>
                        </a>
                        <div class="collapse <?php menuShow1('category'); menuShow1('cuisine'); ?>" id="user">
                            <ul class="nav flex-column sub-menu">
                            <!-- <li class="nav-item"> <a class="nav-link sub" href="products.php">products</a></li> -->
                                <!-- <li class="nav-item"> <a class="nav-link sub" href="types.php">Food Type</a></li> -->
                                <li class="nav-item"> <a class="nav-link sub <?php menuActive1('category'); ?> " href="categories.php">Food Category</a></li>
                                <li class="nav-item"> <a class="nav-link sub <?php menuActive1('cuisine'); ?> " href="cuisines.php">Cuisine</a></li>
                            </ul>
                        </div>
                    </li>
                   
                    
                    <li class="nav-item <?php menuActive1('branch'); ?>" <?php if($rdata["b_access"]!=="1"){
                        echo "hidden";
                    } ?>>
                        <a class="nav-link" href="branchs.php">
                            <i class=" typcn typcn-home-outline menu-icon"></i>
                            <span class="menu-title">Branch</span>
                        </a>
                    </li>
                     
                 
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">