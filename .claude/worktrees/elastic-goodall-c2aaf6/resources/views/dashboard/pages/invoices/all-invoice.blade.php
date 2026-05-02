@extends('dashboard.layouts.master')

@section('title', 'All Invoices')

@section('content')

    <style>
        /* Modern Invoices Page */
        .invoices-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Header */
        .invoices-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(52, 152, 219, 0.3);
            position: relative;
            overflow: hidden;
        }

        .invoices-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .invoices-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.blue::before { background: linear-gradient(90deg, #3498db, #2980b9); }
        .stat-card.yellow::before { background: linear-gradient(90deg, #f39c12, #e67e22); }
        .stat-card.purple::before { background: linear-gradient(90deg, #9b59b6, #8e44ad); }
        .stat-card.green::before { background: linear-gradient(90deg, #27ae60, #229954); }
        .stat-card.red::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
        .stat-icon.yellow { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
        .stat-icon.purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; }
        .stat-icon.green { background: linear-gradient(135deg, #27ae60, #229954); color: white; }
        .stat-icon.red { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #2c3e50;
            margin: 5px 0;
        }

        .stat-label {
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .filter-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }

        .filter-body {
            padding: 20px;
        }

        /* Form Controls */
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 8px 12px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155,89,182,0.1);
        }

        /* Checkbox Modern */
        .checkbox-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: #f8f9ff;
            border-radius: 8px;
            border: 2px solid #e7f3ff;
            margin-top: 22px;
        }

        .checkbox-modern input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #9b59b6;
        }

        .checkbox-modern label {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-card table {
            margin: 0;
        }

        .table-card thead {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        }

        .table-card thead th {
            color: white;
            padding: 15px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border: none;
        }

        .table-card tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .table-card tbody tr:hover {
            background: #f8f9ff;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-under-process {
            background: #cce5ff;
            color: #004085;
        }

        .status-ready {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Buttons */
        .btn-modern {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-purple-modern {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        /* Invoice Link */
        .invoice-link {
            color: #3498db;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .invoice-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Pagination */
        .pagination {
            margin: 20px 0 0 0;
        }

        .pagination li a {
            border-radius: 8px;
            margin: 0 3px;
            border: 2px solid #e0e6ed;
            color: #2c3e50;
            font-weight: 600;
        }

        .pagination li.active a {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-color: #3498db;
            color: white;
        }
    </style>

    <div class="invoices-page">

        <!-- Header -->
        <div class="invoices-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-receipt"></i> All Invoices</h1>
                    <p style="margin: 0; opacity: 0.9; position: relative; z-index: 1;">
                        Manage and track all pending, under process, and ready invoices
                    </p>
                </div>
                <div class="col-md-4 text-right" style="position: relative; z-index: 1;">
                <span style="background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 20px; font-weight: 700; font-size: 18px;">
                    <i class="bi bi-files"></i> {{ $invoices->total() }} Invoices
                </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Total Invoices -->
            <div class="stat-card blue">
                <div class="stat-icon blue">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div class="stat-value">{{ $invoices->total() }}</div>
                <div class="stat-label">Total Invoices</div>
            </div>

            <!-- Pending -->
            <div class="stat-card yellow">
                <div class="stat-icon yellow">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-value">
                    {{ $invoices->where('status', 'pending')->count() }}
                </div>
                <div class="stat-label">Pending</div>
            </div>

            <!-- Under Process -->
            <div class="stat-card purple">
                <div class="stat-icon purple">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="stat-value">
                    {{ $invoices->where('status', 'under process')->count() }}
                </div>
                <div class="stat-label">Under Process</div>
            </div>

            <!-- Ready -->
            <div class="stat-card green">
                <div class="stat-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-value">
                    {{ $invoices->where('status', 'ready')->count() }}
                </div>
                <div class="stat-label">Ready</div>
            </div>

            <!-- Remaining Balance -->
            <div class="stat-card red">
                <div class="stat-icon red">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-value">
                    {{ number_format($invoices->sum('remaining'), 0) }}
                </div>
                <div class="stat-label">Total Remaining (QAR)</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <div class="filter-header">
                <div style="display: flex; align-items: center;">
                    <div class="filter-icon">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Filters</h3>
                </div>
            </div>
            <div class="filter-body">
                <form action="{{ route('dashboard.all-invoices') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="bi bi-upc-scan"></i> Invoice Code</label>
                                <input type="text" name="invoice_code" class="form-control"
                                       value="{{ request()->invoice_code }}"
                                       placeholder="INV-001">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="bi bi-person-badge"></i> Customer Code</label>
                                <input type="text" name="customer_code" class="form-control"
                                       value="{{ request()->customer_code }}"
                                       placeholder="C-001">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="bi bi-person"></i> Customer Name</label>
                                <input type="text" name="customer_name" class="form-control"
                                       value="{{ request()->customer_name }}"
                                       placeholder="Search name">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-date"></i> Creation Date</label>
                                <input type="date" name="creation_date" class="form-control"
                                       value="{{ request()->creation_date }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><i class="bi bi-bar-chart"></i> Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under process" {{ request()->status == 'under process' ? 'selected' : '' }}>Under Process</option>
                                    <option value="ready" {{ request()->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="checkbox-modern">
                                <input type="checkbox" name="remaining_balance" id="remaining" value="1"
                                    {{ request()->remaining_balance == '1' ? 'checked' : '' }}>
                                <label for="remaining">Has Remaining</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-purple-modern btn-modern">
                                <i class="bi bi-search"></i> Apply Filters
                            </button>
                            <a href="{{ route('dashboard.all-invoices') }}" class="btn btn-modern" style="background: #ecf0f1; color: #2c3e50;">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="table-card">
            @if($invoices->count() > 0)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice Code</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Doctor</th>
                        <th>Sales Person</th>
                        <th>Total (QAR)</th>
                        <th>Remaining (QAR)</th>
                        <th>Status</th>
                        <th>Pickup Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $index => $invoice)
                        <tr>
                            <td><strong>{{ $invoices->firstItem() + $index }}</strong></td>

                            <td>
                                <a href="{{ route('dashboard.show-invoice-items', $invoice->invoice_code) }}" class="invoice-link">
                                    <i class="bi bi-file-earmark-text"></i> {{ $invoice->invoice_code }}
                                </a>
                            </td>

                            <td>
                                <i class="bi bi-calendar-event"></i>
                                {{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : '-' }}
                            </td>

                            <td>
                                <strong>{{ $invoice->customer->english_name ?? '-' }}</strong>
                            </td>

                            <td>{{ $invoice->doctor->name ?? '-' }}</td>

                            <td>{{ $invoice->user->first_name ?? '-' }}</td>

                            <td>
                                <strong style="color: #27ae60;">
                                    {{ number_format($invoice->total, 2) }}
                                </strong>
                            </td>

                            <td>
                                @if($invoice->remaining > 0)
                                    <strong style="color: #e74c3c;">
                                        {{ number_format($invoice->remaining, 2) }}
                                    </strong>
                                @else
                                    <span style="color: #27ae60;">Paid</span>
                                @endif
                            </td>

                            <td>
                                @if($invoice->status == 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @elseif($invoice->status == 'under process')
                                    <span class="status-badge status-under-process">
                                        <i class="bi bi-gear"></i> Under Process
                                    </span>
                                @elseif($invoice->status == 'ready')
                                    <span class="status-badge status-ready">
                                        <i class="bi bi-check-circle"></i> Ready
                                    </span>
                                @else
                                    <span class="status-badge">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </td>

                            <td>
                                @if($invoice->pickup_date)
                                    <i class="bi bi-clock"></i> {{ $invoice->pickup_date }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="padding: 20px;">
                    {{ $invoices->appends(request()->query())->links() }}
                </div>

            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No Invoices Found</h4>
                    <p>Try adjusting your filters or create a new invoice</p>
                </div>
            @endif
        </div>

    </div>

@endsection
