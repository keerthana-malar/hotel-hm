<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<?php
include('header.php');
include('menu.php');
$branchSql = "SELECT * FROM branch";
$branchData = $pdo->query($branchSql);

$logUser = $_SESSION['user'];
?>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="create-branch.php">
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
    <h2 class="mb-3">Branches</h2>

    <?php

    if ($branchData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>Branch ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr> </thead>";

        foreach ($branchData as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
            <a href='edit-branch.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> |
            <a href='delete-branch.php?delete_id=" . $row['id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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