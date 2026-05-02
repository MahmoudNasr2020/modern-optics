<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }

        .stat-box h3 {
            margin: 0;
            font-size: 28px;
        }

        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }

        tfoot td {
            background: #f8f9fa;
            font-weight: bold;
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
    <h1>EXPENSES REPORT</h1>
    <p>Optics Modern</p>
    <p>Period: {{ $date_from }} to {{ $date_to }}</p>
</div>

<div class="stats-grid">
    <div class="stat-box">
        <h3>{{ $stats['expenses_count'] }}</h3>
        <p>Total Expenses</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($stats['total_expenses'], 2) }}</h3>
        <p>Total Amount (QAR)</p>
    </div>
    <div class="stat-box">
        <h3>{{ count($byCategory) }}</h3>
        <p>Categories Used</p>
    </div>
</div>

<h3>Expenses by Category</h3>
<table>
    <thead>
    <tr>
        <th>Category</th>
        <th style="text-align: right;">Amount (QAR)</th>
        <th style="text-align: right;">Percentage</th>
    </tr>
    </thead>
    <tbody>
    @foreach($byCategory as $categoryName => $amount)
        <tr>
            <td>{{ $categoryName }}</td>
            <td style="text-align: right;">{{ number_format($amount, 2) }}</td>
            <td style="text-align: right;">{{ number_format(($amount / $stats['total_expenses']) * 100, 1) }}%</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td>TOTAL</td>
        <td style="text-align: right;">{{ number_format($stats['total_expenses'], 2) }}</td>
        <td style="text-align: right;">100%</td>
    </tr>
    </tfoot>
</table>

<h3>Detailed Expenses</h3>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Description</th>
        <th>Vendor</th>
        <th style="text-align: right;">Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($expenses as $expense)
        <tr>
            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
            <td>{{ $expense->category->name }}</td>
            <td>{{ $expense->description }}</td>
            <td>{{ $expense->vendor_name ?? '-' }}</td>
            <td style="text-align: right;">{{ number_format($expense->amount, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    <p>This is a computer-generated report.</p>
    <p>© {{ now()->year }} Optics Modern. All rights reserved.</p>
</div>

<div class="no-print" style="text-align: center; margin-top: 30px;">
    <button onclick="window.print()" style="padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        Print Report
    </button>
</div>
</body>
</html>
