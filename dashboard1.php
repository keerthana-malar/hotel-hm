<style>
    .dashboard-item {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
        border-radius: 20px;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
        font-size: 1em;
    }

    .box {
        padding: 10px;
        width: 80%;
        height: 100px;
    }

    .box p {
        font-size: 100px;
    }

    .icon {
        color: #000000;
    }

    .fas,
    .fa-solid {
        color: #fff;
        filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.1));
    }

    .violet {
        background-color: #D89216;
    }

    .pink {
        background-color: #46B5D1;
    }

    .red {
        background-color: #C62A88;
    }

    .green {
        background-color: #511845;
    }

    .blue {
        background-color: #E43F5A;
    }

    .orange {
        background-color: #590995;
        590995
    }

    .black {
        background-color: #00337C;
    }

    .yellow {
        background-color: #FF5733;
    }

    .inner {
        display: flex;
        gap: 1px;
        color: #fff;
    }

    .fa-shopping-cart {
        size: 100px;
    }

    .dashboard-item .icon i {
        font-size: 5em;
    }

    .dashboard-item .inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-item .inner h3 {
        margin-bottom: 10px;
    }

    .dashboard-item .icon {
        margin-left: 10px;
    }

    .inner p {
        font-weight: bold;
        font-size: 30px;
        margin-top: 20px;
    }
</style>
<?php
include('header.php'); // Include your header file
include('menu.php'); // Include your menu file
// Include your database connection file

// Calculate total orders
if ($udata['id'] == "1") {
    $totalOrdersQuery = "SELECT COUNT(*) as totalOrders FROM `order`";
} else {
    $totalOrdersQuery = "SELECT COUNT(*) as totalOrders FROM `order` WHERE branchid = $userBranch";
}
$totalOrdersResult = $pdo->query($totalOrdersQuery)->fetch(PDO::FETCH_ASSOC);
$totalOrders = $totalOrdersResult['totalOrders'];

// Calculate total stocks
$totalStocksQuery = "SELECT COUNT(*) as totalStocks FROM `stock`";
$totalStocksResult = $pdo->query($totalStocksQuery)->fetch(PDO::FETCH_ASSOC);
$totalStocks = $totalStocksResult['totalStocks'];

// Calculate total Branches
$totalbranchQuery = "SELECT COUNT(*) as totalbranch FROM `branch`";
$totalbranchResult = $pdo->query($totalbranchQuery)->fetch(PDO::FETCH_ASSOC);
$totalbranch = $totalbranchResult['totalbranch'];

// Calculate total wastes
if ($udata['id'] == "1") {
    $totalWastesQuery = "SELECT COUNT(*) as totalWastes FROM `waste`";
} else {
    $totalWastesQuery = "SELECT COUNT(*) as totalWastes FROM `waste` WHERE branchid = $userBranch";
}
$totalWastesResult = $pdo->query($totalWastesQuery)->fetch(PDO::FETCH_ASSOC);
$totalWastes = $totalWastesResult['totalWastes'];


$currentDay = date('Y-m-d');

// Calculate total waste amount for today
if ($udata['id'] == "1") {
    $totalWasteAmountQuery = "SELECT SUM(waste_amount) as totalWasteAmount FROM `waste` WHERE DATE(date) = :today";
} else {
    $totalWasteAmountQuery = "SELECT SUM(waste_amount) as totalWasteAmount FROM `waste` WHERE DATE(date) = :today AND branchid = $userBranch";
}
$totalWasteAmountResult = $pdo->prepare($totalWasteAmountQuery);
$totalWasteAmountResult->execute(['today' => $currentDay]);
$totalWasteAmount = $totalWasteAmountResult->fetch(PDO::FETCH_ASSOC)['totalWasteAmount'];

// Calculate total orders for today
$today = date('Y-m-d');
if ($udata['id'] == "1") {
    $todayOrdersQuery = "SELECT COUNT(*) as todayTotalOrders FROM `order` WHERE orderdate = :today";
} else {
    $todayOrdersQuery = "SELECT COUNT(*) as todayTotalOrders FROM `order` WHERE orderdate = :today AND branchid = $userBranch";
}
$todayOrdersResult = $pdo->prepare($todayOrdersQuery);
$todayOrdersResult->execute(['today' => $today]);
$todayTotalOrders = $todayOrdersResult->fetch(PDO::FETCH_ASSOC)['todayTotalOrders'];

// Calculate total complete orders for today
if ($udata['id'] == "1") {
    $todayCompletedOrdersQuery = "SELECT COUNT(*) as todayCompletedOrders FROM `order` WHERE DATE(orderdate) = :today AND status = 'Delivered'";
} else {
    $todayCompletedOrdersQuery = "SELECT COUNT(*) as todayCompletedOrders FROM `order` WHERE DATE(orderdate) = :today AND status = 'Delivered' AND branchid = $userBranch";
}
$todayCompletedOrdersResult = $pdo->prepare($todayCompletedOrdersQuery);
$todayCompletedOrdersResult->execute(['today' => $today]);
$todayCompletedOrders = $todayCompletedOrdersResult->fetch(PDO::FETCH_ASSOC)['todayCompletedOrders'];

// Calculate total pending orders for today
if ($udata['id'] == "1") {
    $todayPendingOrdersQuery = "SELECT COUNT(*) as todayPendingOrders FROM `order` WHERE DATE(orderdate) = :today AND (status = 'Created' OR status = 'Accepted' )";
} else {
    $todayPendingOrdersQuery = "SELECT COUNT(*) as todayPendingOrders FROM `order` WHERE DATE(orderdate) = :today AND (status = 'Created' OR status = 'Accepted' AND branchid = $userBranch)";
}
$todayPendingOrdersResult = $pdo->prepare($todayPendingOrdersQuery);
$todayPendingOrdersResult->execute(['today' => $today]);
$todayPendingOrders = $todayPendingOrdersResult->fetch(PDO::FETCH_ASSOC)['todayPendingOrders'];

// Calculate total stocks for today
if ($udata['id'] == "1") {
    $todayStocksQuery = "SELECT COUNT(*) as todayTotalStocks FROM `stock` WHERE DATE(date_created) = :today";
} else {
    $todayStocksQuery = "SELECT COUNT(*) as todayTotalStocks FROM `stock` WHERE DATE(date_created) = :today AND branchid = $userBranch";
}
$todayStocksResult = $pdo->prepare($todayStocksQuery);
$todayStocksResult->execute(['today' => $today]);
$todayTotalStocks = $todayStocksResult->fetch(PDO::FETCH_ASSOC)['todayTotalStocks'];

// Calculate total wastes for today
if ($udata['id'] == "1") {
    $todayWastesQuery = "SELECT COUNT(*) as todayTotalWastes FROM `waste` WHERE DATE(date) = :today";
} else {
    $todayWastesQuery = "SELECT COUNT(*) as todayTotalWastes FROM `waste` WHERE DATE(date) = :today AND branchid = $userBranch";
}
$todayWastesResult = $pdo->prepare($todayWastesQuery);
$todayWastesResult->execute(['today' => $today]);
$todayTotalWastes = $todayWastesResult->fetch(PDO::FETCH_ASSOC)['todayTotalWastes'];


?>
<div class="main-box">
    <h2 class="mb-3">Dashboard</h2>

    <hr>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php 
if($rdata['d_access'] == '1'){ ?>
    <div class="dashboard-container1">
    <h3>Total's</h3><br>
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="dashboard-item violet">
                <div class="inner">
                    <div>
                        <h3> Total Orders</h3>
                        <p>
                            <?= $totalOrders ?>
                        </p>
                    </div>
                    <div>
                        <span class="icon">
                            <a href="orders.php">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-item pink">
                <div class="inner">
                    <div>
                        <h3> Total Branches</h3>
                        <p>
                            <?= $totalbranch ?>
                        </p>
                    </div>
                    <div><span class="icon">
                            <a href="branchs.php">
                                <i class="fa-solid fa-box-open"></i> </a>
                        </span></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-item red">
                <div class="inner">
                    <div>
                        <h3> Total Waste Cost</h3>
                        <p>
                            Rs.
                            <?= $totalWasteAmount ?>
                        </p>
                    </div>
                    <div> <span class="icon">
                            <a href="wastes.php">
                                <i class="fa-solid fa-dumpster"></i> </a>
                        </span></div>
                </div>
            </div>
        </div>

    </div>
    <br><br>
    <h3>Today's</h3><br>
    <div class="row mb-5">
        <!-- <div class="col-1"></div> -->
        <div class="col-md-4 mb-3">
            <div class="dashboard-item green ">
                <div class="inner">
                    <div>
                        <h3> Orders</h3>
                        <p>
                            <?= $todayTotalOrders ?>
                        </p>
                    </div>
                    <div>
                        <span class="icon">
                            <a href="orders.php">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-item blue">
                <div class="inner">
                    <div>
                        <h3>Completed Orders</h3>
                        <p>
                            <?= $todayCompletedOrders ?>
                        </p>
                    </div>
                    <div>
                        <span class="icon">
                            <a href="orders.php">
                                <i class="fa-solid fa-calendar-check"></i> </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-item orange">
                <div class="inner">
                    <div>
                        <h3>Pending Orders</h3>
                        <p>
                            <?= $todayPendingOrders ?>
                        </p>
                    </div>
                    <div>
                        <span class="icon">
                            <a href="orders.php">
                                <i class="fa-solid fa-file-excel"></i> </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4 mb-3">
            <div class="dashboard-item yellow">
                <div class="inner">
                    <div>
                                <h3>Stock</h3>
                                <p>
                                <?= $todayTotalStocks ?>
                             </div>
                    <div>
                    <span class="icon">
                        <a href="stocks.php">
                        <i class="fa-solid fa-boxes-packing"></i>                            </a>
                    </span>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="col-md-4">
            <div class="dashboard-item black">
                <div class="inner">
                    <div>
                                <h3>Waste</h3>
                                <p>
                                <?= $todayTotalWastes ?>
                             </div>
                    <div>
                    <span class="icon">
                        <a href="wastes.php">
                        <i class="fa-solid fa-dumpster-fire"></i>                            </a>
                    </span>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-1"></div>
    </div>
    <br><br>
    <div class="dashboard-container">

        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="totalOrdersPieChart" width="250" height="250"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="todayOrdersPieChart" width="250" height="250"></canvas>
                </div>
            </div>
            <br><br>
            <div class="col-md-12">

                <div class="chart-container">
                    <canvas id="todayDetailsBarChart" width="250" height="250"></canvas>
                </div>
            </div>


        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var totalOrdersPieChart = new Chart(document.getElementById('totalOrdersPieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Total Orders', 'Total Wastes'],
                        datasets: [{
                            data: [<?= $totalOrders ?>, <?= $totalWastes ?>],
                            backgroundColor: ['#DF204A', '#4ADF20', '#204ADF']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                var todayOrdersPieChart = new Chart(document.getElementById('todayOrdersPieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Today\'s Completed Orders', 'Today\'s Pending Orders'],
                        datasets: [{
                            data: [<?= $todayCompletedOrders ?>, <?= $todayPendingOrders ?>],
                            backgroundColor: ['#36A2EB', '#FFCE56']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                // Create a bar chart for today's details
                var todayDetailsBarChart = new Chart(document.getElementById('todayDetailsBarChart'), {
                    type: 'bar',
                    data: {
                        labels: ['Today\'s Orders', 'Today\'s Wastes'],
                        datasets: [{
                            label: 'Today\'s Details',
                            data: [<?= $todayTotalOrders ?>, <?= $todayTotalWastes ?>, 0],
                            backgroundColor: ['#FF6384', '#C70039'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: 10
                            }
                        }
                    }
                });
            });


        </script>
    </div>
<?php }else{ ?>
<div class="text-center ">
    <h1 class="mb-4">Magizham Hotel</h1>
    <h3>Welcome <?php echo $logName; ?></h3>
    <img class="img-fluid" src="images/chef.gif">
</div>
    <?php } ?>

        <?php
        include('footer.php'); // Include your footer file
        ?>