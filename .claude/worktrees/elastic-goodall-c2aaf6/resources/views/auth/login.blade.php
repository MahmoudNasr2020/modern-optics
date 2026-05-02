<!DOCTYPE html>
<html class="lockscreen">
<head>
    <meta charset="UTF-8">
    <title>Modern Optics - Login</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Theme style -->
    <link href="{{asset('assets/css/AdminLTE.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">

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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Circles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
        }

        body::after {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, 50px) scale(1.1); }
            50% { transform: translate(100px, -50px) scale(0.9); }
            75% { transform: translate(-50px, 100px) scale(1.05); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        .login-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
        }

        /* Header Section */
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        .company-name {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-weight: 500;
        }

        /* Time Display */
        .time-display {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 15px;
            backdrop-filter: blur(10px);
        }

        #time {
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        /* Form Section */
        .login-body {
            padding: 40px 35px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h3 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0 0 5px 0;
        }

        .welcome-text p {
            color: #999;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
        }

        .help-block {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .forgot-password {
            text-align: right;
            margin-top: -15px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #667eea;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .forgot-password a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #f0f2f5;
            background: #fafbfc;
        }

        .login-footer p {
            color: #999;
            font-size: 13px;
            margin: 0;
        }

        .login-footer strong {
            color: #667eea;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
                padding: 15px;
            }

            .login-body {
                padding: 30px 25px;
            }

            .company-name {
                font-size: 26px;
            }

            .welcome-text h3 {
                font-size: 20px;
            }
        }

        /* Loading state for button */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
<div class="login-container">
    <div class="login-box">
        <!-- Header -->
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="bi bi-eyeglasses"></i>
                </div>
                <h1 class="company-name">Modern Optics</h1>
                <p class="company-tagline">Better Vision, Better Life</p>
                <div class="time-display">
                    <h4 id="time"></h4>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="login-body">
            <div class="welcome-text">
                <h3>Welcome Back!</h3>
                <p>Please login to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required
                               autocomplete="email" autofocus placeholder="Enter your email">
                    </div>
                    @error('email')
                    <span class="help-block">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password"
                               placeholder="Enter your password">
                    </div>
                    @error('password')
                    <span class="help-block">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">
                        <i class="bi bi-question-circle"></i> Forgot your password?
                    </a>
                </div>

                <!-- Login Button -->
                <div class="form-group">
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; {{ date('Y') }} <strong>Modern Optics</strong> - All Rights Reserved</p>
        </div>
    </div>
</div>

@include('dashboard.partials._Spinner')

<!-- jQuery -->
<script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    $(function() {
        startTime();
    });

    // Time Display
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();

        m = checkTime(m);
        s = checkTime(s);

        var day_or_night = (h > 11) ? "PM" : "AM";

        if (h > 12) h -= 12;
        if (h === 0) h = 12;

        $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
        setTimeout(function() {
            startTime()
        }, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    // Form submission with loading state
    $('#loginForm').on('submit', function() {
        var btn = $('#loginBtn');
        btn.addClass('loading');
        btn.prop('disabled', true);
    });

    // Input focus animations
    $('.form-control').on('focus', function() {
        $(this).parent().find('.input-icon').css('color', '#667eea');
    });

    $('.form-control').on('blur', function() {
        $(this).parent().find('.input-icon').css('color', '#999');
    });
</script>
</body>
</html>
