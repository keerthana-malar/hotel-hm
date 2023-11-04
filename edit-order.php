<?php

include('header.php');
include('menu.php');
if($udata["role"]=="1"){
    $branchSql = "SELECT * FROM `branch` WHERE status = 'Active'";
}else{
    $branchSql = "SELECT * FROM `branch` WHERE status = 'Active' AND id = $userBranch";
}

if (isset($_GET['id'])) {
    $orderID = $_GET['id'];

    // Retrieve the order details from the database
    $orderSql = "SELECT * FROM `order` WHERE id = :id";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->bindParam(':id', $orderID);
    $orderStmt->execute();
    $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);

    $oi = $pdo->query("SELECT * FROM orderitem WHERE order_id = " . $orderID . "");
    $orderItem = $oi->fetchAll(PDO::FETCH_ASSOC);

    // $branchSql = "SELECT * FROM `branch` WHERE status = 'Active'";
    $branchData = $pdo->query($branchSql);
    $typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
    $currentDate = date('Y-m-d');
} else {
    header("Location: orders.php");
    exit();
}
?>

<div class="main-box">
    <h2>Edit Food Order</h2>
    <hr>
    <form class="forms-sample" method="post" action="update-order.php" onsubmit="return handleSubmit()">
        <div class="row">

            <input type="hidden" name="orderID" value="<?php echo $orderData['id']; ?>">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="orderName">Order Name</label>
                    <input type="text" class="form-control" name="orderName" id="orderName"
                        value="<?php echo $orderData['order_name']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">

                <div class="form-group">
                    <label for="branch">Branch</label>
                    <select class="form-control" name="branch" id="branch">
                        <?php foreach ($branchData as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>" <?php if ($orderData['branchid'] === $branch['id'])
                                   echo 'selected'; ?>>
                                <?php echo $branch['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="orderdate">Order Date</label>
                    <input type="date" class="form-control" name="orderdate" id="orderdate"
                        value="<?php echo $orderData['orderdate']; ?>" value="<?= $currentDate ?>" readonly>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="deliverydate">Delivery Date</label>
                    <input type="date" class="form-control" name="deliverydate" id="deliverydate"
                        value="<?php echo $orderData['deliverydate']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" name="priority" id="status">
                        <option value="High" <?php if ($orderData['priority'] === 'High')
                            echo 'selected'; ?>>High
                        </option>
                        <option value="Low" <?php if ($orderData['priority'] === 'Low')
                            echo 'selected'; ?>>Low</option>
                        <option value="Normal" <?php if ($orderData['priority'] === 'Normal')
                            echo 'selected'; ?>>Normal
                        </option>
                        <option value="Urgent" <?php if ($orderData['priority'] === 'Urgent')
                            echo 'selected'; ?>>Urgent
                        </option>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id="status" onchange="handleQty()">
                        <option value="created" <?php if ($orderData['status'] === 'Created')
                            echo 'selected'; ?>>Created
                        </option>
                        <option value="Accepted" <?php if ($orderData['status'] === 'Accepted')
                            echo 'selected'; ?>>
                            Accepted</option>
                        <option value="Delivered" <?php if ($orderData['status'] === 'Delivered')
                            echo 'selected'; ?>>
                            Delivered</option>
                        <option value="Received" <?php if ($orderData['status'] === 'Received')
                            echo 'selected'; ?>>
                            Received</option>
                        <option value="Cancelled" <?php if ($orderData['status'] === 'Cancelled')
                            echo 'selected'; ?>>
                            Cancelled</option>
                        <option value="Rejected" <?php if ($orderData['status'] === 'Rejected')
                            echo 'selected'; ?>>
                            Rejected</option>
                    </select>
                </div>
            </div>

            <div class="col-12 ">
                <label for="">Description</label>
                <textarea class="form-control mb-2" name="des"><?php echo $orderData['description']; ?></textarea>
            </div>

        </div>



        <!-- Additional product details rows -->
        <div class="pro-box">
            <div class="row">
                
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="exampleInputStatus">Product</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="exampleInputStatus">Category</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                    <label for="exampleInputStatus">Cuisine</label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Unit <span>*</span></label>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Order_Qty</label>
                    </div>
            </div>
            <?php foreach ($orderItem as $od) { ?>
                <div class="row">

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Product</label> -->
                            <select class="form-control mb-2" name="pro[]">
                                <?php foreach ($productdata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['productid']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Type</label>
                        <select class="form-control mb-2" name="ty[]">
                            <?php foreach ($typedata as $row): ?>
                                <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['typeid']) {
                                      echo 'selected';
                                  } ?>><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div> -->

                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Category</label> -->
                            <select class="form-control mb-2" name="ca[]">
                                <?php foreach ($categorydata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['categoryid']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus">Cuisine</label> -->
                            <select class="form-control mb-2" name="cu[]">
                                <?php foreach ($cuisinedata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['cuisineid']) {
                                          echo 'selected';
                                      } ?>>
                                        <?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <!-- <label for="">Unit <span>*</span></label> -->
                        <input type="text" class="form-control mb-2" name="unit[]" value="<?php echo $od['unit']; ?>"
                            required readonly>

                    </div>
                    <!-- <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <label for="exampleInputStatus">Priority</label>
                            <select class="form-control" name="pr[]">
                                <option value="High" <?php if ($od['priority'] === 'High')
                                    echo 'selected'; ?>>High</option>
                                <option value="Low" <?php if ($od['priority'] === 'Low')
                                    echo 'selected'; ?>>Low</option>
                                <option value="Normal" <?php if ($od['priority'] === 'Normal')
                                    echo 'selected'; ?>>Normal
                                </option>
                                <option value="Urgent" <?php if ($od['priority'] === 'Urgent')
                                    echo 'selected'; ?>>Urgent
                                </option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <!-- <label for="">Order_Qty</label> -->
                        <input type="number" class="form-control mb-2" name="qt[]" value="<?php echo $od['order_qty']; ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2 hiddenDel">
                        <label for="">Delivery_Qty</label>
                        <input type="number" class="form-control mb-2" value="<?php echo $od['delivery_qty']; ?>"
                            name="deliveryqt[]">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2 hiddenRec">
                        <label for="">Received_Qty</label>
                        <input type="number" class="form-control mb-2" value="<?php echo $od['received_qty']; ?>"
                            name="receivedqt[]">
                    </div>
                    <input type="number" class="form-control mb-2" value="<?php if ($orderData['status'] === 'Received') {
                        echo $od['received_qty'];
                    } else {
                        echo 0;
                    } ?>" name="oldRecQty[]" hidden>

                    <input type="hidden" name="ty[]" value="1">

                </div>
            <?php } ?>
        </div>

        <!-- End of additional product details rows -->
        <div class="col-12 col-md-6 col-lg-2">
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>

        <input type="hidden" name="oid" value="<?php echo $orderID ?>">

        <button type="submit" class="btn btn-primary">Update Order</button>
</div>
</form>
</div>

<script>
    function handleQty() {

        let e = document.querySelector('[name="status"]')

        let pro = document.querySelectorAll('[name="pro[]"]')
        let cus = document.querySelectorAll('[name="cu[]"]')
        let cat = document.querySelectorAll('[name="ca[]"]')
        let pri = document.querySelectorAll('[name="pr[]"]')
        let qty = document.querySelectorAll('[name="qt[]"]')
        let dQty = document.querySelectorAll('[name="deliveryqt[]"]')
        let rQty = document.querySelectorAll('[name="receivedqt[]"]')


        let db = document.querySelectorAll(".hiddenDel");
        let rb = document.querySelectorAll('.hiddenRec');

        db.forEach((p) => {
            p.hidden = true
        })
        rb.forEach((p) => {
            p.hidden = true
        })

        console.log(db)

        if (e.value !== "created") {

            pro.forEach((p) => {
                p.disabled = true
            })
            cus.forEach((p) => {
                p.disabled = true
            })
            cat.forEach((p) => {
                p.disabled = true
            })
            pri.forEach((p) => {
                p.disabled = true
            })
            qty.forEach((p) => {
                p.disabled = true
            })

        } else {
            pro.forEach((p) => {
                p.disabled = false
            })
            cus.forEach((p) => {
                p.disabled = false
            })
            cat.forEach((p) => {
                p.disabled = false
            })
            pri.forEach((p) => {
                p.disabled = false
            })
            qty.forEach((p) => {
                p.disabled = false
            })
        }

        if (e.value == "Delivered") {
            dQty.forEach((p) => {
                p.disabled = false
            })
            db.forEach((p) => {
                p.hidden = false
            })

        } else {
            dQty.forEach((p) => {
                p.disabled = true
            })
        }

        if (e.value == "Received") {
            rQty.forEach((p) => {
                p.disabled = false
            })
            rb.forEach((p) => {
                p.hidden = false
            })
        } else {
            rQty.forEach((p) => {
                p.disabled = true
            })
        }
    }
    handleQty()

    function handleSubmit() {

        let pro = document.querySelectorAll('[name="pro[]"]')
        let cus = document.querySelectorAll('[name="cu[]"]')
        let cat = document.querySelectorAll('[name="ca[]"]')
        let pri = document.querySelectorAll('[name="pr[]"]')
        let qty = document.querySelectorAll('[name="qt[]"]')
        let dQty = document.querySelectorAll('[name="deliveryqt[]"]')
        let rQty = document.querySelectorAll('[name="receivedqt[]"]')

        pro.forEach((p) => {
            p.disabled = false
        })
        cus.forEach((p) => {
            p.disabled = false
        })
        cat.forEach((p) => {
            p.disabled = false
        })
        pri.forEach((p) => {
            p.disabled = false
        })
        qty.forEach((p) => {
            p.disabled = false
        })
        dQty.forEach((p) => {
            p.disabled = false
        })
        rQty.forEach((p) => {
            p.disabled = false
        })

        return true;

    }

    
</script>

<?php include('footer.php'); ?>