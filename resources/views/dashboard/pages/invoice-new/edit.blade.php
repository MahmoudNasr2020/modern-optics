@extends('dashboard.layouts.master')

@section('title', 'Edit Invoice')

@section('content')

    <style>
        /* Modern Invoice Design */
        .invoice-page { padding: 20px; background: #f5f7fa; min-height: 100vh; }

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
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }
        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }

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

        .items-table tbody tr:hover {
            background: #f8f9ff;
        }

        /* 🔥 Beautiful Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 3px solid #d1d5db;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .custom-checkbox:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.15);
            transform: scale(1.05);
        }

        .custom-checkbox:checked {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(102,126,234,0.4);
            transform: scale(1.1);
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 8px;
            top: 3px;
            width: 8px;
            height: 14px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
            animation: checkPop 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes checkPop {
            0% {
                transform: scale(0) rotate(45deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(45deg);
            }
            100% {
                transform: scale(1) rotate(45deg);
                opacity: 1;
            }
        }

        .custom-checkbox:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            box-shadow: none;
        }

        .custom-checkbox:disabled:hover {
            border-color: #d1d5db;
            transform: scale(1);
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
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

        .btn-danger-modern {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* Payment Cards Design */
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

        .payment-delete-btn {
            margin-top: 15px;
            width: 100%;
            position: relative;
            z-index: 1;
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

        /* الشكل الأساسي */
        .custom-checkbox-btn {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            background: #ffffff;
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        /* Hover */
        .custom-checkbox-btn:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.12);
            transform: translateY(-1px);
        }

        /* Checked */
        .custom-checkbox-btn:checked {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
        }

        /* علامة الصح */
        .custom-checkbox-btn:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 6px;
            height: 12px;
            border: solid #fff;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
            animation: checkPop 0.2s ease;
        }

        /* أنيميشن */
        @keyframes checkPop {
            0% { transform: scale(0) rotate(45deg); opacity: 0; }
            100% { transform: scale(1) rotate(45deg); opacity: 1; }
        }

        /* Disabled */
        .custom-checkbox-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* تأثير خفيف على الصف لما يتحدد */
        td.text-center:has(.custom-checkbox-btn:checked) {
            background: rgba(102,126,234,0.05);
            transition: background 0.3s ease;
        }

    </style>

    <div class="invoice-page">

        <!-- Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-pencil-square"></i> Edit Invoice</h1>
                    <span class="invoice-code-badge">
                    Invoice Code: {{ $invoice->invoice_code }}
                </span>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.invoice.show', $invoice->invoice_code) }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-left"></i> Back
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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-exclamation-circle"></i>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.invoice.update', $invoice->invoice_code) }}">
            @csrf
            @method('PUT')

            <!-- Invoice Items with Status -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-purple">
                    <div style="display: flex; align-items: center;">
                        <div class="card-title-icon">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Invoice Items</h3>
                    </div>
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
                            <th>Ready</th>
                            <th>Delivered</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->invoiceItems as $key => $item)
                            <tr>
                                <td><strong>{{ $key + 1 }}</strong></td>
                                <td><strong style="color: #667eea;">{{ $item->product_id }}</strong></td>
                                <td>
                                    @if($item->stockable->description)
                                        {{ $item->stockable->description }}
                                        @if($item->direction)
                                            {{ '('.$item->direction.')' }}
                                        @endif
                                    @else
                                        {{ '-' }}
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
                                <td class="text-center">
                                    <input type="checkbox"
                                           name="ready_items[]"
                                           value="{{ $item->id }}"
                                           class="custom-checkbox-btn"
                                        {{ $item->status == 'ready' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           name="delivery_items[]"
                                           value="{{ $item->id }}"
                                           class="custom-checkbox-btn"
                                        {{ $item->status == 'delivery' ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Settings -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-orange">
                    <div style="display: flex; align-items: center;">
                        <div class="card-title-icon">
                            <i class="bi bi-gear"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Invoice Settings</h3>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-flag"></i> Invoice Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $invoice->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Under Process" {{ $invoice->status == 'Under Process' ? 'selected' : '' }}>Under Process</option>
                                    <option value="ready" {{ $invoice->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                    <option value="delivered" {{ $invoice->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-wallet2"></i> Paid Amount</label>
                                <input type="text" class="form-control" value="{{ number_format($invoice->paied, 2) }} QAR" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-cash"></i> Remaining Amount</label>
                                <input type="text" class="form-control" value="{{ number_format($invoice->remaining, 2) }} QAR" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-sticky"></i> Notes</label>
                                <textarea name="notes" class="form-control" rows="4">{{ $invoice->notes }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-inbox"></i> Tray Reference #</label>
                                <textarea name="tray_number" class="form-control" rows="4">{{ $invoice->tray_number }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success-modern btn-modern btn-block btn-lg">
                                <i class="bi bi-check-circle"></i> Update Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <!-- Payment Cards with Delete -->
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

                            <button type="button"
                                    class="btn btn-danger-modern btn-modern payment-delete-btn delete-payment-btn"
                                    data-payment-id="{{ $payment->id }}"
                                    data-payment-amount="{{ number_format($payment->payed_amount, 2) }}"
                                    data-payment-type="{{ $payment->type }}">
                                <i class="bi bi-trash"></i> Delete Payment
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-payments">
                    <i class="bi bi-inbox"></i>
                    <h4>No payments recorded</h4>
                </div>
            @endif
        </div>

        <!-- Add Payment Form -->
        <div class="invoice-card">
            <div class="card-header-custom" style="background: #f8f9ff; color: #333;">
                <div style="display: flex; align-items: center;">
                    <div class="card-title-icon" style="background: #667eea; color: white;">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Add New Payment</h3>
                </div>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="{{ route('dashboard.invoice.payment.add', $invoice->invoice_code) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-wallet2"></i> Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-control" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Atm">ATM</option>
                                    <option value="visa">VISA</option>
                                    <option value="Master Card">Master Card</option>
                                    <option value="Gift Voudire">Gift Voucher</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-cash"></i> Amount (QAR)</label>
                                <input type="number" name="payed_amount" class="form-control" step="0.01" min="0.01" max="{{ $invoice->remaining }}" required>
                                <small class="text-muted">Max: {{ number_format($invoice->remaining, 2) }} QAR</small>
                            </div>
                        </div>

                        <div class="col-md-6" id="card_details" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank</label>
                                        <input type="text" name="bank" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Card Number</label>
                                        <input type="text" name="card_number" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success-modern btn-modern">
                                <i class="bi bi-plus-circle"></i> Add Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Hidden Delete Form -->
    <form id="deletePaymentForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show/Hide card details
        document.getElementById('payment_type').addEventListener('change', function() {
            const cardDetails = document.getElementById('card_details');
            if (this.value === 'Cash') {
                cardDetails.style.display = 'none';
            } else {
                cardDetails.style.display = 'block';
            }
        });

        // SweetAlert Delete Payment
        document.querySelectorAll('.delete-payment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.dataset.paymentId;
                const amount = this.dataset.paymentAmount;
                const type = this.dataset.paymentType;

                Swal.fire({
                    title: 'Delete Payment?',
                    html: `
                    <div style="text-align: left; padding: 20px;">
                        <p style="font-size: 16px; color: #555; margin-bottom: 15px;">
                            Are you sure you want to delete this payment?
                        </p>
                        <div style="background: #f8f9ff; padding: 15px; border-radius: 8px; border-left: 4px solid #e74c3c;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="color: #999; font-weight: 600;">Type:</span>
                                <span style="color: #2c3e50; font-weight: 700;">${type}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #999; font-weight: 600;">Amount:</span>
                                <span style="color: #e74c3c; font-weight: 900; font-size: 18px;">${amount} QAR</span>
                            </div>
                        </div>
                        <p style="margin-top: 15px; color: #e74c3c; font-weight: 600;">
                            <i class="bi bi-exclamation-triangle"></i> This action cannot be undone!
                        </p>
                    </div>
                `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: '<i class="bi bi-trash"></i> Yes, Delete',
                    cancelButtonText: '<i class="bi bi-x"></i> Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger btn-lg',
                        cancelButton: 'btn btn-secondary btn-lg'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit delete form
                        const form = document.getElementById('deletePaymentForm');
                        form.action = `/dashboard/invoices/payment/${paymentId}`;
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
