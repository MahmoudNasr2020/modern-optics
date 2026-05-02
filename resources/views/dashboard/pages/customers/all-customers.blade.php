@extends('dashboard.layouts.master')

@section('title', 'Customers')

@section('content')

    <style>
        /* Modern Customers Page Styling */
        .customers-page {
            padding: 20px;
        }

        /* Header Box */
        .customers-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }

        .customers-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .customers-header h2 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .customers-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 15px;
            position: relative;
            z-index: 1;
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-box h4 {
            margin: 0 0 20px 0;
            color: #667eea;
            font-weight: 700;
        }

        .search-box .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            height: 45px;
        }

        .search-box .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            height: 45px;
            padding: 0 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .search-box .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .search-box .btn-add {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border: none;
            color: white;
            font-weight: 600;
            height: 45px;
            padding: 14px 25px;
            border-radius: 8px;
            transition: all 0.3s;
            margin-left: 10px;
        }

        .search-box .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .customers-table {
            border-radius: 8px;
            overflow: hidden;
        }

        .customers-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .customers-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .customers-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .customers-table tbody tr {
            transition: all 0.3s;
        }

        .customers-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Customer ID Link */
        .customer-id-link {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .customer-id-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
            font-size: 13px;
            font-weight: 600;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-update {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-invoice {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-eye {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .btn-history {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #999;
            font-size: 18px;
            font-weight: 600;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 25px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .modal-header .close {
            color: white;
            opacity: 1;
            font-size: 30px;
            font-weight: 300;
        }

        .modal-body {
            padding: 25px;
        }

        /* Eye Test Table */
        .eye-table {
            border-radius: 8px;
            overflow: hidden;
        }

        .eye-table thead {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .eye-table thead th {
            color: white;
            font-weight: 700;
            padding: 15px 10px;
            border: 1px solid rgba(255,255,255,0.2);
            text-transform: uppercase;
            font-size: 11px;
        }

        .eye-table tbody td {
            padding: 12px 10px;
            border: 1px solid #e0e6ed;
            font-size: 13px;
        }

        .eye-table .mid_th td {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            font-weight: 600;
        }

        /* History Table */
        .history-table {
            border-radius: 8px;
            overflow: hidden;
        }

        .history-table thead {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .history-table thead td {
            color: white;
            font-weight: 700;
            padding: 15px 12px;
            border: none;
            text-transform: uppercase;
            font-size: 11px;
        }

        .history-table tbody td {
            padding: 15px 12px;
            border-bottom: 1px solid #e0e6ed;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .dropdown-content a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Alert Success */
        .alert-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
        }

        .pagination > li > a,
        .pagination > li > span {
            color: #667eea;
            border-radius: 8px;
            margin: 0 3px;
            border: 2px solid #e0e6ed;
        }

        .pagination > li.active > a,
        .pagination > li.active > span {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .pagination > li > a:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .customers-page > * {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Customers Management
            <small>View and manage all customers</small>
        </h1>
    </section>

    <div class="customers-page">
        <!-- Header -->
        <div class="customers-header">
            <div class="row">
                <div class="col-md-12">
                    <h2>
                        <i class="fa fa-users"></i> Customers Directory
                    </h2>
                    <p>
                        <i class="fa fa-info-circle"></i> Manage customer information, create invoices, and view history
                    </p>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <h4><i class="fa fa-search"></i> Search Customers</h4>
            <form action="{{route('dashboard.get-all-customers')}}" method="GET">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, mobile, or customer ID..."
                               value="{{request()->search}}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-search">
                            <i class="fa fa-search"></i> Search
                        </button>
                        @can('create-customers')
                            <a href="{{route('dashboard.get-add-customer')}}" class="btn btn-add">
                                <i class="fa fa-plus"></i> Add Customer
                            </a>
                        @endcan
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            @if($customers)
                @if(count($customers) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover customers-table">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Customer ID</th>
                                <th width="15%">Name</th>
                                <th width="15%">Local Name</th>
                                <th width="15%">Email</th>
                                <th width="12%">Mobile</th>
                                <th width="28%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $index => $customer)
                                <tr>
                                    <td>
                                        <strong style="color: #667eea; font-size: 16px;">{{ $customers->firstItem() + $index }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{route('dashboard.show-customer',['id' => $customer->id])}}" class="customer-id-link">
                                            {{$customer->customer_id}}
                                        </a>
                                    </td>
                                    <td><strong>{{$customer->english_name}}</strong></td>
                                    <td>{{$customer->local_name}}</td>
                                    <td>{{$customer->email ?: '-'}}</td>
                                    <td>{{$customer->mobile_number}}</td>
                                    <td>
                                        <div class="action-buttons">
                                               @can('edit-customers')
                                                <a href="{{route('dashboard.get-update-customer', $customer->id)}}" class="btn btn-update" title="Update Customer">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                               {{-- <button class="btn btn-update" disabled title="No Permission">
                                                    <i class="fa fa-edit"></i>
                                                </button>--}}
                                            {{--@endif--}}

                                               @can('create-invoices')
                                               <a href="{{route('dashboard.invoice.create', $customer->customer_id)}}" class="btn btn-invoice" title="Create Invoice">
                                                   <i class="fa fa-file-text"></i>
                                               </a>
                                               @endcan

                                                @can('delete-customers')
                                                       <form action="{{route('dashboard.delete-customer', $customer->id)}}" method="GET" style="display: inline;">
                                                           @csrf
                                                           <button type="submit" class="btn btn-delete delete" title="Delete Customer">
                                                               <i class="fa fa-trash-o"></i>
                                                           </button>
                                                       </form>
                                                   @endcan
                                            {{--@else
                                                <button class="btn btn-delete" disabled title="No Permission">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif--}}

                                            @if(count($customer->eyetests) > 0)
                                                <button data-id="{{ $customer->customer_id }}"
                                                        class="btn btn-eye eyetest"
                                                        data-target="#eyeModal"
                                                        data-toggle="modal"
                                                        title="Eye Test">
                                                    <i data-id="{{ $customer->customer_id }}" class="fa fa-eye eyetest"></i>
                                                </button>
                                            @endif

                                            @if(count($customer->customerinvoices) > 0)
                                                <button data-id="{{ $customer->customer_id }}"
                                                        class="btn btn-history history"
                                                        data-target="#histModal"
                                                        data-toggle="modal"
                                                        title="Customer History">
                                                    <i data-id="{{ $customer->customer_id }}" class="bi bi-clock-history"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $customers->appends(request()->query())->links() }}
                @else
                    <div class="empty-state">
                        <i class="fa fa-users"></i>
                        <h4>No customers found</h4>
                        <p style="color: #999;">Try adjusting your search criteria</p>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fa fa-search"></i>
                    <h4>Search for customers</h4>
                    <p style="color: #999;">Use the search box above to find customers</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Eye Test Modal -->
    <div id="eyeModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><i class="fa fa-eye"></i> Customer Eye Test History</h3>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table eye-table">
                            <thead>
                            <tr>
                                <th colspan="4" class="text-center">Details</th>
                                <th colspan="5" class="text-center">Right Eye</th>
                                <th colspan="4" class="text-center">Left Eye</th>
                                <th colspan="1" class="text-center">Print</th>
                            </tr>
                            <tr class="mid_th">
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
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>
                    <a href="{{ route('dashboard.add-eye-test', ['id' => 1]) }}"
                       class="btn btn-add new-eye" style="background: #9b59b6;color: #fff;">
                        <i class="fa fa-plus"></i> Add New Eye Test
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer History Modal -->
    <div id="histModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><i class="fa fa-history"></i> Customer Invoice History</h3>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success delivered" style="display: none">
                        <i class="fa fa-check-circle"></i> Invoice Delivered Successfully!
                    </div>

                    <div class="table-responsive">
                        <table class="table history-table">
                            <thead>
                            <tr class="mid_th">
                                <td>#</td>
                                <td>Invoice ID</td>
                                <td>Total</td>
                                <td>Date & Time</td>
                                <td>Status</td>
                                <td>Actions</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        // Eye Tests Part
        let eyetests = document.querySelectorAll('.action-buttons');
        let postButton;

        eyetests.forEach(eye => {
            eye.addEventListener('click', function(e) {
                let href = document.querySelector('.new-eye');

                if(e.target.classList.contains('eyetest') || e.target.parentElement.classList.contains('eyetest')) {
                    let customer_id = e.target.getAttribute('data-id') || e.target.parentElement.getAttribute('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "GET",
                        url: '{{route("dashboard.customer-eye")}}',
                        data: { customer_id: customer_id },
                        success: function(response) {
                            let table = document.querySelector('.eye-table');
                            table.querySelector('tbody').innerHTML = '';

                            response.responsea.forEach(r => {
                                let row = `
                                <tr>
                                    <td>${r.doctor_id}</td>
                                    <td>${r.visit_date}</td>
                                    <td>E</td>
                                    <td>${r.sph_right_sign + ' ' + r.cyl_right_sign}</td>
                                    <td>${r.sph_right_value}</td>
                                    <td>${r.cyl_right_value}</td>
                                    <td>${r.axis_right}</td>
                                    <td>${r.addition_right}</td>
                                    <td>${r.sph_left_sign + ' ' + r.cyl_left_sign}</td>
                                    <td>${r.sph_left_value}</td>
                                    <td>${r.cyl_left_value}</td>
                                    <td>${r.axis_left}</td>
                                    <td>${r.addition_left}</td>
                                    <td style="text-align: center;">
                                        <a href="/dashboard/print-eye-test/${r.id}" target="_blank"
                                           class="btn btn-update">
                                           <i class="fa fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                                table.querySelector('tbody').innerHTML += row;
                            });

                            href.setAttribute('href', `{{ url('/dashboard/add-eye-test/${response.customer_id}')}}`);
                        }
                    });

                } else if(e.target.classList.contains('history') || e.target.parentElement.classList.contains('history')) {
                    let customer_id = e.target.getAttribute('data-id') || e.target.parentElement.getAttribute('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "GET",
                        url: '{{route("dashboard.customer-history")}}',
                        data: { customer_id: customer_id },
                       /* success: function(response) {
                            let table = document.querySelector('.history-table');
                            table.querySelector('tbody').innerHTML = '';

                            response.forEach((r, index) => {
                                let row = `
                                <tr>
                                    <td>${index+1}</td>
                                    <td><strong>${r.invoice_code}</strong></td>
                                    <td><strong style="color: #27ae60;">${r.total} QAR</strong></td>
                                    <td>${r.created_at}</td>
                                    <td>
                                        ${r.status == 'pending'
                                    ? '<span class="label label-warning">Pending</span>'
                                    : '<span class="label label-success">Delivered</span>'}
                                    </td>
                                    <td style="display: flex; gap: 8px;">
                                        ${r.status == 'pending'
                                    ? `<button id="postDeliver" class="btn btn-add post-invoice" data-id='${r.invoice_code}'>
                                                <i class="fa fa-check"></i> Post
                                               </button>`
                                    : ''}

                                        <div class="dropdown">
                                            <button class="btn btn-update">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                            <div class="dropdown-content">
                                                <a href='{{ url('/dashboard/print-pending-invoice/ar/') . '/'}}${r.invoice_code}' target="_blank">
                                                    <i class="fa fa-language"></i> Arabic
                                                </a>
                                                <a href='{{ url('/dashboard/print-pending-invoice/en/') . '/'}}${r.invoice_code}' target="_blank">
                                                    <i class="fa fa-language"></i> English
                                                </a>
                                            </div>
                                        </div>

                                        <a href="/dashboard/invoices/${r.invoice_code}/show"
   target="_blank"
   class="btn btn-invoice">
   <i class="fa fa-eye"></i> View
</a>

                                    </td>
                                </tr>
                            `;
                                table.querySelector('tbody').innerHTML += row;
                            });

                            postButton = document.querySelectorAll('#postDeliver');
                            postButton.forEach(post => {
                                post.addEventListener('click', function (e) {
                                    e.preventDefault();

                                    let invoice_notes = '';
                                    let invoice_trayn = '';
                                    let InvoiceID = e.target.getAttribute('data-id');

                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        type: "POST",
                                        url: '{{ route("dashboard.post-deliver-invoice") }}',
                                        data: {InvoiceID, invoice_notes, invoice_trayn},
                                        success: function (response) {
                                            document.querySelector('.delivered').style.display = 'block';
                                            setTimeout(function(){
                                                location.reload();
                                            }, 700);
                                        }
                                    });
                                });
                            });
                        }*/
                        success: function(response) {
                            let table = document.querySelector('.history-table');
                            table.querySelector('tbody').innerHTML = '';

                            // ── متغيرات للحساب ──
                            let totalAll       = 0;
                            let totalDelivered = 0;
                            let countAll       = response.length;
                            let countDelivered = 0;

                            response.forEach((r, index) => {
                                totalAll += parseFloat(r.total) || 0;
                                if (r.status === 'delivered') {
                                    totalDelivered += parseFloat(r.total) || 0;
                                    countDelivered++;
                                }

                                let row = `
            <tr>
                <td>${index+1}</td>
                <td><strong>${r.invoice_code}</strong></td>
                <td><strong style="color: #27ae60;">${r.total} QAR</strong></td>
                <td>${r.created_at}</td>
                <td>
                    ${r.status == 'pending'
                                    ? '<span class="label label-warning">Pending</span>'
                                    : '<span class="label label-success">Delivered</span>'}
                </td>
                <td style="display: flex; gap: 8px;">
                    ${r.status == 'pending'
                                    ? `<button id="postDeliver" class="btn btn-add post-invoice" data-id='${r.invoice_code}'>
                               <i class="fa fa-check"></i> Post
                           </button>`
                                    : ''}

                    <div class="dropdown">
                        <button class="btn btn-update">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <div class="dropdown-content">
                            <a href='{{ url('/dashboard/print-pending-invoice/ar/') . '/'}}${r.invoice_code}' target="_blank">
                                <i class="fa fa-language"></i> Arabic
                            </a>
                            <a href='{{ url('/dashboard/print-pending-invoice/en/') . '/'}}${r.invoice_code}' target="_blank">
                                <i class="fa fa-language"></i> English
                            </a>
                        </div>
                    </div>

                    <a href="/dashboard/invoices/${r.invoice_code}/show"
                       target="_blank"
                       class="btn btn-invoice">
                        <i class="fa fa-eye"></i> View
                    </a>
                </td>
            </tr>
        `;
                                table.querySelector('tbody').innerHTML += row;
                            });

                            // ── احذف الـ summary القديم لو موجود ──
                            let oldSummary = document.getElementById('history-summary');
                            if (oldSummary) oldSummary.remove();

                            // ── أضف الـ summary ──
                            let summaryHtml = `
        <div id="history-summary" style="margin-top: 20px;">

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 15px;">

                <div style="background: linear-gradient(135deg, #667eea, #764ba2);
                            color: white; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 26px; font-weight: 800;">${countAll}</div>
                    <div style="font-size: 11px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">
                        Total Invoices
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #27ae60, #229954);
                            color: white; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 26px; font-weight: 800;">${countDelivered}</div>
                    <div style="font-size: 11px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">
                        Delivered
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f39c12, #e67e22);
                            color: white; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800;">${totalDelivered.toFixed(2)}</div>
                    <div style="font-size: 11px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">
                        Delivered Amount (QAR)
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #2c3e50, #34495e);
                            color: white; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 22px; font-weight: 800;">${totalAll.toFixed(2)}</div>
                    <div style="font-size: 11px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">
                        Grand Total (QAR)
                    </div>
                </div>

            </div>

            ${countAll > 0 ? `
            <div style="background: #f0f2f5; border-radius: 8px; padding: 12px 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;
                            font-size: 12px; font-weight: 700; color: #555;">
                    <span>Delivery Rate</span>
                    <span>${((countDelivered / countAll) * 100).toFixed(0)}%</span>
                </div>
                <div style="background: #ddd; border-radius: 20px; height: 8px; overflow: hidden;">
                    <div style="width: ${((countDelivered / countAll) * 100).toFixed(0)}%;
                                height: 100%;
                                background: linear-gradient(135deg, #27ae60, #229954);
                                border-radius: 20px;
                                transition: width 0.5s ease;">
                    </div>
                </div>
            </div>
            ` : ''}

        </div>
    `;

                            document.querySelector('#histModal .modal-body').insertAdjacentHTML('beforeend', summaryHtml);

                            postButton = document.querySelectorAll('#postDeliver');
                            postButton.forEach(post => {
                                post.addEventListener('click', function (e) {
                                    e.preventDefault();

                                    let invoice_notes = '';
                                    let invoice_trayn = '';
                                    let InvoiceID = e.target.getAttribute('data-id');

                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        type: "POST",
                                        url: '{{ route("dashboard.post-deliver-invoice") }}',
                                        data: {InvoiceID, invoice_notes, invoice_trayn},
                                        success: function (response) {
                                            document.querySelector('.delivered').style.display = 'block';
                                            setTimeout(function(){
                                                location.reload();
                                            }, 700);
                                        }
                                    });
                                });
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
