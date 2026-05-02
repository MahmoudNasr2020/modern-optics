@extends('dashboard.layouts.master')

@section('title', 'Sales Summary By Salesman')

@section('content')

    <style>
        .report-page {
            padding: 20px;
        }

        .box-report {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }

        .box-report .box-header {
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-report .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-report .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-report .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        /* Info Cards */
        .info-cards {
            margin-bottom: 25px;
        }

        .info-card {
            background: white;
            padding: 25px 20px;
            border-radius: 12px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .info-card .icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.3);
        }

        .info-card h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }

        .info-card p {
            margin: 0;
            font-size: 13px;
            color: #999;
        }

        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, #e8eaf6 0%, #fff 100%);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #9fa8da;
        }

        .filter-section label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
        }

        .filter-section label i {
            color: #3f51b5;
            margin-right: 5px;
        }

        .filter-section .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 14px;
            height: 45px;
        }

        .filter-section .form-control:focus {
            border-color: #3f51b5;
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
        }

        .btn-print {
            background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(63, 81, 181, 0.3);
            height: 45px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(63, 81, 181, 0.4);
            color: white;
        }

        .btn-print i {
            margin-right: 8px;
        }

        /* Alert Box */
        .alert-info-custom {
            background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
            border-left: 5px solid #3f51b5;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }

        .alert-info-custom i {
            color: #3f51b5;
            font-size: 24px;
            margin-right: 15px;
        }

        .alert-info-custom strong {
            color: #283593;
            font-size: 16px;
        }

        .alert-info-custom p {
            margin: 5px 0 0 39px;
            color: #1a237e;
            font-size: 14px;
        }

        .bi-people-fill{
            color: #fff !important;
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

        .box-report {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-people-fill"></i> Sales Summary By Salesman
            <small>Sales performance analysis</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Reports</a></li>
            <li class="active">Sales Summary By Salesman</li>
        </ol>
    </section>

    <div class="report-page">
        <div class="box box-primary box-report">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-people-fill"></i> Salesman Performance Report
                </h3>
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Info Cards -->
                <div class="row info-cards">
                    @if(auth()->user()->canSeeAllBranches())
                        <div class="col-md-3">
                            <div class="info-card">
                                <div class="icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h3>Select Branch</h3>
                                <p>Filter by branch</p>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <div class="info-card">
                            <div class="icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                            <h3>Date Range</h3>
                            <p>Select sales period</p>
                        </div>
                    </div>

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <div class="info-card">
                            <div class="icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h3>By Salesman</h3>
                            <p>Individual performance</p>
                        </div>
                    </div>

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <div class="info-card">
                            <div class="icon">
                                <i class="bi bi-printer"></i>
                            </div>
                            <h3>Generate Report</h3>
                            <p>Print detailed analysis</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <form action="{{ route('dashboard.report-sales-summary-salesman') }}" method="GET" target="_blank">
                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <i class="bi bi-building"></i> Branch
                                        </label>
                                        <select name="branch_id" class="form-control">
                                            <option value="">All Branches</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                            @endif

                            <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '5' }}">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-calendar-event"></i> Date From
                                        <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="date"
                                           name="date_from"
                                           class="form-control"
                                           value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '5' }}">
                                <div class="form-group">
                                    <label>
                                        <i class="bi bi-calendar-check"></i> Date To
                                        <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="date"
                                           name="date_to"
                                           class="form-control"
                                           value="{{ request('date_to', now()->format('Y-m-d')) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '2' }}">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-print">
                                        <i class="bi bi-printer-fill"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Info Alert -->
                <div class="alert-info-custom">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Report Information</strong>
                    <p>This report provides a detailed analysis of sales performance by individual salesmen, grouped by category. It includes quantity sold, cost prices, retail prices, profit calculations, and a comprehensive summary for the selected period.</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Auto-fill dates if empty
            if (!$('input[name="date_from"]').val()) {
                const firstDay = new Date();
                firstDay.setDate(1);
                $('input[name="date_from"]').val(firstDay.toISOString().split('T')[0]);
            }

            if (!$('input[name="date_to"]').val()) {
                $('input[name="date_to"]').val(new Date().toISOString().split('T')[0]);
            }
        });
    </script>
@endsection
