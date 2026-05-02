@extends('dashboard.layouts.master')

@section('title', 'Edit User')

@section('content')

    <style>
        .user-edit-page {
            padding: 20px;
        }

        .box-user-edit {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-user-edit .box-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-user-edit .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-user-edit .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-user-edit .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-user-edit .box-header .btn {
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

        .box-user-edit .box-header .btn:hover {
            background: white;
            color: #f39c12;
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
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #f39c12;
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
            /*padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .image-preview-box {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border: 2px dashed #f39c12;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .image-preview-box:hover {
            border-color: #e67e22;
            box-shadow: 0 3px 12px rgba(243, 156, 18, 0.15);
        }

        .imag-preview {
            border-radius: 50%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: 4px solid white;
            transition: all 0.3s;
        }

        .imag-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.3);
        }

        .upload-hint {
            margin-top: 15px;
            color: #f39c12;
            font-size: 13px;
            font-weight: 600;
        }

        .upload-hint i {
            margin-right: 5px;
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

        /* Custom Select Styling */
        select.form-control[multiple] {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border: 2px solid #e0e6ed;
        }

        select.form-control[multiple] option {
            padding: 10px;
            border-radius: 4px;
            margin: 2px 0;
        }

        select.form-control[multiple] option:checked {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        /* Password Section Alert */
        .password-note {
            background: linear-gradient(135deg, #fff3cd 0%, #fff 100%);
            border-left: 5px solid #f39c12;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .password-note i {
            font-size: 18px;
            margin-right: 10px;
            color: #f39c12;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-pencil-square"></i> Edit User
            <small>Update user information and settings</small>
        </h1>
    </section>

    <div class="user-edit-page">
        <form action="{{ route('dashboard.post-update-user', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf

            <div class="box box-warning box-user-edit">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-person-fill-gear"></i> Edit User: {{ $user->full_name }}
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.get-all-users') }}" class="btn btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4><i class="bi bi-exclamation-triangle-fill"></i> Validation Errors:</h4>
                            <ul style="margin-bottom: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <h4>Basic Information</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">
                                        First Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="first_name"
                                           id="first_name"
                                           value="{{ $user->first_name }}"
                                           placeholder="e.g., Ahmed"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        User's first name
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">
                                        Last Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           name="last_name"
                                           id="last_name"
                                           value="{{ $user->last_name }}"
                                           placeholder="e.g., Mohammed"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        User's last name
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">
                                        Email Address
                                        <span class="required">*</span>
                                    </label>
                                    <input type="email"
                                           class="form-control"
                                           name="email"
                                           id="email"
                                           value="{{ $user->email }}"
                                           placeholder="user@example.com"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Valid email address for login and communication
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Active Status -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="icon-box">
                                    <i class="bi bi-toggle-on"></i>
                                </div>
                                <h4>Account Status</h4>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="checkbox" style="padding: 15px; background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%); border: 2px solid #e0e6ed; border-radius: 8px;">
                                            <label style="font-size: 15px; font-weight: 600; cursor: pointer;">
                                                <input type="checkbox"
                                                       name="is_active"
                                                       id="is_active"
                                                    {{ $user->is_active ? 'checked' : '' }}>
                                                <span style="margin-left: 10px;">Active User</span>
                                            </label>
                                            <p class="help-text" style="margin-left: 25px; margin-top: 8px;">
                                                <i class="bi bi-info-circle"></i>
                                                When unchecked, user will not be able to login to the system
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Password Section (Optional) -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <h4>Change Password (Optional)</h4>
                        </div>

                        <div class="password-note">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Note:</strong> Leave password fields empty if you don't want to change the current password.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password"
                                           class="form-control"
                                           name="password"
                                           id="password"
                                           placeholder="Enter new password">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Minimum 8 characters, include letters and numbers
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <input type="password"
                                           class="form-control"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           placeholder="Re-enter new password">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Must match the new password
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branch & Role Assignment -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4>Branch & Role Assignment</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_id">
                                        Branch
                                        <span class="required">*</span>
                                    </label>
                                    <select name="branch_id" id="branch_id" class="form-control" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Assign user to a specific branch location
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roles">
                                        User Roles
                                        <span class="required">*</span>
                                    </label>
                                    <select name="roles[]" id="roles" class="form-control" multiple size="5" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ $role->display_name ?? $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Hold Ctrl (Windows) or Cmd (Mac) to select multiple roles
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h4>Employment & Compensation</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary">Salary</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="salary"
                                           id="salary"
                                           value="{{ $user->salary }}"
                                           placeholder="0.00">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Monthly base salary amount
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="commission">Commission</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="commission"
                                           id="commission"
                                           value="{{ $user->commission }}"
                                           placeholder="0.00">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Commission percentage or fixed amount
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Image -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-image-fill"></i>
                            </div>
                            <h4>User Profile Image</h4>
                        </div>

                        <div class="form-group">
                            <label for="image">Upload New Profile Image</label>
                            <input type="file"
                                   class="form-control image"
                                   name="image"
                                   id="image"
                                   accept="image/*">
                            <p class="help-text">
                                <i class="bi bi-info-circle"></i>
                                Leave empty to keep current image. Recommended: Square image, minimum 200x200 pixels
                            </p>
                        </div>

                        <div class="image-preview-box">
                            <img src="{{ $user->image_path }}"
                                 class="img-thumbnail imag-preview"
                                 style="width: 150px; height: 150px;"
                                 alt="User Image Preview">
                            <p class="upload-hint">
                                <i class="bi bi-cloud-upload"></i>
                                Current profile image - Upload new to replace
                            </p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Update User
                        </button>
                        <a href="{{ route('dashboard.get-all-users') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
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
            // Image preview with animation
            $('.image').on('change', function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.imag-preview').fadeOut(200, function() {
                            $(this).attr('src', e.target.result).fadeIn(200);
                        });
                        $('.upload-hint').html('<i class="bi bi-check-circle-fill"></i> New image uploaded successfully!');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Form submission
            $('#userForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Updating User...');
            });

            // Password confirmation validation
            $('#password_confirmation').on('keyup', function() {
                var password = $('#password').val();
                var confirmation = $(this).val();

                if (password && confirmation && password !== confirmation) {
                    $(this).css('border-color', '#e74c3c');
                } else if (password && confirmation && password === confirmation) {
                    $(this).css('border-color', '#27ae60');
                } else {
                    $(this).css('border-color', '#e0e6ed');
                }
            });

            // Show/hide password confirmation when password is filled
            $('#password').on('keyup', function() {
                if ($(this).val().length > 0) {
                    $('#password_confirmation').closest('.form-group').show();
                } else {
                    $('#password_confirmation').closest('.form-group').hide();
                    $('#password_confirmation').val('');
                }
            });
        });
    </script>
@endsection
