@extends('dashboard.layouts.master')

@section('content')

    <style>
        .branch-edit-page {
            background: #ecf0f5;
            padding: 15px;
        }

        .box-branch-edit {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: visible;
            border: none;
        }

        .box-branch-edit .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-branch-edit .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-branch-edit .box-header .box-title {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-branch-edit .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-branch-edit .box-header .box-tools {
            position: relative;
            z-index: 1;
        }

        .box-branch-edit .box-header .box-tools .btn {
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .box-branch-edit .box-header .box-tools .btn:hover {
            background: white;
            color: #667eea;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin: 30px 0 25px 0;
            padding: 18px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header i {
            font-size: 24px;
            color: #667eea;
            margin-right: 12px;
            background: rgba(102, 126, 234, 0.1);
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #667eea;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .text-danger {
            font-size: 12px;
            margin-top: 5px;
            display: block;
            font-weight: 600;
        }

        .help-block {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .checkbox {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .checkbox label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            background: white;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .checkbox label:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox label i {
            font-size: 18px;
        }

        .box-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            border-radius: 0 0 12px 12px;
            border-top: 2px solid #e8ecf7;
            display: flex;
            gap: 15px;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-default {
            background: white;
            color: #666;
            border: 2px solid #ddd;
        }

        .btn-default:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #333;
        }

        .required-indicator {
            color: #e74c3c;
            font-weight: 700;
            margin-left: 3px;
        }

        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Rent field highlight */
        .rent-field-wrap {
            background: linear-gradient(135deg, #fff8f0 0%, #fff 100%);
            border: 2px solid #e67e22;
            border-radius: 10px;
            padding: 18px 20px;
        }

        .rent-field-wrap label i { color: #e67e22 !important; }

        .rent-field-wrap .form-control:focus {
            border-color: #e67e22;
            box-shadow: 0 0 0 4px rgba(230,126,34,0.1);
        }

        .current-rent-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg,#fff3e0,#fff);
            border: 2px solid #e67e22; border-radius: 20px;
            padding: 4px 12px; font-size: 12px; font-weight: 700; color: #e67e22;
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .section-header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .box-footer { flex-direction: column; }
            .btn-lg { width: 100%; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .box-branch-edit { animation: fadeInUp 0.5s ease-out; }
    </style>

    <div class="branch-edit-page">
        <div class="box box-primary box-branch-edit">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-edit"></i> Edit Branch
                </h3>
                <div class="box-tools pull-right">
                    @can('view-branches')
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    @endcan
                </div>
            </div>

            <form action="{{ route('dashboard.branches.update', $branch) }}" method="POST" id="branchForm">
                @csrf
                @method('PUT')

                <div class="box-body" style="padding: 30px;">

                    <!-- Basic Information -->
                    <div class="section-header">
                        <i class="fa fa-info-circle"></i>
                        <h4>Basic Information</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-tag"></i>
                                    Branch Name (English)
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $branch->name) }}"
                                       placeholder="Enter branch name in English" required>
                                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-tag"></i>
                                    Branch Name (Arabic)
                                </label>
                                <input type="text" name="name_ar" class="form-control"
                                       value="{{ old('name_ar', $branch->name_ar) }}"
                                       placeholder="أدخل اسم الفرع بالعربي">
                                @error('name_ar')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-location-arrow"></i>
                                    Address
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address', $branch->address) }}"
                                       placeholder="Enter full address" required>
                                @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-map-marker"></i>
                                    City
                                </label>
                                <input type="text" name="city" class="form-control"
                                       value="{{ old('city', $branch->city) }}"
                                       placeholder="Enter city name">
                                @error('city')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="section-header">
                        <i class="fa fa-phone"></i>
                        <h4>Contact Information</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-phone"></i>
                                    Phone Number
                                </label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $branch->phone) }}"
                                       placeholder="+966 50 123 4567">
                                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-envelope"></i>
                                    Email
                                </label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $branch->email) }}"
                                       placeholder="branch@example.com">
                                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-user"></i>
                                    Branch Manager Name
                                </label>
                                <input type="text" name="manager_name" class="form-control"
                                       value="{{ old('manager_name', $branch->manager_name) }}"
                                       placeholder="Enter manager name">
                                @error('manager_name')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="section-header">
                        <i class="fa fa-clock-o"></i>
                        <h4>Working Hours</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-sun-o"></i>
                                    Opening Time
                                </label>
                                <input type="time" name="opening_time" class="form-control"
                                       value="{{ old('opening_time', $branch->opening_time ? date('H:i', strtotime($branch->opening_time)) : '') }}">
                                @error('opening_time')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-moon-o"></i>
                                    Closing Time
                                </label>
                                <input type="time" name="closing_time" class="form-control"
                                       value="{{ old('closing_time', $branch->closing_time ? date('H:i', strtotime($branch->closing_time)) : '') }}">
                                @error('closing_time')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="section-header">
                        <i class="fa fa-align-left"></i>
                        <h4>Additional Information</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-file-text"></i>
                                    Description
                                </label>
                                <textarea name="description" class="form-control" rows="4"
                                          placeholder="Enter branch description (optional)">{{ old('description', $branch->description) }}</textarea>
                                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="section-header">
                        <i class="fa fa-cog"></i>
                        <h4>Settings</h4>
                    </div>

                    <div class="row">
                        {{-- Monthly Rent Amount --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="rent-field-wrap">
                                    <label>
                                        <i class="fa fa-home"></i>
                                        Monthly Rent Amount (QAR)
                                    </label>
                                    <input type="number" name="rent_amount" class="form-control"
                                           value="{{ old('rent_amount', $branch->rent_amount ?? 0) }}"
                                           step="0.01" min="0" placeholder="0.00">
                                    @error('rent_amount')<span class="text-danger">{{ $message }}</span>@enderror
                                    @if($branch->rent_amount > 0)
                                        <span class="current-rent-badge">
                                            <i class="fa fa-tag"></i>
                                            Current: {{ number_format($branch->rent_amount, 2) }} QAR / month
                                        </span>
                                    @endif
                                    <span class="help-block">
                                        <i class="fa fa-info-circle"></i>
                                        Used to auto-fill rent expenses for this branch
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- placeholder --}}
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                                    <i class="fa fa-check-circle text-success"></i>
                                    Active Branch
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_main" value="1"
                                        {{ old('is_main', $branch->is_main) ? 'checked' : '' }}>
                                    <i class="fa fa-star text-warning"></i>
                                    Main Branch
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    @can('edit-branches')
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Save Changes
                        </button>
                    @endcan
                    @can('view-branches')
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-default btn-lg">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    @endcan
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.getElementById('branchForm').addEventListener('submit', function(e) {
            var submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving Changes...';
        });
    </script>
@endsection
