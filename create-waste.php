<?php
include('header.php');
include('menu.php');

$branchsql = "SELECT * FROM `branch` WHERE status = 'Active'";
$branchdata = $pdo->query($branchsql);
$typedata = $pdo->query("SELECT * FROM `type`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$cuisinedata = $pdo->query("SELECT * FROM `cuisine`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$categorydata = $pdo->query("SELECT * FROM `category`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
$productdata = $pdo->query("SELECT * FROM `product`WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="main-box">
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
    <h2 class="mb-3">Create Waste</h2>
    <hr>
    <form class="forms-sample" method="post" action="create-waste-post.php" onsubmit="handleSubmit(this)">
        <div class="row">

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputStatus">Branch <span>*</span></label>
                    <select class="form-control" name="branch" id="exampleInputStatus" required>

                        <?php foreach ($branchdata as $row): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputDate">Date</label>
                    <input type="date" class="form-control" name="date" id="exampleInputDate"
                        value="<?= date('Y-m-d') ?>" readonly>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <label for="exampleInputName1">Total Waste Amount <span>*</span></label>
                    <input type="number" class="form-control" name="amount" id="exampleInputName1"
                        placeholder="Enter amount" readonly>
                </div>
            </div>
        </div>
        <!-- Additional product details rows -->
        <div class="pro-box">

            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Product <span>*</span></label>
                        <select class="form-control mb-2" name="pro[]" onchange="handleQty(this)" required>
                            <?php foreach ($productdata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Type</label>
                        <select class="form-control mb-2" name="ty[]">
                            <?php foreach ($typedata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Category</label>
                        <select class="form-control mb-2" name="ca[]">
                            <?php foreach ($categorydata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="exampleInputStatus">Cuisine</label>
                        <select class="form-control mb-2" name="cu[]">
                            <?php foreach ($cuisinedata as $row): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="">Waste Qty <span>*</span></label>
                    <input class="form-control mb-2" name="qt[]" oninput="handleCost(this)" required>
                </div>

                <input class="form-control mb-2" type="number" value="" name="cost[]" readonly hidden>

            </div>
        </div>
        <!-- End of additional product details rows -->

        <div>
            <a class="btn add-btn btn-success" id="addRow">+</a>
        </div><br><br><br>
        <button type="submit" class="btn btn-primary mr-2">Submit</button>

</div>

</div>

</form>
</div>

<!-- Cost Waste  -->
<!-- <script>
    var costInput = document.querySelectorAll('[name="cost[]"]');
    var qty = document.querySelectorAll('[name="qt[]"]');
    var costTotal = document.querySelector('[name="amount"]');
    var proData = "";

    function updateCost(){
        qty.forEach(e => {
            let proId = e.closest(".row").querySelector('[name="pro[]"]').value;
            console.log(proId)
       });
    }
       
       function updateCostVar(){
           costInput = document.querySelectorAll('[name="cost[]"]');
           qty = document.querySelectorAll('[name="qt[]"]');
           updateCost()
           console.log(costInput.length)
       }
       updateCost()

       
    
</script> -->

<!-- <script>
    // Define the proData variable as an empty array for now
    var proData = [];

    var qty = document.querySelectorAll('[name="qt[]"]');
    var costTotal = document.querySelector('[name="amount"]');
    

    var totalCost = 0;



    const updateCost = ()=>{
        qty.forEach(function (element) {
        element.addEventListener("change", () => {
            var proId = element.closest(".row").querySelector('[name="pro[]"]').value;
            var proCost = element.closest(".row").querySelector('[name="cost[]"]').value;
            var quantity = element.value;
            alert("hee")

            // Fetch the price from the proData array based on proId
            var product = proData.find(function (product) {
                return product.id === proId;
            });

            if (product) {
                totalCost += product.price * quantity;
            }

            costTotal.value = totalCost;
        })
    });
    }
    

    function updateCostVar() {
        costInput = document.querySelectorAll('[name="cost[]"]');
        qty = document.querySelectorAll('[name="qt[]"]');
        // updateCost();
    }

    document.getElementById("addRow").addEventListener("click", function () {
        alert("haiiiiiiiiiiiii")
    updateCost();
});



    // Load the product data from PHP
    proData = ?>;

    // Attach change event listeners to the relevant form fields to trigger cost updates
    // var quantityInputs = document.querySelectorAll('[name="qt[]"]');
    // quantityInputs.forEach(function (input) {
    //     input.addEventListener('change', updateCost);
    // });

    // Update the cost when the page loads
    updateCost()
</script> -->

<script>
    function handleCost(e) {
        var proData = [];
        proData = <?php echo json_encode($productdata); ?>;

        var proId = e.closest(".row").querySelector('[name="pro[]"]').value;
        var proCost = e.closest(".row").querySelector('[name="cost[]"]');

        var product = proData.find(function (product) {
            return product.id === proId;
        });

        if (product) {
            var Cost = product.price * e.value;
            proCost.value = Cost;
        }
    }

    function handleSubmit(e) {
        e.preventDefault;
        let costFields = document.querySelectorAll('[name="cost[]"]');
        let totalCostField = document.querySelector('[name="amount"]');
        let totalCost = 0;
        costFields.forEach((cost) => {
            totalCost += parseInt(cost.value);
            totalCostField.value = totalCost;
        })
        return true;
    }

    function handleQty(e) {
        var qtyField = e.closest(".row").querySelector('[name="qt[]"]');
        var costField = e.closest(".row").querySelector('[name="cost[]"]');
        qtyField.value = "";
        costField.value = "";
    }
</script>

<!-- <script>
    // Define the proData variable as an empty array for now
    var proData = [];

    var qty = document.querySelectorAll('[name="qt[]"]');
    var costTotal = document.querySelector('[name="amount"]');
    

    var totalCost = 0;



    var updateCost = ()=>{
        qty.forEach(function (element) {
        element.addEventListener("change", () => {
            var proId = element.closest(".row").querySelector('[name="pro[]"]').value;
            var proCost = element.closest(".row").querySelector('[name="cost[]"]').value;
            var quantity = element.value;
            alert("hee")

            // Fetch the price from the proData array based on proId
            var product = proData.find(function (product) {
                return product.id === proId;
            });

            if (product) {
                totalCost += product.price * quantity;
            }

            costTotal.value = totalCost;
        })
    });
    }
    

    function updateCostVar() {
        costInput = document.querySelectorAll('[name="cost[]"]');
        qty = document.querySelectorAll('[name="qt[]"]');
        updateCost()
        console.log(qty.length)
    }



    // Load the product data from PHP
    proData =;

    //Attach change event listeners to the relevant form fields to trigger cost updates
    var quantityInputs = document.querySelectorAll('[name="qt[]"]');
    quantityInputs.forEach(function (input) {
        input.addEventListener('change', updateCost);
        console.log(quantityInputs.length)
    });

    // Update the cost when the page loads
    updateCost()
</script> -->





<?php
include('footer.php');
?>