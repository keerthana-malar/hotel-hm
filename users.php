<style>
  .typcn {
    font-size: 22px;
  }
</style>
<?php

include('header.php');
include('menu.php');


$userSql = "SELECT u.*, r.role_name AS role_name FROM user u
            LEFT JOIN role r ON u.role = r.role_id";
$userData = $pdo->query($userSql);
$logUser = $_SESSION['user'];


if($rdata['edit_user'] == '0'){$dslinkEdit = 'dis';}
if($rdata['view_user'] == '0'){ $dslinkView = 'dis';}
if($rdata['delete_user'] == '0'){$dslinkDelete = 'dis';}

?>
<div class="main-box">
  <div class="d-flex justify-content-end mb-5">
    <a href="create-user.php">
      <button class="btn btn-success" <?php if($rdata["create_user"]=="0"){echo "disabled";} ?>>Create</button>
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
  <h2 class="mb-3">Users</h2>

  <?php

  if ($userData) {
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover'>";
    echo "<thead> <tr>
            <th>User Id</th>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Branch</th>
            <th class='action-column'></th>
            
        </tr> </thead>";

    foreach ($userData as $row) {
      $branchee = $pdo->query('SELECT name FROM `branch` WHERE id="' . $row["branch"] . '"');
      $branchee = $branchee->fetch(PDO::FETCH_ASSOC);
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['username'] . "</td>";
      echo "<td>" . $row['role_name'] . "</td>";
      echo "<td>" . $branchee['name'] . "</td>";
      echo "<td>
                <a class='".$dslinkEdit."' href='edit-user.php?id=" . $row['id'] . "'><i class=' typcn typcn-edit'></i></a> |
                <a href='delete-user.php?delete_id=" . $row['id'] . "' class='text-danger ".$dslinkDelete."' onclick='return confirmDelete()'><i class='  typcn typcn-trash'></i></a>
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