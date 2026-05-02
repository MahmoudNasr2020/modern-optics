<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Items To Be Delivered Report</title>

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
            border-bottom: 3px solid #f39c12;
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
            color: #f39c12;
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
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 2px solid #ffd54f;
        }

        .info-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-header strong {
            color: #f39c12;
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
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #f39c12;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 800;
            color: #f39c12;
            margin-bottom: 5px;
        }

        .stat-box .label {
            font-size: 12px;
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
        }

        .report-table thead {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
            padding: 15px;
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .report-table tbody td:first-child {
            font-weight: 700;
            color: #f39c12;
            font-size: 15px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-badge.under-process {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .status-badge.ready {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
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
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(243, 156, 18, 0.5);
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
            color: #f39c12;
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
            <h1>Items To Be Delivered</h1>
            <div class="subtitle">Pending Delivery Report</div>
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
                    {{ $start_date }} <strong>to</strong> {{ $end_date }}
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
        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">{{ $invoices->count() }}</div>
                <div class="label">Total Items</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $invoices->unique('invoice_id')->count() }}</div>
                <div class="label">Unique Invoices</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $invoices->where('status', 'under_process')->count() }}</div>
                <div class="label">Under Process</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $invoices->where('status', 'ready')->count() }}</div>
                <div class="label">Ready</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $invoices->sum('quantity') }}</div>
                <div class="label">Total Quantity</div>
            </div>
        </div>

        <!-- Table -->
        <table class="report-table">
            <thead>
            <tr>
                <th style="width: 110px;">INVOICE</th>
                <th style="width: 100px;">DATE</th>
                <th>Type</th>
                <th>CATEGORY</th>
                <th style="width: 100px;">PICK-UP</th>
                <th style="width: 90px;">ITEM #</th>
                <th>DESCRIPTION</th>
                <th style="width: 60px; text-align: center;">QTY</th>
                <th style="width: 110px; text-align: center;">STATUS</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $item)
                <tr>
                    <td>{{ $item->invoice->invoice_code ?? '-' }}</td>

                    <td>{{ optional($item->invoice->created_at)->format('Y-m-d') }}</td>

                    <td>{{ $item->type ?? '-' }}</td>

                    <td>{{ $item->product->category->category_name ?? '-' }}</td>

                    <td>{{ $item->invoice->pickup_date ?? '-' }}</td>

                    <td>{{ $item->product_id }}</td>

                    <td>
                        @if($item->type == 'product')
                            {{ $item->product->description ?? '-' }}
                        @else
                            {{ $item->lens->description ?? '-' }}
                        @endif
                    </td>

                    <td style="text-align: center;">
                        <strong>{{ $item->quantity }}</strong>
                    </td>

                    <td style="text-align: center;">
                        @if($item->status === 'under_process')
                            <span class="status-badge under-process">
                <i class="bi bi-hourglass-split"></i> Process
            </span>
                        @else
                            <span class="status-badge ready">
                <i class="bi bi-check-circle"></i> Ready
            </span>
                        @endif
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No Items Found</h3>
            <p>There are no items awaiting delivery for the selected date range.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="report-footer">
        <p>
            <strong>Optics Modern</strong> - Items To Be Delivered Report<br>
            This is a system-generated report showing all items with status "Under Process" or "Ready".<br>
            Report generated on {{ date('Y-m-d H:i:s') }}
        </p>
    </div>
</div>

@include('dashboard.partials._report_export_toolbar', ['filename' => 'items-delivered-report'])
</body>
</html>
