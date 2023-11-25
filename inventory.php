<?php
include('header.php');
include('menu.php');
$stockSql = "SELECT * FROM `stock` $logbranchA ORDER BY id DESC";
$stockData = $pdo->query($stockSql);
$logUser = $_SESSION['user'];

// User access control 
if($rdata['edit_sc'] == '0'){$dslinkEdit = 'dis';}
if($rdata['view_sc'] == '0'){ $dslinkView = 'dis';}
if($rdata['delete_sc'] == '0'){$dslinkDelete = 'dis';}
?>
<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<div class="main-box">
    <!-- <div class="d-flex justify-content-end mb-5">
        <a href="create-stock.php">
            <button class="btn btn-success">Create</button>
        </a>
    </div> -->
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
    <h2 class="mb-3">View Stocks</h2>

    <?php

    if ($stockData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th> ID</th>
            <th> Branch</th>
            <th class='action-column'>Action</th>
        </tr> </thead>";

        foreach ($stockData as $row) {
            $branchee = $pdo->query('SELECT name FROM `branch` WHERE id="'.$row["branchid"].'"');
            $branchee = $branchee->fetch(PDO::FETCH_ASSOC);
            
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $branchee['name']. "</td>";

            
            echo "<td>
            <a class='".$dslinkView."' href='view-inventory.php?id=" . $row['id'] ."'><i class='typcn typcn-eye'></i></a>
        </td>";
    



            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "Error fetching data";
    }
    
    ?>

</div>


<!-- edit and delete echo  -->

            <!-- <a href='delete-stock.php?delete_id=" . $row['id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a> -->
            
<?php
include('footer.php');
?>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this stock?");
}
</script>