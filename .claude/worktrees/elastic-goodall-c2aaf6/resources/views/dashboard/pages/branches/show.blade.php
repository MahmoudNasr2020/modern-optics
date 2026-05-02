@extends('dashboard.layouts.master')

@section('title', 'Branch Details - ' . $branch->name)

@section('content')

    <style>
        /* Page Styling */
        .branch-details-page {
            background: #ecf0f5;
            padding: 15px;
        }

        /* Main Box */
        .box-branch-details {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: visible;
            border: none;
        }

        .box-branch-details .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-branch-details .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-branch-details .box-header .box-title {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-branch-details .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-branch-details .box-header .box-tools {
            position: relative;
            z-index: 1;
        }

        .box-branch-details .box-header .box-tools .btn {
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .box-branch-details .box-header .box-tools .btn:hover {
            background: white;
            color: #667eea;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Status Badges */
        .status-badges {
            margin-bottom: 25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge-large {
            padding: 10px 20px;
            border-radius: 25px;
            color:#fff;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .badge-large.label-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .badge-large.label-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .badge-large.label-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            margin: 30px 0 20px 0;
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header i {
            font-size: 24px;
            color: #667eea;
            margin-right: 12px;
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .section-header.warning {
            border-left-color: #f39c12;
        }

        .section-header.warning i {
            color: #f39c12;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: white;
            padding: 18px;
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            transition: all 0.3s;
        }

        .info-item:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value i {
            color: #667eea;
            font-size: 18px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(0,0,0,0.02);
            border-radius: 50%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.sales {
            border-top: 4px solid #27ae60;
        }

        .stat-card.invoices {
            border-top: 4px solid #3498db;
        }

        .stat-card.stock {
            border-top: 4px solid #f39c12;
        }

        .stat-card.users {
            border-top: 4px solid #9b59b6;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            position: relative;
            z-index: 1;
        }

        .stat-card.sales .stat-icon {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.invoices .stat-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .stat-card.stock .stat-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-card.users .stat-icon {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #333;
            position: relative;
            z-index: 1;
        }

        .stat-value small {
            font-size: 12px;
            color: #999;
            font-weight: 600;
        }

        /* Tables Enhanced */
        .table-enhanced {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 15px;
        }

        .table-enhanced thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table-enhanced thead th {
            color: white;
            font-weight: 700;
            padding: 15px 12px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-enhanced tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f0f2f5;
        }

        .table-enhanced tbody tr {
            transition: all 0.3s;
        }

        .table-enhanced tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(5px);
        }

        /* Low Stock Table */
        .table-warning thead {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        /* Product Name */
        .product-name {
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-name i {
            color: #667eea;
        }

        /* Quantity Badge */
        .qty-badge {
            background: #f0f0f0;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 700;
            color: #333;
            display: inline-block;
        }

        /* Status Labels */
        .label-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .label-ok {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .label-low {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .label-out {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* Invoice Code */
        .invoice-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: 700;
            display: inline-block;
            font-size: 12px;
        }

        /* Customer Name */
        .customer-name {
            font-weight: 600;
            color: #333;
        }

        /* Amount Display */
        .amount-display {
            font-weight: 700;
            color: #27ae60;
            font-size: 15px;
        }

        /* Date Display */
        .date-display {
            color: #666;
            font-size: 13px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .fa-users {
            color: #fff !important;
        }
        .fa-building-o {
            color: #fff !important;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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

        .stat-card, .info-item {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <div class="branch-details-page">
        <div class="box box-primary box-branch-details">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-building-o"></i> {{ $branch->name }}
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.branches.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('dashboard.branches.edit', $branch) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-stack"></i> Stock
                    </a>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                <!-- Status Badges -->
                <div class="status-badges">
                    @if($branch->is_main)
                        <span class="badge-large label-primary">
                            <i class="fa fa-star"></i> Main Branch
                        </span>
                    @endif
                    @if($branch->is_active)
                        <span class="badge-large label-success">
                            <i class="fa fa-check-circle"></i> Active
                        </span>
                    @else
                        <span class="badge-large label-danger">
                            <i class="fa fa-times-circle"></i> Inactive
                        </span>
                    @endif
                </div>

                <!-- Basic Information -->
                <div class="section-header">
                    <i class="fa fa-info-circle"></i>
                    <h4>Branch Information</h4>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Branch Code</span>
                        <div class="info-value">
                            <i class="fa fa-barcode"></i>
                            {{ $branch->code }}
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">City</span>
                        <div class="info-value">
                            <i class="fa fa-map-marker"></i>
                            {{ $branch->city ?: '-' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <div class="info-value">
                            @if($branch->phone)
                                <i class="fa fa-phone"></i>
                                {{ $branch->phone }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <div class="info-value">
                            @if($branch->email)
                                <i class="fa fa-envelope"></i>
                                {{ $branch->email }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Manager</span>
                        <div class="info-value">
                            <i class="fa fa-user-tie"></i>
                            {{ $branch->manager_name ?: '-' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Working Hours</span>
                        <div class="info-value">
                            <i class="fa fa-clock"></i>
                            @if($branch->opening_time && $branch->closing_time)
                                {{ $branch->opening_time }} - {{ $branch->closing_time }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="info-item full-width">
                        <span class="info-label">Address</span>
                        <div class="info-value">
                            <i class="fa fa-location-arrow"></i>
                            {{ $branch->address }}
                        </div>
                    </div>

                    @if($branch->description)
                        <div class="info-item full-width">
                            <span class="info-label">Description</span>
                            <div class="info-value">
                                <i class="fa fa-align-left"></i>
                                {{ $branch->description }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="section-header">
                    <i class="fa fa-chart-bar"></i>
                    <h4>Statistics</h4>
                </div>

                <div class="stats-grid">
                    <div class="stat-card sales">
                        <div class="stat-icon">
                            <i class="fa fa-dollar"></i>
                        </div>
                        <div class="stat-label">Total Sales</div>
                        <div class="stat-value">
                            {{ number_format($stats['total_sales'], 2) }}
                            <small>QAR</small>
                        </div>
                    </div>

                    <div class="stat-card invoices">
                        <div class="stat-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="stat-label">Invoices Count</div>
                        <div class="stat-value">{{ $stats['total_invoices'] }}</div>
                    </div>

                    <div class="stat-card stock">
                        <div class="stat-icon">
                            <i class="bi bi-stack"></i>
                        </div>
                        <div class="stat-label">Stock Value</div>
                        <div class="stat-value">
                            {{ number_format($stats['total_stock_value'], 2) }}
                            <small>SAR</small>
                        </div>
                    </div>

                    <div class="stat-card users">
                        <div class="stat-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="stat-label">Total Staff</div>
                        <div class="stat-value">{{ $stats['total_users'] }}</div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                @if($stats['low_stock_count'] > 0)
                    <div class="section-header warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        <h4>Low Stock Alert ({{ $stats['low_stock_count'] }})</h4>
                    </div>

                    <table class="table table-hover table-enhanced table-warning">
                        <thead>
                        <tr>
                            <th width="45%">Product</th>
                            <th width="18%" class="text-center">Available Qty</th>
                            <th width="18%" class="text-center">Min Qty</th>
                            <th width="19%" class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lowStockProducts as $stock)
                            <tr>
                                <td>
                                    <div class="product-name">
                                        <i class="fa fa-cube"></i>
                                        {{ $stock->product->description }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="qty-badge">{{ $stock->quantity }}</span>
                                </td>
                                <td class="text-center">
                                    <span style="color: #999;">{{ $stock->min_quantity }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="label-enhanced label-{{ $stock->stock_status['class'] }}">
                                        {{ $stock->stock_status['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Recent Invoices -->
                <div class="section-header">
                    <i class="fa fa-file-invoice"></i>
                    <h4>Recent Invoices</h4>
                </div>

                @if($recentInvoices->count() > 0)
                    <table class="table table-hover table-enhanced">
                        <thead>
                        <tr>
                            <th width="20%">Invoice #</th>
                            <th width="35%">Customer</th>
                            <th width="25%" class="text-right">Total</th>
                            <th width="20%">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentInvoices as $invoice)
                            <tr>
                                <td>
                                    <span class="invoice-code">{{ $invoice->invoice_code }}</span>
                                </td>
                                <td>
                                    <span class="customer-name">{{ $invoice->customer->english_name ?? '-' }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="amount-display">{{ number_format($invoice->total, 2) }} SAR</span>
                                </td>
                                <td>
                                    <span class="date-display">{{ $invoice->created_at->format('d M Y') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <p>No invoices yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
