@extends('dashboard.layouts.master')

@section('title', 'Create New Doctor')

@section('content')

    <style>
        .doctor-create-page {
            padding: 20px;
        }

        .box-doctor-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-doctor-create .box-header {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-doctor-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-doctor-create .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-doctor-create .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-doctor-create .box-header .btn {
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

        .box-doctor-create .box-header .btn:hover {
            background: white;
            color: #16a085;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .form-section {
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
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(22, 160, 133, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #16a085;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-group label .required {
            color: #e74c3c;
            font-weight: 700;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #16a085;
            box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .btn-submit-group {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
            margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-cancel {
            background: white;
            color: #666;
            border: 2px solid #ddd;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            margin: 0 10px;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #333;
            transform: translateY(-3px);
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

        .form-section {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-user-md"></i> Create New Doctor
            <small>Add a new doctor to the system</small>
        </h1>
    </section>

    <div class="doctor-create-page">
        <form action="{{ route('dashboard.post-add-doctor') }}" method="POST" id="doctorForm">
            @csrf
            @method('POST')

            <div class="box box-success box-doctor-create">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus-circle"></i> Doctor Information
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.get-all-doctors') }}" class="btn btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Doctors
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @include('dashboard.partials._errors')

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-user-md"></i>
                            </div>
                            <h4>Basic Information</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">
                                        Doctor Code
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="code"
                                           id="code"
                                           value="{{ $DoctorId }}"
                                           readonly>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Auto-generated doctor code
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        Doctor Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="name"
                                           id="name"
                                           value="{{ old('name') }}"
                                           placeholder="e.g., Dr. Ahmed Mohammed"
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Enter the doctor's full name
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="fa fa-check-circle"></i> Create Doctor
                        </button>
                        <a href="{{ route('dashboard.get-all-doctors') }}" class="btn btn-cancel">
                            <i class="fa fa-times-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Form submission
            $('#doctorForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Creating Doctor...');
            });
        });
    </script>
@endsection
