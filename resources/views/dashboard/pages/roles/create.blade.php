@extends('dashboard.layouts.master')

@section('title', 'Create New Role')

@section('content')

    <style>
        .role-create-page {
            /*background: #ecf0f5;*/
            padding: 20px;
        }

        .box-role-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-role-create .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-role-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-role-create .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-role-create .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-role-create .box-header .btn {
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

        .box-role-create .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
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
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e8ecf7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .module-group:hover {
            border-color: #667eea;
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.15);
        }

        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
        }

        .module-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .module-header h5 i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .select-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
            transform: translateX(3px);
        }

        .permission-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
            accent-color: #667eea;
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
            <i class="fa fa-shield"></i> Create New Role
            <small>Add a new role to the system</small>
        </h1>
    </section>

    <div class="role-create-page">
        <form action="{{ route('dashboard.roles.store') }}" method="POST" id="roleForm">
            @csrf

            <div class="box box-primary box-role-create">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus-circle"></i> Role Information
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
                                           value="{{ old('name') }}"
                                           placeholder="e.g., branch-manager"
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Lowercase letters, numbers, and dashes only (no spaces)
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
                                           value="{{ old('display_name') }}"
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
                                              placeholder="Brief description of this role and its responsibilities...">{{ old('description') }}</textarea>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Optional: Describe what this role is responsible for
                                    </p>
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
                            <h4>Assign Permissions</h4>
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
                                                    {{ old('permissions') && in_array($permission->name, old('permissions')) ? 'checked' : '' }}>
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
                            <i class="fa fa-save"></i> Create Role
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
            // Select/Deselect all permissions in a module
            $('.select-all-btn').click(function () {

                var moduleBox = $(this).closest('.module-group');
                var checkboxes = moduleBox.find('input[type="checkbox"]');

                var anyUnchecked = checkboxes.filter(':not(:checked)').length > 0;

                if (anyUnchecked) {
                    checkboxes.iCheck('check');
                } else {
                    checkboxes.iCheck('uncheck');
                }

                $(this).html(anyUnchecked
                    ? '<i class="fa fa-square-o"></i> Deselect All'
                    : '<i class="fa fa-check-square-o"></i> Select All'
                );
            });


            // Auto-generate system name from display name
            $('#display_name').on('input', function() {
                var displayName = $(this).val();
                var systemName = displayName
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '');

                $('#name').val(systemName);
            });

            // Form submission
            $('#roleForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Creating Role...');
            });
        });
    </script>
@endsection
