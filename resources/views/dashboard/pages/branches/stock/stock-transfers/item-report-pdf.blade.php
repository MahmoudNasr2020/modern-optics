<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; padding: 20px; }
h2 { font-size: 18px; color: #0f172a; margin-bottom: 4px; }
.sub { font-size: 12px; color: #64748b; margin-bottom: 16px; }
.product-bar { background: #eff6ff; border: 2px solid #bfdbfe; border-radius: 8px;
               padding: 10px 16px; margin-bottom: 16px; font-size: 13px; }
.product-bar strong { font-size: 15px; color: #0f172a; }
.stats { display: flex; gap: 16px; margin-bottom: 16px; }
.stat { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 8px 16px; text-align: center; flex: 1; }
.stat .val { font-size: 20px; font-weight: 900; }
.stat .lbl { font-size: 10px; color: #94a3b8; }

table { width: 100%; border-collapse: collapse; font-size: 10px; }
thead th { background: #0f172a; color: #fff; padding: 8px 10px; text-align: left;
           font-size: 10px; font-weight: 700; letter-spacing: .04em; white-space: nowrap; }
tbody tr { border-bottom: 1px solid #f1f5f9; }
tbody tr:nth-child(even) { background: #f8fafc; }
tbody td { padding: 7px 10px; vertical-align: middle; }

.badge { display: inline-block; padding: 1px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; }
.badge-received  { background: #dcfce7; color: #15803d; }
.badge-shipped   { background: #dbeafe; color: #1d4ed8; }
.badge-approved  { background: #fef9c3; color: #854d0e; }
.badge-pending   { background: #fce7f3; color: #9d174d; }
.badge-cancelled { background: #f1f5f9; color: #64748b; }

.footer { margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head>
<body>

<h2>🔄 Items Transfer Report</h2>
<div class="sub">Generated: {{ now()->format('d M Y, H:i') }}</div>

@if($selectedProduct)
<div class="product-bar">
    <strong>{{ $selectedProduct->description }}</strong>
    <span style="color:#64748b;margin-left:10px;">ID: {{ $selectedProduct->product_id }}</span>
</div>

@php
    $totalMovements = $transfers->count();
    $receivedCount  = $transfers->where('status','received')->count();
    $totalQty       = $transfers->where('status','received')->sum('quantity');
@endphp

<div class="stats">
    <div class="stat"><div class="val">{{ $totalMovements }}</div><div class="lbl">Total Movements</div></div>
    <div class="stat"><div class="val" style="color:#16a34a;">{{ $receivedCount }}</div><div class="lbl">Completed</div></div>
    <div class="stat"><div class="val" style="color:#2563eb;">{{ $totalQty }}</div><div class="lbl">Units Moved</div></div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Transfer #</th>
            <th>Date</th>
            <th>From Branch</th>
            <th>To Branch</th>
            <th>Qty</th>
            <th>Status</th>
            <th>Received Date</th>
            <th>Created By</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transfers as $i => $tr)
        <tr>
            <td style="color:#94a3b8;">{{ $i + 1 }}</td>
            <td style="font-weight:700;">{{ $tr->transfer_number }}</td>
            <td style="white-space:nowrap;">{{ $tr->transfer_date ? $tr->transfer_date->format('d M Y') : '—' }}</td>
            <td>{{ $tr->fromBranch->name ?? '—' }}</td>
            <td>{{ $tr->toBranch->name ?? '—' }}</td>
            <td style="font-weight:800;text-align:center;font-size:13px;">{{ $tr->quantity }}</td>
            <td>
                @php $sc = ['received'=>'badge-received','shipped'=>'badge-shipped','approved'=>'badge-approved','pending'=>'badge-pending','cancelled'=>'badge-cancelled'][$tr->status] ?? 'badge-pending'; @endphp
                <span class="badge {{ $sc }}">{{ ucfirst($tr->status) }}</span>
            </td>
            <td style="white-space:nowrap;">{{ $tr->received_date ? $tr->received_date->format('d M Y') : '—' }}</td>
            <td>{{ $tr->creator->name ?? '—' }}</td>
            <td style="color:#64748b;">{{ $tr->notes ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    Items Transfer Report — {{ config('app.name') }} — {{ now()->format('d M Y') }}
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'item-transfer-report'])
</body>
</html>
