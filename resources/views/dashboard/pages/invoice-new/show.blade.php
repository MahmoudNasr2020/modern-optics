@extends('dashboard.layouts.master')

@section('title', 'Invoice Details')

@section('content')

    <style>
        /* Modern Invoice Design */
        .invoice-page { padding: 20px; background: #f5f7fa; min-height: 100vh; }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .invoice-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .invoice-code-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        /* Cards */
        .invoice-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header-custom {
            padding: 20px 25px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }

        .card-title-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }

        .card-body-custom { padding: 25px; }

        /* Items Table */
        .items-table {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .items-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .items-table thead th {
            color: white;
            padding: 18px 15px;
            font-weight: 700;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .items-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .items-table tbody tr {
            transition: all 0.3s;
        }

        .items-table tbody tr:hover {
            background: #f8f9ff;
            transform: translateX(3px);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1ecf1; color: #0c5460; }

        /* Discount Table */
        .discount-table {
            background: #fff9e6;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .discount-table table {
            margin-bottom: 0;
        }

        .discount-table th {
            background: #f39c12;
            color: white;
            padding: 12px;
        }

        /* 🔥 NEW: Payment Cards Design */
        .payments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding: 25px;
        }

        .payment-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border: 2px solid #e7f3ff;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .payment-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(39,174,96,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(39,174,96,0.2);
            border-color: #27ae60;
        }

        .payment-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .payment-type-badge {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .payment-number {
            background: rgba(102,126,234,0.1);
            color: #667eea;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 14px;
        }

        .payment-amount {
            font-size: 28px;
            font-weight: 900;
            color: #27ae60;
            margin: 15px 0;
            position: relative;
            z-index: 1;
        }

        .payment-details {
            display: grid;
            gap: 10px;
            margin-top: 15px;
            position: relative;
            z-index: 1;
        }

        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .payment-detail-row:last-child {
            border-bottom: none;
        }

        .payment-detail-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .payment-detail-value {
            font-weight: 700;
            color: #2c3e50;
        }

        .empty-payments {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-payments i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-payments h4 {
            color: #999;
            font-size: 18px;
            margin: 0;
        }

        /* Summary Box */
        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-size: 20px;
            font-weight: 900;
            padding-top: 15px;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
            background: #fff;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }
    </style>

    <div class="invoice-page">

        <!-- Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-receipt-cutoff"></i> Invoice Details</h1>
                    <span class="invoice-code-badge">
                    Invoice Code: {{ $invoice->invoice_code }}
                </span>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.invoice.pending') }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Invoice Items -->
        <div class="invoice-card">
            <div class="card-header-custom card-header-purple">
                <div style="display: flex; align-items: center;">
                    <div class="card-title-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Invoice Items</h3>
                </div>
                <span style="background: rgba(255,255,255,0.2); padding: 6px 15px; border-radius: 15px; font-weight: 700;">
                {{ $invoice->invoiceItems->count() }} Items
            </span>
            </div>
            <div class="card-body-custom">
                <table class="table items-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Item ID</th>
                        <th>Description</th>
                        <th>QTY</th>
                        <th>Price</th>
                        @if($invoice->insurance_cardholder_type)
                            <th>Discount %</th>
                        @endif
                        <th>Total</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->invoiceItems as $key => $item)
                        <tr>
                            <td><strong>{{ $key + 1 }}</strong></td>
                            <td><strong style="color: #667eea;">{{ $item->product_id }}</strong></td>
                            <td>
                                @php $desc = optional($item->stockable)->description; @endphp
                                @if($desc)
                                    {{ $desc }}
                                    @if($item->direction)
                                        {{ '('.$item->direction.')' }}
                                    @endif
                                @else
                                    {{ $item->product_id }}
                                @endif
                            </td>
                            <td><strong>{{ $item->quantity }}</strong></td>
                            <td>{{ number_format($item->price, 2) }} QAR</td>
                            @if($invoice->insurance_cardholder_type)
                                <td>
                                    @if($item->insurance_cardholder_discount > 0)
                                        <span style="color: #27ae60; font-weight: 700;">
                                            {{ $item->insurance_cardholder_discount }}%
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                            <td><strong>{{ number_format($item->total, 2) }} QAR</strong></td>
                            <td>
                                @php
                                    $stock = $item->stock_amount;
                                    $badgeClass = $stock > 10 ? 'badge-success' : ($stock > 0 ? 'badge-warning' : 'badge-danger');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $stock }}</span>
                            </td>
                            <td>
                                @if($item->status == 'ready')
                                    <span class="status-badge status-ready">
                                        <i class="bi bi-check-circle"></i> Ready
                                    </span>
                                @elseif($item->status == 'delivery')
                                    <span class="status-badge status-delivered">
                                        <i class="bi bi-truck"></i> Delivered
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Discount Info -->
        @php
            $totalBefore = $invoice->total_before_discount ?? $invoice->total;
            $totalAfter = $invoice->total;
            $discountAmount = max($totalBefore - $totalAfter, 0);
        @endphp

        @if($invoice->discount_type && !$invoice->insurance_cardholder_type)
            <div class="discount-table">
                <h4 style="color: #f39c12; font-weight: 700; margin-bottom: 15px;">
                    <i class="bi bi-percent"></i> Discount Applied
                </h4>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Before</th>
                        <th>Amount</th>
                        <th>After</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>{{ ucfirst($invoice->discount_type) }}</strong></td>
                        <td>
                            @if($invoice->discount_type == 'fixed')
                                {{ number_format($invoice->discount_value, 2) }} QAR
                            @else
                                {{ $invoice->discount_value }}%
                            @endif
                        </td>
                        <td>{{ number_format($totalBefore, 2) }} QAR</td>
                        <td><strong style="color: #e74c3c;">-{{ number_format($discountAmount, 2) }} QAR</strong></td>
                        <td><strong>{{ number_format($totalAfter, 2) }} QAR</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        @if($invoice->insurance_cardholder_type)
            @php
                $company = $invoice->insurance_cardholder_type === 'insurance'
                    ? \App\InsuranceCompany::find($invoice->insurance_cardholder_type_id)
                    : \App\Cardholder::find($invoice->insurance_cardholder_type_id);
            @endphp

            <div class="discount-table">
                <h4 style="color: #f39c12; font-weight: 700; margin-bottom: 15px;">
                    <i class="bi bi-shield-check"></i> {{ ucfirst($invoice->insurance_cardholder_type) }} Discount
                </h4>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Categories</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Approval Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>{{ ucfirst($invoice->insurance_cardholder_type) }}</strong></td>
                        <td>{{ $company->company_name ?? $company->cardholder_name ?? 'N/A' }}</td>
                        <td>
                            @foreach($company->categories ?? [] as $category)
                                <div>{{ $category->category_name }} ({{ $category->pivot->discount_percent }}%)</div>
                            @endforeach
                        </td>
                        <td>{{ number_format($totalBefore, 2) }} QAR</td>
                        <td><strong>{{ number_format($totalAfter, 2) }} QAR</strong></td>
                        <td>{{ number_format($invoice->insurance_approval_amount ?? 0, 2) }} QAR</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- 🔥 NEW: Payment Cards Design -->
        <div class="invoice-card">
            <div class="card-header-custom card-header-green">
                <div style="display: flex; align-items: center;">
                    <div class="card-title-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Payment History</h3>
                </div>
                <span style="background: rgba(255,255,255,0.2); padding: 6px 15px; border-radius: 15px; font-weight: 700;">
                {{ $payments->count() }} Payments
            </span>
            </div>

            @if($payments->count() > 0)
                <div class="payments-grid">
                    @foreach($payments as $key => $payment)
                        <div class="payment-card">
                            <div class="payment-card-header">
                                <div class="payment-type-badge">
                                    <i class="bi bi-wallet2"></i>
                                    {{ $payment->type }}
                                </div>
                                <div class="payment-number">#{{ $key + 1 }}</div>
                            </div>

                            <div class="payment-amount">
                                {{ number_format($payment->payed_amount, 2) }} <span style="font-size: 18px;">QAR</span>
                            </div>

                            <div class="payment-details">
                                @if($payment->bank)
                                    <div class="payment-detail-row">
                                    <span class="payment-detail-label">
                                        <i class="bi bi-bank"></i> Bank
                                    </span>
                                        <span class="payment-detail-value">{{ $payment->bank }}</span>
                                    </div>
                                @endif

                                @if($payment->card_number)
                                    <div class="payment-detail-row">
                                    <span class="payment-detail-label">
                                        <i class="bi bi-credit-card-2-front"></i> Card
                                    </span>
                                        <span class="payment-detail-value">{{ $payment->card_number }}</span>
                                    </div>
                                @endif

                                <div class="payment-detail-row">
                                <span class="payment-detail-label">
                                    <i class="bi bi-person-circle"></i> By
                                </span>
                                    <span class="payment-detail-value">
                                    {{ optional($payment->getBenficiary)->first_name }} {{ optional($payment->getBenficiary)->last_name }}
                                </span>
                                </div>

                                <div class="payment-detail-row">
                                <span class="payment-detail-label">
                                    <i class="bi bi-calendar-check"></i> Date
                                </span>
                                    <span class="payment-detail-value">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-payments">
                    <i class="bi bi-inbox"></i>
                    <h4>No payments recorded</h4>
                </div>
            @endif

            <!-- Payment Summary -->
            <div class="summary-box">
                <div class="summary-item">
                    <span>Total Invoice:</span>
                    <strong>{{ number_format($invoice->total, 2) }} QAR</strong>
                </div>
                <div class="summary-item">
                    <span>Paid:</span>
                    <strong>{{ number_format($invoice->paied, 2) }} QAR</strong>
                </div>
                <div class="summary-item">
                    <span>Remaining:</span>
                    <strong>{{ number_format($invoice->remaining, 2) }} QAR</strong>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="invoice-card">
            <div class="card-body-custom">
                <div class="action-btns">
                    <a href="{{ route('dashboard.invoice.edit', $invoice->invoice_code) }}" class="btn btn-warning-modern btn-modern">
                        <i class="bi bi-pencil"></i> Edit Invoice
                    </a>

                    <a href="{{ url('/dashboard/print-pending-invoice/en/' . $invoice->invoice_code) }}" target="_blank" class="btn btn-primary-modern btn-modern">
                        <i class="bi bi-printer"></i> Print (English)
                    </a>

                    <a href="{{ url('/dashboard/print-pending-invoice/ar/' . $invoice->invoice_code) }}" target="_blank" class="btn btn-success-modern btn-modern">
                        <i class="bi bi-printer"></i> Print (Arabic)
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
