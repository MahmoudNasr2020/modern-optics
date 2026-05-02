<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Under Maintenance</title>

    {{-- CSS Links --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .maintenance-page-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .maintenance-content-wrapper {
            max-width: 700px;
            width: 100%;
            text-align: center;
        }

        .maintenance-animation-box {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            animation: slideInDown 0.6s ease-out;
        }

        .maintenance-icon-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .maintenance-icon-circle {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: rotate 3s linear infinite;
        }

        .maintenance-icon-circle i {
            font-size: 70px;
            color: white;
        }

        .maintenance-title {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .maintenance-subtitle {
            font-size: 18px;
            color: #666;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .maintenance-message {
            font-size: 15px;
            color: #888;
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .countdown-container {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e8ecf7;
        }

        .countdown-title {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .countdown-timer {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            min-width: 100px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
            border: 2px solid #e8ecf7;
        }

        .countdown-value {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 8px;
        }

        .countdown-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .progress-container {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #e8ecf7;
        }

        .progress-title {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .progress-bar-wrapper {
            height: 12px;
            background: #e8ecf7;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            animation: progressAnimation 2s ease-in-out;
        }

        .features-updating {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e8ecf7;
        }

        .features-title {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .feature-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon i {
            color: white;
            font-size: 18px;
        }

        .feature-text {
            text-align: left;
            flex: 1;
        }

        .feature-label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .feature-status {
            font-size: 11px;
            color: #27ae60;
            font-weight: 600;
        }

        .contact-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-title {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .contact-info {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }

        .contact-item i {
            color: #667eea;
            font-size: 16px;
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

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes progressAnimation {
            from {
                width: 0;
            }
            to {
                width: 75%;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .maintenance-title {
                font-size: 28px;
            }

            .maintenance-subtitle {
                font-size: 16px;
            }

            .maintenance-animation-box {
                padding: 40px 20px;
            }

            .countdown-item {
                min-width: 80px;
                padding: 15px;
            }

            .countdown-value {
                font-size: 28px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .maintenance-icon-circle {
                width: 120px;
                height: 120px;
            }

            .maintenance-icon-circle i {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>
<div class="maintenance-page-container">
    <div class="maintenance-content-wrapper">
        <!-- Main Maintenance Box -->
        <div class="maintenance-animation-box">
            <div class="maintenance-icon-container">
                <div class="maintenance-icon-circle">
                    <i class="fa fa-cogs"></i>
                </div>
            </div>
            <h1 class="maintenance-title">We'll Be Back Soon!</h1>
            <p class="maintenance-subtitle">System Under Maintenance</p>
            <p class="maintenance-message">
                {{ $message ?? 'We\'re currently performing scheduled maintenance to improve your experience. Our system will be back online shortly. Thank you for your patience!' }}
            </p>
        </div>

        <!-- Countdown Timer -->
        <div class="countdown-container">
            <div class="countdown-title">
                <i class="fa fa-clock-o"></i>
                <span>Estimated Time Remaining</span>
            </div>
            <div class="countdown-timer">
                <div class="countdown-item">
                    <div class="countdown-value" id="hours">02</div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-value" id="minutes">30</div>
                    <div class="countdown-label">Minutes</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-value" id="seconds">45</div>
                    <div class="countdown-label">Seconds</div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-title">
                <span>Maintenance Progress</span>
                <span>75%</span>
            </div>
            <div class="progress-bar-wrapper">
                <div class="progress-bar-fill" style="width: 75%;"></div>
            </div>
        </div>

        <!-- Features Being Updated -->
        <div class="features-updating">
            <div class="features-title">
                <i class="fa fa-wrench"></i>
                <span>What We're Updating</span>
            </div>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-label">Database</div>
                        <div class="feature-status">Optimizing...</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-label">Security</div>
                        <div class="feature-status">Upgrading...</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-label">Performance</div>
                        <div class="feature-status">Improving...</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa fa-code"></i>
                    </div>
                    <div class="feature-text">
                        <div class="feature-label">New Features</div>
                        <div class="feature-status">Installing...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="contact-section">
            <div class="contact-title">
                <i class="fa fa-envelope"></i>
                <span>Need Immediate Help?</span>
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fa fa-phone"></i>
                    <span>+1 (555) 123-4567</span>
                </div>
                <div class="contact-item">
                    <i class="fa fa-envelope"></i>
                    <span>support@example.com</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple countdown timer
    setInterval(function() {
        let seconds = document.getElementById('seconds');
        let minutes = document.getElementById('minutes');
        let hours = document.getElementById('hours');

        let sec = parseInt(seconds.innerText);
        let min = parseInt(minutes.innerText);
        let hrs = parseInt(hours.innerText);

        if(sec > 0) {
            sec--;
            seconds.innerText = sec.toString().padStart(2, '0');
        } else if(min > 0) {
            min--;
            minutes.innerText = min.toString().padStart(2, '0');
            seconds.innerText = '59';
        } else if(hrs > 0) {
            hrs--;
            hours.innerText = hrs.toString().padStart(2, '0');
            minutes
