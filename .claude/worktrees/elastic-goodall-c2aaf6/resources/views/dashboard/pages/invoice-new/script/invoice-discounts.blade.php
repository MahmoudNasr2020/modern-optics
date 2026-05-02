<script>
    /**
     * ====================================================
     * INVOICE DISCOUNTS - jQuery Version
     * ====================================================
     */

    /**
     * ====================================================
     * REGULAR DISCOUNT
     * ====================================================
     */

    function applyRegularDiscount() {
        var discount_type = $('#discount_type').val();
        var discount_value = parseFloat($('#discount_value').val()) || 0;

        // Validate
        if (!discount_type) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Discount Type',
                text: 'Please select discount type first',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (discount_value <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Value',
                text: 'Please enter a valid discount value',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (invoiceState.items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items',
                text: 'Please add items before applying discount',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        showLoading();

        $.ajax({
            url: '/dashboard/invoices/discounts/apply-regular',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            data: JSON.stringify({
                discount_type: discount_type,
                discount_value: discount_value
            }),
            contentType: 'application/json',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    invoiceState = response.draft;
                    renderItemsTable();

                    Swal.fire({
                        icon: 'success',
                        title: 'Discount Applied!',
                        text: 'Discount of ' + discount_value + ' ' + (discount_type === 'percentage' ? '%' : 'QAR') + ' applied',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Disable payer section
                    $('#payer_type').prop('disabled', true);
                    $('#applyPayerBtn').prop('disabled', true);
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to apply discount',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }

    function removeRegularDiscount() {
        showLoading();

        $.ajax({
            url: '/dashboard/invoices/discounts/remove-regular',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            success: function(response) {
                hideLoading();

                if (response.success) {
                    invoiceState = response.draft;
                    renderItemsTable();

                    $('#discount_type').val('');
                    $('#discount_value').val('');

                    // Enable payer section
                    $('#payer_type').prop('disabled', false);
                    $('#applyPayerBtn').prop('disabled', false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Discount Removed!',
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

    /**
     * ====================================================
     * PAYER DISCOUNT (Insurance/Cardholder)
     * ====================================================
     */

    function handlePayerTypeChange() {
        var type = $('#payer_type').val();
        var companySelect = $('#payer_company');
        var approvalGroup = $('#approvalAmountGroup');

        if (!type) {
            companySelect.prop('disabled', true).html('<option value="">Select company</option>');
            approvalGroup.hide();
            return;
        }

        // Show/hide approval amount
        if (type === 'insurance') {
            approvalGroup.show();
        } else {
            approvalGroup.hide();
            $('#approval_amount').val('');
        }

        // Load companies
        showLoading();

        $.ajax({
            url: '/dashboard/invoices/discounts/get-companies/' + type,
            method: 'GET',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    var options = '<option value="">Select company</option>';
                    $.each(response.companies, function(index, company) {
                        options += '<option value="' + company.id + '" data-categories=\'' +
                            JSON.stringify(company.categories) + '\'>' + company.name + '</option>';
                    });

                    companySelect.html(options).prop('disabled', false);
                }
            },
            error: function() {
                hideLoading();
            }
        });
    }

    function applyPayerDiscount() {
        var payer_type = $('#payer_type').val();
        var payer_id = $('#payer_company').val();
        var approval_amount = parseFloat($('#approval_amount').val()) || 0;

        // Validate
        if (!payer_type) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Payer Type',
                text: 'Please select insurance or cardholder',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (!payer_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Company',
                text: 'Please select a company',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        if (invoiceState.items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items',
                text: 'Please add items before applying discount',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        showLoading();

        $.ajax({
            url: '/dashboard/invoices/discounts/apply-payer',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            data: JSON.stringify({
                payer_type: payer_type,
                payer_id: parseInt(payer_id),
                approval_amount: approval_amount
            }),
            contentType: 'application/json',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    invoiceState = response.draft;
                    renderItemsTable();

                    Swal.fire({
                        icon: 'success',
                        title: 'Discount Applied!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Disable regular discount
                    $('#discount_type').prop('disabled', true);
                    $('#discount_value').prop('disabled', true);
                    $('#applyDiscountBtn').prop('disabled', true);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            },
            error: function() {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to apply payer discount',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }

    function removePayerDiscount() {
        showLoading();

        $.ajax({
            url: '/dashboard/invoices/discounts/remove-payer',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            success: function(response) {
                hideLoading();

                if (response.success) {
                    invoiceState = response.draft;
                    renderItemsTable();

                    $('#payer_type').val('');
                    $('#payer_company').val('').prop('disabled', true);
                    $('#approval_amount').val('');
                    $('#approvalAmountGroup').hide();

                    // Enable regular discount
                    $('#discount_type').prop('disabled', false);
                    $('#discount_value').prop('disabled', false);
                    $('#applyDiscountBtn').prop('disabled', false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Payer Discount Removed!',
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

    /**
     * ====================================================
     * INITIALIZE DISCOUNT EVENTS
     * ====================================================
     */

    $(document).ready(function() {
        // Regular discount
        $('#applyDiscountBtn').on('click', applyRegularDiscount);
        $('#removeDiscountBtn').on('click', removeRegularDiscount);

        // Payer discount
        $('#payer_type').on('change', handlePayerTypeChange);
        $('#applyPayerBtn').on('click', applyPayerDiscount);
        $('#removePayerBtn').on('click', removePayerDiscount);
    });
</script>
