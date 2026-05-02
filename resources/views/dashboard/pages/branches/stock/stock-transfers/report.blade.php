@extends('dashboard.layouts.master')

@section('title', 'Stock Transfer Report')

@section('content')

    <style>
        /* ═══════════════════════════════════════════════════════════
           🎨 PAGE CONTAINER
           ═══════════════════════════════════════════════════════════ */
        .report-page {
            padding: 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
        }

        /* ═══════════════════════════════════════════════════════════
           🎯 MODERN HEADER
           ═══════════════════════════════════════════════════════════ */
        .report-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .report-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .report-header h3 {
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: 900;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .report-header h3 i {
            font-size: 36px;
            background: rgba(255,255,255,0.2);
            padding: 12px;
            border-radius: 12px;
        }

        .report-header p {
            margin: 0;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            font-size: 15px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .header-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            backdrop-filter: blur(10px);
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* ═══════════════════════════════════════════════════════════
           📊 STATISTICS CARDS
           ═══════════════════════════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            transition: height 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        }

        .stat-card:hover::before {
            height: 8px;
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.yellow::before {
            background: linear-gradient(90deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-card.success::before {
            background: linear-gradient(90deg, #00c9a7 0%, #00b894 100%);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            float: left;
            margin-right: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .stat-card.green .stat-icon {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.yellow .stat-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-card.success .stat-icon {
            background: linear-gradient(135deg, #00c9a7 0%, #00b894 100%);
        }

        .stat-content {
            overflow: hidden;
            padding-top: 8px;
        }

        .stat-label {
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 900;
            color: #2c3e50;
            line-height: 1;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
            outline: none;
        }

        /* ═══════════════════════════════════════════════════════════
           🔍 FILTER BOX
           ═══════════════════════════════════════════════════════════ */
        .filter-box {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .filter-box h4 {
            color: #667eea;
            font-weight: 800;
            margin: 0 0 25px 0;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-box h4 i {
            font-size: 24px;
        }

        .form-group label {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }



        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-filter::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transform: skewX(-25deg);
            transition: 0.5s;
        }

        .btn-filter:hover::before {
            left: 100%;
        }

        .btn-filter:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(118, 75, 162, 0.5);
            color: white;
        }

        .btn-filter:active {
            transform: scale(0.97);
        }

        /* ═══════════════════════════════════════════════════════════
           📋 REPORT TABLE
           ═══════════════════════════════════════════════════════════ */
        .report-table {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 4px solid #667eea;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            font-size: 26px;
            color: #667eea;
        }

        .section-title .badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            margin-left: auto;
        }

        .transfers-table {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e8e9ed;
        }

        .transfers-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .transfers-table thead th {
            color: white;
            font-weight: 800;
            padding: 18px 16px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.8px;
        }

        .transfers-table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }

        .transfers-table tbody tr {
            transition: all 0.2s ease;
        }

        .transfers-table tbody tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
        }

        .transfers-table tbody tr.total-row {
            background: linear-gradient(135deg, #e8f0fe 0%, #f0e7ff 100%);
            font-weight: 800;
            border-top: 3px solid #667eea;
        }

        .transfers-table tbody tr.total-row:hover {
            background: linear-gradient(135deg, #e0e8fe 0%, #e8dfff 100%);
        }

        /* ═══════════════════════════════════════════════════════════
           🏷️ STATUS BADGES
           ═══════════════════════════════════════════════════════════ */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .status-badge i {
            font-size: 13px;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .status-badge.approved {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
            color: white;
        }

        .status-badge.in_transit {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .status-badge.received {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-badge.canceled {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* ═══════════════════════════════════════════════════════════
           📊 ANALYSIS BOXES
           ═══════════════════════════════════════════════════════════ */
        .analysis-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .analysis-box {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .analysis-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .analysis-box .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
        }

        .analysis-box .box-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .analysis-box .box-content {
            padding: 25px;
        }

        .analysis-box .list-group-item {
            border: none;
            border-bottom: 1px solid #f0f2f5;
            padding: 16px 0;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .analysis-box .list-group-item:last-child {
            border-bottom: none;
        }

        .analysis-box .list-group-item:hover {
            padding-left: 10px;
            background: #f8f9ff;
        }

        .analysis-box .list-group-item i {
            font-size: 20px;
            margin-right: 12px;
        }

        .analysis-box .badge {
            margin-left: auto;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
        }

        /* ═══════════════════════════════════════════════════════════
           🎨 UTILITY CLASSES
           ═══════════════════════════════════════════════════════════ */
        .transfer-code {
            background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #2c3e50;
            border: 1px solid #ddd;
        }

        .branch-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .branch-badge.from {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .branch-badge.to {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-state i {
            font-size: 120px;
            color: #667eea;
            opacity: 0.3;
            margin-bottom: 25px;
        }

        .empty-state h3 {
            color: #667eea;
            font-weight: 800;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #95a5a6;
            font-size: 16px;
        }

        /* ═══════════════════════════════════════════════════════════
           🖨️ PRINT STYLES
           ═══════════════════════════════════════════════════════════ */
        @media print {
            .hide-on-print {
                display: none !important;
            }

            .report-page {
                background: white;
                padding: 0;
            }

            .stat-card,
            .filter-box,
            .report-table,
            .analysis-box {
                box-shadow: none;
                page-break-inside: avoid;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           📱 RESPONSIVE
           ═══════════════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .analysis-grid {
                grid-template-columns: 1fr;
            }

            .report-header h3 {
                font-size: 24px;
            }

            .stat-value {
                font-size: 28px;
            }
        }
    </style>

    <section class="content-header hide-on-print">
        <h1>
            <i class="bi bi-file-earmark-bar-graph"></i> Stock Transfer Report
            <small>Comprehensive Analysis</small>
        </h1>
    </section>

    <div class="report-page">

        {{-- Modern Header --}}
        <div class="report-header">
            <div class="row">
                <div class="col-md-8">
                    <h3>
                        <i class="bi bi-file-text"></i>
                        Stock Transfer Report
                    </h3>
                    <p><i class="bi bi-info-circle"></i> Complete analysis and tracking of stock movements between branches</p>
                </div>
                <div class="col-md-4 text-right hide-on-print">
                    <div class="header-actions">
                        <a href="{{ route('dashboard.stock-transfers.index') }}" class="header-btn">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button onclick="rptExportExcel('transfers-report-{{ now()->format("Ymd") }}')" class="header-btn" style="background:#1b5e20;color:#fff;border-color:#1b5e20;">
                            <i class="bi bi-file-earmark-excel-fill"></i> Excel
                        </button>
                        @can('view-transfer-reports')
                            <button onclick="rptDownloadPdf('transfers-report-{{ now()->format("Ymd") }}')" class="header-btn" id="btnPdfReport" style="background:#c62828;color:#fff;border-color:#c62828;">
                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="filter-box hide-on-print">
            <h4><i class="bi bi-funnel"></i> Filter Report</h4>
            <form method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="bi bi-calendar"></i> From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="bi bi-calendar-check"></i> To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="bi bi-building"></i> Branch</label>
                            <select name="branch_id" class="form-control">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><i class="bi bi-check-circle"></i> Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="bi bi-search"></i> Apply Filters
                </button>
            </form>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Transfers</div>
                    <div class="stat-value">{{ $statistics['total_transfers'] }}</div>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Quantity Transferred</div>
                    <div class="stat-value">{{ number_format($statistics['total_quantity_transferred']) }}</div>
                </div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">In Progress</div>
                    <div class="stat-value">{{ $statistics['in_progress_count'] }}</div>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $statistics['received_count'] }}</div>
                </div>
            </div>
        </div>

        {{-- Transfer Log Table --}}
        <div class="report-table">
            <h4 class="section-title">
                <i class="bi bi-list-check"></i>
                Transfer Movement Log
                @if(request()->hasAny(['from_date', 'to_date', 'branch_id', 'status']))
                    <span class="badge">Filtered</span>
                @endif
            </h4>

            @if($transfers->count() > 0)
                <div class="table-responsive">
                    <table class="table transfers-table">
                        <thead>
                        <tr>
                            <th width="12%">Request #</th>
                            <th width="12%">Date</th>
                            <th width="15%">From Branch</th>
                            <th width="15%">To Branch</th>
                            <th width="22%">Item</th>
                            <th width="8%" class="text-center">Qty</th>
                            <th width="16%">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $totalQty = 0; @endphp

                        @foreach($transfers as $transfer)
                            @php
                                if ($transfer->status == 'received') {
                                    $totalQty += $transfer->quantity;
                                }
                            @endphp
                            <tr>
                                <td>
                                    <code class="transfer-code">{{ $transfer->transfer_number }}</code>
                                </td>
                                <td>
                                    <strong style="display: block; margin-bottom: 4px;">{{ $transfer->transfer_date->format('d M Y') }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $transfer->transfer_date->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                <span class="branch-badge from">
                                    <i class="bi bi-building"></i> {{ $transfer->fromBranch->name }}
                                </span>
                                </td>
                                <td>
                                <span class="branch-badge to">
                                    <i class="bi bi-building"></i> {{ $transfer->toBranch->name }}
                                </span>
                                </td>
                                <td>
                                    <i class="bi bi-{{ $transfer->stockable_type === 'App\\Product' ? 'box' : 'eye' }}" style="color: #667eea; margin-right: 6px;"></i>
                                    <strong>{{ Str::limit($transfer->item_description, 35) }}</strong>
                                </td>
                                <td class="text-center">
                                    <strong style="font-size: 18px; color: #667eea;">{{ number_format($transfer->quantity) }}</strong>
                                </td>
                                <td>
                                <span class="status-badge {{ $transfer->status }}">
                                    <i class="bi bi-{{ $transfer->status == 'pending' ? 'clock' : ($transfer->status == 'approved' ? 'check-circle' : ($transfer->status == 'in_transit' ? 'truck' : ($transfer->status == 'received' ? 'check2-all' : 'x-circle'))) }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $transfer->status)) }}
                                </span>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td colspan="5" class="text-right">
                                <i class="bi bi-calculator"></i> <strong>Total Received Quantity:</strong>
                            </td>
                            <td class="text-center">
                                <strong style="font-size: 20px; color: #27ae60;">{{ number_format($totalQty) }}</strong>
                            </td>
                            <td>-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No Transfers Found</h3>
                    <p>No stock transfers match the specified filters</p>
                </div>
            @endif
        </div>

        {{-- Analysis Boxes --}}
        <div class="analysis-grid">
            {{-- Status Breakdown --}}
            <div class="analysis-box">
                <div class="box-header">
                    <h3><i class="bi bi-pie-chart"></i> Status Breakdown</h3>
                </div>
                <div class="box-content">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="bi bi-clock" style="color: #f39c12;"></i>
                            <strong>Pending</strong>
                            <span class="badge" style="background: #f39c12;">{{ $statistics['pending_count'] }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle" style="color: #00c0ef;"></i>
                            <strong>Approved</strong>
                            <span class="badge" style="background: #00c0ef;">{{ $statistics['approved_count'] }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-truck" style="color: #3498db;"></i>
                            <strong>In Transit</strong>
                            <span class="badge" style="background: #3498db;">{{ $statistics['in_transit_count'] }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check2-all" style="color: #27ae60;"></i>
                            <strong>Received</strong>
                            <span class="badge" style="background: #27ae60;">{{ $statistics['received_count'] }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-x-circle" style="color: #e74c3c;"></i>
                            <strong>Canceled</strong>
                            <span class="badge" style="background: #e74c3c;">{{ $statistics['canceled_count'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Most Active Branches --}}
            <div class="analysis-box">
                <div class="box-header">
                    <h3><i class="bi bi-trophy"></i> Most Active Branches</h3>
                </div>
                <div class="box-content">
                    @php
                        $branchActivity = [];
                        foreach($branches as $branch) {
                            $sent = $transfers->where('from_branch_id', $branch->id)->count();
                            $received = $transfers->where('to_branch_id', $branch->id)->count();
                            $total = $sent + $received;

                            if ($total > 0) {
                                $branchActivity[] = [
                                    'branch' => $branch,
                                    'total' => $total,
                                    'sent' => $sent,
                                    'received' => $received
                                ];
                            }
                        }

                        usort($branchActivity, function($a, $b) {
                            return $b['total'] - $a['total'];
                        });

                        $branchActivity = array_slice($branchActivity, 0, 5);
                    @endphp

                    <ul class="list-group">
                        @forelse($branchActivity as $index => $activity)
                            <li class="list-group-item">
                                <i class="bi bi-trophy" style="color: {{ $index == 0 ? '#f39c12' : ($index == 1 ? '#95a5a6' : '#cd7f32') }};"></i>
                                <div style="flex: 1;">
                                    <strong style="display: block; margin-bottom: 4px;">{{ $activity['branch']->name }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-arrow-up"></i> Sent: {{ $activity['sent'] }} |
                                        <i class="bi bi-arrow-down"></i> Received: {{ $activity['received'] }}
                                    </small>
                                </div>
                                <strong style="color: #667eea; font-size: 20px;">{{ $activity['total'] }}</strong>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                <i class="bi bi-info-circle"></i> No activity data available
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Footer Info --}}
        <div class="text-center hide-on-print" style="margin-top: 30px; padding: 25px; background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <p style="margin: 0; color: #7f8c8d; font-size: 14px;">
                <i class="bi bi-calendar"></i> <strong>Report Generated:</strong> {{ now()->format('l, d F Y - h:i A') }}
            </p>
            @if(request()->has('from_date') || request()->has('to_date'))
                <p style="margin: 8px 0 0 0; color: #7f8c8d; font-size: 14px;">
                    <i class="bi bi-funnel"></i> <strong>Period:</strong>
                    {{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d M Y') : 'Beginning' }}
                    to
                    {{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d M Y') : 'Now' }}
                </p>
            @endif
            <p style="margin: 8px 0 0 0; color: #7f8c8d; font-size: 13px;">
                <i class="bi bi-person"></i> <strong>Generated by:</strong> {{ auth()->user()->name }}
                @if(auth()->user()->branch)
                    | <i class="bi bi-building"></i> {{ auth()->user()->branch->name }}
                @endif
            </p>
        </div>

    </div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function rptExportExcel(filename) {
    var table = document.querySelector('table.transfers-table');
    if (!table) { alert('No data table found.'); return; }
    var html = '<!DOCTYPE html><html xmlns:o="urn:schemas-microsoft-com:office:office" '
             + 'xmlns:x="urn:schemas-microsoft-com:office:excel">'
             + '<head><meta charset="UTF-8"/>'
             + '<style>td,th{border:1px solid #ccc;padding:6px 10px;font-size:12px;font-family:Arial;}'
             + 'th{background:#1a237e;color:#fff;font-weight:bold;}</style>'
             + '</head><body>'
             + '<h2 style="font-family:Arial;color:#1a237e;">Stock Transfer Report — {{ now()->format("d M Y") }}</h2>'
             + table.outerHTML + '</body></html>';
    var blob = new Blob(['﻿' + html], { type: 'application/vnd.ms-excel;charset=utf-8' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a');
    a.href = url; a.download = filename + '.xls';
    document.body.appendChild(a); a.click();
    document.body.removeChild(a); URL.revokeObjectURL(url);
}

function rptDownloadPdf(filename) {
    var btn = document.getElementById('btnPdfReport');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating…';
    btn.style.opacity = '.6'; btn.style.pointerEvents = 'none';

    var opt = {
        margin: 8, filename: filename + '.pdf',
        image: { type: 'jpeg', quality: 0.95 },
        html2canvas: { scale: 2, useCORS: true, logging: false },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    html2pdf().set(opt).from(document.querySelector('.report-page, .box-body')).save()
        .then(function() {
            btn.innerHTML = '<i class="bi bi-file-earmark-pdf-fill"></i> PDF';
            btn.style.opacity = ''; btn.style.pointerEvents = '';
        });
}
</script>
@endsection
