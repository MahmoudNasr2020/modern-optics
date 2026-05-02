<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Branches Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        thead tr {
            background: #667eea;
            color: white;
        }

        thead th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        tbody td {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr.total-row {
            background: #764ba2;
            color: white;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-main { background: #ffd700; color: #333; }
        .badge-excellent { background: #27ae60; }
        .badge-very-good { background: #3498db; }
        .badge-good { background: #00c0ef; }
        .badge-improvement { background: #f39c12; }
        .badge-stock-abundant { background: #27ae60; }
        .badge-stock-good { background: #00c0ef; }
        .badge-stock-medium { background: #f39c12; }
        .badge-stock-low { background: #e74c3c; }

    </style>
</head>
<body>

<h2>Sales Statistics</h2>
<table>
    <thead>
    <tr>
        <th>Branch</th>
        <th class="text-center">Invoices</th>
        <th class="text-right">Total Sales</th>
        <th class="text-right">Avg Invoice</th>
        <th class="text-center">Percentage</th>
        <th class="text-center">Rating</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalSales = $salesByBranch->sum('total_sales') ?: 1;
        $totalInvoices = $salesByBranch->sum('invoices_count') ?: 0;
    @endphp

    @foreach($salesByBranch as $branch)
        @php
            $avgInvoice = $branch->invoices_count > 0 ? $branch->total_sales / $branch->invoices_count : 0;
            $percentage = $totalSales > 0 ? ($branch->total_sales / $totalSales) * 100 : 0;
        @endphp
        <tr>
            <td>{{ $branch->name }} @if($branch->is_main) <span class="badge badge-main">Main</span> @endif</td>
            <td class="text-center">{{ $branch->invoices_count }}</td>
            <td class="text-right">{{ number_format($branch->total_sales,2) }} SAR</td>
            <td class="text-right">{{ number_format($avgInvoice,2) }} SAR</td>
            <td class="text-center">{{ number_format($percentage,1) }}%</td>
            <td class="text-center">
                @if($percentage >= 40)
                    <span class="badge badge-excellent">Excellent</span>
                @elseif($percentage >= 25)
                    <span class="badge badge-very-good">Very Good</span>
                @elseif($percentage >= 15)
                    <span class="badge badge-good">Good</span>
                @else
                    <span class="badge badge-improvement">Improve</span>
                @endif
            </td>
        </tr>
    @endforeach

    <tr class="total-row">
        <td>TOTAL</td>
        <td class="text-center">{{ $totalInvoices }}</td>
        <td class="text-right">{{ number_format($totalSales,2) }} SAR</td>
        <td class="text-right">{{ $totalInvoices > 0 ? number_format($totalSales / $totalInvoices,2) : 0 }} SAR</td>
        <td class="text-center">100%</td>
        <td class="text-center">-</td>
    </tr>
    </tbody>
</table>

<h2>Stock Statistics</h2>
<table>
    <thead>
    <tr>
        <th>Branch</th>
        <th class="text-center">Products</th>
        <th class="text-center">Total Quantity</th>
        <th class="text-center">Avg/Product</th>
        <th class="text-center">Status</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalProducts = $stockByBranch->sum('products_count') ?: 0;
        $totalQuantity = $stockByBranch->sum('total_quantity') ?: 0;
    @endphp

    @foreach($stockByBranch as $branch)
        @php
            $avgPerProduct = $branch->products_count > 0 ? $branch->total_quantity / $branch->products_count : 0;
        @endphp
        <tr>
            <td>{{ $branch->name }} @if($branch->is_main) <span class="badge badge-main">Main</span> @endif</td>
            <td class="text-center">{{ $branch->products_count }}</td>
            <td class="text-center">{{ $branch->total_quantity }} pieces</td>
            <td class="text-center">{{ number_format($avgPerProduct,1) }} pieces</td>
            <td class="text-center">
                @if($branch->total_quantity > 500)
                    <span class="badge badge-stock-abundant">Abundant</span>
                @elseif($branch->total_quantity > 200)
                    <span class="badge badge-stock-good">Good</span>
                @elseif($branch->total_quantity > 50)
                    <span class="badge badge-stock-medium">Medium</span>
                @else
                    <span class="badge badge-stock-low">Low</span>
                @endif
            </td>
        </tr>
    @endforeach

    <tr class="total-row">
        <td>TOTAL</td>
        <td class="text-center">{{ $totalProducts }}</td>
        <td class="text-center">{{ $totalQuantity }} pieces</td>
        <td class="text-center">{{ $totalProducts > 0 ? number_format($totalQuantity / $totalProducts,1) : 0 }} pieces</td>
        <td class="text-center">-</td>
    </tr>
    </tbody>
</table>

</body>
</html>
