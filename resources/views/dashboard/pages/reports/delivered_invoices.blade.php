@extends('dashboard.layouts.master')

@section('title', 'Delivered Invoices Report')

@section('content')

<style>
    .report-page{padding:20px}
    .box-report{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);overflow:hidden;border:none;margin-bottom:20px}
    .box-report .box-header{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%);color:#fff;padding:25px 30px;border:none;position:relative;overflow:hidden}
    .box-report .box-header::before{content:'';position:absolute;top:-50%;right:-10%;width:200px;height:200px;background:rgba(255,255,255,.1);border-radius:50%}
    .box-report .box-header .box-title{font-size:22px;font-weight:700;position:relative;z-index:1;margin:0}
    .box-report .box-header .box-title i{background:rgba(255,255,255,.2);padding:10px;border-radius:8px;margin-right:10px}
    .info-cards{margin-bottom:25px}
    .info-card{background:#fff;padding:25px 20px;border-radius:12px;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,.05);text-align:center;transition:all .3s;position:relative;overflow:hidden}
    .info-card::before{content:'';position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(135deg,#00a86b,#008b5a)}
    .info-card:hover{transform:translateY(-5px);box-shadow:0 6px 20px rgba(0,0,0,.1)}
    .info-card .icon{width:60px;height:60px;margin:0 auto 15px;background:linear-gradient(135deg,#00a86b,#008b5a);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;box-shadow:0 4px 12px rgba(0,168,107,.3)}
    .info-card h3{margin:0 0 8px;font-size:16px;color:#666;font-weight:600}
    .info-card p{margin:0;font-size:13px;color:#999}
    .filter-section{background:linear-gradient(135deg,#f0fff8,#fff);padding:30px;border-radius:10px;margin-bottom:25px;border:2px solid #b2dfdb}
    .filter-section label{font-weight:600;color:#555;font-size:13px;margin-bottom:8px;display:block}
    .filter-section label i{color:#00a86b;margin-right:5px}
    .filter-section .form-control{border:2px solid #e0e6ed;border-radius:8px!important;padding:12px 15px;transition:all .3s;font-size:14px;height:45px}
    .filter-section .form-control:focus{border-color:#00a86b;box-shadow:0 0 0 3px rgba(0,168,107,.1)}
    .btn-print{background:linear-gradient(135deg,#00a86b,#008b5a);color:#fff;border:none;padding:12px 30px;border-radius:8px;font-size:16px;font-weight:700;transition:all .3s;box-shadow:0 4px 12px rgba(0,168,107,.3);height:45px;width:100%;text-transform:uppercase;letter-spacing:.5px}
    .btn-print:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(0,168,107,.4);color:#fff}
    .alert-info-custom{background:linear-gradient(135deg,#f0fff8,#e8f5e9);border-left:5px solid #00a86b;padding:20px;border-radius:8px;margin-top:25px}
    .alert-info-custom i{color:#00a86b;font-size:24px;margin-right:15px}
    .alert-info-custom strong{color:#00a86b;font-size:16px}
    .alert-info-custom p{margin:5px 0 0 39px;color:#2e7d32;font-size:14px}
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-check-circle-fill" style="color:#00a86b;"></i> Delivered Invoices
        <small>Invoices delivered to customers</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
        <li class="active">Delivered Invoices</li>
    </ol>
</section>

<div class="report-page">
    <div class="box box-success box-report">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="bi bi-check-circle-fill"></i> Delivered Invoices Report
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
                        <h3>Date Range</h3><p>From / To delivery date</p>
                    </div>
                </div>
                <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                    <div class="info-card">
                        <div class="icon"><i class="bi bi-check2-circle"></i></div>
                        <h3>Delivered Status</h3><p>Invoices handed to customer</p>
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
                <form action="{{ route('dashboard.report-delivered-invoices') }}" method="GET" target="_blank">
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
                                <label><i class="bi bi-calendar-event"></i> Delivery Date — From <span style="color:#e74c3c;">*</span></label>
                                <input type="date" name="date_from" class="form-control"
                                       value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '5' }}">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-event"></i> Delivery Date — To <span style="color:#e74c3c;">*</span></label>
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
                <i class="bi bi-info-circle-fill"></i>
                <strong>Report Information</strong>
                <p>Shows all invoices with status <strong>Delivered</strong>, filtered by delivery date range. Includes invoice code, customer, total amount, amount paid, and exact delivery date/time.</p>
            </div>
        </div>
    </div>
</div>

@endsection
