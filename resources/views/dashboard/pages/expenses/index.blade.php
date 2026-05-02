@extends('dashboard.layouts.master')

@section('title', 'Expenses Management')

@section('content')

    <style>
        .expenses-page {
            padding: 20px;
        }

        .box-expenses {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-expenses .box-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-expenses .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-expenses .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-expenses .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-expenses .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-left: 5px;
        }

        .box-expenses .box-header .btn:hover {
            background: white;
            color: #f39c12;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Statistics Cards */
        .stats-row {
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .stat-card.total {
            border-color: #3498db;
        }

        .stat-card.amount {
            border-color: #27ae60;
        }

        .stat-card.cash {
            border-color: #f39c12;
        }

        .stat-card.count {
            border-color: #9b59b6;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .stat-card p {
            margin: 0;
            color: #999;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.total h3 { color: #3498db; }
        .stat-card.amount h3 { color: #27ae60; }
        .stat-card.cash h3 { color: #f39c12; }
        .stat-card.count h3 { color: #9b59b6; }

        /* Filter Box */
        .filter-box {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #ffe0b2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border: none;
            color: white;
            font-weight: 600;
            height: 42px;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
        }

        /* Table */
        .expenses-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .expenses-table thead {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .expenses-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .expenses-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .expenses-table tbody tr {
            transition: all 0.3s;
        }

        .expenses-table tbody tr:hover {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .category-badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .branch-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .payment-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .payment-badge.cash {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .payment-badge.visa,
        .payment-badge.mastercard,
        .payment-badge.mada,
        .payment-badge.atm {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .payment-badge.bank_transfer {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-buttons .btn {
            min-width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #999;
            font-size: 18px;
            font-weight: 600;
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

        .box-expenses {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> Expenses Management
            <small>Track and manage all expenses</small>
        </h1>
    </section>

    <div class="expenses-page">
        <!-- Statistics -->
        <div class="row stats-row">
            <div class="col-md-3">
                <div class="stat-card total">
                    <h3>{{ $expenses->total() }}</h3>
                    <p><i class="fa fa-list"></i> Total Expenses</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card amount">
                    <h3>{{ number_format($expenses->sum('amount'), 2) }}</h3>
                    <p><i class="fa fa-money"></i> Total Amount (QAR)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card cash">
                    <h3>{{ $expenses->where('payment_method', 'CASH')->count() }}</h3>
                    <p><i class="fa fa-credit-card"></i> Cash Expenses</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card count">
                    <h3>{{ number_format($expenses->where('payment_method', 'CASH')->sum('amount'), 2) }}</h3>
                    <p><i class="fa fa-calculator"></i> Cash Amount (QAR)</p>
                </div>
            </div>
        </div>

        <div class="box box-warning box-expenses">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> All Expenses
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.expenses.report') }}" class="btn btn-sm">
                        <i class="fa fa-bar-chart-o"></i> Report
                    </a>
                    <a href="{{ route('dashboard.expenses.categories') }}" class="btn btn-sm">
                        <i class="fa fa-tags"></i> Categories
                    </a>
                    @can('create-expenses')
                        <a href="{{ route('dashboard.expenses.create') }}" class="btn btn-sm">
                            <i class="fa fa-plus"></i> Add Expense
                        </a>
                    @endcan
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.expenses.index') }}">
                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-2">
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

                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                            </div>
                            <div class="col-md-2">
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="payment_method" class="form-control">
                                    <option value="">All Payments</option>
                                    <option value="CASH" {{ request('payment_method') == 'CASH' ? 'selected' : '' }}>Cash</option>
                                    <option value="VISA" {{ request('payment_method') == 'VISA' ? 'selected' : '' }}>Visa</option>
                                    <option value="MASTERCARD" {{ request('payment_method') == 'MASTERCARD' ? 'selected' : '' }}>MasterCard</option>
                                    <option value="MADA" {{ request('payment_method') == 'MADA' ? 'selected' : '' }}>Mada</option>
                                    <option value="ATM" {{ request('payment_method') == 'ATM' ? 'selected' : '' }}>ATM</option>
                                    <option value="BANK_TRANSFER" {{ request('payment_method') == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover expenses-table">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Date</th>
                            @if(auth()->user()->canSeeAllBranches())
                                <th width="12%">Branch</th>
                            @endif
                            <th width="15%">Category</th>
                            <th width="20%">Description</th>
                            <th width="12%">Vendor</th>
                            <th width="10%">Amount</th>
                            <th width="10%">Payment</th>
                            <th width="16%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <strong style="color: #f39c12; font-size: 16px;">{{ $loop->iteration }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $expense->expense_date->format('d M Y') }}</strong>
                                </td>
                                @if(auth()->user()->canSeeAllBranches())
                                    <td>
                                        <span class="branch-badge">
                                            <i class="fa fa-building"></i>
                                            {{ $expense->branch->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    <span class="category-badge">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td>{{ \Str::limit($expense->description, 40) }}</td>
                                <td>{{ $expense->vendor_name ?? '-' }}</td>
                                <td>
                                    <strong style="color: #27ae60; font-size: 15px;">
                                        {{ number_format($expense->amount, 2) }} QAR
                                    </strong>
                                </td>
                                <td>
                                    <span class="payment-badge {{ strtolower($expense->payment_method) }}">
                                        {{ str_replace('_', ' ', $expense->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @can('edit-expenses')
                                        <a href="{{ route('dashboard.expenses.edit', $expense->id) }}"
                                           class="btn btn-edit btn-xs"
                                           title="Edit Expense">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endcan

                                        @can('delete-expenses')
                                        <button type="button"
                                                class="btn btn-delete btn-xs btn-delete-expense"
                                                data-id="{{ $expense->id }}"
                                                data-description="{{ $expense->description }}"
                                                title="Delete Expense">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->canSeeAllBranches() ? 9 : 8 }}">
                                    <div class="empty-state">
                                        <i class="fa fa-inbox"></i>
                                        <h4>No expenses found</h4>
                                        <p style="color: #999;">Try adjusting your filters or add a new expense</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $expenses->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="fa fa-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="GET">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="fa fa-warning"></i>
                            Are you sure you want to delete expense: <strong id="expenseDescription"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i>
                            This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-trash"></i> Delete Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Delete Expense
            $(document).on('click', '.btn-delete-expense', function() {
                var expenseId = $(this).data('id');
                var description = $(this).data('description');

                var finalRoute = "{{ route('dashboard.expenses.delete', ':id') }}".replace(':id', expenseId);

                $('#expenseDescription').text(description);
                $('#deleteForm').attr('action', finalRoute);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
