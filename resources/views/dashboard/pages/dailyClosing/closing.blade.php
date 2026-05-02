@extends('dashboard.layouts.master')

@section('title', 'Daily Closing - ' . $date)

@section('content')

    <style>
        .closing-page {
            padding: 20px;
        }

        .box-closing {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }

        .box-closing .box-header {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-closing .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-closing .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-closing .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-closing .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .box-closing .box-header .btn:hover {
            background: white;
            color: #8e44ad;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Noty Custom Theme - Beautiful Notifications */
        .noty_theme__custom.noty_bar {
            margin: 8px 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            border: none;
            padding: 18px 22px;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 15px;
            font-weight: 600;
        }

        .noty_theme__custom.noty_bar .noty_body {
            padding: 0;
            display: flex;
            align-items: center;
        }

        .noty_theme__custom.noty_bar .noty_body::before {
            content: '';
            font-family: 'bootstrap-icons';
            font-size: 22px;
            margin-right: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .noty_theme__custom.noty_type__success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .noty_theme__custom.noty_type__success .noty_body::before {
            content: '✓';
            background: rgba(255,255,255,0.25);
            font-weight: bold;
        }

        .noty_theme__custom.noty_type__error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .noty_theme__custom.noty_type__error .noty_body::before {
            content: '✕';
            background: rgba(255,255,255,0.25);
            font-weight: bold;
        }

        .noty_theme__custom.noty_type__warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #fff;
        }

        .noty_theme__custom.noty_type__warning .noty_body::before {
            content: '⚠';
            background: rgba(255,255,255,0.25);
            font-weight: bold;
        }

        .noty_theme__custom.noty_type__info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .noty_theme__custom.noty_type__info .noty_body::before {
            content: 'ℹ';
            background: rgba(255,255,255,0.25);
            font-weight: bold;
        }

        .noty_theme__custom.noty_bar .noty_close_button {
            background: rgba(255,255,255,0.3);
            color: white;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 300;
            transition: all 0.3s;
        }

        .noty_theme__custom.noty_bar .noty_close_button:hover {
            background: rgba(255,255,255,0.5);
            transform: rotate(90deg);
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.open {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            color: #856404;
            border: 2px solid #ffc107;
        }

        .status-badge.closed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }

        .status-badge i {
            margin-right: 5px;
        }

        /* Transactions Table */
        .transactions-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .transactions-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .transactions-table thead th {
            color: white;
            font-weight: 700;
            padding: 15px 12px;
            border: none;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .transactions-table tbody td {
            padding: 14px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-size: 13px;
        }

        .transactions-table tbody tr {
            transition: all 0.3s;
        }

        .transactions-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
        }

        .label {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .label-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #28a745;
        }

        .label-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #dc3545;
        }

        /* Totals Table */
        .totals-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .totals-table thead {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .totals-table thead th {
            color: white;
            font-weight: 700;
            padding: 15px 12px;
            border: none;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .totals-table tbody td {
            padding: 14px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-size: 13px;
        }

        .totals-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        }

        .totals-table tfoot {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 700;
        }

        .totals-table tfoot td {
            padding: 15px 12px;
            border: none;
        }

        /* Daily Closing Table */
        .daily-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .daily-table th,
        .daily-table td {
            border: 1px solid #e0e6ed;
            padding: 14px;
            text-align: center;
        }

        .daily-table th {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .daily-table tbody tr {
            transition: all 0.3s;
        }

        .daily-table tbody tr:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .daily-table tbody tr.danger {
            background-color: #fff5f5 !important;
            border-left: 4px solid #dc3545;
        }

        .daily-table tbody tr.success {
            background-color: #f0fff4 !important;
            border-left: 4px solid #28a745;
        }

        .daily-table tbody tr.warning {
            background-color: #fffbf0 !important;
            border-left: 4px solid #ffc107;
        }

        .daily-input,
        .daily-select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s;
        }

        .daily-input:focus,
        .daily-select:focus {
            outline: none;
            border-color: #8e44ad;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
        }

        .daily-input.positive {
            color: #28a745;
            font-weight: bold;
            background: linear-gradient(135deg, #f0fff4 0%, #fff 100%);
        }

        .daily-input.negative {
            color: #dc3545;
            font-weight: bold;
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        }

        .status-cell-badge {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .status-cell-badge.resolved {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }

        .status-cell-badge.unresolved {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 2px solid #dc3545;
            animation: pulse-badge 2s infinite;
        }

        .status-cell-badge.average {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            color: #856404;
            border: 2px solid #ffc107;
        }

        @keyframes pulse-badge {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.85;
                transform: scale(1.03);
            }
        }

        /* Balance Log */
        .balance-log {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            max-height: 400px;
            overflow-y: auto;
            border: 2px solid #ffd54f;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .balance-log h4 {
            color: #f57c00;
            margin: 0 0 15px 0;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .balance-item {
            padding: 12px 15px;
            margin: 8px 0;
            background: white;
            border-radius: 8px;
            font-size: 13px;
            text-align: left;
            border-left: 4px solid #ff9800;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .balance-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            transform: translateX(8px);
            border-left-width: 6px;
        }

        /* Action Buttons */
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            border-radius: 10px;
        }

        .daily-btn {
            padding: 14px 30px;
            color: #fff;
            border: none;
            cursor: pointer;
            margin: 5px;
            font-weight: 700;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .daily-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }

        .daily-btn i {
            margin-right: 8px;
        }

        .btn-balance {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .btn-balance:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        }

        .btn-save-all {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }

        .btn-save-all:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        }

        .btn-close-day {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .btn-close-day:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner {
            border: 5px solid rgba(255,255,255,0.3);
            border-top: 5px solid #8e44ad;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 60px;
            color: #dee2e6;
            margin-bottom: 15px;
            display: block;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Info Row Styling */
        .info-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 0;
        }

        .info-row strong {
            color: #495057;
            font-weight: 600;
        }

        .info-row i {
            color: #8e44ad;
            margin-right: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .daily-table th,
            .daily-table td {
                padding: 8px;
                font-size: 11px;
            }

            .daily-btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }

            .balance-item {
                font-size: 11px;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-calendar-check"></i> Daily Closing
            <small>{{ $date }} - {{ $branch->name }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('dashboard.daily-closing.index') }}">Daily Closing</a></li>
            <li class="active">{{ $date }}</li>
        </ol>
    </section>

    <div class="closing-page">
        <!-- Header Box -->
        <div class="box box-primary box-closing">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-info-circle"></i> Closing Information
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.daily-closing.index') }}" class="btn btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="box-body info-row">
                <div class="row">
                    <div class="col-md-3">
                        <strong><i class="bi bi-calendar3"></i> Date:</strong> {{ $date }}
                    </div>
                    <div class="col-md-3">
                        <strong><i class="bi bi-building"></i> Branch:</strong> {{ $branch->name }}
                    </div>
                    <div class="col-md-3">
                        <strong><i class="bi bi-person"></i> Created by:</strong> {{ $dailyClosing->createdBy->full_name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3" style="text-align: right;">
                    <span class="status-badge {{ $dailyClosing->status }}">
                        @if($dailyClosing->status === 'open')
                            <i class="bi bi-unlock"></i> OPEN
                        @else
                            <i class="bi bi-lock"></i> CLOSED
                        @endif
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="box box-primary box-closing">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-receipt"></i> Transactions
                </h3>
            </div>
            <div class="box-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-hover transactions-table">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Payment</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->transaction_type == 'refund')
                                        <span class="label label-danger">REFUND</span>
                                    @elseif($transaction->transaction_type == 'expense')
                                        <span class="label label-danger">EXPENSE</span>
                                    @else
                                        <span class="label label-success">SALE</span>
                                    @endif
                                </td>
                                <td>{{ optional($transaction->invoice)->invoice_code ?? '-' }}</td>
                                <td>{{ $transaction->transaction_date->format('H:i') }}</td>
                                <td>{{ optional($transaction->customer)->english_name ?? '-' }}</td>
                                <td><strong>{{ strtoupper($transaction->payment_type) }}</strong></td>
                                <td style="text-align: right; {{ $transaction->amount < 0 ? 'color: #e74c3c;' : 'color: #27ae60;' }}">
                                    <strong>{{ number_format($transaction->amount, 2) }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No transactions found</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Totals Table -->
        <div class="box box-success box-closing">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                <h3 class="box-title" style="color: white;">
                    <i class="bi bi-calculator"></i> Payment Totals
                </h3>
            </div>
            <div class="box-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table totals-table">
                        <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th style="text-align: right;">Sales</th>
                            <th style="text-align: right;">Refunds</th>
                            <th style="text-align: right;">Expenses</th>
                            <th style="text-align: right;">Net Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(['CASH', 'ATM', 'VISA', 'MASTERCARD', 'MADA', 'BANK_TRANSFER', 'GIFT VOUCHER', 'POINT'] as $type)
                            @php
                                $sales = $salesTotals[$type] ?? 0;
                                $refunds = $refundsTotals[$type] ?? 0;
                                $expense = $expensesTotals[$type] ?? 0;
                                $net = $paymentTotals[$type] ?? 0;
                            @endphp

                            @if($sales != 0 || $refunds != 0 || $expense != 0)
                                <tr>
                                    <td><strong>{{ $type }}</strong></td>
                                    <td style="text-align: right; color: #27ae60;">
                                        {{ number_format($sales, 2) }}
                                    </td>
                                    <td style="text-align: right; color: #e74c3c;">
                                        {{ number_format($refunds, 2) }}
                                    </td>
                                    <td style="text-align: right; color: #dc3545;">
                                        {{ number_format($expense, 2) }}
                                    </td>
                                    <td style="text-align: right; font-weight: bold;">
                                        {{ number_format($net, 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td><strong>GRAND TOTAL</strong></td>
                            <td style="text-align: right; color: #27ae60;">
                                <strong>{{ number_format($salesTotals->sum(), 2) }}</strong>
                            </td>
                            <td style="text-align: right; color: #e74c3c;">
                                <strong>{{ number_format($refundsTotals->sum(), 2) }}</strong>
                            </td>
                            <td style="text-align: right; color: #dc3545;">
                                <strong>{{ number_format($expensesTotals->sum(), 2) }}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>{{ number_format($paymentTotals->sum(), 2) }}</strong>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Daily Closing Details -->
        <div class="box box-warning box-closing">
            <div class="box-header with-border" style="background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%); color: white;">
                <h3 class="box-title" style="color: white;">
                    <i class="bi bi-clipboard-check"></i> Daily Closing Details
                </h3>
            </div>
            <div class="box-body" style="padding: 25px;">

                <!-- Balance Log -->
                @if($balanceLogs->isNotEmpty())
                    <div class="balance-log">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0;"><i class="bi bi-clock-history"></i> Balance History</h4>
                            @if($dailyClosing->status === 'open')
                                <button onclick="clearBalanceHistory()"
                                        style="background:#dc3545; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:bold;">
                                    <i class="bi bi-trash"></i> Clear
                                </button>
                            @endif
                        </div>
                        <div id="balanceItems">
                            @foreach($balanceLogs as $log)
                                <div class="balance-item">
                                    <i class="bi bi-arrow-left-right"></i> Transferred <strong>{{ number_format($log->amount, 2) }}</strong>
                                    from <strong style="color:#28a745;">{{ $log->from_payment_type }}</strong>
                                    to <strong style="color:#dc3545;">{{ $log->to_payment_type }}</strong>
                                    <span style="color:#999; font-size:11px; margin-left:10px;">
                                    ({{ $log->created_at->format('H:i:s') }})
                                </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Daily Table -->
                <table class="daily-table">
                    <thead>
                    <tr>
                        <th style="width: 100px;">Payment</th>
                        <th style="width: 110px;">System</th>
                        <th style="width: 110px;">Entry</th>
                        <th style="width: 110px;">Difference</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;">Reason</th>
                        <th style="width: 200px;">Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(['CASH', 'VISA', 'MASTERCARD', 'ATM'] as $type)
                        @php
                            $payment = $closingPayments[$type] ?? null;
                            $rowClass = $payment ? $payment->getStatusColorClass() : '';
                            $system = $paymentTotals[$type] ?? 0;
                            $entry = $payment ? $payment->entry_amount : 0;
                            $diff = $payment ? $payment->difference : 0;
                            $reason = $payment ? $payment->reason : '';
                            $notes = $payment ? $payment->notes : '';
                            $isClosed = $dailyClosing->status === 'closed';

                            $statusBadge = '';
                            $statusClass = '';
                            if ($diff == 0) {
                                $statusBadge = '✅ Resolved';
                                $statusClass = 'resolved';
                            } elseif ($reason === 'ACTUAL_AVERAGE') {
                                $statusBadge = '⚠️ Average';
                                $statusClass = 'average';
                            } else {
                                $statusBadge = '❌ Unresolved';
                                $statusClass = 'unresolved';
                            }
                        @endphp

                        <tr class="{{ $rowClass }}" data-type="{{ $type }}">
                            <td><strong>{{ $type }}</strong></td>
                            <td>
                                <strong style="color: #8e44ad;">{{ number_format($system, 2) }}</strong>
                            </td>
                            <td>
                                <input type="number"
                                       class="daily-input entry-input"
                                       data-type="{{ $type }}"
                                       value="{{ $entry }}"
                                       step="0.01"
                                       {{ $isClosed ? 'disabled' : '' }}
                                       onchange="updateDifference('{{ $type }}')">
                            </td>
                            <td>
                                <input type="number"
                                       class="daily-input diff-input {{ $diff > 0 ? 'positive' : ($diff < 0 ? 'negative' : '') }}"
                                       data-type="{{ $type }}"
                                       value="{{ number_format($diff, 2) }}"
                                       step="0.01"
                                       {{ $isClosed ? 'disabled' : '' }}
                                       onchange="updateFromDifference('{{ $type }}')">
                            </td>
                            <td class="status-cell" data-type="{{ $type }}">
                                <span class="status-cell-badge {{ $statusClass }}">{{ $statusBadge }}</span>
                            </td>
                            <td>
                                <select class="daily-select reason-select"
                                        data-type="{{ $type }}"
                                        {{ $isClosed ? 'disabled' : '' }}
                                        onchange="updateStatus('{{ $type }}')">
                                    <option value="">-- Select --</option>
                                    <option value="ACTUAL_AVERAGE" {{ $reason === 'ACTUAL_AVERAGE' ? 'selected' : '' }}>Actual Average</option>
                                    <option value="WRONG_PAYMENT" {{ $reason === 'WRONG_PAYMENT' ? 'selected' : '' }}>Wrong Payment</option>
                                    <option value="ACTUAL_SHORTAGE" {{ $reason === 'ACTUAL_SHORTAGE' ? 'selected' : '' }}>Actual Shortage</option>
                                </select>
                            </td>
                            <td>
                            <textarea class="daily-input notes-input"
                                      data-type="{{ $type }}"
                                      rows="2"
                                      {{ $isClosed ? 'disabled' : '' }}
                                      placeholder="Notes...">{{ $notes }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Action Buttons -->
                @if($dailyClosing->status === 'open')
                    <div class="action-buttons">
                        <button class="daily-btn btn-balance" onclick="autoBalance()">
                            <i class="bi bi-arrow-left-right"></i> Auto Balance
                        </button>
                        <button class="daily-btn btn-save-all" onclick="saveAll()">
                            <i class="bi bi-floppy"></i> Save All
                        </button>
                        <button class="daily-btn btn-close-day" onclick="closeDailyClosing()">
                            <i class="bi bi-lock"></i> Close Daily Closing
                        </button>
                    </div>
                @else
                    <div style="text-align: center; margin-top: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%); padding: 25px; border-radius: 10px;">
                        <p style="color: #155724; font-weight: bold; font-size: 16px; margin-bottom: 20px;">
                            ✅ Closed at {{ $dailyClosing->closed_at->format('Y-m-d H:i:s') }}
                        </p>
                        <button class="daily-btn" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);" onclick="reopenDailyClosing()">
                            <i class="bi bi-unlock"></i> Reopen
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div style="text-align: center;">
            <div class="spinner"></div>
            <p style="color: white; margin-top: 15px; font-size: 16px; font-weight: 600;">Loading...</p>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const dailyClosingId = {{ $dailyClosing->id }};
        const branchId = {{ $branchId }};
        const systemAmounts = {
            'CASH': {{ $paymentTotals['CASH'] ?? 0 }},
            'VISA': {{ $paymentTotals['VISA'] ?? 0 }},
            'MASTERCARD': {{ $paymentTotals['MASTERCARD'] ?? 0 }},
            'ATM': {{ $paymentTotals['ATM'] ?? 0 }}
        };

        function showLoading() {
            $('#loadingOverlay').addClass('show');
        }

        function hideLoading() {
            $('#loadingOverlay').removeClass('show');
        }

        function showNotification(message, type = 'success') {
            const config = {
                title: type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : type === 'warning' ? 'Warning!' : 'Info!',
                text: message,
                icon: type,
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast'
                }
            };

            Swal.fire(config);
        }

        function showConfirmation(title, message, onConfirm) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-check-circle"></i> Yes, Continue',
                cancelButtonText: '<i class="bi bi-x-circle"></i> Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger btn-lg',
                    cancelButton: 'btn btn-secondary btn-lg'
                },
                buttonsStyling: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        }

        function updateStatus(type) {
            const difference = parseFloat($('.diff-input[data-type="' + type + '"]').val()) || 0;
            const reason = $('.reason-select[data-type="' + type + '"]').val();
            const statusCell = $('.status-cell[data-type="' + type + '"]');

            let statusBadge = '';
            let statusClass = '';

            if (difference === 0) {
                statusBadge = '✅ Resolved';
                statusClass = 'resolved';
            } else if (reason === 'ACTUAL_AVERAGE') {
                statusBadge = '⚠️ Average';
                statusClass = 'average';
            } else {
                statusBadge = '❌ Unresolved';
                statusClass = 'unresolved';
            }

            statusCell.html('<span class="status-cell-badge ' + statusClass + '">' + statusBadge + '</span>');
            updateRowColor(type);
        }

        function updateDifference(type) {
            const entry = parseFloat($('.entry-input[data-type="' + type + '"]').val()) || 0;
            const system = systemAmounts[type];
            const difference = entry - system;

            const diffInput = $('.diff-input[data-type="' + type + '"]');
            diffInput.val(difference.toFixed(2));

            diffInput.removeClass('positive negative');
            if (difference > 0) {
                diffInput.addClass('positive');
            } else if (difference < 0) {
                diffInput.addClass('negative');
            }

            updateStatus(type);
        }

        function updateFromDifference(type) {
            const difference = parseFloat($('.diff-input[data-type="' + type + '"]').val()) || 0;
            const system = systemAmounts[type];
            const entry = system + difference;

            $('.entry-input[data-type="' + type + '"]').val(entry.toFixed(2));
            updateStatus(type);
        }

        function updateRowColor(type) {
            const row = $('tr[data-type="' + type + '"]');
            const difference = parseFloat($('.diff-input[data-type="' + type + '"]').val()) || 0;
            const reason = $('.reason-select[data-type="' + type + '"]').val();

            row.removeClass('danger success warning');

            if (difference === 0) {
                row.addClass('success');
            } else if (reason === 'ACTUAL_AVERAGE') {
                row.addClass('warning');
            } else if (difference !== 0) {
                row.addClass('danger');
            }
        }

        function saveEntry(type) {
            const entry = parseFloat($('.entry-input[data-type="' + type + '"]').val()) || 0;
            const difference = parseFloat($('.diff-input[data-type="' + type + '"]').val()) || 0;
            const reason = $('.reason-select[data-type="' + type + '"]').val();
            const notes = $('.notes-input[data-type="' + type + '"]').val();
            const date = "{{ $date }}";

            return $.ajax({
                url: "{{ route('dashboard.daily-closing.save-entry') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    daily_closing_id: dailyClosingId,
                    branch_id: branchId,
                    payment_type: type,
                    entry_amount: entry,
                    difference: difference,
                    reason: reason,
                    notes: notes,
                    date: date
                }
            });
        }

        function saveAll() {
            showLoading();

            const promises = ['CASH', 'VISA', 'MASTERCARD', 'ATM'].map(type => saveEntry(type));

            Promise.all(promises)
                .then(responses => {
                    hideLoading();
                    showNotification('All entries saved successfully!', 'success');

                    responses.forEach(response => {
                        if (response.success && response.data) {
                            updateStatus(response.data.payment_type);
                        }
                    });
                })
                .catch(error => {
                    hideLoading();
                    showNotification(error.responseJSON?.message || 'Unknown error occurred', 'error');
                });
        }

        function autoBalance() {
            showLoading();

            const manualDifferences = {};
            ['CASH', 'VISA', 'MASTERCARD', 'ATM'].forEach(type => {
                const diff = parseFloat($('.diff-input[data-type="' + type + '"]').val()) || 0;
                manualDifferences[type] = diff;
            });

            $.ajax({
                url: "{{ route('dashboard.daily-closing.auto-balance') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    daily_closing_id: dailyClosingId,
                    manual_differences: manualDifferences
                },
                success: function(response) {
                    hideLoading();

                    if (response.success) {
                        showNotification(response.message, 'success');

                        response.payments.forEach(payment => {
                            const type = payment.payment_type;
                            const diffInput = $('.diff-input[data-type="' + type + '"]');
                            const newDiff = parseFloat(payment.difference);

                            diffInput.val(newDiff.toFixed(2));

                            diffInput.removeClass('positive negative');
                            if (newDiff > 0) {
                                diffInput.addClass('positive');
                            } else if (newDiff < 0) {
                                diffInput.addClass('negative');
                            }

                            updateStatus(type);
                        });

                        if (response.balance_log && response.balance_log.length > 0) {
                            let logHtml = '';
                            response.balance_log.forEach(log => {
                                const now = new Date().toLocaleTimeString('en-US', { hour12: false });
                                logHtml += `<div class="balance-item" style="background:#e8f5e9; border-left:4px solid #28a745;">
                                <i class="bi bi-arrow-left-right"></i> Transferred <strong>${parseFloat(log.amount).toFixed(2)}</strong> from <strong style="color:#28a745;">${log.from}</strong> to <strong style="color:#dc3545;">${log.to}</strong>
                                <span style="color:#999; font-size:11px; margin-left:10px;">(${now})</span>
                            </div>`;
                            });

                            $('#balanceItems').prepend(logHtml);
                            $('.balance-log').show();
                        }
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showNotification(xhr.responseJSON?.message || 'Auto balance failed', 'error');
                }
            });
        }

        function closeDailyClosing() {
            showConfirmation(
                'Close Daily Closing?',
                'Are you sure you want to close this daily closing? This action cannot be undone easily.',
                function() {
                    showLoading();

                    $.ajax({
                        url: "{{ route('dashboard.daily-closing.close') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            daily_closing_id: dailyClosingId
                        },
                        success: function(response) {
                            hideLoading();

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showNotification(xhr.responseJSON?.message || 'Cannot close daily closing!', 'error');
                        }
                    });
                }
            );
        }

        function reopenDailyClosing() {
            showConfirmation(
                'Reopen Daily Closing?',
                'Are you sure you want to reopen this daily closing? You will be able to make changes again.',
                function() {
                    showLoading();

                    $.ajax({
                        url: "{{ route('dashboard.daily-closing.reopen') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            daily_closing_id: dailyClosingId
                        },
                        success: function(response) {
                            hideLoading();

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Reopened!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showNotification(xhr.responseJSON?.message || 'Cannot reopen daily closing!', 'error');
                        }
                    });
                }
            );
        }

        function clearBalanceHistory() {
            showConfirmation(
                'Clear Balance History?',
                'Are you sure you want to clear all balance transfer logs? This cannot be undone.',
                function() {
                    showLoading();

                    $.ajax({
                        url: "{{ route('dashboard.daily-closing.clear-logs') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            daily_closing_id: dailyClosingId
                        },
                        success: function(response) {
                            hideLoading();

                            if (response.success) {
                                showNotification(response.message, 'success');
                                $('#balanceItems').html('<p style="color:#999; text-align:center; padding:20px;">No balance operations yet.</p>');
                                $('.balance-log').hide();
                            }
                        },
                        error: function(xhr) {
                            hideLoading();
                            showNotification(xhr.responseJSON?.message || 'Cannot clear balance history!', 'error');
                        }
                    });
                }
            );
        }

        $(document).ready(function() {
            // Initialize status for all payment types
            ['CASH', 'VISA', 'MASTERCARD', 'ATM'].forEach(type => {
                updateStatus(type);
            });
        });
    </script>

    <!-- Custom SweetAlert2 Styling -->
    <style>
        /* Toast Notifications Styling */
        .swal2-toast {
            border-radius: 12px !important;
            padding: 15px 20px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .swal2-toast .swal2-title {
            font-size: 16px !important;
            font-weight: 700 !important;
            margin: 0 !important;
        }

        .swal2-toast .swal2-html-container {
            margin: 5px 0 0 0 !important;
            font-size: 13px !important;
        }

        .swal2-toast.swal2-icon-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white !important;
        }

        .swal2-toast.swal2-icon-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            color: white !important;
        }

        .swal2-toast.swal2-icon-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
            color: white !important;
        }

        .swal2-toast.swal2-icon-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
            color: white !important;
        }

        .swal2-toast .swal2-icon {
            margin: 0 10px 0 0 !important;
        }

        .swal2-toast .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: white !important;
        }

        .swal2-toast .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(255,255,255,0.3) !important;
        }

        .swal2-toast .swal2-timer-progress-bar {
            background: rgba(255,255,255,0.5) !important;
        }

        /* Confirmation Dialog Styling */
        .swal2-popup {
            border-radius: 16px !important;
            padding: 30px !important;
        }

        .swal2-icon {
            margin: 20px auto !important;
        }

        .swal2-title {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #333 !important;
            margin: 20px 0 10px 0 !important;
        }

        .swal2-html-container {
            font-size: 16px !important;
            color: #666 !important;
            margin: 10px 0 20px 0 !important;
        }

        .swal2-actions {
            margin-top: 30px !important;
            gap: 15px !important;
        }

        .swal2-confirm.btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            padding: 12px 30px !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3) !important;
            transition: all 0.3s !important;
        }

        .swal2-confirm.btn-danger:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
        }

        .swal2-cancel.btn-secondary {
            background: white !important;
            color: #666 !important;
            border: 2px solid #ddd !important;
            padding: 12px 30px !important;
            border-radius: 8px !important;
            font-weight: 700 !important;
            transition: all 0.3s !important;
        }

        .swal2-cancel.btn-secondary:hover {
            background: #f8f9fa !important;
            border-color: #bbb !important;
            color: #333 !important;
            transform: translateY(-2px) !important;
        }
    </style>
@endsection
