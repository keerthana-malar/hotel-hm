<?php
// error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?from dashboard");
    exit();
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Hotel Management</title>
  <link rel="stylesheet" href="vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- DataTables CSS -->
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css"> -->
  <!-- DataTables Buttons CSS -->
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.2/css/buttons.dataTables.min.css"> -->
  <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="shortcut icon" href="images/favicon.png" />
  <style>
    .main-box {
      background-color: #fff;
      padding: 20px;
    }

    .log-box {
      height: 100svh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f5f5f5;
    }

    .log-box-inn {
      width: 500px;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
    }

    .form-control {
      height: 47px;
    }

    .remove-row {
      height: 20px;
      width: 20px;
      border-radius: 50%;
      padding: 0;
      margin: 0;
      margin-top: 15px;
      background: none;
      color: red;
    }

    label>span {
      color: red;
    }
    .sz{
        padding: 10px;
    }
  </style>

</head>