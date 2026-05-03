@extends('dashboard.layouts.master')
@section('title', 'Create Lab Order')
@section('content')

<style>
/* ── Page Layout ── */
.lpo-create { padding: 24px; }

/* ── Top Hero Banner ── */
.lpo-hero {
    background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 24px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(26,35,126,.35);
}
.lpo-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -40px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
}
.lpo-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: 20%;
    width: 300px; height: 300px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.lpo-hero-title {
    font-size: 26px;
    font-weight: 800;
    margin: 0 0 6px;
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 12px;
}
.lpo-hero-title .icon-wrap {
    background: rgba(255,255,255,.15);
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    backdrop-filter: blur(4px);
}
.lpo-hero-sub { font-size: 14px; opacity: .75; margin: 0 0 20px; position: relative; z-index: 1; }
.lpo-hero-meta {
    display: flex; flex-wrap: wrap; gap: 24px;
    position: relative; z-index: 1;
}
.lpo-hero-meta-item { display: flex; flex-direction: column; gap: 3px; }
.lpo-hero-meta-item .mk { font-size: 11px; opacity: .65; text-transform: uppercase; letter-spacing: .8px; font-weight: 600; }
.lpo-hero-meta-item .mv { font-size: 15px; font-weight: 700; }
.lpo-hero-meta-divider { width: 1px; background: rgba(255,255,255,.2); align-self: stretch; }

/* ── Section Cards ── */
.lpo-card {
    background: #fff;
    border-radius: 14px;
    border: 2px solid #e8ecf7;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    overflow: hidden;
    margin-bottom: 20px;
}
.lpo-card-header {
    padding: 18px 22px;
    border-bottom: 2px solid #f0f2f8;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9ff;
}
.lpo-card-header-title {
    font-size: 15px; font-weight: 700; color: #2c3e50;
    display: flex; align-items: center; gap: 8px;
}
.lpo-card-header-title i { color: #3949ab; font-size: 18px; }
.lpo-card-body { padding: 22px; }

/* ── Select All Bar ── */
.select-all-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: linear-gradient(135deg, #e8eaf6, #f3f4fd);
    border-radius: 10px;
    border: 2px solid #c5cae9;
    margin-bottom: 16px;
    cursor: pointer;
    transition: all .2s;
    user-select: none;
}
.select-all-bar:hover { border-color: #3949ab; background: linear-gradient(135deg, #e3e5f7, #eef0fc); }
.select-all-bar.all-checked { background: linear-gradient(135deg, #e8f5e9, #f1f8e9); border-color: #a5d6a7; }
.select-all-bar.all-checked:hover { border-color: #43a047; }

/* ── Visual Box Checkbox (iCheck-safe — uses <div> not <input>) ── */
.vbox {
    width: 24px; height: 24px;
    border-radius: 6px;
    border: 2px solid #9fa8da;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .18s, border-color .18s, transform .12s;
    flex-shrink: 0;
    user-select: none;
}
.vbox:hover { transform: scale(1.1); }
.vbox .vck { display: none; color: #fff; font-size: 14px; font-weight: 900; line-height: 1; }
.vbox.on  { background: #3949ab; border-color: #3949ab; }
.vbox.on  .vck { display: block; }
/* item-level: green */
.vbox.vbox-item              { border-color: #26a69a; }
.vbox.vbox-item.on           { background: #26a69a; border-color: #26a69a; }
/* indeterminate state for select-all */
.vbox.vbox-partial           { background: #7986cb; border-color: #5c6bc0; }
.vbox.vbox-partial .vck      { display: block; font-size: 10px; }

/* ── Lens Item Rows ── */
.lens-item-row {
    border: 2px solid #e0f2f1;
    border-radius: 12px;
    padding: 0;
    margin-bottom: 12px;
    overflow: hidden;
    transition: all .25s;
    background: #fff;
}
.lens-item-row:hover { border-color: #26a69a; box-shadow: 0 4px 14px rgba(38,166,154,.12); }
.lens-item-row.item-unchecked {
    border-color: #e0e0e0;
    opacity: .55;
    background: #fafafa;
}
.lens-item-row.item-unchecked:hover { opacity: .7; }

.item-row-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: linear-gradient(135deg, #e0f2f1, #f1faf9);
    border-bottom: 2px solid #b2dfdb;
    flex-wrap: wrap;
}
.lens-item-row.item-unchecked .item-row-header {
    background: #f5f5f5;
    border-bottom-color: #e0e0e0;
}
.item-badge {
    background: linear-gradient(135deg, #00897b, #00695c);
    color: #fff;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 12px; font-weight: 700;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
    white-space: nowrap;
}
.item-row-name { font-size: 14px; font-weight: 700; color: #1a237e; }
.item-row-meta { font-size: 12px; color: #888; }

.item-row-fields { padding: 16px 18px; }
.item-field-label { font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.item-field-input {
    border: 2px solid #e0e6ed !important;
    border-radius: 8px !important;
    transition: all .2s;
    height: 40px;
}
.item-field-input:focus {
    border-color: #26a69a !important;
    box-shadow: 0 0 0 3px rgba(38,166,154,.12) !important;
}
.lens-item-row.item-unchecked .item-field-input { background: #f5f5f5; }

/* ── Sidebar Cards ── */
.sidebar-card {
    background: #fff;
    border-radius: 14px;
    border: 2px solid #e8ecf7;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    overflow: hidden;
    margin-bottom: 20px;
}
.sidebar-card-header {
    padding: 16px 20px;
    display: flex; align-items: center; gap: 10px;
    border-bottom: 2px solid #f0f2f8;
}
.sidebar-card-header i { font-size: 18px; }
.sidebar-card-header span { font-size: 14px; font-weight: 700; color: #2c3e50; }
.sidebar-card-body { padding: 20px; }

/* Lab card header */
.lab-header { background: linear-gradient(135deg, #e8eaf6, #f3f4fd); }
.lab-header i { color: #3949ab; }

/* Info notice */
.info-notice {
    background: linear-gradient(135deg, #fff8e1, #fffde7);
    border: 2px solid #ffe082;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 13px;
    color: #795548;
}
.info-notice .info-row { display: flex; justify-content: space-between; padding: 3px 0; }
.info-notice .info-key { font-weight: 600; color: #5d4037; }

/* Existing POs warning */
.existing-po-card { border-color: #ffe082 !important; }
.existing-po-header { background: linear-gradient(135deg, #fff8e1, #fffde7); }
.existing-po-header i { color: #f9a825; }

/* ── Form controls in sidebar ── */
.sidebar-card-body .form-group { margin-bottom: 16px; }
.sidebar-card-body label {
    font-size: 12px; font-weight: 700; color: #555;
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 6px; display: block;
}
.sidebar-card-body .form-control {
    border: 2px solid #c5cae9;
    border-radius: 8px;
    height: 44px;
    font-size: 14px;
    color: #2c3e50;
    transition: border-color .2s, box-shadow .2s;
    background: #fff;
    padding: 8px 12px;
}
.sidebar-card-body .form-control:focus {
    border-color: #3949ab;
    box-shadow: 0 0 0 3px rgba(57,73,171,.12);
    outline: none;
}
.sidebar-card-body select.form-control {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233949ab' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
    cursor: pointer;
}
.sidebar-card-body select.form-control:hover {
    border-color: #9fa8da;
}
.sidebar-card-body textarea.form-control {
    height: auto;
    resize: vertical;
    min-height: 80px;
}

/* ── Action Buttons ── */
.btn-create-order {
    background: linear-gradient(135deg, #2e7d32, #388e3c);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 14px 20px;
    font-size: 15px;
    font-weight: 700;
    width: 100%;
    transition: all .25s;
    box-shadow: 0 4px 15px rgba(46,125,50,.35);
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-create-order:hover {
    background: linear-gradient(135deg, #1b5e20, #2e7d32);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(46,125,50,.45);
    color: #fff;
}
.btn-back {
    background: #f5f5f5;
    color: #555;
    border: 2px solid #e0e6ed;
    border-radius: 10px;
    padding: 11px 20px;
    font-size: 14px;
    font-weight: 600;
    width: 100%;
    margin-top: 10px;
    transition: all .25s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none;
}
.btn-back:hover {
    background: #eeeeee;
    border-color: #bdbdbd;
    color: #333;
    text-decoration: none;
}

/* ── Item counter badge ── */
.items-count-badge {
    background: linear-gradient(135deg, #3949ab, #283593);
    color: #fff;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(57,73,171,.3);
}

/* ── Selected counter ── */
#selectedCounter {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
#selectedCounter span { color: #27ae60; font-weight: 700; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-flask"></i> Create Lab Order
        <small>Invoice #{{ $invoice->invoice_code }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="{{ route('dashboard.lens-purchase-orders.index') }}">Lab Orders</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<div class="lpo-create">
<form action="{{ route('dashboard.lens-purchase-orders.store') }}" method="POST" id="poForm">
@csrf
<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

    {{-- ── Hero Banner ── --}}
    <div class="lpo-hero">
        <div class="lpo-hero-title">
            <div class="icon-wrap"><i class="bi bi-flask"></i></div>
            New Lab Order
        </div>
        <div class="lpo-hero-sub">Preparing lens purchase order for the invoice below</div>
        <div class="lpo-hero-meta">
            <div class="lpo-hero-meta-item">
                <span class="mk">Invoice #</span>
                <span class="mv">{{ $invoice->invoice_code }}</span>
            </div>
            <div class="lpo-hero-meta-divider"></div>
            <div class="lpo-hero-meta-item">
                <span class="mk">Customer</span>
                <span class="mv">{{ optional($invoice->customer)->english_name ?? '—' }}</span>
            </div>
            <div class="lpo-hero-meta-divider"></div>
            <div class="lpo-hero-meta-item">
                <span class="mk">Branch</span>
                <span class="mv">{{ optional($invoice->branch)->name ?? '—' }}</span>
            </div>
            <div class="lpo-hero-meta-divider"></div>
            <div class="lpo-hero-meta-item">
                <span class="mk">Invoice Date</span>
                <span class="mv">{{ $invoice->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="lpo-hero-meta-divider"></div>
            <div class="lpo-hero-meta-item">
                <span class="mk">Lens Items</span>
                <span class="mv">{{ $lensItems->count() }} item(s)</span>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- ── LEFT: Lens Items ── --}}
        <div class="col-md-8">
            <div class="lpo-card">
                <div class="lpo-card-header">
                    <div class="lpo-card-header-title">
                        <i class="bi bi-eyeglasses"></i>
                        Lens Items to Order
                        <span class="items-count-badge">{{ $lensItems->count() }}</span>
                    </div>
                    <span id="selectedCounter">
                        Selected: <span id="selectedCount">{{ $lensItems->count() }}</span> / {{ $lensItems->count() }}
                    </span>
                </div>
                <div class="lpo-card-body">

                    {{-- Select All Bar — uses <div> so iCheck never touches it --}}
                    <div class="select-all-bar all-checked" id="selectAllBar" onclick="handleSelectAll()">
                        <div class="vbox on" id="vbox-all" title="Select / Deselect all">
                            <span class="vck">✓</span>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1a237e;" id="selectAllLabel">
                                ✓ All items selected — click to deselect all
                            </div>
                            <div style="font-size:12px;color:#5c6bc0;">
                                Click individual items below to include or exclude them
                            </div>
                        </div>
                    </div>

                    {{-- Lens Item Rows --}}
                    @foreach($lensItems as $index => $item)
                    <div class="lens-item-row" id="create-row-{{ $item->id }}">
                        <input type="hidden"
                               name="items[{{ $index }}][invoice_item_id]"
                               value="{{ $item->id }}"
                               class="item-hidden-{{ $item->id }}">

                        {{-- Row Header — vbox is a <div>, iCheck ignores it --}}
                        <div class="item-row-header">
                            <div class="vbox vbox-item on"
                                 id="vbox-{{ $item->id }}"
                                 onclick="handleItemClick({{ $item->id }})"
                                 title="Include / Exclude this item">
                                <span class="vck">✓</span>
                            </div>
                            <span class="item-badge">{{ $item->product_id }}</span>
                            <span class="item-row-name">{{ $item->lensModel->description ?? '—' }}</span>
                            @if($item->lensModel)
                            <span class="item-row-meta">
                                Brand: <strong>{{ $item->lensModel->brand ?? '—' }}</strong>
                                &nbsp;|&nbsp; Index: <strong>{{ $item->lensModel->index ?? '—' }}</strong>
                                &nbsp;|&nbsp; Type: <strong>{{ $item->lensModel->lense_type ?? '—' }}</strong>
                            </span>
                            @endif
                            @if($item->direction)
                            <span style="background:{{ $item->direction === 'R' ? '#e3f2fd' : '#f3e5f5' }};
                                         color:{{ $item->direction === 'R' ? '#1565c0' : '#6a1b9a' }};
                                         padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700;
                                         border:1px solid {{ $item->direction === 'R' ? '#90caf9' : '#ce93d8' }};">
                                {{ $item->direction === 'R' ? '👁 Right' : '👁 Left' }}
                            </span>
                            @endif
                        </div>

                        {{-- Row Fields --}}
                        <div class="item-row-fields">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="item-field-label">Quantity</div>
                                    <input type="number"
                                           name="items[{{ $index }}][quantity]"
                                           class="form-control item-field-input"
                                           value="{{ $item->quantity }}" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <div class="item-field-label">Unit Cost</div>
                                    <input type="number"
                                           name="items[{{ $index }}][unit_cost]"
                                           class="form-control item-field-input"
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6">
                                    <div class="item-field-label">Notes / Specs</div>
                                    <input type="text"
                                           name="items[{{ $index }}][notes]"
                                           class="form-control item-field-input"
                                           placeholder="Power, coating, tint…">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- ── RIGHT: Sidebar ── --}}
        <div class="col-md-4">

            {{-- Lab Selection --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header lab-header">
                    <i class="bi bi-building" style="color:#3949ab;font-size:18px;"></i>
                    <span>Lab / Supplier</span>
                </div>
                <div class="sidebar-card-body">
                    <div class="form-group">
                        <label>Select Existing Lab</label>
                        <select name="lab_id" id="labSelect" class="form-control">
                            <option value="">— Or type manually below —</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" data-name="{{ $lab->name }}">
                                    {{ $lab->name }}
                                    @if($lab->phone) ({{ $lab->phone }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lab Name <span class="text-danger">*</span></label>
                        <input type="text" name="lab_name" id="labName" class="form-control" required
                               placeholder="e.g. Essilor Lab…">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Order Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  style="height:auto;"
                                  placeholder="Special instructions, delivery date…"></textarea>
                    </div>
                </div>
            </div>

            {{-- Order Info Notice --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header" style="background:#f8f9ff;">
                    <i class="bi bi-info-circle" style="color:#3949ab;font-size:18px;"></i>
                    <span>Order Information</span>
                </div>
                <div class="sidebar-card-body">
                    <div class="info-notice">
                        <div class="info-row">
                            <span class="info-key"><i class="bi bi-person-fill"></i> Ordered By</span>
                            <span>{{ optional(auth()->user())->name ?? optional(auth()->user())->first_name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key"><i class="bi bi-building"></i> Branch</span>
                            <span>{{ optional($invoice->branch)->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key"><i class="bi bi-calendar3"></i> Date</span>
                            <span>{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Existing POs for this invoice --}}
            @if($existingPos->count() > 0)
            <div class="sidebar-card existing-po-card">
                <div class="sidebar-card-header existing-po-header">
                    <i class="bi bi-exclamation-triangle" style="color:#f9a825;font-size:18px;"></i>
                    <span style="color:#e65100;">Existing Orders ({{ $existingPos->count() }})</span>
                </div>
                <div class="sidebar-card-body" style="padding:14px 18px;">
                    @foreach($existingPos as $ep)
                    <div style="border:1px solid #ffe082;border-radius:8px;padding:10px 14px;margin-bottom:8px;background:#fffde7;">
                        <div style="font-weight:700;font-size:13px;color:#1a237e;">{{ $ep->po_number }}</div>
                        <div style="font-size:12px;color:#666;margin-top:2px;">
                            {{ $ep->lab_name }}
                            &nbsp;·&nbsp;
                            <span class="status-{{ $ep->status }}" style="font-size:11px;padding:2px 8px;border-radius:8px;">
                                {{ strtoupper($ep->status) }}
                            </span>
                            &nbsp;·&nbsp; {{ $ep->items->count() }} item(s)
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Submit --}}
            <div>
                <button type="submit" class="btn-create-order">
                    <i class="bi bi-save2-fill"></i> Create Lab Order
                </button>
                <a href="{{ route('dashboard.invoice.pending') }}" class="btn-back">
                    <i class="bi bi-arrow-left-circle"></i> Back to Pending Invoices
                </a>
            </div>

        </div>
    </div>

</form>
</div>

<style>
.status-pending   { background:#e65100;color:#fff;padding:2px 8px;border-radius:8px; }
.status-sent      { background:#1565c0;color:#fff;padding:2px 8px;border-radius:8px; }
.status-received  { background:#2e7d32;color:#fff;padding:2px 8px;border-radius:8px; }
.status-cancelled { background:#b71c1c;color:#fff;padding:2px 8px;border-radius:8px; }
</style>

<script>
// ── Item IDs list (built from Blade, no DOM scanning needed) ───────
var ALL_ITEM_IDS = [{{ $lensItems->pluck('id')->implode(',') }}];

// ── Lab dropdown auto-fill ─────────────────────────────────────────
document.getElementById('labSelect').addEventListener('change', function () {
    var opt = this.options[this.selectedIndex];
    if (opt.value) document.getElementById('labName').value = opt.getAttribute('data-name');
});

// ── Read state of a vbox div ───────────────────────────────────────
function vIsOn(id) {
    var el = document.getElementById('vbox-' + id);
    return el ? el.classList.contains('on') : false;
}

// ── Set visual state of a vbox div ────────────────────────────────
function vSet(id, on) {
    var box = document.getElementById('vbox-' + id);
    if (!box) return;
    if (on) {
        box.classList.add('on');
        box.classList.remove('vbox-partial');
    } else {
        box.classList.remove('on');
        box.classList.remove('vbox-partial');
    }
}

// ── Toggle a single item row (enable/disable inputs) ──────────────
function toggleRow(id, on) {
    var row    = document.getElementById('create-row-' + id);
    var inputs = row.querySelectorAll('input[type=number], input[type=text], textarea');
    var hidden = row.querySelector('.item-hidden-' + id);
    if (on) {
        row.classList.remove('item-unchecked');
        inputs.forEach(function (el) { el.disabled = false; });
        if (hidden) hidden.disabled = false;
    } else {
        row.classList.add('item-unchecked');
        inputs.forEach(function (el) { el.disabled = true; });
        if (hidden) hidden.disabled = true;
    }
}

// ── Handle click on an individual item vbox ───────────────────────
function handleItemClick(id) {
    var newState = !vIsOn(id);   // toggle
    vSet(id, newState);
    toggleRow(id, newState);
    refreshSelectAll();
}

// ── Handle click on the Select All bar ───────────────────────────
function handleSelectAll() {
    var checkedCount = ALL_ITEM_IDS.filter(function (id) { return vIsOn(id); }).length;
    var newState = checkedCount < ALL_ITEM_IDS.length; // any off → select all; all on → deselect all
    ALL_ITEM_IDS.forEach(function (id) {
        vSet(id, newState);
        toggleRow(id, newState);
    });
    refreshSelectAll();
}

// ── Sync Select All bar appearance ────────────────────────────────
function refreshSelectAll() {
    var checkedCount = ALL_ITEM_IDS.filter(function (id) { return vIsOn(id); }).length;
    var total        = ALL_ITEM_IDS.length;
    var bar          = document.getElementById('selectAllBar');
    var label        = document.getElementById('selectAllLabel');
    var counter      = document.getElementById('selectedCount');
    var allBox       = document.getElementById('vbox-all');

    counter.textContent = checkedCount;

    if (checkedCount === total) {
        allBox.classList.add('on');
        allBox.classList.remove('vbox-partial');
        bar.classList.add('all-checked');
        label.textContent = '✓ All items selected — click to deselect all';
    } else if (checkedCount === 0) {
        allBox.classList.remove('on', 'vbox-partial');
        bar.classList.remove('all-checked');
        label.textContent = 'No items selected — click to select all';
    } else {
        allBox.classList.remove('on');
        allBox.classList.add('vbox-partial');
        bar.classList.remove('all-checked');
        label.textContent = checkedCount + ' of ' + total + ' items selected';
    }
}

// ── Re-index selected items before submit ─────────────────────────
document.getElementById('poForm').addEventListener('submit', function (e) {
    var selected = ALL_ITEM_IDS.filter(function (id) { return vIsOn(id); });
    if (selected.length === 0) {
        e.preventDefault();
        alert('Please select at least one lens item to order.');
        return false;
    }
    var idx = 0;
    ALL_ITEM_IDS.forEach(function (id) {
        if (!vIsOn(id)) return;
        var row = document.getElementById('create-row-' + id);
        row.querySelectorAll('[name^="items["]').forEach(function (el) {
            el.name = el.name.replace(/items\[\d+\]/, 'items[' + idx + ']');
        });
        idx++;
    });
});
</script>
@endsection
