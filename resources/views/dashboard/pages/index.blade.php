@extends('dashboard.layouts.master')

@section('title', 'Dashboard')

@section('content')

    <style>
        .dashboard-page {
            padding: 20px;
            background: #f4f6f9;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .welcome-card h2 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .welcome-card p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .welcome-card .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            position: relative;
            z-index: 1;
        }

        .welcome-card .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #667eea;
            font-weight: 700;
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 50px;
            opacity: 0.1;
        }

        .stat-card .stat-content {
            position: relative;
            z-index: 1;
        }

        .stat-card .stat-number {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 5px 0;
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-change {
            font-size: 12px;
            margin-top: 8px;
            font-weight: 600;
        }

        .stat-change.positive {
            color: #27ae60;
        }

        .stat-change.negative {
            color: #e74c3c;
        }

        /* Color variants */
        .stat-card.blue .stat-number { color: #3498db; }
        .stat-card.blue .stat-icon { color: #3498db; }

        .stat-card.green .stat-number { color: #27ae60; }
        .stat-card.green .stat-icon { color: #27ae60; }

        .stat-card.orange .stat-number { color: #f39c12; }
        .stat-card.orange .stat-icon { color: #f39c12; }

        .stat-card.purple .stat-number { color: #9b59b6; }
        .stat-card.purple .stat-icon { color: #9b59b6; }

        .stat-card.red .stat-number { color: #e74c3c; }
        .stat-card.red .stat-icon { color: #e74c3c; }

        .stat-card.teal .stat-number { color: #16a085; }
        .stat-card.teal .stat-icon { color: #16a085; }

        /* Charts Section */
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .chart-card .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }

        .chart-card .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .chart-card .chart-subtitle {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .quick-actions .action-header {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }

        .action-btn {
            display: block;
            padding: 15px 20px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateX(5px);
            text-decoration: none;
        }

        .action-btn i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Recent Activity */
        .recent-activity {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .recent-activity .activity-header {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .activity-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            color: white;
        }

        .activity-icon.blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .activity-icon.green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .activity-icon.orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .activity-icon.teal { background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%); }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }

        /* Top Products / Recent Customers */
        .top-products {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .top-products .products-header {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        /* Section Title */
        .section-title {
            color: #667eea;
            font-weight: 800;
            margin: 30px 0 20px 0;
            font-size: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }

            .welcome-card h2 {
                font-size: 22px;
            }
        }
    </style>

    <div class="dashboard-page">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2><i class="bi bi-speedometer2"></i> Welcome Back!</h2>
            <p>Here's what's happening with your store today.</p>
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                </div>
                <div>
                    <strong style="font-size: 18px;">{{ auth()->user()->full_name }}</strong><br>
                    <span style="opacity: 0.9;">
                    @if(auth()->user()->canSeeAllBranches())
                            <i class="bi bi-shield-check"></i> Super Admin
                        @else
                            <i class="bi bi-building"></i> {{ auth()->user()->branch->name ?? 'No Branch' }}
                        @endif
                </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row stats-row">
            <!-- Total Sales -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card blue">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_sales'], 2) }}</div>
                        <div class="stat-label">Total Sales (QAR)</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> {{ number_format($stats['sales_growth'], 1) }}% from last month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_invoices']) }}</div>
                        <div class="stat-label">Total Invoices</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> {{ $stats['invoices_today'] }} today
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card purple">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_customers']) }}</div>
                        <div class="stat-label">Total Customers</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> {{ $stats['new_customers_month'] }} new this month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card orange">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['total_products']) }}</div>
                        <div class="stat-label">Total Products</div>
                        <div class="stat-change">
                            <i class="bi bi-check-circle"></i> {{ $stats['active_products'] }} active
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Stats -->
        <div class="row stats-row">
            <!-- Pending Invoices -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card red">
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['pending_invoices']) }}</div>
                        <div class="stat-label">Pending Invoices</div>
                        <div class="stat-change">
                            <i class="bi bi-exclamation-circle"></i> Needs attention
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items to Deliver -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card teal">
                    <div class="stat-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['items_to_deliver']) }}</div>
                        <div class="stat-label">Items to Deliver</div>
                        <div class="stat-change">
                            <i class="bi bi-box-seam"></i> Ready for pickup
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card blue">
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['monthly_revenue'], 2) }}</div>
                        <div class="stat-label">This Month (QAR)</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> {{ number_format($stats['revenue_growth'], 1) }}% growth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['today_sales'], 2) }}</div>
                        <div class="stat-label">Today's Sales (QAR)</div>
                        <div class="stat-change positive">
                            <i class="bi bi-check-circle"></i> {{ $stats['invoices_today'] }} invoices
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Super Admin: Branches Performance -->
        @if(isset($isSuperAdmin) && $isSuperAdmin && isset($branchesData) && $branchesData->count() > 0)
            <h3 class="section-title">
                <i class="bi bi-buildings"></i> Branches Performance
            </h3>

            <!-- Branches Statistics Cards -->
            <div class="row stats-row">
                @foreach($branchesData as $branch)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="stat-card {{ $loop->first ? 'blue' : ($loop->iteration % 4 == 0 ? 'green' : ($loop->iteration % 3 == 0 ? 'purple' : 'orange')) }}">
                            <div class="stat-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ number_format($branch->total_sales, 2) }}</div>
                                <div class="stat-label">{{ $branch->name }}</div>
                                <div class="stat-change">
                                    <i class="bi bi-receipt"></i> {{ number_format($branch->total_invoices) }} invoices
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Best Branch & Branches Chart -->
            <div class="row">
                <!-- Best Branch Card -->
                @if(isset($bestBranch) && $bestBranch)
                    <div class="col-md-4">
                        <div class="chart-card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white;">
                            <div style="text-align: center; padding: 20px 0;">
                                <div style="font-size: 60px; margin-bottom: 10px;">🏆</div>
                                <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 10px; color: white;">
                                    Best Performing Branch
                                </h3>
                                <div style="font-size: 28px; font-weight: 800; margin-bottom: 5px;">
                                    {{ $bestBranch->name }}
                                </div>
                                <div style="font-size: 18px; opacity: 0.9; margin-bottom: 5px;">
                                    {{ number_format($bestBranch->total_sales, 2) }} QAR
                                </div>
                                <div style="font-size: 14px; opacity: 0.8;">
                                    <i class="bi bi-receipt"></i> {{ number_format($bestBranch->total_invoices) }} Invoices
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Branches Comparison Chart -->
                <div class="col-md-8">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div>
                                <div class="chart-title">
                                    <i class="bi bi-bar-chart-fill"></i> Branches Comparison
                                </div>
                                <div class="chart-subtitle">Total sales by branch</div>
                            </div>
                        </div>
                        <canvas id="branchesChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <!-- Charts Row -->
        <div class="row">
            <!-- Customers Registration Chart -->
            <div class="col-md-8">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">
                                <i class="bi bi-people-fill"></i> Customers Registration
                            </div>
                            <div class="chart-subtitle">New customers over last 12 months</div>
                        </div>
                    </div>
                    <canvas id="customersChart" height="80"></canvas>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="col-md-4">
                <div class="top-products">
                    <div class="products-header">
                        <i class="bi bi-person-plus-fill"></i> Recent Customers
                    </div>
                    @if(isset($recentCustomers) && $recentCustomers->count() > 0)
                        @foreach($recentCustomers as $customer)
                            <div class="product-item">
                                <div style="flex: 1;">
                                    <div class="product-name">
                                        <i class="bi bi-person-circle"></i> {{ $customer->english_name }}
                                    </div>
                                    <div style="font-size: 12px; color: #999; margin-top: 5px;">
                                        <i class="bi bi-hash"></i> {{ $customer->customer_id }}
                                        @if($customer->mobile_number)
                                            | <i class="bi bi-phone"></i> {{ $customer->mobile_number }}
                                        @endif
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 11px; color: #667eea; font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; color: #999;">No customers yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Second Charts Row -->
        <div class="row">
            <!-- Category Distribution -->
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">
                                <i class="bi bi-pie-chart-fill"></i> Sales by Category
                            </div>
                            <div class="chart-subtitle">Product category distribution</div>
                        </div>
                    </div>
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">
                                <i class="bi bi-credit-card-fill"></i> Payment Methods
                            </div>
                            <div class="chart-subtitle">Payment distribution</div>
                        </div>
                    </div>
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="quick-actions">
                    <div class="action-header">
                        <i class="bi bi-lightning-fill"></i> Quick Actions
                    </div>
                    <a href="{{ route('dashboard.get-all-customers') }}" class="action-btn">
                        <i class="bi bi-person-plus"></i> Add New Customer
                    </a>
                    <a href="{{ route('dashboard.get-all-products') }}" class="action-btn">
                        <i class="bi bi-box-seam"></i> Manage Products
                    </a>
                    <a href="{{ route('dashboard.report-sales-transactions') }}" class="action-btn">
                        <i class="bi bi-file-earmark-text"></i> View Reports
                    </a>
                    <a href="{{ route('dashboard.cashier-history') }}" class="action-btn">
                        <i class="bi bi-clock-history"></i> Cashier History
                    </a>
                </div>
            </div>

            <!-- Recent Activity & Doctors -->
            <div class="col-md-8">
                <!-- Recent Activity -->
                <div class="recent-activity">
                    <div class="activity-header">
                        <i class="bi bi-activity"></i> Recent Activity
                    </div>
                    @if(isset($recentInvoices) && $recentInvoices->count() > 0)
                        @foreach($recentInvoices as $invoice)
                            <div class="activity-item">
                                <div class="activity-icon {{ $invoice->status == 'delivered' ? 'green' : ($invoice->status == 'pending' ? 'orange' : 'blue') }}">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        Invoice #{{ $invoice->invoice_code }} - {{ $invoice->customer_name ?? 'Walk-in Customer' }}
                                    </div>
                                    <div class="activity-time">
                                        <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($invoice->created_at)->diffForHumans() }} -
                                        <strong>{{ number_format($invoice->total, 2) }} QAR</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; color: #999;">No recent activity</p>
                    @endif
                </div>

                <!-- Recent Doctors -->
                <div class="recent-activity" style="margin-top: 20px;">
                    <div class="activity-header">
                        <i class="bi bi-hospital"></i> Recently Added Doctors
                    </div>
                    @if(isset($recentDoctors) && $recentDoctors->count() > 0)
                        @foreach($recentDoctors as $doctor)
                            <div class="activity-item">
                                <div class="activity-icon teal">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        Dr. {{ $doctor->name }}
                                    </div>
                                    <div class="activity-time">
                                        <i class="bi bi-hash"></i> {{ $doctor->code }}
                                        | <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($doctor->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; color: #999;">No doctors added yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Customers Registration Chart
        const customersCtx = document.getElementById('customersChart').getContext('2d');
        const customersChart = new Chart(customersCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($customersChartData['labels']) !!},
                datasets: [{
                    label: 'New Customers',
                    data: {!! json_encode($customersChartData['data']) !!},
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Math.floor(value) + ' customers';
                            },
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryChartData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($categoryChartData['data']) !!},
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(39, 174, 96, 0.8)',
                        'rgba(243, 156, 18, 0.8)',
                        'rgba(155, 89, 182, 0.8)',
                        'rgba(231, 76, 60, 0.8)',
                        'rgba(52, 152, 219, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Payment Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(paymentCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($paymentChartData['labels']) !!},
                datasets: [{
                    label: 'Amount (QAR)',
                    data: {!! json_encode($paymentChartData['data']) !!},
                    backgroundColor: [
                        'rgba(243, 156, 18, 0.8)',
                        'rgba(26, 31, 113, 0.8)',
                        'rgba(235, 0, 27, 0.8)',
                        'rgba(0, 166, 81, 0.8)',
                        'rgba(52, 152, 219, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' QAR';
                            }
                        }
                    }
                }
            }
        });

        // Branches Chart (Super Admin Only)
        @if(isset($isSuperAdmin) && $isSuperAdmin && isset($branchesChartData))
        const branchesCtx = document.getElementById('branchesChart').getContext('2d');
        const branchesChart = new Chart(branchesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($branchesChartData['labels']) !!},
                datasets: [{
                    label: 'Sales (QAR)',
                    data: {!! json_encode($branchesChartData['data']) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' QAR';
                            }
                        }
                    }
                }
            }
        });
        @endif
    </script>
@endsection
