@extends('dashboard.layouts.master')
@section('content')

<style>
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #ddd;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}

.form-group {
    margin-right: 15px
}

.btn-success {
    margin-top: 5px;
    background: #4fa75b;
    color: #fff;
    border: 1px solid #327e3d;
}

.pending-table th {
    text-align: center
}

.invoice-details th,
td {
    text-align: center
}

.invoice-details thead tr {
    background: #232323;
    color: #fff
}

.invoice-details tbody tr, .payments-table tbody tr {
    background: #4fa75b;
    color: #fff;
    font-weight: 600
}

.selected-row {
    background-color: #00a65a;
    color: #fff;
}
</style>

<section class="content-header">
    <h1>
        Dashboard
        <small>Pending Invoices</small>
    </h1>
</section>

<div class="box box-warning">
    <div class="box-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="box-title">Pending Invoices <small class="badge bg-green">{{ $invoices->count() }}</small>
                </h3>
            </div>
        </div>


        <div class="row" style="margin-top: 6px; padding: 15px">
            <form action="{{route('dashboard.pending-invoices')}}" method="GET">
                <div class="col-md-2">
                    <label for="Invoice Code">Invoice Code</label>
                    <input type="text" name="invoice_code" class="form-control" value="{{request()->invoice_code}}">
                </div>

                <div class="col-md-2">
                    <label for="Invoice Code">Customer Code</label>
                    <input type="text" name="customer_code" class="form-control" value="{{request()->customer_code}}">
                </div>

                <div class="col-md-2">
                    <label for="Customer Name">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" value="{{request()->search}}">
                </div>

                <div class="col-md-3">
                    <label for="creation date">Creation Date</label>
                    <input class="form-control" style="font-family: sans-serif" type="date" name="creation_date"
                        value="{{request()->creation_date}}">
                </div>

                <div class="col-md-2">
                    <br>
                    <button class="remaining_balance btn btn-primary" style="margin-top: 5px">Remaining Balance
                    </button>
                </div>

            </form>

        </div>

        <div class="alert alert-danger" style="display: none; margin: 10px; width: 63%">
            <p class=""></p>
        </div>

    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="box-body no-padding ">
            @if($invoices->count() > 0)
                <div class="scroll-invoices">
                    <table class="table table-bordered pending-table">
                        <thead>
                            <tr>
                                <th>Invoice.</th>
                                <th>Invoice Date</th>
                                <th>Customer</th>
                                <th style="display: none">Customer Code</th>
                                <th>Sales</th>
                                <th>Balance</th>
                                <th>Pick-up Date</th>
                                <th>Show</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $index => $invoice)
                            <tr>
                                <th>{{$invoice->invoice_code}}</th>
                                <th>{{date($invoice->created_at)}}</th>
                                <th>{{$invoice->customer->english_name ?? ''}}</th>
                                <th style="display: none">{{ $invoice->customer_id }}</th>
                                <th>{{$invoice->user_name}}</th>
                                <th>{{$invoice->remaining}}</th>
                                <th>{{$invoice->pickup_date}}</th>
                                <th>
                                    <i class="fa fa-file" style="color: #3c8dbc; cursor: pointer; font-size: 16px"></i>
                                </th>
                                <th>
                                    <div style="display: flex">
                                        <form action="{{route('dashboard.delete-invoice', $invoice->invoice_code)}}"
                                            method="GET">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button type="submit" class="delete" style="margin-left: 10px">
                                                <i class="fa fa-trash-o"
                                                    style="color: #ff0000; cursor: pointer; font-size: 16px"></i>
                                            </button>
                                        </form>
                                    </div>
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
            <table class="table table-bordered pending-table">
                <tbody>
                    <h2>No Records</h2>
                </tbody>
            </table>

            @endif
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<br><br>
<!-- INVOICE DETAILS BOX -->
<div class="box box-warning  details-box" id="detailsBox">
    <div class="box-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="box-title">Invoice Details <small class="badge invoice-badge bg-green"> Invoice
                        Code: </small></h3>
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table invoice-details">
                    <thead>
                        <tr style="background: #232323; color: #fff">
                            <th>NO</th>
                            <th>Item #</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            {{-- <th>Discount Type</th>
                            <th>Discount</th> --}}
                            <th>Net</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Stock</th>
                            <th>Ready</th>
                            <th>Deliver</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

                <br><br>
                <h3>Payments</h3>
                <table class="table table-bordered payments-table">
                    <thead>
                        <tr style="background: #232323; color: #fff">
                            <th>#</th>
                            <th>Type</th>
                            <th>Bank</th>
                            <th>Card No</th>
                            <th>Expiration Date</th>
                            <th>Paid</th>
                            <th>Benificiary</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                <div class="row">
                    <span class="btn btn-success pull-right add-payment" style="font-weight: 700; border: 2px solid #f39c12">Add Payment <i class="fa fa-plus"></i></span>
                </div>
                <br>
                <div class="payments">

                </div>
                <br><br>

            </div>
        </div>
        <br><br>
        <div class="box box-warning  Financial-box" id="FinancialBox">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">Financial Information</h3>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-4">
                            <label for="paied">Paied</label>
                            <input type="text" name="paied" class="form-control" value="" id="paied" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label for="remaining">Remaining</label>
                            <input type="text" name="remaining" class="form-control" value="" id="remaining" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label for="remaining">Discount</label>
                            <input type="text" name="discount" class="form-control" value="" id="discount" readonly>
                        </div>
                    </div> <br /><br /> <br /><br />
                    <div class="col-md-12">
                        <div class="col-lg-6">
                            <label for="remaining">Total Before Discount</label>
                            <input type="text" name="totalBefore" class="form-control" value="" id="totalBefore"
                                readonly>
                        </div>
                        <div class="col-lg-6">
                            <label for="total">Total After Discount</label>
                            <input type="text" name="totalAfter" class="form-control" value="" id="totalAfter" readonly>
                        </div>
                    </div>
                </div><br />

            </div><!-- Box Body -->
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Notes">Notes</label>
                    <textarea name="" class="form-control invoice_notes" id="" cols="30" rows="4"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="Tray Ref">Tray Ref #</label>
                    <textarea name="" class="form-control invoice_trayn" id="" cols="30" rows="4"></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="button" class="btn btn-primary pull-right" name="" id="postDeliver" value="Post"
                        style="font-weight: 600">
                    <div class="dropdown btn-primary pull-right" style="margin-right: 5px">
                        <button class="btn btn-primary"><span style="font-weight: bold;">Print</span></button>
                        <div class="dropdown-content">
                            <a href="" id="printInvoiceAr" target="_blank"
                                style="font-weight: 600; margin-right:20px">Arabic</a>
                            <a href="" id="printInvoiceEn" target="_blank"
                                style="font-weight: 600; margin-right:20px">English</a></option>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Box Body -->
</div>

<script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/jspdf.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
let invoiceCode = document.querySelector('input[name=invoice_code]');
let customer_code = document.querySelector('input[name=customer_code]');
let customer_name = document.querySelector('input[name=customer_name]');
let creation_date = document.querySelector('input[name=creation_date]');
let remaining_balance = document.querySelector('.remaining_balance');

// Filter By Invoice Code
invoiceCode.addEventListener('keyup', function(e) {
    updatePendingTable(e.target.value, 'invoice_code');
});

// Filter By Customer Code
customer_code.addEventListener('keyup', function(e) {
    updatePendingTable(e.target.value, 'customer_code');
});

// Filter By Remaining Balance
remaining_balance.addEventListener('click', function(e) {
    e.preventDefault();
    e.target.classList.add('btn-success');
    updatePendingTable(e.target, 'remaining_balance');
});

// Filter By Customer Name
customer_name.addEventListener('keyup', function(e) {
    updatePendingTable(e.target.value, 'customer_name');
});

// Filter By Customer Name
creation_date.addEventListener('change', function(e) {
    updatePendingTable(e.target.value, 'creation_date');
});


let pendingRows = Array.from(document.querySelectorAll('.pending-table tbody tr'));

function updatePendingTable(searchValue, subject) {

    matchedRows = pendingRows.filter(row => {
        if (subject == 'invoice_code') {
            return row.querySelector('th:nth-child(1)').innerText.match(searchValue);
        } else if (subject == 'customer_code') {
            return row.querySelector('th:nth-child(4)').innerText.match(searchValue);
        } else if (subject == 'remaining_balance') {

            if (searchValue.classList.contains('btn-success')) {
                return row.querySelector('th:nth-child(6)').innerText != '0';
            } else {
                // searchValue.classList.remove('btn-success');
                return row;
            }

        } else if (subject == 'customer_name') {
            return row.querySelector('th:nth-child(3)').innerText.match(searchValue);
        } else if (subject == 'creation_date') {
            return row.querySelector('th:nth-child(2)').innerText.match(searchValue);
        }
    })

    pendingRows.forEach(r => {
        if (matchedRows.includes(r)) {
            r.style.display = 'table-row';
        } else {
            r.style.display = 'none';
        }
    });

}

// Get Invoice Details
let InvoicesTable = document.querySelector('.pending-table');
let InvoiceDetailsTable = document.querySelector('.invoice-details tbody');
let InvoiceBadge = document.querySelector('.invoice-badge');

let InvoiceDetailsBox = document.querySelector('.details-box');

// Get Invoice PAyments Details
let paymentsTable = document.querySelector('.payments-table tbody');

// Define Invoice Id
let InvoiceID;
let paymentsContainer = document.querySelector('.payments');

// Hide Invoice Detils Box
InvoiceDetailsBox.style.display = 'none';

InvoicesTable.addEventListener('click', function(e) {
    if (e.target.tagName == 'I') {
        InvoiceID = e.target.parentElement.parentElement.querySelector('th:nth-child(1)').innerText;

        // Set The Selected Row background
        e.target.parentElement.parentElement.classList.add('selected-row');
        // Remove All Other rows Background
        $(e.target.parentElement.parentElement).siblings().removeClass('selected-row');
        // Reset Payments Div HTNL
        paymentsContainer.innerHTML = '';

        // Undisable the add payment btn
        document.querySelector('.add-payment').removeAttribute('disabled');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "GET",
            url: '{{ route("dashboard.get-invoice-details") }}',
            data: {
                InvoiceID
            },
            success: function(response) {
                // Reset The Invoice Items Table
                InvoiceDetailsTable.innerHTML = '';

                // RESET Payments Table
                paymentsTable.innerHTML = '';
                // Show Invoice Details Box
                InvoiceDetailsBox.style.display = 'block';

                // Set The Green Bade
                InvoiceBadge.innerText = response.invoice.invoice_code;

                let invoiceCode = response.invoice.invoice_code;

                var printEn = document.getElementById('printInvoiceEn'); //or grab it by tagname etc

                printEn.href = '{{url('dashboard/print-pending-invoice/en').'/'}}' + invoiceCode;

                var printAr = document.getElementById('printInvoiceAr'); //or grab it by tagname etc

                printAr.href = '{{url('dashboard/print-pending-invoice/ar').'/'}}' + invoiceCode;

                let row = document.createElement('tr');

                let total_before_discount = 0.00;

                if (response.invoice.discount_type == "fixed")
                    total_before_discount = (+response.invoice.discount_value) + (+response.invoice.total);
                else
                    total_before_discount = ((+response.invoice.total) / (1 - (+response.invoice.discount_value) / 100));

                //Financial Information
                $('#totalAfter').val((+response.invoice.total));
                $('#paied').val((+response.invoice.paied));
                $('#remaining').val((+response.invoice.remaining));
                $('#discount').val((+response.invoice.discount_value));
                $('#totalBefore').val((+total_before_discount));

                response.Invoice_items.forEach((r, index) => {
                    row.innerHTML = `

                                    <td>${index + 1}</td>
                                    <td>${r.product_id}</td>
                                    <td>${r.name}</td>
                                    <td>${r.quantity}</td>
                                    <td>${r.price}</td>
                                    <td>${r.net}</td>
                                    <td>${r.tax}</td>
                                    <td>${r.total}</td>
                                    <td>${r.stock}</td>
                                    <td><input type="button" class="form-control btn btn-warning itemReady" name="ready" onclick="itemReady(${r.id})" value="R" style="cursor: pointer; width: 35px; height: 30px; margin:auto" /></td>
                                    <td><input type="button" class="form-control btn btn-info itemDeliver" name="deliver" onclick="itemDeliver(${r.id})" value="D" style="cursor: pointer; width: 35px; height: 30px; margin:auto" /></td>
                                    <td class="ProductItemId" style="display:none;">${r.id}</td>
                                `;
                    InvoiceDetailsTable.innerHTML += row.innerHTML;

                });
                response.payments.forEach( (pay, index)=> {
                    pay.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${pay.type}</td>
                        <td>${pay.bank??'-'}</td>
                        <td>${pay.card_number ?? '-'}</td>
                        <td>${ (pay.expiration_date != '0000-00-00' && pay.expiration_date != null) ? pay.expiration_date : '-' }</td>
                        <td>${pay.payed_amount}</td>
                        <td>${ pay.get_benficiary ? pay.get_benficiary.first_name + ' ' + pay.get_benficiary.last_name : '-' }</td>
                        <td>${pay.created_at}</td>
                    `;
                    paymentsTable.innerHTML += pay.innerHTML;
                    console.log(pay);
                });

            }
        });

    }
});

// Post Deliver Pending Invoice
let postButton = document.querySelector('#postDeliver');
postButton.addEventListener('click', function(e) {
    e.preventDefault();

    // Get Invoice Tray Number And Notes
    let invoice_notes = document.querySelector('.invoice_notes').value;
    let invoice_trayn = document.querySelector('.invoice_trayn').value;
    var product_status = '';
    $('input:checkbox[name=status]').each(function() {
        if ($(this).is(':checked'))
            product_status = $(this).val();
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type: "POST",
        url: '{{ route("dashboard.post-deliver-invoice") }}',
        data: {
            product_status,
            InvoiceID,
            invoice_notes,
            invoice_trayn
        },
        success: function(response) {
            console.log(response);
            location.reload();
        }
    });


    console.log(InvoiceID);
});

//function item ready change status
function itemReady(readyId) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type: "POST",
        url: '{{ url('dashboard/change-status-ready').'/' }}' + readyId,
        data: {
            id: readyId,
            status: 'ready'
        },
        success: function(response) {
            var n = new Noty({
                text: `Item has been updated successfully to Ready`,
                killer: true,
                type: 'warning',
            }).show();
            let aquiringMessage = document.querySelector('.noty_layout');
            aquiringMessage.classList.add('alert', 'alert-success');
            aquiringMessage.style.padding = '10px';
        }
    });
}

//function item ready change status
function itemDeliver(deliverId) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type: "POST",
        url: '{{ url('dashboard/change-status-deliver').'/' }}' + deliverId,
        data: {
            id: deliverId,
            status: 'deliver'
        },
        success: function(response) {
            var n = new Noty({
                text: `Item has been updated successfully to Deliver`,
                killer: true,
                type: 'warning',
            }).show();
            let aquiringMessage = document.querySelector('.noty_layout');
            aquiringMessage.classList.add('alert', 'alert-success');
            aquiringMessage.style.padding = '10px';
        }
    });
}

// Add Payment
let addPaymentBtn = document.querySelector('.add-payment');
let updatePaymentBtn;
addPaymentBtn.addEventListener('click', function(e) {
    e.preventDefault();
    let paymentsContainer = document.querySelector('.payments');
    let onePay = document.createElement('div');
    onePay.innerHTML =  `
    <form>
        <div class="one-pay">
            <div class="row">
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
                        <input type="text" class="form-control" name="paied" value="" id="paied" required>
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

        <div class="row">
            <button style="margin-right: 25px; margin-top: 10px" class="btn btn-success pull-right update_payment">Update Invoice</button>
        </div>
        </form>
    `;
    paymentsContainer.appendChild(onePay);
    addPaymentBtn.setAttribute('disabled', true);
    // On Change Payment Type
    document.querySelector('#payment_type').addEventListener('change', function(e) {
        if(e.target.value != 'Cash') {
            e.target.parentElement.parentElement.nextElementSibling.querySelector('table').style.display = 'inline-table';
        } else {
            e.target.parentElement.parentElement.nextElementSibling.querySelector('table').style.display = 'none';
        }
    });

    updatePaymentBtn = document.querySelector('.update_payment');
    updatePaymentBtn.addEventListener('click', function(e) {
        e.preventDefault();

        let payment = {
            type: document.querySelector('.payment_type').value,
            bank: document.querySelector('#Bank').value,
            card_number: document.querySelector('#Card_No').value,
            expiration_date: document.querySelector('#expiration_date').value,
            payed_amount: document.querySelector('#paied').value,
        };
        console.log(payment);
        $.ajax({
            type: 'POST',
            url: "{{ route('dashboard.add-payment-to-pending-invoice') }}",
            data: {
                InvoiceID,
                payment,
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                let paymentsTable = document.querySelector('.payments-table tbody');
                let newPaymentRow = `
                    <tr>
                        <td></td>
                        <td>${response.type}</td>
                        <td>${response.bank ?? '-'}</td>
                        <td>${response.card_number ?? '-'}</td>
                        <td>${response.expiration_date != '0000-00-00' && response.expiration_date != null ? response.expiration_date : '-' }</td>
                        <td>${response.payed_amount ?? '-'}</td>
                        <td>${response.get_benficiary ? response.get_benficiary.first_name + ' ' + response.get_benficiary.last_name : '-'}</td>
                        <td>${response.created_at}</td>
                    </tr>
                `;

                paymentsTable.innerHTML += newPaymentRow;
                alert('Ivoice Payment Added Successfully');
            },

            error: function(error) {

            }
        });
    });
});

</script>
@stop
