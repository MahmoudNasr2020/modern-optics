@extends('dashboard.layouts.master')

@section('title', 'Transfer Details')

@section('content')

    <style>
        .show-transfer-page { padding: 20px; }

        .box-show {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .box-show .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-show .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-show .box-header .box-title {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-show .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .box-show .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Status Card */
        .status-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
        }

        .status-badges {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .badge-enhanced {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .action-buttons-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-buttons-group .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }

        .action-buttons-group .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* Request Number */
        .request-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }

        .request-number-label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .request-number-value {
            font-size: 24px;
            font-weight: 900;
            margin-top: 5px;
        }

        /* Branch Cards */
        .branch-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
        }

        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .branch-card.from { border-left: 5px solid #95a5a6; }
        .branch-card.to { border-left: 5px solid #00c0ef; }

        .branch-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .branch-name {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .branch-name i.from { color: #95a5a6; }
        .branch-name i.to { color: #00c0ef; }

        /* Product Card */
        .product-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid #e8ecf7;
            margin-top: 20px;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-info { flex: 1; }

        .product-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .product-name i {
            font-size: 28px;
            color: #667eea;
        }

        .quantity-display {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(102,126,234,0.3);
        }

        .quantity-number {
            font-size: 48px;
            font-weight: 900;
            line-height: 1;
        }

        .quantity-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-top: 8px;
        }

        /* Info Alert */
        .info-alert {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid #00c0ef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 20px;
        }

        .info-alert.danger { border-left-color: #e74c3c; }

        .info-alert-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-weight: 700;
            color: #333;
        }

        .info-alert-header i {
            font-size: 20px;
            color: #00c0ef;
        }

        .info-alert.danger .info-alert-header i { color: #e74c3c; }

        /* Timeline */
        .timeline {
            position: relative;
            padding: 20px 0;
            list-style: none;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 30px;
            width: 4px;
            height: calc(100% - 50px);
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .timeline > li {
            position: relative;
            margin-bottom: 30px;
            padding-left: 30px;
            min-height: 70px;
        }

        .timeline > li > i {
            position: absolute;
            left: 15px;
            top: 0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            font-size: 16px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .timeline-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .timeline-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .timeline-header {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin: 0 0 10px 0;
        }

        .timeline-body {
            color: #666;
            font-size: 14px;
        }

        .timeline-body strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .timeline-end {
            min-height: 30px !important;
            padding-left: 0 !important;
        }

        .timeline-end i {
            left: 18px !important;
            width: 30px !important;
            height: 30px !important;
            line-height: 30px !important;
            font-size: 14px !important;
        }

        /* Sidebar */
        .info-box-sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .info-box-sidebar .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border: none;
        }

        .info-box-sidebar .box-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .info-box-sidebar .box-body {
            padding: 20px;
        }

        .info-box-sidebar .box-body p {
            margin-bottom: 15px;
            font-size: 13px;
            color: #666;
        }

        .info-box-sidebar .box-body p:last-child {
            margin-bottom: 0;
        }

        .info-box-sidebar .box-body strong {
            color: #333;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .info-box-sidebar.success .box-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .related-link {
            display: block;
            padding: 12px 15px;
            background: white;
            border: 2px solid #e8ecf7;
            border-radius: 8px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
        }

        .related-link:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-color: #667eea;
            transform: translateX(5px);
            color: #667eea;
            text-decoration: none;
        }

        .related-link i {
            margin-right: 10px;
            width: 20px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media print {
            .btn, .box-tools, .action-buttons-group { display: none !important; }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-eye"></i> Transfer Details
            <small>View request information</small>
        </h1>
    </section>

    <div class="show-transfer-page">
        <div class="box box-show">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="bi bi-file-text"></i> Transfer Request Details
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.stock-transfers.index') }}" class="btn btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button onclick="window.print()" class="btn btn-sm">
                        <i class="bi bi-printer"></i> Print
                    </button>
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

                {{-- Status Card --}}
                <div class="status-card">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="status-badges">
                            <span class="badge-enhanced label-{{ $stockTransfer->status_label['class'] }}">
                                <i class="bi bi-{{ $stockTransfer->status == 'pending' ? 'clock' : ($stockTransfer->status == 'approved' ? 'check-circle' : ($stockTransfer->status == 'in_transit' ? 'truck' : ($stockTransfer->status == 'received' ? 'check2-all' : 'x-circle'))) }}"></i>
                                {{ $stockTransfer->status_label['label'] }}
                            </span>
                                <span class="badge-enhanced label-{{ $stockTransfer->priority_label['class'] }}">
                                <i class="bi bi-{{ $stockTransfer->priority == 'urgent' ? 'lightning' : ($stockTransfer->priority == 'high' ? 'exclamation-circle' : ($stockTransfer->priority == 'medium' ? 'exclamation-triangle' : 'dash-circle')) }}"></i>
                                {{ $stockTransfer->priority_label['label'] }}
                            </span>
                            </div>
                            <div style="color: #666; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-calendar" style="color: #667eea;"></i>
                                <span>
                                Request Date:
                                {{ optional($stockTransfer->transfer_date)->format('d M Y') ?? '-' }}
                                ({{ optional($stockTransfer->transfer_date)->diffForHumans() ?? '-' }})
                                </span>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="action-buttons-group">
                                @if($stockTransfer->status == 'pending')
                                    <button type="button" class="btn btn-success" onclick="approveTransfer()">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                @endif

                                @if($stockTransfer->status == 'approved')
                                    <button type="button" class="btn btn-primary" onclick="shipTransfer()">
                                        <i class="bi bi-truck"></i> Ship
                                    </button>
                                @endif

                                @if(in_array($stockTransfer->status, ['approved', 'in_transit']))
                                    <button type="button" class="btn btn-success" onclick="receiveTransfer()">
                                        <i class="bi bi-check2-all"></i> Receive
                                    </button>
                                @endif

                                @if(!in_array($stockTransfer->status, ['received', 'canceled']))
                                    <button type="button" class="btn btn-danger" onclick="cancelTransfer()">
                                        <i class="bi bi-x"></i> Cancel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">

                        {{-- Request Number --}}
                        <div class="request-number">
                            <div class="request-number-label">Request Number</div>
                            <div class="request-number-value">{{ $stockTransfer->transfer_number }}</div>
                        </div>

                        {{-- Branches --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="branch-card from">
                                    <div class="branch-label">
                                        <i class="bi bi-box-arrow-up"></i> From Branch
                                    </div>
                                    <div class="branch-name">
                                        <i class="bi bi-building from"></i>
                                        {{ $stockTransfer->fromBranch->name }}
                                    </div>
                                    @if($stockTransfer->fromBranch->address)
                                        <div style="color: #666; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                                            <i class="bi bi-geo-alt" style="color: #667eea;"></i>
                                            {{ $stockTransfer->fromBranch->address }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="branch-card to">
                                    <div class="branch-label">
                                        <i class="bi bi-box-arrow-in-down"></i> To Branch
                                    </div>
                                    <div class="branch-name">
                                        <i class="bi bi-building to"></i>
                                        {{ $stockTransfer->toBranch->name }}
                                    </div>
                                    @if($stockTransfer->toBranch->address)
                                        <div style="color: #666; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                                            <i class="bi bi-geo-alt" style="color: #667eea;"></i>
                                            {{ $stockTransfer->toBranch->address }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Product --}}
                        <div class="product-card">
                            <div class="product-label">Item & Quantity</div>
                            <div class="product-header">
                                <div class="product-info">
                                    <div class="product-name">
                                        <i class="bi bi-{{ $stockTransfer->stockable_type === 'App\\Product' ? 'box' : 'eye' }}"></i>
                                        {{ $stockTransfer->item_description }}
                                    </div>
                                    <div style="font-size: 12px; color: #999;">
                                        Code: {{ $stockTransfer->stockable->id ?? '' }}
                                    </div>
                                </div>
                                <div class="quantity-display">
                                    <div class="quantity-number">{{ $stockTransfer->quantity }}</div>
                                    <div class="quantity-label">Units</div>
                                </div>
                            </div>
                        </div>

                        @if($stockTransfer->notes)
                            <div class="info-alert">
                                <div class="info-alert-header">
                                    <i class="bi bi-sticky"></i>
                                    <span>Notes</span>
                                </div>
                                <div class="info-alert-body">{{ $stockTransfer->notes }}</div>
                            </div>
                        @endif

                        @if($stockTransfer->status == 'canceled' && $stockTransfer->rejection_reason)
                            <div class="info-alert danger">
                                <div class="info-alert-header">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <span>Cancellation Reason</span>
                                </div>
                                <div class="info-alert-body">{{ $stockTransfer->rejection_reason }}</div>
                            </div>
                        @endif

                        {{-- Timeline --}}
                        <div style="margin-top: 40px; padding: 15px 20px; background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%); border-radius: 10px; border-left: 5px solid #667eea; margin-bottom: 20px;">
                            <i class="bi bi-clock-history" style="font-size: 20px; color: #667eea; margin-right: 10px;"></i>
                            <strong style="font-size: 18px; color: #333;">Activity Log</strong>
                        </div>

                        <ul class="timeline">
                            {{-- Created --}}
                            <li>
                                <i class="bi bi-plus-circle" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Request Created</h3>
                                    <div class="timeline-body">
                                        <strong>{{ $stockTransfer->creator->fullName ?? 'System' }}</strong>
                                        <small>{{ $stockTransfer->created_at->format('d M Y h:i A') }}</small>
                                    </div>
                                </div>
                            </li>

                            @if($stockTransfer->approved_by)
                                <li>
                                    <i class="bi bi-check-circle" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Approved</h3>
                                        <div class="timeline-body">
                                            <strong>{{ $stockTransfer->approver->fullName ?? 'System' }}</strong>
                                            <small>{{ optional($stockTransfer->approved_at)->format('d M Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($stockTransfer->shipped_by)
                                <li>
                                    <i class="bi bi-truck" style="background: linear-gradient(135deg, #00c0ef 0%, #0099cc 100%);"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Shipped</h3>
                                        <div class="timeline-body">
                                            <strong>{{ $stockTransfer->shipper->fullName ?? 'System' }}</strong>
                                            <small>{{ optional($stockTransfer->shipped_at)->format('d M Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($stockTransfer->received_by)
                                <li>
                                    <i class="bi bi-check2-all" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Received</h3>
                                        <div class="timeline-body">
                                            <strong>{{ $stockTransfer->receiver->fullName ?? 'System' }}</strong>
                                            <small>{{ optional($stockTransfer->received_at)->format('d M Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($stockTransfer->status == 'canceled')
                                <li>
                                    <i class="bi bi-x-circle" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header text-danger">Canceled</h3>
                                        <div class="timeline-body">
                                            <small>{{ $stockTransfer->updated_at->format('d M Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <li class="timeline-end">
                                <i class="bi bi-clock" style="background: #95a5a6;"></i>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        {{-- Quick Info --}}
                        <div class="info-box-sidebar">
                            <div class="box-header">
                                <h3><i class="bi bi-info-circle"></i> Quick Info</h3>
                            </div>
                            <div class="box-body">
                                <p>
                                    <strong>Request #:</strong>
                                    {{ $stockTransfer->transfer_number }}
                                </p>
                                <p>
                                    <strong>Priority:</strong>
                                    <span class="label label-{{ $stockTransfer->priority_label['class'] }}">
                                    {{ $stockTransfer->priority_label['label'] }}
                                </span>
                                </p>
                                <p>
                                    <strong>Age:</strong>
                                    {{ $stockTransfer->days_old }} days
                                </p>
                                <p>
                                    <strong>Last Update:</strong>
                                    {{ $stockTransfer->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Related Links --}}
                        <div class="info-box-sidebar success">
                            <div class="box-header">
                                <h3><i class="bi bi-link-45deg"></i> Related Links</h3>
                            </div>
                            <div class="box-body">
                                <a href="{{ route('dashboard.branches.show', $stockTransfer->fromBranch) }}" class="related-link">
                                    <i class="bi bi-building"></i> Sender Branch
                                </a>
                                <a href="{{ route('dashboard.branches.show', $stockTransfer->toBranch) }}" class="related-link">
                                    <i class="bi bi-building"></i> Receiver Branch
                                </a>
                                <a href="{{ route('dashboard.stock-transfers.index') }}" class="related-link">
                                    <i class="bi bi-list-ul"></i> All Transfers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Forms --}}
    <form id="approveForm" action="{{ route('dashboard.stock-transfers.approve', $stockTransfer) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="shipForm" action="{{ route('dashboard.stock-transfers.ship', $stockTransfer) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="receiveForm" action="{{ route('dashboard.stock-transfers.receive', $stockTransfer) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="cancelForm" action="{{ route('dashboard.stock-transfers.cancel', $stockTransfer) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="rejection_reason" id="rejectionReason">
    </form>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function approveTransfer() {
            Swal.fire({
                title: 'Approve Transfer?',
                html: `
            <p>Are you sure you want to approve transfer request <strong>{{ $stockTransfer->transfer_number }}</strong>?</p>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>This will:</strong>
                <ul style="text-align: left; margin-top: 10px;">
                    <li>Change status to <span style="color: #00c0ef;">Approved</span></li>
                    <li>Allow the transfer to be shipped</li>
                </ul>
            </div>
        `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-check"></i> Yes, Approve',
                cancelButtonText: '<i class="bi bi-x"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm').submit();
                }
            });
        }

        function shipTransfer() {
            Swal.fire({
                title: 'Ship Transfer?',
                html: `
            <p>Mark transfer <strong>{{ $stockTransfer->transfer_number }}</strong> as shipped?</p>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>This will:</strong>
                <ul style="text-align: left; margin-top: 10px;">
                    <li>Change status to <span style="color: #3498db;">In Transit</span></li>
                    <li>Record shipment timestamp</li>
                    <li>Allow destination branch to receive</li>
                </ul>
            </div>
        `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-truck"></i> Yes, Ship It',
                cancelButtonText: '<i class="bi bi-x"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('shipForm').submit();
                }
            });
        }

        function receiveTransfer() {
            Swal.fire({
                title: 'Receive Transfer?',
                html: `
            <p>Confirm receipt of transfer <strong>{{ $stockTransfer->transfer_number }}</strong>?</p>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>This will:</strong>
                <ul style="text-align: left; margin-top: 10px;">
                    <li>Change status to <span style="color: #27ae60;">Received</span></li>
                    <li>Update stock levels in both branches</li>
                    <li>Complete the transfer process</li>
                </ul>
            </div>
        `,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-check2-all"></i> Yes, Received',
                cancelButtonText: '<i class="bi bi-x"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('receiveForm').submit();
                }
            });
        }

        function cancelTransfer() {
            Swal.fire({
                title: 'Cancel Transfer?',
                html: `
            <p>Are you sure you want to cancel <strong>{{ $stockTransfer->transfer_number }}</strong>?</p>
            <textarea id="cancelReason" class="swal2-input" placeholder="Provide cancellation reason..." style="height: 100px; resize: none;" required></textarea>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-x-circle"></i> Yes, Cancel It',
                cancelButtonText: '<i class="bi bi-arrow-left"></i> Go Back',
                preConfirm: () => {
                    const reason = document.getElementById('cancelReason').value;
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('Please provide a cancellation reason');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectionReason').value = result.value;
                    document.getElementById('cancelForm').submit();
                }
            });
        }
    </script>
@endsection
