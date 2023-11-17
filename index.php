<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Hotel Management</title>
  <link rel="stylesheet" href="vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
  <!-- DataTables Buttons CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.2/css/buttons.dataTables.min.css">
  <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css"> -->
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
  </style>

</head>
<body>
<div class="log-box">
    <div class="log-box-inn">
        <div class="main-box">
                        <!-- Php for error handling -->
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
            <h2 class="mb-3">Login</h2>
            <hr>
            <form class="forms-sample" method="post" action="login-post.php">
                <div class="form-group">
                    <label for="#username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="#pass">Password</label>
                    <input type="password" class="form-control" name="password" id="pass" placeholder="Password">
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include('footer.php');
?>