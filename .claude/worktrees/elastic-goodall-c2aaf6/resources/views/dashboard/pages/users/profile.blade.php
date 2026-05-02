@extends('dashboard.layouts.master')

@section('title', 'My Profile')

@section('content')

    <style>
        .profile-page {
            padding: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            border-radius: 12px 12px 0 0;
            position: relative;
            overflow: hidden;
            margin-bottom: 0;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .profile-header-content {
            display: flex;
            align-items: center;
            gap: 30px;
            position: relative;
            z-index: 1;
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            object-fit: cover;
        }

        .profile-info h2 {
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: 900;
        }

        .profile-info p {
            margin: 5px 0;
            opacity: 0.95;
            font-size: 15px;
        }

        .profile-info .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            margin-right: 5px;
        }

        .profile-body {
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .profile-tabs {
            display: flex;
            border-bottom: 2px solid #e8ecf7;
            padding: 0 30px;
            background: #f8f9ff;
        }

        .profile-tab {
            padding: 18px 30px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            color: #666;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-tab:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .profile-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: white;
        }

        .profile-content {
            padding: 40px 30px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #e8ecf7;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
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

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .image-upload-box {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px dashed #667eea;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .image-upload-box:hover {
            border-color: #764ba2;
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.15);
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: 4px solid white;
            transition: all 0.3s;
            object-fit: cover;
        }

        .image-preview:hover {
            transform: scale(1.05);
        }

        .btn-save {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .info-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e8ecf7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e8ecf7;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #667eea;
            min-width: 150px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #555;
            font-weight: 500;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-person-circle"></i> My Profile
            <small>Manage your account settings</small>
        </h1>
    </section>

    <div class="profile-page">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4><i class="bi bi-exclamation-triangle"></i> Errors:</h4>
                <ul style="margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-box">
            {{-- Profile Header --}}
            <div class="profile-header">
                <div class="profile-header-content">
                    <img src="{{ $user->image_path }}"
                         alt="{{ $user->full_name }}"
                         class="profile-avatar-large">
                    <div class="profile-info">
                        <h2>{{ $user->full_name }}</h2>
                        <p><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                        <p>
                            @foreach($user->roles as $role)
                                <span class="badge">
                                    <i class="bi bi-shield"></i> {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                        </p>
                        @if($user->branch)
                            <p><i class="bi bi-building"></i> {{ $user->branch->name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Profile Body --}}
            <div class="profile-body">
                {{-- Tabs --}}
                <div class="profile-tabs">
                    <div class="profile-tab active" data-tab="info">
                        <i class="bi bi-person"></i> Profile Information
                    </div>
                    <div class="profile-tab" data-tab="edit">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </div>
                    <div class="profile-tab" data-tab="password">
                        <i class="bi bi-key"></i> Change Password
                    </div>
                </div>

                {{-- Tab Content --}}
                <div class="profile-content">
                    {{-- Tab 1: Profile Information --}}
                    <div class="tab-pane active" id="info">
                        <div class="info-card">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-person"></i> Full Name
                                </div>
                                <div class="info-value">{{ $user->full_name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-envelope"></i> Email
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            @if($user->branch)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-building"></i> Branch
                                    </div>
                                    <div class="info-value">{{ $user->branch->name }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-shield"></i> Roles
                                </div>
                                <div class="info-value">
                                    @foreach($user->roles as $role)
                                        <span class="badge" style="background: #667eea; color: white; margin-right: 5px;">
                                            {{ $role->display_name ?? $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @if($user->salary)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-cash"></i> Salary
                                    </div>
                                    <div class="info-value">{{ number_format($user->salary, 2) }}</div>
                                </div>
                            @endif
                            @if($user->commission)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-percent"></i> Commission
                                    </div>
                                    <div class="info-value">{{ number_format($user->commission, 2) }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="bi bi-calendar"></i> Member Since
                                </div>
                                <div class="info-value">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Edit Profile --}}
                    <div class="tab-pane" id="edit">
                        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Basic Information --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="icon-box">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <h4>Basic Information</h4>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="first_name"
                                                   id="first_name"
                                                   value="{{ old('first_name', $user->first_name) }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="last_name"
                                                   id="last_name"
                                                   value="{{ old('last_name', $user->last_name) }}"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email"
                                                   class="form-control"
                                                   name="email"
                                                   id="email"
                                                   value="{{ old('email', $user->email) }}"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Profile Image --}}
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="icon-box">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    <h4>Profile Image</h4>
                                </div>

                                <div class="form-group">
                                    <label for="image">Upload New Image</label>
                                    <input type="file"
                                           class="form-control"
                                           name="image"
                                           id="image"
                                           accept="image/*">
                                </div>

                                <div class="image-upload-box">
                                    <img src="{{ $user->image_path }}"
                                         class="image-preview"
                                         id="imagePreview"
                                         alt="Profile Image">
                                    <p style="margin-top: 15px; color: #667eea;">
                                        <i class="bi bi-info-circle"></i>
                                        Click "Choose File" to change your profile image
                                    </p>
                                </div>
                            </div>

                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-check-circle"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 3: Change Password --}}
                    <div class="tab-pane" id="password">
                        <form action="{{ route('dashboard.profile.change-password') }}" method="POST">
                            @csrf

                            <div class="form-section">
                                <div class="section-header">
                                    <div class="icon-box">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                    <h4>Change Password</h4>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="current_password">Current Password</label>
                                            <input type="password"
                                                   class="form-control"
                                                   name="current_password"
                                                   id="current_password"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password"
                                                   class="form-control"
                                                   name="new_password"
                                                   id="new_password"
                                                   required>
                                            <small class="text-muted">Minimum 8 characters</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password_confirmation">Confirm New Password</label>
                                            <input type="password"
                                                   class="form-control"
                                                   name="new_password_confirmation"
                                                   id="new_password_confirmation"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-key"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Tab switching
            $('.profile-tab').on('click', function() {
                var targetTab = $(this).data('tab');

                // Update active tab
                $('.profile-tab').removeClass('active');
                $(this).addClass('active');

                // Update active content
                $('.tab-pane').removeClass('active');
                $('#' + targetTab).addClass('active');
            });

            // Image preview
            $('#image').on('change', function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').fadeOut(200, function() {
                            $(this).attr('src', e.target.result).fadeIn(200);
                        });
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Password confirmation validation
            $('#new_password_confirmation').on('keyup', function() {
                var password = $('#new_password').val();
                var confirmation = $(this).val();

                if (confirmation && password !== confirmation) {
                    $(this).css('border-color', '#e74c3c');
                } else if (confirmation) {
                    $(this).css('border-color', '#27ae60');
                }
            });
        });
    </script>
@endsection
