<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>إعادة تعيين كلمة المرور | Modern Optics</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">
    <style>
        body {
            background: url("{{ asset('assets/img/login.png.png') }}") repeat center center fixed;
        }
        .panel {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .panel-heading {
            background-color: #337ab7;
            color: white;
            padding: 20px;
            border-radius: 6px 6px 0 0;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .form-control {
            height: 44px;
            font-size: 15px;
        }
    </style>
</head>
<body>

<div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="col-md-6 col-sm-8 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Reset password</div>
            <div class="panel-body" style="padding: 30px;">
                <form method="POST" action="{{ route('password.update') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ $email ?? old('email') }}" required autofocus>
                        @error('email')
                        <span class="help-block text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required>
                        @error('password')
                        <span class="help-block text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Password Confirm</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required>
                    </div>

                    <div class="form-group text-center" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" style="font-weight: bold;">
                            Confirm reset
                        </button>
                    </div>

                    <p class="text-center text-muted">Modern Optics, Better Vision</p>
                </form>
            </div>
        </div>
    </div>
</div>
@include('dashboard.partials._Spinner')

<!-- Scripts -->
<script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>
</html>
