@extends('dashboard.layouts.master')

@section('title', 'Product Details')

@section('content')

    <style>
        .product-show-page {
            padding: 20px;
        }

        .box-product-details {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
        }

        .box-product-details .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
        }

        .box-product-details .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
        }

        .box-product-details .box-header .btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .box-product-details .box-header .btn:hover {
            background: white;
            color: #667eea;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-box .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 15px;
        }

        .stat-box.total .icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-box.available .icon { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .stat-box.reserved .icon { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        .stat-box.value .icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-box.sold .icon { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }

        .stat-box h3 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: #333;
        }

        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }

        /* Info Section */
        .info-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e8ecf7;
            margin-bottom: 20px;
        }

        .info-section h4 {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8ecf7;
        }

        .info-section h4 i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9ff;
            border-radius: 8px;
        }

        .info-item .label {
            font-size: 100% !important;
            font-weight: 700;
            color: #667eea;
            min-width: 120px;
        }

        .info-item .value {
            color: #333;
            font-weight: 600;
        }

        /* Branch Stock Table */
        .branch-stock-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .branch-stock-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .branch-stock-table thead th {
            color: white;
            font-weight: 700;
            padding: 15px;
            border: none;
        }

        .branch-stock-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .branch-stock-table tbody tr:hover {
            background: #f8f9ff;
        }

        .branch-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 700;
        }

        .stock-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 700;
            min-width: 60px;
            text-align: center;
        }

        .stock-badge.high { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .stock-badge.low { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .stock-badge.out { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }

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

        .box-product-details {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-box-seam"></i> Product Details
            <small>{{ $product->product_id }}</small>
        </h1>
    </section>

    <div class="product-show-page">
        <!-- Header -->
        <div class="box box-primary box-product-details">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-info-circle-fill"></i> {{ $product->product_id }} - {{ $product->description }}
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.get-update-product', $product->id) }}" class="btn btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit Product
                    </a>
                    <a href="{{ route('dashboard.get-all-products') }}" class="btn btn-sm" style="margin-left: 10px;">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-box total">
                <div class="icon">
                    <i class="bi bi-boxes"></i>
                </div>
                <h3>{{ number_format($totalQuantity) }}</h3>
                <p>Total Stock</p>
            </div>

            <div class="stat-box available">
                <div class="icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3>{{ number_format($totalAvailable) }}</h3>
                <p>Available</p>
            </div>

            <div class="stat-box reserved">
                <div class="icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <h3>{{ number_format($totalReserved) }}</h3>
                <p>Reserved</p>
            </div>

            <div class="stat-box value">
                <div class="icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3>{{ number_format($totalValue, 2) }}</h3>
                <p>Total Value</p>
            </div>

            <div class="stat-box sold">
                <div class="icon">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <h3>{{ number_format($totalSold) }}</h3>
                <p>Total Sold</p>
            </div>
        </div>

        <!-- Product Information -->
        <div class="info-section">
            <h4>
                <i class="bi bi-info-square-fill"></i>
                Product Information
            </h4>

            <div class="info-row">
                <div class="info-item">
                    <span class="label">Product ID:</span>
                    <span class="value">{{ $product->product_id }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Description:</span>
                    <span class="value">{{ $product->description }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Category:</span>
                    <span class="value">{{ $product->category->category_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Brand:</span>
                    <span class="value">{{ $product->brand->brand_name ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-item">
                    <span class="label">Model:</span>
                    <span class="value">{{ $product->model->model_id ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Color:</span>
                    <span class="value">{{ $product->color ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Size:</span>
                    <span class="value">{{ $product->size ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Type:</span>
                    <span class="value">{{ $product->type ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="info-section">
            <h4>
                <i class="bi bi-cash-stack"></i>
                Pricing Information
            </h4>

            <div class="info-row">
                <div class="info-item">
                    <span class="label">Cost Price:</span>
                    <span class="value">{{ number_format($product->price, 2) }} SAR</span>
                </div>
                <div class="info-item">
                    <span class="label">Retail Price:</span>
                    <span class="value">{{ number_format($product->retail_price, 2) }} SAR</span>
                </div>
                <div class="info-item">
                    <span class="label">Tax:</span>
                    <span class="value">{{ number_format($product->tax, 2) }}%</span>
                </div>
                <div class="info-item">
                    <span class="label">Discount:</span>
                    <span class="value">{{ ucfirst($product->discount_type) }}: {{ $product->discount_value }}</span>
                </div>
            </div>
        </div>

        <!-- Branch Stock Distribution -->
        <div class="info-section">
            <h4>
                <i class="bi bi-buildings"></i>
                Stock Distribution by Branch
            </h4>

            @if($branchStocks->count() > 0)
                <div class="table-responsive">
                    <table class="table branch-stock-table">
                        <thead>
                        <tr>
                            <th>Branch</th>
                            <th class="text-center">Total Stock</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Reserved</th>
                            <th class="text-center">Min Qty</th>
                            <th class="text-center">Max Qty</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($branchStocks as $stock)
                            <tr>
                                <td>
                                        <span class="branch-badge">
                                            <i class="bi bi-building"></i>
                                            {{ $stock->branch->name ?? $stock->branch->branch_name }}
                                        </span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($stock->quantity) }}</strong>
                                </td>
                                <td class="text-center">
                                        <span style="color: #27ae60; font-weight: 700;">
                                            {{ number_format($stock->available_quantity) }}
                                        </span>
                                </td>
                                <td class="text-center">
                                    @if($stock->reserved_quantity > 0)
                                        <span style="color: #3498db; font-weight: 700;">
                                                {{ number_format($stock->reserved_quantity) }}
                                            </span>
                                    @else
                                        <span style="color: #999;">0</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($stock->min_quantity) }}</td>
                                <td class="text-center">{{ number_format($stock->max_quantity) }}</td>
                                <td class="text-center">
                                    @php
                                        $status = $stock->stock_status;
                                        $badgeClass = 'high';
                                        if ($status['status'] === 'low_stock') {
                                            $badgeClass = 'low';
                                        } elseif ($status['status'] === 'out_of_stock') {
                                            $badgeClass = 'out';
                                        }
                                    @endphp
                                    <span class="stock-badge {{ $badgeClass }}">
                                            <i class="bi bi-{{ $status['icon'] }}"></i>
                                            {{ $status['label'] }}
                                        </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    No stock records found for this product in branches.
                </div>
            @endif
        </div>

        <!-- Sales History -->
        <div class="info-section">
            <h4>
                <i class="bi bi-graph-up"></i>
                Sales Summary
            </h4>

            <div class="info-row">
                <div class="info-item">
                    <span class="label">Total Units Sold:</span>
                    <span class="value">{{ number_format($totalSold) }} units</span>
                </div>
                <div class="info-item">
                    <span class="label">Number of Sales:</span>
                    <span class="value">{{ number_format($salesCount) }} transactions</span>
                </div>
                <div class="info-item">
                    <span class="label">Average per Sale:</span>
                    <span class="value">{{ $salesCount > 0 ? number_format($totalSold / $salesCount, 2) : '0' }} units</span>
                </div>
            </div>
        </div>
    </div>

@endsection
