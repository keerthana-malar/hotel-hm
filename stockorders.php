<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<?php

include('header.php');
include('menu.php');
$orderSql = "SELECT * FROM `order` WHERE ordertype = '2'";
$orderData = $pdo->query($orderSql);
$logUser = $_SESSION['user'];
?>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="create-stockorder.php">
            <button class="btn btn-success" <?php if($rdata["so_create"]=="0"){echo "disabled";} ?> >Create</button>
        </a>
    </div>
    <?php if (!empty($_GET['succ'])): ?>
					  
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php  echo $_GET['succ'] ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                                        <?php endif ?>
                                        <?php if (!empty($_GET['err'])): ?>
                                          <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php  echo $_GET['err'] ?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>  
                                        <?php endif ?>
    <h2 class="mb-3">Stock Orders</h2>

    <?php

    if ($orderData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th> ID</th>
            <th> Branch</th>
            <th> Order Date</th>
            <th> Delivery Date</th>
            <th>priority</th>
            <th>Status</th>
            <th>Action</th>
            <th></th>
        </tr> </thead>";
        foreach ($orderData as $row) {
            $branchee = $pdo->query('SELECT name FROM `branch` WHERE id="'.$row["branchid"].'"');
            $branchee = $branchee->fetch(PDO::FETCH_ASSOC);
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $branchee['name']. "</td>";

            echo "<td>" . $row['orderdate'] . "</td>";
            echo "<td>" . $row['deliverydate'] . "</td>";
            echo "<td>" . $row['priority'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            
            echo "<td>
            <a href='edit-stockorder.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> | 
            <a href='delete-order.php?delete_id=" . $row['id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a> |
            <a href='view-stockorder.php?id=" . $row['id'] . "'><i class='typcn typcn-eye'></i></a>
        </td>";
        echo "<td>
                <a href='print-order.php?id=" . $row['id'] . "' target='_blank'><i class='typcn typcn-print'></i></a>
            </td>";
    



            echo "</tr> ";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "Error fetching data";
    }
    ?>

</div>

<?php
include('footer.php');
?>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this order?");
}
</script>