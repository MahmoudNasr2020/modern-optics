<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Cashier Transactions Report</title>

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
            border-bottom: 3px solid #009688;
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
            color: #009688;
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
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #80cbc4;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #009688;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #009688;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 800;
            color: #009688;
            margin-bottom: 5px;
        }

        .stat-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Transactions Table */
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
            background: linear-gradient(135deg, #009688 0%, #00796b 100%);
        }

        .report-table thead th {
            padding: 18px 12px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
        }

        .report-table thead th:nth-child(7) {
            text-align: right;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover:not(.summary-row) {
            background: #f8f9fa;
        }

        .report-table tbody td {
            padding: 14px 12px;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:nth-child(7) {
            text-align: right;
            font-weight: 700;
            font-family: 'Courier New', monospace;
        }

        /* Transaction Type Rows */
        .sale-row {
            background: rgba(232, 245, 233, 0.3);
        }

        .refund-row {
            background: rgba(255, 235, 238, 0.4);
            color: #d32f2f;
        }

        .refund-row td:first-child {
            font-weight: 800;
        }

        .expense-row {
            background: rgba(255, 243, 224, 0.4);
            color: #f57c00;
        }

        .expense-row td:first-child {
            font-weight: 800;
        }

        /* Summary Table */
        .summary-section {
            margin-top: 40px;
        }

        .summary-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #009688;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .summary-title i {
            margin-right: 10px;
        }

        .summary-table {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 10px;
            overflow: hidden;
        }

        .summary-table thead {
            background: linear-gradient(135deg, #00796b 0%, #004d40 100%);
        }

        .summary-table thead th {
            padding: 18px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .summary-table thead th:nth-child(n+2) {
            text-align: right;
        }

        .summary-table tbody tr {
            border-bottom: 1px solid #e9ecef;
        }

        .summary-table tbody tr:last-child {
            border-bottom: none;
        }

        .summary-table tbody td {
            padding: 16px 15px;
            font-size: 15px;
        }

        .summary-table tbody td:first-child {
            font-weight: 700;
            color: #37474f;
        }

        .summary-table tbody td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .summary-table tbody td:nth-child(2) {
            color: #2e7d32;
        }

        .summary-table tbody td:nth-child(3),
        .summary-table tbody td:nth-child(4) {
            color: #d32f2f;
        }

        .summary-table tbody td:nth-child(5) {
            color: #00796b;
            font-size: 16px;
        }

        /* Grand Total Row */
        .summary-table tfoot {
            background: linear-gradient(135deg, #009688 0%, #00796b 100%);
        }

        .summary-table tfoot td {
            padding: 20px 15px;
            color: white;
            font-weight: 800;
            font-size: 16px;
        }

        .summary-table tfoot td:first-child {
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-table tfoot td:nth-child(n+2) {
            text-align: right;
            font-family: 'Courier New', monospace;
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
            background: linear-gradient(135deg, #009688 0%, #00796b 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 150, 136, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 150, 136, 0.5);
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
            color: #009688;
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
                background: transparent;
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
            <h1>Cashier Transactions</h1>
            <div class="subtitle">Payment Activities Report</div>
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
                    {{ $date_from }} <strong>to</strong> {{ $date_to }}
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

    @if($transactions && $transactions->isNotEmpty())
        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $transactions->count() }}</div>
                <div class="label">Total Transactions</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $transactions->where('transaction_type', 'sale')->count() }}</div>
                <div class="label">Sales</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $transactions->where('transaction_type', 'refund')->count() }}</div>
                <div class="label">Refunds</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $transactions->where('transaction_type', 'expense')->count() }}</div>
                <div class="label">Expenses</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ number_format($netTotals->sum(), 2) }}</div>
                <div class="label">Net Total (QAR)</div>
            </div>
        </div>

        <!-- Detailed Transactions Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 120px;">TYPE</th>
                <th style="width: 110px;">INVOICE</th>
                <th style="width: 140px;">DATE/TIME</th>
                <th style="width: 100px;">CUST. CODE</th>
                <th style="width: 200px;">CUSTOMER</th>
                <th style="width: 130px;">PAYMENT TYPE</th>
                <th style="width: 120px;">AMOUNT (QAR)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr class="{{ $transaction->transaction_type }}-row">
                    <td>
                        @if($transaction->transaction_type == 'refund')
                            <strong><i class="bi bi-arrow-return-left"></i> REFUND</strong>
                        @elseif($transaction->transaction_type == 'expense')
                            <strong><i class="bi bi-wallet2"></i> EXPENSE</strong>
                        @else
                            <i class="bi bi-cart-check"></i> SALE
                        @endif
                    </td>
                    <td>{{ optional($transaction->invoice)->invoice_code ?? '-' }}</td>
                    <td>{{ $transaction->transaction_date->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaction->customer_id ?? '-' }}</td>
                    <td>{{ optional($transaction->customer)->english_name ?? '-' }}</td>
                    <td>{{ strtoupper($transaction->payment_type) }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Summary by Payment Method -->
        <div class="summary-section">
            <div class="summary-title">
                <i class="bi bi-calculator"></i> Summary by Payment Method
            </div>

            <table class="summary-table">
                <thead>
                <tr>
                    <th>PAYMENT METHOD</th>
                    <th>SALES</th>
                    <th>REFUNDS</th>
                    <th>EXPENSES</th>
                    <th>NET TOTAL</th>
                </tr>
                </thead>
                <tbody>
                @foreach(['CASH', 'VISA', 'MASTERCARD', 'MADA', 'ATM', 'BANK_TRANSFER', 'GIFT VOUCHER', 'POINT'] as $type)
                    @php
                        $sales = $salesTotals[$type] ?? 0;
                        $refunds = $refundsTotals[$type] ?? 0;
                        $expense = $expensesTotals[$type] ?? 0;
                        $net = $netTotals[$type] ?? 0;
                    @endphp

                    @if($sales != 0 || $refunds != 0 || $expense != 0)
                        <tr>
                            <td>{{ $type }}</td>
                            <td>{{ number_format($sales, 2) }}</td>
                            <td>{{ number_format($refunds, 2) }}</td>
                            <td>{{ number_format($expense, 2) }}</td>
                            <td>{{ number_format($net, 2) }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td>Grand Total</td>
                    <td>{{ number_format($salesTotals->sum(), 2) }}</td>
                    <td>{{ number_format($refundsTotals->sum(), 2) }}</td>
                    <td>{{ number_format($expensesTotals->sum(), 2) }}</td>
                    <td>{{ number_format($netTotals->sum(), 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Transactions Found</h3>
            <p>There are no cashier transactions for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Cashier Transactions Report<br>
            This is a system-generated report showing all payment transactions including sales, refunds, and expenses.<br>
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
