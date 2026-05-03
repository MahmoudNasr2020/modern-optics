@extends('layouts.error')

@section('title', 'Access Denied')

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
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(231, 76, 60, 0.3);
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
            font-size: 80px;
            font-weight: 900;
            color: white;
            line-height: 1;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            text-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -2px;
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

        .error-details-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 10px;
            border-left: 4px solid #e74c3c;
        }

        .error-details-item:last-child {
            margin-bottom: 0;
        }

        .error-details-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .error-details-icon i {
            color: white !important;
            font-size: 20px;
        }

        .error-details-text {
            text-align: left;
            flex: 1;
        }

        .error-details-label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .error-details-value {
            font-size: 15px;
            color: #333;
            font-weight: 600;
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

        .btn-success-action {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-success-action:hover {
            color: white;
        }

        .help-section {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border: 2px solid #f39c12;
            animation: slideInUp 0.6s ease-out 0.6s both;
        }

        .help-section-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #f39c12;
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
            content: '→';
            position: absolute;
            left: 0;
            color: #f39c12;
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
                font-size: 60px;
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
                        <i class="fa fa-ban"></i>
                    </div>
                </div>
                <div class="error-code">403</div>
                <h1 class="error-title">Access Denied</h1>
                <p class="error-message">
                    Sorry, you don't have permission to access this resource. This area is restricted to authorized users only.
                </p>
            </div>

            <!-- Error Details -->
            <div class="error-details-box">
                <div class="error-details-item">
                    <div class="error-details-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="error-details-text">
                        <div class="error-details-label">Error Type</div>
                        <div class="error-details-value">Permission Denied (403 Forbidden)</div>
                    </div>
                </div>

                <div class="error-details-item">
                    <div class="error-details-icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="error-details-text">
                        <div class="error-details-label">Current User</div>
                        <div class="error-details-value">{{ optional(auth()->user())->full_name ?? optional(auth()->user())->name ?? 'Guest' }}</div>
                    </div>
                </div>

                <div class="error-details-item">
                    <div class="error-details-icon">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="error-details-text">
                        <div class="error-details-label">Required Permission</div>
                        <div class="error-details-value">Administrator or Authorized Role</div>
                    </div>
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
                @if(auth()->check())
                    <a href="{{ route('dashboard.get-all-users') }}" class="btn-action btn-success-action">
                        <i class="fa fa-user"></i>
                        My Profile
                    </a>
                @endif
            </div>

            <!-- Help Section -->
            <div class="help-section">
                <div class="help-section-title">
                    <i class="fa fa-question-circle"></i>
                    <span>Need Help?</span>
                </div>
                <ul class="help-list">
                    <li>Contact your system administrator to request access permissions</li>
                    <li>Verify that you're logged in with the correct account</li>
                    <li>Check if your role has been recently updated</li>
                    <li>Return to the dashboard and try a different section</li>
                </ul>
            </div>
        </div>
    </div>

@endsection
