<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #f0e6f6;
            padding: 24px;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(156, 39, 176, 0.15);
            border-radius: 12px;
        }

        /* ─── Header ─── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 3px solid #9c27b0;
        }

        .report-logo img {
            max-width: 120px;
            height: auto;
        }

        .report-title h1 {
            font-size: 30px;
            color: #7b1fa2;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .report-title .subtitle {
            font-size: 13px;
            color: #9c27b0;
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
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            border: 2px solid #ce93d8;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .info-header strong {
            color: #9c27b0;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 2px;
        }

        .info-header .dates,
        .info-header .branch {
            font-size: 16px;
            color: #4a148c;
            font-weight: 600;
        }

        /* ─── Category Header ─── */
        .category-header {
            background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);
            color: white;
            font-weight: 800;
            font-size: 15px;
            padding: 14px 20px;
            margin-top: 30px;
            margin-bottom: 0;
            border-radius: 10px 10px 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 3px 10px rgba(156, 39, 176, 0.25);
        }

        /* ─── Products Table ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(156, 39, 176, 0.1);
            border-radius: 0 0 10px 10px;
            overflow: hidden;
            border: 1px solid #e1bee7;
            border-top: none;
        }

        .report-table thead {
            background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
        }

        .report-table thead th {
            padding: 13px 12px;
            text-align: left;
            color: rgba(255,255,255,0.9);
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(n+3) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #f3e5f5;
            transition: background 0.15s;
        }

        .report-table tbody tr:hover:not(.category-total-row) {
            background: #faf5fd;
        }

        .report-table tbody td {
            padding: 11px 12px;
            font-size: 13px;
            color: #444;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(n+3) {
            text-align: right;
            /*font-family: 'Courier New', monospace;*/
            font-weight: 600;
        }

        .report-table tbody td:nth-child(2) {
            color: #6a1b9a;
            font-weight: 600;
        }

        /* profit cell in rows */
        .profit-cell {
            color: #2e7d32 !important;
            font-weight: 700 !important;
        }

        /* ─── Category Total Row ─── */
        .category-total-row {
            background: linear-gradient(135deg, #4a148c 0%, #6a1b9a 100%) !important;
        }

        .category-total-row td {
            padding: 15px 12px;
            color: white !important;
            font-size: 13px;
            font-weight: 700;
            border-top: none !important;
        }

        .category-total-row td.profit-cell {
            color: #a5d6a7 !important;
            font-weight: 800 !important;
        }

        /* ─── Summary Section ─── */
        .summary-section {
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            border-radius: 12px;
            border: 2px solid #9c27b0;
            box-shadow: 0 6px 20px rgba(156, 39, 176, 0.2);
        }

        .summary-title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #7b1fa2;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .summary-table {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .summary-table thead {
            background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%);
        }

        .summary-table thead th {
            padding: 16px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-table thead th:nth-child(n+2) {
            text-align: right;
        }

        .summary-table tbody tr {
            border-bottom: 1px solid #e1bee7;
        }

        .summary-table tbody tr:last-child {
            border-bottom: none;
        }

        .summary-table tbody tr:hover {
            background: #faf5fd;
        }

        .summary-table tbody td {
            padding: 15px;
            font-size: 14px;
        }

        .summary-table tbody td:first-child {
            font-weight: 700;
            color: #6a1b9a;
        }

        .summary-table tbody td:nth-child(n+2) {
            text-align: right;
            /*font-family: 'Courier New', monospace;*/
            font-weight: 600;
        }

        .summary-table tbody td:nth-child(5) {
            color: #2e7d32;
            font-weight: 800;
        }

        /* Grand Total Row */
        .summary-table tfoot {
            background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);
        }

        .summary-table tfoot td {
            padding: 18px 15px;
            color: white;
            font-weight: 800;
            font-size: 16px;
        }

        .summary-table tfoot td:first-child {
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .summary-table tfoot td:nth-child(n+2) {
            text-align: right;
            /*font-family: 'Courier New', monospace;*/
        }

        .summary-table tfoot td:nth-child(5) {
            color: #a5d6a7;
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #bbb;
        }

        .empty-state i {
            font-size: 72px;
            margin-bottom: 20px;
            display: block;
            color: #ddd;
        }

        .empty-state h3 {
            color: #888;
            font-size: 20px;
            margin-bottom: 8px;
        }

        /* ─── Print Button ─── */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(156, 39, 176, 0.45);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(156, 39, 176, 0.55);
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

        .report-footer strong {
            color: #9c27b0;
        }

        /* ─── Print ─── */
        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .report-table tbody tr:hover { background: transparent; }
            @page { margin: 1.5cm; size: landscape; }
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
            <h1><i class="bi bi-bar-chart-line-fill" style="font-size:26px;vertical-align:middle;margin-right:8px;"></i>Sales Summary</h1>
            <div class="subtitle">Products Analysis Report</div>
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
                    {{ $date_from }} &nbsp;→&nbsp; {{ $date_to }}
                </span>
            </div>
            @if(isset($selectedBranch))
                <div>
                    <strong>Branch</strong>
                    <span class="branch">
                    <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                </span>
                </div>
            @else
                <div>

                    <span class="branch">
                    <i class="bi bi-building"></i> All Branches
                </span>
                </div>
            @endif
        </div>
    </div>

    @if(isset($products) && count($products) > 0)
        @php
            $summaryByType   = [];
            $summaryTotalQty    = 0;
            $summaryTotalCost   = 0;
            $summaryTotalRetail = 0;
        @endphp

        @foreach($products as $categoryName => $items)
            @php
                $categoryQty         = 0;
                $categoryTotalCost   = 0;
                $categoryTotalRetail = 0;
            @endphp

                <!-- Category Header (attached to table) -->
            <div class="category-header">
                <i class="bi bi-box-seam"></i> {{ $categoryName }}
            </div>

            <table class="report-table">
                <thead>
                <tr>
                    <th style="width:110px;">Item No.</th>
                    <th>Description</th>
                    <th style="width:65px;">Qty</th>
                    <th style="width:115px;">Cost Price</th>
                    <th style="width:130px;">Total Cost</th>
                    <th style="width:115px;">Retail Price</th>
                    <th style="width:130px;">Total Retail</th>
                    <th style="width:130px;">Profit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @php
                        $qty             = $item->item_count;
                        $costPrice       = $item->price ?? $item->lens_price ?? 0;
                        $retailPrice     = $item->retail_price ?? $item->lens_retail_price ?? 0;
                        $totalCostPrice  = $costPrice * $qty;
                        $totalRetailPrice= $retailPrice * $qty;
                        $rowProfit       = $totalRetailPrice - $totalCostPrice;

                        $categoryQty         += $qty;
                        $categoryTotalCost   += $totalCostPrice;
                        $categoryTotalRetail += $totalRetailPrice;
                    @endphp
                    <tr>
                        <td>{{ $item->product_id }}</td>
                        <td>{{ $item->description ?? $item->lens_description ?? '-' }}</td>
                        <td>{{ $qty }}</td>
                        <td>{{ number_format($costPrice, 2) }}</td>
                        <td>{{ number_format($totalCostPrice, 2) }}</td>
                        <td>{{ number_format($retailPrice, 2) }}</td>
                        <td>{{ number_format($totalRetailPrice, 2) }}</td>
                        <td class="profit-cell">{{ number_format($rowProfit, 2) }}</td>
                    </tr>
                @endforeach

                <!-- Category Total -->
                <tr class="category-total-row">
                    <td colspan="2">
                        <i class="bi bi-calculator"></i>&nbsp; {{ $categoryName }} — Total
                    </td>
                    <td>{{ $categoryQty }}</td>
                    <td>—</td>
                    <td>{{ number_format($categoryTotalCost, 2) }}</td>
                    <td>—</td>
                    <td>{{ number_format($categoryTotalRetail, 2) }}</td>
                    <td class="profit-cell">{{ number_format($categoryTotalRetail - $categoryTotalCost, 2) }}</td>
                </tr>
                </tbody>
            </table>

            @php
                $summaryByType[$categoryName] = [
                    'qty'    => $categoryQty,
                    'cost'   => $categoryTotalCost,
                    'retail' => $categoryTotalRetail,
                ];
                $summaryTotalQty    += $categoryQty;
                $summaryTotalCost   += $categoryTotalCost;
                $summaryTotalRetail += $categoryTotalRetail;
            @endphp
        @endforeach

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-title">
                <i class="bi bi-receipt-cutoff"></i> Summary &amp; Profit Analysis
            </div>

            <table class="summary-table">
                <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Qty</th>
                    <th>Total Cost</th>
                    <th>Total Retail</th>
                    <th>Profit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($summaryByType as $type => $data)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $data['qty'] }}</td>
                        <td>{{ number_format($data['cost'], 2) }} QAR</td>
                        <td>{{ number_format($data['retail'], 2) }} QAR</td>
                        <td>{{ number_format($data['retail'] - $data['cost'], 2) }} QAR</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td>Grand Total</td>
                    <td>{{ $summaryTotalQty }}</td>
                    <td>{{ number_format($summaryTotalCost, 2) }} QAR</td>
                    <td>{{ number_format($summaryTotalRetail, 2) }} QAR</td>
                    <td>{{ number_format($summaryTotalRetail - $summaryTotalCost, 2) }} QAR</td>
                </tr>
                </tfoot>
            </table>
        </div>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Sales Data Found</h3>
            <p>There are no product sales for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <strong>Optics Modern</strong> — Sales Summary Report<br>
        System-generated report · Product sales grouped by category with profit analysis<br>
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'sales-summary-report'])
</body>
</html>
