<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Stock Report — {{ $branch->name }}</title>
    <style>
        /* ── Reset & Base ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            padding: 30px 20px;
        }

        /* ── Wrapper ── */
        .page {
            max-width: 1280px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 30px rgba(0,0,0,.12);
            overflow: hidden;
        }

        /* ── Top Header Bar ── */
        .report-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            padding: 28px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .report-header::after {
            content: '';
            position: absolute;
            right: -60px; top: -60px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }
        .report-header::before {
            content: '';
            position: absolute;
            right: 80px; bottom: -80px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .header-left { display: flex; align-items: center; gap: 20px; position: relative; z-index: 1; }
        .header-logo img { height: 64px; width: auto; filter: brightness(0) invert(1); }
        .header-title h1 { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: .5px; }
        .header-title .sub { font-size: 12px; color: #94a3b8; margin-top: 3px; letter-spacing: 1px; text-transform: uppercase; }
        .header-right { text-align: right; position: relative; z-index: 1; }
        .header-right .branch-badge {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .header-right .meta { font-size: 11px; color: #94a3b8; line-height: 1.8; }

        /* ── Accent Bar ── */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
        }

        /* ── Stats Row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .stat-card {
            padding: 20px 24px;
            text-align: center;
            border-right: 1px solid #e2e8f0;
            position: relative;
        }
        .stat-card:last-child { border-right: none; }
        .stat-card .s-val {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .s-lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 700;
            color: #94a3b8;
        }
        .stat-card.blue   .s-val { color: #3b82f6; }
        .stat-card.purple .s-val { color: #8b5cf6; }
        .stat-card.green  .s-val { color: #10b981; }
        .stat-card.amber  .s-val { color: #f59e0b; }
        .stat-card.red    .s-val { color: #ef4444; }

        /* ── Table Wrapper ── */
        .table-wrap { padding: 24px 28px 32px; }

        .section-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Main Table ── */
        .stock-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .stock-table thead tr {
            background: #0f172a;
        }
        .stock-table thead th {
            padding: 12px 10px;
            color: #e2e8f0;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            white-space: nowrap;
        }
        .stock-table thead th:first-child { border-radius: 0; padding-left: 16px; }
        .stock-table thead th:last-child  { padding-right: 16px; }

        .stock-table tbody tr { border-bottom: 1px solid #f1f5f9; }
        .stock-table tbody tr:nth-child(even) { background: #f8fafc; }
        .stock-table tbody tr:hover { background: #eff6ff; }
        .stock-table tbody td {
            padding: 10px 10px;
            vertical-align: middle;
            color: #334155;
        }
        .stock-table tbody td:first-child { padding-left: 16px; }
        .stock-table tbody td:last-child  { padding-right: 16px; }

        /* ── Cell Styles ── */
        .row-num {
            width: 30px;
            height: 30px;
            background: #f1f5f9;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
        }
        .item-code {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: 700;
            color: #3b82f6;
            background: #eff6ff;
            padding: 3px 8px;
            border-radius: 5px;
            border: 1px solid #bfdbfe;
        }
        .type-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .type-product { background: #e0e7ff; color: #3730a3; }
        .type-lens    { background: #ccfbf1; color: #065f46; }

        .item-name { font-weight: 600; color: #1e293b; font-size: 12.5px; }

        .qty-badge {
            font-weight: 800;
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
        }
        .qty-ok   { background: #dcfce7; color: #15803d; }
        .qty-low  { background: #fef9c3; color: #92400e; }
        .qty-out  { background: #fee2e2; color: #dc2626; }
        .qty-over { background: #dbeafe; color: #1d4ed8; }

        .price-val { font-weight: 700; color: #0f172a; }
        .value-val { font-weight: 800; color: #059669; }
        .min-val, .max-val { color: #94a3b8; font-size: 12px; }

        .status-pill {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .3px;
            display: inline-block;
        }
        .s-normal  { background: #dcfce7; color: #15803d; }
        .s-low     { background: #fef9c3; color: #92400e; }
        .s-out     { background: #fee2e2; color: #dc2626; }
        .s-over    { background: #dbeafe; color: #1d4ed8; }

        /* ── Footer Total Row ── */
        .total-row td {
            background: #0f172a !important;
            color: #fff !important;
            font-weight: 800;
            font-size: 13px;
            padding: 14px 10px !important;
            border: none !important;
        }
        .total-row td:first-child { padding-left: 16px !important; border-radius: 0; }
        .total-row td:last-child  { padding-right: 16px !important; }

        /* ── Page Footer ── */
        .report-footer {
            margin: 0 28px;
            padding: 16px 0;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
        }
        .report-footer strong { color: #475569; }

        /* ── Print Button (screen only) ── */
        .print-btn-wrap {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 999;
        }
        .print-btn {
            background: linear-gradient(135deg, #0f172a, #1e3a5f);
            color: #fff;
            border: none;
            padding: 13px 26px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(15,23,42,.35);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
            text-decoration: none;
        }
        .print-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15,23,42,.45); }
        .print-btn.back  { background: linear-gradient(135deg, #475569, #334155); }

        /* ── @media print ── */
        @media print {
            @page { size: A4 landscape; margin: 8mm 10mm; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

            body { background: #fff !important; padding: 0 !important; }
            .page { box-shadow: none !important; border-radius: 0 !important; max-width: 100%; }
            .print-btn-wrap { display: none !important; }

            .report-header { padding: 16px 20px !important; }
            .header-logo img { height: 44px !important; }
            .header-title h1 { font-size: 16px !important; }

            .stats-row { break-inside: avoid; }

            .stock-table { font-size: 8.5px !important; }
            .stock-table thead th { padding: 7px 6px !important; font-size: 8px !important; }
            .stock-table tbody td { padding: 6px 6px !important; }
            .stock-table tbody td:first-child { padding-left: 10px !important; }
            .item-name { font-size: 8.5px !important; }
            .row-num { width: 22px; height: 22px; font-size: 9px !important; }
            .item-code { font-size: 8px !important; padding: 2px 5px !important; }
            .qty-badge { font-size: 9px !important; padding: 2px 7px !important; }
            .status-pill { font-size: 8px !important; padding: 2px 6px !important; }
            .type-badge { font-size: 8px !important; padding: 2px 6px !important; }

            .table-wrap { padding: 12px 14px 20px !important; }
            .report-footer { margin: 0 14px !important; font-size: 8px !important; }

            .stat-card .s-val { font-size: 20px !important; }
        }
    </style>
</head>
<body>

@php
    $totalQty   = $allStocks->sum('quantity');
    $totalValue = 0;
    foreach ($allStocks as $s) {
        if ($s->stockable) {
            $p = $s->stockable->price ?? $s->stockable->retail_price ?? 0;
            $totalValue += $s->quantity * $p;
        }
    }
    $countNormal = $allStocks->filter(function($s) { return ($s->stock_status['status'] ?? '') === 'normal'; })->count();
    $countLow    = $allStocks->filter(function($s) { return ($s->stock_status['status'] ?? '') === 'low_stock'; })->count();
    $countOut    = $allStocks->filter(function($s) { return ($s->stock_status['status'] ?? '') === 'out_of_stock'; })->count();
    $countOver   = $allStocks->filter(function($s) { return ($s->stock_status['status'] ?? '') === 'over_stock'; })->count();
@endphp

<div class="page">

    {{-- ═══ Header ═══ --}}
    <div class="report-header">
        <div class="header-left">
            <div class="header-logo">
                <img src="{{ asset('assets/img/modern.png') }}" alt="Logo">
            </div>
            <div class="header-title">
                <h1>Stock Inventory Report</h1>
                <div class="sub">Branch Stock — Full Snapshot</div>
            </div>
        </div>
        <div class="header-right">
            <div class="branch-badge">
                📍 {{ $branch->name }}
                @if($branch->code) &nbsp;·&nbsp; {{ $branch->code }} @endif
            </div>
            <div class="meta">
                Generated: {{ now()->format('d M Y, h:i A') }}<br>
                @if($branch->address) {{ $branch->address }}<br>@endif
                Total Items: <strong style="color:#cbd5e1;">{{ $allStocks->count() }}</strong>
            </div>
        </div>
    </div>

    {{-- ═══ Accent Bar ═══ --}}
    <div class="accent-bar"></div>

    {{-- ═══ Stats ═══ --}}
    <div class="stats-row">
        <div class="stat-card blue">
            <div class="s-val">{{ $allStocks->count() }}</div>
            <div class="s-lbl">Total Items</div>
        </div>
        <div class="stat-card purple">
            <div class="s-val">{{ number_format($totalQty) }}</div>
            <div class="s-lbl">Total Units</div>
        </div>
        <div class="stat-card green">
            <div class="s-val">{{ number_format($totalValue, 0) }}</div>
            <div class="s-lbl">Stock Value (QAR)</div>
        </div>
        <div class="stat-card amber">
            <div class="s-val">{{ $countLow }}</div>
            <div class="s-lbl">Low Stock</div>
        </div>
        <div class="stat-card red">
            <div class="s-val">{{ $countOut }}</div>
            <div class="s-lbl">Out of Stock</div>
        </div>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="table-wrap">

        <div class="section-label">
            Stock Details — {{ $allStocks->count() }} items
        </div>

        <table class="stock-table">
            <thead>
            <tr>
                <th style="width:42px; text-align:center;">#</th>
                <th style="width:110px;">Code</th>
                <th style="width:80px;">Type</th>
                <th>Description</th>
                <th style="width:90px; text-align:center;">Qty</th>
            </tr>
            </thead>
            <tbody>
            @php $grandQty = 0; @endphp
            @foreach($allStocks as $i => $stock)
                @php $grandQty += $stock->quantity; @endphp
                <tr>
                    <td style="text-align:center;">
                        <span class="row-num">{{ $i + 1 }}</span>
                    </td>
                    <td>
                        <span class="item-code">{{ $stock->item_code }}</span>
                    </td>
                    <td>
                        @if($stock->stockable_type === 'App\\Product')
                            <span class="type-badge type-product">Product</span>
                        @else
                            <span class="type-badge type-lens">Lens</span>
                        @endif
                    </td>
                    <td>
                        <span class="item-name">{{ $stock->description }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="qty-badge qty-ok">{{ $stock->quantity }}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align:right; padding-right:16px !important; letter-spacing:.5px;">
                    GRAND TOTAL
                </td>
                <td style="text-align:center;">{{ number_format($grandQty) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    {{-- ═══ Footer ═══ --}}
    <div class="report-footer">
        <span>
            <strong>{{ $branch->name }}</strong>
            @if($branch->address) &nbsp;·&nbsp; {{ $branch->address }}@endif
        </span>
        <span>Generated {{ now()->format('d M Y — h:i A') }} &nbsp;·&nbsp; Stock Inventory Report</span>
    </div>

</div>{{-- /page --}}

{{-- ═══ Floating Buttons (screen only) ═══ --}}
<div class="print-btn-wrap">
    <a href="{{ url()->previous() }}" class="print-btn back">
        ← Back
    </a>
    <button onclick="window.print()" class="print-btn">
        🖨️ Print / Save PDF
    </button>
</div>

</body>
</html>
