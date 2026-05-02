<script>
    /**
     * ====================================================
     * INVOICE MAIN - jQuery Version
     * ====================================================
     */

// Global Variables
    var invoiceState = {
        branch_id: null,
        customer_id: null,
        doctor_id: null,
        items: [],
        discount: {
            type: null,
            value: 0,
            payer_type: null,
            payer_id: null,
            approval_amount: 0
        },
        payments: [],
        totals: {
            subtotal: 0,
            total_qty: 0,
            total_net: 0,
            total_tax: 0,
            discount_amount: 0,
            grand_total: 0,
            subtotal_before:0
        }
    };

    // CSRF Token Helper
    function getCSRFToken() {
        return $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val() || '';
    }

    // Show/Hide Loading
    function showLoading() {
        $('#loadingOverlay').addClass('active');
    }

    function hideLoading() {
        $('#loadingOverlay').removeClass('active');
    }

    // Round to 2 decimals
    function roundTo2(value) {
        return Math.round(parseFloat(value) * 100) / 100;
    }

    // Format currency
    function formatCurrency(value) {
        return parseFloat(value).toFixed(2) + ' QAR';
    }

    /**
     * ====================================================
     * CALCULATIONS
     * ====================================================
     */

    /*function calculateTotals() {
        var subtotal = 0;
        var total_qty = 0;
        var total_net = 0;
        var total_tax = 0;

        $.each(invoiceState.items, function(index, item) {
            total_qty += item.quantity;
            var item_total = parseFloat(item.total);
            subtotal += item_total;

            // Calculate tax amount
            var tax_amount = (item_total * item.tax / (100 + item.tax));
            total_tax += tax_amount;
            total_net += (item_total - tax_amount);
        });

        // Apply regular discount
        var discount_amount = 0;
        if (invoiceState.discount.type) {
            if (invoiceState.discount.type === 'fixed') {
                discount_amount = invoiceState.discount.value;
            } else if (invoiceState.discount.type === 'percentage') {
                discount_amount = (subtotal * invoiceState.discount.value / 100);
            }
        }

        // Apply approval amount
        if (invoiceState.discount.approval_amount) {
            discount_amount += invoiceState.discount.approval_amount;
        }

        // Calculate grand total
        var grand_total = subtotal - discount_amount;

        // Update state
        invoiceState.totals = {
            subtotal: roundTo2(subtotal),
            total_qty: total_qty,
            total_net: roundTo2(total_net),
            total_tax: roundTo2(total_tax),
            discount_amount: roundTo2(discount_amount),
            grand_total: roundTo2(grand_total)
        };

        return invoiceState.totals;
    }*/

    function calculateTotals() {
        var subtotal = 0;
        var subtotal_before = 0;  // ✅ أضف هنا
        var total_qty = 0;
        var total_net = 0;
        var total_tax = 0;

        $.each(invoiceState.items, function(index, item) {
            total_qty += item.quantity;

            // ✅ احسب قبل الخصم
            var item_total_before = item.price * item.quantity;
            subtotal_before += item_total_before;

            // احسب بعد الخصم
            var item_total = parseFloat(item.total);
            subtotal += item_total;

            // Calculate tax amount
            var tax_amount = (item_total * item.tax / (100 + item.tax));
            total_tax += tax_amount;
            total_net += (item_total - tax_amount);
        });

        // Apply regular discount
        var discount_amount = 0;
        if (invoiceState.discount.type) {
            if (invoiceState.discount.type === 'fixed') {
                discount_amount = invoiceState.discount.value;
            } else if (invoiceState.discount.type === 'percentage') {
                discount_amount = (subtotal * invoiceState.discount.value / 100);
            }
        }

        // Apply approval amount
        if (invoiceState.discount.approval_amount) {
            discount_amount += invoiceState.discount.approval_amount;
        }

        // Calculate grand total
        var grand_total = subtotal - discount_amount;

        // Update state
        invoiceState.totals = {
            subtotal_before: roundTo2(subtotal_before),  // ✅ أضفها
            subtotal: roundTo2(subtotal),
            total_qty: total_qty,
            total_net: roundTo2(total_net),
            total_tax: roundTo2(total_tax),
            discount_amount: roundTo2(discount_amount),
            grand_total: roundTo2(grand_total)
        };

        return invoiceState.totals;
    }

    function calculateItemTotal(item) {
        var subtotal = item.price * item.quantity;

        if (item.discount_percent && item.discount_percent > 0) {
            subtotal = subtotal - (subtotal * item.discount_percent / 100);
        }

        return roundTo2(subtotal);
    }

    /**
     * ====================================================
     * RENDER ITEMS TABLE
     * ====================================================
     */

    function renderItemsTable() {
        calculateTotals();

        var container = $('#itemsTableContainer');
        var itemsCount = $('#items_count');

        itemsCount.text(invoiceState.items.length + ' Items');

        if (invoiceState.items.length === 0) {
            container.html(`
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No Items Added Yet</h4>
                <p>Use the search above to add products to this invoice</p>
            </div>
        `);
            return;
        }

        var tableHTML = `
        <table class="table items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item ID</th>
                    <th>Description</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Discount %</th>
                    <th>Total</th>
                    <th>Stock</th>
                    <th>Branch</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    `;

        $.each(invoiceState.items, function(index, item) {
            var stockBadge = item.stock > 10 ? 'badge-success' :
                item.stock > 0 ? 'badge-warning' : 'badge-danger';

            tableHTML += `
            <tr>
                <td><strong>${index + 1}</strong></td>
                <td><strong style="color: #667eea;">${item.product_id}</strong></td>
                <td>${item.description}</td>
                <td><strong>${item.quantity}</strong></td>
              <td>${parseFloat(item.price).toFixed(2)}</td>
                <td>
                    ${item.discount_percent && item.discount_percent > 0
                                ? '<span style="color: #27ae60; font-weight: 700;">' + item.discount_percent + '%</span>'
                                : '-'}
                </td>
                <td><strong>${parseFloat(item.total).toFixed(2)}</strong></td>
                <td><span class="badge ${stockBadge}">${item.stock}</span></td>
                <td><span style="color: #3498db;">${item.branch_name}</span></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-item-btn" data-id="${item.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        });

        tableHTML += `
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>TOTAL</strong></td>
                <td><strong>${invoiceState.totals.total_qty}</strong></td>
                <td><strong>${parseFloat(invoiceState.totals.subtotal_before).toFixed(2)}</strong></td>
                <td>-</td>
                <td colspan="4">
`;

// ✅ Compare subtotal_before with grand_total
        if (parseFloat(invoiceState.totals.subtotal_before) !== parseFloat(invoiceState.totals.grand_total)) {
            // فيه خصم (عادي أو تأمين أو cardholder)
            tableHTML += `
                    <span style="text-decoration: line-through; color: #c8c7c7; font-weight: normal; margin-right: 15px;">
                        ${parseFloat(invoiceState.totals.subtotal_before).toFixed(2)} QAR
                    </span>
                    <strong style="color: #fff; font-size: 20px;">
                        ${parseFloat(invoiceState.totals.grand_total).toFixed(2)} QAR
                    </strong>
`;
        } else {
            // مفيش خصم
            tableHTML += `
                    <strong>${parseFloat(invoiceState.totals.grand_total).toFixed(2)} QAR</strong>
`;
        }

        tableHTML += `
                </td>
            </tr>
`;

// ✅ Discount row (show if any discount exists)
        var totalDiscount = parseFloat(invoiceState.totals.subtotal_before) - parseFloat(invoiceState.totals.grand_total);
        if (totalDiscount > 0) {
            tableHTML += `
            <tr style="background: #fff;">
                <td colspan="11" style="text-align: right; color: #856404;">
                    <strong>Total Discount: -${totalDiscount.toFixed(2)} QAR</strong>
                </td>
            </tr>
    `;
        }

        tableHTML += `
        </tfoot>
    </table>
`;

        if (invoiceState.totals.discount_amount > 0) {
            tableHTML += `
                <tr style="background: #fff3cd;">
                    <td colspan="11" style="text-align: right; color: #856404;">
                        <strong>Discount Applied: -${parseFloat(invoiceState.totals.discount_amount).toFixed(2)} QAR</strong>
                    </td>
                </tr>
        `;
        }

        tableHTML += `
            </tfoot>
        </table>
    `;

        container.html(tableHTML);

        // Bind delete events
        $('.delete-item-btn').on('click', function() {
            var itemId = $(this).data('id');
            deleteItem(itemId);
        });
    }

    /**
     * ====================================================
     * ADD PRODUCT
     * ====================================================
     */

    function addProduct() {
        var branch_id = $('#branch_id').val();
        var product_id = $('#product_id_input').val().trim();
        var quantity = parseInt($('#product_quantity').val()) || 1;

        // Validate
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
            $('#product_id_input').focus();
            return;
        }

        showLoading();

        $.ajax({
            url: '/dashboard/invoices/session/store-item',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            data: JSON.stringify({
                type: 'product',
                product_id: product_id,
                quantity: quantity,
                branch_id: branch_id
            }),
            contentType: 'application/json',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    invoiceState = response.draft;
                    renderItemsTable();

                    // Clear inputs
                    $('#product_id_input').val('');
                    $('#product_quantity').val(1);

                    Swal.fire({
                        icon: 'success',
                        title: 'Product Added!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            },
            error: function(xhr) {
                hideLoading();
                var message = 'Failed to add product';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }

    /**
     * ====================================================
     * DELETE ITEM
     * ====================================================
     */

    function deleteItem(item_id) {
        Swal.fire({
            icon: 'warning',
            title: 'Delete Item?',
            text: 'Are you sure you want to remove this item?',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6'
        }).then(function(result) {
            if (result.isConfirmed) {
                showLoading();

                $.ajax({
                    url: '/dashboard/invoices/session/delete-item',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    data: JSON.stringify({ item_id: item_id }),
                    contentType: 'application/json',
                    success: function(response) {
                        hideLoading();

                        if (response.success) {
                            invoiceState = response.draft;
                            renderItemsTable();

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Item removed successfully',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        hideLoading();
                    }
                });
            }
        });
    }

    /**
     * ====================================================
     * CLEAR INVOICE
     * ====================================================
     */

    function clearInvoice() {
        $.ajax({
            url: '/dashboard/invoices/session/clear',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            success: function(response) {
                if (response.success) {
                    invoiceState.items = [];
                    invoiceState.discount = {
                        type: null,
                        value: 0,
                        payer_type: null,
                        payer_id: null,
                        approval_amount: 0
                    };
                    invoiceState.payments = [];
                    invoiceState.totals = {
                        subtotal: 0,
                        total_qty: 0,
                        total_net: 0,
                        total_tax: 0,
                        discount_amount: 0,
                        grand_total: 0
                    };

                    renderItemsTable();
                    renderPayments();
                }
            }
        });
    }

    /**
     * ====================================================
     * RESET INVOICE
     * ====================================================
     */

    function resetInvoice() {
        Swal.fire({
            icon: 'warning',
            title: 'Reset Invoice?',
            text: 'This will clear all items and start fresh',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reset',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6'
        }).then(function(result) {
            if (result.isConfirmed) {
                clearInvoice();

                $('#discount_type').val('');
                $('#discount_value').val('');
                $('#payer_type').val('');
                $('#payer_company').val('').prop('disabled', true);
                $('#approval_amount').val('');

                Swal.fire({
                    icon: 'success',
                    title: 'Invoice Reset!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    /**
     * ====================================================
     * BRANCH CHANGE WARNING
     * ====================================================
     */

    function handleBranchChange() {
        var hasItems = invoiceState.items.length > 0;

        if (hasItems) {
            Swal.fire({
                icon: 'warning',
                title: 'Branch Change Warning',
                text: 'Changing branch will clear all invoice items. Continue?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Change Branch',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6'
            }).then(function(result) {
                if (result.isConfirmed) {
                    clearInvoice();
                    invoiceState.branch_id = $('#branch_id').val();
                } else {
                    $('#branch_id').val(invoiceState.branch_id);
                }
            });
        } else {
            invoiceState.branch_id = $('#branch_id').val();
        }
    }

    /**
     * ====================================================
     * LOAD DRAFT FROM SERVER
     * ====================================================
     */

    function loadDraftFromServer() {
        $.ajax({
            url: '/dashboard/invoices/session/get-draft',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            success: function(response) {
                if (response.success && response.draft) {
                    // Update state with server data
                    invoiceState = response.draft;

                    // Re-render table
                    renderItemsTable();

                    console.log('✅ Draft loaded from server:', invoiceState.items.length, 'items');
                } else {
                    // No draft, just render empty table
                    renderItemsTable();
                }
            },
            error: function() {
                console.log('ℹ️ No existing draft found');
                // Render empty table
                renderItemsTable();
            }
        });
    }

    /**
     * ====================================================
     * INITIALIZE
     * ====================================================
     */

    $(document).ready(function() {
        console.log('🚀 Invoice System Initialized');

        // Load initial state
        invoiceState.branch_id = $('#branch_id').val();
        invoiceState.customer_id = $('input[name="customer_id"]').val();
        invoiceState.doctor_id = $('#doctor_id').val();

        // ✅ FIX: Load draft from server (if exists)
        loadDraftFromServer();

        // Branch change event
        $('#branch_id').on('change', handleBranchChange);

        // Product ID Enter key
        $('#product_id_input').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                addProduct();
            }
        });

        // Add product button
        $('#addProductBtn').on('click', addProduct);

        // Reset button
        $('#resetInvoiceBtn').on('click', resetInvoice);
    });
</script>
