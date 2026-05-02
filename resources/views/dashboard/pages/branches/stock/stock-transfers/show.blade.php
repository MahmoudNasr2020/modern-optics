@extends('dashboard.layouts.master')
@section('title', 'Transfer — ' . $stockTransfer->transfer_number)
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
.stat-pill .n { font-size: 28px; font-weight: 900; line-height: 1; }
.stat-pill .l { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
.stat-pill.s-blue   { border-color: #5c6bc0; } .stat-pill.s-blue   .n { color: #3949ab; }
.stat-pill.s-orange { border-color: #fb8c00; } .stat-pill.s-orange .n { color: #e65100; }
.stat-pill.s-green  { border-color: #43a047; } .stat-pill.s-green  .n { color: #2e7d32; }
.stat-pill.s-red    { border-color: #e53935; } .stat-pill.s-red    .n { color: #b71c1c; }

/* ── Main card ── */
.main-card {
    background: #fff; border-radius: 12px;
    overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08);
    border: 1px solid #e8ecf7;
}

/* ── Action toolbar ── */
.action-bar {
    background: #f8f9ff; padding: 14px 20px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    border-bottom: 1px solid #e8ecf7;
}
.action-bar .bar-label { font-size: 13px; color: #555; font-weight: 600; flex: 1; }

/* ── Buttons ── */
.tb { display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 8px; font-size: 13px;
    font-weight: 700; border: none; cursor: pointer;
    transition: all .2s; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.tb:hover { transform: translateY(-2px); box-shadow: 0 5px 16px rgba(0,0,0,.18); }
.tb-green  { background: linear-gradient(135deg,#43a047,#2e7d32); color: #fff; }
.tb-red    { background: linear-gradient(135deg,#e53935,#b71c1c); color: #fff; }
.tb-grey   { background: #ecf0f1; color: #555; box-shadow: none; }

/* ── Table ── */
.tr-table { width: 100%; border-collapse: collapse; }
.tr-table thead tr { background: linear-gradient(135deg,#37474f,#455a64); }
.tr-table thead th {
    color: #fff; font-weight: 700; font-size: 12px;
    text-transform: uppercase; letter-spacing: .7px;
    padding: 14px 16px; border: none; white-space: nowrap;
}
.tr-table tbody tr { border-bottom: 1px solid #f0f2f5; }
.tr-table tbody tr.row-done { background: #f1fdf4; }
.tr-table tbody tr.row-rej  { background: #fff5f5; opacity: .75; }
.tr-table tbody td { padding: 18px 16px; vertical-align: middle; font-size: 14px; }

/* Product cell */
.prod-code { display: inline-block; background: #e8eaf6; color: #3949ab;
    padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; margin-bottom: 4px; }
.prod-name { font-weight: 700; color: #2c3e50; font-size: 15px; }

/* Qty cell */
.qty-big { font-size: 28px; font-weight: 900; color: #3949ab; line-height: 1; }

/* Status badge */
.s-badge { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.s-pending  { background: #fff3e0; color: #e65100; }
.s-received { background: #e8f5e9; color: #2e7d32; }
.s-canceled { background: #fce4ec; color: #880e4f; }
.s-approved { background: #e3f2fd; color: #0d47a1; }

/* Row actions */
.row-acts { display: flex; gap: 8px; justify-content: flex-end; }
.ra { display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 16px; border-radius: 8px; font-size: 13px;
    font-weight: 700; border: none; cursor: pointer; transition: all .15s; }
.ra:hover { transform: translateY(-2px); }
.ra-ok { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
.ra-ok:hover { background: #43a047; color: #fff; border-color: #43a047; }
.ra-no { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }
.ra-no:hover { background: #e53935; color: #fff; border-color: #e53935; }

/* Info grid */
.info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; padding: 18px 20px; }
.info-item { background: #f8f9ff; border-radius: 8px; padding: 12px 14px; border: 1px solid #e8ecf7; }
.info-item .lbl { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.info-item .val { font-size: 14px; font-weight: 700; color: #2c3e50; }

/* Notes / reason */
.notes-bar { padding: 12px 20px; background: #fffbf0; border-top: 1px solid #ffeaa7;
    font-size: 13px; color: #795548; display: flex; align-items: flex-start; gap: 8px; }
.reject-bar { padding: 12px 20px; background: #fff5f5; border-top: 1px solid #ffcdd2;
    font-size: 13px; color: #c62828; display: flex; align-items: flex-start; gap: 8px; }

/* Activity log */
.timeline-wrap { padding: 20px; }
.tl-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; position: relative; }
.tl-item:not(:last-child)::before {
    content: ''; position: absolute; left: 17px; top: 38px;
    width: 2px; height: calc(100% - 8px); background: #e8ecf7;
}
.tl-dot { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 16px; color: #fff; flex-shrink: 0; }
.tl-body { background: #f8f9ff; border-radius: 8px; padding: 10px 14px; flex: 1; border: 1px solid #e8ecf7; }
.tl-title { font-weight: 700; font-size: 13px; color: #2c3e50; }
.tl-meta  { font-size: 12px; color: #888; margin-top: 3px; }
</style>

{{-- ═══════════════════════════════════════════ --}}
<section class="content-header" style="padding:10px 20px 5px;">
    <h1 style="font-size:18px;margin:0;">
        <i class="bi bi-arrow-left-right" style="color:#3949ab;"></i>
        Transfer Request
        <small style="font-size:13px;color:#888;margin-left:8px;">{{ $stockTransfer->transfer_number }}</small>
    </h1>
</section>

<div class="tr-page">

    {{-- ══ Header card ══ --}}
    <div class="tr-header">
        <div class="row" style="align-items:center;">
            <div class="col-md-8">
                <h2>
                    <i class="bi bi-file-text" style="opacity:.75;margin-right:6px;"></i>
                    {{ $stockTransfer->transfer_number }}
                    @php
                        $sBadge = [
                            'pending'  => ['bg'=>'rgba(255,152,0,.35)',  'border'=>'rgba(255,152,0,.6)',  'label'=>'Pending'],
                            'approved' => ['bg'=>'rgba(33,150,243,.35)', 'border'=>'rgba(33,150,243,.6)', 'label'=>'Approved'],
                            'received' => ['bg'=>'rgba(67,160,71,.35)',  'border'=>'rgba(67,160,71,.6)',  'label'=>'Accepted'],
                            'canceled' => ['bg'=>'rgba(229,57,53,.35)',  'border'=>'rgba(229,57,53,.6)',  'label'=>'Rejected'],
                        ][$stockTransfer->status] ?? ['bg'=>'rgba(255,255,255,.2)','border'=>'rgba(255,255,255,.4)','label'=>ucfirst($stockTransfer->status)];
                    @endphp
                    <span style="font-size:13px;background:{{ $sBadge['bg'] }};border:1px solid {{ $sBadge['border'] }};
                        padding:3px 12px;border-radius:20px;vertical-align:middle;margin-left:8px;">
                        {{ $sBadge['label'] }}
                    </span>
                </h2>
                <div class="sub">
                    <span><i class="bi bi-building"></i> <strong>{{ $stockTransfer->fromBranch->name ?? '—' }}</strong></span>
                    <span style="opacity:.6;">→</span>
                    <span><i class="bi bi-building"></i> <strong>{{ $stockTransfer->toBranch->name ?? '—' }}</strong></span>
                    <span><i class="bi bi-person"></i> {{ $stockTransfer->creator->name ?? '—' }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $stockTransfer->created_at->format('d M Y — H:i') }}</span>
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
        <div class="stat-pill s-blue">
            <div class="n">1</div>
            <div class="l">Product</div>
        </div>
        <div class="stat-pill s-orange">
            <div class="n">{{ $stockTransfer->quantity }}</div>
            <div class="l">Units Requested</div>
        </div>
        @if($stockTransfer->status === 'received')
        <div class="stat-pill s-green">
            <div class="n">{{ $stockTransfer->quantity }}</div>
            <div class="l">Units Accepted</div>
        </div>
        @elseif($stockTransfer->status === 'canceled')
        <div class="stat-pill s-red">
            <div class="n">{{ $stockTransfer->quantity }}</div>
            <div class="l">Units Rejected</div>
        </div>
        @else
        <div class="stat-pill s-green">
            <div class="n">—</div>
            <div class="l">Awaiting Action</div>
        </div>
        @endif
        <div class="stat-pill" style="border-color:#90a4ae;">
            <div class="n" style="color:#546e7a;font-size:16px;padding-top:4px;">
                {{ $stockTransfer->created_at->diffForHumans() }}
            </div>
            <div class="l">Submitted</div>
        </div>
    </div>

    {{-- ══ Main table card ══ --}}
    <div class="main-card">

        {{-- Action toolbar --}}
        <div class="action-bar">
            <div class="bar-label">
                <i class="bi bi-box" style="color:#3949ab;margin-right:5px;"></i>
                Transfer Item
            </div>

            @if($stockTransfer->status === 'pending')
                <button class="tb tb-green" onclick="acceptTransfer()">
                    <i class="bi bi-check-circle-fill"></i> Accept & Add to Stock
                </button>
                <button class="tb tb-red" onclick="rejectTransfer()">
                    <i class="bi bi-x-octagon-fill"></i> Reject
                </button>
            @elseif($stockTransfer->status === 'received')
                <span style="font-size:13px;color:#2e7d32;font-weight:700;">
                    <i class="bi bi-check-circle-fill"></i> Accepted — stock has been added to branch
                </span>
            @elseif($stockTransfer->status === 'canceled')
                <span style="font-size:13px;color:#c62828;font-weight:700;">
                    <i class="bi bi-x-circle-fill"></i> Rejected
                </span>
            @else
                <span style="font-size:13px;color:#0d47a1;font-weight:700;">
                    <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                    {{ ucfirst($stockTransfer->status) }}
                </span>
            @endif
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="tr-table">
                <thead>
                    <tr>
                        <th width="44" style="text-align:center;">#</th>
                        <th>Product</th>
                        <th width="90" style="text-align:center;">Qty</th>
                        <th width="120" style="text-align:center;">Status</th>
                        <th width="200" style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        if ($stockTransfer->status === 'received')     $rowCls = 'row-done';
                        elseif ($stockTransfer->status === 'canceled') $rowCls = 'row-rej';
                        else                                           $rowCls = '';
                        $badgeCls = 's-' . $stockTransfer->status;
                    @endphp
                    <tr class="{{ $rowCls }}">

                        {{-- Row number --}}
                        <td style="text-align:center;color:#aaa;font-size:13px;">1</td>

                        {{-- Product --}}
                        <td>
                            <div class="prod-code">{{ $stockTransfer->stockable->product_id ?? '—' }}</div>
                            <div class="prod-name">{{ $stockTransfer->stockable->description ?? $stockTransfer->item_description }}</div>
                            @if($stockTransfer->notes)
                                <div style="font-size:12px;color:#888;margin-top:4px;">
                                    <i class="bi bi-chat-text"></i> {{ $stockTransfer->notes }}
                                </div>
                            @endif
                            @if($stockTransfer->status === 'canceled' && $stockTransfer->rejection_reason)
                                <div style="font-size:12px;color:#e53935;margin-top:4px;font-style:italic;">
                                    <i class="bi bi-x-circle"></i> {{ $stockTransfer->rejection_reason }}
                                </div>
                            @endif
                        </td>

                        {{-- Qty --}}
                        <td style="text-align:center;">
                            <span class="qty-big">{{ $stockTransfer->quantity }}</span>
                            <div style="font-size:11px;color:#aaa;margin-top:2px;">units</div>
                        </td>

                        {{-- Status badge --}}
                        <td style="text-align:center;">
                            <span class="s-badge {{ $badgeCls }}">
                                @if($stockTransfer->status === 'pending')   <i class="bi bi-hourglass-split"></i>
                                @elseif($stockTransfer->status === 'received') <i class="bi bi-check-circle-fill"></i>
                                @elseif($stockTransfer->status === 'canceled') <i class="bi bi-x-circle-fill"></i>
                                @else <i class="bi bi-circle"></i>
                                @endif
                                {{ ucfirst(str_replace('_',' ',$stockTransfer->status)) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            @if($stockTransfer->status === 'pending')
                                <div class="row-acts">
                                    <button class="ra ra-ok" onclick="acceptTransfer()">
                                        <i class="bi bi-check-lg"></i> Accept
                                    </button>
                                    <button class="ra ra-no" onclick="rejectTransfer()">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </div>
                            @elseif($stockTransfer->status === 'received')
                                <div style="text-align:center;color:#43a047;font-size:13px;font-weight:600;">
                                    <i class="bi bi-check-circle-fill"></i> Accepted
                                </div>
                            @else
                                <div style="text-align:center;color:#aaa;font-size:13px;">—</div>
                            @endif
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Info grid row ── --}}
        <div class="info-grid" style="border-top:1px solid #e8ecf7;">
            <div class="info-item">
                <div class="lbl"><i class="bi bi-building"></i> From</div>
                <div class="val">{{ $stockTransfer->fromBranch->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="lbl"><i class="bi bi-building"></i> To</div>
                <div class="val">{{ $stockTransfer->toBranch->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="lbl"><i class="bi bi-person"></i> Requested By</div>
                <div class="val">{{ $stockTransfer->creator->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="lbl"><i class="bi bi-calendar3"></i> Date</div>
                <div class="val">{{ $stockTransfer->created_at->format('d M Y') }}</div>
            </div>
            @if($stockTransfer->approver)
            <div class="info-item">
                <div class="lbl"><i class="bi bi-check-circle"></i> Accepted By</div>
                <div class="val">{{ $stockTransfer->approver->name ?? '—' }}</div>
            </div>
            @endif
            @if($stockTransfer->approved_at)
            <div class="info-item">
                <div class="lbl"><i class="bi bi-clock"></i> Accepted At</div>
                <div class="val">{{ $stockTransfer->approved_at->format('d M Y H:i') }}</div>
            </div>
            @endif
        </div>

        {{-- Table footer ── --}}
        <div style="padding:12px 20px;background:#f8f9ff;border-top:1px solid #e8ecf7;font-size:13px;color:#888;display:flex;justify-content:space-between;align-items:center;">
            <span>
                <i class="bi bi-info-circle"></i>
                <strong style="color:#3949ab;">{{ $stockTransfer->quantity }} units</strong>
                of <strong style="color:#2c3e50;">{{ $stockTransfer->stockable->description ?? '—' }}</strong>
            </span>
            <span>
                <i class="bi bi-clock"></i> {{ $stockTransfer->created_at->diffForHumans() }}
            </span>
        </div>

    </div>

    {{-- ══ Activity log ── --}}
    <div style="margin-top:20px;background:#fff;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);border:1px solid #e8ecf7;">
        <div style="padding:14px 20px;border-bottom:1px solid #e8ecf7;font-weight:700;font-size:14px;color:#2c3e50;">
            <i class="bi bi-clock-history" style="color:#3949ab;margin-right:6px;"></i> Activity Log
        </div>
        <div class="timeline-wrap">

            {{-- Created --}}
            <div class="tl-item">
                <div class="tl-dot" style="background:linear-gradient(135deg,#3498db,#2980b9);">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="tl-body">
                    <div class="tl-title">Request Created</div>
                    <div class="tl-meta">
                        {{ $stockTransfer->creator->name ?? 'System' }}
                        &nbsp;·&nbsp; {{ $stockTransfer->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            @if($stockTransfer->approved_by)
            <div class="tl-item">
                <div class="tl-dot" style="background:linear-gradient(135deg,#43a047,#2e7d32);">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="tl-body">
                    <div class="tl-title">Accepted — Stock Added to Branch</div>
                    <div class="tl-meta">
                        {{ $stockTransfer->approver->name ?? 'System' }}
                        &nbsp;·&nbsp; {{ optional($stockTransfer->approved_at)->format('d M Y, H:i') ?? '—' }}
                    </div>
                </div>
            </div>
            @endif

            @if($stockTransfer->status === 'canceled')
            <div class="tl-item">
                <div class="tl-dot" style="background:linear-gradient(135deg,#e53935,#b71c1c);">
                    <i class="bi bi-x-lg"></i>
                </div>
                <div class="tl-body" style="background:#fff5f5;border-color:#ffcdd2;">
                    <div class="tl-title" style="color:#c62828;">Rejected</div>
                    <div class="tl-meta">
                        {{ $stockTransfer->updated_at->format('d M Y, H:i') }}
                        @if($stockTransfer->rejection_reason)
                            &nbsp;·&nbsp; {{ $stockTransfer->rejection_reason }}
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- Hidden Forms --}}
<form id="fAccept" action="{{ route('dashboard.stock-transfers.accept', $stockTransfer) }}" method="POST" style="display:none;">
    @csrf
</form>
<form id="fReject" action="{{ route('dashboard.stock-transfers.reject', $stockTransfer) }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="rejection_reason" id="rejR">
</form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ── Accept ── */
function acceptTransfer() {
    Swal.fire({
        title: 'Accept this transfer?',
        html: `<b>{{ $stockTransfer->transfer_number }}</b> — {{ $stockTransfer->quantity }} units<br>
               <small style="color:#888;margin-top:6px;display:block;">Stock will be added to <strong>{{ $stockTransfer->toBranch->name ?? 'your branch' }}</strong> immediately.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#43a047',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-check-circle-fill"></i> Accept & Add to Stock',
        cancelButtonText: 'Cancel'
    }).then(r => {
        if (r.isConfirmed) document.getElementById('fAccept').submit();
    });
}

/* ── Reject ── */
function rejectTransfer() {
    Swal.fire({
        title: 'Reject this transfer?',
        html: `<b>{{ $stockTransfer->transfer_number }}</b><br>
               <textarea id="ri" class="swal2-input" placeholder="Reason for rejection…"
                         style="height:80px;resize:none;margin-top:10px;"></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-x-circle-fill"></i> Reject',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            var v = document.getElementById('ri').value.trim();
            if (!v) { Swal.showValidationMessage('Please enter a reason'); return false; }
            return v;
        }
    }).then(r => {
        if (!r.isConfirmed) return;
        document.getElementById('rejR').value = r.value;
        document.getElementById('fReject').submit();
    });
}
</script>
@endsection
