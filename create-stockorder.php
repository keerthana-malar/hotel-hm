<?php
include('header.php');
include('menu.php');
$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
$typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
// $cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active' AND typeid = '2'")->fetchAll(PDO::FETCH_ASSOC);
$productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active' AND typeid = '2'")->fetchAll(PDO::FETCH_ASSOC);
$currentDate = date('Y-m-d');
?>

<style>
    .remove-row{
        height:20px;
        width: 20px;
        border-radius: 50%;
        padding: 0;
        margin: 0;
        margin-top: 15px;
        background: none;
        color:red;
    }
</style>

<div class="main-box">
    <h2 class="mb-3">Create Stock Orders</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-stockorder-post.php">
        <!-- Branch, Order Date, Delivery Date, Priority, Status fields ... -->
        <div class="row">
        <div class="col-12 col-md-6 col-lg-3">
    <div class="form-group">
        <label for="orderName">Order Name</label>
        <input type="text" class="form-control" name="orderName" id="orderName">
    </div>
</div>
        <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Branch</label>
                    <select class="form-control" name="branch" id="exampleInputStatus">
       
                <?php foreach ($branchdata as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                 
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputDate">Order Date</label>
                    <input type="date" class="form-control" name="orderDate" id="exampleInputDate" value="<?= $currentDate ?>" readonly>
                </div>
            </div>
<div class="col-12 col-md-6 col-lg-3">
    <div class="form-group">
        <label for="exampleInputDate">Delivery Date</label>
        <input type="date" class="form-control" name="deliveryDate" id="exampleInputDate">
    </div>
</div>


        
         
<div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Priority</label>
                    <select class="form-control" name="priority" id="exampleInputStatus">
                        <option value="High">High</option>
                        <option value="Low">Low</option>
                        <option value="Normal">Normal</option>
                        <option value="Urgent">Urgent</option>

                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Status</label>
                    <select class="form-control" name="status" id="exampleInputStatus">
                    <option value="Created">Created</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Received">Received</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                    <label for="">Description</label>
                    <textarea class="form-control mb-2" name="des" id="description"></textarea>                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
    <div class="form-group">
        <select class="form-control" name="orderType"  id="orderType" hidden>
            <option value="1" >Food Order</option>
            <option value="2" selected>Stock Order</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</div>
          

            
        <!-- Additional product details rows -->
        <div class="pro-box">
            <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Product</label>
                        <select class="form-control mb-2" name="pro[]">
                            <?php foreach ($productdata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <!-- <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Type</label>
                        <select class="form-control mb-2" name="ty[]">
                            <?php foreach ($typedata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div> -->
                
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="exampleInputStatus">Category</label>
                        <select class="form-control mb-2" name="ca[]">
                            <?php foreach ($categorydata as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="exampleInputStatus">Priority</label>
                    <select class="form-control" name="pr[]" id="exampleInputStatus">
                    <option value="High">High</option>
                        <option value="Low">Low</option>
                        <option value="Normal">Normal</option>
                        <option value="Urgent">Urgent</option>


                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Qty</label>
                    <input type="number" class="form-control mb-2" name="qt[]">
                </div>
                <div class="col-12 col-md-6 col-lg-1">
    <input type="hidden" name="ty[]" value="2">   
</div>
            </div>
        </div>
        <!-- End of additional product details rows -->

        <div>
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>

        <button type="submit" class="btn btn-primary mr-2">Submit</button>
    </form>
</div>



<script>

    document.addEventListener('DOMContentLoaded', function () {


        function HandleProClone() {
            const product = document.querySelectorAll('[name="pro[]"]')
            product.forEach((item, index) => {
                item.addEventListener("change", () => {
                    const cat = item.closest(".row").querySelector('[name="ca[]"]')
                    // console.log(cat.value)
                    // console.log(cus.value)
                    let proId = item.value;
                    // console.log(proId)
                    fetch('fetchProductDetails.php?product_id=' + proId)
                        .then(response => response.json())
                        .then(data => {
                            console.log(cat.value)
                            console.log("....................")
                            var catSel = cat.options[cat.selectedIndex];
                            var catName = catSel.text;

                            cat.value = data.catid;
                            catName = data.catname;
                        })
                });
            });

        }

        const addInputButton = document.getElementById('addRow');
        const inputContainer = document.querySelector('.pro-box');

        // Initial product data
        const productDataJSON = <?php echo json_encode($productdata); ?>;

        addInputButton.addEventListener('click', function () {
            const newRow = inputContainer.querySelector('.row').cloneNode(true);

            // Clear the product dropdown and quantity input in the cloned row
            newRow.querySelector('[name="pro[]"]').value = "";
            newRow.querySelector('[name="qt[]"]').value = "";
            // Hide labels in the cloned row
            const labels = newRow.querySelectorAll('label');
            labels.forEach(function (label) {
                label.style.display = 'none';
            });


            // Populate the product dropdown in the cloned row
            const productSelect = newRow.querySelector('[name="pro[]"]');
            productSelect.innerHTML = ''; // Clear existing options before populating
            productDataJSON.forEach(function (product) {
                const option = document.createElement('option');
                option.value = product.id;
                option.text = product.name;
                productSelect.appendChild(option);
            });

            // Append the cloned row to the input container
            

            // Remove button 
            const removeButton = document.createElement('button');
            removeButton.textContent = 'X';
            removeButton.className = 'btn btn-danger remove-row';
            removeButton.addEventListener('click', function () {
                inputContainer.removeChild(newRow);
            });
            newRow.appendChild(removeButton);

            inputContainer.appendChild(newRow);

            HandleProClone();
            // var productSelects = document.querySelectorAll('[name="pro[]"]');
            // console.log(productSelects)
        });
        // Set the current date for the "Order Date" field
        const orderDateInput = document.querySelector('[name="orderDate"]');
        const currentDate = new Date();
        const formattedCurrentDate = currentDate.toISOString().split('T')[0];
        orderDateInput.value = formattedCurrentDate;

        // CAT 
        HandleProClone()

    });


</script>


<?php
include('footer.php');
?>
