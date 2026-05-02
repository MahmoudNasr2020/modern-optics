@extends('dashboard.layouts.master')

@section('title', 'Daily Closing')

@section('content')

    <style>
        .closing-page {
            padding: 20px;
        }

        .box-closing {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-closing .box-header {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-closing .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-closing .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-closing .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        /* Filter Box */
        .filter-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
        }

        .filter-box .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 14px;
            height: 45px;
        }

        .filter-box .form-control:focus {
            border-color: #8e44ad;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
        }

        .btn-show {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(142, 68, 173, 0.3);
            height: 45px;
            width: 100%;
        }

        .btn-show:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(142, 68, 173, 0.4);
            color: white;
        }

        .btn-show i {
            margin-right: 8px;
        }

        /* Info Cards */
        .info-cards {
            margin-bottom: 25px;
        }

        .info-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-card .icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 3px 10px rgba(142, 68, 173, 0.3);
        }

        .info-card h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }

        .info-card p {
            margin: 0;
            font-size: 13px;
            color: #999;
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

        .box-closing {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-calendar-check"></i> Daily Closing
            <small>View and manage daily closings</small>
        </h1>
    </section>

    <div class="closing-page">
        <div class="box box-primary box-closing">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-calendar-check"></i> Select Date & Branch
                </h3>
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Info Cards -->
                <div class="row info-cards">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="icon">
                                <i class="bi bi-calendar3"></i>
                            </div>
                            <h3>Select Date</h3>
                            <p>Choose the date for daily closing</p>
                        </div>
                    </div>
                    @if(auth()->user()->canSeeAllBranches())
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h3>Select Branch</h3>
                                <p>Choose the branch to view</p>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="icon">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h3>View Closing</h3>
                            <p>Review daily transactions</p>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="filter-box">
                    <form action="{{ route('dashboard.daily-closing.closing') }}" method="GET">
                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="branch_id">
                                            <i class="bi bi-building"></i> Branch
                                            <span style="color: #e74c3c;">*</span>
                                        </label>
                                        <select name="branch_id" id="branch_id" class="form-control" required>
                                            <option value="">-- Select Branch --</option>
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

                            <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '5' : '10' }}">
                                <div class="form-group">
                                    <label for="date">
                                        <i class="bi bi-calendar3"></i> Date
                                        <span style="color: #e74c3c;">*</span>
                                    </label>
                                    <input type="date"
                                           name="date"
                                           id="date"
                                           class="form-control"
                                           value="{{ request('date', now()->format('Y-m-d')) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-show">
                                        <i class="bi bi-search"></i> Show
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @if(request()->has('date') && !request()->has('branch_id') && auth()->user()->canSeeAllBranches())
                    <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Please select a branch</strong> to view the daily closing.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
