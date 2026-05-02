<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Customers Report</title>

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
            max-width: 1600px;
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
            border-bottom: 3px solid #3498db;
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
            color: #3498db;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .report-title .date-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Date Range */
        .date-range {
            background: linear-gradient(135deg, #ebf5fb 0%, #d6eaf8 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px solid #aed6f1;
        }

        .date-range strong {
            color: #3498db;
            font-size: 16px;
            font-weight: 700;
        }

        .date-range .dates {
            font-size: 18px;
            color: #333;
            margin: 5px 0 0 0;
            font-weight: 600;
        }

        /* Statistics */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #3498db;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 800;
            color: #3498db;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
            font-size: 11px;
        }

        .report-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .report-table thead th {
            padding: 14px 8px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
            white-space: nowrap;
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
            padding: 10px 8px;
            font-size: 11px;
            color: #333;
            vertical-align: middle;
        }

        .report-table tbody td:first-child {
            font-weight: 600;
            color: #3498db;
        }

        /* Badge */
        .count-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            background: #d6eaf8;
            color: #1f618d;
            border: 1px solid #3498db;
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
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
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
                margin: 1cm;
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
            <h1>Customers Report</h1>
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
            {{ $date_from }} <strong>to</strong> {{ $date_to }}
        </div>
    </div>

    @if(isset($customers) && $customers->count() > 0)
        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $customers->count() }}</div>
                <div class="label">Total Customers</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $customers->sum('invoices_count') }}</div>
                <div class="label">Total Invoices</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($customers->avg('invoices_count'), 1) }}</div>
                <div class="label">Avg Invoices/Customer</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($customers->sum('total_payed'), 2) }}</div>
                <div class="label">Total Amount Paid</div>
            </div>
        </div>

        <!-- Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 150px;">English Name</th>
                <th style="width: 80px;">ID</th>
                <th style="width: 90px;">Created At</th>
                <th style="width: 90px;">Birth Date</th>
                <th style="width: 50px;">Age</th>
                <th style="width: 110px;">Mobile</th>
                <th style="width: 110px;">Office Phone</th>
                <th style="width: 180px;">Email</th>
                <th style="width: 90px;">First Invoice</th>
                <th style="width: 90px;">Last Invoice</th>
                <th style="width: 80px; text-align: center;">Invoices</th>
                <th style="width: 100px; text-align: right;">Total Paid</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>
                        <strong>{{ $customer->english_name }}</strong>
                    </td>
                    <td>{{ $customer->customer_id }}</td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td>{{ $customer->birth_date ?? '-' }}</td>
                    <td style="text-align: center;">{{ $customer->age ?? '-' }}</td>
                    <td>{{ $customer->mobile_number ?? '-' }}</td>
                    <td>{{ $customer->office_number ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ $customer->email ?? '-' }}</td>
                    <td>{{ $customer->first_invoice ? \Carbon\Carbon::parse($customer->first_invoice)->format('Y-m-d') : '-' }}</td>
                    <td>{{ $customer->last_invoice ? \Carbon\Carbon::parse($customer->last_invoice)->format('Y-m-d') : '-' }}</td>
                    <td style="text-align: center;">
                        <span class="count-badge">{{ $customer->invoices_count }}</span>
                    </td>
                    <td style="text-align: right;">
                        <strong>{{ number_format($customer->customerPayedInvoices(), 2) }}</strong>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Customers Found</h3>
            <p>There are no customers with invoices for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Customers Report<br>
            This is a system-generated report. All information is accurate as of the generation date.
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

    // Auto-print option (uncomment if needed)
    // window.onload = function() {
    //     setTimeout(function() {
    //         window.print();
    //     }, 500);
    // };
</script>
</body>
</html>
