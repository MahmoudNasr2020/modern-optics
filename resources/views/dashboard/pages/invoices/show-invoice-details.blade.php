@php use App\Cardholder;use App\InsuranceCompany; @endphp
@extends('dashboard.layouts.master')

@section('content')
    <style>
        .table tbody tr {
            background: #f4f4f4;
            color: black;
        }

    </style>
    @php
        $totalBefore = $invoice->total_before_discount !== null
       ? (float) $invoice->total_before_discount
       : (float) ($invoice->total ?? 0);

   $totalAfter = (float) ($invoice->total ?? 0);
   $discountAmount = max($totalBefore - $totalAfter, 0);
    @endphp


    <div class="box box-warning">
        <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip"
                        title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
            </div>

            <i class="fa fa-flag"></i>
            <h3 class="box-title">Invoice Details
                <span class="badge bg-yellow" style="padding: 6px 12px; font-weight:bold">Invoice Code: {{ $invoice->invoice_code }}</span>
            </h3><br>

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
                  {{--  <th>Discount</t--}}
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
                      {{--  <td class="text-center">
                            @php
                                $discountValue = $item->price - $item->net;
                                $discountPercent = $item->price > 0
                                    ? round(($discountValue / $item->price) * 100, 1)
                                    : 0;
                            @endphp

                            <div>{{ $discountPercent }}%</div>
                            <small class="text-muted">{{ number_format($discountValue, 2) }} QAR</small>
                        </td>--}}

                        <td>{{ $item->total .' QAR' ?? '' }}</td>
                        <td>{{ $item->getItemDescription->amount ?? '-' }}</td>
                        @if($invoice->insurance_cardholder_type)
                            <td>{{ $item->insurance_cardholder_discount .'%' }} </td>
                        @endif
                        <td>
                            <div style="position:relative; display:inline-block;">
                                <input type="checkbox"
                                       class="ready"
                                       {{ $item->status == 'ready' ? 'checked' : '' }}
                                       style="position:relative; z-index:1;">
                                <div
                                    style="position:absolute; left:0; top:0; width:100%; height:100%; z-index:2;"></div>
                            </div>

                        </td>

                        <td>
                            <div style="position:relative; display:inline-block;">
                                <input type="checkbox"
                                       class="delivery"
                                       {{ $item->status == 'delivery' ? 'checked' : '' }}
                                       style="position:relative; z-index:1;">
                                <div
                                    style="position:absolute; left:0; top:0; width:100%; height:100%; z-index:2;"></div>
                            </div>

                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>



            @if(!empty($invoice->discount_type) && empty($invoice->insurance_cardholder_type))

                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Discount Type</th>
                        <th>Discount Value</th>
                        <th>Total Before Discount</th>
                        <th>Discount Amount</th>
                        <th>Total After Discount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ ucfirst($invoice->discount_type) }}</td>
                        <td>
                            @if($invoice->discount_type == 'fixed')
                                {{ number_format($invoice->discount_value,2) }} QAR
                            @else
                                {{ $invoice->discount_value }} %
                            @endif
                        </td>
                        <td>{{ number_format($totalBefore,2) }} QAR</td>
                        <td>{{ number_format($discountAmount,2) }} QAR</td>
                        <td>{{ number_format($totalAfter,2) }} QAR</td>
                    </tr>
                    </tbody>
                </table>
            @endif

            @php
                $insurance  = null;
                $cardholder = null;

                if ($invoice->insurance_cardholder_type === 'insurance') {
                    $insurance = \App\InsuranceCompany::find($invoice->insurance_cardholder_type_id);
                }

                if ($invoice->insurance_cardholder_type === 'cardholder') {
                    $cardholder = \App\Cardholder::find($invoice->insurance_cardholder_type_id);
                }
            @endphp

            @if($invoice->insurance_cardholder_type)
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Discount Type</th>
                        <th>Name</th>
                        <th>Categories</th>
                        <th>Total Before Discount</th>
                        <th>Total After Discount</th>
                        <th>Approval Amount</th>
                        <th>Discount Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ ucfirst($invoice->insurance_cardholder_type) }}</td>

                        <td>
                            {{ $insurance->company_name
                                ?? $cardholder->cardholder_name
                                ?? 'N/A' }}
                        </td>

                        <td>
                            @foreach(optional($insurance ?? $cardholder)->categories ?? [] as $category)
                                <div>
                                    {{ $category->category_name }}
                                    ({{ $category->pivot->discount_percent }}%)
                                </div>
                            @endforeach
                        </td>

                        <td>{{ number_format((float)$totalBefore,2) }} QAR</td>
                        <td>{{ number_format((float)$totalAfter,2) }} QAR</td>
                        <td>{{ number_format((float)$invoice->insurance_approval_amount,2) }} QAR</td>
                        <td>{{ number_format((float)$discountAmount,2) }} QAR</td>
                    </tr>
                    </tbody>
                </table>
            @endif

        </div>

    </div>

    {{-- invoice status --}}


    <div class="box box-warning">
        <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
                <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip"
                        title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
            </div>

            <i class="fa fa-file"></i>
            <h3 class="box-title">Invoice Payments Details <span class="badge bg-yellow"
                                                                 style="padding: 6px 12px; font-weight:bold">{{ $payments->count() }}</span>
            </h3><br>

        </div>

        <div class="box-body">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Bank</th>
                    <th>Card No</th>
                    <th>Expiration Date</th>
                    <th>Paid</th>
                    <th>Total</th>
                    <th>Benificiary</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                </thead>

                <tbody>
                {{--@php
                    $finalTotal = $invoice->total;
                    if($invoice->discount_type == 'fixed') {
                        $finalTotal = $invoice->total - $invoice->discount_value;
                    } elseif($invoice->discount_type == 'percentage') {
                        $finalTotal = $invoice->total - ($invoice->total * $invoice->discount_value/100);
                    }
                @endphp--}}

                @foreach ($payments as $key => $pay)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $pay->type }}</td>
                        <td>{{ $pay->bank ?? '-' }}</td>
                        <td>{{ $pay->card_number ?? '-' }}</td>
                        <td>{{ $pay->expiration_date != '0000-00-00' && $pay->expiration_date != null ? $pay->expiration_date : '-' }}</td>
                        <td>{{ number_format($pay->payed_amount, 2) .' QAR' }}</td>
                        <td>{{ number_format($invoice->total,2) }} QAR</td>
                        <td>{{ $pay->getBenficiary ? $pay->getBenficiary->first_name . ' ' . $pay->getBenficiary->last_name : '-' }}</td>
                        <td>-</td>
                        <td>{{ $pay->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="6" class="text-center">Status</th>
                    <th colspan="6" style="color:
                    {{ $invoice->status == 'pending' ? '#f39c12' : '' }}
                    {{ $invoice->status == 'ready' ? '#3498db' : '' }}
                    {{ $invoice->status == 'delivered' ? '#00a65a' : '' }}
                    {{ $invoice->status == 'returned' ? '#e74c3c' : '' }}
                ">
                        {{ ucfirst($invoice->status) }}
                    </th>
                </tr>

                <tr>
                    <th colspan="6" class="text-center">Paid</th>
                    <th colspan="6">{{ number_format((float) $payments->sum('payed_amount'),2) }}
                        QAR
                    </th>
                </tr>

                <tr>
                    <th colspan="6" class="text-center">Total</th>
                    <th colspan="6">{{ number_format($invoice->total,2) }} QAR</th>
                </tr>

                </tfoot>
            </table>

            <br>
            <div style="direction: rtl;">
                <a href="{{ url('/dashboard/print-pending-invoice/en/') . '/'.$invoice->invoice_code}}">
                    <button class="btn btn-primary">
                        <span style="font-weight: bold;">Print Invoice</span>
                    </button>
                </a>
                <a href="{{ url('/dashboard/print-pending-invoice/ar/') . '/'.$invoice->invoice_code}}">
                    <button class="btn btn-primary">
                        <span style="font-weight: bold;">طباعة الفاتورة</span>
                    </button>
                </a>

                <a href="{{ route('dashboard.edit-invoice',$invoice->invoice_code) }}">
                    <button class="btn btn-primary">
                        <span style="font-weight: bold;">تعديل الفاتورة</span>
                    </button>
                </a>

            </div>
        </div>

    </div>

    {{--<div class="dropdown btn-primary pull-right" style="margin-left: 15px">
        <button class="btn btn-primary"><span style="font-weight: bold;">Print</span></button>
        <div class="dropdown-content">

            <a href='{{ url('/dashboard/print-pending-invoice/ar/') . '/'}}${r.invoice_code}' id="printInvoiceAr" target="_blank"
               style="font-weight: 600; margin-right:20px">Arabic</a>
            <a href='{{ url('/dashboard/print-pending-invoice/en/') . '/'}}${r.invoice_code}' id="printInvoiceEn" target="_blank"
               style="font-weight: 600; margin-right:20px">English</a></option>
        </div>
    </div>--}}

@endsection

@section('scripts')
    {{-- @include('dashboard.pages.invoices.layout.scripts.update_status_ready')--}}
@stop
