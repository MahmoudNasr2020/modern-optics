<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Cashier Report — {{ $date_from }} to {{ $date_to }}</title>

    <link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333; background: #f5f5f5; padding: 20px;
        }

        .report-container {
            max-width: 1400px; margin: 0 auto; background: white;
            padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 8px;
        }

        /* ─── Report Header ─── */
        .report-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; padding-bottom: 20px;
            border-bottom: 3px solid #1a5276;
        }
        .report-logo img { max-width: 120px; height: auto; }
        .report-title    { text-align: right; }
        .report-title h1 {
            font-size: 28px; color: #1a5276;
            margin-bottom: 5px; font-weight: 700;
        }
        .report-title .subtitle {
            font-size: 14px; color: #666; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .report-title .date-info { font-size: 14px; color: #666; margin-top: 5px; }

        /* ─── Info Header ─── */
        .info-header {
            background: linear-gradient(135deg, #eaf4fb 0%, #aed6f1 100%);
            padding: 15px 20px; border-radius: 8px;
            margin-bottom: 25px; border: 2px solid #85c1e9;
        }
        .info-header-content {
            display: flex; justify-content: space-between; align-items: center;
        }
        .info-header strong { color: #1a5276; font-size: 16px; font-weight: 700; }
        .info-header .dates  { font-size: 18px; color: #1a5276; font-weight: 600; }
        .info-header .branch { font-size: 16px; color: #555; font-weight: 600; }

        /* ─── Stats Row ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px; margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #eaf4fb 0%, #aed6f1 100%);
            padding: 20px; border-radius: 8px;
            text-align: center; border-left: 4px solid #1a5276;
        }
        .stat-box.green  { background: linear-gradient(135deg, #eafaf1 0%, #a9dfbf 100%); border-left-color: #1e8449; }
        .stat-box.red    { background: linear-gradient(135deg, #fdedec 0%, #f1948a 100%); border-left-color: #c0392b; }
        .stat-box.purple { background: linear-gradient(135deg, #f5eef8 0%, #c39bd3 100%); border-left-color: #7d3c98; }
        .stat-box.gray   { background: linear-gradient(135deg, #f2f3f4 0%, #d5d8dc 100%); border-left-color: #717d7e; }

        .stat-box .number {
            font-size: 28px; font-weight: 800;
            color: #1a5276; margin-bottom: 5px;
        }
        .stat-box.green  .number { color: #1e8449; }
        .stat-box.red    .number { color: #c0392b; }
        .stat-box.purple .number { color: #7d3c98; }
        .stat-box.gray   .number { color: #4d5656; }

        .stat-box .label {
            font-size: 12px; color: #666; text-transform: uppercase;
            letter-spacing: 0.5px; font-weight: 600;
        }

        /* ─── Report Table ─── */
        .report-table {
            width: 100%; border-collapse: collapse; margin-bottom: 30px;
            background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 10px; overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #1a5276 0%, #2980b9 100%);
        }

        .report-table thead th {
            padding: 18px 12px; text-align: left; color: white;
            font-weight: 700; font-size: 13px; text-transform: uppercase;
            letter-spacing: 0.8px; border: none; white-space: nowrap;
        }
        .report-table thead th.text-right  { text-align: right; }
        .report-table thead th.text-center { text-align: center; }

        .report-table tbody tr {
            border-bottom: 1px solid #e9ecef; transition: background 0.2s;
        }
        .report-table tbody tr:hover     { background: #f8f9fa; }
        .report-table tbody tr.row-refund { background: #fff5f5; }
        .report-table tbody tr.row-refund:hover { background: #fdecea; }
        .report-table tbody tr:last-child { border-bottom: none; }
        .report-table tbody td {
            padding: 14px 12px; font-size: 13px; color: #333; font-weight: 500;
        }
        .report-table tbody td:first-child {
            font-weight: 700; color: #1a5276; font-size: 14px;
        }

        /* ─── Pay cells ─── */
        .pay-cell         { text-align: right; font-weight: 600; white-space: nowrap; }
        .pay-cell .empty  { color: #ccc; font-weight: 400; }
        .pay-cell.red-val { color: #c0392b; }
        .pay-cell.grn-val { color: #1e8449; }

        /* ─── Badges ─── */
        .status-badge {
            display: inline-block; padding: 6px 12px;
            border-radius: 12px; font-size: 11px; font-weight: 700;
            text-transform: uppercase;
        }
        .badge-sale   { background: #d4edda; color: #155724; border: 1px solid #28a745; }
        .badge-refund { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ─── tfoot ─── */
        .report-table tfoot { background: linear-gradient(135deg, #1a5276 0%, #2980b9 100%); }
        .report-table tfoot td {
            padding: 14px 12px; color: white;
            font-weight: 800; font-size: 13px; border: none;
        }
        .report-table tfoot td.text-right { text-align: right; }

        /* ─── Totals Section ─── */
        .totals-section {
            margin-top: 30px; padding: 25px;
            background: linear-gradient(135deg, #eaf4fb 0%, #aed6f1 100%);
            border-radius: 10px; border: 3px solid #85c1e9;
            box-shadow: 0 4px 15px rgba(26,82,118,0.15);
        }
        .totals-section h3 {
            color: #1a5276; font-size: 20px; margin-bottom: 20px;
            text-align: center; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .totals-section h3 i { margin-right: 10px; }

        .totals-table { width: 100%; max-width: 650px; margin: 0 auto; }
        .totals-table tr { border-bottom: 2px solid #aed6f1; }
        .totals-table tr:last-child { border-bottom: none; border-top: 4px solid #1a5276; }
        .totals-table td { padding: 14px 20px; font-size: 15px; }
        .totals-table td:first-child { font-weight: 700; color: #555; }
        .totals-table td:first-child i { margin-right: 10px; font-size: 16px; color: #1a5276; }
        .totals-table td:last-child  { text-align: right; font-weight: 700; color: #1a5276; font-size: 16px; }
        .totals-table tr.net-row td  { font-size: 18px; color: #1e8449 !important; }

        /* ─── Empty State ─── */
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i  { font-size: 64px; color: #ddd; margin-bottom: 20px; display: block; }
        .empty-state h3 { color: #666; font-size: 20px; margin-bottom: 10px; }
        .empty-state p  { color: #999; font-size: 14px; }

        /* ─── Print Button (fixed) ─── */
        .print-button {
            position: fixed; bottom: 30px; right: 30px;
            background: linear-gradient(135deg, #1a5276 0%, #2980b9 100%);
            color: white; border: none; padding: 15px 30px;
            border-radius: 50px; font-size: 16px; font-weight: 700;
            cursor: pointer; box-shadow: 0 4px 15px rgba(26,82,118,0.4);
            transition: all 0.3s; z-index: 1000;
        }
        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(26,82,118,0.5);
        }
        .print-button i { margin-right: 8px; }

        /* ─── Footer ─── */
        .report-footer {
            margin-top: 40px; padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center; color: #999; font-size: 12px;
        }
        .report-footer strong { color: #1a5276; }

        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .report-table tbody tr:hover { background: white; }
            @page { margin: 1.5cm; size: landscape; }
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
            <h1>Cashier Report</h1>
            <div class="subtitle">Sales &amp; Returns — By Payment Method</div>
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
                    <span class="branch">
                        <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                    </span>
                </div>
            @else
                <div>
                    <span class="branch"><i class="bi bi-buildings"></i> All Branches</span>
                </div>
            @endif
        </div>
    </div>

    @php
        $countSales   = $invoiceRows->where('transaction_type', 'sale')->count();
        $countRefunds = $invoiceRows->where('transaction_type', 'refund')->count();
    @endphp

        <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $invoiceRows->count() }}</div>
            <div class="label">Total Rows</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $countSales }}</div>
            <div class="label">Sale Invoices</div>
        </div>
        <div class="stat-box red">
            <div class="number">{{ $countRefunds }}</div>
            <div class="label">Return Invoices</div>
        </div>
        <div class="stat-box green">
            <div class="number">{{ number_format($totalSales, 2) }}</div>
            <div class="label">Total Sales (QAR)</div>
        </div>
        <div class="stat-box red">
            <div class="number">{{ number_format($totalRefunds, 2) }}</div>
            <div class="label">Total Returns (QAR)</div>
        </div>
        <div class="stat-box purple">
            <div class="number">{{ number_format($netTotal, 2) }}</div>
            <div class="label">Net Total (QAR)</div>
        </div>
        @foreach($paymentTypes as $type)
            <div class="stat-box gray">
                <div class="number">{{ number_format($columnTotals[$type] ?? 0, 2) }}</div>
                <div class="label">Net {{ $type }}</div>
            </div>
        @endforeach
    </div>

    @if($invoiceRows->count() > 0)

        <!-- Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th style="width:90px;">Date</th>
                <th style="width:110px;">Invoice</th>
                <th>Customer</th>
                @if(!$selectedBranch)
                    <th>Branch</th>
                @endif
                @foreach($paymentTypes as $type)
                    <th class="text-right">{{ $type }}</th>
                @endforeach
                <th class="text-right" style="width:100px;">Total</th>
                <th class="text-center" style="width:100px;">Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceRows as $index => $row)
                <tr class="{{ $row['transaction_type'] === 'refund' ? 'row-refund' : '' }}">
                    <td style="color:#aaa; font-size:11px; font-weight:500;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('Y-m-d') }}</td>
                    <td>{{ $row['invoice_code'] }}</td>
                    <td>{{ $row['customer'] }}</td>
                    @if(!$selectedBranch)
                        <td>{{ $row['branch'] }}</td>
                    @endif
                    @foreach($paymentTypes as $type)
                        <td class="pay-cell {{ $row['transaction_type'] === 'refund' ? 'red-val' : '' }}">
                            @if(($row['payments'][$type] ?? 0) > 0)
                                {{ number_format($row['payments'][$type], 2) }}
                            @else
                                <span class="empty">—</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="pay-cell {{ $row['transaction_type'] === 'refund' ? 'red-val' : 'grn-val' }}">
                        {{ number_format($row['total'], 2) }}
                    </td>
                    <td style="text-align:center;">
                        @if($row['transaction_type'] === 'sale')
                            <span class="status-badge badge-sale">
                                <i class="bi bi-cart-check"></i> Sale
                            </span>
                        @else
                            <span class="status-badge badge-refund">
                                <i class="bi bi-arrow-return-left"></i> Return
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="{{ $selectedBranch ? 4 : 5 }}">
                    <i class="bi bi-calculator"></i> NET TOTALS
                </td>
                @foreach($paymentTypes as $type)
                    <td class="text-right">{{ number_format($columnTotals[$type] ?? 0, 2) }}</td>
                @endforeach
                <td class="text-right">{{ number_format($netTotal, 2) }}</td>
                <td></td>
            </tr>
            </tfoot>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <h3><i class="bi bi-calculator"></i> Financial Summary</h3>
            <table class="totals-table">
                <tr>
                    <td><i class="bi bi-arrow-up-circle"></i> Total Sales</td>
                    <td>{{ number_format($totalSales, 2) }} QAR</td>
                </tr>
                <tr>
                    <td><i class="bi bi-arrow-down-circle"></i> Total Returns</td>
                    <td style="color:#c0392b;">{{ number_format($totalRefunds, 2) }} QAR</td>
                </tr>
                @foreach($paymentTypes as $type)
                    <tr>
                        <td>
                            <i class="bi bi-credit-card"></i> Net {{ $type }}
                            <small style="color:#999; font-weight:400; font-size:12px;">
                                ({{ number_format($columnSaleTotals[$type] ?? 0, 2) }} sales
                                &minus; {{ number_format($columnRefundTotals[$type] ?? 0, 2) }} returns)
                            </small>
                        </td>
                        <td>{{ number_format($columnTotals[$type] ?? 0, 2) }} QAR</td>
                    </tr>
                @endforeach
                <tr class="net-row">
                    <td><i class="bi bi-check-circle-fill"></i> Net Total</td>
                    <td>{{ number_format($netTotal, 2) }} QAR</td>
                </tr>
            </table>
        </div>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Transactions Found</h3>
            <p>No sales or returns found in the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> — Cashier Report (Sales &amp; Returns)<br>
            One row per invoice &bull; Payment methods as columns &bull; Returns highlighted in red<br>
            Report generated on {{ date('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'cashier-report'])
</body>
</html>
