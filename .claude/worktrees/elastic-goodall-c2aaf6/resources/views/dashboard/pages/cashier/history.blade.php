@extends('dashboard.layouts.master')

@section('title', 'Cashier History')

@section('content')

    <style>
        .history-page {
            padding: 20px;
        }

        /* Stats Cards - Beautiful Design */
        .stats-cards {
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 50px;
            opacity: 0.15;
        }

        .stat-card .stat-content {
            position: relative;
            z-index: 1;
        }

        .stat-card .stat-content h3 {
            margin: 0 0 5px 0;
            font-size: 28px;
            font-weight: 800;
        }

        .stat-card .stat-content p {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        .stat-card .currency {
            display: inline-block;
            margin-top: 5px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            background: rgba(255,255,255,0.3);
        }

        .stat-card .stat-corner {
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.1;
        }

        /* Individual Card Colors */
        .transactions-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .transactions-card .stat-corner {
            background: white;
        }

        .sales-card {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .sales-card .stat-corner {
            background: white;
        }

        .refunds-card {
            background: linear-gradient(135deg, #e74c3c 0%, #c82333 100%);
            color: white;
        }

        .refunds-card .stat-corner {
            background: white;
        }

        .expenses-card {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .expenses-card .stat-corner {
            background: white;
        }

        .net-card {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .net-card .stat-corner {
            background: white;
        }

        /* Box Styles */
        .box-history {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }

        .box-history .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-history .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-history .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
        }

        .filter-section label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .filter-section .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            transition: all 0.3s;
        }

        .filter-section .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 6px 12px !important;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Transaction Items */
        .transaction-item {
            background: #fff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .transaction-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }

        .transaction-item.refund {
            border-left-color: #e74c3c;
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        }

        .transaction-item.sale {
            border-left-color: #27ae60;
            background: linear-gradient(135deg, #f0fdf4 0%, #fff 100%);
        }

        .transaction-item.expense {
            border-left-color: #f39c12;
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }

        .transaction-type {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .transaction-type.sale {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .transaction-type.refund {
            background: linear-gradient(135deg, #e74c3c 0%, #c82333 100%);
            color: white;
        }

        .transaction-type.expense {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .transaction-amount {
            font-size: 26px;
            font-weight: bold;
        }

        .transaction-amount.positive {
            color: #27ae60;
        }

        .transaction-amount.negative {
            color: #e74c3c;
        }

        .transaction-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
        }

        .detail-value {
            color: #333;
            font-size: 13px;
        }

        .payment-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .payment-badge.cash {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .payment-badge.visa {
            background: linear-gradient(135deg, #1a1f71 0%, #141a5e 100%);
            color: white;
        }

        .payment-badge.mastercard {
            background: linear-gradient(135deg, #eb001b 0%, #c70016 100%);
            color: white;
        }

        .payment-badge.mada {
            background: linear-gradient(135deg, #00a651 0%, #008a44 100%);
            color: white;
        }

        .payment-badge.atm {
            background: linear-gradient(135deg, #5dade2 0%, #3498db 100%);
            color: white;
        }

        /* Empty State */
        .no-transactions {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }

        .no-transactions i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
            color: #dee2e6;
        }

        .no-transactions h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .no-transactions p {
            font-size: 14px;
        }

        /* Payment Stats - Beautiful Cards */
        .payment-stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-top: 4px solid #667eea;
        }

        .payment-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .payment-stat-card .payment-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .payment-stat-card .payment-type {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payment-stat-card .payment-amount {
            font-size: 28px;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 5px;
        }

        .payment-stat-card .payment-currency {
            font-size: 14px;
            color: #999;
            font-weight: 600;
        }

        .payment-stat-card .payment-count {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 12px;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecf7 100%);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #667eea;
        }

        .payment-stat-card .payment-wave {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0.05;
        }

        /* Individual Payment Card Colors */
        .payment-stat-card.cash-card {
            border-top-color: #f39c12;
        }

        .payment-stat-card.cash-card .payment-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .payment-stat-card.cash-card .payment-amount {
            color: #f39c12;
        }

        .payment-stat-card.cash-card .payment-count {
            color: #f39c12;
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        }

        .payment-stat-card.visa-card {
            border-top-color: #1a1f71;
        }

        .payment-stat-card.visa-card .payment-icon {
            background: linear-gradient(135deg, #1a1f71 0%, #141a5e 100%);
        }

        .payment-stat-card.visa-card .payment-amount {
            color: #1a1f71;
        }

        .payment-stat-card.visa-card .payment-count {
            color: #1a1f71;
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
        }

        .payment-stat-card.mastercard-card {
            border-top-color: #eb001b;
        }

        .payment-stat-card.mastercard-card .payment-icon {
            background: linear-gradient(135deg, #eb001b 0%, #c70016 100%);
        }

        .payment-stat-card.mastercard-card .payment-amount {
            color: #eb001b;
        }

        .payment-stat-card.mastercard-card .payment-count {
            color: #eb001b;
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        .payment-stat-card.mada-card {
            border-top-color: #00a651;
        }

        .payment-stat-card.mada-card .payment-icon {
            background: linear-gradient(135deg, #00a651 0%, #008a44 100%);
        }

        .payment-stat-card.mada-card .payment-amount {
            color: #00a651;
        }

        .payment-stat-card.mada-card .payment-count {
            color: #00a651;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .payment-stat-card.atm-card {
            border-top-color: #5dade2;
        }

        .payment-stat-card.atm-card .payment-icon {
            background: linear-gradient(135deg, #5dade2 0%, #3498db 100%);
        }

        .payment-stat-card.atm-card .payment-amount {
            color: #5dade2;
        }

        .payment-stat-card.atm-card .payment-count {
            color: #5dade2;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }

            .stat-card .stat-content h3 {
                font-size: 22px;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-clock-history"></i> Cashier History
            <small>Transaction records</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Cashier History</li>
        </ol>
    </section>

    <div class="history-page">
        <!-- Statistics Cards -->
        <div class="row stats-cards">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card transactions-card">
                    <div class="stat-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $stats['transactions_count'] }}</h3>
                        <p>Total Transactions</p>
                    </div>
                    <div class="stat-corner"></div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stat-card sales-card">
                    <div class="stat-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($stats['total_sales'], 2) }}</h3>
                        <p>Total Sales</p>
                        <span class="currency">QAR</span>
                    </div>
                    <div class="stat-corner"></div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stat-card refunds-card">
                    <div class="stat-icon">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($stats['total_refunds'], 2) }}</h3>
                        <p>Total Refunds</p>
                        <span class="currency">QAR</span>
                    </div>
                    <div class="stat-corner"></div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stat-card expenses-card">
                    <div class="stat-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($stats['total_expenses'], 2) }}</h3>
                        <p>Total Expenses</p>
                        <span class="currency">QAR</span>
                    </div>
                    <div class="stat-corner"></div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card net-card">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($stats['net_total'], 2) }}</h3>
                        <p>Net Total</p>
                        <span class="currency">QAR</span>
                    </div>
                    <div class="stat-corner"></div>
                </div>
            </div>
        </div>

        <!-- Main Box -->
        <div class="box box-primary box-history">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> Transaction History
                    @if($selectedBranch)
                        - {{ $selectedBranch->name }}
                    @endif
                </h3>
            </div>

            <div class="box-body" style="padding: 25px;">
                <!-- Filters -->
                <div class="filter-section">
                    <form method="GET" action="{{ route('dashboard.cashier-history') }}">
                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <i class="bi bi-building"></i> Branch
                                            <span style="color: #e74c3c;">*</span>
                                        </label>
                                        <select name="branch_id" class="form-control">
                                            <option value="">All Branches</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                            @endif

                            <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '2' : '3' }}">
                                <div class="form-group">
                                    <label><i class="bi bi-calendar3"></i> Date</label>
                                    <input type="date"
                                           name="date"
                                           class="form-control"
                                           value="{{ $date }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="bi bi-person"></i> Cashier</label>
                                    <select name="cashier_id" class="form-control">
                                        <option value="">All Cashiers</option>
                                        @foreach($cashiers as $cashier)
                                            <option value="{{ $cashier->id }}"
                                                {{ $cashierId == $cashier->id ? 'selected' : '' }}>
                                                {{ $cashier->first_name }} {{ $cashier->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="bi bi-tag"></i> Type</label>
                                    <select name="transaction_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="sale" {{ $transactionType == 'sale' ? 'selected' : '' }}>Sale</option>
                                        <option value="refund" {{ $transactionType == 'refund' ? 'selected' : '' }}>Refund</option>
                                        <option value="expense" {{ $transactionType == 'expense' ? 'selected' : '' }}>Expense</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="bi bi-credit-card"></i> Payment</label>
                                    <select name="payment_type" class="form-control">
                                        <option value="">All Payments</option>
                                        <option value="cash" {{ $paymentType == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="visa" {{ $paymentType == 'visa' ? 'selected' : '' }}>Visa</option>
                                        <option value="mastercard" {{ $paymentType == 'mastercard' ? 'selected' : '' }}>Mastercard</option>
                                        <option value="atm" {{ $paymentType == 'atm' ? 'selected' : '' }}>ATM</option>
                                        <option value="mada" {{ $paymentType == 'mada' ? 'selected' : '' }}>Mada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-filter btn-block">
                                        <i class="bi bi-funnel"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Transactions List -->
                <div class="transactions-list">
                    @if($transactions->isEmpty())
                        <div class="no-transactions">
                            <i class="bi bi-inbox"></i>
                            <h4>No transactions found</h4>
                            <p>Try adjusting your filters or select a different date</p>
                        </div>
                    @else
                        @foreach($transactions as $transaction)
                            <div class="transaction-item {{ $transaction->transaction_type }}">
                                <div class="transaction-header">
                                    <div>
                                        <span class="transaction-type {{ $transaction->transaction_type }}">
                                            @if($transaction->transaction_type == 'sale')
                                                <i class="bi bi-cart-check"></i> Sale
                                            @elseif($transaction->transaction_type == 'expense')
                                                <i class="bi bi-wallet2"></i> Expense
                                            @else
                                                <i class="bi bi-arrow-return-left"></i> Refund
                                            @endif
                                        </span>

                                        <span class="payment-badge {{ strtolower($transaction->payment_type) }}">
                                            {{ strtoupper($transaction->payment_type) }}
                                        </span>
                                    </div>

                                    <div class="transaction-amount {{ $transaction->amount >= 0 ? 'positive' : 'negative' }}">
                                        {{ number_format(abs($transaction->amount), 2) }} QAR
                                    </div>
                                </div>

                                <div class="transaction-details">
                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-file-text"></i> Invoice:
                                        </span>
                                        <span class="detail-value">
                                            @if($transaction->invoice)
                                                <a href="{{ route('dashboard.edit-invoice', $transaction->invoice->invoice_code) }}" target="_blank">
                                                    #{{ $transaction->invoice->invoice_code }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-person"></i> Customer:
                                        </span>
                                        <span class="detail-value">
                                            {{ optional($transaction->customer)->english_name ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-person-circle"></i> Cashier:
                                        </span>
                                        <span class="detail-value">
                                            {{ $transaction->cashier->first_name ?? '-' }} {{ $transaction->cashier->last_name ?? '' }}
                                        </span>
                                    </div>

                                    @if(auth()->user()->canSeeAllBranches())
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="bi bi-building"></i> Branch:
                                            </span>
                                            <span class="detail-value">
                                                {{ optional($transaction->branch)->name ?? '-' }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="detail-item">
                                        <span class="detail-label">
                                            <i class="bi bi-clock"></i> Time:
                                        </span>
                                        <span class="detail-value">
                                            {{ $transaction->transaction_date->format('h:i A') }}
                                        </span>
                                    </div>

                                    @if($transaction->bank)
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="bi bi-bank"></i> Bank:
                                            </span>
                                            <span class="detail-value">
                                                {{ $transaction->bank }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($transaction->card_number)
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="bi bi-credit-card"></i> Card:
                                            </span>
                                            <span class="detail-value">
                                                ****{{ substr($transaction->card_number, -4) }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($transaction->notes)
                                        <div class="detail-item" style="grid-column: 1 / -1;">
                                            <span class="detail-label">
                                                <i class="bi bi-sticky"></i> Notes:
                                            </span>
                                            <span class="detail-value">
                                                {{ $transaction->notes }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Methods Breakdown - Beautiful Cards -->
        @if($paymentStats->isNotEmpty())
            <div class="box box-success box-history">
                <div class="box-header with-border" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                    <h3 class="box-title" style="color: white;">
                        <i class="bi bi-pie-chart-fill"></i> Payment Methods Breakdown
                    </h3>
                </div>
                <div class="box-body" style="padding: 30px;">
                    <div class="row">
                        @foreach($paymentStats as $type => $data)
                            @php
                                $cardClass = strtolower($type) . '-card';
                                $icons = [
                                    'cash' => 'bi-cash-stack',
                                    'visa' => 'bi-credit-card-2-front',
                                    'mastercard' => 'bi-credit-card',
                                    'mada' => 'bi-credit-card-fill',
                                    'atm' => 'bi-bank',
                                ];
                                $icon = $icons[strtolower($type)] ?? 'bi-credit-card';
                            @endphp
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="payment-stat-card {{ $cardClass }}">
                                    <div class="payment-icon">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <div class="payment-type">{{ strtoupper($type) }}</div>
                                    <div class="payment-amount">{{ number_format($data['total'], 2) }}</div>
                                    <div class="payment-currency">QAR</div>
                                    <div class="payment-count">
                                        <i class="bi bi-receipt"></i> {{ $data['count'] }} transactions
                                    </div>
                                    <div class="payment-wave"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
    <script>
        // Auto-submit on date change
        $('input[name="date"]').on('change', function() {
            $(this).closest('form').submit();
        });
    </script>
@endsection
