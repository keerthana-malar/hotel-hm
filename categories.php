<?php
include('header.php');
include('menu.php');
$categorySql = "SELECT * FROM category ORDER BY id DESC";
$categoryData = $pdo->query($categorySql);

$logUser = $_SESSION['user'];
?>
<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="create-category.php">
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
    <h2 class="mb-3">Categories</h2>

    <?php

    if ($categoryData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th class='action-column'></th>
        </tr> </thead>";

        foreach ($categoryData as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            // Prevent Delete
            $valueToCheck = $row['id'];
            // Prepare the SELECT query
            $sqlDup = "SELECT * FROM `product` WHERE categoryid = :valueToCheck";

            // Prepare and execute the statement
            $stmtDup = $pdo->prepare($sqlDup);
            $stmtDup->bindParam(':valueToCheck', $valueToCheck);
            $stmtDup->execute();

            if ($stmtDup->rowCount() > 0) {
                $dslinkEditTdy = 'dis';
            } else {
                $dslinkEditTdy = '';
            }
            echo "<td>
            <a href='edit-category.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> |
            <a href='delete-category.php?delete_id=" . $row['id'] . "' class='text-danger ".$dslinkEditTdy."' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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