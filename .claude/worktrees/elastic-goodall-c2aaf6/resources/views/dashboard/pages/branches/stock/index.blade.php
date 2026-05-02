@extends('dashboard.layouts.master')

@section('title', 'Branch Stock - ' . $branch->name)

@section('content')

    <style>
        .stock-page {
            padding: 20px;
        }

        .box-stock {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .box-stock .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-stock .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-stock .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-stock .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-stock .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-left: 8px;
        }

        .box-stock .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Stats Cards */
        .stats-section {
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
            position: relative;
            overflow: hidden;
            border-left: 4px solid #667eea;
        }

        .stat-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(102,126,234,0.05);
            border-radius: 50%;
        }

        .stat-card.products { border-left-color: #3498db; }
        .stat-card.products::before { background: rgba(52,152,219,0.05); }

        .stat-card.lenses { border-left-color: #9b59b6; }
        .stat-card.lenses::before { background: rgba(155,89,182,0.05); }

        .stat-card.low { border-left-color: #f39c12; }
        .stat-card.low::before { background: rgba(243,156,18,0.05); }

        .stat-card.out { border-left-color: #e74c3c; }
        .stat-card.out::before { background: rgba(231,76,60,0.05); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            float: left;
            margin-right: 15px;
            position: relative;
            z-index: 1;
        }

        .stat-card.products .stat-icon { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .stat-card.lenses .stat-icon { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
        .stat-card.low .stat-icon { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .stat-card.out .stat-icon { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }

        .stat-content {
            overflow: hidden;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 900;
            color: #333;
            line-height: 1;
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

        /* Stock Table */
        .stock-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .stock-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stock-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .stock-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .stock-table tbody tr {
            transition: all 0.3s;
        }

        .stock-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 6px 4px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .type-badge.product {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 6px 2px;
        }

        .type-badge.lens {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        /* Status Badge */
        .status-badge {
            padding: 6px 7px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .status-badge.normal {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-badge.low {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 6px 5px;
        }

        .status-badge.out {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 6px 0 !important;
            font-size: 10px !important;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 38px;
            height: 38px;
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

        .btn-add {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-reduce {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-history {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        /* Empty State */
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
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-box-seam"></i> Stock Management - {{ $branch->name }}
            <small>Manage branch inventory</small>
        </h1>
    </section>

    <div class="stock-page">
        <div class="box box-primary box-stock">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> Stock Items
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.branches.stock.create', $branch) }}" class="btn btn-sm">
                        <i class="bi bi-plus-circle"></i> Add Stock
                    </a>
                    <a href="{{ route('dashboard.branches.stock.report', $branch) }}" class="btn btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Report
                    </a>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Statistics --}}
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card products">
                                <div class="stat-icon">
                                    <i class="bi bi-box"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Products</div>
                                    <div class="stat-value">{{ $stocks->where('stockable_type', 'App\\Product')->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card lenses">
                                <div class="stat-icon">
                                    <i class="bi bi-eye"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Lenses</div>
                                    <div class="stat-value">{{ $stocks->where('stockable_type', 'App\\glassLense')->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card low">
                                <div class="stat-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Low Stock</div>
                                    <div class="stat-value">{{ $stocks->filter(fn($s) => $s->quantity <= $s->min_quantity && $s->quantity > 0)->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card out">
                                <div class="stat-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Out of Stock</div>
                                    <div class="stat-value">{{ $stocks->filter(fn($s) => $s->quantity == 0)->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.branches.stock.index', $branch) }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="🔍 Search by code or description..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Products Only</option>
                                    <option value="lens" {{ request('type') == 'lens' ? 'selected' : '' }}>Lenses Only</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Stock Table --}}
                @if($stocks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover stock-table">
                            <thead>
                            <tr>
                                <th width="4%" class="text-center">#</th>
                                <th width="8%">Type</th>
                                <th width="10%">Code</th>
                                <th width="22%">Description</th>
                                <th width="7%" class="text-center">Quantity</th>
                                <th width="7%" class="text-center">Reserved</th>
                                <th width="7%" class="text-center">Available</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="25%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stocks as $index => $stock)
                                <tr>
                                    <td class="text-center">
                                        <strong style="color: #667eea; font-size: 16px;">{{ $stocks->firstItem() + $index }}</strong>
                                    </td>

                                    {{-- Type --}}
                                    <td>
                                        @if($stock->stockable_type === 'App\\Product')
                                            <span class="type-badge product">
                                                <i class="bi bi-box"></i> Product
                                            </span>
                                        @else
                                            <span class="type-badge lens">
                                                <i class="bi bi-eye"></i> Lens
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Code --}}
                                    <td>
                                        <strong style="color: #667eea;">{{ $stock->item_code }}</strong>
                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        <strong>{{ $stock->description }}</strong>
                                        @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                                            <br><small class="text-muted">{{ $stock->stockable->brand }} - {{ $stock->stockable->index }}</small>
                                        @endif
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="text-center">
                                        <strong style="font-size: 18px; color: {{ $stock->quantity > $stock->min_quantity ? '#27ae60' : ($stock->quantity > 0 ? '#f39c12' : '#e74c3c') }};">
                                            {{ $stock->quantity }}
                                        </strong>
                                    </td>

                                    {{-- Reserved --}}
                                    <td class="text-center">
                                        @if($stock->reserved_quantity > 0)
                                            <span class="badge" style="background: #f39c12; color: white;">{{ $stock->reserved_quantity }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Available --}}
                                    <td class="text-center">
                                        <strong style="color: #3498db;">{{ $stock->available_quantity }}</strong>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @php $status = $stock->stock_status; @endphp
                                        @if($status['status'] === 'normal')
                                            <span class="status-badge normal">
                                                <i class="bi bi-check-circle"></i> {{ $status['label'] }}
                                            </span>
                                        @elseif($status['status'] === 'low_stock')
                                            <span class="status-badge low">
                                                <i class="bi bi-exclamation-triangle"></i> {{ $status['label'] }}
                                            </span>
                                        @else
                                            <span class="status-badge out">
                                                <i class="bi bi-x-circle"></i> {{ $status['label'] }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="action-buttons">
                                            {{-- Add Quantity --}}
                                            <button type="button" class="btn btn-add btn-xs open-add-modal"
                                                    data-description="{{ $stock->description }}"
                                                    data-quantity="{{ $stock->quantity }}"
                                                    data-url="{{ route('dashboard.branches.stock.add-quantity', [$branch, $stock]) }}"
                                                    title="Add Quantity">
                                                <i class="bi bi-plus"></i>
                                            </button>

                                            {{-- Reduce Quantity --}}
                                            <button type="button" class="btn btn-reduce btn-xs open-reduce-modal"
                                                    data-description="{{ $stock->description }}"
                                                    data-quantity="{{ $stock->quantity }}"
                                                    data-max="{{ $stock->available_quantity }}"
                                                    data-url="{{ route('dashboard.branches.stock.reduce-quantity', [$branch, $stock]) }}"
                                                    title="Reduce Quantity">
                                                <i class="bi bi-dash"></i>
                                            </button>

                                            {{-- View --}}
                                            <a href="{{ route('dashboard.branches.stock.show', [$branch, $stock]) }}"
                                               class="btn btn-view btn-xs" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('dashboard.branches.stock.edit', [$branch, $stock]) }}"
                                               class="btn btn-edit btn-xs" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            {{-- History --}}
                                            <a href="{{ route('dashboard.branches.stock.movements', [$branch, $stock]) }}"
                                               class="btn btn-history btn-xs" title="History">
                                                <i class="bi bi-clock-history"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <button type="button" class="btn btn-delete btn-xs btn-delete-stock"
                                                    data-id="{{ $stock->id }}"
                                                    data-description="{{ $stock->description }}"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($stocks->hasPages())
                        <div class="text-center" style="margin-top: 25px;">
                            {{ $stocks->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No Stock Items Found</h4>
                        <p style="color: #999;">Add some stock items to get started</p>
                        <a href="{{ route('dashboard.branches.stock.create', $branch) }}" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="bi bi-plus-circle"></i> Add Stock
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Quantity Modal --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="bi bi-plus-circle"></i> Add Quantity - <span id="addDescription"></span>
                    </h4>
                </div>
                <form id="addForm" method="POST">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                            <label>Current Quantity</label>
                            <input type="text" id="addCurrentQty" class="form-control" readonly style="background: #f8f9fa; font-weight: 700; font-size: 16px;">
                        </div>

                        <div class="form-group">
                            <label>Quantity to Add <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="Enter quantity">
                        </div>

                        <div class="form-group">
                            <label>Cost per Unit (Optional)</label>
                            <input type="number" name="cost" class="form-control" step="0.01" placeholder="0.00 QAR">
                        </div>

                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
                            <i class="bi bi-check"></i> Confirm Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reduce Quantity Modal --}}
    <div class="modal fade" id="reduceModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="bi bi-dash-circle"></i> Reduce Quantity - <span id="reduceDescription"></span>
                    </h4>
                </div>
                <form id="reduceForm" method="POST">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                            <label>Current Quantity</label>
                            <input type="text" id="reduceCurrentQty" class="form-control" readonly style="background: #f8f9fa; font-weight: 700; font-size: 16px;">
                        </div>

                        <div class="form-group">
                            <label>Quantity to Reduce <span class="text-danger">*</span></label>
                            <input type="number" id="reduceQtyInput" name="quantity" class="form-control" required min="1" placeholder="Enter quantity">
                            <small class="text-muted">Maximum available: <strong id="reduceMaxText"></strong></small>
                        </div>

                        <div class="form-group">
                            <label>Reason <span class="text-danger">*</span></label>
                            <select name="reason" class="form-control" required>
                                <option value="">Select reason...</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Expired">Expired</option>
                                <option value="Returned">Returned</option>
                                <option value="Sample">Sample</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                            <i class="bi bi-check"></i> Confirm Reduce
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete <strong id="stockDescription"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This will remove the stock item from this branch's inventory.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; border: none;">
                            <i class="bi bi-trash"></i> Delete
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
            // Add Modal
            $('.open-add-modal').on('click', function() {
                $('#addDescription').text($(this).data('description'));
                $('#addCurrentQty').val($(this).data('quantity'));
                $('#addForm').attr('action', $(this).data('url'));
                $('#addModal').modal('show');
            });

            // Reduce Modal
            $('.open-reduce-modal').on('click', function() {
                $('#reduceDescription').text($(this).data('description'));
                $('#reduceCurrentQty').val($(this).data('quantity'));
                $('#reduceMaxText').text($(this).data('max'));
                $('#reduceQtyInput').attr('max', $(this).data('max'));
                $('#reduceForm').attr('action', $(this).data('url'));
                $('#reduceModal').modal('show');
            });

            // Delete Stock
            $('.btn-delete-stock').on('click', function() {
                var stockId = $(this).data('id');
                var description = $(this).data('description');

                var deleteUrl = '{{ route("dashboard.branches.stock.destroy", [$branch, ":id"]) }}';
                deleteUrl = deleteUrl.replace(':id', stockId);

                $('#stockDescription').text(description);
                $('#deleteForm').attr('action', deleteUrl);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
