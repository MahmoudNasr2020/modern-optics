@extends('dashboard.layouts.master')

@section('title', 'Add New Customer')

@section('content')

    <style>
        /* Modern Add Customer Page */
        .add-customer-page {
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
        }

        /* Header */
        .add-customer-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.3);
            position: relative;
            overflow: hidden;
        }

        .add-customer-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .add-customer-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .add-customer-header p {
            margin: 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Cards */
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
        }

        .card-header-green { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; }
        .card-header-blue { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; }
        .card-header-orange { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; }

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
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39,174,96,0.1);
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
            background: #f8fff9;
            border-radius: 8px;
            border: 2px solid #d4edda;
            transition: all 0.3s;
        }

        .checkbox-modern:hover {
            border-color: #27ae60;
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

        .btn-success-modern {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-light {
            background: white;
            color: #27ae60;
            border: 2px solid white;
        }

        /* Action Buttons Footer */
        .action-footer {
            background: #f8f9fa;
            padding: 20px 25px;
            border-top: 2px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>

    <div class="add-customer-page">

        <!-- Header -->
        <div class="add-customer-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="bi bi-person-plus"></i> Add New Customer</h1>
                    <p>Create a new customer profile in the system</p>
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

        <form action="{{ route('dashboard.post-add-customer') }}" method="POST">
            @csrf
            @method('POST')

            <!-- Basic Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-green">
                    <div class="card-title-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Basic Information</h3>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-upc-scan"></i> Customer ID</label>
                                <input type="text" class="form-control" name="customer_id" value="{{ $customerID }}" readonly>
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
                                <input type="checkbox" name="points" id="points">
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
                                    <option value="Dr">Dr.</option>
                                    <option value="Her Highness">Her Highness</option>
                                    <option value="Mr" selected>Mr.</option>
                                    <option value="Mrs">Mrs.</option>
                                    <option value="Ms">Ms.</option>
                                    <option value="Prince">Prince</option>
                                    <option value="Sheikh">Sheikh</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-alphabet"></i> English Name</label>
                                <input type="text" class="form-control" name="english_name" value="{{ old('english_name') }}" placeholder="Enter English name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-translate"></i> Local Name</label>
                                <input type="text" class="form-control" name="local_name" value="{{ old('local_name') }}" placeholder="Enter local name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-gender-ambiguous"></i> Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="Male" selected>Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-calendar-date"></i> Birth Date</label>
                                <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-translate"></i> Preferred Language</label>
                                <select name="prefered_language" class="form-control">
                                    <option value="English" selected>English</option>
                                    <option value="Arabic">Arabic</option>
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
                                    <option value="indian">Indian</option>
                                    <option value="pakistani">Pakistani</option>
                                    <option value="bangladeshi">Bangladeshi</option>
                                    <option value="filipino">Filipino</option>
                                    <!-- Add more as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-card-text"></i> National ID</label>
                                <input type="text" class="form-control" name="national_id" value="{{ old('national_id') }}" placeholder="Enter national ID">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="bi bi-hourglass-split"></i> Age</label>
                                <input type="text" class="form-control" name="age" value="{{ old('age') }}" readonly placeholder="Auto-calculated">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-blue">
                    <div class="card-title-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Address Information</h3>
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
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Oman">Oman</option>
                                    <!-- Add more as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-building"></i> City</label>
                                <select name="city" class="form-control">
                                    <option value="Doha" selected>Doha</option>
                                    <option value="Al Rayyan">Al Rayyan</option>
                                    <option value="Al Wakrah">Al Wakrah</option>
                                    <option value="Al Khawr">Al Khawr</option>
                                    <option value="Umm Salal Muhammad">Umm Salal Muhammad</option>
                                    <!-- Add more cities -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="bi bi-house"></i> Address</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Enter full address">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><i class="bi bi-sticky"></i> Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="customer-card">
                <div class="card-header-custom card-header-orange">
                    <div class="card-title-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">Contact Information</h3>
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
                                <input type="text" class="form-control" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Enter mobile number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="bi bi-envelope"></i> Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter email address">
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
                                    <option value="Both" selected>Both</option>
                                    <option value="None">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="bi bi-briefcase"></i> Office Number</label>
                                <input type="text" class="form-control" name="office_number" value="{{ old('office_number') }}" placeholder="Enter office number">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="action-footer">
                    <div>
                        <button type="submit" class="btn btn-success-modern btn-modern btn-lg" name="action" value="save">
                            <i class="bi bi-check-circle"></i> Save Customer
                        </button>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary-modern btn-modern" name="action" value="saveAndCreateInvoice">
                            <i class="bi bi-receipt"></i> Save & Create Invoice
                        </button>
                        <button type="submit" class="btn btn-warning-modern btn-modern" name="action" value="saveAndCreateEyeTest">
                            <i class="bi bi-eye"></i> Save & Create Eye Test
                        </button>
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
