@extends('dashboard.layouts.master')

@section('title', 'System Settings')

@section('styles')
    <style>
        .settings-page {
            padding: 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
        }

        /* ═══════════════════════════════════════════════════════════
           🎨 MODERN HEADER
           ═══════════════════════════════════════════════════════════ */
        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 35px;
            border-radius: 16px 16px 0 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        }

        .settings-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .settings-header-content {
            position: relative;
            z-index: 1;
        }

        .settings-header h1 {
            margin: 0 0 10px 0;
            font-size: 36px;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .settings-header h1 i {
            font-size: 40px;
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 14px;
            backdrop-filter: blur(10px);
        }

        .settings-header p {
            margin: 0;
            opacity: 0.95;
            font-size: 16px;
        }

        /* ═══════════════════════════════════════════════════════════
           📑 MODERN TABS
           ═══════════════════════════════════════════════════════════ */
        .settings-box {
            background: white;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .settings-tabs {
            display: flex;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-bottom: 3px solid #e8ecf7;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .settings-tabs .tab {
            flex: 1;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-right: 1px solid #e8ecf7;
            position: relative;
            background: transparent;
        }

        .settings-tabs .tab:last-child {
            border-right: none;
        }

        .settings-tabs .tab::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .settings-tabs .tab:hover {
            background: linear-gradient(135deg, #f0f3ff 0%, #fff 100%);
        }

        .settings-tabs .tab.active {
            background: white;
        }

        .settings-tabs .tab.active::after {
            transform: scaleX(1);
        }

        .settings-tabs .tab .icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
            color: #95a5a6;
            transition: all 0.3s ease;
        }

        .settings-tabs .tab.active .icon {
            color: #667eea;
            transform: scale(1.15);
        }

        .settings-tabs .tab .label {
            font-size: 14px;
            font-weight: 700;
            color: #7f8c8d;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .settings-tabs .tab.active .label {
            color: #667eea;
        }

        /* ═══════════════════════════════════════════════════════════
           📝 TAB CONTENT
           ═══════════════════════════════════════════════════════════ */
        .tab-content {
            padding: 40px;
        }

        .tab-pane {
            display: none;
            animation: fadeInUp 0.4s ease-out;
        }

        .tab-pane.active {
            display: block;
        }

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

        /* ═══════════════════════════════════════════════════════════
           🎯 SECTION HEADER
           ═══════════════════════════════════════════════════════════ */
        .section-header {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 35px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }

        .section-header h4 {
            margin: 0 0 8px 0;
            color: #2c3e50;
            font-size: 22px;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header h4 i {
            color: #667eea;
            font-size: 26px;
        }

        .section-header p {
            margin: 0;
            color: #7f8c8d;
            font-size: 14px;
            padding-left: 38px;
        }

        /* ═══════════════════════════════════════════════════════════
           📋 FORM GROUPS
           ═══════════════════════════════════════════════════════════ */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 700;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group label i {
            color: #667eea;
            font-size: 16px;
        }

        .form-group label .required {
            color: #e74c3c;
            margin-left: 3px;
        }

        .form-control {
            border: 2px solid #e8ecf7;
            border-radius: 10px !important;
            padding: 14px 18px;
            transition: all 0.3s ease;
            font-size: 14px;
            background: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
            background: white;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .help-text {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .help-text i {
            font-size: 13px;
        }

        /* ═══════════════════════════════════════════════════════════
           ☑️ MODERN CHECKBOX
           ═══════════════════════════════════════════════════════════ */
        .modern-checkbox {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e8ecf7;
            border-radius: 12px;
            padding: 20px 25px;
            margin: 15px 0;
            transition: all 0.3s ease;
        }

        .modern-checkbox:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 15px;
            margin: 0;
        }

        .checkbox-label input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-label span {
            font-weight: 700;
            color: #2c3e50;
            font-size: 15px;
        }

        /* ═══════════════════════════════════════════════════════════
           📁 FILE UPLOAD
           ═══════════════════════════════════════════════════════════ */
        .file-upload-wrapper {
            position: relative;
            border: 3px dashed #e8ecf7;
            border-radius: 12px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            cursor: pointer;
        }

        .file-upload-wrapper:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f3ff 0%, #fff 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        .file-upload-wrapper .icon {
            font-size: 56px;
            color: #667eea;
            margin-bottom: 15px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .file-upload-wrapper:hover .icon {
            transform: scale(1.1) translateY(-5px);
        }

        .file-upload-wrapper .text {
            color: #7f8c8d;
            font-size: 15px;
            line-height: 1.6;
        }

        .file-upload-wrapper .text strong {
            color: #667eea;
            font-weight: 800;
            font-size: 16px;
        }

        /* ═══════════════════════════════════════════════════════════
           🖼️ IMAGE PREVIEW
           ═══════════════════════════════════════════════════════════ */
        .image-preview {
            margin-top: 20px;
            padding: 20px;
            border: 2px solid #e8ecf7;
            border-radius: 12px;
            background: white;
            text-align: center;
            transition: all 0.3s ease;
        }

        .image-preview:hover {
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        }

        .image-preview img {
            max-width: 100%;
            max-height: 180px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .image-preview img:hover {
            transform: scale(1.05);
        }

        .image-preview .info {
            margin-top: 15px;
            font-size: 14px;
            color: #27ae60;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* ═══════════════════════════════════════════════════════════
           💾 SAVE BUTTON
           ═══════════════════════════════════════════════════════════ */
        .btn-save {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 16px 50px;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 800;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
            cursor: pointer;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-save:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(39, 174, 96, 0.5);
            color: white;
        }

        .btn-save:active {
            transform: translateY(-2px);
        }

        .btn-save i {
            font-size: 20px;
        }

        /* ═══════════════════════════════════════════════════════════
           📱 RESPONSIVE
           ═══════════════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .settings-header h1 {
                font-size: 28px;
            }

            .settings-tabs {
                flex-direction: column;
            }

            .settings-tabs .tab {
                border-right: none;
                border-bottom: 1px solid #e8ecf7;
            }

            .tab-content {
                padding: 25px;
            }

            .btn-save {
                position: static;
                width: 100%;
                margin-top: 30px;
                border-radius: 10px;
            }

            .section-header {
                padding: 20px;
            }

            .section-header h4 {
                font-size: 18px;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           ✨ ANIMATIONS
           ═══════════════════════════════════════════════════════════ */
        .settings-page > * {
            animation: slideInUp 0.5s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop

@section('content')

    <section class="content-header">
        <h1>
            <i class="bi bi-gear-fill"></i> System Settings
            <small>Configure your system</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <div class="settings-page">
        {{-- Modern Header --}}
        <div class="settings-header">
            <div class="settings-header-content">
                <h1>
                    <i class="bi bi-sliders"></i>
                    System Configuration
                </h1>
                <p>Manage and customize your system settings, branding, and preferences</p>
            </div>
        </div>

        {{-- Settings Box --}}
        <div class="settings-box">
            @include('dashboard.partials._errors')

            {{-- Modern Tabs --}}
            <div class="settings-tabs">
                <div class="tab active" data-tab="system">
                    <span class="icon"><i class="bi bi-pc-display"></i></span>
                    <span class="label">System Info</span>
                </div>
                <div class="tab" data-tab="invoice">
                    <span class="icon"><i class="bi bi-receipt"></i></span>
                    <span class="label">Invoice Settings</span>
                </div>
                <div class="tab" data-tab="eyetest">
                    <span class="icon"><i class="bi bi-eye"></i></span>
                    <span class="label">Eye Test Settings</span>
                </div>
                <div class="tab" data-tab="whatsapp">
                    <span class="icon"><i class="bi bi-whatsapp"></i></span>
                    <span class="label">WhatsApp Settings</span>
                </div>
            </div>

            <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="tab-content">
                    {{-- ═══════════════════════════════════════════════════════════
                        TAB 1: SYSTEM INFO
                        ═══════════════════════════════════════════════════════════ --}}
                    <div class="tab-pane active" id="system">
                        <div class="section-header">
                            <h4>
                                <i class="bi bi-pc-display"></i> System Information
                            </h4>
                            <p>Configure your system name, status, and branding</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="system_name">
                                        <i class="bi bi-building"></i> System Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="system_name"
                                           id="system_name"
                                           class="form-control"
                                           placeholder="Enter system name"
                                           value="{{ Settings::get('system_name') }}"
                                           required>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> This name will appear throughout the system
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-power"></i> System Status
                                    </label>
                                    <div class="modern-checkbox">
                                        <label class="checkbox-label">
                                            <input type="checkbox"
                                                   name="system_status"
                                                   id="system_status"
                                                   value="maintenance"
                                                {{ Settings::get('system_status') == 'maintenance' ? 'checked' : '' }}>
                                            <span>Enable Maintenance Mode</span>
                                        </label>
                                    </div>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Check to put system in maintenance mode
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-image"></i> System Logo
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="system_logo" id="system_logo" accept="image/*">
                                        <div class="icon"><i class="bi bi-cloud-upload"></i></div>
                                        <div class="text">
                                            <strong>Click to upload</strong> or drag and drop<br>
                                            <small>PNG, JPG or SVG (max 2MB)</small>
                                        </div>
                                    </div>
                                    @if(!empty(Settings::get('system_logo')))
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/' . Settings::get('system_logo')) }}" alt="Logo">
                                            <div class="info">
                                                <i class="bi bi-check-circle-fill"></i> Current logo
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-app-indicator"></i> System Icon
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="system_icon" id="system_icon" accept="image/*">
                                        <div class="icon"><i class="bi bi-cloud-upload"></i></div>
                                        <div class="text">
                                            <strong>Click to upload</strong> or drag and drop<br>
                                            <small>PNG or ICO (max 1MB)</small>
                                        </div>
                                    </div>
                                    @if(!empty(Settings::get('system_icon')))
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/' . Settings::get('system_icon')) }}" alt="Icon">
                                            <div class="info">
                                                <i class="bi bi-check-circle-fill"></i> Current icon
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════════
                        TAB 2: INVOICE SETTINGS
                        ═══════════════════════════════════════════════════════════ --}}
                    <div class="tab-pane" id="invoice">
                        <div class="section-header">
                            <h4>
                                <i class="bi bi-receipt"></i> Invoice Configuration
                            </h4>
                            <p>Customize your invoice details and footer</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_name">
                                        <i class="bi bi-card-heading"></i> Invoice Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="invoice_name"
                                           id="invoice_name"
                                           class="form-control"
                                           placeholder="Enter invoice name"
                                           value="{{ Settings::get('invoice_name') }}"
                                           required>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> This will appear on all invoices
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_address">
                                        <i class="bi bi-geo-alt"></i> Address
                                    </label>
                                    <input type="text"
                                           name="invoice_address"
                                           id="invoice_address"
                                           class="form-control"
                                           placeholder="Enter business address"
                                           value="{{ Settings::get('invoice_address') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Your business location
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_phone">
                                        <i class="bi bi-telephone"></i> Phone
                                    </label>
                                    <input type="text"
                                           name="invoice_phone"
                                           id="invoice_phone"
                                           class="form-control"
                                           placeholder="Enter phone number"
                                           value="{{ Settings::get('invoice_phone') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Contact phone number
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_email">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email"
                                           name="invoice_email"
                                           id="invoice_email"
                                           class="form-control"
                                           placeholder="Enter email address"
                                           value="{{ Settings::get('invoice_email') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Contact email address
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="invoice_footer">
                                <i class="bi bi-text-paragraph"></i> Invoice Footer
                            </label>
                            <textarea name="invoice_footer"
                                      id="invoice_footer"
                                      class="form-control"
                                      placeholder="Enter footer text (terms, thank you message, etc.)">{{ Settings::get('invoice_footer') }}</textarea>
                            <small class="help-text">
                                <i class="bi bi-info-circle"></i> This text will appear at the bottom of invoices
                            </small>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════════
                        TAB 3: EYE TEST SETTINGS
                        ═══════════════════════════════════════════════════════════ --}}
                    <div class="tab-pane" id="eyetest">
                        <div class="section-header">
                            <h4>
                                <i class="bi bi-eye"></i> Eye Test Configuration
                            </h4>
                            <p>Customize your eye test form details</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="eye_test_name">
                                        <i class="bi bi-card-heading"></i> Eye Test Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="eye_test_name"
                                           id="eye_test_name"
                                           class="form-control"
                                           placeholder="Enter eye test name"
                                           value="{{ Settings::get('eye_test_name') }}"
                                           required>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> This will appear on all eye test forms
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="eye_test_address">
                                        <i class="bi bi-geo-alt"></i> Address
                                    </label>
                                    <input type="text"
                                           name="eye_test_address"
                                           id="eye_test_address"
                                           class="form-control"
                                           placeholder="Enter clinic address"
                                           value="{{ Settings::get('eye_test_address') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Clinic location
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="eye_test_phone">
                                        <i class="bi bi-telephone"></i> Phone
                                    </label>
                                    <input type="text"
                                           name="eye_test_phone"
                                           id="eye_test_phone"
                                           class="form-control"
                                           placeholder="Enter phone number"
                                           value="{{ Settings::get('eye_test_phone') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Contact phone number
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="eye_test_email">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <input type="email"
                                           name="eye_test_email"
                                           id="eye_test_email"
                                           class="form-control"
                                           placeholder="Enter email address"
                                           value="{{ Settings::get('eye_test_email') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Contact email address
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="eye_test_footer">
                                <i class="bi bi-text-paragraph"></i> Eye Test Footer
                            </label>
                            <textarea name="eye_test_footer"
                                      id="eye_test_footer"
                                      class="form-control"
                                      placeholder="Enter footer text (disclaimers, instructions, etc.)">{{ Settings::get('eye_test_footer') }}</textarea>
                            <small class="help-text">
                                <i class="bi bi-info-circle"></i> This text will appear at the bottom of eye test forms
                            </small>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════════
                        TAB 4: WHATSAPP SETTINGS
                        ═══════════════════════════════════════════════════════════ --}}
                    <div class="tab-pane" id="whatsapp">
                        <div class="section-header">
                            <h4>
                                <i class="bi bi-whatsapp"></i> WhatsApp Integration
                            </h4>
                            <p>Configure WhatsApp API settings for automated messages</p>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-toggle-on"></i> Enable WhatsApp
                                    </label>
                                    <div class="modern-checkbox">
                                        <label class="checkbox-label">
                                            <input type="checkbox"
                                                   name="send_whatsapp"
                                                   id="send_whatsapp"
                                                   value="1"
                                                {{ Settings::get('send_whatsapp') == 1 ? 'checked' : '' }}>
                                            <span>Enable WhatsApp Messaging</span>
                                        </label>
                                    </div>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Enable or disable WhatsApp message sending
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp_device_id">
                                        <i class="bi bi-phone"></i> Device ID
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="whatsapp_device_id"
                                           id="whatsapp_device_id"
                                           class="form-control"
                                           placeholder="Enter WhatsApp device ID"
                                           value="{{ Settings::get('whatsapp_device_id') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Your WhatsApp API device identifier
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp_username">
                                        <i class="bi bi-person-badge"></i> Username
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="whatsapp_username"
                                           id="whatsapp_username"
                                           class="form-control"
                                           placeholder="Enter WhatsApp API username"
                                           value="{{ Settings::get('whatsapp_username') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Your WhatsApp API username
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="whatsapp_url">
                                        <i class="bi bi-link-45deg"></i> API URL
                                        <span class="required">*</span>
                                    </label>
                                    <input type="url"
                                           name="whatsapp_url"
                                           id="whatsapp_url"
                                           class="form-control"
                                           placeholder="Enter WhatsApp API endpoint URL"
                                           value="{{ Settings::get('whatsapp_url') }}">
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Full API endpoint URL (e.g., https://api.whatsapp.com/v1/send)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="alert" style="background: linear-gradient(135deg, #fff3cd 0%, #fff 100%); border-left: 5px solid #f39c12; padding: 20px; border-radius: 10px; margin-top: 20px;">
                            <h5 style="color: #f39c12; margin: 0 0 10px 0; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-exclamation-triangle-fill"></i> Important
                            </h5>
                            <p style="margin: 0; color: #7f8c8d; line-height: 1.6;">
                                Make sure to get these credentials from your WhatsApp API provider. Enable WhatsApp only after configuring all fields correctly to avoid message delivery failures.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle-fill"></i> Save Settings
                </button>
            </form>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            // ═══════════════════════════════════════════════════════════
            // ✅ SUCCESS MESSAGE
            // ═══════════════════════════════════════════════════════════
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '✅ Settings Saved!',
                html: '<p style="font-size: 16px; color: #666;">{{ session('success') }}</p>',
                confirmButtonColor: '#27ae60',
                confirmButtonText: '<i class="bi bi-check-circle"></i> Great!',
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
            @endif

            // ═══════════════════════════════════════════════════════════
            // ❌ ERROR MESSAGE
            // ═══════════════════════════════════════════════════════════
            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: '❌ Validation Error',
                html: '<div style="text-align: left;">@foreach($errors->all() as $error)<div style="padding: 5px 0;">• {{ $error }}</div>@endforeach</div>',
                confirmButtonColor: '#e74c3c',
                confirmButtonText: '<i class="bi bi-x-circle"></i> OK'
            });
            @endif

            // ═══════════════════════════════════════════════════════════
            // 📑 TAB SWITCHING
            // ═══════════════════════════════════════════════════════════
            $('.settings-tabs .tab').click(function() {
                var tabId = $(this).data('tab');

                // Update active tab
                $('.settings-tabs .tab').removeClass('active');
                $(this).addClass('active');

                // Update active pane
                $('.tab-pane').removeClass('active');
                $('#' + tabId).addClass('active');
            });

            // ═══════════════════════════════════════════════════════════
            // 📁 FILE UPLOAD PREVIEW
            // ═══════════════════════════════════════════════════════════
            $('input[type="file"]').change(function() {
                var input = this;
                var wrapper = $(this).closest('.file-upload-wrapper');
                var fileName = input.files && input.files[0] ? input.files[0].name : 'No file selected';

                if (input.files && input.files[0]) {
                    // Update text with file name
                    wrapper.find('.text').html(
                        '<strong style="color: #27ae60;">' + fileName + '</strong><br>' +
                        '<small><i class="bi bi-check-circle-fill"></i> File selected successfully</small>'
                    );

                    // Change icon
                    wrapper.find('.icon').html('<i class="bi bi-check-circle-fill"></i>');
                    wrapper.find('.icon').css('color', '#27ae60');
                }
            });

            // ═══════════════════════════════════════════════════════════
            // 💾 FORM SUBMISSION CONFIRMATION
            // ═══════════════════════════════════════════════════════════
            $('form').submit(function(e) {
                e.preventDefault();

                const form = this;

                Swal.fire({
                    title: '💾 Save Settings?',
                    html: '<p style="font-size: 15px; color: #666;">Do you want to save all settings changes?</p>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: '<i class="bi bi-check-circle"></i> Yes, Save',
                    cancelButtonText: '<i class="bi bi-x-circle"></i> Cancel',
                    reverseButtons: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: '⏳ Saving...',
                            html: '<p style="color: #666;">Please wait while we save your settings</p>',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        form.submit();
                    }
                });

                return false;
            });
        });
    </script>
@endsection
