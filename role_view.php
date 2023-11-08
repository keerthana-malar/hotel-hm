<?php

include('header.php');
include('menu.php');

$counterSql = "SELECT * FROM role";
$counterData = $pdo->query($counterSql);
$logUser = $_SESSION['user'];

// User access control 
if ($rdata['edit_role'] == '0') {
    $dslinkEdit = 'dis';
}
if ($rdata['view_role'] == '0') {
    $dslinkView = 'dis';
}
if ($rdata['delete_role'] == '0') {
    $dslinkDelete = 'dis';
}
?>
<style>
    .typcn {
        font-size: 22px
    }
</style>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="role.php">
            <button class="btn btn-success" <?php if ($rdata["create_role"] == "0") {
                echo "disabled";
            } ?>>Create</button>
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
            <th class='action-column'></th>
        </tr> </thead>";
        foreach ($counterData as $row) {
            if ($row["role_id"] == "1" || $row["role_id"] == "2" || $row["role_id"] == "3" || $row["role_id"] == "4") {
                $admin = "admin-role";
            } else {
                $admin = "";
            }
            echo "<tr>";
            echo "<td>" . $row['role_id'] . "</td>";
            echo "<td>" . $row['role_name'] . "</td>";
            echo "<td>
            <a class='" . $dslinkEdit . "' id='" . $admin . "' href='role_edit.php?role_id=" . $row['role_id'] . "'><i class=' typcn typcn-edit'></i></i></a> |
            <a id='" . $admin . "' href='role_delete.php?delete_id=" . $row['role_id'] . "' class='text-danger " . $dslinkDelete . "' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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

<script>
    var roleAcc = document.querySelectorAll("#admin-role");
    var icon = document.querySelectorAll("#admin-role .typcn");

    roleAcc.forEach((f) => {
        f.removeAttribute("href");
        f.removeAttribute("onclick");
    })
    icon.forEach((fn) => {
        fn.style.color = "grey";
    })
</script>

<?php
include('footer.php');
?>