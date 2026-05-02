<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>إعادة تعيين كلمة المرور | Modern Optics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 3 -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">

    <style>
        body {
            /*background-color: #f4f6f9;*/
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
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
            text-align: center;
        }
        .form-control {
            height: 44px;
            font-size: 15px;
        }
    </style>
</head>
<body>

<div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;" >
    <div class="col-md-6 col-sm-8 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Send password reset link</div>
            <div class="panel-body" style="padding: 30px;">

                @if (session('status'))
                    <div class="alert alert-success text-center" style="font-weight: bold;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <span class="help-block text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group text-center" style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" style="font-weight: bold;">
                            Send reset link
                        </button>
                    </div>

                    <p class="text-center text-muted" style="margin-top: 10px;">Modern Optics, Better Vision</p>
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
