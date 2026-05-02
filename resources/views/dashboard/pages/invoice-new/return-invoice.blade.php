@extends('dashboard.layouts.master')

@section('title', 'Return Invoices')

@section('content')

    <style>
        /* Modern Return Invoices Page */
        .return-invoices-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Header */
        .return-header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(231, 76, 60, 0.3);
            position: relative;
            overflow: hidden;
        }

        .return-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .return-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .filter-header {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }

        .filter-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }

        .filter-body {
            padding: 20px;
        }

        /* Form Controls */
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 8px 12px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231,76,60,0.1);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .table-card thead {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        }

        .table-card thead th {
            color: white;
            padding: 15px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border: none;
            text-align: center;
        }

        .table-card tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            text-align: center;
        }

        .table-card tbody tr:hover {
            background: #f8f9ff;
        }

        .table-card tbody tr.selected-row {
            background: #e8f5e9;
            border-left: 4px solid #27ae60;
        }

        .table-card tbody tr.invoice-canceled {
            background: #ffebee;
            color: #c62828;
        }

        /* Details Card */
        .details-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
            display: none;
        }

        .details-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .details-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }

        .details-body {
            padding: 20px;
        }

        /* Invoice Details Table */
        .invoice-details-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .invoice-details-table thead th {
            color: white;
            padding: 12px;
            font-weight: 700;
            text-align: center;
            font-size: 11px;
        }

        .invoice-details-table tbody td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #f0f2f5;
        }

        /* Payments Table */
        .payments-table thead {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .payments-table thead th {
            color: white;
            padding: 12px;
            font-weight: 700;
            text-align: center;
            font-size: 11px;
        }

        .payments-table tbody td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #f0f2f5;
        }

        /* Financial Info */
        .financial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .financial-item {
            padding: 15px;
            background: #f8f9ff;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .financial-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .financial-value {
            font-size: 20px;
            font-weight: 900;
            color: #2c3e50;
        }

        /* Buttons */
        .btn-modern {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-orange-modern {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        /* Eye Icon */
        .view-icon {
            color: #3498db;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
        }

        .view-icon:hover {
            color: #2980b9;
            transform: scale(1.2);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 100px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Alert Success */
        .alert-modern {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .alert-success-modern {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
    </style>

    <div class="return-invoices-page">

        <!-- Header -->
        <div class="return-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-arrow-return-left"></i> Return Invoices</h1>
                    <p style="margin: 0; opacity: 0.9; position: relative; z-index: 1;">
                        Search and return delivered or pending invoices
                    </p>
                </div>
                <div class="col-md-4 text-right" style="position: relative; z-index: 1;">
                    <a href="{{ route('dashboard.index') }}" class="btn btn-modern" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3);">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert-modern alert-success-modern">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="filter-card">
            <div class="filter-header">
                <div style="display: flex; align-items: center;">
                    <div class="filter-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Search Invoices</h3>
                </div>
            </div>
            <div class="filter-body">
                <form action="{{ route('dashboard.invoice.returned') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-upc-scan"></i> Invoice Code</label>
                                <input type="text" name="invoice_code" class="form-control"
                                       value="{{ request()->invoice_code }}"
                                       placeholder="INV-001">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-person-badge"></i> Customer Code</label>
                                <input type="text" name="customer_code" class="form-control"
                                       value="{{ request()->customer_code }}"
                                       placeholder="C-001">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-box"></i> Article ID</label>
                                <input type="text" name="product_id" class="form-control"
                                       value="{{ request()->product_id }}"
                                       placeholder="P-001">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-orange-modern btn-modern btn-block">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="table-card">
            @if($invoices->count() > 0)
                <table class="table pending-table">
                    <thead>
                    <tr>
                        <th>Invoice Code</th>
                        <th>Invoice Date</th>
                        <th>Customer</th>
                        <th style="display: none;">Customer Code</th>
                        <th>Sales Person</th>
                        <th>Balance (QAR)</th>
                        <th>Pick-up Date</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr class="{{ strtolower($invoice->status) == 'canceled' ? 'invoice-canceled' : '' }}">
                            <td><strong>{{ $invoice->invoice_code }}</strong></td>
                            <td>
                                <i class="bi bi-calendar-event"></i>
                                {{ date('Y-m-d', strtotime($invoice->created_at)) }}
                            </td>
                            <td>{{ $invoice->customer->english_name ?? '-' }}</td>
                            <td style="display: none;">{{ $invoice->customer_id }}</td>
                            <td>{{ $invoice->user_name ?? '-' }}</td>
                            <td>
                                <strong style="color: {{ $invoice->remaining > 0 ? '#e74c3c' : '#27ae60' }};">
                                    {{ number_format($invoice->remaining, 2) }}
                                </strong>
                            </td>
                            <td>{{ $invoice->pickup_date ?? '-' }}</td>
                            <td>
                                @if(strtolower($invoice->status) == 'canceled')
                                    <span class="badge" style="background: #c62828; color: white; padding: 6px 12px; border-radius: 20px;">
                                        <i class="bi bi-x-circle"></i> CANCELED
                                    </span>
                                @else
                                    <span class="badge" style="background: #27ae60; color: white; padding: 6px 12px; border-radius: 20px;">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <i class="bi bi-file-earmark-text view-icon"></i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h4>No Invoices Found</h4>
                    <p>Use the search filters above to find invoices to return</p>
                </div>
            @endif
        </div>

        <!-- Invoice Details Card -->
        <div class="details-card" id="detailsBox">
            <div class="details-header">
                <div style="display: flex; align-items: center;">
                    <div class="details-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700;">
                        Invoice Details
                        <span class="badge invoice-badge" style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 15px; margin-left: 10px;"></span>
                    </h3>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="details-body">
                <h4 style="margin-bottom: 15px; color: #2c3e50;">
                    <i class="bi bi-cart"></i> Invoice Items
                </h4>
                <table class="table invoice-details-table">
                    <thead>
                    <tr>
                        <th>NO</th>
                        <th>Item #</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Net</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Stock</th>
                        <th>Available Stock</th>
                        <th>Reserved Stock</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- Payments -->
            <div class="details-body" id="payments">
                <h4 style="margin-bottom: 15px; color: #2c3e50;">
                    <i class="bi bi-credit-card"></i> Payments
                </h4>
                <table class="table payments-table">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Bank</th>
                        <th>Card No</th>
                        <th>Exp. Date</th>
                        <th>Currency</th>
                        <th>Payed Amount</th>
                        <th>Exchange Rate</th>
                        <th>Local Payment</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody class="payments-details"></tbody>
                </table>
            </div>

            <!-- Financial Information -->
            <div class="details-body" id="FinancialBox">
                <h4 style="margin-bottom: 15px; color: #2c3e50;">
                    <i class="bi bi-calculator"></i> Financial Information
                </h4>

                <div class="financial-grid">
                    <div class="financial-item">
                        <div class="financial-label">Total</div>
                        <div class="financial-value" id="total">0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Discount</div>
                        <div class="financial-value" id="discount">0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Net</div>
                        <div class="financial-value" id="net">0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Paid</div>
                        <div class="financial-value" id="paied" style="color: #27ae60;">0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Remaining</div>
                        <div class="financial-value" id="remaining" style="color: #e74c3c;">0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Return Amount</div>
                        <div class="financial-value" id="return_amount" style="color: #e74c3c;">0.00</div>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="bi bi-exclamation-triangle"></i> Reason of Return</label>
                            <select name="return_reason" class="form-control return_reason">
                                <option value="">Select reason...</option>
                                <option value="Defective">Defective</option>
                                <option value="Replacement">Replacement</option>
                                <option value="Wrong Entry">Wrong Entry</option>
                                <option value="Delaying in Delivery">Delaying in Delivery</option>
                                <option value="Customer Not Serious">Customer Not Serious</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="bi bi-sticky"></i> Notes</label>
                            <textarea class="form-control invoice_notes" rows="3" placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 text-right">
                        <button type="button" id="returnInvoice" class="btn btn-danger-modern btn-modern btn-lg">
                            <i class="bi bi-arrow-return-left"></i> Return Invoice
                        </button>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary-modern btn-modern btn-lg">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let InvoiceID;
            let InvoiceDetailsTable = $('.invoice-details-table tbody');
            let paymentsDetailsTable = $('.payments-details');
            let InvoiceBadge = $('.invoice-badge');
            let InvoiceDetailsBox = $('#detailsBox');
            let FinancialBox = $('#FinancialBox');

            // Click on view icon
            $('.pending-table').on('click', '.view-icon', function() {
                let row = $(this).closest('tr');
                InvoiceID = row.find('td:first').text().trim();

                // Highlight selected row
                $('.pending-table tbody tr').removeClass('selected-row');
                row.addClass('selected-row');

                // Fetch invoice details
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: "GET",
                    url: '{{ route("dashboard.invoice.details") }}',
                    data: { InvoiceID },
                    success: function(response) {
                        // Reset tables
                        InvoiceDetailsTable.html('');
                        paymentsDetailsTable.html('');

                        // Show details box
                        InvoiceDetailsBox.show();

                        // Check if canceled
                        if (response.invoice.status.toLowerCase() === 'canceled') {
                            FinancialBox.hide();
                        } else {
                            FinancialBox.show();
                        }

                        // Set financial info
                        $('#total').text(parseFloat(response.invoice.total).toFixed(2));
                        $('#paied').text(parseFloat(response.invoice.paied).toFixed(2));
                        $('#remaining').text(parseFloat(response.invoice.remaining).toFixed(2));
                        $('#net').text(parseFloat(response.invoice.total).toFixed(2));
                        $('#discount').text('0.00');
                        $('#return_amount').text(parseFloat(response.invoice.paied).toFixed(2));

                        InvoiceBadge.text(response.invoice.invoice_code);

                        // Add invoice items
                        response.Invoice_items.forEach((item, index) => {
                            let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.product_id}</td>
                          <td>
                              ${item.name}${item.direction ? ` (${item.direction})` : ''}
                            </td>

                            <td>${item.quantity}</td>
                            <td>${parseFloat(item.price).toFixed(2)}</td>
                            <td>${parseFloat(item.net).toFixed(2)}</td>
                            <td>${parseFloat(item.tax).toFixed(2)}</td>
                            <td>${parseFloat(item.total).toFixed(2)}</td>
                            <td>${item.stock}</td>
                            <td>${item.available_stock}</td>
                            <td>${item.reserved_stock}</td>
                        </tr>
                    `;
                            InvoiceDetailsTable.append(row);
                        });

                        // Add payments
                        response.payments.forEach(payment => {
                            let row = `
                        <tr>
                            <td>${payment.type}</td>
                            <td>${payment.bank || 'N/A'}</td>
                            <td>${payment.card_number || 'N/A'}</td>
                            <td>${payment.expiration_date || 'N/A'}</td>
                            <td>${payment.currency}</td>
                            <td>${parseFloat(payment.payed_amount).toFixed(2)}</td>
                            <td>${payment.exchange_rate}</td>
                            <td>${payment.local_payment || 'N/A'}</td>
                            <td>${payment.created_at}</td>
                        </tr>
                    `;
                            paymentsDetailsTable.append(row);
                        });

                        // Scroll to details
                        $('html, body').animate({
                            scrollTop: InvoiceDetailsBox.offset().top - 20
                        }, 500);
                    }
                });
            });

            // Return invoice
            $('#returnInvoice').on('click', function() {
                let invoice_notes = $('.invoice_notes').val();
                let return_reason = $('.return_reason').val();

                if (!return_reason) {
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please select a reason for return',
                        icon: 'warning',
                        confirmButtonColor: '#e74c3c'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Return Invoice?',
                    html: `
                <p>Are you sure you want to return this invoice?</p>
                <p><strong>Invoice:</strong> ${InvoiceID}</p>
                <p><strong>Reason:</strong> ${return_reason}</p>
                <p style="color: #e74c3c; margin-top: 15px;">
                    <i class="bi bi-exclamation-triangle"></i>
                    This will cancel the invoice and refund all payments!
                </p>
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-arrow-return-left"></i> Yes, Return It',
                    cancelButtonText: '<i class="bi bi-x"></i> Cancel',
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    customClass: {
                        confirmButton: 'btn btn-danger btn-lg',
                        cancelButton: 'btn btn-secondary btn-lg'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            type: "POST",
                            url: '{{ route("dashboard.invoice.return") }}',
                            data: { InvoiceID, invoice_notes, return_reason },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Invoice returned successfully',
                                    icon: 'success',
                                    confirmButtonColor: '#27ae60'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to return invoice',
                                    icon: 'error',
                                    confirmButtonColor: '#e74c3c'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
