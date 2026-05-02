@extends('dashboard.layouts.master')
@section('title', 'هالك — Damaged Lenses Management')
@section('content')

<style>
.halak-page { padding: 20px; }

/* ── Header ── */
.halak-header {
    background: linear-gradient(135deg,#7b1fa2,#c62828);
    color:#fff; border-radius:14px;
    padding:26px 30px; margin-bottom:22px;
    box-shadow:0 6px 24px rgba(0,0,0,.2);
}
.halak-header h2 { margin:0 0 4px; font-size:24px; font-weight:700; }
.halak-header p  { opacity:.8; font-size:13px; margin:0; }

/* ── Stat cards ── */
.stat-box { background:#fff; border-radius:12px; padding:20px 24px; text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.06); }
.stat-box .num { font-size:36px; font-weight:900; line-height:1; }
.stat-box .lbl { font-size:12px; color:#888; text-transform:uppercase; letter-spacing:.5px; margin-top:6px; }
.stat-box.red   { border-left:4px solid #e74c3c; }
.stat-box.green { border-left:4px solid #27ae60; }
.stat-box.orange{ border-left:4px solid #f39c12; }

/* ── Entry row ── */
.entry-row {
    background:#fff; border:1px solid #f5c6cb;
    border-left:4px solid #e74c3c;
    border-radius:10px; padding:16px 20px;
    margin-bottom:10px;
    transition:box-shadow .2s;
}
.entry-row:hover { box-shadow:0 3px 14px rgba(231,76,60,.12); }
.entry-row.fully-recovered { border-left-color:#27ae60; opacity:.65; }

.lens-name  { font-size:14px; font-weight:700; color:#2c3e50; }
.lens-meta  { font-size:12px; color:#888; margin-top:3px; }

.qty-badge { display:inline-block; padding:3px 12px; border-radius:20px; font-size:13px; font-weight:700; }
.qty-damaged  { background:#f8d7da; color:#721c24; }
.qty-recovered{ background:#d4edda; color:#155724; }
.qty-remaining{ background:#fff3cd; color:#856404; }
.qty-zero     { background:#e9ecef; color:#6c757d; }

/* ── Recover form ── */
.recover-form { background:#f0fff4; border:2px solid #b2dfdb; border-radius:8px; padding:14px 16px; margin-top:12px; display:none; }
.recover-form.open { display:block; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-exclamation-triangle-fill" style="color:#e74c3c;"></i>
        هالك — Damaged Lenses
        <a href="{{ route('dashboard.lens-purchase-orders.index') }}"
           style="margin-left:10px;font-size:13px;background:#e8ecf7;color:#555;padding:6px 14px;border-radius:8px;text-decoration:none;vertical-align:middle;">
            <i class="bi bi-arrow-left"></i> Back to Lab Orders
        </a>
    </h1>
</section>

<div class="halak-page">

    {{-- ══ Header ══ --}}
    <div class="halak-header">
        <div class="row" style="align-items:center;">
            <div class="col-md-7">
                <h2><i class="bi bi-exclamation-triangle-fill" style="opacity:.8;margin-right:8px;"></i>Damaged Lenses (هالك)</h2>
                <p>View all damaged lens entries and recover them back to stock when repaired or re-evaluated.</p>
            </div>
            <div class="col-md-5 text-right">
                @if(auth()->user()->canSeeAllBranches())
                    <form method="GET" style="display:inline-block;">
                        <select name="branch_id" class="form-control" onchange="this.form.submit()"
                                style="display:inline-block;width:200px;border-radius:8px;">
                            <option value="">All Branches</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="border-left:5px solid #27ae60;border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="border-left:5px solid #e74c3c;border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- ══ Stats ══ --}}
    <div class="row" style="margin-bottom:22px;">
        <div class="col-md-4">
            <div class="stat-box red">
                <div class="num" style="color:#e74c3c;">{{ $totalDamaged }}</div>
                <div class="lbl"><i class="bi bi-exclamation-triangle"></i> Total Damaged Lenses</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box green">
                <div class="num" style="color:#27ae60;">{{ $totalRecovered }}</div>
                <div class="lbl"><i class="bi bi-arrow-counterclockwise"></i> Total Recovered</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box orange">
                <div class="num" style="color:#f39c12;">{{ $netDamaged }}</div>
                <div class="lbl"><i class="bi bi-hourglass-split"></i> Net Still Damaged</div>
            </div>
        </div>
    </div>

    {{-- ══ Damaged Entries ══ --}}
    <div style="background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.05);border:1px solid #e8ecf7;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:12px;border-bottom:2px solid #e8ecf7;">
            <h4 style="margin:0;font-size:15px;font-weight:700;color:#2c3e50;">
                <i class="bi bi-list-ul" style="color:#e74c3c;"></i> Damaged Lens Entries
            </h4>
            <span style="background:#f8d7da;color:#721c24;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700;">
                {{ $damaged->total() }} record(s)
            </span>
        </div>

        @forelse($damaged as $entry)
            @php $fullyRecovered = $entry->remaining_damaged <= 0; @endphp
            <div class="entry-row {{ $fullyRecovered ? 'fully-recovered' : '' }}" id="entry-{{ $entry->id }}">
                <div class="row" style="align-items:center;">

                    {{-- Lens Info --}}
                    <div class="col-md-4">
                        <div class="lens-name">
                            <i class="bi bi-eyeglasses" style="color:#9b59b6;"></i>
                            {{ $entry->lens->description ?? 'Unknown Lens' }}
                        </div>
                        <div class="lens-meta">
                            @if($entry->lens)
                                Code: <strong>{{ $entry->lens->product_id }}</strong>
                                @if($entry->lens->brand) · {{ $entry->lens->brand }} @endif
                                @if($entry->lens->index) · Index {{ $entry->lens->index }} @endif
                            @endif
                            @if($entry->side)
                                · <span style="background:{{ $entry->side==='R' ? '#e3f2fd' : '#f3e5f5' }};color:{{ $entry->side==='R' ? '#1565c0' : '#6a1b9a' }};padding:1px 7px;border-radius:8px;font-size:11px;font-weight:700;">
                                    {{ $entry->side === 'R' ? 'Right' : 'Left' }}
                                </span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#aaa;margin-top:4px;">
                            <i class="bi bi-building"></i> {{ $entry->branch->name ?? '—' }}
                            · <i class="bi bi-person"></i> {{ $entry->user->name ?? '—' }}
                            · {{ $entry->created_at->format('d/m/Y') }}
                        </div>
                        @php
                            $invoice   = $entry->sourcePo->invoice ?? null;
                            $po        = $entry->sourcePo ?? null;
                        @endphp
                        @if($invoice)
                        <div style="margin-top:6px;display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap;">
                            <span style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;
                                         padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                <i class="bi bi-receipt"></i>
                                Invoice: {{ $invoice->invoice_code }}
                            </span>
                            @if($po)
                            <span style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;
                                         padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                <i class="bi bi-flask"></i>
                                PO: {{ $po->po_number }}
                            </span>
                            @endif
                        </div>
                        @endif
                        @if($entry->notes)
                            <div style="font-size:11px;color:#e67e22;margin-top:3px;">
                                <i class="bi bi-chat-text"></i> {{ Str::limit($entry->notes, 60) }}
                            </div>
                        @endif
                    </div>

                    {{-- Quantities --}}
                    <div class="col-md-5">
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                            <div style="text-align:center;">
                                <div class="qty-badge qty-damaged">{{ $entry->quantity }}</div>
                                <div style="font-size:10px;color:#888;margin-top:3px;">Damaged</div>
                            </div>
                            <div style="color:#ccc;font-size:18px;">→</div>
                            <div style="text-align:center;">
                                <div class="qty-badge qty-recovered">{{ $entry->recovered_qty }}</div>
                                <div style="font-size:10px;color:#888;margin-top:3px;">Recovered</div>
                            </div>
                            <div style="color:#ccc;font-size:18px;">=</div>
                            <div style="text-align:center;">
                                <div class="qty-badge {{ $entry->remaining_damaged > 0 ? 'qty-remaining' : 'qty-zero' }}">
                                    {{ $entry->remaining_damaged }}
                                </div>
                                <div style="font-size:10px;color:#888;margin-top:3px;">Still Damaged</div>
                            </div>
                            @if($fullyRecovered)
                                <span style="background:#d4edda;color:#155724;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;">
                                    <i class="bi bi-check-circle-fill"></i> Fully Recovered
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="col-md-3 text-right">
                        @if(!$fullyRecovered)
                            @can('edit-stock')
                                <button type="button"
                                        class="btn btn-success btn-sm"
                                        onclick="toggleRecoverForm({{ $entry->id }})"
                                        style="border-radius:8px;font-weight:600;padding:8px 16px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Recover to Stock
                                </button>
                            @endcan
                        @else
                            <span style="color:#27ae60;font-size:13px;font-weight:600;">
                                <i class="bi bi-check-circle-fill"></i> Done
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Recover Form (hidden by default) --}}
                @if(!$fullyRecovered)
                    <div class="recover-form" id="recoverForm-{{ $entry->id }}">
                        <form action="{{ route('dashboard.lens-purchase-orders.recover-damaged', $entry->id) }}"
                              method="POST">
                            @csrf
                            <div class="row" style="align-items:flex-end;">
                                <div class="col-md-3">
                                    <label style="font-size:12px;font-weight:700;color:#004d40;">Quantity to Recover</label>
                                    <input type="number" name="recover_qty" class="form-control form-control-sm"
                                           value="{{ $entry->remaining_damaged }}"
                                           min="1" max="{{ $entry->remaining_damaged }}" required
                                           style="border:2px solid #27ae60;border-radius:6px;text-align:center;font-size:16px;font-weight:700;">
                                    <small style="color:#888;">Max: {{ $entry->remaining_damaged }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:700;color:#004d40;">Reason / Notes</label>
                                    <input type="text" name="notes" class="form-control form-control-sm"
                                           placeholder="e.g. Re-evaluated, repaired, coating fixed..."
                                           style="border:2px solid #27ae60;border-radius:6px;">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-sm btn-block"
                                            style="border-radius:6px;font-weight:700;padding:8px;">
                                        <i class="bi bi-check-circle"></i> Confirm Recovery
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm btn-block"
                                            onclick="toggleRecoverForm({{ $entry->id }})"
                                            style="border-radius:6px;margin-top:4px;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align:center;padding:50px;color:#aaa;">
                <i class="bi bi-check-circle" style="font-size:56px;display:block;margin-bottom:14px;color:#27ae60;"></i>
                <h4 style="color:#666;">No damaged lens entries found</h4>
                <p style="font-size:13px;">Damaged lenses will appear here when marked as هالك during re-orders.</p>
            </div>
        @endforelse

        @if($damaged->hasPages())
            <div style="margin-top:20px;">
                {{ $damaged->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
function toggleRecoverForm(id) {
    var form = document.getElementById('recoverForm-' + id);
    form.classList.toggle('open');
}
</script>
@endsection
