@extends('dashboard.layouts.master')

@section('title', 'Page Not Found')

@section('content')

    <style>
        .error-page-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .error-content-wrapper {
            max-width: 700px;
            width: 100%;
            text-align: center;
        }

        .error-animation-box {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(155, 89, 182, 0.3);
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            animation: slideInDown 0.6s ease-out;
        }

        .error-animation-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floating 6s ease-in-out infinite;
        }

        .error-animation-box::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: floating 8s ease-in-out infinite reverse;
        }

        .error-icon-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .error-icon-circle {
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid rgba(255, 255, 255, 0.4);
            animation: pulse 2s ease-in-out infinite;
        }

        .error-icon-circle i {
            font-size: 70px;
            color: white;
        }

        .error-code {
            font-size: 100px;
            font-weight: 900;
            color: white;
            line-height: 1;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            text-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -3px;
        }

        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .error-message {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.6;
            position: relative;
            z-index: 2;
            max-width: 500px;
            margin: 0 auto;
        }

        .search-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            animation: slideInUp 0.6s ease-out 0.2s both;
        }

        .search-box-title {
            font-size: 18px;
            font-weight: 700;
            color: #9b59b6;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .search-box-title i {
            font-size: 22px;
        }

        .search-input-group {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.1);
        }

        .search-button {
            padding: 15px 35px;
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.3s;
            cursor: pointer;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(155, 89, 182, 0.3);
        }

        .quick-links-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            animation: slideInUp 0.6s ease-out 0.3s both;
        }

        .quick-links-title {
            font-size: 18px;
            font-weight: 700;
            color: #9b59b6;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-link-item {
            padding: 15px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quick-link-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(155, 89, 182, 0.2);
            border-color: #9b59b6;
            text-decoration: none;
        }

        .quick-link-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .quick-link-icon i {
            color: white;
            font-size: 18px;
        }

        .quick-link-text {
            text-align: left;
        }

        .quick-link-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .quick-link-desc {
            font-size: 11px;
            color: #999;
        }

        .action-buttons-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            animation: slideInUp 0.6s ease-out 0.4s both;
        }

        .btn-action {
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            text-decoration: none;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-primary-action:hover {
            color: white;
        }

        .btn-secondary-action {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .btn-secondary-action:hover {
            color: white;
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .error-code {
                font-size: 70px;
            }

            .error-title {
                font-size: 24px;
            }

            .error-animation-box {
                padding: 40px 20px;
            }

            .btn-action {
                padding: 12px 30px;
                font-size: 14px;
            }

            .error-icon-circle {
                width: 120px;
                height: 120px;
            }

            .error-icon-circle i {
                font-size: 50px;
            }

            .search-input-group {
                flex-direction: column;
            }

            .quick-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="error-page-container">
        <div class="error-content-wrapper">
            <!-- Main Error Box -->
            <div class="error-animation-box">
                <div class="error-icon-container">
                    <div class="error-icon-circle">
                        <i class="fa fa-compass"></i>
                    </div>
                </div>
                <div class="error-code">404</div>
                <h1 class="error-title">Oops! Page Not Found</h1>
                <p class="error-message">
                    The page you're looking for doesn't exist or has been moved to another location. Let's help you find what you need!
                </p>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <div class="search-box-title">
                    <i class="fa fa-search"></i>
                    <span>Search for what you need</span>
                </div>
                <form action="{{ route('dashboard.index') }}" method="GET">
                    <div class="search-input-group">
                        <input type="text"
                               name="search"
                               class="search-input"
                               placeholder="Search for pages, users, products...">
                        <button type="submit" class="search-button">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="quick-links-box">
                <div class="quick-links-title">
                    <i class="fa fa-link"></i>
                    <span>Quick Links</span>
                </div>
                <div class="quick-links-grid">
                    <a href="{{ route('dashboard.index') }}" class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fa fa-home"></i>
                        </div>
                        <div class="quick-link-text">
                            <div class="quick-link-label">Dashboard</div>
                            <div class="quick-link-desc">Main page</div>
                        </div>
                    </a>

                  {{--  @if(auth()->user()->hasPermission('read_users'))
                        <a href="{{ route('dashboard.get-all-users') }}" class="quick-link-item">
                            <div class="quick-link-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="quick-link-text">
                                <div class="quick-link-label">Users</div>
                                <div class="quick-link-desc">Manage users</div>
                            </div>
                        </a>
                    @endif--}}

                    <a href="{{ route('dashboard.get-all-doctors') }}" class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fa fa-user-md"></i>
                        </div>
                        <div class="quick-link-text">
                            <div class="quick-link-label">Doctors</div>
                            <div class="quick-link-desc">View doctors</div>
                        </div>
                    </a>

                    <a href="javascript:history.back()" class="quick-link-item">
                        <div class="quick-link-icon">
                            <i class="fa fa-arrow-left"></i>
                        </div>
                        <div class="quick-link-text">
                            <div class="quick-link-label">Go Back</div>
                            <div class="quick-link-desc">Previous page</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons-container">
                <a href="{{ route('dashboard.index') }}" class="btn-action btn-primary-action">
                    <i class="fa fa-home"></i>
                    Go to Dashboard
                </a>
                <a href="javascript:history.back()" class="btn-action btn-secondary-action">
                    <i class="fa fa-arrow-left"></i>
                    Go Back
                </a>
            </div>
        </div>
    </div>

@endsection
