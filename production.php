<?php
include('header.php');
include('menu.php');
// User access control 
// if($rdata['edit_role'] == '0'){$dslinkEdit = 'dis';}
// if($rdata['view_role'] == '0'){ $dslinkView = 'dis';}
// if($rdata['delete_role'] == '0'){$dslinkDelete = 'dis';}

// Get production chart data from db 
$pcSql = "SELECT * FROM pro_chart";
$pcData = $pdo->query($pcSql);
$logUser = $_SESSION['user'];

?>
<style>
    .typcn{
        font-size:22px
    }
</style>
<div class="main-box">
    <div class="d-flex justify-content-end mb-5">     
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
    <h2 class="mb-3">Production Chart</h2>
    <?php
    if ($pcData) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-hover'>";
        echo "<thead> <tr>
            <th>ID</th>
            <th>Production Date</th>
            <th class='action-column'></th>
        </tr> </thead>";
        foreach ($pcData as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>
            <a class='".$dslinkEdit."' href='production-view.php?id=" . $row['id'] . "'><i class=' typcn typcn-eye'></i></i></a> 
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
    var roleAcc =document.querySelectorAll("#admin-role");
    var icon = document.querySelectorAll("#admin-role .typcn");

    roleAcc.forEach((f)=>{
        f.removeAttribute("href");
        f.removeAttribute("onclick");
    })
    icon.forEach((fn)=>{
        fn.style.color="grey";
    })
</script>

<?php
include('footer.php');
?>