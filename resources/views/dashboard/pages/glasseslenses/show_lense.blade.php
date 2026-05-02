@extends('dashboard.layouts.master')
@section('title', 'Lens · ' . $lens->product_id)
@section('content')

<style>
/* ── Page ── */
.ls-show { padding: 20px; }

/* ── Header box ── */
.box-lens-show { border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,.10); overflow:hidden; border:none; margin-bottom:22px; }
.box-lens-show .box-header {
    background: linear-gradient(135deg,#9b59b6,#8e44ad);
    color:#fff; padding:24px 30px; border:none; position:relative; overflow:hidden;
}
.box-lens-show .box-header::before {
    content:''; position:absolute; top:-50%; right:-8%;
    width:200px; height:200px; background:rgba(255,255,255,.10); border-radius:50%;
}
.box-lens-show .box-header .box-title { font-size:21px; font-weight:700; position:relative; z-index:1; }
.box-lens-show .box-header .btn {
    position:relative; z-index:1; background:rgba(255,255,255,.2);
    border:2px solid rgba(255,255,255,.4); color:#fff; padding:8px 18px;
    border-radius:8px; font-weight:600; transition:.3s; margin-left:6px;
}
.box-lens-show .box-header .btn:hover { background:#fff; color:#8e44ad; transform:translateY(-2px); }

/* ── Branch filter bar ── */
.branch-bar {
    background:#fff; border-radius:10px; padding:14px 20px;
    margin-bottom:20px; border:2px solid #e8ecf7;
    display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.branch-bar label { margin:0; font-weight:700; color:#555; font-size:13px; }
.branch-bar select { border:2px solid #e0e6ed; border-radius:8px; padding:7px 14px; font-size:13px; min-width:200px; }
.branch-bar select:focus { outline:none; border-color:#9b59b6; }

/* ── Summary stats row ── */
.stat-row { display:flex; gap:14px; margin-bottom:22px; flex-wrap:wrap; }
.stat-card {
    flex:1; min-width:150px; background:#fff; border-radius:12px;
    padding:20px 22px; border:2px solid #e8ecf7;
    box-shadow:0 2px 8px rgba(0,0,0,.05); display:flex; align-items:center; gap:14px; transition:.3s;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 6px 18px rgba(0,0,0,.10); }
.stat-ico { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; flex-shrink:0; }
.stat-ico.stock   { background:linear-gradient(135deg,#9b59b6,#8e44ad); }
.stat-ico.in      { background:linear-gradient(135deg,#27ae60,#219a52); }
.stat-ico.out     { background:linear-gradient(135deg,#e74c3c,#c0392b); }
.stat-ico.sold    { background:linear-gradient(135deg,#f39c12,#e67e22); }
.stat-num { font-size:28px; font-weight:800; color:#2c3e50; margin:0; line-height:1; }
.stat-lbl { font-size:12px; color:#888; font-weight:600; margin-top:3px; }

/* ── Branch summary cards ── */
.branch-cards { display:flex; gap:14px; margin-bottom:22px; flex-wrap:wrap; }
.branch-card {
    flex:1; min-width:200px; background:#fff; border-radius:12px; padding:20px;
    border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.05);
    border-top:4px solid #9b59b6;
}
.branch-card .bc-name { font-size:14px; font-weight:700; color:#9b59b6; margin-bottom:14px; }
.branch-card .bc-name i { background:linear-gradient(135deg,#9b59b6,#8e44ad); color:#fff; padding:5px 8px; border-radius:6px; margin-right:6px; font-size:13px; }
.bc-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px solid #f0f2f5; font-size:13px; }
.bc-row:last-child { border-bottom:none; }
.bc-row .lbl { color:#888; }
.bc-row .val { font-weight:700; }
.bc-row .val.green { color:#27ae60; }
.bc-row .val.red   { color:#e74c3c; }
.bc-row .val.blue  { color:#1565c0; }
.bc-row .val.purple { color:#8e44ad; }
.bc-stock-total { font-size:26px; font-weight:800; color:#2c3e50; text-align:center; padding:10px 0 4px; }
.bc-rl { display:flex; justify-content:center; gap:10px; margin-bottom:8px; }
.rl-badge { padding:3px 12px; border-radius:20px; font-size:12px; font-weight:700; }
.rl-badge.R { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
.rl-badge.L { background:#f3e5f5; color:#6a1b9a; border:1px solid #ce93d8; }
.rl-badge.unk { background:#f4f6fb; color:#888; border:1px solid #ddd; }

/* ── Info sections ── */
.info-section {
    background:#fff; border-radius:12px; padding:24px;
    margin-bottom:20px; border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.04);
}
.info-section .sec-title {
    font-size:16px; font-weight:700; color:#9b59b6;
    margin-bottom:18px; padding-bottom:10px; border-bottom:2px solid #f0f2f5;
    display:flex; align-items:center; gap:8px;
}
.info-section .sec-title i {
    background:linear-gradient(135deg,#9b59b6,#8e44ad); color:#fff;
    width:32px; height:32px; border-radius:8px;
    display:inline-flex; align-items:center; justify-content:center; font-size:15px;
}
.info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(230px,1fr)); gap:12px; }
.info-item {
    background:#f8f9ff; border-radius:8px; padding:12px 14px;
    display:flex; flex-direction:column; gap:3px;
}
.info-item .lbl { font-size:11px; font-weight:700; color:#9b59b6; text-transform:uppercase; letter-spacing:.5px; }
.info-item .val { font-size:14px; font-weight:700; color:#2c3e50; }

/* ── Timeline ── */
.timeline-wrap { position:relative; padding-left:40px; }
.timeline-wrap::before {
    content:''; position:absolute; left:15px; top:0; bottom:0;
    width:3px; background:linear-gradient(to bottom,#9b59b6,#e8ecf7);
    border-radius:2px;
}
.tl-item { position:relative; margin-bottom:20px; }
.tl-dot {
    position:absolute; left:-32px; top:12px;
    width:26px; height:26px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; color:#fff; box-shadow:0 2px 8px rgba(0,0,0,.2); z-index:1;
}
.tl-dot.in  { background:linear-gradient(135deg,#27ae60,#219a52); }
.tl-dot.out { background:linear-gradient(135deg,#e74c3c,#c0392b); }
.tl-card {
    background:#fff; border-radius:10px; padding:14px 18px;
    border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.04);
    transition:.2s;
}
.tl-card:hover { border-color:#9b59b6; box-shadow:0 4px 14px rgba(155,89,182,.12); }
.tl-card.in  { border-left:4px solid #27ae60; }
.tl-card.out { border-left:4px solid #e74c3c; }
.tl-header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:8px; }
.tl-dir-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700;
}
.tl-dir-badge.in  { background:#e8f8f5; color:#27ae60; }
.tl-dir-badge.out { background:#fde8e8; color:#e74c3c; }
.tl-qty { font-size:22px; font-weight:800; }
.tl-qty.in  { color:#27ae60; }
.tl-qty.out { color:#e74c3c; }
.tl-meta { display:flex; gap:10px; flex-wrap:wrap; font-size:12px; color:#888; }
.tl-meta span { display:flex; align-items:center; gap:4px; }
.tl-source {
    display:inline-flex; align-items:center; gap:5px;
    background:#f0f4ff; color:#555; padding:3px 10px;
    border-radius:12px; font-size:12px; font-weight:600;
}

/* ── Empty timeline ── */
.tl-empty { text-align:center; padding:50px 20px; color:#bbb; }
.tl-empty i { font-size:52px; display:block; margin-bottom:10px; }
</style>

<section class="content-header">
    <h1><i class="bi bi-eyeglasses"></i> Lens Details <small>{{ $lens->product_id }}</small></h1>
</section>

<div class="ls-show">

    {{-- ═══ Header ═══ --}}
    <div class="box box-primary box-lens-show">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="bi bi-eyeglasses" style="background:rgba(255,255,255,.2);padding:8px;border-radius:8px;margin-right:8px;"></i>
                {{ $lens->product_id }} — {{ $lens->description }}
            </h3>
            <div class="box-tools pull-right">
                <a href="{{ route('dashboard.get-edit-glense', $lens->id) }}" class="btn btn-sm">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ route('dashboard.get-glassess-lenses') }}" class="btn btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ Branch Filter (admins only) ═══ --}}
    @if(auth()->user()->canSeeAllBranches())
    <div class="branch-bar">
        <label><i class="bi bi-building"></i> Branch:</label>
        <form method="GET" action="{{ request()->url() }}" style="display:flex;gap:8px;align-items:center;">
            <select name="branch_id" onchange="this.form.submit()">
                <option value="">All Branches</option>
                @foreach($accessibleBranches as $br)
                    <option value="{{ $br->id }}" {{ $selectedBranchId == $br->id ? 'selected' : '' }}>
                        {{ $br->name }}
                    </option>
                @endforeach
            </select>
            @if($selectedBranchId)
                <a href="{{ request()->url() }}" style="font-size:12px;color:#e74c3c;text-decoration:none;">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            @endif
        </form>
    </div>
    @endif

    {{-- ═══ Overall Stats ═══ --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-ico stock"><i class="bi bi-boxes"></i></div>
            <div>
                <div class="stat-num">{{ $overallStock }}</div>
                <div class="stat-lbl">Current Stock</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ico in"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <div>
                <div class="stat-num" style="color:#27ae60;">{{ $overallIn }}</div>
                <div class="stat-lbl">Total Received (IN)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ico out"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div>
                <div class="stat-num" style="color:#e74c3c;">{{ $overallOut }}</div>
                <div class="stat-lbl">Total Delivered (OUT)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-ico sold"><i class="bi bi-cart-check-fill"></i></div>
            <div>
                <div class="stat-num" style="color:#e67e22;">{{ $totalSold }}</div>
                <div class="stat-lbl">Total Sold (Invoices)</div>
            </div>
        </div>
    </div>

    {{-- ═══ Per-Branch Cards ═══ --}}
    @if(count($branchSummaries) > 0)
    <div class="branch-cards">
        @foreach($branchSummaries as $bs)
        <div class="branch-card">
            <div class="bc-name"><i class="bi bi-building"></i> {{ $bs['branch']->name }}</div>
            <div class="bc-stock-total">{{ $bs['current_stock'] }}</div>
            <div style="text-align:center;font-size:11px;color:#888;margin-bottom:10px;">Current Stock</div>
            <div class="bc-rl">
                @if($bs['stock_R'] > 0)
                    <span class="rl-badge R"><i class="bi bi-arrow-right-circle-fill"></i> R: {{ $bs['stock_R'] }}</span>
                @endif
                @if($bs['stock_L'] > 0)
                    <span class="rl-badge L"><i class="bi bi-arrow-left-circle-fill"></i> L: {{ $bs['stock_L'] }}</span>
                @endif
                @if($bs['stock_unk'] > 0)
                    <span class="rl-badge unk"><i class="bi bi-question-circle"></i> {{ $bs['stock_unk'] }}</span>
                @endif
            </div>
            <div class="bc-row">
                <span class="lbl"><i class="bi bi-arrow-down-circle"></i> Received IN</span>
                <span class="val green">+{{ $bs['total_in'] }}</span>
            </div>
            <div class="bc-row">
                <span class="lbl"><i class="bi bi-arrow-up-circle"></i> Delivered OUT</span>
                <span class="val red">-{{ $bs['total_out'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ═══ Lens Info ═══ --}}
    <div class="info-section">
        <div class="sec-title"><i class="bi bi-info-square-fill"></i> Lens Information</div>
        <div class="info-grid">
            <div class="info-item"><span class="lbl">Lens ID</span><span class="val">{{ $lens->product_id }}</span></div>
            <div class="info-item"><span class="lbl">Description</span><span class="val">{{ $lens->description ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Brand</span><span class="val">{{ $lens->brand ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Index</span><span class="val">{{ $lens->index ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Lens Type</span><span class="val">{{ $lens->lense_type ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Frame Type</span><span class="val">{{ $lens->frame_type ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Technology</span><span class="val">{{ $lens->lense_tech ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Production</span><span class="val">{{ $lens->lense_production ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Life Style</span><span class="val">{{ $lens->life_style ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Customer Activity</span><span class="val">{{ $lens->customer_activity ?? '—' }}</span></div>
            <div class="info-item"><span class="lbl">Cost Price</span><span class="val">{{ number_format($lens->price, 2) }} QAR</span></div>
            <div class="info-item"><span class="lbl">Retail Price</span><span class="val">{{ number_format($lens->retail_price, 2) }} QAR</span></div>
        </div>
    </div>

    {{-- ═══ Stock Movement Timeline ═══ --}}
    <div class="info-section">
        <div class="sec-title"><i class="bi bi-clock-history"></i> Stock Movement Timeline</div>

        @if($timeline->isNotEmpty())
        <div class="timeline-wrap">
            @foreach($timeline as $entry)
            @php
                $isIn  = $entry->direction === 'in';
                $dir   = $isIn ? 'in' : 'out';
                $sign  = $isIn ? '+' : '−';

                // Source label
                if ($entry->source_type === 'purchase_order') {
                    $srcLabel = 'Lab Order';
                    $srcRef   = $poMap->get($entry->source_id) ?? '#'.$entry->source_id;
                    $srcIcon  = 'bi-flask';
                } elseif ($entry->source_type === 'invoice_delivery') {
                    $srcLabel = 'Invoice Delivery';
                    $srcRef   = $invMap->get($entry->source_id) ?? '#'.$entry->source_id;
                    $srcIcon  = 'bi-receipt';
                } else {
                    $srcLabel = ucfirst(str_replace('_', ' ', $entry->source_type));
                    $srcRef   = '#'.$entry->source_id;
                    $srcIcon  = 'bi-box';
                }

                // Side
                $sideLabel = null;
                if ($entry->side === 'R') {
                    $sideLabel = ['txt' => 'Right Eye', 'cls' => 'R', 'ico' => 'bi-arrow-right-circle-fill'];
                } elseif ($entry->side === 'L') {
                    $sideLabel = ['txt' => 'Left Eye',  'cls' => 'L', 'ico' => 'bi-arrow-left-circle-fill'];
                }
            @endphp

            <div class="tl-item">
                <div class="tl-dot {{ $dir }}">
                    <i class="bi {{ $isIn ? 'bi-arrow-down' : 'bi-arrow-up' }}"></i>
                </div>
                <div class="tl-card {{ $dir }}">
                    <div class="tl-header">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span class="tl-dir-badge {{ $dir }}">
                                <i class="bi {{ $isIn ? 'bi-arrow-down-circle-fill' : 'bi-arrow-up-circle-fill' }}"></i>
                                {{ $isIn ? 'Stock IN' : 'Stock OUT' }}
                            </span>
                            @if($sideLabel)
                                <span class="rl-badge {{ $sideLabel['cls'] }}">
                                    <i class="bi {{ $sideLabel['ico'] }}"></i> {{ $sideLabel['txt'] }}
                                </span>
                            @endif
                            <span class="tl-source">
                                <i class="bi {{ $srcIcon }}"></i>
                                {{ $srcLabel }}: <strong>{{ $srcRef }}</strong>
                            </span>
                        </div>
                        <span class="tl-qty {{ $dir }}">{{ $sign }}{{ $entry->quantity }}</span>
                    </div>
                    <div class="tl-meta">
                        <span><i class="bi bi-building"></i> {{ $entry->branch->name ?? '—' }}</span>
                        <span><i class="bi bi-calendar3"></i> {{ $entry->created_at->format('d M Y') }}</span>
                        <span><i class="bi bi-clock"></i> {{ $entry->created_at->format('H:i') }}</span>
                        @if($entry->user)
                            <span><i class="bi bi-person-fill"></i> {{ $entry->user->name }}</span>
                        @endif
                        @if($entry->notes)
                            <span><i class="bi bi-chat-text"></i> {{ $entry->notes }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="tl-empty">
            <i class="bi bi-clock-history"></i>
            <p>No stock movements recorded yet for this lens{{ $selectedBranchId ? ' in the selected branch' : '' }}.</p>
        </div>
        @endif
    </div>

</div>
@endsection
