@extends('dashboard.layouts.master')

@section('title', 'Update Lens')

@section('content')

    <style>
        .lens-update-page {
            padding: 20px;
        }

        .box-lens-update {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-lens-update .box-header {
            background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-lens-update .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-lens-update .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-lens-update .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-lens-update .box-header .btn {
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

        .box-lens-update .box-header .btn:hover {
            background: white;
            color: #00a86b;
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
            background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(0, 168, 107, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #00a86b;
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
            border-color: #00a86b;
            box-shadow: 0 0 0 3px rgba(0, 168, 107, 0.1);
        }

        .form-control:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
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
            background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0, 168, 107, 0.3);
            margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 168, 107, 0.4);
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
            border: 2px solid #00a86b;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            color: #00a86b;
            text-align: center;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-pencil-square"></i> Update Lens
            <small>Edit lens information</small>
        </h1>
    </section>

    <div class="lens-update-page">
        <form action="{{route('dashboard.edit-glassess-lenses',['id'=> $glassLense->id ])}}" method="POST" id="lensUpdateForm">
            @csrf
            @method('POST')

            <div class="box box-success box-lens-update">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-pencil-fill"></i> Update Lens Information
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
                                           value="{{$glassLense->product_id}}"
                                           readonly>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens identifier (cannot be changed)
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="index">Index</label>
                                    <select name="index" class="form-control">
                                        <option value="1.5"  {{ ($glassLense->index == 1.5) ? 'selected' : '' }}>1.5</option>
                                        <option value="1.53" {{ ($glassLense->index == 1.53) ? 'selected' : '' }}>1.53</option>
                                        <option value="1.56" {{ ($glassLense->index == 1.56) ? 'selected' : '' }}>1.56</option>
                                        <option value="1.59" {{ ($glassLense->index == 1.59) ? 'selected' : '' }}>1.59</option>
                                        <option value="1.6" {{ ($glassLense->index == 1.6) ? 'selected' : '' }}>1.6</option>
                                        <option value="1.61" {{ ($glassLense->index == 1.61) ? 'selected' : '' }}>1.61</option>
                                        <option value="1.67" {{ ($glassLense->index == 1.67) ? 'selected' : '' }}>1.67</option>
                                        <option value="1.74" {{ ($glassLense->index == 1.74) ? 'selected' : '' }}>1.74</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens refractive index
                                    </p>
                                </div>
                            </div>

                           {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select name="brand" class="form-control">
                                        <option {{ ($glassLense->brand == 'Essilor') ? 'selected' : '' }} value="Essilor">Essilor</option>
                                        <option {{ ($glassLense->brand == 'KODAC') ? 'selected' : '' }} value="KODAC">KODAC</option>
                                        <option {{ ($glassLense->brand == 'TECHLINE') ? 'selected' : '' }} value="TECHLINE">TECHLINE</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens brand manufacturer
                                    </p>
                                </div>
                            </div>--}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lens_brand_id">
                                        Brand
                                        <a href="{{ route('dashboard.lens-brands.create') }}"
                                           target="_blank"
                                           style="font-size:11px;margin-right:8px;color:#667eea;font-weight:600;">
                                            + Add Brand
                                        </a>
                                    </label>
                                    <select name="lens_brand_id" id="lens_brand_id" class="form-control select2" required>
                                        <option value="">-- Select Brand --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{$glassLense->lens_brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
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
                                        <option value="HBC Frame" {{ ($glassLense->frame_type == "HBC Frame") ? 'selected' : '' }}>HBC Frame</option>
                                        <option value="Full Frame" {{ ($glassLense->frame_type == "Full Frame") ? 'selected' : '' }}>Full Frame</option>
                                        <option value="Nilor" {{ ($glassLense->frame_type == "Nilor") ? 'selected' : '' }}>Nilor</option>
                                        <option value="Rimless" {{ ($glassLense->frame_type == "Rimless") ? 'selected' : '' }}>Rimless</option>
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
                                        <option value="All Distance Lense" {{ ($glassLense->lense_type == "All Distance Lense") ? 'selected' : '' }}>All Distance Lense</option>
                                        <option value="Biofocal" {{ ($glassLense->lense_type == "Biofocal") ? 'selected' : '' }}>Biofocal</option>
                                        <option value="Single Vision" {{ ($glassLense->lense_type == "Single Vision") ? 'selected' : '' }}>Single Vision</option>
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
                                        <option value="Normal" {{ ($glassLense->life_style == "Normal") ? 'selected' : '' }}>Normal</option>
                                        <option value="Active" {{ ($glassLense->life_style == "Active") ? 'selected' : '' }}>Active</option>
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
                                        <option value="Clear / Tintable" {{ ($glassLense->customer_activity == "Clear / Tintable") ? 'selected' : '' }}>Clear / Tintable</option>
                                        <option value="Transition" {{ ($glassLense->customer_activity == "Transition") ? 'selected' : '' }}>Transition</option>
                                        <option value="Glare Free" {{ ($glassLense->customer_activity == "Glare Free") ? 'selected' : '' }}>Glare Free</option>
                                        <option value="POLARIZED" {{ ($glassLense->customer_activity == "POLARIZED") ? 'selected' : '' }}>POLARIZED</option>
                                        <option value="TINTED" {{ ($glassLense->customer_activity == "TINTED") ? 'selected' : '' }}>TINTED</option>
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
                                        <option value="HD / Digital Lense" {{ ($glassLense->lense_tech == "HD / Digital Lense") ? 'selected' : '' }}>HD / Digital Lense</option>
                                        <option value="Basic" {{ ($glassLense->lense_tech == "Basic") ? 'selected' : '' }}>Basic</option>
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
                                        <option {{ ($glassLense->lense_production == 'Stock') ? 'selected' : '' }} value="Stock">Stock</option>
                                        <option {{ ($glassLense->lense_production == 'RX') ? 'selected' : '' }} value="RX">RX</option>
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
                                           value="{{ $glassLense->price }}"
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
                                           value="{{ $glassLense->retail_price }}"
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
                                           value="{{$glassLense->amount}}"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Available stock quantity
                                    </p>
                                </div>
                            </div>--}}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">
                                        Description
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="description"
                                           class="form-control"
                                           value="{{$glassLense->description}}"
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
                            <i class="bi bi-check-circle-fill"></i> Update Lens
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
            $('#lensUpdateForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Updating Lens...');
            });

            // Real-time price calculation
            $('input[name="price"], input[name="retail_price"]').on('keyup', function() {
                var costPrice = parseFloat($('input[name="price"]').val()) || 0;
                var retailPrice = parseFloat($('input[name="retail_price"]').val()) || 0;

                if (costPrice > 0 && retailPrice > 0) {
                    var profit = retailPrice - costPrice;
                    var margin = ((profit / retailPrice) * 100).toFixed(2);
                    console.log('Profit Margin: ' + margin + '%');
                }
            });
        });
    </script>

@endsection
