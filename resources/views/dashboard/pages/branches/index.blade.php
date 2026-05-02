@extends('dashboard.layouts.master')

@section('title', 'Branches Management')

@section('content')

    <style>
        .branches-page { padding: 20px; }

        /* Main Box */
        .box-branches {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .box-branches .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-branches .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-branches .box-header .box-title {
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-branches .box-header .btn {
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

        .box-branches .box-header .btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Stats Cards */
        .stats-section { margin-bottom: 25px; }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(0,0,0,0.03);
            border-radius: 50%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.total { border-left: 5px solid #667eea; }
        .stat-card.active { border-left: 5px solid #27ae60; }
        .stat-card.employees { border-left: 5px solid #3498db; }
        .stat-card.invoices { border-left: 5px solid #f39c12; }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            float: left;
            margin-right: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.active .stat-icon {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .stat-card.employees .stat-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .stat-card.invoices .stat-icon {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .stat-content {
            overflow: hidden;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 900;
            color: #333;
            line-height: 1;
        }

        /* Filter Box */
        .filter-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box .form-control,
        .filter-box .btn {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(102,126,234,0.3);
        }

        .filter-box .btn-default {
            background: white;
            border: 2px solid #e0e6ed;
            color: #666;
            font-weight: 600;
        }

        .filter-box .btn-default:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        /* Table */
        .branches-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .branches-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .branches-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .branches-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
        }

        .branches-table tbody tr {
            transition: all 0.3s;
        }

        .branches-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        /* Branch Code */
        .branch-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(102,126,234,0.3);
        }

        .badge-main {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 8px;
            box-shadow: 0 2px 6px rgba(243,156,18,0.3);
        }

        /* Branch Name */
        .branch-name {
            color: #667eea;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .branch-name:hover {
            color: #764ba2;
            text-decoration: none;
        }

        /* Address */
        .address-text {
            color: #666;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .address-text i {
            color: #3498db;
            margin-top: 2px;
        }

        /* Manager Info */
        .manager-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .manager-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.3);
        }

        /* Count Badges */
        .count-badge {
            background: linear-gradient(135deg, #e8f4f8 0%, #d4ebf3 100%);
            color: #2980b9;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(52,152,219,0.2);
        }

        .count-badge.invoices {
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
            color: #e67e22;
            box-shadow: 0 2px 6px rgba(243,156,18,0.2);
        }

        /* Status Labels */
        .status-label {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .status-label.active {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-label.inactive {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btns .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
            font-size: 14px;
        }

        .action-btns .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .action-btns .btn-info {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .action-btns .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .action-btns .btn-primary {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .action-btns .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #999;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 25px;
        }

        .bi-box-seam {
            color: #fff !important;
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
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-building"></i> Branches Management
            <small>Manage all branch locations</small>
        </h1>
    </section>

    <div class="branches-page">
        <div class="box box-primary box-branches">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> All Branches
                </h3>
                <div class="box-tools pull-right">
                    @can('create-branches')
                        <a href="{{ route('dashboard.branches.create') }}" class="btn btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Branch
                        </a>
                    @endcan
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Statistics --}}
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card total">
                                <div class="stat-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Branches</div>
                                    <div class="stat-value">{{ $branches->total() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card active">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Active Branches</div>
                                    <div class="stat-value">{{ $branches->where('is_active', true)->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card employees">
                                <div class="stat-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Employees</div>
                                    <div class="stat-value">{{ $branches->sum('users_count') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card invoices">
                                <div class="stat-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Invoices</div>
                                    <div class="stat-value">{{ $branches->sum('invoices_count') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.branches.index') }}">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="🔍 Search branches by name, code, city, or address..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <div style="display: flex; gap: 5px;">
                                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('dashboard.branches.index') }}"
                                           class="btn btn-default"
                                           title="Reset">
                                            <i class="bi bi-x"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Branches Table --}}
                <div class="table-responsive">
                    <table class="table table-hover branches-table">
                        <thead>
                        <tr>
                            <th width="10%">Code</th>
                            <th width="20%">Branch Name</th>
                            <th width="22%">Address</th>
                            <th width="15%">Manager</th>
                            <th width="8%" class="text-center">Employees</th>
                            <th width="8%" class="text-center">Invoices</th>
                            <th width="8%">Status</th>
                            <th width="9%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td>
                                    <span class="branch-code">{{ $branch->code }}</span>
                                    @if($branch->is_main)
                                        <br>
                                        <span class="badge-main">
                                            <i class="bi bi-star-fill"></i> Main
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.branches.show', $branch) }}" class="branch-name">
                                        {{ $branch->name }}
                                    </a>
                                    @if($branch->city)
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i> {{ $branch->city }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="address-text">
                                        <i class="bi bi-map"></i>
                                        <span>{{ \Str::limit($branch->address, 50) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($branch->manager_name)
                                        <div class="manager-info">
                                            <div class="manager-avatar">
                                                {{ strtoupper(substr($branch->manager_name, 0, 1)) }}
                                            </div>
                                            <span style="font-weight: 600;">{{ $branch->manager_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-person-x"></i> Not Assigned
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="count-badge">
                                        <i class="bi bi-people"></i>
                                        {{ $branch->users_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge invoices">
                                        <i class="bi bi-file-earmark-text"></i>
                                        {{ $branch->invoices_count }}
                                    </span>
                                </td>
                                <td>
                                    @if($branch->is_active)
                                        <span class="status-label active">
                                            <i class="bi bi-check-circle-fill"></i> Active
                                        </span>
                                    @else
                                        <span class="status-label inactive">
                                            <i class="bi bi-x-circle-fill"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btns">
                                        {{-- View --}}
                                        @can('view-branches')
                                            <a href="{{ route('dashboard.branches.show', $branch) }}"
                                               class="btn btn-info"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan

                                        {{-- Edit --}}
                                        @can('edit-branches')
                                            <a href="{{ route('dashboard.branches.edit', $branch) }}"
                                               class="btn btn-warning"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        {{-- Stock --}}
                                        @can('view-stock')
                                            <a href="{{ route('dashboard.branches.stock.index', $branch) }}"
                                               class="btn btn-primary"
                                               title="Stock Management">
                                                <i class="bi bi-box-seam"></i>
                                            </a>
                                        @endcan

                                        {{-- Delete --}}
                                        @can('delete-branches')
                                            @if(!$branch->is_main && $branch->invoices_count == 0)
                                                <button type="button"
                                                        class="btn btn-danger"
                                                        onclick="confirmDelete({{ $branch->id }}, '{{ $branch->name }}')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="bi bi-building"></i>
                                        <h3>No branches found</h3>
                                        <p>
                                            {{ request('search')
                                                ? 'Try adjusting your search criteria'
                                                : 'Get started by adding your first branch' }}
                                        </p>
                                        @if(!request('search'))
                                            <a href="{{ route('dashboard.branches.create') }}" class="btn btn-success btn-lg">
                                                <i class="bi bi-plus-circle"></i> Add First Branch
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($branches->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $branches->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Hidden Delete Forms --}}
    @foreach($branches as $branch)
        @if(!$branch->is_main && $branch->invoices_count == 0)
            <form id="deleteForm{{ $branch->id }}"
                  action="{{ route('dashboard.branches.destroy', $branch) }}"
                  method="POST"
                  style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(branchId, branchName) {
            Swal.fire({
                title: 'Delete Branch?',
                html: `
            <p>Are you sure you want to delete branch <strong>${branchName}</strong>?</p>
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #f39c12;">
                <strong style="color: #856404;">⚠️ Warning:</strong>
                <p style="color: #856404; margin: 10px 0 0 0;">This action cannot be undone!</p>
            </div>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-trash"></i> Yes, Delete It',
                cancelButtonText: '<i class="bi bi-x"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + branchId).submit();
                }
            });
        }
    </script>
@endsection
