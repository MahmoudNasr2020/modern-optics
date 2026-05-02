@extends('dashboard.layouts.master')

@section('title', 'Add New Product')

@section('content')

    <style>
        .product-create-page {
            padding: 20px;
        }

        .box-product-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-product-create .box-header {
            background: linear-gradient(135deg, #00a86b 0%, #008b5a 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-product-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-product-create .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-product-create .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-product-create .box-header .btn {
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

        .box-product-create .box-header .btn:hover {
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
            <i class="bi bi-box-seam-fill"></i> Add New Product
            <small>Add a new product to inventory</small>
        </h1>
    </section>

    <div class="product-create-page">
        <form action="{{route('dashboard.post-add-product')}}" method="POST" id="productForm">
            @csrf
            @method('POST')

            <div class="box box-success box-product-create">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="bi bi-plus-circle-fill"></i> Product Information
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ url()->previous() }}" class="btn btn-sm">
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
                                           value="{{ $productID['product_id'] + 1 }}"
                                           readonly>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Auto-generated product identifier
                                    </p>
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
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
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
                                    <select name="brand" id="brand" class="form-control" disabled required>
                                        <option value="">Select Category First</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Select category to enable
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="model">
                                        Model
                                        <span class="required">*</span>
                                    </label>
                                    <select name="model" id="model" class="form-control" disabled required>
                                        <option value="">Select Brand First</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Select brand to enable
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text"
                                           class="form-control"
                                           name="color"
                                           id="color"
                                           value="{{ old('color') }}"
                                           placeholder="e.g., Black, Blue, Red">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product color or shade
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <input type="text"
                                           class="form-control"
                                           name="size"
                                           id="size"
                                           value="{{ old('size') }}"
                                           placeholder="e.g., Small, Medium, Large">
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product size or dimensions
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Branch -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4>Description</h4>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text"
                                           class="form-control"
                                           name="description"
                                           id="description"
                                           value="{{ old('description') }}"
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
                                           value="{{ old('price') }}"
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
                                           value="{{ old('retail_price') }}"
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
                                           value="{{ old('tax',0) }}"
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
                                        <option value="fixed">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
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
                                           value="{{ old('discount_value') }}"
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
                                        <option value="clear">CLEAR</option>
                                        <option value="color">COLOR</option>
                                        <option value="toric">TORIC</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Product segment classification
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lens Specifications -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="bi bi-eyeglasses"></i>
                            </div>
                            <h4>Lens Specifications</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="power">Power</label>
                                    <select name="power" id="power" class="form-control">
                                        <option value="">Select Power</option>
                                        @for ($i = 0; $i <=20; $i += 0.25)
                                            <option value="{{ $i }}">{{ number_format((float)$i, 2, '.', '') }}</option>
                                        @endfor
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Lens power value
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sign">Sign</label>
                                    <select name="sign" id="sign" class="form-control">
                                        <option value="">Select Sign</option>
                                        <option value="00">00</option>
                                        <option value="-">Minus (-)</option>
                                        <option value="+">Plus (+)</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Power sign indicator
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lense_use">Lens Use</label>
                                    <select name="lense_use" id="lense_use" class="form-control">
                                        <option value="">Select Usage</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Recommended usage period
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="folding metal">FOLDING METAL</option>
                                        <option value="folding plastic">FOLDING PLASTIC</option>
                                        <option value="metal frame">METAL FRAME</option>
                                        <option value="plastic frame">PLASTIC FRAME</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="bi bi-info-circle"></i>
                                        Frame or product type
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Add Product
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-cancel">
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
            // Set the brands after choosing the category ID
            let category_ID = document.querySelector('#category');
            $(category_ID).on('change', function(e) {
                console.log(category_ID.value);
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
                    modelSelect.innerHTML = '<option value="">Select Brand First</option>';
                    modelSelect.disabled = true;
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

            // SET the type options when changing category
            $(category_ID).on('change', function(e) {
                let cat_value = category_ID.options[category_ID.selectedIndex].innerText;
                console.log(cat_value);

                if(cat_value == 'OTHERS (CHAINS)') {
                    let typeSelectBox = document.querySelector('#type');
                    typeSelectBox.innerHTML = `
                        <option value="">Select Type</option>
                        <option value="multicolor">MULTICOLOR</option>
                        <option value="silver">SILVER</option>
                        <option value="gold">GOLD</option>
                    `;
                } else if(cat_value == 'OTHERS (C.L SOLUTION)') {
                    let typeSelectBox = document.querySelector('#type');
                    typeSelectBox.innerHTML = `
                        <option value="">Select Type</option>
                        <option value="opti free">OPTI FREE</option>
                    `;
                } else {
                    let typeSelectBox = document.querySelector('#type');
                    typeSelectBox.innerHTML = `
                        <option value="">Select Type</option>
                        <option value="folding metal">FOLDING METAL</option>
                        <option value="folding plastic">FOLDING PLASTIC</option>
                        <option value="metal frame">METAL FRAME</option>
                        <option value="plastic frame">PLASTIC FRAME</option>
                    `;
                }
            });

            // Form submission with loading state
            $('#productForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="bi bi-hourglass-split"></i> Adding Product...');
            });

            // Real-time price calculation
            $('#price, #retail_price').on('keyup', function() {
                var costPrice = parseFloat($('#price').val()) || 0;
                var retailPrice = parseFloat($('#retail_price').val()) || 0;

                if (costPrice > 0 && retailPrice > 0) {
                    var profit = retailPrice - costPrice;
                    var margin = ((profit / retailPrice) * 100).toFixed(2);

                    // You can display this somewhere if needed
                    console.log('Profit Margin: ' + margin + '%');
                }
            });

            // Input validation for numeric fields
            $('input[type="number"]').on('keypress', function(e) {
                var charCode = (e.which) ? e.which : e.keyCode;
                if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });
        });
    </script>

@endsection
