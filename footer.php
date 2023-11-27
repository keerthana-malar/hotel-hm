</div>
<style>
    /* Hide the spinner controls for number input */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<style>
    .search-input {
        border: none;
        color: black !important;
    }

    .search-input:hover {
        border: 1px solid black !important;

        /* Add border when hovering */
    }

    .search-input::placeholder {
        color: black !important;
        font-weight: bold;
    }

    thead input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
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
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script> -->
<!-- DataTables Buttons JavaScript -->
<!-- <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.0.2/js/dataTables.buttons.min.js"></script>  -->
<script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    integrity="sha384-L6ziqmHr0DKKGovWfCGR6chGWm5IefSeDNXb8yF2t04eo2fOnVjLZKp7yltj3gdl" crossorigin="anonymous"> -->


<script>
    $(document).ready(() => {
        var dateFields = $(".datepic");

        dateFields.datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: 0,
            onSelect: function (dateText, inst) {
                $(this).prop("readonly", true);
            }
        });
    });

    $(document).ready(() => {
        var dateFields = $(".datepicc");

        dateFields.datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var select = document.querySelectorAll('[name="pro[]"]');
        let proUpdate = () => {
            select.forEach((e) => {
                // Get the options excluding the first option
                var options = Array.from(e.options).slice(1);

                // Sort options alphabetically
                options.sort(function (a, b) {
                    var textA = a.text.toUpperCase();
                    var textB = b.text.toUpperCase();
                    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
                });

                // Clear the existing options (excluding the first one)
                e.options.length = 1;

                // Add the first option back to the select element
                e.add(e.options[0]);

                // Add the sorted options back to the select element
                options.forEach(function (option) {
                    e.add(option);
                });
            })
        }
        proUpdate()
        document.querySelector('.add-btn').addEventListener('click', () => {
            let upt = () => {
                select = document.querySelectorAll('[name="pro[]"]');
                proUpdate()
            }
            setTimeout(upt, 500)
        })
    })
</script>

<!-- DataTables sorting, string searching and pagination  -->
<script>
    $(document).ready(function () {
        var table = $('.table').DataTable({
            ordering: false, // Disable sorting
            searching: true,
            buttons: [
                'copy', // Copy to clipboard
                'excel', // Export to Excel
                'csv',   // Export to CSV
                'pdf',   // Export to PDF
                'print'  // Print button 
            ]
        });

        // Add column-wise search functionality
        $('.table thead th:not(.action-column)').each(function (index) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="' + title + '" class="search-input" />');

            // Column-wise search event handler
            $('input.search-input', this).on('input', function () {
                table.column(index).search(this.value).draw();
            });

        });
    });

</script>

<!-- Readonly for automatically filled fields  -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function fieldRO() {
            // let pro = document.querySelectorAll('[name="pro[]"]')

            let cus = document.querySelectorAll('[name="cu[]"]')
            let cat = document.querySelectorAll('[name="ca[]"]')
            let type = document.querySelectorAll('[name="ty[]"]')

            function mouseDown(elename) {
                elename.forEach(function (checkbox) {
                    checkbox.addEventListener('mousedown', function (e) {
                        console.log('Clicked on checkbox with value:', checkbox.value);
                        e.preventDefault();
                    });
                });
            }
            mouseDown(cat);
            mouseDown(cus);
            mouseDown(type);

            function keyDown(elename) {
                document.addEventListener('keydown', function (e) {
                    var focusedElement = document.activeElement;

                    if (focusedElement && focusedElement.name === elename && e.key === 'Enter') {
                        e.preventDefault();
                        console.log('Enter key is disabled for the focused input field.');
                    }
                });
            }

            keyDown("cu[]");
            keyDown("ca[]");
            keyDown("ty[]");
        }

        fieldRO();

        let addBtn = document.querySelector('.add-btn');
        addBtn.addEventListener("click", () => {
            setTimeout(fieldRO, 1000)

        })

    });

</script>


<!-- Add btn and Product Data  -->

<script>

    document.addEventListener('DOMContentLoaded', function () {

        var delDate = document.querySelectorAll('.datepic');
        delDate.forEach((e) => {
            e.addEventListener('change', () => {
                e.readOnly = true;
            })
        })

        function HandleProClone() {
            const product = document.querySelectorAll('[name="pro[]"]')
            product.forEach((item, index) => {
                item.addEventListener("change", () => {
                    const cus = item.closest(".row").querySelector('[name="cu[]"]')
                    const cat = item.closest(".row").querySelector('[name="ca[]"]')
                    const typ = item.closest(".row").querySelector('[name="ty[]"]')
                    const uni = item.closest(".row").querySelector('[name="unit[]"]')

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

                            if (window.location.href.includes('consumption') || window.location.href.includes('waste')) {
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
                            uni.value = data.unit;
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

        // // Detect Select Option Row
        // let proItems = document.querySelectorAll('name="pro[]"')
        // let isSel = false;
        // function checkSelect() {
        //     proItems.forEach((pi) => {
        //         if (pi.value == "") {
        //             isSel = true
        //         }
        //     })
        // }
        // checkSelect()
        // if (isSel) {
        //     checkSelect()


        // Function to check if the last cloned row has a selected product
        function isLastRowProductSelected() {
            const lastRow = inputContainer.lastElementChild;
            const lastProductSelect = lastRow.querySelector('[name="pro[]"]');
            return lastProductSelect.value !== "";
        }

        var currentPageName = window.location.pathname.split('/').pop();
        

        addInputButton.addEventListener('click', function () {

            // Check if the last row has a selected product
            if (!isLastRowProductSelected()) {
                alert("Please select a product");
                return;
            }

            

            const newRow = inputContainer.querySelector('.row').cloneNode(true);
            // Clear the product dropdown and quantity input in the cloned row
            newRow.querySelector('[name="pro[]"]').value = "";
            newRow.querySelector('[name="qt[]"]').value = "";
            if(currentPageName == "edit-consumption.php"){
                newRow.querySelector('[name="old_qty[]"]').value = "";
            }
            if(currentPageName == "edit-waste.php"){
                newRow.querySelector('[name="old_wq[]"]').value = "";
            }
            // newRow.querySelector('[name="cu[]"]').value = "";
            // newRow.querySelector('[name="ca[]"]').value = "";
            // newRow.querySelector('[name="ty[]"]').value = "";
            // Hide labels in the cloned row
            const labels = newRow.querySelectorAll('label');
            labels.forEach(function (label) {
                label.style.display = 'none';
            });
            // Populate the product dropdown in the cloned row
            const productSelect = newRow.querySelector('[name="pro[]"]');
            productSelect.innerHTML = '<option value="">Select</option>'; // Clear existing options before populating
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


        // }    
        // Set the current date for the "Order Date" field
        const orderDateInput = document.querySelector('[name="orderDate"]');
        const currentDate = new Date();
        const formattedCurrentDate = `${currentDate.getDate()}/${currentDate.getMonth()}/${currentDate.getFullYear()}`;
        orderDateInput.value = formattedCurrentDate;


        // var deliveryDateInput = document.getElementById('deliveryDateinput');

        // var currentDated = formattedCurrentDate
        // currentDated.setHours(0, 0, 0, 0);
        // deliveryDateInput.addEventListener('change', function () {
        //     var selectedDate = new Date(deliveryDateInput.value);

        //     if (selectedDate < currentDated) {
        //         alert('Please choose a delivery date that is today or later.');
        //         deliveryDateInput.value = '';
        //     }
        // });

    });


</script>

<!-- Icons disable script  -->
<script>
    var dislink = document.querySelectorAll(".dis");
    dislink.forEach((e) => {
        e.removeAttribute("href");
        e.removeAttribute('onclick');
    })
    var icon = document.querySelectorAll('.dis .typcn');
    icon.forEach((ee) => {
        ee.style.color = "grey";
    })

    // For Today Only 
    // var disTdy = document.querySelectorAll(".dis");
    // disTdy.forEach((e) => {
    //     e.removeAttribute("href");
    //     e.removeAttribute('onclick');
    // })
    // var icons = document.querySelectorAll('.dis .typcn');
    // icons.forEach((ee) => {
    //     ee.style.color = "grey";
    // })
</script>

<!-- Disbale + Button After Accepted Status  -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let status = document.querySelector('[name="status"]').value;
        let plusBtn = document.querySelector('#addRow');

        function disBtn() {

            if (status != "Created") {
                plusBtn.classList.add('d-none')
            } else {
                plusBtn.classList.remove('d-none')
            }
        }
        disBtn()
    })
</script>

<script>
    let addBtn = document.querySelector('.add-btn')
    let rmBtn = document.querySelectorAll('.remove-row')
    let proInput = document.querySelectorAll(".uniquePro");
    let proVal = [];

    function fetchVal(index) {
        let proInputs = document.querySelectorAll(".uniquePro");
        proInputs.forEach((fn, key) => {
            if (index != key) {
                
                proVal.push(fn.value);
                // alert(key)
            }
            // proVal.push(fn.value)
            console.log("Heloooooooooooooooooooooooooooooooooooo" + "       " + proVal)
        })
    }

    function duplicateCheck(pro) {
        pro.forEach((e, key) => {
            e.addEventListener('change', () => {
                fetchVal(key)
                if (proVal.includes(e.value)) {
                    alert('Product Already Selected')

                    e.selectedIndex = 0
                } else {
                    console.log("elseeeeeeeeeeeeeeeeeeeeeeeeeeeeee" + "       " + proVal)
                }
            })
        })
    }

    function updatePro() {
        rmBtn = document.querySelectorAll('.remove-row')
        proInput = document.querySelectorAll(".uniquePro");
        duplicateCheck(proInput)
        console.log("Before", proVal)
        proVal.length = 0
        console.log("After", proVal)
        console.log(proInput)
        console.log("VALLLLLLLLLLLL" + proVal)
        console.log('Panitaaaaaaa')
        console.log(rmBtn)
        rmBtn.forEach((r) => {
            r.addEventListener('click', () => {
                console.log('varandaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
                setTimeout(updatePro, 500)
                // proVal.length = 0
            })
        })
    }

    duplicateCheck(proInput)

    addBtn.addEventListener('click', () => {
        setTimeout(updatePro, 500)
        // proVal.length = 0
    })


</script>

<!-- <script>

    var selectedProducts = [];
    let addBtn = document.querySelector('.add-btn')
    let productSelects = document.querySelectorAll('.uniquePro');

    function handleProductSelection(event) {
        var selectedProductId = event.target.value;

        if (selectedProducts.includes(selectedProductId)) {
            alert('Product already selected!');

            // Reset the select field to the previous value or take appropriate action
            event.target.value = selectedProducts[selectedProducts.length - 1];
        } else {
            // Clear the array and add the new selection
            selectedProducts = [selectedProductId];
        }
    }

    // Assuming product select fields have class 'product-select'


    function updatePro() {
        productSelects = document.querySelectorAll('.uniquePro');
        alert("pannita")
        productSelects.forEach(function (select) {
            select.addEventListener('change', handleProductSelection);
        });
    }

    addBtn.addEventListener('click', () => {
        setTimeout(updatePro, 500)
        // proVal.length = 0
    })


</script> -->

<!-- <script>
    // Pure JavaScript example

    let addBtn = document.querySelector('.add-btn')
    var selectedProducts = [];
    let proBox = document.querySelectorAll('.uniquePro')
    let rmBtn = document.querySelectorAll('.remove-row')


    function rr() {
        var selectedProductId = this.value;

        // Check if the product was previously selected
        var index = selectedProducts.indexOf(selectedProductId);

        if (index !== -1) {
            alert('Product already selected. Please choose a different one.');
            // Reset the selection or handle it according to your UI/UX
        } else {
            // Remove the previous selection, if any
            if (selectedProducts.length > 0) {
                // Assuming only one product selection is allowed; modify as needed
                var previousSelection = selectedProducts.pop();
                // Handle or log the removal of the previous selection
            }

            // Add the new selection to the array
            selectedProducts.push(selectedProductId);
        }
    }

    function updatePro() {
        proBox = document.querySelectorAll('.uniquePro');

        proBox.forEach(function (select) {
            select.addEventListener('change', rr);
        });

        rmBtn = document.querySelectorAll('.remove-row')
        aa()
    }

    proBox.forEach(function (select) {
        select.addEventListener('change', rr);
    });



    function aa() {
        rmBtn.forEach((r) => {
            r.addEventListener('click', () => {
                console.log('varandaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
                setTimeout(updatePro, 500)
                // proVal.length = 0
            })
        })
    }





    addBtn.addEventListener('click', () => {
        setTimeout(updatePro, 500)
        // proVal.length = 0
    })

</script> -->

</body>

</html>