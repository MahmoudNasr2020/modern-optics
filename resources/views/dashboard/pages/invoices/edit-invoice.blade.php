@extends('dashboard.layouts.master')

@section('content')
    <style>
        .table tbody tr {
            background: #f4f4f4;
            color: black;
        }

    </style>

    <div class="box box-warning">
        <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip"
                        title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
            </div>

            <i class="fa fa-flag"></i>
            <h3 class="box-title">Update Invoice <span class="badge bg-yellow" style="padding: 6px 12px; font-weight:bold">Invoice Code: {{ $invoice->invoice_code }}</span></h3><br>

        </div>

        <div class="box-body">
            <table class="table table-bordered">
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
                    @if($invoice->insurance_cardholder_type)
                        <th>I&C Discount</th>
                    @endif
                    <th>Ready</th>
                    <th>Delivery</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($invoice->invoiceItems as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->product_id }}</td>
                        <td>{{ $item->getItemDescription->description ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->price .' ' .' QAR' }}</td>
                        <td>{{ $item->net .' QAR' }}</td>
                        <td>{{ $item->tax }}</td>
                        <td>{{ $item->total .' QAR' ?? '' }}</td>
                        <td>{{ $item->getItemDescription->amount ?? '-' }}</td>
                        @if($invoice->insurance_cardholder_type)
                            <td>{{ $item->insurance_cardholder_discount .'%' }} </td>
                        @endif
                        <td>
                            <input type="checkbox"
                                   class="ready"
                                   name="status"
                                   id="status-{{ $item->id }}"
                                   value="ready"
                                   data-invoice_item_id="{{ $item->id }}"
                                   style="cursor:pointer;width:20px;height:20px;margin:auto" {{ $item->status == 'ready' ? 'checked' : '' }} />
                        </td>

                        <td>
                            <input type="checkbox"
                                   class="delivery"
                                   name="status"
                                   id="status-{{ $item->id }}"
                                   value="delivery"
                                   data-invoice_item_id="{{ $item->id }}"
                                   style="cursor:pointer;width:20px;height:20px;margin:auto"
                                {{ $item->status == 'delivery' ? 'checked' : '' }} />
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($invoice->discount_type)
                <br><br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Discount Type</th>
                        <th>Discount Value</th>
                        <th>Total Before</th>
                        <th>Total After</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>{{ $invoice->discount_type }}</td>
                        @if($invoice->discount_type == 'fixed')
                            <td>{{$invoice->discount_value}}</td>
                        @else
                            <td>{{$invoice->discount_value}}%</td>
                        @endif

                        @if($invoice->discount_type == 'fixed')
                            <td>{{$invoice->total + $invoice->discount_value .' QAR'}}</td>
                        @else
                            <td>{{ ($invoice->total/ (1 - $invoice->discount_value/100)) }}</td>
                        @endif

                        @if($invoice->discount_type == 'fixed')
                            <td>{{ $invoice->total  .' QAR' }}</td>
                        @else
                            <td>{{ ($invoice->total/(1 - $invoice->discount_value/100)) - (($invoice->total/(1 - $invoice->discount_value/100)) - $invoice->total)  .' QAR' }} </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            @endif


            @if($invoice->insurance_cardholder_type)
                <br>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Insurance&Cardholder Discount Type</th>
                        <th>Type Name</th>
                        <th>Category</th>
                        <th>Total Before</th>
                        <th>Total After</th>
                        <th>Approval Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($invoice->insurance_cardholder_type == 'insurance')
                        @php
                            $insurance = \App\InsuranceCompany::find($invoice->insurance_cardholder_type_id);
                        @endphp

                        <tr>
                            <td>Insurance</td>
                            <td>{{ $insurance->company_name ?? 'N/A' }}</td>
                            <td>
                                @foreach($insurance->categories as $category)
                                    <div>{{ $category->category_name }} ({{ $category->pivot->discount_percent }}%)</div>
                                @endforeach
                            </td>
                            <td>{{ number_format($invoice->total_before_discount, 2) .' QAR' ?? number_format($invoice->total, 2) .' QAR' }}</td>
                            <td>{{ number_format($invoice->total, 2) .' QAR' }}</td>
                            <td>{{ number_format($invoice->insurance_approval_amount, 2) .' QAR' }}</td>
                        </tr>
                    @elseif($invoice->insurance_cardholder_type == 'cardholder')

                        @php
                            $cardholder = \App\Cardholder::find($invoice->insurance_cardholder_type_id);
                        @endphp

                        <tr>
                            <td>Cardholder</td>
                            <td>{{ $cardholder->cardholder_name ?? 'N/A' }}</td>
                            <td>
                                @foreach($cardholder->categories as $category)
                                    <div>{{ $category->category_name }} ({{ $category->pivot->discount_percent }}%)</div>
                                @endforeach
                            </td>
                            <td>{{ number_format($invoice->total_before_discount, 2) .' QAR' ?? number_format($invoice->total, 2) .' QAR' }}</td>
                            <td>{{ number_format($invoice->total, 2) .' QAR' }}</td>
                            <td>{{ number_format($invoice->insurance_approval_amount, 2) .' QAR' }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            @endif



        </div>

    </div>


    <div class="box box-warning">
        <div class="box-header">
            <i class="fa fa-file"></i>
            <h3 class="box-title">Invoice Form</h3><br>
        </div>

        <div class="box-body">
            <form method="POST" action="{{ route('dashboard.update-invoice', $invoice->invoice_code) }}">
                @csrf

                <!-- Invoice Status Field (col-12 for full width) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status">Invoice Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <{{--option value="ready" {{ $invoice->status == 'ready' ? 'selected' : '' }}>Ready</option>--}}
                                <option value="delivered" {{ $invoice->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
{{--
                                <option value="returned" {{ $invoice->status == 'returned' ? 'selected' : '' }}>Returned</option>
--}}
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Financial Information Section (col-6 for each field) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="paied">Paied</label>
                            <input type="text" name="paied" class="form-control" value="{{ $invoice->paied }}" id="paied" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remaining">Remaining</label>
                            <input type="text" name="remaining" class="form-control" value="{{ $invoice->remaining }}" id="remaining" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" name="discount" class="form-control" value="{{ $invoice->discount_value }}" id="discount" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="totalBefore">Total Before Discount</label>
                            <input type="text" name="totalBefore" class="form-control" value="{{ $invoice->total_before_discount }}" id="totalBefore" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="totalAfter">Total After Discount</label>
                            <input type="text" name="totalAfter" class="form-control" value="{{ $invoice->total }}" id="totalAfter" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Notes and Tray Ref Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Notes">Notes</label>
                            <textarea name="notes" class="form-control" id="notes" cols="30" rows="4">{{ $invoice->notes }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Tray Ref">Tray Ref #</label>
                            <textarea name="tray_number" class="form-control" id="tray_number" cols="30" rows="4">{{ $invoice->tray_number }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">\
                        @can('edit-invoices')
                        <button type="submit" class="btn btn-primary pull-right" style="font-weight: 600;">
                            <span style="font-weight: bold;">Update</span>
                        </button>
                        @endcan
                    </div>
                </div>
            </form>
        </div><!-- Box Body -->
    </div>

    <div class="box box-warning">
        <div class="box-header">
            <i class="fa fa-credit-card"></i>
            <h3 class="box-title">Add / Update Payment</h3><br>
        </div>

        <div class="box-body">

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
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($invoice->payments as $index => $pay)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pay->type }}</td>
                        <td>{{ $pay->bank ?? '-' }}</td>
                        <td>{{ $pay->card_number ?? '-' }}</td>
                        <td>{{ ($pay->expiration_date != '0000-00-00' && $pay->expiration_date != null) ? $pay->expiration_date : '-' }}</td>
                        <td>{{ $pay->payed_amount }}</td>
                        <td>{{ $pay->beneficiary ? $pay->getBenficiary->first_name . ' ' . $pay->getBenficiary->last_name : '-' }}</td>
                        <td>{{ $pay->created_at }}</td>
                        <td>
                            @can('delete-payments')
                            <form action="{{ route('dashboard.delete-payment',$pay->id) }}"
                                  method="GET" style="margin-left: 10px;">
                                @csrf
                                <button type="submit"
                                        class="btn btn-danger btn-sm btn-flat delete">
                                    <i class="fa fa-trash-o"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>


            <form method="POST" action="{{ route('dashboard.add-payment-to-invoice', $invoice->invoice_code) }}">
                    @csrf

                    <div class="one-pay">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Choose Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-control">
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
                                    <input type="number" class="form-control" name="payed_amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">

                                <table style="display:none; width:100%" class="table payments-details" id="payment_table">
                                    <thead>
                                    <tr style="background:#232323; color:#fff">
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
                                            <input type="text" class="form-control" name="bank">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" name="card_number">
                                        </td>

                                        <td>
                                            <input class="form-control" type="date" name="expiration_date">
                                        </td>

                                        <td>
                                            <select name="currency" class="form-control">
                                                <option value="QAR">QAR</option>
                                            </select>
                                        </td>

                                        <td>
                                            1
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" name="local_payment" readonly>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @can('add-payments')
                                    <button style="margin-top:15px" class="btn btn-success pull-right">
                                        Update Invoice Payment
                                    </button>
                                @endcan
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>



@endsection

{{--
@section('scripts')
    @include('dashboard.pages.invoices.layout.scripts.update_status_ready')

        <script>
            document.getElementById('payment_type').addEventListener('change', function() {
            let table = document.getElementById('payment_table');

            if (this.value === 'Cash') {
            table.style.display = 'none';
        } else {
            table.style.display = 'table';
        }
        });
    </script>

@stop
--}}

@section('scripts')
    {{-- شيل @include('dashboard.pages.invoices.layout.scripts.update_status_ready') --}}

    <script>
        // إظهار/إخفاء تفاصيل الدفع
        document.getElementById('payment_type').addEventListener('change', function() {
            let table = document.getElementById('payment_table');
            if (this.value === 'Cash') {
                table.style.display = 'none';
            } else {
                table.style.display = 'table';
            }
        });

        // تأكيد التحديث
        document.querySelector('form[action*="update-invoice"]').addEventListener('submit', function(e) {
            e.preventDefault();

            // التحقق من الفاتورة المسلمة والمتبقي
            const status = document.getElementById('status').value;
            const remaining = parseFloat('{{ $invoice->remaining }}');

            if (status === 'delivered' && remaining > 0) {
                alert('⚠️ Cannot mark invoice as DELIVERED!\n\nRemaining Amount: ' + remaining.toFixed(2) + ' QAR\n\nPlease collect the remaining amount first.');
                return false;
            }

            // رسالة التأكيد
            let message = 'Are you sure you want to update this invoice?\n\n';

            // عدد العناصر الـ Ready
            const readyItems = document.querySelectorAll('.ready:checked').length;
            if (readyItems > 0) {
                message += '✓ ' + readyItems + ' item(s) marked as READY\n';
            }

            // عدد العناصر الـ Delivered
            const deliveredItems = document.querySelectorAll('.delivery:checked').length;
            if (deliveredItems > 0) {
                message += '✓ ' + deliveredItems + ' item(s) marked as DELIVERED\n';
            }

            message += '\nInvoice Status: ' + status.toUpperCase();

            if (confirm(message)) {
                this.submit();
            }
        });
    </script>

@stop
