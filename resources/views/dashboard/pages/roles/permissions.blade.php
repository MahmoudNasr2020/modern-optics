@extends('dashboard.layouts.master')

@section('title', 'Manage Permissions')

@section('content')

    <style>
        .permissions-page {
            background: #ecf0f5;
            padding: 20px;
        }

        .box-permissions {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-permissions .box-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-permissions .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-permissions .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-permissions .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-permissions .box-header .btn {
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

        .box-permissions .box-header .btn:hover {
            background: white;
            color: #9b59b6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Role Info Card */
        .role-info-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #9b59b6;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(155, 89, 182, 0.15);
        }

        .role-info-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .role-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        }

        .role-details h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #9b59b6;
        }

        .role-details p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .role-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e8ecf7;
            text-align: center;
        }

        .stat-item .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-item .stat-value {
            font-size: 24px;
            font-weight: 900;
            color: #9b59b6;
        }

        /* Quick Actions */
        .quick-actions {
            background: linear-gradient(135deg, #fff3e6 0%, #fff 100%);
            border: 2px solid #f39c12;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .quick-actions h5 {
            margin: 0 0 15px 0;
            font-size: 16px;
            font-weight: 700;
            color: #f39c12;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-actions h5 i {
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

        .quick-action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
        }

        /* Module Groups */
        .module-group {
            background: white;
            border: 2px solid #e8ecf7;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .module-group:hover {
            border-color: #9b59b6;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.15);
        }

        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8ecf7;
        }

        .module-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .module-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.3);
        }

        .module-title h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #9b59b6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .module-count {
            font-size: 12px;
            color: #999;
            font-weight: 600;
        }

        .select-all-module {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(155, 89, 182, 0.3);
        }

        .select-all-module:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(155, 89, 182, 0.4);
        }

        /* Permission Items */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 18px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .permission-item:hover {
            border-color: #9b59b6;
            box-shadow: 0 3px 12px rgba(155, 89, 182, 0.15);
            transform: translateX(5px);
        }

        .permission-item.checked {
            background: linear-gradient(135deg, #f3e5f5 0%, #fff 100%);
            border-color: #9b59b6;
        }

        .permission-item input[type="checkbox"] {
            width: 22px;
            height: 22px;
            margin-right: 15px;
            cursor: pointer;
            accent-color: #9b59b6;
        }

        .permission-content {
            flex: 1;
            margin-left: 10px;
        }

        .permission-item label {
            margin: 0;
            cursor: pointer;
            font-weight: 700;
            color: #333;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        .permission-item small {
            color: #999;
            font-size: 12px;
            font-style: italic;
        }

        .permission-badge {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 10px;
        }

        /* Submit Section */
        .submit-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            bottom: 20px;
            z-index: 100;
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 16px 60px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
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
            padding: 16px 60px;
            border-radius: 10px;
            font-size: 18px;
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

        /* Progress Bar */
        .progress-bar-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #666;
        }

        .progress {
            height: 30px;
            border-radius: 15px;
            background: #e9ecef;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }

        .progress-bar {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            font-weight: 700;
            line-height: 30px;
            transition: width 0.5s ease;
        }

        /* Animations */
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

        .module-group {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .role-stats {
                grid-template-columns: 1fr;
            }

            .submit-section {
                position: relative;
                bottom: 0;
            }

            .btn-submit, .btn-cancel {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-key"></i> Manage Permissions
            <small>Assign permissions to role</small>
        </h1>
    </section>

    <div class="permissions-page">
        <form action="{{ route('dashboard.roles.permissions.update', $role) }}" method="POST" id="permissionsForm">
            @csrf
            @method('PUT')

            <div class="box box-primary box-permissions">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-shield"></i> {{ $role->display_name }} - Permissions
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

                    <!-- Role Info Card -->
                    <div class="role-info-card">
                        <div class="role-info-header">
                            <div class="role-icon">
                                <i class="fa fa-shield"></i>
                            </div>
                            <div class="role-details">
                                <h3>{{ $role->display_name }}</h3>
                                <p>
                                    <strong>System Name:</strong> {{ $role->name }}
                                    @if($role->description)
                                        | {{ $role->description }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="role-stats">
                            <div class="stat-item">
                                <div class="stat-label">Total Permissions</div>
                                <div class="stat-value" id="totalPermissions">{{ $permissions->flatten()->count() }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Currently Assigned</div>
                                <div class="stat-value" id="assignedPermissions">{{ count($rolePermissions) }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Modules</div>
                                <div class="stat-value">{{ $permissions->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <h5>
                            <i class="fa fa-bolt"></i>
                            Quick Actions
                        </h5>
                        <div class="quick-action-buttons">
                            <button type="button" class="quick-action-btn" id="selectAll">
                                <i class="fa fa-check-square-o"></i> Select All
                            </button>
                            <button type="button" class="quick-action-btn" id="deselectAll">
                                <i class="fa fa-square-o"></i> Deselect All
                            </button>
                            <button type="button" class="quick-action-btn" id="invertSelection">
                                <i class="fa fa-exchange"></i> Invert Selection
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-bar-container">
                        <div class="progress-info">
                            <span><i class="fa fa-tasks"></i> Permissions Selected</span>
                            <span id="progressText">{{ count($rolePermissions) }} / {{ $permissions->flatten()->count() }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" id="progressBar"
                                 style="width: {{ $permissions->flatten()->count() > 0 ? (count($rolePermissions) / $permissions->flatten()->count() * 100) : 0 }}%">
                                {{ $permissions->flatten()->count() > 0 ? round(count($rolePermissions) / $permissions->flatten()->count() * 100) : 0 }}%
                            </div>
                        </div>
                    </div>

                    <!-- Permissions by Module -->
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="module-group">
                            <div class="module-header">
                                <div class="module-title">
                                    <div class="module-icon">
                                        <i class="fa fa-folder-o"></i>
                                    </div>
                                    <div>
                                        <h4>{{ ucwords(str_replace('-', ' ', $module)) }}</h4>
                                        <span class="module-count">
                                        <span class="module-selected-count">{{ $modulePermissions->whereIn('name', $rolePermissions)->count() }}</span> / {{ $modulePermissions->count() }} permissions
                                    </span>
                                    </div>
                                </div>
                                <button type="button"
                                        class="select-all-module"
                                        data-module="{{ $module }}">
                                    <i class="fa fa-check-square-o"></i> Select All
                                </button>
                            </div>

                            <div class="permissions-grid">
                                @foreach($modulePermissions as $permission)
                                    <div class="permission-item {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               id="permission_{{ $permission->id }}"
                                               value="{{ $permission->name }}"
                                               data-module="{{ $module }}"
                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                        <div class="permission-content">
                                            <label for="permission_{{ $permission->id }}">
                                                <i class="fa fa-key"></i> {{ $permission->display_name }}
                                                @if(in_array($permission->name, $rolePermissions))
                                                    <span class="permission-badge">
                                                    <i class="fa fa-check"></i> Assigned
                                                </span>
                                                @endif
                                            </label>
                                            @if($permission->description)
                                                <small><i class="fa fa-info-circle"></i> {{ $permission->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <button type="submit" class="btn btn-submit">
                            <i class="fa fa-save"></i> Update Permissions
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
            var totalPermissions = {{ $permissions->flatten()->count() }};

            function updateProgress() {
                var checkedCount = $('input[name="permissions[]"]').filter(':checked').length;
                var percentage = totalPermissions > 0 ? (checkedCount / totalPermissions * 100) : 0;

                $('#assignedPermissions').text(checkedCount);
                $('#progressText').text(checkedCount + ' / ' + totalPermissions);
                $('#progressBar').css('width', percentage + '%').text(Math.round(percentage) + '%');

                // Update module counts
                $('.module-group').each(function() {
                    var module = $(this);
                    var moduleCheckboxes = module.find('input[name="permissions[]"]');
                    var moduleChecked = moduleCheckboxes.filter(':checked').length;
                    module.find('.module-selected-count').text(moduleChecked);
                });
            }

            // Listen to iCheck changes
            $('input[name="permissions[]"]').on('ifChanged', function() {
                var item = $(this).closest('.permission-item');

                if ($(this).is(':checked')) {
                    item.addClass('checked');
                    item.find('.permission-badge').remove();
                    item.find('label').append('<span class="permission-badge"><i class="fa fa-check"></i> Assigned</span>');
                } else {
                    item.removeClass('checked');
                    item.find('.permission-badge').remove();
                }

                updateProgress();
            });

            // Quick Actions
            $('#selectAll').click(function() {
                $('input[name="permissions[]"]').iCheck('check'); // iCheck
            });

            $('#deselectAll').click(function() {
                $('input[name="permissions[]"]').iCheck('uncheck'); // iCheck
            });

            $('#invertSelection').click(function() {
                $('input[name="permissions[]"]').each(function() {
                    $(this).iCheck('toggle'); // iCheck
                });
            });

            // Module Select All
            $('.select-all-module').click(function() {
                var module = $(this).data('module');
                var checkboxes = $('input[data-module="' + module + '"]');
                var allChecked = checkboxes.filter(':checked').length === checkboxes.length;

                if (allChecked) {
                    checkboxes.iCheck('uncheck');
                    $(this).html('<i class="fa fa-check-square-o"></i> Select All');
                } else {
                    checkboxes.iCheck('check');
                    $(this).html('<i class="fa fa-square-o"></i> Deselect All');
                }
            });

            // Form submission
            $('#permissionsForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating Permissions...');
            });
        });

    </script>
@endsection
