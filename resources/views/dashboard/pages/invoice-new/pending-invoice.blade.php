@extends('dashboard.layouts.master')

@section('title', 'Pending Invoices')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
/* ════════════════════════════════════════════
   PENDING INVOICES — PREMIUM REDESIGN
════════════════════════════════════════════ */
* { box-sizing: border-box; }

.piv-body {
    background: #f0f3f9;
    padding: 0 24px 48px;
    min-height: 100vh;
}

/* ─── Hero Banner ─── */
.piv-hero {
    position: relative; overflow: hidden;
    background: linear-gradient(135deg, #1a1f36 0%, #2d3561 50%, #1a1f36 100%);
    border-radius: 0 0 28px 28px;
    padding: 32px 32px 50px;
    margin: 0 -24px 0;
    color: #fff;
}
.piv-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(circle at 20% 50%, rgba(99,102,241,.25) 0%, transparent 55%),
        radial-gradient(circle at 80% 20%, rgba(59,130,246,.2) 0%, transparent 45%),
        radial-gradient(circle at 60% 80%, rgba(139,92,246,.15) 0%, transparent 40%);
}
.piv-hero-dots {
    position: absolute; inset: 0; overflow: hidden; pointer-events: none;
}
.piv-hero-dots::before {
    content: '';
    position: absolute; width: 500px; height: 500px;
    border-radius: 50%; border: 1px solid rgba(255,255,255,.05);
    top: -200px; right: -100px;
}
.piv-hero-dots::after {
    content: '';
    position: absolute; width: 300px; height: 300px;
    border-radius: 50%; border: 1px solid rgba(255,255,255,.06);
    bottom: -150px; left: -50px;
}
.piv-hero-inner {
    position: relative; z-index: 2;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
}
.piv-hero-icon {
    width: 56px; height: 56px; border-radius: 16px; flex-shrink: 0;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; box-shadow: 0 8px 24px rgba(99,102,241,.45);
}
.piv-hero h1 {
    margin: 0 0 4px; font-size: 24px; font-weight: 900; color: #fff;
}
.piv-hero p {
    margin: 0; font-size: 13px; color: rgba(255,255,255,.65);
}
.piv-hero-badge {
    background: rgba(255,255,255,.12); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2); border-radius: 50px;
    padding: 10px 22px; font-size: 16px; font-weight: 800; color: #fff;
    display: flex; align-items: center; gap: 8px;
}

/* ─── Stats strip (floats over hero bottom) ─── */
.piv-stats-strip {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-top: -28px;
    position: relative; z-index: 10;
    padding: 0 2px;
}
@media(max-width:900px){ .piv-stats-strip { grid-template-columns: repeat(3,1fr); } }
@media(max-width:600px){ .piv-stats-strip { grid-template-columns: 1fr 1fr; } }

.piv-stat {
    background: #fff;
    border-radius: 16px;
    padding: 18px 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
    display: flex; flex-direction: column; gap: 10px;
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.piv-stat:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.13); }
.piv-stat::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    border-radius: 0 0 16px 16px;
}
.piv-stat.s-blue::after   { background: linear-gradient(90deg,#3b82f6,#6366f1); }
.piv-stat.s-amber::after  { background: linear-gradient(90deg,#f59e0b,#f97316); }
.piv-stat.s-purple::after { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
.piv-stat.s-green::after  { background: linear-gradient(90deg,#22c55e,#16a34a); }
.piv-stat.s-red::after    { background: linear-gradient(90deg,#ef4444,#dc2626); }

.piv-stat-ic {
    width: 40px; height: 40px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
}
.s-blue   .piv-stat-ic { background:#eff6ff; color:#3b82f6; }
.s-amber  .piv-stat-ic { background:#fffbeb; color:#f59e0b; }
.s-purple .piv-stat-ic { background:#f5f3ff; color:#8b5cf6; }
.s-green  .piv-stat-ic { background:#f0fdf4; color:#22c55e; }
.s-red    .piv-stat-ic { background:#fef2f2; color:#ef4444; }

.piv-stat-val { font-size: 26px; font-weight: 900; color: #1e293b; line-height: 1; }
.piv-stat-lbl { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }

/* ─── Section gap ─── */
.piv-gap { height: 22px; }

/* ─── Filter card ─── */
.piv-filter {
    background: #fff; border-radius: 18px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    overflow: hidden; margin-bottom: 18px;
}
.piv-filter-head {
    padding: 14px 22px;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    display: flex; align-items: center; gap: 10px;
    color: #fff; font-size: 14px; font-weight: 800;
}
.piv-filter-head i { font-size: 18px; }
.piv-filter-body { padding: 20px 22px; }
.piv-filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
    align-items: end;
}
.piv-fld label {
    font-size: 11px; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: .4px;
    display: flex; align-items: center; gap: 5px; margin-bottom: 6px;
}
.piv-fld label i { color: #6366f1; }
.piv-input {
    width: 100%; padding: 9px 13px;
    border: 2px solid #e2e8f0; border-radius: 10px;
    font-size: 13px; color: #1e293b; background: #fff;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
.piv-input:focus { border-color: #6366f1; outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

.piv-has-remaining {
    display: flex; align-items: center; gap: 9px;
    padding: 10px 14px; background: #f8f9ff;
    border: 2px solid #e0e7ff; border-radius: 10px;
    cursor: pointer; font-size: 13px; font-weight: 600; color: #4338ca;
}
.piv-has-remaining input { accent-color: #6366f1; width:16px; height:16px; cursor:pointer; }

.piv-filter-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:14px; }

.piv-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 10px; border: none;
    font-size: 13px; font-weight: 700; cursor: pointer; transition: all .2s; text-decoration: none;
}
.piv-btn:hover { transform: translateY(-2px); text-decoration: none; }
.piv-btn.primary {
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,.3);
}
.piv-btn.primary:hover { box-shadow: 0 8px 22px rgba(99,102,241,.4); color:#fff; }
.piv-btn.clear {
    background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0;
}
.piv-btn.clear:hover { background: #e2e8f0; color:#1e293b; }

/* ─── Table card ─── */
.piv-table-card {
    background: #fff; border-radius: 18px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07); overflow: hidden;
}
.piv-table-topbar {
    padding: 16px 22px; border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    background: linear-gradient(135deg,#fafbff,#fff);
}
.piv-table-topbar h4 {
    margin: 0; font-size: 15px; font-weight: 800; color: #1e293b;
    display: flex; align-items: center; gap: 8px;
}
.piv-results-badge {
    background: #eff0ff; color: #4338ca;
    font-size: 12px; font-weight: 800; padding: 3px 11px;
    border-radius: 20px;
}

.piv-table { width: 100%; border-collapse: collapse; min-width: 800px; }

.piv-table thead tr {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
}
.piv-table thead th {
    padding: 13px 14px; color: rgba(255,255,255,.85);
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; white-space: nowrap; text-align: center;
    border: none;
}
.piv-table thead th:first-child { border-radius: 0; }

.piv-table tbody tr {
    border-bottom: 1px solid #f0f4f8; transition: background .15s;
}
.piv-table tbody tr:hover { background: #f8f9ff; }
.piv-table tbody tr:last-child { border-bottom: none; }

.piv-table td {
    padding: 13px 14px; font-size: 13px;
    color: #334155; text-align: center; vertical-align: middle;
}

/* ── Cell components ── */
.piv-inv-code {
    display: inline-flex; align-items: center; gap: 5px;
    background: #eff6ff; color: #1d4ed8;
    font-family: monospace; font-size: 12px; font-weight: 800;
    padding: 4px 11px; border-radius: 7px;
    text-decoration: none; transition: all .2s;
}
.piv-inv-code:hover { background: #1d4ed8; color: #fff; text-decoration: none; }

.piv-customer-name { font-weight: 700; color: #1e293b; }

.piv-total   { font-weight: 800; color: #16a34a; }
.piv-remain  { font-weight: 800; color: #dc2626; }
.piv-paid    { font-weight: 700; color: #16a34a; }

/* Status badges */
.piv-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
    white-space: nowrap;
}
.piv-status.pending       { background: #fffbeb; color: #92400e; }
.piv-status.under-process { background: #eff6ff; color: #1d4ed8; }
.piv-status.ready         { background: #f0fdf4; color: #15803d; }
.piv-status.delivered     { background: #f0fdf4; color: #065f46; }

/* Lab order cell */
.piv-lab-active {
    display: inline-flex; align-items: center; gap: 4px;
    color: #16a34a; font-size: 12px; font-weight: 700;
}
.piv-lab-new {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fff7ed; border: 1.5px solid #fed7aa;
    color: #c2410c; border-radius: 7px;
    padding: 4px 10px; font-size: 11px; font-weight: 700;
    text-decoration: none; transition: all .2s;
}
.piv-lab-new:hover { background: #f97316; color: #fff; border-color: #f97316; text-decoration: none; }
.piv-cl-active {
    display: inline-flex; align-items: center; gap: 4px;
    color: #2563eb; font-size: 12px; font-weight: 700;
}
.piv-cl-new {
    display: inline-flex; align-items: center; gap: 4px;
    background: #eff6ff; border: 1.5px solid #bfdbfe;
    color: #1d4ed8; border-radius: 7px;
    padding: 4px 10px; font-size: 11px; font-weight: 700;
    text-decoration: none; transition: all .2s;
}
.piv-cl-new:hover { background: #3b82f6; color: #fff; border-color: #3b82f6; text-decoration: none; }
.piv-lab-group { display: flex; flex-direction: column; gap: 4px; }

/* Row number */
.piv-row-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: #f1f5f9; color: #64748b;
    font-size: 12px; font-weight: 800;
    display: inline-flex; align-items: center; justify-content: center;
}

/* ─── Empty state ─── */
.piv-empty {
    padding: 80px 20px; text-align: center;
}
.piv-empty-ic {
    width: 90px; height: 90px; border-radius: 24px;
    background: linear-gradient(135deg,#e0e7ff,#f5f3ff);
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; color: #a5b4fc;
    margin: 0 auto 20px;
}
.piv-empty h3 { font-size: 20px; font-weight: 800; color: #334155; margin: 0 0 8px; }
.piv-empty p  { font-size: 14px; color: #94a3b8; margin: 0; }

/* ─── Pagination ─── */
.piv-pagination {
    padding: 18px 22px; border-top: 1px solid #f0f4f8;
}
.piv-pagination .pagination > li > a,
.piv-pagination .pagination > li > span {
    border-radius: 8px !important; margin: 0 2px;
    border: 2px solid #e2e8f0; color: #475569;
    font-weight: 700; font-size: 13px;
    transition: all .2s;
}
.piv-pagination .pagination > li.active > a,
.piv-pagination .pagination > li.active > span {
    background: linear-gradient(135deg,#6366f1,#8b5cf6) !important;
    border-color: transparent !important; color: #fff !important;
    box-shadow: 0 3px 10px rgba(99,102,241,.35);
}
.piv-pagination .pagination > li > a:hover {
    background: #f8f9ff; border-color: #c7d2fe; color: #4338ca;
}
</style>
@stop

@section('content')

<div class="piv-body">

{{-- ════════════════════ HERO ════════════════════ --}}
<div class="piv-hero">
    <div class="piv-hero-dots"></div>
    <div class="piv-hero-inner">
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="piv-hero-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <h1>Pending Invoices</h1>
                <p>Track and manage all invoices awaiting delivery</p>
            </div>
        </div>
        <div class="piv-hero-badge">
            <i class="bi bi-files"></i>
            {{ $invoices->total() }} Invoices
        </div>
    </div>
</div>

{{-- ════════════════════ STATS STRIP ════════════════════ --}}
<div class="piv-stats-strip">
    <div class="piv-stat s-blue">
        <div class="piv-stat-ic"><i class="bi bi-receipt-cutoff"></i></div>
        <div>
            <div class="piv-stat-val">{{ $invoices->total() }}</div>
            <div class="piv-stat-lbl">Total</div>
        </div>
    </div>
    <div class="piv-stat s-amber">
        <div class="piv-stat-ic"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="piv-stat-val">{{ $invoices->where('status','pending')->count() }}</div>
            <div class="piv-stat-lbl">Pending</div>
        </div>
    </div>
    <div class="piv-stat s-purple">
        <div class="piv-stat-ic"><i class="bi bi-gear-fill"></i></div>
        <div>
            <div class="piv-stat-val">{{ $invoices->where('status','Under Process')->count() }}</div>
            <div class="piv-stat-lbl">Under Process</div>
        </div>
    </div>
    <div class="piv-stat s-green">
        <div class="piv-stat-ic"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="piv-stat-val">{{ $invoices->where('status','ready')->count() }}</div>
            <div class="piv-stat-lbl">Ready</div>
        </div>
    </div>
    <div class="piv-stat s-red">
        <div class="piv-stat-ic"><i class="bi bi-cash-stack"></i></div>
        <div>
            <div class="piv-stat-val">{{ number_format($invoices->sum('remaining'), 0) }}</div>
            <div class="piv-stat-lbl">Remaining (QAR)</div>
        </div>
    </div>
</div>

<div class="piv-gap"></div>

{{-- Flash --}}
@if(session('success'))
<div style="margin-bottom:14px;padding:13px 18px;background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;color:#166534;font-size:14px;font-weight:700;display:flex;align-items:center;gap:9px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="margin-bottom:14px;padding:13px 18px;background:#fef2f2;border:1.5px solid #fca5a5;border-radius:12px;color:#991b1b;font-size:14px;font-weight:700;display:flex;align-items:center;gap:9px;">
    <i class="bi bi-exclamation-circle-fill" style="font-size:18px;"></i> {{ session('error') }}
</div>
@endif

{{-- ════════════════════ FILTERS ════════════════════ --}}
<div class="piv-filter">
    <div class="piv-filter-head">
        <i class="bi bi-sliders"></i>
        <span>Filters</span>
    </div>
    <div class="piv-filter-body">
        <form action="{{ route('dashboard.invoice.pending') }}" method="GET">
            <div class="piv-filter-grid">
                <div class="piv-fld">
                    <label><i class="bi bi-upc-scan"></i> Invoice Code</label>
                    <input type="text" name="invoice_code" class="piv-input"
                           value="{{ request()->invoice_code }}" placeholder="e.g. INV-001">
                </div>
                <div class="piv-fld">
                    <label><i class="bi bi-person-badge"></i> Customer Code</label>
                    <input type="text" name="customer_code" class="piv-input"
                           value="{{ request()->customer_code }}" placeholder="e.g. C-001">
                </div>
                <div class="piv-fld">
                    <label><i class="bi bi-person"></i> Customer Name</label>
                    <input type="text" name="customer_name" class="piv-input"
                           value="{{ request()->customer_name }}" placeholder="Search name…">
                </div>
                <div class="piv-fld">
                    <label><i class="bi bi-calendar3"></i> Creation Date</label>
                    <input type="date" name="creation_date" class="piv-input"
                           value="{{ request()->creation_date }}">
                </div>
                <div class="piv-fld">
                    <label><i class="bi bi-bar-chart"></i> Status</label>
                    <select name="status" class="piv-input">
                        <option value="">All Status</option>
                        <option value="pending"       {{ request()->status=='pending'       ? 'selected':'' }}>Pending</option>
                        <option value="Under Process" {{ request()->status=='Under Process' ? 'selected':'' }}>Under Process</option>
                        <option value="ready"         {{ request()->status=='ready'         ? 'selected':'' }}>Ready</option>
                    </select>
                </div>
                @if($branches->count() > 1)
                <div class="piv-fld">
                    <label><i class="bi bi-building"></i> Branch</label>
                    <select name="branch_id" class="piv-input">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request()->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="piv-fld">
                    <label style="opacity:0;user-select:none;">.</label>
                    <label class="piv-has-remaining">
                        <input type="checkbox" name="remaining_balance" value="1"
                               {{ request()->remaining_balance=='1' ? 'checked':'' }}>
                        <i class="bi bi-cash-coin"></i> Has Remaining Balance
                    </label>
                </div>
            </div>
            <div class="piv-filter-actions">
                <a href="{{ route('dashboard.invoice.pending') }}" class="piv-btn clear">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
                <button type="submit" class="piv-btn primary">
                    <i class="bi bi-search"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════ TABLE ════════════════════ --}}
<div class="piv-table-card">
    <div class="piv-table-topbar">
        <h4>
            <i class="bi bi-table" style="color:#6366f1;"></i>
            Invoices List
            <span class="piv-results-badge">{{ $invoices->count() }} shown</span>
        </h4>
        <div style="font-size:12px;color:#94a3b8;">
            Page {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }}
        </div>
    </div>

    @if($invoices->count() > 0)
    <div style="overflow-x:auto;">
        <table class="piv-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice Code</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Doctor</th>
                    <th>Sales Person</th>
                    <th>Total (QAR)</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Pickup Date</th>
                    <th>Lab Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $index => $invoice)
                <tr>
                    <td>
                        <span class="piv-row-num">{{ $invoices->firstItem() + $index }}</span>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.invoice.show', $invoice->invoice_code) }}" class="piv-inv-code">
                            <i class="bi bi-file-earmark-text"></i>
                            {{ $invoice->invoice_code }}
                        </a>
                    </td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                        <i class="bi bi-calendar3" style="margin-right:3px;"></i>
                        {{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : '—' }}
                    </td>
                    <td>
                        <span class="piv-customer-name">{{ $invoice->customer->english_name ?? '—' }}</span>
                    </td>
                    <td>
                        @if($invoice->branch)
                            <span style="display:inline-flex;align-items:center;gap:5px;background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;">
                                <i class="bi bi-building"></i> {{ $invoice->branch->name }}
                            </span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#64748b;">
                        {{ $invoice->doctor->name ?? '—' }}
                    </td>
                    <td style="font-size:12px;color:#64748b;">
                        {{ $invoice->user->first_name ?? '—' }}
                    </td>
                    <td>
                        <span class="piv-total">{{ number_format($invoice->total, 2) }}</span>
                    </td>
                    <td>
                        @if($invoice->remaining > 0)
                            <span class="piv-remain">{{ number_format($invoice->remaining, 2) }}</span>
                        @else
                            <span class="piv-paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                        @endif
                    </td>
                    <td>
                        @if($invoice->status == 'pending')
                            <span class="piv-status pending">
                                <i class="bi bi-hourglass-split"></i> Pending
                            </span>
                        @elseif($invoice->status == 'Under Process')
                            <span class="piv-status under-process">
                                <i class="bi bi-gear-fill"></i> Under Process
                            </span>
                        @elseif($invoice->status == 'ready')
                            <span class="piv-status ready">
                                <i class="bi bi-check-circle-fill"></i> Ready
                            </span>
                        @elseif($invoice->status == 'delivered')
                            <span class="piv-status delivered">
                                <i class="bi bi-bag-check-fill"></i> Delivered
                            </span>
                        @else
                            <span class="piv-status">{{ ucfirst($invoice->status) }}</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                        @if($invoice->pickup_date)
                            <i class="bi bi-clock" style="margin-right:3px;"></i>{{ $invoice->pickup_date }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($invoice->has_lens_items || $invoice->has_cl_items)
                            <div class="piv-lab-group">
                                {{-- Lens Lab Order --}}
                                @if($invoice->has_lens_items)
                                    @if($invoice->has_active_po)
                                        <a href="{{ route('dashboard.lens-purchase-orders.index') }}?search={{ $invoice->invoice_code }}"
                                           class="piv-lab-active" title="View Lens Lab Order">
                                            <i class="fa fa-flask"></i> Lens Active
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard.lens-purchase-orders.create', $invoice->id) }}"
                                           class="piv-lab-new" title="Create Lens Lab Order">
                                            <i class="fa fa-flask"></i> Lens Order
                                        </a>
                                    @endif
                                @endif
                                {{-- Contact Lens Lab Order --}}
                                @if($invoice->has_cl_items)
                                    @if($invoice->has_active_cl_po)
                                        <a href="{{ route('dashboard.lens-purchase-orders.index') }}?search={{ $invoice->invoice_code }}"
                                           class="piv-cl-active" title="View CL Lab Order">
                                            <i class="bi bi-eye-fill"></i> CL Active
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard.lens-purchase-orders.cl.create', $invoice->id) }}"
                                           class="piv-cl-new" title="Create Contact Lens Lab Order">
                                            <i class="bi bi-eye"></i> CL Order
                                        </a>
                                    @endif
                                @endif
                            </div>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="piv-pagination">
        {{ $invoices->appends(request()->query())->links() }}
    </div>

    @else
    <div class="piv-empty">
        <div class="piv-empty-ic"><i class="bi bi-inbox"></i></div>
        <h3>No Invoices Found</h3>
        <p>Try adjusting your filters or check back later.</p>
    </div>
    @endif
</div>

</div>{{-- .piv-body --}}
@endsection
