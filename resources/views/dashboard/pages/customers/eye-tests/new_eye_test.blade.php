@extends('dashboard.layouts.master')

@section('title', 'Add Eye Test')

@section('content')

    <style>
        /* Modern Eye Test Page */
        .eye-test-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Header */
        .eye-test-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(155, 89, 182, 0.3);
            position: relative;
            overflow: hidden;
        }

        .eye-test-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .eye-test-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        /* Cards */
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header-custom {
            padding: 15px 20px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
        }

        .card-header-purple { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border: none; }
        .card-header-blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; }
        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }

        .card-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 20px;
        }

        .card-body-custom {
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
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155,89,182,0.1);
        }

        .form-control[readonly],
        .form-control[disabled] {
            background: #f8f9fa;
            cursor: not-allowed;
        }

        /* Measurement Table */
        .measurement-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .measurement-row {
            display: grid;
            grid-template-columns: 30px 30px 120px 100px 120px 30px 30px;
            gap: 10px;
            align-items: center;
        }

        .measurement-label {
            grid-column: 4;
            text-align: center;
            font-weight: 700;
            color: #2c3e50;
            font-size: 14px;
        }

        .measurement-header {
            display: grid;
            grid-template-columns: 30px 30px 120px 100px 120px 30px 30px;
            gap: 10px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 12px;
            color: #7f8c8d;
            text-align: center;
        }

        .sign-radio {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }

        /* Radio Modern */
        input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #9b59b6;
        }

        /* Buttons */
        .btn-modern {
            padding: 7px 25px;
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

        .btn-success-modern {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-light {
            background: white;
            color: #9b59b6;
            border: 2px solid white;
        }

        /* Glasses Type Radio */
        .glasses-type-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .glasses-type-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .glasses-type-item input[type="radio"] {
            width: 18px;
            height: 18px;
        }

        .glasses-type-item label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        /* Modern File Upload */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-input {
            display: none !important;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 3px dashed #e0e6ed;
            border-radius: 12px;
            background: #f8f9ff;
            cursor: pointer;
            transition: all 0.3s;
            margin: 0;
        }

        .file-upload-label:hover {
            border-color: #f39c12;
            background: #fff;
            transform: translateY(-2px);
        }

        .file-upload-icon {
            font-size: 48px;
            color: #f39c12;
            margin-bottom: 15px;
        }

        .file-upload-text {
            text-align: center;
        }

        .file-upload-title {
            display: block;
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .file-upload-hint {
            display: block;
            color: #7f8c8d;
            font-size: 13px;
        }

        .file-upload-name {
            margin-top: 15px;
            padding: 10px 15px;
            background: #e8f5e9;
            border-radius: 8px;
            color: #27ae60;
            font-weight: 600;
            text-align: center;
            display: none;
        }

        .file-upload-name.active {
            display: block;
        }

        /* Bootstrap Select Fix */
        .bootstrap-select .dropdown-menu {
            max-height: 250px !important;
            overflow-y: auto;
        }

        .bootstrap-select.btn-group .dropdown-menu {
            position: absolute !important;
            z-index: 9999 !important;
        }

        .bootstrap-select button {
            border: 2px solid #e0e6ed !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
        }

        .bootstrap-select button:focus {
            border-color: #27ae60 !important;
            box-shadow: 0 0 0 3px rgba(39,174,96,0.1) !important;
        }

        /* Modern Bootstrap Select Button */
        .btn-modern-select {
            background: white !important;
            border: 2px solid #e0e6ed !important;
            border-radius: 8px !important;
            color: #2c3e50 !important;
            font-size: 14px !important;
            text-align: left !important;
            padding: 10px 15px !important;
            min-height: 42px !important;
        }

        .btn-modern-select:hover,
        .btn-modern-select:focus,
        .btn-modern-select:active {
            background: white !important;
            border-color: #27ae60 !important;
            box-shadow: 0 0 0 3px rgba(39,174,96,0.1) !important;
            color: #2c3e50 !important;
        }

        /* Dropdown menu styling */
        .bootstrap-select .dropdown-menu li a {
            padding: 10px 15px !important;
            font-size: 14px !important;
            transition: all 0.2s !important;
        }

        .bootstrap-select .dropdown-menu li a:hover {
            background: #f8f9ff !important;
            color: #27ae60 !important;
        }

        .bootstrap-select .dropdown-menu li.selected a {
            background: #e8f5e9 !important;
            color: #27ae60 !important;
            font-weight: 600 !important;
        }

        /* Search box styling */
        .bootstrap-select .bs-searchbox input {
            border: 2px solid #e0e6ed !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            margin-bottom: 10px !important;
        }

        .bootstrap-select .bs-searchbox input:focus {
            border-color: #27ae60 !important;
            box-shadow: 0 0 0 3px rgba(39,174,96,0.1) !important;
            outline: none !important;
        }

        /* Checkmark for selected items */
        .bootstrap-select .dropdown-menu li.selected a span.check-mark {
            color: #27ae60 !important;
        }

        /* No results text */
        .bootstrap-select .no-results {
            padding: 10px 15px !important;
            color: #999 !important;
            font-style: italic !important;
        }
    </style>

    <div class="eye-test-page">

        <!-- Header -->
        <div class="eye-test-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-eye"></i> Add New Eye Test</h1>
                    <p style="margin: 0; opacity: 0.9; position: relative; z-index: 1;">
                        Customer: <strong>{{ $customer->english_name }}</strong> ({{ $customer->customer_id }})
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.show-customer', $customer->id) }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @include('dashboard.partials._errors')

        <form action="{{ route('dashboard.new-eye-test', ['id' => $customer->customer_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="customerId" id="customerId" value="{{ $customer->customer_id }}">
            <input type="hidden" name="doctorId" id="doctorId" value="">

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">

                    <!-- Visit Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-blue">
                            <div class="card-title-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Visit Information</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="bi bi-hash"></i> Visit Number</label>
                                        <input type="text" class="form-control" name="visitNumber" value="{{ $visits }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="bi bi-calendar-date"></i> Date</label>
                                        <input type="date" class="form-control" name="visitDate" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="bi bi-clock"></i> Time</label>
                                        <input type="text" class="form-control" name="visitTime" value="<?php echo date('H:i:s'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-green">
                            <div class="card-title-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Doctor Information</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="bi bi-upc-scan"></i> Doctor ID</label>
                                        <input type="text" class="form-control" name="doctor_id" id="doctorIdInput" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="bi bi-person"></i> Doctor Name</label>
                                        <input type="text" class="form-control" name="doctor_name" id="doctorName">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-success-modern btn-modern" data-target="#DoctorModal" data-toggle="modal">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-purple">
                            <div class="card-title-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Customer Information</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="bi bi-upc-scan"></i> Customer ID</label>
                                        <input type="text" class="form-control" name="customer_id" value="{{ $customer->customer_id }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><i class="bi bi-person"></i> Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ $customer->english_name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachment Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-orange">
                            <div class="card-title-icon">
                                <i class="bi bi-paperclip"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Attachment</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="file-upload-wrapper">
                                <input type="file" class="file-upload-input" name="attachment" id="fileUpload" accept=".pdf,.jpg,.jpeg,.png">
                                <label for="fileUpload" class="file-upload-label">
                                    <div class="file-upload-icon">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <div class="file-upload-text">
                                        <span class="file-upload-title">Choose a file or drag it here</span>
                                        <span class="file-upload-hint">PDF, JPG, PNG (Max 10MB)</span>
                                    </div>
                                </label>
                                <div class="file-upload-name" id="fileName"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-blue">
                            <div class="card-title-icon">
                                <i class="bi bi-sticky"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Remarks</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="form-group" style="margin: 0;">
                                <textarea class="form-control" name="remarks" rows="4" placeholder="Enter any remarks"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <!-- Diagnosis Card - MOVED TO TOP -->
                    <div class="test-card" style="min-height: 450px;">
                        <div class="card-header-custom card-header-green">
                            <div class="card-title-icon">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Diagnosis</h3>
                        </div>
                        <div class="card-body-custom" style="padding-bottom: 80px;">
                            <div class="row">
                                <div class="col-md-12" style="">
                                    <div class="form-group">
                                        <label><i class="bi bi-eye"></i> Right Eye</label>
                                        <select class="form-control selectpicker" name="rightDiagnosis[]" multiple data-live-search="true" data-size="5" data-dropup-auto="false" data-style="btn-modern-select">
                                            <option value="1">Astigmatism-Irregular</option>
                                            <option value="2">Astigmatism-Regular</option>
                                            <option value="3">Astigmatism-Unspecified</option>
                                            <option value="4">Hypermetropia</option>
                                            <option value="5">Myopia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" style="margin: 0;">
                                        <label><i class="bi bi-eye"></i> Left Eye</label>
                                        <select class="form-control selectpicker" name="leftDiagnosis[]" multiple data-live-search="true" data-size="5" data-dropup-auto="false" data-style="btn-modern-select">
                                            <option value="1">Astigmatism-Irregular</option>
                                            <option value="2">Astigmatism-Regular</option>
                                            <option value="3">Astigmatism-Unspecified</option>
                                            <option value="4">Hypermetropia</option>
                                            <option value="5">Myopia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Glasses Type Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-orange">
                            <div class="card-title-icon">
                                <i class="bi bi-eyeglasses"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Glasses Type</h3>
                        </div>
                        <div class="card-body-custom">
                            <div class="glasses-type-group">
                                <div class="glasses-type-item">
                                    <input type="radio" name="Glasses_Type" id="contactLens" value="contactLens">
                                    <label for="contactLens">Contact Lens</label>
                                </div>
                                <div class="glasses-type-item">
                                    <input type="radio" name="Glasses_Type" id="Distance" value="Distance" checked>
                                    <label for="Distance">Distance</label>
                                </div>
                                <div class="glasses-type-item">
                                    <input type="radio" name="Glasses_Type" id="Intermediate" value="Intermediate">
                                    <label for="Intermediate">Intermediate</label>
                                </div>
                                <div class="glasses-type-item">
                                    <input type="radio" name="Glasses_Type" id="Reading" value="Reading">
                                    <label for="Reading">Reading</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Measurement Card -->
                    <div class="test-card">
                        <div class="card-header-custom card-header-purple">
                            <div class="card-title-icon">
                                <i class="bi bi-rulers"></i>
                            </div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">Measurement</h3>
                        </div>
                        <div class="card-body-custom">

                            <!-- Header -->
                            <div class="measurement-header">
                                <div>+</div>
                                <div>-</div>
                                <div>Right</div>
                                <div></div>
                                <div>Left</div>
                                <div>+</div>
                                <div>-</div>
                            </div>

                            <div class="measurement-grid">

                                <!-- Sph Row -->
                                <div class="measurement-row">
                                    <input type="radio" name="SphRightSign" value="+" class="sign-radio">
                                    <input type="radio" name="SphRightSign" value="-" class="sign-radio" checked>
                                    <select name="SphRight" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 25; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="measurement-label">Sph</div>
                                    <select name="SphLeft" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 25; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="radio" name="SphLeftSign" value="+" class="sign-radio">
                                    <input type="radio" name="SphLeftSign" value="-" class="sign-radio" checked>
                                </div>

                                <!-- Cyl Row -->
                                <div class="measurement-row">
                                    <input type="radio" name="CylRightSign" value="+" class="sign-radio">
                                    <input type="radio" name="CylRightSign" value="-" class="sign-radio" checked>
                                    <select name="CylRight" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 25; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="measurement-label">Cyl</div>
                                    <select name="CylLeft" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 25; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="radio" name="CylLeftSign" value="+" class="sign-radio">
                                    <input type="radio" name="CylLeftSign" value="-" class="sign-radio" checked>
                                </div>

                                <!-- Axis Row -->
                                <div class="measurement-row">
                                    <div></div>
                                    <div></div>
                                    <input type="number" class="form-control" name="AxisRight" value="{{ old('AxisRight') }}" placeholder="0">
                                    <div class="measurement-label">Axis</div>
                                    <input type="number" class="form-control" name="AxisLeft" value="{{ old('AxisLeft') }}" placeholder="0">
                                    <div></div>
                                    <div></div>
                                </div>

                                <!-- Addition Row -->
                                <div class="measurement-row">
                                    <div></div>
                                    <div></div>
                                    <select name="AdditionRight" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 4; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="measurement-label">Addition</div>
                                    <select name="AdditionLeft" class="form-control">
                                        <?php
                                        for ($x = 0; $x <= 4; $x += 0.25) {
                                            $xValue = number_format($x, 2);
                                            echo "<option value='$xValue'>$xValue</option>";
                                        }
                                        ?>
                                    </select>
                                    <div></div>
                                    <div></div>
                                </div>

                                <!-- PD Row -->
                                <div class="measurement-row">
                                    <div></div>
                                    <div></div>
                                    <input type="number" class="form-control" name="PDRight" value="{{ old('PDRight') }}" placeholder="0">
                                    <div class="measurement-label">PD +</div>
                                    <input type="number" class="form-control" name="PDLeft" value="{{ old('PDLeft') }}" placeholder="0">
                                    <div></div>
                                    <div></div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Button -->
            <div class="test-card">
                <div style="padding: 20px; background: #f8f9fa; border-top: 2px solid #e0e6ed; text-align: center;">
                    <button type="submit" class="btn btn-success-modern btn-modern btn-lg">
                        <i class="bi bi-check-circle"></i> Save Eye Test
                    </button>
                </div>
            </div>

        </form>

    </div>

    <!-- Doctor Modal -->
    <div id="DoctorModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                    <h3 style="margin: 0;">Select Doctor</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover">
                        <thead style="background: #2c3e50; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->code }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm select-doctor-btn"
                                            data-id="{{ $doctor->id }}"
                                            data-code="{{ $doctor->code }}"
                                            data-name="{{ $doctor->name }}">
                                        <i class="bi bi-check"></i> Select
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Select Doctor
            $('.select-doctor-btn').on('click', function() {
                var doctorId = $(this).data('id');
                var doctorCode = $(this).data('code');
                var doctorName = $(this).data('name');

                $('#doctorIdInput').val(doctorCode);
                $('#doctorName').val(doctorName);
                $('#doctorId').val(doctorCode);

                $('#DoctorModal').modal('hide');
            });

            // File Upload Display
            $('#fileUpload').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    $('#fileName').text('Selected: ' + fileName).addClass('active');
                } else {
                    $('#fileName').removeClass('active');
                }
            });
        });
    </script>
@endsection
