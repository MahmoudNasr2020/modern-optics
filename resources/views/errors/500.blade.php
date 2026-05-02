@extends('dashboard.layouts.master')

@section('title', 'Server Error')

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
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(230, 126, 34, 0.3);
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
            animation: shake 3s ease-in-out infinite;
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

        .error-details-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            animation: slideInUp 0.6s ease-out 0.2s both;
        }

        .error-details-title {
            font-size: 18px;
            font-weight: 700;
            color: #e67e22;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .error-details-content {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #e67e22;
            text-align: left;
        }

        .error-details-item {
            padding: 10px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .error-details-item:last-child {
            border-bottom: none;
        }

        .error-details-label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .error-details-value {
            font-size: 14px;
            color: #333;
            font-family: 'Courier New', monospace;
            background: white;
            padding: 8px 12px;
            border-radius: 5px;
            word-break: break-all;
        }

        .action-buttons-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            animation: slideInUp 0.6s ease-out 0.3s both;
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

        .btn-warning-action {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-warning-action:hover {
            color: white;
        }

        .btn-secondary-action {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .btn-secondary-action:hover {
            color: white;
        }

        .help-section {
            background: linear-gradient(135deg, #e8f5e9 0%, #fff 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border: 2px solid #27ae60;
            animation: slideInUp 0.6s ease-out 0.4s both;
        }

        .help-section-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 15px;
        }

        .help-section-title i {
            font-size: 24px;
        }

        .help-list {
            text-align: left;
            max-width: 500px;
            margin: 0 auto;
            list-style: none;
            padding: 0;
        }

        .help-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .help-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: 700;
            font-size: 18px;
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

        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-5deg);
            }
            75% {
                transform: rotate(5deg);
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
        }
    </style>

    <div class="error-page-container">
        <div class="error-content-wrapper">
            <!-- Main Error Box -->
            <div class="error-animation-box">
                <div class="error-icon-container">
                    <div class="error-icon-circle">
                        <i class="bi bi-server"></i>
                    </div>
                </div>
                <div class="error-code">500</div>
                <h1 class="error-title">Internal Server Error</h1>
                <p class="error-message">
                    Oops! Something went wrong on our end. Our team has been notified and we're working to fix the issue as quickly as possible.
                </p>
            </div>

            <!-- Error Details (Only show in development mode) -->
            @if(config('app.debug') && isset($exception))
                <div class="error-details-box">
                    <div class="error-details-title">
                        <i class="fa fa-code"></i>
                        <span>Error Details (Development Mode)</span>
                    </div>
                    <div class="error-details-content">
                        <div class="error-details-item">
                            <div class="error-details-label">Error Message</div>
                            <div class="error-details-value">{{ $exception->getMessage() ?? 'Unknown error' }}</div>
                        </div>
                        <div class="error-details-item">
                            <div class="error-details-label">File</div>
                            <div class="error-details-value">{{ $exception->getFile() ?? 'Unknown file' }}</div>
                        </div>
                        <div class="error-details-item">
                            <div class="error-details-label">Line</div>
                            <div class="error-details-value">{{ $exception->getLine() ?? 'Unknown line' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons-container">
                <a href="{{ route('dashboard.index') }}" class="btn-action btn-primary-action">
                    <i class="fa fa-home"></i>
                    Go to Dashboard
                </a>
                <a href="javascript:location.reload()" class="btn-action btn-warning-action">
                    <i class="fa fa-refresh"></i>
                    Reload Page
                </a>
                <a href="javascript:history.back()" class="btn-action btn-secondary-action">
                    <i class="fa fa-arrow-left"></i>
                    Go Back
                </a>
            </div>

            <!-- Help Section -->
            <div class="help-section">
                <div class="help-section-title">
                    <i class="fa fa-lightbulb-o"></i>
                    <span>What Can You Do?</span>
                </div>
                <ul class="help-list">
                    <li>Try refreshing the page - sometimes temporary issues resolve themselves</li>
                    <li>Clear your browser cache and cookies</li>
                    <li>Return to the dashboard and try again later</li>
                    <li>Contact technical support if the problem persists</li>
                    <li>Check your internet connection</li>
                </ul>
            </div>
        </div>
    </div>

@endsection
