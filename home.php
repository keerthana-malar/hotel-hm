<?php

include('header.php');
include('menu.php');

$logUser = $_SESSION['user'];
$logName = $logUser['name'];
?>

<div class="main-box">
    <h1 class="text-center">Welcome <?php echo $logName ?></h1>
</div>

<?php
include('footer.php');
?>