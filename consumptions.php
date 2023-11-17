<?php
include('header.php');
include('menu.php');
$consumptionSql = "SELECT * FROM `consumption` WHERE branchid = $logbranch";
$consumptionData = $pdo->query($consumptionSql);
$logUser = $_SESSION['user'];

// User access control 
if($rdata['edit_cs'] == '0'){$dslinkEdit = 'dis';}
if($rdata['view_cs'] == '0'){ $dslinkView = 'dis';}
if($rdata['delete_cs'] == '0'){$dslinkDelete = 'dis';}
?>
<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="create-consumption.php">
            <button class="btn btn-success" <?php if($rdata["create_cs"]=="0"){echo "disabled";} ?>>Create</button>
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
    <h2 class="mb-3">Closing Stock</h2>

    <?php

    if ($consumptionData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th> ID</th>
            <th>  Date</th>
            <th> Branch</th>
            <th class='action-column'></th>
        </tr> </thead>";

        foreach ($consumptionData as $row) {
            $branchee = $pdo->query('SELECT name FROM `branch` WHERE id="'.$row["branchid"].'"');
            $branchee = $branchee->fetch(PDO::FETCH_ASSOC);
            
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['date_created'] . "</td>";
            echo "<td>" . $branchee['name']. "</td>";

            
            echo "<td>
            <a class='".$dslinkView."' href='view-consumption.php?id=" . $row['id'] ."'><i class='typcn typcn-eye'></i></a> |
            <a class='".$dslinkEdit."' href='edit-consumption.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> | 
            <a href='delete-consumption.php?delete_id=" . $row['id'] . "' class='text-danger ".$dslinkDelete."' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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

<?php
include('footer.php');
?>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this order?");
}
</script>