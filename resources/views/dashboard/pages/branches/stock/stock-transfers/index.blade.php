@extends('dashboard.layouts.master')
@section('title', 'Stock Transfers')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ══ Page ══ */
.st-page { padding: 20px; background: #f0f2f8; min-height: calc(100vh - 100px); }

/* ══ Header ══ */
.st-header {
    background: linear-gradient(135deg,#1a237e 0%,#283593 45%,#1565c0 100%);
    border-radius: 16px; padding: 26px 32px; color: #fff;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
    margin-bottom: 22px;
    box-shadow: 0 8px 30px rgba(21,101,192,.28);
    position: relative; overflow: hidden;
}
.st-header::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:220px; height:220px; border-radius:50%;
    background:rgba(255,255,255,.07);
}
.st-header h2 { margin:0; font-size:22px; font-weight:900; display:flex; align-items:center; gap:12px; }
.st-header .sub { font-size:12px; opacity:.7; margin-top:4px; }
.st-hdr-actions { display:flex; gap:10px; flex-wrap:wrap; position:relative; z-index:1; }
.st-hbtn {
    display:inline-flex; align-items:center; gap:7px;
    padding:10px 18px; border-radius:9px; font-size:13px; font-weight:700;
    cursor:pointer; border:none; text-decoration:none; transition:all .18s;
}
.st-hbtn:hover { transform:translateY(-2px); box-shadow:0 5px 18px rgba(0,0,0,.22); text-decoration:none; }
.st-hbtn-white  { background:rgba(255,255,255,.15); color:#fff; border:1.5px solid rgba(255,255,255,.3); }
.st-hbtn-white:hover  { background:#fff; color:#1565c0; }
.st-hbtn-green  { background:linear-gradient(135deg,#27ae60,#1e8449); color:#fff; }
.st-hbtn-indigo { background:linear-gradient(135deg,#5c6bc0,#3949ab); color:#fff; }

/* ══ Stats ══ */
.st-stats { display:flex; gap:14px; margin-bottom:20px; flex-wrap:wrap; }
.st-stat {
    flex:1; min-width:120px; background:#fff; border-radius:12px;
    padding:18px 20px; display:flex; align-items:center; gap:14px;
    box-shadow:0 2px 10px rgba(0,0,0,.07); border-left:4px solid transparent;
    transition:transform .2s, box-shadow .2s;
}
.st-stat:hover { transform:translateY(-2px); box-shadow:0 5px 20px rgba(0,0,0,.1); }
.st-stat.s-pending  { border-left-color:#f59e0b; }
.st-stat.s-approved { border-left-color:#0ea5e9; }
.st-stat.s-transit  { border-left-color:#6366f1; }
.st-stat.s-received { border-left-color:#10b981; }
.st-stat-icon {
    width:46px; height:46px; border-radius:11px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff;
}
.s-pending  .st-stat-icon { background:linear-gradient(135deg,#f59e0b,#d97706); }
.s-approved .st-stat-icon { background:linear-gradient(135deg,#0ea5e9,#0284c7); }
.s-transit  .st-stat-icon { background:linear-gradient(135deg,#6366f1,#4f46e5); }
.s-received .st-stat-icon { background:linear-gradient(135deg,#10b981,#059669); }
.st-stat-label { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.4px; }
.st-stat-val   { font-size:28px; font-weight:900; color:#1e293b; line-height:1.1; }

/* ══ Filter bar ══ */
.st-filter {
    background:#fff; border-radius:12px; padding:16px 20px;
    margin-bottom:18px; box-shadow:0 2px 10px rgba(0,0,0,.06);
    border:1px solid #e8ecf7;
}
.st-filter .form-control {
    border:1.5px solid #e0e6ed; border-radius:8px !important;
    padding:9px 14px; font-size:13px; height:40px; transition:border-color .2s;
}
.st-filter .form-control:focus { border-color:#3949ab; box-shadow:0 0 0 3px rgba(57,73,171,.1); }
.st-filter .btn-filter {
    background:linear-gradient(135deg,#3949ab,#283593); color:#fff;
    border:none; border-radius:8px; padding:9px 22px; font-weight:700; height:40px;
    display:inline-flex; align-items:center; gap:6px;
}
.st-filter .btn-reset {
    background:#f0f2f8; color:#64748b; border:1.5px solid #e0e6ed;
    border-radius:8px; padding:9px 18px; font-weight:600; height:40px;
    display:inline-flex; align-items:center; gap:6px; text-decoration:none;
}
.st-filter .btn-reset:hover { background:#e2e6f0; color:#1e293b; text-decoration:none; }

/* ══ Table card ══ */
.st-table-card {
    background:#fff; border-radius:14px;
    box-shadow:0 2px 14px rgba(0,0,0,.07); overflow:hidden;
    border:1px solid #e8ecf7;
}
.st-table { width:100%; border-collapse:collapse; }
.st-table thead tr { background:linear-gradient(135deg,#1a237e,#283593); }
.st-table thead th {
    color:#fff; font-size:11px; font-weight:800; text-transform:uppercase;
    letter-spacing:.7px; padding:14px 14px; border:none; white-space:nowrap;
}
.st-table tbody tr { border-bottom:1px solid #f0f4f8; transition:background .12s; }
.st-table tbody tr:hover { background:#f8f9ff; }
.st-table tbody td { padding:14px 14px; vertical-align:middle; }

/* ══ Request number cell ══ */
.req-num  { font-weight:800; color:#3949ab; font-size:13px; text-decoration:none; }
.req-num:hover { text-decoration:underline; color:#1a237e; }
.req-date { font-size:11px; color:#94a3b8; margin-top:2px; }
.batch-tag {
    display:inline-flex; align-items:center; gap:4px;
    font-size:11px; color:#7c3aed; background:#f5f3ff;
    padding:2px 8px; border-radius:5px; margin-top:3px;
    font-weight:700;
}

/* ══ Branch pills ══ */
.branch-pill {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:6px; font-size:11.5px; font-weight:700;
}
.bp-from { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
.bp-to   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.branch-arrow { color:#94a3b8; font-size:14px; margin: 0 4px; }

/* ══ Items list ══ */
.item-list { list-style:none; margin:0; padding:0; }
.item-list li {
    display:flex; align-items:center; gap:8px;
    padding:4px 0; font-size:12.5px; color:#334155;
}
.item-list li + li { border-top:1px dashed #f1f5f9; }
.item-icon-ok  { color:#10b981; font-size:14px; flex-shrink:0; }
.item-icon-x   { color:#ef4444; font-size:14px; flex-shrink:0; }
.item-icon-clk { color:#f59e0b; font-size:14px; flex-shrink:0; }
.item-icon-app { color:#0ea5e9; font-size:14px; flex-shrink:0; }
.item-icon-trk { color:#6366f1; font-size:14px; flex-shrink:0; }
.item-qty {
    background:#e8eaf6; color:#3949ab;
    padding:1px 7px; border-radius:5px; font-size:11px; font-weight:800;
    margin-left:auto; flex-shrink:0;
}

/* ══ Overall status badges ══ */
.stbadge {
    display:inline-flex; align-items:center; gap:5px;
    padding:6px 14px; border-radius:20px; font-size:11px; font-weight:700;
    text-transform:uppercase; letter-spacing:.4px; white-space:nowrap;
}
.stbadge-pending   { background:#fef3c7; color:#92400e; border:1.5px solid #fde68a; }
.stbadge-approved  { background:#e0f2fe; color:#0c4a6e; border:1.5px solid #bae6fd; }
.stbadge-transit   { background:#ede9fe; color:#4c1d95; border:1.5px solid #ddd6fe; }
.stbadge-done      { background:#d1fae5; color:#065f46; border:1.5px solid #a7f3d0; }
.stbadge-cancelled { background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0; }
.stbadge-partial   { background:#fff7ed; color:#9a3412; border:1.5px solid #fed7aa; }

/* ══ Action buttons ══ */
.act-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border-radius:7px; border:none; cursor:pointer;
    font-size:14px; transition:all .15s; text-decoration:none;
}
.act-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.2); text-decoration:none; }
.act-view    { background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; }
.act-approve { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
.act-ship    { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
.act-receive { background:linear-gradient(135deg,#0ea5e9,#0369a1); color:#fff; }
.act-cancel  { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }

/* ══ Empty state ══ */
.st-empty { text-align:center; padding:60px 20px; }
.st-empty i { font-size:72px; color:#d1d5db; display:block; margin-bottom:16px; }
.st-empty h4 { color:#9ca3af; font-size:17px; }
</style>

<div class="st-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="border-left:5px solid #10b981;border-radius:10px;margin-bottom:16px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-check-circle-fill"></i> {!! session('success') !!}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="border-left:5px solid #ef4444;border-radius:10px;margin-bottom:16px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="st-header">
        <div style="position:relative;z-index:1;">
            <h2><i class="bi bi-arrow-left-right"></i> Stock Transfers</h2>
            <div class="sub">Manage inter-branch transfer requests</div>
        </div>
        <div class="st-hdr-actions">
            @can('create-transfers')
                <a href="{{ route('dashboard.stock-transfers.create') }}" class="st-hbtn st-hbtn-green">
                    <i class="bi bi-plus-circle-fill"></i> New Request
                </a>
            @endcan
            @can('view-transfer-reports')
                <a href="{{ route('dashboard.stock-transfers.report') }}" class="st-hbtn st-hbtn-white">
                    <i class="bi bi-bar-chart-line-fill"></i> Report
                </a>
                <a href="{{ route('dashboard.stock-transfers.report.items') }}" class="st-hbtn st-hbtn-indigo">
                    <i class="bi bi-box-arrow-right"></i> By Item
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="st-stats">
        <div class="st-stat s-pending">
            <div class="st-stat-icon"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="st-stat-label">Pending</div>
                <div class="st-stat-val">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="st-stat s-approved">
            <div class="st-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="st-stat-label">Approved</div>
                <div class="st-stat-val">{{ $stats['approved'] }}</div>
            </div>
        </div>
        <div class="st-stat s-transit">
            <div class="st-stat-icon"><i class="bi bi-truck"></i></div>
            <div>
                <div class="st-stat-label">In Transit</div>
                <div class="st-stat-val">{{ $stats['in_transit'] }}</div>
            </div>
        </div>
        <div class="st-stat s-received">
            <div class="st-stat-icon"><i class="bi bi-check2-all"></i></div>
            <div>
                <div class="st-stat-label">Received</div>
                <div class="st-stat-val">{{ $stats['received'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── Filter ── --}}
    <div class="st-filter">
        <form method="GET" action="{{ route('dashboard.stock-transfers.index') }}">
            <div class="row" style="align-items:center;">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="🔍  Search by transfer #, batch # or product…"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending"    {{ request('status')=='pending'    ? 'selected':'' }}>Pending</option>
                        <option value="approved"   {{ request('status')=='approved'   ? 'selected':'' }}>Approved</option>
                        <option value="in_transit" {{ request('status')=='in_transit' ? 'selected':'' }}>In Transit</option>
                        <option value="received"   {{ request('status')=='received'   ? 'selected':'' }}>Received</option>
                        <option value="canceled"   {{ request('status')=='canceled'   ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="from_branch" class="form-control">
                        <option value="">From Branch…</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('from_branch')==$b->id ? 'selected':'' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" style="display:flex;gap:8px;">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('dashboard.stock-transfers.index') }}" class="btn-reset">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Table ── --}}
    <div class="st-table-card">
        @if($transfers->count() > 0)
        <div style="overflow-x:auto;">
            <table class="st-table">
                <thead>
                    <tr>
                        <th style="width:48px;text-align:center;">#</th>
                        <th style="width:170px;">Reference</th>
                        <th style="width:200px;">Route</th>
                        <th>Items</th>
                        <th style="width:130px;text-align:center;">Status</th>
                        <th style="width:100px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transfers as $gi => $group)
                @php
                    /* ── Representative (first item in group) ── */
                    $rep      = $group->first();
                    $isBatch  = $group->count() > 1;
                    $statuses = $group->pluck('status');

                    /* ── Overall status ── */
                    $allReceived = true;
                    $allCanceled = true;
                    $hasPending  = false;
                    $hasApproved = false;
                    $hasTransit  = false;

                    foreach ($statuses as $s) {
                        if ($s !== 'received') $allReceived = false;
                        if ($s !== 'canceled') $allCanceled = false;
                        if ($s === 'pending')   $hasPending  = true;
                        if ($s === 'approved')  $hasApproved = true;
                        if ($s === 'in_transit')$hasTransit  = true;
                    }

                    if ($allReceived)       $overallStatus = 'received';
                    elseif ($allCanceled)   $overallStatus = 'canceled';
                    elseif ($hasPending)    $overallStatus = 'pending';
                    elseif ($hasTransit)    $overallStatus = 'in_transit';
                    elseif ($hasApproved)   $overallStatus = 'approved';
                    else                    $overallStatus = 'partial'; // mix received+canceled

                    /* ── Link ── */
                    $link = $isBatch
                        ? route('dashboard.stock-transfers.batch', $rep->batch_number)
                        : route('dashboard.stock-transfers.show', $rep);

                    /* ── Total qty ── */
                    $totalQty = $group->sum('quantity');

                    /* ── Is fully closed (no more actions needed) ── */
                    $isClosed = in_array($overallStatus, ['received', 'canceled', 'partial']);
                @endphp
                <tr>
                    {{-- # --}}
                    <td style="text-align:center;color:#94a3b8;font-size:12px;font-weight:700;">
                        {{ $transfers->firstItem() + $gi }}
                    </td>

                    {{-- Reference # + date --}}
                    <td>
                        <a href="{{ $link }}" class="req-num">
                            @if($isBatch)
                                <i class="bi bi-collection-fill" style="color:#7c3aed;font-size:12px;"></i>
                                {{ $rep->batch_number }}
                            @else
                                {{ $rep->transfer_number }}
                            @endif
                        </a>
                        @if($isBatch)
                            <div style="margin-top:3px;">
                                <span class="batch-tag">
                                    <i class="bi bi-boxes"></i> {{ $group->count() }} items
                                </span>
                            </div>
                        @endif
                        <div class="req-date">
                            <i class="bi bi-calendar3" style="font-size:10px;"></i>
                            {{ $rep->transfer_date->format('d M Y') }}
                        </div>
                        @if($rep->creator)
                            <div class="req-date" style="margin-top:1px;">
                                <i class="bi bi-person" style="font-size:10px;"></i>
                                {{ $rep->creator->full_name ?? $rep->creator->name }}
                            </div>
                        @endif
                    </td>

                    {{-- Route: From → To --}}
                    <td>
                        <div style="display:flex;flex-direction:column;gap:5px;">
                            <span class="branch-pill bp-from">
                                <i class="bi bi-building"></i>
                                {{ $rep->fromBranch->name ?? '—' }}
                            </span>
                            <div style="text-align:center;color:#94a3b8;font-size:11px;">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                            <span class="branch-pill bp-to">
                                <i class="bi bi-building-fill"></i>
                                {{ $rep->toBranch->name ?? '—' }}
                            </span>
                        </div>
                    </td>

                    {{-- Items list with per-item status ── --}}
                    <td>
                        <ul class="item-list">
                        @foreach($group as $t)
                        @php
                            $iconClass = 'item-icon-clk';
                            $iconName  = 'clock-history';
                            if ($t->status === 'received')   { $iconClass = 'item-icon-ok';  $iconName = 'check-circle-fill'; }
                            elseif ($t->status === 'canceled'){ $iconClass = 'item-icon-x';   $iconName = 'x-circle-fill'; }
                            elseif ($t->status === 'approved'){ $iconClass = 'item-icon-app'; $iconName = 'check-circle'; }
                            elseif ($t->status === 'in_transit'){ $iconClass = 'item-icon-trk'; $iconName = 'truck'; }
                        @endphp
                        <li>
                            <i class="bi bi-{{ $iconName }} {{ $iconClass }}"></i>
                            <span style="{{ $t->status === 'canceled' ? 'text-decoration:line-through;color:#94a3b8;' : '' }}">
                                {{ Str::limit($t->item_description, 35) }}
                            </span>
                            <span class="item-qty">{{ $t->quantity }}</span>
                        </li>
                        @endforeach
                        </ul>
                    </td>

                    {{-- Overall Status ── --}}
                    <td style="text-align:center;">
                        @if($overallStatus === 'received')
                            <span class="stbadge stbadge-done">
                                <i class="bi bi-check2-all"></i> Completed
                            </span>
                        @elseif($overallStatus === 'canceled')
                            <span class="stbadge stbadge-cancelled">
                                <i class="bi bi-x-circle-fill"></i> Cancelled
                            </span>
                        @elseif($overallStatus === 'partial')
                            <span class="stbadge stbadge-partial">
                                <i class="bi bi-slash-circle"></i> Partial
                            </span>
                        @elseif($overallStatus === 'in_transit')
                            <span class="stbadge stbadge-transit">
                                <i class="bi bi-truck"></i> In Transit
                            </span>
                        @elseif($overallStatus === 'approved')
                            <span class="stbadge stbadge-approved">
                                <i class="bi bi-check-circle-fill"></i> Approved
                            </span>
                        @else
                            <span class="stbadge stbadge-pending">
                                <i class="bi bi-clock-history"></i> Pending
                            </span>
                        @endif
                    </td>

                    {{-- Actions ── --}}
                    <td style="text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;flex-wrap:wrap;">

                            {{-- View --}}
                            @can('view-transfers')
                                <a href="{{ $link }}" class="act-btn act-view" title="View details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            @endcan

                            @if(!$isClosed)
                                @if($isBatch)
                                    {{-- Batch: go to batch page for detailed actions --}}
                                    <a href="{{ $link }}" class="act-btn act-approve" title="Manage batch"
                                       style="background:linear-gradient(135deg,#7c3aed,#5b21b6);">
                                        <i class="bi bi-sliders"></i>
                                    </a>
                                @else
                                    {{-- Single transfer: quick actions ── --}}
                                    @if($rep->status === 'pending')
                                        @can('approve-transfers')
                                            <button class="act-btn act-approve" title="Approve"
                                                    onclick="quickAction('approve', {{ $rep->id }})">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endcan
                                    @endif

                                    @if($rep->status === 'approved')
                                        @can('ship-transfers')
                                            <button class="act-btn act-ship" title="Ship"
                                                    onclick="quickAction('ship', {{ $rep->id }})">
                                                <i class="bi bi-truck"></i>
                                            </button>
                                        @endcan
                                    @endif

                                    @if(in_array($rep->status, ['approved','in_transit']))
                                        @can('receive-transfers')
                                            <button class="act-btn act-receive" title="Receive"
                                                    onclick="quickAction('receive', {{ $rep->id }})">
                                                <i class="bi bi-check2-all"></i>
                                            </button>
                                        @endcan
                                    @endif

                                    @if($rep->status === 'pending')
                                        @can('cancel-transfers')
                                            <button class="act-btn act-cancel" title="Cancel"
                                                    onclick="quickAction('cancel', {{ $rep->id }})">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endcan
                                    @endif
                                @endif
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
            <div style="padding:16px 20px;border-top:1px solid #f0f4f8;">
                {{ $transfers->appends(request()->query())->links() }}
            </div>
        @endif

        @else
        <div class="st-empty">
            <i class="bi bi-inbox"></i>
            <h4>No transfer requests found</h4>
            <p style="color:#9ca3af;margin-top:6px;">
                @if(request()->hasAny(['search','status','from_branch']))
                    No results match your filters. <a href="{{ route('dashboard.stock-transfers.index') }}">Clear filters</a>
                @else
                    Create your first request to get started.
                @endif
            </p>
            @can('create-transfers')
                <a href="{{ route('dashboard.stock-transfers.create') }}"
                   style="margin-top:16px;display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#3949ab,#283593);color:#fff;padding:11px 22px;border-radius:9px;font-weight:700;text-decoration:none;">
                    <i class="bi bi-plus-circle-fill"></i> New Transfer Request
                </a>
            @endcan
        </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
function quickAction(action, id) {
    if (action === 'cancel') {
        Swal.fire({
            title: 'Cancel this transfer?',
            input: 'textarea',
            inputLabel: 'Reason for cancellation',
            inputPlaceholder: 'Enter reason…',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-x-circle"></i> Yes, Cancel',
            cancelButtonText: 'Go Back',
            inputValidator: function(v) { if (!v) return 'Please enter a reason.'; }
        }).then(function(r) {
            if (!r.isConfirmed) return;
            submitForm('/dashboard/stock-transfers/' + id + '/cancel', {
                _token: '{{ csrf_token() }}',
                rejection_reason: r.value
            });
        });
        return;
    }

    var cfg = {
        approve: { title:'Approve Transfer?', icon:'question', btnColor:'#10b981', btnText:'<i class="bi bi-check-lg"></i> Approve' },
        ship:    { title:'Mark as Shipped?',  icon:'info',     btnColor:'#6366f1', btnText:'<i class="bi bi-truck"></i> Ship' },
        receive: { title:'Confirm Receipt?',  icon:'success',  btnColor:'#0ea5e9', btnText:'<i class="bi bi-check2-all"></i> Receive' }
    }[action];

    Swal.fire({
        title: cfg.title, icon: cfg.icon, showCancelButton: true,
        confirmButtonColor: cfg.btnColor, cancelButtonColor: '#94a3b8',
        confirmButtonText: cfg.btnText, cancelButtonText: 'Cancel'
    }).then(function(r) {
        if (r.isConfirmed) submitForm('/dashboard/stock-transfers/' + id + '/' + action, { _token: '{{ csrf_token() }}' });
    });
}

function submitForm(action, data) {
    var form = document.createElement('form');
    form.method = 'POST'; form.action = action;
    Object.keys(data).forEach(function(k) {
        var inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = k; inp.value = data[k];
        form.appendChild(inp);
    });
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
