@extends('dashboard.layouts.master')
@section('title', 'Damaged Lenses Report (هالك)')
@section('content')

<style>
    .report-page{padding:20px}
    .box-report{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);overflow:hidden;border:none;margin-bottom:20px}
    .box-report .box-header{background:linear-gradient(135deg,#e74c3c 0%,#c0392b 100%);color:#fff;padding:25px 30px;border:none;position:relative;overflow:hidden}
    .box-report .box-header::before{content:'';position:absolute;top:-50%;right:-10%;width:200px;height:200px;background:rgba(255,255,255,.1);border-radius:50%}
    .box-report .box-header .box-title{font-size:22px;font-weight:700;position:relative;z-index:1;margin:0}
    .info-cards{margin-bottom:25px}
    .info-card{background:#fff;padding:25px 20px;border-radius:12px;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,.05);text-align:center;transition:all .3s;position:relative;overflow:hidden}
    .info-card::before{content:'';position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(135deg,#e74c3c,#c0392b)}
    .info-card:hover{transform:translateY(-5px);box-shadow:0 6px 20px rgba(0,0,0,.1)}
    .info-card .icon{width:60px;height:60px;margin:0 auto 15px;background:linear-gradient(135deg,#e74c3c,#c0392b);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;box-shadow:0 4px 12px rgba(231,76,60,.3)}
    .info-card h3{margin:0 0 8px;font-size:16px;color:#666;font-weight:600}
    .info-card p{margin:0;font-size:13px;color:#999}
    .filter-section{background:linear-gradient(135deg,#fff5f5,#fff);padding:30px;border-radius:10px;margin-bottom:25px;border:2px solid #f5c6cb}
    .filter-section label{font-weight:600;color:#555;font-size:13px;margin-bottom:8px;display:block}
    .filter-section label i{color:#e74c3c;margin-right:5px}
    .filter-section .form-control{border:2px solid #e0e6ed;border-radius:8px!important;padding:12px 15px;transition:all .3s;font-size:14px;height:45px}
    .filter-section .form-control:focus{border-color:#e74c3c;box-shadow:0 0 0 3px rgba(231,76,60,.1)}
    .btn-print{background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;border:none;padding:12px 30px;border-radius:8px;font-size:16px;font-weight:700;transition:all .3s;box-shadow:0 4px 12px rgba(231,76,60,.3);height:45px;width:100%;text-transform:uppercase;letter-spacing:.5px}
    .btn-print:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(231,76,60,.4);color:#fff}
    .alert-info-custom{background:linear-gradient(135deg,#fff5f5,#fce8e8);border-left:5px solid #e74c3c;padding:20px;border-radius:8px;margin-top:25px}
    .alert-info-custom i{color:#e74c3c;font-size:24px;margin-right:15px}
    .alert-info-custom strong{color:#e74c3c;font-size:16px}
    .alert-info-custom p{margin:5px 0 0 39px;color:#721c24;font-size:14px}
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-exclamation-triangle" style="color:#e74c3c;"></i> Damaged Lenses (هالك)
        <small>Defective lenses moved to damaged stock</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
        <li class="active">Damaged Lenses</li>
    </ol>
</section>

<div class="report-page">
    <div class="box box-danger box-report">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-exclamation-triangle"></i> Damaged Lenses Report (هالك)
            </h3>
        </div>

        <div class="box-body" style="padding:30px;">

            <div class="row info-cards">
                @if(auth()->user()->canSeeAllBranches())
                <div class="col-md-3">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-building"></i></div>
                        <h3>Branch</h3><p>Filter by branch</p>
                    </div>
                </div>
                @endif
                <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-calendar-range"></i></div>
                        <h3>Date Range</h3><p>From / To date</p>
                    </div>
                </div>
                <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                    <div class="info-card">
                        <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                        <h3>Damaged Stock</h3><p>هالك — defective lenses</p>
                    </div>
                </div>
                <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-printer"></i></div>
                        <h3>Generate Report</h3><p>Print detailed report</p>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <form action="{{ route('dashboard.report-damaged-lenses') }}" method="GET" target="_blank">
                    <div class="row">
                        @if(auth()->user()->canSeeAllBranches())
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-building"></i> Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id ? 'selected':'' }}>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                        @endif

                        <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '5' }}">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-event"></i> Date — From <span style="color:#e74c3c;">*</span></label>
                                <input type="date" name="date_from" class="form-control"
                                       value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '5' }}">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-event"></i> Date — To <span style="color:#e74c3c;">*</span></label>
                                <input type="date" name="date_to" class="form-control"
                                       value="{{ request('date_to', now()->format('Y-m-d')) }}" required>
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

            <div class="alert-info-custom">
                <i class="fa fa-info-circle"></i>
                <strong>Report Information</strong>
                <p>Shows all lenses marked as <strong>هالك (Damaged/Defective)</strong> in the selected date range. Includes lens details, side (R/L), quantity damaged, recovery information, branch, and the employee who reported the damage.</p>
            </div>
        </div>
    </div>
</div>

@endsection
