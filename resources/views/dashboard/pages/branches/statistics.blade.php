@extends('dashboard.layouts.master')

@section('content')

    <style>
        /* General Enhancements */
        .statistics-page {
          /*  background: #ecf0f5;*/
            padding: 15px;
        }

        /* Main Box Styling */
        .box-statistics {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: visible;
            border: none;
        }

        .box-statistics .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-statistics .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-statistics .box-header .box-title {
            font-size: 26px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-statistics .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 12px;
            border-radius: 10px;
            margin-right: 10px;
        }

        .box-statistics .box-header .box-tools {
            position: relative;
            z-index: 1;
        }

        .box-statistics .box-header .box-tools .btn {
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .box-statistics .box-header .box-tools .btn:hover {
            background: white;
            color: #667eea;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            margin: 40px 0 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-header .icon-container {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .section-header .icon-container i {
            font-size: 28px;
            color: white;
        }

        .section-header h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #333;
            letter-spacing: -0.5px;
        }

        /* Enhanced Tables */
        .table-statistics {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            border: none;
        }

        .table-statistics .total-row:hover {
            background-color: inherit !important;
            color: inherit !important;
        }


        .table-statistics thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table-statistics thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .table-statistics tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }

        .table-statistics tbody tr {
            transition: all 0.3s;
        }

        .table-statistics tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .table-statistics .total-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
        }

        .table-statistics .total-row td {
            border: none;
            padding: 20px 15px;
            font-size: 15px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* Stock Table Styling */
        .table-stock thead tr {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
        }

        .table-stock .total-row {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
        }

        /* Branch Info Card */
        .branch-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .branch-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .branch-details {
            flex: 1;
        }

        .branch-name {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            display: block;
            margin-bottom: 4px;
        }

        .branch-location {
            font-size: 12px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .branch-location i {
            color: #667eea;
        }

        /* Enhanced Labels */
        .label-enhanced {
            padding: 6px 5px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .label-main-branch {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 10px;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Sales Amount Styling */
        .sales-amount {
            font-size: 18px;
            font-weight: 900;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .currency {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 600;
            margin-left: 4px;
        }

        /* Enhanced Progress Bar */
        .progress-enhanced {
            height: 32px;
            border-radius: 16px;
            background: #f0f2f5;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .progress-enhanced .progress-bar {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            line-height: 32px;
            font-weight: 700;
            font-size: 13px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            position: relative;
            transition: width 1s ease;
        }

        .progress-enhanced .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
            transparent,
            rgba(255,255,255,0.3),
            transparent
            );
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Rating Labels */
        .rating-excellent {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .rating-very-good {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .rating-good {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
            color: white;
        }

        .rating-improvement {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        /* Stock Status Labels */
        .stock-abundant {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .stock-good {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
            color: white;
        }

        .stock-medium {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .stock-low {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* Count Badges */
        .count-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 900;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            min-width: 60px;
            text-align: center;
        }

        .count-badge.invoice {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
            box-shadow: 0 4px 12px rgba(0, 192, 239, 0.4);
        }

        .count-badge.product {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            box-shadow: 0 4px 12px rgba(155, 89, 182, 0.4);
        }

        /* Quantity Display */
        .quantity-display {
            font-size: 18px;
            font-weight: 900;
            color: #333;
        }

        .quantity-unit {
            font-size: 11px;
            color: #999;
            margin-left: 4px;
            font-weight: 600;
        }

        /* Average Display */
        .average-display {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
        }

        /* Charts Section */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .chart-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 0;
            margin: 0;
            position: relative;
        }

        .chart-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -5%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .chart-header.sales {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .chart-header.stock {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
        }

        .chart-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .chart-header h3 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .chart-body {
            padding: 25px;
        }

        /* Print Styles */
        @media print {
            .box-statistics .box-header .box-tools {
                display: none;
            }

            .chart-container {
                page-break-inside: avoid;
            }

            .statistics-page {
                background: white;
            }
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

        .table-statistics tbody tr {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header .icon-container {
                margin-bottom: 10px;
            }

            .table-statistics {
                font-size: 12px;
            }

            .branch-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="statistics-page">
        <div class="box box-info box-statistics">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-bar-chart-o"></i> Branches Statistics
                </h3>
                <div class="box-tools pull-right">
                    @can('view-branches')
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    @endcan
                        @can('view-reports')
                            <button onclick="window.print()" class="btn btn-info btn-sm">
                                <i class="fa fa-print"></i> Print
                            </button>
                        @endcan
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-download"></i> Export <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="?export=excel"><i class="fa fa-file-excel-o"></i> Excel (soon)</a></li>
                            @can('export-reports')
                                <li>
                                    <a href="{{ route('dashboard.branches.statistics.pdf') }}">
                                        <i class="fa fa-file-pdf-o"></i> PDF
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                <!-- Sales Statistics -->
                <div class="section-header">
                    <div class="icon-container">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <h4>Sales Statistics</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-statistics">
                        <thead>
                        <tr>
                            <th width="25%">Branch</th>
                            <th width="12%" class="text-center">Invoices</th>
                            <th width="18%" class="text-right">Total Sales</th>
                            <th width="15%" class="text-right">Avg Invoice</th>
                            <th width="20%" class="text-center">Percentage</th>
                            <th width="10%" class="text-center">Rating</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalSales = $salesByBranch->sum('total_sales') ?: 1;
                            $totalInvoices = $salesByBranch->sum('invoices_count') ?: 0;
                        @endphp

                        @foreach($salesByBranch as $branch)
                            @php
                                $avgInvoice = $branch->invoices_count > 0
                                    ? $branch->total_sales / $branch->invoices_count
                                    : 0;
                                $percentage = $totalSales > 0
                                    ? ($branch->total_sales / $totalSales) * 100
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="branch-info">
                                        <div class="branch-avatar">
                                            {{ substr($branch->name, 0, 1) }}
                                        </div>
                                        <div class="branch-details">
                                            <span class="branch-name">
                                                {{ $branch->name }}
                                                @if($branch->is_main)
                                                    <span class="label-main-branch">
                                                        <i class="fa fa-star"></i> Main
                                                    </span>
                                                @endif
                                            </span>
                                            <div class="branch-location">
                                                <i class="fa fa-map-marker"></i>
                                                {{ $branch->city ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge">{{ $branch->invoices_count }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="sales-amount">{{ number_format($branch->total_sales, 2) }}</span>
                                    <span class="currency">QAR</span>
                                </td>
                                <td class="text-right">
                                    <span class="average-display">{{ number_format($avgInvoice, 2) }} QAR</span>
                                </td>
                                <td class="text-center">
                                    <div class="progress progress-enhanced">
                                        <div class="progress-bar" style="width: {{ $percentage }}%">
                                            {{ number_format($percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($percentage >= 40)
                                        <span class="label-enhanced rating-excellent">
                                            <i class="fa fa-star"></i> Excellent
                                        </span>
                                    @elseif($percentage >= 25)
                                        <span class="label-enhanced rating-very-good">
                                            <i class="fa fa-thumbs-up"></i> Very Good
                                        </span>
                                    @elseif($percentage >= 15)
                                        <span class="label-enhanced rating-good">
                                            <i class="fa fa-check"></i> Good
                                        </span>
                                    @else
                                        <span class="label-enhanced rating-improvement">
                                            <i class="fa fa-arrow-up"></i> Improve
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <!-- Total Row -->
                        <tr class="total-row">
                            <td><strong><i class="fa fa-calculator"></i> TOTAL</strong></td>
                            <td class="text-center"><strong>{{ $totalInvoices }}</strong></td>
                            <td class="text-right">
                                <strong>{{ number_format($totalSales, 2) }} SAR</strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ $totalInvoices > 0 ? number_format($totalSales / $totalInvoices, 2) : 0 }} SAR</strong>
                            </td>
                            <td class="text-center"><strong>100%</strong></td>
                            <td class="text-center">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Stock Statistics -->
                <div class="section-header">
                    <div class="icon-container" style="background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%); box-shadow: 0 5px 15px rgba(0, 192, 239, 0.4);">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <h4>Stock Statistics</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-statistics table-stock">
                        <thead>
                        <tr>
                            <th width="30%">Branch</th>
                            <th width="15%" class="text-center">Products</th>
                            <th width="20%" class="text-center">Total Quantity</th>
                            <th width="20%" class="text-center">Avg/Product</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalProducts = $stockByBranch->sum('products_count') ?: 0;
                            $totalQuantity = $stockByBranch->sum('total_quantity') ?: 0;
                        @endphp

                        @foreach($stockByBranch as $branch)
                            @php
                                $avgPerProduct = $branch->products_count > 0
                                    ? $branch->total_quantity / $branch->products_count
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="branch-info">
                                        <div class="branch-avatar" style="background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);">
                                            {{ substr($branch->name, 0, 1) }}
                                        </div>
                                        <div class="branch-details">
                                            <span class="branch-name">
                                                {{ $branch->name }}
                                                @if($branch->is_main)
                                                    <span class="label-main-branch">
                                                        <i class="fa fa-star"></i> Main
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge product">{{ $branch->products_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="quantity-display">{{ $branch->total_quantity }}</span>
                                    <span class="quantity-unit">pieces</span>
                                </td>
                                <td class="text-center">
                                    <span class="average-display">{{ number_format($avgPerProduct, 1) }} pieces</span>
                                </td>
                                <td class="text-center">
                                    @if($branch->total_quantity > 500)
                                        <span class="label-enhanced stock-abundant">
                                            <i class="fa fa-check-circle"></i> Abundant
                                        </span>
                                    @elseif($branch->total_quantity > 200)
                                        <span class="label-enhanced stock-good">
                                            <i class="fa fa-check"></i> Good
                                        </span>
                                    @elseif($branch->total_quantity > 50)
                                        <span class="label-enhanced stock-medium">
                                            <i class="fa fa-exclamation-triangle"></i> Medium
                                        </span>
                                    @else
                                        <span class="label-enhanced stock-low">
                                            <i class="fa fa-times-circle"></i> Low
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <!-- Total Row -->
                        <tr class="total-row">
                            <td><strong><i class="fa fa-calculator"></i> TOTAL</strong></td>
                            <td class="text-center"><strong>{{ $totalProducts }}</strong></td>
                            <td class="text-center">
                                <strong>{{ $totalQuantity }} pieces</strong>
                            </td>
                            <td class="text-center">
                                <strong>{{ $totalProducts > 0 ? number_format($totalQuantity / $totalProducts, 1) : 0 }} pieces</strong>
                            </td>
                            <td class="text-center">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Charts Section -->
                <div class="row" style="margin-top: 50px;">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-header sales">
                                <h3>
                                    <i class="fa fa-bar-chart-o"></i> Sales Distribution
                                </h3>
                            </div>
                            <div class="chart-body">
                                <div style="position: relative; height: 280px;">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="chart-container">
                            <div class="chart-header stock">
                                <h3>
                                    <i class="fa fa-bar-chart-o"></i> Stock Distribution
                                </h3>
                            </div>
                            <div class="chart-body">
                                <div style="position: relative; height: 280px;">
                                    <canvas id="stockChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        // تأكد من تحميل Chart.js أولاً
        if (typeof Chart !== 'undefined') {

            // بيانات المبيعات - تجهيز البيانات بشكل آمن
            var salesLabels = [];
            var salesData = [];

            @foreach($salesByBranch as $index => $branch)
            salesLabels.push('{{ addslashes($branch->name) }}');
            salesData.push({{ $branch->total_sales }});
            @endforeach

            // بيانات المخزون - تجهيز البيانات بشكل آمن
            var stockLabels = [];
            var stockData = [];

            @foreach($stockByBranch as $index => $branch)
            stockLabels.push('{{ addslashes($branch->name) }}');
            stockData.push({{ $branch->total_quantity }});
            @endforeach

            // Sales Chart - مع try/catch للحماية
            try {
                var salesCanvas = document.getElementById('salesChart');
                if (salesCanvas && salesLabels.length > 0) {
                    var salesCtx = salesCanvas.getContext('2d');
                    new Chart(salesCtx, {
                        type: 'pie',
                        data: {
                            labels: salesLabels,
                            datasets: [{
                                data: salesData,
                                backgroundColor: [
                                    '#667eea', '#27ae60', '#3498db', '#f39c12', '#e74c3c',
                                    '#9b59b6', '#00c0ef', '#1abc9c', '#e67e22', '#34495e'
                                ],
                                borderWidth: 3,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    fontSize: 13,
                                    fontFamily: 'Arial, sans-serif',
                                    boxWidth: 20,
                                    fontStyle: 'bold'
                                }
                            },
                            tooltips: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFontSize: 14,
                                bodyFontSize: 13,
                                cornerRadius: 8,
                                xPadding: 15,
                                yPadding: 15,
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        var label = data.labels[tooltipItem.index] || '';
                                        var value = data.datasets[0].data[tooltipItem.index] || 0;
                                        var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        var percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value.toFixed(2) + ' SAR (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error creating sales chart:', error);
            }

            // Stock Chart - مع try/catch للحماية
            try {
                var stockCanvas = document.getElementById('stockChart');
                if (stockCanvas && stockLabels.length > 0) {
                    var stockCtx = stockCanvas.getContext('2d');
                    new Chart(stockCtx, {
                        type: 'bar',
                        data: {
                            labels: stockLabels,
                            datasets: [{
                                label: 'Quantity',
                                data: stockData,
                                backgroundColor: 'rgba(0, 192, 239, 0.8)',
                                borderColor: '#00c0ef',
                                borderWidth: 2,
                                hoverBackgroundColor: 'rgba(0, 192, 239, 1)',
                                hoverBorderColor: '#0099cc',
                                hoverBorderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        fontSize: 13,
                                        fontFamily: 'Arial, sans-serif',
                                        fontStyle: 'bold',
                                        fontColor: '#666'
                                    },
                                    gridLines: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        lineWidth: 1
                                    }
                                }],
                                xAxes: [{
                                    ticks: {
                                        fontSize: 12,
                                        fontFamily: 'Arial, sans-serif',
                                        fontStyle: 'bold',
                                        fontColor: '#666'
                                    },
                                    gridLines: {
                                        display: false
                                    }
                                }]
                            },
                            tooltips: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFontSize: 14,
                                bodyFontSize: 13,
                                cornerRadius: 8,
                                xPadding: 15,
                                yPadding: 15,
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return 'Quantity: ' + tooltipItem.yLabel + ' pieces';
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error creating stock chart:', error);
            }

        } else {
            console.error('Chart.js library not loaded');
        }
    </script>
@endsection
