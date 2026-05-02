<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Pending Invoices Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        /* Header */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #d68910;
        }

        .report-logo img {
            max-width: 120px;
            height: auto;
        }

        .report-title {
            text-align: right;
        }

        .report-title h1 {
            font-size: 28px;
            color: #d68910;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .report-title .subtitle {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title .date-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Info Header */
        .info-header {
            background: linear-gradient(135deg, #fef5e7 0%, #f9e79f 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #f4d03f;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #d68910;
            font-size: 16px;
            font-weight: 700;
        }

        .info-header .dates {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .info-header .branch {
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }

        /* Statistics */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #fef5e7 0%, #f9e79f 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #d68910;
        }

        .stat-box .number {
            font-size: 24px;
            font-weight: 800;
            color: #d68910;
            margin-bottom: 5px;
        }

        .stat-box .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
            font-size: 13px;
        }

        .report-table thead {
            background: linear-gradient(135deg, #d68910 0%, #b9770e 100%);
        }

        .report-table thead th {
            padding: 15px 10px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover {
            background: #f8f9fa;
        }

        .report-table tbody tr:last-child {
            border-bottom: none;
        }

        .report-table tbody td {
            padding: 12px 10px;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #d68910;
        }

        /* Totals Section */
        .totals-section {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #fef5e7 0%, #f9e79f 100%);
            border-radius: 10px;
            border: 3px solid #d68910;
            box-shadow: 0 4px 15px rgba(214, 137, 16, 0.2);
        }

        .totals-section h3 {
            color: #d68910;
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .totals-section h3 i {
            margin-right: 10px;
        }

        .totals-table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .totals-table tr {
            border-bottom: 2px solid #f4d03f;
        }

        .totals-table tr:last-child {
            border-bottom: none;
            border-top: 4px solid #d68910;
        }

        .totals-table td {
            padding: 14px 20px;
            font-size: 15px;
        }

        .totals-table td:first-child {
            font-weight: 700;
            color: #555;
        }

        .totals-table td:first-child i {
            margin-right: 10px;
            font-size: 16px;
            color: #d68910;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 700;
            color: #d68910;
            font-size: 16px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #666;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            font-size: 14px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #d68910 0%, #b9770e 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(214, 137, 16, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(214, 137, 16, 0.5);
        }

        .print-button i {
            margin-right: 8px;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        .report-footer strong {
            color: #d68910;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .report-container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none !important;
            }

            .report-table tbody tr:hover {
                background: white;
            }

            @page {
                margin: 1.5cm;
                size: landscape;
            }
        }
    </style>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
<div class="report-container">
    <!-- Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
        </div>
        <div class="report-title">
            <h1>Pending Invoices</h1>
            <div class="subtitle">Outstanding Invoices Report</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Report As At:</strong>
                <span class="dates">
                    <i class="bi bi-calendar-x"></i>
                    {{ $date_from }}
                </span>
            </div>
            @if(isset($selectedBranch))
                <div>
                    <strong>Branch:</strong>
                    <span class="branch">
                        <i class="bi bi-building"></i> {{ $selectedBranch->name }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    @if(isset($invoices) && $invoices->count() > 0)
        @php
            $totals_before_discount = 0;
            $total_discount = 0;
            $total_net = 0;
            $total_paid = 0;
            $total_balance = 0;
        @endphp

            <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $invoices->count() }}</div>
                <div class="label">Total Invoices</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($invoices->sum('total_before_discount') ?? $invoices->sum('total'), 2) }}</div>
                <div class="label">Before Discount</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($invoices->sum('total_before_discount') - $invoices->sum('total'), 2) }}</div>
                <div class="label">Total Discount</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($invoices->sum('total'), 2) }}</div>
                <div class="label">Net Total</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($invoices->sum('paied'), 2) }}</div>
                <div class="label">Total Paid</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($invoices->sum('remaining'), 2) }}</div>
                <div class="label">Total Balance</div>
            </div>
        </div>

        <!-- Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 90px;">INVOICE</th>
                <th style="width: 85px;">DATE</th>
                <th>CUSTOMER</th>
                <th style="width: 90px;">PICK-UP</th>
                <th style="width: 100px; text-align: right;">BEFORE DISC.</th>
                <th style="width: 90px; text-align: right;">DISCOUNT</th>
                <th style="width: 100px; text-align: right;">NET TOTAL</th>
                <th style="width: 90px; text-align: right;">PAID</th>
                <th style="width: 90px; text-align: right;">BALANCE</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $item)
                @php
                    // Total before discount = if exists, otherwise use total
                    $totalBefore = $item->total_before_discount ?? $item->total ?? 0;
                    $discount = $totalBefore - ($item->total ?? 0);
                    $net = $item->total ?? 0;

                    $totals_before_discount += $totalBefore;
                    $total_discount += $discount;
                    $total_net += $net;
                    $total_paid += $item->paied ?? 0;
                    $total_balance += $item->remaining ?? 0;
                @endphp

                <tr>
                    <td>{{ $item->invoice_code }}</td>
                    <td>{{ date_format($item->created_at, 'Y-m-d') }}</td>
                    <td>{{ $item->customer->english_name ?? '-' }}</td>
                    <td>{{ $item->pickup_date ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($totalBefore, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($discount, 2) }}</td>
                    <td style="text-align: right;"><strong>{{ number_format($net, 2) }}</strong></td>
                    <td style="text-align: right;">{{ number_format($item->paied ?? 0, 2) }}</td>
                    <td style="text-align: right;"><strong>{{ number_format($item->remaining ?? 0, 2) }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <h3><i class="bi bi-calculator"></i> Summary Totals</h3>
            <table class="totals-table">
                <tr>
                    <td><i class="bi bi-cash-stack"></i> Total Before Discount</td>
                    <td>{{ number_format($totals_before_discount, 2) }} QAR</td>
                </tr>
                <tr>
                    <td><i class="bi bi-percent"></i> Total Discount</td>
                    <td>{{ number_format($total_discount, 2) }} QAR</td>
                </tr>
                <tr>
                    <td><i class="bi bi-calculator"></i> Total Net</td>
                    <td>{{ number_format($total_net, 2) }} QAR</td>
                </tr>
                <tr>
                    <td><i class="bi bi-check-circle"></i> Total Paid</td>
                    <td>{{ number_format($total_paid, 2) }} QAR</td>
                </tr>
                <tr>
                    <td><i class="bi bi-exclamation-circle"></i> Total Balance</td>
                    <td>{{ number_format($total_balance, 2) }} QAR</td>
                </tr>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Pending Invoices</h3>
            <p>There are no pending invoices as of the selected date.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Pending Invoices Report<br>
            This is a system-generated report showing all pending invoices (not delivered, returned, or canceled).<br>
            Report generated on {{ date('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

<!-- Print Button -->
<button type="button" class="print-button" onclick="printReport()">
    <i class="bi bi-printer-fill"></i> Print Report
</button>

<script>
    function printReport() {
        window.print();
    }
</script>
</body>
</html>
