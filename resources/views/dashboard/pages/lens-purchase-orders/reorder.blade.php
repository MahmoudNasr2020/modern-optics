@extends('dashboard.layouts.master')
@section('title', 'Re-order Lenses — ' . $po->po_number)
@section('content')

<style>
.reorder-page { padding: 20px; }
.section-card { background:#fff; border-radius:12px; padding:24px; margin-bottom:20px; border:2px solid #e8ecf7; box-shadow:0 2px 10px rgba(0,0,0,.05); }
.section-title { font-size:15px; font-weight:700; color:#2c3e50; margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #e8ecf7; display:flex; align-items:center; gap:8px; }
.orig-item { background:#fff8f8; border:2px solid #f5c6cb; border-radius:8px; padding:12px 16px; margin-bottom:10px; display:flex; align-items:center; gap:12px; }
.orig-item.selected { background:#fff3cd; border-color:#ffc107; }
.lens-badge { background:#e74c3c; color:#fff; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700; }
.side-R { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:700; }
.side-L { background:#f3e5f5; color:#6a1b9a; border:1px solid #ce93d8; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:700; }

/* ── Replacement lens rows ── */
.rep-lens-row { border-radius:10px; margin-bottom:12px; overflow:hidden;
    border:2px solid #b2dfdb; transition:border-color .2s, opacity .2s; }
.rep-lens-row.excluded { border-color:#e2e8f0; opacity:.55; }
.rep-lens-header { display:flex; align-items:center; gap:12px;
    background:#e0f2f1; padding:10px 14px; }
.rep-lens-header.excluded-hd { background:#f1f5f9; }
.rep-lens-body { padding:14px 16px 16px; background:#f9fffd; }
.rep-lens-row.excluded .rep-lens-body { background:#f8fafc; }

/* ── Nice square checkbox ── */
.rep-cb-wrap { display:flex; align-items:center; gap:8px; cursor:pointer; flex-shrink:0; }
.rep-state  { display:none; }
.rep-cb-box {
    width:22px; height:22px; border-radius:6px;
    border:2px solid #94a3b8; background:#fff;
    display:flex; align-items:center; justify-content:center;
    transition:background .2s, border-color .2s;
    flex-shrink:0;
}
.rep-cb-txt { font-size:12px; font-weight:700; color:#94a3b8; transition:color .2s; }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-flask" style="color:#e74c3c;"></i> Re-order Lenses
        <small>Original PO: <strong>{{ $po->po_number }}</strong> — Invoice: {{ $po->invoice->invoice_code }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('dashboard.lens-purchase-orders.index') }}">Lab Orders</a></li>
        <li><a href="{{ route('dashboard.lens-purchase-orders.show', $po->id) }}">{{ $po->po_number }}</a></li>
        <li class="active">Re-order</li>
    </ol>
</section>

<div class="reorder-page">

    <form action="{{ route('dashboard.lens-purchase-orders.reorder.store', $po->id) }}" method="POST" id="reorderForm">
        @csrf
        <div class="row">

            {{-- ═══ LEFT: Original PO items (mark as هالك) ═══ --}}
            <div class="col-md-7">

                {{-- Step 1: Mark Defective --}}
                <div class="section-card" style="border-color:#f5c6cb;">
                    <div class="section-title" style="color:#c0392b;">
                        <span style="background:#e74c3c;color:#fff;width:26px;height:26px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;">1</span>
                        Mark Defective Lenses → هالك
                        <span style="font-size:12px;color:#888;font-weight:400;">(all pre-checked — uncheck any that are NOT defective)</span>
                    </div>

                    @forelse($po->items as $item)
                        @php $dir = trim($item->invoiceItem->direction ?? ''); @endphp
                        <div class="orig-item selected" id="orig-{{ $item->id }}">
                            <input type="checkbox" name="defective[{{ $item->id }}][selected]" value="1"
                                   class="defect-cb" data-id="{{ $item->id }}"
                                   checked
                                   style="width:20px;height:20px;cursor:pointer;accent-color:#e74c3c;"
                                   onchange="toggleDefect({{ $item->id }}, this.checked)">

                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                                    <span class="lens-badge">{{ $item->lens_product_id }}</span>
                                    @if(in_array($dir,['R','L']))
                                        <span class="side-{{ $dir }}">{{ $dir==='R'?'Right Eye':'Left Eye' }}</span>
                                    @endif
                                    <strong>{{ $item->lens->description ?? '—' }}</strong>
                                </div>
                                <div style="font-size:12px;color:#888;">
                                    Received: <strong>{{ $item->received_quantity }}</strong>
                                    @if($item->lens) | {{ $item->lens->brand ?? '' }} | Index {{ $item->lens->index ?? '' }} @endif
                                </div>
                                <input type="hidden" name="defective[{{ $item->id }}][glass_lense_id]" value="{{ $item->glass_lense_id }}">
                                <input type="hidden" name="defective[{{ $item->id }}][invoice_item_id]" value="{{ $item->invoiceItem->id ?? '' }}">
                            </div>

                            <div class="damaged-qty-{{ $item->id }}" style="min-width:110px;">
                                <label style="font-size:11px;color:#888;display:block;">Defective Qty</label>
                                <input type="number"
                                       name="defective[{{ $item->id }}][qty]"
                                       class="form-control form-control-sm"
                                       value="{{ $item->received_quantity }}"
                                       min="1" max="{{ $item->received_quantity }}"
                                       style="width:90px;background:#f8fafc;cursor:not-allowed;"
                                       readonly>
                            </div>
                        </div>
                    @empty
                        <p style="text-align:center;color:#999;padding:20px;">No items in this order.</p>
                    @endforelse
                </div>

                {{-- Step 2: Replacement Lenses — from original order, tick to include --}}
                <div class="section-card" style="border-color:#b2dfdb;">
                    <div class="section-title" style="color:#00a86b;">
                        <span style="background:#00a86b;color:#fff;width:26px;height:26px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;">2</span>
                        Replacement Lenses
                        <span style="font-size:12px;color:#888;font-weight:400;">— all pre-selected · uncheck what you don't want to re-order</span>
                    </div>

                    @forelse($po->items as $item)
                    @php
                        $dir  = trim($item->invoiceItem->direction ?? '');
                        $lens = $item->lens;
                        $cbId = 'repCb_' . $item->id;
                    @endphp
                    <div class="rep-lens-row" id="repRow_{{ $item->id }}">
                        {{-- ── Header with nice checkbox ── --}}
                        <div class="rep-lens-header" id="repHd_{{ $item->id }}">
                            <div class="rep-cb-wrap" onclick="handleRepClick({{ $item->id }})" title="Include in re-order" style="cursor:pointer;">
                                <span class="rep-state" id="{{ $cbId }}" data-checked="1"></span>
                                <span class="rep-cb-box" id="repBox_{{ $item->id }}"></span>
                                <span class="rep-cb-txt" id="repTxt_{{ $item->id }}">Include</span>
                            </div>

                            {{-- Lens name --}}
                            <span style="font-size:13px;font-weight:700;color:#004d40;margin-left:6px;flex:1;">
                                <i class="bi bi-eyeglasses"></i>
                                {{ $lens->description ?? $item->lens_product_id }}
                            </span>

                            @if(in_array($dir,['R','L']))
                                <span class="side-{{ $dir }}">{{ $dir==='R'?'Right (R)':'Left (L)' }}</span>
                            @endif

                            <span style="font-size:11px;background:#e0f7fa;color:#006064;padding:2px 8px;border-radius:8px;white-space:nowrap;">
                                ID: {{ $item->lens_product_id }}
                            </span>
                        </div>

                        {{-- ── Body: always in DOM but inputs disabled when excluded ── --}}
                        <div class="rep-lens-body" id="repBody_{{ $item->id }}">
                            <input type="hidden" name="new_lenses[pre_{{ $item->id }}][glass_lense_id]"
                                   value="{{ $item->glass_lense_id }}"
                                   class="rep-input rep-input-{{ $item->id }}">
                            <input type="hidden" name="new_lenses[pre_{{ $item->id }}][product_id]"
                                   value="{{ $lens->lens_product_id ?? $item->lens_product_id }}"
                                   class="rep-input rep-input-{{ $item->id }}">

                            @if($lens)
                            <div style="background:#f0fff8;border:1px solid #b2dfdb;border-radius:6px;
                                        padding:7px 12px;margin-bottom:10px;font-size:12px;color:#475569;">
                                @if($lens->brand) <strong>{{ $lens->brand }}</strong> @endif
                                @if($lens->index) &nbsp;·&nbsp; Index {{ $lens->index }} @endif
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-4">
                                    <label style="font-size:11px;color:#64748b;font-weight:700;">Side</label>
                                    <select name="new_lenses[pre_{{ $item->id }}][side]"
                                            class="form-control form-control-sm rep-input rep-input-{{ $item->id }}">
                                        <option value="">Any</option>
                                        <option value="R" {{ $dir==='R'?'selected':'' }}>Right (R)</option>
                                        <option value="L" {{ $dir==='L'?'selected':'' }}>Left (L)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size:11px;color:#64748b;font-weight:700;">Quantity</label>
                                    <input type="number" name="new_lenses[pre_{{ $item->id }}][quantity]"
                                           class="form-control form-control-sm rep-input rep-input-{{ $item->id }}"
                                           value="{{ $item->received_quantity ?? 1 }}" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size:11px;color:#64748b;font-weight:700;">Notes</label>
                                    <input type="text" name="new_lenses[pre_{{ $item->id }}][notes]"
                                           class="form-control form-control-sm rep-input rep-input-{{ $item->id }}"
                                           value="{{ $item->notes ?? '' }}" placeholder="Power, specs...">
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:25px;color:#aaa;border:2px dashed #ddd;border-radius:8px;">
                        <i class="bi bi-eyeglasses" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                        No lenses found in original order.
                    </div>
                    @endforelse
                </div>

            </div>

            {{-- ═══ RIGHT: Lab selection & Submit ═══ --}}
            <div class="col-md-5">

                {{-- PO Info --}}
                <div style="background:#f8f9ff;border:2px solid #e8ecf7;border-radius:10px;padding:14px;margin-bottom:16px;font-size:13px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="color:#888;">Original PO:</span>
                        <strong style="color:#e74c3c;">{{ $po->po_number }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                        <span style="color:#888;">Invoice:</span>
                        <strong>{{ $po->invoice->invoice_code }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#888;">Customer:</span>
                        <strong>{{ $po->invoice->customer->english_name ?? '—' }}</strong>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-title" style="color:#3498db;">
                        <span style="background:#3498db;color:#fff;width:26px;height:26px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;">3</span>
                        Lab / Supplier
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Select Lab</label>
                        <select name="lab_id" id="labSelect" class="form-control">
                            <option value="">-- Or type manually --</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" data-name="{{ $lab->name }}"
                                    {{ $lab->id == $po->lab_id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Lab Name <span class="text-danger">*</span></label>
                        <input type="text" name="lab_name" id="labName" class="form-control" required
                               value="{{ $po->lab_name }}">
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Defect reason, instructions...">Re-order for defective lenses — Original PO: {{ $po->po_number }}</textarea>
                    </div>

                    <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:12px;font-size:12px;color:#856404;margin-bottom:14px;">
                        <strong>What will happen:</strong><br>
                        ✓ Checked lenses → <strong>هالك (Damaged OUT)</strong><br>
                        ✓ New lenses added → <strong>New Lab Order created</strong>
                    </div>

                    <button type="button" onclick="submitReorder()"
                            class="btn btn-danger btn-block" style="padding:12px;font-size:15px;font-weight:700;border-radius:8px;">
                        <i class="fa fa-refresh"></i> Create Re-order
                    </button>
                    <a href="{{ route('dashboard.lens-purchase-orders.show', $po->id) }}"
                       class="btn btn-default btn-block" style="margin-top:8px;">
                        <i class="bi bi-arrow-left"></i> Back to Order
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Select2 --}}
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}">
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

<script>
/* ── Update the visual box (JS-driven — bypasses iCheck CSS issues) ── */
function setRepBox(id, checked) {
    var box = document.getElementById('repBox_' + id);
    var txt = document.getElementById('repTxt_' + id);
    if (!box) return;
    if (checked) {
        box.style.background   = '#16a34a';
        box.style.borderColor  = '#16a34a';
        box.innerHTML = '<span style="color:#fff;font-size:13px;font-weight:900;line-height:1;">✓</span>';
        if (txt) { txt.textContent = 'Include'; txt.style.color = '#15803d'; }
    } else {
        box.style.background   = '#fff';
        box.style.borderColor  = '#94a3b8';
        box.innerHTML = '';
        if (txt) { txt.textContent = 'Exclude'; txt.style.color = '#94a3b8'; }
    }
}

/* ── Handle click — reads/toggles data-checked on a <span> (iCheck ignores non-inputs) ── */
function handleRepClick(id) {
    var state   = document.getElementById('repCb_' + id);
    var checked = state.dataset.checked !== '1';   /* toggle */
    state.dataset.checked = checked ? '1' : '0';
    setRepBox(id, checked);
    toggleRepLens(id, checked);
}

/* ── Toggle row state (include / exclude from submission) ── */
function toggleRepLens(id, checked) {
    var row    = document.getElementById('repRow_' + id);
    var hd     = document.getElementById('repHd_' + id);
    var inputs = document.querySelectorAll('.rep-input-' + id);

    if (checked) {
        row.classList.remove('excluded');
        hd.classList.remove('excluded-hd');
        inputs.forEach(function(el) {
            el.disabled = false;
            if (el.dataset.disabledName) { el.name = el.dataset.disabledName; delete el.dataset.disabledName; }
        });
    } else {
        row.classList.add('excluded');
        hd.classList.add('excluded-hd');
        inputs.forEach(function(el) {
            if (el.name) { el.dataset.disabledName = el.name; el.name = ''; }
            el.disabled = true;
        });
    }
}

/* ── Toggle defective row (Step 1) ── */
function toggleDefect(id, checked) {
    var row = document.getElementById('orig-' + id);
    if (checked) {
        row.classList.add('selected');
    } else {
        row.classList.remove('selected');
    }
}

/* ── Submit confirmation ── */
function submitReorder() {
    var included = document.querySelectorAll('.rep-lens-row:not(.excluded)').length;
    var defective = document.querySelectorAll('.defect-cb:checked').length;

    Swal.fire({
        title: 'Confirm Re-order',
        html: `
            <div style="text-align:left;margin-top:8px;font-size:14px;line-height:1.7;">
                <div>🔴 Lenses marked as <strong style="color:#e74c3c;">هالك</strong>: <strong>${defective}</strong></div>
                <div>🟢 Lenses to <strong style="color:#16a34a;">Re-order</strong>: <strong>${included}</strong></div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="fa fa-refresh"></i> Yes, Create Re-order',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById('reorderForm').submit();
        }
    });
}

document.getElementById('labSelect').addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    if (opt.value) document.getElementById('labName').value = opt.getAttribute('data-name');
});

/* ── Set initial visual state — runs after all scripts (including iCheck) finish ── */
setTimeout(function() {
    document.querySelectorAll('.rep-state').forEach(function(el) {
        var id = el.id.replace('repCb_', '');
        setRepBox(id, el.dataset.checked === '1');
    });
}, 0);
</script>

@endsection
