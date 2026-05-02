@extends('dashboard.layouts.master')

@section('title', 'Salesman Sales')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>

        /* ═══════════════════════════════════════════════
           LAYOUT
        ═══════════════════════════════════════════════ */
        .sm-page { padding: 20px; }

        /* ═══════════════════════════════════════════════
           PAGE HEADER
        ═══════════════════════════════════════════════ */
        .sm-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 16px;
            padding: 30px 35px;
            margin-bottom: 25px;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sm-header::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 250px; height: 250px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .sm-header::after {
            content: '';
            position: absolute;
            bottom: -80px; left: 20%;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .sm-header-left h1 {
            font-size: 26px; font-weight: 800; margin: 0 0 6px;
            position: relative; z-index: 1;
        }
        .sm-header-left p {
            font-size: 13px; opacity: .7; margin: 0;
            position: relative; z-index: 1;
        }
        .sm-header-right {
            font-size: 70px; opacity: .08;
            position: relative; z-index: 1;
        }

        /* ═══════════════════════════════════════════════
           FILTER BOX
        ═══════════════════════════════════════════════ */
        .sm-filter {
            background: #fff;
            border-radius: 14px;
            padding: 25px 30px;
            margin-bottom: 25px;
            border: 1px solid #eaeef2;
            box-shadow: 0 2px 12px rgba(0,0,0,.05);
        }
        .sm-filter-title {
            font-size: 14px; font-weight: 700; color: #1a1a2e;
            margin: 0 0 18px; padding-bottom: 12px;
            border-bottom: 2px solid #f0f2f5;
            display: flex; align-items: center; gap: 8px;
        }
        .sm-filter label {
            font-size: 11px; font-weight: 700; color: #666;
            text-transform: uppercase; letter-spacing: .4px;
            display: block; margin-bottom: 7px;
        }
        .sm-filter .form-control {
            border: 1.5px solid #e0e4ea;
            border-radius: 9px !important;
            height: 42px;
            font-size: 13px;
            transition: border-color .2s;
            padding: 0 12px;
        }
        .sm-filter .form-control:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15,52,96,.1);
            outline: none;
        }
        .btn-search {
            background: linear-gradient(135deg, #0f3460, #1a5276);
            color: white !important;
            border: none;
            border-radius: 9px;
            height: 42px;
            width: 100%;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity .2s;
            margin-top: 23px;
        }
        .btn-search:hover { opacity: .88; }

        /* ═══════════════════════════════════════════════
           RESULTS HEADER (summary bar)
        ═══════════════════════════════════════════════ */
        .results-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .results-bar-title {
            font-size: 16px; font-weight: 800; color: #1a1a2e;
            display: flex; align-items: center; gap: 8px;
        }
        .results-bar-title .badge-count {
            background: linear-gradient(135deg, #0f3460, #1a5276);
            color: white; border-radius: 20px;
            padding: 2px 12px; font-size: 12px; font-weight: 700;
        }
        .results-stats {
            display: flex; gap: 15px; flex-wrap: wrap;
        }
        .rs-stat {
            background: #f8fafc;
            border: 1px solid #e8ecf0;
            border-radius: 8px;
            padding: 8px 16px;
            text-align: center;
        }
        .rs-stat .rs-num { font-size: 16px; font-weight: 800; color: #0f3460; }
        .rs-stat .rs-lbl { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: .4px; }

        /* ═══════════════════════════════════════════════
           SALESMAN CARDS GRID
        ═══════════════════════════════════════════════ */
        .salesmen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .sm-card {
            background: white;
            border-radius: 16px;
            border: 2px solid #eaeef2;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
            overflow: hidden;
            cursor: pointer;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            position: relative;
        }
        .sm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,.12);
            border-color: #0f3460;
        }
        .sm-card.active {
            border-color: #0f3460;
            box-shadow: 0 8px 30px rgba(15,52,96,.18);
        }

        /* Card top accent */
        .sm-card-accent {
            height: 5px;
            background: linear-gradient(90deg, #0f3460, #1a5276, #2980b9);
        }

        /* Card body */
        .sm-card-body { padding: 20px; }

        /* Avatar */
        .sm-avatar-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }
        .sm-avatar {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
            overflow: hidden;
        }
        .sm-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .sm-name { font-size: 16px; font-weight: 800; color: #1a1a2e; margin: 0 0 2px; }
        .sm-rank { font-size: 11px; color: #888; font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .sm-rank .rank-badge {
            background: #f0f4f8; border-radius: 10px;
            padding: 2px 8px; font-size: 10px; font-weight: 700; color: #555;
        }

        /* Card stats */
        .sm-stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }
        .sm-stat-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 10px 8px;
            text-align: center;
        }
        .sm-stat-item .ssi-num { font-size: 17px; font-weight: 800; color: #1a1a2e; }
        .sm-stat-item .ssi-lbl { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: .3px; }

        /* Revenue highlight */
        .sm-revenue {
            background: linear-gradient(135deg, #e8f4fd 0%, #c8e6f8 100%);
            border-radius: 10px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .sm-revenue .rev-label { font-size: 11px; color: #1a5276; font-weight: 600; }
        .sm-revenue .rev-amount { font-size: 20px; font-weight: 900; color: #0f3460; }

        /* Category pills */
        .sm-categories {
            display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 14px;
        }
        .cat-pill {
            background: #f0f4f8;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 10px;
            color: #555;
            font-weight: 600;
        }

        /* Card footer / expand button */
        .sm-card-footer {
            border-top: 1px solid #f0f2f5;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px; font-weight: 600; color: #0f3460;
            background: #fafbfc;
            transition: background .2s;
        }
        .sm-card:hover .sm-card-footer { background: #f0f5fb; }
        .sm-card-footer .expand-icon {
            transition: transform .3s;
            font-size: 16px;
        }
        .sm-card.active .sm-card-footer .expand-icon { transform: rotate(180deg); }

        /* ═══════════════════════════════════════════════
           DETAIL PANEL (expands below clicked card)
        ═══════════════════════════════════════════════ */
        .sm-detail-panel {
            display: none;
            background: white;
            border-radius: 14px;
            border: 2px solid #0f3460;
            box-shadow: 0 8px 30px rgba(15,52,96,.12);
            margin-bottom: 20px;
            overflow: hidden;
            animation: slideDown .3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .sm-detail-panel.show { display: block; }

        .sm-detail-header {
            background: linear-gradient(135deg, #0f3460 0%, #1a5276 100%);
            color: white;
            padding: 18px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sm-detail-header h3 { font-size: 16px; font-weight: 700; margin: 0; }
        .sm-detail-header .close-btn {
            background: rgba(255,255,255,.15);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s;
        }
        .sm-detail-header .close-btn:hover { background: rgba(255,255,255,.25); }

        /* Detail summary stats */
        .sm-detail-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0;
            border-bottom: 1px solid #eaeef2;
        }
        .sm-dstat {
            padding: 18px;
            text-align: center;
            border-right: 1px solid #eaeef2;
        }
        .sm-dstat:last-child { border-right: none; }
        .sm-dstat .dstat-num { font-size: 22px; font-weight: 900; color: #0f3460; }
        .sm-dstat .dstat-lbl { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .4px; }

        /* Category breakdown inside detail */
        .sm-by-category {
            padding: 20px 25px;
            border-bottom: 1px solid #f0f2f5;
        }
        .sm-by-category h4 { font-size: 13px; font-weight: 700; color: #555; margin: 0 0 12px; }
        .cat-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 8px; font-size: 12px;
        }
        .cat-row .cat-name { font-weight: 600; color: #333; min-width: 120px; }
        .cat-row .cat-bar-wrap { flex: 1; background: #f0f4f8; border-radius: 4px; height: 8px; overflow: hidden; }
        .cat-row .cat-bar-fill { background: linear-gradient(90deg, #0f3460, #2980b9); height: 100%; border-radius: 4px; }
        .cat-row .cat-val { font-weight: 700; color: #0f3460; min-width: 80px; text-align: right; }

        /* Items Table inside detail */
        .sm-detail-table-wrap { padding: 20px 25px; }
        .sm-detail-table-wrap h4 { font-size: 13px; font-weight: 700; color: #555; margin: 0 0 12px; }

        .sm-detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .sm-detail-table thead { background: #f0f4f8; }
        .sm-detail-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            color: #555;
            border: none;
        }
        .sm-detail-table tbody tr { border-bottom: 1px solid #f5f7fa; transition: background .15s; }
        .sm-detail-table tbody tr:hover { background: #f8fafc; }
        .sm-detail-table tbody td { padding: 10px 12px; color: #333; }
        .sm-detail-table tbody td:first-child { font-weight: 700; color: #0f3460; }
        .sm-detail-table tfoot { background: #0f3460; }
        .sm-detail-table tfoot td { padding: 10px 12px; color: white; font-weight: 800; font-size: 12px; }

        /* Type badge */
        .type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .type-product { background: #d5f5e3; color: #1e8449; }
        .type-lens    { background: #d6eaf8; color: #1a5276; }

        /* ═══════════════════════════════════════════════
           PRINT BUTTON
        ═══════════════════════════════════════════════ */
        .btn-print-report {
            background: linear-gradient(135deg, #27ae60, #1e8449);
            color: white;
            border: none;
            border-radius: 9px;
            padding: 10px 22px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: opacity .2s;
            text-decoration: none;
        }
        .btn-print-report:hover { opacity: .88; color: white; }

        /* ═══════════════════════════════════════════════
           EMPTY STATE
        ═══════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 70px 20px;
            color: #bbb;
        }
        .empty-state i { font-size: 60px; display: block; margin-bottom: 15px; }
        .empty-state h3 { font-size: 20px; color: #999; font-weight: 600; margin-bottom: 6px; }
        .empty-state p  { font-size: 13px; color: #bbb; }

        /* Avatar colour palette */
        .av-1 { background: linear-gradient(135deg, #0f3460, #1a5276); }
        .av-2 { background: linear-gradient(135deg, #1e8449, #27ae60); }
        .av-3 { background: linear-gradient(135deg, #8e44ad, #9b59b6); }
        .av-4 { background: linear-gradient(135deg, #c0392b, #e74c3c); }
        .av-5 { background: linear-gradient(135deg, #e67e22, #f39c12); }
        .av-6 { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .av-7 { background: linear-gradient(135deg, #16a085, #1abc9c); }
        .av-8 { background: linear-gradient(135deg, #c0392b, #8e44ad); }

    </style>
@endsection


@section('content')
    <div class="sm-page">

        <!-- ═══ Page Header ═══ -->
        <div class="sm-header">
            <div class="sm-header-left">
                <h1><i class="bi bi-people-fill"></i> Salesman Sales Dashboard</h1>
                <p>تقرير مبيعات الموظفين — اضغط على أي موظف لرؤية تفاصيل مبيعاته</p>
            </div>
            <div class="sm-header-right">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
        </div>


        <!-- ═══ Filter Box ═══ -->
        <div class="sm-filter">
            <div class="sm-filter-title">
                <i class="bi bi-funnel-fill"></i> Filter Results
            </div>

            <form method="GET" action="{{ route('dashboard.salesman-sales') }}">
                <div class="row">

                    @if(auth()->user()->canSeeAllBranches())
                        <div class="col-md-3">
                            <label><i class="bi bi-building"></i> Branch</label>
                            <select name="branch_id" class="form-control">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    @endif

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <label><i class="bi bi-calendar-event"></i> Date From <span style="color:#e74c3c;">*</span></label>
                        <input type="date" name="date_from" class="form-control"
                               value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <label><i class="bi bi-calendar-check"></i> Date To <span style="color:#e74c3c;">*</span></label>
                        <input type="date" name="date_to" class="form-control"
                               value="{{ request('date_to', now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-{{ auth()->user()->canSeeAllBranches() ? '3' : '4' }}">
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>


        @if(request('date_from') && request('date_to'))

            @php
                $grandTotal   = $salesmenData->sum('total_amount');
                $grandItems   = $salesmenData->sum('total_items');
                $grandInvoices= $salesmenData->sum('total_invoices');
            @endphp

                <!-- ═══ Results Bar ═══ -->
            <div class="results-bar">
                <div class="results-bar-title">
                    <i class="bi bi-people-fill" style="color:#0f3460;"></i>
                    Salesmen Results
                    <span class="badge-count">{{ $salesmenData->count() }}</span>
                    @if($selectedBranch)
                        <small style="color:#888; font-weight:500; font-size:12px;">
                            — {{ $selectedBranch->name }}
                        </small>
                    @endif
                    &nbsp;
                    <a href="{{ route('dashboard.report-sales-summary-salesman') }}?date_from={{ request('date_from') }}&date_to={{ request('date_to') }}&branch_id={{ request('branch_id') }}"
                       target="_blank" class="btn-print-report">
                        <i class="bi bi-printer-fill"></i> Print Summary
                    </a>
                </div>
                <div class="results-stats">
                    <div class="rs-stat">
                        <div class="rs-num">{{ $grandInvoices }}</div>
                        <div class="rs-lbl">Invoices</div>
                    </div>
                    <div class="rs-stat">
                        <div class="rs-num">{{ $grandItems }}</div>
                        <div class="rs-lbl">Items Sold</div>
                    </div>
                    <div class="rs-stat">
                        <div class="rs-num" style="color:#1e8449;">{{ number_format($grandTotal, 2) }}</div>
                        <div class="rs-lbl">Total (QAR)</div>
                    </div>
                </div>
            </div>


            @if($salesmenData->isEmpty())

                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No Sales Data Found</h3>
                    <p>No delivered invoices found for the selected date range and branch.</p>
                </div>

            @else

                <!-- ═══ Salesman Cards ═══ -->
                @php
                    $avColors = ['av-1','av-2','av-3','av-4','av-5','av-6','av-7','av-8'];
                    $maxAmount = $salesmenData->max('total_amount') ?: 1;
                @endphp

                <div class="salesmen-grid" id="salesmenGrid">
                    @foreach($salesmenData as $idx => $sm)
                        @php
                            $initials  = collect(explode(' ', $sm['name']))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
                            $avClass   = $avColors[$idx % count($avColors)];
                            $pct       = $maxAmount > 0 ? round(($sm['total_amount'] / $maxAmount) * 100) : 0;

                            switch ($idx) {
                                case 0:
                                    $rankLabel = '🥇 Top Performer';
                                    break;
                                case 1:
                                    $rankLabel = '🥈 2nd Place';
                                    break;
                                case 2:
                                    $rankLabel = '🥉 3rd Place';
                                    break;
                                default:
                                    $rankLabel = '#' . ($idx + 1) . ' Rank';
                                    break;
                            }

                        @endphp

                        <div class="sm-card" id="card-{{ $idx }}" onclick="toggleDetail({{ $idx }})">
                            <div class="sm-card-accent"></div>
                            <div class="sm-card-body">

                                <!-- Avatar + Name -->
                                <div class="sm-avatar-row">
                                    <div class="sm-avatar {{ $avClass }}">
                                        @if($sm['image'])
                                            <img src="{{ asset('storage/uploads/images/users/' . $sm['image']) }}"
                                                 onerror="this.parentElement.innerHTML='{{ $initials }}'">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="sm-name">{{ $sm['name'] }}</div>
                                        <div class="sm-rank">
                                            <span class="rank-badge">{{ $rankLabel }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="sm-stats-row">
                                    <div class="sm-stat-item">
                                        <div class="ssi-num">{{ $sm['total_invoices'] }}</div>
                                        <div class="ssi-lbl">Invoices</div>
                                    </div>
                                    <div class="sm-stat-item">
                                        <div class="ssi-num">{{ $sm['total_items'] }}</div>
                                        <div class="ssi-lbl">Items</div>
                                    </div>
                                    <div class="sm-stat-item">
                                        <div class="ssi-num">{{ $sm['by_category']->count() }}</div>
                                        <div class="ssi-lbl">Categories</div>
                                    </div>
                                </div>

                                <!-- Revenue -->
                                <div class="sm-revenue">
                                    <div>
                                        <div class="rev-label">Total Revenue</div>
                                        <div style="font-size:10px; color:#888; margin-top:2px;">
                                            {{ $pct }}% of top performer
                                        </div>
                                    </div>
                                    <div class="rev-amount">{{ number_format($sm['total_amount'], 2) }}</div>
                                </div>

                                <!-- Category pills -->
                                <div class="sm-categories">
                                    @foreach($sm['by_category']->keys()->take(4) as $cat)
                                        <span class="cat-pill">{{ $cat ?? 'Uncategorized' }}</span>
                                    @endforeach
                                    @if($sm['by_category']->count() > 4)
                                        <span class="cat-pill">+{{ $sm['by_category']->count() - 4 }} more</span>
                                    @endif
                                </div>

                            </div>

                            <div class="sm-card-footer">
                                <span><i class="bi bi-list-ul"></i>&nbsp; View Sales Details</span>
                                <i class="bi bi-chevron-down expand-icon"></i>
                            </div>
                        </div>

                    @endforeach
                </div>
                <!-- end grid -->


                <!-- ═══ Detail Panels (one per salesman, rendered outside grid) ═══ -->
                @foreach($salesmenData as $idx => $sm)
                    @php $catMax = $sm['by_category']->max('amount') ?: 1; @endphp

                    <div class="sm-detail-panel" id="detail-{{ $idx }}">

                        <!-- Header -->
                        <div class="sm-detail-header">
                            <h3>
                                <i class="bi bi-person-badge-fill"></i>
                                {{ $sm['name'] }} — Sales Details
                                <small style="opacity:.7; font-weight:400; font-size:12px;">
                                    {{ request('date_from') }} → {{ request('date_to') }}
                                </small>
                            </h3>
                            <button class="close-btn" onclick="toggleDetail({{ $idx }})">
                                <i class="bi bi-x-lg"></i> Close
                            </button>
                        </div>

                        <!-- Summary Stats -->
                        <div class="sm-detail-stats">
                            <div class="sm-dstat">
                                <div class="dstat-num">{{ $sm['total_invoices'] }}</div>
                                <div class="dstat-lbl">Invoices</div>
                            </div>
                            <div class="sm-dstat">
                                <div class="dstat-num">{{ $sm['total_items'] }}</div>
                                <div class="dstat-lbl">Items Sold</div>
                            </div>
                            <div class="sm-dstat">
                                <div class="dstat-num">{{ $sm['by_category']->count() }}</div>
                                <div class="dstat-lbl">Categories</div>
                            </div>
                            <div class="sm-dstat">
                                <div class="dstat-num" style="color:#1e8449;">{{ number_format($sm['total_amount'], 2) }}</div>
                                <div class="dstat-lbl">Total (QAR)</div>
                            </div>
                            @if($sm['total_invoices'] > 0)
                                <div class="sm-dstat">
                                    <div class="dstat-num">{{ number_format($sm['total_amount'] / $sm['total_invoices'], 2) }}</div>
                                    <div class="dstat-lbl">Avg / Invoice</div>
                                </div>
                            @endif
                        </div>

                        <!-- Category Breakdown -->
                        <div class="sm-by-category">
                            <h4><i class="bi bi-pie-chart-fill"></i> By Category</h4>
                            @foreach($sm['by_category'] as $catName => $catData)
                                @php $catPct = round(($catData['amount'] / $catMax) * 100); @endphp
                                <div class="cat-row">
                                    <span class="cat-name">{{ $catName ?? 'Uncategorized' }}</span>
                                    <div class="cat-bar-wrap">
                                        <div class="cat-bar-fill" style="width:{{ $catPct }}%;"></div>
                                    </div>
                                    <span class="cat-val">
                        {{ $catData['count'] }} pcs &nbsp;|&nbsp; {{ number_format($catData['amount'], 2) }}
                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Items Table -->
                        <div class="sm-detail-table-wrap">
                            <h4><i class="bi bi-table"></i> All Sold Items ({{ $sm['total_items'] }} items across {{ $sm['total_invoices'] }} invoices)</h4>

                            <div style="overflow-x:auto;">
                                <table class="sm-detail-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th style="text-align:center;">Qty</th>
                                        <th style="text-align:right;">Price</th>
                                        <th style="text-align:right;">Net</th>
                                        <th style="text-align:right;">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sm['items'] as $i => $item)
                                        <tr>
                                            <td style="color:#aaa; font-size:11px;">{{ $i + 1 }}</td>
                                            <td><strong>{{ $item->invoice_code ?? '—' }}</strong></td>
                                            <td>{{ $item->category_name ?? '—' }}</td>
                                            <td>
                                                @if($item->type === 'product')
                                                    {{ $item->product_description ?? $item->description ?? '—' }}
                                                @else
                                                    {{ $item->lens_description ?? $item->description ?? '—' }}
                                                @endif
                                            </td>
                                            <td>
                            <span class="type-badge {{ $item->type === 'product' ? 'type-product' : 'type-lens' }}">
                                {{ $item->type }}
                            </span>
                                            </td>
                                            <td style="text-align:center; font-weight:700;">{{ $item->quantity ?? $item->item_count ?? 1 }}</td>
                                            <td style="text-align:right;">
                                                @if($item->type === 'product')
                                                    {{ number_format($item->product_price ?? $item->price ?? 0, 2) }}
                                                @else
                                                    {{ number_format($item->lens_price ?? $item->price ?? 0, 2) }}
                                                @endif
                                            </td>
                                            <td style="text-align:right;">{{ number_format($item->net ?? 0, 2) }}</td>
                                            <td style="text-align:right; font-weight:700; color:#0f3460;">{{ number_format($item->total ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="7">TOTAL — {{ $sm['total_items'] }} items</td>
                                        <td style="text-align:right;">{{ number_format($sm['items']->sum('net'), 2) }}</td>
                                        <td style="text-align:right;">{{ number_format($sm['total_amount'], 2) }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- end detail panel -->
                @endforeach


            @endif {{-- end if salesmenData not empty --}}

        @else

            <!-- No filter yet — show helper prompt -->
            <div class="empty-state">
                <i class="bi bi-funnel" style="color:#aed6f1;"></i>
                <h3 style="color:#555;">Select a Date Range</h3>
                <p>Choose the date range above (and optionally a branch) then click <strong>Search</strong> to view salesman sales data.</p>
            </div>

        @endif

    </div>
@endsection


@section('scripts')
    <script>
        // ─── Toggle detail panel for clicked salesman card ───────
        function toggleDetail(idx) {
            const card   = document.getElementById('card-' + idx);
            const detail = document.getElementById('detail-' + idx);

            const isActive = card.classList.contains('active');

            // Close all first
            document.querySelectorAll('.sm-card').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.sm-detail-panel').forEach(d => d.classList.remove('show'));

            if (!isActive) {
                card.classList.add('active');
                detail.classList.add('show');

                // ✅ Smooth scroll to detail panel
                setTimeout(() => {
                    detail.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        }

        // ─── Keyboard: close with Escape ─────────────────────────
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.sm-card').forEach(c => c.classList.remove('active'));
                document.querySelectorAll('.sm-detail-panel').forEach(d => d.classList.remove('show'));
            }
        });
    </script>
@endsection
