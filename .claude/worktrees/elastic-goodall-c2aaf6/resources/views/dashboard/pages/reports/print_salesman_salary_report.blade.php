<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Salesman Salary Report</title>

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
            border-bottom: 3px solid #9b59b6;
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
            color: #9b59b6;
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
            background: linear-gradient(135deg, #f4ecf7 0%, #ebdef0 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #d2b4de;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #9b59b6;
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

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #f4ecf7 0%, #ebdef0 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #9b59b6;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 800;
            color: #9b59b6;
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
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
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

        .report-table thead th:nth-child(n+2) {
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

        .report-table tbody td:nth-child(n+2) {
            text-align: right;
            font-weight: 600;
        }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #9b59b6;
            font-size: 16px;
        }

        /* Total Row */
        .total-row {
            background: linear-gradient(135deg, #ecf0f1 0%, #d5dbdb 100%);
            font-weight: 700;
            border-top: 3px solid #9b59b6;
            border-bottom: 3px solid #9b59b6;
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
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.5);
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
            color: #9b59b6;
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
            <h1>Salesman Salary Report</h1>
            <div class="subtitle">Commission & Salary Breakdown</div>
            <div class="date-info">
                <i class="bi bi-calendar-event"></i> Generated: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div>
                <strong>Report Period:</strong>
                <span class="dates">
                        <i class="bi bi-calendar-range"></i>
                        {{ $date_from ?? 'N/A' }} <strong>to</strong> {{ $date_to ?? 'N/A' }}
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

    @if($data->isNotEmpty())
        @php
            $totalSales = $data->sum('total_sales');
            $totalBaseSalary = $data->sum('salary');
            $totalCommissionValue = $data->sum('commission_value');
            $grandTotal = $data->sum('total_salary');
            $totalInvoices = $data->sum('invoice_count');
        @endphp

            <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-box">
                <div class="number">{{ $data->count() }}</div>
                <div class="label">Salesmen</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalSales, 2) }}</div>
                <div class="label">Total Sales</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($totalCommissionValue, 2) }}</div>
                <div class="label">Total Commission</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($grandTotal, 2) }}</div>
                <div class="label">Total Salary</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $totalInvoices }}</div>
                <div class="label">Total Invoices</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th>SALESMAN</th>
                @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                    <th>BRANCH</th>
                @endif
                <th>INVOICES</th>
                <th>TOTAL SALES</th>
                <th>BASE SALARY</th>
                <th>COMMISSION %</th>
                <th>COMMISSION QAR</th>
                <th>TOTAL SALARY</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->salesman }}</td>
                    @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                        <td>{{ $row->branch_name }}</td>
                    @endif
                    <td>{{ $row->invoice_count }}</td>
                    <td>{{ number_format($row->total_sales, 2) }}</td>
                    <td>{{ number_format($row->salary, 2) }}</td>
                    <td>{{ $row->commission }}%</td>
                    <td>{{ number_format($row->commission_value, 2) }}</td>
                    <td>{{ number_format($row->total_salary, 2) }}</td>
                </tr>
            @endforeach

            <!-- Total Row -->
            <tr class="total-row">
                <td>
                    <i class="bi bi-calculator"></i> GRAND TOTAL
                </td>
                @if(auth()->user()->canSeeAllBranches() && !isset($selectedBranch))
                    <td>-</td>
                @endif
                <td>{{ $totalInvoices }}</td>
                <td>{{ number_format($totalSales, 2) }}</td>
                <td>{{ number_format($totalBaseSalary, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($totalCommissionValue, 2) }}</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
            </tbody>
        </table>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Salary Data Found</h3>
            <p>There are no salesman records for the selected period.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Salesman Salary Report<br>
            This report shows salary calculations including base salary and commission based on sales performance.<br>
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
