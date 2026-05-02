@extends('dashboard.layouts.master')
@section('title', 'New Transfer Request')
@section('content')

<style>
/* ── Page ── */
.ctr-page { padding: 20px; }

/* ── Header card ── */
.ctr-header {
    background: linear-gradient(135deg,#1a237e,#283593,#1565c0);
    border-radius:14px; padding:22px 28px; color:#fff;
    margin-bottom:20px; box-shadow:0 6px 28px rgba(21,101,192,.3);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
}
.ctr-header h2 { margin:0; font-size:20px; font-weight:800; }
.ctr-header .sub { font-size:12px; opacity:.75; margin-top:3px; }

/* ── Section cards ── */
.ctr-card {
    background:#fff; border-radius:12px; border:1.5px solid #e8ecf7;
    box-shadow:0 2px 12px rgba(0,0,0,.05); margin-bottom:16px; overflow:hidden;
}
.ctr-card-head {
    padding:14px 20px; background:#f8f9ff; border-bottom:1.5px solid #e8ecf7;
    display:flex; align-items:center; gap:10px;
    font-size:14px; font-weight:700; color:#2c3e50;
}
.ctr-card-head i { font-size:17px; }
.ctr-card-body { padding:20px; }

/* ── Branch pill ── */
.branch-pill {
    background:#e3f2fd; border:1.5px solid #90caf9; border-radius:8px;
    padding:11px 15px; display:flex; align-items:center; gap:8px;
    font-weight:700; color:#1565c0; font-size:14px;
}
.store-badge {
    background:linear-gradient(135deg,#3498db,#2980b9); color:#fff;
    padding:2px 9px; border-radius:20px; font-size:11px; font-weight:700;
}

/* ── Products table ── */
.pt-table { width:100%; border-collapse:collapse; }
.pt-table thead tr { background:linear-gradient(135deg,#37474f,#455a64); }
.pt-table thead th {
    color:#fff; font-size:11px; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; padding:11px 12px; border:none; white-space:nowrap;
}
.pt-table tbody tr { border-bottom:1px solid #f0f2f5; transition:background .12s; }
.pt-table tbody tr:hover { background:#f8f9ff; }
.pt-table tbody td { padding:10px 10px; vertical-align:middle; }
.pt-table tbody tr td:first-child { padding-left:14px; }

/* ── Search box inside table ── */
.prod-search-wrap { position:relative; }
.prod-search-input {
    width:100%; box-sizing:border-box; border:1.5px solid #e0e6ed;
    border-radius:8px; padding:8px 32px 8px 12px; font-size:13px;
    outline:none; transition:border-color .15s;
}
.prod-search-input:focus { border-color:#3949ab; box-shadow:0 0 0 3px rgba(57,73,171,.1); }
.prod-clear-btn {
    position:absolute; right:8px; top:50%; transform:translateY(-50%);
    background:none; border:none; color:#aaa; cursor:pointer; font-size:16px;
    padding:0; display:none;
}
.prod-dropdown {
    position:fixed; /* fixed = escapes overflow:hidden on parent cards */
    background:#fff; border:1.5px solid #e0e6ed; border-radius:8px;
    box-shadow:0 6px 28px rgba(0,0,0,.16); z-index:99999;
    max-height:260px; overflow-y:auto; overflow-x:hidden; display:none;
    min-width:420px; /* wide enough to show code + name + stock in one line */
}
.prod-option {
    padding:9px 14px; cursor:pointer; font-size:13px;
    border-bottom:1px solid #f5f5f5; transition:background .1s;
    display:flex; align-items:center; gap:10px;
    white-space:nowrap; /* keep everything on one line */
}
.prod-option:last-child { border-bottom:none; }
.prod-option:hover { background:#f0f4ff; }
.prod-option .po-code  { font-size:11px; background:#e8eaf6; color:#3949ab; padding:2px 8px; border-radius:10px; font-weight:700; flex-shrink:0; }
.prod-option .po-name  { flex:1; color:#333; font-weight:500; overflow:hidden; text-overflow:ellipsis; }
.prod-option .po-stock { font-size:11px; font-weight:700; flex-shrink:0; }
.po-stock-ok   { color:#27ae60; }
.po-stock-low  { color:#f39c12; }
.po-stock-zero { color:#e74c3c; }

/* ── Selected product display ── */
.prod-selected {
    display:none; background:#f0f4ff; border-radius:8px;
    padding:8px 12px; font-size:13px;
}
.prod-selected .ps-code { font-size:11px; background:#e8eaf6; color:#3949ab; padding:1px 8px; border-radius:10px; font-weight:700; }
.prod-selected .ps-name { font-weight:600; color:#2c3e50; margin-left:6px; }
.prod-selected .ps-stock { font-size:11px; color:#888; margin-left:4px; }
.prod-selected .ps-change { font-size:11px; color:#3949ab; cursor:pointer; margin-left:6px; text-decoration:underline; }

/* ── Quantity input ── */
.qty-input {
    width:80px; border:1.5px solid #e0e6ed; border-radius:8px;
    padding:8px 10px; font-size:16px; font-weight:700; text-align:center;
    outline:none; transition:border-color .15s;
}
.qty-input:focus { border-color:#3949ab; box-shadow:0 0 0 3px rgba(57,73,171,.1); }

/* ── Notes input ── */
.note-input {
    width:100%; box-sizing:border-box; border:1.5px solid #e0e6ed;
    border-radius:8px; padding:7px 10px; font-size:12px; outline:none;
    transition:border-color .15s;
}
.note-input:focus { border-color:#3949ab; }

/* ── Remove row btn ── */
.btn-rm {
    background:#fce4ec; color:#c62828; border:1.5px solid #ef9a9a;
    border-radius:7px; padding:5px 10px; cursor:pointer;
    font-size:14px; transition:all .15s; line-height:1;
}
.btn-rm:hover { background:#e53935; color:#fff; border-color:#e53935; }
.btn-rm:disabled { opacity:.3; cursor:default; }

/* ── Add row button ── */
.btn-add-row {
    display:inline-flex; align-items:center; gap:7px;
    background:#e3f2fd; color:#1565c0; border:1.5px solid #90caf9;
    border-radius:8px; padding:9px 18px; font-size:13px; font-weight:700;
    cursor:pointer; transition:all .18s; margin-top:12px;
}
.btn-add-row:hover { background:#1565c0; color:#fff; border-color:#1565c0; }

/* ── Submit ── */
.btn-submit {
    width:100%; padding:15px; border:none; border-radius:10px; cursor:pointer;
    background:linear-gradient(135deg,#27ae60,#229954); color:#fff;
    font-size:16px; font-weight:800; display:flex; align-items:center;
    justify-content:center; gap:8px;
    box-shadow:0 4px 16px rgba(39,174,96,.35); transition:all .2s;
}
.btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(39,174,96,.45); }
.btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }

/* ── Row count badge ── */
.row-count-badge {
    display:inline-block; background:#3949ab; color:#fff;
    padding:2px 10px; border-radius:20px; font-size:12px; font-weight:700;
    margin-left:8px;
}

/* ── Validation error ── */
.row-error { color:#e53935; font-size:11px; margin-top:3px; display:block; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-box-arrow-in-down" style="color:#1565c0;"></i>
        New Transfer Request
        <a href="{{ route('dashboard.stock-transfers.index') }}"
           style="margin-left:10px;font-size:13px;background:#e8ecf7;color:#555;padding:6px 14px;border-radius:8px;text-decoration:none;vertical-align:middle;">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </h1>
</section>

<div class="ctr-page">

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius:10px;border-left:5px solid #e74c3c;">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dashboard.stock-transfers.store-multi') }}" method="POST" id="tf">
        @csrf

        <div class="row">

            {{-- ── LEFT column ── --}}
            <div class="col-md-8">

                {{-- Branches --}}
                <div class="ctr-card">
                    <div class="ctr-card-head">
                        <i class="bi bi-building" style="color:#3498db;"></i> Transfer Route
                    </div>
                    <div class="ctr-card-body">
                        <div class="row">
                            @php $store = \App\Branch::where('is_main',true)->where('is_active',true)->first(); @endphp
                            <div class="col-md-6">
                                <label style="font-weight:700;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.5px;">FROM</label>
                                <div class="branch-pill" style="margin-top:6px;">
                                    <i class="bi bi-building"></i>
                                    <span>{{ $store->name ?? 'Main Store' }}</span>
                                    <span class="store-badge">STORE</span>
                                </div>
                                <input type="hidden" name="from_branch_id" value="{{ $store->id ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label style="font-weight:700;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.5px;">TO</label>
                                @if(auth()->user()->canSeeAllBranches())
                                    <select name="to_branch_id" id="toBranch" class="form-control" required
                                            style="margin-top:6px;border:1.5px solid #e0e6ed;border-radius:8px;height:42px;">
                                        <option value="">-- Select Destination Branch --</option>
                                        @foreach($branches as $b)
                                            @if(!$b->is_main)
                                                <option value="{{ $b->id }}" {{ old('to_branch_id')==$b->id ? 'selected' : '' }}>
                                                    {{ $b->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <div class="branch-pill" style="margin-top:6px;background:#f0f4ff;border-color:#c5cae9;color:#3949ab;">
                                        <i class="bi bi-building"></i>
                                        <strong>{{ auth()->user()->branch->name ?? 'Your Branch' }}</strong>
                                    </div>
                                    <input type="hidden" name="to_branch_id" value="{{ auth()->user()->branch_id }}">
                                    <input type="hidden" id="toBranch" value="{{ auth()->user()->branch_id }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products table --}}
                <div class="ctr-card">
                    <div class="ctr-card-head">
                        <i class="bi bi-box-seam" style="color:#9b59b6;"></i>
                        Products
                        <span class="row-count-badge" id="rowCountBadge">1</span>
                    </div>
                    <div class="ctr-card-body" style="padding:0;">
                        <div style="overflow-x:auto;">
                            <table class="pt-table" style="table-layout:fixed;width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width:55%;">Product (code or description)</th>
                                        <th style="width:80px;text-align:center;">Qty</th>
                                        <th style="width:22%;">Notes</th>
                                        <th style="width:42px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="productRows">
                                    {{-- Rows injected by JS --}}
                                </tbody>
                            </table>
                        </div>
                        <div style="padding:12px 16px;border-top:1px solid #f0f2f5;">
                            <button type="button" class="btn-add-row" onclick="addRow()">
                                <i class="bi bi-plus-circle-fill"></i> Add Another Product
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT column ── --}}
            <div class="col-md-4">

                {{-- How it works --}}
                <div class="ctr-card" style="border-color:#3498db;">
                    <div class="ctr-card-head" style="color:#1565c0;background:#e3f2fd;border-color:#90caf9;">
                        <i class="bi bi-info-circle-fill"></i> How it works
                    </div>
                    <div class="ctr-card-body">
                        <ol style="padding-left:18px;font-size:13px;color:#555;line-height:2.1;margin:0;">
                            <li>Select products &amp; quantities below</li>
                            <li>Submit → request goes to Store as <strong>Pending</strong></li>
                            <li>Store admin reviews &amp; approves</li>
                            <li>You <strong>Accept</strong> each item → stock enters your branch</li>
                        </ol>
                        <div style="background:#e8f5e9;border-radius:8px;padding:10px 12px;font-size:12px;color:#1b5e20;margin-top:12px;">
                            <i class="bi bi-lightning-fill" style="color:#27ae60;"></i>
                            Multiple products = <strong>one BATCH request</strong> with a shared batch number.
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="ctr-card">
                    <div class="ctr-card-body">
                        <div style="font-size:13px;color:#888;margin-bottom:12px;text-align:center;">
                            <i class="bi bi-cart-check-fill" style="color:#27ae60;font-size:18px;"></i><br>
                            <span id="summaryText">0 products ready to submit</span>
                        </div>
                        <button type="button" class="btn-submit" id="btnSubmit" onclick="submitForm()">
                            <i class="bi bi-send-fill"></i> <span id="btnLabel">Submit Transfer Request</span>
                        </button>
                        <div style="text-align:center;margin-top:10px;">
                            <small style="color:#aaa;font-size:11px;">
                                <i class="bi bi-info-circle"></i>
                                Also available: <a href="{{ route('dashboard.stock-transfers.bulk-request') }}" style="color:#3498db;">Excel bulk upload</a>
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
var STORE_ID  = {{ $store->id ?? 'null' }};
var CSRF      = '{{ csrf_token() }}';
var CHECK_URL = '{{ route("dashboard.stock-transfers.check-stock") }}';
var rowSeq    = 0;

/* ── Build one table row ── */
function makeRow() {
    var id = ++rowSeq;
    var tr = document.createElement('tr');
    tr.id = 'row-' + id;
    tr.innerHTML = `
        <td>
            <input type="hidden" name="items[${id}][product_id]" id="pid_${id}" value="">
            <div class="prod-search-wrap">
                <input type="text" class="prod-search-input" id="search_${id}"
                       placeholder="Type code or description…"
                       autocomplete="off"
                       oninput="onSearchInput(${id},this.value)"
                       onkeydown="onSearchKey(event,${id})">
                <button type="button" class="prod-clear-btn" id="clr_${id}" onclick="clearRow(${id})" title="Clear">×</button>
                <div class="prod-dropdown" id="dd_${id}"></div>
            </div>
            <div class="prod-selected" id="sel_${id}"></div>
        </td>
        <td style="text-align:center;">
            <input type="number" name="items[${id}][quantity]" class="qty-input"
                   value="1" min="1" id="qty_${id}" onchange="updateSummary()">
        </td>
        <td>
            <input type="text" name="items[${id}][notes]" class="note-input"
                   placeholder="Optional notes…" id="note_${id}">
        </td>
        <td style="text-align:center;">
            <button type="button" class="btn-rm" id="rm_${id}" onclick="removeRow(${id})" title="Remove row">
                <i class="bi bi-trash3"></i>
            </button>
        </td>`;
    return tr;
}

/* ── Add a row ── */
function addRow() {
    document.getElementById('productRows').appendChild(makeRow());
    refreshRemoveButtons();
    updateSummary();
    updateRowCount();
    /* Focus the new search input */
    var lastInput = document.querySelector('#productRows tr:last-child .prod-search-input');
    if (lastInput) lastInput.focus();
}

/* ── Remove a row ── */
function removeRow(id) {
    var tr = document.getElementById('row-' + id);
    if (tr) tr.remove();
    refreshRemoveButtons();
    updateSummary();
    updateRowCount();
}

/* ── Disable remove btn when only 1 row left ── */
function refreshRemoveButtons() {
    var rows = document.querySelectorAll('#productRows tr');
    rows.forEach(function(r) {
        var btn = r.querySelector('.btn-rm');
        if (btn) btn.disabled = (rows.length <= 1);
    });
}

/* ── Search debounce — starts from 1 character ── */
var _timers = {};
function onSearchInput(id, val) {
    clearTimeout(_timers[id]);
    document.getElementById('clr_' + id).style.display = val ? 'block' : 'none';
    if (val.length < 1) { closeDropdown(id); return; }
    _timers[id] = setTimeout(function(){ doSearch(id, val); }, 250);
}

function onSearchKey(e, id) {
    if (e.key === 'Escape') closeDropdown(id);
}

/* ── Position dropdown using fixed coords (escapes overflow:hidden) ── */
function positionDropdown(id) {
    var input = document.getElementById('search_' + id);
    var dd    = document.getElementById('dd_' + id);
    if (!input || !dd) return;
    var rect   = input.getBoundingClientRect();
    var minW   = 420;
    var width  = Math.max(rect.width, minW);
    /* If it would overflow the right edge, shift left */
    var left = rect.left;
    if (left + width > window.innerWidth - 8) {
        left = window.innerWidth - width - 8;
    }
    if (left < 4) left = 4;
    dd.style.top   = (rect.bottom + 3) + 'px';
    dd.style.left  = left + 'px';
    dd.style.width = width + 'px';
}

/* ── AJAX product search ── */
function doSearch(id, q) {
    var toBranch = document.getElementById('toBranch')
        ? (document.getElementById('toBranch').value || '')
        : '';
    var dd = document.getElementById('dd_' + id);
    positionDropdown(id);
    dd.innerHTML = '<div style="padding:10px 14px;color:#888;font-size:13px;"><i class="bi bi-hourglass-split"></i> Searching…</div>';
    dd.style.display = 'block';

    fetch('{{ route("dashboard.stock-transfers.search-products") }}?q=' + encodeURIComponent(q)
          + '&from_branch_id=' + STORE_ID + '&to_branch_id=' + toBranch, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (!data.length) {
            dd.innerHTML = '<div style="padding:10px 14px;color:#aaa;font-size:13px;"><i class="bi bi-search"></i> No products found</div>';
            return;
        }
        dd.innerHTML = '';
        data.forEach(function(p){
            var stockClass = p.stock > 10 ? 'po-stock-ok' : (p.stock > 0 ? 'po-stock-low' : 'po-stock-zero');
            var div = document.createElement('div');
            div.className = 'prod-option';
            div.innerHTML = `
                <span class="po-code">${p.product_id}</span>
                <span class="po-name">${p.description}</span>
                <span class="po-stock ${stockClass}">${p.stock} in stock</span>`;
            div.onclick = function(){ selectProduct(id, p); };
            dd.appendChild(div);
        });
    })
    .catch(function(){
        dd.innerHTML = '<div style="padding:10px 14px;color:#e53935;font-size:13px;">Error searching products</div>';
    });
}

/* ── Select a product from dropdown ── */
function selectProduct(id, p) {
    document.getElementById('pid_' + id).value = p.id;
    document.getElementById('search_' + id).style.display = 'none';
    document.getElementById('clr_' + id).style.display = 'none';
    closeDropdown(id);

    var stockClass = p.stock > 10 ? 'po-stock-ok' : (p.stock > 0 ? 'po-stock-low' : 'po-stock-zero');
    var sel = document.getElementById('sel_' + id);
    sel.innerHTML = `
        <span class="ps-code">${p.product_id}</span>
        <span class="ps-name">${p.description}</span>
        <span class="ps-stock ${stockClass}">(${p.stock} available)</span>
        <a class="ps-change" onclick="clearRow(${id})">change</a>`;
    sel.style.display = 'block';

    updateSummary();
}

/* ── Clear / reset a row ── */
function clearRow(id) {
    document.getElementById('pid_' + id).value = '';
    var si = document.getElementById('search_' + id);
    si.value = ''; si.style.display = '';
    document.getElementById('clr_' + id).style.display = 'none';
    document.getElementById('sel_' + id).style.display = 'none';
    closeDropdown(id);
    updateSummary();
}

function closeDropdown(id) {
    var dd = document.getElementById('dd_' + id);
    if (dd) dd.style.display = 'none';
}

/* ── Close dropdowns on outside click ── */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.prod-search-wrap') && !e.target.closest('.prod-dropdown')) {
        document.querySelectorAll('.prod-dropdown').forEach(function(d){ d.style.display='none'; });
    }
});

/* ── Reposition open dropdowns on scroll/resize (stay attached to input) ── */
function repositionOpenDropdowns() {
    document.querySelectorAll('.prod-dropdown').forEach(function(d) {
        if (d.style.display !== 'none') {
            var rowId = d.id.replace('dd_', '');
            positionDropdown(rowId);
        }
    });
}
window.addEventListener('scroll', repositionOpenDropdowns, true);
window.addEventListener('resize', repositionOpenDropdowns);

/* ── Summary & button label ── */
function updateSummary() {
    var filled = document.querySelectorAll('#productRows input[name$="[product_id]"]');
    var count = 0;
    filled.forEach(function(el){ if (el.value) count++; });
    document.getElementById('summaryText').textContent = count + ' product' + (count!==1?'s':'') + ' ready to submit';
    var btn = document.getElementById('btnLabel');
    if (count > 1) {
        btn.textContent = 'Submit BATCH Request (' + count + ' products)';
    } else {
        btn.textContent = 'Submit Transfer Request';
    }
}

function updateRowCount() {
    var n = document.querySelectorAll('#productRows tr').length;
    document.getElementById('rowCountBadge').textContent = n;
}

/* ── Submit with validation ── */
function submitForm() {
    var toBranch = document.getElementById('toBranch');
    if (!toBranch || !toBranch.value) {
        Swal.fire({ icon:'warning', title:'Missing destination', text:'Please select a destination branch.', confirmButtonColor:'#3949ab' });
        return;
    }

    var pids = document.querySelectorAll('#productRows input[name$="[product_id]"]');
    var valid = 0;
    pids.forEach(function(el){ if (el.value) valid++; });

    if (valid === 0) {
        Swal.fire({ icon:'warning', title:'No products selected', text:'Please add at least one product before submitting.', confirmButtonColor:'#3949ab' });
        return;
    }

    /* Warn about empty rows */
    var total = pids.length;
    if (valid < total) {
        Swal.fire({
            icon:'question', title:'Incomplete rows',
            text: (total - valid) + ' row(s) have no product selected and will be skipped. Continue?',
            showCancelButton: true, confirmButtonColor:'#27ae60', cancelButtonColor:'#95a5a6',
            confirmButtonText:'Yes, Submit ' + valid + ' product(s)'
        }).then(function(r){ if (r.isConfirmed) document.getElementById('tf').submit(); });
        return;
    }

    document.getElementById('tf').submit();
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', function(){
    addRow();
    updateSummary();
});
</script>

@endsection
