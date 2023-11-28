<?php
include('header.php');
include('menu.php');

require 'vendor/autoload.php';

// Check if the form is submitted and the "import_file" key is set in the $_FILES array
if (isset($_POST['submit_import']) && isset($_FILES['import_file'])) {
  // Specify the absolute path to the upload directory
  $uploadDir = __DIR__ . '/uploads/';
  // File upload path
  $uploadFile = $uploadDir . basename($_FILES['import_file']['name']);
  // Check if the file has a valid extension
  $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
  $allowedExtensions = array('xlsx');
  if (!in_array($fileExtension, $allowedExtensions)) {
    echo 'Invalid file format. Only Excel files (xlsx) are allowed.';
    exit;
  }
  // Create the upload directory if it doesn't exist
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }
  // Move the uploaded file to the specified directory
  if (move_uploaded_file($_FILES['import_file']['tmp_name'], $uploadFile)) {
    // Call the importProducts function with the file path
    if (importProducts($uploadFile, $pdo)) {
      echo 'File has been uploaded and processed successfully.';
    } else {
      echo 'Error processing the file.';
    }
  } else {
    echo 'Error uploading the file.';
  }
}

// Fetch the product data after import
$productSql = "SELECT * FROM product WHERE typeid = '2' ORDER BY id DESC";
$productData = $pdo->query($productSql);
$logUser = $_SESSION['user'];


// User access control 
if ($rdata['edit_sc'] == '0') {
  $dslinkEdit = 'dis';
}
if ($rdata['view_sc'] == '0') {
  $dslinkView = 'dis';
}
if ($rdata['delete_sc'] == '0') {
  $dslinkDelete = 'dis';
}
?>
<style>
  .typcn {
    font-size: 18px;
  }
</style>
<div class="main-box">
  <div class="d-flex justify-content-end mb-5">
    <a href="create-product.php?type=2">
      <button class="btn btn-success" <?php if ($rdata["create_fc"] == "0") {
        echo "disabled";
      } ?>>Create</button>
    </a>
    <button class="btn btn-primary " onclick="toggleImportForm()" style="margin-left: 10px;">Import</button>

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
  <form id="importForm" action="stock_import.php" method="post" enctype="multipart/form-data"
    style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; display: none;">
    <label for="import_file" style="font-size: 16px; margin-bottom: 10px; display: block;">Choose Excel file for
      import:</label>
    <input type="file" name="import_file" id="import_file" accept=".xlsx" style="margin-bottom: 10px;">
    <input type="submit" name="submit_import" value="Import" class="btn btn-primary">
    <a href="excel/StockSatalog-Sample.xlsx" download="sample-Stock Catalog.xlsx" class="btn btn-info">Download Sample
      Excel</a>


  </form>

  <?php

  if ($productData) {
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover'>";
    echo "<thead> <tr>
            <th> ID</th>
            <th>product </th>
            <th>Unit</th>
            <th>Price (per unit)</th>
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

      // Prevent Delete
      $valueToCheck = $row['id'];
      // Prepare the SELECT query
      $sqlDup = "SELECT * FROM `orderitem` WHERE productid = :valueToCheck";

      // Prepare and execute the statement
      $stmtDup = $pdo->prepare($sqlDup);
      $stmtDup->bindParam(':valueToCheck', $valueToCheck);
      $stmtDup->execute();

      if ($stmtDup->rowCount() > 0) {
        $dslinkEditTdy = 'dis';
      } else {
        $dslinkEditTdy = '';
      }

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
      echo "<a href='delete-product.php?type=stock&id=" . $row['id'] . "' class='text-danger " . $dslinkDelete . $dslinkEditTdy . "'onclick='return confirmDelete()'><i class='  typcn typcn-trash'></a></td>";
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
    return confirm("Are you sure you want to delete this product?");
  }
  function toggleImportForm() {
    var importForm = document.getElementById("importForm");
    importForm.style.display = (importForm.style.display === "none") ? "block" : "none";
  }
</script>