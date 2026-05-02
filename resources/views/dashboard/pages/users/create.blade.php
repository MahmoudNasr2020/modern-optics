@extends('dashboard.layouts.master')

@section('title', 'Create New User')

@section('content')

    <style>
        .user-create-page {
            padding: 20px;
        }

        .box-user-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-user-create .box-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-user-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-user-create .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-user-create .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-user-create .box-header .btn {
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

        .box-user-create .box-header .btn:hover {
            background: white;
            color: #667eea;
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

        .form-group label .required {
            color: #e74c3c;
            font-weight: 700;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
           /* padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px dashed #667eea;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .image-preview-box:hover {
            border-color: #764ba2;
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.15);
        }

        .imag-preview {
            border-radius: 50%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: 4px solid white;
            transition: all 0.3s;
        }

        .imag-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .upload-hint {
            margin-top: 15px;
            color: #667eea;
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
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #e0e6ed;
        }

        select.form-control[multiple] option {
            padding: 10px;
            border-radius: 4px;
            margin: 2px 0;
        }

        select.form-control[multiple] option:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-person-plus-fill"></i> Create New User
            <small>Add a new user to the system</small>
        </h1>
    </section>

    <div class="user-create-page">
        <form action="{{ route('dashboard.post-add-user') }}" method="POST" enctype="multipart/form-data" id="userForm">
            @csrf
            @method('POST')

            <div class="box box-primary box-user-create">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-plus-circle-fill"></i> User Information
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
                                           value="{{ old('first_name') }}"
                                           placeholder="e.g., Ahmed"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Enter the user's first name
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
                                           value="{{ old('last_name') }}"
                                           placeholder="e.g., Mohammed"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Enter the user's last name
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
                                           value="{{ old('email') }}"
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

                    <!-- Security Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <h4>Security & Authentication</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">
                                        Password
                                        <span class="required">*</span>
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="password"
                                           id="password"
                                           placeholder="Enter strong password"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Minimum 8 characters, include letters and numbers
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">
                                        Confirm Password
                                        <span class="required">*</span>
                                    </label>
                                    <input type="password"
                                           class="form-control"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           placeholder="Re-enter password"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Must match the password above
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
                                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                            <option value="{{ $role->name }}" {{ old('roles') && in_array($role->name, old('roles')) ? 'selected' : '' }}>
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
                                           value="{{ old('salary') }}"
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
                                           value="{{ old('commission') }}"
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
                            <label for="image">Upload Profile Image</label>
                            <input type="file"
                                   class="form-control image"
                                   name="image"
                                   id="image"
                                   accept="image/*">
                            <p class="help-text">
                                <i class="bi bi-info-circle"></i>
                                Recommended: Square image, minimum 200x200 pixels (JPG, PNG)
                            </p>
                        </div>

                        <div class="image-preview-box">
                            <img src="{{ url('storage/uploads/images/users/default.jpg') }}"
                                 class="img-thumbnail imag-preview"
                                 style="width: 150px; height: 150px;"
                                 alt="User Image Preview">
                            <p class="upload-hint">
                                <i class="bi bi-cloud-upload"></i>
                                Click "Choose File" to upload profile image
                            </p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Create User
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
                        $('.upload-hint').html('<i class="bi bi-check-circle-fill"></i> Image uploaded successfully!');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Form submission
            $('#userForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Creating User...');
            });

            // Password confirmation validation
            $('#password_confirmation').on('keyup', function() {
                var password = $('#password').val();
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
