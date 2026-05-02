@extends('dashboard.layouts.master')

@section('title', 'Roles Management')

@section('content')

    <style>
        .roles-page {
            /*background: #ecf0f5;*/
            padding: 20px;
        }

        .box-roles {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-roles .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-roles .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-roles .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-roles .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-roles .box-header .btn {
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

        .box-roles .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .roles-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .roles-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .roles-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .roles-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .roles-table tbody tr {
            transition: all 0.3s;
        }

        .roles-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .role-name {
            font-weight: 700;
            color: #667eea;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role-name i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .role-description {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }

        .role-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .role-badge.system {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .role-badge.custom {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
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

        .users-count {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-width: 45px;
            height: 45px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
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

        .btn-view {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-permissions {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
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

        .box-roles {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td
        {
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
            <i class="fa fa-shield"></i> Roles Management
            <small>Manage system roles and permissions</small>
        </h1>
    </section>

    <div class="roles-page">
        <div class="box box-primary box-roles">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> All Roles
                </h3>
                <div class="box-tools pull-right">
                    @can('create-roles')
                        <a href="{{ route('dashboard.roles.create') }}" class="btn btn-sm">
                            <i class="fa fa-plus"></i> Create New Role
                        </a>
                    @endcan
                </div>
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

                <div class="table-responsive">
                    <table class="table table-hover roles-table">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Role Name</th>
                            <th width="20%">Display Name</th>
                            <th width="15%" class="text-center">Type</th>
                            <th width="10%" class="text-center">Users</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>
                                    <strong style="color: #667eea; font-size: 16px;">{{ $loop->iteration }}</strong>
                                </td>
                                <td>
                                    <div class="role-name">
                                        <i class="fa fa-shield"></i>
                                        <span>{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #333; font-size: 15px;">{{ $role->display_name }}</strong>
                                    @if($role->description)
                                        <div class="role-description">
                                            <i class="fa fa-info-circle"></i> {{ $role->description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="role-badge {{ $role->is_system ? 'system' : 'custom' }}">
                                        <i class="fa fa-{{ $role->is_system ? 'lock' : 'unlock' }}"></i>
                                        {{ $role->is_system ? 'System' : 'Custom' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="users-count" title="{{ $role->users_count }} users assigned">
                                        {{ $role->users_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge {{ $role->is_active ? 'active' : 'inactive' }}">
                                        <i class="fa fa-{{ $role->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @can('view-roles')
                                            <a href="{{ route('dashboard.roles.show', $role) }}"
                                               class="btn btn-view btn-xs"
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('assign-permissions')
                                            <a href="{{ route('dashboard.roles.permissions.edit', $role) }}"
                                               class="btn btn-permissions btn-xs"
                                               title="Manage Permissions">
                                                <i class="fa fa-key"></i>
                                            </a>
                                        @endcan

                                        @can('edit-roles')
                                            @if(!$role->is_system)
                                                <a href="{{ route('dashboard.roles.edit', $role) }}"
                                                   class="btn btn-edit btn-xs"
                                                   title="Edit Role">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('delete-roles')
                                            @if(!$role->is_system && $role->users_count == 0)
                                                <button type="button"
                                                        class="btn btn-delete btn-xs btn-delete-role"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ $role->display_name }}"
                                                        title="Delete Role">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa fa-shield"></i>
                                        <h4>No roles found</h4>
                                        <p style="color: #999;">Start by creating your first role</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($roles->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $roles->links() }}
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
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="fa fa-warning"></i>
                            Are you sure you want to delete role <strong id="roleName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i>
                            This action cannot be undone. All permissions will be removed.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-trash"></i> Delete Role
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
            // Delete Role
            $(document).on('click', '.btn-delete-role', function() {
                var roleId = $(this).data('id');
                var roleName = $(this).data('name');

                $('#roleName').text(roleName);
                $('#deleteForm').attr('action', '/dashboard/roles/' + roleId);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
