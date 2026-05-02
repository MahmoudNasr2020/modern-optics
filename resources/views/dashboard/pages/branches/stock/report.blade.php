@extends('dashboard.layouts.master')

@section('title', 'Stock Report - ' . $branch->name)

@section('content')

    <style>
        /* Page Background */
        .report-page {
            padding: 15px;
        }

        /* Main Box Styling */
        .box-report {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: visible;
            border: none;
        }

        .box-report .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-report .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-report .box-header .box-title {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-report .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 12px;
            border-radius: 10px;
            margin-right: 10px;
        }

        .box-report .box-header .box-tools {
            position: relative;
            z-index: 1;
        }

        .box-report .box-header .box-tools .btn {
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            margin-left: 5px;
        }

        .box-report .box-header .box-tools .btn:hover {
            background: white;
            color: #667eea;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Report Info Section */
        .report-info {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px 25px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .report-info p {
            margin: 8px 0;
            font-size: 14px;
            color: #666;
        }

        .report-info strong {
            color: #333;
            font-weight: 700;
        }

        .report-info i {
            color: #667eea;
            margin-right: 8px;
        }

        /* Enhanced Info Boxes */
        .info-box-enhanced {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .info-box-enhanced::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(0,0,0,0.03);
            border-radius: 50%;
        }

        .info-box-enhanced:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .info-box-enhanced .icon-container {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .info-box-enhanced.aqua .icon-container {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
        }

        .info-box-enhanced.blue .icon-container {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .info-box-enhanced.green .icon-container {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .info-box-enhanced.yellow .icon-container {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .info-box-text {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .info-box-number {
            font-size: 36px;
            font-weight: 900;
            color: #333;
            position: relative;
            z-index: 1;
        }

        .info-box-number small {
            font-size: 14px;
            color: #999;
            font-weight: 600;
            margin-left: 5px;
        }

        /* Filter Box */
        .filter-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box .form-control,
        .filter-box .btn {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(102,126,234,0.3);
        }

        .filter-box .btn-default {
            background: white;
            border: 2px solid #e0e6ed;
            color: #666;
            font-weight: 600;
        }

        .filter-box .btn-default:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        /* Section Header */
        .section-header {
            display: flex;
            align-items: center;
            margin: 40px 0 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-header .icon-box {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .section-header .icon-box i {
            font-size: 22px;
            color: white;
        }

        .section-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        /* Enhanced Table */
        .table-report {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .table-report thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table-report thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .table-report tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }

        .table-report tbody tr {
            transition: all 0.3s;
        }

        .table-report tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .table-report .total-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
        }

        .table-report .total-row td {
            border: none;
            padding: 18px 15px;
            font-size: 15px;
        }

        /* Product Barcode */
        .product-barcode {
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #667eea;
            font-size: 12px;
            border: 1px solid #e0e6ed;
        }

        /* Product Name */
        .product-name {
            font-weight: 700;
            color: #333;
            font-size: 14px;
        }

        /* Enhanced Labels */
        .label-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .label-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .label-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .label-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .label-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        /* Analysis Boxes */
        .analysis-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .analysis-box .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 20px;
            border: none;
        }

        .analysis-box .box-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .analysis-box .box-header h3 i {
            background: rgba(255,255,255,0.2);
            padding: 8px;
            border-radius: 8px;
            margin-right: 8px;
        }

        .analysis-box .box-body {
            padding: 20px;
        }

        .analysis-box .list-group-item {
            border: none;
            border-bottom: 1px solid #f0f2f5;
            padding: 15px;
            transition: all 0.3s;
        }

        .analysis-box .list-group-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .analysis-box .list-group-item i {
            font-size: 18px;
            margin-right: 10px;
        }

        .analysis-box .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* Top Products */
        .top-product-item {
            border: none;
            border-bottom: 1px solid #f0f2f5;
            padding: 18px;
            transition: all 0.3s;
            background: white;
        }

        .top-product-item:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(5px);
        }

        .top-product-item strong {
            color: #333;
            font-size: 15px;
        }

        .top-product-value {
            color: #27ae60;
            font-weight: 900;
            font-size: 16px;
        }

        /* Number Badge */
        .number-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            font-size: 13px;
        }

        /* Price Display */
        .price-display {
            font-weight: 700;
            color: #333;
            font-size: 14px;
        }

        /* Value Display */
        .value-display {
            font-weight: 900;
            color: #27ae60;
            font-size: 15px;
        }

        /* Min/Max Display */
        .minmax-display {
            color: #999;
            font-size: 13px;
        }

        /* Pagination Info */
        .pagination-info {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info strong {
            color: #667eea;
            font-size: 16px;
        }

        .pagination-info .per-page-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pagination-info select {
            border: 2px solid #e0e6ed;
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 600;
            color: #333;
        }

        /* Print Styles */
        .print-only { display: none !important; }

        @media print {
            @page { size: A4 landscape; margin: 10mm 8mm; }

            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

            /* hide everything screen-specific */
            .btn, .box-tools, .hide-on-print, .filter-box, .pagination,
            .pagination-info, .screen-only, .content-header, .main-header,
            .main-sidebar, .sidebar, nav, header, .box-header,
            .info-box-enhanced, .analysis-box, .grand-total-bar,
            .breadcrumb { display: none !important; }

            /* show print section */
            .print-only { display: block !important; }

            body, .report-page, .box-report, .box-body { background: #fff !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important; border: none !important; }

            /* print header */
            .print-only-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 3px solid #0f172a;
                padding-bottom: 8px;
                margin-bottom: 12px;
            }
            .print-only-header h2 { font-size: 16px; font-weight: 900; color: #0f172a; margin: 0; }
            .print-only-header .meta { font-size: 9px; color: #64748b; text-align: right; line-height: 1.6; }

            /* stats row */
            .print-stats { display: table !important; width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 10px; }
            .print-stat  { display: table-cell; background: #f8fafc; border: 1px solid #e2e8f0; border-top: 3px solid #0f172a; border-radius: 4px; padding: 6px 10px; text-align: center; }
            .print-stat .val { font-size: 18px; font-weight: 900; color: #0f172a; }
            .print-stat .lbl { font-size: 7px; color: #94a3b8; text-transform: uppercase; font-weight: 700; }

            /* table */
            .print-only table { width: 100%; border-collapse: collapse; font-size: 8.5px; }
            .print-only table thead tr { background: #0f172a !important; }
            .print-only table thead th { color: #fff !important; padding: 6px 5px; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; }
            .print-only table tbody tr:nth-child(even) { background: #f8fafc !important; }
            .print-only table tbody td { padding: 5px; border-bottom: 1px solid #f1f5f9; color: #1e293b; }
            .print-only table tfoot tr { background: #0f172a !important; }
            .print-only table tfoot td { color: #fff !important; padding: 6px 5px; font-weight: 700; }

            /* status colors in print */
            .ps-ok   { background: #dcfce7 !important; color: #15803d !important; padding: 1px 5px; border-radius: 4px; font-weight: 700; font-size: 7px; }
            .ps-low  { background: #fef9c3 !important; color: #854d0e !important; padding: 1px 5px; border-radius: 4px; font-weight: 700; font-size: 7px; }
            .ps-out  { background: #fee2e2 !important; color: #dc2626 !important; padding: 1px 5px; border-radius: 4px; font-weight: 700; font-size: 7px; }
            .ps-over { background: #dbeafe !important; color: #1d4ed8 !important; padding: 1px 5px; border-radius: 4px; font-weight: 700; font-size: 7px; }

            /* footer */
            .print-footer { margin-top: 10px; padding-top: 6px; border-top: 1px solid #e2e8f0; font-size: 7.5px; color: #94a3b8; display: flex !important; justify-content: space-between; }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-box-enhanced {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-file-earmark-bar-graph"></i> Stock Report
            <small>{{ $branch->name }}</small>
        </h1>
    </section>

    <div class="report-page">
        <div class="box box-primary box-report">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-file-text"></i> Stock Report - {{ $branch->name }}
                </h3>
                <div class="box-tools pull-right">
                    @can('view-stock')
                        <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-default btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    @endcan
                        @can('view-stock-reports')
                            <a href="{{ route('dashboard.branches.stock.report.excel', $branch) }}"
                               class="btn btn-sm"
                               style="background:#1b5e20;color:#fff;border-color:#1b5e20;">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </a>
                        @endcan
                        @can('view-stock-reports')
                            <a href="{{ route('dashboard.branches.stock.report.print', $branch) }}"
                               target="_blank"
                               class="btn btn-sm"
                               style="background:#c62828;color:#fff;border-color:#c62828;">
                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF / Print
                            </a>
                        @endcan
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Report Info -->
                <div class="report-info">
                    <p>
                        <i class="bi bi-building"></i>
                        <strong>Branch:</strong> {{ $branch->name }}
                    </p>
                    <p>
                        <i class="bi bi-calendar"></i>
                        <strong>Report Date:</strong> {{ now()->format('d M Y, h:i A') }}
                    </p>
                    @if($branch->address)
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            <strong>Address:</strong> {{ $branch->address }}
                        </p>
                    @endif
                </div>

                <!-- Summary Info Boxes -->
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box-enhanced aqua">
                            <div class="icon-container">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="info-box-text">Total Items</div>
                            <div class="info-box-number">{{ $allStocks->count() }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-box-enhanced blue">
                            <div class="icon-container">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="info-box-text">Total Units</div>
                            <div class="info-box-number">{{ $allStocks->sum('quantity') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-box-enhanced green">
                            <div class="icon-container">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="info-box-text">Stock Value</div>
                            <div class="info-box-number">
                                @php
                                    $totalValue = 0;
                                    foreach($allStocks as $stock) {
                                        if($stock->stockable) {
                                            $price = $stock->stockable->price ?? $stock->stockable->retail_price ?? 0;
                                            $totalValue += $stock->quantity * $price;
                                        }
                                    }
                                @endphp
                                {{ number_format($totalValue, 0) }}
                                <small>QAR</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-box-enhanced yellow">
                            <div class="icon-container">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="info-box-text">Low Stock</div>
                            <div class="info-box-number">
                                {{ $allStocks->filter(function($s) {
                                    return $s->quantity <= $s->min_quantity && $s->quantity > 0;
                                })->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.branches.stock.report', $branch) }}">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control"
                                       placeholder="🔍 Search by name or code..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Products</option>
                                    <option value="lens" {{ request('type') == 'lens' ? 'selected' : '' }}>Lenses</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                                    <option value="over" {{ request('status') == 'over' ? 'selected' : '' }}>Over Stock</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="per_page" class="form-control">
                                    <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 / Page</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / Page</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / Page</option>
                                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </div>

                            <div class="col-md-1">
                                <a href="{{ route('dashboard.branches.stock.report', $branch) }}" class="btn btn-default btn-block">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Pagination Info --}}
                @if($stocks->total() > 0)
                    <div class="pagination-info hide-on-print">
                        <div>
                            Showing <strong>{{ $stocks->firstItem() }}</strong> to <strong>{{ $stocks->lastItem() }}</strong> of <strong>{{ $stocks->total() }}</strong> items
                        </div>
                        <div class="per-page-selector">
                            <span>Items per page:</span>
                            <select onchange="window.location.href='{{ route('dashboard.branches.stock.report', $branch) }}?per_page=' + this.value + '&search={{ request('search') }}&type={{ request('type') }}&status={{ request('status') }}'">
                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                @endif

                <!-- Detailed Report Section — screen view (paginated) -->
                <div class="screen-only">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="bi bi-list-ul"></i>
                    </div>
                    <h4>Stock Details (Page {{ $stocks->currentPage() }} of {{ $stocks->lastPage() }})</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-report">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="8%">Code</th>
                            <th width="8%">Type</th>
                            <th width="24%">Item Description</th>
                            <th width="8%" class="text-center">Qty</th>
                            <th width="10%" class="text-right">Price</th>
                            <th width="12%" class="text-right">Total Value</th>
                            <th width="7%" class="text-center">Min</th>
                            <th width="7%" class="text-center">Max</th>
                            <th width="11%" class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $pageValue = 0;
                            $pageQuantity = 0;
                        @endphp

                        @forelse($stocks as $stock)
                            @php
                                $price = 0;
                                if($stock->stockable) {
                                    $price = $stock->stockable->price ?? $stock->stockable->retail_price ?? 0;
                                }
                                $itemValue = $stock->quantity * $price;
                                $pageValue += $itemValue;
                                $pageQuantity += $stock->quantity;
                            @endphp
                            <tr>
                                <td>
                                    <span class="number-badge">{{ $stocks->firstItem() + $loop->index }}</span>
                                </td>
                                <td>
                                    <span class="product-barcode">{{ $stock->item_code }}</span>
                                </td>
                                <td>
                                    @if($stock->stockable_type === 'App\\Product')
                                        <span class="label label-primary">Product</span>
                                    @else
                                        <span class="label label-info">Lens</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="product-name">{{ $stock->description }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="label-enhanced label-{{ $stock->quantity > $stock->min_quantity ? 'success' : ($stock->quantity > 0 ? 'warning' : 'danger') }}">
                                        {{ $stock->quantity }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="price-display">{{ number_format($price, 2) }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="value-display">{{ number_format($itemValue, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="minmax-display">{{ $stock->min_quantity }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="minmax-display">{{ $stock->max_quantity }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="label-enhanced label-{{ $stock->stock_status['class'] }}">
                                        {{ $stock->stock_status['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #ddd;"></i>
                                    <p style="color: #999; margin-top: 15px;">No stock items found</p>
                                </td>
                            </tr>
                        @endforelse

                        <!-- Page Total Row -->
                        @if($stocks->count() > 0)
                            <tr class="total-row">
                                <td colspan="4" class="text-right">
                                    <strong><i class="bi bi-calculator"></i> PAGE TOTAL:</strong>
                                </td>
                                <td class="text-center">
                                    <strong>{{ $pageQuantity }}</strong>
                                </td>
                                <td class="text-right">-</td>
                                <td class="text-right">
                                    <strong>{{ number_format($pageValue, 2) }} QAR</strong>
                                </td>
                                <td colspan="3" class="text-center">-</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($stocks->hasPages())
                    <div class="text-center hide-on-print" style="margin-top: 25px;">
                        {{ $stocks->appends(request()->query())->links() }}
                    </div>
                @endif
                </div>{{-- end .screen-only --}}

                {{-- ═══ PRINT-ONLY: Full table with ALL items ═══ --}}
                <div class="print-only">
                    <div style="margin-bottom:12px;">
                        <h3 style="margin:0 0 4px;font-size:16px;color:#333;">
                            Stock Report &mdash; {{ $branch->name }}
                        </h3>
                        <div style="font-size:11px;color:#666;">
                            Generated: {{ date('Y-m-d H:i') }} &nbsp;|&nbsp;
                            Total Items: {{ $allStocks->count() }} &nbsp;|&nbsp;
                            Total Units: {{ $allStocks->sum('quantity') }}
                        </div>
                    </div>
                    <table class="table table-report" style="font-size:10px;width:100%;border-collapse:collapse;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Price</th>
                            <th style="text-align:right;">Total Value</th>
                            <th style="text-align:center;">Min</th>
                            <th style="text-align:center;">Max</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $printTotal = 0; $printQty = 0; @endphp
                        @foreach($allStocks as $pi => $ps)
                            @php
                                $pp = 0;
                                if($ps->stockable) {
                                    $pp = $ps->stockable->price ?? $ps->stockable->retail_price ?? 0;
                                }
                                $pv = $ps->quantity * $pp;
                                $printTotal += $pv;
                                $printQty   += $ps->quantity;
                            @endphp
                            <tr>
                                <td>{{ $pi + 1 }}</td>
                                <td>{{ $ps->item_code }}</td>
                                <td>{{ $ps->stockable_type === 'App\\Product' ? 'Product' : 'Lens' }}</td>
                                <td>{{ $ps->description }}</td>
                                <td style="text-align:center;">{{ $ps->quantity }}</td>
                                <td style="text-align:right;">{{ number_format($pp, 2) }}</td>
                                <td style="text-align:right;">{{ number_format($pv, 2) }}</td>
                                <td style="text-align:center;">{{ $ps->min_quantity }}</td>
                                <td style="text-align:center;">{{ $ps->max_quantity }}</td>
                                <td style="text-align:center;">{{ $ps->stock_status['label'] ?? '' }}</td>
                            </tr>
                        @endforeach
                        <tr style="background:#27ae60;color:#fff;font-weight:700;">
                            <td colspan="4" style="text-align:right;">GRAND TOTAL</td>
                            <td style="text-align:center;">{{ $printQty }}</td>
                            <td>-</td>
                            <td style="text-align:right;">{{ number_format($printTotal, 2) }}</td>
                            <td colspan="3" style="text-align:center;">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>{{-- end .print-only --}}

                {{-- Grand Total (All Pages) --}}
                @if($stocks->count() > 0)
                    <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 20px; border-radius: 10px; margin-top: 30px; box-shadow: 0 4px 15px rgba(39,174,96,0.3);">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Items</div>
                                <div style="font-size: 28px; font-weight: 900;">{{ $allStocks->count() }}</div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Units</div>
                                <div style="font-size: 28px; font-weight: 900;">{{ $allStocks->sum('quantity') }}</div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Grand Total Value</div>
                                <div style="font-size: 28px; font-weight: 900;">{{ number_format($totalValue, 2) }} QAR</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Analysis Section -->
                <div class="row hide-on-print" style="margin-top: 40px;">
                    <div class="col-md-6">
                        <div class="analysis-box">
                            <div class="box-header">
                                <h3>
                                    <i class="bi bi-bar-chart-line"></i> Stock by Status
                                </h3>
                            </div>
                            <div class="box-body">
                                @php
                                    $statusCounts = [
                                        'normal' => $allStocks->filter(fn($s) => $s->stock_status['status'] == 'normal')->count(),
                                        'low_stock' => $allStocks->filter(fn($s) => $s->stock_status['status'] == 'low_stock')->count(),
                                        'out_of_stock' => $allStocks->filter(fn($s) => $s->stock_status['status'] == 'out_of_stock')->count(),
                                        'over_stock' => $allStocks->filter(fn($s) => $s->stock_status['status'] == 'over_stock')->count(),
                                    ];
                                @endphp

                                <ul class="list-group" style="margin: 0;">
                                    <li class="list-group-item">
                                        <i class="bi bi-check-circle text-success"></i>
                                        <strong>Good Stock</strong>
                                        <span class="badge bg-green pull-right">{{ $statusCounts['normal'] }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <strong>Low Stock</strong>
                                        <span class="badge bg-yellow pull-right">{{ $statusCounts['low_stock'] }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="bi bi-x-circle text-danger"></i>
                                        <strong>Out of Stock</strong>
                                        <span class="badge bg-red pull-right">{{ $statusCounts['out_of_stock'] }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="bi bi-info-circle text-info"></i>
                                        <strong>Over Stock</strong>
                                        <span class="badge bg-aqua pull-right">{{ $statusCounts['over_stock'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="analysis-box">
                            <div class="box-header">
                                <h3>
                                    <i class="bi bi-trophy"></i> Top 5 Items by Value
                                </h3>
                            </div>
                            <div class="box-body">
                                @php
                                    $topItems = $allStocks->sortByDesc(function($s) {
                                        if($s->stockable) {
                                            $price = $s->stockable->price ?? $s->stockable->retail_price ?? 0;
                                            return $s->quantity * $price;
                                        }
                                        return 0;
                                    })->take(5);
                                @endphp

                                <ul class="list-group" style="margin: 0;">
                                    @foreach($topItems as $stock)
                                        @php
                                            $price = 0;
                                            if($stock->stockable) {
                                                $price = $stock->stockable->price ?? $stock->stockable->retail_price ?? 0;
                                            }
                                            $value = $stock->quantity * $price;
                                        @endphp
                                        <li class="list-group-item top-product-item">
                                            <strong>{{ $stock->description }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-box"></i> {{ $stock->quantity }} × {{ number_format($price, 2) }} QAR
                                            </small>
                                            <div class="pull-right">
                                                <span class="top-product-value">{{ number_format($value, 2) }} QAR</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
