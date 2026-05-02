@extends('dashboard.layouts.master')

@section('title', 'Pending Transfer Requests')

@section('content')

    <style>
        .pending-page { padding: 20px; }

        .box-pending {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            animation: fadeInUp 0.5s ease-out;
        }

        .box-pending .box-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-pending .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
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
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-card.pending {
            border-left: 4px solid #f39c12;
        }

        .stat-card.urgent {
            border-left: 4px solid #e74c3c;
            animation: pulse 2s infinite;
        }

        .stat-card.late {
            border-left: 4px solid #00c0ef;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

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
        }

        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-card.urgent .stat-icon {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .stat-card.late .stat-icon {
            background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);
        }

        .stat-content {
            overflow: hidden;
            padding-top: 5px;
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

        .pending-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .pending-table thead {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .pending-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
        }

        .pending-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .pending-table tbody tr {
            transition: all 0.3s;
        }

        .pending-table tbody tr:hover {
            background: #fff9e6;
            transform: translateX(3px);
        }

        .pending-table tbody tr.urgent-row {
            background: #ffebee;
            border-left: 4px solid #e74c3c;
        }

        .pending-table tbody tr.late-row {
            background: #fff3cd;
            border-left: 4px solid #f39c12;
        }

        .badge-late {
            background: #e74c3c;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 5px;
            animation: blink 1.5s infinite;
        }

        .empty-state {
            text-align: center;
            padding: 70px 30px;
            background: linear-gradient(145deg, #f8fafc, #ffffff);
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
            border: 1px solid #edf2f7;
            max-width: 520px;
            margin: 40px auto;
            position: relative;
            overflow: hidden;
        }

        /* الهالة الخلفية */
        .empty-state::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(39,174,96,0.15) 0%, transparent 70%);
            top: -60px;
            right: -60px;
        }

        /* الأيقونة */
        .empty-state i {
            font-size: 64px;
            color: #27ae60;
            display: inline-block;
            margin-bottom: 20px;
            background: rgba(39,174,96,0.1);
            padding: 22px;
            border-radius: 50%;
            animation: floatIcon 3s ease-in-out infinite;
        }

        /* العنوان */
        .empty-state h3 {
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.3px;
        }

        /* النص */
        .empty-state p {
            color: #6b7280 !important;
            font-size: 15px !important;
            margin-bottom: 20px;
        }

        /* الزرار */
        .empty-state .btn-success {
            border-radius: 10px;
            padding: 12px 26px;
            font-weight: 600;
            letter-spacing: 0.3px;
            background: linear-gradient(135deg, #27ae60, #219150);
            border: none;
            box-shadow: 0 10px 22px rgba(39,174,96,0.25);
            transition: all 0.25s ease;
        }

        .empty-state .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(39,174,96,0.35);
        }

        /* حركة خفيفة للأيقونة */
        @keyframes floatIcon {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }


        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.5; }
        }

        .priority-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .priority-badge.urgent {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            animation: pulse-priority 1.5s infinite;
        }

        @keyframes pulse-priority {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .action-buttons .btn {
            width: 100%;
            min-height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 600;
            border: none;
        }

        .action-buttons .btn:hover {
            transform: translateX(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-approve {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-reject {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
        }

        .empty-state i {
            font-size: 100px;
            color: #27ae60;
            margin-bottom: 20px;
        }

        .tips-box {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 4px solid #27ae60;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .tips-box h4 {
            color: #27ae60;
            margin: 0 0 15px 0;
        }

        .tips-box ul {
            margin-bottom: 0;
        }

        .tips-box li {
            margin-bottom: 8px;
            color: #333;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-clock"></i> Pending Transfer Requests
            <small>Awaiting approval</small>
        </h1>
    </section>

    <div class="pending-page">
        <div class="box box-pending">
            <div class="box-header">
                <h3 class="box-title" style="font-size: 22px; font-weight: 700; position: relative; z-index: 1;">
                    <i class="bi bi-hourglass-split"></i> Pending Requests
                </h3>
                <div class="box-tools pull-right" style="position: relative; z-index: 1;">
                    <a href="{{ route('dashboard.stock-transfers.index') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); color: white;">
                        <i class="bi bi-arrow-left"></i> Back
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
                <div class="stats-section" style="margin-bottom: 25px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card pending">
                                <div class="stat-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Pending</div>
                                    <div class="stat-value">{{ $transfers->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stat-card urgent">
                                <div class="stat-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Urgent</div>
                                    <div class="stat-value">{{ $transfers->where('priority', 'urgent')->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stat-card late">
                                <div class="stat-icon">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Late (+3 days)</div>
                                    <div class="stat-value">{{ $transfers->filter(function($t) { return $t->days_old > 3; })->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($transfers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover pending-table">
                            <thead>
                            <tr>
                                <th width="12%">Request #</th>
                                <th width="10%">Date</th>
                                <th width="15%">From</th>
                                <th width="15%">To</th>
                                <th width="20%">Item</th>
                                <th width="8%" class="text-center">Qty</th>
                                <th width="12%">Priority</th>
                                <th width="8%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transfers as $transfer)
                                <tr class="{{ $transfer->priority == 'urgent' ? 'urgent-row' : ($transfer->days_old > 3 ? 'late-row' : '') }}">
                                    <td>
                                        <a href="{{ route('dashboard.stock-transfers.show', $transfer) }}" style="text-decoration: none;">
                                            <strong style="color: #667eea; font-size: 15px;">{{ $transfer->transfer_number }}</strong>
                                        </a>
                                        @if($transfer->days_old > 3)
                                            <br><span class="badge-late">
                                                <i class="bi bi-exclamation-triangle"></i> {{ $transfer->days_old }} days
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $transfer->transfer_date->format('d M Y') }}</strong>
                                        <br><small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $transfer->transfer_date->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #95a5a6; color: white; font-size: 11px;">
                                            <i class="bi bi-building"></i> {{ $transfer->fromBranch->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #3498db; color: white; font-size: 11px;">
                                            <i class="bi bi-building"></i> {{ $transfer->toBranch->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-{{ $transfer->stockable_type === 'App\\Product' ? 'box' : 'eye' }}" style="color: #667eea;"></i>
                                        <strong>{{ Str::limit($transfer->item_description, 30) }}</strong>
                                        <br><small class="text-muted">
                                            <i class="bi bi-person"></i> By: {{ $transfer->creator->first_name ?? 'System' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <strong style="font-size: 18px; color: #667eea;">{{ $transfer->quantity }}</strong>
                                    </td>
                                    <td>
                                        <span class="priority-badge {{ $transfer->priority }}">
                                            <i class="bi bi-{{ $transfer->priority == 'urgent' ? 'lightning' : ($transfer->priority == 'high' ? 'exclamation-circle' : ($transfer->priority == 'medium' ? 'exclamation-triangle' : 'dash-circle')) }}"></i>
                                            {{ ucfirst($transfer->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('dashboard.stock-transfers.show', $transfer) }}" class="btn btn-view btn-xs">
                                                <i class="bi bi-eye"></i> View
                                            </a>

                                            <form action="{{ route('dashboard.stock-transfers.approve', $transfer->id) }}" method="POST" onsubmit="return confirm('Approve this request?')">
                                                @csrf
                                                <button type="submit" class="btn btn-approve btn-xs">
                                                    <i class="bi bi-check"></i> Approve
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-reject btn-xs" data-toggle="modal" data-target="#rejectModal{{ $transfer->id }}">
                                                <i class="bi bi-x"></i> Reject
                                            </button>
                                        </div>

                                        {{-- Reject Modal --}}
                                        <div class="modal fade" id="rejectModal{{ $transfer->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content" style="border-radius: 12px;">
                                                    <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                                                        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                                                        <h4 class="modal-title" style="font-weight: 700;">
                                                            <i class="bi bi-x-circle"></i> Reject Request
                                                        </h4>
                                                    </div>
                                                    <form action="{{ route('dashboard.stock-transfers.cancel', $transfer) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body" style="padding: 25px;">
                                                            <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                Reject <strong>{{ $transfer->transfer_number }}</strong>?
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Provide detailed reason..."></textarea>
                                                                <small class="text-muted">This will be visible to the requester</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                <i class="bi bi-x"></i> Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                                                                <i class="bi bi-check"></i> Reject
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($transfers->hasPages())
                        <div class="text-center" style="margin-top: 20px;">
                            {{ $transfers->links() }}
                        </div>
                    @endif

                    {{-- Tips --}}
                    <div class="tips-box">
                        <h4><i class="bi bi-lightbulb"></i> Quick Tips</h4>
                        <ul>
                            <li><strong style="color: #e74c3c;"><i class="bi bi-lightning"></i> Urgent</strong> requests need immediate attention</li>
                            <li><strong style="color: #f39c12;"><i class="bi bi-exclamation-triangle"></i> Late</strong> requests (+3 days) should be prioritized</li>
                            <li><i class="bi bi-check-circle"></i> Verify stock availability before approving</li>
                            <li><i class="bi bi-truck"></i> Consider delivery logistics for multiple requests</li>
                            <li><i class="bi bi-chat-dots"></i> Provide clear rejection reasons</li>
                        </ul>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-check-circle"></i>
                        <h3 style="color: #27ae60;">All Clear!</h3>
                        <p style="color: #999; font-size: 16px;">No pending requests at the moment</p>
                        <a href="{{ route('dashboard.stock-transfers.index') }}" class="btn btn-success btn-lg" style="margin-top: 15px;">
                            <i class="bi bi-list-ul"></i> View All Requests
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
