@extends('dashboard.layouts.master')

@section('title', 'Create Invoice')

@section('styles')
    <style>
        /* ============================================
           MODERN INVOICE SYSTEM - PREMIUM DESIGN
        ============================================ */

        .invoice-page {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            min-height: 100vh;
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
        }

        /* Cards */
        .invoice-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .invoice-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(0,0,0,0.12);
        }

        .card-header-custom {
            padding: 20px 25px;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .card-header-blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .card-header-teal { background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); color: white; }

        .card-title-custom {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .card-title-custom i {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 12px;
            font-size: 20px;
        }

        .card-body-custom {
            padding: 25px;
        }

        /* Branch Selector - CRITICAL */
        .branch-selector-sticky {
            position: sticky;
            top: 70px;
            z-index: 100;
            background: white;
            border: 3px solid #3498db;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
        }

        .branch-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .branch-label {
            font-size: 14px;
            font-weight: 800;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .branch-select {
            font-size: 16px;
            font-weight: 600;
            border: 2px solid #3498db !important;
            border-radius: 10px !important;
            /*padding: 12px 15px !important;*/
        }

        /* Form Controls */
        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 10px 15px;*/
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
        }

        /* Items Table */
        .items-table {
            /*border-radius: 10px;*/
            overflow: hidden;
            margin-bottom: 0;
        }

        .items-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .items-table thead th {
            color: white;
            padding: 15px 12px;
            font-weight: 700;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            text-align: center;
        }

        .items-table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            text-align: center;
        }

        .items-table tbody tr:hover {
            background: #f8f9ff;
        }

        .items-table tfoot {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .items-table tfoot td {
            color: white;
            padding: 15px;
            font-weight: 800;
            font-size: 16px;
            border: none;
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
        }

        /* Barcode Input */
        .barcode-input-group {
            position: relative;
        }

        .barcode-input-group input {
            padding-left: 45px;
            font-size: 16px;
            font-weight: 600;
        }

        .barcode-input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #667eea;
        }

        /* Lenses Section */
        .lenses-section {
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9ff;
        }

        .lenses-collapsed {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding: 0 !important;
        }

        .lenses-expanded {
            /*max-height: 2000px;*/
            transition: max-height 0.5s ease-in;
        }

        /* Action Buttons */
        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 20px;
            border-top: 3px solid #e0e6ed;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            z-index: 99;
            border-radius: 12px;
        }

        .btn-submit-large {
            padding: 15px 50px;
            font-size: 18px;
            font-weight: 800;
            border-radius: 10px;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* نخفي الشكل الافتراضي */
        input[name="selected_eye_test"] {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background: #fff;
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        /* Hover */
        input[name="selected_eye_test"]:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.12);
        }

        /* لما يتحدد */
        input[name="selected_eye_test"]:checked {
            border-color: #667eea;
            background: #fff;
        }

        /* الدائرة اللي جوه */
        input[name="selected_eye_test"]:checked::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            animation: radioPop 0.2s ease forwards;
        }

        /* أنيميشن */
        @keyframes radioPop {
            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Disabled */
        input[name="selected_eye_test"]:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* الشكل الأساسي */
        .lens-select-left,
        .lens-select-right {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 8px;
            border: 2px solid #d1d5db;
            background: #ffffff;
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        /* Hover */
        .lens-select-left:hover,
        .lens-select-right:hover {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.12);
        }

        /* Checked */
        .lens-select-left:checked,
        .lens-select-right:checked {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
            transform: scale(1.05);
        }

        /* علامة الصح */
        .lens-select-left:checked::after,
        .lens-select-right:checked::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
            animation: checkPop 0.2s ease;
        }

        /* أنيميشن */
        @keyframes checkPop {
            0% { transform: scale(0) rotate(45deg); opacity: 0; }
            100% { transform: scale(1) rotate(45deg); opacity: 1; }
        }

        /* Disabled */
        .lens-select-left:disabled,
        .lens-select-right:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            box-shadow: none;
        }


        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section('content')
    <div class="invoice-page">

        <!-- Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-receipt-cutoff"></i> Create Invoice</h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">
                        Customer: <strong>{{ $customer->customer_id }} - {{ $customer->english_name }}</strong>
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.get-all-customers') }}" class="btn btn-light" style="background: #fff;">
                        <i class="bi bi-arrow-left"></i> Back to Customers
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form id="invoiceForm">
            @csrf

            <!-- ========== BRANCH SELECTION (STICKY) ========== -->
            <div class="branch-selector-sticky" id="branchSelector">
                <div class="branch-icon">
                    <i class="bi bi-building"></i>
                </div>
                <label class="branch-label">
                    <i class="bi bi-geo-alt-fill"></i> SELECT BRANCH
                    <span style="color: #e74c3c;">*</span>
                </label>
                <select name="branch_id" id="branch_id" class="form-control branch-select" required>
                    <option value="">-- Choose Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                                {{ ($defaultBranch && $defaultBranch->id == $branch->id) ? 'selected' : '' }}
                                data-name="{{ $branch->name }}">
                            {{ $branch->name }}
                            @if($branch->is_main) ⭐ (Main) @endif
                        </option>
                    @endforeach
                </select>
                <div class="alert alert-info" style="margin-top: 15px; margin-bottom: 0;">
                    <i class="bi bi-info-circle"></i>
                    <strong>Note:</strong> Stock will be checked from the selected branch only
                </div>
            </div>

            <!-- ========== CUSTOMER & DOCTOR ========== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="invoice-card">
                        <div class="card-header-custom card-header-purple">
                            <h3 class="card-title-custom">
                                <i class="bi bi-person-fill"></i>
                                Customer
                            </h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Customer ID</label>
                                    <input type="text" class="form-control"
                                           value="{{ $customer->customer_id }}" readonly>
                                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Customer Name</label>
                                    <input type="text" class="form-control"
                                           value="{{ $customer->english_name }}" readonly>
                                </div>
                               {{-- <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary-custom btn-block"
                                            data-toggle="modal" data-target="#customerModal">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="invoice-card">
                        <div class="card-header-custom card-header-teal">
                            <h3 class="card-title-custom">
                                <i class="bi bi-hospital"></i>
                                Doctor
                            </h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Doctor ID</label>
                                    <input type="text" class="form-control" id="doctor_id_display"
                                           value="{{ session('doctor_id') }}" readonly>
                                    <input type="hidden" name="doctor_id" id="doctor_id"
                                           value="{{ session('doctor_id') }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Doctor Name</label>
                                    <input type="text" class="form-control" id="doctor_name"
                                           name="doctor_name" value="{{ session('doctor_name') }}">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary-custom btn-block"
                                            data-toggle="modal" data-target="#doctorModal">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== PRODUCT SEARCH ========== -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-orange">
                    <h3 class="card-title-custom">
                        <i class="bi bi-cart-plus"></i>
                        Add Products
                    </h3>
                    <button type="button" class="btn btn-light"
                            data-toggle="modal" data-target="#searchModal">
                        <i class="bi bi-funnel"></i> Advanced Search
                    </button>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Quantity</label>
                            <input type="number" class="form-control" id="product_quantity"
                                   value="1" min="1">
                        </div>
                        <div class="col-md-7">
                            <label>Product ID / Barcode</label>
                            <div class="barcode-input-group">
                                <i class="bi bi-upc-scan"></i>
                                <input type="text" class="form-control" id="product_id_input"
                                       placeholder="Scan or enter product ID">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-success-custom btn-block"
                                    id="addProductBtn">
                                <i class="bi bi-plus-circle"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== LENSES SECTION (COLLAPSIBLE) ========== -->
            {{--
    ====================================================
    LENSES SECTION - To be included in main create_invoice_blade.php
    Replace the placeholder lenses section with this
    ====================================================
--}}

            <!-- ========== LENSES SECTION (COLLAPSIBLE) - COMPLETE ========== -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-blue" style="cursor: pointer;"
                     onclick="toggleLensesSection()">
                    <h3 class="card-title-custom">
                        <i class="bi bi-eye"></i>
                        Add Lenses
                    </h3>
                    <i class="bi bi-chevron-down" id="lensesToggleIcon"></i>
                </div>
                <div class="card-body-custom lenses-collapsed" id="lensesSection">
                    <div class="lenses-section">

                        <!-- Step 1: Select Eye Test -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 style="color: #667eea; font-weight: 700; margin-bottom: 20px;">
                                    <i class="bi bi-clipboard-check"></i> Step 1: Select Eye Test
                                </h5>

                                <button type="button" class="btn btn-primary-custom" id="loadEyeTestsBtn">
                                    <i class="bi bi-eye"></i> Load Eye Tests
                                </button>

                                <a href="{{ route('dashboard.add-eye-test', ['id' => $customer->id]) }}"
                                   class="btn btn-success-custom" target="_blank">
                                    <i class="bi bi-plus-circle"></i> Add New Eye Test
                                </a>

                                <div id="eyeTestsContainer" style="margin-top: 20px;">
                                    <!-- Eye tests will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <hr style="margin: 30px 0;">

                        <!-- Step 2: Filter Lenses -->
                        <div class="row" id="lensFiltersSection" style="display: none;">
                            <div class="col-md-12">
                                <h5 style="color: #667eea; font-weight: 700; margin-bottom: 20px;">
                                    <i class="bi bi-funnel"></i> Step 2: Filter Lenses
                                </h5>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Frame Type</label>
                                            <select class="form-control" id="lens_frame_type">
                                                <option value="">All</option>
                                                <option value="HBC Frame">HBC Frame</option>
                                                <option value="Full Frame">Full Frame</option>
                                                <option value="Nilor">Nilor</option>
                                                <option value="Rimless">Rimless</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lens Type</label>
                                            <select class="form-control" id="lens_type">
                                                <option value="">All</option>
                                                <option value="All Distance Lense">All Distance</option>
                                                <option value="Biofocal">Biofocal</option>
                                                <option value="Single Vision">Single Vision</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <select class="form-control" id="lens_brand">
                                                <option value="">All</option>
                                                <option value="Essilor">Essilor</option>
                                                <option value="KODAC">KODAC</option>
                                                <option value="TECHLINE">TECHLINE</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Index</label>
                                            <select class="form-control" id="lens_index">
                                                <option value="">All</option>
                                                <option value="1.5">1.5</option>
                                                <option value="1.56">1.56</option>
                                                <option value="1.6">1.6</option>
                                                <option value="1.67">1.67</option>
                                                <option value="1.74">1.74</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success-custom btn-block" id="filterLensesBtn">
                                            <i class="bi bi-search"></i> Search Lenses
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr style="margin: 30px 0;" id="lensResultsDivider" style="display: none;">

                        <!-- Step 3: Select Lenses -->
                        <div class="row" id="lensResultsSection" style="display: none;">
                            <div class="col-md-12">
                                <h5 style="color: #667eea; font-weight: 700; margin-bottom: 20px;">
                                    <i class="bi bi-check-square"></i> Step 3: Select Lenses
                                </h5>

                                <div id="lensesTableContainer">
                                    <!-- Lenses will be loaded here -->
                                </div>

                                <div style="margin-top: 20px;">
                                    <button type="button" class="btn btn-success-custom btn-lg" id="addSelectedLensesBtn">
                                        <i class="bi bi-plus-circle"></i> Add Selected Lenses to Invoice
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- ========== DISCOUNTS ========== -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-orange">
                    <h3 class="card-title-custom">
                        <i class="bi bi-percent"></i>
                        Discounts
                    </h3>
                    <button type="button" class="btn btn-light"
                            data-toggle="modal" data-target="#discountModal">
                        <i class="bi bi-info-circle"></i> View Details
                    </button>
                </div>
                <div class="card-body-custom">
                    <!-- Regular Discount -->
                    <div class="row" id="regularDiscountSection">
                        <div class="col-md-3">
                            <label>Discount Type</label>
                            <select class="form-control" id="discount_type">
                                <option value="">None</option>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Discount Value</label>
                            <input type="number" class="form-control" id="discount_value"
                                   placeholder="0.00" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-success-custom"
                                        id="applyDiscountBtn">
                                    <i class="bi bi-check"></i> Apply
                                </button>
                                <button type="button" class="btn btn-danger-custom"
                                        id="removeDiscountBtn">
                                    <i class="bi bi-x"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Payer Discount -->
                    <div class="row">
                        <div class="col-md-3">
                            <label>Payer Type</label>
                            <select class="form-control" id="payer_type">
                                <option value="">None</option>
                                <option value="insurance">Insurance</option>
                                <option value="cardholder">Cardholder</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Company</label>
                            <select class="form-control" id="payer_company" disabled>
                                <option value="">Select company</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="approvalAmountGroup" style="display: none;">
                            <label>Approval Amount</label>
                            <input type="number" class="form-control" id="approval_amount"
                                   placeholder="0.00" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-success-custom"
                                        id="applyPayerBtn">
                                    <i class="bi bi-check"></i> Apply
                                </button>
                                <button type="button" class="btn btn-danger-custom"
                                        id="removePayerBtn">
                                    <i class="bi bi-x"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== OTHER INFO ========== -->
            <div class="row">
                <div class="col-md-6">
                    <div class="invoice-card">
                        <div class="card-header-custom card-header-blue">
                            <h3 class="card-title-custom">
                                <i class="bi bi-calendar-event"></i>
                                Pickup Date
                            </h3>
                        </div>
                        <div class="card-body-custom">
                            <input type="date" class="form-control" name="pickup_date"
                                   id="pickup_date" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-card">
                        <div class="card-header-custom card-header-teal">
                            <h3 class="card-title-custom">
                                <i class="bi bi-person-circle"></i>
                                Created By
                            </h3>
                        </div>
                        <div class="card-body-custom">
                            <input type="text" class="form-control"
                                   value="{{ auth()->user()->full_name }}" readonly>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== PAYMENTS ========== -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-green">
                    <h3 class="card-title-custom">
                        <i class="bi bi-credit-card"></i>
                        Payments
                    </h3>
                </div>
                <div class="card-body-custom">
                    <div id="paymentsContainer">
                        <!-- Payment rows will be added here -->
                    </div>
                    <button type="button" class="btn btn-primary-custom" id="addPaymentBtn">
                        <i class="bi bi-plus-circle"></i> Add Payment Method
                    </button>
                </div>
            </div>

            <!-- ========== INVOICE ITEMS TABLE ========== -->
            <div class="invoice-card">
                <div class="card-header-custom card-header-purple">
                    <h3 class="card-title-custom">
                        <i class="bi bi-list-check"></i>
                        Invoice Items
                    </h3>
                    <span id="items_count" style="background: rgba(255,255,255,0.2);
                      padding: 5px 15px; border-radius: 20px; font-weight: 700;">
                    0 Items
                </span>
                </div>
                <div class="card-body-custom" style="padding: 0;">
                    <div id="itemsTableContainer">
                        <!-- Table will be rendered by JavaScript -->
                    </div>
                </div>
            </div>

        </form>

        <!-- ========== ACTION BUTTONS ========== -->
        <div class="action-buttons">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-danger-custom" id="resetInvoiceBtn">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Invoice
                    </button>
                    <button type="button" class="btn btn-primary-custom" id="saveDraftBtn">
                        <i class="bi bi-save"></i> Save Draft
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success-custom btn-submit-large"
                            id="submitInvoiceBtn">
                        <i class="bi bi-check-circle-fill"></i> Submit Invoice
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Include Modals -->
    {{--@include('dashboard.pages.invoice-new.modals.customer_modal')--}}
    @include('dashboard.pages.invoice-new.modals.doctor_modal')
    @include('dashboard.pages.invoice-new.modals.search_modal')
    @include('dashboard.pages.invoice-new.modals.discount_modal')

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle Lenses Section
        function toggleLensesSection() {
            const section = document.getElementById('lensesSection');
            const icon = document.getElementById('lensesToggleIcon');

            if (section.classList.contains('lenses-collapsed')) {
                section.classList.remove('lenses-collapsed');
                section.classList.add('lenses-expanded');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            } else {
                section.classList.remove('lenses-expanded');
                section.classList.add('lenses-collapsed');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        }
    </script>

    <!-- Include JavaScript Files -->
    @include('dashboard.pages.invoice-new.script.invoice-main')
    @include('dashboard.pages.invoice-new.script.invoice-discounts')
    @include('dashboard.pages.invoice-new.script.invoice-submit')

    <script>


        // Search functionality
        $('#customer-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#customers-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Select customer
        $('.select-customer-btn').on('click', function() {
            var customerId = $(this).data('id');
            var customerName = $(this).data('name');

            // Redirect to invoice page for this customer
            window.location.href = '/dashboard/invoices/create/' + customerId;
        });


    </script>

    <script>

        // Search functionality
        $('#doctor-search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#doctors-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Select doctor
        $('.select-doctor-btn').on('click', function() {
            var doctorCode = $(this).data('code');
            var doctorName = $(this).data('name');

            // Update form fields
            $('#doctor_id').val(doctorCode);
            $('#doctor_id_display').val(doctorCode);
            $('#doctor_name').val(doctorName);

            // Store in session
            $.ajax({
                url: '/dashboard/session/store-doctor',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    doctor_id: doctorCode,
                    doctor_name: doctorName
                },
                success: function(response) {
                    console.log('Doctor stored in session');
                }
            });

            // Close modal
            $('#doctorModal').modal('hide');

            // Success message
            Swal.fire({
                icon: 'success',
                title: 'Doctor Selected!',
                text: doctorName,
                timer: 1500,
                showConfirmButton: false
            });
        });

    </script>

    <script>
        $(document).ready(function() {

            // Execute search
            $('#executeSearchBtn').on('click', function() {
                const branch_id = $('#branch_id').val();

                if (!branch_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Branch Required',
                        text: 'Please select a branch first!',
                        confirmButtonColor: '#3498db'
                    });
                    return;
                }

                const filters = {
                    branch_id: branch_id,
                    category_id: $('#search_category_id').val(),
                    size: $('#search_size').val(),
                    color: $('#search_color').val(),
                    type: $('#search_type').val()
                };

                // Show loading
                $('#searchResultsContainer').html(`
            <div class="text-center" style="padding: 40px;">
                <div class="spinner-border text-primary" role="status"></div>
                <p style="margin-top: 15px;">Searching products...</p>
            </div>
        `);

                // AJAX search
                $.ajax({
                    url: '/dashboard/invoices/products/search',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: filters,
                    success: function(response) {
                        if (response.success) {
                            displaySearchResults(response.products);
                        } else {
                            $('#searchResultsContainer').html(`
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> ${response.message}
                        </div>
                    `);
                        }
                    },
                    error: function(xhr) {
                        $('#searchResultsContainer').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i> Search failed. Please try again.
                    </div>
                `);
                    }
                });
            });

            // Display search results
            function displaySearchResults(products) {
                if (products.length === 0) {
                    $('#searchResultsContainer').html(`
                <div class="alert alert-info text-center">
                    <i class="bi bi-inbox"></i> No products found matching your criteria
                </div>
            `);
                    return;
                }

                let tableHTML = `
            <div class="table-responsive">
                <table class="table table-hover items-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Description</th>
                            <th>Tax</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

                products.forEach(product => {
                    const stockBadge = product.stock > 10 ? 'badge-success' :
                        product.stock > 0 ? 'badge-warning' : 'badge-danger';

                    tableHTML += `
                <tr>
                    <td><strong style="color: #667eea;">${product.product_id}</strong></td>
                    <td>${product.description}</td>
                    <td>${product.tax}%</td>
                    <td><strong>${parseFloat(product.retail_price).toFixed(2)} QAR</strong></td>
                    <td><span class="badge ${stockBadge}">${product.available_quantity}</span></td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm select-product-from-search"
                                data-product-id="${product.product_id}"
                                ${product.available_quantity <= 0 ? 'disabled' : ''}>
                            <i class="bi bi-check-circle"></i> Select
                        </button>
                    </td>
                </tr>
            `;
                });

                tableHTML += `
                    </tbody>
                </table>
            </div>
        `;

                $('#searchResultsContainer').html(tableHTML);

                // Bind select events
                $('.select-product-from-search').on('click', function() {
                    const product_id = $(this).data('product-id');

                    // Fill main form
                    $('#product_id_input').val(product_id);

                    // Close modal
                    $('#searchModal').modal('hide');

                    // Trigger add
                    $('#addProductBtn').click();
                });
            }
        });
    </script>

    <script>
        // Lenses Management
        const LensesManager = {
            selectedEyeTest: null,
            selectedLenses: [],

            init() {
                this.setupEvents();
            },

            setupEvents() {
                $('#loadEyeTestsBtn').on('click', () => this.loadEyeTests());
                $('#filterLensesBtn').on('click', () => this.filterLenses());
                $('#addSelectedLensesBtn').on('click', () => this.addLensesToInvoice());
            },

            /* ================= LOAD EYE TESTS ================= */
            loadEyeTests() {
                const customer_id = $('input[name="customer_id"]').val();

                showLoading();

                $.ajax({
                    url: `/dashboard/invoices/lenses/eye-tests/${customer_id}`,
                    type: 'GET',

                    success: (data) => {
                        hideLoading();

                        if (data.success && data.eye_tests.length > 0) {
                            this.displayEyeTests(data.eye_tests);
                        } else {
                            $('#eyeTestsContainer').html(`
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No eye tests found for this customer.
                            <a href="/dashboard/customers/${customer_id}/add-eye-test" target="_blank">Add one now</a>
                        </div>
                    `);
                        }
                    },

                    error: (error) => {
                        hideLoading();
                        console.error('Load eye tests error:', error);
                    }
                });
            },

            /* ================= DISPLAY EYE TESTS ================= */
            displayEyeTests(tests) {
                let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background: #667eea; color: white;">
                        <tr>
                            <th>Select</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th colspan="4">Right Eye</th>
                            <th colspan="4">Left Eye</th>
                        </tr>
                        <tr style="background: #764ba2; color: white;">
                            <th></th><th></th><th></th>
                            <th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th>
                            <th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

                tests.forEach(test => {
                    html += `
                <tr>
                    <td>
                        <input type="radio" name="selected_eye_test" value="${test.id}"
                               data-test='${JSON.stringify(test)}'>
                    </td>
                    <td>${test.doctor_name}</td>
                    <td>${test.visit_date}</td>
                    <td>${test.sph_right_sign} ${test.sph_right_value || '-'}</td>
                    <td>${test.cyl_right_sign} ${test.cyl_right_value || '-'}</td>
                    <td>${test.axis_right || '-'}</td>
                    <td>${test.addition_right || '-'}</td>
                    <td>${test.sph_left_sign} ${test.sph_left_value || '-'}</td>
                    <td>${test.cyl_left_sign} ${test.cyl_left_value || '-'}</td>
                    <td>${test.axis_left || '-'}</td>
                    <td>${test.addition_left || '-'}</td>
                </tr>
            `;
                });

                html += `</tbody></table></div>`;
                $('#eyeTestsContainer').html(html);

                $('input[name="selected_eye_test"]').on('change', (e) => {
                    this.selectedEyeTest = JSON.parse($(e.target).attr('data-test'));
                    $('#lensFiltersSection').slideDown();

                    Swal.fire({
                        icon: 'success',
                        title: 'Eye Test Selected',
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            },

            /* ================= FILTER LENSES ================= */
            filterLenses() {
                const branch_id = $('#branch_id').val();

                if (!branch_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Branch Required',
                        text: 'Please select a branch first',
                        confirmButtonColor: '#f39c12'
                    });
                    return;
                }

                const filters = {
                    branch_id: branch_id,
                    frame_type: $('#lens_frame_type').val(),
                    lense_type: $('#lens_type').val(),
                    brand: $('#lens_brand').val(),
                    index: $('#lens_index').val()
                };

                showLoading();

                $.ajax({
                    url: '/dashboard/invoices/lenses/filter',
                    type: 'POST',
                    data: JSON.stringify(filters),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },

                    success: (data) => {
                        hideLoading();

                        if (data.success && data.lenses.length > 0) {
                            this.displayLenses(data.lenses);
                            $('#lensResultsDivider').show();
                            $('#lensResultsSection').slideDown();
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Lenses Found',
                                text: 'Try adjusting your filters',
                                confirmButtonColor: '#3498db'
                            });
                        }
                    },

                    error: (error) => {
                        hideLoading();
                        console.error('Filter lenses error:', error);
                    }
                });
            },

            /* ================= DISPLAY LENSES ================= */
            displayLenses(lenses) {
                let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background: #667eea; color: white;">
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Index</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Left Eye</th>
                            <th>Right Eye</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

                lenses.forEach(lens => {
                    const stockBadge = lens.stock > 5 ? 'badge-success' :
                        lens.stock > 0 ? 'badge-warning' : 'badge-danger';

                    html += `
                <tr>
                    <td><strong style="color: #667eea;">${lens.product_id}</strong></td>
                    <td>${lens.description}</td>
                    <td>${lens.index}</td>
                    <td><strong>${parseFloat(lens.retail_price).toFixed(2)} QAR</strong></td>
                    <td><span class="badge ${stockBadge}">${lens.stock}</span></td>
                    <td>
                        <input type="checkbox" class="lens-select-left"
                               value="${lens.product_id}"
                               ${lens.stock <= 0 ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="checkbox" class="lens-select-right"
                               value="${lens.product_id}"
                               ${lens.stock <= 0 ? 'disabled' : ''}>
                    </td>
                </tr>
            `;
                });

                html += `</tbody></table></div>`;
                $('#lensesTableContainer').html(html);
            },

            /* ================= ADD LENSES TO INVOICE ================= */
            addLensesToInvoice() {
                const branch_id = $('#branch_id').val();
                const lenses = [];

                $('.lens-select-left:checked').each(function () {
                    lenses.push({ product_id: $(this).val(), direction: 'L' });
                });

                $('.lens-select-right:checked').each(function () {
                    lenses.push({ product_id: $(this).val(), direction: 'R' });
                });

                if (lenses.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Lenses Selected',
                        text: 'Please select at least one lens',
                        confirmButtonColor: '#f39c12'
                    });
                    return;
                }

                showLoading();

                $.ajax({
                    url: '/dashboard/invoices/lenses/add',
                    type: 'POST',
                    data: JSON.stringify({
                        lenses: lenses,
                        branch_id: branch_id,
                        eye_test_id: this.selectedEyeTest ? this.selectedEyeTest.id : null
                    }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },

                    success: (data) => {
                        hideLoading();

                        if (data.success) {
                            // ✅ FIX: Update invoiceState with new draft from server
                            invoiceState = data.draft;

                            // ✅ FIX: Re-render table with updated state
                            renderItemsTable();

                            // Clear selections
                            $('.lens-select-left, .lens-select-right').prop('checked', false);

                            // Collapse lenses section
                            toggleLensesSection();

                            Swal.fire({
                                icon: 'success',
                                title: 'Lenses Added!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },

                    error: () => {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add lenses',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        };

        $(document).ready(function () {
            LensesManager.init();
        });
    </script>


@endsection
