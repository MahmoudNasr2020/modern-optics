@extends('dashboard.layouts.master')

@section('title', 'Add New Lens')

@section('content')

    <style>
        .lens-create-page {
            padding: 20px;
        }

        .box-lens-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-lens-create .box-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-lens-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-lens-create .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-lens-create .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-lens-create .box-header .btn {
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

        .box-lens-create .box-header .btn:hover {
            background: white;
            color: #9b59b6;
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
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #9b59b6;
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
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.1);
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
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.3);
            margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
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

        .lens-id-display {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border: 2px solid #9b59b6;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            color: #9b59b6;
            text-align: center;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-eyeglasses"></i> Add New Lens
            <small>Add a new glass lens to inventory</small>
        </h1>
    </section>

    <div class="lens-create-page">
        <form action="{{route('dashboard.post-glassess-lenses')}}" method="POST" id="lensForm">
            @csrf
            @method('POST')

            <div class="box box-success box-lens-create">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-plus-circle-fill"></i> Lens Information
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.get-glassess-lenses') }}" class="btn btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Lenses
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @include('dashboard.partials._errors')

                    <!-- Lens Identification -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <h4>Lens Identification</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product_id">
                                        Lens ID
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control lens-id-display"
                                           name="product_id"
                                           value="{{ $lenseID['product_id'] + 1 }}"
                                           readonly>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Auto-generated lens identifier
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="index">
                                        Index
                                        <span class="required">*</span>
                                    </label>
                                    <select name="index" class="form-control" required>
                                        <option value="">Select Index</option>
                                        <option value="1.5">1.5</option>
                                        <option value="1.53">1.53</option>
                                        <option value="1.56">1.56</option>
                                        <option value="1.59">1.59</option>
                                        <option value="1.6">1.6</option>
                                        <option value="1.61">1.61</option>
                                        <option value="1.67">1.67</option>
                                        <option value="1.74">1.74</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens refractive index
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select name="brand" class="form-control">
                                        <option value="">Select Brand</option>
                                        <option value="Essilor">Essilor</option>
                                        <option value="KODAC">KODAC</option>
                                        <option value="TECHLINE">TECHLINE</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens brand manufacturer
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lens Specifications -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <h4>Lens Specifications</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="frame_type">Frame Type</label>
                                    <select name="frame_type" class="form-control">
                                        <option value="">Select Frame Type</option>
                                        <option value="HBC Frame">HBC Frame</option>
                                        <option value="Full Frame">Full Frame</option>
                                        <option value="Nilor">Nilor</option>
                                        <option value="Rimless">Rimless</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Compatible frame type
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lense_type">Lens Type</label>
                                    <select name="lense_type" class="form-control">
                                        <option value="">Select Lens Type</option>
                                        <option value="All Distance Lense">All Distance Lense</option>
                                        <option value="Biofocal">Biofocal</option>
                                        <option value="Single Vision">Single Vision</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Type of vision correction
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="life_style">Life Style</label>
                                    <select name="life_style" class="form-control">
                                        <option value="">Select Life Style</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Active">Active</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Recommended lifestyle usage
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_activity">Customer Activity</label>
                                    <select name="customer_activity" class="form-control">
                                        <option value="">Select Activity</option>
                                        <option value="Clear / Tintable">Clear / Tintable</option>
                                        <option value="Transition">Transition</option>
                                        <option value="Glare Free">Glare Free</option>
                                        <option value="POLARIZED">POLARIZED</option>
                                        <option value="TINTED">TINTED</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens coating type
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lense_tech">Lens Technology</label>
                                    <select name="lense_tech" class="form-control">
                                        <option value="">Select Technology</option>
                                        <option value="HD / Digital Lense">HD / Digital Lense</option>
                                        <option value="Basic">Basic</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Manufacturing technology
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="production">Lens Production</label>
                                    <select name="production" class="form-control">
                                        <option value="">Select Production</option>
                                        <option value="Stock">Stock</option>
                                        <option value="RX">RX</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Production type
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h4>Pricing & Stock Information</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price">
                                        Cost Price
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           name="price"
                                           class="form-control"
                                           placeholder="0.00"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Purchase or cost price
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="retail_price">
                                        Retail Price
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           name="retail_price"
                                           class="form-control"
                                           placeholder="0.00"
                                           value="{{ old('retail_price') }}"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Selling price to customers
                                    </p>
                                </div>
                            </div>

                            {{--<div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">
                                        Stock Quantity
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           name="amount"
                                           class="form-control"
                                           placeholder="0"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Available stock quantity
                                    </p>
                                </div>
                            </div>--}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description">
                                        Description
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="description"
                                           class="form-control"
                                           placeholder="Lens description"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Brief lens description
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Add Lens
                        </button>
                        <a href="{{ route('dashboard.get-glassess-lenses') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            // Form submission with loading state
            $('#lensForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Adding Lens...');
            });
        });
    </script>

@endsection
