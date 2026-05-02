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

        .invoice-details th, td {
            text-align: center
        }

        .invoice-details thead tr {
            background: #232323;
            color: #fff
        }

        .invoice-details tbody tr {
            background: #4fa75b;
            color: #fff;
            font-weight: 600
        }

        .selected-row {
            background-color: #00a65a;
            color: #fff;
        }

        .invoice-canceled {
            background-color: #f8d7da !important;
            color: #721c24;
        }

        .invoice-canceled i {
            color: #721c24 !important;
        }
    </style>

    <section class="content-header">
        <h1>
            Dashboard
            <small>Return Invoices</small>
        </h1>
    </section>

    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Return Invoices</h3>

            <div class="row" style="margin-top: 6px; padding: 15px">
               {{-- <form action="{{route('dashboard.pending-invoices')}}" method="GET">
                    <div class="col-md-2">
                        <label for="Invoice Code">Invoice Code</label>
                        <input type="text" name="invoice_code" class="form-control" value="{{request()->invoice_code}}">
                    </div>

                    <div class="col-md-2">
                        <label for="Invoice Code">Customer Code</label>
                        <input type="text" name="customer_code" class="form-control"
                               value="{{request()->customer_code}}">
                    </div>
                </form>--}}

                <form action="{{ route('dashboard.return-invoices') }}" method="GET">
                    <div class="col-md-2">
                        <label for="invoice_code">Invoice Code</label>
                        <input type="text" name="invoice_code" class="form-control" value="{{ request()->invoice_code }}">
                    </div>

                    <div class="col-md-2">
                        <label for="customer_code">Customer Code</label>
                        <input type="text" name="customer_code" class="form-control" value="{{ request()->customer_code }}">
                    </div>

                    <div class="col-md-2">
                        <label for="product_id">Article ID</label>
                        <input type="text" name="product_id" class="form-control" value="{{ request()->product_id }}">
                    </div>


                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success" style="margin-top: 25px;">Search</button>
                    </div>
                </form>


            </div>

            <div class="alert alert-danger" style="display: none; margin: 10px; width: 63%">
                <p class=""></p>
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="box-body no-padding ">
              {{--  @if($invoices->count() > 0)--}}
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
                            <th>Details</th>
                        </tr>
                        </thead>
                        {{--<tbody>
                        @foreach($invoices as $index => $invoice)
                            <tr>
                                <th>{{$invoice->invoice_code}}</th>
                                <th>{{date($invoice->created_at)}}</th>
                                <th>{{$invoice->customer->english_name ?? ''}}</th>
                                <th style="display: none">{{ $invoice->customer_id }}</th>
                                <th>{{$invoice->user_name}}</th>
                                <th>{{$invoice->remaining}}</th>
                                <th>{{$invoice->pickup_date}}</th>
                                <th><i class="fa fa-file" style="color: #3c8dbc; cursor: pointer; font-size: 16px"></i>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>--}}

                        <tbody>
                        @if($invoices->count() > 0)
                            @foreach($invoices as $invoice)
                                <tr class="{{ strtolower($invoice->status) == 'canceled' ? 'invoice-canceled' : '' }}">

                                <th>{{ $invoice->invoice_code }}</th>
                                    <th>{{ date('Y-m-d', strtotime($invoice->created_at)) }}</th>
                                    <th>{{ $invoice->customer->english_name ?? '' }}</th>
                                    <th style="display: none">{{ $invoice->customer_id }}</th>
                                    <th>{{ $invoice->user_name }}</th>
                                    <th>{{ $invoice->remaining }}</th>
                                    <th>{{ $invoice->pickup_date }}</th>
                                    <th><i class="fa fa-file" style="color: #3c8dbc; cursor: pointer; font-size: 16px"></i></th>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" style="text-align: center;">No Records</td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
               {{-- @else
                    <h2>No Records</h2>
                @endif--}}
            </div>
        </div><!-- /.box-body -->

    </div>
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
{{--                            <th>Ready</th>--}}
{{--                            <th>Deliver</th>--}}
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- Box Body -->
        <div class="box box-warning  payments-box" id="payments">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="box-title">Payments</h3>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table payments-details">
                            <thead>
                            <tr style="background: #232323; color: #fff">
                                <th>Type</th>
                                <th>Bank</th>
                                <th>Card No</th>
                                <th>Expiration Date</th>
                                <th>Curr</th>
                                <th>Payed Amount</th>
                                 <th>Exchange Rate</th>
                                <th>Local Payment</th>
                                <th>Date</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <br/>
                </div><br/>
            </div><!-- Box Body -->
        </div>
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
                            <label for="total">Total</label>
                            <input type="text" name="total" class="form-control" value="" id="total" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label for="discount">Discount</label>
                            <input type="text" name="discount" class="form-control" value="0" id="discount" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label for="net">Net</label>
                            <input type="text" name="net" class="form-control" value="" id="net" readonly>
                        </div>
                    </div>
                    <br/>
                    <div class="col-md-12">
                        <div class="col-lg-4">
                            <label for="paied">Paied</label>
                            <input type="text" name="paied" class="form-control" value="" id="paied" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label for="remaining">Remaining</label>
                            <input type="text" name="remaining" class="form-control" value="" id="remaining" readonly>
                        </div>
                    </div>
                </div><br/>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg-6">
                            <label for="reason">Reason Of Return</label>
                            <select name="return_reason" class="form-control return_reason" id="return_reason">
                                <option value=""></option>
                                <option value="Defective">Defective</option>
                                <option value="Replacement">Replacement</option>
                                <option value="Wrong Entry">Wrong Entry</option>
                                <option value="Delaying in Delivery">Delaying in Delivery</option>
                                <option value="Customer Not Serious">Customer Not Serious</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="return_amount">Return Amount</label>
                            <input type="text" name="return_amount" class="form-control" value="" id="return_amount"
                                   readonly>
                        </div>
                        <div class="col-md-12">
                            <label for="Notes">Notes</label>
                            <textarea name="" class="form-control invoice_notes" id="" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                </div><br/><br/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="button" class="btn btn-primary pull-right" name="" id="returnInvoice"
                                   value="Return"
                                   style="font-weight: 600">

                            <a type="button" class="btn btn-primary pull-right"  id="returnInvoice"
                                   href="{{url('dashboard/home')}}"
                                    style="font-weight: 600;margin-right: 5px">Back</a>
                        </div>
                    </div>
                </div>
            </div><!-- Box Body -->
        </div>

    </div>

    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/jspdf.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">


        let invoiceCode = document.querySelector('input[name=invoice_code]');
        let customer_code = document.querySelector('input[name=customer_code]');

        // Filter By Invoice Code
        invoiceCode.addEventListener('keyup', function (e) {
            //updatePendingTable(e.target.value, 'invoice_code');
        });

        // Filter By Customer Code
        customer_code.addEventListener('keyup', function (e) {
            //updatePendingTable(e.target.value, 'customer_code');
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
        let paymentsDetailsTable = document.querySelector('.payments-details tbody');
        let InvoiceBadge = document.querySelector('.invoice-badge');

        let InvoiceDetailsBox = document.querySelector('.details-box');

        // Define Invoice Id
        let InvoiceID;

        let FinancialBox = document.querySelector('#FinancialBox');


        // Hide Invoice Detils Box
        InvoiceDetailsBox.style.display = 'none';

        InvoicesTable.addEventListener('click', function (e) {
            if (e.target.tagName == 'I') {
                InvoiceID = e.target.parentElement.parentElement.querySelector('th:nth-child(1)').innerText;
                // Set The Selected Row background
                e.target.parentElement.parentElement.classList.add('selected-row');
                // Remove All Other rows Background
                $(e.target.parentElement.parentElement).siblings().removeClass('selected-row');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "GET",
                    url: '{{ route("dashboard.get-invoice-details") }}',
                    data: {InvoiceID},
                    success: function (response) {
                        // Reset The Invoice Items Table
                        InvoiceDetailsTable.innerHTML = '';

                        // Reset The Invoice payments Table
                        paymentsDetailsTable.innerHTML = '';

                        // Show Invoice Details Box
                        InvoiceDetailsBox.style.display = 'block';


                        if (response.invoice.status.toLowerCase() === 'canceled') {
                            FinancialBox.style.display = 'none';
                        } else {
                            FinancialBox.style.display = 'block';
                        }


                        // Set The Green Bade
                        $('#total').val(response.invoice.total);
                        $('#paied').val(response.invoice.paied);
                        $('#remaining').val(response.invoice.remaining);
                        $('#net').val(response.invoice.total);
                        $('#return_amount').val(response.invoice.total);

                        InvoiceBadge.innerText = response.invoice.invoice_code;

                        let row = document.createElement('tr');

                        let row1 = document.createElement('tr');

                        let payments = response.payments;

                        payments.forEach(payment => {
                            let row1 = document.createElement('tr');

                            row1.innerHTML = `
                            <td>${payment.type}</td>
                            <td>${payment.bank || 'N/A'}</td>
                            <td>${payment.card_number || 'N/A'}</td>
                            <td>${payment.expiration_date || 'N/A'}</td>
                            <td>${payment.currency}</td>
                            <td>${payment.payed_amount}</td>
                            <td>${payment.exchange_rate}</td>
                            <td>${payment.local_payment || 'N/A'}</td>
                            <td>${payment.created_at}</td>
                        `;

                                                // إضافة الصف إلى الجدول
                             paymentsDetailsTable.innerHTML += row1.outerHTML;
                         });


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
                                `;
                            InvoiceDetailsTable.innerHTML += row.innerHTML;
                        });

                    }
                });

            }
        });

        // Post Deliver Pending Invoice
        let postButton = document.querySelector('#returnInvoice');
        postButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Get Invoice Tray Number And Notes
            let invoice_notes = document.querySelector('.invoice_notes').value;
            let return_reason = document.querySelector('.return_reason').value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: '{{ route("dashboard.post-return-invoice") }}',
                data: {InvoiceID, invoice_notes, return_reason},
                success: function (response) {
                    console.log(response);
                    location.reload();
                }
            });


            console.log(InvoiceID);
        });

    </script>
@stop
