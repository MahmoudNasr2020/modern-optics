@extends('dashboard.layouts.master')

@section('title', 'Create Invoice')

@section('styles')
<style>
/* ╔══════════════════════════════════════════════════════════╗
   ║   INVOICE DESIGN 2 — CLEAN & SIMPLE                     ║
   ╚══════════════════════════════════════════════════════════╝ */

/* ── Page wrapper ── */
.iv2-page {
    background: #f1f5f9;
    min-height: 100vh;
    padding: 0;
}

/* ── Top bar ── */
.iv2-topbar {
    background: #fff;
    border-bottom: 2px solid #e2e8f0;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: sticky;
    top: 0;
    z-index: 200;
}
.iv2-topbar-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.iv2-topbar-title i { color: #2563eb; font-size: 20px; }
.iv2-topbar-badge {
    background: #eff6ff;
    color: #2563eb;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #bfdbfe;
}
.iv2-topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
.iv2-btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    color: #475569; padding: 7px 16px; border-radius: 8px;
    font-size: 13px; font-weight: 700; text-decoration: none;
    transition: all .15s;
}
.iv2-btn-back:hover { background: #f1f5f9; color: #0f172a; text-decoration: none; }

/* ── Body layout ── */
.iv2-body {
    display: flex;
    gap: 20px;
    padding: 20px 24px;
    align-items: flex-start;
}

/* ── LEFT COLUMN ── */
.iv2-left { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 16px; }

/* ── RIGHT COLUMN (summary sidebar) ── */
.iv2-right {
    width: 320px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: sticky;
    top: 72px;
}

/* ── Section card ── */
.iv2-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

/* ── Section head ── */
.iv2-head {
    padding: 13px 18px;
    border-bottom: 1.5px solid #e8edf3;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
}
.iv2-head-icon {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.iv2-head-icon.blue   { background: #eff6ff; color: #2563eb; }
.iv2-head-icon.green  { background: #f0fdf4; color: #16a34a; }
.iv2-head-icon.orange { background: #fff7ed; color: #ea580c; }
.iv2-head-icon.indigo { background: #eef2ff; color: #4f46e5; }
.iv2-head-icon.teal   { background: #f0fdfa; color: #0d9488; }
.iv2-head-icon.slate  { background: #f1f5f9; color: #475569; }
.iv2-head-icon.red    { background: #fef2f2; color: #dc2626; }

.iv2-head-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.iv2-head-sub {
    font-size: 11px;
    color: #94a3b8;
    margin: 1px 0 0;
}
.iv2-head-actions { margin-left: auto; display: flex; gap: 8px; align-items: center; }

/* ── Section body ── */
.iv2-body-pad { padding: 18px; }

/* ── Labels & inputs ── */
.iv2-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 5px;
    display: block;
}
.iv2-input {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    color: #0f172a;
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.iv2-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    outline: none;
}
.iv2-input[readonly] { background: #f8fafc; color: #64748b; }
select.iv2-input { cursor: pointer; }

/* ── Buttons ── */
.iv2-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 8px;
    font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    transition: all .15s; text-decoration: none;
}
.iv2-btn:hover { opacity: .88; transform: translateY(-1px); }
.iv2-btn.blue   { background: #2563eb; color: #fff; }
.iv2-btn.green  { background: #16a34a; color: #fff; }
.iv2-btn.red    { background: #dc2626; color: #fff; }
.iv2-btn.indigo { background: #4f46e5; color: #fff; }
.iv2-btn.outline {
    background: transparent; color: #475569;
    border: 1.5px solid #e2e8f0;
}
.iv2-btn.outline:hover { background: #f1f5f9; }
.iv2-btn.block { width: 100%; justify-content: center; }
.iv2-btn.lg { padding: 12px 28px; font-size: 15px; }

/* ── Branch sticky bar ── */
.iv2-branch-bar {
    background: #fff;
    border: 1.5px solid #2563eb;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.iv2-branch-icon {
    width: 40px; height: 40px; background: #eff6ff;
    border-radius: 10px; display: flex; align-items: center;
    justify-content: center; font-size: 20px; color: #2563eb; flex-shrink: 0;
}
.iv2-branch-bar select { flex: 1; }
.iv2-branch-note {
    font-size: 12px; color: #2563eb; background: #eff6ff;
    padding: 5px 10px; border-radius: 6px; display: flex;
    align-items: center; gap: 5px; font-weight: 600;
}

/* ── Collapsible toggle ── */
.iv2-collapse-toggle { cursor: pointer; }
.iv2-collapse-toggle:hover { background: #f0f4f8; }
.iv2-collapse-body { }
.iv2-collapse-body.collapsed { display: none; }

/* ── Steps ── */
.iv2-steps {
    display: flex;
    gap: 0;
    margin-bottom: 18px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}
.iv2-step {
    flex: 1;
    padding: 9px 12px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    background: #f8fafc;
    border-right: 1.5px solid #e2e8f0;
}
.iv2-step:last-child { border-right: none; }
.iv2-step.active { background: #2563eb; color: #fff; }
.iv2-step span {
    display: block;
    width: 22px; height: 22px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    margin: 0 auto 4px;
    line-height: 22px;
}
.iv2-step:not(.active) span { background: #e2e8f0; color: #64748b; }

/* ── Filter row ── */
.iv2-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}
.iv2-filter-grid-2 {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
}

/* ── Items table ── */
.iv2-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.iv2-table thead th {
    background: #0f172a;
    color: #fff;
    padding: 10px 12px;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: center;
    border: none;
}
.iv2-table tbody td {
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    text-align: center;
    color: #334155;
}
.iv2-table tbody tr:hover { background: #f8fafc; }
.iv2-table tfoot td {
    background: #0f172a;
    color: #fff;
    padding: 12px;
    font-weight: 800;
    font-size: 15px;
    text-align: center;
    border: none;
}

/* ── Summary sidebar ── */
.iv2-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
    color: #475569;
}
.iv2-summary-row:last-child { border-bottom: none; }
/* Use .sum-lbl instead of .label to avoid Bootstrap 3 conflict (Bootstrap makes .label white) */
.iv2-summary-row .sum-lbl { font-weight: 600; color: #334155; }
.iv2-summary-row .sum-val { font-weight: 800; color: #0f172a; }
/* Keep old .label/.value selectors for any code that still uses them */
.iv2-summary-row .label { font-weight: 600; color: #334155 !important; }
.iv2-summary-row .value { font-weight: 800; color: #0f172a !important; }
.iv2-summary-grand {
    background: #0f172a;
    color: #fff;
    padding: 14px 18px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}
.iv2-summary-grand .lbl { font-size: 13px; font-weight: 700; opacity: .75; color: #fff !important; }
.iv2-summary-grand .val { font-size: 22px; font-weight: 900; color: #fff !important; }

/* ── Payment row ── */
.iv2-payment-row {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
}

/* ── Bottom submit bar (sticky) ── */
.iv2-submit-bar {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-top: 2px solid #e2e8f0;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 150;
    gap: 12px;
}
.iv2-submit-bar .left-btns { display: flex; gap: 10px; }

/* ── Empty state ── */
.iv2-empty {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}
.iv2-empty i { font-size: 48px; display: block; margin-bottom: 10px; }
.iv2-empty p { font-size: 14px; margin: 0; }

/* ── Discount section ── */
.iv2-discount-group {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 12px;
}
.iv2-discount-group h6 {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 0 0 12px;
}

/* ── Barcode input ── */
.iv2-barcode-wrap { position: relative; }
.iv2-barcode-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 16px;
    pointer-events: none;
}
.iv2-barcode-wrap input { padding-left: 38px; }

/* ── Camera button ── */
.iv2-camera-btn {
    height: 38px;
    padding: 0 14px;
    border-radius: 8px;
    background: #4f46e5;
    color: #fff;
    border: none;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .15s;
    flex-shrink: 0;
}
.iv2-camera-btn:hover { background: #4338ca; }

/* ── Lens step label ── */
.iv2-step-label {
    font-size: 13px;
    font-weight: 800;
    color: #2563eb;
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 7px;
}

/* ── Divider ── */
.iv2-divider {
    border: none;
    border-top: 1.5px solid #e2e8f0;
    margin: 16px 0;
}

/* ── Info chip in topbar ── */
.iv2-customer-chip {
    display: flex; align-items: center; gap: 8px;
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 8px; padding: 6px 14px;
    font-size: 13px; font-weight: 700; color: #334155;
}
.iv2-customer-chip i { color: #2563eb; }

/* ── Design switch link ── */
.iv2-design-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f1f5f9; border: 1.5px solid #e2e8f0;
    color: #64748b; padding: 6px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 700; text-decoration: none;
    transition: all .15s;
}
.iv2-design-pill:hover { background: #e2e8f0; color: #334155; text-decoration: none; }

/* Collapse toggle icon */
.iv2-chevron { transition: transform .2s; }
.iv2-chevron.open { transform: rotate(180deg); }

/* ── Lens filters hidden initially ── */
#iv2LensFilters { display: none; }
#iv2LensResults { display: none; }

/* Responsive */
@media (max-width: 1024px) {
    .iv2-body { flex-direction: column; }
    .iv2-right { width: 100%; position: static; }
    .iv2-filter-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .iv2-body { padding: 14px; }
    .iv2-filter-grid { grid-template-columns: 1fr; }
}

/* ── keep existing JS-rendered styles working ── */
.items-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.items-table thead { background: #0f172a; }
.items-table thead th { color: #fff; padding: 10px 12px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; text-align: center; border: none; }
.items-table tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; text-align: center; }
.items-table tfoot { background: #0f172a; }
.items-table tfoot td { color: #fff; padding: 12px; font-weight: 800; font-size: 15px; border: none; }
.items-table tbody tr:hover { background: #f8fafc; }
.btn-danger-custom { background: #dc2626; border: none; padding: 6px 14px; border-radius: 7px; font-weight: 700; color: #fff; font-size: 12px; cursor: pointer; }
.btn-danger-custom:hover { background: #b91c1c; color: #fff; }
.empty-state { text-align: center; padding: 50px 20px; color: #94a3b8; }
.empty-state i { font-size: 56px; display: block; margin-bottom: 12px; }

/* Loading overlay */
.loading-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center; }
.loading-overlay.active { display:flex; }
.loading-spinner { width:52px; height:52px; border:5px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin .8s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }

/* Lens eye selectors */
.lens-select-left, .lens-select-right {
    appearance: none; -webkit-appearance: none;
    width: 22px; height: 22px;
    border: 2px solid #2563eb; border-radius: 5px;
    cursor: pointer; position: relative; display: inline-block;
    transition: all .15s; vertical-align: middle;
}
.lens-select-left:checked, .lens-select-right:checked {
    background: #2563eb; border-color: transparent;
}
.lens-select-left:checked::after, .lens-select-right:checked::after {
    content: ''; position: absolute;
    left: 5px; top: 1px; width: 6px; height: 11px;
    border: solid white; border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}
.lens-select-left:disabled, .lens-select-right:disabled { opacity: .4; cursor: not-allowed; }

/* Camera scanline animation */
@keyframes scanLine { 0%,100% { top:20%; } 50% { top:80%; } }
</style>
@endsection

@section('content')
<div class="iv2-page">

    {{-- ══ TOP BAR ══ --}}
    <div class="iv2-topbar">
        <h2 class="iv2-topbar-title">
            <i class="bi bi-receipt-cutoff"></i>
            Create Invoice
        </h2>
        <span class="iv2-topbar-badge">NEW</span>

        <div class="iv2-customer-chip">
            <i class="bi bi-person-fill"></i>
            {{ $customer->customer_id }} — {{ $customer->english_name }}
        </div>

        <div class="iv2-topbar-right">
            @can('edit-settings')
            <a href="{{ route('dashboard.settings.index') }}" class="iv2-design-pill"
               title="Switch invoice design in Settings">
                <i class="bi bi-palette"></i> Design 2
            </a>
            @endcan
            <a href="{{ route('dashboard.get-all-customers') }}" class="iv2-btn-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ══ MAIN BODY ══ --}}
    <div class="iv2-body">

        {{-- ════════ LEFT COLUMN ════════ --}}
        <div class="iv2-left">

            <form id="invoiceForm">
            @csrf

            {{-- ── 1. BRANCH ── --}}
            <div class="iv2-branch-bar">
                <div class="iv2-branch-icon"><i class="bi bi-building"></i></div>
                <div style="flex:1;">
                    <label class="iv2-label" style="margin-bottom:4px;">
                        <i class="bi bi-geo-alt-fill" style="color:#2563eb;"></i>
                        Branch <span style="color:#dc2626;">*</span>
                    </label>
                    <select name="branch_id" id="branch_id" class="iv2-input" style="height:36px;" required>
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
                <div class="iv2-branch-note">
                    <i class="bi bi-info-circle"></i> Stock checked per branch
                </div>
            </div>

            {{-- ── 2. CUSTOMER & DOCTOR ── --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon blue"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="iv2-head-title">Customer & Doctor</div>
                        <div class="iv2-head-sub">Invoice parties</div>
                    </div>
                </div>
                <div class="iv2-body-pad">
                    <div class="row">
                        {{-- Customer --}}
                        <div class="col-md-2">
                            <label class="iv2-label">Customer ID</label>
                            <input type="text" class="iv2-input" value="{{ $customer->customer_id }}" readonly>
                            <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                        </div>
                        <div class="col-md-4">
                            <label class="iv2-label">Customer Name</label>
                            <input type="text" class="iv2-input" value="{{ $customer->english_name }}" readonly>
                        </div>
                        {{-- Doctor --}}
                        <div class="col-md-2">
                            <label class="iv2-label">Doctor ID</label>
                            <input type="text" class="iv2-input" id="doctor_id_display"
                                   value="{{ session('doctor_id') }}" readonly>
                            <input type="hidden" name="doctor_id" id="doctor_id"
                                   value="{{ session('doctor_id') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="iv2-label">Doctor Name</label>
                            <input type="text" class="iv2-input" id="doctor_name"
                                   name="doctor_name" value="{{ session('doctor_name') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="iv2-label">&nbsp;</label>
                            <button type="button" class="iv2-btn blue block"
                                    data-toggle="modal" data-target="#doctorModal"
                                    style="height:36px;padding:0;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 3. ADD PRODUCTS ── --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon orange"><i class="bi bi-cart-plus"></i></div>
                    <div>
                        <div class="iv2-head-title">Add Products</div>
                        <div class="iv2-head-sub">Scan barcode or enter product ID</div>
                    </div>
                    <div class="iv2-head-actions">
                        <button type="button" class="iv2-btn outline"
                                data-toggle="modal" data-target="#searchModal">
                            <i class="bi bi-funnel"></i> Advanced Search
                        </button>
                    </div>
                </div>
                <div class="iv2-body-pad">
                    <div style="display:flex;gap:10px;align-items:flex-end;">
                        <div style="width:100px;">
                            <label class="iv2-label">Qty</label>
                            <input type="number" class="iv2-input" id="product_quantity"
                                   value="1" min="1">
                        </div>
                        <div style="flex:1;">
                            <label class="iv2-label">Product ID / Barcode</label>
                            <div class="iv2-barcode-wrap">
                                <i class="bi bi-upc-scan"></i>
                                <input type="text" class="iv2-input" id="product_id_input"
                                       placeholder="Scan or enter product ID / barcode…">
                            </div>
                        </div>
                        <button type="button" id="openCameraBtn" class="iv2-camera-btn" title="Scan with camera">
                            <i class="bi bi-camera-video"></i>
                        </button>
                        <button type="button" class="iv2-btn green" id="addProductBtn"
                                style="height:38px;white-space:nowrap;">
                            <i class="bi bi-plus-circle"></i> Add
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── 4. ADD LENSES ── --}}
            <div class="iv2-card">
                <div class="iv2-head iv2-collapse-toggle" onclick="iv2ToggleLenses(this)">
                    <div class="iv2-head-icon teal"><i class="bi bi-eye"></i></div>
                    <div>
                        <div class="iv2-head-title">Add Lenses</div>
                        <div class="iv2-head-sub">Eye test → filter → select</div>
                    </div>
                    <div class="iv2-head-actions">
                        <i class="bi bi-chevron-down iv2-chevron" id="lensesToggleIcon"></i>
                    </div>
                </div>
                <div class="iv2-collapse-body collapsed iv2-body-pad" id="lensesSection">

                    {{-- Step 1 --}}
                    <p class="iv2-step-label"><i class="bi bi-clipboard-check"></i> Step 1 — Select Eye Test</p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button type="button" class="iv2-btn blue" id="loadEyeTestsBtn">
                            <i class="bi bi-eye"></i> Load Eye Tests
                        </button>
                        <a href="{{ route('dashboard.add-eye-test', ['id' => $customer->id]) }}"
                           class="iv2-btn green" target="_blank">
                            <i class="bi bi-plus-circle"></i> Add New Eye Test
                        </a>
                    </div>
                    <div id="eyeTestsContainer" style="margin-top:16px;"></div>

                    <hr class="iv2-divider" id="lensResultsDivider">

                    {{-- Step 2 (hidden until eye test selected) --}}
                    <div id="lensFiltersSection" style="display:none;" id="iv2LensFilters">
                        <p class="iv2-step-label"><i class="bi bi-funnel"></i> Step 2 — Filter Lenses</p>
                        <div class="iv2-filter-grid">
                            <div>
                                <label class="iv2-label">Frame Type</label>
                                <select class="iv2-input" id="lens_frame_type">
                                    <option value="">All</option>
                                    <option>HBC Frame</option>
                                    <option>Full Frame</option>
                                    <option>Nilor</option>
                                    <option>Rimless</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Lens Type</label>
                                <select class="iv2-input" id="lens_type">
                                    <option value="">All</option>
                                    <option>All Distance Lense</option>
                                    <option>Biofocal</option>
                                    <option>Single Vision</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Life Style</label>
                                <select class="iv2-input" id="lens_life_style">
                                    <option value="">All</option>
                                    <option>Normal</option>
                                    <option>Active</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Customer Activity</label>
                                <select class="iv2-input" id="lens_customer_activity">
                                    <option value="">All</option>
                                    <option>Clear / Tintable</option>
                                    <option>Transition</option>
                                    <option>Glare Free</option>
                                    <option>POLARIZED</option>
                                    <option>TINTED</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Lens Technology</label>
                                <select class="iv2-input" id="lens_lense_tech">
                                    <option value="">All</option>
                                    <option>HD / Digital Lense</option>
                                    <option>Basic</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Production</label>
                                <select class="iv2-input" id="lens_production">
                                    <option value="">All</option>
                                    <option>Stock</option>
                                    <option>RX</option>
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Brand</label>
                                <select class="iv2-input select2" name="lens_brand" id="lens_brand">
                                    <option value="">All</option>
                                    @foreach($lensBrands as $lb)
                                        <option value="{{ $lb->id }}">{{ $lb->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="iv2-label">Index</label>
                                <select class="iv2-input" id="lens_index">
                                    <option value="">All</option>
                                    <option>1.5</option><option>1.53</option><option>1.56</option>
                                    <option>1.59</option><option>1.6</option><option>1.61</option>
                                    <option>1.67</option><option>1.74</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex;gap:10px;margin-top:4px;">
                            <div style="flex:1;">
                                <label class="iv2-label">Description Search</label>
                                <input type="text" class="iv2-input" id="lens_description"
                                       placeholder="Search by description…">
                            </div>
                            <div style="display:flex;align-items:flex-end;">
                                <button type="button" class="iv2-btn green" id="filterLensesBtn">
                                    <i class="bi bi-search"></i> Search Lenses
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3 (hidden until search done) --}}
                    <div id="lensResultsSection" style="display:none;" id="iv2LensResults">
                        <p class="iv2-step-label" style="margin-top:16px;">
                            <i class="bi bi-check-square"></i> Step 3 — Select Lenses
                        </p>
                        <div id="lensesTableContainer"></div>
                        <div style="margin-top:14px;">
                            <button type="button" class="iv2-btn green" id="addSelectedLensesBtn">
                                <i class="bi bi-plus-circle"></i> Add Selected Lenses to Invoice
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── 5. DISCOUNTS ── --}}
            <div class="iv2-card">
                <div class="iv2-head iv2-collapse-toggle" onclick="iv2ToggleDiscount(this)">
                    <div class="iv2-head-icon red"><i class="bi bi-percent"></i></div>
                    <div>
                        <div class="iv2-head-title">Discounts</div>
                        <div class="iv2-head-sub">Regular discount & payer (insurance / cardholder)</div>
                    </div>
                    <div class="iv2-head-actions">
                        <button type="button" class="iv2-btn outline" id="openDiscountModalBtn"
                                onclick="event.stopPropagation()">
                            <i class="bi bi-info-circle"></i> Details
                        </button>
                        <i class="bi bi-chevron-down iv2-chevron" id="discountToggleIcon"></i>
                    </div>
                </div>
                <div class="iv2-collapse-body collapsed iv2-body-pad" id="discountSection">

                    {{-- Regular discount --}}
                    <div class="iv2-discount-group" id="regularDiscountSection">
                        <h6>Regular Discount</h6>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                            <div style="flex:1;min-width:140px;">
                                <label class="iv2-label">Type</label>
                                <select class="iv2-input" id="discount_type">
                                    <option value="">None</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage (%)</option>
                                </select>
                            </div>
                            <div style="flex:1;min-width:120px;">
                                <label class="iv2-label">Value</label>
                                <input type="number" class="iv2-input" id="discount_value"
                                       placeholder="0.00" min="0" step="0.01">
                            </div>
                            <button type="button" class="iv2-btn green" id="applyDiscountBtn">
                                <i class="bi bi-check"></i> Apply
                            </button>
                            <button type="button" class="iv2-btn red" id="removeDiscountBtn">
                                <i class="bi bi-x"></i> Remove
                            </button>
                        </div>
                    </div>

                    {{-- Payer discount --}}
                    <div class="iv2-discount-group">
                        <h6>Payer Discount (Insurance / Cardholder)</h6>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                            <div style="flex:1;min-width:140px;">
                                <label class="iv2-label">Payer Type</label>
                                <select class="iv2-input" id="payer_type">
                                    <option value="">None</option>
                                    <option value="insurance">Insurance</option>
                                    <option value="cardholder">Cardholder</option>
                                </select>
                            </div>
                            <div style="flex:1;min-width:160px;">
                                <label class="iv2-label">Company</label>
                                <select class="iv2-input" id="payer_company" disabled>
                                    <option value="">Select company</option>
                                </select>
                            </div>
                            <div style="min-width:130px;" id="approvalAmountGroup" class="d-none">
                                <label class="iv2-label">Approval Amount</label>
                                <input type="number" class="iv2-input" id="approval_amount"
                                       placeholder="0.00" min="0" step="0.01">
                            </div>
                            <button type="button" class="iv2-btn green" id="applyPayerBtn">
                                <i class="bi bi-check"></i> Apply
                            </button>
                            <button type="button" class="iv2-btn red" id="removePayerBtn">
                                <i class="bi bi-x"></i> Remove
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── 6. EXTRA INFO ── --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon slate"><i class="bi bi-info-circle"></i></div>
                    <div>
                        <div class="iv2-head-title">Additional Info</div>
                        <div class="iv2-head-sub">Pickup date & created by</div>
                    </div>
                </div>
                <div class="iv2-body-pad">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="iv2-label">Pickup Date <span style="color:#dc2626;">*</span></label>
                            <input type="date" class="iv2-input" name="pickup_date" id="pickup_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="iv2-label">Created By</label>
                            <input type="text" class="iv2-input" value="{{ auth()->user()->full_name }}" readonly>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 7. PAYMENTS ── --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon green"><i class="bi bi-credit-card"></i></div>
                    <div>
                        <div class="iv2-head-title">Payments</div>
                        <div class="iv2-head-sub">Add one or more payment methods</div>
                    </div>
                </div>
                <div class="iv2-body-pad">
                    <div id="paymentsContainer"></div>
                    <button type="button" class="iv2-btn blue" id="addPaymentBtn">
                        <i class="bi bi-plus-circle"></i> Add Payment Method
                    </button>
                </div>
            </div>

            {{-- ── 8. INVOICE ITEMS TABLE ── --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon indigo"><i class="bi bi-list-check"></i></div>
                    <div>
                        <div class="iv2-head-title">Invoice Items</div>
                        <div class="iv2-head-sub">All added products and lenses</div>
                    </div>
                    <div class="iv2-head-actions">
                        <span id="items_count"
                              style="background:#eef2ff;color:#4f46e5;font-size:12px;font-weight:800;padding:4px 12px;border-radius:20px;border:1px solid #c7d2fe;">
                            0 Items
                        </span>
                    </div>
                </div>
                <div style="padding:0;">
                    <div id="itemsTableContainer"></div>
                </div>
            </div>

            </form>

        </div>{{-- end .iv2-left --}}

        {{-- ════════ RIGHT SIDEBAR ════════ --}}
        <div class="iv2-right">

            {{-- Summary totals --}}
            <div class="iv2-card">
                <div class="iv2-head">
                    <div class="iv2-head-icon indigo"><i class="bi bi-calculator"></i></div>
                    <div>
                        <div class="iv2-head-title">Summary</div>
                        <div class="iv2-head-sub">Invoice totals</div>
                    </div>
                </div>
                <div class="iv2-body-pad">
                    <div class="iv2-summary-row">
                        <span class="sum-lbl">Subtotal</span>
                        <span class="sum-val" id="sidebar_subtotal">0.00</span>
                    </div>
                    <div class="iv2-summary-row">
                        <span class="sum-lbl">Items</span>
                        <span class="sum-val" id="sidebar_qty">0</span>
                    </div>
                    <div class="iv2-summary-row">
                        <span class="sum-lbl" style="color:#16a34a;font-weight:700;">
                            <i class="bi bi-tag" style="font-size:11px;"></i> Discount
                        </span>
                        <span class="sum-val" style="color:#16a34a;" id="sidebar_discount">—</span>
                    </div>
                    <div class="iv2-summary-row" style="background:#fff8f1;border-radius:8px;padding:8px 10px;margin:4px 0;">
                        <span class="sum-lbl" style="color:#ea580c;font-weight:700;display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-shield-check" style="font-size:12px;"></i> Payer
                        </span>
                        <span class="sum-val" style="color:#ea580c;font-weight:800;" id="sidebar_payer">—</span>
                    </div>
                    <hr style="margin:8px 0;border-color:#f1f5f9;">
                    <div class="iv2-summary-grand" id="sidebar_grand">
                        <span class="lbl">GRAND TOTAL</span>
                        <span class="val" id="sidebar_total">0.00</span>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="iv2-card">
                <div class="iv2-body-pad" style="display:flex;flex-direction:column;gap:10px;">
                    <button type="button" class="iv2-btn green block lg" id="submitInvoiceBtn">
                        <i class="bi bi-check-circle-fill"></i> Submit Invoice
                    </button>
                    <button type="button" class="iv2-btn blue block" id="saveDraftBtn">
                        <i class="bi bi-save"></i> Save Draft
                    </button>
                    <button type="button" class="iv2-btn red block" id="resetInvoiceBtn">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Invoice
                    </button>
                </div>
            </div>

            {{-- Tips --}}
            <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:14px 16px;">
                <div style="font-size:12px;font-weight:800;color:#1d4ed8;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-lightbulb"></i> Quick Tips
                </div>
                <ul style="margin:0;padding-left:16px;font-size:12px;color:#3b82f6;line-height:1.8;">
                    <li>Scan barcode to add products instantly</li>
                    <li>Load eye tests before adding lenses</li>
                    <li>Apply discounts before submitting</li>
                </ul>
            </div>

        </div>{{-- end .iv2-right --}}

    </div>{{-- end .iv2-body --}}

    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    {{-- Modals --}}
    @include('dashboard.pages.invoice-new.modals.doctor_modal')
    @include('dashboard.pages.invoice-new.modals.search_modal')
    @include('dashboard.pages.invoice-new.modals.discount_modal')

    {{-- ══ CAMERA BARCODE MODAL ══ --}}
    <div id="cameraScanModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.75);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;width:480px;max-width:96vw;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.4);">
            <div style="background:#4f46e5;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;">
                <div style="color:#fff;font-size:16px;font-weight:800;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-camera-video" style="font-size:20px;"></i> Camera Scanner
                </div>
                <button id="closeCameraBtn" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:7px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>
            <div style="padding:20px;">
                <div id="camStatus" style="text-align:center;margin-bottom:12px;font-size:13px;color:#4f46e5;font-weight:600;min-height:18px;">Starting camera…</div>
                <div style="position:relative;border-radius:12px;overflow:hidden;background:#111;aspect-ratio:4/3;">
                    <video id="camVideo" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                    <div style="position:absolute;top:50%;left:8%;right:8%;height:2px;background:rgba(79,70,229,.85);transform:translateY(-50%);border-radius:2px;box-shadow:0 0 10px rgba(79,70,229,.8);animation:scanLine 2s ease-in-out infinite;"></div>
                    <div style="position:absolute;top:15%;left:8%;width:28px;height:28px;border-top:3px solid #4f46e5;border-left:3px solid #4f46e5;border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute;top:15%;right:8%;width:28px;height:28px;border-top:3px solid #4f46e5;border-right:3px solid #4f46e5;border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute;bottom:15%;left:8%;width:28px;height:28px;border-bottom:3px solid #4f46e5;border-left:3px solid #4f46e5;border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute;bottom:15%;right:8%;width:28px;height:28px;border-bottom:3px solid #4f46e5;border-right:3px solid #4f46e5;border-radius:0 0 4px 0;"></div>
                </div>
                <div style="margin-top:12px;display:flex;gap:10px;align-items:center;">
                    <label style="font-size:12px;font-weight:600;color:#666;white-space:nowrap;"><i class="bi bi-camera"></i> Camera:</label>
                    <select id="camDeviceSelect" class="iv2-input" style="height:34px;flex:1;"></select>
                    <button id="switchCamBtn" type="button" style="flex-shrink:0;height:34px;padding:0 12px;border-radius:7px;background:#4f46e5;color:#fff;border:none;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap;">
                        <i class="bi bi-arrow-repeat"></i> Switch
                    </button>
                </div>
                <div style="margin-top:10px;display:flex;gap:8px;">
                    <input type="text" id="camManualInput" placeholder="Or type barcode here…"
                           style="flex:1;border:1.5px solid #e2e8f0;border-radius:7px;padding:7px 12px;font-size:14px;">
                    <button id="camManualAddBtn" type="button" style="flex-shrink:0;padding:0 16px;border-radius:7px;background:#16a34a;color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-plus-circle"></i> Add
                    </button>
                </div>
                <div id="lastDetected" style="display:none;margin-top:12px;background:#f0fdf4;border:2px solid #86efac;border-radius:9px;padding:10px 14px;text-align:center;">
                    <div style="font-size:12px;color:#15803d;font-weight:700;margin-bottom:3px;"><i class="bi bi-check-circle-fill"></i> Detected!</div>
                    <div id="lastDetectedCode" style="font-size:18px;font-weight:800;color:#15803d;font-family:monospace;letter-spacing:2px;"></div>
                    <div style="font-size:11px;color:#888;margin-top:3px;">Adding to invoice…</div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- .iv2-page --}}

<script>
/* ── Design 2 collapse helpers ── */
function iv2ToggleLenses(header) {
    var body  = document.getElementById('lensesSection');
    var icon  = document.getElementById('lensesToggleIcon');
    var open  = !body.classList.contains('collapsed');
    body.classList.toggle('collapsed', open);
    icon.classList.toggle('open', !open);
}
function iv2ToggleDiscount(header) {
    var body = document.getElementById('discountSection');
    var icon = document.getElementById('discountToggleIcon');
    var open = !body.classList.contains('collapsed');
    body.classList.toggle('collapsed', open);
    icon.classList.toggle('open', !open);
}

/* iv2UpdateSidebar is defined later in the page scripts after script.main loads */
</script>
@endsection

@section('scripts')
    @include('dashboard.pages.invoice-new.script.main')

    {{-- ══ Design 2 Sidebar Sync — Triple-layer approach ══ --}}
    <script>
    /* ─────────────────────────────────────────────────────────
       CORE UPDATE FUNCTION
       Reads invoiceState directly and writes to sidebar DOM.
    ───────────────────────────────────────────────────────── */
    function iv2UpdateSidebar() {
        if (typeof invoiceState === 'undefined') return;

        var t  = invoiceState.totals   || {};
        var d  = invoiceState.discount || {};

        var subtotalBefore = parseFloat(t.subtotal_before || t.subtotal || 0);
        var grandTotal     = parseFloat(t.grand_total     || 0);
        var qty            = parseInt(t.total_qty         || 0, 10);
        var totalDiscount  = Math.max(0, subtotalBefore - grandTotal);
        var regDisc        = totalDiscount;

        /* Payment total — sum all payment-amount inputs added by the user */
        var paymentTotal = 0;
        document.querySelectorAll('.payment-amount').forEach(function(el) {
            paymentTotal += parseFloat(el.value) || 0;
        });

        function put(id, txt) {
            var el = document.getElementById(id);
            if (el && el.textContent !== txt) el.textContent = txt;
        }

        put('sidebar_subtotal', subtotalBefore.toFixed(2));
        put('sidebar_qty',      String(qty));
        put('sidebar_discount', regDisc      > 0 ? '− ' + regDisc.toFixed(2)      : '—');
        put('sidebar_payer',    paymentTotal > 0 ? paymentTotal.toFixed(2)         : '—');
        put('sidebar_total',    grandTotal.toFixed(2));

        /* Make Payer row highlight when active */
        var payerRow = document.getElementById('sidebar_payer');
        if (payerRow) {
            var parentRow = payerRow.parentElement;
            if (parentRow) {
                parentRow.style.background    = paymentTotal > 0 ? '#fff8f1'    : 'transparent';
                parentRow.style.borderRadius  = paymentTotal > 0 ? '8px'        : '';
            }
        }

        var grandEl = document.getElementById('sidebar_grand');
        if (grandEl) {
            grandEl.style.background = totalDiscount > 0 ? '#16a34a' : '#0f172a';
            grandEl.style.color = '#fff';
            var valEl = document.getElementById('sidebar_total');
            if (valEl) valEl.style.color = '#fff';
        }
    }

    /* ─────────────────────────────────────────────────────────
       LAYER 1 — Wrap renderItemsTable so every render triggers
       the update. renderItemsTable is a global function defined
       in script.main (synchronously included just above).
    ───────────────────────────────────────────────────────── */
    if (typeof renderItemsTable === 'function') {
        var _iv2OrigRender = renderItemsTable;
        renderItemsTable = function () {            /* reassign global */
            _iv2OrigRender.apply(this, arguments);
            iv2UpdateSidebar();
        };
        window.renderItemsTable = renderItemsTable; /* make sure window ref matches */
    }

    /* ─────────────────────────────────────────────────────────
       LAYER 2 — MutationObserver on #itemsTableContainer.
       Fires whenever jQuery's .html() replaces the table DOM.
    ───────────────────────────────────────────────────────── */
    $(document).ready(function () {
        var container = document.getElementById('itemsTableContainer');
        if (container) {
            new MutationObserver(function () {
                setTimeout(iv2UpdateSidebar, 20);
            }).observe(container, { childList: true, subtree: false });
        }

        /* Discount buttons also need a re-sync (they don't always call renderItemsTable) */
        $(document).on('click',
            '#applyDiscountBtn, #removeDiscountBtn, #applyPayerBtn, #removePayerBtn',
            function () { setTimeout(iv2UpdateSidebar, 100); }
        );
    });

    /* ─────────────────────────────────────────────────────────
       LAYER 3 — Polling fallback every 400 ms.
       Guarantees the sidebar is always in sync no matter what.
    ───────────────────────────────────────────────────────── */
    setInterval(iv2UpdateSidebar, 400);
    </script>

    {{-- ZXing barcode decoder --}}
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.20.0/umd/index.min.js"></script>

    <script>
    /* ═══════════════════════════════════════════════════════════
       CAMERA BARCODE SCANNER (Design 2)
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
        var currentStream = null;
        var scanCooldown  = false;
        var devices       = [];

        document.getElementById('openCameraBtn').addEventListener('click', function () {
            modal.style.display = 'flex';
            initScanner();
        });
        document.getElementById('closeCameraBtn').addEventListener('click', closeCamera);
        modal.addEventListener('click', function (e) { if (e.target === modal) closeCamera(); });
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
                if (devices.length === 0) { statusEl.textContent = '⚠ No camera found.'; return; }
                devices.forEach(function (d, i) {
                    var opt = document.createElement('option');
                    opt.value = d.deviceId;
                    opt.textContent = d.label || ('Camera ' + (i + 1));
                    deviceSel.appendChild(opt);
                });
                var backCam = devices.find(function (d) { return /back|rear|environment/i.test(d.label); });
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
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var osc = ctx.createOscillator(); var gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.2);
            } catch(e) {}
            triggerAdd(code);
        }

        function triggerAdd(code) {
            closeCamera();
            var inp = document.getElementById('product_id_input');
            if (inp) {
                inp.value = code; inp.focus();
                if (typeof addProduct === 'function') {
                    addProduct();
                } else {
                    document.getElementById('addProductBtn').click();
                }
            }
        }

        function closeCamera() {
            if (codeReader) { codeReader.reset(); codeReader = null; }
            if (currentStream) { currentStream.getTracks().forEach(function(t){t.stop();}); currentStream = null; }
            modal.style.display = 'none';
        }
    })();
    </script>
@endsection
