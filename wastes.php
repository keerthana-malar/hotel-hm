<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<?php
include('header.php');
include('menu.php');
$wasteSql = "SELECT * FROM `waste`";
$wasteData = $pdo->query($wasteSql);

$logUser = $_SESSION['user'];
?>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="create-waste.php">
            <button class="btn btn-success">Create</button>
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
    <h2 class="mb-3">Wastes</h2>

    <?php

    if ($wasteData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th> ID</th>
            <th> Branch</th>
            <th>  Date</th>
            <th> Waste Amount</th>

            <th>Action</th>
        </tr> </thead>";

        foreach ($wasteData as $row) {
            $branchee = $pdo->query('SELECT name FROM `branch` WHERE id="'.$row["branchid"].'"');
            $branchee = $branchee->fetch(PDO::FETCH_ASSOC);
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $branchee['name']. "</td>";

            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['waste_amount'] . "</td>";

            
            echo "<td>
            <a href='view-waste.php?id=" . $row['id'] . "'><i class='typcn typcn-eye'></i></a> | 
            <a href='edit-waste.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> | 
            <a href='delete-waste.php?delete_id=" . $row['id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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