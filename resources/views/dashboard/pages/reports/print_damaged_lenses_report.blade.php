<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Damaged Lenses Report — هالك</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; background: #f5f5f5; padding: 20px; }
        .report-container { max-width: 1300px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,.1); border-radius: 8px; }

        .report-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; padding-bottom:20px; border-bottom:3px solid #e74c3c; }
        .report-logo img { max-width: 120px; }
        .report-title h1 { font-size: 26px; color: #e74c3c; font-weight: 700; }
        .report-title .subtitle { font-size: 13px; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        .report-title .date-info { font-size: 13px; color: #888; margin-top: 4px; }

        .info-header { background:linear-gradient(135deg,#fff5f5,#fce8e8); padding:15px 20px; border-radius:8px; margin-bottom:25px; border:2px solid #f5c6cb; }
        .info-header-content { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .info-header strong { color:#e74c3c; font-size:14px; }
        .info-header .dates { font-size:17px; color:#333; font-weight:600; }

        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:15px; margin-bottom:30px; }
        .stat-box { background:linear-gradient(135deg,#fff5f5,#fce8e8); padding:18px; border-radius:8px; text-align:center; border-left:4px solid #e74c3c; }
        .stat-box .number { font-size:22px; font-weight:800; color:#e74c3c; margin-bottom:4px; }
        .stat-box .label  { font-size:11px; color:#666; text-transform:uppercase; font-weight:600; letter-spacing:.5px; }
        .stat-box.green { background:linear-gradient(135deg,#f0fff8,#e8f5e9); border-left-color:#00a86b; }
        .stat-box.green .number { color:#00a86b; }

        .section-title { font-size:16px; font-weight:700; color:#e74c3c; margin:25px 0 15px; padding-bottom:8px; border-bottom:2px solid #f5c6cb; }
        .section-title.green { color:#00a86b; border-bottom-color:#b2dfdb; }

        .report-table { width:100%; border-collapse:collapse; margin-bottom:30px; font-size:13px; box-shadow:0 2px 8px rgba(0,0,0,.06); border-radius:8px; overflow:hidden; }
        .report-table thead { background:linear-gradient(135deg,#e74c3c,#c0392b); }
        .report-table thead th { padding:13px 10px; color:white; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:.5px; text-align:left; }
        .report-table thead.green-head { background:linear-gradient(135deg,#00a86b,#008b5a); }
        .report-table tbody tr { border-bottom:1px solid #e9ecef; }
        .report-table tbody tr:hover { background:#fff5f5; }
        .report-table tbody td { padding:11px 10px; font-size:13px; color:#333; }
        .report-table tbody td:first-child { font-weight:700; color:#e74c3c; }
        .report-table tfoot td { padding:11px 10px; font-weight:700; background:#fce8e8; border-top:2px solid #e74c3c; color:#e74c3c; }

        .badge-R { background:#e3f2fd; color:#1565c0; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:700; }
        .badge-L { background:#f3e5f5; color:#6a1b9a; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:700; }
        .badge-damaged { background:#f8d7da; color:#721c24; padding:2px 10px; border-radius:10px; font-size:12px; font-weight:700; }
        .badge-recovered { background:#d4edda; color:#155724; padding:2px 10px; border-radius:10px; font-size:12px; font-weight:700; }

        .summary-box { margin-top:25px; padding:25px; background:linear-gradient(135deg,#fff5f5,#fce8e8); border-radius:10px; border:3px solid #e74c3c; }
        .summary-box h3 { color:#e74c3c; font-size:17px; text-align:center; font-weight:700; margin-bottom:18px; text-transform:uppercase; }
        .summary-table { width:100%; max-width:520px; margin:0 auto; }
        .summary-table tr { border-bottom:1px solid #f5c6cb; }
        .summary-table td { padding:12px 18px; font-size:14px; }
        .summary-table td:last-child { text-align:right; font-weight:700; color:#e74c3c; }

        .empty-state { text-align:center; padding:50px 20px; color:#999; }
        .empty-state .icon { font-size:50px; color:#ddd; margin-bottom:15px; }
        .empty-state h3 { color:#666; margin-bottom:8px; }

        .print-btn { position:fixed; bottom:30px; right:30px; background:linear-gradient(135deg,#e74c3c,#c0392b); color:white; border:none; padding:14px 28px; border-radius:50px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 6px 20px rgba(231,76,60,.4); }
        .print-btn:hover { transform:translateY(-3px); }

        @media print {
            body { background:white; padding:0; }
            .report-container { box-shadow:none; padding:15px; }
            .print-btn { display:none !important; }
            .report-table { font-size:11px; }
        }
    </style>
</head>
<body>
<div class="report-container">

    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Logo">
        </div>
        <div class="report-title" style="text-align:right;">
            <h1>Currently Damaged Lenses — هالك</h1>
            <div class="subtitle">Active Damaged Stock (Not Yet Recovered)</div>
            <div class="date-info">Generated: {{ date('Y-m-d H:i') }}</div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Period:</strong>
                <span class="dates">{{ $date_from }} &nbsp;→&nbsp; {{ $date_to }}</span>
            </div>
            @if(isset($selectedBranch) && $selectedBranch)
                <div><strong>Branch:</strong> <span style="font-weight:600;">{{ $selectedBranch->name }}</span></div>
            @else
                <div><strong>Branch:</strong> <span style="font-weight:600;">All Branches</span></div>
            @endif
        </div>
    </div>

    @php
        $totalDamagedQty   = $entries->sum('net_qty');
        $distinctLensCount = $entries->unique('glass_lense_id')->count();
    @endphp

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $totalDamagedQty }}</div>
            <div class="label">Total Damaged Qty — إجمالي الهالك</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $distinctLensCount }}</div>
            <div class="label">Distinct Lens Types — أنواع مختلفة</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $entries->count() }}</div>
            <div class="label">Entries (Rows) — عدد السجلات</div>
        </div>
    </div>

    {{-- ─── Damaged Entries ─── --}}
    <div class="section-title">
        <i class="fa fa-exclamation-triangle"></i> Damaged Lenses (هالك)
    </div>

    @if($entries->count() > 0)
    <table class="report-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Lens / Product ID</th>
            <th>Description</th>
            <th>Side</th>
            <th>Branch</th>
            <th style="text-align:center;">Qty Damaged</th>
            <th>PO #</th>
            <th>Invoice #</th>
            <th>Reported By</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @foreach($entries as $i => $entry)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ date('Y-m-d', strtotime($entry->created_at)) }}</td>
                <td>{{ $entry->lens->product_id ?? '—' }}</td>
                <td>{{ $entry->lens->description ?? '—' }}</td>
                <td>
                    @if($entry->side)
                        <span class="badge-{{ $entry->side }}">{{ $entry->side === 'R' ? 'Right' : 'Left' }}</span>
                    @else
                        <span style="color:#aaa;">—</span>
                    @endif
                </td>
                <td>{{ $entry->branch->name ?? '—' }}</td>
                <td style="text-align:center;">
                    <span class="badge-damaged">{{ $entry->net_qty }}</span>
                </td>
                <td style="font-weight:600;color:#555;">{{ $entry->sourcePo->po_number ?? '—' }}</td>
                <td style="font-weight:600;color:#1a237e;">
                    {{ $entry->sourcePo->invoice->invoice_code ?? '—' }}
                </td>
                <td>{{ $entry->user->full_name ?? '—' }}</td>
                <td style="font-size:12px;color:#666;">{{ $entry->notes ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6" style="text-align:right;">TOTAL DAMAGED</td>
            <td style="text-align:center;">{{ $totalDamagedQty }}</td>
            <td colspan="4"></td>
        </tr>
        </tfoot>
    </table>
    @else
        <div class="empty-state">
            <div class="icon">&#9888;</div>
            <h3>No Damaged Lenses Found</h3>
            <p>No lenses were marked as damaged in this period.</p>
        </div>
    @endif

    {{-- ─── Recovery Entries ─── --}}
    @if($recoveries->count() > 0)
    <div class="section-title green" style="margin-top:35px;">
        <i class="fa fa-recycle"></i> Recovered Lenses
    </div>
    <table class="report-table">
        <thead class="green-head">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Lens / Product ID</th>
            <th>Description</th>
            <th>Side</th>
            <th>Branch</th>
            <th style="text-align:center;">Qty Recovered</th>
            <th>Recovered By</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @foreach($recoveries as $i => $entry)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ date('Y-m-d', strtotime($entry->created_at)) }}</td>
                <td>{{ $entry->lens->product_id ?? '—' }}</td>
                <td>{{ $entry->lens->description ?? '—' }}</td>
                <td>
                    @if($entry->side)
                        <span class="badge-{{ $entry->side }}">{{ $entry->side === 'R' ? 'Right' : 'Left' }}</span>
                    @else —
                    @endif
                </td>
                <td>{{ $entry->branch->name ?? '—' }}</td>
                <td style="text-align:center;">
                    <span class="badge-recovered">{{ $entry->quantity }}</span>
                </td>
                <td>{{ $entry->user->full_name ?? '—' }}</td>
                <td style="font-size:12px;color:#666;">{{ $entry->notes ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <!-- Summary -->
    <div class="summary-box">
        <h3>Summary — Currently Damaged Lenses (هالك)</h3>
        <table class="summary-table">
            <tr><td>Distinct Lens Types — أنواع مختلفة</td><td>{{ $distinctLensCount }}</td></tr>
            <tr><td>Entries (Rows) — عدد السجلات</td><td>{{ $entries->count() }}</td></tr>
            <tr style="border-top:2px solid #e74c3c;">
                <td><strong>Total Damaged Quantity — إجمالي الهالك</strong></td>
                <td><strong>{{ $totalDamagedQty }}</strong></td>
            </tr>
        </table>
    </div>

</div>

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons/bootstrap-icons.min.css') }}">
@include('dashboard.partials._report_export_toolbar', ['filename' => 'damaged-lenses-report'])
</body>
</html>
