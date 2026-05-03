@extends('dashboard.layouts.master')
@section('title', 'Stock Movement History')
@section('content')

<style>
/* ── Reset content-header ── */
.content-header { margin:0; padding:0; height:0; overflow:hidden; }

/* ── Hero ── */
.smh-hero {
    margin: -20px -20px 0;
    background: linear-gradient(135deg, #0f172a 0%, #1a2f4e 40%, #0d2137 75%, #0f172a 100%);
    padding: 34px 40px 70px;
    position: relative;
    overflow: hidden;
}
.smh-hero::before {
    content:'';
    position:absolute; top:-60px; right:-60px;
    width:320px; height:320px;
    background:radial-gradient(circle, rgba(99,179,237,.12) 0%, transparent 70%);
    border-radius:50%;
}
.smh-hero::after {
    content:'';
    position:absolute; bottom:-40px; left:10%;
    width:200px; height:200px;
    background:radial-gradient(circle, rgba(129,140,248,.10) 0%, transparent 70%);
    border-radius:50%;
}
.smh-hero-back {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: #ffffff;
    color: #1e293b;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 22px;
    border-radius: 10px;
    text-decoration: none;
    margin-bottom: 24px;
    border: none;
    box-shadow: 0 4px 14px rgba(0,0,0,.25);
    transition: background .18s, box-shadow .18s, transform .18s;
    position: relative;
    z-index: 2;
    letter-spacing: .2px;
}
.smh-hero-back i {
    font-size: 16px;
    color: #3b82f6;
    transition: transform .18s;
}
.smh-hero-back:hover {
    background: #f1f5f9;
    color: #1e293b;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(0,0,0,.30);
    transform: translateY(-2px);
}
.smh-hero-back:hover i {
    transform: translateX(-3px);
}
.smh-hero-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18);
    color:rgba(255,255,255,.85); padding:4px 14px; border-radius:20px;
    font-size:12px; font-weight:600; margin-bottom:14px;
}
.smh-hero-title {
    font-size:28px; font-weight:800; color:#fff; margin:0 0 6px; line-height:1.2;
}
.smh-hero-sub {
    font-size:14px; color:rgba(255,255,255,.55); margin:0;
}
.smh-hero-meta {
    display:flex; flex-wrap:wrap; gap:16px; margin-top:14px;
}
.smh-meta-pill {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.13);
    color:rgba(255,255,255,.75); padding:5px 14px; border-radius:20px; font-size:12px;
}

/* ── Stats Strip ── */
.smh-stats-outer {
    padding: 0 20px;
    margin-top: -32px;
    position: relative; z-index: 10;
}
.smh-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.smh-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 18px;
    box-shadow: 0 4px 20px rgba(0,0,0,.10);
    border: 1.5px solid #f0f4f8;
    display: flex; align-items: center; gap: 14px;
}
.smh-stat-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.smh-stat-icon.teal    { background:linear-gradient(135deg,#0d9488,#14b8a6); color:#fff; }
.smh-stat-icon.green   { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; }
.smh-stat-icon.red     { background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; }
.smh-stat-icon.blue    { background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; }
.smh-stat-num { font-size:26px; font-weight:900; color:#1e293b; line-height:1; }
.smh-stat-lbl { font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-top:3px; }

/* ── Content Area ── */
.smh-body { padding: 24px 20px 30px; }

/* ── Timeline ── */
.smh-timeline { position:relative; padding: 10px 0; }
.smh-timeline::before {
    content:'';
    position:absolute;
    left: 38px;
    top:0; bottom:0;
    width: 3px;
    background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 3px;
}
.smh-item {
    position:relative;
    padding: 18px 0 18px 86px;
    animation: smi-fadein .4s ease-out;
}
@keyframes smi-fadein {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}
.smh-dot {
    position:absolute;
    left: 22px; top: 26px;
    width: 34px; height: 34px;
    border-radius: 50%;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:15px;
    box-shadow: 0 3px 12px rgba(0,0,0,.2);
    z-index:2;
}
.smh-dot.in           { background:linear-gradient(135deg,#16a34a,#22c55e); }
.smh-dot.out          { background:linear-gradient(135deg,#dc2626,#ef4444); }
.smh-dot.transfer-in  { background:linear-gradient(135deg,#2563eb,#3b82f6); }
.smh-dot.transfer-out { background:linear-gradient(135deg,#7c3aed,#8b5cf6); }
.smh-dot.adjustment   { background:linear-gradient(135deg,#d97706,#f59e0b); }
.smh-dot.sale         { background:linear-gradient(135deg,#0891b2,#06b6d4); }
.smh-dot.return       { background:linear-gradient(135deg,#2563eb,#60a5fa); }
.smh-dot.reserve      { background:linear-gradient(135deg,#ca8a04,#eab308); }

.smh-card {
    background:#fff;
    border:2px solid #f1f5f9;
    border-radius:14px;
    padding:20px 22px;
    box-shadow:0 2px 12px rgba(0,0,0,.05);
    transition: border-color .2s, box-shadow .2s, transform .2s;
}
.smh-card:hover {
    border-color:#3b82f6;
    box-shadow:0 6px 24px rgba(59,130,246,.12);
    transform:translateX(4px);
}
.smh-card-top {
    display:flex; justify-content:space-between; align-items:flex-start;
    flex-wrap:wrap; gap:10px; margin-bottom:14px;
}
.smh-type-badge {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:20px;
    font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
}
.smh-type-badge.in           { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; }
.smh-type-badge.out          { background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; }
.smh-type-badge.transfer-in  { background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; }
.smh-type-badge.transfer-out { background:linear-gradient(135deg,#7c3aed,#8b5cf6); color:#fff; }
.smh-type-badge.adjustment   { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; }
.smh-type-badge.sale         { background:linear-gradient(135deg,#0891b2,#06b6d4); color:#fff; }
.smh-type-badge.return       { background:linear-gradient(135deg,#2563eb,#60a5fa); color:#fff; }
.smh-type-badge.reserve      { background:linear-gradient(135deg,#ca8a04,#eab308); color:#fff; }

.smh-qty {
    font-size:28px; font-weight:900; margin:10px 0;
}
.smh-qty.positive { color:#16a34a; }
.smh-qty.negative { color:#dc2626; }

.smh-details {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap:14px;
    margin-top:14px; padding-top:14px;
    border-top:1.5px solid #f1f5f9;
}
.smh-detail-lbl { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; font-weight:600; }
.smh-detail-val { font-size:14px; font-weight:700; color:#334155; }

.smh-bal-before { display:inline-block; padding:4px 12px; border-radius:8px; background:#f1f5f9; color:#64748b; font-weight:700; font-size:13px; }
.smh-bal-after  { display:inline-block; padding:4px 12px; border-radius:8px; background:linear-gradient(135deg,#3b82f6,#6366f1); color:#fff; font-weight:700; font-size:13px; }

.smh-note {
    margin-top:12px; padding:11px 14px;
    border-radius:10px; font-size:13px;
}
.smh-note.reason { background:#f8fafc; border-left:3px solid #94a3b8; color:#475569; }
.smh-note.notes  { background:#fffbeb; border-left:3px solid #f59e0b; color:#92400e; }

/* ── Empty State ── */
.smh-empty {
    text-align:center; padding:80px 20px;
}
.smh-empty i { font-size:72px; color:#cbd5e1; }
.smh-empty h4 { color:#94a3b8; font-size:20px; margin:20px 0 10px; }
.smh-empty p { color:#cbd5e1; font-size:14px; }
.smh-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 22px;
    padding: 11px 26px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
    border: none;
    transition: transform .18s, box-shadow .18s;
}
.smh-empty-btn i { font-size: 16px; transition: transform .18s; }
.smh-empty-btn:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(99,102,241,.45);
}
.smh-empty-btn:hover i { transform: translateX(-3px); }

/* ── Pagination ── */
.smh-pagination { display:flex; justify-content:center; margin-top:30px; }
.smh-pagination .pagination > li > a,
.smh-pagination .pagination > li > span {
    border-radius:8px !important; margin:0 3px;
    border:2px solid #e2e8f0 !important;
    color:#475569 !important;
    font-weight:600;
}
.smh-pagination .pagination > .active > a,
.smh-pagination .pagination > .active > span {
    background:linear-gradient(135deg,#3b82f6,#6366f1) !important;
    border-color:transparent !important;
    color:#fff !important;
}
</style>

{{-- Hidden content-header --}}
<section class="content-header" style="margin:0;padding:0;height:0;overflow:hidden;"></section>

@php
    $isProduct = $stock->stockable_type === 'App\\Product';
    $typeLabel = $isProduct ? 'Product' : 'Lens';
    $typeIcon  = $isProduct ? 'bi-box-seam' : 'bi-eyeglasses';

    $totalIn  = $movements->getCollection()->where('is_incoming', true)->sum('quantity');
    $totalOut = $movements->getCollection()->where('is_incoming', false)->sum('quantity');
    $totalMov = $movements->total();
@endphp

{{-- ─── HERO ─── --}}
<div class="smh-hero">
    <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="smh-hero-back">
        <i class="bi bi-arrow-left"></i> Back to Stock
    </a>

    <div class="smh-hero-badge">
        <i class="bi {{ $typeIcon }}"></i> {{ $typeLabel }}
        &nbsp;·&nbsp; {{ $branch->name }}
    </div>

    <h1 class="smh-hero-title">
        <i class="bi bi-clock-history"></i>
        {{ $stock->description }}
    </h1>
    <p class="smh-hero-sub">Movement History &mdash; every stock change recorded</p>

    <div class="smh-hero-meta">
        @if($stock->stockable && $isProduct)
            @if($stock->stockable->product_id)
            <span class="smh-meta-pill"><i class="bi bi-upc"></i> ID: {{ $stock->stockable->product_id }}</span>
            @endif
        @elseif($stock->stockable && !$isProduct)
            @if($stock->stockable->brand)
            <span class="smh-meta-pill"><i class="bi bi-tag"></i> {{ $stock->stockable->brand }}</span>
            @endif
            @if($stock->stockable->index)
            <span class="smh-meta-pill"><i class="bi bi-layers"></i> Index: {{ $stock->stockable->index }}</span>
            @endif
        @endif
        <span class="smh-meta-pill"><i class="bi bi-building"></i> {{ $branch->name }}</span>
        <span class="smh-meta-pill"><i class="bi bi-stack"></i> Min {{ $stock->min_quantity }} / Max {{ $stock->max_quantity }}</span>
    </div>
</div>

{{-- ─── STATS STRIP ─── --}}
<div class="smh-stats-outer">
    <div class="smh-stats-grid">
        <div class="smh-stat-card">
            <div class="smh-stat-icon teal"><i class="bi bi-archive"></i></div>
            <div>
                <div class="smh-stat-num">{{ $stock->quantity }}</div>
                <div class="smh-stat-lbl">Current Stock</div>
            </div>
        </div>
        <div class="smh-stat-card">
            <div class="smh-stat-icon green"><i class="bi bi-arrow-down-circle"></i></div>
            <div>
                <div class="smh-stat-num">{{ $stock->total_in }}</div>
                <div class="smh-stat-lbl">Total In</div>
            </div>
        </div>
        <div class="smh-stat-card">
            <div class="smh-stat-icon red"><i class="bi bi-arrow-up-circle"></i></div>
            <div>
                <div class="smh-stat-num">{{ $stock->total_out }}</div>
                <div class="smh-stat-lbl">Total Out</div>
            </div>
        </div>
        <div class="smh-stat-card">
            <div class="smh-stat-icon blue"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="smh-stat-num">{{ $totalMov }}</div>
                <div class="smh-stat-lbl">Movements</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── BODY ─── --}}
<div class="smh-body">
    @if($movements->count() > 0)
        <div class="smh-timeline">
            @foreach($movements as $movement)
            @php
                $typeSlug = str_replace('_', '-', $movement->type);
                $isIn     = $movement->is_incoming;
                $icon     = $movement->type_info['icon'] ?? 'question-circle';
                $label    = $movement->type_info['label'] ?? ucfirst($movement->type);
            @endphp

            <div class="smh-item">
                {{-- Dot on the line --}}
                <div class="smh-dot {{ $typeSlug }}">
                    <i class="bi bi-{{ $icon }}"></i>
                </div>

                {{-- Card --}}
                <div class="smh-card">
                    <div class="smh-card-top">
                        <span class="smh-type-badge {{ $typeSlug }}">
                            <i class="bi bi-{{ $icon }}"></i>
                            {{ $label }}
                        </span>
                        <div style="text-align:right; color:#94a3b8; font-size:13px;">
                            <i class="bi bi-clock"></i>
                            {{ $movement->created_at->format('d M Y, h:i A') }}
                            <br>
                            <small>{{ $movement->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="smh-qty {{ $isIn ? 'positive' : 'negative' }}">
                        {{ $isIn ? '+' : '-' }}{{ abs($movement->quantity) }}
                        <small style="font-size:16px; opacity:.6; font-weight:600;">units</small>
                    </div>

                    {{-- Details grid --}}
                    <div class="smh-details">
                        <div>
                            <div class="smh-detail-lbl">Before</div>
                            <div class="smh-detail-val">
                                <span class="smh-bal-before">{{ $movement->balance_before ?? 0 }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="smh-detail-lbl">After</div>
                            <div class="smh-detail-val">
                                <span class="smh-bal-after">{{ $movement->balance_after ?? 0 }}</span>
                            </div>
                        </div>
                        @if($movement->cost)
                        <div>
                            <div class="smh-detail-lbl">Cost</div>
                            <div class="smh-detail-val">{{ number_format($movement->cost, 2) }} QAR</div>
                        </div>
                        @endif
                        <div>
                            <div class="smh-detail-lbl">By</div>
                            <div class="smh-detail-val">
                                <i class="bi bi-person-circle"></i>
                                {{ optional($movement->user)->name ?? optional($movement->user)->first_name ?? 'System' }}
                            </div>
                        </div>
                    </div>

                    @if($movement->reason)
                    <div class="smh-note reason">
                        <strong><i class="bi bi-info-circle"></i> Reason:</strong>
                        {{ $movement->reason }}
                    </div>
                    @endif

                    @if($movement->notes)
                    <div class="smh-note notes">
                        <strong><i class="bi bi-sticky"></i> Notes:</strong>
                        {{ $movement->notes }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($movements->hasPages())
        <div class="smh-pagination">
            {{ $movements->links() }}
        </div>
        @endif

    @else
        <div class="smh-empty">
            <i class="bi bi-clock-history"></i>
            <h4>No Movement History Yet</h4>
            <p>Stock changes (add, reduce, adjust, sale) will appear here once they happen.</p>
            <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="smh-empty-btn">
                <i class="bi bi-arrow-left"></i> Back to Stock
            </a>
        </div>
    @endif
</div>

@endsection
