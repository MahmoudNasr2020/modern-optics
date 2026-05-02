<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock Transfers Report</title>
    <style>
        @page {
            margin: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            color: #2c3e50;
            line-height: 1.4;
        }

        /* Header */
        .report-header {
            background: #667eea;
            color: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .report-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .report-header p {
            font-size: 12px;
            opacity: 0.95;
        }

        /* Info Box */
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #667eea;
            border-radius: 5px;
        }

        .info-box h3 {
            font-size: 13px;
            color: #667eea;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .info-row {
            margin-bottom: 6px;
            font-size: 10px;
        }

        .info-row strong {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Statistics Grid */
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px 0;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 4px solid #667eea;
            border-radius: 6px;
        }

        .stat-label {
            font-size: 9px;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #667eea;
            line-height: 1;
        }

        /* Section Title */
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }

        table thead {
            background: #667eea;
        }

        table thead th {
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 10px;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table tbody tr:hover {
            background: #e8f0fe;
        }

        table tfoot {
            background: #ecf0f1;
            font-weight: 700;
        }

        table tfoot td {
            padding: 12px 8px;
            border-top: 3px solid #667eea;
            font-size: 11px;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
        }

        .badge.pending { background: #f39c12; }
        .badge.approved { background: #00c0ef; }
        .badge.in_transit { background: #3498db; }
        .badge.received { background: #27ae60; }
        .badge.canceled { background: #e74c3c; }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            margin-right: 5px;
        }

        .type-badge.product {
            background: #3498db;
            color: white;
        }

        .type-badge.lens {
            background: #9b59b6;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #bdc3c7;
        }

        .empty-state p {
            color: #95a5a6;
            font-size: 12px;
            font-style: italic;
        }

        /* Analysis Table */
        .analysis-table {
            margin-top: 20px;
        }

        .analysis-table tbody td {
            padding: 12px 8px;
        }

        .percentage-bar {
            background: #ecf0f1;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .percentage-fill {
            background: #667eea;
            height: 100%;
            border-radius: 10px;
        }

        /* Footer */
        .report-footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #95a5a6;
            padding: 10px;
            border-top: 1px solid #ecf0f1;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Text Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }
        .text-warning { color: #f39c12; }
        .text-info { color: #3498db; }
        .text-muted { color: #95a5a6; }

        .font-bold { font-weight: 700; }
        .font-normal { font-weight: 400; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="report-header">
    <h1>📊 Stock Transfer Report</h1>
    <p>Comprehensive Analysis of Inter-Branch Stock Transfers</p>
</div>

{{-- Report Information --}}
<div class="info-box">
    <h3>📋 Report Information</h3>
    <div class="info-row">
        <strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}
    </div>
    <div class="info-row">
        <strong>Total Records:</strong> {{ $transfers->count() }} transfer{{ $transfers->count() != 1 ? 's' : '' }}
    </div>
    @if($request->from_date || $request->to_date)
        <div class="info-row">
            <strong>Period:</strong>
            {{ $request->from_date ? \Carbon\Carbon::parse($request->from_date)->format('d M Y') : 'Start' }}
            →
            {{ $request->to_date ? \Carbon\Carbon::parse($request->to_date)->format('d M Y') : 'Now' }}
        </div>
    @endif
    @if($request->branch_id)
        @php
            $selectedBranch = $branches->find($request->branch_id);
        @endphp
        @if($selectedBranch)
            <div class="info-row">
                <strong>Branch Filter:</strong> {{ $selectedBranch->name }}
            </div>
        @endif
    @endif
    @if($request->status)
        <div class="info-row">
            <strong>Status Filter:</strong> {{ ucfirst(str_replace('_', ' ', $request->status)) }}
        </div>
    @endif
</div>

{{-- Statistics --}}
<div class="stats-grid">
    <div class="stat-box">
        <div class="stat-label">Total Requests</div>
        <div class="stat-value">{{ $transfers->count() }}</div>
    </div>

    <div class="stat-box">
        <div class="stat-label">Units Transferred</div>
        <div class="stat-value">{{ $transfers->where('status', 'received')->sum('quantity') }}</div>
    </div>

    <div class="stat-box">
        <div class="stat-label">In Progress</div>
        <div class="stat-value">{{ $transfers->whereIn('status', ['pending', 'approved', 'in_transit'])->count() }}</div>
    </div>

    <div class="stat-box">
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $transfers->where('status', 'received')->count() }}</div>
    </div>
</div>

{{-- Transfer Log --}}
<h3 class="section-title">📦 Transfer Movement Log</h3>

@if($transfers->count() > 0)
    <table>
        <thead>
        <tr>
            <th width="10%">Request #</th>
            <th width="10%">Date</th>
            <th width="8%">Type</th>
            <th width="25%">Item Description</th>
            <th width="13%">From Branch</th>
            <th width="13%">To Branch</th>
            <th width="8%" class="text-center">Qty</th>
            <th width="13%">Status</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalQty = 0;
            $productCount = 0;
            $lensCount = 0;
        @endphp

        @foreach($transfers as $transfer)
            @php
                if ($transfer->status == 'received') {
                    $totalQty += $transfer->quantity;
                }

                if ($transfer->stockable_type === 'App\\Product') {
                    $productCount++;
                } else {
                    $lensCount++;
                }
            @endphp
            <tr>
                <td class="font-bold">{{ $transfer->transfer_number }}</td>
                <td>{{ $transfer->transfer_date->format('d M Y') }}</td>
                <td>
                    @if($transfer->stockable_type === 'App\\Product')
                        <span class="type-badge product">PRODUCT</span>
                    @else
                        <span class="type-badge lens">LENS</span>
                    @endif
                </td>
                <td>{{ Str::limit($transfer->item_description, 40) }}</td>
                <td>{{ $transfer->fromBranch->name }}</td>
                <td>{{ $transfer->toBranch->name }}</td>
                <td class="text-center font-bold">{{ $transfer->quantity }}</td>
                <td>
                        <span class="badge {{ $transfer->status }}">
                            {{ ucfirst(str_replace('_', ' ', $transfer->status)) }}
                        </span>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6" class="text-right">
                <strong>Total Units Received:</strong>
            </td>
            <td class="text-center">
                <strong class="text-success">{{ $totalQty }}</strong>
            </td>
            <td>-</td>
        </tr>
        </tfoot>
    </table>
@else
    <div class="empty-state">
        <p>📭 No transfer operations match the specified filters</p>
    </div>
@endif

{{-- Page Break --}}
<div class="page-break"></div>

{{-- Analysis Section --}}
<h3 class="section-title">📊 Statistical Analysis</h3>

{{-- Status Analysis --}}
<table class="analysis-table">
    <thead>
    <tr>
        <th width="30%">Status</th>
        <th width="20%" class="text-center">Count</th>
        <th width="20%" class="text-center">Percentage</th>
        <th width="30%">Visual</th>
    </tr>
    </thead>
    <tbody>
    @php
        $statusData = [
            ['name' => 'Pending', 'status' => 'pending', 'color' => '#f39c12'],
            ['name' => 'Approved', 'status' => 'approved', 'color' => '#00c0ef'],
            ['name' => 'In Transit', 'status' => 'in_transit', 'color' => '#3498db'],
            ['name' => 'Received', 'status' => 'received', 'color' => '#27ae60'],
            ['name' => 'Canceled', 'status' => 'canceled', 'color' => '#e74c3c'],
        ];

        $total = $transfers->count();
    @endphp

    @foreach($statusData as $item)
        @php
            $count = $transfers->where('status', $item['status'])->count();
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
        @endphp
        <tr>
            <td class="font-bold">{{ $item['name'] }}</td>
            <td class="text-center">{{ $count }}</td>
            <td class="text-center">{{ $percentage }}%</td>
            <td>
                <div class="percentage-bar">
                    <div class="percentage-fill" style="width: {{ $percentage }}%; background: {{ $item['color'] }};"></div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- Type Analysis --}}
@if($transfers->count() > 0)
    <h3 class="section-title" style="margin-top: 30px;">📦 Item Type Distribution</h3>

    <table>
        <thead>
        <tr>
            <th width="40%">Type</th>
            <th width="30%" class="text-center">Count</th>
            <th width="30%" class="text-center">Percentage</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="font-bold">
                <span class="type-badge product">PRODUCT</span> Products
            </td>
            <td class="text-center">{{ $productCount }}</td>
            <td class="text-center">{{ $total > 0 ? round(($productCount / $total) * 100, 1) : 0 }}%</td>
        </tr>
        <tr>
            <td class="font-bold">
                <span class="type-badge lens">LENS</span> Lenses
            </td>
            <td class="text-center">{{ $lensCount }}</td>
            <td class="text-center">{{ $total > 0 ? round(($lensCount / $total) * 100, 1) : 0 }}%</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-right font-bold">Total:</td>
            <td class="text-center font-bold">{{ $total }}</td>
            <td class="text-center font-bold">100%</td>
        </tr>
        </tfoot>
    </table>
@endif

{{-- Summary Box --}}
<div class="info-box" style="margin-top: 30px; border-left-color: #27ae60;">
    <h3 style="color: #27ae60;">✅ Summary</h3>
    <div class="info-row">
        <strong>Total Transfer Requests:</strong> {{ $transfers->count() }}
    </div>
    <div class="info-row">
        <strong>Successfully Completed:</strong> {{ $transfers->where('status', 'received')->count() }} ({{ $total > 0 ? round(($transfers->where('status', 'received')->count() / $total) * 100, 1) : 0 }}%)
    </div>
    <div class="info-row">
        <strong>Total Units Transferred:</strong> {{ $totalQty }}
    </div>
    <div class="info-row">
        <strong>Products Transferred:</strong> {{ $productCount }}
    </div>
    <div class="info-row">
        <strong>Lenses Transferred:</strong> {{ $lensCount }}
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
