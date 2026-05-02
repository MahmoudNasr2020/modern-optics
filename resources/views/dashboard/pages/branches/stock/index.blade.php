@extends('dashboard.layouts.master')
@section('title', 'Stock — ' . $branch->name)
@section('content')

<style>
/* ────────────────────────────────────────────────────────
   BRANCH STOCK  ·  Premium Redesign
   .right-side .content { padding: 20px } → hero pulls back
──────────────────────────────────────────────────────── */

/* ── Hero (full-bleed — cancels the 20px content padding) ── */
.bst-hero {
    margin: -20px -20px 0;
    background: linear-gradient(135deg,#0f172a 0%,#1e3a5f 45%,#0c2340 75%,#0f172a 100%);
    padding: 34px 40px 64px;
    position: relative;
    overflow: hidden;
}
.bst-hero::before {
    content: '';
    position: absolute; top: -80px; left: -80px;
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(99,179,237,.13) 0%, transparent 70%);
    pointer-events: none;
}
.bst-hero::after {
    content: '';
    position: absolute; bottom: -60px; right: -60px;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(167,139,250,.10) 0%, transparent 70%);
    pointer-events: none;
}
.bst-hero-inner {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
    position: relative; z-index: 2;
}
.bst-hero-left { display: flex; align-items: center; gap: 18px; }
.bst-hero-icon {
    width: 58px; height: 58px;
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: #fff;
    box-shadow: 0 8px 24px rgba(99,102,241,.4);
    flex-shrink: 0;
}
.bst-hero-title {
    color: #fff; font-size: 22px; font-weight: 800;
    line-height: 1.2; margin: 0 0 4px;
}
.bst-hero-sub {
    color: rgba(255,255,255,.55); font-size: 13px; margin: 0;
}
.bst-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.bst-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px;
    font-size: 13px; font-weight: 700;
    border: none; cursor: pointer;
    transition: all .25s; text-decoration: none;
}
.bst-btn-green {
    background: linear-gradient(135deg,#10b981,#059669);
    color: #fff;
    box-shadow: 0 4px 14px rgba(16,185,129,.35);
}
.bst-btn-ghost {
    background: rgba(255,255,255,.12);
    color: #fff;
    border: 1.5px solid rgba(255,255,255,.25) !important;
}
.bst-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(0,0,0,.25);
    color: #fff; text-decoration: none;
}

/* ── Floating Stats Strip ── */
.bst-stats-outer {
    padding: 0 20px;
    margin-top: -30px;
    position: relative; z-index: 10;
}
.bst-stats-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 16px;
}
.bst-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: 0 8px 28px rgba(0,0,0,.09);
    display: flex; align-items: center; gap: 16px;
    border-bottom: 4px solid transparent;
    transition: transform .25s, box-shadow .25s;
    overflow: hidden;
}
.bst-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 36px rgba(0,0,0,.13);
}
.bst-stat-card.sc-products { border-bottom-color: #3b82f6; }
.bst-stat-card.sc-lenses   { border-bottom-color: #8b5cf6; }
.bst-stat-card.sc-low      { border-bottom-color: #f59e0b; }
.bst-stat-card.sc-out      { border-bottom-color: #ef4444; }
.bst-stat-ico {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff; flex-shrink: 0;
}
.sc-products .bst-stat-ico { background: linear-gradient(135deg,#3b82f6,#2563eb); box-shadow: 0 4px 12px rgba(59,130,246,.4); }
.sc-lenses   .bst-stat-ico { background: linear-gradient(135deg,#8b5cf6,#6d28d9); box-shadow: 0 4px 12px rgba(139,92,246,.4); }
.sc-low      .bst-stat-ico { background: linear-gradient(135deg,#f59e0b,#d97706); box-shadow: 0 4px 12px rgba(245,158,11,.4); }
.sc-out      .bst-stat-ico { background: linear-gradient(135deg,#ef4444,#dc2626); box-shadow: 0 4px 12px rgba(239,68,68,.4); }
.bst-stat-num { font-size: 30px; font-weight: 900; color: #1e293b; line-height: 1; }
.bst-stat-lbl { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .7px; margin-top: 5px; }

/* ── Content Area ── */
.bst-content { padding: 24px 20px 40px; }

/* ── Alerts ── */
.bst-alert {
    border-radius: 10px; padding: 14px 18px;
    margin-bottom: 16px; font-size: 13.5px;
    font-weight: 600; display: flex;
    align-items: center; gap: 10px;
}
.bst-alert-success { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; }
.bst-alert-error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; }

/* ── Filter Card ── */
.bst-filter-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    margin-bottom: 22px;
    border: 1.5px solid #e2e8f0;
}
.bst-filter-head {
    background: linear-gradient(135deg,#1e293b 0%,#334155 100%);
    padding: 14px 22px; color: #fff;
    font-size: 13px; font-weight: 700;
    letter-spacing: .4px;
    display: flex; align-items: center; gap: 8px;
}
.bst-filter-body { padding: 18px 22px; }
.bst-filter-body .form-control {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 9px !important;
    height: 40px !important;
    background: #f8fafc !important;
    font-size: 13px !important;
    transition: border-color .2s, box-shadow .2s !important;
}
.bst-filter-body .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important;
    background: #fff !important;
}
.bst-btn-filter {
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    color: #fff; border: none;
    border-radius: 9px; height: 40px;
    padding: 0 20px;
    font-size: 13px; font-weight: 700;
    cursor: pointer; width: 100%;
    display: flex; align-items: center;
    justify-content: center; gap: 6px;
    transition: all .2s;
}
.bst-btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(99,102,241,.35);
}

/* ── Table Card ── */
.bst-table-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    border: 1.5px solid #e2e8f0;
}
.bst-table-card .table {
    margin-bottom: 0 !important;
}
.bst-table-card .table > thead > tr > th {
    background: linear-gradient(135deg,#1e293b 0%,#334155 100%) !important;
    color: #e2e8f0 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: .8px !important;
    padding: 16px 14px !important;
    border: none !important;
    white-space: nowrap;
}
.bst-table-card .table > tbody > tr > td {
    padding: 15px 14px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #f1f5f9 !important;
    border-top: none !important;
    font-size: 13.5px !important;
    color: #374151 !important;
}
.bst-table-card .table > tbody > tr:last-child > td {
    border-bottom: none !important;
}
.bst-table-card .table > tbody > tr:hover > td {
    background: #f8faff !important;
}

/* Row number */
.bst-row-num {
    width: 28px; height: 28px; border-radius: 7px;
    background: linear-gradient(135deg,#e0e7ff,#c7d2fe);
    color: #4338ca; font-size: 11px; font-weight: 800;
    display: inline-flex; align-items: center; justify-content: center;
}

/* Type badge */
.bst-type {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
}
.bst-type-product { background: #dbeafe; color: #1d4ed8; }
.bst-type-lens    { background: #ede9fe; color: #5b21b6; }

/* Code */
.bst-code {
    font-family: 'Courier New', monospace;
    font-size: 12px; font-weight: 700;
    color: #4f46e5; background: #eef2ff;
    padding: 3px 9px; border-radius: 6px;
    display: inline-block;
}

/* Quantity */
.bst-qty { font-size: 20px; font-weight: 900; line-height: 1; }
.bst-qty-good { color: #16a34a; }
.bst-qty-low  { color: #d97706; }
.bst-qty-out  { color: #dc2626; }

/* Status badge */
.bst-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    white-space: nowrap;
}
.bst-st-normal { background: #dcfce7; color: #15803d; }
.bst-st-low    { background: #fef3c7; color: #b45309; }
.bst-st-over   { background: #dbeafe; color: #1d4ed8; }
.bst-st-out    { background: #fee2e2; color: #b91c1c; }

/* Reserved */
.bst-reserved {
    background: #fff7ed; color: #c2410c;
    border: 1.5px solid #fed7aa;
    font-size: 11px; font-weight: 700;
    padding: 2px 8px; border-radius: 6px;
    display: inline-block;
}

/* ── Action Buttons ── */
.bst-actions { display: flex; gap: 5px; justify-content: center; flex-wrap: wrap; }
.bst-act {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 11px; border-radius: 8px;
    font-size: 11.5px; font-weight: 700;
    border: none; cursor: pointer;
    transition: all .2s; text-decoration: none;
    white-space: nowrap;
}
.bst-act:hover { transform: translateY(-2px); text-decoration: none; }
.bst-act-add     { background: linear-gradient(135deg,#10b981,#059669); color:#fff !important; box-shadow:0 3px 10px rgba(16,185,129,.3); }
.bst-act-reduce  { background: linear-gradient(135deg,#ef4444,#dc2626); color:#fff !important; box-shadow:0 3px 10px rgba(239,68,68,.3); }
.bst-act-view    { background: linear-gradient(135deg,#3b82f6,#2563eb); color:#fff !important; box-shadow:0 3px 10px rgba(59,130,246,.3); }
.bst-act-edit    { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff !important; box-shadow:0 3px 10px rgba(245,158,11,.3); }
.bst-act-history { background: linear-gradient(135deg,#8b5cf6,#6d28d9); color:#fff !important; box-shadow:0 3px 10px rgba(139,92,246,.3); }
.bst-act-delete  { background: linear-gradient(135deg,#6b7280,#4b5563); color:#fff !important; box-shadow:0 3px 10px rgba(107,114,128,.3); }
.bst-act-add:hover     { box-shadow:0 6px 18px rgba(16,185,129,.45); }
.bst-act-reduce:hover  { box-shadow:0 6px 18px rgba(239,68,68,.45); }
.bst-act-view:hover    { box-shadow:0 6px 18px rgba(59,130,246,.45); }
.bst-act-edit:hover    { box-shadow:0 6px 18px rgba(245,158,11,.45); }
.bst-act-history:hover { box-shadow:0 6px 18px rgba(139,92,246,.45); }
.bst-act-delete:hover  { box-shadow:0 6px 18px rgba(107,114,128,.45); }

/* ── Pagination ── */
.bst-pager { margin-top: 22px; display: flex; justify-content: center; }
.bst-pager .pagination { margin: 0; }
.bst-pager .pagination > li > a,
.bst-pager .pagination > li > span {
    padding: 8px 15px; font-size: 13px; font-weight: 700;
    border: 2px solid #e2e8f0 !important; color: #475569;
    border-radius: 9px !important; margin: 0 2px;
    transition: all .2s; background: #fff;
}
.bst-pager .pagination > li.active > a,
.bst-pager .pagination > li.active > span {
    background: linear-gradient(135deg,#6366f1,#4f46e5) !important;
    border-color: #6366f1 !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(99,102,241,.4);
}
.bst-pager .pagination > li > a:hover {
    background: #eef2ff; color: #4338ca;
    border-color: #c7d2fe !important;
    text-decoration: none;
}

/* ── Empty State ── */
.bst-empty {
    text-align: center;
    padding: 70px 20px;
}
.bst-empty-ico {
    width: 80px; height: 80px; border-radius: 20px;
    background: linear-gradient(135deg,#f1f5f9,#e2e8f0);
    display: inline-flex; align-items: center;
    justify-content: center;
    font-size: 38px; color: #94a3b8;
    margin-bottom: 18px;
}
.bst-empty h4 { color: #64748b; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
.bst-empty p  { color: #94a3b8; font-size: 14px; margin-bottom: 18px; }

/* ── Modals ── */
.bst-modal .modal-content  { border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
.bst-modal .modal-header   { padding: 20px 24px; border: none; }
.bst-modal .modal-title    { font-weight: 800; font-size: 16px; }
.bst-modal .modal-body     { padding: 24px; }
.bst-modal .modal-footer   { padding: 16px 24px; border-top: 2px solid #f1f5f9; background: #fafbfc; }
.bst-modal label           { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; }
.bst-modal .form-control,
.bst-modal select.form-control {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 9px !important;
    padding: 10px 14px !important;
    font-size: 13.5px !important;
    height: auto !important;
    background: #f8fafc !important;
    transition: border-color .2s !important;
}
.bst-modal .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,.12) !important;
    background: #fff !important;
}
.bst-curr-qty {
    background: linear-gradient(135deg,#f8fafc,#f1f5f9);
    border: 2px solid #e2e8f0; border-radius: 10px;
    padding: 14px 18px; margin-bottom: 18px;
    display: flex; align-items: center; gap: 14px;
}
.bst-curr-qty .cq-num { font-size: 32px; font-weight: 900; color: #1e293b; line-height: 1; }
.bst-curr-qty .cq-lbl { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
.bst-mfooter-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 10px;
    font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    transition: all .2s;
}
.bst-mfooter-btn:hover { transform: translateY(-1px); }
.bst-mbtn-cancel  { background: #f1f5f9; color: #475569; }
.bst-mbtn-cancel:hover { background: #e2e8f0; }
.bst-mbtn-add    { background: linear-gradient(135deg,#10b981,#059669); color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,.35); }
.bst-mbtn-reduce { background: linear-gradient(135deg,#ef4444,#dc2626); color: #fff; box-shadow: 0 4px 14px rgba(239,68,68,.35); }
.bst-mbtn-delete { background: linear-gradient(135deg,#6b7280,#4b5563); color: #fff; box-shadow: 0 4px 14px rgba(107,114,128,.3); }
.bst-mbtn-add:hover    { box-shadow: 0 8px 22px rgba(16,185,129,.45); }
.bst-mbtn-reduce:hover { box-shadow: 0 8px 22px rgba(239,68,68,.45); }
.bst-mbtn-delete:hover { box-shadow: 0 8px 22px rgba(107,114,128,.4); }
.bst-warn-box {
    background: #fef9ec; border: 1.5px solid #fcd34d;
    border-radius: 10px; padding: 14px 16px;
    font-size: 13px; color: #78350f;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 12px;
}

@media (max-width: 900px) {
    .bst-stats-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 600px) {
    .bst-hero { padding: 24px 20px 54px; }
    .bst-stats-outer { padding: 0 10px; }
    .bst-content { padding: 18px 10px 30px; }
    .bst-stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
}
</style>

{{-- hide the built-in section header --}}
<section class="content-header" style="margin:0;padding:0;height:0;overflow:hidden;"></section>

{{-- ── Hero ──────────────────────────────────────────────── --}}
<div class="bst-hero">
    <div class="bst-hero-inner">
        <div class="bst-hero-left">
            <div class="bst-hero-icon"><i class="bi bi-boxes"></i></div>
            <div>
                <p class="bst-hero-title">
                    <i class="bi bi-geo-alt-fill" style="font-size:15px;opacity:.65;"></i>
                    {{ $branch->name }} — Stock
                </p>
                <p class="bst-hero-sub">Branch inventory management &nbsp;·&nbsp; {{ $stocks->total() }} total items</p>
            </div>
        </div>
        <div class="bst-hero-actions">
            @can('create-stock')
                <a href="{{ route('dashboard.branches.stock.create', $branch) }}" class="bst-btn bst-btn-green">
                    <i class="bi bi-plus-circle-fill"></i> Add Stock Item
                </a>
            @endcan
            @can('export-stock-report')
                <a href="{{ route('dashboard.branches.stock.report', $branch) }}" class="bst-btn bst-btn-ghost">
                    <i class="bi bi-file-earmark-pdf"></i> PDF Report
                </a>
            @endcan
        </div>
    </div>
</div>

{{-- ── Floating Stats Strip ──────────────────────────────── --}}
<div class="bst-stats-outer">
    <div class="bst-stats-grid">
        <div class="bst-stat-card sc-products">
            <div class="bst-stat-ico"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="bst-stat-num">{{ $stocks->where('stockable_type','App\\Product')->count() }}</div>
                <div class="bst-stat-lbl">Products</div>
            </div>
        </div>
        <div class="bst-stat-card sc-lenses">
            <div class="bst-stat-ico"><i class="bi bi-eye"></i></div>
            <div>
                <div class="bst-stat-num">{{ $stocks->where('stockable_type','App\\glassLense')->count() }}</div>
                <div class="bst-stat-lbl">Lenses</div>
            </div>
        </div>
        <div class="bst-stat-card sc-low">
            <div class="bst-stat-ico"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="bst-stat-num">{{ $stocks->filter(function($s){ return $s->quantity <= $s->min_quantity && $s->quantity > 0; })->count() }}</div>
                <div class="bst-stat-lbl">Low Stock</div>
            </div>
        </div>
        <div class="bst-stat-card sc-out">
            <div class="bst-stat-ico"><i class="bi bi-x-octagon-fill"></i></div>
            <div>
                <div class="bst-stat-num">{{ $stocks->filter(function($s){ return $s->quantity == 0; })->count() }}</div>
                <div class="bst-stat-lbl">Out of Stock</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Content ───────────────────────────────────────────── --}}
<div class="bst-content">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bst-alert bst-alert-success">
            <i class="bi bi-check-circle-fill" style="font-size:18px;flex-shrink:0;"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bst-alert bst-alert-error">
            <i class="bi bi-exclamation-circle-fill" style="font-size:18px;flex-shrink:0;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Filter Card ────────────────────────────────────── --}}
    <div class="bst-filter-card">
        <div class="bst-filter-head">
            <i class="bi bi-funnel-fill"></i> Filter &amp; Search
        </div>
        <div class="bst-filter-body">
            <form method="GET" action="{{ route('dashboard.branches.stock.index', $branch) }}">
                <div class="row">
                    <div class="col-md-4" style="margin-bottom:10px;">
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by code or description…"
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3" style="margin-bottom:10px;">
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="product" {{ request('type')=='product'?'selected':'' }}>Products Only</option>
                            <option value="lens"    {{ request('type')=='lens'   ?'selected':'' }}>Lenses Only</option>
                        </select>
                    </div>
                    <div class="col-md-3" style="margin-bottom:10px;">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="normal" {{ request('status')=='normal'?'selected':'' }}>Normal Stock</option>
                            <option value="low"    {{ request('status')=='low'   ?'selected':'' }}>Low Stock</option>
                            <option value="out"    {{ request('status')=='out'   ?'selected':'' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-2" style="margin-bottom:10px;">
                        <button type="submit" class="bst-btn-filter">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Stock Table ──────────────────────────────────────── --}}
    @if($stocks->count() > 0)
        <div class="bst-table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="4%"  class="text-center">#</th>
                        <th width="8%">Type</th>
                        <th width="10%">Code</th>
                        <th width="23%">Description</th>
                        <th width="7%"  class="text-center">Qty</th>
                        <th width="7%"  class="text-center">Reserved</th>
                        <th width="7%"  class="text-center">Available</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="24%" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stocks as $index => $stock)
                        <tr>
                            {{-- # --}}
                            <td class="text-center">
                                <span class="bst-row-num">{{ $stocks->firstItem() + $index }}</span>
                            </td>

                            {{-- Type --}}
                            <td>
                                @if($stock->stockable_type === 'App\\Product')
                                    <span class="bst-type bst-type-product"><i class="bi bi-box-seam"></i> Product</span>
                                @else
                                    <span class="bst-type bst-type-lens"><i class="bi bi-eye"></i> Lens</span>
                                @endif
                            </td>

                            {{-- Code --}}
                            <td><span class="bst-code">{{ $stock->item_code }}</span></td>

                            {{-- Description --}}
                            <td>
                                <strong style="font-size:13.5px;color:#1e293b;">{{ $stock->description }}</strong>
                                @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                                    <br><small style="color:#94a3b8;">{{ $stock->stockable->brand }} &mdash; {{ $stock->stockable->index }}</small>
                                @endif
                            </td>

                            {{-- Quantity --}}
                            <td class="text-center">
                                @php
                                    $qClass = $stock->quantity > $stock->min_quantity
                                        ? 'bst-qty-good'
                                        : ($stock->quantity > 0 ? 'bst-qty-low' : 'bst-qty-out');
                                @endphp
                                <span class="bst-qty {{ $qClass }}">{{ $stock->quantity }}</span>
                            </td>

                            {{-- Reserved --}}
                            <td class="text-center">
                                @if($stock->reserved_quantity > 0)
                                    <span class="bst-reserved">{{ $stock->reserved_quantity }}</span>
                                @else
                                    <span style="color:#cbd5e1;font-weight:700;">—</span>
                                @endif
                            </td>

                            {{-- Available --}}
                            <td class="text-center">
                                <strong style="color:#0369a1;font-size:15px;font-weight:800;">{{ $stock->available_quantity }}</strong>
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @php $st = $stock->stock_status; @endphp
                                @if($st['status'] === 'normal')
                                    <span class="bst-status bst-st-normal"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                                @elseif($st['status'] === 'low_stock')
                                    <span class="bst-status bst-st-low"><i class="bi bi-exclamation-triangle-fill"></i> Low</span>
                                @elseif($st['status'] === 'over_stock')
                                    <span class="bst-status bst-st-over"><i class="bi bi-arrow-up-circle-fill"></i> Over</span>
                                @else
                                    <span class="bst-status bst-st-out"><i class="bi bi-x-circle-fill"></i> Out</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="bst-actions">
                                    @can('increase-stock')
                                        <button type="button"
                                                class="bst-act bst-act-add open-add-modal"
                                                data-description="{{ $stock->description }}"
                                                data-quantity="{{ $stock->quantity }}"
                                                data-url="{{ route('dashboard.branches.stock.add-quantity', [$branch, $stock]) }}">
                                            <i class="bi bi-plus-lg"></i> Add
                                        </button>
                                    @endcan
                                    @can('decrease-stock')
                                        <button type="button"
                                                class="bst-act bst-act-reduce open-reduce-modal"
                                                data-description="{{ $stock->description }}"
                                                data-quantity="{{ $stock->quantity }}"
                                                data-max="{{ $stock->available_quantity }}"
                                                data-url="{{ route('dashboard.branches.stock.reduce-quantity', [$branch, $stock]) }}">
                                            <i class="bi bi-dash-lg"></i> Reduce
                                        </button>
                                    @endcan
                                    @can('view-stock')
                                        <a href="{{ route('dashboard.branches.stock.show', [$branch, $stock]) }}"
                                           class="bst-act bst-act-view">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    @endcan
                                    @can('edit-stock')
                                        <a href="{{ route('dashboard.branches.stock.edit', [$branch, $stock]) }}"
                                           class="bst-act bst-act-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @endcan
                                    @can('view-stock-movements')
                                        <a href="{{ route('dashboard.branches.stock.movements', [$branch, $stock]) }}"
                                           class="bst-act bst-act-history">
                                            <i class="bi bi-clock-history"></i> History
                                        </a>
                                    @endcan
                                    @can('delete-stock')
                                        <button type="button"
                                                class="bst-act bst-act-delete btn-delete-stock"
                                                data-id="{{ $stock->id }}"
                                                data-description="{{ $stock->description }}">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($stocks->hasPages())
            <div class="bst-pager">
                {{ $stocks->appends(request()->query())->links() }}
            </div>
        @endif

    @else
        <div class="bst-table-card">
            <div class="bst-empty">
                <div class="bst-empty-ico"><i class="bi bi-inbox"></i></div>
                <h4>No Stock Items Found</h4>
                <p>Add items to this branch's inventory to get started.</p>
                @can('create-stock')
                    <a href="{{ route('dashboard.branches.stock.create', $branch) }}" class="bst-act bst-act-add" style="display:inline-flex;">
                        <i class="bi bi-plus-lg"></i> Add First Item
                    </a>
                @endcan
            </div>
        </div>
    @endif

</div>{{-- /bst-content --}}


{{-- ═══════════════════════════════════════════
     MODALS
═══════════════════════════════════════════ --}}

{{-- Add Quantity --}}
<div class="modal fade bst-modal" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#10b981,#059669);">
                <button type="button" class="close" data-dismiss="modal"
                        style="color:#fff;opacity:1;font-size:22px;line-height:1;">&times;</button>
                <h4 class="modal-title" style="color:#fff;">
                    <i class="bi bi-plus-circle-fill"></i> Add Quantity
                    <small style="display:block;font-size:13px;font-weight:400;opacity:.85;margin-top:2px;" id="addDescription"></small>
                </h4>
            </div>
            <form id="addForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="bst-curr-qty">
                        <div>
                            <div class="cq-lbl">Current Quantity</div>
                            <div class="cq-num" id="addCurrentQty">—</div>
                        </div>
                        <i class="bi bi-boxes" style="font-size:36px;color:#d1d5db;margin-left:auto;"></i>
                    </div>
                    <div class="form-group">
                        <label>Quantity to Add <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="quantity" class="form-control" required min="1" placeholder="Enter quantity…">
                    </div>
                    <div class="form-group">
                        <label>Cost per Unit <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
                        <input type="number" name="cost" class="form-control" step="0.01" min="0" placeholder="0.00">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Notes <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any notes…" style="height:auto;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bst-mfooter-btn bst-mbtn-cancel" data-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="submit" class="bst-mfooter-btn bst-mbtn-add">
                        <i class="bi bi-check2-circle"></i> Confirm Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reduce Quantity --}}
<div class="modal fade bst-modal" id="reduceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                <button type="button" class="close" data-dismiss="modal"
                        style="color:#fff;opacity:1;font-size:22px;line-height:1;">&times;</button>
                <h4 class="modal-title" style="color:#fff;">
                    <i class="bi bi-dash-circle-fill"></i> Reduce Quantity
                    <small style="display:block;font-size:13px;font-weight:400;opacity:.85;margin-top:2px;" id="reduceDescription"></small>
                </h4>
            </div>
            <form id="reduceForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="bst-curr-qty">
                        <div>
                            <div class="cq-lbl">Current Quantity</div>
                            <div class="cq-num" id="reduceCurrentQty">—</div>
                        </div>
                        <i class="bi bi-dash-circle" style="font-size:36px;color:#d1d5db;margin-left:auto;"></i>
                    </div>
                    <div class="form-group">
                        <label>Quantity to Reduce <span style="color:#ef4444;">*</span></label>
                        <input type="number" id="reduceQtyInput" name="quantity"
                               class="form-control" required min="1" placeholder="Enter quantity…">
                        <small style="color:#94a3b8;font-size:12px;display:block;margin-top:4px;">
                            Maximum available: <strong id="reduceMaxText" style="color:#1e293b;"></strong>
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Reason <span style="color:#ef4444;">*</span></label>
                        <select name="reason" class="form-control" required>
                            <option value="">Select reason…</option>
                            <option>Damaged</option>
                            <option>Expired</option>
                            <option>Returned</option>
                            <option>Sample</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Notes <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Additional details…" style="height:auto;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bst-mfooter-btn bst-mbtn-cancel" data-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="submit" class="bst-mfooter-btn bst-mbtn-reduce">
                        <i class="bi bi-check2-circle"></i> Confirm Reduce
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete --}}
<div class="modal fade bst-modal" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#6b7280,#4b5563);">
                <button type="button" class="close" data-dismiss="modal"
                        style="color:#fff;opacity:1;font-size:22px;line-height:1;">&times;</button>
                <h4 class="modal-title" style="color:#fff;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                </h4>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="bst-warn-box">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:20px;color:#f59e0b;flex-shrink:0;margin-top:1px;"></i>
                        <span>Are you sure you want to delete <strong id="stockDescription"></strong> from this branch's inventory?</span>
                    </div>
                    <p style="font-size:13px;color:#94a3b8;margin:0;">
                        <i class="bi bi-info-circle"></i>
                        This removes the stock entry only — product records are not affected.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bst-mfooter-btn bst-mbtn-cancel" data-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="submit" class="bst-mfooter-btn bst-mbtn-delete">
                        <i class="bi bi-trash3-fill"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ── Add Quantity ───────────────────────────────────────────────
    $('.open-add-modal').on('click', function () {
        $('#addDescription').text($(this).data('description'));
        $('#addCurrentQty').text($(this).data('quantity'));
        $('#addForm').attr('action', $(this).data('url'));
        $('#addModal').modal('show');
    });

    // ── Reduce Quantity ────────────────────────────────────────────
    $('.open-reduce-modal').on('click', function () {
        $('#reduceDescription').text($(this).data('description'));
        $('#reduceCurrentQty').text($(this).data('quantity'));
        $('#reduceMaxText').text($(this).data('max'));
        $('#reduceQtyInput').attr('max', $(this).data('max'));
        $('#reduceForm').attr('action', $(this).data('url'));
        $('#reduceModal').modal('show');
    });

    // ── Delete ─────────────────────────────────────────────────────
    $('.btn-delete-stock').on('click', function () {
        var stockId     = $(this).data('id');
        var description = $(this).data('description');
        var url = '{{ route("dashboard.branches.stock.destroy", [$branch, ":id"]) }}';
        url = url.replace(':id', stockId);
        $('#stockDescription').text(description);
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });

});
</script>
@endsection
