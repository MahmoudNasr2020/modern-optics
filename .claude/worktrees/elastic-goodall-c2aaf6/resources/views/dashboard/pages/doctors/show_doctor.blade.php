@extends('dashboard.layouts.master')

@section('title', 'Doctor Details')

@section('content')

    <style>
        .doctor-show-page {
            padding: 20px;
        }

        .box-doctor-show {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-doctor-show .box-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-doctor-show .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-doctor-show .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-doctor-show .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-doctor-show .box-header .btn {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .box-doctor-show .box-header .btn:hover {
            background: white;
            color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .info-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
        }

        .section-header .icon-box {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #3498db;
        }

        .info-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            min-width: 150px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i {
            color: #3498db;
            font-size: 16px;
        }

        .info-value {
            color: #333;
            font-size: 15px;
        }

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

        .info-section {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-eye"></i> Doctor Details
            <small>View doctor information</small>
        </h1>
    </section>

    <div class="doctor-show-page">
        <div class="box box-info box-doctor-show">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-user-md"></i> Doctor: {{ $doctor->name }}
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.get-all-doctors') }}" class="btn btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to Doctors
                    </a>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                @include('dashboard.partials._errors')

                <!-- Doctor Information -->
                <div class="info-section">
                    <div class="section-header">
                        <div class="icon-box">
                            <i class="fa fa-user-md"></i>
                        </div>
                        <h4>Doctor Information</h4>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fa fa-barcode"></i>
                            Doctor Code:
                        </div>
                        <div class="info-value">
                            <strong>{{ $doctor->code }}</strong>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fa fa-user"></i>
                            Doctor Name:
                        </div>
                        <div class="info-value">
                            {{ $doctor->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
