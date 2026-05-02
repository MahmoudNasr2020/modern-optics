<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary By Doctor</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #e8f8f5;
            padding: 24px;
        }

        .report-container {
            max-width: 1300px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(22, 160, 133, 0.15);
            border-radius: 12px;
        }

        /* ─── Header ─── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 3px solid #16a085;
        }

        .report-logo img { max-width: 120px; height: auto; }

        .report-title h1 {
            font-size: 28px;
            color: #16a085;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .report-title .subtitle {
            font-size: 12px;
            color: #16a085;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .report-title .date-info {
            font-size: 13px;
            color: #999;
            margin-top: 6px;
        }

        /* ─── Info Header ─── */
        .info-header {
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            border: 2px solid #7dcea0;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #16a085;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 2px;
        }

        .info-header .dates {
            font-size: 16px;
            color: #0e6655;
            font-weight: 600;
        }

        /* ─── Summary Stats ─── */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            padding: 20px 14px;
            border-radius: 12px;
            text-align: center;
            border-left: 5px solid #16a085;
            box-shadow: 0 3px 10px rgba(22, 160, 133, 0.12);
            transition: transform 0.2s;
        }

        .stat-box:hover { transform: translateY(-3px); }

        .stat-box.discount-stat { border-left-color: #e74c3c; }
        .stat-box.discount-stat .number { color: #c0392b; }

        .stat-box .number {
            font-size: 22px;
            font-weight: 800;
            color: #16a085;
            margin-bottom: 4px;
        }

        .stat-box .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* ─── Main Table ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #16a085 0%, #0e6655 100%);
        }

        .report-table thead th {
            padding: 16px 12px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(n+3) { text-align: right; }
        .report-table thead th.discount-col    { background: rgba(0,0,0,0.15); }

        .report-table tbody tr {
            border-bottom: 1px solid #e8f8f5;
            transition: background 0.15s;
        }

        .report-table tbody tr:hover:not(.total-row) { background: #f0fdf8; }

        .report-table tbody td {
            padding: 14px 12px;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(n+3) { text-align: right; font-weight: 600; }

        .report-table tbody td:nth-child(1) { color: #888; font-weight: 600; }
        .report-table tbody td:nth-child(2) { color: #16a085; font-weight: 700; font-size: 14px; }

        /* discount cells */
        .discount-cell   { background: #fdf2f0; }
        .discount-amount { color: #c0392b; font-weight: 700; }
        .discount-pct    { color: #e74c3c; font-size: 11px; font-weight: 600; }

        /* percentage badge */
        .pct-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            color: #0e6655;
        }

        /* ─── Total Row ─── */
        .total-row {
            background: linear-gradient(135deg, #16a085 0%, #0e6655 100%);
        }

        .total-row td {
            padding: 18px 12px;
            color: white !important;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: right;
        }

        .total-row td:first-child,
        .total-row td:nth-child(2) { text-align: left; }

        .total-row td.discount-cell { background: rgba(0,0,0,0.15); }
        .total-row .discount-amount { color: #f1948a !important; }
        .total-row .discount-pct    { color: #f5b7b1 !important; }

        .total-row .pct-badge {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #bbb;
        }

        .empty-state i { font-size: 72px; margin-bottom: 20px; display: block; color: #ddd; }
        .empty-state h3 { color: #888; font-size: 20px; margin-bottom: 8px; }

        /* ─── Print Button ─── */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #16a085 0%, #0e6655 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(22, 160, 133, 0.45);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(22, 160, 133, 0.55);
        }

        /* ─── Footer ─── */
        .report-footer {
            margin-top: 40px;
            padding-top: 18px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #aaa;
            font-size: 12px;
            line-height: 1.8;
        }

        .report-footer strong { color: #16a085; }

        /* ─── Print ─── */
        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; }
            .print-button { display: none !important; }
            .report-table tbody tr:hover { background: white; }
            @page { margin: 1.5cm; size: landscape; }
        }
    </style>

    <link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
<div class="report-container">

    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
        </div>
        <div class="report-title">
            <h1><i class="bi bi-person-badge-fill" style="font-size:24px;vertical-align:middle;margin-right:8px;"></i>Sales Summary By Doctor</h1>
            <div class="subtitle">Doctor Performance Analysis</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Report Period</strong>
                <span class="dates">
                    <i class="bi bi-calendar-range"></i>
                    {{ $date_from ?? 'N/A' }} &nbsp;→&nbsp; {{ $date_to ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>

    @if($doctorSummary->isNotEmpty())
        @php
            $grandInvoices        = $doctorSummary->sum('invoices_count');
            $grandBefore          = $doctorSummary->sum('total_sales_before_discount');
            $grandAfter           = $doctorSummary->sum('total_sales');
            $grandDiscount        = $grandBefore - $grandAfter;
            $grandDiscountPct     = $grandBefore > 0 ? ($grandDiscount / $grandBefore) * 100 : 0;
            $totalDoctors         = $doctorSummary->count();
        @endphp

            <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-box">
                <div class="number">{{ $totalDoctors }}</div>
                <div class="label">Total Doctors</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $grandInvoices }}</div>
                <div class="label">Total Invoices</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandBefore, 0) }}</div>
                <div class="label">Before Discount (QAR)</div>
            </div>
            <div class="stat-box discount-stat">
                <div class="number">{{ number_format($grandDiscount, 0) }}</div>
                <div class="label">Total Discount (QAR)</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandAfter, 0) }}</div>
                <div class="label">After Discount (QAR)</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width:110px;">Doctor Code</th>
                <th>Doctor Name</th>
                <th style="width:90px;">Invoices</th>
                <th style="width:155px;">Before Discount</th>
                <th style="width:90px;">%</th>
                <th class="discount-col" style="width:195px;">Discount</th>
                <th style="width:155px;">After Discount</th>
                <th style="width:90px;">%</th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctorSummary as $doctor)
                @php
                    $discountAmt = $doctor->total_sales_before_discount - $doctor->total_sales;
                    $discountPct = $doctor->total_sales_before_discount > 0
                                    ? ($discountAmt / $doctor->total_sales_before_discount) * 100
                                    : 0;
                    $pctBefore   = $grandBefore > 0
                                    ? ($doctor->total_sales_before_discount / $grandBefore) * 100
                                    : 0;
                    $pctAfter    = $grandAfter > 0
                                    ? ($doctor->total_sales / $grandAfter) * 100
                                    : 0;
                @endphp
                <tr>
                    <td>{{ $doctor->doctor_code ?? '-' }}</td>
                    <td>{{ $doctor->doctor_name ?? 'Unknown' }}</td>
                    <td>{{ $doctor->invoices_count }}</td>
                    <td>{{ number_format($doctor->total_sales_before_discount, 2) }} QAR</td>
                    <td><span class="pct-badge">{{ number_format($pctBefore, 2) }}%</span></td>
                    <td class="discount-cell">
                        <span class="discount-amount">{{ number_format($discountAmt, 2) }} QAR</span>
                        <span class="discount-pct">&nbsp;({{ number_format($discountPct, 2) }}%)</span>
                    </td>
                    <td>{{ number_format($doctor->total_sales, 2) }} QAR</td>
                    <td><span class="pct-badge">{{ number_format($pctAfter, 2) }}%</span></td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2"><i class="bi bi-calculator"></i> Grand Total</td>
                <td>{{ $grandInvoices }}</td>
                <td>{{ number_format($grandBefore, 2) }} QAR</td>
                <td><span class="pct-badge">100%</span></td>
                <td class="discount-cell">
                    <span class="discount-amount">{{ number_format($grandDiscount, 2) }} QAR</span>
                    <span class="discount-pct">&nbsp;({{ number_format($grandDiscountPct, 2) }}%)</span>
                </td>
                <td>{{ number_format($grandAfter, 2) }} QAR</td>
                <td><span class="pct-badge">100%</span></td>
            </tr>
            </tbody>
        </table>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Doctor Data Found</h3>
            <p>There are no sales records for the selected period.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <strong>Optics Modern</strong> — Sales Summary By Doctor<br>
        System-generated report · Sales performance grouped by doctor referrals<br>
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'sales-summary-doctor-report'])
</body>
</html>
