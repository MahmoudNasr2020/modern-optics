<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Summary By Doctor</title>

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
            max-width: 1200px;
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
            border-bottom: 3px solid #16a085;
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
            color: #16a085;
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

        /* Date Range */
        .date-range {
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px solid #7dcea0;
        }

        .date-range strong {
            color: #16a085;
            font-size: 16px;
            font-weight: 700;
        }

        .date-range .dates {
            font-size: 18px;
            color: #333;
            margin: 5px 0 0 0;
            font-weight: 600;
        }

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #16a085;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 800;
            color: #16a085;
            margin-bottom: 5px;
        }

        .stat-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Main Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        .report-table thead {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
        }

        .report-table thead th {
            padding: 18px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(n+3) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover:not(.total-row) {
            background: #f8f9fa;
        }

        .report-table tbody td {
            padding: 15px;
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(n+3) {
            text-align: right;
            font-weight: 600;
        }

        .report-table tbody td:nth-child(2) {
            font-weight: 700;
            color: #16a085;
            font-size: 16px;
        }

        .report-table tbody td:first-child {
            color: #666;
            font-weight: 600;
        }

        /* Total Row */
        .total-row {
            background: linear-gradient(135deg, #ecf0f1 0%, #d5dbdb 100%);
            font-weight: 700;
            border-top: 3px solid #16a085;
            border-bottom: 3px solid #16a085;
        }

        .total-row td {
            padding: 18px 15px;
            color: #2c3e50;
            font-size: 17px;
        }

        .total-row td:first-child {
            font-size: 16px;
            color: #2c3e50;
        }

        .total-row i {
            margin-right: 8px;
        }

        /* Percentage Badge */
        .percentage-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, #d5f4e6 0%, #abebc6 100%);
            color: #0e6655;
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
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(22, 160, 133, 0.5);
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
            color: #16a085;
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
            <h1>Sales Summary By Doctor</h1>
            <div class="subtitle">Doctor Performance Analysis</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Date Range -->
    <div class="date-range">
        <strong>Report Period</strong>
        <div class="dates">
            <i class="bi bi-calendar-range"></i>
            {{ $date_from ?? 'N/A' }} <strong>to</strong> {{ $date_to ?? 'N/A' }}
        </div>
    </div>

    @if($doctorSummary->isNotEmpty())
        @php
            $grandInvoices = $doctorSummary->sum('invoices_count');
            $grandTotal = $doctorSummary->sum('total_sales');
            $grandTotalBeforeDiscount = $doctorSummary->sum('total_sales_before_discount');
            $totalDoctors = $doctorSummary->count();
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
                <div class="number">{{ number_format($grandTotalBeforeDiscount, 2) }}</div>
                <div class="label">Before Discount</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandTotal, 2) }}</div>
                <div class="label">Total Sales</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandTotalBeforeDiscount - $grandTotal, 2) }}</div>
                <div class="label">Total Discount</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 120px;">DOCTOR CODE</th>
                <th>DOCTOR NAME</th>
                <th style="width: 130px;">INVOICES</th>
                <th style="width: 150px;">BEFORE DISCOUNT</th>
                <th style="width: 150px;">TOTAL SALES</th>
                <th style="width: 120px;">% OF TOTAL</th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctorSummary as $doctor)
                <tr>
                    <td>{{ $doctor->doctor_code ?? '-' }}</td>
                    <td>{{ $doctor->doctor_name ?? 'Unknown' }}</td>
                    <td>{{ $doctor->invoices_count }}</td>
                    <td>{{ number_format($doctor->total_sales_before_discount, 2) }}</td>
                    <td>{{ number_format($doctor->total_sales, 2) }}</td>
                    <td>
                                <span class="percentage-badge">
                                    {{ $grandTotal > 0 ? number_format(($doctor->total_sales / $grandTotal) * 100, 2) : 0 }}%
                                </span>
                    </td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2">
                    <i class="bi bi-calculator"></i> GRAND TOTAL
                </td>
                <td style="float: right;">{{ $grandInvoices }}</td>
                <td>{{ number_format($grandTotalBeforeDiscount, 2) }}</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
                <td>
                    <span class="percentage-badge">100%</span>
                </td>
            </tr>
            </tbody>
        </table>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Doctor Data Found</h3>
            <p>There are no sales records for the selected period.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Sales Summary By Doctor<br>
            This report shows sales performance grouped by doctor referrals.<br>
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
