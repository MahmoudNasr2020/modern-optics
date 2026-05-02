<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Statement - {{ $customer->english_name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #667eea;
        }

        .customer-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .customer-info p {
            margin: 5px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }

        .stat-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }

        tfoot td {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <h1>CUSTOMER STATEMENT</h1>
    <p>Optics Modern</p>
    <p style="margin: 5px 0;">Date: {{ now()->format('d M Y') }}</p>
</div>

<div class="customer-info">
    <h3 style="margin-top: 0;">Customer Information</h3>
    <p><strong>Name:</strong> {{ $customer->english_name }}</p>
    <p><strong>Customer ID:</strong> {{ $customer->customer_id }}</p>
    <p><strong>Mobile:</strong> {{ $customer->mobile ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
</div>

<div class="stats-grid">
    <div class="stat-box">
        <div class="label">Total Invoices</div>
        <div class="value">{{ $stats['total_invoices'] }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Total Purchases</div>
        <div class="value">{{ number_format($stats['total_purchases'], 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Total Paid</div>
        <div class="value" style="color: #27ae60;">{{ number_format($stats['total_paid'], 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Outstanding</div>
        <div class="value" style="color: #e74c3c;">{{ number_format($stats['total_remaining'], 2) }}</div>
    </div>
</div>

<h3>Invoice History</h3>
<table>
    <thead>
    <tr>
        <th>Invoice #</th>
        <th>Date</th>
        <th>Items</th>
        <th>Total</th>
        <th>Paid</th>
        <th>Remaining</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>#{{ $invoice->invoice_code }}</td>
            <td>{{ $invoice->created_at->format('d M Y') }}</td>
            <td>{{ $invoice->invoiceItems->count() }}</td>
            <td>{{ number_format($invoice->total, 2) }} QAR</td>
            <td style="color: #27ae60;">{{ number_format($invoice->paied, 2) }} QAR</td>
            <td style="color: #e74c3c;">{{ number_format($invoice->remaining, 2) }} QAR</td>
            <td>{{ ucfirst($invoice->status) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">TOTALS:</td>
        <td>{{ number_format($stats['total_purchases'], 2) }} QAR</td>
        <td style="color: #27ae60;">{{ number_format($stats['total_paid'], 2) }} QAR</td>
        <td style="color: #e74c3c;">{{ number_format($stats['total_remaining'], 2) }} QAR</td>
        <td></td>
    </tr>
    </tfoot>
</table>

<div class="footer">
    <p>This is a computer-generated statement and does not require a signature.</p>
    <p>© {{ now()->year }} Optics Modern. All rights reserved.</p>
</div>

<div class="no-print" style="text-align: center; margin-top: 30px;">
    <button onclick="window.print()" style="padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Print Statement
    </button>
</div>
</body>
</html>
