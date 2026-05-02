<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Item Transfer Report — {{ $selectedProduct->product_id ?? '' }}</title>
<style>
/* ── Reset ── */
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    color: #1e293b;
    background: #f1f5f9;
    padding: 30px 20px;
}

/* ── Page wrapper ── */
.page {
    max-width: 1180px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 30px rgba(0,0,0,.12);
    overflow: hidden;
}

/* ── Header ── */
.rpt-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 26px 36px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.rpt-header::before {
    content:''; position:absolute; top:-70px; right:-70px;
    width:240px; height:240px; border-radius:50%;
    background: rgba(255,255,255,.05);
}
.rpt-header::after {
    content:''; position:absolute; bottom:-50px; right:120px;
    width:160px; height:160px; border-radius:50%;
    background: rgba(255,255,255,.03);
}
.hdr-left { display:flex; align-items:center; gap:18px; position:relative; z-index:1; }
.hdr-logo img { height:58px; width:auto; filter:brightness(0) invert(1); opacity:.9; }
.hdr-title h1 { font-size:20px; font-weight:800; color:#fff; }
.hdr-title .sub { font-size:11px; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:1.5px; margin-top:3px; }
.hdr-right { text-align:right; position:relative; z-index:1; }
.hdr-right .meta { font-size:11px; color:rgba(255,255,255,.5); line-height:2; }
.hdr-right .meta strong { color:rgba(255,255,255,.85); }

/* ── Accent bar ── */
.accent { height:4px; background:linear-gradient(90deg,#3b82f6,#8b5cf6,#06b6d4); }

/* ── Product info banner ── */
.product-banner {
    background: #eff6ff;
    border-bottom: 1px solid #bfdbfe;
    padding: 16px 36px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.product-banner .p-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg,#3b82f6,#2563eb);
    border-radius: 10px;
    display:flex; align-items:center; justify-content:center;
    font-size: 20px; color:#fff; flex-shrink:0;
}
.product-banner .p-name { font-size:17px; font-weight:800; color:#0f172a; }
.product-banner .p-code { font-size:12px; color:#64748b; margin-top:2px; }

/* ── Stats row ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-bottom: 1px solid #e2e8f0;
}
.stat-cell {
    padding: 18px 24px;
    text-align: center;
    border-right: 1px solid #e2e8f0;
}
.stat-cell:last-child { border-right:none; }
.stat-cell .sv { font-size:26px; font-weight:900; line-height:1; margin-bottom:4px; }
.stat-cell .sl { font-size:10px; text-transform:uppercase; letter-spacing:.8px; font-weight:700; color:#94a3b8; }
.sv-blue   { color:#3b82f6; }
.sv-green  { color:#10b981; }
.sv-amber  { color:#f59e0b; }
.sv-indigo { color:#6366f1; }

/* ── Table wrapper ── */
.tbl-wrap { padding: 24px 28px 32px; }
.section-lbl {
    font-size:11px; text-transform:uppercase; letter-spacing:1px;
    font-weight:700; color:#64748b; margin-bottom:12px;
    display:flex; align-items:center; gap:8px;
}
.section-lbl::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* ── Main table ── */
.rpt-table { width:100%; border-collapse:collapse; font-size:12.5px; }
.rpt-table thead tr { background:#0f172a; }
.rpt-table thead th {
    padding:11px 10px; color:#e2e8f0; font-weight:700;
    font-size:10px; text-transform:uppercase; letter-spacing:.6px;
    white-space:nowrap; text-align:left;
}
.rpt-table thead th:first-child { padding-left:16px; }
.rpt-table thead th:last-child  { padding-right:16px; }

.rpt-table tbody tr { border-bottom:1px solid #f1f5f9; }
.rpt-table tbody tr:nth-child(even) { background:#f8fafc; }
.rpt-table tbody tr:hover { background:#eff6ff; }
.rpt-table tbody td { padding:10px 10px; vertical-align:middle; color:#334155; }
.rpt-table tbody td:first-child { padding-left:16px; }
.rpt-table tbody td:last-child  { padding-right:16px; }

/* ── Cell styles ── */
.row-n { font-size:11px; font-weight:700; color:#94a3b8; }
.tx-num {
    font-weight:800; color:#3b82f6; font-size:12px;
    font-family:'Courier New',monospace;
}
.batch-tag {
    font-size:10px; color:#7c3aed; background:#f5f3ff;
    padding:1px 6px; border-radius:4px; font-weight:700;
    display:inline-block; margin-top:2px;
}
.branch-tag {
    font-weight:600; color:#1e293b; font-size:12px;
}
.qty-val {
    font-size:15px; font-weight:900; color:#0f172a;
    text-align:center;
}
.badge {
    display:inline-block; padding:3px 10px;
    border-radius:20px; font-size:10px; font-weight:700;
    text-transform:uppercase; letter-spacing:.3px;
}
.b-received  { background:#dcfce7; color:#15803d; }
.b-approved  { background:#e0f2fe; color:#0c4a6e; }
.b-transit   { background:#ede9fe; color:#4c1d95; }
.b-pending   { background:#fef3c7; color:#92400e; }
.b-canceled  { background:#f1f5f9; color:#475569; }

.date-cell { font-size:11.5px; color:#475569; white-space:nowrap; }
.note-cell { font-size:11px; color:#94a3b8; max-width:130px; }

/* ── Footer total row ── */
.total-row td {
    background:#0f172a !important; color:#fff !important;
    font-weight:800; font-size:13px; padding:12px 10px !important;
}
.total-row td:first-child { padding-left:16px !important; }
.total-row td:last-child  { padding-right:16px !important; }

/* ── Page footer ── */
.rpt-footer {
    margin:0 28px; padding:14px 0;
    border-top:1px solid #e2e8f0;
    display:flex; justify-content:space-between; align-items:center;
    font-size:11px; color:#94a3b8;
}
.rpt-footer strong { color:#475569; }

/* ── Print button (screen only) ── */
.print-btns {
    position:fixed; bottom:28px; right:28px;
    display:flex; flex-direction:column; gap:10px; z-index:999;
}
.pbtn {
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 24px; border-radius:50px; font-size:14px;
    font-weight:700; cursor:pointer; border:none;
    text-decoration:none; color:#fff;
    box-shadow:0 6px 20px rgba(0,0,0,.25);
    transition:all .2s;
}
.pbtn:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(0,0,0,.35); }
.pbtn-print { background:linear-gradient(135deg,#0f172a,#1e3a5f); }
.pbtn-back  { background:linear-gradient(135deg,#475569,#334155); }
.pbtn:visited { color:#fff; }

/* ── Empty state ── */
.empty { text-align:center; padding:50px; color:#94a3b8; font-size:14px; }

/* ── @media print ── */
@media print {
    @page { size: A4 landscape; margin: 8mm 10mm; }
    * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
    body { background:#fff !important; padding:0 !important; }
    .page { box-shadow:none !important; border-radius:0 !important; max-width:100%; }
    .print-btns { display:none !important; }

    .rpt-header { padding:14px 18px !important; }
    .hdr-logo img { height:40px !important; }
    .hdr-title h1 { font-size:15px !important; }

    .product-banner { padding:10px 18px !important; }
    .tbl-wrap { padding:12px 14px 20px !important; }

    .rpt-table { font-size:8.5px !important; }
    .rpt-table thead th { padding:7px 6px !important; font-size:8px !important; }
    .rpt-table tbody td { padding:6px 6px !important; }
    .rpt-table tbody td:first-child { padding-left:10px !important; }
    .badge { font-size:8px !important; padding:2px 6px !important; }
    .tx-num { font-size:9px !important; }
    .qty-val { font-size:12px !important; }

    .rpt-footer { margin:0 14px !important; font-size:8px !important; }
    .stat-cell .sv { font-size:18px !important; }
}
</style>
</head>
<body>

@php
    $totalMovements = $transfers->count();
    $receivedCount  = $transfers->where('status','received')->count();
    $inProgressCount= $transfers->whereIn('status',['pending','approved','in_transit'])->count();
    $totalQty       = $transfers->where('status','received')->sum('quantity');
@endphp

<div class="page">

    {{-- ═══ Header ═══ --}}
    <div class="rpt-header">
        <div class="hdr-left">
            <div class="hdr-logo">
                <img src="{{ asset('assets/img/modern.png') }}" alt="Logo"
                     onerror="this.style.display='none'"/>
            </div>
            <div class="hdr-title">
                <h1>Items Transfer Report</h1>
                <div class="sub">Product Movement History</div>
            </div>
        </div>
        <div class="hdr-right">
            <div class="meta">
                Generated: <strong>{{ now()->format('d M Y, h:i A') }}</strong><br>
                Total Records: <strong>{{ $totalMovements }}</strong>
            </div>
        </div>
    </div>

    {{-- ═══ Accent ═══ --}}
    <div class="accent"></div>

    {{-- ═══ Product Banner ═══ --}}
    <div class="product-banner">
        <div class="p-icon">📦</div>
        <div>
            <div class="p-name">{{ $selectedProduct->description ?? '—' }}</div>
            <div class="p-code">Product ID: <strong>{{ $selectedProduct->product_id ?? '—' }}</strong></div>
        </div>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="stats-row">
        <div class="stat-cell">
            <div class="sv sv-blue">{{ $totalMovements }}</div>
            <div class="sl">Total Records</div>
        </div>
        <div class="stat-cell">
            <div class="sv sv-green">{{ $receivedCount }}</div>
            <div class="sl">Completed</div>
        </div>
        <div class="stat-cell">
            <div class="sv sv-amber">{{ $inProgressCount }}</div>
            <div class="sl">In Progress</div>
        </div>
        <div class="stat-cell">
            <div class="sv sv-indigo">{{ $totalQty }}</div>
            <div class="sl">Units Transferred</div>
        </div>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="tbl-wrap">

        <div class="section-lbl">
            Transfer History — {{ $totalMovements }} records
        </div>

        @if($transfers->isEmpty())
            <div class="empty">No transfer records found for this product.</div>
        @else
        <table class="rpt-table">
            <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th style="width:130px;">Transfer #</th>
                <th style="width:100px;">Date</th>
                <th>From Branch</th>
                <th>To Branch</th>
                <th style="width:60px;text-align:center;">Qty</th>
                <th style="width:100px;text-align:center;">Status</th>
                <th style="width:110px;">Approved / Received</th>
                <th style="width:110px;">Created By</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transfers as $i => $tr)
            @php
                $statusClass = 'b-pending';
                if ($tr->status === 'received')   $statusClass = 'b-received';
                elseif ($tr->status === 'approved')   $statusClass = 'b-approved';
                elseif ($tr->status === 'in_transit') $statusClass = 'b-transit';
                elseif ($tr->status === 'canceled')   $statusClass = 'b-canceled';
            @endphp
            <tr>
                <td><span class="row-n">{{ $i + 1 }}</span></td>

                <td>
                    <div class="tx-num">{{ $tr->transfer_number }}</div>
                    @if($tr->batch_number)
                        <span class="batch-tag">{{ $tr->batch_number }}</span>
                    @endif
                </td>

                <td class="date-cell">
                    {{ $tr->transfer_date ? $tr->transfer_date->format('d M Y') : '—' }}
                </td>

                <td><span class="branch-tag">{{ $tr->fromBranch->name ?? '—' }}</span></td>
                <td><span class="branch-tag">{{ $tr->toBranch->name ?? '—' }}</span></td>

                <td class="qty-val">{{ $tr->quantity }}</td>

                <td style="text-align:center;">
                    <span class="badge {{ $statusClass }}">{{ ucfirst($tr->status) }}</span>
                </td>

                <td class="date-cell">
                    @if($tr->approved_date)
                        <div>✔ {{ $tr->approved_date->format('d M Y') }}</div>
                    @endif
                    @if($tr->received_date)
                        <div>📦 {{ $tr->received_date->format('d M Y') }}</div>
                    @endif
                    @if(!$tr->approved_date && !$tr->received_date) — @endif
                </td>

                <td style="font-size:12px;color:#475569;">
                    {{ $tr->creator->name ?? '—' }}
                </td>

                <td class="note-cell">{{ $tr->notes ?: '—' }}</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align:right; padding-right:16px !important; letter-spacing:.5px;">
                    TOTAL COMPLETED TRANSFERS
                </td>
                <td style="text-align:center; color:#6ee7b7 !important;">{{ $totalQty }}</td>
                <td colspan="4" style="color:#94a3b8 !important; font-size:11px !important;">
                    {{ $receivedCount }} completed · {{ $inProgressCount }} in progress
                </td>
            </tr>
            </tfoot>
        </table>
        @endif
    </div>

    {{-- ═══ Footer ═══ --}}
    <div class="rpt-footer">
        <span>
            <strong>{{ $selectedProduct->description ?? '' }}</strong>
            @if($selectedProduct) &nbsp;·&nbsp; ID: {{ $selectedProduct->product_id }} @endif
        </span>
        <span>Generated {{ now()->format('d M Y — h:i A') }} &nbsp;·&nbsp; Items Transfer Report</span>
    </div>

</div>

{{-- ═══ Floating buttons (screen only) ═══ --}}
<div class="print-btns">
    <a href="javascript:history.back()" class="pbtn pbtn-back">← Back</a>
    <button onclick="window.print()" class="pbtn pbtn-print">🖨️ Print / Save PDF</button>
</div>

</body>
</html>
