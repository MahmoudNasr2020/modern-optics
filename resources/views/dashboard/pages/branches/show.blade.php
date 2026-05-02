@extends('dashboard.layouts.master')

@section('title', 'Branch Details - ' . $branch->name)

@section('content')

    <style>
        .branch-details-page { padding: 20px; }

        /* ===== HEADER BOX ===== */
        .branch-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 35px 40px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 8px 30px rgba(102,126,234,0.35);
        }

        .branch-hero::before {
            content: '';
            position: absolute; top: -60px; right: -40px;
            width: 280px; height: 280px;
            background: rgba(255,255,255,0.08); border-radius: 50%;
        }

        .branch-hero::after {
            content: '';
            position: absolute; bottom: -80px; right: 120px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.05); border-radius: 50%;
        }

        .hero-content { position: relative; z-index: 1; }

        .hero-title {
            font-size: 32px; font-weight: 800;
            margin: 0 0 8px 0; letter-spacing: -.5px;
        }

        .hero-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px 12px; border-radius: 10px; margin-right: 12px;
            font-size: 26px;
        }

        .hero-subtitle { font-size: 14px; opacity: .75; margin-bottom: 20px; }

        .hero-badges { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 16px; border-radius: 25px;
            font-size: 13px; font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .hero-badge.main   { background: rgba(243,156,18,0.3); border: 2px solid rgba(243,156,18,0.6); }
        .hero-badge.active { background: rgba(39,174,96,0.3); border: 2px solid rgba(39,174,96,0.6); }
        .hero-badge.inactive { background: rgba(231,76,60,0.3); border: 2px solid rgba(231,76,60,0.6); }

        .hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .hero-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 20px; border-radius: 10px;
            font-size: 13px; font-weight: 700;
            text-decoration: none; transition: all 0.3s;
            cursor: pointer; border: none;
        }

        .hero-btn.back  { background: rgba(255,255,255,0.15); color: white; border: 2px solid rgba(255,255,255,0.3); }
        .hero-btn.edit  { background: rgba(243,156,18,0.25); color: white; border: 2px solid rgba(243,156,18,0.5); }
        .hero-btn.stock { background: rgba(52,152,219,0.25); color: white; border: 2px solid rgba(52,152,219,0.5); }

        .hero-btn:hover {
            background: rgba(255,255,255,0.25); color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
        }

        /* ===== RENT HIGHLIGHT CARD ===== */
        .rent-highlight-card {
            background: linear-gradient(135deg, #fff3e0 0%, #fff8f0 100%);
            border: 2px solid #e67e22;
            border-radius: 14px;
            padding: 22px 28px;
            margin-bottom: 25px;
            display: flex; align-items: center; gap: 20px;
            box-shadow: 0 4px 15px rgba(230,126,34,0.15);
            animation: fadeInUp 0.5s ease-out;
        }

        .rent-icon-wrap {
            width: 65px; height: 65px; flex-shrink: 0;
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; color: white;
            box-shadow: 0 4px 12px rgba(230,126,34,0.35);
        }

        .rent-info .label {
            font-size: 12px; font-weight: 700;
            text-transform: uppercase; color: #e67e22;
            letter-spacing: .5px; margin-bottom: 5px;
        }

        .rent-info .amount {
            font-size: 30px; font-weight: 800; color: #d35400; line-height: 1;
        }

        .rent-info .sub {
            font-size: 12px; color: #aaa; margin-top: 4px;
        }

        .rent-not-set {
            font-size: 16px; font-weight: 600; color: #ccc;
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex; align-items: center;
            margin: 28px 0 18px 0; padding: 14px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px; border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header i { font-size: 20px; color: #667eea; margin-right: 12px; }
        .section-header h4 { margin: 0; font-size: 17px; font-weight: 700; color: #333; }
        .section-header.warning { border-left-color: #f39c12; }
        .section-header.warning i { color: #f39c12; }

        /* ===== INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px; margin-top: 16px;
        }

        .info-item {
            background: white;
            padding: 18px 20px;
            border-radius: 12px;
            border: 2px solid #f0f2f7;
            transition: all 0.3s;
        }

        .info-item:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(102,126,234,0.12);
        }

        .info-item.full-width { grid-column: 1 / -1; }

        .info-label {
            font-size: 11px; color: #bbb;
            text-transform: uppercase; letter-spacing: .8px;
            font-weight: 700; margin-bottom: 8px; display: block;
        }

        .info-value {
            font-size: 15px; font-weight: 700; color: #333;
            display: flex; align-items: center; gap: 8px;
        }

        .info-value i { color: #667eea; font-size: 16px; }

        /* ===== STATS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px; margin-top: 16px;
        }

        .stat-card {
            background: white; border-radius: 14px; padding: 22px;
            text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            transition: all 0.3s; position: relative; overflow: hidden;
        }

        .stat-card::after {
            content: ''; position: absolute;
            bottom: -30px; right: -30px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(0,0,0,0.03);
        }

        .stat-card:hover { transform: translateY(-6px); box-shadow: 0 10px 28px rgba(0,0,0,0.13); }

        .stat-card.sales   { border-top: 4px solid #27ae60; }
        .stat-card.invoices { border-top: 4px solid #3498db; }
        .stat-card.stock   { border-top: 4px solid #f39c12; }
        .stat-card.users   { border-top: 4px solid #9b59b6; }

        .stat-icon {
            width: 55px; height: 55px; margin: 0 auto 14px;
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-size: 24px; color: white;
        }

        .stat-card.sales   .stat-icon { background: linear-gradient(135deg, #27ae60, #229954); }
        .stat-card.invoices .stat-icon { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-card.stock   .stat-icon { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-card.users   .stat-icon { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .stat-label { font-size: 11px; color: #aaa; text-transform: uppercase; letter-spacing: .6px; font-weight: 700; margin-bottom: 8px; }
        .stat-value { font-size: 26px; font-weight: 900; color: #2d3436; }
        .stat-value small { font-size: 11px; color: #bbb; font-weight: 600; display: block; margin-top: 2px; }

        /* ===== TABLES ===== */
        .table-enhanced {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07); margin-top: 15px;
        }

        .table-enhanced thead { background: linear-gradient(135deg, #667eea, #764ba2); }
        .table-enhanced thead th {
            color: white; font-weight: 700; padding: 15px 14px;
            border: none; font-size: 12px;
            text-transform: uppercase; letter-spacing: .5px;
        }

        .table-enhanced tbody td {
            padding: 14px; vertical-align: middle;
            border: none; border-bottom: 1px solid #f5f6fa;
        }

        .table-enhanced tbody tr { transition: all 0.25s; }
        .table-enhanced tbody tr:hover {
            background: #f8f9ff; transform: translateX(5px);
        }

        .table-warning thead { background: linear-gradient(135deg, #f39c12, #e67e22); }

        .product-name { font-weight: 700; color: #333; display: flex; align-items: center; gap: 8px; }
        .product-name i { color: #667eea; }
        .qty-badge { background: #f0f2f7; padding: 5px 12px; border-radius: 8px; font-weight: 700; color: #333; }

        .label-enhanced { padding: 5px 12px; border-radius: 15px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .label-ok  { background: linear-gradient(135deg, #27ae60, #229954); color: white; }
        .label-low { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
        .label-out { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }

        .invoice-code {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; padding: 5px 12px; border-radius: 6px;
            font-weight: 700; display: inline-block; font-size: 12px;
        }

        .customer-name { font-weight: 600; color: #333; }
        .amount-display { font-weight: 700; color: #27ae60; font-size: 15px; }
        .date-display { color: #888; font-size: 13px; }

        .empty-state { text-align: center; padding: 50px; color: #ccc; }
        .empty-state i { font-size: 55px; margin-bottom: 15px; display: block; }


        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 992px)  { .info-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px)  { .info-grid, .stats-grid { grid-template-columns: 1fr; } .hero-title { font-size: 24px; } }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-card  { animation: fadeInUp 0.5s ease-out; }
        .info-item  { animation: fadeInUp 0.4s ease-out; }
    </style>

    <div class="branch-details-page">

        {{-- ===== HERO HEADER ===== --}}
        <div class="branch-hero">
            <div class="hero-content">
                <h2 class="hero-title">
                    <i class="bi bi-building"></i> {{ $branch->name }}
                </h2>
                @if($branch->name_ar)
                    <div class="hero-subtitle">{{ $branch->name_ar }}</div>
                @endif

                <div class="hero-badges">
                    @if($branch->is_main)
                        <span class="hero-badge main">
                            <i class="bi bi-star-fill"></i> Main Branch
                        </span>
                    @endif
                    <span class="hero-badge {{ $branch->is_active ? 'active' : 'inactive' }}">
                        <i class="bi bi-{{ $branch->is_active ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($branch->city)
                        <span class="hero-badge" style="background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.3);">
                            <i class="bi bi-geo-alt-fill"></i> {{ $branch->city }}
                        </span>
                    @endif
                </div>

                <div class="hero-actions">
                    <a href="{{ route('dashboard.branches.index') }}" class="hero-btn back">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    @can('edit-branches')
                        <a href="{{ route('dashboard.branches.edit', $branch) }}" class="hero-btn edit">
                            <i class="bi bi-pencil-square"></i> Edit Branch
                        </a>
                    @endcan
                    @can('view-stock')
                        <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="hero-btn stock">
                            <i class="bi bi-boxes"></i> Stock
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- ===== RENT HIGHLIGHT ===== --}}
        <div class="rent-highlight-card">
            <div class="rent-icon-wrap">
                <i class="bi bi-house-fill"></i>
            </div>
            <div class="rent-info">
                <div class="label"><i class="bi bi-tag-fill"></i> Monthly Rent</div>
                @if($branch->rent_amount > 0)
                    <div class="amount">{{ number_format($branch->rent_amount, 2) }} <span style="font-size:16px;font-weight:600;color:#e67e22;">QAR</span></div>
                    <div class="sub">Fixed monthly rent — used for auto-fill in expenses</div>
                @else
                    <div class="rent-not-set">Not configured &nbsp;<small style="font-size:12px;color:#ccc;">Go to Edit to set rent amount</small></div>
                @endif
            </div>
        </div>

        <div class="box" style="border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:none;">
            <div class="box-body" style="padding: 30px;">

                <!-- Branch Information -->
                <div class="section-header">
                    <i class="bi bi-info-circle-fill"></i>
                    <h4>Branch Information</h4>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Branch Code</span>
                        <div class="info-value">
                            <i class="bi bi-upc-scan"></i> {{ $branch->code }}
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <div class="info-value">
                            @if($branch->phone)
                                <i class="bi bi-telephone-fill"></i> {{ $branch->phone }}
                            @else
                                <span style="color:#ccc;font-weight:400;">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <div class="info-value">
                            @if($branch->email)
                                <i class="bi bi-envelope-fill"></i> {{ $branch->email }}
                            @else
                                <span style="color:#ccc;font-weight:400;">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Manager</span>
                        <div class="info-value">
                            <i class="bi bi-person-fill"></i>
                            {{ $branch->manager_name ?: '—' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Working Hours</span>
                        <div class="info-value">
                            <i class="bi bi-clock-fill"></i>
                            @if($branch->opening_time && $branch->closing_time)
                                {{ $branch->opening_time }} — {{ $branch->closing_time }}
                            @else
                                <span style="color:#ccc;font-weight:400;">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <span class="info-label">City</span>
                        <div class="info-value">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $branch->city ?: '—' }}
                        </div>
                    </div>

                    <div class="info-item full-width">
                        <span class="info-label">Address</span>
                        <div class="info-value">
                            <i class="bi bi-map-fill"></i>
                            {{ $branch->address }}
                        </div>
                    </div>

                    @if($branch->description)
                        <div class="info-item full-width">
                            <span class="info-label">Description</span>
                            <div class="info-value" style="font-weight:400;color:#555;line-height:1.6;">
                                {{ $branch->description }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="section-header">
                    <i class="bi bi-bar-chart-fill"></i>
                    <h4>Statistics</h4>
                </div>

                <div class="stats-grid">
                    <div class="stat-card sales">
                        <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                        <div class="stat-label">Total Sales</div>
                        <div class="stat-value">
                            {{ number_format($stats['total_sales'], 2) }}
                            <small>QAR</small>
                        </div>
                    </div>

                    <div class="stat-card invoices">
                        <div class="stat-icon"><i class="bi bi-receipt-cutoff"></i></div>
                        <div class="stat-label">Invoices</div>
                        <div class="stat-value">{{ $stats['total_invoices'] }}</div>
                    </div>

                    <div class="stat-card stock">
                        <div class="stat-icon"><i class="bi bi-boxes"></i></div>
                        <div class="stat-label">Stock Value</div>
                        <div class="stat-value">
                            {{ number_format($stats['total_stock_value'], 2) }}
                            <small>QAR</small>
                        </div>
                    </div>

                    <div class="stat-card users">
                        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-label">Total Staff</div>
                        <div class="stat-value">{{ $stats['total_users'] }}</div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                @if($stats['low_stock_count'] > 0)
                    <div class="section-header warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
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
                                        <i class="bi bi-box-seam"></i> {{ $stock->product->description }}
                                    </div>
                                </td>
                                <td class="text-center"><span class="qty-badge">{{ $stock->quantity }}</span></td>
                                <td class="text-center"><span style="color:#bbb;">{{ $stock->min_quantity }}</span></td>
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
                    <i class="bi bi-clock-fill"></i>
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
                                <td><span class="invoice-code">{{ $invoice->invoice_code }}</span></td>
                                <td><span class="customer-name">{{ $invoice->customer->english_name ?? '—' }}</span></td>
                                <td class="text-right">
                                    <span class="amount-display">{{ number_format($invoice->total, 2) }} QAR</span>
                                </td>
                                <td><span class="date-display">{{ $invoice->created_at->format('d M Y') }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No invoices yet</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
