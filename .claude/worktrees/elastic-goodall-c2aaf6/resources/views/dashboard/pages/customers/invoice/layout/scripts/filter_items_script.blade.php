<script>
    // Filter Pick Up Values
    $('.search-picks').on('keyup', event => {
        let searchText = event.target.value;
        updatePicksTable(searchText);
    });

    // Search the picks options
    function updatePicksTable(searchValue) {
        let picksTableRows = Array.from(document.querySelectorAll('.picks-table tbody tr'));
        matchedRows = picksTableRows.filter(row => {
            return row.innerText.match(searchValue.toUpperCase());
        });
        picksTableRows.forEach(r => {
            if (!matchedRows.includes(r)) {
                r.style.display = 'none';
            } else {
                r.style.display = 'table-row';
            }
        });
    }


    // Full Search
    /*$('.full-search').on('click', function (e) {

        let category_id = document.querySelector('#category_id').value;
        let brand_input_id = document.querySelector('#brand_input').getAttribute('data-id');
        let model_input_id = document.querySelector('#model_input').getAttribute('data-id');
        let color = document.querySelector('#color').value;
        let size = document.querySelector('#size').value;
        let segment = document.querySelector('#brandsegment_input').value;
        let power = document.querySelector('#power').value;
        let sign = document.querySelector('#sign').value;
        let type = document.querySelector('#type').value;
        console.log(1);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "POST",
            url: '{{route("dashboard.full-search")}}',
            data: {
                category_id: category_id,
                brand_id: brand_input_id,
                model_id: model_input_id,
                color: color,
                size: size,
                brand_segment: segment,
                power: power,
                sign: sign,
                type: type
            },
            success: function (response) {
                if (response.length == 0) {
                    let table = document.querySelector('.panel-table');
                    table.querySelector('.no-items').style.display = 'block';
                    table.querySelector('table').style.display = 'none';

                } else {
                    let table = document.querySelector('.panel-table');
                    table.querySelector('table tbody').innerHTML = '';
                    table.style.opacity = '1';
                    // set table TDs
                    response.forEach((resp, index) => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                                    <td><button name="productId" id="productId" style="height: 15px;margin-right: 5px;"
                                                        value="${resp.product_id}"></button>${resp.product_id}</td>
                                    <td>${resp.description}</td>
                                    <td>${resp.tax}</td>
                                    <td>${resp.retail_price}</td>
                                    <td>${resp.amount}</td>
                                `
                        table.querySelector('table tbody').appendChild(row);
                    });
                    table.querySelector('table').style.display = 'inline-table';
                    table.querySelector('.no-items').style.display = 'none';
                }
            }
        });

    });

    // Choose Product To Add To Invoice
    $(document).on('click', '#productId', function (e) {
        e.preventDefault();
        let product_id = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "GET",
            url: '{{route("dashboard.get-product-details")}}',
            data: {product_id: product_id},
            success: handleProductResponse
        });

    })*/


    /**
     * ====================================================
     * PRODUCT SEARCH WITH BRANCH SUPPORT - FIXED VERSION
     * ====================================================
     */

   /* // ==================== FULL SEARCH ====================
    $('.full-search').on('click', function (e) {
        e.preventDefault();

        // Get selected branch
        let branch_id = document.querySelector('#branch_id').value;

        // Validate branch selection
        if (!branch_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Branch Required',
                text: 'Please select a branch first!',
                confirmButtonColor: '#3498db'
            });
            $('#branch_id').focus();
            return;
        }

        // Get search parameters
        let category_id = document.querySelector('#category_id').value;
        let brand_input_id = document.querySelector('#brand_input').getAttribute('data-id');
        let model_input_id = document.querySelector('#model_input').getAttribute('data-id');
        let color = document.querySelector('#color').value;
        let size = document.querySelector('#size').value;
        let segment = document.querySelector('#brandsegment_input').value;
        let power = document.querySelector('#power').value;
        let sign = document.querySelector('#sign').value;
        let type = document.querySelector('#type').value;

        // Show loading
        let table = document.querySelector('.panel-table');
        table.querySelector('table tbody').innerHTML = `
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p style="margin-top: 15px; color: #667eea; font-weight: 600;">
                    Searching products...
                </p>
            </td>
        </tr>
    `;
        table.querySelector('table').style.display = 'table';
        table.querySelector('.no-items').style.display = 'none';

        // AJAX Request
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "POST",
            url: '{{route("dashboard.full-search")}}',
            data: {
                branch_id: branch_id,
                category_id: category_id,
                brand_id: brand_input_id,
                model_id: model_input_id,
                color: color,
                size: size,
                brand_segment: segment,
                power: power,
                sign: sign,
                type: type
            },
            success: function (response) {
                console.log('Search Response:', response);

                if (!response.success) {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Search failed',
                        confirmButtonColor: '#e74c3c'
                    });

                    table.querySelector('.no-items').style.display = 'block';
                    table.querySelector('table').style.display = 'none';
                    return;
                }

                if (response.products.length == 0) {
                    // No products found
                    table.querySelector('.no-items').style.display = 'block';
                    table.querySelector('table').style.display = 'none';

                    Swal.fire({
                        icon: 'info',
                        title: 'No Products Found',
                        text: 'No products match your search criteria in this branch',
                        confirmButtonColor: '#3498db'
                    });

                } else {
                    // Display products
                    table.querySelector('table tbody').innerHTML = '';
                    table.style.opacity = '1';

                    response.products.forEach((product, index) => {
                        // Determine stock badge color
                        let stockBadgeClass = '';
                        if (product.stock > 10) {
                            stockBadgeClass = 'badge-success';
                        } else if (product.stock > 0) {
                            stockBadgeClass = 'badge-warning';
                        } else {
                            stockBadgeClass = 'badge-danger';
                        }

                        let row = document.createElement('tr');
                        row.style.cursor = product.stock > 0 ? 'pointer' : 'not-allowed';
                        row.style.opacity = product.stock > 0 ? '1' : '0.6';
                        row.classList.add('product-search-row');

                        row.innerHTML = `
                        <td>
                            <button name="productId"
                                    id="productId"
                                    class="btn-select-product"
                                    style="display: none;"
                                    value="${product.product_id}"
                                    data-stock="${product.stock}"
                                    data-branch-id="${product.branch_id}"
                                    data-branch-name="${product.branch_name}"></button>
                            <strong style="color: #667eea;">${product.product_id}</strong>
                        </td>
                        <td style="max-width: 250px;">
                            ${product.description}
                        </td>
                        <td style="text-align: center;">
                            <span style="color: #f39c12; font-weight: 600;">${product.tax}%</span>
                        </td>
                        <td style="text-align: right;">
                            <strong style="color: #27ae60; font-size: 15px;">
                                ${parseFloat(product.retail_price).toFixed(2)} QAR
                            </strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge ${stockBadgeClass}" style="font-size: 13px; padding: 6px 12px;">
                                ${product.stock}
                            </span>
                        </td>
                    `;

                        // Click event for row
                        if (product.stock > 0) {
                            row.addEventListener('click', function() {
                                row.querySelector('#productId').click();
                            });
                        } else {
                            row.addEventListener('click', function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Out of Stock',
                                    text: 'This product is out of stock in this branch',
                                    confirmButtonColor: '#e74c3c'
                                });
                            });
                        }

                        table.querySelector('table tbody').appendChild(row);
                    });

                    table.querySelector('table').style.display = 'table';
                    table.querySelector('.no-items').style.display = 'none';

                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Products Found!',
                        text: `Found ${response.products.length} products in this branch`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Search Error:', xhr.responseJSON);

                let errorMessage = 'An error occurred while searching';

                if (xhr.status === 400 || xhr.status === 403) {
                    errorMessage = xhr.responseJSON?.message || errorMessage;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Search Failed',
                    text: errorMessage,
                    confirmButtonColor: '#e74c3c'
                });

                table.querySelector('.no-items').style.display = 'block';
                table.querySelector('table').style.display = 'none';
            }
        });
    });


    // ==================== GET PRODUCT DETAILS & ADD TO INVOICE ====================
    $(document).on('click', '#productId', function (e) {
        e.preventDefault();

        let product_id = $(this).val();
        let stock = $(this).data('stock');
        let branch_id = $(this).data('branch-id');
        let branch_name = $(this).data('branch-name');

        // Validate stock
        if (!stock || stock <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Out of Stock',
                text: 'This product is out of stock in this branch',
                confirmButtonColor: '#e74c3c'
            });
            return;
        }

        // Get selected branch from form
        let selectedBranchId = document.querySelector('#branch_id').value;

        if (!selectedBranchId) {
            Swal.fire({
                icon: 'warning',
                title: 'Branch Required',
                text: 'Please select a branch first!',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Loading...',
            text: 'Getting product details',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // AJAX Request
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "GET",
            url: '{{route("dashboard.get-product-details")}}',
            data: {
                product_id: product_id,
                branch_id: selectedBranchId
            },
            success: function(response) {
                console.log('Product Details:', response);

                if (!response.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to get product details',
                        confirmButtonColor: '#e74c3c'
                    });
                    return;
                }

                // Close loading
                Swal.close();

                // Handle product response
                handleProductResponse(response.product);

                // Close search modal
                $('#searchModal').modal('hide');

                // Success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added!',
                    text: `Product ${product_id} from ${branch_name}`,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr, status, error) {
                console.error('Product Details Error:', xhr.responseJSON);

                let errorMessage = 'Failed to get product details';

                if (xhr.status === 404) {
                    errorMessage = 'Product not found in this branch or out of stock';
                } else if (xhr.status === 403) {
                    errorMessage = xhr.responseJSON?.message || 'You do not have access to this branch';
                } else if (xhr.status === 400) {
                    errorMessage = xhr.responseJSON?.message || errorMessage;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    });


    // ==================== HELPER: HANDLE PRODUCT RESPONSE ====================
    function handleProductResponse(product) {
        // Fill product ID and quantity fields
        $('#product_id').val(product.product_id);

        // Set default quantity if empty
        if (!$('#product_quantity').val() || $('#product_quantity').val() <= 0) {
            $('#product_quantity').val(1);
        }

        // Validate quantity against stock
        let requestedQty = parseInt($('#product_quantity').val());
        let availableStock = parseInt(product.stock);

        if (requestedQty > availableStock) {
            Swal.fire({
                icon: 'warning',
                title: 'Insufficient Stock',
                text: `Only ${availableStock} units available. Quantity adjusted.`,
                confirmButtonColor: '#f39c12'
            });
            $('#product_quantity').val(availableStock);
        }

        // Display product info (optional)
        console.log('Product Selected:', {
            id: product.product_id,
            description: product.description,
            price: product.retail_price,
            stock: product.stock,
            branch: product.branch_name
        });

        // Focus on quantity field
        $('#product_quantity').focus().select();
    }


    // ==================== ADDITIONAL: PRODUCT ID ENTER KEY ====================
    $('#product_id').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();

            let product_id = $(this).val();
            let branch_id = $('#branch_id').val();

            if (!branch_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch first!',
                    confirmButtonColor: '#3498db'
                });
                $('#branch_id').focus();
                return;
            }

            if (!product_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Product ID Required',
                    text: 'Please enter a product ID',
                    confirmButtonColor: '#3498db'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Searching...',
                text: 'Looking for product',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get product details
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "GET",
                url: '{{route("dashboard.get-product-details")}}',
                data: {
                    product_id: product_id,
                    branch_id: branch_id
                },
                success: function(response) {
                    Swal.close();

                    if (response.success) {
                        handleProductResponse(response.product);

                        Swal.fire({
                            icon: 'success',
                            title: 'Product Found!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: response.message,
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Product not found in this branch';

                    if (xhr.status === 404) {
                        errorMessage = 'Product not found or out of stock in this branch';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have access to this branch';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        }
    });


    // ==================== BRANCH CHANGE WARNING ====================
    $('#branch_id').on('change', function() {
        let hasProducts = $('.products tbody tr').length > 0;

        if (hasProducts) {
            Swal.fire({
                icon: 'warning',
                title: 'Branch Changed',
                text: 'Changing branch will clear current invoice items. Continue?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Change Branch',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6'
            }).then((result) => {
                if (!result.isConfirmed) {
                    // Revert to previous branch
                    $(this).val($(this).data('previous-branch'));
                } else {
                    // Clear invoice items
                    $('.products tbody').empty();

                    // Reset totals
                    $('#TotalQTY').text('0');
                    $('#TotalPrice').text('0.00');
                    $('#TotalNet').text('0.00');
                    $('#TotalTax').text('0.00');
                    $('#Totals').text('0.00 QAR');
                }
            });
        }

        // Store current branch for reverting
        $(this).data('previous-branch', $(this).val());
    });*/

    /**
     * ====================================================
     * PRODUCT SEARCH & ADD TO INVOICE - مظبوط 100%
     * ====================================================
     */

    $(document).ready(function() {

        // ==================== FULL SEARCH ====================
        $('.full-search').on('click', function (e) {
            e.preventDefault();

            // Get selected branch
            let branch_id = $('#branch_id').val();

            // Validate branch selection
            if (!branch_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch first!',
                    confirmButtonColor: '#3498db'
                });
                $('#branch_id').focus();
                return;
            }

            // Get search parameters
            let category_id = $('#category_id').val();
            let brand_input_id = $('#brand_input').attr('data-id');
            let model_input_id = $('#model_input').attr('data-id');
            let color = $('#color').val();
            let size = $('#size').val();
            let segment = $('#brandsegment_input').val();
            let power = $('#power').val();
            let sign = $('#sign').val();
            let type = $('#type').val();

            // Show loading
            let table = $('.panel-table');
            table.find('table tbody').html(`
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p style="margin-top: 15px; color: #667eea; font-weight: 600;">
                        Searching products...
                    </p>
                </td>
            </tr>
        `);
            table.find('table').show();
            table.find('.no-items').hide();

            // AJAX Request
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: '{{route("dashboard.full-search")}}',
                data: {
                    branch_id: branch_id,
                    category_id: category_id,
                    brand_id: brand_input_id,
                    model_id: model_input_id,
                    color: color,
                    size: size,
                    brand_segment: segment,
                    power: power,
                    sign: sign,
                    type: type
                },
                success: function (response) {
                    console.log('Search Response:', response);

                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Search failed',
                            confirmButtonColor: '#e74c3c'
                        });

                        table.find('.no-items').show();
                        table.find('table').hide();
                        return;
                    }

                    if (response.products.length == 0) {
                        table.find('.no-items').show();
                        table.find('table').hide();

                        Swal.fire({
                            icon: 'info',
                            title: 'No Products Found',
                            text: 'No products match your search criteria in this branch',
                            confirmButtonColor: '#3498db'
                        });
                    } else {
                        displayProducts(response.products);

                        Swal.fire({
                            icon: 'success',
                            title: 'Products Found!',
                            text: `Found ${response.products.length} products`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Search Error:', xhr);

                    let errorMessage = 'An error occurred while searching';

                    if (xhr.status === 400 || xhr.status === 403) {
                        errorMessage = xhr.responseJSON?.message || errorMessage;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Search Failed',
                        text: errorMessage,
                        confirmButtonColor: '#e74c3c'
                    });

                    table.find('.no-items').show();
                    table.find('table').hide();
                }
            });
        });

        // ==================== DISPLAY PRODUCTS ====================
        function displayProducts(products) {
            let table = $('.panel-table');
            table.find('table tbody').html('');

            products.forEach(function(product) {
                // Determine stock badge color
                let stockBadgeClass = product.stock > 10 ? 'badge-success' :
                    product.stock > 0 ? 'badge-warning' : 'badge-danger';

                let row = $('<tr></tr>');
                row.css({
                    'cursor': product.stock > 0 ? 'pointer' : 'not-allowed',
                    'opacity': product.stock > 0 ? '1' : '0.6',
                    'transition': 'all 0.3s'
                });
                row.addClass('product-search-row');

                row.html(`
                <td>
                    <strong style="color: #667eea;">${product.product_id}</strong>
                </td>
                <td style="max-width: 250px;">
                    ${product.description || 'N/A'}
                </td>
                <td style="text-align: center;">
                    <span style="color: #f39c12; font-weight: 600;">${product.tax}%</span>
                </td>
                <td style="text-align: right;">
                    <strong style="color: #27ae60; font-size: 15px;">
                        ${parseFloat(product.retail_price).toFixed(2)} QAR
                    </strong>
                </td>
                <td style="text-align: center;">
                    <span class="badge ${stockBadgeClass}" style="font-size: 13px; padding: 6px 12px;">
                        ${product.stock}
                    </span>
                </td>
                <td style="text-align: center;">
                    ${product.stock > 0 ?
                    `<button type="button"
                                class="btn btn-sm btn-select-product"
                                data-product-id="${product.product_id}"
                                data-stock="${product.stock}"
                                data-branch-id="${product.branch_id}"
                                data-branch-name="${product.branch_name}"
                                data-description="${product.description || ''}"
                                data-price="${product.retail_price}"
                                data-tax="${product.tax}"
                                data-category="${product.category_id}"
                                style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
                                       color: white; border: none; padding: 8px 20px;
                                       border-radius: 6px; font-weight: 600; transition: all 0.3s;
                                       box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);">
                            <i class="bi bi-check-circle"></i> Select
                        </button>`
                    :
                    `<span style="color: #e74c3c; font-weight: 600;">
                            <i class="bi bi-x-circle"></i> Out of Stock
                        </span>`
                }
                </td>
            `);

                // Hover effect
                row.on('mouseenter', function() {
                    if (product.stock > 0) {
                        $(this).css({
                            'background': 'linear-gradient(135deg, #f8f9ff 0%, #fff 100%)',
                            'transform': 'translateX(3px)'
                        });
                    }
                }).on('mouseleave', function() {
                    $(this).css({
                        'background': 'white',
                        'transform': 'translateX(0)'
                    });
                });

                table.find('table tbody').append(row);
            });

            table.find('table').show();
            table.find('.no-items').hide();
        }

        // ==================== SELECT PRODUCT & ADD TO INVOICE ====================
        $(document).on('click', '.btn-select-product', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let $btn = $(this);
            let product_id = $btn.data('product-id');
            let stock = $btn.data('stock');
            let branch_id = $btn.data('branch-id');
            let branch_name = $btn.data('branch-name');

            // Validate stock
            if (!stock || stock <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Out of Stock',
                    text: 'This product is out of stock',
                    confirmButtonColor: '#e74c3c'
                });
                return;
            }

            // Get selected branch from form
            let selectedBranchId = $('#branch_id').val();

            if (!selectedBranchId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch first!',
                    confirmButtonColor: '#3498db'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Adding Product...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX Request
            $.ajax({
                /*headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },*/
                type: "GET",
                url: "{{ route('dashboard.get-product-details') }}",
                data: {
                    product_id: product_id,
                    branch_id: selectedBranchId
                },
                success: function(response) {
                    console.log('Product Details:', response);

                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to get product',
                            confirmButtonColor: '#e74c3c'
                        });
                        return;
                    }

                    Swal.close();

                    // Add product to invoice
                    addProductToInvoice(response.product);

                    // Close modal
                    $('#searchModal').modal('hide');

                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Added!',
                        text: `${response.product.product_id} added to invoice`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    console.error('Error:', xhr);

                    let errorMessage = 'Failed to add product';

                    if (xhr.status === 404) {
                        errorMessage = 'Product not found or out of stock';
                    } else if (xhr.status === 403) {
                        errorMessage = xhr.responseJSON?.message || 'Access denied';
                    } else if (xhr.status === 400) {
                        errorMessage = xhr.responseJSON?.message || errorMessage;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        });

        // ==================== ADD PRODUCT TO INVOICE ====================
        function addProductToInvoice(product) {
            // Get quantity
            let quantity = parseInt($('#product_quantity').val()) || 1;

            // Validate quantity against stock
            if (quantity > product.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Insufficient Stock',
                    text: `Only ${product.stock} units available`,
                    confirmButtonColor: '#f39c12'
                });
                quantity = product.stock;
            }

            // Calculate values
            let price = parseFloat(product.retail_price);
            let tax = parseFloat(product.tax);
            let net = price - (price * tax / 100);
            let total = price * quantity;

            // Prepare product data
            let productData = {
                Product: {
                    id: product.id,
                    product_id: product.product_id,
                    description: product.description,
                    category_id: product.category_id,
                    product_quantity: quantity,
                    price: price.toFixed(2),
                    net: net.toFixed(2),
                    tax: tax.toFixed(2),
                    total: total.toFixed(2),
                    stock: product.stock,
                    branch_id: product.branch_id,
                    branch_name: product.branch_name,
                    type: 'product'
                }
            };

            // Save to session
            $.ajax({
                /*headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },*/
                type: "GET",
                url: "{{ route('dashboard.store-data-in-session') }}", // استخدم الـ URL المباشر
                data: productData,
                success: function(response) {
                    console.log('Product saved to session:', response);

                    // Reload page to show updated invoice
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Session error:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add product to invoice',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        }

        // ==================== PRODUCT ID ENTER KEY ====================
        $('#product_id').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();

                let product_id = $(this).val();
                let branch_id = $('#branch_id').val();

                if (!branch_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Branch Required',
                        text: 'Please select a branch first!',
                        confirmButtonColor: '#3498db'
                    });
                    $('#branch_id').focus();
                    return;
                }

                if (!product_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Product ID Required',
                        text: 'Please enter a product ID',
                        confirmButtonColor: '#3498db'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Searching...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    /*headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },*/

                    type: "GET",
                    url: "{{ route('dashboard.get-product-details') }}",
                    data: {
                        product_id: product_id,
                        branch_id: branch_id
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.success) {
                            addProductToInvoice(response.product);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Not Found',
                                text: response.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Product not found in this branch',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });

        // ==================== BRANCH CHANGE WARNING ====================
        $('#branch_id').on('change', function() {
            let hasProducts = $('.products tbody tr').length > 0;

            if (hasProducts) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Changed',
                    text: 'Changing branch will clear invoice items. Continue?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Change',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3498db',
                    cancelButtonColor: '#95a5a6'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        $(this).val($(this).data('previous-branch'));
                    } else {
                        // Clear session
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '/dashboard/session/delete-product',
                            data: { clear_all: true },
                            success: function() {
                                location.reload();
                            }
                        });
                    }
                });
            }

            $(this).data('previous-branch', $(this).val());
        });

        // ==================== BUTTON HOVER EFFECT ====================
        $(document).on('mouseenter', '.btn-select-product', function() {
            $(this).css({
                'transform': 'translateY(-2px)',
                'box-shadow': '0 5px 20px rgba(39, 174, 96, 0.5)'
            });
        }).on('mouseleave', '.btn-select-product', function() {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': '0 3px 10px rgba(39, 174, 96, 0.3)'
            });
        });
    });


    // ==================== STYLING FOR SEARCH RESULTS ====================
    $('<style>')
        .text(`
        .product-search-row:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%) !important;
            transform: translateX(3px);
            transition: all 0.3s;
        }

        .badge-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .badge-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .badge-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }
    `)
        .appendTo('head');

    // get products -- doctors
    // $(document).ready(function () {

        let Id = document.getElementById('id').value;
        window.localStorage.setItem("Id", Id);

        let customerId = document.getElementById('customerId').value;
        window.localStorage.setItem("customerId", customerId);

        let customerName = document.getElementById('customer_name').value;
        window.localStorage.setItem("customerName", customerName);

        /*var doctorId = window.localStorage.getItem("doctorId");
        var doctorName = window.localStorage.getItem("doctorName");

        document.getElementById('doctor_id').value = doctorId;
        document.getElementById('doctor_name').value = doctorName;*/


        var doctorId = window.localStorage.getItem("doctorId");
        var doctorName = window.localStorage.getItem("doctorName");


        var doctorIdInput = document.getElementById('doctor_id');
        var doctorNameInput = document.getElementById('doctor_name');

        if (doctorIdInput && (!doctorIdInput.value || doctorIdInput.value.trim() === "")) {
            doctorIdInput.value = doctorId || "";
        }

        if (doctorNameInput && (!doctorNameInput.value || doctorNameInput.value.trim() === "")) {
            doctorNameInput.value = doctorName || "";
        }


        $(document).on('click', '#customerId', function (e) {
            e.preventDefault();
            let customer_id = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "GET",
                url: '{{route("dashboard.get-customer-details")}}',
                data: {customer_id: customer_id},
                success: function (response) {
                    document.getElementById('customer_id').value = response.customer.customer_id;
                    document.getElementById('customer_name').value = response.customer.english_name + ' / ' + response.customer.local_name;

                    $('#customerModal').modal('hide');
                }
            });

        })


        $(document).on('click', '#doctorId', function (e) {
            e.preventDefault();

            let doctor_id = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "GET",
                url: "{{route("dashboard.get-doctor-details")}}",
                data: { doctor_id: doctor_id },

                success: function (response) {

                    if (!response || !response.doctor) {
                        alert('❌ لم يتم جلب بيانات الدكتور');
                        return;
                    }

                    $('#doctor-id').val(response.doctor.code).attr('value', response.doctor.code);

                    $('#doctor_id').val(response.doctor.code).attr('value', response.doctor.code);

                    $('#doctor_name').val(response.doctor.name).attr('value', response.doctor.name);



                    localStorage.setItem("doctorId", response.doctor.code);
                    localStorage.setItem("doctorName", response.doctor.name);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: "{{route("dashboard.set-doctor-session")}}",
                        data: {
                            doctor_id: response.doctor.code
                        },
                        success: function (res) {

                            if (res.status === true) {
                                $('#DoctorModal').modal('hide');
                                console.log('✅ تم حفظ الدكتور في السيشن');
                            } else {
                                alert('❌ فشل حفظ الدكتور في السيشن');
                            }

                        },
                        error: function () {
                            alert('❌ خطأ أثناء حفظ السيشن');
                        }
                    });
                },

                error: function () {
                    alert('❌ خطأ أثناء جلب بيانات الدكتور');
                }
            });
        });



        // On Changing Category Select Box
        let categorySelector = document.querySelector('#category_id');
        $(categorySelector).on('change', function (e) {
            let brand_input = document.querySelector('#brand_input');
            let model_input = document.querySelector('#model_input');
            brand_input.value = '';
            model_input.value = '';
            // Showing Filters Container
            if ($(this).val() != '') {
                let flitersContainer = document.querySelector('.filters-container');
                flitersContainer.style.display = 'block';
            }


            // Hide size and color if categoy is contact lenses
            let categoryText = categorySelector.options[categorySelector.selectedIndex].innerText;
            let typeContainer = document.getElementById('type').parentElement;
            let sizeContainer = document.getElementById('size').parentElement;
            let cololContainer = document.getElementById('color').parentElement;
            let powerontainer = document.getElementById('power').parentElement;
            let signContainer = document.getElementById('sign').parentElement;
            let segment = document.getElementById('brandsegment_input').parentElement;

            if (categoryText == 'Contact Lenses') {
                sizeContainer.style.display = 'none';
                cololContainer.style.display = 'none';
                typeContainer.style.display = 'none';
                signContainer.style.display = 'flex';
                powerontainer.style.display = 'flex';
                segment.style.display = 'flex';

            } else if (categoryText == 'OTHERS (READING GLASSES)') {
                signContainer.style.display = 'none';
                segment.style.display = 'none';
                typeContainer.style.display = 'flex';
                powerontainer.style.display = 'none';

                typeContainer.querySelector('select').innerHTML = `
                    <option value=""></option>
                    <option value="folding metal">FOLDING METAL</option>
                    <option value="folding plastic">FOLDING PLASTIC</option>
                    <option value="metal frame">METAL FRAME</option>
                    <option value="plastic frame">PLASTIC FRAME</option>
                    `;

            } else if (categoryText == 'OTHERS (CHAINS)') {
                sizeContainer.style.display = 'none';
                cololContainer.style.display = 'none';
                typeContainer.style.display = 'flex';
                signContainer.style.display = 'none';
                signContainer.style.display = 'none';
                powerontainer.style.display = 'none';
                segment.style.display = 'none';

                typeContainer.querySelector('select').innerHTML = `
                        <option value=""></option>
                        <option value="multicolor">MULTICOLOR</option>
                        <option value="silver" plastic">SILVER</option>
                        <option value="gold">GOLD</option>
                    `;

            } else if (categoryText == 'OTHERS (C.L SOLUTION)') {
                sizeContainer.style.display = 'none';
                cololContainer.style.display = 'none';
                typeContainer.style.display = 'flex';
                signContainer.style.display = 'none';
                powerontainer.style.display = 'none';
                segment.style.display = 'none';

                typeContainer.querySelector('select').innerHTML = `
                        <option value=""></option>
                        <option value="opti free">OPTI FREE</option>
                    `;

            } else {
                sizeContainer.style.display = 'flex';
                cololContainer.style.display = 'flex';
                typeContainer.style.display = 'none';
                signContainer.style.display = 'none';
                signContainer.style.display = 'none';
                powerontainer.style.display = 'none';
                segment.style.display = 'none';
            }


            // Get All Brands Under Certain Category And Set Them In the PickUp Values Section
            $('.filter-brands').on('click', function (e) {
                e.preventDefault();
                let brand_value = document.querySelector('#brand_input').value;

                // Get all Brands Under The Choosed Category

                category_ID = document.querySelector('#category_id').value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "POST",
                    url: '{{route("dashboard.filter-brands-by-category-id")}}',
                    data: {category_id: category_ID},
                    success: function (response) {
                        table = document.querySelector('.panel.right .panel-body table tbody');
                        document.querySelector('.panel.right .panel-body table').style.opacity = '1';
                        table.innerHTML = '';
                        if (response.length != 0) {
                            document.querySelector('.panel.right .panel-body .no-items').style.display = 'none';
                            response.forEach((brand, index) => {
                                let row = document.createElement('tr');
                                row.innerHTML += `
                                        <td>
                                            <a href="#" class="translate-brand translate"><i class="fa fa-arrows-h"></i></a>
                                            <p class="text-center"><strong data-id="${brand.id}">${brand.brand_name}</strong></p>
                                        </td>
                                    `;
                                table.appendChild(row)
                            });
                        } else {
                            document.querySelector('.panel.right .panel-body table').style.opacity = '0';
                            document.querySelector('.panel.right .panel-body .no-items').style.display = 'block';
                        }
                    }
                });
            });

            // Filter Models
            $('.filter-models').on('click', function (e) {
                e.preventDefault();
                let model_value = document.querySelector('#model_input').value;

                // Get all Models Under The Choosed Brand

                category_ID = document.querySelector('#category_id').value;
                brand_ID = document.querySelector('#brand_input').getAttribute('data-id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "POST",
                    url: '{{route("dashboard.filter-models-by-category-and-brand-id")}}',
                    data: {category_id: category_ID, brand_id: brand_ID},
                    success: function (response) {
                        table = document.querySelector('.panel.right .panel-body table tbody');
                        document.querySelector('.panel.right .panel-body table').style.opacity = '1';
                        table.innerHTML = '';
                        if (response.length != 0) {
                            document.querySelector('.panel.right .panel-body .no-items').style.display = 'none';
                            response.forEach((model, index) => {
                                let row = document.createElement('tr');
                                row.innerHTML += `
                                        <td>
                                            <a href="#" class="translate-model translate"><i class="fa fa-arrows-h"></i></a>
                                            <p class="text-center"><strong data-id="${model.id}">${model.model_id}</strong></p>
                                        </td>
                                    `;
                                table.appendChild(row)
                            });
                        } else {
                            document.querySelector('.panel.right .panel-body table').style.opacity = '0';
                            document.querySelector('.panel.right .panel-body .no-items').style.display = 'block';
                        }
                    }
                });

            });

        });

        // Translate Brand to left box
        let brandTable = document.querySelector('.panel.right .panel-body table');
        brandTable.addEventListener('click', function (e) {
            if (e.target.tagName == 'I' & e.target.parentElement.classList.contains('translate-brand')) {
                let brandValue = e.target.parentElement.nextSibling.nextSibling.firstChild;
                let brandID = brandValue.getAttribute('data-id');

                let brandSelect = document.querySelector('#brand_input');
                brandSelect.value = brandValue.innerText;
                brandSelect.setAttribute('data-id', brandID);
            }

            if (e.target.tagName == 'I' & e.target.parentElement.classList.contains('translate-model')) {
                let modelValue = e.target.parentElement.nextSibling.nextSibling.firstChild;
                let modelID = modelValue.getAttribute('data-id');

                let modelSelect = document.querySelector('#model_input');
                modelSelect.value = modelValue.innerText;
                modelSelect.setAttribute('data-id', modelID);
            }
        });



        // Reset Brand Input
        $('.reset-brand').on('click', function (e) {
            e.preventDefault();
            $('#brand_input').val('');
            $('#brand_input').attr('data-id', '');
        });

        // Reset Model Input
        $('.reset-model').on('click', function (e) {
            e.preventDefault();
            $('#model_input').val('');
            $('#model_input').attr('data-id', '');
        });

        // Hide Products Table on modal closing
        $('#myModal, #updateModal').on('hidden.bs.modal', function (e) {
            document.querySelector('.panel-table div table').style.opacity = '0';
        })

        let product_id = document.querySelector('#product_id');
        let product_quantity = document.querySelector('#product_quantity');

        // On Enter Press To add More Than one Product To Invoice
        $(product_id).keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                event.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "GET",
                    url: '{{route("dashboard.get-product-details")}}',
                    data: {product_id: product_id.value},
                    success: function (resp) {
                        handleProductResponseWithQty(resp, product_quantity.value);
                    },
                    error: function (resp) {
                    }
                });
            }
        });


        function handleProductResponse(resp) {
            /*if (+resp.product[0].amount === 0) {
                new Noty({
                    text: '⚠️ This product is out of stock.',
                    type: 'warning',
                    killer: true,
                    timeout: 3000
                }).show();
                styleNotyLayout('warning');
                return;
            }*/

            let table = $('.product_invoice_table table');
            table.css({ "opacity": 1 });
            let body = $('.product_invoice_table table tbody');

            let productId = resp.product[0].product_id;
            let stock = +resp.product[0].amount;
            let existingRow = body.find(`#P_${productId}`);
            let newQty = 1;

            if (existingRow.length > 0) {
                let currentQty = parseInt(existingRow.find('.QTY').text(), 10);

                /*if (currentQty < stock) {*/
                    newQty = currentQty + 1;
                    existingRow.find('.QTY').text(newQty);


                    let unitPrice = parseFloat(existingRow.find('.PRICE').text());
                    let newNet = (unitPrice * newQty).toFixed(2);
                    existingRow.find('.NET').text(newNet);

                    let tax = parseFloat(existingRow.find('.TAX').text());
                    let newTotal = (unitPrice * newQty + tax).toFixed(2);
                    existingRow.find('.TOTALS').text(newTotal);

                    existingRow.attr('data-original-net', newNet);
                    existingRow.attr('data-original-total', newTotal);

                    updateQuantity();
                    updateTotalPrice();
                    updateTotalNet();
                    updateTotalTax();
                    updateTotalTotal();
                    //$('#searchModal').modal('hide');
                    new Noty({
                        text: '✅ Product quantity increased successfully.',
                        type: 'success',
                        killer: true,
                        timeout: 3000
                    }).show();
                    styleNotyLayout('success');

                let Product = {
                    id: resp.product[0].id,
                    product_id: resp.product[0].product_id,
                    product_category_id: resp.product[0].category_id,
                    description: resp.product[0].description,
                    product_quantity: newQty,
                    price: unitPrice,
                    net: resp.product[0].retail_price,
                    tax: tax,
                    total: newTotal,
                    stock: resp.product[0].amount,
                    branch_name: resp.product[0].branch_name,
                    type: 'product'
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "GET",
                    url: '{{route("dashboard.store-data-in-session")}}',
                    data: { Product },
                    success: function (response) {
                        console.log('Session updated', response);
                    }
                });


                return;
                /*}
                else {
                    new Noty({
                        text: `⚠️ This product is already added and only ${stock} is available in stock.`,
                        type: 'warning',
                        killer: true,
                        timeout: 3000
                    }).show();
                    styleNotyLayout('warning');
                    return;
                }*/
            }



            // لو مش موجود
            let row = `<tr id="P_${resp.product[0].product_id}" data-category="${resp.product[0].category_id}">
                    <td>${resp.product[0].id}</td>
                    <td>${resp.product[0].product_id}</td>
                    <td>${resp.product[0].description}</td>
                    <td class="QTY">1</td>
                    <td class="PRICE">${(+resp.product[0].retail_price).toFixed(0)}</td>
                    <td class="NET">${(+resp.product[0].retail_price).toFixed(0)}</td>
                    <td class="TAX">${(+resp.product[0].tax).toFixed(0)}</td>
                    <td class="TOTALS">${(+resp.product[0].tax) + (+resp.product[0].retail_price)}</td>
                    <td>${resp.product[0].amount}</td>
                    <td>${resp.product[0].branch_name}</td>
                    <td><button class="delete-invoice-item btn btn-danger" data-id=${resp.product[0].product_id}>Delete <i class="fa fa-trash-o"></i></button></td>
                    <td style="display: none">product</td>
                </tr>`;
            body.append(row);

            let $newRow = body.find('tr').last();
            $newRow.attr('data-original-net', $newRow.find('.NET').text());
            $newRow.attr('data-original-total', $newRow.find('.TOTALS').text());

            //$('#searchModal').modal('hide');
            new Noty({
                text: '✅ Product added successfully.',
                type: 'success',
                killer: true,
                timeout: 3000
            }).show();
            styleNotyLayout('success');


            let totalQTY = updateQuantity();
            let totalPRICE = updateTotalPrice();
            let totalNET = updateTotalNet();
            let totalTAX = updateTotalTax();
            let T = updateTotalTotal();


            let Product = {
                id: resp.product[0].id,
                product_id: resp.product[0].product_id,
                product_category_id: resp.product[0].category_id,
                description: resp.product[0].description,
                product_quantity: newQty,
                price: resp.product[0].retail_price,
                net: resp.product[0].retail_price,
                tax: resp.product[0].tax,
                total: resp.product[0].retail_price + resp.product[0].tax,
                stock: resp.product[0].amount,
                branch_name: resp.product[0].branch_name,
                type: 'product'
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "GET",
                url: '{{route("dashboard.store-data-in-session")}}',
                data: { Product },
                success: function (response) {
                    console.log(response)
                }
            });

            let totals = {
                totalQTY: totalQTY,
                totalPRICE: totalPRICE,
                totalNET: totalNET,
                totalTAX: totalTAX,
                Totals: T,
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "GET",
                url: '{{route("dashboard.store-total-in-session")}}',
                data: { totals },
                success: function (response) { }
            });
        }

        function handleProductResponseWithQty(resp, qty) {

            let table = $('.product_invoice_table table');
            table.css({ "opacity": 1 });
            let body = $('.product_invoice_table table tbody');

            let productId = resp.product[0].product_id;
            let stock = +resp.product[0].amount;
            let existingRow = body.find(`#P_${productId}`);

            qty = parseInt(qty);
            if (!qty || qty <= 0) qty = 1;

            let newQty = qty;

            if (existingRow.length > 0) {

                let currentQty = parseInt(existingRow.find('.QTY').text(), 10);

                newQty = currentQty + qty;
                existingRow.find('.QTY').text(newQty);

                let unitPrice = parseFloat(existingRow.find('.PRICE').text());

                let newNet = (unitPrice * newQty).toFixed(2);
                existingRow.find('.NET').text(newNet);

                let taxPerUnit = resp.product[0].tax;
                let newTax = (taxPerUnit * newQty).toFixed(2);
                existingRow.find('.TAX').text(newTax);

                let newTotal = (parseFloat(newNet) + parseFloat(newTax)).toFixed(2);
                existingRow.find('.TOTALS').text(newTotal);

                existingRow.attr('data-original-net', newNet);
                existingRow.attr('data-original-total', newTotal);

                updateQuantity();
                updateTotalPrice();
                updateTotalNet();
                updateTotalTax();
                updateTotalTotal();

                new Noty({
                    text: '✅ Product quantity increased successfully.',
                    type: 'success',
                    killer: true,
                    timeout: 3000
                }).show();
                styleNotyLayout('success');

                let Product = {
                    id: resp.product[0].id,
                    product_id: resp.product[0].product_id,
                    product_category_id: resp.product[0].category_id,
                    description: resp.product[0].description,
                    product_quantity: newQty,
                    price: unitPrice,
                    net: resp.product[0].retail_price,
                    tax: taxPerUnit,
                    total: newTotal,
                    stock: resp.product[0].amount,
                    branch_name: resp.product[0].branch_name,
                    type: 'product'
                };

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    type: "GET",
                    url: '{{route("dashboard.store-data-in-session")}}',
                    data: { Product }
                });

                return;
            }


            let unitPrice = parseFloat(resp.product[0].retail_price);
            let net = (unitPrice * qty).toFixed(2);
            let tax = (resp.product[0].tax * qty).toFixed(2);
            let total = (parseFloat(net) + parseFloat(tax)).toFixed(2);

            let row = `<tr id="P_${resp.product[0].product_id}" data-category="${resp.product[0].category_id}">
        <td>${resp.product[0].id}</td>
        <td>${resp.product[0].product_id}</td>
        <td>${resp.product[0].description}</td>
        <td class="QTY">${qty}</td>
        <td class="PRICE">${unitPrice}</td>
        <td class="NET">${net}</td>
        <td class="TAX">${tax}</td>
        <td class="TOTALS">${total}</td>
        <td>${resp.product[0].amount}</td>
        <td>${resp.product[0].branch_name}</td>
        <td>
            <button class="delete-invoice-item btn btn-danger"
                    data-id="${resp.product[0].product_id}">
                Delete <i class="fa fa-trash-o"></i>
            </button>
        </td>
        <td style="display: none">product</td>
    </tr>`;

            body.append(row);

            let $newRow = body.find('tr').last();
            $newRow.attr('data-original-net', net);
            $newRow.attr('data-original-total', total);

            new Noty({
                text: '✅ Product added successfully.',
                type: 'success',
                killer: true,
                timeout: 3000
            }).show();
            styleNotyLayout('success');

            let totalQTY = updateQuantity();
            let totalPRICE = updateTotalPrice();
            let totalNET = updateTotalNet();
            let totalTAX = updateTotalTax();
            let T = updateTotalTotal();

            let Product = {
                id: resp.product[0].id,
                product_id: resp.product[0].product_id,
                product_category_id: resp.product[0].category_id,
                description: resp.product[0].description,
                product_quantity: qty,
                price: unitPrice,
                net: unitPrice,
                tax: resp.product[0].tax,
                total: total,
                stock: resp.product[0].amount,
                branch_name: resp.product[0].branch_name,
                type: 'product'
            };

            $.ajax({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: "GET",
                url: '{{route("dashboard.store-data-in-session")}}',
                data: { Product }
            });

            let totals = {
                totalQTY: totalQTY,
                totalPRICE: totalPRICE,
                totalNET: totalNET,
                totalTAX: totalTAX,
                Totals: T
            };

            $.ajax({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                type: "GET",
                url: '{{route("dashboard.store-total-in-session")}}',
                data: { totals }
            });
        }



    //});
</script>
