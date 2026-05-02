@extends('dashboard.layouts.master')

@section('title', 'Product Discounts')

@section('styles')
    <style>
        .discounts-page {
            padding: 20px;
        }

        .box-discounts {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            background: white;
        }

        .box-discounts .box-header {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-discounts .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-discounts .box-header h3 {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-discounts .box-header h3 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-discounts .box-body {
            padding: 40px;
        }

        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #ff9800;
        }

        .info-banner i {
            color: #ff9800;
            font-size: 20px;
            margin-right: 12px;
        }

        .info-banner strong {
            color: #f57c00;
            font-size: 16px;
        }

        .info-banner p {
            margin: 8px 0 0 32px;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Form Sections */
        .form-section {
            background: linear-gradient(135deg, #fff3e0 0%, #fff 100%);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #ffcc80;
        }

        .form-section h4 {
            color: #ff9800;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ffcc80;
        }

        .form-section h4 i {
            margin-right: 10px;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            padding: 8px 10px;
            border-radius: 6px;
        }

        /* Form Groups */
        .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-group label i {
            color: #ff9800;
            margin-right: 5px;
        }

        .form-group label .required {
            color: #e74c3c;
            margin-left: 3px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
           /* padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
            height: 45px;
        }

        .form-control:focus {
            border-color: #ff9800;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
            outline: none;
        }

        /* Bootstrap Select Styling */
        .bootstrap-select .btn {
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 14px;
            height: 45px;
            background: white;
        }

        .bootstrap-select .btn:focus {
            border-color: #ff9800 !important;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1) !important;
            outline: none !important;
        }

        .bootstrap-select .dropdown-menu {
            border: 2px solid #ffcc80;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .bootstrap-select .dropdown-menu li a {
            padding: 10px 15px;
            transition: all 0.2s;
        }

        .bootstrap-select .dropdown-menu li a:hover {
            background: #fff3e0;
            color: #ff9800;
        }

        .bootstrap-select .dropdown-menu li.selected a {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
        }

        /* Discount Type Cards */
        .discount-type-cards {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .discount-type-card {
            flex: 1;
            background: white;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .discount-type-card:hover {
            border-color: #ff9800;
            background: #fff3e0;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.2);
        }

        .discount-type-card.active {
            border-color: #ff9800;
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }

        .discount-type-card .icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
        }

        .discount-type-card .label {
            font-weight: 700;
            color: #ff9800;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .discount-type-card .description {
            font-size: 12px;
            color: #999;
        }

        /* Action Buttons */
        .btn-save {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
            cursor: pointer;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
            color: white;
        }

        .btn-save i {
            margin-right: 8px;
        }

        .btn-reset {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
            margin-left: 10px;
        }

        .btn-reset:hover {
            background: #7f8c8d;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
            color: white;
        }

        .btn-reset i {
            margin-right: 8px;
        }

        /* Help Text */
        .help-text {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #ffcc80;
        }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
        }

        .stats-card .label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .bi-percent, .bi-tag-fill, .bi-box-seam, .bi-calculator {
            color: white !important;
        }
        .bi-box-seam
        {
            color: white !important;
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

        .box-discounts {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@stop

@section('content')

    <section class="content-header">
        <h1>
            <i class="bi bi-percent"></i> Product Discounts
            <small>Apply bulk discounts to products</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Discounts</li>
        </ol>
    </section>

    <div class="discounts-page">
        <div class="box box-success box-discounts">
            <div class="box-header">
                <h3>
                    <i class="bi bi-tag-fill"></i> Apply Product Discounts
                </h3>
            </div>

            <div class="box-body">
                @include('dashboard.partials._errors')

                <!-- Info Banner -->
                <div class="info-banner">
                    <div style="display: flex; align-items: flex-start;">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Bulk Discount Management</strong>
                            <p>Select multiple products and apply discounts in bulk. You can choose between fixed amount discount or percentage-based discount. Changes will be applied immediately to all selected products.</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="bi bi-box-seam" style="color: white !important;"></i>
                        </div>
                        <div class="label">Select Products</div>
                    </div>
                    <div class="stats-card">
                        <div class="icon">
                            <i class="bi bi-percent"></i>
                        </div>
                        <div class="label">Choose Discount Type</div>
                    </div>
                    <div class="stats-card">
                        <div class="icon">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <div class="label">Set Discount Value</div>
                    </div>
                    <div class="stats-card">
                        <div class="icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="label">Apply Changes</div>
                    </div>
                </div>

                <form action="{{ route('dashboard.save-discounts') }}" method="POST">
                    @csrf

                    <!-- Products Selection Section -->
                    <div class="form-section">
                        <h4>
                            <i class="bi bi-box-seam" style="color: white !important;"></i> Select Products
                        </h4>

                        <div class="form-group">
                            <label for="products">
                                <i class="bi bi-search"></i> Products
                                <span class="required">*</span>
                            </label>
                            <select class="form-control selectpicker"
                                    id="products"
                                    data-live-search="true"
                                    name="products[]"
                                    multiple
                                    required
                                    data-selected-text-format="count > 3"
                                    data-actions-box="true"
                                    title="Choose products...">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_id }} | {{ $product->description }} | {{ $product->retail_price }} QAR
                                    </option>
                                @endforeach
                            </select>
                            <small class="help-text">
                                <i class="bi bi-info-circle"></i> Search and select multiple products. Use Ctrl+Click to select multiple items.
                            </small>
                        </div>
                    </div>

                    <!-- Discount Configuration Section -->
                    <div class="form-section">
                        <h4>
                            <i class="bi bi-percent"></i> Discount Configuration
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_type">
                                        <i class="bi bi-tag"></i> Discount Type
                                        <span class="required">*</span>
                                    </label>
                                    <select name="discount_type" id="discount_type" class="form-control" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="fixed">Fixed Amount (QAR)</option>
                                        <option value="percentage">Percentage (%)</option>
                                    </select>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Choose how discount will be calculated
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_value">
                                        <i class="bi bi-calculator"></i> Discount Value
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           name="discount_value"
                                           id="discount_value"
                                           class="form-control"
                                           placeholder="Enter discount value"
                                           min="0"
                                           step="0.01"
                                           required>
                                    <small class="help-text">
                                        <i class="bi bi-info-circle"></i> Enter amount in QAR or percentage
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Discount Type Cards -->
                        <div style="margin-top: 25px;">
                            <label style="display: block; margin-bottom: 15px;">
                                <i class="bi bi-grid"></i> Quick Select:
                            </label>
                            <div class="discount-type-cards">
                                <div class="discount-type-card" data-type="fixed">
                                    <div class="icon">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                    <div class="label">Fixed Amount</div>
                                    <div class="description">Deduct fixed QAR amount</div>
                                </div>
                                <div class="discount-type-card" data-type="percentage">
                                    <div class="icon">
                                        <i class="bi bi-percent"></i>
                                    </div>
                                    <div class="label">Percentage</div>
                                    <div class="description">Deduct % from price</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i> Apply Discounts
                        </button>
                        <button type="reset" class="btn-reset">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize selectpicker
            $('.selectpicker').selectpicker({
                liveSearch: true,
                liveSearchPlaceholder: 'Search products...',
                noneSelectedText: 'No products selected',
                selectedTextFormat: 'count > 3',
                countSelectedText: function (numSelected, numTotal) {
                    return numSelected + ' products selected';
                }
            });

            // Discount type card selection
            $('.discount-type-card').click(function() {
                $('.discount-type-card').removeClass('active');
                $(this).addClass('active');

                var type = $(this).data('type');
                $('#discount_type').val(type).trigger('change');
            });

            // Update active card when dropdown changes
            $('#discount_type').change(function() {
                var selectedType = $(this).val();
                $('.discount-type-card').removeClass('active');
                $('.discount-type-card[data-type="' + selectedType + '"]').addClass('active');

                // Update placeholder for discount value
                if (selectedType === 'fixed') {
                    $('#discount_value').attr('placeholder', 'Enter amount in QAR (e.g., 50)');
                } else if (selectedType === 'percentage') {
                    $('#discount_value').attr('placeholder', 'Enter percentage (e.g., 15)');
                }
            });

            // Form validation
            $('form').submit(function(e) {
                var products = $('#products').val();
                var discountType = $('#discount_type').val();
                var discountValue = $('#discount_value').val();

                if (!products || products.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one product');
                    return false;
                }

                if (!discountType) {
                    e.preventDefault();
                    alert('Please select a discount type');
                    return false;
                }

                if (!discountValue || discountValue <= 0) {
                    e.preventDefault();
                    alert('Please enter a valid discount value');
                    return false;
                }

                return true;
            });
        });
    </script>
@endsection
