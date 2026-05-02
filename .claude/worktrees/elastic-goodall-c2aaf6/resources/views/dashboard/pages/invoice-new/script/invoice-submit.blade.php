<script>
    /**
     * ====================================================
     * INVOICE PAYMENTS & SUBMIT - jQuery Version
     * ====================================================
     */

    /**
     * ====================================================
     * PAYMENTS MANAGEMENT
     * ====================================================
     */

    function addPaymentRow() {
        var paymentHTML = `
        <div class="payment-row" style="border: 2px solid #e0e6ed; border-radius: 10px; padding: 20px; margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-3">
                    <label>Payment Type</label>
                    <select class="form-control payment-type" required>
                        <option value="Cash">💵 Cash</option>
                        <option value="Atm">🏧 ATM</option>
                        <option value="visa">💳 VISA</option>
                        <option value="Master Card">💳 MasterCard</option>
                        <option value="Gift Voudire">🎁 Gift Voucher</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Amount</label>
                    <input type="number" class="form-control payment-amount" min="0" step="0.01" required>
                </div>
                <div class="col-md-5 card-details-group" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Bank</label>
                            <input type="text" class="form-control payment-bank">
                        </div>
                        <div class="col-md-6">
                            <label>Card Number</label>
                            <input type="text" class="form-control payment-card">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-block remove-payment-btn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

        $('#paymentsContainer').append(paymentHTML);
        setupPaymentEvents();
    }

    function setupPaymentEvents() {
        // Show/hide card details
        $('.payment-type').off('change').on('change', function() {
            var row = $(this).closest('.payment-row');
            var cardDetails = row.find('.card-details-group');
            var type = $(this).val();

            if (type === 'Cash' || type === 'Gift Voudire') {
                cardDetails.hide();
            } else {
                cardDetails.show();
            }
        });

        // Remove payment
        $('.remove-payment-btn').off('click').on('click', function() {
            $(this).closest('.payment-row').remove();
        });
    }

    function renderPayments() {
        var container = $('#paymentsContainer');
        container.empty();

        if (invoiceState.payments.length === 0) {
            addPaymentRow();
        } else {
            $.each(invoiceState.payments, function() {
                addPaymentRow();
            });
        }
    }

    function collectPayments() {
        var payments = [];

        $('.payment-row').each(function() {
            var type = $(this).find('.payment-type').val();
            var amount = parseFloat($(this).find('.payment-amount').val()) || 0;
            var bank = $(this).find('.payment-bank').val();
            var card_number = $(this).find('.payment-card').val();

            if (amount > 0) {
                payments.push({
                    type: type,
                    amount: amount,
                    bank: bank || null,
                    card_number: card_number || null
                });
            }
        });

        return payments;
    }

    /**
     * ====================================================
     * SAVE DRAFT
     * ====================================================
     */

    function saveDraft() {
        if (invoiceState.items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items',
                text: 'Please add items before saving',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Save as Draft?',
            text: 'This will save the invoice as a draft without payments',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save Draft',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3498db'
        }).then(function(result) {
            if (result.isConfirmed) {
                showLoading();

                var pickup_date = $('#pickup_date').val();
                var doctor_id = $('#doctor_id').val();

                $.ajax({
                    url: '/dashboard/invoices/save-draft',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    data: JSON.stringify({
                        pickup_date: pickup_date,
                        doctor_id: doctor_id
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        hideLoading();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Draft Saved!',
                                text: 'Invoice ' + response.invoice_code + ' saved as draft',
                                confirmButtonColor: '#27ae60'
                            }).then(function() {
                                window.location.href = '/dashboard/invoices/all';
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
                    error: function() {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save draft',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });
    }

    /**
     * ====================================================
     * SUBMIT INVOICE
     * ====================================================
     */

    function submitInvoice() {
        // Validate items
        if (invoiceState.items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items',
                text: 'Please add items before submitting',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        // Validate doctor
        var doctor_id = $('#doctor_id').val();
        if (!doctor_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Doctor Required',
                text: 'Please select a doctor',
                confirmButtonColor: '#f39c12'
            });
            $('#doctor_id').focus();
            return;
        }

        // Validate pickup date
        var pickup_date = $('#pickup_date').val();
        if (!pickup_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Pickup Date Required',
                text: 'Please select a pickup date',
                confirmButtonColor: '#f39c12'
            });
            $('#pickup_date').focus();
            return;
        }

        // Collect payments
        var payments = collectPayments();

        if (payments.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Payment Required',
                text: 'Please add at least one payment method',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        // Calculate totals
        var totalPaid = 0;
        $.each(payments, function(index, payment) {
            totalPaid += payment.amount;
        });

        var total = invoiceState.totals.grand_total;
        var minPayment = total / 2;

        // Validate minimum payment
        if (totalPaid < minPayment) {
            Swal.fire({
                icon: 'warning',
                title: 'Insufficient Payment',
                text: 'Minimum payment is ' + minPayment.toFixed(2) + ' QAR (50% of total)',
                confirmButtonColor: '#f39c12'
            });
            return;
        }

        var remaining = total - totalPaid;

        // Confirmation
        Swal.fire({
            icon: 'question',
            title: 'Submit Invoice?',
            html: `
            <div style="text-align: left; padding: 20px;">
                <p><strong>Total:</strong> ${total.toFixed(2)} QAR</p>
                <p><strong>Paid:</strong> ${totalPaid.toFixed(2)} QAR</p>
                <p><strong>Remaining:</strong> ${remaining.toFixed(2)} QAR</p>
                <hr>
                <p style="color: #e74c3c;"><strong>⚠️ This will reduce stock and cannot be undone</strong></p>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit Invoice',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#95a5a6'
        }).then(function(result) {
            if (result.isConfirmed) {
                submitToBackend(doctor_id, pickup_date, payments);
            }
        });
    }

    function submitToBackend(doctor_id, pickup_date, payments) {
        showLoading();

        $.ajax({
            url: '/dashboard/invoices/save',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            data: JSON.stringify({
                doctor_id: doctor_id,
                pickup_date: pickup_date,
                payments: payments
            }),
            contentType: 'application/json',
            success: function(response) {
                hideLoading();

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Invoice Created!',
                        text: 'Invoice ' + response.invoice_code + ' created successfully',
                        confirmButtonColor: '#27ae60'
                    }).then(function() {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = '/dashboard/invoices/all';
                        }
                    });
                } else {
                    var errorHTML = '<p>' + response.message + '</p>';

                    if (response.errors && Array.isArray(response.errors)) {
                        errorHTML += '<ul style="text-align: left;">';
                        $.each(response.errors, function(index, error) {
                            errorHTML += '<li>' + error.description + ': Requested ' +
                                error.requested + ', Available ' + error.available + '</li>';
                        });
                        errorHTML += '</ul>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorHTML,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            },
            error: function() {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to submit invoice',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }

    /**
     * ====================================================
     * INITIALIZE PAYMENT & SUBMIT EVENTS
     * ====================================================
     */

    $(document).ready(function() {
        // Render initial payments
        renderPayments();

        // Add payment button
        $('#addPaymentBtn').on('click', addPaymentRow);

        // Save draft button
        $('#saveDraftBtn').on('click', saveDraft);

        // Submit invoice button
        $('#submitInvoiceBtn').on('click', submitInvoice);
    });
</script>
