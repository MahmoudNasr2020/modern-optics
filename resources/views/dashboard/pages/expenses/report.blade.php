@extends('dashboard.layouts.master')

@section('title', 'Expenses Report')

@section('content')

    <style>
        .report-page {
            padding: 20px;
        }

        .report-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
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
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .report-header h2 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .report-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 15px;
            position: relative;
            z-index: 1;
        }

        .report-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .report-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 5px solid;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.sales {
            border-color: #27ae60;
        }

        .stat-card.refunds {
            border-color: #e74c3c;
        }

        .stat-card.expenses {
            border-color: #f39c12;
        }

        .stat-card.profit {
            border-color: #3498db;
        }

        .stat-card h2 {
            margin: 0 0 10px 0;
            font-size: 36px;
            font-weight: 700;
        }

        .stat-card p {
            margin: 0;
            color: #999;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.sales h2 { color: #27ae60; }
        .stat-card.refunds h2 { color: #e74c3c; }
        .stat-card.expenses h2 { color: #f39c12; }
        .stat-card.profit h2 { color: #3498db; }
        .stat-card.profit.negative h2 { color: #e74c3c; }

        /* Filter Box */
        .filter-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .filter-box h4 {
            margin: 0 0 20px 0;
            color: #667eea;
            font-weight: 700;
        }

        .filter-box .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            height: 42px;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        /* Report Sections */
        .report-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .report-section h4 {
            margin: 0 0 20px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
            color: #667eea;
            font-weight: 700;
        }

        .report-section h4 i {
            margin-right: 10px;
        }

        .report-table {
            border-radius: 8px;
            overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        }

        .report-table thead th {
            color: #667eea;
            font-weight: 700;
            padding: 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .report-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #f0f2f5;
        }

        .report-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        }

        .report-table tfoot {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
        }

        .report-table tfoot td {
            padding: 15px;
            border: none;
        }

        .progress {
            height: 25px;
            border-radius: 20px;
            background: #f0f2f5;
        }

        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            line-height: 25px;
            font-weight: 700;
        }

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

        .report-page > * {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-bar-chart"></i> Expenses & Profit Report
            <small>Financial overview and analysis</small>
        </h1>
    </section>

    <div class="report-page">
        <!-- Report Header -->
        <div class="report-header">
            <div class="row">
                <div class="col-md-8">
                    <h2>
                        <i class="fa fa-file-text"></i> Financial Report
                    </h2>
                    <p>
                        <i class="fa fa-calendar"></i> <strong>Period:</strong> {{ \Carbon\Carbon::parse($date_from)->format('d M Y') }} to {{ \Carbon\Carbon::parse($date_to)->format('d M Y') }}
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.expenses.index') }}" class="btn">
                        <i class="fa fa-arrow-left"></i> Back to Expenses
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row stats-row">
            <div class="col-md-3">
                <div class="stat-card sales">
                    <h2>{{ number_format($totalSales, 2) }}</h2>
                    <p><i class="fa fa-shopping-cart"></i> Total Sales (QAR)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card refunds">
                    <h2>{{ number_format($totalRefunds, 2) }}</h2>
                    <p><i class="fa fa-undo"></i> Total Refunds (QAR)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card expenses">
                    <h2>{{ number_format($stats['total_expenses'], 2) }}</h2>
                    <p><i class="fa fa-money"></i> Total Expenses (QAR)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card profit {{ $netProfit < 0 ? 'negative' : '' }}">
                    <h2>{{ number_format($netProfit, 2) }}</h2>
                    <p><i class="bi bi-graph-up"></i> Net Profit (QAR)</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-box">
            <h4><i class="fa fa-filter"></i> Filter Report</h4>
            <form method="GET" action="{{ route('dashboard.expenses.report') }}">
                <div class="row">
                    @if(auth()->user()->canSeeAllBranches())
                        <div class="col-md-3">
                            <label>Branch</label>
                            <select name="branch_id" class="form-control">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-3">
                        <label>From Date</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $date_from }}">
                    </div>
                    <div class="col-md-3">
                        <label>To Date</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $date_to }}">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Expenses by Category -->
        <div class="report-section">
            <h4><i class="fa fa-pie-chart"></i> Expenses by Category</h4>
            <table class="table table-bordered report-table">
                <thead>
                <tr>
                    <th>Category</th>
                    <th>Count</th>
                    <th>Total Amount (QAR)</th>
                    <th>Percentage</th>
                </tr>
                </thead>
                <tbody>
                @foreach($byCategory as $categoryName => $data)
                    <tr>
                        <td><strong>{{ $categoryName }}</strong></td>
                        <td>{{ $data['count'] }}</td>
                        <td><strong style="color: #27ae60;">{{ number_format($data['total'], 2) }}</strong></td>
                        <td>
                            <div class="progress" style="margin: 0;">
                                <div class="progress-bar"
                                     style="width: {{ $stats['total_expenses'] > 0 ? ($data['total'] / $stats['total_expenses']) * 100 : 0 }}%">
                                    {{ $stats['total_expenses'] > 0 ? number_format(($data['total'] / $stats['total_expenses']) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td>{{ $stats['expenses_count'] }}</td>
                    <td>{{ number_format($stats['total_expenses'], 2) }}</td>
                    <td>100%</td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            <!-- Payment Methods -->
            <div class="col-md-6">
                <div class="report-section">
                    <h4><i class="fa fa-credit-card"></i> By Payment Method</h4>
                    <table class="table table-bordered report-table">
                        <tbody>
                        <tr>
                            <td><strong>Cash</strong></td>
                            <td class="text-right"><strong style="color: #f39c12;">{{ number_format($stats['cash_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Visa</strong></td>
                            <td class="text-right"><strong style="color: #3498db;">{{ number_format($stats['visa_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td><strong>MasterCard</strong></td>
                            <td class="text-right"><strong style="color: #3498db;">{{ number_format($stats['mastercard_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Mada</strong></td>
                            <td class="text-right"><strong style="color: #9b59b6;">{{ number_format($stats['mada_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td><strong>ATM</strong></td>
                            <td class="text-right"><strong style="color: #e74c3c;">{{ number_format($stats['atm_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Bank Transfer</strong></td>
                            <td class="text-right"><strong style="color: #27ae60;">{{ number_format($stats['bank_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Profit Calculation -->
            <div class="col-md-6">
                <div class="report-section">
                    <h4><i class="fa fa-calculator"></i> Profit Calculation</h4>
                    <table class="table report-table">
                        <tbody>
                        <tr>
                            <td>Total Sales</td>
                            <td class="text-right" style="color: #27ae60;"><strong>{{ number_format($totalSales, 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td>Total Refunds</td>
                            <td class="text-right" style="color: #e74c3c;"><strong>{{ number_format($totalRefunds, 2) }} QAR</strong></td>
                        </tr>
                        <tr>
                            <td>Total Expenses</td>
                            <td class="text-right" style="color: #f39c12;"><strong>{{ number_format($stats['total_expenses'], 2) }} QAR</strong></td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td><strong>Net Profit</strong></td>
                            <td class="text-right"><strong>{{ number_format($netProfit, 2) }} QAR</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detailed Expenses List -->
        <div class="report-section">
            <h4><i class="fa fa-list"></i> Detailed Expenses List</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped report-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        @if(auth()->user()->canSeeAllBranches())
                            <th>Branch</th>
                        @endif
                        <th>Category</th>
                        <th>Description</th>
                        <th>Vendor</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                            @if(auth()->user()->canSeeAllBranches())
                                <td>{{ optional($expense->branch)->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ optional($expense->category)->name ?? '—' }}</td>
                            <td>{{ \Str::limit($expense->description, 50) }}</td>
                            <td>{{ $expense->vendor_name ?? '-' }}</td>
                            <td>{{ str_replace('_', ' ', $expense->payment_method) }}</td>
                            <td class="text-right"><strong style="color: #27ae60;">{{ number_format($expense->amount, 2) }} QAR</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
