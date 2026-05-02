@extends('dashboard.layouts.master')

@section('title', 'Role Details')

@section('content')

    <style>
        .role-show-page {
            background: #ecf0f5;
            padding: 20px;
        }

        .box-role-show {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-role-show .box-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-role-show .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-role-show .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-role-show .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-role-show .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin-left: 10px;
        }

        .box-role-show .box-header .btn:hover {
            background: white;
            color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Role Hero Card */
        .role-hero-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .role-hero-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .role-hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .role-hero-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .role-hero-details h2 {
            margin: 0 0 10px 0;
            font-size: 36px;
            font-weight: 900;
        }

        .role-hero-details .system-name {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .role-hero-details .description {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .role-badges {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .hero-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .hero-badge.system {
            background: rgba(231, 76, 60, 0.9);
        }

        .hero-badge.custom {
            background: rgba(52, 152, 219, 0.9);
        }

        .hero-badge.active {
            background: rgba(39, 174, 96, 0.9);
        }

        .hero-badge.inactive {
            background: rgba(149, 165, 166, 0.9);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            border: 2px solid #e8ecf7;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.blue {
            border-top: 4px solid #3498db;
        }

        .stat-card.purple {
            border-top: 4px solid #9b59b6;
        }

        .stat-card.green {
            border-top: 4px solid #27ae60;
        }

        .stat-card.orange {
            border-top: 4px solid #f39c12;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin: 0 auto 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .stat-card.purple .stat-icon {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .stat-card.green .stat-icon {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.orange .stat-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 900;
            color: #333;
        }

        /* Info Cards */
        .info-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8ecf7;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .section-header.permissions .section-icon {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .section-header.users .section-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .section-header.info .section-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .section-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .section-header .count-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            margin-left: auto;
        }

        /* Permissions Grid */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .permission-badge {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }

        .permission-badge:hover {
            border-color: #9b59b6;
            transform: translateX(5px);
            box-shadow: 0 3px 12px rgba(155, 89, 182, 0.15);
        }

        .permission-badge i {
            color: #9b59b6;
            font-size: 18px;
        }

        .permission-badge span {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .permission-module-group {
            margin-bottom: 30px;
        }

        .module-label {
            font-size: 14px;
            font-weight: 700;
            color: #9b59b6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 4px solid #9b59b6;
        }

        /* Users Table */
        .users-table {
            width: 100%;
        }

        .users-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .users-table thead th {
            color: white;
            font-weight: 700;
            padding: 15px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .users-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        .users-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #3498db;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }

        .user-details .user-name {
            font-weight: 700;
            color: #333;
            font-size: 14px;
        }

        .user-details .user-email {
            font-size: 12px;
            color: #999;
        }

        .branch-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 20px;
        }

        .info-item .label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-item .value {
            font-size: 18px;
            font-weight: 700;
            color: #333;
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

        .role-hero-card, .stats-grid, .info-section {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .role-hero-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-shield"></i> Role Details
            <small>View role information and permissions</small>
        </h1>
    </section>

    <div class="role-show-page">
        <div class="box box-primary box-role-show">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-info-circle"></i> {{ $role->display_name }}
                </h3>
                <div class="box-tools pull-right">
                    @can('assign-permissions')
                        <a href="{{ route('dashboard.roles.permissions.edit', $role) }}" class="btn btn-sm">
                            <i class="fa fa-key"></i> Manage Permissions
                        </a>
                    @endcan

                    @can('edit-roles')
                        @if(!$role->is_system)
                            <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-sm">
                                <i class="fa fa-edit"></i> Edit Role
                            </a>
                        @endif
                    @endcan

                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                <!-- Role Hero Card -->
                <div class="role-hero-card">
                    <div class="role-hero-content">
                        <div class="role-hero-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <div class="role-hero-details">
                            <h2>{{ $role->display_name }}</h2>
                            <div class="system-name">
                                <i class="fa fa-code"></i> System Name: <strong>{{ $role->name }}</strong>
                            </div>
                            @if($role->description)
                                <div class="description">
                                    <i class="fa fa-info-circle"></i> {{ $role->description }}
                                </div>
                            @endif
                            <div class="role-badges">
                            <span class="hero-badge {{ $role->is_system ? 'system' : 'custom' }}">
                                <i class="fa fa-{{ $role->is_system ? 'lock' : 'unlock' }}"></i>
                                {{ $role->is_system ? 'System Role' : 'Custom Role' }}
                            </span>
                                <span class="hero-badge {{ $role->is_active ? 'active' : 'inactive' }}">
                                <i class="fa fa-{{ $role->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card blue">
                        <div class="stat-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="stat-label">Users Assigned</div>
                        <div class="stat-value">{{ $role->users->count() }}</div>
                    </div>

                    <div class="stat-card purple">
                        <div class="stat-icon">
                            <i class="fa fa-key"></i>
                        </div>
                        <div class="stat-label">Total Permissions</div>
                        <div class="stat-value">{{ $role->permissions->count() }}</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon">
                            <i class="fa fa-folder-o"></i>
                        </div>
                        <div class="stat-label">Modules</div>
                        <div class="stat-value">{{ $role->permissions->pluck('module')->unique()->count() }}</div>
                    </div>

                    <div class="stat-card orange">
                        <div class="stat-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="stat-label">Created</div>
                        <div class="stat-value" style="font-size: 16px; margin-top: 10px;">
                            {{ $role->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="info-section">
                    <div class="section-header permissions">
                        <div class="section-icon">
                            <i class="fa fa-key"></i>
                        </div>
                        <h3>Assigned Permissions</h3>
                        <span class="count-badge">{{ $role->permissions->count() }} permissions</span>
                    </div>

                    @if($role->permissions->count() > 0)
                        @foreach($role->permissions->groupBy('module') as $module => $modulePermissions)
                            <div class="permission-module-group">
                                <div class="module-label">
                                    <i class="fa fa-folder-o"></i> {{ ucwords(str_replace('-', ' ', $module)) }}
                                </div>
                                <div class="permissions-grid">
                                    @foreach($modulePermissions as $permission)
                                        <div class="permission-badge">
                                            <i class="fa fa-check-circle"></i>
                                            <span>{{ $permission->display_name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fa fa-key"></i>
                            <p>No permissions assigned to this role yet</p>
                        </div>
                    @endif
                </div>

                <!-- Users Section -->
                <div class="info-section">
                    <div class="section-header users">
                        <div class="section-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <h3>Users with this Role</h3>
                        <span class="count-badge">{{ $role->users->count() }} users</span>
                    </div>

                    @if($role->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table users-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($role->users as $user)
                                    <tr>
                                        <td><strong>{{ $loop->iteration }}</strong></td>
                                        <td>
                                            <div class="user-info">
                                                <img src="{{ $user->image_path }}"
                                                     alt="{{ $user->full_name }}"
                                                     class="user-avatar">
                                                <div class="user-details">
                                                    <div class="user-name">{{ $user->full_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color: #666;">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            @if($user->branch)
                                                <span class="branch-badge">
                                                    <i class="fa fa-building"></i> {{ $user->branch->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="color: #999; font-size: 13px;">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fa fa-users"></i>
                            <p>No users assigned to this role yet</p>
                        </div>
                    @endif
                </div>

                <!-- Additional Info -->
                <div class="info-section">
                    <div class="section-header info">
                        <div class="section-icon">
                            <i class="fa fa-info-circle"></i>
                        </div>
                        <h3>Additional Information</h3>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label"><i class="fa fa-tag"></i> Role Name</div>
                            <div class="value">{{ $role->name }}</div>
                        </div>

                        <div class="info-item">
                            <div class="label"><i class="fa fa-shield"></i> Guard Name</div>
                            <div class="value">{{ $role->guard_name }}</div>
                        </div>

                        <div class="info-item">
                            <div class="label"><i class="fa fa-calendar-plus-o"></i> Created At</div>
                            <div class="value">{{ $role->created_at->format('M d, Y - H:i A') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="label"><i class="fa fa-calendar-check-o"></i> Last Updated</div>
                            <div class="value">{{ $role->updated_at->format('M d, Y - H:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
