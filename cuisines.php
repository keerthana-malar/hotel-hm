<?php
include('header.php');
include('menu.php');
$cuisineSql = "SELECT * FROM cuisine ORDER BY id DESC";
$cuisineData = $pdo->query($cuisineSql);

$logUser = $_SESSION['user'];
?>
<style>
  .typcn {
    font-size: 22px; 
  }
</style>
<div class="main-box">
<?php if (!empty($_GET['succ'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>
                <?php echo $_GET['succ'] ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>
    <?php if (!empty($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>
                <?php echo $_GET['err'] ?>
            </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>
    <div class="d-flex justify-content-end mb-5">
        <a href="create-cuisine.php">
            <button class="btn btn-success">Create</button>
        </a>
    </div>
    <h2 class="mb-3">cuisines</h2>

    <?php

    if ($cuisineData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th class='action-column'></th>

        </tr> </thead>";

        foreach ($cuisineData as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
            <a href='edit-cuisine.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></i></a> |
            <a href='delete-cuisine.php?delete_id=" . $row['id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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
    return confirm("Are you sure you want to delete this cuisine?");
}
</script>