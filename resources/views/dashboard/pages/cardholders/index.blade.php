@extends('dashboard.layouts.master')

@section('title', 'Cardholders')

@section('styles')
    <style>
        .cardholders-page {
            padding: 20px;
        }

        .box-cardholders {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            background: white;
        }

        .box-cardholders .box-header {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-cardholders .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-cardholders .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-cardholders .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-cardholders .box-header .badge {
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
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #80deea;
            margin-bottom: 20px;
        }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);
        }

        .stats-card .value {
            font-size: 32px;
            font-weight: 800;
            color: #00bcd4;
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
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 188, 212, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-add i {
            margin-right: 8px;
        }

        /* Cardholder Card */
        .cardholder-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .cardholder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.12);
        }

        .cardholder-card .card-header {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            padding: 20px 25px;
            border-bottom: 3px solid #00bcd4;
        }

        .cardholder-card .card-header h4 {
            margin: 0;
            color: #00bcd4;
            font-size: 20px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cardholder-card .card-header .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .cardholder-card .card-header .status-badge.active {
            background: #27ae60;
            color: white;
        }

        .cardholder-card .card-header .status-badge.inactive {
            background: #e74c3c;
            color: white;
        }

        .cardholder-card .card-body {
            padding: 0;
        }

        .cardholder-table {
            width: 100%;
            margin: 0;
        }

        .cardholder-table thead {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
        }

        .cardholder-table thead th {
            padding: 15px 20px;
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .cardholder-table tbody tr {
            border-bottom: 1px solid #e0f7fa;
            transition: background 0.2s;
        }

        .cardholder-table tbody tr:hover {
            background: #f0fdff;
        }

        .cardholder-table tbody td {
            padding: 15px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        .cardholder-table tbody td:first-child {
            font-weight: 700;
            color: #00bcd4;
        }

        .cardholder-table tbody .discount-cell {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #27ae60;
            font-size: 15px;
        }

        .cardholder-table tbody .not-assigned {
            color: #e74c3c;
            font-style: italic;
            font-weight: 600;
        }

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
        .bi-credit-card, .bi-person-badge {
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

        .cardholder-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@stop

@section('content')

    <section class="content-header">
        <h1>
            <i class="bi bi-credit-card"></i> Cardholders
            <small>Manage cardholder partnerships</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Cardholders</li>
        </ol>
    </section>

    <div class="cardholders-page">
        <div class="box box-primary box-cardholders">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="bi bi-person-badge"></i> Cardholders
                    <span class="badge">{{ $cardholders->count() }}</span>
                </h3>
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Stats & Actions -->
                <div class="row" style="margin-bottom: 25px;">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="value">{{ $cardholders->count() }}</div>
                            <div class="label">Total Cardholders</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="value">{{ $cardholders->where('status', 1)->count() }}</div>
                            <div class="label">Active</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="value">{{ $cardholders->where('status', 0)->count() }}</div>
                            <div class="label">Inactive</div>
                        </div>
                    </div>
                </div>

                <!-- Add Button -->
                @can('create-cardholders')
                <div style="margin-bottom: 25px;">
                    <a href="{{ route('dashboard.get-add-cardholder') }}" class="btn-add">
                        <i class="bi bi-plus-circle"></i> Add New Cardholder
                    </a>
                </div>
                @endcan

                <!-- Cardholders List -->
                @if($cardholders->count() > 0)
                    @foreach($cardholders as $cardholder)
                        <div class="cardholder-card">
                            <div class="card-header">
                                <h4>
                                <span>
                                    <i class="bi bi-person-badge"></i> {{ $cardholder->cardholder_name }}
                                </span>
                                    <span class="status-badge {{ $cardholder->status == 1 ? 'active' : 'inactive' }}">
                                    {{ $cardholder->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="cardholder-table">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        @if($cardholder->categories->count() > 0)
                                            @foreach($cardholder->categories as $category)
                                                <th>{{ $category->category_name }}</th>
                                            @endforeach
                                        @else
                                            <th>-</th>
                                        @endif
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="font-weight: 700; color: #00bcd4;">DISCOUNT</td>
                                        @if($cardholder->categories->count() > 0)
                                            @foreach($cardholder->categories as $category)
                                                <td class="discount-cell">
                                                    {{ floatval($category->pivot->discount_percent) }}%
                                                </td>
                                            @endforeach
                                        @else
                                            <td class="not-assigned">
                                                <i class="bi bi-exclamation-circle"></i> Not Assigned
                                            </td>
                                        @endif
                                        <td>
                                            <div class="action-buttons">
                                                @can('edit-cardholders')
                                                <a href="{{ route('dashboard.get-update-cardholder', $cardholder->id) }}"
                                                   class="btn btn-edit btn-xs"
                                                   title="Edit Cardholder">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @endcan

                                                @can('delete-cardholders')
                                                <form action="{{ route('dashboard.delete-cardholder', $cardholder->id) }}"
                                                      method="GET"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"
                                                            class="btn btn-delete btn-xs delete"
                                                            title="Delete Cardholder">
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
                        {{ $cardholders->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="no-data">
                        <img src="{{ asset('assets/img/no_data.webp') }}" alt="No Data">
                        <h3>No Cardholders Found</h3>
                        <p>Start by adding your first cardholder partner</p>
                        <div style="margin-top: 20px;">
                            <a href="{{ route('dashboard.get-add-cardholder') }}" class="btn-add">
                                <i class="bi bi-plus-circle"></i> Add First Cardholder
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@stop
