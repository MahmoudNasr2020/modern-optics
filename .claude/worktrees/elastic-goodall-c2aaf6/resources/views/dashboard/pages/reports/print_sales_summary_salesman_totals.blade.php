<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary Per Salesman Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        /* Header */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #00bcd4;
        }

        .report-logo img {
            max-width: 120px;
            height: auto;
        }

        .report-title {
            text-align: right;
        }

        .report-title h1 {
            font-size: 28px;
            color: #00bcd4;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .report-title .subtitle {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title .date-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Info Header */
        .info-header {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #80deea;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #00bcd4;
            font-size: 16px;
            font-weight: 700;
        }

        .info-header .dates {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .info-header .branch {
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .summary-card {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 25px 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 5px solid #00bcd4;
            box-shadow: 0 3px 10px rgba(0, 188, 212, 0.15);
        }

        .summary-card .card-icon {
            font-size: 36px;
            color: #00bcd4;
            margin-bottom: 10px;
        }

        .summary-card .card-value {
            font-size: 28px;
            font-weight: 800;
            color: #0097a7;
            margin-bottom: 5px;
        }

        .summary-card .card-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        /* Main Table */
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
            padding: 20px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }

        .report-table thead th:nth-child(n+2) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 2px solid #e0f7fa;
            transition: all 0.3s;
        }

        .report-table tbody tr:hover:not(.grand-total-row) {
            background: #f0fdff;
            transform: scale(1.005);
        }

        .report-table tbody td {
            padding: 18px 15px;
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #00bcd4;
            font-size: 16px;
        }

        .report-table tbody td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        /* Percentage Column */
        .percentage-cell {
            position: relative;
        }

        .percentage-value {
            position: relative;
            z-index: 2;
            font-weight: 800;
            color: #0097a7;
        }

        .percentage-bar {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            /*background: linear-gradient(90deg, transparent 0%, #b2ebf2 100%);*/
            z-index: 1;
            border-radius: 4px;
        }

        /* Grand Total Row */
        .grand-total-row {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: white;
            font-weight: 800;
            border-top: 4px solid #006064;
        }

        .grand-total-row td {
            padding: 22px 15px;
            font-size: 18px;
            color: white !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .grand-total-row td:first-child {
            color: white !important;
        }

        /* Visual Chart Section */
        .chart-section {
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 10px;
            border: 3px solid #00bcd4;
        }

        .chart-title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #00bcd4;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .chart-title i {
            margin-right: 10px;
        }

        .chart-bar-item {
            margin-bottom: 20px;
        }

        .chart-bar-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 700;
            color: #0097a7;
        }

        .chart-bar-container {
            width: 100%;
            height: 30px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
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
            font-size: 13px;
            transition: width 1s ease;
            box-shadow: 0 2px 4px rgba(0, 188, 212, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #666;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            font-size: 14px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 188, 212, 0.5);
        }

        .print-button i {
            margin-right: 8px;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        .report-footer strong {
            color: #00bcd4;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .report-container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none !important;
            }

            .report-table tbody tr:hover {
                background: transparent;
                transform: none;
            }

            .chart-bar-fill {
                transition: none;
            }

            @page {
                margin: 1.5cm;
                size: portrait;
            }
        }
    </style>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
<div class="report-container">
    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
        </div>
        <div class="report-title">
            <h1>Sales Summary Per Salesman</h1>
            <div class="subtitle">Totals & Percentages Analysis</div>
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
                    {{ $date_from ?? 'N/A' }} <strong>to</strong> {{ $date_to ?? 'N/A' }}
                </span>
            </div>
            @if(isset($selectedBranch))
                <div>
                    <strong>Branch:</strong>
                    <span class="branch">
                        <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    @if($salesSummary->isNotEmpty())
        @php
            $grandBefore = $salesSummary->sum('total_before_discount');
            $grandAfter = $salesSummary->sum('total_after_discount');
            $totalDiscount = $grandBefore - $grandAfter;
            $salesmenCount = $salesSummary->count();
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
            <div class="summary-card">
                <div class="card-icon"><i class="bi bi-percent"></i></div>
                <div class="card-value">{{ number_format($totalDiscount, 0) }}</div>
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
                <th style="width: 250px;">SALESMAN</th>
                <th style="width: 180px;">BEFORE DISCOUNT</th>
                <th style="width: 120px;">%</th>
                <th style="width: 180px;">AFTER DISCOUNT</th>
                <th style="width: 120px;">%</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesSummary as $sale)
                @php
                    $percentBefore = $grandBefore > 0
                        ? ($sale->total_before_discount / $grandBefore) * 100
                        : 0;
                    $percentAfter = $grandAfter > 0
                        ? ($sale->total_after_discount / $grandAfter) * 100
                        : 0;
                @endphp

                <tr>
                    <td>{{ $sale->salesman ?? 'Unknown' }}</td>
                    <td>{{ number_format($sale->total_before_discount, 2) }} QAR</td>
                    <td class="percentage-cell">
                        <div class="percentage-bar" style="width: {{ $percentBefore }}%;"></div>
                        <span class="percentage-value">{{ number_format($percentBefore, 2) }}%</span>
                    </td>
                    <td>{{ number_format($sale->total_after_discount, 2) }} QAR</td>
                    <td class="percentage-cell">
                        <div class="percentage-bar" style="width: {{ $percentAfter }}%;"></div>
                        <span class="percentage-value">{{ number_format($percentAfter, 2) }}%</span>
                    </td>
                </tr>
            @endforeach

            <!-- Grand Total Row -->
            <tr class="grand-total-row">
                <td><i class="bi bi-trophy"></i> Grand Total</td>
                <td>{{ number_format($grandBefore, 2) }} QAR</td>
                <td>100.00%</td>
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
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Sales Data Found</h3>
            <p>There are no sales by salesmen for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Sales Summary Per Salesman Report<br>
            This is a system-generated report showing sales totals and performance percentages by salesman.<br>
            Report generated on {{ date('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

<!-- Print Button -->
<button type="button" class="print-button" onclick="printReport()">
    <i class="bi bi-printer-fill"></i> Print Report
</button>

<script>
    function printReport() {
        window.print();
    }
</script>
</body>
</html>
