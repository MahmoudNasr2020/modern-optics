@extends('dashboard.layouts.master')

@section('title', 'Users Management')

@section('content')

    <style>
        .users-page {
            padding: 20px;
        }

        .box-users {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-users .box-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-users .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-users .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-users .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-users .box-header .btn {
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

        .box-users .box-header .btn:hover {
            background: white;
            color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .filter-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box .form-control, .filter-box .btn {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }

        .users-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .users-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .users-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .users-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .users-table tbody tr {
            transition: all 0.3s;
        }

        .users-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .user-name {
            font-weight: 700;
            color: #3498db;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-name i {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }

        /* Avatar */
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ecf0f1;
        }

        .user-email {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-right: 5px;
            margin-bottom: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .role-badge.super-admin {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .role-badge.manager {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .role-badge.cashier {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .role-badge.stock-keeper {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .role-badge.default {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .branch-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .status-badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .status-badge.active {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        /* ✅ Custom Toggle Switch (بدل iCheck) */
        .custom-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .custom-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            transition: .4s;
            border-radius: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }

        .toggle-slider:hover {
            box-shadow: 0 3px 12px rgba(0,0,0,0.3);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-delete:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

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

        /* Notifications */
        .notification {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            animation: slideInRight 0.5s ease-out;
        }

        .notification.success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border-left: 5px solid #1e8449;
        }

        .notification.error {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border-left: 5px solid #a93226;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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

        .box-users {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                gap: 5px;
            }

            .action-buttons .btn {
                min-width: 35px;
                height: 35px;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Users Management
            <small>Manage system users</small>
        </h1>
    </section>

    <div class="users-page">
        <div class="box box-primary box-users">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> All Users
                </h3>
                @can('create-users')
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.get-add-user') }}" class="btn btn-sm">
                        <i class="fa fa-plus"></i> Add New User
                    </a>
                </div>
                @endcan
            </div>

            <div class="box-body" style="padding: 30px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-users') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search by name or email..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- 🏢 Branch Filter --}}
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <select name="branch_id" class="form-control">
                                            <option value="">All Branches</option>
                                            @foreach(auth()->user()->getAccessibleBranches() as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                @can('create-users')
                                    <a href="{{ route('dashboard.get-add-user') }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add User
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover users-table">
                        <thead>
                        <tr>
                            <th width="5%"  class="text-center">#</th>
                            <th width="8%"  class="text-center">Image</th>
                            <th width="22%">User</th>
                            <th width="18%">Roles</th>
                            <th width="15%" class="text-center">Branch</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="22%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>
                                    <strong style="color: #3498db; font-size: 16px;">{{ $index + 1 }}</strong>
                                </td>

                                {{-- Image --}}
                                <td class="text-center">
                                    <img src="{{ $user->image_path  }}"
                                         alt="{{ $user->full_name }}"
                                         class="user-avatar">
                                </td>

                                <td>
                                    <div class="user-name">
                                        <i class="fa fa-user"></i>
                                        <span>{{ $user->full_name }}</span>
                                    </div>
                                    @if($user->email)
                                        <div class="user-email">
                                            <i class="fa fa-envelope"></i> {{ $user->email }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($user->roles && $user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="role-badge {{ $role->name }}">
                                                <i class="fa fa-shield"></i> {{ $role->display_name ?? $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="role-badge default">
                                            <i class="fa fa-ban"></i> No Role
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->branch)
                                        <span class="branch-badge">
                                            <i class="fa fa-building"></i>
                                            {{ $user->branch->name }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-style: italic;">
                                            <i class="fa fa-minus-circle"></i> Not Assigned
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->is_active)
                                        <span class="status-badge active">
                                            <i class="fa fa-check-circle"></i> Active
                                        </span>
                                                                        @else
                                                                            <span class="status-badge inactive">
                                            <i class="fa fa-times-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    <div class="action-buttons">
                                        @can('edit-users')
                                            <a href="{{ route('dashboard.get-update-user', $user->id) }}"
                                               class="btn btn-edit btn-xs"
                                               title="Update User">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('delete-users')
                                            @if(!$user->isSuperAdmin())
                                                <button type="button"
                                                        class="btn btn-delete btn-xs btn-delete-user"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->full_name }}"
                                                        data-can-delete="{{ $user->canBeDeleted() ? 'true' : 'false' }}"
                                                        title="Delete User">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-delete btn-xs"
                                                        disabled
                                                        title="Cannot delete Super Admin">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fa fa-users"></i>
                                        <h4>No users found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new user</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="fa fa-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="GET">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="fa fa-warning"></i>
                            Are you sure you want to delete user <strong id="userName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i>
                            This action cannot be undone. All user data will be permanently removed.
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-trash"></i> Delete User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // ✅ Custom Toggle (بدون iCheck)
            $(document).on('change', '.toggle-status', function() {
                var userId = $(this).data('user-id');
                var checkbox = $(this);
                var isChecked = checkbox.is(':checked');

                console.log('Toggle clicked for user:', userId, 'New state:', isChecked);

                $.ajax({
                    url: '{{ url("dashboard/users-toggle-active") }}/' + userId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        showNotification('success', 'تم تحديث حالة المستخدم بنجاح');
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', xhr.responseText);

                        // Revert checkbox
                        checkbox.prop('checked', !isChecked);

                        var message = 'حدث خطأ أثناء تحديث حالة المستخدم';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showNotification('error', message);
                    }
                });
            });

            // ✅ Delete User
            $(document).on('click', '.btn-delete-user', function() {
                var userId = $(this).data('id');
                var userName = $(this).data('name');
                var canDelete = $(this).data('can-delete');

                console.log('Delete clicked:', userId, canDelete);

                if (canDelete === 'false' || canDelete === false) {
                    showNotification('error', 'لا يمكن حذف هذا المستخدم - المستخدم مرتبط ببيانات أخرى في النظام');
                    return;
                }

                var finalRoute = '{{ url("dashboard/users/delete") }}/' + userId;

                $('#userName').text(userName);
                $('#deleteForm').attr('action', finalRoute);
                $('#deleteModal').modal('show');
            });

            // ✅ Show Notification Function
            function showNotification(type, message) {
                var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                var notification = $('<div class="notification ' + type + '">' +
                    '<i class="fa ' + icon + '"></i> ' + message +
                    '</div>');

                $('body').append(notification);

                setTimeout(function() {
                    notification.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            console.log('Script loaded successfully');
        });
    </script>
@endsection
