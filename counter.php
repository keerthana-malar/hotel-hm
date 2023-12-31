<?php
include('header.php');
include('menu.php');

$counterSql = "SELECT * FROM counter ORDER BY id DESC";
$counterData = $pdo->query($counterSql);
$logUser = $_SESSION['user'];

// User access control 
if($rdata['edit_cc'] == '0'){$dslinkEdit = 'dis';}
if($rdata['view_cc'] == '0'){ $dslinkView = 'dis';}
if($rdata['delete_cc'] == '0'){$dslinkDelete = 'dis';}
?>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">
        <a href="counter_create.php">
            <button class="btn btn-success" <?php if($rdata["create_cc"]=="0"){echo "disabled";} ?>>Create</button>
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
    <h2 class="mb-3">Counter Closing</h2>
    <?php
    if ($counterData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Branch</th>
            <th>Shortage</th>
            <th>Excess</th>
            <th >Accounts</th>
            <th >Status</th>
            <th class='action-column'></th>
        </tr> </thead>";
        foreach ($counterData as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['branch'] . "</td>";
            echo "<td>" . $row['shortage'] . "</td>";
            echo "<td>" . $row['excess'] . "</td>";
            echo "<td>" . $row['acc_dep'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
            <a class='".$dslinkEdit."'href='counter_edit.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></i></a> |
            <a href='counter_delete.php?delete_id=" . $row['id'] . "' class='text-danger ".$dslinkDelete."' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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
    return confirm("Are you sure you want to delete this?");
}
</script>

<?php
include('footer.php');
?>