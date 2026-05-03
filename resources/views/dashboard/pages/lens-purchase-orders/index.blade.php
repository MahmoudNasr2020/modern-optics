@extends('dashboard.layouts.master')
@section('title', 'Lab Orders')
@section('content')

<style>
    /* ── Core layout ── */
    .lpo-page { padding: 20px; }

    /* ── Box card ── */
    .box-lpo { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.10); overflow: hidden; border: none; }
    .box-lpo .box-header {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        color: #fff; padding: 25px 30px; border: none; position: relative; overflow: hidden;
    }
    .box-lpo .box-header::before {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 200px; height: 200px; background: rgba(255,255,255,.10); border-radius: 50%;
    }
    .box-lpo .box-header .box-title { font-size: 22px; font-weight: 700; position: relative; z-index: 1; }
    .box-lpo .box-header .box-title i { background: rgba(255,255,255,.2); padding: 10px; border-radius: 8px; margin-right: 10px; }
    .box-lpo .box-header .btn {
        position: relative; z-index: 1; background: rgba(255,255,255,.2);
        border: 2px solid rgba(255,255,255,.4); color: #fff; padding: 10px 20px;
        border-radius: 8px; font-weight: 600; transition: .3s;
    }
    .box-lpo .box-header .btn:hover { background: #fff; color: #e67e22; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,.2); }

    /* ── Stats row ── */
    .stats-row { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
    .stat-card { flex: 1; min-width: 160px; background: #fff; padding: 20px; border-radius: 10px; border: 2px solid #e8ecf7; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; align-items: center; gap: 15px; transition: .3s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,.1); }
    .stat-icon { width: 55px; height: 55px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; box-shadow: 0 3px 10px rgba(0,0,0,.2); flex-shrink: 0; }
    .stat-icon.total    { background: linear-gradient(135deg, #e67e22, #d35400); }
    .stat-icon.pending  { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .stat-icon.sent     { background: linear-gradient(135deg, #3498db, #2980b9); }
    .stat-icon.received { background: linear-gradient(135deg, #27ae60, #219a52); }
    .stat-content h4 { margin: 0 0 4px; font-size: 26px; font-weight: 700; color: #333; }
    .stat-content p  { margin: 0; font-size: 12px; color: #666; font-weight: 600; }

    /* ── Filter bar ── */
    .filter-box { background: linear-gradient(135deg, #f8f9ff, #fff); padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 2px solid #e8ecf7; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
    .filter-box .form-control, .filter-box .btn { border: 2px solid #e0e6ed; border-radius: 8px !important; padding: 10px 15px; transition: .3s; height: 42px; }
    .filter-box .form-control:focus { border-color: #e67e22; box-shadow: 0 0 0 3px rgba(230,126,34,.1); }
    .filter-box .btn-primary { background: linear-gradient(135deg, #e67e22, #d35400); border: none; color: #fff; font-weight: 600; }

    /* ── Create bar ── */
    .create-bar { background: #fff8f0; border: 2px solid #f0c08a; border-radius: 10px; padding: 18px 20px; margin-bottom: 20px; }
    .create-bar .form-control { border: 2px solid #e0e6ed; border-radius: 8px !important; height: 42px; padding: 10px 15px; transition: .3s; }
    .create-bar .form-control:focus { border-color: #e67e22; box-shadow: 0 0 0 3px rgba(230,126,34,.1); }

    /* ── Table ── */
    .lpo-table { background: #fff; border-radius: 12px; overflow: hidden; }
    .lpo-table thead { background: linear-gradient(135deg, #e67e22, #d35400); }
    .lpo-table thead th { color: #fff; font-weight: 700; padding: 16px 14px; border: none; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; white-space: nowrap; }
    .lpo-table tbody td { padding: 15px 14px; vertical-align: middle; border-bottom: 1px solid #f0f2f5; }
    .lpo-table tbody tr { transition: .3s; }
    .lpo-table tbody tr:hover { background: linear-gradient(135deg, #fff8f0, #fff); transform: translateX(3px); box-shadow: 0 2px 8px rgba(0,0,0,.05); }

    /* ── PO badge ── */
    .po-badge { background: linear-gradient(135deg, #e67e22, #d35400); color: #fff; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,.15); }

    /* ── Status badges ── */
    .status-pending   { background: #fff3e0; color: #e65100; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; border: 1px solid #ffcc80; }
    .status-sent      { background: #e3f2fd; color: #1565c0; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; border: 1px solid #90caf9; }
    .status-received  { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; border: 1px solid #a5d6a7; }
    .status-cancelled { background: #fce4ec; color: #c62828; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700; border: 1px solid #ef9a9a; }

    /* ── Action buttons ── */
    .action-buttons { display: flex; gap: 6px; justify-content: center; flex-wrap: nowrap; }
    .action-buttons .btn { min-width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: .3s; border: none; font-size: 13px; }
    .action-buttons .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.2); }
    .btn-view    { background: linear-gradient(135deg, #3498db, #2980b9); color: #fff; }
    .btn-receive { background: linear-gradient(135deg, #27ae60, #219a52); color: #fff; }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 80px; color: #ddd; margin-bottom: 20px; display: block; }
    .empty-state h4 { color: #999; font-size: 18px; font-weight: 600; }
</style>

<section class="content-header">
    <h1><i class="bi bi-flask"></i> Lab Orders <small>Lens Purchase Orders</small></h1>
</section>

<div class="lpo-page">
<div class="box box-primary box-lpo">

    {{-- ── Box Header ── --}}
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-flask"></i> All Lab Orders</h3>
        <div class="box-tools pull-right" style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('dashboard.lens-purchase-orders.lens-stock') }}" class="btn btn-sm">
                <i class="bi bi-boxes"></i> Lens Stock
            </a>
            <a href="{{ route('dashboard.invoice.pending') }}" class="btn btn-sm">
                <i class="bi bi-receipt"></i> Pending Invoices
            </a>
            @can('add-stock')
            <button class="btn btn-sm" data-toggle="modal" data-target="#addLabModal"
                    style="background:rgba(255,255,255,.25);border:2px solid rgba(255,255,255,.5);color:#fff;border-radius:8px;font-weight:600;padding:7px 14px;">
                <i class="bi bi-building-gear"></i> Manage Labs
            </button>
            @endcan
        </div>
    </div>

    <div class="box-body" style="padding:30px;">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" style="border-left:5px solid #27ae60;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" style="border-left:5px solid #e74c3c;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible" style="border-left:5px solid #3498db;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
            </div>
        @endif

        {{-- ── Stats ── --}}
        @php
            $allOrders = $orders->getCollection();
            $cntPending  = $allOrders->where('status','pending')->count();
            $cntSent     = $allOrders->where('status','sent')->count();
            $cntReceived = $allOrders->where('status','received')->count();
        @endphp
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon total"><i class="bi bi-flask"></i></div>
                <div class="stat-content"><h4>{{ $orders->total() }}</h4><p>Total Orders</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-content"><h4>{{ $cntPending }}</h4><p>Pending</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon sent"><i class="bi bi-send-fill"></i></div>
                <div class="stat-content"><h4>{{ $cntSent }}</h4><p>Sent to Lab</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon received"><i class="bi bi-box-arrow-in-down"></i></div>
                <div class="stat-content"><h4>{{ $cntReceived }}</h4><p>Received</p></div>
            </div>
        </div>

        {{-- ── Create New Lab Order ── --}}
        @can('add-stock')
        <div class="create-bar">
            <div style="margin-bottom:12px;">
                <strong style="color:#e67e22;font-size:15px;"><i class="bi bi-plus-circle-fill"></i> Create New Lab Order</strong>
                <span style="color:#888;font-size:13px;margin-left:8px;">Enter the invoice code of a pending invoice that has lens items</span>
            </div>
            <form method="GET" action="{{ route('dashboard.lens-purchase-orders.search-invoice') }}" class="row">
                <div class="col-md-6">
                    <input type="text" name="invoice_code" class="form-control"
                           placeholder="Enter Invoice Code (e.g. 44337982)..."
                           value="{{ old('invoice_code', request('invoice_code')) }}" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning btn-block" style="height:42px;font-weight:700;border-radius:8px!important;">
                        <i class="fa fa-flask"></i> Find &amp; Create Order
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('dashboard.invoice.pending') }}" class="btn btn-default btn-block" style="height:42px;line-height:22px;border-radius:8px!important;">
                        <i class="bi bi-list"></i> Browse Pending Invoices
                    </a>
                </div>
            </form>
            @if(session('search_error'))
                <div class="alert alert-danger" style="margin-top:12px;margin-bottom:0;border-radius:8px;">
                    <i class="bi bi-exclamation-circle"></i> {{ session('search_error') }}
                </div>
            @endif
        </div>
        @endcan

        {{-- ── Filter ── --}}
        <div class="filter-box">
            <form method="GET" action="{{ route('dashboard.lens-purchase-orders.index') }}" class="row">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by PO# or Invoice#..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>Pending</option>
                        <option value="sent"      {{ request('status')=='sent'      ? 'selected':'' }}>Sent to Lab</option>
                        <option value="received"  {{ request('status')=='received'  ? 'selected':'' }}>Received</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block" style="height:42px;">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 text-right">
                    @if(request()->hasAny(['search','status']))
                    <a href="{{ route('dashboard.lens-purchase-orders.index') }}" class="btn btn-default" style="height:42px;line-height:22px;">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Table ── --}}
        <div class="table-responsive lpo-table">
            <table class="table table-hover" style="margin:0;">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Branch</th>
                        <th>Lab</th>
                        <th class="text-center">Items</th>
                        <th class="text-center">Status</th>
                        <th>Ordered By</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $po)
                    <tr>
                        <td>
                            <span class="po-badge">{{ $po->po_number }}</span>
                        </td>
                        <td>
                            @if($po->invoice)
                                <a href="{{ route('dashboard.invoice.show', $po->invoice->invoice_code) }}"
                                   style="font-weight:700;color:#e67e22;">
                                    {{ $po->invoice->invoice_code }}
                                </a>
                            @else
                                <span style="color:#aaa;">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ optional(optional($po->invoice)->customer)->english_name ?? '—' }}</td>
                        <td style="font-size:13px;">{{ $po->branch->name ?? '—' }}</td>
                        <td style="font-size:13px;">{{ $po->lab_name ?? '—' }}</td>
                        <td class="text-center">
                            <span style="background:#e8ecf7;color:#555;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                {{ $po->items->count() }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $st = $po->status;
                                $clsMap  = [
                                    'pending'   => 'status-pending',
                                    'sent'      => 'status-sent',
                                    'received'  => 'status-received',
                                    'cancelled' => 'status-cancelled',
                                ];
                                $iconMap = [
                                    'pending'   => 'bi-hourglass-split',
                                    'sent'      => 'bi-send-fill',
                                    'received'  => 'bi-check-circle-fill',
                                    'cancelled' => 'bi-x-circle-fill',
                                ];
                                $cls  = isset($clsMap[$st])  ? $clsMap[$st]  : 'status-pending';
                                $icon = isset($iconMap[$st]) ? $iconMap[$st] : 'bi-circle';
                            @endphp
                            <span class="{{ $cls }}">
                                <i class="bi {{ $icon }}"></i> {{ ucfirst($st) }}
                            </span>
                        </td>
                        <td style="font-size:13px;">{{ $po->orderedBy->name ?? '—' }}</td>
                        <td style="font-size:12px;color:#888;white-space:nowrap;">
                            {{ $po->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('dashboard.lens-purchase-orders.show', $po->id) }}"
                                   class="btn btn-view" title="View Order">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                @can('increase-stock')
                                    @if(!$po->isReceived() && !$po->isCancelled())
                                        <a href="{{ route('dashboard.lens-purchase-orders.receive', $po->id) }}"
                                           class="btn btn-receive" title="Receive Lenses">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="bi bi-flask"></i>
                                <h4>No lab orders found</h4>
                                <p style="color:#999;">
                                    @if(request()->hasAny(['search','status']))
                                        Try adjusting your filters
                                    @else
                                        Create your first lab order using the form above
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div style="margin-top:20px;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif

    </div>{{-- /box-body --}}
</div>{{-- /box-lpo --}}
</div>{{-- /lpo-page --}}

{{-- ── Add Lab Modal ── --}}
<div class="modal fade" id="addLabModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#e67e22,#d35400);color:#fff;border:none;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
                <h4 class="modal-title" style="font-weight:700;"><i class="bi bi-plus-circle"></i> Add New Lab</h4>
            </div>
            <form action="{{ route('dashboard.lens-labs.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:25px;">
                    <div class="form-group">
                        <label>Lab Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Essilor Lab"
                               style="border:2px solid #e0e6ed;border-radius:8px;">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Phone number"
                                       style="border:2px solid #e0e6ed;border-radius:8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email"
                                       style="border:2px solid #e0e6ed;border-radius:8px;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."
                                  style="border:2px solid #e0e6ed;border-radius:8px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:2px solid #f0f2f5;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:8px;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning" style="border-radius:8px;font-weight:700;">
                        <i class="bi bi-save"></i> Save Lab
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
