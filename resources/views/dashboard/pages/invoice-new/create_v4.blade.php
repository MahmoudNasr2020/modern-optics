@extends('dashboard.layouts.master')

@section('title', 'Create Invoice')

@section('styles')
<style>
/* ╔══════════════════════════════════════════════════════════════╗
   ║   INVOICE DESIGN 4 — SPLIT-SCREEN COMMAND                   ║
   ╚══════════════════════════════════════════════════════════════╝ */

/* ── Reset / base ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Page root ── */
.iv4-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
    background: #f8fafc;
}

/* ══════════════════════════════════════
   TOP CHROME BAR
══════════════════════════════════════ */
.iv4-chrome {
    background: #fff;
    padding: 0 20px;
    height: 52px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
    z-index: 300;
    border-bottom: 2px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
}
.iv4-chrome-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #0f172a;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: -.3px;
    white-space: nowrap;
}
.iv4-chrome-brand i { color: #3b82f6; font-size: 18px; }
.iv4-chrome-badge {
    background: #eff6ff;
    color: #2563eb;
    font-size: 10px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 10px;
    border: 1px solid #bfdbfe;
    letter-spacing: .5px;
}
.iv4-chrome-sep {
    width: 1px;
    height: 24px;
    background: #e2e8f0;
}
.iv4-chrome-customer {
    display: flex;
    align-items: center;
    gap: 7px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 7px;
    padding: 5px 12px;
    color: #1e40af;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}
.iv4-chrome-customer i { color: #3b82f6; }
.iv4-chrome-right {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
}
.iv4-chrome-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    padding: 5px 12px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all .15s;
}
.iv4-chrome-link:hover { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; text-decoration: none; }

/* ══════════════════════════════════════
   SPLIT BODY
══════════════════════════════════════ */
.iv4-split {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* ══════════════════════════════════════
   LEFT PANEL
══════════════════════════════════════ */
.iv4-left {
    width: 280px;
    flex-shrink: 0;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
    border-right: 2px solid #e2e8f0;
}
.iv4-left::-webkit-scrollbar { width: 4px; }
.iv4-left::-webkit-scrollbar-track { background: #f1f5f9; }
.iv4-left::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }

/* Left panel logo strip */
.iv4-panel-logo {
    padding: 18px 16px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
}
.iv4-panel-logo-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #fff;
    flex-shrink: 0;
}
.iv4-panel-logo-text {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.iv4-panel-logo-sub {
    font-size: 10px;
    color: #64748b;
    font-weight: 500;
}

/* Left panel sections */
.iv4-lp-section {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
}
.iv4-lp-label {
    font-size: 9px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 6px;
    display: block;
}

/* Customer chip */
.iv4-customer-chip {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.iv4-customer-chip-avatar {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff;
    flex-shrink: 0;
}
.iv4-customer-chip-id {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}
.iv4-customer-chip-name {
    font-size: 13px;
    color: #0f172a;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Left panel input */
.iv4-lp-input {
    width: 100%;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 7px;
    padding: 8px 10px;
    font-size: 13px;
    color: #0f172a;
    transition: border-color .15s;
}
.iv4-lp-input:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 2px rgba(59,130,246,.15);
}
.iv4-lp-input[readonly] {
    background: #f8fafc;
    color: #64748b;
    cursor: default;
}
select.iv4-lp-input { cursor: pointer; }
select.iv4-lp-input option { background: #fff; color: #0f172a; }

/* Doctor row */
.iv4-doctor-row {
    display: flex;
    gap: 6px;
    align-items: flex-end;
}
.iv4-doctor-row .iv4-lp-input { flex: 1; min-width: 0; }
.iv4-lp-btn-icon {
    width: 34px; height: 34px;
    border-radius: 7px;
    background: #3b82f6;
    border: none;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .15s;
}
.iv4-lp-btn-icon:hover { background: #2563eb; }

/* Spacer */
.iv4-lp-spacer { flex: 1; }

/* Mini totals card */
.iv4-mini-totals {
    padding: 14px 16px;
    background: #eff6ff;
    border-top: 2px solid #bfdbfe;
}
.iv4-mini-totals-title {
    font-size: 9px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 10px;
}
.iv4-mini-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    font-size: 12px;
    color: #475569;
}
.iv4-mini-row:last-child {
    padding-top: 10px;
    border-top: 1px solid #bfdbfe;
    margin-top: 4px;
}
.iv4-mini-row .lbl { font-weight: 600; }
.iv4-mini-row .val { font-weight: 800; color: #0f172a; }
.iv4-mini-grand {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    border-radius: 8px;
    padding: 10px 12px;
    margin-top: 8px;
}
.iv4-mini-grand .lbl { font-size: 10px; font-weight: 800; color: rgba(255,255,255,.7); text-transform: uppercase; letter-spacing: .5px; }
.iv4-mini-grand .val { font-size: 20px; font-weight: 900; color: #fff; font-variant-numeric: tabular-nums; }

/* ══════════════════════════════════════
   RIGHT PANEL
══════════════════════════════════════ */
.iv4-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #f8fafc;
}

/* ── Tab bar ── */
.iv4-tabbar {
    background: #fff;
    border-bottom: 2px solid #e2e8f0;
    display: flex;
    align-items: stretch;
    flex-shrink: 0;
    padding: 0 20px;
    gap: 2px;
}
.iv4-tab-btn {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 0 18px;
    height: 46px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    background: transparent;
    border: none;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    transition: all .15s;
    white-space: nowrap;
}
.iv4-tab-btn:hover { color: #3b82f6; background: #f0f9ff; }
.iv4-tab-btn.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background: #eff6ff;
}
.iv4-tab-btn .iv4-tab-icon { font-size: 15px; }

/* ── Right scrollable content ── */
.iv4-right-content {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.iv4-right-content::-webkit-scrollbar { width: 5px; }
.iv4-right-content::-webkit-scrollbar-track { background: #f1f5f9; }
.iv4-right-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

/* ── Tab panels ── */
.iv4-tab-panel { display: none; }

/* ── Cards in right panel ── */
.iv4-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.iv4-card-head {
    padding: 12px 16px;
    border-bottom: 1.5px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fafcff;
}
.iv4-card-head-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.iv4-card-head-icon.blue   { background: #eff6ff; color: #3b82f6; }
.iv4-card-head-icon.green  { background: #f0fdf4; color: #22c55e; }
.iv4-card-head-icon.amber  { background: #fffbeb; color: #f59e0b; }
.iv4-card-head-icon.red    { background: #fef2f2; color: #ef4444; }
.iv4-card-head-icon.teal   { background: #f0fdfa; color: #14b8a6; }
.iv4-card-head-icon.indigo { background: #eef2ff; color: #6366f1; }
.iv4-card-head-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.iv4-card-head-sub {
    font-size: 11px;
    color: #94a3b8;
    margin: 1px 0 0;
}
.iv4-card-head-actions { margin-left: auto; display: flex; gap: 6px; align-items: center; }
.iv4-card-body { padding: 16px; }

/* ── Labels & inputs (right panel) ── */
.iv4-label {
    font-size: 10px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
    display: block;
}
.iv4-input {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 7px;
    padding: 7px 11px;
    font-size: 13px;
    color: #0f172a;
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
}
.iv4-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    outline: none;
}
select.iv4-input { cursor: pointer; }

/* ── Buttons (right panel) ── */
.iv4-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
    white-space: nowrap;
}
.iv4-btn:hover { opacity: .88; text-decoration: none; }
.iv4-btn.blue   { background: #3b82f6; color: #fff; }
.iv4-btn.green  { background: #22c55e; color: #fff; }
.iv4-btn.red    { background: #ef4444; color: #fff; }
.iv4-btn.amber  { background: #f59e0b; color: #fff; }
.iv4-btn.outline {
    background: transparent;
    color: #64748b;
    border: 1.5px solid #e2e8f0;
}
.iv4-btn.outline:hover { background: #f8fafc; color: #334155; }

/* ── Product barcode row ── */
.iv4-barcode-wrap { position: relative; }
.iv4-barcode-wrap .iv4-bc-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 15px;
    pointer-events: none;
}
.iv4-barcode-wrap .iv4-input { padding-left: 34px; }

/* ── Camera button ── */
.iv4-cam-btn {
    height: 35px;
    padding: 0 12px;
    border-radius: 7px;
    background: #6366f1;
    border: none;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background .15s;
}
.iv4-cam-btn:hover { background: #4f46e5; }

/* ── Step label ── */
.iv4-step-label {
    font-size: 12px;
    font-weight: 800;
    color: #3b82f6;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ── Filter grid ── */
.iv4-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}
@media (max-width: 900px) { .iv4-filter-grid { grid-template-columns: repeat(2,1fr); } }

/* ── Divider ── */
.iv4-divider { border: none; border-top: 1.5px solid #f1f5f9; margin: 14px 0; }

/* ── Discount group ── */
.iv4-discount-group {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 12px;
}
.iv4-discount-group h6 {
    font-size: 10px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0 0 10px;
}

/* ── Payment row ── */
.iv4-payment-row {
    display: flex;
    gap: 8px;
    align-items: center;
    padding: 9px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
}

/* ── Horizontal rule separator between tabs and items table ── */
.iv4-items-rule {
    border: none;
    border-top: 2px solid #e2e8f0;
    margin: 0;
    flex-shrink: 0;
}

/* ── Items section ── */
.iv4-items-section {
    flex-shrink: 0;
    background: #fff;
    border-top: 2px solid #e2e8f0;
}
.iv4-items-head {
    padding: 10px 16px;
    border-bottom: 1.5px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #1e1b4b;
}
.iv4-items-head-title {
    font-size: 13px;
    font-weight: 800;
    color: #f1f5f9;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 7px;
}
.iv4-items-head-title i { color: #3b82f6; }
.iv4-items-count-badge {
    background: #1e3a5f;
    color: #60a5fa;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 12px;
    border: 1px solid #2563eb;
}

/* ── Bottom sticky bar ── */
.iv4-bottom-bar {
    background: #fff;
    border-top: 2px solid #e2e8f0;
    box-shadow: 0 -2px 8px rgba(0,0,0,.06);
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
    z-index: 200;
}
.iv4-bottom-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.iv4-bottom-stat {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.iv4-bottom-stat .lbl { font-size: 9px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.iv4-bottom-stat .val { font-size: 16px; font-weight: 900; color: #0f172a; font-variant-numeric: tabular-nums; }
.iv4-bottom-sep { width: 1px; height: 32px; background: #e2e8f0; }
.iv4-bottom-actions { margin-left: auto; display: flex; gap: 8px; align-items: center; }

/* Bottom bar buttons */
.iv4-bar-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
}
.iv4-bar-btn:hover { opacity: .88; transform: translateY(-1px); }
.iv4-bar-btn.reset {
    background: #f1f5f9;
    border: 1.5px solid #e2e8f0;
    color: #475569;
}
.iv4-bar-btn.reset:hover { background: #e2e8f0; color: #0f172a; }
.iv4-bar-btn.draft {
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
    color: #1e40af;
}
.iv4-bar-btn.draft:hover { background: #dbeafe; color: #1e3a8a; }
.iv4-bar-btn.submit {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    padding: 8px 28px;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(34,197,94,.35);
}

/* ── Loading overlay ── */
.loading-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.loading-overlay.active { display: flex; }
.loading-spinner {
    width: 52px; height: 52px;
    border: 5px solid rgba(255,255,255,.2);
    border-top-color: #fff;
    border-radius: 50%;
    animation: iv4Spin .8s linear infinite;
}
@keyframes iv4Spin { to { transform: rotate(360deg); } }

/* ── Lenses / Discount collapse (JS toggled) ── */
.lenses-collapsed { max-height: 0; overflow: hidden; padding: 0 !important; transition: max-height .3s ease-out; }
/* overflow:hidden keeps the section in the scroll-height calculation of iv4-right-content.
   JS removes max-height after transition so the Add button is never clipped. */
.lenses-expanded  { max-height: 9999px !important; overflow: hidden !important; padding: 16px !important; padding-bottom: 32px !important; transition: max-height .5s ease-in; }

/* ── Form flex fix: makes right-content scroll properly ── */
.iv4-right > form {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 0;
}

/* ── Horizontal scroll for lens/item containers ── */
#lensesTableContainer, #itemsTableContainer {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* ── Items table overrides (JS-rendered table) ── */
.items-table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 650px; }
.items-table thead { background: #1e1b4b; }
.items-table thead th { color: #fff; padding: 9px 10px; font-weight: 700; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; text-align: center; border: none; }
.items-table tbody td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; text-align: center; color: #334155; }
.items-table tbody tr:hover { background: #f8fafc; }
.items-table tfoot { background: #1e1b4b; }
.items-table tfoot td { color: #fff; padding: 10px; font-weight: 800; font-size: 13px; border: none; }
.btn-danger-custom { background: #ef4444; border: none; padding: 5px 12px; border-radius: 6px; font-weight: 700; color: #fff; font-size: 11px; cursor: pointer; }
.btn-danger-custom:hover { background: #dc2626; color: #fff; }
.empty-state { text-align: center; padding: 36px 20px; color: #94a3b8; }
.empty-state i { font-size: 44px; display: block; margin-bottom: 10px; }

/* ── Camera scan line ── */
@keyframes iv4ScanLine { 0%,100% { top: 20%; } 50% { top: 80%; } }

/* ── Lens eye selectors ── */
.lens-select-left, .lens-select-right {
    appearance: none; -webkit-appearance: none;
    width: 20px; height: 20px;
    border: 2px solid #3b82f6; border-radius: 5px;
    cursor: pointer; position: relative; display: inline-block;
    transition: all .15s; vertical-align: middle; background: #fff;
}
.lens-select-left:checked, .lens-select-right:checked {
    background: #3b82f6; border-color: transparent;
}
.lens-select-left:disabled, .lens-select-right:disabled { opacity: .4; cursor: not-allowed; }

/* d-none helper */
.d-none { display: none !important; }

/* Approval amount group */
#approvalAmountGroup.d-none { display: none !important; }
</style>
@endsection

@section('content')
<div class="iv4-page">

    {{-- ══ CHROME TOP BAR ══ --}}
    <div class="iv4-chrome">
        <div class="iv4-chrome-brand">
            <i class="bi bi-receipt-cutoff"></i>
            Invoice Studio
        </div>
        <span class="iv4-chrome-badge">DESIGN 4</span>
        <div class="iv4-chrome-sep"></div>
        <div class="iv4-chrome-customer">
            <i class="bi bi-person-fill"></i>
            {{ $customer->customer_id }} &mdash; {{ $customer->english_name }}
        </div>
        <div class="iv4-chrome-right">
            @can('edit-settings')
            <a href="{{ route('dashboard.settings.index') }}" class="iv4-chrome-link">
                <i class="bi bi-palette"></i> Switch Design
            </a>
            @endcan
            <a href="{{ route('dashboard.get-all-customers') }}" class="iv4-chrome-link">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ══ MAIN SPLIT ══ --}}
    <div class="iv4-split">

        {{-- ════════════════════════════════════
             LEFT PANEL (280px fixed, dark)
        ════════════════════════════════════ --}}
        <aside class="iv4-left">

            {{-- Logo / title strip --}}
            <div class="iv4-panel-logo">
                <div class="iv4-panel-logo-icon"><i class="bi bi-receipt-cutoff"></i></div>
                <div>
                    <div class="iv4-panel-logo-text">Invoice Studio</div>
                    <div class="iv4-panel-logo-sub">New Invoice &mdash; Design 4</div>
                </div>
            </div>

            {{-- Customer chip --}}
            <div class="iv4-lp-section">
                <span class="iv4-lp-label">Customer</span>
                <div class="iv4-customer-chip">
                    <div class="iv4-customer-chip-avatar"><i class="bi bi-person-fill"></i></div>
                    <div style="min-width:0;">
                        <div class="iv4-customer-chip-id">{{ $customer->customer_id }}</div>
                        <div class="iv4-customer-chip-name" title="{{ $customer->english_name }}">{{ $customer->english_name }}</div>
                    </div>
                </div>
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->customer_id }}">
            </div>

            {{-- Branch selector --}}
            <div class="iv4-lp-section">
                <span class="iv4-lp-label"><i class="bi bi-building" style="font-size:9px;"></i> Branch <span style="color:#ef4444;">*</span></span>
                <select name="branch_id" id="branch_id" class="iv4-lp-input" required>
                    <option value="">— Choose Branch —</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                                {{ ($defaultBranch && $defaultBranch->id == $branch->id) ? 'selected' : '' }}
                                data-name="{{ $branch->name }}">
                            {{ $branch->name }}{{ $branch->is_main ? ' ⭐' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Doctor --}}
            <div class="iv4-lp-section">
                <span class="iv4-lp-label"><i class="bi bi-hospital" style="font-size:9px;"></i> Doctor</span>
                <div style="margin-bottom:6px;">
                    <div class="iv4-lp-label" style="font-size:8px;margin-bottom:3px;">ID</div>
                    <input type="text" class="iv4-lp-input" id="doctor_id_display"
                           value="{{ session('doctor_id') }}" readonly placeholder="—">
                    <input type="hidden" name="doctor_id" id="doctor_id" value="{{ session('doctor_id') }}">
                </div>
                <div class="iv4-lp-label" style="font-size:8px;margin-bottom:3px;">Name</div>
                <div class="iv4-doctor-row">
                    <input type="text" class="iv4-lp-input" id="doctor_name" name="doctor_name"
                           value="{{ session('doctor_name') }}" placeholder="Doctor name">
                    <button type="button" class="iv4-lp-btn-icon"
                            data-toggle="modal" data-target="#doctorModal" title="Search doctor">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            {{-- Pickup date --}}
            <div class="iv4-lp-section">
                <span class="iv4-lp-label"><i class="bi bi-calendar-event" style="font-size:9px;"></i> Pickup Date <span style="color:#ef4444;">*</span></span>
                <input type="date" class="iv4-lp-input" name="pickup_date" id="pickup_date" required>
            </div>

            {{-- Created by --}}
            <div class="iv4-lp-section">
                <span class="iv4-lp-label"><i class="bi bi-person-circle" style="font-size:9px;"></i> Created By</span>
                <input type="text" class="iv4-lp-input" value="{{ auth()->user()->full_name }}" readonly>
                <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            </div>

            {{-- Flexible spacer --}}
            <div class="iv4-lp-spacer"></div>

            {{-- Mini totals --}}
            <div class="iv4-mini-totals">
                <div class="iv4-mini-totals-title">Invoice Totals</div>
                <div class="iv4-mini-row">
                    <span class="lbl">Subtotal</span>
                    <span class="val" id="iv4_subtotal">0.00</span>
                </div>
                <div class="iv4-mini-row">
                    <span class="lbl">Items</span>
                    <span class="val" id="iv4_qty">0</span>
                </div>
                <div class="iv4-mini-grand">
                    <span class="lbl">Grand Total</span>
                    <span class="val" id="iv4_grand">0.00</span>
                </div>
            </div>

        </aside>{{-- end .iv4-left --}}

        {{-- ════════════════════════════════════
             RIGHT PANEL
        ════════════════════════════════════ --}}
        <div class="iv4-right">

            <form id="invoiceForm">
            @csrf

            {{-- ── TAB BAR ── --}}
            <div class="iv4-tabbar">
                <button type="button" class="iv4-tab-btn active" data-iv4tab="products" onclick="iv4ShowTab('products')">
                    <span class="iv4-tab-icon">🛒</span> Products
                </button>
                <button type="button" class="iv4-tab-btn" data-iv4tab="lenses" onclick="iv4ShowTab('lenses')">
                    <span class="iv4-tab-icon">👁</span> Lenses
                </button>
                <button type="button" class="iv4-tab-btn" data-iv4tab="payments" onclick="iv4ShowTab('payments')">
                    <span class="iv4-tab-icon">💳</span> Payments
                </button>
                <button type="button" class="iv4-tab-btn" data-iv4tab="discounts" onclick="iv4ShowTab('discounts')">
                    <span class="iv4-tab-icon">📋</span> Discounts
                </button>
            </div>

            {{-- ── TAB CONTENT AREA ── --}}
            <div class="iv4-right-content">

                {{-- ══════ TAB: PRODUCTS ══════ --}}
                <div class="iv4-tab-panel" id="iv4-panel-products" style="display:block;">
                    <div class="iv4-card">
                        <div class="iv4-card-head">
                            <div class="iv4-card-head-icon amber"><i class="bi bi-cart-plus"></i></div>
                            <div>
                                <div class="iv4-card-head-title">Add Products</div>
                                <div class="iv4-card-head-sub">Scan barcode or enter product ID</div>
                            </div>
                            <div class="iv4-card-head-actions">
                                <button type="button" class="iv4-btn outline"
                                        data-toggle="modal" data-target="#searchModal">
                                    <i class="bi bi-funnel"></i> Advanced Search
                                </button>
                            </div>
                        </div>
                        <div class="iv4-card-body">
                            <div style="display:flex;gap:10px;align-items:flex-end;">
                                <div style="width:90px;">
                                    <label class="iv4-label">Qty</label>
                                    <input type="number" class="iv4-input" id="product_quantity"
                                           value="1" min="1">
                                </div>
                                <div style="flex:1;">
                                    <label class="iv4-label">Product ID / Barcode</label>
                                    <div class="iv4-barcode-wrap">
                                        <i class="bi bi-upc-scan iv4-bc-icon"></i>
                                        <input type="text" class="iv4-input" id="product_id_input"
                                               placeholder="Scan or enter product ID / barcode…">
                                    </div>
                                </div>
                                <button type="button" id="openCameraBtn" class="iv4-cam-btn" title="Scan with camera">
                                    <i class="bi bi-camera-video"></i>
                                </button>
                                <button type="button" class="iv4-btn green" id="addProductBtn"
                                        style="height:35px;align-self:flex-end;">
                                    <i class="bi bi-plus-circle"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════ TAB: LENSES ══════ --}}
                <div class="iv4-tab-panel" id="iv4-panel-lenses">
                    <div class="iv4-card">
                        <div class="iv4-card-head">
                            <div class="iv4-card-head-icon teal"><i class="bi bi-eye"></i></div>
                            <div>
                                <div class="iv4-card-head-title">Add Lenses</div>
                                <div class="iv4-card-head-sub">Eye test &rarr; filter &rarr; select</div>
                            </div>
                        </div>
                        {{-- Lenses section body — always visible inside the lenses tab; tab panel handles show/hide --}}
                        <div class="iv4-card-body" id="lensesSection" style="padding:16px;">

                            {{-- Step 1 --}}
                            <p class="iv4-step-label"><i class="bi bi-clipboard-check"></i> Step 1 — Select Eye Test</p>
                            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                <button type="button" class="iv4-btn blue" id="loadEyeTestsBtn">
                                    <i class="bi bi-eye"></i> Load Eye Tests
                                </button>
                                <a href="{{ route('dashboard.add-eye-test', ['id' => $customer->id]) }}"
                                   class="iv4-btn green" target="_blank">
                                    <i class="bi bi-plus-circle"></i> Add New Eye Test
                                </a>
                            </div>
                            <div id="eyeTestsContainer" style="margin-top:14px;"></div>

                            <hr class="iv4-divider" id="lensResultsDivider">

                            {{-- Step 2 (hidden until eye test selected) --}}
                            <div id="lensFiltersSection" style="display:none;">
                                <p class="iv4-step-label"><i class="bi bi-funnel"></i> Step 2 — Filter Lenses</p>
                                <div class="iv4-filter-grid">
                                    <div>
                                        <label class="iv4-label">Frame Type</label>
                                        <select class="iv4-input" id="lens_frame_type">
                                            <option value="">All</option>
                                            <option value="HBC Frame">HBC Frame</option>
                                            <option value="Full Frame">Full Frame</option>
                                            <option value="Nilor">Nilor</option>
                                            <option value="Rimless">Rimless</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Lens Type</label>
                                        <select class="iv4-input" id="lens_type">
                                            <option value="">All</option>
                                            <option value="All Distance Lense">All Distance Lense</option>
                                            <option value="Biofocal">Biofocal</option>
                                            <option value="Single Vision">Single Vision</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Life Style</label>
                                        <select class="iv4-input" id="lens_life_style">
                                            <option value="">All</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Active">Active</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Customer Activity</label>
                                        <select class="iv4-input" id="lens_customer_activity">
                                            <option value="">All</option>
                                            <option value="Clear / Tintable">Clear / Tintable</option>
                                            <option value="Transition">Transition</option>
                                            <option value="Glare Free">Glare Free</option>
                                            <option value="POLARIZED">POLARIZED</option>
                                            <option value="TINTED">TINTED</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Lens Technology</label>
                                        <select class="iv4-input" id="lens_lense_tech">
                                            <option value="">All</option>
                                            <option value="HD / Digital Lense">HD / Digital Lense</option>
                                            <option value="Basic">Basic</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Production</label>
                                        <select class="iv4-input" id="lens_production">
                                            <option value="">All</option>
                                            <option value="Stock">Stock</option>
                                            <option value="RX">RX</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Brand</label>
                                        <select class="iv4-input select2" name="lens_brand" id="lens_brand">
                                            <option value="">All</option>
                                            @foreach($lensBrands as $lb)
                                                <option value="{{ $lb->id }}">{{ $lb->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="iv4-label">Index</label>
                                        <select class="iv4-input" id="lens_index">
                                            <option value="">All</option>
                                            <option value="1.5">1.5</option>
                                            <option value="1.53">1.53</option>
                                            <option value="1.56">1.56</option>
                                            <option value="1.59">1.59</option>
                                            <option value="1.6">1.6</option>
                                            <option value="1.61">1.61</option>
                                            <option value="1.67">1.67</option>
                                            <option value="1.74">1.74</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="display:flex;gap:10px;margin-top:6px;flex-wrap:wrap;">
                                    <div style="flex:1;min-width:180px;">
                                        <label class="iv4-label">Description Search</label>
                                        <input type="text" class="iv4-input" id="lens_description"
                                               placeholder="Search by description…">
                                    </div>
                                    <div style="display:flex;align-items:flex-end;">
                                        <button type="button" class="iv4-btn green" id="filterLensesBtn">
                                            <i class="bi bi-search"></i> Search Lenses
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3 (hidden until search done) --}}
                            <div id="lensResultsSection" style="display:none;">
                                <p class="iv4-step-label" style="margin-top:14px;">
                                    <i class="bi bi-check-square"></i> Step 3 — Select Lenses
                                </p>
                                <div id="lensesTableContainer"></div>
                                <div style="margin-top:12px;">
                                    <button type="button" class="iv4-btn green" id="addSelectedLensesBtn">
                                        <i class="bi bi-plus-circle"></i> Add Selected Lenses to Invoice
                                    </button>
                                </div>
                            </div>

                        </div>{{-- end #lensesSection --}}
                    </div>

                    {{-- Expand lenses hint when section is still collapsed --}}
                    <p style="font-size:12px;color:#94a3b8;text-align:center;margin:6px 0 0;">
                        <i class="bi bi-info-circle"></i> The lenses panel opens automatically when you switch to this tab.
                    </p>
                </div>

                {{-- ══════ TAB: PAYMENTS ══════ --}}
                <div class="iv4-tab-panel" id="iv4-panel-payments">
                    <div class="iv4-card">
                        <div class="iv4-card-head">
                            <div class="iv4-card-head-icon green"><i class="bi bi-credit-card"></i></div>
                            <div>
                                <div class="iv4-card-head-title">Payments</div>
                                <div class="iv4-card-head-sub">Add one or more payment methods</div>
                            </div>
                        </div>
                        <div class="iv4-card-body">
                            <div id="paymentsContainer"></div>
                            <button type="button" class="iv4-btn blue" id="addPaymentBtn">
                                <i class="bi bi-plus-circle"></i> Add Payment Method
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ══════ TAB: DISCOUNTS ══════ --}}
                <div class="iv4-tab-panel" id="iv4-panel-discounts">

                    {{-- Regular Discount --}}
                    <div class="iv4-card" style="margin-bottom:14px;">
                        <div class="iv4-card-head">
                            <div class="iv4-card-head-icon red"><i class="bi bi-tag"></i></div>
                            <div>
                                <div class="iv4-card-head-title">Regular Discount</div>
                                <div class="iv4-card-head-sub">Fixed amount or percentage</div>
                            </div>
                            <div class="iv4-card-head-actions">
                                <button type="button" class="iv4-btn outline" id="openDiscountModalBtn">
                                    <i class="bi bi-info-circle"></i> Details
                                </button>
                            </div>
                        </div>
                        <div class="iv4-card-body lenses-collapsed" id="discountSection">
                            <div id="regularDiscountSection">
                                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                                    <div style="flex:1;min-width:130px;">
                                        <label class="iv4-label">Type</label>
                                        <select class="iv4-input" id="discount_type">
                                            <option value="">None</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage (%)</option>
                                        </select>
                                    </div>
                                    <div style="flex:1;min-width:110px;">
                                        <label class="iv4-label">Value</label>
                                        <input type="number" class="iv4-input" id="discount_value"
                                               placeholder="0.00" min="0" step="0.01">
                                    </div>
                                    <button type="button" class="iv4-btn green" id="applyDiscountBtn">
                                        <i class="bi bi-check"></i> Apply
                                    </button>
                                    <button type="button" class="iv4-btn red" id="removeDiscountBtn">
                                        <i class="bi bi-x"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payer Discount --}}
                    <div class="iv4-card">
                        <div class="iv4-card-head">
                            <div class="iv4-card-head-icon indigo"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <div class="iv4-card-head-title">Payer Discount</div>
                                <div class="iv4-card-head-sub">Insurance / Cardholder</div>
                            </div>
                        </div>
                        <div class="iv4-card-body">
                            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                                <div style="flex:1;min-width:130px;">
                                    <label class="iv4-label">Payer Type</label>
                                    <select class="iv4-input" id="payer_type">
                                        <option value="">None</option>
                                        <option value="insurance">Insurance</option>
                                        <option value="cardholder">Cardholder</option>
                                    </select>
                                </div>
                                <div style="flex:1;min-width:150px;">
                                    <label class="iv4-label">Company</label>
                                    <select class="iv4-input" id="payer_company" disabled>
                                        <option value="">Select company</option>
                                    </select>
                                </div>
                                <div style="min-width:120px;" id="approvalAmountGroup" class="d-none">
                                    <label class="iv4-label">Approval Amount</label>
                                    <input type="number" class="iv4-input" id="approval_amount"
                                           placeholder="0.00" min="0" step="0.01">
                                </div>
                                <button type="button" class="iv4-btn green" id="applyPayerBtn">
                                    <i class="bi bi-check"></i> Apply
                                </button>
                                <button type="button" class="iv4-btn red" id="removePayerBtn">
                                    <i class="bi bi-x"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                </div>{{-- end discounts tab --}}

                {{-- ══ INVOICE ITEMS (always visible, below tabs) ══ --}}
                <hr class="iv4-items-rule" style="margin:4px 0 0;">

                <div class="iv4-card" style="flex-shrink:0;">
                    <div class="iv4-items-head">
                        <span class="iv4-items-head-title">
                            <i class="bi bi-list-check"></i>
                            Invoice Items
                        </span>
                        <span id="items_count" class="iv4-items-count-badge">0 Items</span>
                    </div>
                    <div id="itemsTableContainer"></div>
                </div>

            </div>{{-- end .iv4-right-content --}}

            </form>

            {{-- ══ BOTTOM STICKY BAR ══ --}}
            <div class="iv4-bottom-bar">
                <div class="iv4-bottom-info">
                    <div class="iv4-bottom-stat">
                        <span class="lbl">Items</span>
                        <span class="val" id="iv4_bar_qty">0</span>
                    </div>
                    <div class="iv4-bottom-sep"></div>
                    <div class="iv4-bottom-stat">
                        <span class="lbl">Subtotal</span>
                        <span class="val" id="iv4_bar_subtotal">0.00</span>
                    </div>
                    <div class="iv4-bottom-sep"></div>
                    <div class="iv4-bottom-stat">
                        <span class="lbl" style="color:#16a34a;">Grand Total</span>
                        <span class="val" style="color:#16a34a;" id="iv4_bar_grand">0.00</span>
                    </div>
                </div>
                <div class="iv4-bottom-actions">
                    <button type="button" class="iv4-bar-btn reset" id="resetInvoiceBtn">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                    <button type="button" class="iv4-bar-btn draft" id="saveDraftBtn">
                        <i class="bi bi-save"></i> Save Draft
                    </button>
                    <button type="button" class="iv4-bar-btn submit" id="submitInvoiceBtn">
                        <i class="bi bi-check-circle-fill"></i> Submit Invoice
                    </button>
                </div>
            </div>

        </div>{{-- end .iv4-right --}}

    </div>{{-- end .iv4-split --}}

    {{-- ══ LOADING OVERLAY ══ --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner" id="loadingSpinner"></div>
    </div>

    {{-- ══ MODALS ══ --}}
    @include('dashboard.pages.invoice-new.modals.doctor_modal')
    @include('dashboard.pages.invoice-new.modals.search_modal')
    @include('dashboard.pages.invoice-new.modals.discount_modal')

    {{-- ══ CAMERA BARCODE SCANNER MODAL ══ --}}
    <div id="cameraScanModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.78);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;width:480px;max-width:96vw;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.45);">

            {{-- Camera modal header --}}
            <div style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
                <div style="color:#fff;font-size:15px;font-weight:800;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-camera-video" style="font-size:18px;"></i>
                    Camera Barcode Scanner
                </div>
                <button id="closeCameraBtn" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:7px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>

            {{-- Camera modal body --}}
            <div style="padding:20px;">
                <div id="camStatus" style="text-align:center;margin-bottom:12px;font-size:13px;color:#3b82f6;font-weight:600;min-height:18px;">Starting camera&hellip;</div>
                <div style="position:relative;border-radius:12px;overflow:hidden;background:#111;aspect-ratio:4/3;">
                    <video id="camVideo" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                    <div style="position:absolute;top:50%;left:8%;right:8%;height:2px;background:rgba(59,130,246,.85);transform:translateY(-50%);border-radius:2px;box-shadow:0 0 10px rgba(59,130,246,.8);animation:iv4ScanLine 2s ease-in-out infinite;"></div>
                    <div style="position:absolute;top:15%;left:8%;width:28px;height:28px;border-top:3px solid #3b82f6;border-left:3px solid #3b82f6;border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute;top:15%;right:8%;width:28px;height:28px;border-top:3px solid #3b82f6;border-right:3px solid #3b82f6;border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute;bottom:15%;left:8%;width:28px;height:28px;border-bottom:3px solid #3b82f6;border-left:3px solid #3b82f6;border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute;bottom:15%;right:8%;width:28px;height:28px;border-bottom:3px solid #3b82f6;border-right:3px solid #3b82f6;border-radius:0 0 4px 0;"></div>
                </div>
                <div style="margin-top:12px;display:flex;gap:10px;align-items:center;">
                    <label style="font-size:12px;font-weight:600;color:#666;white-space:nowrap;"><i class="bi bi-camera"></i> Camera:</label>
                    <select id="camDeviceSelect" style="flex:1;border:1.5px solid #e2e8f0;border-radius:7px;padding:5px 10px;font-size:13px;height:34px;"></select>
                    <button id="switchCamBtn" type="button" style="flex-shrink:0;height:34px;padding:0 12px;border-radius:7px;background:#3b82f6;color:#fff;border:none;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap;">
                        <i class="bi bi-arrow-repeat"></i> Switch
                    </button>
                </div>
                <div style="margin-top:10px;display:flex;gap:8px;">
                    <input type="text" id="camManualInput" placeholder="Or type barcode here&hellip;"
                           style="flex:1;border:1.5px solid #e2e8f0;border-radius:7px;padding:7px 12px;font-size:14px;">
                    <button id="camManualAddBtn" type="button" style="flex-shrink:0;padding:0 16px;border-radius:7px;background:#22c55e;color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-plus-circle"></i> Add
                    </button>
                </div>
                <div id="lastDetected" style="display:none;margin-top:12px;background:#f0fdf4;border:2px solid #86efac;border-radius:9px;padding:10px 14px;text-align:center;">
                    <div style="font-size:12px;color:#15803d;font-weight:700;margin-bottom:3px;"><i class="bi bi-check-circle-fill"></i> Detected!</div>
                    <div id="lastDetectedCode" style="font-size:18px;font-weight:800;color:#15803d;font-family:monospace;letter-spacing:2px;"></div>
                    <div style="font-size:11px;color:#888;margin-top:3px;">Adding to invoice&hellip;</div>
                </div>
            </div>

        </div>
    </div>

</div>{{-- .iv4-page --}}

{{-- ── Tab switch: expand lenses panel when switching to lenses tab ── --}}
<script>
function iv4ShowTab(tabName) {
    document.querySelectorAll('.iv4-tab-panel').forEach(function(p) {
        p.style.display = 'none';
    });
    document.querySelectorAll('.iv4-tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    document.getElementById('iv4-panel-' + tabName).style.display = 'block';
    document.querySelector('[data-iv4tab="' + tabName + '"]').classList.add('active');

    /* Discount section expand when discounts tab is opened */
    if (tabName === 'discounts') {
        var ds = document.getElementById('discountSection');
        if (ds && ds.classList.contains('lenses-collapsed')) {
            ds.classList.remove('lenses-collapsed');
            ds.classList.add('lenses-expanded');
            setTimeout(function () {
                ds.style.maxHeight = 'none';
                ds.style.overflow  = 'visible';
            }, 560);
        }
    }
}
</script>
@endsection

@section('scripts')
    @include('dashboard.pages.invoice-new.script.main')

    {{-- ══ Design 4 — Override toggleLensesSection so it doesn't crash (no toggle icon in v4) ══ --}}
    <script>
    /* In Design 4 the lenses panel is a tab, not a collapsible section.
       The global toggleLensesSection() from script.main references #lensesToggleIcon
       which doesn't exist here — override it to avoid a JS crash after lenses are added. */
    /* lensesSection in v4 is always visible inside the lenses tab.
       After lenses are added, just switch to the Products tab. */
    window.toggleLensesSection = function () {
        if (typeof iv4ShowTab === 'function') {
            iv4ShowTab('products');
        }
    };
    </script>

    {{-- ══ Design 4 Sidebar & Bottom Bar Sync — Triple-layer approach ══ --}}
    <script>
    /* ─────────────────────────────────────────────────────────
       CORE UPDATE FUNCTION
       Reads invoiceState and updates left panel + bottom bar.
    ───────────────────────────────────────────────────────── */
    function iv4UpdateSync() {
        if (typeof invoiceState === 'undefined') return;

        var t = invoiceState.totals   || {};
        var d = invoiceState.discount || {};

        var subtotalBefore = parseFloat(t.subtotal_before || t.subtotal || 0);
        var grandTotal     = parseFloat(t.grand_total     || 0);
        var qty            = parseInt(t.total_qty         || 0, 10);

        function put(id, txt) {
            var el = document.getElementById(id);
            if (el && el.textContent !== txt) el.textContent = txt;
        }

        /* Left panel mini totals */
        put('iv4_subtotal', subtotalBefore.toFixed(2));
        put('iv4_qty',      String(qty));
        put('iv4_grand',    grandTotal.toFixed(2));

        /* Bottom bar */
        put('iv4_bar_subtotal', subtotalBefore.toFixed(2));
        put('iv4_bar_qty',      String(qty));
        put('iv4_bar_grand',    grandTotal.toFixed(2));

        /* Grand total pill color */
        var grandEl = document.getElementById('iv4_grand');
        if (grandEl) {
            var disc = Math.max(0, subtotalBefore - grandTotal);
            grandEl.parentElement.style.background = disc > 0
                ? 'linear-gradient(135deg, #16a34a, #22c55e)'
                : 'linear-gradient(135deg, #1d4ed8, #3b82f6)';
        }
    }

    /* ─────────────────────────────────────────────────────────
       LAYER 1 — Wrap renderItemsTable
    ───────────────────────────────────────────────────────── */
    if (typeof renderItemsTable === 'function') {
        var _iv4OrigRender = renderItemsTable;
        renderItemsTable = function () {
            _iv4OrigRender.apply(this, arguments);
            iv4UpdateSync();
        };
        window.renderItemsTable = renderItemsTable;
    }

    /* ─────────────────────────────────────────────────────────
       LAYER 2 — MutationObserver on #itemsTableContainer
    ───────────────────────────────────────────────────────── */
    $(document).ready(function () {
        var container = document.getElementById('itemsTableContainer');
        if (container) {
            new MutationObserver(function () {
                setTimeout(iv4UpdateSync, 20);
            }).observe(container, { childList: true, subtree: false });
        }

        /* Discount / payer buttons also need re-sync */
        $(document).on('click',
            '#applyDiscountBtn, #removeDiscountBtn, #applyPayerBtn, #removePayerBtn',
            function () { setTimeout(iv4UpdateSync, 100); }
        );
    });

    /* ─────────────────────────────────────────────────────────
       LAYER 3 — Polling fallback every 400 ms
    ───────────────────────────────────────────────────────── */
    setInterval(iv4UpdateSync, 400);
    </script>

    {{-- ZXing barcode decoder --}}
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.20.0/umd/index.min.js"></script>

    <script>
    /* ═══════════════════════════════════════════════════════════
       CAMERA BARCODE SCANNER (Design 4)
    ═══════════════════════════════════════════════════════════ */
    (function () {
        var modal       = document.getElementById('cameraScanModal');
        var video       = document.getElementById('camVideo');
        var statusEl    = document.getElementById('camStatus');
        var deviceSel   = document.getElementById('camDeviceSelect');
        var lastDiv     = document.getElementById('lastDetected');
        var lastCode    = document.getElementById('lastDetectedCode');
        var manualInput = document.getElementById('camManualInput');
        var codeReader  = null;
        var scanCooldown = false;
        var devices      = [];

        document.getElementById('openCameraBtn').addEventListener('click', function () {
            modal.style.display = 'flex';
            initScanner();
        });

        document.getElementById('closeCameraBtn').addEventListener('click', closeCamera);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeCamera();
        });

        document.getElementById('switchCamBtn').addEventListener('click', function () {
            var sel = deviceSel.value;
            if (sel) startDecoding(sel);
        });

        document.getElementById('camManualAddBtn').addEventListener('click', function () {
            var code = manualInput.value.trim();
            if (code) { triggerAdd(code); manualInput.value = ''; }
        });

        manualInput.addEventListener('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var code = this.value.trim();
                if (code) { triggerAdd(code); this.value = ''; }
            }
        });

        function initScanner() {
            statusEl.textContent = 'Requesting camera access…';
            lastDiv.style.display = 'none';

            if (typeof ZXing === 'undefined') {
                statusEl.textContent = '⚠ Scanner library not loaded. Use manual input.';
                return;
            }

            codeReader = new ZXing.BrowserMultiFormatReader();

            codeReader.listVideoInputDevices().then(function (videoDevices) {
                devices = videoDevices;
                deviceSel.innerHTML = '';

                if (devices.length === 0) {
                    statusEl.textContent = '⚠ No camera found.';
                    return;
                }

                devices.forEach(function (d, i) {
                    var opt = document.createElement('option');
                    opt.value = d.deviceId;
                    opt.textContent = d.label || ('Camera ' + (i + 1));
                    deviceSel.appendChild(opt);
                });

                var backCam = devices.find(function (d) {
                    return /back|rear|environment/i.test(d.label);
                });
                var chosen = backCam ? backCam.deviceId : devices[0].deviceId;
                deviceSel.value = chosen;
                startDecoding(chosen);

            }).catch(function (err) {
                statusEl.textContent = '⚠ Camera access denied: ' + err.message;
            });
        }

        function startDecoding(deviceId) {
            if (codeReader) codeReader.reset();
            statusEl.textContent = 'Point camera at barcode…';
            lastDiv.style.display = 'none';
            scanCooldown = false;

            codeReader.decodeFromVideoDevice(deviceId, video, function (result, err) {
                if (result && !scanCooldown) {
                    scanCooldown = true;
                    onBarcodeDetected(result.getText());
                    setTimeout(function () { scanCooldown = false; }, 2500);
                }
            });
        }

        function onBarcodeDetected(code) {
            lastCode.textContent = code;
            lastDiv.style.display = 'block';
            statusEl.textContent = '✓ Scanned — adding to invoice…';

            try {
                var ctx  = new (window.AudioContext || window.webkitAudioContext)();
                var osc  = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.2);
            } catch (e) {}

            triggerAdd(code);
        }

        function triggerAdd(code) {
            closeCamera();
            var inp = document.getElementById('product_id_input');
            if (inp) {
                inp.value = code;
                inp.focus();
                if (typeof addProduct === 'function') {
                    addProduct();
                } else {
                    document.getElementById('addProductBtn').click();
                }
            }
        }

        function closeCamera() {
            if (codeReader) { codeReader.reset(); codeReader = null; }
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(function (t) { t.stop(); });
                video.srcObject = null;
            }
            modal.style.display = 'none';
        }
    })();
    </script>
@endsection
