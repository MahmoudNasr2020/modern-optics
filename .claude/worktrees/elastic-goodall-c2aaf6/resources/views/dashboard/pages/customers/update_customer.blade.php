@extends('dashboard.layouts.master')

@section('title', 'Update Customer')

@section('content')

    <style>
        /* Modern Customer Page Design */
        .customer-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Header */
        .customer-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(52, 152, 219, 0.3);
            position: relative;
            overflow: hidden;
        }

        .customer-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .customer-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .customer-id-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        /* Modern Cards */
        .customer-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header-custom {
            padding: 20px 25px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; }
        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }
        .card-header-purple { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border: none; }

        .card-title-icon {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }

        .card-body-custom {
            padding: 25px;
        }

        /* Form Controls */
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 10px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        .form-control[readonly] {
            background: #f8f9fa;
            cursor: not-allowed;
        }

        /* Checkbox Modern */
        .checkbox-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9ff;
            border-radius: 8px;
            border: 2px solid #e7f3ff;
            transition: all 0.3s;
        }

        .checkbox-modern:hover {
            border-color: #3498db;
            background: #fff;
        }

        .checkbox-modern input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        /* Buttons */
        .btn-modern {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-light {
            background: white;
            color: #3498db;
            border: 2px solid white;
        }
    </style>

    <div class="customer-page">

        <!-- Header -->
        <div class="customer-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-person-circle"></i> Update Customer</h1>
                    <span class="customer-id-badge">
                    Customer ID: {{ $customer->customer_id }}
                </span>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('dashboard.get-all-customers') }}" class="btn btn-light btn-modern">
                        <i class="bi bi-arrow-left"></i> Back to Customers
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @include('dashboard.partials._errors')

        <form action="{{ route('dashboard.post-update-customer', ['id' => $customer->customer_id]) }}" method="POST">
            @csrf
            @method('POST')

            <!-- Basic Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-blue">
                    <div style="display: flex; align-items: center;">
                        <div class="card-title-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Basic Information</h3>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-upc-scan"></i> Customer ID</label>
                                <input type="text" class="form-control" name="customer_id" value="{{ $customer->customer_id }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-tag"></i> Customer Type</label>
                                <input type="text" class="form-control" value="Normal" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox-modern">
                                <input type="checkbox" name="points" id="points" {{ $customer->moftah_club == 1 ? 'checked' : '' }}>
                                <label for="points" style="margin: 0; cursor: pointer;">
                                    <i class="bi bi-star-fill"></i> Gain Points
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-person"></i> Title</label>
                                <select name="title" class="form-control">
                                    <option value="Dr" {{ $customer->title == 'Dr' ? 'selected' : '' }}>Dr.</option>
                                    <option value="Her Highness" {{ $customer->title == 'Her Highness' ? 'selected' : '' }}>Her Highness</option>
                                    <option value="Mr" {{ $customer->title == 'Mr' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Prince" {{ $customer->title == 'Prince' ? 'selected' : '' }}>Prince</option>
                                    <option value="Sheikh" {{ $customer->title == 'Sheikh' ? 'selected' : '' }}>Sheikh</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-alphabet"></i> English Name</label>
                                <input type="text" class="form-control" name="english_name" value="{{ $customer->english_name }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-translate"></i> Local Name</label>
                                <input type="text" class="form-control" name="local_name" value="{{ $customer->local_name }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-gender-ambiguous"></i> Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-date"></i> Birth Date</label>
                                <input type="date" class="form-control" name="birth_date" value="{{ $customer->birth_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-translate"></i> Preferred Language</label>
                                <select name="prefered_language" class="form-control">
                                    <option value="English" {{ $customer->prefered_language == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Arabic" {{ $customer->prefered_language == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-flag"></i> Nationality</label>
                                <select name="nationality" class="form-control">
                                    <option value="qatari" selected>Qatari</option>
                                    <option value="saudi">Saudi</option>
                                    <option value="egyptian">Egyptian</option>
                                    <!-- Add other nationalities as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-card-text"></i> National ID</label>
                                <input type="text" class="form-control" name="national_id" value="{{ $customer->national_id }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-hourglass-split"></i> Age</label>
                                <input type="text" class="form-control" name="age" value="{{ $customer->age }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-green">
                    <div style="display: flex; align-items: center;">
                        <div class="card-title-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Address Information</h3>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-globe"></i> Country</label>
                                <select name="country" class="form-control">
                                    <option value="Qatar" selected>Qatar</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="UAE">UAE</option>
                                    <!-- Add other countries -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-building"></i> City</label>
                                <select name="city" class="form-control">
                                    <option value="Doha">Doha</option>
                                    <option value="Al Rayyan">Al Rayyan</option>
                                    <option value="Al Wakrah">Al Wakrah</option>
                                    <!-- Add other cities -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="bi bi-house"></i> Address</label>
                                <textarea class="form-control" name="address" rows="3">{{ $customer->address }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="bi bi-sticky"></i> Notes</label>
                                <textarea class="form-control" name="notes" rows="3">{{ $customer->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-orange">
                    <div style="display: flex; align-items: center;">
                        <div class="card-title-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Contact Information</h3>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-phone"></i> Dial Code</label>
                                <input type="text" class="form-control" name="dial_code" value="974" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-phone-vibrate"></i> Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" value="{{ $customer->mobile_number }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-envelope"></i> Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $customer->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-bell"></i> Receive Notifications</label>
                                <select name="receive_nots" class="form-control">
                                    <option value="Email Notifications">Email Notifications</option>
                                    <option value="SMS Notifications">SMS Notifications</option>
                                    <option value="Both">Both</option>
                                    <option value="None">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-briefcase"></i> Office Number</label>
                                <input type="text" class="form-control" name="office_number" value="{{ $customer->office_number }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success-modern btn-modern btn-lg btn-block">
                                <i class="bi bi-check-circle"></i> Update Customer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>

@endsection

@section('scripts')
    <script>
        // Age calculation
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let yyyy = today.getFullYear();
        today = new Date(yyyy + '-' + mm + '-' + dd);

        let date = document.querySelector('input[name="birth_date"]');
        let ageInput = document.querySelector('input[name="age"]');

        if (date && ageInput) {
            date.addEventListener('change', function() {
                let birthdate = new Date(date.value);
                let age = Math.floor((today - birthdate) / (365.25 * 24 * 60 * 60 * 1000));
                ageInput.value = age;
            });
        }
    </script>
@endsection
