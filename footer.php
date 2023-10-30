</div>
<!-- <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://www.infygain.com/" target="_blank">Infygain</a> 2023</span>
    </div>
</footer> -->

</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>

<script src="vendors/js/vendor.bundle.base.js"></script>

<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="vendors/progressbar.js/progressbar.min.js"></script>
<script src="vendors/chart.js/Chart.min.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="js/dashboard.js"></script>
<!-- End custom js for this page-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>


<script>
         $(document).ready(function() {
         $('.table').DataTable();
    });
</script>

<!-- Add btn and Product Data  -->

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        function HandleProClone() {
            const product = document.querySelectorAll('[name="pro[]"]')
            product.forEach((item, index) => {
                item.addEventListener("change", () => {
                    const cus = item.closest(".row").querySelector('[name="cu[]"]')
                    const cat = item.closest(".row").querySelector('[name="ca[]"]')
                    const typ = item.closest(".row").querySelector('[name="ty[]"]')
                    
                    // console.log(cus.value)
                    console.log("ddddddddddddd" + typ.value)
                    let proId = item.value;
                    // console.log(proId)
                    fetch('fetchProductDetails.php?product_id=' + proId)
                        .then(response => response.json())
                        .then(data => {
                            console.log(cat.value)
                            console.log("....................")
                            var catSel = cat.options[cat.selectedIndex];
                            var cusSel = cus.options[cus.selectedIndex];

                            if(window.location.href.includes('consumption') || window.location.href.includes('stock')){
                                var typSel = typ.options[typ.selectedIndex];
                                var typName = typSel.text;
                                typ.value = data.typid;
                                typName = data.typname;
                            }

                            var catName = catSel.text;
                            var cusName = cusSel.text;
                            cat.value = data.catid;
                            cus.value = data.cusid;
                            catName = data.catname;
                            cusName = data.cusname;
                            console.log(data)
                        })
                });
            });
        }
        HandleProClone()
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

    });

</script>


</body>

</html>
