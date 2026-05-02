@extends('dashboard.layouts.master')

@section('title', 'Edit Role')

@section('content')

    <style>
        .role-edit-page {
            /*background: #ecf0f5;*/
            padding: 20px;
        }

        .box-role-edit {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-role-edit .box-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-role-edit .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-role-edit .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-role-edit .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-role-edit .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .box-role-edit .box-header .btn:hover {
            background: white;
            color: #f39c12;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .alert-system-role {
            background: linear-gradient(135deg, #fff3cd 0%, #fff 100%);
            border-left: 5px solid #f39c12;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-system-role i {
            font-size: 20px;
            margin-right: 10px;
            color: #f39c12;
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
        }

        .section-header .icon-box {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #f39c12;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-group label .required {
            color: #e74c3c;
            font-weight: 700;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
        }

        .form-control:disabled,
        .form-control:read-only {
            background: #f8f9fa;
            cursor: not-allowed;
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .checkbox-toggle {
            display: flex;
            align-items: center;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .checkbox-toggle:hover {
            border-color: #f39c12;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.1);
        }

        .checkbox-toggle input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
            accent-color: #f39c12;
        }

        .checkbox-toggle label {
            margin: 0;
            cursor: pointer;
            flex: 1;
            font-weight: 600;
            color: #333;
            margin-left: 10px;
        }

        .permissions-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .module-group {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border: 2px solid #ffe0b2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .module-group:hover {
            border-color: #f39c12;
            box-shadow: 0 3px 12px rgba(243, 156, 18, 0.15);
        }

        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ffe0b2;
        }

        .module-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #f39c12;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .module-header h5 i {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .select-all-btn {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
        }

        .select-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .permission-item:hover {
            border-color: #f39c12;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.1);
            transform: translateX(3px);
        }

        .permission-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
            accent-color: #f39c12;
        }

        .permission-item label {
            margin: 0;
            cursor: pointer;
            flex: 1;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-left: 10px;
        }

        .permission-item small {
            color: #999;
            font-size: 12px;
            display: block;
            margin-top: 3px;
            font-weight: normal;
        }

        .btn-submit-group {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
            margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-cancel {
            background: white;
            color: #666;
            border: 2px solid #ddd;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            margin: 0 10px;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #333;
            transform: translateY(-3px);
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

        .form-section, .permissions-section, .module-group {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-edit"></i> Edit Role
            <small>Update role details and permissions</small>
        </h1>
    </section>

    <div class="role-edit-page">
        <form action="{{ route('dashboard.roles.update', $role) }}" method="POST" id="roleForm">
            @csrf
            @method('PUT')

            <div class="box box-warning box-role-edit">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-pencil-square-o"></i> Edit Role: {{ $role->display_name }}
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Roles
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4><i class="fa fa-ban"></i> Validation Errors:</h4>
                            <ul style="margin-bottom: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($role->is_system)
                        <div class="alert-system-role">
                            <i class="fa fa-lock"></i>
                            <strong>System Role:</strong> This is a system role. The role name cannot be changed, but you can update the display name, description, and activity status.
                        </div>
                    @endif

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-edit"></i>
                            </div>
                            <h4>Basic Information</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        Role Name (System)
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control"
                                           value="{{ old('name', $role->name) }}"
                                           placeholder="e.g., branch-manager"
                                           {{ $role->is_system ? 'readonly' : '' }}
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        @if($role->is_system)
                                            System role name cannot be changed
                                        @else
                                            Lowercase letters, numbers, and dashes only (no spaces)
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">
                                        Display Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="display_name"
                                           id="display_name"
                                           class="form-control"
                                           value="{{ old('display_name', $role->display_name) }}"
                                           placeholder="e.g., Branch Manager"
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Human-readable name that will be shown in the interface
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description"
                                              id="description"
                                              class="form-control"
                                              rows="4"
                                              placeholder="Brief description of this role and its responsibilities...">{{ old('description', $role->description) }}</textarea>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Optional: Describe what this role is responsible for
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="checkbox-toggle">
                                    <input type="checkbox"
                                           name="is_active"
                                           id="is_active"
                                        {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
                                    <label for="is_active">
                                        <strong>Active Status</strong> - Role is active and can be assigned to users
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="permissions-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-key"></i>
                            </div>
                            <h4>Manage Permissions</h4>
                        </div>

                        @foreach($permissions as $module => $modulePermissions)
                            <div class="module-group">
                                <div class="module-header">
                                    <h5>
                                        <i class="fa fa-folder-o"></i>
                                        {{ ucwords(str_replace('-', ' ', $module)) }} Module
                                    </h5>
                                    <button type="button"
                                            class="select-all-btn"
                                            data-module="{{ $module }}">
                                        <i class="fa fa-check-square-o"></i> Select All
                                    </button>
                                </div>

                                <div class="row">
                                    @foreach($modulePermissions as $permission)
                                        <div class="col-md-6">
                                            <div class="permission-item">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       id="permission_{{ $permission->id }}"
                                                       value="{{ $permission->name }}"
                                                       data-module="{{ $module }}"
                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                <label for="permission_{{ $permission->id }}">
                                                    {{ $permission->display_name }}
                                                    @if($permission->description)
                                                        <small>{{ $permission->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="fa fa-save"></i> Update Role
                        </button>
                        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-cancel">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            function updateButtonState(moduleGroup) {
                var checkboxes = moduleGroup.find('input[type="checkbox"][data-module]');
                var total = checkboxes.length;
                var checked = checkboxes.filter(':checked').length;
                var button = moduleGroup.find('.select-all-btn');

                if (total === checked && total !== 0) {
                    button.html('<i class="fa fa-square-o"></i> Deselect All');
                } else {
                    button.html('<i class="fa fa-check-square-o"></i> Select All');
                }
            }

            // عند تحميل الصفحة (عشان الـ edit فيها صلاحيات جاهزة)
            $('.module-group').each(function () {
                updateButtonState($(this));
            });

            // عند الضغط على الزرار
            $('.select-all-btn').click(function () {

                var moduleGroup = $(this).closest('.module-group');
                var checkboxes = moduleGroup.find('input[type="checkbox"][data-module]');
                var total = checkboxes.length;
                var checked = checkboxes.filter(':checked').length;

                if (checked === total) {
                    checkboxes.iCheck('uncheck');
                } else {
                    checkboxes.iCheck('check');
                }

                updateButtonState(moduleGroup);
            });

            // لو المستخدم علم يدوي
            $('input[type="checkbox"][data-module]').on('ifChanged', function() {
                var moduleGroup = $(this).closest('.module-group');
                updateButtonState(moduleGroup);
            });

        });

    </script>
@endsection
