<?php
include('header.php');
include('menu.php');
$productSql = "SELECT * FROM product WHERE typeid = '2'";
$productData = $pdo->query($productSql);
$logUser = $_SESSION['user'];

// User access control 
if($rdata['edit_fc'] == '0'){$dslinkEdit = 'dis';}
if($rdata['view_fc'] == '0'){ $dslinkView = 'dis';}
if($rdata['delete_fc'] == '0'){$dslinkDelete = 'dis';}
?>
<style>
  .typcn {
    font-size: 18px;
  }
</style>
<div class="main-box">
  <div class="d-flex justify-content-end mb-5">
    <a href="create-product.php?type=2">
      <button class="btn btn-success" <?php if($rdata["create_fc"]=="0"){echo "disabled";} ?>>Create</button>
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
  <h2 class="mb-3">Stock Catalog</h2>

  <?php

  if ($productData) {
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover'>";
    echo "<thead> <tr>
            <th> ID</th>
            <th>product </th>
            <th>Unit</th>
            <th>Price (per Unit)</th>
            <th>Category</th>
            <th>Status</th>
            <th class='action-column'></th>
        </tr> </thead>";

    foreach ($productData as $row) {
      $typee = $pdo->query('SELECT name FROM `type` WHERE id="' . $row["typeid"] . '"');
      $typee = $typee->fetch(PDO::FETCH_ASSOC);
      $catee = $pdo->query('SELECT name FROM `category` WHERE id="' . $row["categoryid"] . '"');
      $catee = $catee->fetch(PDO::FETCH_ASSOC);
      $cusiee = $pdo->query('SELECT name FROM `cuisine` WHERE id="' . $row["cuisineid"] . '"');
      $cusiee = $cusiee->fetch(PDO::FETCH_ASSOC);
      echo "<tr>";
      echo "<td>" . $row['id'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['unit'] . "</td>";

      echo "<td>" . $row['price'] . "</td>";
    //   echo "<td>" . $typee['name'] . "</td>";
      echo "<td>" . $catee['name'] . "</td>";
    //   echo "<td>" . $cusiee['name'] . "</td>";
      echo "<td>" . $row['status'] . "</td>";
      echo "<td><a class='" . $dslinkView . "' href='view-product.php?id=" . $row['id'] . "&type=" . $row['typeid'] . "'><i class='typcn typcn-eye'></i></a> | ";
      echo "<a class='" . $dslinkEdit . "' href='edit-product.php?id=" . $row['id'] . "&type=" . $row['typeid'] . "'><i class='typcn typcn-edit'></i></a> | ";
      echo "<a href='delete-product.php?id=" . $row['id'] . "' class='text-danger ".$dslinkDelete."'><i class='  typcn typcn-trash'></a></td>";
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