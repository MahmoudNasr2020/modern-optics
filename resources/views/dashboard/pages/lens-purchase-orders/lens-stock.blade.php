@extends('dashboard.layouts.master')
@section('title', 'Lens Stock')
@section('content')

<style>
/* ── Page layout ── */
.ls-page { padding: 20px; }

/* ── Header card ── */
.ls-header {
    background: linear-gradient(135deg,#1a1f3a,#2c3e50);
    color:#fff; border-radius:14px; padding:24px 30px; margin-bottom:22px;
    box-shadow:0 6px 24px rgba(0,0,0,.18);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
}
.ls-header h2 { margin:0; font-size:22px; font-weight:700; }
.ls-header .sub { opacity:.7; font-size:13px; margin-top:4px; }

/* ── Filter bar ── */
.ls-filter {
    background:#fff; border-radius:10px; padding:16px 20px;
    margin-bottom:20px; border:1px solid #e0e6ed;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
}

/* ── Summary stats ── */
.ls-stats { display:flex; gap:14px; margin-bottom:20px; flex-wrap:wrap; }
.ls-stat {
    flex:1; min-width:160px; background:#fff; border-radius:10px;
    padding:18px 20px; border-left:4px solid #ccc;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
}
.ls-stat.total  { border-color:#2c3e50; }
.ls-stat.right  { border-color:#1565c0; }
.ls-stat.left   { border-color:#6a1b9a; }
.ls-stat.low    { border-color:#f39c12; }
.ls-stat .num   { font-size:28px; font-weight:700; }
.ls-stat .lbl   { font-size:12px; color:#888; margin-top:2px; }
.ls-stat.total .num { color:#2c3e50; }
.ls-stat.right  .num { color:#1565c0; }
.ls-stat.left   .num { color:#6a1b9a; }
.ls-stat.low    .num { color:#f39c12; }

/* ── Table ── */
.ls-table { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.07); }
.ls-table thead th {
    background:#2c3e50; color:#fff; padding:14px 12px;
    font-size:11px; text-transform:uppercase; letter-spacing:.8px; font-weight:700;
    text-align:center; border:none;
}
.ls-table thead th.col-desc { text-align:left; }
.ls-table tbody td { padding:14px 12px; vertical-align:middle; border-bottom:1px solid #f0f2f5; text-align:center; }
.ls-table tbody td.col-desc { text-align:left; }
.ls-table tbody tr:hover { background:#f8f9ff; }

/* ── Badges ── */
.lens-id-badge {
    background:linear-gradient(135deg,#3498db,#2980b9); color:#fff;
    padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700;
    display:inline-block; white-space:nowrap;
}
.side-badge {
    padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700;
    display:inline-block;
}
.side-badge.R { background:#e3f2fd; color:#1565c0; }
.side-badge.L { background:#f3e5f5; color:#6a1b9a; }

/* ── Stock number cells ── */
.stk-num {
    font-size:18px; font-weight:700; padding:4px 14px;
    border-radius:20px; display:inline-block;
}
.stk-ok   { background:#e8f8f5; color:#27ae60; }
.stk-zero { background:#f4f6fb; color:#aaa;    }
.stk-low  { background:#fff8e1; color:#e65100; }

.stk-io { font-size:12px; font-weight:600; }
.stk-in  { color:#27ae60; }
.stk-out { color:#e74c3c; }

/* ── Empty state ── */
.ls-empty { text-align:center; padding:60px 20px; color:#aaa; }
.ls-empty i { font-size:56px; display:block; margin-bottom:12px; }

/* ── Action buttons ── */
.ls-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600;
    border:none; cursor:pointer; text-decoration:none;
    transition:all .2s; box-shadow:0 2px 6px rgba(0,0,0,.12); white-space:nowrap;
}
.ls-btn:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.18); text-decoration:none; }
.ls-btn-back  { background:#e8ecf7; color:#555; }
.ls-btn-back:hover  { color:#333; }
.ls-btn-orders { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; }
.ls-btn-orders:hover { color:#fff; }
.ls-btn-all   { background:linear-gradient(135deg,#9b59b6,#8e44ad); color:#fff; }
.ls-btn-all:hover { color:#fff; }
</style>

<section class="content-header">
    <h1><i class="bi bi-boxes"></i> Lens Stock <small>Available inventory by eye side</small></h1>
</section>

<div class="ls-page">

    {{-- ═══ Header ═══ --}}
    <div class="ls-header">
        <div>
            <h2><i class="bi bi-boxes" style="opacity:.7;margin-right:8px;"></i>Lens Stock</h2>
            <div class="sub">Real-time lens inventory · Right &amp; Left eye breakdown</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('dashboard.lens-purchase-orders.index') }}" class="ls-btn ls-btn-orders">
                <i class="bi bi-flask"></i> Lab Orders
            </a>
            <a href="{{ route('dashboard.invoice.pending') }}" class="ls-btn" style="background:#27ae60;color:#fff;">
                <i class="bi bi-receipt"></i> Pending Invoices
            </a>
        </div>
    </div>

    {{-- ═══ Filters ═══ --}}
    <div class="ls-filter">
        <form method="GET" action="{{ route('dashboard.lens-purchase-orders.lens-stock') }}" class="row">
            @if(auth()->user()->canSeeAllBranches())
            <div class="col-md-4">
                <select name="branch_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-4">
                <label style="display:flex;align-items:center;gap:8px;height:34px;cursor:pointer;margin:0;">
                    <input type="checkbox" name="show_all" value="1" {{ request('show_all') ? 'checked' : '' }} onchange="this.form.submit()">
                    <span style="font-size:13px;color:#555;">Show all lenses (including zero stock)</span>
                </label>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('dashboard.lens-purchase-orders.lens-stock') }}" class="ls-btn ls-btn-back">
                    <i class="bi bi-x-circle"></i> Clear Filters
                </a>
            </div>
        </form>
    </div>

    {{-- ═══ Summary stats ═══ --}}
    @php
        $totalLenses  = $lenses->count();
        $totalRight   = $lenses->sum('stock_R');
        $totalLeft    = $lenses->sum('stock_L');
        $lowStock     = $lenses->filter(fn($l) => $l->stock_available > 0 && $l->stock_available <= 2)->count();
    @endphp
    <div class="ls-stats">
        <div class="ls-stat total">
            <div class="num">{{ $totalLenses }}</div>
            <div class="lbl"><i class="bi bi-eyeglasses"></i> Lens Types in Stock</div>
        </div>
        <div class="ls-stat right">
            <div class="num">{{ $totalRight }}</div>
            <div class="lbl"><i class="bi bi-arrow-right-circle-fill"></i> Right Eye Units</div>
        </div>
        <div class="ls-stat left">
            <div class="num">{{ $totalLeft }}</div>
            <div class="lbl"><i class="bi bi-arrow-left-circle-fill"></i> Left Eye Units</div>
        </div>
        <div class="ls-stat low">
            <div class="num">{{ $lowStock }}</div>
            <div class="lbl"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock (≤2)</div>
        </div>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="ls-table">
        <table class="table" style="margin:0;">
            <thead>
                <tr>
                    <th style="width:120px;">Lens ID</th>
                    <th class="col-desc">Description</th>
                    <th>Brand</th>
                    <th>Index</th>
                    <th>Type</th>
                    <th style="background:#1a3a5c;">
                        <i class="bi bi-arrow-right-circle-fill" style="color:#7ec8f4;"></i> Right
                    </th>
                    <th style="background:#2d1b4e;">
                        <i class="bi bi-arrow-left-circle-fill" style="color:#ce93d8;"></i> Left
                    </th>
                    <th title="Actual stock from glass_lenses.amount — reflects receive/damage/recovery">Stock</th>
                    <th>IN</th>
                    <th>OUT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lenses as $lens)
                <tr>
                    <td>
                        <span class="lens-id-badge">{{ $lens->product_id }}</span>
                    </td>
                    <td class="col-desc" style="font-size:13px;max-width:200px;">
                        {{ $lens->description }}
                    </td>
                    <td style="font-size:12px;color:#555;">{{ $lens->brand ?? '-' }}</td>
                    <td>
                        @if($lens->index)
                            <span style="background:#e8ecf7;color:#555;padding:2px 8px;border-radius:12px;font-size:12px;font-weight:600;">
                                {{ $lens->index }}
                            </span>
                        @else -
                        @endif
                    </td>
                    <td style="font-size:12px;color:#555;">{{ $lens->lense_type ?? '-' }}</td>

                    {{-- Right Eye --}}
                    <td>
                        @if($lens->stock_R > 0)
                            <span class="stk-num stk-ok" style="background:#e3f2fd;color:#1565c0;">{{ $lens->stock_R }}</span>
                        @elseif($lens->stock_R == 0 && $lens->stock_in > 0)
                            <span class="stk-num" style="background:#fce4ec;color:#c62828;font-size:14px;">0</span>
                        @else
                            <span class="stk-num stk-zero" style="font-size:14px;">0</span>
                        @endif
                    </td>

                    {{-- Left Eye --}}
                    <td>
                        @if($lens->stock_L > 0)
                            <span class="stk-num stk-ok" style="background:#f3e5f5;color:#6a1b9a;">{{ $lens->stock_L }}</span>
                        @elseif($lens->stock_L == 0 && $lens->stock_in > 0)
                            <span class="stk-num" style="background:#fce4ec;color:#c62828;font-size:14px;">0</span>
                        @else
                            <span class="stk-num stk-zero" style="font-size:14px;">0</span>
                        @endif
                    </td>

                    {{-- Total --}}
                    <td>
                        @php $t = $lens->stock_available; @endphp
                        @if($t > 2)
                            <span class="stk-num stk-ok">{{ $t }}</span>
                        @elseif($t > 0)
                            <span class="stk-num stk-low">{{ $t }}</span>
                        @else
                            <span class="stk-num stk-zero">0</span>
                        @endif
                    </td>

                    {{-- In / Out --}}
                    <td class="stk-io stk-in">+{{ $lens->stock_in }}</td>
                    <td class="stk-io stk-out">-{{ $lens->stock_out }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="ls-empty">
                            <i class="bi bi-inbox"></i>
                            @if(request('show_all'))
                                No lenses found in the system.
                            @else
                                No lenses in stock. Receive a lab order to update stock.
                                <br>
                                <a href="{{ route('dashboard.lens-purchase-orders.index') }}" class="ls-btn ls-btn-orders" style="margin-top:14px;display:inline-flex;">
                                    <i class="bi bi-flask"></i> View Lab Orders
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lenses->isNotEmpty())
    <div style="margin-top:14px;font-size:12px;color:#888;text-align:right;">
        <i class="bi bi-info-circle"></i>
        Stock is per branch{{ $branchId ? '' : ' (all branches combined)' }}.
        Right/Left breakdown reflects the eye side recorded on the lab order.
    </div>
    @endif

</div>
@endsection
