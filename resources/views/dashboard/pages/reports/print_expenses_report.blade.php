<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Expenses Report — {{ $date_from }} to {{ $date_to }}</title>

    <link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333; background: #f5f5f5; padding: 20px;
        }

        .report-container {
            max-width: 1300px; margin: 0 auto; background: white;
            padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 8px;
        }

        /* ─── Report Header ─── */
        .report-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; padding-bottom: 20px;
            border-bottom: 3px solid #c0392b;
        }
        .report-logo img  { max-width: 120px; height: auto; }
        .report-title     { text-align: right; }
        .report-title h1  { font-size: 28px; color: #c0392b; margin-bottom: 5px; font-weight: 700; }
        .report-title .subtitle {
            font-size: 14px; color: #666; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .report-title .date-info { font-size: 14px; color: #666; margin-top: 5px; }

        /* ─── Info Header ─── */
        .info-header {
            background: linear-gradient(135deg, #fdf2f0 0%, #fadbd8 100%);
            padding: 15px 20px; border-radius: 8px;
            margin-bottom: 25px; border: 2px solid #f1948a;
        }
        .info-header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .info-header strong { color: #c0392b; font-size: 16px; font-weight: 700; }
        .info-header .dates  { font-size: 18px; color: #c0392b; font-weight: 600; }
        .info-header .branch { font-size: 16px; color: #555; font-weight: 600; }

        /* ─── Stats Row ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 15px; margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #fdf2f0 0%, #fadbd8 100%);
            padding: 20px; border-radius: 8px;
            text-align: center; border-left: 4px solid #c0392b;
        }
        .stat-box.orange { background: linear-gradient(135deg, #fef5e7 0%, #fad7a0 100%); border-left-color: #e67e22; }
        .stat-box.gray   { background: linear-gradient(135deg, #f2f3f4 0%, #d5d8dc 100%); border-left-color: #717d7e; }
        .stat-box.purple { background: linear-gradient(135deg, #f5eef8 0%, #c39bd3 100%); border-left-color: #7d3c98; }

        .stat-box .number {
            font-size: 26px; font-weight: 800; color: #c0392b; margin-bottom: 5px;
        }
        .stat-box.orange .number { color: #e67e22; }
        .stat-box.gray   .number { color: #4d5656; }
        .stat-box.purple .number { color: #7d3c98; }
        .stat-box .label {
            font-size: 12px; color: #666; text-transform: uppercase;
            letter-spacing: 0.5px; font-weight: 600;
        }

        /* ─── Section Title ─── */
        .section-title {
            font-size: 16px; font-weight: 700; color: #c0392b;
            margin: 30px 0 15px; padding-bottom: 8px;
            border-bottom: 2px solid #f1948a;
            display: flex; align-items: center; gap: 8px;
        }

        /* ─── ✅ NEW: Net Profit Panel ─── */
        .profit-panel {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            margin-bottom: 30px;
        }

        @media (max-width: 700px) {
            .profit-panel { grid-template-columns: 1fr; }
        }

        .profit-cell {
            padding: 28px 24px;
            text-align: center;
            position: relative;
        }
        .profit-cell.revenue {
            background: linear-gradient(135deg, #1a8a4c 0%, #27ae60 100%);
            color: white;
        }
        .profit-cell.expenses {
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
            color: white;
        }
        .profit-cell.net {
            color: white;
        }
        .profit-cell.net.positive {
            background: linear-gradient(135deg, #1a5276 0%, #2980b9 100%);
        }
        .profit-cell.net.negative {
            background: linear-gradient(135deg, #6c3483 0%, #8e44ad 100%);
        }

        .profit-cell .pc-icon {
            font-size: 28px; margin-bottom: 8px; display: block; opacity: 0.9;
        }
        .profit-cell .pc-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; opacity: 0.85; margin-bottom: 10px;
        }
        .profit-cell .pc-amount {
            font-size: 30px; font-weight: 900; line-height: 1;
        }
        .profit-cell .pc-sub {
            font-size: 12px; opacity: 0.75; margin-top: 6px;
        }
        .profit-cell .pc-badge {
            display: inline-block; margin-top: 10px;
            background: rgba(255,255,255,0.25); border-radius: 20px;
            padding: 3px 12px; font-size: 12px; font-weight: 700;
        }

        /* Arrow dividers between cells */
        .profit-cell::after {
            content: '';
            position: absolute; right: -1px; top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-style: solid;
            border-width: 20px 0 20px 18px;
            z-index: 2;
        }
        .profit-cell.revenue::after {
            border-color: transparent transparent transparent #27ae60;
        }
        .profit-cell.expenses::after {
            border-color: transparent transparent transparent #e74c3c;
        }
        .profit-cell.net::after { display: none; }

        /* ─── Ratio Bar ─── */
        .ratio-bar-wrap {
            background: #eaf9f0; border-radius: 10px; padding: 18px 24px;
            margin-bottom: 30px; border: 1px solid #a9dfbf;
        }
        .ratio-bar-label {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px; font-size: 13px; font-weight: 600; color: #555;
        }
        .ratio-bar-label span.pct { font-size: 18px; font-weight: 800; color: #c0392b; }
        .ratio-bar-track {
            height: 14px; background: #d5f5e3; border-radius: 10px; overflow: hidden;
        }
        .ratio-bar-fill {
            height: 100%; border-radius: 10px;
            background: linear-gradient(90deg, #c0392b, #e74c3c);
            transition: width 0.6s ease;
        }
        .ratio-bar-hint {
            margin-top: 8px; font-size: 12px; color: #888; text-align: right;
        }

        /* ─── Summary Cards (2 columns) ─── */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 800px) {
            .summary-grid { grid-template-columns: 1fr; }
        }

        .summary-card {
            background: white;
            border: 1px solid #f0e0de;
            border-radius: 8px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .summary-card-header {
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
            color: white; padding: 12px 18px;
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .summary-card table { width: 100%; border-collapse: collapse; }
        .summary-card table tr { border-bottom: 1px solid #fdf2f0; }
        .summary-card table tr:last-child { border-bottom: none; background: #fdf2f0; font-weight: 800; }
        .summary-card table td { padding: 10px 15px; font-size: 13px; }
        .summary-card table td:last-child { text-align: right; font-weight: 600; color: #c0392b; }
        .summary-card table td .progress-bar-wrap {
            background: #f8e0de; border-radius: 4px;
            height: 6px; margin-top: 5px; overflow: hidden;
        }
        .summary-card table td .progress-bar-fill {
            background: linear-gradient(135deg, #c0392b, #e74c3c);
            height: 100%; border-radius: 4px;
        }

        /* ─── Report Table ─── */
        .report-table {
            width: 100%; border-collapse: collapse; margin-bottom: 30px;
            background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 10px; overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
        }

        .report-table thead th {
            padding: 15px 12px; text-align: left; color: white;
            font-weight: 700; font-size: 12px; text-transform: uppercase;
            letter-spacing: 0.6px; border: none; white-space: nowrap;
        }

        .report-table tbody tr { border-bottom: 1px solid #e9ecef; transition: background 0.2s; }
        .report-table tbody tr:hover    { background: #fdf9f9; }
        .report-table tbody tr:last-child { border-bottom: none; }
        .report-table tbody td {
            padding: 13px 12px; font-size: 13px; color: #333; font-weight: 500;
        }
        .report-table tbody td:first-child {
            font-weight: 700; color: #c0392b; font-size: 14px;
        }

        /* ─── Payment Method Badge ─── */
        .pay-badge {
            display: inline-block; padding: 4px 10px;
            border-radius: 10px; font-size: 11px; font-weight: 700;
            text-transform: uppercase;
        }
        .pay-CASH         { background: #d5f5e3; color: #1e8449; }
        .pay-VISA         { background: #d6eaf8; color: #1a5276; }
        .pay-MASTERCARD   { background: #e8daef; color: #6c3483; }
        .pay-MADA         { background: #d0ece7; color: #1a5f47; }
        .pay-ATM          { background: #fdebd0; color: #784212; }
        .pay-BANK_TRANSFER{ background: #eaecee; color: #2c3e50; }
        .pay-OTHER        { background: #eaecee; color: #2c3e50; }

        /* ─── Grand Total row in tfoot ─── */
        .report-table tfoot { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%); }
        .report-table tfoot td {
            padding: 14px 12px; color: white;
            font-weight: 800; font-size: 13px; border: none;
        }
        .report-table tfoot td:last-child { text-align: right; font-size: 15px; }

        /* ─── Empty State ─── */
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i  { font-size: 64px; color: #ddd; margin-bottom: 20px; display: block; }
        .empty-state h3 { color: #666; font-size: 20px; margin-bottom: 10px; }
        .empty-state p  { color: #999; font-size: 14px; }

        /* ─── Print Button (fixed) ─── */
        .print-button {
            position: fixed; bottom: 30px; right: 30px;
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
            color: white; border: none; padding: 15px 30px;
            border-radius: 50px; font-size: 16px; font-weight: 700;
            cursor: pointer; box-shadow: 0 4px 15px rgba(192,57,43,0.4);
            transition: all 0.3s; z-index: 1000;
        }
        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(192,57,43,0.5);
        }
        .print-button i { margin-right: 8px; }

        /* ─── Footer ─── */
        .report-footer {
            margin-top: 40px; padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center; color: #999; font-size: 12px;
        }
        .report-footer strong { color: #c0392b; }

        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .report-table tbody tr:hover { background: white; }
            .summary-grid { grid-template-columns: 1fr 1fr; }
            .profit-panel { grid-template-columns: repeat(3, 1fr); }
            .profit-cell::after { display: none; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>

<body>
<div class="report-container">

    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern"
                 onerror="this.style.display='none'">
        </div>
        <div class="report-title">
            <h1>Expenses Report</h1>
            <div class="subtitle">Detailed Expenses — Categorized &amp; Summarized</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Report Period:</strong>
                <span class="dates">
                    <i class="bi bi-calendar-range"></i>
                    {{ $date_from }} <strong>to</strong> {{ $date_to }}
                </span>
            </div>
            @if($selectedBranch)
                <div>
                    <strong>Branch:</strong>
                    <span class="branch"><i class="bi bi-building"></i> {{ $selectedBranch->name }}</span>
                </div>
            @else
                <div>
                    <span class="branch"><i class="bi bi-buildings"></i> All Branches</span>
                </div>
            @endif
            @if($selectedCategory)
                <div>
                    <strong>Category:</strong>
                    <span class="branch"><i class="bi bi-tag"></i> {{ $selectedCategory->name }}</span>
                </div>
            @endif
            @if($paymentMethod)
                <div>
                    <strong>Payment:</strong>
                    <span class="branch"><i class="bi bi-credit-card"></i> {{ $paymentMethod }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $expenses->count() }}</div>
            <div class="label">Total Expenses</div>
        </div>
        <div class="stat-box orange">
            <div class="number">{{ number_format($grandTotal, 2) }}</div>
            <div class="label">Grand Total (QAR)</div>
        </div>
        <div class="stat-box gray">
            <div class="number">{{ $byCategory->count() }}</div>
            <div class="label">Categories Used</div>
        </div>
        <div class="stat-box purple">
            <div class="number">{{ $byPayment->count() }}</div>
            <div class="label">Payment Methods</div>
        </div>
        @if(!$filterBranchId)
            <div class="stat-box gray">
                <div class="number">{{ $byBranch->count() }}</div>
                <div class="label">Branches</div>
            </div>
        @endif
        @if($expenses->count() > 0)
            <div class="stat-box gray">
                <div class="number">{{ number_format($grandTotal / $expenses->count(), 2) }}</div>
                <div class="label">Avg per Expense</div>
            </div>
        @endif
    </div>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- ✅ NET PROFIT SECTION                                   --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div class="section-title">
        <i class="bi bi-graph-up-arrow"></i> Profit & Expenses Overview
    </div>

    <!-- Three-cell panel: Revenue → Expenses → Net Profit -->
    <div class="profit-panel">

        {{-- Cell 1: Revenue --}}
        <div class="profit-cell revenue">
            <span class="pc-icon"><i class="bi bi-cash-coin"></i></span>
            <div class="pc-label">Total Revenue (Sales)</div>
            <div class="pc-amount">{{ number_format($totalRevenue, 2) }}</div>
            <div class="pc-sub">QAR &mdash; {{ $totalSalesCount }} delivered invoice(s)</div>
        </div>

        {{-- Cell 2: Expenses --}}
        <div class="profit-cell expenses">
            <span class="pc-icon"><i class="bi bi-receipt-cutoff"></i></span>
            <div class="pc-label">Total Expenses</div>
            <div class="pc-amount">{{ number_format($grandTotal, 2) }}</div>
            <div class="pc-sub">QAR &mdash; {{ $expenses->count() }} expense(s)</div>
            <div class="pc-badge">{{ $expensesRatio }}% of revenue</div>
        </div>

        {{-- Cell 3: Net Profit --}}
        <div class="profit-cell net {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
            <span class="pc-icon">
                <i class="bi bi-{{ $netProfit >= 0 ? 'trending-up' : 'trending-down' }}"></i>
            </span>
            <div class="pc-label">Net Profit After Expenses</div>
            <div class="pc-amount">
                {{ $netProfit >= 0 ? '' : '−' }}{{ number_format(abs($netProfit), 2) }}
            </div>
            <div class="pc-sub">QAR</div>
            <div class="pc-badge">
                @if($totalRevenue > 0)
                    {{ round((abs($netProfit) / $totalRevenue) * 100, 1) }}%
                    {{ $netProfit >= 0 ? 'profit margin' : 'loss' }}
                @else
                    No revenue this period
                @endif
            </div>
        </div>
    </div>

    <!-- Expenses-to-Revenue ratio bar -->
    @if($totalRevenue > 0)
        <div class="ratio-bar-wrap">
            <div class="ratio-bar-label">
            <span><i class="bi bi-bar-chart-fill" style="color:#c0392b;"></i>
                &nbsp;Expenses consume <span class="pct">{{ $expensesRatio }}%</span> of revenue
            </span>
                <span style="font-size:12px; color:#888;">
                {{ number_format($grandTotal, 2) }} QAR out of {{ number_format($totalRevenue, 2) }} QAR
            </span>
            </div>
            <div class="ratio-bar-track">
                <div class="ratio-bar-fill" style="width: {{ min($expensesRatio, 100) }}%;"></div>
            </div>
            <div class="ratio-bar-hint">
                @if($expensesRatio <= 20)
                    <i class="bi bi-emoji-smile" style="color:#27ae60;"></i> Excellent — expenses are well controlled.
                @elseif($expensesRatio <= 40)
                    <i class="bi bi-emoji-neutral" style="color:#e67e22;"></i> Good — monitor expenses closely.
                @else
                    <i class="bi bi-emoji-frown" style="color:#c0392b;"></i> High — expenses are a significant portion of revenue.
                @endif
            </div>
        </div>
    @endif

    @if($expenses->count() > 0)

        <!-- Summary Grids -->
        <div class="section-title">
            <i class="bi bi-bar-chart-line"></i> Summary Breakdown
        </div>

        <div class="summary-grid">

            <!-- By Category -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <i class="bi bi-tags"></i>&nbsp; By Category
                </div>
                <table>
                    @foreach($byCategory as $catName => $data)
                        <tr>
                            <td>
                                {{ $catName }}
                                <div class="progress-bar-wrap">
                                    <div class="progress-bar-fill"
                                         style="width:{{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%">
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $data['count'] }} &bull;
                                {{ number_format($data['total'], 2) }} QAR
                                <small style="color:#aaa;">({{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%)</small>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td>{{ $expenses->count() }} &bull; {{ number_format($grandTotal, 2) }} QAR</td>
                    </tr>
                </table>
            </div>

            <!-- By Payment Method -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <i class="bi bi-credit-card"></i>&nbsp; By Payment Method
                </div>
                <table>
                    @foreach($byPayment as $method => $data)
                        <tr>
                            <td>
                                <span class="pay-badge pay-{{ $method }}">{{ $method }}</span>
                                <div class="progress-bar-wrap">
                                    <div class="progress-bar-fill"
                                         style="width:{{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%">
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $data['count'] }} &bull;
                                {{ number_format($data['total'], 2) }} QAR
                                <small style="color:#aaa;">({{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%)</small>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td>{{ $expenses->count() }} &bull; {{ number_format($grandTotal, 2) }} QAR</td>
                    </tr>
                </table>
            </div>

            @if(!$filterBranchId && $byBranch->count() > 1)
                <!-- By Branch -->
                <div class="summary-card">
                    <div class="summary-card-header">
                        <i class="bi bi-building"></i>&nbsp; By Branch
                    </div>
                    <table>
                        @foreach($byBranch as $branchName => $data)
                            <tr>
                                <td>
                                    {{ $branchName }}
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar-fill"
                                             style="width:{{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $data['count'] }} &bull;
                                    {{ number_format($data['total'], 2) }} QAR
                                    <small style="color:#aaa;">({{ $grandTotal > 0 ? round(($data['total']/$grandTotal)*100) : 0 }}%)</small>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td>{{ $expenses->count() }} &bull; {{ number_format($grandTotal, 2) }} QAR</td>
                        </tr>
                    </table>
                </div>
            @endif

        </div>

        <!-- Detailed Table -->
        <div class="section-title">
            <i class="bi bi-table"></i> Expense Details
        </div>

        <table class="report-table">
            <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th style="width:100px;">Date</th>
                <th>Description</th>
                <th style="width:140px;">Category</th>
                @if(!$filterBranchId)
                    <th style="width:120px;">Branch</th>
                @endif
                <th style="width:110px;">Vendor</th>
                <th style="width:90px;">Receipt #</th>
                <th style="width:120px;">Payment</th>
                <th style="width:90px;">Paid By</th>
                <th style="width:110px; text-align:right;">Amount (QAR)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expenses as $index => $expense)
                <tr>
                    <td style="color:#aaa; font-size:11px; font-weight:500;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                    <td>
                        {{ $expense->description }}
                        @if($expense->notes)
                            <br><small style="color:#aaa;">{{ $expense->notes }}</small>
                        @endif
                    </td>
                    <td>
                    <span style="font-size:12px; color:#7d3c98; font-weight:600;">
                        {{ $expense->category->name ?? '—' }}
                    </span>
                        @if($expense->category)
                            <br><small style="color:#aaa;">{{ ucfirst($expense->category->type ?? '') }}</small>
                        @endif
                    </td>
                    @if(!$filterBranchId)
                        <td>{{ $expense->branch->name ?? '—' }}</td>
                    @endif
                    <td>{{ $expense->vendor_name ?? '—' }}</td>
                    <td style="font-size:12px;">{{ $expense->receipt_number ?? '—' }}</td>
                    <td>
                    <span class="pay-badge pay-{{ strtoupper($expense->payment_method ?? 'OTHER') }}">
                        {{ $expense->payment_method ?? '—' }}
                    </span>
                    </td>
                    <td style="font-size:12px;">{{ $expense->paidBy->first_name ?? '—' }}</td>
                    <td style="text-align:right; font-weight:700; color:#c0392b; font-size:13px;">
                        {{ number_format($expense->amount, 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="{{ $filterBranchId ? 8 : 9 }}">
                    <i class="bi bi-calculator"></i> GRAND TOTAL — {{ $expenses->count() }} expense(s)
                </td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
            </tfoot>
        </table>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Expenses Found</h3>
            <p>No expenses found for the selected date range and filters.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> — Expenses Report<br>
            Filtered by date {{ $date_from }} to {{ $date_to }}
            @if($selectedBranch) | Branch: {{ $selectedBranch->name }} @endif
            @if($selectedCategory) | Category: {{ $selectedCategory->name }} @endif
            @if($paymentMethod) | Payment: {{ $paymentMethod }} @endif
            <br>Report generated on {{ date('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'expenses-report'])
</body>
</html>
