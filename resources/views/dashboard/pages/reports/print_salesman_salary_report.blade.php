<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Salesman Salary Report</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #f3e5f5;
            padding: 24px;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(155, 89, 182, 0.15);
            border-radius: 12px;
        }

        /* ─── Header ─── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 3px solid #9b59b6;
        }

        .report-logo img { max-width: 120px; height: auto; }

        .report-title h1 {
            font-size: 28px;
            color: #9b59b6;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .report-title .subtitle {
            font-size: 12px;
            color: #9b59b6;
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
            background: linear-gradient(135deg, #f4ecf7 0%, #ebdef0 100%);
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 28px;
            border: 2px solid #d2b4de;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #9b59b6;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 2px;
        }

        .info-header .dates,
        .info-header .branch {
            font-size: 16px;
            color: #6c3483;
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
            background: linear-gradient(135deg, #f4ecf7 0%, #ebdef0 100%);
            padding: 20px 14px;
            border-radius: 12px;
            text-align: center;
            border-left: 5px solid #9b59b6;
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.12);
            transition: transform 0.2s;
        }

        .stat-box:hover { transform: translateY(-3px); }

        .stat-box.discount-stat { border-left-color: #e74c3c; }
        .stat-box.discount-stat .number { color: #c0392b; }

        .stat-box .number {
            font-size: 22px;
            font-weight: 800;
            color: #9b59b6;
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
            background: linear-gradient(135deg, #9b59b6 0%, #7d3c98 100%);
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

        .report-table thead th:nth-child(n+2) { text-align: right; }

        /* discount group header accent */
        .report-table thead th.discount-col { background: rgba(0,0,0,0.18); }

        .report-table tbody tr {
            border-bottom: 1px solid #f4ecf7;
            transition: background 0.15s;
        }

        .report-table tbody tr:hover:not(.total-row) { background: #fdf8ff; }

        .report-table tbody td {
            padding: 14px 12px;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(n+2) { text-align: right; font-weight: 600; }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #9b59b6;
            font-size: 14px;
        }

        /* discount cells */
        .discount-cell { background: #fdf2f0; }
        .discount-amount { color: #c0392b; font-weight: 700; }
        .discount-pct    { color: #e74c3c; font-size: 11px; font-weight: 600; }

        /* salary cells */
        .salary-highlight { color: #1a7a4a; font-weight: 800 !important; }

        /* ─── Total Row ─── */
        .total-row {
            background: linear-gradient(135deg, #9b59b6 0%, #7d3c98 100%);
        }

        .total-row td {
            padding: 18px 12px;
            color: white !important;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .total-row td:first-child { font-size: 14px; }

        .total-row td.discount-cell { background: rgba(0,0,0,0.15); }
        .total-row .discount-amount { color: #f1948a !important; }
        .total-row .discount-pct    { color: #f5b7b1 !important; }
        .total-row .salary-highlight { color: #a9dfbf !important; }

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
            background: linear-gradient(135deg, #9b59b6 0%, #7d3c98 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.45);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(155, 89, 182, 0.55);
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

        .report-footer strong { color: #9b59b6; }

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
            <h1><i class="bi bi-person-badge-fill" style="font-size:24px;vertical-align:middle;margin-right:8px;"></i>Salesman Salary Report</h1>
            <div class="subtitle">Commission &amp; Salary Breakdown</div>
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
            @if(isset($selectedBranch))
                <div>
                    <strong>Branch</strong>
                    <span class="branch">
                    <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                </span>
                </div>
            @endif
        </div>
    </div>

    @if($data->isNotEmpty())
        @php
            $totalBeforeDiscount  = $data->sum('total_before_discount');
            $totalAfterDiscount   = $data->sum('total_after_discount');
            $totalDiscount        = $totalBeforeDiscount - $totalAfterDiscount;
            $totalDiscountPct     = $totalBeforeDiscount > 0 ? ($totalDiscount / $totalBeforeDiscount) * 100 : 0;
            $totalBaseSalary      = $data->sum('salary');
            $totalCommissionValue = $data->sum('commission_value');
            $grandTotal           = $data->sum('total_salary');
            $totalInvoices        = $data->sum('invoice_count');
        @endphp

            <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-box">
                <div class="number">{{ $data->count() }}</div>
                <div class="label">Salesmen</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalBeforeDiscount, 0) }}</div>
                <div class="label">Sales Before Disc. (QAR)</div>
            </div>
            <div class="stat-box discount-stat">
                <div class="number">{{ number_format($totalDiscount, 0) }}</div>
                <div class="label">Total Discount (QAR)</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalAfterDiscount, 0) }}</div>
                <div class="label">Sales After Disc. (QAR)</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandTotal, 0) }}</div>
                <div class="label">Total Salary (QAR)</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th>Salesman</th>
                @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                    <th>Branch</th>
                @endif
                <th>Invoices</th>
                <th>Before Discount</th>
                <th class="discount-col">Discount</th>
                <th>After Discount</th>
                <th>Base Salary</th>
                <th>Commission %</th>
                <th>Commission QAR</th>
                <th>Total Salary</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                @php
                    $discountAmt = $row->total_before_discount - $row->total_after_discount;
                    $discountPct = $row->total_before_discount > 0
                                    ? ($discountAmt / $row->total_before_discount) * 100
                                    : 0;
                @endphp
                <tr>
                    <td>{{ $row->salesman }}</td>
                    @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                        <td>{{ $row->branch_name }}</td>
                    @endif
                    <td>{{ $row->invoice_count }}</td>
                    <td>{{ number_format($row->total_before_discount, 2) }} QAR</td>
                    <td class="discount-cell">
                        <span class="discount-amount">{{ number_format($discountAmt, 2) }} QAR</span>
                        <span class="discount-pct">&nbsp;({{ number_format($discountPct, 2) }}%)</span>
                    </td>
                    <td>{{ number_format($row->total_after_discount, 2) }} QAR</td>
                    <td>{{ number_format($row->salary, 2) }} QAR</td>
                    <td>{{ $row->commission }}%</td>
                    <td>{{ number_format($row->commission_value, 2) }} QAR</td>
                    <td class="salary-highlight">{{ number_format($row->total_salary, 2) }} QAR</td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td><i class="bi bi-calculator"></i> Grand Total</td>
                @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                    <td>—</td>
                @endif
                <td>{{ $totalInvoices }}</td>
                <td>{{ number_format($totalBeforeDiscount, 2) }} QAR</td>
                <td class="discount-cell">
                    <span class="discount-amount">{{ number_format($totalDiscount, 2) }} QAR</span>
                    <span class="discount-pct">&nbsp;({{ number_format($totalDiscountPct, 2) }}%)</span>
                </td>
                <td>{{ number_format($totalAfterDiscount, 2) }} QAR</td>
                <td>{{ number_format($totalBaseSalary, 2) }} QAR</td>
                <td>—</td>
                <td>{{ number_format($totalCommissionValue, 2) }} QAR</td>
                <td class="salary-highlight">{{ number_format($grandTotal, 2) }} QAR</td>
            </tr>
            </tbody>
        </table>

    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Salary Data Found</h3>
            <p>There are no salesman records for the selected period.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <strong>Optics Modern</strong> — Salesman Salary Report<br>
        Commission calculated on After-Discount sales · System-generated report<br>
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'salesman-salary-report'])
</body>
</html>
