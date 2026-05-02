<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sales Transactions Report - Optics Modern</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }

        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 12px;
        }

        /* Enhanced Header */
        .report-header {
            display: grid;
            grid-template-columns: 150px 1fr 150px;
            gap: 20px;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 4px solid #3498db;
            position: relative;
        }

        .report-header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 200px;
            height: 4px;
            background: linear-gradient(90deg, #3498db, transparent);
        }

        .report-logo {
            text-align: left;
        }

        .report-logo img {
            max-width: 140px;
            height: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .report-center {
            text-align: center;
        }

        .report-center h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .report-center .subtitle {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .report-meta {
            text-align: right;
            font-size: 13px;
            color: #7f8c8d;
        }

        .report-meta .meta-item {
            margin-bottom: 6px;
        }

        .report-meta .meta-item i {
            color: #3498db;
            margin-right: 5px;
        }

        .report-meta .meta-item strong {
            color: #2c3e50;
        }

        /* Enhanced Info Header */
        .info-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            color: white;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item i {
            font-size: 24px;
            opacity: 0.9;
        }

        .info-label {
            font-size: 11px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            font-size: 20px;
            font-weight: 700;
        }

        /* Enhanced Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-box {
            background: white;
            padding: 25px 20px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid #ecf0f1;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.2);
            border-color: #3498db;
        }

        .stat-icon {
            font-size: 32px;
            color: #3498db;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stat-change {
            font-size: 11px;
            margin-top: 5px;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .stat-change.positive {
            background: #d4edda;
            color: #155724;
        }

        .stat-change.neutral {
            background: #e8f4f8;
            color: #3498db;
        }

        /* Enhanced Main Table */
        .report-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 35px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e0e6ed;
        }

        .report-table thead {
            background: #1e3a5f;
            background: linear-gradient(180deg, #1e3a5f 0%, #2c5282 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .report-table thead th {
            padding: 20px 15px;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border: none;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .report-table thead th:nth-child(n+4) {
            text-align: right;
        }

        .report-table thead th:nth-child(4) {
            background: rgba(255,255,255,0.1);
        }

        .report-table thead th:nth-child(5) {
            background: rgba(231,76,60,0.2);
        }

        .report-table thead th:nth-child(6) {
            background: rgba(46,125,50,0.2);
        }

        .report-table thead th:nth-child(7) {
            background: rgba(0,172,193,0.2);
        }

        .report-table thead th:nth-child(8) {
            background: rgba(211,47,47,0.2);
        }

        /* Enhanced Date Group Header - Much Better Design */
        .date-group-header {
            background: #2874a6 !important;
            background: linear-gradient(180deg, #2874a6 0%, #1f618d 100%) !important;
            color: white !important;
            font-weight: 800;
            font-size: 16px;
            padding: 18px 25px !important;
            position: relative;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            border-top: 3px solid #3498db;
            border-bottom: 2px solid #1f618d;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .date-group-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            background: #f39c12;
            box-shadow: 2px 0 5px rgba(243, 156, 18, 0.5);
        }

        .date-group-header::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 15px solid rgba(255,255,255,0.1);
        }

        .date-group-header i {
            margin-right: 12px;
            font-size: 20px;
            color: #f39c12;
            text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }

        .date-group-header .day-name {
            font-size: 14px;
            opacity: 1;
            margin-left: 15px;
            font-weight: 700;
            color: #f8f9fa;
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 15px;
            display: inline-block;
        }

        /* Enhanced Regular Rows */
        .report-table tbody tr {
            border-bottom: 2px solid #e8eaf6;
            transition: all 0.3s ease;
            background: white;
        }

        .report-table tbody tr:nth-child(4n+2):not(.date-group-header):not(.daily-total-row) {
            background: #fafbfc;
        }

        .report-table tbody tr:hover:not(.date-group-header):not(.daily-total-row) {
            background: #e3f2fd;
            transform: scale(1.008);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
            position: relative;
            z-index: 10;
            border-bottom: 2px solid #2196f3;
        }

        .report-table tbody td {
            padding: 16px 15px;
            font-size: 14px;
            color: #2c3e50;
            font-weight: 500;
            border-right: 1px solid #f0f0f0;
        }

        .report-table tbody td:last-child {
            border-right: none;
        }

        .report-table tbody td:nth-child(n+4) {
            text-align: right;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        /* Column-specific styling for better visual separation */
        .report-table tbody td:nth-child(4) {
            background: rgba(236,240,241,0.3);
        }

        .report-table tbody td:nth-child(5) {
            background: rgba(255,235,238,0.4);
        }

        .report-table tbody td:nth-child(6) {
            background: rgba(232,245,233,0.4);
            font-weight: 900;
            font-size: 15px;
        }

        .report-table tbody td:nth-child(7) {
            background: rgba(224,247,250,0.4);
        }

        .report-table tbody td:nth-child(8) {
            background: rgba(255,235,238,0.4);
        }

        .report-table tbody tr:hover td:nth-child(4),
        .report-table tbody tr:hover td:nth-child(5),
        .report-table tbody tr:hover td:nth-child(6),
        .report-table tbody tr:hover td:nth-child(7),
        .report-table tbody tr:hover td:nth-child(8) {
            background: rgba(227,242,253,0.6);
        }

        .report-table tbody td:nth-child(2) {
            font-weight: 800;
            color: #1976d2;
            font-size: 15px;
            background: linear-gradient(135deg, transparent 0%, rgba(25, 118, 210, 0.08) 100%);
            border-left: 3px solid #2196f3;
            letter-spacing: 0.5px;
        }

        .report-table tbody td:first-child {
            color: #546e7a;
            font-weight: 700;
            font-size: 13px;
        }

        .report-table tbody td:nth-child(3) {
            color: #37474f;
            font-weight: 600;
        }

        /* Amount Styling with Icons */
        .amount-positive {
            color: #2e7d32;
            font-weight: 800;
        }

        .amount-negative {
            color: #d32f2f;
            font-weight: 800;
        }

        .amount-zero {
            color: #9e9e9e;
            font-weight: 600;
        }

        /* Enhanced Daily Total Row */
        .daily-total-row {
            background: #263238 !important;
            background: linear-gradient(180deg, #37474f 0%, #263238 100%) !important;
            font-weight: 800;
            border-top: 3px solid #00acc1 !important;
            border-bottom: 3px solid #00acc1 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .daily-total-row td {
            padding: 18px 15px;
            font-size: 15px;
            color: white !important;
            border-right: 1px solid rgba(255,255,255,0.1);
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .daily-total-row td:last-child {
            border-right: none;
        }

        .daily-total-row td:first-child {
            color: white !important;
            font-size: 14px;
        }

        .daily-total-row td:nth-child(4) {
            background: rgba(255,255,255,0.1);
        }

        .daily-total-row td:nth-child(5) {
            background: rgba(231,76,60,0.3);
        }

        .daily-total-row td:nth-child(6) {
            background: rgba(46,125,50,0.3);
            font-size: 17px;
        }

        .daily-total-row td:nth-child(7) {
            background: rgba(0,172,193,0.3);
        }

        .daily-total-row td:nth-child(8) {
            background: rgba(211,47,47,0.3);
        }

        .daily-total-row i {
            margin-right: 10px;
            color: #00acc1;
            font-size: 18px;
        }

        /* Add invoice count badge style */
        .daily-total-row .invoice-count {
            background: rgba(0,172,193,0.3);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
            color: #e0f7fa;
        }

        /* Enhanced Grand Total Section */
        .grand-total-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 35px 40px;
            border-radius: 12px;
            margin-top: 35px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }

        .grand-total-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(52,152,219,0.1) 0%, transparent 70%);
        }

        .grand-total-title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }

        .grand-total-title i {
            font-size: 32px;
            margin-right: 12px;
            color: #3498db;
        }

        .grand-total-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .total-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }

        .total-card:hover {
            background: rgba(255,255,255,0.15);
            border-color: #3498db;
            transform: translateY(-3px);
        }

        .total-card i {
            font-size: 28px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .total-card .total-label {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .total-card .total-amount {
            font-size: 24px;
            font-weight: 800;
            color: white;
            font-family: 'Courier New', monospace;
        }

        .total-card .total-currency {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            margin-left: 5px;
        }

        /* Grand Total Summary */
        .grand-total-summary {
            background: #3498db;
            padding: 25px 30px;
            border-radius: 10px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .grand-total-summary .summary-label {
            font-size: 14px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .grand-total-summary .summary-amount {
            font-size: 36px;
            font-weight: 800;
            color: white;
            font-family: 'Courier New', monospace;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 25px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #666;
            font-size: 24px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .empty-state p {
            color: #999;
            font-size: 15px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .print-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.5);
        }

        .print-button i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 3px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }

        .report-footer strong {
            color: #3498db;
            font-weight: 700;
        }

        .report-footer .footer-links {
            margin-top: 10px;
        }

        .report-footer .footer-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
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
                transform: none;
            }

            .stat-box:hover {
                transform: none;
            }

            .date-group-header {
                page-break-after: avoid;
            }

            .daily-total-row {
                page-break-after: auto;
            }

            .grand-total-section {
                page-break-before: auto;
            }

            @page {
                margin: 1.5cm;
                size: landscape;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-box {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        .stat-box:nth-child(1) { animation-delay: 0.1s; }
        .stat-box:nth-child(2) { animation-delay: 0.2s; }
        .stat-box:nth-child(3) { animation-delay: 0.3s; }
        .stat-box:nth-child(4) { animation-delay: 0.4s; }
        .stat-box:nth-child(5) { animation-delay: 0.5s; }
        .stat-box:nth-child(6) { animation-delay: 0.6s; }

        /* Responsive */
        @media (max-width: 768px) {
            .report-header {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .report-logo, .report-meta {
                text-align: center;
            }

            .info-header-content {
                flex-direction: column;
            }

            .summary-stats {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .grand-total-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
<div class="report-container">
    <!-- Enhanced Header -->
    <div class="report-header">
        <div class="report-logo">
            <img src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
        </div>
        <div class="report-center">
            <h1>Sales Transactions</h1>
            <div class="subtitle">Detailed Financial Report</div>
        </div>
        <div class="report-meta">
            <div class="meta-item">
                <i class="bi bi-calendar-event"></i>
                <strong>{{ date('d M Y') }}</strong>
            </div>
            <div class="meta-item">
                <i class="bi bi-clock"></i>
                <strong>{{ date('H:i') }}</strong>
            </div>
            <div class="meta-item">
                <i class="bi bi-file-earmark-text"></i>
                <strong>#{{ date('Ymd-His') }}</strong>
            </div>
        </div>
    </div>

    <!-- Enhanced Info Header -->
    <div class="info-header">
        <div class="info-header-content">
            <div class="info-item">
                <i class="bi bi-calendar-range"></i>
                <div>
                    <div class="info-label">Report Period</div>
                    <div class="info-value">{{ $date_from }} - {{ $date_to }}</div>
                </div>
            </div>
            @if(isset($selectedBranch))
                <div class="info-item">
                    <i class="bi bi-building"></i>
                    <div>
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $selectedBranch->name }}</div>
                    </div>
                </div>
            @else
                <div class="info-item">
                    <i class="bi bi-buildings"></i>
                    <div>
                        <div class="info-label">Scope</div>
                        <div class="info-value">All Branches</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($invoices->isNotEmpty())
        @php
            $grand_total_before_discount = 0;
            $grand_total_discount = 0;
            $grand_total_net = 0;
            $grand_total_paid = 0;
            $grand_total_balance = 0;
            $total_invoices = 0;
            $total_days = $invoices->count();

            // Calculate totals for statistics
            foreach($invoices as $dailyInvoices) {
                foreach($dailyInvoices as $invoice) {
                    $totalBefore = $invoice->total_before_discount ?? $invoice->total ?? 0;
                    $netTotal = $invoice->total ?? 0;
                    $grand_total_before_discount += $totalBefore;
                    $grand_total_net += $netTotal;
                    $grand_total_paid += $invoice->paied ?? 0;
                    $grand_total_balance += $invoice->remaining ?? 0;
                    $total_invoices++;
                }
            }
            $grand_total_discount = $grand_total_before_discount - $grand_total_net;
            $avg_per_day = $total_days > 0 ? $grand_total_net / $total_days : 0;
            $avg_per_invoice = $total_invoices > 0 ? $grand_total_net / $total_invoices : 0;
        @endphp

            <!-- Enhanced Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-calendar-week"></i></div>
                <div class="stat-number">{{ $total_days }}</div>
                <div class="stat-label">Days</div>
                <div class="stat-change neutral">{{ \Carbon\Carbon::parse($date_from)->diffInDays(\Carbon\Carbon::parse($date_to)) + 1 }} Day Period</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div class="stat-number">{{ number_format($total_invoices) }}</div>
                <div class="stat-label">Total Invoices</div>
                <div class="stat-change positive">{{ number_format($total_invoices / max($total_days, 1), 1) }} per day</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="stat-number">{{ number_format($grand_total_net, 0) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive">QAR</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-percent"></i></div>
                <div class="stat-number">{{ $grand_total_before_discount > 0 ? number_format(($grand_total_discount / $grand_total_before_discount) * 100, 1) : 0 }}%</div>
                <div class="stat-label">Discount Rate</div>
                <div class="stat-change neutral">{{ number_format($grand_total_discount, 0) }} QAR</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
                <div class="stat-number">{{ number_format($avg_per_day, 0) }}</div>
                <div class="stat-label">Avg Per Day</div>
                <div class="stat-change neutral">QAR</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="bi bi-calculator"></i></div>
                <div class="stat-number">{{ number_format($avg_per_invoice, 0) }}</div>
                <div class="stat-label">Avg Per Invoice</div>
                <div class="stat-change neutral">QAR</div>
            </div>
        </div>

        <!-- Main Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 100px;">DATE</th>
                <th style="width: 120px;">INVOICE</th>
                <th style="width: 200px;">CUSTOMER</th>
                <th style="width: 130px;">BEFORE DISC.</th>
                <th style="width: 110px;">DISCOUNT</th>
                <th style="width: 120px;">NET TOTAL</th>
                <th style="width: 110px;">PAID</th>
                <th style="width: 110px;">BALANCE</th>
            </tr>
            </thead>

            <tbody>
            @foreach($invoices as $date => $dailyInvoices)
                @php
                    $daily_total_before = 0;
                    $daily_total_discount = 0;
                    $daily_total_net = 0;
                    $daily_total_paid = 0;
                    $daily_total_balance = 0;
                    $dayName = \Carbon\Carbon::parse($date)->format('l');
                @endphp

                    <!-- Enhanced Date Group Header -->
                <tr>
                    <td colspan="8" class="date-group-header">
                        <i class="bi bi-calendar-day"></i>
                        {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                        <span class="day-name">({{ $dayName }})</span>
                    </td>
                </tr>

                @foreach($dailyInvoices as $invoice)
                    @php
                        $totalBefore = $invoice->total_before_discount ?? $invoice->total ?? 0;
                        $netTotal = $invoice->total ?? 0;
                        $discount = $totalBefore - $netTotal;
                        $paid = $invoice->paied ?? 0;
                        $balance = $invoice->remaining ?? 0;

                        $daily_total_before += $totalBefore;
                        $daily_total_discount += $discount;
                        $daily_total_net += $netTotal;
                        $daily_total_paid += $paid;
                        $daily_total_balance += $balance;
                    @endphp

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $invoice->invoice_code }}</td>
                        <td>{{ $invoice->customer->english_name ?? '-' }}</td>
                        <td>{{ number_format($totalBefore, 2) }}</td>
                        <td class="{{ $discount > 0 ? 'amount-negative' : 'amount-zero' }}">
                            {{ number_format($discount, 2) }}
                        </td>
                        <td class="amount-positive">{{ number_format($netTotal, 2) }}</td>
                        <td class="{{ $paid > 0 ? 'amount-positive' : 'amount-zero' }}">
                            {{ number_format($paid, 2) }}
                        </td>
                        <td class="{{ $balance > 0 ? 'amount-negative' : 'amount-zero' }}">
                            {{ number_format($balance, 2) }}
                        </td>
                    </tr>
                @endforeach

                <!-- Enhanced Daily Total -->
                <tr class="daily-total-row">
                    <td colspan="3" style="text-align: right; padding-right: 15px;">
                        <i class="bi bi-calculator-fill"></i>
                        <strong>Daily Total</strong>
                        <span class="invoice-count">{{ $dailyInvoices->count() }} invoices</span>
                    </td>
                    <td>{{ number_format($daily_total_before, 2) }}</td>
                    <td>{{ number_format($daily_total_discount, 2) }}</td>
                    <td>{{ number_format($daily_total_net, 2) }}</td>
                    <td>{{ number_format($daily_total_paid, 2) }}</td>
                    <td>{{ number_format($daily_total_balance, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Enhanced Grand Total Section -->
        <div class="grand-total-section">
            <div class="grand-total-title">
                <i class="bi bi-receipt-cutoff"></i> Grand Total Summary
            </div>

            <div class="grand-total-grid">
                <div class="total-card">
                    <i class="bi bi-cash-stack"></i>
                    <div class="total-label">Before Discount</div>
                    <div class="total-amount">
                        {{ number_format($grand_total_before_discount, 2) }}
                        <span class="total-currency">QAR</span>
                    </div>
                </div>

                <div class="total-card">
                    <i class="bi bi-percent"></i>
                    <div class="total-label">Total Discount</div>
                    <div class="total-amount">
                        {{ number_format($grand_total_discount, 2) }}
                        <span class="total-currency">QAR</span>
                    </div>
                </div>

                <div class="total-card">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="total-label">Total Paid</div>
                    <div class="total-amount">
                        {{ number_format($grand_total_paid, 2) }}
                        <span class="total-currency">QAR</span>
                    </div>
                </div>

                <div class="total-card">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div class="total-label">Total Balance</div>
                    <div class="total-amount">
                        {{ number_format($grand_total_balance, 2) }}
                        <span class="total-currency">QAR</span>
                    </div>
                </div>
            </div>

            <div class="grand-total-summary">
                <div class="summary-label">Net Total Revenue</div>
                <div class="summary-amount">{{ number_format($grand_total_net, 2) }} QAR</div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Sales Data Found</h3>
            <p>There are no sales transactions for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Sales Transactions Report<br>
            This is a system-generated report showing all sales grouped by day with comprehensive financial analysis.<br>
            Report ID: #{{ date('Ymd-His') }} | Generated on {{ date('l, F d, Y \a\t H:i:s') }}
        </p>
        <div class="footer-links">
            <span style="color: #95a5a6;">Confidential Business Document</span>
        </div>
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'sales-transactions-report'])
</body>
</html>
