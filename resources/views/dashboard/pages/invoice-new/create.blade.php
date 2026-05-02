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
                <div class="col-md-4 text-right" style="display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                    @can('edit-settings')
                    <a href="{{ route('dashboard.settings.index') }}"
                       style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.15);color:#fff;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;border:1px solid rgba(255,255,255,.25);transition:background .2s;"
                       onmouseover="this.style.background='rgba(255,255,255,.25)'"
                       onmouseout="this.style.background='rgba(255,255,255,.15)'"
                       title="Change invoice design in Settings">
                        <i class="bi bi-palette"></i>
                        {{ isset($invoiceDesign) ? ucfirst(str_replace('design', 'Design ', $invoiceDesign)) : 'Design 1' }}
                    </a>
                    @endcan
                    <a href="{{ route('dashboard.get-all-customers') }}" class="btn btn-light" style="background: #fff;">
                        <i class="bi bi-arrow-left"></i> Back
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
                        <div class="col-md-6">
                            <label>Product ID / Barcode</label>
                            <div class="barcode-input-group">
                                <i class="bi bi-upc-scan"></i>
                                <input type="text" class="form-control" id="product_id_input"
                                       placeholder="Scan or enter product ID / barcode">
                            </div>
                        </div>
                        <div class="col-md-1" style="padding-right:4px;">
                            <label>&nbsp;</label>
                            <button type="button" id="openCameraBtn"
                                    title="Scan with camera"
                                    style="width:100%;height:42px;border-radius:8px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:none;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 10px rgba(124,58,237,.35);transition:.2s;"
                                    onmouseover="this.style.transform='translateY(-2px)'"
                                    onmouseout="this.style.transform=''">
                                <i class="bi bi-camera-video"></i>
                            </button>
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

                                <!-- Row 1 -->
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
                                            <label>Lense Type</label>
                                            <select class="form-control" id="lens_type">
                                                <option value="">All</option>
                                                <option value="All Distance Lense">All Distance Lense</option>
                                                <option value="Biofocal">Biofocal</option>
                                                <option value="Single Vision">Single Vision</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Life Style</label>
                                            <select class="form-control" id="lens_life_style">
                                                <option value="">All</option>
                                                <option value="Normal">Normal</option>
                                                <option value="Active">Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Customer Activity</label>
                                            <select class="form-control" id="lens_customer_activity">
                                                <option value="">All</option>
                                                <option value="Clear / Tintable">Clear / Tintable</option>
                                                <option value="Transition">Transition</option>
                                                <option value="Glare Free">Glare Free</option>
                                                <option value="POLARIZED">POLARIZED</option>
                                                <option value="TINTED">TINTED</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lense Technology</label>
                                            <select class="form-control" id="lens_lense_tech">
                                                <option value="">All</option>
                                                <option value="HD / Digital Lense">HD / Digital Lense</option>
                                                <option value="Basic">Basic</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lense Production</label>
                                            <select class="form-control" id="lens_production">
                                                <option value="">All</option>
                                                <option value="Stock">Stock</option>
                                                <option value="RX">RX</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            {{--<select class="form-control" id="lens_brand">
                                                <option value="">All</option>
                                                <option value="Essilor">Essilor</option>
                                                <option value="KODAC">KODAC</option>
                                                <option value="TECHLINE">TECHLINE</option>
                                            </select>--}}
                                            <select name="lens_brand" id="lens_brand" class="form-control select2" required>
                                                <option value="">All</option>
                                                @foreach($lensBrands as $brand)
                                                    <option value="{{ $brand->id }}">
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Index</label>
                                            <select class="form-control" id="lens_index">
                                                <option value="">All</option>
                                                <option value="1.5">1.5</option>
                                                <option value="1.53">1.53</option>
                                                <option value="1.56">1.56</option>
                                                <option value="1.59">1.59</option>
                                                <option value="1.6">1.6</option>
                                                <option value="1.61">1.61</option>
                                                <option value="1.67">1.67</option>
                                                <option value="1.74">1.74</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 3 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" class="form-control" id="lens_description"
                                                   placeholder="Search by description...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-success-custom btn-block" id="filterLensesBtn">
                                                <i class="bi bi-search"></i> Search Lenses
                                            </button>
                                        </div>
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
                <div class="card-header-custom card-header-orange" style="cursor:pointer;"
                     onclick="toggleDiscountSection()">
                    <h3 class="card-title-custom">
                        <i class="bi bi-percent"></i>
                        Discounts
                    </h3>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button type="button" class="btn btn-light"
                                id="openDiscountModalBtn">
                            <i class="bi bi-info-circle"></i> View Details
                        </button>
                        <i class="bi bi-chevron-down" id="discountToggleIcon"></i>
                    </div>
                </div>
                <div class="card-body-custom lenses-collapsed" id="discountSection">
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

    {{-- ══════════════════════════════════════════════════════════
         CAMERA BARCODE SCANNER MODAL
    ══════════════════════════════════════════════════════════ --}}
    <div id="cameraScanModal" style="
        display:none;position:fixed;inset:0;z-index:99999;
        background:rgba(0,0,0,.75);
        align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:20px;width:480px;max-width:96vw;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.4);">

            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                <div style="color:#fff;font-size:17px;font-weight:700;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-camera-video" style="font-size:22px;"></i>
                    Camera Barcode Scanner
                </div>
                <button id="closeCameraBtn" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:8px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>

            {{-- Body --}}
            <div style="padding:24px;">

                {{-- Status bar --}}
                <div id="camStatus" style="text-align:center;margin-bottom:14px;font-size:13px;color:#7c3aed;font-weight:600;min-height:20px;">
                    Starting camera…
                </div>

                {{-- Camera viewfinder --}}
                <div style="position:relative;border-radius:14px;overflow:hidden;background:#111;aspect-ratio:4/3;">
                    <video id="camVideo" autoplay playsinline muted
                           style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                    {{-- Scan guide line --}}
                    <div style="position:absolute;top:50%;left:8%;right:8%;height:2px;background:rgba(124,58,237,.85);transform:translateY(-50%);border-radius:2px;box-shadow:0 0 10px rgba(124,58,237,.8);animation:scanLine 2s ease-in-out infinite;"></div>
                    {{-- Corner guides --}}
                    <div style="position:absolute;top:15%;left:8%;width:30px;height:30px;border-top:3px solid #7c3aed;border-left:3px solid #7c3aed;border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute;top:15%;right:8%;width:30px;height:30px;border-top:3px solid #7c3aed;border-right:3px solid #7c3aed;border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute;bottom:15%;left:8%;width:30px;height:30px;border-bottom:3px solid #7c3aed;border-left:3px solid #7c3aed;border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute;bottom:15%;right:8%;width:30px;height:30px;border-bottom:3px solid #7c3aed;border-right:3px solid #7c3aed;border-radius:0 0 4px 0;"></div>
                </div>

                {{-- Camera selector --}}
                <div style="margin-top:14px;display:flex;gap:10px;align-items:center;">
                    <label style="font-size:12px;font-weight:600;color:#666;white-space:nowrap;"><i class="bi bi-camera"></i> Camera:</label>
                    <select id="camDeviceSelect" class="form-control" style="border-radius:8px;border:2px solid #e0e6ed;font-size:13px;height:36px;padding:0 10px;flex:1;"></select>
                    <button id="switchCamBtn" type="button"
                            style="flex-shrink:0;height:36px;padding:0 14px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap;">
                        <i class="bi bi-arrow-repeat"></i> Switch
                    </button>
                </div>

                {{-- Manual override input --}}
                <div style="margin-top:12px;">
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="camManualInput" placeholder="Or type / paste barcode here…"
                               style="flex:1;border:2px solid #e0e6ed;border-radius:8px;padding:8px 12px;font-size:14px;font-family:monospace;">
                        <button id="camManualAddBtn" type="button"
                                style="flex-shrink:0;padding:0 18px;border-radius:8px;background:linear-gradient(135deg,#27ae60,#16a34a);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">
                            <i class="bi bi-plus-circle"></i> Add
                        </button>
                    </div>
                </div>

                {{-- Last detected --}}
                <div id="lastDetected" style="display:none;margin-top:14px;background:#f0fdf4;border:2px solid #86efac;border-radius:10px;padding:12px 16px;text-align:center;">
                    <div style="font-size:12px;color:#15803d;font-weight:600;margin-bottom:4px;"><i class="bi bi-check-circle-fill"></i> Detected!</div>
                    <div id="lastDetectedCode" style="font-size:18px;font-weight:800;color:#15803d;font-family:monospace;letter-spacing:2px;"></div>
                    <div style="font-size:11px;color:#888;margin-top:4px;">Adding to invoice…</div>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes scanLine {
        0%,100% { top:20%; }
        50%      { top:80%; }
    }
    </style>

@endsection

@section('scripts')
    @include('dashboard.pages.invoice-new.script.main')

    {{-- ZXing browser barcode decoder (supports 1D + QR) --}}
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.20.0/umd/index.min.js"></script>

    <script>
    /* ═══════════════════════════════════════════════════════════
       CAMERA BARCODE SCANNER
    ═══════════════════════════════════════════════════════════ */
    (function () {

        var modal       = document.getElementById('cameraScanModal');
        var video       = document.getElementById('camVideo');
        var statusEl    = document.getElementById('camStatus');
        var deviceSel   = document.getElementById('camDeviceSelect');
        var lastDiv     = document.getElementById('lastDetected');
        var lastCode    = document.getElementById('lastDetectedCode');
        var manualInput = document.getElementById('camManualInput');

        var codeReader  = null;
        var currentStream = null;
        var scanCooldown  = false;   // debounce duplicate scans
        var devices       = [];

        // ── Open modal ───────────────────────────────────────────
        document.getElementById('openCameraBtn').addEventListener('click', function () {
            modal.style.display = 'flex';
            initScanner();
        });

        // ── Close modal ──────────────────────────────────────────
        document.getElementById('closeCameraBtn').addEventListener('click', closeCamera);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeCamera();
        });

        // ── Switch camera ────────────────────────────────────────
        document.getElementById('switchCamBtn').addEventListener('click', function () {
            var sel = deviceSel.value;
            if (sel) startDecoding(sel);
        });

        // ── Manual add ───────────────────────────────────────────
        document.getElementById('camManualAddBtn').addEventListener('click', function () {
            var code = manualInput.value.trim();
            if (code) { triggerAdd(code); manualInput.value = ''; }
        });
        manualInput.addEventListener('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var code = this.value.trim();
                if (code) { triggerAdd(code); this.value = ''; }
            }
        });

        // ── Init scanner ─────────────────────────────────────────
        function initScanner() {
            statusEl.textContent = 'Requesting camera access…';
            lastDiv.style.display = 'none';

            if (typeof ZXing === 'undefined') {
                statusEl.textContent = '⚠ Scanner library not loaded. Use manual input below.';
                return;
            }

            codeReader = new ZXing.BrowserMultiFormatReader();

            codeReader.listVideoInputDevices().then(function (videoDevices) {
                devices = videoDevices;
                deviceSel.innerHTML = '';

                if (devices.length === 0) {
                    statusEl.textContent = '⚠ No camera found. Use manual input below.';
                    return;
                }

                devices.forEach(function (d, i) {
                    var opt = document.createElement('option');
                    opt.value = d.deviceId;
                    opt.textContent = d.label || ('Camera ' + (i + 1));
                    deviceSel.appendChild(opt);
                });

                // Prefer back camera on mobile
                var backCam = devices.find(function (d) {
                    return /back|rear|environment/i.test(d.label);
                });
                var chosen = backCam ? backCam.deviceId : devices[0].deviceId;
                deviceSel.value = chosen;
                startDecoding(chosen);

            }).catch(function (err) {
                statusEl.textContent = '⚠ Camera access denied: ' + err.message;
            });
        }

        // ── Start decoding from selected device ──────────────────
        function startDecoding(deviceId) {
            if (codeReader) codeReader.reset();
            statusEl.textContent = 'Point camera at barcode…';
            lastDiv.style.display = 'none';
            scanCooldown = false;

            codeReader.decodeFromVideoDevice(deviceId, video, function (result, err) {
                if (result && !scanCooldown) {
                    scanCooldown = true;
                    var code = result.getText();
                    onBarcodeDetected(code);
                    // Resume scanning after 2.5 s so user can scan next item
                    setTimeout(function () { scanCooldown = false; }, 2500);
                }
                // err is thrown on every frame that has no result — ignore
            });
        }

        // ── Barcode detected ─────────────────────────────────────
        function onBarcodeDetected(code) {
            // Visual feedback
            lastCode.textContent = code;
            lastDiv.style.display = 'block';
            statusEl.textContent = '✓ Scanned — adding to invoice…';

            // Beep (short 880 Hz tone)
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.2);
            } catch(e) {}

            triggerAdd(code);
        }

        // ── Put code in invoice input + trigger addProduct ───────
        function triggerAdd(code) {
            closeCamera();
            var inp = document.getElementById('product_id_input');
            if (inp) {
                inp.value = code;
                inp.focus();
                // Trigger addProduct defined in invoice-main.blade.php
                if (typeof addProduct === 'function') {
                    addProduct();
                } else {
                    document.getElementById('addProductBtn').click();
                }
            }
        }

        // ── Close & release camera ────────────────────────────────
        function closeCamera() {
            modal.style.display = 'none';
            if (codeReader) { codeReader.reset(); codeReader = null; }
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(function (t) { t.stop(); });
                video.srcObject = null;
            }
        }

    })();
    </script>
@endsection
