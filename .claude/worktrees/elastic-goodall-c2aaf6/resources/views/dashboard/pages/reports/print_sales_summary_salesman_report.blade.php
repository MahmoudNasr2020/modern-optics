<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary By Salesman Report</title>

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
            max-width: 1400px;
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
            border-bottom: 3px solid #3f51b5;
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
            color: #3f51b5;
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
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #9fa8da;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #3f51b5;
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

        /* Salesman Section Header */
        .salesman-header {
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            color: white;
            padding: 20px 25px;
            margin: 40px 0 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(63, 81, 181, 0.3);
            position: relative;
            overflow: hidden;
        }

        .salesman-header::before {
            content: '';
            position: absolute;
            right: -50px;
            top: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .salesman-header h2 {
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .salesman-header h2 i {
            margin-right: 12px;
            background: rgba(255,255,255,0.2);
            padding: 8px 12px;
            border-radius: 8px;
        }

        /* Category Header */
        .category-header {
            background: linear-gradient(135deg, #5c6bc0 0%, #3949ab 100%);
            color: white;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 20px;
            margin-top: 20px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 3px 10px rgba(92, 107, 192, 0.3);
        }

        .category-header i {
            margin-right: 10px;
        }

        /* Products Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
            font-size: 12px;
        }

        .report-table thead {
            background: linear-gradient(135deg, #283593 0%, #1a237e 100%);
        }

        .report-table thead th {
            padding: 14px 10px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(n+5) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e8eaf6;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover:not(.category-total-row) {
            background: #f8f9fd;
        }

        .report-table tbody td {
            padding: 11px 10px;
            font-size: 12px;
            color: #333;
            font-weight: 500;
            text-align: right;
        }

        .report-table tbody td:nth-child(n+5) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .report-table tbody td:nth-child(1) {
            color: #3f51b5;
            font-weight: 700;
        }

        /* Category Total Row */
        .category-total-row {
            background: linear-gradient(135deg, #c5cae9 0%, #9fa8da 100%);
            font-weight: 700;
            border-top: 2px solid #5c6bc0;
            border-bottom: 2px solid #5c6bc0;
        }

        .category-total-row td {
            padding: 13px 10px;
            color: #1a237e;
            font-size: 13px;
        }

        .category-total-row td:first-child {
            font-weight: 800;
        }

        /* Salesman Summary Section */
        .salesman-summary-section {
            margin: 30px 0;
            padding: 25px;
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            border-radius: 10px;
            border: 3px solid #5c6bc0;
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.2);
        }

        .salesman-summary-title {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            color: #3f51b5;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .salesman-summary-title i {
            margin-right: 10px;
        }

        .summary-table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .summary-table thead {
            background: linear-gradient(135deg, #303f9f 0%, #283593 100%);
        }

        .summary-table thead th {
            padding: 15px 12px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .summary-table thead th:nth-child(n+2) {
            text-align: right;
        }

        .summary-table tbody tr {
            border-bottom: 1px solid #c5cae9;
        }

        .summary-table tbody tr:last-child {
            border-bottom: none;
        }

        .summary-table tbody td {
            padding: 14px 12px;
            font-size: 14px;
        }

        .summary-table tbody td:first-child {
            font-weight: 700;
            color: #3949ab;
        }

        .summary-table tbody td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .summary-table tfoot {
            background: linear-gradient(135deg, #5c6bc0 0%, #3949ab 100%);
        }

        .summary-table tfoot td {
            padding: 16px 12px;
            color: white;
            font-weight: 800;
            font-size: 15px;
        }

        .summary-table tfoot td:first-child {
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-table tfoot td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        /* Divider */
        .salesman-divider {
            margin: 60px 0;
            border: 0;
            border-top: 3px dashed #9fa8da;
        }

        /* Global Summary Section */
        .global-summary-section {
            margin-top: 60px;
            padding: 35px;
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(63, 81, 181, 0.3);
        }

        .global-summary-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .global-summary-title i {
            margin-right: 12px;
            font-size: 32px;
        }

        .global-summary-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .global-summary-table thead {
            background: linear-gradient(135deg, #283593 0%, #1a237e 100%);
        }

        .global-summary-table thead th {
            padding: 18px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .global-summary-table thead th:nth-child(n+3) {
            text-align: right;
        }

        .global-summary-table tbody tr {
            border-bottom: 1px solid #e8eaf6;
        }

        .global-summary-table tbody td {
            padding: 15px;
            font-size: 14px;
            text-align: right;
        }

        .global-summary-table tbody td:first-child {
            font-weight: 800;
            color: #3f51b5;
            vertical-align: middle;
        }

        .global-summary-table tbody td:nth-child(2) {
            font-weight: 600;
            color: #5c6bc0;
        }

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
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
        }

        .global-summary-table tfoot td {
            padding: 20px 15px;
            color: white;
            font-weight: 800;
            font-size: 17px;
        }

        .global-summary-table tfoot td:first-child {
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .global-summary-table tfoot td:nth-child(n+3) {
            text-align: right;
            font-family: 'Courier New', monospace;
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
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(63, 81, 181, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(63, 81, 181, 0.5);
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
            color: #3f51b5;
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
            }

            .salesman-divider {
                page-break-after: always;
            }

            @page {
                margin: 1.5cm;
                size: landscape;
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
            <h1>Sales Summary By Salesman</h1>
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
                <strong>Report Period:</strong>
                <span class="dates">
                    <i class="bi bi-calendar-range"></i>
                    {{ $date_from }} <strong>to</strong> {{ $date_to }}
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

    @if(isset($products) && count($products) > 0)
        @php
            $globalSummary = [];
        @endphp

            <!-- Loop through each salesman -->
        @foreach($products as $salesmanName => $items)
            @php
                $salesmanSummary = [];

                // Group items by category
                $items = $items->groupBy(function($e){
                    return $e->category_name ?? 'Lenses';
                });
            @endphp

                <!-- Salesman Header -->
            <div class="salesman-header">
                <h2><i class="bi bi-person-badge"></i> {{ $salesmanName }}</h2>
            </div>

            <!-- Loop through each category for this salesman -->
            @foreach($items as $categoryName => $categoryItems)
                @php
                    $categoryQty = 0;
                    $categoryTotalCost = 0;
                    $categoryTotalRetail = 0;
                @endphp

                    <!-- Category Header -->
                <div class="category-header">
                    <i class="bi bi-box-seam"></i> {{ $categoryName }}
                </div>

                <!-- Products Table -->
                <table class="report-table">
                    <thead>
                    <tr>
                        <th style="width: 100px;">INVOICE</th>
                        <th style="width: 100px;">ITEM</th>
                        <th style="width: 100px;">DATE</th>
                        <th style="width: 250px;">DESCRIPTION</th>
                        <th style="width: 70px;">QTY</th>
                        <th style="width: 110px;">COST PRICE</th>
                        <th style="width: 120px;">TOTAL COST</th>
                        <th style="width: 110px;">RETAIL PRICE</th>
                        <th style="width: 120px;">TOTAL RETAIL</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categoryItems as $item)
                        @php
                            $qty = $item->item_count;
                            $costPrice = $item->price ?? $item->lens_price ?? 0;
                            $retailPrice = $item->retail_price ?? $item->lens_retail_price ?? 0;
                            $totalCostPrice = $costPrice * $qty;
                            $totalRetailPrice = $retailPrice * $qty;

                            $categoryQty += $qty;
                            $categoryTotalCost += $totalCostPrice;
                            $categoryTotalRetail += $totalRetailPrice;

                            // Update salesman summary
                            if(!isset($salesmanSummary[$categoryName])){
                                $salesmanSummary[$categoryName] = [
                                    'qty' => 0,
                                    'cost' => 0,
                                    'retail' => 0,
                                ];
                            }

                            $salesmanSummary[$categoryName]['qty'] += $qty;
                            $salesmanSummary[$categoryName]['cost'] += $totalCostPrice;
                            $salesmanSummary[$categoryName]['retail'] += $totalRetailPrice;

                            // Update global summary
                            if (!isset($globalSummary[$salesmanName])) {
                                $globalSummary[$salesmanName] = [];
                            }

                            if (!isset($globalSummary[$salesmanName][$categoryName])) {
                                $globalSummary[$salesmanName][$categoryName] = [
                                    'qty' => 0,
                                    'cost' => 0,
                                    'retail' => 0,
                                ];
                            }

                            $globalSummary[$salesmanName][$categoryName]['qty'] += $qty;
                            $globalSummary[$salesmanName][$categoryName]['cost'] += $totalCostPrice;
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
                        </tr>
                    @endforeach

                    <!-- Category Total -->
                    <tr class="category-total-row">
                        <td colspan="4">
                            <i class="bi bi-calculator"></i> <strong>Totals</strong>
                        </td>
                        <td><strong>{{ $categoryQty }}</strong></td>
                        <td>-</td>
                        <td><strong>{{ number_format($categoryTotalCost, 2) }}</strong></td>
                        <td>-</td>
                        <td><strong>{{ number_format($categoryTotalRetail, 2) }}</strong></td>
                    </tr>
                    </tbody>
                </table>
            @endforeach

            <!-- Salesman Summary -->
            <div class="salesman-summary-section">
                <div class="salesman-summary-title">
                    <i class="bi bi-receipt"></i> {{ $salesmanName }} Summary
                </div>

                <table class="summary-table">
                    <thead>
                    <tr>
                        <th>CATEGORY</th>
                        <th>TOTAL QTY</th>
                        <th>TOTAL COST</th>
                        <th>TOTAL RETAIL</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $salesmanTotalQty = 0;
                        $salesmanTotalCost = 0;
                        $salesmanTotalRetail = 0;
                    @endphp

                    @foreach($salesmanSummary as $cat => $sum)
                        @php
                            $salesmanTotalQty += $sum['qty'];
                            $salesmanTotalCost += $sum['cost'];
                            $salesmanTotalRetail += $sum['retail'];
                        @endphp

                        <tr>
                            <td>{{ $cat }}</td>
                            <td>{{ $sum['qty'] }}</td>
                            <td>{{ number_format($sum['cost'], 2) }} QAR</td>
                            <td>{{ number_format($sum['retail'], 2) }} QAR</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Grand Total</td>
                        <td>{{ $salesmanTotalQty }}</td>
                        <td>{{ number_format($salesmanTotalCost, 2) }} QAR</td>
                        <td>{{ number_format($salesmanTotalRetail, 2) }} QAR</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Divider (except for last salesman) -->
            @if(!$loop->last)
                <hr class="salesman-divider">
            @endif
        @endforeach

        <!-- Global Summary Section -->
        <div class="global-summary-section">
            <div class="global-summary-title">
                <i class="bi bi-trophy"></i> Final Sales Summary
            </div>

            <table class="global-summary-table">
                <thead>
                <tr>
                    <th>SALESMAN</th>
                    <th>CATEGORY</th>
                    <th>TOTAL QTY</th>
                    <th>TOTAL COST</th>
                    <th>TOTAL RETAIL</th>
                    <th>PROFIT</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $grandQty = 0;
                    $grandCost = 0;
                    $grandRetail = 0;
                    $grandProfit = 0;
                @endphp

                @foreach($globalSummary as $seller => $categories)
                    @php
                        $rowspan = count($categories);
                        $firstRow = true;
                    @endphp

                    @foreach($categories as $cat => $sum)
                        @php
                            $profit = $sum['retail'] - $sum['cost'];

                            $grandQty += $sum['qty'];
                            $grandCost += $sum['cost'];
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
            <strong>Optics Modern</strong> - Sales Summary By Salesman Report<br>
            This is a system-generated report showing sales performance grouped by salesman and category with profit analysis.<br>
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
