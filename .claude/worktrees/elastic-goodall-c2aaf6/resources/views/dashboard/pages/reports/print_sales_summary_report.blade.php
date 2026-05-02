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
            border-bottom: 3px solid #9c27b0;
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
            color: #9c27b0;
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
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #ce93d8;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #9c27b0;
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

        /* Category Header */
        .category-header {
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
            color: white;
            font-weight: 800;
            font-size: 16px;
            padding: 16px 20px;
            margin-top: 25px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(156, 39, 176, 0.3);
        }

        .category-header i {
            margin-right: 10px;
            font-size: 18px;
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
        }

        .report-table thead {
            background: linear-gradient(135deg, #6a1b9a 0%, #4a148c 100%);
        }

        .report-table thead th {
            padding: 15px 12px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(n+4) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #f3e5f5;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover:not(.category-total-row) {
            background: #faf8fb;
        }

        .report-table tbody td {
            padding: 12px;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(n+4) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .report-table tbody td:nth-child(2) {
            color: #6a1b9a;
            font-weight: 700;
        }

        /* Category Total Row */
        .category-total-row {
            background: linear-gradient(135deg, #e1bee7 0%, #ce93d8 100%);
            font-weight: 700;
            border-top: 2px solid #9c27b0;
            border-bottom: 2px solid #9c27b0;
        }

        .category-total-row td {
            padding: 14px 12px;
            color: #4a148c;
            font-size: 14px;
        }

        .category-total-row td:first-child {
            font-weight: 800;
        }

        /* Summary Table */
        .summary-section {
            margin-top: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            border-radius: 10px;
            border: 3px solid #9c27b0;
            box-shadow: 0 4px 15px rgba(156, 39, 176, 0.2);
        }

        .summary-title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: #9c27b0;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .summary-title i {
            margin-right: 12px;
            font-size: 32px;
        }

        .summary-table {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .summary-table thead {
            background: linear-gradient(135deg, #7b1fa2 0%, #6a1b9a 100%);
        }

        .summary-table thead th {
            padding: 18px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 13px;
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

        .summary-table tbody td {
            padding: 16px 15px;
            font-size: 15px;
        }

        .summary-table tbody td:first-child {
            font-weight: 700;
            color: #6a1b9a;
        }

        .summary-table tbody td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .summary-table tbody td:nth-child(5) {
            color: #2e7d32;
            font-weight: 800;
        }

        /* Grand Total Row */
        .summary-table tfoot {
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
        }

        .summary-table tfoot td {
            padding: 20px 15px;
            color: white;
            font-weight: 800;
            font-size: 17px;
        }

        .summary-table tfoot td:first-child {
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .summary-table tfoot td:nth-child(n+2) {
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
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(156, 39, 176, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(156, 39, 176, 0.5);
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
            color: #9c27b0;
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
            <h1>Sales Summary</h1>
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
            $summaryByType = [];
            $summaryTotalQty = 0;
            $summaryTotalCost = 0;
            $summaryTotalRetail = 0;
        @endphp

            <!-- Detailed Products by Category -->
        @foreach($products as $categoryName => $items)
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
                    <th style="width: 120px;">ITEM NUMBER</th>
                    <th style="width: 300px;">DESCRIPTION</th>
                    <th style="width: 80px;">QTY</th>
                    <th style="width: 120px;">COST PRICE</th>
                    <th style="width: 140px;">TOTAL COST</th>
                    <th style="width: 120px;">RETAIL PRICE</th>
                    <th style="width: 140px;">TOTAL RETAIL</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @php
                        $qty = $item->item_count;
                        $costPrice = $item->price ?? $item->lens_price ?? 0;
                        $retailPrice = $item->retail_price ?? $item->lens_retail_price ?? 0;
                        $totalCostPrice = $costPrice * $qty;
                        $totalRetailPrice = $retailPrice * $qty;

                        $categoryQty += $qty;
                        $categoryTotalCost += $totalCostPrice;
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
                    </tr>
                @endforeach

                <!-- Category Total -->
                <tr class="category-total-row">
                    <td colspan="2">
                        <i class="bi bi-calculator"></i> <strong>{{ $categoryName }} Total</strong>
                    </td>
                    <td><strong>{{ $categoryQty }}</strong></td>
                    <td>-</td>
                    <td><strong>{{ number_format($categoryTotalCost, 2) }}</strong></td>
                    <td>-</td>
                    <td><strong>{{ number_format($categoryTotalRetail, 2) }}</strong></td>
                </tr>
                </tbody>
            </table>

            @php
                $summaryByType[$categoryName] = [
                    'qty' => $categoryQty,
                    'cost' => $categoryTotalCost,
                    'retail' => $categoryTotalRetail,
                ];
                $summaryTotalQty += $categoryQty;
                $summaryTotalCost += $categoryTotalCost;
                $summaryTotalRetail += $categoryTotalRetail;
            @endphp
        @endforeach

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-title">
                <i class="bi bi-receipt-cutoff"></i> Summary & Profit Analysis
            </div>

            <table class="summary-table">
                <thead>
                <tr>
                    <th>CATEGORY</th>
                    <th>TOTAL QTY</th>
                    <th>TOTAL COST</th>
                    <th>TOTAL RETAIL</th>
                    <th>PROFIT</th>
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
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Sales Data Found</h3>
            <p>There are no product sales for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Sales Summary Report<br>
            This is a system-generated report showing product sales grouped by category with profit analysis.<br>
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
