@extends('dashboard.layouts.master')

@section('title', 'Customer Profile')

@section('content')

    <style>
        /* Modern Customer Profile */
        .customer-profile-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Hero Header */
        .customer-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 15px 50px rgba(102,126,234,0.4);
            position: relative;
            overflow: hidden;
        }

        .customer-hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .customer-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #667eea;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .customer-name {
            font-size: 36px;
            font-weight: 900;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
        }

        .customer-id-hero {
            font-size: 18px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.blue::before { background: linear-gradient(90deg, #3498db, #2980b9); }
        .stat-card.green::before { background: linear-gradient(90deg, #27ae60, #229954); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f39c12, #e67e22); }
        .stat-card.red::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
        .stat-icon.green { background: linear-gradient(135deg, #27ae60, #229954); color: white; }
        .stat-icon.orange { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
        .stat-icon.red { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }

        .stat-value {
            font-size: 32px;
            font-weight: 900;
            color: #2c3e50;
            margin: 10px 0 5px 0;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .info-header {
            padding: 20px 25px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-header.blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; }
        .info-header.green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }
        .info-header.orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }
        .info-header.purple { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border: none; }

        .info-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }

        .info-body {
            padding: 25px;
        }

        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 0 0 200px;
            font-weight: 600;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            flex: 1;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Eye Tests Table */
        .eye-tests-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .eye-tests-table thead {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .eye-tests-table thead th,
        .eye-tests-table thead td {
            color: white;
            padding: 15px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .eye-tests-table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            text-align: center;
        }

        .eye-tests-table tbody tr:hover {
            background: #f8f9ff;
        }

        /* Timeline */
        .invoice-timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding: 20px 0 20px 50px;
            border-left: 3px solid #e0e6ed;
        }

        .timeline-item:last-child {
            border-left-color: transparent;
        }

        .timeline-marker {
            position: absolute;
            left: -12px;
            top: 25px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: 3px solid white;
            box-shadow: 0 3px 10px rgba(52,152,219,0.3);
        }

        .timeline-date {
            font-weight: 700;
            color: #3498db;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .timeline-invoices {
            background: #f8f9ff;
            border-radius: 10px;
            padding: 15px;
        }

        .timeline-invoice {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .timeline-invoice:last-child {
            margin-bottom: 0;
        }

        .timeline-invoice:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .invoice-code {
            font-weight: 700;
            color: #3498db;
            font-size: 16px;
        }

        .invoice-total {
            font-weight: 900;
            color: #27ae60;
            font-size: 18px;
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

        .btn-warning-modern {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-light {
            background: white;
            color: #667eea;
            border: 2px solid white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        /* Action Buttons */
        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
    </style>

    <div class="customer-profile-page">

        <!-- Hero Header -->
        <div class="customer-hero">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="customer-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h1 class="customer-name">
                        {{ $customer->english_name ?? $customer->local_name }}
                    </h1>
                    <p class="customer-id-hero">
                        <i class="bi bi-upc-scan"></i> Customer ID: {{ $customer->customer_id }}
                    </p>
                </div>
                <div class="col-md-4 text-right" style="position: relative; z-index: 1;">
                    <a href="{{ route('dashboard.get-all-customers') }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('dashboard.get-update-customer', $customer->id) }}" class="btn btn-warning-modern btn-modern">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stat-value">{{ $customerInvoices->flatten()->count() }}</div>
                <div class="stat-label">Total Invoices</div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon green">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-value">{{ number_format($customerInvoices->flatten()->sum('total'), 0) }}</div>
                <div class="stat-label">Total Spent (QAR)</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon orange">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stat-value">{{ $lenses->count() }}</div>
                <div class="stat-label">Eye Tests</div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon red">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-value" style="font-size: 18px;">
                    @if($customerInvoices->count() > 0)
                        {{ $customerInvoices->flatten()->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                    @else
                        Never
                    @endif
                </div>
                <div class="stat-label">Last Visit</div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="info-card">
            <div class="info-header blue">
                <div style="display: flex; align-items: center;">
                    <div class="info-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Personal Information</h3>
                </div>
            </div>
            <div class="info-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-person"></i> Title
                            </div>
                            <div class="info-value">{{ $customer->title ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-alphabet"></i> English Name
                            </div>
                            <div class="info-value">{{ $customer->english_name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-translate"></i> Local Name
                            </div>
                            <div class="info-value">{{ $customer->local_name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-gender-ambiguous"></i> Gender
                            </div>
                            <div class="info-value">{{ $customer->gender ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-date"></i> Birth Date
                            </div>
                            <div class="info-value">{{ $customer->birth_date ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-hourglass-split"></i> Age
                            </div>
                            <div class="info-value">{{ $customer->age ?? '-' }} years</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-flag"></i> Nationality
                            </div>
                            <div class="info-value">{{ ucfirst($customer->nationality ?? '-') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-card-text"></i> National ID
                            </div>
                            <div class="info-value">{{ $customer->national_id ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="info-card">
            <div class="info-header green">
                <div style="display: flex; align-items: center;">
                    <div class="info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Contact Information</h3>
                </div>
            </div>
            <div class="info-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-phone-vibrate"></i> Mobile
                            </div>
                            <div class="info-value">+974 {{ $customer->mobile_number ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-envelope"></i> Email
                            </div>
                            <div class="info-value">{{ $customer->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-briefcase"></i> Office
                            </div>
                            <div class="info-value">{{ $customer->office_number ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-bell"></i> Notifications
                            </div>
                            <div class="info-value">{{ $customer->receive_nots ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-geo-alt"></i> Address
                    </div>
                    <div class="info-value">{{ $customer->address ?? '-' }}</div>
                </div>
                @if($customer->notes)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-sticky"></i> Notes
                        </div>
                        <div class="info-value">{{ $customer->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Eye Tests -->
        @if($lenses->count() > 0)
            <div class="info-card">
                <div class="info-header purple">
                    <div style="display: flex; align-items: center;">
                        <div class="info-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Eye Tests History</h3>
                    </div>
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 15px; border-radius: 15px; font-weight: 700;">
                {{ $lenses->count() }} Tests
            </span>
                </div>
                <div class="info-body" style="padding: 0; overflow-x: auto;">
                    <table class="eye-tests-table">
                        <thead>
                        <tr>
                            <th colspan="4">Details</th>
                            <th colspan="5">Right Eye</th>
                            <th colspan="4">Left Eye</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <td>Doctor</td>
                            <td>Date</td>
                            <td>Source</td>
                            <td>Sign</td>
                            <td>Sph.</td>
                            <td>Cyl.</td>
                            <td>Axis</td>
                            <td>Add</td>
                            <td>Sign</td>
                            <td>Sph.</td>
                            <td>Cyl.</td>
                            <td>Axis</td>
                            <td>Add</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lenses as $lens)
                                <?php $doctor = App\Doctor::where('code', $lens->doctor_id)->first() ? App\Doctor::where('code', $lens->doctor_id)->first()->name : '-'; ?>
                            <tr>
                                <td>{{ $doctor }}</td>
                                <td>{{ $lens->visit_date }}</td>
                                <td>E</td>
                                <td>{{ $lens->sph_right_sign.' '.$lens->cyl_right_sign }}</td>
                                <td>{{ $lens->sph_right_value ?? '-' }}</td>
                                <td>{{ $lens->cyl_right_value ?? '-' }}</td>
                                <td>{{ $lens->axis_right ?? '-' }}</td>
                                <td>{{ $lens->addition_right ?? '-' }}</td>
                                <td>{{ $lens->sph_left_sign.' '.$lens->cyl_left_sign }}</td>
                                <td>{{ $lens->sph_left_value ?? '-' }}</td>
                                <td>{{ $lens->cyl_left_value ?? '-' }}</td>
                                <td>{{ $lens->axis_left ?? '-' }}</td>
                                <td>{{ $lens->addition_left ?? '-' }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        @if($lens->attachment != '')
                                            <button type="button" class="btn btn-primary-modern btn-sm btn-action"
                                                    data-toggle="modal"
                                                    data-id="{{ $lens->id }}"
                                                    data-file="{{ \App\Facades\File::getUrl($lens->attachment) }}"
                                                    data-target="#eyeTestModal"
                                                    title="View Attachment">
                                                <i class="bi bi-paperclip"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('dashboard.print-eye-test', $lens->id) }}"
                                           target="_blank"
                                           class="btn btn-info-modern btn-sm btn-action"
                                           title="Print Test">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <button class="btn btn-danger-modern btn-sm btn-action delete-btn"
                                                data-id="{{ $lens->id }}"
                                                title="Delete Test">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Invoice History Timeline -->
        @if($customerInvoices->count() > 0)
            <div class="info-card">
                <div class="info-header orange">
                    <div style="display: flex; align-items: center;">
                        <div class="info-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Purchase History</h3>
                    </div>
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 15px; border-radius: 15px; font-weight: 700;">
                {{ $customerInvoices->flatten()->count() }} Invoices
            </span>
                </div>
                <div class="info-body">
                    <div class="invoice-timeline">
                        @foreach($customerInvoices as $date => $invoices)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-date">
                                    <i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                </div>
                                <div class="timeline-invoices">
                                    @foreach($invoices as $invoice)
                                        <div class="timeline-invoice">
                                            <div>
                                                <div class="invoice-code">
                                                    <i class="bi bi-receipt"></i> {{ $invoice->invoice_code }}
                                                </div>
                                                <small style="color: #7f8c8d;">{{ $invoice->created_at->format('h:i A') }}</small>
                                            </div>
                                            <div class="invoice-total">
                                                {{ number_format($invoice->total, 2) }} QAR
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="info-card">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No Purchase History</h4>
                    <p>This customer hasn't made any purchases yet.</p>
                </div>
            </div>
        @endif

    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal -->
    <div class="modal fade" id="eyeTestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                    <h3 style="margin: 0;">Eye Test Attachment</h3>
                </div>
                <div class="modal-body text-center">
                    <embed id="eyeTestFrame" src="" style="width:100%; height:700px;" frameborder="0">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#eyeTestModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let file = button.data('file');
            $('#eyeTestFrame').attr('src', file);
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: 'Delete Eye Test?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash"></i> Yes, Delete',
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
                    let form = document.getElementById('deleteForm');
                    form.action = '/dashboard/delete-eye-test/' + id;
                    form.submit();
                }
            });
        });
    </script>
@endsection
