<script>

    // Show Payment Details
    let payment_selects;
    let payment_boxes;
    let delete_paymentBtns;

    function looponPaymntWaysSelectBoxes() {
        payment_boxes = Array.from(document.querySelectorAll('.one-pay'));
        payment_selects = Array.from(document.querySelectorAll('.payment_type'));
        delete_paymentBtns = Array.from(document.querySelectorAll('.delete-payment'));

        payment_selects.forEach(payment => {
            payment.addEventListener('change', function (e) {
                if (payment.value != 'Cash') {
                    payment.parentElement.parentElement.nextElementSibling.querySelector('table').style.display = 'inline-table';
                } else {
                    payment.parentElement.parentElement.nextElementSibling.querySelector('table').style.display = 'none';
                }
            });
        });
    };

    // Loop On payment ways selct boxes
    looponPaymntWaysSelectBoxes();

    $(document).ready(function () {

        // Update Total Quantity
        let totalQTY = updateQuantity();

        // Update Total Price
        let totalPRICE = updateTotalPrice();

        // Update Total NET Price
        let totalNET = updateTotalNet();

        // Update Total Tax Price
        let totalTAX = updateTotalTax();

        // Update Total Total
        let T = updateTotalTotal();

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
            data: {totals},
            success: function (response) {
            }
        });


    });

    $('#reset').one('click', function () {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "GET",
            url: '{{route('dashboard.delete-session-invoices')}}',
            success: function (response) {
                location.reload();
            }
        });
    });



    let invoiceForm = document.querySelector('.saveInvoice');
    let invoiceAction = invoiceForm.getAttribute('action');


    let customer_id = document.getElementById('customer_id').value;
    //let doctor_id = window.localStorage.getItem("doctorId");
    let session_doctor_id = "{{ session('doctor_id') }}";
    let doctor_id =
        (typeof session_doctor_id === 'string' && session_doctor_id.trim() !== '')
            ? session_doctor_id
            : (window.localStorage.getItem('doctorId') && window.localStorage.getItem('doctorId').trim() !== '')
                ? window.localStorage.getItem('doctorId')
                : $('#doctor_id').val();


    let user_id = document.getElementById('user_id').value;
    var datas = [];


   /* let discount_value = document.getElementById('discount_value');
    let exexuted = false;
    $('#discount_value').keypress(function (event) {
        let discount_type = document.getElementById('discount_type').value;
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            discount_value
            event.preventDefault();
            let TOT = $('#Totals').html();
            discountValue = (+document.querySelector('#discount_value').value);
            if (!exexuted) {
                exexuted = true;
                if (discount_type == 'fixed')
                    $('#Totals').html(TOT - event.target.value);
                else if (discount_type == 'percentage')
                    $('#Totals').html(TOT - ((TOT * event.target.value) / 100));
            }
        }
    });*/

    // Add Payment Button
    /*let addPaymentBtn = document.querySelector('.add-payment-link');
    addPaymentBtn.addEventListener('click', function (e) {
        e.preventDefault();
        deletePayment();
        let paymentsContainer = document.querySelector('.payments-box');
        let onePay = document.createElement('div');
        onePay.innerHTML = `
                <div class="one-pay">
                    <div class="row">
                        <div class="row"><span class="btn btn-danger delete-payment pull-right" style="margin-right: 30px; padding: 4px 10px"><i class="fa fa-trash-o"></i></span></div>
                        <div class="col-md-6">
                            <label for="">Choose Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-control payment_type">
                                <option value="Cash">Cash</option>
                                <option value="Atm">Atm</option>
                                <option value="visa">VISA</option>
                                <option value="Master Card">Master Card</option>
                                <option value="Gift Voudire">Gift Voudire</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paied">Payed Amount</label>
                                <input type="text" class="form-control" name="paied" value="" id="paied">
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-md-12">

                            <table style="display: none; width: 100%" class="table payments-details">
                                <thead>
                                    <tr style="background: #232323; color: #fff">
                                        <th>Bank</th>
                                        <th>Card No</th>
                                        <th>Expiration Date</th>
                                        <th>Curr</th>
                                        <th>Exchange Rate</th>
                                        <th>Local Payment</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="Bank"
                                                value="" id="Bank">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="Card_No"
                                                value="" id="Card_No">
                                        </td>
                                        <td>
                                            <input class="form-control" style="font-family: sans-serif" type="date"
                                                name="expiration_date"
                                                id="expiration_date"
                                                value="">
                                        </td>
                                        <td>
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="QAR">QAR</option>
                                            </select>
                                        </td>

                                        <td>1</td>
                                        <td><input type="text" class="form-control" name="local_payment" id="local_payment" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br/>
                    <br/>
                </div>
            `;

        paymentsContainer.appendChild(onePay);
        looponPaymntWaysSelectBoxes();
        deletePayment();
    });*/

    document.addEventListener("DOMContentLoaded", function () {

        const addPaymentBtn = document.querySelector('.add-payment-btn');
        const paymentsContainer = document.querySelector('.payment-section-body');

        if (!addPaymentBtn || !paymentsContainer) return;

        addPaymentBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const onePay = document.createElement('div');
            onePay.innerHTML = `
            <div class="one-pay" style="margin-top:20px; border-top:1px dashed #ccc; padding-top:15px;">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <span class="btn btn-danger delete-payment" style="margin-bottom:10px; padding:4px 10px;">
                            <i class="fa fa-trash"></i> Remove
                        </span>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="payment_type[]" class="form-control payment_type">
                                <option value="Cash">💵 Cash</option>
                                <option value="Atm">🏧 ATM</option>
                                <option value="visa">💳 VISA</option>
                                <option value="Master Card">💳 MasterCard</option>
                                <option value="Gift Voudire">🎁 Gift Voucher</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Paid Amount</label>
                            <input type="text" class="form-control" name="paied[]" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table style="display:none; width:100%; margin-top:15px;" class="table table-bordered payments-details">
                            <thead>
                                <tr>
                                    <th>Bank</th>
                                    <th>Card Number</th>
                                    <th>Expiration Date</th>
                                    <th>Currency</th>
                                    <th>Exchange Rate</th>
                                    <th>Local Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="Bank[]"></td>
                                    <td><input type="text" class="form-control" name="Card_No[]"></td>
                                    <td><input type="date" class="form-control" name="expiration_date[]"></td>
                                    <td>
                                        <select name="currency[]" class="form-control">
                                            <option value="QAR">QAR</option>
                                        </select>
                                    </td>
                                    <td>1</td>
                                    <td><input type="text" class="form-control" name="local_payment[]" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

            paymentsContainer.appendChild(onePay);
        });

        // delete payment
        document.addEventListener('click', function (e) {
            if (e.target.closest('.delete-payment')) {
                e.target.closest('.one-pay').remove();
            }
        });

        document.addEventListener('change', function (e) {

            if (e.target.classList.contains('payment_type')) {

                const paymentType = e.target.value;
                const onePayBox = e.target.closest('.one-pay');
                const table = onePayBox.querySelector('.payments-details');

                if (!table) return;

                if (paymentType.toLowerCase() === 'cash') {
                    table.style.display = 'none';
                } else {
                    table.style.display = 'table';
                }
            }

        });


    });


    // Format Payments Mthods Array
    let totalPaied;


    // Delete Invoice Item From items table
    $(document).on('click', '.delete-invoice-item', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let $row = $(this).closest('tr');

        let productId = $(this).data('id');
        console.log(productId);

        deleteProductFromSession(productId);

        $row.remove();

        updateQuantity();
        updateTotalPrice();
        updateTotalNet();
        updateTotalTax();
        updateTotalTotal();

        looponPaymntWaysSelectBoxes();
        deletePayment();
    });


    // Delete Payment Way
    function deletePayment() {
        delete_paymentBtns.forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                if (e.target.closest('.one-pay')) {
                    e.target.closest('.one-pay').style.border = 'none';
                    e.target.closest('.one-pay').style.margin = '0';
                    e.target.closest('.one-pay').style.marginBottom = '0';
                    e.target.closest('.one-pay').style.padding = '0';
                    e.target.closest('.one-pay').innerHTML = '';
                }
            });
        });
    }

    deletePayment();


    $(document).ready(function () {
        $('.product_invoice_table table tbody tr').each(function () {
            let $row = $(this);
            $row.attr('data-original-net', $row.find('.NET').text());
            $row.attr('data-original-total', $row.find('.TOTALS').text());
            $row.attr('data-updated-net', 0);
            $row.attr('data-updated-total', 0);
            $row.attr('data-payer-discount', 0);
        });
    });

    // styleNoty


</script>

@include('dashboard.pages.customers.invoice.layout.scripts.filter_items_script')
@include('dashboard.pages.customers.invoice.layout.scripts.save_invoice_script')
@include('dashboard.pages.customers.invoice.layout.scripts.items_method_script')
{{--@include('dashboard.pages.customers.invoice.layout.scripts.insurance_script')
@include('dashboard.pages.customers.invoice.layout.scripts.cardholder_script')--}}
@include('dashboard.pages.customers.invoice.layout.scripts.discount_script')
@include('dashboard.pages.customers.invoice.layout.scripts.regular_discount_script')
