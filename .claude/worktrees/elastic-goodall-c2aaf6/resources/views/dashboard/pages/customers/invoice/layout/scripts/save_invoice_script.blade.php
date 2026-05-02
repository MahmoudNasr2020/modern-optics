<script>
    //POST Invoice
    invoiceForm.addEventListener('submit', function (e) {
        e.preventDefault();
        document.querySelector('.save-and-exit').setAttribute('disabled', true);
        setTimeout(function () {
            document.querySelector('.save-and-exit').removeAttribute('disabled');
        }, 3000);
        saveInvoice();
    });
    let discountValue;

    // POST Invoice
    function saveInvoice() {

        let pickup_date = document.getElementById('pickup_date').value;
        let paied = (+document.getElementById('paied').value);

       // let discount_type = document.getElementById('discount_type').value;
       // let discount_value = (+document.getElementById('discount_value').value);

        let discount_type = document.getElementById('regular_discount_type').value;
        let discount_value = (+document.getElementById('regular_discount_value').value);

        let type = document.getElementById('payment_type').value;
        let bank = document.getElementById('Bank').value;
        let card_number = document.getElementById('Card_No').value;
        let expiration_date = document.getElementById('expiration_date').value;
        let currency = document.getElementById('currency').value;
        let exchange_rate = 1;
        let local_payment = document.getElementById('local_payment').value;

        let allInvoiceRows = Array.from(document.querySelectorAll('.product_invoice_table table tbody tr'));
        let InvoiceTotalRow = Array.from(document.querySelectorAll('.product_invoice_table table tfoot tr'));

        var doctorIdInput = document.getElementById('doctor_id');
        var doctorNameInput = document.getElementById('doctor_name');
        // if no found product
        if (allInvoiceRows.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please add products before proceeding.',
                confirmButtonText: 'OK'
            });
            return;
        }


        // Prepare Payment Methods
        let payment_boxes = document.querySelectorAll('.one-pay');
        let payment_methods = formatMethods(payment_boxes);

        let total = '';
        InvoiceTotalRow.forEach((row) => {
            total = (+row.querySelector('td:nth-child(6)').innerText);
        });
        let minPay = total / 2;
        if (totalPaied < minPay) {
            // alert('Can\'t Pay less than half '  + minPay + 'QR.');

            Swal.fire({
                icon: 'warning',
                title: 'Payment Problem',
                text: 'Can\'t Pay less than half ' + minPay + 'QR.',
                confirmButtonText: 'OK'
            });

        }
        else if(pickup_date == '')
        {
            Swal.fire({
                icon: 'warning',
                title: 'Pickup Date Problem',
                text: 'Please enter the pickup date.',
                confirmButtonText: 'OK'
            });

        }
        else if((doctorIdInput && (!doctorIdInput.value || doctorIdInput.value.trim() === "")))
        {
            Swal.fire({
                icon: 'warning',
                title: 'Doctor Problem',
                text: 'Please select doctor.',
                confirmButtonText: 'OK'
            });

        }
        else {
            let doctor_id =
                (typeof session_doctor_id === 'string' && session_doctor_id.trim() !== '')
                    ? session_doctor_id
                    : (window.localStorage.getItem('doctorId') && window.localStorage.getItem('doctorId').trim() !== '')
                        ? window.localStorage.getItem('doctorId')
                        : $('#doctor_id').val();
            console.log(doctor_id);
            console.log('hi');
            let insurance_cardholder_type = $('#payer_type').val();
            let insurance_cardholder_type_id = $('#payer_type_id').val();
            let insurance_approval_amount = $('#payer_type_approval_amount').val();
            let insurance_cardholder_discount = $('#payer_type_discount').val();
            let total_before_discount = $('#TotalPrice').text();



            allInvoiceRows.forEach((row) => {
                if (row.innerText != '') {
                    let product_id = row.querySelector('td:nth-child(2)').innerText;
                    let quantity = (+row.querySelector('td:nth-child(4)').innerText);
                    let price = (+row.querySelector('td:nth-child(5)').innerText);
                    let net = (+row.querySelector('td:nth-child(6)').innerText);
                    let tax = (+row.querySelector('td:nth-child(7)').innerText);
                    let total = (+row.querySelector('td:nth-child(8)').innerText);
                    let type = row.querySelector('td:nth-child(12)').innerText;
                    let insurance_cardholder_discount = +row.getAttribute('data-payer-discount') || 0;
                    var dataAll = {
                        product_id: product_id,
                        quantity: quantity,
                        price: price,
                        net: net,
                        //total_before_discount:total_before_discount,
                        tax: tax,
                        total: total,
                        type: type,
                        insurance_cardholder_discount: insurance_cardholder_discount
                    }
                    datas.push(dataAll);
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: invoiceAction,
                data: {
                    customer_id,
                    doctor_id,
                    user_id,
                    pickup_date,
                    total,
                    total_before_discount,
                    paied: totalPaied,
                    datas,
                    type,
                    bank,
                    card_number,
                    expiration_date,
                    currency,
                    exchange_rate,
                    local_payment,
                    discount_type,
                    discount_value,
                    payment_methods,
                    insurance_cardholder_type,
                    insurance_cardholder_type_id,
                    insurance_approval_amount,
                    insurance_cardholder_discount
                },

                success: function (response) {
                    if (response.error) {
                        new Noty({
                            text: response.message,
                            killer: true,
                            type: 'warning',
                            timeout: 3000
                        }).show();
                        styleNotyLayout('warning');
                        return;
                    }


                    invoiceForm.setAttribute('disabled', 'true');
                    styleNotyLayout('success');

                     window.location.href = response.route;
                },
                error: function (error) {
                    if (error.responseJSON && error.responseJSON.messages) {
                        for (const key in error.responseJSON.messages) {
                            error.responseJSON.messages[key].forEach(msg => {
                                new Noty({
                                    text: msg,
                                    killer: true,
                                    type: 'warning'
                                }).show();
                            });
                        }
                    } else {
                        new Noty({
                            text: 'Unexpected error occurred',
                            killer: true,
                            type: 'error'
                        }).show();
                    }
                }

            });

        }

    }

    // SAVE INVOICE
    $('.saveOnly').on('click', function (e) {
        e.preventDefault();
        e.target.setAttribute('disabled', true);
        setTimeout(function () {
            e.target.removeAttribute('disabled');
        }, 3000);
        saveAndExitInvoice();
    });


    // SAVE Invoice
    function saveAndExitInvoice() {

        return 0;
        let pickup_date = document.getElementById('pickup_date').value;
        let paied = (+document.getElementById('paied').value);

        let discount_type = document.getElementById('discount_type').value;
        let discount_value = (+document.getElementById('discount_value').value);

        let type = document.getElementById('payment_type').value;
        let bank = document.getElementById('Bank').value;
        let card_number = document.getElementById('Card_No').value;
        let expiration_date = document.getElementById('expiration_date').value;
        let currency = document.getElementById('currency').value;
        let exchange_rate = 1;
        let local_payment = document.getElementById('local_payment').value;

        let allInvoiceRows = Array.from(document.querySelectorAll('.product_invoice_table table tbody tr'));
        let InvoiceTotalRow = Array.from(document.querySelectorAll('.product_invoice_table table tfoot tr'));

        //if no found product
        if (allInvoiceRows.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please add products before proceeding.',
                confirmButtonText: 'OK'
            });
            return;
        }

        let payment_boxes = document.querySelectorAll('.one-pay');
        let payment_methods = formatMethods(payment_boxes);

        let total = '';
        InvoiceTotalRow.forEach((row) => {
            total = (+row.querySelector('td:nth-child(6)').innerText);
        });

        let minPay = total / 2;
        if (paied < minPay) {
            Swal.fire({
                icon: 'warning',
                title: 'Payment Problem',
                text: 'Can\'t Pay less than half ' + minPay + 'QR.',
                confirmButtonText: 'OK'
            });
        } else {

           // let doctor_id =
            let doctor_id =
                (typeof session_doctor_id === 'string' && session_doctor_id.trim() !== '')
                    ? session_doctor_id
                    : (window.localStorage.getItem('doctorId') && window.localStorage.getItem('doctorId').trim() !== '')
                        ? window.localStorage.getItem('doctorId')
                        : $('#doctor_id').val();
            let insurance_id_input = $('#insurance_id_input').val();
            let insurance_approval_amount_input = $('#insurance_approval_amount_input').val();

            let cardholder_id_input = $('#cardholder_id_input').val();
            let cardholder_discounts_input = $('#cardholder_discounts_input').val();
            allInvoiceRows.forEach((row) => {
                let product_id = row.querySelector('td:nth-child(2)').innerText;
                let quantity = (+row.querySelector('td:nth-child(4)').innerText);
                let price = (+row.querySelector('td:nth-child(5)').innerText);
                let net = (+row.querySelector('td:nth-child(6)').innerText);
                let tax = (+row.querySelector('td:nth-child(7)').innerText);
                let total = (+row.querySelector('td:nth-child(8)').innerText);
                let type = row.querySelector('td:nth-child(11)').innerText;
                let total_before_discount = $('#TotalPrice').innerText;

                var dataAll = {
                    product_id: product_id,
                    quantity: quantity,
                    price: price,
                    net: net,
                    tax: tax,
                    total: total,
                    type: type,
                    total_before_discount:total_before_discount
                }
                datas.push(dataAll);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: "{{url('dashboard/save-only-invoice')}}",
                data: {
                    customer_id,
                    doctor_id,
                    user_id,
                    pickup_date,
                    total,
                    total_before_discount,
                    paied,
                    datas,
                    type,
                    bank,
                    card_number,
                    expiration_date,
                    currency,
                    exchange_rate,
                    local_payment,
                    discount_type,
                    discount_value,
                    payment_methods,
                    cardholder_id_input,
                    cardholder_discounts_input,
                    insurance_id_input,
                    insurance_approval_amount_input
                },
                success: function (response) {
                    invoiceForm.setAttribute('disabled', 'true');
                    var n = new Noty({
                        text: response,
                        killer: true,
                        type: 'warning',
                    }).show();
                    styleNotyLayout('warning');
                    window.location.href = "{{url('dashboard/all-customers')}}"
                },
                error: function (response) {
                    var n = new Noty({
                        text: [
                            response.responseJSON.errors['paied'] ? response.responseJSON.errors['paied'] : '',
                            response.responseJSON.errors['pickup_date'] ? response.responseJSON.errors['pickup_date'] : ''
                        ],
                        killer: true,
                        type: 'warning',
                    }).show();
                    styleNotyLayout('danger');
                }
            });
        }
    }
</script>
