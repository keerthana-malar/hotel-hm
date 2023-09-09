<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include('header.php');
include('menu.php');
require('db.php');

$counterSql = "SELECT * FROM role";
$counterData = $pdo->query($counterSql);
$logUser = $_SESSION['user'];
?>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="role.php">
            <button class="btn btn-success">Create</button>
        </a>
    </div>
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
    <h2 class="mb-3">Role</h2>
    <?php
    if ($counterData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr> </thead>";
        foreach ($counterData as $row) {
            echo "<tr>";
            echo "<td>" . $row['role_id'] . "</td>";
            echo "<td>" . $row['role_name'] . "</td>";
            echo "<td>
            <a href='role_edit.php?role_id=" . $row['role_id'] . "'><i class=' typcn typcn-edit'></i></i></a> |
            <a href='role_delete.php?delete_id=" . $row['role_id'] . "' class='text-danger' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this role?");
}
</script>

<?php
include('footer.php');
?>