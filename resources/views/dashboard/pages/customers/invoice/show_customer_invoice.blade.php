@extends('dashboard.layouts.master')

@section('title', 'Create Invoice')

@section('styles')
    <style>
        /* ============================================
           MODERN INVOICE PAGE - ULTRA PREMIUM DESIGN
        ============================================ */

        .invoice-page {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px 20px;
            min-height: 100vh;
        }

        /* Page Header */
        .invoice-main-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px 40px;
            border-radius: 15px;
            margin-bottom: 35px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .invoice-main-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .invoice-main-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .invoice-main-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        /* Modern Box Design */
        .invoice-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
            transition: all 0.3s;
            border: none;
        }

        .invoice-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(0,0,0,0.12);
        }

        /* Box Headers */
        .invoice-box-header {
            padding: 20px 30px;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .invoice-box-header.header-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .invoice-box-header.header-orange {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .invoice-box-header.header-blue {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .invoice-box-header.header-green {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .invoice-box-header.header-red {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .invoice-box-header.header-teal {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            color: white;
        }

        .box-header-title {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .box-header-title i {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 15px;
            font-size: 22px;
        }

        /* Box Body */
        .invoice-box-body {
            padding: 30px;
        }

        /* Branch Selector - PREMIUM */
        .branch-selector-premium {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            border: 3px solid #3498db;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .branch-selector-premium::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 50%;
        }

        .branch-icon-premium {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
        }

        .branch-label-premium {
            font-size: 16px;
            font-weight: 800;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: block;
        }

        .branch-label-premium i {
            margin-right: 8px;
            color: #3498db;
        }

        .branch-label-premium .required {
            color: #e74c3c;
            font-size: 20px;
            margin-left: 5px;
        }

        .branch-select-premium {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            border: 3px solid #3498db !important;
            border-radius: 12px !important;
            padding: 15px 20px !important;
            transition: all 0.3s;
            background: white;
        }

        .branch-select-premium:focus {
            border-color: #2980b9 !important;
            box-shadow: 0 0 0 5px rgba(52, 152, 219, 0.15) !important;
            transform: translateY(-2px);
        }

        .branch-info-premium {
            margin-top: 15px;
            padding: 15px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 10px;
            font-size: 14px;
            color: #2c3e50;
            font-weight: 600;
        }

        .branch-info-premium i {
            color: #3498db;
            margin-right: 5px;
        }

        /* Form Controls - Enhanced */
        .form-group label {
            font-weight: 700;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }

        .form-group label i {
            margin-right: 5px;
            color: #667eea;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 10px !important;
            /*padding: 12px 18px;*/
            transition: all 0.3s;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-control:disabled {
            background: #f8f9fa;
            cursor: not-allowed;
        }

        /* Premium Buttons */
        .btn-search-premium {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-search-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-add-lenses-premium {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-add-lenses-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Info Alert */
        .info-alert-premium {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            border-left: 5px solid #3498db;
            border-radius: 10px;
            padding: 15px 20px;
            margin-top: 25px;
            margin-bottom: 0;
            font-weight: 600;
            color: #2c3e50;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.1);
        }

        .info-alert-premium i {
            color: #3498db;
            font-size: 18px;
            margin-right: 10px;
        }

        /* Discount Section */
        .discount-section {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border-left: 5px solid #f39c12;
            border-radius: 15px;
            padding: 0;
        }

        /* Payment Section */
        .payment-section-body {
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            padding: 25px;
            border-radius: 12px;
        }

        .add-payment-link {
            color: #3498db;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-payment-link:hover {
            color: #2980b9;
            transform: translateX(5px);
        }

        .add-payment-link i {
            font-size: 18px;
            margin-right: 5px;
        }

        /* Items Table - ULTRA PREMIUM */
        .invoice-items-table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .invoice-items-table table {
            margin-bottom: 0;
        }

        .invoice-items-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .invoice-items-table thead th {
            color: white;
            padding: 18px 15px;
            font-weight: 800;
            border: none;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            text-align: center;
        }

        .invoice-items-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 2px solid #f0f2f5;
            text-align: center;
            font-weight: 600;
            color: #2c3e50;
        }

        .invoice-items-table tbody tr {
            transition: all 0.3s;
        }

        .invoice-items-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: scale(1.01);
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }

        .invoice-items-table tfoot {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .invoice-items-table tfoot td {
            color: white;
            padding: 20px 15px;
            font-weight: 800;
            font-size: 17px;
            border: none;
            text-align: center;
        }

        /* Delete Button */
        .btn-delete-item {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
        }

        .btn-delete-item:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.5);
        }

        /* Action Buttons - Premium */
        .btn-primary-premium {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
            color: white;
        }

        .btn-primary-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.5);
            color: white;
        }

        .btn-danger-premium {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.3);
            color: white;
        }

        .btn-danger-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(231, 76, 60, 0.5);
            color: white;
        }

        .btn-info-premium {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(26, 188, 156, 0.3);
            color: white;
        }

        .btn-info-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(26, 188, 156, 0.5);
            color: white;
        }

        .btn-submit-premium {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border: none;
            padding: 18px 50px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 18px;
            transition: all 0.3s;
            box-shadow: 0 5px 25px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-submit-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(39, 174, 96, 0.6);
            color: white;
        }

        /* Empty State */
        .empty-state-premium {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-premium i {
            font-size: 100px;
            color: #ddd;
            margin-bottom: 25px;
            display: block;
        }

        .empty-state-premium h4 {
            color: #999;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-state-premium p {
            color: #bbb;
            font-size: 15px;
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

        .invoice-box {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .invoice-page {
                padding: 15px 10px;
            }

            .invoice-main-header {
                padding: 25px 20px;
            }

            .invoice-main-header h1 {
                font-size: 24px;
            }

            .invoice-box-body {
                padding: 20px;
            }

            .branch-selector-premium {
                padding: 20px;
            }

            .btn-submit-premium {
                width: 100%;
                margin-top: 15px;
            }
        }

        /* Payment Details Table */
        .payments-details {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }

        .payments-details thead tr {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .payments-details thead th {
            color: white;
            padding: 15px;
            font-weight: 700;
            border: none;
        }

        .payments-details tbody td {
            padding: 12px 15px;
        }

        /* Show Discount Button */
        .btn-show-discount {
            background: rgba(255,255,255,0.25);
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-show-discount:hover {
            background: rgba(255,255,255,0.35);
            border-color: rgba(255,255,255,0.6);
            color: white;
        }

        #customerId, #doctorId, #productId {
            width: auto !important;
            height: auto !important;
        }
    </style>
@endsection

@section('content')
    <div class="invoice-page">
        <!-- Main Header -->
        <div class="invoice-main-header">
            <h1>
                <i class="bi bi-receipt-cutoff"></i> Create Customer Invoice
            </h1>
            <p>
                <i class="bi bi-info-circle"></i> Fill in all required information to create a new invoice
            </p>
        </div>

        <form action="{{route('dashboard.save-invoice')}}" method="post" class="saveInvoice">
            {{csrf_field()}}

            <!-- Hidden Fields -->
            <input type="hidden" name="id" value="{{$id}}" id="id">
            <input type="hidden" name="TotalQuantity" value="" id="TotalQuantity">
            <input type="hidden" name="T-Price" value="" id="T_Price">
            <input type="hidden" name="T-Net" value="" id="T_Net">
            <input type="hidden" name="T-Tax" value="" id="T_Tax">
            <input type="hidden" name="T_Totals" value="" id="T_Totals">

            <!-- BRANCH SELECTION - PREMIUM -->
            <div class="branch-selector-premium">
                <div class="branch-icon-premium">
                    <i class="bi bi-building"></i>
                </div>
                <label for="branch_id" class="branch-label-premium">
                    <i class="bi bi-geo-alt-fill"></i> Select Branch
                    <span class="required">*</span>
                </label>
                <select name="branch_id" id="branch_id" class="form-control branch-select-premium" required>
                    <option value="" selected>-- Choose Branch --</option>

                    @foreach(auth()->user()->getAccessibleBranches() as $branch)
                        <option value="{{ $branch->id }}"
                                {{ ($defaultBranch && $defaultBranch->id == $branch->id) ? 'selected' : '' }}
                                data-address="{{ $branch->address }}"
                                data-phone="{{ $branch->phone }}">
                            {{ $branch->name }}
                            @if($branch->is_main)
                                ⭐ (Main Branch)
                            @endif
                        </option>
                    @endforeach

                </select>
                <div class="branch-info-premium" id="branch-info">
                    @if($defaultBranch)
                        <i class="bi bi-geo-alt"></i> {{ $defaultBranch->address }}
                        @if($defaultBranch->phone)
                            <i class="bi bi-telephone" style="margin-left: 15px;"></i> {{ $defaultBranch->phone }}
                        @endif
                    @else
                        <i class="bi bi-info-circle"></i> Please select a branch to continue with the invoice
                    @endif
                </div>
            </div>

            <!-- CUSTOMER & DOCTOR ROW -->
            <div class="row">
                <!-- Customer Box -->
                <div class="col-md-6">
                    <div class="invoice-box">
                        <div class="invoice-box-header header-purple">
                            <h3 class="box-header-title">
                                <i class="bi bi-person-fill"></i>
                                Customer Information
                            </h3>
                        </div>
                        <div class="invoice-box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_id">
                                            <i class="bi bi-hash"></i> Customer ID
                                        </label>
                                        <input type="text" class="form-control" disabled
                                               value="{{$customer->customer_id}}" id="customer_id">
                                        <input type="hidden" value="{{$customer->customer_id}}" name="customer_id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">
                                            <i class="bi bi-person-badge"></i> Customer Name
                                        </label>
                                        <input type="text" class="form-control" name="customer_name"
                                               value="{{$customer->english_name}}" id="customer_name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-search-premium btn-block"
                                                data-target="#customerModal" data-toggle="modal">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Box -->
                <div class="col-md-6">
                    <div class="invoice-box">
                        <div class="invoice-box-header header-teal">
                            <h3 class="box-header-title">
                                <i class="bi bi-hospital"></i>
                                Doctor Information
                            </h3>
                        </div>
                        <div class="invoice-box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="doctor_id">
                                            <i class="bi bi-hash"></i> Doctor ID
                                        </label>
                                        <input type="text" class="form-control" disabled
                                               value="{{ session('doctor_id') }}" id="doctor_id">
                                        <input type="hidden" value="{{ session('doctor_id') }}" name="doctor_id" id="doctor-id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_name">
                                            <i class="bi bi-person-badge"></i> Doctor Name
                                        </label>
                                        <input type="text" class="form-control" name="doctor_name"
                                               value="{{ session('doctor_name') }}" id="doctor_name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-search-premium btn-block"
                                                data-target="#DoctorModal" data-toggle="modal">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SELECT ITEMS -->
            <div class="invoice-box">
                <div class="invoice-box-header header-orange">
                    <h3 class="box-header-title">
                        <i class="bi bi-cart-plus"></i>
                        Select Products
                    </h3>
                    <a href="{{ route('dashboard.get-lenses-view', ['id' => $customer->id]) }}"
                       class="btn btn-add-lenses-premium">
                        <i class="bi bi-eye"></i> Add Lenses
                    </a>
                </div>
                <div class="invoice-box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_quantity">
                                    <i class="bi bi-123"></i> Quantity
                                </label>
                                <input type="number" class="form-control" name="product_quantity"
                                       value="1" id="product_quantity" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="product_id">
                                    <i class="bi bi-upc-scan"></i> Product ID / Barcode
                                </label>
                                <input type="text" class="form-control" name="product_id"
                                       value="" id="product_id" placeholder="Enter product ID or scan barcode">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-search-premium btn-block"
                                        data-target="#searchModal" data-toggle="modal">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="info-alert-premium">
                                <i class="bi bi-info-circle-fill"></i>
                                <strong>Stock will be checked from the selected branch</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BENEFICIARY & PICKUP DATE ROW -->
            <div class="row">
                <div class="col-md-6">
                    <div class="invoice-box">
                        <div class="invoice-box-header header-blue">
                            <h3 class="box-header-title">
                                <i class="bi bi-person-circle"></i>
                                Beneficiary
                            </h3>
                        </div>
                        <div class="invoice-box-body">
                            <div class="form-group">
                                <label for="userLogin">
                                    <i class="bi bi-person-check"></i> Created By
                                </label>
                                <input type="text" class="form-control" name="userLogin"
                                       value="{{auth()->user()->first_name.' '.auth()->user()->last_name}}"
                                       id="userLogin" readonly>
                                <input type="hidden" name="user_id" id="user_id" value="{{auth()->user()->id}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="invoice-box">
                        <div class="invoice-box-header header-green">
                            <h3 class="box-header-title">
                                <i class="bi bi-calendar-event"></i>
                                Pickup Information
                            </h3>
                        </div>
                        <div class="invoice-box-body">
                            <div class="form-group">
                                <label for="pickup_date">
                                    <i class="bi bi-calendar-check"></i> Pickup Date
                                </label>
                                <input class="form-control" type="date" id="pickup_date"
                                       name="pickup_date" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DISCOUNT SECTION -->
            <div class="invoice-box discount-section">
                <div class="invoice-box-header header-orange">
                    <h3 class="box-header-title">
                        <i class="bi bi-percent"></i>
                        Discount
                    </h3>
                </div>
                <div class="invoice-box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_type">
                                    <i class="bi bi-tag"></i> Discount Type
                                </label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage (%)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_value">
                                    <i class="bi bi-cash"></i> Discount Value
                                </label>
                                <input type="text" class="form-control" name="discount_value"
                                       value="" id="discount_value" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-primary-premium" id="applyDiscount">
                                    <i class="bi bi-check-circle"></i> Apply
                                </button>
                                <button type="button" class="btn btn-danger-premium" id="removeDiscount">
                                    <i class="bi bi-x-circle"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="discount_type" id="regular_discount_type" value="">
                    <input type="hidden" name="discount_value" id="regular_discount_value" value="">
                </div>
            </div>

            <!-- INSURANCE & CARDHOLDER -->
            <div class="invoice-box">
                <div class="invoice-box-header header-blue">
                    <h3 class="box-header-title">
                        <i class="bi bi-shield-check"></i>
                        Insurance & Cardholder
                    </h3>
                    <button type="button" class="btn btn-show-discount" data-target="#DiscountModal" data-toggle="modal">
                        <i class="bi bi-tag"></i> Show Discounts
                    </button>
                </div>
                <div class="invoice-box-body">
                    <div class="row">
                        <input type="hidden" name="payer_type" id="payer_type" value="">
                        <input type="hidden" name="payer_type_id" id="payer_type_id" value="">
                        <input type="hidden" name="payer_type_approval_amount" id="payer_type_approval_amount" value="">
                        <input type="hidden" name="payer_type_discount" id="payer_type_discount" value="">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="insurance_cardholder_type">
                                    <i class="bi bi-list-ul"></i> Choose Type
                                </label>
                                <select name="insurance_cardholder_type" id="insurance_cardholder_type" class="form-control">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="insurance">Insurance Company</option>
                                    <option value="cardholder">Cardholder</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="payer_label" for="payer_select">
                                    <i class="bi bi-building"></i> Choose Company
                                </label>
                                <select name="payer_id" id="payer_select" class="form-control" disabled>
                                    <option value="" disabled selected>Select Company</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" id="approval_amount_wrap" hidden>
                            <div class="form-group">
                                <label for="approval_amount">
                                    <i class="bi bi-cash-coin"></i> Approval Amount
                                </label>
                                <input type="number" min="0" step="0.01" name="approval_amount"
                                       id="approval_amount" class="form-control" placeholder="0.00">
                            </div>
                        </div>

                        <input type="hidden" name="insurance_cardholder_type_id" id="insurance_cardholder_type_id">

                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary-premium" id="applyPayer">
                                <i class="bi bi-check-circle"></i> Apply
                            </button>
                            <button type="button" class="btn btn-info-premium" id="showPayer">
                                <i class="bi bi-eye"></i> Show Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYMENTS SECTION -->
            <div class="invoice-box">
                <div class="invoice-box-header header-green">
                    <h3 class="box-header-title">
                        <i class="bi bi-credit-card"></i>
                        Payment Methods
                    </h3>
                </div>
                <div class="invoice-box-body payment-section-body">
                    <div class="one-pay">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_type">
                                        <i class="bi bi-wallet2"></i> Payment Type
                                    </label>
                                    <select name="payment_type" id="payment_type" class="form-control payment_type">
                                        <option value="Cash">💵 Cash</option>
                                        <option value="Atm">🏧 ATM</option>
                                        <option value="visa">💳 VISA</option>
                                        <option value="Master Card">💳 MasterCard</option>
                                        <option value="Gift Voudire">🎁 Gift Voucher</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paied">
                                        <i class="bi bi-cash-stack"></i> Paid Amount
                                    </label>
                                    <input type="text" class="form-control" name="paied"
                                           value="" id="paied" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <table style="display: none; width: 100%; margin-top: 20px;" class="table table-bordered payments-details">
                                    <thead>
                                    <tr>
                                        <th>Bank</th>
                                        <th>Card Number</th>
                                        <th>Expiration Date</th>
                                        <th>Currency</th>
                                        <th>Exchange Rate</th>
                                        <th>Local Payment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="Bank" value="" id="Bank"></td>
                                        <td><input type="text" class="form-control" name="Card_No" value="" id="Card_No"></td>
                                        <td><input class="form-control" type="date" name="expiration_date" id="expiration_date" value=""></td>
                                        <td>
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="QAR">QAR</option>
                                            </select>
                                        </td>
                                        <td>1</td>
                                        <td><input type="text" class="form-control" name="local_payment" id="local_payment" readonly></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="add-payment-link pull-right">
                <a href="#" class="add-payment-btn">
                    <i class="bi bi-plus-circle-fill"></i> Add Another Payment
                </a>
            </div>
            <div class="clearfix"></div>

            <!-- ITEMS TABLE -->
            <div class="invoice-box" style="margin-top: 20px;">
                <div class="invoice-box-header header-purple">
                    <h3 class="box-header-title">
                        <i class="bi bi-list-check"></i>
                        Invoice Items
                    </h3>
                </div>
                <div class="invoice-box-body" style="padding: 0;">
                    @if (isset($_SESSION['products']) && count($_SESSION['products']) > 0)
                        <div class="invoice-items-table">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>Item ID</th>
                                    <th>Description</th>
                                    <th>QTY</th>
                                    <th>Price</th>
                                    <th>Net</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                    <th>Stock</th>
                                    <th>Branch</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="products">
                                @foreach($_SESSION['products'] as $product)
                                    <tr id="P_{!!  $product['Product']['product_id']!!}"
                                        data-category="{{ isset($product['Product']['product_category_id']) ? $product['Product']['product_category_id'] : '' }}">
                                        <td><strong>{{$product['Product']['id']}}</strong></td>
                                        <td><strong style="color: #667eea;">{{$product['Product']['product_id']}}</strong></td>
                                        <td>{{$product['Product']['description']}}</td>
                                        <td class="QTY"><strong>{{$product['Product']['product_quantity']}}</strong></td>
                                        <td class="PRICE">{{ number_format((float) $product['Product']['price'], 2) }}</td>

                                        <td class="NET">
                                            {{ number_format((float) $product['Product']['net'] * (float) $product['Product']['product_quantity'], 2) }}
                                        </td>

                                        <td class="TAX">{{ number_format((float) $product['Product']['tax'], 2) }}</td>

                                        <td class="TOTALS">
                                            <strong>{{ number_format((float) $product['Product']['total'], 2) }}</strong>
                                        </td>

                                        <td>{{$product['Product']['stock']}}</td>
                                        <td><span style="color: #3498db; font-weight: 600;">{{$product['Product']['branch_name']}}</span></td>
                                        <td>
                                            <button type="button" class="btn-delete-item delete-invoice-item"
                                                    data-id="{{ $product['Product']['product_id'] }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                        <td style="display: none">{{$product['Product']['type']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                @if (isset($_SESSION['totals']))
                                    <tr>
                                        <td colspan="3">TOTAL</td>
                                        <td id="TotalQTY">{{$_SESSION['totals']['totals']['totalQTY']}}</td>
                                        <td id="TotalPrice">{{ number_format((float) ($_SESSION['totals']['totals']['totalPRICE'] ?? 0), 2) }}</td>

                                        <td id="TotalNet">{{ number_format((float) ($_SESSION['totals']['totals']['totalNET'] ?? 0), 2) }}</td>

                                        <td id="TotalTax">{{ number_format((float) ($_SESSION['totals']['totals']['totalTAX'] ?? 0), 2) }}</td>

                                        <td colspan="4" id="Totals">
                                            <strong>{{ number_format((float) ($_SESSION['totals']['totals']['Totals'] ?? 0), 2) }} QAR</strong>
                                        </td>

                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3">TOTAL</td>
                                        <td id="TotalQTY">0</td>
                                        <td id="TotalPrice">0.00</td>
                                        <td id="TotalNet">0.00</td>
                                        <td id="TotalTax">0.00</td>
                                        <td colspan="4" id="Totals"><strong>0.00 QAR</strong></td>
                                    </tr>
                                @endif
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-premium">
                            <i class="bi bi-inbox"></i>
                            <h4>No Items Added Yet</h4>
                            <p>Use the search above to add products to this invoice</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SUBMIT BUTTONS -->
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-8">
                    <button type="button" id="reset" class="btn btn-danger-premium">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Invoice
                    </button>
                    <button type="submit" class="btn btn-primary-premium saveOnly">
                        <i class="bi bi-save"></i> Save Draft
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-submit-premium pull-right save-and-exit">
                        <i class="bi bi-check-circle-fill"></i> Submit Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- MODALS -->
    @include('dashboard.pages.customers.invoice.layout.models.doctor_model')
    @include('dashboard.pages.customers.invoice.layout.models.search_model')
    @include('dashboard.pages.customers.invoice.layout.models.customer_model')
    @include('dashboard.pages.customers.invoice.layout.models.discount_model')
    @include('dashboard.pages.customers.invoice.layout.models.insurance_cardholder_single_model')

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Branch Selection Update
            $('#branch_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var address = selectedOption.data('address');
                var phone = selectedOption.data('phone');

                var infoText = '';
                if (address) {
                    infoText += '<i class="bi bi-geo-alt"></i> ' + address;
                }
                if (phone) {
                    infoText += ' <i class="bi bi-telephone" style="margin-left: 15px;"></i> ' + phone;
                }

                $('#branch-info').html(infoText || '<i class="bi bi-info-circle"></i> Branch selected successfully');
            });

            // Validate branch before adding products
            $('#productSearch, #product_id').on('click focus', function() {
                if (!$('#branch_id').val()) {
                    alert('⚠️ Please select a branch first!');
                    $('#branch_id').focus();
                    return false;
                }
            });

            // Form validation
            $(document).on('submit', '.saveInvoice', function(e) {
                if (!$('#branch_id').val()) {
                    e.preventDefault();
                    alert('⚠️ Please select a branch before submitting!');
                    $('#branch_id').focus();
                    return false;
                }
            });

            // Number formatting
            $('.form-control[type="number"]').on('blur', function() {
                var value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $(this).val(value.toFixed(2));
                }
            });
        });
    </script>

    @include('dashboard.pages.customers.invoice.layout.scripts.show_customer_invoice_scripts')
@endsection
