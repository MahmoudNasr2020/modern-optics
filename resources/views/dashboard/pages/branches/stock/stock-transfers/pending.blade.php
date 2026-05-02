@extends('dashboard.layouts.master')
@section('title', 'Pending Transfer Requests')
@section('content')

<style>
.tr-page { padding: 20px; }

/* Header */
.tr-header {
    background: linear-gradient(135deg, #e65100 0%, #f57c00 60%, #fb8c00 100%);
    border-radius: 14px; padding: 22px 28px;
    color: #fff; margin-bottom: 20px;
    box-shadow: 0 6px 28px rgba(230,81,0,.30);
}
.tr-header h2 { margin: 0 0 4px; font-size: 21px; font-weight: 800; }
.tr-header .sub { font-size: 13px; opacity: .85; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 3px; }
.tr-header .sub span { display: flex; align-items: center; gap: 5px; }

/* Stats pills */
.stats-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-pill {
    flex: 1; min-width: 110px; background: #fff;
    border-radius: 10px; padding: 14px 16px;
    text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,.07);
    border-top: 3px solid #ddd;
}
.stat-pill .n { font-size: 30px; font-weight: 900; line-height: 1; }
.stat-pill .l { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }
.stat-pill.s-orange { border-color: #fb8c00; } .stat-pill.s-orange .n { color: #e65100; }
.stat-pill.s-red    { border-color: #e53935; } .stat-pill.s-red    .n { color: #b71c1c; }
.stat-pill.s-blue   { border-color: #5c6bc0; } .stat-pill.s-blue   .n { color: #3949ab; }

/* Main card */
.main-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); border: 1px solid #e8ecf7; }

/* Table */
.pt-table { width: 100%; border-collapse: collapse; }
.pt-table thead tr { background: linear-gradient(135deg, #37474f, #455a64); }
.pt-table thead th {
    color: #fff; font-weight: 700; font-size: 11px;
    text-transform: uppercase; letter-spacing: .7px;
    padding: 13px 14px; border: none; white-space: nowrap;
}
.pt-table thead th:first-child { border-radius: 0; }
.pt-table tbody tr { border-bottom: 1px solid #f0f2f5; transition: background .12s; }
.pt-table tbody tr:hover { background: #fafbff; }
.pt-table tbody tr.late-row { background: #fffbf0; border-left: 3px solid #fb8c00; }
.pt-table tbody td { padding: 12px 14px; vertical-align: middle; font-size: 13px; }

/* Transfer number */
.tf-num { font-weight: 700; color: #3949ab; font-size: 13px; }
.tf-num:hover { color: #1a237e; text-decoration: none; }

/* Batch badge */
.batch-tag { display: inline-block; background: #ede7f6; color: #6a1b9a; padding: 2px 8px;
    border-radius: 10px; font-size: 10px; font-weight: 700; margin-top: 3px; }

/* Branch pill */
.br-pill { display: inline-block; background: #ecf0f1; color: #555; padding: 3px 10px;
    border-radius: 12px; font-size: 12px; font-weight: 600; }
.br-pill.store { background: #e3f2fd; color: #1565c0; }

/* Product info */
.prod-code { display: inline-block; background: #e8eaf6; color: #3949ab;
    padding: 1px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; margin-bottom: 2px; }
.prod-name { font-weight: 600; color: #2c3e50; font-size: 13px; }

/* Qty */
.qty-n { font-size: 18px; font-weight: 900; color: #3949ab; }

/* Age badge */
.age-ok   { color: #aaa; font-size: 11px; }
.age-warn { color: #fb8c00; font-size: 11px; font-weight: 700; }
.age-late { color: #e53935; font-size: 11px; font-weight: 700; animation: blink-age 1.4s infinite; }
@keyframes blink-age { 0%,100%{opacity:1} 50%{opacity:.5} }

/* Action buttons — small inline */
.acts { display: flex; gap: 5px; justify-content: flex-end; align-items: center; }
.ab { display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 12px; border-radius: 6px; font-size: 11px;
    font-weight: 700; border: none; cursor: pointer;
    transition: all .15s; white-space: nowrap; text-decoration: none; }
.ab:hover { transform: translateY(-1px); }
.ab-view   { background: #e8eaf6; color: #3949ab; border: 1px solid #c5cae9; }
.ab-view:hover { background: #3949ab; color: #fff; }
.ab-ok     { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
.ab-ok:hover { background: #43a047; color: #fff; border-color: #43a047; }
.ab-no     { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }
.ab-no:hover { background: #e53935; color: #fff; border-color: #e53935; }

/* Empty state */
.empty-wrap { text-align: center; padding: 70px 30px; }
.empty-wrap .e-icon { font-size: 64px; color: #43a047; display: block; margin-bottom: 16px;
    background: rgba(67,160,71,.1); width: 110px; height: 110px; line-height: 110px;
    border-radius: 50%; margin: 0 auto 20px; animation: float-e 3s ease-in-out infinite; }
@keyframes float-e { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
</style>

<section class="content-header" style="padding:10px 20px 5px;">
    <h1 style="font-size:18px;margin:0;">
        <i class="bi bi-hourglass-split" style="color:#fb8c00;"></i>
        Pending Requests
        <small style="font-size:13px;color:#888;margin-left:8px;">Awaiting action</small>
    </h1>
</section>

<div class="tr-page">

    {{-- Header --}}
    <div class="tr-header">
        <div class="row" style="align-items:center;">
            <div class="col-md-8">
                <h2>
                    <i class="bi bi-hourglass-split" style="opacity:.8;margin-right:6px;"></i>
                    Pending Transfer Requests
                    @if($transfers->count() > 0)
                        <span style="font-size:13px;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.4);
                            padding:3px 11px;border-radius:20px;vertical-align:middle;margin-left:8px;">
                            {{ $transfers->count() }} Pending
                        </span>
                    @endif
                </h2>
                <div class="sub">
                    <span><i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}</span>
                    @if($transfers->where('priority','urgent')->count() > 0)
                        <span style="background:rgba(229,57,53,.4);padding:2px 10px;border-radius:12px;">
                            <i class="bi bi-lightning-fill"></i> {{ $transfers->where('priority','urgent')->count() }} Urgent
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('dashboard.stock-transfers.index') }}"
                   style="background:rgba(255,255,255,.15);color:#fff;padding:8px 16px;border-radius:8px;
                   text-decoration:none;font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,.3);">
                    <i class="bi bi-arrow-left"></i> All Transfers
                </a>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
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

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-pill s-orange">
            <div class="n">{{ $transfers->count() }}</div>
            <div class="l">Total Pending</div>
        </div>
        <div class="stat-pill s-red">
            @php $urgentCount = $transfers->where('priority','urgent')->count(); @endphp
            <div class="n">{{ $urgentCount }}</div>
            <div class="l">Urgent</div>
        </div>
        <div class="stat-pill s-blue">
            @php $lateCount = $transfers->filter(function($t){ return $t->days_old > 3; })->count(); @endphp
            <div class="n">{{ $lateCount }}</div>
            <div class="l">Late (+3 days)</div>
        </div>
        <div class="stat-pill" style="border-color:#43a047;">
            <div class="n" style="color:#2e7d32;">{{ $transfers->sum('quantity') }}</div>
            <div class="l">Total Units</div>
        </div>
    </div>

    {{-- Main card --}}
    <div class="main-card">

        @if($transfers->count() > 0)

        <div class="table-responsive">
            <table class="pt-table">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Product</th>
                        <th width="80" style="text-align:center;">Qty</th>
                        <th>From → To</th>
                        <th>Requester</th>
                        <th width="70" style="text-align:center;">Age</th>
                        <th width="170" style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transfers as $transfer)
                    @php $isLate = $transfer->days_old > 3; @endphp
                    <tr class="{{ $isLate ? 'late-row' : '' }}" id="row-{{ $transfer->id }}">

                        {{-- Request # --}}
                        <td>
                            @if($transfer->batch_number)
                                <a href="{{ route('dashboard.stock-transfers.batch', $transfer->batch_number) }}" class="tf-num">
                                    <i class="bi bi-collection" style="font-size:11px;"></i> {{ $transfer->batch_number }}
                                </a>
                                <div class="batch-tag"><i class="bi bi-layers"></i> Batch</div>
                            @else
                                <a href="{{ route('dashboard.stock-transfers.show', $transfer) }}" class="tf-num">
                                    {{ $transfer->transfer_number }}
                                </a>
                            @endif
                            <div style="font-size:11px;color:#aaa;margin-top:2px;">
                                <i class="bi bi-calendar3"></i> {{ $transfer->created_at->format('d M Y') }}
                            </div>
                        </td>

                        {{-- Product --}}
                        <td>
                            <div class="prod-code">{{ $transfer->stockable->product_id ?? '—' }}</div>
                            <div class="prod-name">{{ Str::limit($transfer->stockable->description ?? $transfer->item_description, 35) }}</div>
                        </td>

                        {{-- Qty --}}
                        <td style="text-align:center;">
                            <span class="qty-n">{{ $transfer->quantity }}</span>
                            <div style="font-size:10px;color:#aaa;">units</div>
                        </td>

                        {{-- Route --}}
                        <td>
                            <span class="br-pill store">
                                <i class="bi bi-building"></i> {{ $transfer->fromBranch->name ?? '—' }}
                            </span>
                            <span style="color:#aaa;margin:0 4px;font-size:13px;">→</span>
                            <span class="br-pill">
                                <i class="bi bi-building"></i> {{ $transfer->toBranch->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Requester --}}
                        <td>
                            <div style="font-weight:600;color:#2c3e50;font-size:13px;">
                                {{ $transfer->creator->name ?? '—' }}
                            </div>
                            <div style="font-size:11px;color:#aaa;">
                                {{ $transfer->created_at->diffForHumans() }}
                            </div>
                        </td>

                        {{-- Age --}}
                        <td style="text-align:center;">
                            @if($transfer->days_old === 0)
                                <span class="age-ok">Today</span>
                            @elseif($transfer->days_old <= 3)
                                <span class="age-ok">{{ $transfer->days_old }}d</span>
                            @elseif($transfer->days_old <= 7)
                                <span class="age-warn"><i class="bi bi-exclamation-triangle"></i> {{ $transfer->days_old }}d</span>
                            @else
                                <span class="age-late"><i class="bi bi-exclamation-circle-fill"></i> {{ $transfer->days_old }}d</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="acts">
                                <a href="{{ $transfer->batch_number
                                    ? route('dashboard.stock-transfers.batch', $transfer->batch_number)
                                    : route('dashboard.stock-transfers.show', $transfer) }}"
                                   class="ab ab-view">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <button class="ab ab-ok" onclick="acceptOne({{ $transfer->id }}, '{{ $transfer->transfer_number }}')">
                                    <i class="bi bi-check-lg"></i> Accept
                                </button>
                                <button class="ab ab-no" onclick="rejectOne({{ $transfer->id }}, '{{ $transfer->transfer_number }}')">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 18px;background:#f8f9ff;border-top:1px solid #e8ecf7;font-size:13px;color:#888;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <span>
                <i class="bi bi-info-circle"></i>
                <strong style="color:#3949ab;">{{ $transfers->count() }} requests</strong>
                · <strong style="color:#2c3e50;">{{ $transfers->sum('quantity') }} total units</strong>
            </span>
            @if($transfers->hasPages())
                {{ $transfers->links() }}
            @endif
        </div>

        @else

        <div class="empty-wrap">
            <span class="e-icon"><i class="bi bi-check-circle-fill"></i></span>
            <h3 style="color:#43a047;font-weight:800;margin-bottom:8px;">All Clear!</h3>
            <p style="color:#888;font-size:15px;margin-bottom:20px;">No pending transfer requests at the moment.</p>
            <a href="{{ route('dashboard.stock-transfers.index') }}"
               style="background:linear-gradient(135deg,#43a047,#2e7d32);color:#fff;padding:11px 24px;border-radius:10px;
               text-decoration:none;font-size:14px;font-weight:700;display:inline-block;
               box-shadow:0 4px 14px rgba(67,160,71,.35);">
                <i class="bi bi-list-ul"></i> View All Transfers
            </a>
        </div>

        @endif
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

function acceptOne(id, num) {
    Swal.fire({
        title: 'Accept this transfer?',
        html: '<b>' + num + '</b><br><small style="color:#888;margin-top:5px;display:block;">Stock will be added to the destination branch immediately.</small>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#43a047',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-check-circle-fill"></i> Accept & Add to Stock',
        cancelButtonText: 'Cancel'
    }).then(function(r) {
        if (!r.isConfirmed) return;
        var f = document.getElementById('fAccept');
        f.action = '/dashboard/stock-transfers/' + id + '/accept';
        f.submit();
    });
}

function rejectOne(id, num) {
    Swal.fire({
        title: 'Reject this transfer?',
        html: '<b>' + num + '</b><br>' +
              '<textarea id="ri" class="swal2-input" placeholder="Reason for rejection…" style="height:80px;resize:none;margin-top:10px;"></textarea>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="bi bi-x-circle-fill"></i> Reject',
        cancelButtonText: 'Cancel',
        preConfirm: function() {
            var v = document.getElementById('ri').value.trim();
            if (!v) { Swal.showValidationMessage('Please enter a reason'); return false; }
            return v;
        }
    }).then(function(r) {
        if (!r.isConfirmed) return;
        document.getElementById('rejR').value = r.value;
        var f = document.getElementById('fReject');
        f.action = '/dashboard/stock-transfers/' + id + '/reject';
        f.submit();
    });
}
</script>
@endsection
