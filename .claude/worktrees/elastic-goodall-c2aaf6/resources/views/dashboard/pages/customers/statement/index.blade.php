@extends('dashboard.layouts.master')

@section('content')

    <style>
        .statement-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }

        .invoice-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .invoice-table thead {
            background: #f8f9fa;
        }

        .invoice-table th {
            padding: 15px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        .invoice-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-ready {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-canceled {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            margin-bottom: 20px;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
    </style>

    <!-- Header Section -->
    <div class="statement-header">
        <div class="row">
            <div class="col-md-8">
                <h2 style="margin: 0 0 10px 0;">
                    <i class="fa fa-user-circle"></i>
                    {{ $customer->english_name }}
                </h2>
                <p style="margin: 0; opacity: 0.9;">
                    <strong>Customer ID:</strong> {{ $customer->customer_id }} |
                    <strong>Mobile:</strong> {{ $customer->mobile ?? 'N/A' }} |
                    <strong>Email:</strong> {{ $customer->email ?? 'N/A' }}
                </p>
            </div>
            <div class="col-md-4 text-right">
                <h3 style="margin: 10px 0;">
                    Statement Date: {{ now()->format('d M Y') }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('dashboard.customers.statement.print', $customer->customer_id) }}"
           target="_blank"
           class="btn btn-primary">
            <i class="fa fa-print"></i> Print Statement
        </a>

        <a href="{{ route('dashboard.customers.statement.pdf', $customer->customer_id) }}"
           class="btn btn-danger">
            <i class="fa fa-file"></i> Download PDF
        </a>

       {{-- <button type="button"
                class="btn btn-success"
                data-toggle="modal"
                data-target="#emailModal">
            <i class="fa fa-envelope"></i> Send via Email
        </button>--}}
    </div>

    <!-- Statistics Cards -->
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-label">Total Invoices</div>
                <div class="stat-value" style="color: #667eea;">{{ $stats['total_invoices'] }}</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-label">Total Purchases</div>
                <div class="stat-value" style="color: #27ae60;">{{ number_format($stats['total_purchases'], 2) }} QAR</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-label">Total Paid</div>
                <div class="stat-value" style="color: #3498db;">{{ number_format($stats['total_paid'], 2) }} QAR</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-label">Outstanding Balance</div>
                <div class="stat-value" style="color: #e74c3c;">{{ number_format($stats['total_remaining'], 2) }} QAR</div>
            </div>
        </div>
    </div>

    <!-- Monthly Purchases Chart -->
    <div class="chart-container">
        <h4><i class="fa fa-bar-chart"></i> Monthly Purchases (Last 6 Months)</h4>
        <canvas id="purchasesChart" height="80"></canvas>
    </div>

    <!-- Recent Payments -->
    @if($recentPayments->isNotEmpty())
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money"></i> Recent Payments</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr style="background: #f8f9fa;">
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentPayments as $payment)
                        <tr>
                            <td>{{ $payment->transaction_date->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('dashboard.edit-invoice', $payment->invoice->invoice_code) }}" target="_blank">
                                    #{{ $payment->invoice->invoice_code }}
                                </a>
                            </td>
                            <td>{{ strtoupper($payment->payment_type) }}</td>
                            <td style="color: {{ $payment->amount_in_sar >= 0 ? '#27ae60' : '#e74c3c' }};">
                                {{ number_format($payment->amount_in_sar, 2) }} QAR
                            </td>
                            <td>
                                @if($payment->transaction_type == 'refund')
                                    <span class="label label-danger">REFUND</span>
                                @else
                                    <span class="label label-success">PAYMENT</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- All Invoices -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-file-text"></i> All Invoices</h3>
        </div>
        <div class="box-body">
            <table class="invoice-table table table-bordered">
                <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <strong>#{{ $invoice->invoice_code }}</strong>
                        </td>
                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                        <td>{{ $invoice->invoiceItems->count() }} items</td>
                        <td><strong>{{ number_format($invoice->total, 2) }} QAR</strong></td>
                        <td style="color: #27ae60;">{{ number_format($invoice->paied, 2) }} QAR</td>
                        <td style="color: #e74c3c;">{{ number_format($invoice->remaining, 2) }} QAR</td>
                        <td>
                        <span class="status-badge status-{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.edit-invoice', $invoice->invoice_code) }}"
                               class="btn btn-sm btn-info"
                               target="_blank">
                                <i class="fa fa-eye"></i> View
                            </a>
                        </td>
                    </tr>

                    <!-- تفاصيل الفاتورة (قابلة للتوسيع) -->
                    <tr class="invoice-details" id="details-{{ $invoice->id }}" style="display: none;">
                        <td colspan="8" style="background: #f8f9fa;">
                            <div style="padding: 15px;">
                                <h5><strong>Invoice Items:</strong></h5>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr style="background: #e9ecef;">
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->invoiceItems as $item)
                                        <tr>
                                            <td>{{ $item->getItemDescription->description ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 2) }} QAR</td>
                                            <td>{{ number_format($item->total, 2) }} QAR</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <h5><strong>Payments:</strong></h5>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr style="background: #e9ecef;">
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ strtoupper($payment->type) }}</td>
                                            <td>{{ number_format($payment->payed_amount, 2) }} QAR</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <i class="fa fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                            <p style="margin-top: 10px; color: #999;">No invoices found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>

                @if($invoices->isNotEmpty())
                    <tfoot style="background: #f8f9fa; font-weight: bold;">
                    <tr>
                        <td colspan="3" class="text-right"><strong>TOTALS:</strong></td>
                        <td><strong>{{ number_format($stats['total_purchases'], 2) }} QAR</strong></td>
                        <td style="color: #27ae60;"><strong>{{ number_format($stats['total_paid'], 2) }} QAR</strong></td>
                        <td style="color: #e74c3c;"><strong>{{ number_format($stats['total_remaining'], 2) }} QAR</strong></td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Email Modal -->
   {{-- <div class="modal fade" id="emailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-envelope"></i> Send Statement via Email</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $customer->email }}"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-send"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>--}}

@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        // Monthly Purchases Chart
        const ctx = document.getElementById('purchasesChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyPurchases->pluck('month')) !!},
                datasets: [{
                    label: 'Purchases (QAR)',
                    data: {!! json_encode($monthlyPurchases->pluck('total')) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Toggle invoice details
        $(document).on('click', '.invoice-table tbody tr:not(.invoice-details)', function() {
            const invoiceId = $(this).find('strong').text().replace('#', '');
            const detailsRow = $(this).next('.invoice-details');
            detailsRow.toggle();
        });
    </script>
@endsection
