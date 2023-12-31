<?php
include('header.php');
include('menu.php');
if ($udata["branch"] == "1") {
    $branchSql = "SELECT * FROM `branch` WHERE status = 'Active'";
} else {
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
}

?>

<div class="main-box">
    <h2>Edit Stock Order</h2>

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

    <hr>
    <form class="forms-sample" method="post" action="update-stockorder.php" onsubmit="return handleSubmit()">
        <div class="row">

            <input type="hidden" name="orderID" value="<?php echo $orderData['id']; ?>">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="orderName">Order Name</label>
                    <input type="text" class="form-control" name="orderName" id="orderName" readonly
                        value="<?php echo $orderData['order_name']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="branch">Branch <span>*</span></label>
                    <select required class="form-control <?php if($orderData['status'] != 'Created'){echo 'disbox';} ?>"<?php if($orderData['status'] != 'Created'){echo 'readonly';} ?> name="branch" id="branch">
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
                    <input type="text" class="form-control" name="orderdate" id="orderdate"
                        value="<?php echo $orderData['orderdate']; ?>" value="<?= $currentDate ?>" readonly>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="deliverydate">Delivery Date <span>*</span></label>
                    <input type="text" autocomplete="off"
                        class="form-control <?php if ($orderData['status'] == 'Created') {
                            echo 'datepic';
                        } ?><?php if($orderData['status'] != 'Created'){echo 'disbox';} ?>"
                        name="deliverydate" id="deliverydate" <?php if ($orderData['status'] != 'Created') {
                            echo 'readonly';
                        } else {
                            echo '';
                        } ?> value="<?php echo $orderData['deliverydate']; ?>">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" name="priority" id="status">
                    <option value="Normal" <?php if ($orderData['priority'] === 'Normal')
                            echo 'selected'; ?>>Normal
                        </option>
                        <option value="Low" <?php if ($orderData['priority'] === 'Low')
                            echo 'selected'; ?>>Low</option>
                        <option value="High" <?php if ($orderData['priority'] === 'High')
                            echo 'selected'; ?>>High
                        </option>                                         
                        <option value="Urgent" <?php if ($orderData['priority'] === 'Urgent')
                            echo 'selected'; ?>>Urgent
                        </option>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="status">Status <span>*</span></label>
                    <select class="form-control" name="status" id="status" onchange="handleQty()">

                        <?php if ($orderData['status'] === 'Created') { ?>
                            <option value="Created" <?php if ($orderData['status'] === 'Created')
                                echo 'selected'; ?>>Created
                            </option>
                            <?php if ($rdata['role_id'] == '3' || $rdata['role_id'] == '4' || $rdata['role_id'] == '1') { ?>
                                <option value="Accepted" <?php if ($orderData['status'] === 'Accepted')
                                    echo 'selected'; ?>>
                                    Accepted</option>
                            <?php } ?>
                        <?php } ?>

                        <?php if ($orderData['status'] === 'Accepted') { ?>
                            <?php if ($rdata['role_id'] == '3' || $rdata['role_id'] == '4' || $rdata['role_id'] == '1') { ?>
                                <option value="Accepted" <?php if ($orderData['status'] === 'Accepted')
                                    echo 'selected'; ?>>
                                    Accepted</option>
                            <?php } ?>
                            <?php if ($rdata['role_id'] == '3' || $rdata['role_id'] == '4' || $rdata['role_id'] == '1') { ?>
                                <option value="Delivered" <?php if ($orderData['status'] === 'Delivered')
                                    echo 'selected'; ?>>
                                    Delivered</option>
                            <?php } ?>
                        <?php } ?>

                        <?php if ($orderData['status'] === 'Delivered') { ?>
                            <?php if ($rdata['role_id'] == '3' || $rdata['role_id'] == '4' || $rdata['role_id'] == '1') { ?>
                                <option value="Delivered" <?php if ($orderData['status'] === 'Delivered')
                                    echo 'selected'; ?>>
                                    Delivered</option>
                            <?php } ?>
                            <option value="Received" <?php if ($orderData['status'] === 'Received')
                                echo 'selected'; ?>>
                                Received</option>

                        <?php } ?>
                        <?php if ($orderData['status'] === 'Received') { ?>
                            <option value="Received" <?php if ($orderData['status'] === 'Received')
                                echo 'selected'; ?>>
                                Received</option>
                        <?php } ?>
                        <?php if ($orderData['status'] !== 'Received') { ?>
                            <option class="text-danger" value="Cancelled" <?php if ($orderData['status'] === 'Cancelled')
                                echo 'selected'; ?>>
                                Cancelled</option>
                            <option class="text-danger" value="Rejected" <?php if ($orderData['status'] === 'Rejected')
                                echo 'selected'; ?>>
                                Rejected</option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-12 mb-5">
                <label for="">Description</label>
                <textarea class="form-control mb-2" name="des"><?php echo $orderData['description']; ?></textarea>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Product <span>*</span></label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="exampleInputStatus">Category</label>
            </div>
            <!-- <div class="col-12 col-md-6 col-lg-2 hc">
                <label for="exampleInputStatus">Cuisine</label>
                </div> -->
            <div class="col-12 col-md-6 col-lg-2">
                <label for="">Unit</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <label for="">Order Qty</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2 hiddenDel">
                <label for="">Delivered Qty</label>
            </div>
            <div class="col-12 col-md-6 col-lg-2 hiddenRec">
                <label for="">Received Qty</label>
            </div>
        </div>
        <!-- Additional product details rows -->
        <div class="pro-box">


            <?php foreach ($orderItem as $od) { ?>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus" >Product</label> -->
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
                    <div class="col-12 col-md-6 col-lg-2" hidden>
                        <div class="form-group">
                            <!-- <label for="exampleInputStatus" hidden>Type</label> -->
                            <select class="form-control mb-2" name="ty[]">
                                <?php foreach ($typedata as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?php if ($row['id'] === $od['typeid']) {
                                          echo 'selected';
                                      } ?>><?= $row['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="form-group">

                            <select class="form-control mb-2" name="ca[]" readonly>
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

                    <div class="col-12 col-md-6 col-lg-2 hc d-none" hidden>

                        <div class="form-group">
                            <!-- <label for="exampleInputStatus" hidden>Cuisine</label> -->
                            <select class="form-control mb-2" name="cu[]" readonly>
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
                        <input type="text" class="form-control sz mb-2" name="unit[]" value="<?php echo $od['unit']; ?>"
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
                    <div class="col-12 col-md-6 col-lg-2 orderQtyColumn">
                        <!-- <label for="" hidden>Order_Qty</label> -->
                        <input type="number" class="form-control mb-2" name="qt[]" value="<?php echo $od['order_qty']; ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2 hiddenDel delivery-column">
                        <!-- <label for="">Delivery_Qty</label> -->
                        <input type="number" class="form-control mb-2" name="deliveryqt[]"
                            value="<?php if ($orderData['status'] == 'Delivered') {
                                echo $od['delivery_qty'];
                            } else {
                                echo $od['order_qty'];
                            } ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2 hiddenRec receivedQtyColumn">
                        <!-- <label for="">Received_Qty</label> -->
                        <input type="number" class="form-control mb-2" name="receivedqt[]"
                            value="<?php if ($orderData['status'] == 'Received') {
                                echo $od['received_qty'];
                            } else {
                                echo $od['delivery_qty'];
                            } ?>">
                    </div>
                    <input type="number" class="form-control mb-2" value="<?php if ($orderData['status'] === 'Received') {
                        echo $od['received_qty'];
                    } else {
                        echo 0;
                    } ?>" name="oldRecQty[]" hidden>
                    <input type="hidden" name="ty[]" value="2">

                </div>
            <?php } ?>
        </div>

        <!-- End of additional product details rows -->
        <div class="col-12 col-md-6 col-lg-2">
            <?php if ($orderData['status'] !== 'Accepted'): ?>
                <a class="btn add-btn btn-success" id="addRow">+</a>
            <?php endif; ?>
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
        let cb = document.querySelectorAll(".hc");

        db.forEach((p) => {
            p.hidden = true
        })
        rb.forEach((p) => {
            p.hidden = true
        })


        if (e.value !== "Created") {

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
            db.forEach((p) => {
                p.hidden = false
            })
            rb.forEach((p) => {
                p.hidden = false
            })
        } else {
            rQty.forEach((p) => {
                p.disabled = true
            })
        }
        if (e.value == "Delivered" || e.value == "Received") {
            cb.forEach((f) => {
                f.hidden = true
            })
        }
        else {
            cb.forEach((f) => {
                f.hidden = false
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
            p.removeClassName
        })
        return true;
    }
</script>


<?php include('footer.php'); ?>