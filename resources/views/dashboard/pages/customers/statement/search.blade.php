@extends('dashboard.layouts.master')

@section('content')

    <style>
        .search-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-header h2 {
            color: #667eea;
            margin: 0;
        }

        .search-header p {
            color: #666;
            margin-top: 10px;
        }

        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input-group input {
            flex: 1;
            height: 50px;
            font-size: 16px;
            padding: 0 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .search-input-group input:focus {
            border-color: #667eea;
            outline: none;
        }

        .search-btn {
            height: 50px;
            padding: 0 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
        }

        .customer-not-found {
            text-align: center;
            padding: 40px;
            background: #fff5f5;
            border: 2px dashed #e74c3c;
            border-radius: 8px;
            margin-top: 20px;
        }

        .customer-not-found i {
            font-size: 48px;
            color: #e74c3c;
            margin-bottom: 15px;
        }

        .customer-found {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .customer-found h3 {
            margin: 0 0 15px 0;
        }

        .customer-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item i {
            font-size: 18px;
            opacity: 0.8;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
        }

        .view-full-btn {
            text-align: center;
            margin-top: 30px;
        }

        .view-full-btn a {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.3s;
        }

        .view-full-btn a:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .customer-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="search-container">
        <div class="search-header">
            <h2><i class="fa fa-search"></i> Customer Statement Search</h2>
            <p>Enter customer ID to view their account statement</p>
        </div>

        <div class="search-form">
            <form method="GET" action="{{ route('dashboard.customers.statement') }}">
                <div class="search-input-group">
                    <input type="text"
                           name="customer_id"
                           class="form-control"
                           placeholder="Enter Customer ID (e.g., 12345)"
                           value="{{ request('customer_id') }}"
                           required
                           autofocus>
                    <button type="submit" class="search-btn">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>

            <!-- رسالة لو العميل مش موجود -->
            @if(request('customer_id') && !$customer)
                <div class="customer-not-found">
                    <i class="fa fa-user-times"></i>
                    <h4>Customer Not Found</h4>
                    <p>No customer found with ID: <strong>{{ request('customer_id') }}</strong></p>
                    <p style="margin-top: 10px; color: #666;">Please check the customer ID and try again.</p>
                </div>
            @endif
        </div>
    </div>


@endsection
