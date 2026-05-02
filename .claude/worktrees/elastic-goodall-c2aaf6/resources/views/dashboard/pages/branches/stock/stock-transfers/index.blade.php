@extends('dashboard.layouts.master')

@section('title', 'Stock Transfers')

@section('content')

    <style>
        .transfers-page { padding: 20px; }

        .box-transfers {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .box-transfers .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-transfers .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-transfers .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-transfers .box-header .btn {
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

        .box-transfers .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Stats */
        .stats-section { margin-bottom: 25px; }

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

        .stat-card.pending { border-left-color: #f39c12; }
        .stat-card.pending::before { background: rgba(243,156,18,0.05); }

        .stat-card.approved { border-left-color: #00c0ef; }
        .stat-card.approved::before { background: rgba(0,192,239,0.05); }

        .stat-card.in-transit { border-left-color: #3498db; }
        .stat-card.in-transit::before { background: rgba(52,152,219,0.05); }

        .stat-card.received { border-left-color: #27ae60; }
        .stat-card.received::before { background: rgba(39,174,96,0.05); }

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

        .stat-card.pending .stat-icon { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .stat-card.approved .stat-icon { background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%); }
        .stat-card.in-transit .stat-icon { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .stat-card.received .stat-icon { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }

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

        /* Table */
        .transfers-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .transfers-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .transfers-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .transfers-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .transfers-table tbody tr {
            transition: all 0.3s;
        }

        .transfers-table tbody tr:hover {
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
        }

        .type-badge.lens {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        /* Status Badge */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
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

        /* Priority */
        .priority-badge {
            padding: 6px 7px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .priority-badge.urgent {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .priority-badge.high {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
        }

        .priority-badge.medium {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .priority-badge.low {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        /* Action Buttons - زي Users */
        .action-btns {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btns .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s;
            border: none;
            font-size: 14px;
        }

        .action-btns .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .action-btns .btn-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .action-btns .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .action-btns .btn-primary {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .action-btns .btn-warning {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
            color: white;
        }

        .action-btns .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
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
            <i class="bi bi-arrow-left-right"></i> Stock Transfers
            <small>Manage inter-branch transfers</small>
        </h1>
    </section>

    <div class="transfers-page">
        <div class="box box-primary box-transfers">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> Transfer Requests
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.stock-transfers.create') }}" class="btn btn-sm">
                        <i class="bi bi-plus-circle"></i> New Request
                    </a>
                    <a href="{{ route('dashboard.stock-transfers.report') }}" class="btn btn-sm">
                        <i class="bi bi-file-earmark-bar-graph"></i> Report
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
                            <div class="stat-card pending">
                                <div class="stat-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Pending</div>
                                    <div class="stat-value">{{ $stats['pending'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card approved">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Approved</div>
                                    <div class="stat-value">{{ $stats['approved'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card in-transit">
                                <div class="stat-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">In Transit</div>
                                    <div class="stat-value">{{ $stats['in_transit'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card received">
                                <div class="stat-icon">
                                    <i class="bi bi-check2-all"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Received</div>
                                    <div class="stat-value">{{ $stats['received'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.stock-transfers.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control"
                                       placeholder="🔍 Search..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select name="from_branch" class="form-control">
                                    <option value="">From Branch...</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request('from_branch') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="item_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="product" {{ request('item_type') == 'product' ? 'selected' : '' }}>Products</option>
                                    <option value="lens" {{ request('item_type') == 'lens' ? 'selected' : '' }}>Lenses</option>
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

                {{-- Transfers Table --}}
                @if($transfers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover transfers-table">
                            <thead>
                            <tr>
                                <th width="4%" class="text-center">#</th>
                                <th width="10%">Request #</th>
                                <th width="8%">Type</th>
                                <th width="16%">Item</th>
                                <th width="12%">From</th>
                                <th width="12%">To</th>
                                <th width="6%" class="text-center">Qty</th>
                                <th width="8%">Priority</th>
                                <th width="10%">Status</th>
                                <th width="14%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transfers as $index => $transfer)
                                <tr>
                                    <td class="text-center">
                                        <strong style="color: #667eea; font-size: 16px;">{{ $transfers->firstItem() + $index }}</strong>
                                    </td>

                                    {{-- Request Number --}}
                                    <td>
                                        <a href="{{ route('dashboard.stock-transfers.show', $transfer) }}" style="text-decoration: none;">
                                            <strong style="color: #667eea;">{{ $transfer->transfer_number }}</strong>
                                        </a>
                                        <br><small class="text-muted">{{ $transfer->transfer_date->format('d M Y') }}</small>
                                    </td>

                                    {{-- Type --}}
                                    <td>
                                        @if($transfer->stockable_type === 'App\\Product')
                                            <span class="type-badge product">
                                            <i class="bi bi-box"></i> Product
                                        </span>
                                        @else
                                            <span class="type-badge lens">
                                            <i class="bi bi-eye"></i> Lens
                                        </span>
                                        @endif
                                    </td>

                                    {{-- Item --}}
                                    <td>
                                        <strong>{{ Str::limit($transfer->item_description, 25) }}</strong>
                                    </td>

                                    {{-- From Branch --}}
                                    <td>
                                    <span class="badge" style="background: #95a5a6; color: white; font-size: 11px;">
                                        <i class="bi bi-box-arrow-up"></i> {{ $transfer->fromBranch->name }}
                                    </span>
                                    </td>

                                    {{-- To Branch --}}
                                    <td>
                                    <span class="badge" style="background: #3498db; color: white; font-size: 11px;">
                                        <i class="bi bi-box-arrow-in-down"></i> {{ $transfer->toBranch->name }}
                                    </span>
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="text-center">
                                        <strong style="font-size: 18px; color: #667eea;">{{ $transfer->quantity }}</strong>
                                    </td>

                                    {{-- Priority --}}
                                    <td>
                                    <span class="priority-badge {{ $transfer->priority }}">
                                        <i class="bi bi-{{ $transfer->priority == 'urgent' ? 'lightning' : ($transfer->priority == 'high' ? 'exclamation-circle' : ($transfer->priority == 'medium' ? 'exclamation-triangle' : 'dash-circle')) }}"></i>
                                        {{ ucfirst($transfer->priority) }}
                                    </span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                    <span class="status-badge {{ $transfer->status }}">
                                        <i class="bi bi-{{ $transfer->status == 'pending' ? 'clock' : ($transfer->status == 'approved' ? 'check-circle' : ($transfer->status == 'in_transit' ? 'truck' : ($transfer->status == 'received' ? 'check2-all' : 'x-circle'))) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $transfer->status)) }}
                                    </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="action-btns">
                                            {{-- View --}}
                                            <a href="{{ route('dashboard.stock-transfers.show', $transfer) }}"
                                               class="btn btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- Approve --}}
                                            @if($transfer->status == 'pending')
                                                <button type="button" class="btn btn-success"
                                                        onclick="quickAction('approve', {{ $transfer->id }})"
                                                        title="Approve">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif

                                            {{-- Ship --}}
                                            @if($transfer->status == 'approved')
                                                <button type="button" class="btn btn-primary"
                                                        onclick="quickAction('ship', {{ $transfer->id }})"
                                                        title="Ship">
                                                    <i class="bi bi-truck"></i>
                                                </button>
                                            @endif

                                            {{-- Receive --}}
                                            @if(in_array($transfer->status, ['approved', 'in_transit']))
                                                <button type="button" class="btn btn-warning"
                                                        onclick="quickAction('receive', {{ $transfer->id }})"
                                                        title="Receive">
                                                    <i class="bi bi-check2-all"></i>
                                                </button>
                                            @endif

                                            {{-- Cancel --}}
                                            @if(!in_array($transfer->status, ['received', 'canceled']))
                                                <button type="button" class="btn btn-danger"
                                                        onclick="quickAction('cancel', {{ $transfer->id }})"
                                                        title="Cancel">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($transfers->hasPages())
                        <div class="text-center" style="margin-top: 25px;">
                            {{ $transfers->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No Transfer Requests Found</h4>
                        <p style="color: #999;">Create your first transfer request to get started</p>
                        <a href="{{ route('dashboard.stock-transfers.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="bi bi-plus-circle"></i> Create Request
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function quickAction(action, transferId) {
            let config = {};

            if (action === 'approve') {
                config = {
                    title: 'Approve Transfer?',
                    text: 'This will approve the transfer request',
                    icon: 'question',
                    confirmButtonColor: '#27ae60',
                    confirmButtonText: '<i class="bi bi-check"></i> Approve'
                };
            } else if (action === 'ship') {
                config = {
                    title: 'Ship Transfer?',
                    text: 'This will mark the transfer as shipped',
                    icon: 'info',
                    confirmButtonColor: '#9b59b6',
                    confirmButtonText: '<i class="bi bi-truck"></i> Ship'
                };
            } else if (action === 'receive') {
                config = {
                    title: 'Receive Transfer?',
                    text: 'This will complete the transfer and update stock',
                    icon: 'success',
                    confirmButtonColor: '#00c0ef',
                    confirmButtonText: '<i class="bi bi-check2-all"></i> Receive'
                };
            } else if (action === 'cancel') {
                Swal.fire({
                    title: 'Cancel Transfer?',
                    input: 'textarea',
                    inputLabel: 'Cancellation Reason',
                    inputPlaceholder: 'Provide reason...',
                    inputAttributes: {
                        'aria-label': 'Type reason here',
                        'style': 'height: 100px;'
                    },
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: '<i class="bi bi-x-circle"></i> Yes, Cancel',
                    cancelButtonText: '<i class="bi bi-arrow-left"></i> Go Back',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please provide a reason!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit cancel with reason
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/dashboard/stock-transfers/${transferId}/cancel`;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';

                        const reason = document.createElement('input');
                        reason.type = 'hidden';
                        reason.name = 'rejection_reason';
                        reason.value = result.value;

                        form.appendChild(csrf);
                        form.appendChild(reason);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
                return;
            }

            // For approve, ship, receive
            Swal.fire({
                ...config,
                showCancelButton: true,
                cancelButtonColor: '#95a5a6',
                cancelButtonText: '<i class="bi bi-x"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/dashboard/stock-transfers/${transferId}/${action}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
