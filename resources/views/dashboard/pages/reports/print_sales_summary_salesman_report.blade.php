<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary By Salesman Report</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #e8eaf6;
            padding: 24px;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(63, 81, 181, 0.15);
            border-radius: 12px;
        }

        /* ─── Header ─── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3f51b5;
        }

        .report-logo img { max-width: 120px; height: auto; }

        .report-title h1 {
            font-size: 28px;
            color: #3f51b5;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .report-title .subtitle {
            font-size: 12px;
            color: #3f51b5;
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
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            border: 2px solid #9fa8da;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #3f51b5;
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
            color: #1a237e;
            font-weight: 600;
        }

        /* ─── Salesman Header ─── */
        .salesman-header {
            background: linear-gradient(135deg, #3f51b5 0%, #283593 100%);
            color: white;
            padding: 18px 25px;
            margin: 40px 0 0 0;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 4px 15px rgba(63, 81, 181, 0.3);
            position: relative;
            overflow: hidden;
        }

        .salesman-header::before {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 130px;
            height: 130px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .salesman-header h2 {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .salesman-header h2 i {
            background: rgba(255,255,255,0.18);
            padding: 7px 11px;
            border-radius: 8px;
        }

        /* ─── Category Header ─── */
        .category-header {
            background: linear-gradient(135deg, #5c6bc0 0%, #3949ab 100%);
            color: white;
            font-weight: 700;
            font-size: 13px;
            padding: 12px 18px;
            margin-top: 0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }

        /* ─── Products Table ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            background: white;
            font-size: 12px;
            border-left: 1px solid #e8eaf6;
            border-right: 1px solid #e8eaf6;
        }

        .report-table thead {
            background: linear-gradient(135deg, #283593 0%, #1a237e 100%);
        }

        .report-table thead th {
            padding: 12px 10px;
            text-align: left;
            color: rgba(255,255,255,0.9);
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(5),
        .report-table thead th:nth-child(6),
        .report-table thead th:nth-child(7),
        .report-table thead th:nth-child(8),
        .report-table thead th:nth-child(9),
        .report-table thead th:nth-child(10) { text-align: right; }

        .report-table tbody tr {
            border-bottom: 1px solid #e8eaf6;
            transition: background 0.15s;
        }

        .report-table tbody tr:hover:not(.category-total-row) { background: #f8f9fd; }

        .report-table tbody td {
            padding: 10px;
            font-size: 12px;
            color: #444;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(5),
        .report-table tbody td:nth-child(6),
        .report-table tbody td:nth-child(7),
        .report-table tbody td:nth-child(8),
        .report-table tbody td:nth-child(9),
        .report-table tbody td:nth-child(10) {
            text-align: right;
            font-weight: 600;
        }

        .report-table tbody td:nth-child(1) {
            color: #3f51b5;
            font-weight: 700;
        }

        .profit-cell {
            color: #2e7d32 !important;
            font-weight: 700 !important;
        }

        /* ─── Category Total Row ─── */
        .category-total-row {
            background: linear-gradient(135deg, #3949ab 0%, #283593 100%) !important;
        }

        .category-total-row td {
            padding: 13px 10px;
            color: white !important;
            font-size: 12px;
            font-weight: 700;
        }

        .category-total-row td.profit-cell {
            color: #a5d6a7 !important;
            font-weight: 800 !important;
        }

        /* last table in salesman block gets bottom radius */
        .salesman-block-end {
            border-radius: 0 0 10px 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(63, 81, 181, 0.1);
            margin-bottom: 0;
            border-bottom: 1px solid #e8eaf6;
        }

        /* ─── Salesman Summary ─── */
        .salesman-summary-section {
            margin: 0 0 30px 0;
            padding: 24px;
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            border-radius: 0 0 12px 12px;
            border: 2px solid #5c6bc0;
            border-top: none;
            box-shadow: 0 4px 15px rgba(63, 81, 181, 0.12);
        }

        .salesman-summary-title {
            text-align: center;
            font-size: 17px;
            font-weight: 800;
            color: #3f51b5;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .summary-table {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .summary-table thead {
            background: linear-gradient(135deg, #303f9f 0%, #283593 100%);
        }

        .summary-table thead th {
            padding: 14px 12px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .summary-table thead th:nth-child(n+2) { text-align: right; }

        .summary-table tbody tr { border-bottom: 1px solid #c5cae9; }
        .summary-table tbody tr:last-child { border-bottom: none; }
        .summary-table tbody tr:hover { background: #f5f5fb; }

        .summary-table tbody td { padding: 13px 12px; font-size: 14px; }

        .summary-table tbody td:first-child { font-weight: 700; color: #3949ab; }

        .summary-table tbody td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .summary-table tbody td:nth-child(5) {
            color: #2e7d32;
            font-weight: 800;
        }

        .summary-table tfoot {
            background: linear-gradient(135deg, #5c6bc0 0%, #3949ab 100%);
        }

        .summary-table tfoot td {
            padding: 16px 12px;
            color: white;
            font-weight: 800;
            font-size: 14px;
        }

        .summary-table tfoot td:first-child { text-transform: uppercase; letter-spacing: 1px; }

        .summary-table tfoot td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .summary-table tfoot td:nth-child(5) { color: #a5d6a7; }

        /* ─── Divider ─── */
        .salesman-divider {
            margin: 50px 0;
            border: 0;
            border-top: 3px dashed #9fa8da;
        }

        /* ─── Global Summary ─── */
        .global-summary-section {
            margin-top: 60px;
            padding: 35px;
            background: linear-gradient(135deg, #3f51b5 0%, #283593 100%);
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(63, 81, 181, 0.35);
        }

        .global-summary-title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .global-summary-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .global-summary-table thead {
            background: linear-gradient(135deg, #283593 0%, #1a237e 100%);
        }

        .global-summary-table thead th {
            padding: 16px 14px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .global-summary-table thead th:nth-child(n+3) { text-align: right; }

        .global-summary-table tbody tr { border-bottom: 1px solid #e8eaf6; }
        .global-summary-table tbody tr:hover { background: #f8f9fd; }

        .global-summary-table tbody td { padding: 14px; font-size: 13px; }

        .global-summary-table tbody td:first-child {
            font-weight: 800;
            color: #3f51b5;
            vertical-align: middle;
        }

        .global-summary-table tbody td:nth-child(2) { font-weight: 600; color: #5c6bc0; }

        .global-summary-table tbody td:nth-child(n+3) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .global-summary-table tbody td:last-child {
            color: #2e7d32;
            font-weight: 800;
        }

        .global-summary-table tfoot {
            background: linear-gradient(135deg, #3f51b5 0%, #283593 100%);
        }

        .global-summary-table tfoot td {
            padding: 18px 14px;
            color: white;
            font-weight: 800;
            font-size: 16px;
        }

        .global-summary-table tfoot td:first-child { text-transform: uppercase; letter-spacing: 1.5px; }

        .global-summary-table tfoot td:nth-child(n+3) {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .global-summary-table tfoot td:last-child { color: #a5d6a7; }

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
            background: linear-gradient(135deg, #3f51b5 0%, #283593 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(63, 81, 181, 0.45);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(63, 81, 181, 0.55);
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

        .report-footer strong { color: #3f51b5; }

        /* ─── Print ─── */
        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .salesman-divider { page-break-after: always; }
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
            <h1><i class="bi bi-people-fill" style="font-size:24px;vertical-align:middle;margin-right:8px;"></i>Sales By Salesman</h1>
            <div class="subtitle">Performance Analysis Report</div>
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
            @endif
        </div>
    </div>

    @if(isset($products) && count($products) > 0)
        @php $globalSummary = []; @endphp

        @foreach($products as $salesmanName => $items)
            @php
                $salesmanSummary = [];
                $items = $items->groupBy(function($e){ return $e->category_name ?? 'Lenses'; });
                $categoryKeys = array_keys($items->toArray());
                $lastCategory = end($categoryKeys);
            @endphp

                <!-- Salesman Header (top of block) -->
            <div class="salesman-header">
                <h2><i class="bi bi-person-badge"></i> {{ $salesmanName }}</h2>
            </div>

            @foreach($items as $categoryName => $categoryItems)
                @php
                    $categoryQty         = 0;
                    $categoryTotalCost   = 0;
                    $categoryTotalRetail = 0;
                    $isLast = ($categoryName === $lastCategory);
                @endphp

                    <!-- Category sub-header -->
                <div class="category-header">
                    <i class="bi bi-box-seam"></i> {{ $categoryName }}
                </div>

                <table class="report-table {{ $isLast ? 'salesman-block-end' : '' }}">
                    <thead>
                    <tr>
                        <th style="width:95px;">Invoice</th>
                        <th style="width:85px;">Item</th>
                        <th style="width:90px;">Date</th>
                        <th>Description</th>
                        <th style="width:55px;">Qty</th>
                        <th style="width:105px;">Cost Price</th>
                        <th style="width:115px;">Total Cost</th>
                        <th style="width:105px;">Retail Price</th>
                        <th style="width:115px;">Total Retail</th>
                        <th style="width:115px;">Profit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categoryItems as $item)
                        @php
                            $qty              = $item->item_count;
                            $costPrice        = $item->price ?? $item->lens_price ?? 0;
                            $retailPrice      = $item->retail_price ?? $item->lens_retail_price ?? 0;
                            $totalCostPrice   = $costPrice * $qty;
                            $totalRetailPrice = $retailPrice * $qty;
                            $rowProfit        = $totalRetailPrice - $totalCostPrice;

                            $categoryQty         += $qty;
                            $categoryTotalCost   += $totalCostPrice;
                            $categoryTotalRetail += $totalRetailPrice;

                            if (!isset($salesmanSummary[$categoryName])) {
                                $salesmanSummary[$categoryName] = ['qty'=>0,'cost'=>0,'retail'=>0];
                            }
                            $salesmanSummary[$categoryName]['qty']    += $qty;
                            $salesmanSummary[$categoryName]['cost']   += $totalCostPrice;
                            $salesmanSummary[$categoryName]['retail'] += $totalRetailPrice;

                            if (!isset($globalSummary[$salesmanName])) $globalSummary[$salesmanName] = [];
                            if (!isset($globalSummary[$salesmanName][$categoryName])) {
                                $globalSummary[$salesmanName][$categoryName] = ['qty'=>0,'cost'=>0,'retail'=>0];
                            }
                            $globalSummary[$salesmanName][$categoryName]['qty']    += $qty;
                            $globalSummary[$salesmanName][$categoryName]['cost']   += $totalCostPrice;
                            $globalSummary[$salesmanName][$categoryName]['retail'] += $totalRetailPrice;
                        @endphp
                        <tr>
                            <td>{{ $item->invoice_code }}</td>
                            <td>{{ $item->product_id }}</td>
                            <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
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
                        <td colspan="3" style="text-align:left;"><i class="bi bi-calculator"></i>&nbsp; {{ $categoryName }} — Total</td>
                        <td></td>
                        <td style="text-align:right;">{{ $categoryQty }}</td>
                        <td style="text-align:right;">—</td>
                        <td style="text-align:right;">{{ number_format($categoryTotalCost, 2) }}</td>
                        <td style="text-align:right;">—</td>
                        <td style="text-align:right;">{{ number_format($categoryTotalRetail, 2) }}</td>
                        <td class="profit-cell" style="text-align:right;">{{ number_format($categoryTotalRetail - $categoryTotalCost, 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            @endforeach

            <!-- Salesman Summary -->
            <div class="salesman-summary-section">
                <div class="salesman-summary-title">
                    <i class="bi bi-receipt"></i> {{ $salesmanName }} — Summary
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
                    @php
                        $salesmanTotalQty    = 0;
                        $salesmanTotalCost   = 0;
                        $salesmanTotalRetail = 0;
                    @endphp

                    @foreach($salesmanSummary as $cat => $sum)
                        @php
                            $salesmanTotalQty    += $sum['qty'];
                            $salesmanTotalCost   += $sum['cost'];
                            $salesmanTotalRetail += $sum['retail'];
                        @endphp
                        <tr>
                            <td>{{ $cat }}</td>
                            <td>{{ $sum['qty'] }}</td>
                            <td>{{ number_format($sum['cost'], 2) }} QAR</td>
                            <td>{{ number_format($sum['retail'], 2) }} QAR</td>
                            <td>{{ number_format($sum['retail'] - $sum['cost'], 2) }} QAR</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Grand Total</td>
                        <td>{{ $salesmanTotalQty }}</td>
                        <td>{{ number_format($salesmanTotalCost, 2) }} QAR</td>
                        <td>{{ number_format($salesmanTotalRetail, 2) }} QAR</td>
                        <td>{{ number_format($salesmanTotalRetail - $salesmanTotalCost, 2) }} QAR</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            @if(!$loop->last)
                <hr class="salesman-divider">
            @endif
        @endforeach

        <!-- Global Summary -->
        <div class="global-summary-section">
            <div class="global-summary-title">
                <i class="bi bi-trophy-fill"></i> Final Sales Summary
            </div>

            <table class="global-summary-table">
                <thead>
                <tr>
                    <th>Salesman</th>
                    <th>Category</th>
                    <th>Total Qty</th>
                    <th>Total Cost</th>
                    <th>Total Retail</th>
                    <th>Profit</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $grandQty    = 0;
                    $grandCost   = 0;
                    $grandRetail = 0;
                    $grandProfit = 0;
                @endphp

                @foreach($globalSummary as $seller => $categories)
                    @php
                        $rowspan  = count($categories);
                        $firstRow = true;
                    @endphp

                    @foreach($categories as $cat => $sum)
                        @php
                            $profit       = $sum['retail'] - $sum['cost'];
                            $grandQty    += $sum['qty'];
                            $grandCost   += $sum['cost'];
                            $grandRetail += $sum['retail'];
                            $grandProfit += $profit;
                        @endphp
                        <tr>
                            @if($firstRow)
                                <td rowspan="{{ $rowspan }}">{{ $seller }}</td>
                                @php $firstRow = false; @endphp
                            @endif
                            <td>{{ $cat }}</td>
                            <td>{{ $sum['qty'] }}</td>
                            <td>{{ number_format($sum['cost'], 2) }} QAR</td>
                            <td>{{ number_format($sum['retail'], 2) }} QAR</td>
                            <td>{{ number_format($profit, 2) }} QAR</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">Grand Total</td>
                    <td>{{ $grandQty }}</td>
                    <td>{{ number_format($grandCost, 2) }} QAR</td>
                    <td>{{ number_format($grandRetail, 2) }} QAR</td>
                    <td>{{ number_format($grandProfit, 2) }} QAR</td>
                </tr>
                </tfoot>
            </table>
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
        <strong>Optics Modern</strong> — Sales Summary By Salesman Report<br>
        System-generated report · Sales performance grouped by salesman and category with profit analysis<br>
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'sales-by-salesman-report'])
</body>
</html>
