@extends('dashboard.layouts.master')

@section('title', 'Insurance Companies')

@section('styles')
    <style>
        .insurance-page {
            padding: 20px;
        }

        .box-insurance {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            background: white;
        }

        .box-insurance .box-header {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-insurance .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-insurance .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-insurance .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-insurance .box-header .badge {
            background: rgba(255,255,255,0.3);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            margin-left: 10px;
        }

        /* Stats Card */
        .stats-card {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #ce93d8;
            margin-bottom: 20px;
        }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(142, 68, 173, 0.3);
        }

        .stats-card .value {
            font-size: 32px;
            font-weight: 800;
            color: #8e44ad;
            margin-bottom: 5px;
        }

        .stats-card .label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Add Button */
        .btn-add {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(142, 68, 173, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(142, 68, 173, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-add i {
            margin-right: 8px;
        }

        /* Company Card */
        .company-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.12);
        }

        .company-card .card-header {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 20px 25px;
            border-bottom: 3px solid #8e44ad;
        }

        .company-card .card-header h4 {
            margin: 0;
            color: #8e44ad;
            font-size: 20px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .company-card .card-header .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .company-card .card-header .status-badge.active {
            background: #27ae60;
            color: white;
        }

        .company-card .card-header .status-badge.inactive {
            background: #e74c3c;
            color: white;
        }

        .company-card .card-body {
            padding: 0;
        }

        .company-table {
            width: 100%;
            margin: 0;
        }

        .company-table thead {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
        }

        .company-table thead th {
            padding: 15px 20px;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .company-table tbody tr {
            border-bottom: 1px solid #f3e5f5;
            transition: background 0.2s;
        }

        .company-table tbody tr:hover {
            background: #faf8fb;
        }

        .company-table tbody td {
            padding: 15px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        .company-table tbody td:first-child {
            font-weight: 700;
            color: #8e44ad;
        }

        .company-table tbody .discount-cell {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #27ae60;
            font-size: 15px;
        }

        /* Action Buttons */
        /* Action Buttons */
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

        /* Empty State */
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-data img {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
            opacity: 0.6;
        }

        .no-data h3 {
            color: #666;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .no-data p {
            color: #999;
            font-size: 14px;
        }

        /* Bootstrap Icons color fix */
        .bi-building, .bi-shield-check {
            color: white !important;
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

        .company-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@stop

@section('content')

    <section class="content-header">
        <h1>
            <i class="bi bi-shield-check"></i> Insurance Companies
            <small>Manage insurance partners</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Insurance Companies</li>
        </ol>
    </section>

    <div class="insurance-page">
        <div class="box box-primary box-insurance">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="bi bi-building"></i> Insurance Companies
                    <span class="badge">{{ $insuranceCompanies->count() }}</span>
                </h3>
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Stats & Actions -->
                <div class="row" style="margin-bottom: 25px;">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="value">{{ $insuranceCompanies->count() }}</div>
                            <div class="label">Total Companies</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="value">{{ $insuranceCompanies->where('status', 1)->count() }}</div>
                            <div class="label">Active</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="value">{{ $insuranceCompanies->where('status', 0)->count() }}</div>
                            <div class="label">Inactive</div>
                        </div>
                    </div>
                </div>

                <!-- Add Button -->
                @can('create-insurance-companies')
                <div style="margin-bottom: 25px;">
                    <a href="{{ route('dashboard.get-add-insurance-company') }}" class="btn-add">
                        <i class="bi bi-plus-circle"></i> Add New Company
                    </a>
                </div>
                @endcan

                <!-- Companies List -->
                @if($insuranceCompanies->count() > 0)
                    @foreach($insuranceCompanies as $insuranceCompany)
                        <div class="company-card">
                            <div class="card-header">
                                <h4>
                                <span>
                                    <i class="bi bi-building"></i> {{ $insuranceCompany->company_name }}
                                </span>
                                    <span class="status-badge {{ $insuranceCompany->status == 1 ? 'active' : 'inactive' }}">
                                    {{ $insuranceCompany->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="company-table">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        @foreach($insuranceCompany->categories as $category)
                                            <th>{{ $category->category_name }}</th>
                                        @endforeach
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="font-weight: 700; color: #8e44ad;">DISCOUNT</td>
                                        @foreach($insuranceCompany->categories as $category)
                                            <td class="discount-cell">
                                                {{ floatval($category->pivot->discount_percent) }}%
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="action-buttons">
                                                @can('edit-insurance-companies')
                                                    <a href="{{ route('dashboard.get-update-insurance-company', $insuranceCompany->id) }}"
                                                       class="btn btn-edit btn-xs"
                                                       title="Edit Company">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete-insurance-companies')
                                                    <form action="{{ route('dashboard.delete-insurance-company', $insuranceCompany->id) }}"
                                                          method="GET"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                                class="btn btn-delete btn-xs delete"
                                                                title="Delete Company">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div style="margin-top: 30px;">
                        {{ $insuranceCompanies->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="no-data">
                        <img src="{{ asset('assets/img/no_data.webp') }}" alt="No Data">
                        <h3>No Insurance Companies Found</h3>
                        <p>Start by adding your first insurance company partner</p>
                        <div style="margin-top: 20px;">
                            <a href="{{ route('dashboard.get-add-insurance-company') }}" class="btn-add">
                                <i class="bi bi-plus-circle"></i> Add First Company
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@stop
