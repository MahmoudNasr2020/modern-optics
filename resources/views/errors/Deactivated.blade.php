<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated</title>

    {{-- CSS Links --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            min-height: 100vh;
        }

        .deactivated-page-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .deactivated-content-wrapper {
            max-width: 750px;
            width: 100%;
            text-align: center;
        }

        .deactivated-animation-box {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            animation: slideInDown 0.6s ease-out;
        }

        .deactivated-animation-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(149, 165, 166, 0.1);
            border-radius: 50%;
            animation: floating 6s ease-in-out infinite;
        }

        .deactivated-animation-box::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(149, 165, 166, 0.05);
            border-radius: 50%;
            animation: floating 8s ease-in-out infinite reverse;
        }

        .deactivated-icon-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .deactivated-icon-circle {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(149, 165, 166, 0.3);
            animation: pulse 2s ease-in-out infinite;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .deactivated-icon-circle i {
            font-size: 70px;
            color: white;
        }

        .deactivated-title {
            font-size: 38px;
            font-weight: 900;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .deactivated-subtitle {
            font-size: 20px;
            color: #666;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .deactivated-message {
            font-size: 15px;
            color: #888;
            line-height: 1.8;
            max-width: 550px;
            margin: 0 auto 30px;
            position: relative;
            z-index: 2;
        }

        .user-info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e8ecf7;
            animation: slideInUp 0.6s ease-out 0.2s both;
        }

        .user-info-title {
            font-size: 18px;
            font-weight: 700;
            color: #95a5a6;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .user-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .user-info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            text-align: left;
        }

        .user-info-label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .user-info-label i {
            color: #95a5a6;
            font-size: 14px;
        }

        .user-info-value {
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
        }

        .reasons-box {
            background: linear-gradient(135deg, #fff3cd 0%, #fff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 4px solid #f39c12;
            animation: slideInUp 0.6s ease-out 0.3s both;
        }

        .reasons-title {
            font-size: 18px;
            font-weight: 700;
            color: #f39c12;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .reasons-list {
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }

        .reason-item {
            display: flex;
            align-items: start;
            gap: 12px;
            padding: 12px;
            margin-bottom: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #f0f2f5;
        }

        .reason-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .reason-icon i {
            color: white;
            font-size: 16px;
        }

        .reason-text {
            flex: 1;
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            padding-top: 5px;
        }

        .steps-box {
            background: linear-gradient(135deg, #e8f5e9 0%, #fff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #27ae60;
            animation: slideInUp 0.6s ease-out 0.4s both;
        }

        .steps-title {
            font-size: 18px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .steps-list {
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
        }

        .step-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e0e6ed;
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
        }

        .step-text {
            flex: 1;
            color: #666;
            font-size: 14px;
            line-height: 1.8;
            padding-top: 8px;
        }

        .contact-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            animation: slideInUp 0.6s ease-out 0.5s both;
        }

        .contact-title {
            font-size: 18px;
            font-weight: 700;
            color: #95a5a6;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .contact-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #e8ecf7;
            transition: all 0.3s;
            cursor: pointer;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(149, 165, 166, 0.2);
            border-color: #95a5a6;
        }

        .contact-card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .contact-card-icon i {
            color: white;
            font-size: 24px;
        }

        .contact-card-label {
            font-size: 14px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .contact-card-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .action-buttons-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            animation: slideInUp 0.6s ease-out 0.6s both;
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

        .btn-success-action {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-success-action:hover {
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
            .deactivated-title {
                font-size: 28px;
            }

            .deactivated-subtitle {
                font-size: 16px;
            }

            .deactivated-animation-box {
                padding: 40px 20px;
            }

            .btn-action {
                padding: 12px 30px;
                font-size: 14px;
            }

            .user-info-grid,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .deactivated-icon-circle {
                width: 120px;
                height: 120px;
            }

            .deactivated-icon-circle i {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>
<div class="deactivated-page-container">
    <div class="deactivated-content-wrapper">
        <!-- Main Deactivated Box -->
        <div class="deactivated-animation-box">
            <div class="deactivated-icon-container">
                <div class="deactivated-icon-circle">
                    <i class="bi bi-person-fill-slash"></i>
                </div>
            </div>
            <h1 class="deactivated-title">Account Deactivated</h1>
            <p class="deactivated-subtitle">Your account is currently inactive</p>
            <p class="deactivated-message">
                We're sorry, but your account has been deactivated by a system administrator.
                This means you cannot access the system at this time. Please read the information below
                to understand why this happened and what you can do next.
            </p>
        </div>

        <!-- User Information -->
        @if(isset($user))
            <div class="user-info-box">
                <div class="user-info-title">
                    <i class="bi bi-person-badge"></i>
                    <span>Your Account Information</span>
                </div>
                <div class="user-info-grid">
                    <div class="user-info-item">
                        <div class="user-info-label">
                            <i class="bi bi-person"></i>
                            Full Name
                        </div>
                        <div class="user-info-value">{{ $user->full_name ?? 'N/A' }}</div>
                    </div>

                    <div class="user-info-item">
                        <div class="user-info-label">
                            <i class="bi bi-envelope"></i>
                            Email Address
                        </div>
                        <div class="user-info-value">{{ $user->email ?? 'N/A' }}</div>
                    </div>

                    <div class="user-info-item">
                        <div class="user-info-label">
                            <i class="bi bi-shield-check"></i>
                            Account Status
                        </div>
                        <div class="user-info-value">
                        <span class="status-badge">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Possible Reasons -->
        <div class="reasons-box">
            <div class="reasons-title">
                <i class="bi bi-question-circle-fill"></i>
                <span>Why Was Your Account Deactivated?</span>
            </div>
            <div class="reasons-list">
                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="reason-text">
                        <strong>Policy Violation:</strong> Your account may have violated company policies or terms of service
                    </div>
                </div>

                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="reason-text">
                        <strong>Inactivity:</strong> Extended period of inactivity or discontinued employment
                    </div>
                </div>

                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="bi bi-lock"></i>
                    </div>
                    <div class="reason-text">
                        <strong>Security Reasons:</strong> Suspicious activity or security concerns detected
                    </div>
                </div>

                <div class="reason-item">
                    <div class="reason-icon">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <div class="reason-text">
                        <strong>Administrative Decision:</strong> Deactivated by management or HR department
                    </div>
                </div>
            </div>
        </div>

        <!-- Steps to Reactivate -->
        <div class="steps-box">
            <div class="steps-title">
                <i class="bi bi-check2-circle"></i>
                <span>How to Reactivate Your Account?</span>
            </div>
            <div class="steps-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-text">
                        <strong>Contact Your Administrator:</strong> Reach out to your system administrator or HR department
                        using the contact information below
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-text">
                        <strong>Explain Your Situation:</strong> Provide clear information about your role and why you need
                        account access restored
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-text">
                        <strong>Wait for Approval:</strong> Your request will be reviewed by the appropriate department.
                        This usually takes 1-2 business days
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-text">
                        <strong>Try Logging In Again:</strong> Once your account is reactivated, you'll receive a notification
                        and can log in normally
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        {{--<div class="contact-box">
            <div class="contact-title">
                <i class="bi bi-headset"></i>
                <span>Need Help? Contact Support</span>
            </div>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="contact-card-label">Phone Support</div>
                    <div class="contact-card-value">+1 (555) 123-4567</div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="contact-card-label">Email Support</div>
                    <div class="contact-card-value">support@example.com</div>
                </div>

                <div class="contact-card">
                    <div class="contact-card-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div class="contact-card-label">Live Chat</div>
                    <div class="contact-card-value">Available 24/7</div>
                </div>
            </div>
        </div>--}}

        <!-- Action Buttons -->
        <div class="action-buttons-container">
            <a href="{{ route('login') }}" class="btn-action btn-primary-action">
                <i class="bi bi-box-arrow-in-right"></i>
                Try Login Again
            </a>
            <a href="mailto:support@example.com?subject=Account Reactivation Request" class="btn-action btn-success-action">
                <i class="bi bi-envelope-check"></i>
                Request Reactivation
            </a>
            <a href="{{ url('/') }}" class="btn-action btn-secondary-action">
                <i class="bi bi-house-door"></i>
                Go to Home
            </a>
        </div>
    </div>
</div>
</body>
</html>
