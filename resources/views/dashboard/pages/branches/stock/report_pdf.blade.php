<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock Report - {{ $branch->name }}</title>
    <style>
        @page {
            margin: 15px;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            color: #2c3e50;
            line-height: 1.3;
        }

        /* Header */
        .report-header {
            background: #667eea;
            color: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .report-header h1 {
            font-size: 22px;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .report-header p {
            font-size: 11px;
            opacity: 0.95;
        }

        /* Info Box */
        .info-box {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .info-box h3 {
            font-size: 11px;
            color: #667eea;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .info-row {
            margin-bottom: 4px;
            font-size: 9px;
        }

        .info-row strong {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Statistics Grid */
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-spacing: 8px 0;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            background: #f8f9fa;
            padding: 12px;
            text-align: center;
            border-top: 3px solid #667eea;
            border-radius: 5px;
        }

        .stat-label {
            font-size: 8px;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 6px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 900;
            color: #667eea;
            line-height: 1;
        }

        .stat-value small {
            font-size: 9px;
            color: #95a5a6;
            font-weight: 600;
        }

        /* Section Title */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #2c3e50;
            margin: 20px 0 12px 0;
            padding-bottom: 8px;
            border-bottom: 3px solid #667eea;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: white;
        }

        table thead {
            background: #667eea;
        }

        table thead th {
            color: white;
            padding: 10px 6px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        table tbody td {
            padding: 8px 6px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 9px;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table tfoot {
            background: #667eea;
            color: white;
            font-weight: 700;
        }

        table tfoot td {
            padding: 10px 6px;
            border-top: 3px solid #667eea;
            font-size: 10px;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
        }

        .badge.success { background: #27ae60; }
        .badge.warning { background: #f39c12; }
        .badge.danger { background: #e74c3c; }
        .badge.info { background: #3498db; }
        .badge.primary { background: #667eea; }
        .badge.secondary { background: #95a5a6; }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .type-badge.product {
            background: #3498db;
            color: white;
        }

        .type-badge.lens {
            background: #9b59b6;
            color: white;
        }

        /* Number Badge */
        .number-badge {
            background: #667eea;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 8px;
        }

        /* Code Display */
        .code-display {
            background: #f8f9fa;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #667eea;
            font-size: 8px;
            border: 1px solid #e0e6ed;
        }

        /* Text Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }
        .text-warning { color: #f39c12; }
        .text-muted { color: #95a5a6; }

        .font-bold { font-weight: 700; }

        /* Analysis Section */
        .analysis-grid {
            display: table;
            width: 100%;
            margin-top: 15px;
            border-spacing: 10px 0;
        }

        .analysis-box {
            display: table-cell;
            width: 50%;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
            border-top: 3px solid #667eea;
        }

        .analysis-title {
            font-size: 11px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .analysis-item {
            padding: 8px;
            margin-bottom: 6px;
            background: white;
            border-radius: 4px;
            font-size: 9px;
        }

        .analysis-item:last-child {
            margin-bottom: 0;
        }

        .analysis-value {
            float: right;
            font-weight: 700;
            color: #667eea;
        }

        /* Footer */
        .report-footer {
            position: fixed;
            bottom: 8px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #95a5a6;
            padding: 8px;
            border-top: 1px solid #ecf0f1;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="report-header">
    <h1>📦 Stock Inventory Report</h1>
    <p>{{ $branch->name }} - Complete Stock Analysis</p>
</div>

{{-- Report Information --}}
<div class="info-box">
    <h3>📋 Report Information</h3>
    <div class="info-row">
        <strong>Branch:</strong> {{ $branch->name }}
        @if($branch->code)
            ({{ $branch->code }})
        @endif
    </div>
    <div class="info-row">
        <strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}
    </div>
    @if($branch->address)
        <div class="info-row">
            <strong>Address:</strong> {{ $branch->address }}
        </div>
    @endif
    <div class="info-row">
        <strong>Total Items:</strong> {{ $stocks->count() }}
    </div>
</div>

{{-- Statistics --}}
@php
    $totalValue = 0;
    $totalQuantity = 0;
    $lowStockCount = 0;

    foreach($stocks as $stock) {
        $totalQuantity += $stock->quantity;

        if($stock->quantity <= $stock->min_quantity && $stock->quantity > 0) {
            $lowStockCount++;
        }

        if($stock->stockable) {
            $price = $stock->stockable->price ?? $stock->stockable->retail_price ?? 0;
            $totalValue += $stock->quantity * $price;
        }
    }
@endphp

<div class="stats-grid">
    <div class="stat-box">
        <div class="stat-label">Total Items</div>
        <div class="stat-value">{{ $stocks->count() }}</div>
    </div>

    <div class="stat-box">
        <div class="stat-label">Total Units</div>
        <div class="stat-value">{{ $totalQuantity }}</div>
    </div>

    <div class="stat-box">
        <div class="stat-label">Stock Value</div>
        <div class="stat-value">{{ number_format($totalValue, 0) }} <small>QAR</small></div>
    </div>

    <div class="stat-box">
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-value">{{ $lowStockCount }}</div>
    </div>
</div>

{{-- Stock Details Table --}}
<h3 class="section-title">📊 Complete Stock Details</h3>

@if($stocks->count() > 0)
    <table>
        <thead>
        <tr>
            <th width="4%">#</th>
            <th width="8%">Code</th>
            <th width="8%">Type</th>
            <th width="30%">Item Description</th>
            <th width="8%" class="text-center">Qty</th>
            <th width="10%" class="text-right">Price</th>
            <th width="12%" class="text-right">Value</th>
            <th width="6%" class="text-center">Min</th>
            <th width="6%" class="text-center">Max</th>
            <th width="8%" class="text-center">Status</th>
        </tr>
        </thead>
        <tbody>
        @php
            $runningTotal = 0;
        @endphp

        @foreach($stocks as $stock)
            @php
                $price = 0;
                if($stock->stockable) {
                    $price = $stock->stockable->price ?? $stock->stockable->retail_price ?? 0;
                }
                $itemValue = $stock->quantity * $price;
                $runningTotal += $itemValue;
            @endphp
            <tr>
                <td class="text-center">
                    <span class="number-badge">{{ $loop->iteration }}</span>
                </td>
                <td>
                    <span class="code-display">{{ $stock->item_code }}</span>
                </td>
                <td>
                    @if($stock->stockable_type === 'App\\Product')
                        <span class="type-badge product">PROD</span>
                    @else
                        <span class="type-badge lens">LENS</span>
                    @endif
                </td>
                <td class="font-bold">{{ Str::limit($stock->description, 50) }}</td>
                <td class="text-center">
                        <span class="badge {{ $stock->quantity > $stock->min_quantity ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger') }}">
                            {{ $stock->quantity }}
                        </span>
                </td>
                <td class="text-right font-bold">{{ number_format($price, 2) }}</td>
                <td class="text-right text-success font-bold">{{ number_format($itemValue, 2) }}</td>
                <td class="text-center text-muted">{{ $stock->min_quantity }}</td>
                <td class="text-center text-muted">{{ $stock->max_quantity }}</td>
                <td class="text-center">
                        <span class="badge {{ $stock->stock_status['class'] }}">
                            @if($stock->stock_status['status'] == 'normal')
                                OK
                            @elseif($stock->stock_status['status'] == 'low_stock')
                                LOW
                            @elseif($stock->stock_status['status'] == 'out_of_stock')
                                OUT
                            @else
                                OVER
                            @endif
                        </span>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
            <td class="text-center"><strong>{{ $totalQuantity }}</strong></td>
            <td class="text-right">-</td>
            <td class="text-right"><strong>{{ number_format($totalValue, 2) }} QAR</strong></td>
            <td colspan="3" class="text-center">-</td>
        </tr>
        </tfoot>
    </table>
@else
    <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 6px; border: 2px dashed #bdc3c7;">
        <p style="color: #95a5a6; font-size: 11px;">📭 No stock items found</p>
    </div>
@endif

{{-- Analysis Section --}}
<h3 class="section-title">📈 Stock Analysis</h3>

<div class="analysis-grid">
    {{-- Status Breakdown --}}
    <div class="analysis-box">
        <div class="analysis-title">📊 By Status</div>

        @php
            $statusCounts = [
                'normal' => $stocks->filter(fn($s) => $s->stock_status['status'] == 'normal')->count(),
                'low_stock' => $stocks->filter(fn($s) => $s->stock_status['status'] == 'low_stock')->count(),
                'out_of_stock' => $stocks->filter(fn($s) => $s->stock_status['status'] == 'out_of_stock')->count(),
                'over_stock' => $stocks->filter(fn($s) => $s->stock_status['status'] == 'over_stock')->count(),
            ];
        @endphp

        <div class="analysis-item">
            <strong>✓ Good Stock</strong>
            <span class="analysis-value">{{ $statusCounts['normal'] }}</span>
        </div>
        <div class="analysis-item">
            <strong>⚠ Low Stock</strong>
            <span class="analysis-value">{{ $statusCounts['low_stock'] }}</span>
        </div>
        <div class="analysis-item">
            <strong>✗ Out of Stock</strong>
            <span class="analysis-value">{{ $statusCounts['out_of_stock'] }}</span>
        </div>
        <div class="analysis-item">
            <strong>ⓘ Over Stock</strong>
            <span class="analysis-value">{{ $statusCounts['over_stock'] }}</span>
        </div>
    </div>

    {{-- Top 5 Items --}}
    <div class="analysis-box">
        <div class="analysis-title">🏆 Top 5 by Value</div>

        @php
            $topItems = $stocks->sortByDesc(function($s) {
                if($s->stockable) {
                    $price = $s->stockable->price ?? $s->stockable->retail_price ?? 0;
                    return $s->quantity * $price;
                }
                return 0;
            })->take(5);
        @endphp

        @foreach($topItems as $item)
            @php
                $price = 0;
                if($item->stockable) {
                    $price = $item->stockable->price ?? $item->stockable->retail_price ?? 0;
                }
                $value = $item->quantity * $price;
            @endphp
            <div class="analysis-item">
                <strong>{{ Str::limit($item->description, 25) }}</strong>
                <br>
                <small class="text-muted">{{ $item->quantity }} × {{ number_format($price, 2) }}</small>
                <span class="analysis-value text-success">{{ number_format($value, 2) }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- Type Distribution --}}
@php
    $productCount = $stocks->filter(fn($s) => $s->stockable_type === 'App\\Product')->count();
    $lensCount = $stocks->filter(fn($s) => $s->stockable_type === 'App\\glassLense')->count();
@endphp

<h3 class="section-title">📦 Item Type Distribution</h3>

<table>
    <thead>
    <tr>
        <th width="50%">Type</th>
        <th width="25%" class="text-center">Count</th>
        <th width="25%" class="text-center">Percentage</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><span class="type-badge product">PRODUCT</span> <strong>Products</strong></td>
        <td class="text-center font-bold">{{ $productCount }}</td>
        <td class="text-center font-bold">{{ $stocks->count() > 0 ? round(($productCount / $stocks->count()) * 100, 1) : 0 }}%</td>
    </tr>
    <tr>
        <td><span class="type-badge lens">LENS</span> <strong>Lenses</strong></td>
        <td class="text-center font-bold">{{ $lensCount }}</td>
        <td class="text-center font-bold">{{ $stocks->count() > 0 ? round(($lensCount / $stocks->count()) * 100, 1) : 0 }}%</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="text-right"><strong>Total:</strong></td>
        <td class="text-center"><strong>{{ $stocks->count() }}</strong></td>
        <td class="text-center"><strong>100%</strong></td>
    </tr>
    </tfoot>
</table>

{{-- Summary Box --}}
<div class="info-box" style="margin-top: 15px; border-left-color: #27ae60;">
    <h3 style="color: #27ae60;">✅ Summary</h3>
    <div class="info-row">
        <strong>Branch:</strong> {{ $branch->name }}
    </div>
    <div class="info-row">
        <strong>Total Items:</strong> {{ $stocks->count() }}
    </div>
    <div class="info-row">
        <strong>Total Units:</strong> {{ $totalQuantity }}
    </div>
    <div class="info-row">
        <strong>Total Value:</strong> {{ number_format($totalValue, 2) }} QAR
    </div>
    <div class="info-row">
        <strong>Low Stock Alerts:</strong> {{ $lowStockCount }}
    </div>
</div>

{{-- Footer --}}
<div class="report-footer">
    <p>
        Generated by Stock Management System | {{ config('app.name') }} |
        {{ now()->format('d M Y, h:i A') }}
    </p>
</div>

</body>
</html>
