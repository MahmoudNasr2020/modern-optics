@extends('dashboard.layouts.master')
@section('content')

    <style>
        .form-group {
            margin-right: 15px
        }
    </style>

    <section class="content-header">
        <h1>
            Dashboard
            <small>Delivered Invoices</small>
        </h1>
    </section>

    <div class="box box-warning">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="box-title">Delivered Invoices <small class="badge bg-green">{{ $invoices->count() }}</small></h3>
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
                        <label for="Invoice Code">Remaining Balance</label>
                        <input type="number" name="remaining_balance" class="form-control" value="{{request()->remaining_balance}}">
                    </div>

                    <div class="col-md-2">
                        <label for="Customer Name">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" value="{{request()->search}}">
                    </div>

                    <div class="col-md-2">
                        <label for="creation date">Search By Creation Date</label>
                        <input class="form-control" style="font-family: sans-serif" type="date" name="creation_date" value="{{request()->creation_date}}">
                    </div>


                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary  btn-flat" style="margin-top: 23px"><i class="fa fa-search"></i> Search For
                            Item
                        </button>
                    </div>
                </form>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="box-body no-padding ">
                @if($invoices->count() > 0)
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>Invoice.</th>
                            <th>Invoice Date</th>
                            <th>Customer</th>
                            <th>Sales</th>
                            <th>Balance</th>
                            <th>Pick-up Date</th>
                        </tr>
                        <tbody>
                        @foreach($invoices as $index => $invoice)
                            <tr>
                                <th>{{$invoice->invoice_code}}</th>
                                <th>{{date($invoice->created_at)}}</th>
                                <th>{{$invoice->customer_name}}</th>
                                <th>{{$invoice->user_name}}</th>
                                <th>{{$invoice->remaining}}</th>
                                <th>{{$invoice->pickup_date}}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h2>No Records</h2>
                @endif
            </div><!-- /.box-body -->
        </div><!-- /.box-body -->


    </div><!-- /.box -->

@stop
