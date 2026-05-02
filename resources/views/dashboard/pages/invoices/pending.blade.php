@extends('dashboard.layouts.master')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* ════════════════════════════════════════════════
   PENDING INVOICES — MODERN REDESIGN
════════════════════════════════════════════════ */
:root {
    --pi-blue:   #3b82f6;
    --pi-indigo: #6366f1;
    --pi-purple: #8b5cf6;
    --pi-green:  #22c55e;
    --pi-amber:  #f59e0b;
    --pi-red:    #ef4444;
    --pi-bg:     #f1f5f9;
    --pi-card:   #ffffff;
    --pi-border: #e2e8f0;
    --pi-txt:    #1e293b;
    --pi-muted:  #94a3b8;
}

body { background: var(--pi-bg) !important; }

/* ── Page wrapper ── */
.pi-wrap { padding: 0 20px 40px; }

/* ── Page header ── */
.pi-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 0 14px; flex-wrap: wrap; gap: 10px;
}
.pi-header-left { display: flex; align-items: center; gap: 12px; }
.pi-header-icon {
    width: 46px; height: 46px; border-radius: 13px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 21px;
    background: linear-gradient(135deg,#3b82f6,#6366f1); color: #fff;
    box-shadow: 0 4px 14px rgba(99,102,241,.3);
}
.pi-header h1 { margin: 0; font-size: 20px; font-weight: 800; color: var(--pi-txt); }
.pi-header small { font-size: 12px; color: var(--pi-muted); font-weight: 400; display: block; }
.pi-count-badge {
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    color: #fff; font-size: 13px; font-weight: 800;
    padding: 4px 14px; border-radius: 20px;
    box-shadow: 0 2px 8px rgba(99,102,241,.3);
}

/* ── Stat chips ── */
.pi-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
.pi-stat {
    background: #fff; border-radius: 12px; padding: 12px 18px;
    box-shadow: 0 1px 8px rgba(0,0,0,.06); display: flex; align-items: center; gap: 10px;
    flex: 1; min-width: 130px;
}
.pi-stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.pi-stat-icon.blue   { background: #eff6ff; color: #3b82f6; }
.pi-stat-icon.amber  { background: #fffbeb; color: #f59e0b; }
.pi-stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }
.pi-stat-val { font-size: 18px; font-weight: 800; color: var(--pi-txt); line-height: 1; }
.pi-stat-lbl { font-size: 11px; color: var(--pi-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }

/* ── Filter card ── */
.pi-filter-card {
    background: #fff; border-radius: 16px; padding: 18px 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07); margin-bottom: 16px;
}
.pi-filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
.pi-filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 150px; }
.pi-filter-group label {
    font-size: 11px; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: .4px;
    display: flex; align-items: center; gap: 5px;
}
.pi-filter-group label i { color: var(--pi-indigo); }
.pi-finput {
    padding: 9px 13px; border: 2px solid var(--pi-border); border-radius: 9px;
    font-size: 13px; color: var(--pi-txt); background: #fff; width: 100%;
    transition: border-color .2s, box-shadow .2s; box-sizing: border-box;
    font-family: sans-serif;
}
.pi-finput:focus { border-color: var(--pi-indigo); outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

/* ── Quick-filter pills ── */
.pi-pills { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 14px; }
.pi-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 30px; font-size: 12px; font-weight: 700;
    border: 2px solid var(--pi-border); background: #fff; color: var(--pi-muted);
    cursor: pointer; transition: all .2s;
}
.pi-pill:hover { border-color: var(--pi-indigo); color: var(--pi-indigo); }
.pi-pill.active { background: linear-gradient(135deg,var(--pi-blue),var(--pi-indigo)); border-color: transparent; color: #fff; box-shadow: 0 3px 12px rgba(99,102,241,.3); }
.pi-pill-count { background: rgba(255,255,255,.3); border-radius: 20px; padding: 1px 7px; font-size: 11px; }
.pi-pill.active .pi-pill-count { background: rgba(255,255,255,.25); }
.pi-pill-lab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 30px; font-size: 12px; font-weight: 700;
    border: 2px solid #e0f2fe; background: #f0f9ff; color: #0369a1;
    text-decoration: none; transition: all .2s;
}
.pi-pill-lab:hover { background: #0369a1; color: #fff; border-color: #0369a1; text-decoration: none; }

.pi-btn-balance {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 9px; border: 2px solid var(--pi-border);
    background: #fff; color: #475569; font-size: 12px; font-weight: 700;
    cursor: pointer; transition: all .2s; white-space: nowrap;
}
.pi-btn-balance:hover, .pi-btn-balance.active { background: var(--pi-amber); border-color: var(--pi-amber); color: #fff; }

/* ── Main table card ── */
.pi-table-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07); overflow: hidden; margin-bottom: 22px;
}
.pi-table-head-bar {
    padding: 16px 22px; border-bottom: 1px solid var(--pi-border);
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg,#f8f9ff,#fff);
}
.pi-table-head-bar h4 { margin: 0; font-size: 15px; font-weight: 800; color: var(--pi-txt); }
.pi-table-wrap { overflow-x: auto; }

.pi-table {
    width: 100%; border-collapse: collapse; min-width: 700px;
}
.pi-table thead th {
    background: #f8faff; padding: 11px 14px;
    font-size: 11px; font-weight: 800; color: #475569;
    text-transform: uppercase; letter-spacing: .5px;
    border-bottom: 2px solid var(--pi-border); white-space: nowrap; text-align: center;
}
.pi-table tbody tr {
    border-bottom: 1px solid #f0f4f8; cursor: pointer; transition: background .15s;
}
.pi-table tbody tr:hover { background: #f8f9ff; }
.pi-table tbody tr.pi-selected { background: linear-gradient(135deg,#eff0ff,#f5f3ff) !important; }
.pi-table tbody tr.pi-lens-row { border-left: 3px solid var(--pi-amber); }
.pi-table td { padding: 11px 14px; font-size: 13px; color: var(--pi-txt); text-align: center; vertical-align: middle; }

.pi-code-badge {
    display: inline-block; background: #eff6ff; color: #1d4ed8;
    font-size: 12px; font-weight: 800; padding: 3px 10px; border-radius: 6px; font-family: monospace;
}
.pi-customer-name { font-weight: 700; color: var(--pi-txt); }
.pi-customer-id   { font-size: 11px; color: var(--pi-muted); }
.pi-balance-chip {
    display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700;
}
.pi-balance-chip.zero    { background: #f0fdf4; color: #15803d; }
.pi-balance-chip.nonzero { background: #fef3c7; color: #92400e; }

.pi-date-cell { font-size: 12px; color: #475569; white-space: nowrap; }
.pi-pickup-cell { font-size: 12px; color: #475569; }

.pi-lab-active { display:inline-flex;align-items:center;gap:4px;color:#16a34a;font-size:12px;font-weight:700; }
.pi-lab-order  { display:inline-flex;align-items:center;gap:4px;color:#d97706;background:#fff7ed;border:1px solid #fed7aa;border-radius:6px;padding:3px 9px;font-size:11px;font-weight:700;text-decoration:none;transition:.2s; }
.pi-lab-order:hover { background:#f59e0b;color:#fff;border-color:#f59e0b;text-decoration:none; }
.pi-lab-none { color:#ddd;font-size:18px; }

.pi-action-btn {
    width: 32px; height: 32px; border-radius: 8px; border: none;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 14px; cursor: pointer; transition: all .2s;
}
.pi-action-btn.view  { background: #eff6ff; color: #3b82f6; }
.pi-action-btn.view:hover  { background: #3b82f6; color: #fff; }
.pi-action-btn.del   { background: #fef2f2; color: #ef4444; border: none; }
.pi-action-btn.del:hover   { background: #ef4444; color: #fff; }

/* ── Empty state ── */
.pi-empty { padding: 60px 20px; text-align: center; }
.pi-empty-icon { font-size: 52px; color: #c7d2fe; margin-bottom: 14px; }
.pi-empty h3 { font-size: 18px; font-weight: 800; color: #475569; margin: 0 0 6px; }
.pi-empty p  { color: var(--pi-muted); font-size: 13px; margin: 0; }

/* ════════════════════════════════════════════════
   INVOICE DETAILS PANEL
════════════════════════════════════════════════ */
#detailsBox {
    display: none;
    animation: fadeUp .3s ease;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

.pd-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden; margin-bottom: 18px;
}
.pd-card-head {
    padding: 16px 22px; border-bottom: 1px solid var(--pi-border);
    display: flex; align-items: center; gap: 14px;
    background: linear-gradient(135deg,#1e293b,#334155);
}
.pd-card-head h4 { margin:0; font-size:16px; font-weight:800; color:#fff; }
.pd-card-head .pd-inv-badge {
    background: linear-gradient(135deg,#3b82f6,#6366f1); color:#fff;
    font-size:12px; font-weight:800; padding:3px 12px; border-radius:20px; font-family:monospace;
}
.pd-card-body { padding: 22px; }

/* Items table */
.pd-table { width:100%; border-collapse:collapse; font-size:13px; }
.pd-table thead th {
    background: #f8faff; padding: 10px 12px;
    font-size: 11px; font-weight: 800; color: #475569;
    text-transform: uppercase; letter-spacing: .4px;
    border-bottom: 2px solid var(--pi-border); text-align: center;
}
.pd-table tbody tr { border-bottom: 1px solid #f0f4f8; transition: background .15s; }
.pd-table tbody tr:hover { background: #fafbff; }
.pd-table td { padding: 10px 12px; text-align: center; color: var(--pi-txt); }

/* Ready / Deliver buttons */
.pd-rdy-btn, .pd-dlv-btn {
    width: 32px; height: 28px; border-radius: 7px; border: none;
    font-size: 11px; font-weight: 800; cursor: pointer; transition: all .2s;
}
.pd-rdy-btn { background: #fffbeb; color: #92400e; border: 1.5px solid #fcd34d; }
.pd-rdy-btn:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
.pd-dlv-btn { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd; }
.pd-dlv-btn:hover { background: #3b82f6; color: #fff; border-color: #3b82f6; }

/* Payments table */
.pp-table { width:100%; border-collapse:collapse; font-size:13px; }
.pp-table thead th {
    background: #f8faff; padding: 9px 12px;
    font-size: 11px; font-weight: 800; color: #475569;
    text-transform: uppercase; letter-spacing: .4px;
    border-bottom: 2px solid var(--pi-border); text-align: center;
}
.pp-table tbody tr { border-bottom: 1px solid #f0f4f8; }
.pp-table td { padding: 9px 12px; text-align: center; color: var(--pi-txt); }

/* Financial summary chips */
.pd-fin-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(140px,1fr)); gap: 12px; margin: 16px 0; }
.pd-fin-chip {
    background: #f8faff; border: 1.5px solid var(--pi-border);
    border-radius: 12px; padding: 14px 16px; text-align: center;
}
.pd-fin-chip label { font-size: 11px; font-weight: 700; color: var(--pi-muted); text-transform: uppercase; letter-spacing: .4px; display: block; margin-bottom: 6px; }
.pd-fin-chip input {
    border: none; background: transparent; width: 100%;
    font-size: 18px; font-weight: 800; color: var(--pi-txt);
    text-align: center; outline: none; padding: 0;
}
.pd-fin-chip.green  { background: #f0fdf4; border-color: #86efac; }
.pd-fin-chip.green input { color: #15803d; }
.pd-fin-chip.amber  { background: #fffbeb; border-color: #fcd34d; }
.pd-fin-chip.amber input { color: #92400e; }
.pd-fin-chip.blue   { background: #eff6ff; border-color: #93c5fd; }
.pd-fin-chip.blue input { color: #1d4ed8; }
.pd-fin-chip.purple { background: #f5f3ff; border-color: #c4b5fd; }
.pd-fin-chip.purple input { color: #6d28d9; }
.pd-fin-chip.gray   { background: #f8fafc; border-color: var(--pi-border); }

/* Notes & Tray */
.pd-textarea {
    width: 100%; padding: 10px 14px; border: 2px solid var(--pi-border); border-radius: 10px;
    font-size: 13px; color: var(--pi-txt); resize: vertical; min-height: 90px;
    transition: border-color .2s; box-sizing: border-box;
}
.pd-textarea:focus { border-color: var(--pi-indigo); outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

/* Add Payment btn */
.pd-add-pay-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 9px; border: 2px solid var(--pi-border);
    background: #fff; color: #475569; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: all .2s;
}
.pd-add-pay-btn:hover { background: var(--pi-indigo); border-color: var(--pi-indigo); color: #fff; }
.pd-add-pay-btn:disabled { opacity: .45; cursor: not-allowed; }

/* Payment form section */
.pd-pay-form-wrap { background: #f8faff; border: 1.5px solid #e0e7ff; border-radius: 12px; padding: 18px; margin-top: 14px; }
.pd-pay-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.pd-fld label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .4px; display: block; margin-bottom: 5px; }
.pd-fld select, .pd-fld input[type="text"], .pd-fld input[type="date"] {
    width: 100%; padding: 9px 12px; border: 2px solid var(--pi-border); border-radius: 9px;
    font-size: 13px; color: var(--pi-txt); background: #fff; box-sizing: border-box;
    transition: border-color .2s; font-family: sans-serif;
}
.pd-fld select:focus, .pd-fld input:focus { border-color: var(--pi-indigo); outline: none; }

/* ATM extra table */
.pd-atm-table { width:100%; border-collapse:collapse; margin-top:10px; background:#fff; border-radius:10px; overflow:hidden; }
.pd-atm-table th { background:#f1f5f9; padding:8px 10px; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; border-bottom:1.5px solid var(--pi-border); }
.pd-atm-table td { padding:8px 10px; }
.pd-atm-table input, .pd-atm-table select {
    width:100%; padding:7px 10px; border:1.5px solid var(--pi-border); border-radius:7px; font-size:12px; box-sizing:border-box; font-family:sans-serif;
}

/* Action buttons bar */
.pd-actions-bar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.pd-post-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 26px; border-radius: 10px; border: none;
    background: linear-gradient(135deg,#22c55e,#16a34a); color: #fff;
    font-size: 14px; font-weight: 800; cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 14px rgba(34,197,94,.3);
}
.pd-post-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(34,197,94,.4); }

/* Print dropdown */
.pd-print-wrap { position:relative; display:inline-block; }
.pd-print-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 20px; border-radius: 10px; border: none;
    background: linear-gradient(135deg,#3b82f6,#6366f1); color: #fff;
    font-size: 14px; font-weight: 800; cursor: pointer; transition: all .2s;
    box-shadow: 0 4px 14px rgba(99,102,241,.3);
}
.pd-print-btn:hover { transform: translateY(-2px); }
.pd-print-drop {
    display: none; position:absolute; bottom:calc(100% + 6px); left:0;
    background:#fff; border:1.5px solid var(--pi-border); border-radius:10px;
    box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:140px; overflow:hidden; z-index:20;
}
.pd-print-wrap:hover .pd-print-drop { display:block; }
.pd-print-drop a {
    display:flex; align-items:center; gap:8px; padding:10px 16px;
    font-size:13px; font-weight:700; color:var(--pi-txt); text-decoration:none;
    transition:background .15s;
}
.pd-print-drop a:hover { background:#f8f9ff; color:var(--pi-indigo); }
</style>
@stop

@section('content')

<div class="pi-wrap">

{{-- ── Page header ── --}}
<div class="pi-header">
    <div class="pi-header-left">
        <div class="pi-header-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="pi-header">
            <div>
                <h1 style="margin:0;font-size:20px;font-weight:800;color:#1e293b;display:flex;align-items:center;gap:10px;">
                    Pending Invoices
                    <span class="pi-count-badge">{{ $invoices->count() }}</span>
                </h1>
                <small style="font-size:12px;color:#94a3b8;">Invoices awaiting delivery</small>
            </div>
        </div>
    </div>
</div>

{{-- ── Flash messages ── --}}
@if(session('success'))
<div style="margin-bottom:14px;padding:14px 18px;background:#f0fdf4;border:1px solid #86efac;border-radius:10px;color:#166534;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="margin-bottom:14px;padding:14px 18px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-exclamation-circle-fill" style="font-size:18px;"></i> {{ session('error') }}
</div>
@endif

{{-- ── Stats row ── --}}
@php
    $totalCount  = $invoices->count();
    $lensCount   = $invoices->filter(fn($i) => $i->has_lens_items)->count();
    $balanceCount = $invoices->filter(fn($i) => $i->remaining > 0)->count();
@endphp
<div class="pi-stats">
    <div class="pi-stat">
        <div class="pi-stat-icon blue"><i class="bi bi-receipt"></i></div>
        <div>
            <div class="pi-stat-val">{{ $totalCount }}</div>
            <div class="pi-stat-lbl">Total Pending</div>
        </div>
    </div>
    <div class="pi-stat">
        <div class="pi-stat-icon amber"><i class="bi bi-eyedropper"></i></div>
        <div>
            <div class="pi-stat-val">{{ $lensCount }}</div>
            <div class="pi-stat-lbl">Lens Orders</div>
        </div>
    </div>
    <div class="pi-stat">
        <div class="pi-stat-icon purple"><i class="bi bi-cash-coin"></i></div>
        <div>
            <div class="pi-stat-val">{{ $balanceCount }}</div>
            <div class="pi-stat-lbl">Has Balance</div>
        </div>
    </div>
</div>

{{-- ── Filter card ── --}}
<div class="pi-filter-card">
    <form action="{{ route('dashboard.pending-invoices') }}" method="GET">
        <div class="pi-filter-row">
            <div class="pi-filter-group">
                <label><i class="bi bi-hash"></i> Invoice Code</label>
                <input type="text" name="invoice_code" class="pi-finput remaining_balance" value="{{ request()->invoice_code }}" placeholder="Search code…">
            </div>
            <div class="pi-filter-group">
                <label><i class="bi bi-person-badge"></i> Customer Code</label>
                <input type="text" name="customer_code" class="pi-finput" value="{{ request()->customer_code }}" placeholder="Customer ID…">
            </div>
            <div class="pi-filter-group">
                <label><i class="bi bi-person"></i> Customer Name</label>
                <input type="text" name="customer_name" class="pi-finput" value="{{ request()->search }}" placeholder="Search name…">
            </div>
            <div class="pi-filter-group">
                <label><i class="bi bi-calendar3"></i> Creation Date</label>
                <input type="date" name="creation_date" class="pi-finput" value="{{ request()->creation_date }}">
            </div>
            <div>
                <button type="button" class="pi-btn-balance remaining_balance" id="btnBalance">
                    <i class="bi bi-funnel"></i> Has Balance
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ── Quick filter pills ── --}}
<div class="pi-pills">
    <button type="button" id="btnShowAll" class="pi-pill active" onclick="filterLensRows('all')">
        <i class="bi bi-list-ul"></i> All Invoices
        <span class="pi-pill-count" id="allCount">{{ $totalCount }}</span>
    </button>
    <button type="button" id="btnLensOnly" class="pi-pill" onclick="filterLensRows('lens')">
        <i class="bi bi-eyedropper"></i> Lens Orders Only
        <span class="pi-pill-count" id="lensCount">0</span>
    </button>
    @can('add-stock')
    <a href="{{ route('dashboard.lens-purchase-orders.index') }}" class="pi-pill-lab">
        <i class="bi bi-flask"></i> View All Lab Orders
    </a>
    @endcan
</div>

{{-- ── Main table card ── --}}
<div class="pi-table-card">
    <div class="pi-table-head-bar">
        <i class="bi bi-table" style="font-size:18px;color:#6366f1;"></i>
        <h4>Invoices List</h4>
    </div>
    <div class="pi-table-wrap">
        @if($invoices->count() > 0)
        <table class="pi-table pending-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th style="display:none">Cust. Code</th>
                    <th>Sales Rep</th>
                    <th>Balance</th>
                    <th>Pick-up Date</th>
                    <th>Lab Order</th>
                    <th>View</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr data-has-lens="{{ $invoice->has_lens_items ? '1' : '0' }}"
                    class="{{ $invoice->has_lens_items ? 'pi-lens-row' : '' }}">
                    <td><span class="pi-code-badge">{{ $invoice->invoice_code }}</span></td>
                    <td class="pi-date-cell">{{ date('Y-m-d', strtotime($invoice->created_at)) }}</td>
                    <td>
                        <div class="pi-customer-name">{{ $invoice->customer->english_name ?? '—' }}</div>
                    </td>
                    <td style="display:none">{{ $invoice->customer_id }}</td>
                    <td style="font-size:13px;color:#475569;">{{ $invoice->user_name }}</td>
                    <td>
                        <span class="pi-balance-chip {{ $invoice->remaining == 0 ? 'zero' : 'nonzero' }}">
                            {{ $invoice->remaining }}
                        </span>
                    </td>
                    <td class="pi-pickup-cell">{{ $invoice->pickup_date ?? '—' }}</td>
                    <td>
                        @if($invoice->has_lens_items)
                            @if($invoice->has_active_po)
                                <a href="{{ route('dashboard.lens-purchase-orders.index') }}?search={{ $invoice->invoice_code }}"
                                   class="pi-lab-active" title="View Lab Order">
                                    <i class="bi bi-flask-fill"></i> Active
                                </a>
                            @else
                                <a href="{{ route('dashboard.lens-purchase-orders.create', $invoice->id) }}"
                                   class="pi-lab-order" title="Create Lab Order">
                                    <i class="bi bi-flask"></i> Order
                                </a>
                            @endif
                        @else
                            <span class="pi-lab-none">—</span>
                        @endif
                    </td>
                    <td>
                        <button class="pi-action-btn view" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                    <td>
                        @can('delete-invoices')
                        <form action="{{ route('dashboard.delete-invoice', $invoice->invoice_code) }}" method="GET" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pi-action-btn del" title="Delete Invoice"
                                onclick="return confirm('Delete invoice {{ $invoice->invoice_code }}?')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="pi-empty">
            <div class="pi-empty-icon"><i class="bi bi-inbox"></i></div>
            <h3>No Pending Invoices</h3>
            <p>All invoices have been delivered or no results match your filters.</p>
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════
     INVOICE DETAILS PANEL
════════════════════════════════════════ --}}
<div id="detailsBox">

    {{-- Items card --}}
    <div class="pd-card">
        <div class="pd-card-head">
            <i class="bi bi-card-list" style="font-size:20px;color:#93c5fd;"></i>
            <h4>Invoice Details</h4>
            <span class="pd-inv-badge invoice-badge">—</span>
        </div>
        <div class="pd-card-body">
            <div style="overflow-x:auto;">
                <table class="pd-table invoice-details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item #</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Net</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Stock</th>
                            <th>Ready</th>
                            <th>Deliver</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Payments card --}}
    <div class="pd-card">
        <div class="pd-card-head">
            <i class="bi bi-credit-card-2-front" style="font-size:20px;color:#86efac;"></i>
            <h4>Payments</h4>
            <button class="pd-add-pay-btn add-payment" style="margin-left:auto;" title="Add Payment">
                <i class="bi bi-plus-circle"></i> Add Payment
            </button>
        </div>
        <div class="pd-card-body">
            <div style="overflow-x:auto;">
                <table class="pp-table payments-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Type</th><th>Bank</th><th>Card No</th>
                            <th>Expiry</th><th>Paid</th><th>Beneficiary</th><th>Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="payments" style="margin-top:12px;"></div>
        </div>
    </div>

    {{-- Financial summary --}}
    <div class="pd-card">
        <div class="pd-card-head">
            <i class="bi bi-bar-chart-line" style="font-size:20px;color:#c4b5fd;"></i>
            <h4>Financial Summary</h4>
        </div>
        <div class="pd-card-body">
            <div class="pd-fin-grid">
                <div class="pd-fin-chip green">
                    <label>Paid</label>
                    <input type="text" id="paied" readonly>
                </div>
                <div class="pd-fin-chip amber">
                    <label>Remaining</label>
                    <input type="text" id="remaining" readonly>
                </div>
                <div class="pd-fin-chip blue">
                    <label>Discount</label>
                    <input type="text" id="discount" readonly>
                </div>
                <div class="pd-fin-chip gray">
                    <label>Total Before Disc.</label>
                    <input type="text" id="totalBefore" readonly>
                </div>
                <div class="pd-fin-chip purple">
                    <label>Total After Disc.</label>
                    <input type="text" id="totalAfter" readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes & Actions card --}}
    <div class="pd-card">
        <div class="pd-card-head">
            <i class="bi bi-pencil-square" style="font-size:20px;color:#fcd34d;"></i>
            <h4>Notes &amp; Actions</h4>
        </div>
        <div class="pd-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">Notes</label>
                    <textarea class="pd-textarea invoice_notes" rows="3" placeholder="Add notes…"></textarea>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.4px;display:block;margin-bottom:6px;">Tray Ref #</label>
                    <textarea class="pd-textarea invoice_trayn" rows="3" placeholder="Tray reference…"></textarea>
                </div>
            </div>
            <div class="pd-actions-bar">
                <button type="button" class="pd-post-btn" id="postDeliver">
                    <i class="bi bi-check2-circle"></i> Post &amp; Deliver
                </button>
                <div class="pd-print-wrap">
                    <button type="button" class="pd-print-btn">
                        <i class="bi bi-printer"></i> Print <i class="bi bi-chevron-up" style="font-size:11px;"></i>
                    </button>
                    <div class="pd-print-drop">
                        <a href="" id="printInvoiceAr" target="_blank"><i class="bi bi-file-earmark-text"></i> Arabic</a>
                        <a href="" id="printInvoiceEn" target="_blank"><i class="bi bi-file-earmark-text"></i> English</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end #detailsBox --}}

</div>{{-- end .pi-wrap --}}

<script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/jspdf.min.js') }}" type="text/javascript"></script>

<script>
/* ══════════════════════════════════════
   FILTERS
══════════════════════════════════════ */
var pendingRows   = Array.from(document.querySelectorAll('.pending-table tbody tr'));
var lensRowsArr   = pendingRows.filter(function(r){ return r.getAttribute('data-has-lens')==='1'; });
document.getElementById('lensCount').textContent  = lensRowsArr.length;
document.getElementById('allCount').textContent   = pendingRows.length;

function filterLensRows(mode) {
    document.getElementById('btnShowAll').classList.toggle('active', mode==='all');
    document.getElementById('btnLensOnly').classList.toggle('active', mode==='lens');
    pendingRows.forEach(function(r){
        r.style.display = (mode==='all' || r.getAttribute('data-has-lens')==='1') ? '' : 'none';
    });
}

function updatePendingTable(searchValue, subject) {
    var matched = pendingRows.filter(function(row){
        if(subject==='invoice_code')
            return row.querySelector('td:nth-child(1)').innerText.match(searchValue);
        if(subject==='customer_code')
            return row.querySelector('td:nth-child(4)').innerText.match(searchValue);
        if(subject==='remaining_balance'){
            var btn = document.getElementById('btnBalance');
            if(btn && btn.classList.contains('active'))
                return row.querySelector('td:nth-child(6)').innerText.trim() !== '0';
            return true;
        }
        if(subject==='customer_name')
            return row.querySelector('td:nth-child(3)').innerText.match(searchValue);
        if(subject==='creation_date')
            return row.querySelector('td:nth-child(2)').innerText.match(searchValue);
    });
    pendingRows.forEach(function(r){ r.style.display = matched.includes(r) ? '' : 'none'; });
}

/* Live search inputs */
document.querySelector('input[name=invoice_code]').addEventListener('keyup', function(e){
    updatePendingTable(e.target.value, 'invoice_code');
});
document.querySelector('input[name=customer_code]').addEventListener('keyup', function(e){
    updatePendingTable(e.target.value, 'customer_code');
});
document.querySelector('input[name=customer_name]').addEventListener('keyup', function(e){
    updatePendingTable(e.target.value, 'customer_name');
});
document.querySelector('input[name=creation_date]').addEventListener('change', function(e){
    updatePendingTable(e.target.value, 'creation_date');
});

/* Balance filter button */
var btnBalance = document.getElementById('btnBalance');
btnBalance.addEventListener('click', function(){
    this.classList.toggle('active');
    updatePendingTable(null, 'remaining_balance');
});

/* ══════════════════════════════════════
   ROW CLICK → LOAD INVOICE DETAILS
══════════════════════════════════════ */
var InvoicesTable       = document.querySelector('.pending-table');
var InvoiceDetailsTable = document.querySelector('.invoice-details tbody');
var InvoiceBadge        = document.querySelector('.invoice-badge');
var InvoiceDetailsBox   = document.getElementById('detailsBox');
var paymentsTable       = document.querySelector('.payments-table tbody');
var paymentsContainer   = document.querySelector('.payments');
var InvoiceID;

InvoicesTable.addEventListener('click', function(e){
    var btn = e.target.closest('.pi-action-btn.view');
    if(!btn) return;

    var row = btn.closest('tr');
    InvoiceID = row.querySelector('td:nth-child(1)').innerText.trim();

    /* highlight row */
    pendingRows.forEach(function(r){ r.classList.remove('pi-selected'); });
    row.classList.add('pi-selected');
    paymentsContainer.innerHTML = '';
    document.querySelector('.add-payment').removeAttribute('disabled');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        type: 'GET',
        url: '{{ route("dashboard.get-invoice-details") }}',
        data: { InvoiceID },
        success: function(response){
            InvoiceDetailsTable.innerHTML = '';
            paymentsTable.innerHTML = '';
            InvoiceDetailsBox.style.display = 'block';

            /* scroll to details */
            InvoiceDetailsBox.scrollIntoView({ behavior:'smooth', block:'start' });

            InvoiceBadge.innerText = response.invoice.invoice_code;

            document.getElementById('printInvoiceEn').href = '{{ url("dashboard/print-pending-invoice/en")."/" }}' + response.invoice.invoice_code;
            document.getElementById('printInvoiceAr').href = '{{ url("dashboard/print-pending-invoice/ar")."/" }}' + response.invoice.invoice_code;

            var total_before_discount = 0;
            if(response.invoice.discount_type==='fixed')
                total_before_discount = (+response.invoice.discount_value) + (+response.invoice.total);
            else
                total_before_discount = (+response.invoice.total) / (1 - (+response.invoice.discount_value)/100);

            document.getElementById('totalAfter').value  = (+response.invoice.total).toFixed(2);
            document.getElementById('paied').value        = (+response.invoice.paied).toFixed(2);
            document.getElementById('remaining').value    = (+response.invoice.remaining).toFixed(2);
            document.getElementById('discount').value     = (+response.invoice.discount_value).toFixed(2);
            document.getElementById('totalBefore').value  = total_before_discount.toFixed(2);

            response.Invoice_items.forEach(function(r, index){
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>'+(index+1)+'</td>'+
                    '<td>'+r.product_id+'</td>'+
                    '<td style="text-align:left;">'+r.name+'</td>'+
                    '<td>'+r.quantity+'</td>'+
                    '<td>'+r.price+'</td>'+
                    '<td>'+r.net+'</td>'+
                    '<td>'+r.tax+'</td>'+
                    '<td style="font-weight:700;">'+r.total+'</td>'+
                    '<td>'+r.stock+'</td>'+
                    '<td><button class="pd-rdy-btn" onclick="itemReady('+r.id+')">R</button></td>'+
                    '<td><button class="pd-dlv-btn" onclick="itemDeliver('+r.id+')">D</button></td>';
                InvoiceDetailsTable.appendChild(tr);
            });

            response.payments.forEach(function(pay, index){
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>'+(index+1)+'</td>'+
                    '<td>'+pay.type+'</td>'+
                    '<td>'+(pay.bank||'—')+'</td>'+
                    '<td>'+(pay.card_number||'—')+'</td>'+
                    '<td>'+((pay.expiration_date && pay.expiration_date!=='0000-00-00') ? pay.expiration_date : '—')+'</td>'+
                    '<td style="font-weight:700;">'+pay.payed_amount+'</td>'+
                    '<td>'+(pay.get_benficiary ? pay.get_benficiary.first_name+' '+pay.get_benficiary.last_name : '—')+'</td>'+
                    '<td>'+pay.created_at+'</td>';
                paymentsTable.appendChild(tr);
            });
        }
    });
});

/* ══════════════════════════════════════
   POST DELIVER
══════════════════════════════════════ */
document.getElementById('postDeliver').addEventListener('click', function(e){
    e.preventDefault();
    if(!InvoiceID){ alert('Select an invoice first.'); return; }
    Swal.fire({
        title: 'Post & Deliver?',
        text: 'This will mark the invoice as delivered.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="bi bi-check2-circle"></i> Deliver',
    }).then(function(r){
        if(!r.isConfirmed) return;
        var invoice_notes = document.querySelector('.invoice_notes').value;
        var invoice_trayn = document.querySelector('.invoice_trayn').value;
        $.ajax({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            type: 'POST',
            url: '{{ route("dashboard.post-deliver-invoice") }}',
            data: { InvoiceID, invoice_notes, invoice_trayn },
            success: function(){ location.reload(); }
        });
    });
});

/* ══════════════════════════════════════
   ITEM READY / DELIVER
══════════════════════════════════════ */
function itemReady(id){
    $.ajax({
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        type:'POST',
        url:'{{ url("dashboard/change-status-ready")."/" }}'+id,
        data:{id,status:'ready'},
        success:function(){
            new Noty({text:'Item marked as Ready',killer:true,type:'success'}).show();
        }
    });
}
function itemDeliver(id){
    $.ajax({
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        type:'POST',
        url:'{{ url("dashboard/change-status-deliver")."/" }}'+id,
        data:{id,status:'deliver'},
        success:function(){
            new Noty({text:'Item marked as Delivered',killer:true,type:'success'}).show();
        }
    });
}

/* ══════════════════════════════════════
   ADD PAYMENT
══════════════════════════════════════ */
var addPaymentBtn  = document.querySelector('.add-payment');
var updatePaymentBtn;

addPaymentBtn.addEventListener('click', function(e){
    e.preventDefault();
    var onePay = document.createElement('div');
    onePay.className = 'pd-pay-form-wrap';
    onePay.innerHTML = `
        <div class="pd-pay-form-row">
            <div class="pd-fld">
                <label>Payment Type</label>
                <select id="payment_type" class="payment_type">
                    <option value="Cash">Cash</option>
                    <option value="Atm">ATM</option>
                    <option value="visa">VISA</option>
                    <option value="Master Card">Master Card</option>
                    <option value="Gift Voudire">Gift Voucher</option>
                </select>
            </div>
            <div class="pd-fld">
                <label>Paid Amount</label>
                <input type="text" id="paied_amt" placeholder="0.00">
            </div>
        </div>
        <div id="atm-extra" style="display:none;margin-top:10px;">
            <table class="pd-atm-table">
                <thead><tr><th>Bank</th><th>Card No</th><th>Expiry Date</th><th>Currency</th><th>Rate</th><th>Local Payment</th></tr></thead>
                <tbody><tr>
                    <td><input type="text" id="Bank" placeholder="Bank name"></td>
                    <td><input type="text" id="Card_No" placeholder="Card number"></td>
                    <td><input type="date" id="expiration_date"></td>
                    <td><select id="currency"><option value="QAR">QAR</option></select></td>
                    <td>1</td>
                    <td><input type="text" id="local_payment" readonly></td>
                </tr></tbody>
            </table>
        </div>
        <div style="margin-top:14px;text-align:right;">
            <button class="pd-post-btn update_payment" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);box-shadow:0 4px 14px rgba(99,102,241,.3);">
                <i class="bi bi-check-circle"></i> Save Payment
            </button>
        </div>
    `;
    paymentsContainer.appendChild(onePay);
    addPaymentBtn.setAttribute('disabled', true);

    document.getElementById('payment_type').addEventListener('change', function(){
        document.getElementById('atm-extra').style.display = (this.value !== 'Cash') ? 'block' : 'none';
    });

    updatePaymentBtn = document.querySelector('.update_payment');
    updatePaymentBtn.addEventListener('click', function(e){
        e.preventDefault();
        var payment = {
            type:            document.querySelector('.payment_type').value,
            bank:            (document.getElementById('Bank')  || {value:''}).value,
            card_number:     (document.getElementById('Card_No') || {value:''}).value,
            expiration_date: (document.getElementById('expiration_date') || {value:''}).value,
            payed_amount:    document.getElementById('paied_amt').value,
        };
        $.ajax({
            type: 'POST',
            url:  '{{ route("dashboard.add-payment-to-pending-invoice") }}',
            data: { InvoiceID, payment, '_token':'{{ csrf_token() }}' },
            success: function(response){
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td></td>'+
                    '<td>'+response.type+'</td>'+
                    '<td>'+(response.bank||'—')+'</td>'+
                    '<td>'+(response.card_number||'—')+'</td>'+
                    '<td>'+((response.expiration_date && response.expiration_date!=='0000-00-00') ? response.expiration_date : '—')+'</td>'+
                    '<td style="font-weight:700;">'+response.payed_amount+'</td>'+
                    '<td>'+(response.get_benficiary ? response.get_benficiary.first_name+' '+response.get_benficiary.last_name : '—')+'</td>'+
                    '<td>'+response.created_at+'</td>';
                paymentsTable.appendChild(tr);
                onePay.remove();
                addPaymentBtn.removeAttribute('disabled');
                Swal.fire({icon:'success',title:'Payment Added',timer:1800,showConfirmButton:false});
            },
            error: function(){
                Swal.fire({icon:'error',title:'Error adding payment'});
            }
        });
    });
});
</script>
@stop
