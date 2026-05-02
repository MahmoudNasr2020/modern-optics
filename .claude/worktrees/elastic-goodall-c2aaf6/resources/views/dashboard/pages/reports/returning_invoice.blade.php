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
                <form action="{{route('dashboard.return-invoices')}}" method="GET">
                    <div class="col-md-2">
                        <label for="Invoice Code">Invoice Code</label>
                        <input type="text" name="invoice_code" class="form-control" value="{{request()->invoice_code}}">
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
                    <table class="table table-bordered return-table">
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
                        <tbody>
                        @foreach($invoices as $index => $invoice)
                            <tr>
                                <th>{{$invoice->invoice_code}}</th>
                                <th>{{date($invoice->created_at)}}</th>
                                <th>{{$invoice->customer_name}}</th>
                                <th style="display: none">{{ $invoice->customer_id }}</th>
                                <th>{{$invoice->user_name}}</th>
                                <th>{{$invoice->remaining}}</th>
                                <th>{{$invoice->pickup_date}}</th>
                                <th><a href="{{url('dashboard/print-returning-invoice/en').'/'.$invoice->invoice_code}}"><i class="fa fa-file" style="color: #3c8dbc; cursor: pointer; font-size: 16px"></i></a>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h2>No Records</h2>
                @endif
            </div>
        </div><!-- /.box-body -->

    </div>
   

    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/jspdf.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">


        let invoiceCode = document.querySelector('input[name=invoice_code]');
        // Filter By Invoice Code
        invoiceCode.addEventListener('keyup', function (e) {
            console.log(e.target.value);
            updatePendingTable(e.target.value, 'invoice_code');
        });

        let returnRows = Array.from(document.querySelectorAll('.return-table tbody tr'));

        function updatePendingTable(searchValue, subject) {

            matchedRows = returnRows.filter(row => {
                if (subject == 'invoice_code') {
                    return row.querySelector('th:nth-child(1)').innerText.match(searchValue);
                }
            })

            returnRows.forEach(r => {
                if (matchedRows.includes(r)) {
                    r.style.display = 'table-row';
                } else {
                    r.style.display = 'none';
                }
            });

        }

        // Get Invoice Details
        let InvoicesTable = document.querySelector('.return-table');
        let InvoiceDetailsTable = document.querySelector('.invoice-details tbody');
     
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

                        // Set The Green Bade
                        $('#total').val(response.invoice.total);
                        $('#paied').val(response.invoice.paied);
                        $('#remaining').val(response.invoice.remaining);
                        $('#net').val(response.invoice.total);
                        $('#return_amount').val(response.invoice.total);

                        InvoiceBadge.innerText = response.invoice.invoice_code;

                        let row = document.createElement('tr');

                        let row1 = document.createElement('tr');

                        paymentsDetailsTable.innerHTML += row1.innerHTML;
                    }
                });

            }
        });

    
    </script>
@stop
