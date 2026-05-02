<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary Per Salesman Report</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #e0f7fa;
            padding: 24px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 188, 212, 0.15);
            border-radius: 12px;
        }

        /* ─── Header ─── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 3px solid #00bcd4;
        }

        .report-logo img { max-width: 120px; height: auto; }

        .report-title h1 {
            font-size: 28px;
            color: #00bcd4;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .report-title .subtitle {
            font-size: 12px;
            color: #00bcd4;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .report-title .date-info {
            font-size: 13px;
            color: #999;
            margin-top: 6px;
        }

        /* ─── Info Header ─── */
        .info-header {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            border: 2px solid #80deea;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #00bcd4;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 2px;
        }

        .info-header .dates,
        .info-header .branch {
            font-size: 16px;
            color: #006064;
            font-weight: 600;
        }

        /* ─── Summary Cards ─── */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .summary-card {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 22px 16px;
            border-radius: 12px;
            text-align: center;
            border-left: 5px solid #00bcd4;
            box-shadow: 0 3px 12px rgba(0, 188, 212, 0.15);
            transition: transform 0.2s;
        }

        .summary-card:hover { transform: translateY(-3px); }

        .summary-card .card-icon {
            font-size: 32px;
            color: #00bcd4;
            margin-bottom: 8px;
        }

        .summary-card .card-value {
            font-size: 26px;
            font-weight: 800;
            color: #0097a7;
            margin-bottom: 4px;
        }

        .summary-card .card-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        /* discount card accent */
        .summary-card.discount-card {
            border-left-color: #ef5350;
        }

        .summary-card.discount-card .card-icon,
        .summary-card.discount-card .card-value {
            color: #c62828;
        }

        /* ─── Main Table ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
        }

        .report-table thead th {
            padding: 18px 14px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        .report-table thead th:nth-child(n+2) { text-align: right; }

        /* discount column header accent */
        .report-table thead th.discount-col {
            background: rgba(0,0,0,0.15);
        }

        .report-table tbody tr {
            border-bottom: 2px solid #e0f7fa;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover:not(.grand-total-row) { background: #f0fdff; }

        .report-table tbody td {
            padding: 16px 14px;
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #00bcd4;
            font-size: 15px;
        }

        .report-table tbody td:nth-child(n+2) {
            text-align: right;
            font-weight: 600;
        }

        /* discount cells */
        .discount-cell {
            background: #fff8f8;
        }

        .discount-amount {
            color: #c62828;
            font-weight: 700;
        }

        .discount-pct {
            color: #e53935;
            font-size: 12px;
            font-weight: 600;
        }

        /* percentage cells */
        .percentage-cell { position: relative; }

        .percentage-value {
            font-weight: 800;
            color: #0097a7;
        }

        /* ─── Grand Total Row ─── */
        .grand-total-row {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            font-weight: 800;
            border-top: 4px solid #006064;
        }

        .grand-total-row td {
            padding: 20px 14px;
            font-size: 16px;
            color: white !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .grand-total-row td.discount-cell {
            background: rgba(0,0,0,0.15);
        }

        .grand-total-row .discount-amount,
        .grand-total-row .discount-pct {
            color: #ffcdd2 !important;
        }

        /* ─── Chart Section ─── */
        .chart-section {
            margin-top: 36px;
            padding: 28px;
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 12px;
            border: 2px solid #00bcd4;
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.15);
        }

        .chart-title {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            color: #00bcd4;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .chart-bar-item { margin-bottom: 18px; }

        .chart-bar-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            font-weight: 700;
            color: #0097a7;
            font-size: 13px;
        }

        .chart-bar-container {
            width: 100%;
            height: 28px;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.08);
        }

        .chart-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #00bcd4 0%, #0097a7 100%);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 12px;
            color: white;
            font-weight: 800;
            font-size: 12px;
            transition: width 1s ease;
            box-shadow: 0 2px 4px rgba(0, 188, 212, 0.4);
            min-width: 45px;
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #bbb;
        }

        .empty-state i { font-size: 72px; margin-bottom: 20px; display: block; color: #ddd; }
        .empty-state h3 { color: #888; font-size: 20px; margin-bottom: 8px; }

        /* ─── Print Button ─── */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 188, 212, 0.45);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0, 188, 212, 0.55);
        }

        /* ─── Footer ─── */
        .report-footer {
            margin-top: 40px;
            padding-top: 18px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #aaa;
            font-size: 12px;
            line-height: 1.8;
        }

        .report-footer strong { color: #00bcd4; }

        /* ─── Print ─── */
        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .report-table tbody tr:hover { background: transparent; }
            @page { margin: 1.5cm; size: portrait; }
        }
    </style>

    <link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
<div class="report-container">

    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
        </div>
        <div class="report-title">
            <h1><i class="bi bi-people-fill" style="font-size:24px;vertical-align:middle;margin-right:8px;"></i>Sales Per Salesman</h1>
            <div class="subtitle">Totals, Discounts &amp; Percentages Analysis</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Report Period</strong>
                <span class="dates">
                    <i class="bi bi-calendar-range"></i>
                    {{ $date_from ?? 'N/A' }} &nbsp;→&nbsp; {{ $date_to ?? 'N/A' }}
                </span>
            </div>
            @if(isset($selectedBranch))
                <div>
                    <strong>Branch</strong>
                    <span class="branch">
                    <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                </span>
                </div>
            @endif
        </div>
    </div>

    @if($salesSummary->isNotEmpty())
        @php
            $grandBefore      = $salesSummary->sum('total_before_discount');
            $grandAfter       = $salesSummary->sum('total_after_discount');
            $grandDiscount    = $grandBefore - $grandAfter;
            $grandDiscountPct = $grandBefore > 0 ? ($grandDiscount / $grandBefore) * 100 : 0;
            $salesmenCount    = $salesSummary->count();
        @endphp

            <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                <div class="card-value">{{ $salesmenCount }}</div>
                <div class="card-label">Salesmen</div>
            </div>
            <div class="summary-card">
                <div class="card-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="card-value">{{ number_format($grandBefore, 0) }}</div>
                <div class="card-label">Before Discount (QAR)</div>
            </div>
            <div class="summary-card discount-card">
                <div class="card-icon"><i class="bi bi-scissors"></i></div>
                <div class="card-value">{{ number_format($grandDiscount, 0) }}</div>
                <div class="card-label">Total Discount (QAR)</div>
            </div>
            <div class="summary-card">
                <div class="card-icon"><i class="bi bi-calculator"></i></div>
                <div class="card-value">{{ number_format($grandAfter, 0) }}</div>
                <div class="card-label">After Discount (QAR)</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width:200px;">Salesman</th>
                <th style="width:150px;">Before Discount</th>
                <th style="width:90px;">%</th>
                <th class="discount-col" style="width:190px;">Discount (% of Total Sales)</th>
                <th style="width:150px;">After Discount</th>
                <th style="width:90px;">%</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesSummary as $sale)
                @php
                    $discount    = $sale->total_before_discount - $sale->total_after_discount;
                    $discountPct = $grandBefore > 0
                                    ? ($discount / $grandBefore) * 100
                                    : 0;
                    $pctBefore   = $grandBefore > 0
                                    ? ($sale->total_before_discount / $grandBefore) * 100
                                    : 0;
                    $pctAfter    = $grandAfter > 0
                                    ? ($sale->total_after_discount / $grandAfter) * 100
                                    : 0;
                @endphp
                <tr>
                    <td>{{ $sale->salesman ?? 'Unknown' }}</td>
                    <td>{{ number_format($sale->total_before_discount, 2) }} QAR</td>
                    <td class="percentage-cell">
                        <span class="percentage-value">{{ number_format($pctBefore, 2) }}%</span>
                    </td>
                    <td class="discount-cell">
                        <span class="discount-amount">{{ number_format($discount, 2) }} QAR</span>
                        <span class="discount-pct">&nbsp;({{ number_format($discountPct, 2) }}%)</span>
                    </td>
                    <td>{{ number_format($sale->total_after_discount, 2) }} QAR</td>
                    <td class="percentage-cell">
                        <span class="percentage-value">{{ number_format($pctAfter, 2) }}%</span>
                    </td>
                </tr>
            @endforeach

            <!-- Grand Total -->
            <tr class="grand-total-row">
                <td><i class="bi bi-trophy"></i> Grand Total</td>
                <td>{{ number_format($grandBefore, 2) }} QAR</td>
                <td>100.00%</td>
                <td class="discount-cell">
                    <span class="discount-amount">{{ number_format($grandDiscount, 2) }} QAR</span>
                    <span class="discount-pct">&nbsp;({{ number_format($grandDiscountPct, 2) }}%)</span>
                </td>
                <td>{{ number_format($grandAfter, 2) }} QAR</td>
                <td>100.00%</td>
            </tr>
            </tbody>
        </table>

        <!-- Visual Chart -->
        <div class="chart-section">
            <div class="chart-title">
                <i class="bi bi-bar-chart-fill"></i> Sales Performance Chart
            </div>

            @foreach($salesSummary->sortByDesc('total_after_discount') as $sale)
                @php
                    $percent = $grandAfter > 0
                        ? ($sale->total_after_discount / $grandAfter) * 100
                        : 0;
                @endphp
                <div class="chart-bar-item">
                    <div class="chart-bar-label">
                        <span>{{ $sale->salesman ?? 'Unknown' }}</span>
                        <span>{{ number_format($sale->total_after_discount, 2) }} QAR</span>
                    </div>
                    <div class="chart-bar-container">
                        <div class="chart-bar-fill" style="width: {{ $percent }}%;">
                            {{ number_format($percent, 1) }}%
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Sales Data Found</h3>
            <p>There are no sales by salesmen for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <strong>Optics Modern</strong> — Sales Summary Per Salesman Report<br>
        System-generated report · Sales totals, discounts, and performance percentages by salesman<br>
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'sales-summary-per-salesman-report'])
</body>
</html>
