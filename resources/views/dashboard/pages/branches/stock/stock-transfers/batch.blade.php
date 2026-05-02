@extends('dashboard.layouts.master')
@section('title', 'Transfer Request — ' . $batchNumber)
@section('content')

<style>
/* ── Page wrapper ── */
.tr-page { padding: 20px; }

/* ── Top header card ── */
.tr-header {
    background: linear-gradient(135deg, #1a237e 0%, #283593 60%, #1565c0 100%);
    border-radius: 14px; padding: 24px 28px;
    color: #fff; margin-bottom: 20px;
    box-shadow: 0 6px 28px rgba(21,101,192,.35);
}
.tr-header h2 { margin: 0 0 6px; font-size: 22px; font-weight: 800; letter-spacing: .3px; }
.tr-header .sub { font-size: 13px; opacity: .8; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 4px; }
.tr-header .sub span { display: flex; align-items: center; gap: 5px; }

/* ── Stat pills ── */
.stats-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-pill {
    flex: 1; min-width: 110px; background: #fff;
    border-radius: 10px; padding: 14px 16px;
    text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,.07);
    border-top: 3px solid #ddd;
}
.stat-pill .n { font-size: 30px; font-weight: 900; line-height: 1; }
.stat-pill .l { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
.stat-pill.s-total   { border-color: #5c6bc0; } .stat-pill.s-total   .n { color: #3949ab; }
.stat-pill.s-pending { border-color: #fb8c00; } .stat-pill.s-pending .n { color: #e65100; }
.stat-pill.s-done    { border-color: #43a047; } .stat-pill.s-done    .n { color: #2e7d32; }
.stat-pill.s-rej     { border-color: #e53935; } .stat-pill.s-rej     .n { color: #b71c1c; }

/* ── Action toolbar ── */
.action-bar {
    background: #fff; border-radius: 10px; padding: 12px 18px;
    margin-bottom: 16px; display: flex; align-items: center;
    gap: 10px; flex-wrap: wrap;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    border: 1px solid #e8ecf7;
}
.action-bar .sel-info { font-size: 13px; color: #667eea; font-weight: 700; flex: 1; }

/* ── Action buttons ── */
.tb { display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 8px; font-size: 13px;
    font-weight: 700; border: none; cursor: pointer;
    transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.tb:hover { transform: translateY(-2px); box-shadow: 0 5px 16px rgba(0,0,0,.18); }
.tb:disabled { opacity: .45; cursor: not-allowed; transform: none !important; }
.tb-green  { background: linear-gradient(135deg,#43a047,#2e7d32); color: #fff; }
.tb-red    { background: linear-gradient(135deg,#e53935,#b71c1c); color: #fff; }
.tb-grey   { background: #ecf0f1; color: #555; box-shadow: none; }

/* ── Table ── */
.tr-table { width: 100%; border-collapse: collapse; }
.tr-table thead tr { background: linear-gradient(135deg,#37474f,#455a64); }
.tr-table thead th {
    color: #fff; font-weight: 700; font-size: 12px;
    text-transform: uppercase; letter-spacing: .7px;
    padding: 14px 14px; border: none; white-space: nowrap;
}
.tr-table thead th:first-child { border-radius: 10px 0 0 0; }
.tr-table thead th:last-child  { border-radius: 0 10px 0 0; }

.tr-table tbody tr { transition: background .15s; border-bottom: 1px solid #f0f2f5; }
.tr-table tbody tr:hover { background: #f8f9ff; }
.tr-table tbody tr.row-done    { background: #f1fdf4; }
.tr-table tbody tr.row-rej     { background: #fff5f5; opacity: .72; }
.tr-table tbody td { padding: 14px; vertical-align: middle; font-size: 14px; }

/* Product cell */
.prod-code { display: inline-block; background: #e8eaf6; color: #3949ab;
    padding: 2px 9px; border-radius: 12px; font-size: 11px; font-weight: 700; margin-bottom: 3px; }
.prod-name { font-weight: 600; color: #2c3e50; }

/* Qty cell */
.qty-big { font-size: 22px; font-weight: 900; color: #3949ab; }

/* Status badge */
.s-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.s-pending  { background: #fff3e0; color: #e65100; }
.s-received { background: #e8f5e9; color: #2e7d32; }
.s-canceled { background: #fce4ec; color: #880e4f; }
.s-approved { background: #e3f2fd; color: #0d47a1; }

/* Row actions */
.row-acts { display: flex; gap: 6px; justify-content: flex-end; }
.ra { display: inline-flex; align-items: center; gap: 4px;
    padding: 6px 13px; border-radius: 7px; font-size: 12px;
    font-weight: 700; border: none; cursor: pointer; transition: all .15s; }
.ra:hover { transform: translateY(-1px); }
.ra-ok  { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
.ra-ok:hover  { background: #43a047; color: #fff; border-color: #43a047; }
.ra-no  { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }
.ra-no:hover  { background: #e53935; color: #fff; border-color: #e53935; }

/* Checkbox */
.cb-row { width: 16px; height: 16px; cursor: pointer; accent-color: #3949ab; }

/* Rejection note tooltip */
.rej-note { font-size: 11px; color: #e53935; margin-top: 3px; font-style: italic; }
</style>

{{-- ═══════════════════════════════════════════ --}}
<section class="content-header" style="padding:10px 20px 5px;">
    <h1 style="font-size:18px;margin:0;">
        <i class="bi bi-arrow-left-right" style="color:#3949ab;"></i>
        Transfer Request
        <small style="font-size:13px;color:#888;margin-left:8px;">{{ $batchNumber }}</small>
    </h1>
</section>

<div class="tr-page">

    {{-- ══ Header card ══ --}}
    <div class="tr-header">
        <div class="row" style="align-items:center;">
            <div class="col-md-8">
                <h2>
                    <i class="bi bi-collection" style="opacity:.75;margin-right:6px;"></i>
                    {{ $batchNumber }}
                    @if($transfers->where('status','pending')->count() > 0)
                        <span style="font-size:13px;background:rgba(255,152,0,.35);border:1px solid rgba(255,152,0,.6);
                            padding:3px 11px;border-radius:20px;vertical-align:middle;margin-left:8px;">
                            {{ $transfers->where('status','pending')->count() }} Awaiting
                        </span>
                    @else
                        <span style="font-size:13px;background:rgba(67,160,71,.35);border:1px solid rgba(67,160,71,.6);
                            padding:3px 11px;border-radius:20px;vertical-align:middle;margin-left:8px;">
                            Complete
                        </span>
                    @endif
                </h2>
                <div class="sub">
                    <span><i class="bi bi-building"></i> <strong>{{ $firstTransfer->fromBranch->name ?? '—' }}</strong></span>
                    <span style="opacity:.6;">→</span>
                    <span><i class="bi bi-building"></i> <strong>{{ $firstTransfer->toBranch->name ?? '—' }}</strong></span>
                    <span><i class="bi bi-person"></i> {{ $firstTransfer->creator->name ?? '—' }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $firstTransfer->created_at->format('d M Y — H:i') }}</span>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('dashboard.stock-transfers.index') }}"
                   style="background:rgba(255,255,255,.15);color:#fff;padding:9px 18px;border-radius:8px;
                   text-decoration:none;font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,.3);">
                    <i class="bi bi-arrow-left"></i> All Transfers
                </a>
            </div>
        </div>
    </div>

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="border-left:5px solid #43a047;border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-check-circle-fill"></i> {!! session('success') !!}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="border-left:5px solid #e53935;border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ══ Stats ══ --}}
    <div class="stats-row">
        <div class="stat-pill s-total">
            <div class="n">{{ $transfers->count() }}</div>
            <div class="l">Total Products</div>
        </div>
        <div class="stat-pill s-pending">
            <div class="n">{{ $transfers->where('status','pending')->count() }}</div>
            <div class="l">Pending</div>
        </div>
        <div class="stat-pill s-done">
            <div class="n">{{ $transfers->where('status','received')->count() }}</div>
            <div class="l">Accepted</div>
        </div>
        <div class="stat-pill s-rej">
            <div class="n">{{ $transfers->where('status','canceled')->count() }}</div>
            <div class="l">Rejected</div>
        </div>
        <div class="stat-pill" style="border-color:#5c6bc0;">
            <div class="n" style="color:#3949ab;font-size:22px;">
                {{ $transfers->where('status','received')->sum('quantity') }}
                <span style="font-size:13px;color:#888;">/ {{ $transfers->sum('quantity') }}</span>
            </div>
            <div class="l">Units Accepted</div>
        </div>
    </div>

    {{-- ══ Main table card ══ --}}
    <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);border:1px solid #e8ecf7;">

        {{-- Action toolbar --}}
        @php
            $hasPending = false;
            foreach($transfers as $_xt) { if($_xt->status === 'pending') { $hasPending = true; break; } }
        @endphp
        <div class="action-bar">
            <div class="sel-info" id="selInfo">
                <i class="bi bi-check-square" style="margin-right:4px;"></i>
                <span id="selCount">0</span> selected
            </div>

            @if($hasPending)
                <button class="tb tb-green" id="btnAcceptSel" disabled onclick="acceptSelected()">
                    <i class="bi bi-check-circle-fill"></i> Accept Selected
                </button>
                <button class="tb tb-green" onclick="acceptAll()">
                    <i class="bi bi-check-all"></i> Accept All
                </button>
                <button class="tb tb-red" onclick="rejectAll()">
                    <i class="bi bi-x-octagon-fill"></i> Reject All
                </button>
            @else
                <span style="font-size:13px;color:#27ae60;font-weight:600;">
                    <i class="bi bi-check-circle-fill"></i> All items have been processed
                </span>
            @endif
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="tr-table">
                <thead>
                    <tr>
                        @if($hasPending)
                        <th width="44" style="text-align:center;">
                            <input type="checkbox" id="cbAll"
                                   style="width:16px;height:16px;cursor:pointer;accent-color:#3949ab;"
                                   title="Select all pending">
                        </th>
                        @else
                        <th width="44"></th>
                        @endif
                        <th width="44" style="text-align:center;">#</th>
                        <th>Product</th>
                        <th width="80" style="text-align:center;">Qty</th>
                        <th width="110" style="text-align:center;">Status</th>
                        <th width="160" style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transfers as $i => $t)
                    @php
                        if ($t->status === 'received')     $rowCls = 'row-done';
                        elseif ($t->status === 'canceled') $rowCls = 'row-rej';
                        else                               $rowCls = '';
                        $badgeCls  = 's-' . $t->status;
                        $isPending = $t->status === 'pending';
                    @endphp
                    <tr class="{{ $rowCls }}" id="tr-{{ $t->id }}">

                        {{-- Checkbox (only for pending) --}}
                        <td style="text-align:center;">
                            @if($isPending)
                                <input type="checkbox" class="cb-item"
                                       style="width:16px;height:16px;cursor:pointer;accent-color:#3949ab;"
                                       value="{{ $t->id }}" onchange="onCheck()">
                            @endif
                        </td>

                        {{-- Row number --}}
                        <td style="text-align:center;color:#aaa;font-size:13px;">{{ $i + 1 }}</td>

                        {{-- Product --}}
                        <td>
                            <div class="prod-code">{{ $t->stockable->product_id ?? '—' }}</div>
                            <div class="prod-name">{{ $t->stockable->description ?? '—' }}</div>
                            @if($t->notes && strpos($t->notes,'Bulk transfer') === false)
                                <div style="font-size:11px;color:#888;margin-top:2px;">
                                    <i class="bi bi-chat-text"></i> {{ $t->notes }}
                                </div>
                            @endif
                            @if($t->status === 'canceled' && $t->rejection_reason)
                                <div class="rej-note">
                                    <i class="bi bi-x-circle"></i> {{ $t->rejection_reason }}
                                </div>
                            @endif
                        </td>

                        {{-- Qty --}}
                        <td style="text-align:center;">
                            <span class="qty-big">{{ $t->quantity }}</span>
                            <div style="font-size:11px;color:#aaa;">units</div>
                        </td>

                        {{-- Status badge --}}
                        <td style="text-align:center;">
                            <span class="s-badge {{ $badgeCls }}">
                                @if($t->status === 'pending')   <i class="bi bi-hourglass-split"></i>
                                @elseif($t->status === 'received') <i class="bi bi-check-circle-fill"></i>
                                @elseif($t->status === 'canceled') <i class="bi bi-x-circle-fill"></i>
                                @else <i class="bi bi-circle"></i>
                                @endif
                                {{ ucfirst(str_replace('_',' ',$t->status)) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            @if($isPending)
                                <div class="row-acts">
                                    <button class="ra ra-ok" onclick="acceptOne({{ $t->id }},'{{ $t->transfer_number }}')">
                                        <i class="bi bi-check-lg"></i> Accept
                                    </button>
                                    <button class="ra ra-no" onclick="rejectOne({{ $t->id }},'{{ $t->transfer_number }}')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </div>
                            @elseif($t->status === 'received')
                                <div style="text-align:center;color:#43a047;font-size:13px;font-weight:600;">
                                    <i class="bi bi-check-circle-fill"></i> Accepted
                                </div>
                            @else
                                <div style="text-align:center;color:#aaa;font-size:13px;">—</div>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Table footer summary --}}
        <div style="padding:14px 18px;background:#f8f9ff;border-top:1px solid #e8ecf7;font-size:13px;color:#888;display:flex;justify-content:space-between;align-items:center;">
            <span>
                <i class="bi bi-info-circle"></i>
                Total: <strong style="color:#3949ab;">{{ $transfers->sum('quantity') }} units</strong>
                across <strong>{{ $transfers->count() }} products</strong>
            </span>
            <span>
                From <strong>{{ $firstTransfer->fromBranch->name ?? '—' }}</strong>
                → <strong>{{ $firstTransfer->toBranch->name ?? '—' }}</strong>
            </span>
        </div>

    </div>

</div>

{{-- Hidden forms --}}
<form id="fAccept" method="POST" style="display:none;">@csrf</form>
<form id="fReject" method="POST" style="display:none;">@csrf<input type="hidden" name="rejection_reason" id="rejR"></form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var CSRF = '{{ csrf_token() }}';

/* ── Checkbox logic ── */
var selectedIds = [];

function onCheck() {
    selectedIds = [];
    document.querySelectorAll('.cb-item:checked').forEach(function(el) {
        selectedIds.push(el.value);
    });
    var count = selectedIds.length;
    document.getElementById('selCount').textContent = count;

    var btn = document.getElementById('btnAcceptSel');
    if (!btn) return;
    btn.disabled = (count === 0);
    btn.style.opacity = count > 0 ? '1' : '0.45';
    btn.style.cursor  = count > 0 ? 'pointer' : 'not-allowed';
    if (count > 0) {
        btn.removeAttribute('disabled');
    } else {
        btn.setAttribute('disabled', 'disabled');
    }
}

function toggleAll(masterEl) {
    var items = document.querySelectorAll('.cb-item');
    for (var i = 0; i < items.length; i++) {
        items[i].checked = masterEl.checked;
    }
    onCheck();
}

/* Bind with native jQuery change — iCheck is NOT initialized on these elements */
$(document).ready(function () {
    /* Row checkboxes */
    $(document).on('change', '.cb-item', function () {
        onCheck();
    });
    /* Master checkbox */
    $(document).on('change', '#cbAll', function () {
        var checked = $(this).prop('checked');
        $('.cb-item').prop('checked', checked);
        onCheck();
    });
    /* Run once on load to set correct initial state */
    onCheck();
});

/* ── Accept single ── */
function acceptOne(id, num) {
    Swal.fire({
        title: 'Accept this item?',
        html: `<b>${num}</b><br><small style="color:#888;">Stock will be added to the branch immediately.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#43a047',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-check-circle-fill"></i> Accept & Add to Stock',
    }).then(r => {
        if (!r.isConfirmed) return;
        submitPost(`/dashboard/stock-transfers/${id}/accept`);
    });
}

/* ── Reject single ── */
function rejectOne(id, num) {
    Swal.fire({
        title: '<span style="color:#c62828;font-size:20px;"><i class="bi bi-x-octagon-fill"></i> Reject Item</span>',
        html: `
            <div style="text-align:left;margin-bottom:10px;">
                <span style="display:inline-block;background:#fce4ec;color:#c62828;padding:4px 14px;border-radius:20px;font-size:13px;font-weight:700;">
                    <i class="bi bi-tag-fill"></i> ${num}
                </span>
            </div>
            <label style="display:block;text-align:left;font-size:12px;font-weight:700;color:#666;margin-bottom:6px;letter-spacing:.5px;text-transform:uppercase;">
                <i class="bi bi-chat-left-text"></i> Rejection Reason <span style="color:#e53935;">*</span>
            </label>
            <textarea id="ri" rows="3" placeholder="Enter reason for rejection…"
                style="width:100%;box-sizing:border-box;padding:10px 14px;border:2px solid #ef9a9a;border-radius:8px;
                       font-size:14px;color:#333;outline:none;resize:none;font-family:inherit;
                       transition:border-color .2s;background:#fff9f9;"
                onfocus="this.style.borderColor='#e53935'" onblur="this.style.borderColor='#ef9a9a'"></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-x-circle-fill"></i> Confirm Reject',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            var v = document.getElementById('ri').value.trim();
            if (!v) { Swal.showValidationMessage('Please enter a rejection reason'); return false; }
            return v;
        }
    }).then(r => {
        if (!r.isConfirmed) return;
        document.getElementById('rejR').value = r.value;
        document.getElementById('fReject').action = `/dashboard/stock-transfers/${id}/reject`;
        document.getElementById('fReject').submit();
    });
}

/* ── Accept SELECTED ── */
function acceptSelected() {
    var ids = selectedIds.slice();
    if (!ids.length) return;
    Swal.fire({
        title: `Accept ${ids.length} selected item(s)?`,
        html: `<small style="color:#888;">Stock for each item will be added to the branch immediately.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#43a047',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: `<i class="bi bi-check-all"></i> Accept ${ids.length} Item(s)`,
    }).then(r => {
        if (!r.isConfirmed) return;
        batchFetch(ids, 'accept', null);
    });
}

/* ── Accept ALL pending ── */
function acceptAll() {
    var ids = @json($transfers->where('status','pending')->pluck('id')->values());
    if (!ids.length) return;
    Swal.fire({
        title: `Accept All ${ids.length} Pending Items?`,
        html: `<small style="color:#888;">Stock for all items will be added to the branch immediately.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#43a047',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: `<i class="bi bi-check-all"></i> Accept All`,
    }).then(r => {
        if (!r.isConfirmed) return;
        batchFetch(ids, 'accept', null);
    });
}

/* ── Reject ALL pending ── */
function rejectAll() {
    var ids = @json($transfers->where('status','pending')->pluck('id')->values());
    if (!ids.length) return;
    Swal.fire({
        title: '<span style="color:#c62828;font-size:20px;"><i class="bi bi-x-octagon-fill"></i> Reject All Pending</span>',
        html: `
            <div style="background:#fff3e0;border-left:4px solid #fb8c00;border-radius:6px;padding:10px 14px;margin-bottom:14px;text-align:left;">
                <span style="font-size:13px;color:#e65100;font-weight:600;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    This will reject all <strong>${ids.length}</strong> pending item(s).
                </span>
            </div>
            <label style="display:block;text-align:left;font-size:12px;font-weight:700;color:#666;margin-bottom:6px;letter-spacing:.5px;text-transform:uppercase;">
                <i class="bi bi-chat-left-text"></i> Rejection Reason <span style="color:#e53935;">*</span>
            </label>
            <textarea id="bri" rows="3" placeholder="Enter reason for rejection…"
                style="width:100%;box-sizing:border-box;padding:10px 14px;border:2px solid #ef9a9a;border-radius:8px;
                       font-size:14px;color:#333;outline:none;resize:none;font-family:inherit;
                       transition:border-color .2s;background:#fff9f9;"
                onfocus="this.style.borderColor='#e53935'" onblur="this.style.borderColor='#ef9a9a'"></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: `<i class="bi bi-x-octagon-fill"></i> Reject All (${ids.length})`,
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            var v = document.getElementById('bri').value.trim();
            if (!v) { Swal.showValidationMessage('Please enter a rejection reason'); return false; }
            return v;
        }
    }).then(r => {
        if (!r.isConfirmed) return;
        batchFetch(ids, 'reject', r.value);
    });
}

/* ── Batch fetch helper ── */
function batchFetch(ids, action, reason) {
    Swal.fire({
        title: 'Processing…',
        html: `<div style="text-align:center;padding:10px;">
            <i class="bi bi-arrow-repeat" style="font-size:36px;color:#3949ab;animation:spin 1s linear infinite;"></i>
            <p style="margin-top:10px;color:#888;">Please wait…</p>
        </div>`,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });

    let chain = Promise.resolve();
    ids.forEach(id => {
        chain = chain.then(() => {
            var body = { _token: CSRF };
            if (action === 'reject') body.rejection_reason = reason;
            return fetch(`/dashboard/stock-transfers/${id}/${action}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json',
                           'Accept': 'application/json' },
                body: JSON.stringify(body)
            }).then(function(res) {
                if (!res.ok) {
                    return res.json().then(function(data) {
                        throw new Error(data.error || 'Request failed (HTTP ' + res.status + ')');
                    });
                }
                return res;
            });
        });
    });

    chain.then(() => {
        Swal.fire({
            title: action === 'accept' ? 'Done! Stock Updated ✓' : 'Items Rejected',
            icon: action === 'accept' ? 'success' : 'info',
            timer: 1800, showConfirmButton: false
        }).then(() => window.location.reload());
    }).catch(err => {
        Swal.fire('Error', err.message, 'error');
    });
}

/* ── Simple form post ── */
function submitPost(url) {
    var f = document.getElementById('fAccept');
    f.action = url; f.submit();
}
</script>
<style>
@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
</style>
@endsection
