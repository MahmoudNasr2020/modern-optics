@extends('dashboard.layouts.master')

@section('title', 'Update Product')

@section('content')

    <style>
        .product-update-page {
            padding: 20px;
        }

        .box-product-update {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-product-update .box-header {
            background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-product-update .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-product-update .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-product-update .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-product-update .box-header .btn {
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

        .box-product-update .box-header .btn:hover {
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

        .product-id-display {
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
            <i class="bi bi-pencil-square"></i> Update Product
            <small>Edit product information</small>
        </h1>
    </section>

    <div class="product-update-page">
        <form action="{{route('dashboard.post-update-product', ['id' => $product->id])}}" method="POST" id="productUpdateForm" autocomplete="off">
            @csrf
            @method('POST')

            <div class="box box-success box-product-update">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-pencil-fill"></i> Update Product Information
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.get-all-products') }}" class="btn btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @include('dashboard.partials._errors')

                    <!-- Product Identification -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <h4>Product Identification</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product_id">
                                        Product ID
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control product-id-display"
                                           name="product_id"
                                           value="{{ $product->product_id }}"
                                           readonly>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product identifier (cannot be changed)
                                    </p>
                                </div>
                            </div>

                            {{-- ── Barcode field ── --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-barcode"></i> Barcode <small style="font-weight:400;color:#aaa;">(optional)</small></label>
                                    <div style="display:flex;gap:6px;">
                                        <input type="text" class="form-control" name="barcode" id="barcode_input"
                                               value="{{ old('barcode', $product->barcode) }}"
                                               placeholder="Scan or type barcode…"
                                               style="font-family:monospace;letter-spacing:1px;">
                                        <button type="button" id="generateBarcodeBtn"
                                                style="flex-shrink:0;padding:0 14px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:.2s;">
                                            <i class="bi bi-magic"></i> Generate
                                        </button>
                                    </div>
                                    @if($product->barcode)
                                    <div style="margin-top:10px;text-align:center;background:#f8faff;border-radius:8px;padding:10px;border:1px solid #e0e6ed;">
                                        <svg id="barcodePreview"></svg>
                                        <div style="margin-top:8px;display:flex;gap:8px;justify-content:center;">
                                            <button type="button" onclick="printBarcode('{{ $product->barcode }}','{{ addslashes($product->description) }}')"
                                                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;padding:5px 14px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                                <i class="bi bi-printer"></i> Print
                                            </button>
                                            <button type="button" onclick="downloadBarcode()"
                                                    style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;padding:5px 14px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                                <i class="bi bi-download"></i> Download PNG
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Leave blank to set later. Must be unique.</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">
                                        Category
                                        <span class="required">*</span>
                                    </label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            @if($category->id == 4) @continue @endif
                                            <option value="{{ $category->id }}" {{ ($category->id == $product->category_id) ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product category classification
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">
                                        Brand
                                        <span class="required">*</span>
                                    </label>
                                    <select name="brand" id="brand" class="form-control" required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ ($brand->id == $product->brand_id) ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product brand
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ══ Frames/Sunglasses: Model + Color + Size ══ --}}
                        <div id="fieldGroup-model-color-size" style="display:none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="model">Model</label>
                                    <select name="model" id="model" class="form-control">
                                        <option value="">Select Model</option>
                                        @foreach ($models as $model)
                                            <option value="{{ $model->id }}" {{ ($model->id == $product->model_id) ? 'selected' : '' }}>
                                                {{ $model->model_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" class="form-control" name="color" id="color"
                                           value="{{ old('color', $product->color) }}"
                                           placeholder="e.g., Black, Blue, Red">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <input type="text" class="form-control" name="size" id="size"
                                           value="{{ old('size', $product->size) }}"
                                           placeholder="e.g., Small, Medium, Large">
                                </div>
                            </div>
                        </div>
                        </div>{{-- /fieldGroup-model-color-size --}}

                        {{-- ══ Reading Glasses: Power + Type ══ --}}
                        <div id="fieldGroup-power-type" style="display:none;">
                        <div class="row" style="margin-top:8px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-eyeglasses"></i> Power</label>
                                    <input type="number" step="0.25" min="0.25" max="12" class="form-control"
                                           name="power" id="power"
                                           value="{{ old('power', $product->power) }}"
                                           placeholder="e.g. 1.50">
                                    <p class="help-text">Reading power — e.g. 1.00, 1.50, 2.00 …</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-layout-three-columns"></i> Frame Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select Type</option>
                                        @foreach(['folding metal','folding plastic','metal frame','plastic frame'] as $t)
                                            <option value="{{ $t }}" {{ old('type', $product->type) == $t ? 'selected' : '' }}>
                                                {{ strtoupper($t) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>{{-- /fieldGroup-power-type --}}

                        {{-- ══ Contact Lens Notice ══ --}}
                        <div id="contactLens-notice" style="display:none;">
                            <div style="background:#fff3cd;border:2px solid #ffc107;border-radius:10px;padding:18px 20px;margin-top:12px;">
                                <div style="display:flex;align-items:center;gap:14px;">
                                    <i class="bi bi-info-circle-fill" style="font-size:26px;color:#e67e22;flex-shrink:0;"></i>
                                    <div>
                                        <strong style="color:#7d4e00;font-size:15px;">Contact Lens products have a dedicated screen</strong><br>
                                        <span style="font-size:13px;color:#7d4e00;">Please use the <a href="{{ route('dashboard.contact-lenses.index') }}" style="color:#e67e22;font-weight:700;text-decoration:underline;">Contact Lens screen</a> to manage contact lenses.</span>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- /contactLens-notice --}}

                    </div>

                    <!-- Branch & Description -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4>Description</h4>
                        </div>

                        <div class="row">
                            {{--<div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch">
                                        Branch
                                        <span class="required">*</span>
                                    </label>
                                    <select name="branch" id="branch" class="form-control" required>
                                        @foreach ($branches as $branche)
                                            <option value="{{ $branche->id }}" {{ ($branche->id == $product->branch_id) ? 'selected' : '' }}>
                                                {{ $branche->name ?? $branche->branch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Assign product to branch
                                    </p>
                                </div>
                            </div>
--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text"
                                           class="form-control"
                                           name="description"
                                           id="description"
                                           autocomplete="off"
                                           value="{{ old('description', $product->description) }}"
                                           placeholder="Product description">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Brief product description
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Tax -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h4>Pricing & Tax Information</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">
                                        Cost Price
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="price"
                                           id="price"
                                           value="{{ $product->price }}"
                                           placeholder="0.00"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Purchase or cost price
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="retail_price">
                                        Retail Price
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="retail_price"
                                           id="retail_price"
                                           value="{{ $product->retail_price }}"
                                           placeholder="0.00"
                                           required>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Selling price to customers
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax">Tax (%)</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="tax"
                                           id="tax"
                                           value="{{ $product->tax }}"
                                           placeholder="0.00">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Tax percentage (e.g., 15)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="fixed" {{ ($product->discount_type == 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                                        <option value="percentage" {{ ($product->discount_type == 'percentage') ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Type of discount to apply
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_value">Discount Value</label>
                                    <input type="number"
                                           step="0.01"
                                           name="discount_value"
                                           id="discount_value"
                                           class="form-control"
                                           value="{{ $product->discount_value }}"
                                           placeholder="0.00">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Discount amount or percentage
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand_segment">Brand Segment</label>
                                    <select name="brand_segment" id="brand_segment" class="form-control">
                                        <option value="">Select Segment</option>
                                        <option value="clear" {{ ($product->brand_segment == 'clear') ? 'selected' : '' }}>CLEAR</option>
                                        <option value="color" {{ ($product->brand_segment == 'color') ? 'selected' : '' }}>COLOR</option>
                                        <option value="toric" {{ ($product->brand_segment == 'toric') ? 'selected' : '' }}>TORIC</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product segment classification
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lens Specifications - Hidden (not needed for products) --}}
                    {{--
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="bi bi-eyeglasses"></i></div>
                            <h4>Lens Specifications</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="power">Power</label>
                                    <select name="power" id="power" class="form-control">
                                        <option value="">Select Power</option>
                                        @for ($i = 0; $i <=20; $i += 0.25)
                                            <option value="{{ $i }}" {{ ($product->power == $i) ? 'selected' : '' }}>
                                                {{ number_format((float)$i, 2, '.', '') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sign">Sign</label>
                                    <select name="sign" id="sign" class="form-control">
                                        <option value="">Select Sign</option>
                                        <option value="00" {{ ($product->sign == '00') ? 'selected' : '' }}>00</option>
                                        <option value="-" {{ ($product->sign == '-') ? 'selected' : '' }}>Minus (-)</option>
                                        <option value="+" {{ ($product->sign == '+') ? 'selected' : '' }}>Plus (+)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lense_use">Lens Use</label>
                                    <select name="lense_use" id="lense_use" class="form-control">
                                        <option value="">Select Usage</option>
                                        <option value="daily" {{ ($product->lense_use == 'daily') ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($product->lense_use == 'weekly') ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ ($product->lense_use == 'monthly') ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="folding metal" {{ ($product->type == 'folding metal') ? 'selected' : '' }}>FOLDING METAL</option>
                                        <option value="folding plastic" {{ ($product->type == 'folding plastic') ? 'selected' : '' }}>FOLDING PLASTIC</option>
                                        <option value="metal frame" {{ ($product->type == 'metal frame') ? 'selected' : '' }}>METAL FRAME</option>
                                        <option value="plastic frame" {{ ($product->type == 'plastic frame') ? 'selected' : '' }}>PLASTIC FRAME</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Update Product
                        </button>
                        <a href="{{ route('dashboard.get-all-products') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{asset('assets/js/jquery-2.0.2.min.js')}}" type="text/javascript"></script>
    <script>
        // ── Category → Field Visibility (same logic as create page) ──
        var CAT_MODEL_COLOR_SIZE_UP = [1, 2];
        var CAT_POWER_TYPE_UP       = [6];
        var CAT_CONTACT_LENS_UP     = [4];

        function applyCategoryFieldsUpdate(catId) {
            catId = parseInt(catId) || 0;
            var showMCS = CAT_MODEL_COLOR_SIZE_UP.indexOf(catId) >= 0;
            var showPT  = CAT_POWER_TYPE_UP.indexOf(catId) >= 0;
            var showCL  = CAT_CONTACT_LENS_UP.indexOf(catId) >= 0;
            $('#fieldGroup-model-color-size')[showMCS ? 'show' : 'hide']();
            $('#fieldGroup-power-type')[showPT  ? 'show' : 'hide']();
            $('#contactLens-notice')[showCL  ? 'show' : 'hide']();
            $('.btn-submit').prop('disabled', showCL);
            $('#model').prop('required', showMCS);
        }

        $(document).ready(function() {
            // Apply on page load based on existing product category
            applyCategoryFieldsUpdate({{ $product->category_id ?? 0 }});

            // Set the brands after choosing the category ID
            let category_ID = document.querySelector('#category');
            $(category_ID).on('change', function(e) {
                applyCategoryFieldsUpdate($(this).val());
                if($(this).val() != '') {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.filter-brands-by-category-id")}}',
                        data: { category_id: category_ID.value },
                        success: function(response) {
                            let brandSelect = document.querySelector('#brand');
                            brandSelect.innerHTML = '<option value="">Select Brand</option>';
                            response.forEach((brand, index) => {
                                brandSelect.innerHTML += `
                                    <option value="${brand.id}">${brand.brand_name}</option>
                                `;
                            });
                            brandSelect.disabled = false;
                        }
                    });
                } else {
                    let brandSelect = document.querySelector('#brand');
                    brandSelect.innerHTML = '<option value="">Select Category First</option>';
                    brandSelect.disabled = true;

                    let modelSelect = document.querySelector('#model');
                    if (modelSelect) { modelSelect.innerHTML = '<option value="">Select Brand First</option>'; modelSelect.disabled = true; }
                }
            });

            // Set The models after choosing the Brand ID
            let modal_brand_ID = document.querySelector('#brand');
            $(modal_brand_ID).on('change', function(e) {
                console.log(modal_brand_ID.value);
                if($(this).val() != '') {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.filter-models-by-brand-id")}}',
                        data: { brand_id: modal_brand_ID.value },
                        success: function(response) {
                            let modelsSelect = document.querySelector('#model');
                            modelsSelect.innerHTML = '<option value="">Select Model</option>';
                            response.forEach((model, index) => {
                                modelsSelect.innerHTML += `
                                    <option value="${model.id}">${model.model_id}</option>
                                `;
                            });
                            modelsSelect.disabled = false;
                        }
                    });
                } else {
                    let modelsSelect = document.querySelector('#model');
                    modelsSelect.innerHTML = '<option value="">Select Brand First</option>';
                    modelsSelect.disabled = true;
                }
            });

            // Form submission with loading state
            $('#productUpdateForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Updating Product...');
            });

            // Real-time price calculation
            $('#price, #retail_price').on('keyup', function() {
                var costPrice = parseFloat($('#price').val()) || 0;
                var retailPrice = parseFloat($('#retail_price').val()) || 0;

                if (costPrice > 0 && retailPrice > 0) {
                    var profit = retailPrice - costPrice;
                    var margin = ((profit / retailPrice) * 100).toFixed(2);
                    console.log('Profit Margin: ' + margin + '%');
                }
            });
        });
    </script>

    {{-- ── JsBarcode (Code128) ── --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
    // ── Generate button ──────────────────────────────────────────
    document.getElementById('generateBarcodeBtn').addEventListener('click', function () {
        var digits = '';
        for (var i = 0; i < 12; i++) digits += Math.floor(Math.random() * 10);
        var sum = 0;
        for (var i = 0; i < 12; i++) sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
        var check = (10 - (sum % 10)) % 10;
        var code = digits + check;
        document.getElementById('barcode_input').value = code;
        renderPreview(code);
    });

    // ── Render preview on input change ──────────────────────────
    document.getElementById('barcode_input').addEventListener('input', function () {
        renderPreview(this.value.trim());
    });

    function renderPreview(code) {
        var svg = document.getElementById('barcodePreview');
        if (!svg || !code) return;
        try {
            JsBarcode(svg, code, { format: 'CODE128', width: 2, height: 50, displayValue: true, fontSize: 12, margin: 8 });
            svg.parentElement.style.display = 'block';
        } catch(e) { /* invalid code — ignore */ }
    }

    // ── Auto-render existing barcode on load ─────────────────────
    (function () {
        var existing = document.getElementById('barcode_input').value.trim();
        if (existing) renderPreview(existing);
    })();

    // ── Print ────────────────────────────────────────────────────
    function printBarcode(code, description) {
        var svg = document.getElementById('barcodePreview');
        var svgData = new XMLSerializer().serializeToString(svg);
        var win = window.open('', '_blank', 'width=400,height=300');
        win.document.write('<html><head><title>Barcode - ' + code + '</title><style>body{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;margin:0;font-family:Arial;}p{margin:8px 0 0;font-size:13px;color:#555;}</style></head><body>');
        win.document.write(svgData);
        win.document.write('<p>' + description + '</p>');
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        setTimeout(function(){ win.print(); win.close(); }, 400);
    }

    // ── Download PNG ─────────────────────────────────────────────
    function downloadBarcode() {
        var svg   = document.getElementById('barcodePreview');
        var code  = document.getElementById('barcode_input').value.trim();
        var svgData = new XMLSerializer().serializeToString(svg);
        var canvas  = document.createElement('canvas');
        var img     = new Image();
        var svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        var url     = URL.createObjectURL(svgBlob);
        img.onload = function () {
            canvas.width  = img.width;
            canvas.height = img.height;
            canvas.getContext('2d').drawImage(img, 0, 0);
            URL.revokeObjectURL(url);
            var a = document.createElement('a');
            a.download = 'barcode_' + code + '.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        };
        img.src = url;
    }
    </script>

@endsection
