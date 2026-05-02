<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Delivered Invoices Report</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,.1);
            border-radius: 8px;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #00a86b;
        }

        .report-logo img { max-width: 120px; height: auto; }

        .report-title { text-align: right; }
        .report-title h1 { font-size: 28px; color: #00a86b; margin-bottom: 5px; font-weight: 700; }
        .report-title .subtitle { font-size: 14px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .report-title .date-info { font-size: 13px; color: #888; margin-top: 4px; }

        /* ── Info banner ── */
        .info-header {
            background: linear-gradient(135deg, #f0fff8 0%, #e8f5e9 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #b2dfdb;
        }
        .info-header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .info-header strong { color: #00a86b; font-size: 15px; font-weight: 700; }
        .info-header .dates { font-size: 17px; color: #333; font-weight: 600; }
        .info-header .branch { font-size: 15px; color: #555; font-weight: 600; }

        /* ── Stats ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: linear-gradient(135deg, #f0fff8 0%, #e8f5e9 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #00a86b;
        }
        .stat-box .number { font-size: 22px; font-weight: 800; color: #00a86b; margin-bottom: 5px; }
        .stat-box .label  { font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }

        /* ── Table ── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            border-radius: 10px;
            overflow: hidden;
            font-size: 13px;
        }
        .report-table thead { background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%); }
        .report-table thead th {
            padding: 14px 10px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: none;
        }
        .report-table tbody tr { border-bottom: 1px solid #e9ecef; transition: background .2s; }
        .report-table tbody tr:hover { background: #f0fff8; }
        .report-table tbody tr:last-child { border-bottom: none; }
        .report-table tbody td { padding: 11px 10px; font-size: 13px; color: #333; font-weight: 500; }
        .report-table tbody td:first-child { font-weight: 700; color: #00a86b; }
        .report-table tfoot td {
            padding: 12px 10px;
            font-weight: 700;
            font-size: 13px;
            background: linear-gradient(135deg, #f0fff8, #e8f5e9);
            border-top: 2px solid #00a86b;
            color: #00a86b;
        }

        /* ── Totals section ── */
        .totals-section {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f0fff8 0%, #e8f5e9 100%);
            border-radius: 10px;
            border: 3px solid #00a86b;
            box-shadow: 0 4px 15px rgba(0,168,107,.15);
        }
        .totals-section h3 {
            color: #00a86b; font-size: 18px; margin-bottom: 20px;
            text-align: center; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        }
        .totals-table { width: 100%; max-width: 560px; margin: 0 auto; }
        .totals-table tr { border-bottom: 2px solid #b2dfdb; }
        .totals-table tr:last-child { border-bottom: none; border-top: 3px solid #00a86b; }
        .totals-table td { padding: 13px 20px; font-size: 15px; }
        .totals-table td:first-child { font-weight: 700; color: #555; }
        .totals-table td:last-child  { text-align: right; font-weight: 700; color: #00a86b; font-size: 16px; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i  { font-size: 64px; color: #ddd; margin-bottom: 20px; display: block; }
        .empty-state h3 { color: #666; font-size: 20px; margin-bottom: 10px; }
        .empty-state p  { color: #999; font-size: 14px; }

        /* ── Print button ── */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #00a86b, #008b5a);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0,168,107,.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .3s;
        }
        .print-button:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,168,107,.5); }

        @media print {
            body { background: white; padding: 0; }
            .report-container { box-shadow: none; padding: 20px; max-width: 100%; }
            .print-button { display: none !important; }
            .report-table { font-size: 11px; }
            .report-table thead th { padding: 10px 7px; }
            .report-table tbody td { padding: 8px 7px; }
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
        <div class="report-title">
            <h1>Delivered Invoices</h1>
            <div class="subtitle">Delivery Status Report</div>
            <div class="date-info">Generated: {{ date('Y-m-d H:i') }}</div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Delivery Period:</strong>
                <span class="dates">{{ $date_from }} &nbsp;→&nbsp; {{ $date_to }}</span>
            </div>
            @if(isset($selectedBranch) && $selectedBranch)
                <div>
                    <strong>Branch:</strong>
                    <span class="branch">{{ $selectedBranch->name }}</span>
                </div>
            @else
                <div>
                    <strong>Branch:</strong>
                    <span class="branch">All Branches</span>
                </div>
            @endif
            <div>
                <strong>Total Invoices:</strong>
                <span class="dates">{{ $invoices->count() }}</span>
            </div>
        </div>
    </div>

    @if($invoices->count() > 0)

        @php
            $totalAmount  = $invoices->sum('total');
            $totalPaid    = $invoices->sum('paied');
            $totalRemain  = $invoices->sum('remaining');
        @endphp

        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $invoices->count() }}</div>
                <div class="label">Delivered Invoices</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalAmount, 2) }}</div>
                <div class="label">Total Amount</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalPaid, 2) }}</div>
                <div class="label">Total Paid</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalRemain, 2) }}</div>
                <div class="label">Total Remaining</div>
            </div>
            <div class="stat-box">
                <div class="number">
                    {{ $invoices->where('remaining', 0)->count() }}
                </div>
                <div class="label">Fully Paid</div>
            </div>
            <div class="stat-box">
                <div class="number">
                    {{ $invoices->where('remaining', '>', 0)->count() }}
                </div>
                <div class="label">Has Balance</div>
            </div>
        </div>

        <!-- Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th style="width:100px;">Invoice</th>
                <th style="width:90px;">Invoice Date</th>
                <th style="width:180px;">Customer</th>
                <th style="width:90px;">Branch</th>
                <th style="width:130px;">Delivered At</th>
                <th style="width:130px;">Delivered By</th>
                <th style="width:100px;text-align:right;">Total</th>
                <th style="width:100px;text-align:right;">Paid</th>
                <th style="width:100px;text-align:right;">Remaining</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $i => $invoice)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $invoice->invoice_code }}</td>
                    <td>{{ $invoice->created_at ? date('Y-m-d', strtotime($invoice->created_at)) : '-' }}</td>
                    <td>
                        <strong>{{ $invoice->customer->english_name ?? '-' }}</strong>
                        @if($invoice->customer && $invoice->customer->phone)
                            <br><small style="color:#888;">{{ $invoice->customer->phone }}</small>
                        @endif
                    </td>
                    <td>{{ $invoice->branch->name ?? '-' }}</td>
                    <td>{{ $invoice->delivered_at ? date('Y-m-d H:i', strtotime($invoice->delivered_at)) : '-' }}</td>
                    <td>{{ $invoice->deliveredBy->full_name ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($invoice->total ?? 0, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($invoice->paied ?? 0, 2) }}</td>
                    <td style="text-align:right;">
                        @if(($invoice->remaining ?? 0) > 0)
                            <span style="color:#e74c3c;font-weight:700;">{{ number_format($invoice->remaining, 2) }}</span>
                        @else
                            <span style="color:#00a86b;font-weight:700;">0.00</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;">{{ number_format($totalAmount, 2) }}</td>
                <td style="text-align:right;">{{ number_format($totalPaid, 2) }}</td>
                <td style="text-align:right;">{{ number_format($totalRemain, 2) }}</td>
            </tr>
            </tfoot>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <h3>Summary</h3>
            <table class="totals-table">
                <tr>
                    <td>Total Invoices Delivered</td>
                    <td>{{ $invoices->count() }}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Paid</td>
                    <td>{{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Remaining Balance</td>
                    <td>{{ number_format($totalRemain, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Fully Paid Invoices</strong></td>
                    <td><strong>{{ $invoices->where('remaining', 0)->count() }}</strong></td>
                </tr>
            </table>
        </div>

    @else
        <div class="empty-state">
            <i class="bi bi-check-circle"></i>
            <h3>No Delivered Invoices Found</h3>
            <p>No invoices were delivered in the selected date range ({{ $date_from }} → {{ $date_to }}).</p>
        </div>
    @endif

</div>

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons/bootstrap-icons.min.css') }}">
@include('dashboard.partials._report_export_toolbar', ['filename' => 'delivered-invoices-report'])
</body>
</html>
