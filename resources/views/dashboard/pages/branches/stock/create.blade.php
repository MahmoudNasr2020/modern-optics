{{--
@extends('dashboard.layouts.master')

@section('title', 'Add Stock - ' . $branch->name)

@section('content')

    <style>
        .stock-form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin: -30px -30px 25px -30px;
            font-weight: 700;
            font-size: 18px;
        }

        .item-type-selector {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .type-card {
            flex: 1;
            padding: 30px;
            border: 4px solid #e0e6ed;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
            opacity: 0;
            transition: all 0.3s;
        }

        .type-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.2);
        }

        .type-card:hover::before {
            opacity: 1;
        }

        .type-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }

        .type-card i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .type-card strong {
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .type-card .description {
            font-size: 13px;
            margin-top: 10px;
            opacity: 0.8;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label .text-danger {
            font-size: 16px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 10px 15px;*/
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .info-box i {
            color: #3498db;
            margin-right: 8px;
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
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-plus-circle"></i> Add Stock - {{ $branch->name }}
            <small>Add new item to inventory</small>
        </h1>
    </section>

    <div class="stock-page" style="padding: 20px;">
        <form action="{{ route('dashboard.branches.stock.store', $branch) }}" method="POST" id="stockForm">
            @csrf

            --}}
{{-- Item Type Selection --}}{{--

            <div class="stock-form-card">
                <div class="section-header">
                    <i class="bi bi-box-seam"></i> Select Item Type
                </div>

                <input type="hidden" name="item_type" id="item_type" value="product">

                <div class="item-type-selector">
                    <div class="type-card active" data-type="product">
                        <i class="bi bi-box"></i>
                        <strong>Product</strong>
                        <div class="description">Add eyewear frames and accessories</div>
                    </div>

                    <div class="type-card" data-type="lens">
                        <i class="bi bi-eye"></i>
                        <strong>Lens</strong>
                        <div class="description">Add optical lenses</div>
                    </div>
                </div>
            </div>

            --}}
{{-- Product Selection --}}{{--

            <div class="stock-form-card" id="product_section">
                <div class="section-header">
                    <i class="bi bi-search"></i> Select Product
                </div>

                <div class="form-group">
                    <label>Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-control select2">
                        <option value="">-- Select a product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                [{{ $product->product_id }}] {{ $product->description }} - {{ number_format($product->price, 2) }} QAR
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <strong>Note:</strong> Select the product you want to add to this branch's inventory
                </div>
            </div>

            --}}
{{-- Lens Selection --}}{{--

            <div class="stock-form-card" id="lens_section" style="display: none;">
                <div class="section-header">
                    <i class="bi bi-search"></i> Select Lens
                </div>

                <div class="form-group">
                    <label>Lens <span class="text-danger">*</span></label>
                    <select name="lens_id" id="lens_id" class="form-control select2">
                        <option value="">-- Select a lens --</option>
                        @foreach($lenses as $lens)
                            <option value="{{ $lens->id }}" {{ old('lens_id') == $lens->id ? 'selected' : '' }}>
                                [{{ $lens->product_id }}] {{ $lens->description }} - {{ $lens->brand }} - Index: {{ $lens->index }} - {{ number_format($lens->retail_price, 2) }} QAR
                            </option>
                        @endforeach
                    </select>
                    @error('lens_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="info-box">
                    <i class="bi bi-info-circle"></i>
                    <strong>Note:</strong> Select the lens you want to add to this branch's inventory
                </div>
            </div>

            --}}
{{-- Stock Details --}}{{--

            <div class="stock-form-card">
                <div class="section-header">
                    <i class="bi bi-sliders"></i> Stock Details
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Initial Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                   min="0" value="{{ old('quantity', 0) }}" required>
                            @error('quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Minimum Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="min_quantity" id="min_quantity" class="form-control"
                                   min="0" value="{{ old('min_quantity', 5) }}" required>
                            @error('min_quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Alert when stock falls below this level</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Maximum Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="max_quantity" id="max_quantity" class="form-control"
                                   min="0" value="{{ old('max_quantity', 100) }}" required>
                            @error('max_quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Target stock level</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cost per Unit (Optional)</label>
                            <input type="number" name="cost" id="cost" class="form-control"
                                   min="0" step="0.01" value="{{ old('cost') }}" placeholder="0.00">
                            @error('cost')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            --}}
{{-- Submit --}}{{--

            <div class="text-right">
                @can('add-stock')
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Add to Stock
                    </button>
                @endcan
                @can('view-stock')
                    <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-default btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                @endcan
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Search...'
            });

            // Item Type Selection
            $('.type-card').on('click', function() {
                var type = $(this).data('type');

                // Update UI
                $('.type-card').removeClass('active');
                $(this).addClass('active');
                $('#item_type').val(type);

                // Show/Hide sections
                if (type === 'product') {
                    $('#product_section').slideDown();
                    $('#lens_section').slideUp();
                    $('#product_id').prop('required', true);
                    $('#lens_id').prop('required', false);

                    // Update min/max defaults for products
                    $('#min_quantity').val(5);
                    $('#max_quantity').val(100);
                } else {
                    $('#product_section').slideUp();
                    $('#lens_section').slideDown();
                    $('#product_id').prop('required', false);
                    $('#lens_id').prop('required', true);

                    // Update min/max defaults for lenses
                    $('#min_quantity').val(2);
                    $('#max_quantity').val(50);
                }
            });

            // Form validation
            $('#stockForm').on('submit', function(e) {
                var itemType = $('#item_type').val();
                var itemId = itemType === 'product' ? $('#product_id').val() : $('#lens_id').val();

                if (!itemId) {
                    e.preventDefault();
                    alert('Please select a ' + itemType + ' first!');
                    return false;
                }

                var minQty = parseInt($('#min_quantity').val());
                var maxQty = parseInt($('#max_quantity').val());

                if (minQty >= maxQty) {
                    e.preventDefault();
                    alert('Maximum quantity must be greater than minimum quantity!');
                    return false;
                }
            });
        });
    </script>
@endsection
--}}


{{--@extends('dashboard.layouts.master')

@section('title', 'Add Stock - ' . $branch->name)

@section('content')

    <style>
        .stock-form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin: -30px -30px 25px -30px;
            font-weight: 700;
            font-size: 18px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Table scroll wrapper */
        .table-scroll-wrapper {
            overflow-x: auto;
            overflow-y: visible;
            padding-bottom: 10px;
        }

        /* Table */
        .bulk-table {
            width: 100%;
            min-width: 1150px;
            border-collapse: separate;
            border-spacing: 0 8px;
            table-layout: fixed;
        }

        .bulk-table thead th {
            background: #f4f6fb;
            color: #555;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 10px 12px;
            border: none;
            white-space: nowrap;
        }

        .bulk-table thead th:first-child { border-radius: 8px 0 0 8px; }
        .bulk-table thead th:last-child  { border-radius: 0 8px 8px 0; }

        /* column widths */
        .col-num   { width: 45px;  }
        .col-type  { width: 130px; }
        .col-item  { width: 330px; }
        .col-qty   { width: 85px;  }
        .col-min   { width: 85px;  }
        .col-max   { width: 85px;  }
        .col-cost  { width: 110px; }
        .col-notes { width: 200px; }
        .col-del   { width: 50px;  }

        .stock-row {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: box-shadow .2s;
        }

        .stock-row:hover { box-shadow: 0 4px 16px rgba(102,126,234,0.15); }

        .stock-row td {
            padding: 10px 8px;
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .stock-row td:first-child {
            border-left: 1px solid #f0f0f0;
            border-radius: 8px 0 0 8px;
            padding-left: 12px;
        }

        .stock-row td:last-child {
            border-right: 1px solid #f0f0f0;
            border-radius: 0 8px 8px 0;
            padding-right: 12px;
        }

        .stock-row .form-control {
            border: 1.5px solid #e0e6ed;
            border-radius: 7px !important;
            font-size: 13px;
            height: 36px;
            padding: 4px 10px;
            transition: border-color .2s, box-shadow .2s;
        }

        .stock-row .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .type-badge.product { background: #e8f4fd; color: #2980b9; }
        .type-badge.lens    { background: #f0ebff; color: #764ba2; }

        .row-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            margin: 0 auto;
        }

        .btn-add-row {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all .3s;
            font-size: 14px;
        }

        .btn-add-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.4);
            color: white;
        }

        .btn-remove-row {
            background: #fee;
            border: 1.5px solid #fcc;
            color: #e74c3c;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .btn-remove-row:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        .summary-bar {
            background: linear-gradient(135deg, #667eea10, #764ba210);
            border: 1.5px solid #667eea30;
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            gap: 30px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .summary-item .val {
            font-size: 22px;
            font-weight: 700;
            color: #667eea;
        }

        .summary-item .lbl {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .error-row td {
            border-color: #e74c3c !important;
            background: #fff5f5;
        }

        /* select2 inside table */
        .select2-container { min-width: 180px; }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-plus-circle"></i> Add Stock — {{ $branch->name }}
            <small>Add multiple items at once</small>
        </h1>
    </section>

    <div class="stock-page" style="padding: 20px;">

        --}}{{-- Validation errors --}}{{--
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><i class="bi bi-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.branches.stock.store', $branch) }}" method="POST" id="stockForm">
            @csrf

            --}}{{-- Summary bar --}}{{--
            <div class="summary-bar">
                <div class="summary-item">
                    <span class="val" id="total-rows">1</span>
                    <span class="lbl">Items</span>
                </div>
                <div class="summary-item">
                    <span class="val" id="total-products">0</span>
                    <span class="lbl">Products</span>
                </div>
                <div class="summary-item">
                    <span class="val" id="total-qty">0</span>
                    <span class="lbl">Total Qty</span>
                </div>
                <div class="ms-auto" style="margin-left:auto;">
                    <button type="button" class="btn-add-row" id="addRowBtn">
                        <i class="bi bi-plus-lg"></i> Add Row
                    </button>
                </div>
            </div>

            --}}{{-- Main card --}}{{--
            <div class="stock-form-card">
                <div class="section-header">
                    <i class="bi bi-table"></i> Stock Items
                </div>

                <div class="table-scroll-wrapper">
                    <table class="bulk-table" id="stockTable">
                        <colgroup>
                            <col class="col-num">
                            <col class="col-type">
                            <col class="col-item">
                            <col class="col-qty">
                            <col class="col-min">
                            <col class="col-max">
                            <col class="col-cost">
                            <col class="col-notes">
                            <col class="col-del">
                        </colgroup>
                        <thead>
                        <tr>
                            <th style="text-align:center;">#</th>
                            <th>Product</th>
                            <th>Qty <span class="text-danger">*</span></th>
                            <th>Min Qty <span class="text-danger">*</span></th>
                            <th>Max Qty <span class="text-danger">*</span></th>
                            <th>Cost</th>
                            <th>Notes</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="stockBody">
                        --}}{{-- JS will render rows; if old input exists, PHP renders them --}}{{--
                        @if(old('items'))
                            @foreach(old('items') as $i => $item)
                                <tr class="stock-row" data-index="{{ $i }}">
                                    <td><div class="row-number">{{ $i + 1 }}</div></td>
                                    <td>
                                        <select name="items[{{ $i }}][item_type]"
                                                class="form-control item-type-select"
                                                style="height:36px; padding:4px 8px;">
                                            <option value="product" {{ ($item['item_type'] ?? 'product') === 'product' ? 'selected' : '' }}>📦 Product</option>
                                            <option value="lens"    {{ ($item['item_type'] ?? '') === 'lens' ? 'selected' : '' }}>👁 Lens</option>
                                        </select>
                                    </td>
                                    <td>
                                        --}}{{-- Product wrapper --}}{{--
                                        <div class="product-wrap" style="{{ ($item['item_type'] ?? 'product') === 'lens' ? 'display:none' : '' }}">
                                            <select name="items[{{ $i }}][product_id]" class="form-control select2-product">
                                                <option value="">-- Select product --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ ($item['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                                        [{{ $product->product_id }}] {{ $product->description }} — {{ number_format($product->price, 2) }} QAR
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        --}}{{-- Lens wrapper --}}{{--
                                        <div class="lens-wrap" style="{{ ($item['item_type'] ?? 'product') !== 'lens' ? 'display:none' : '' }}">
                                            <select name="items[{{ $i }}][lens_id]" class="form-control select2-lens">
                                                <option value="">-- Select lens --</option>
                                                @foreach($lenses as $lens)
                                                    <option value="{{ $lens->id }}" {{ ($item['lens_id'] ?? '') == $lens->id ? 'selected' : '' }}>
                                                        [{{ $lens->product_id }}] {{ $lens->description }} — {{ $lens->brand }} — Index: {{ $lens->index }} — {{ number_format($lens->retail_price, 2) }} QAR
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td><input type="number" name="items[{{ $i }}][quantity]"   class="form-control qty-input" min="0" value="{{ $item['quantity'] ?? 0 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][min_quantity]" class="form-control" min="0" value="{{ $item['min_quantity'] ?? 5 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][max_quantity]" class="form-control" min="0" value="{{ $item['max_quantity'] ?? 100 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][cost]"       class="form-control" min="0" step="0.01" value="{{ $item['cost'] ?? '' }}" placeholder="0.00"></td>
                                    <td><input type="text"   name="items[{{ $i }}][notes]"      class="form-control" value="{{ $item['notes'] ?? '' }}" placeholder="Optional..."></td>
                                    <td>
                                        <button type="button" class="btn-remove-row remove-row" title="Remove">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                --}}{{-- Bottom add row --}}{{--
                <div class="text-center mt-3">
                    <button type="button" class="btn-add-row" id="addRowBtn2">
                        <i class="bi bi-plus-lg"></i> Add Another Row
                    </button>
                </div>
            </div>

            --}}{{-- Submit --}}{{--
            <div class="text-right">
                @can('add-stock')
                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Add All to Stock
                    </button>
                @endcan
                @can('view-stock')
                    <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-default btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                @endcan
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            // ─── Build option HTML once ──────────────────────────────────────────────
            var productOptions = '<option value="">-- Select product --</option>';
            @foreach($products as $product)
                productOptions += '<option value="{{ $product->id }}">[{{ addslashes($product->product_id) }}] {{ addslashes($product->description) }} — {{ number_format($product->price, 2) }} QAR</option>';
            @endforeach

            var lensOptions = '<option value="">-- Select lens --</option>';
            @foreach($lenses as $lens)
                lensOptions += '<option value="{{ $lens->id }}">[{{ addslashes($lens->product_id) }}] {{ addslashes($lens->description) }} — {{ addslashes($lens->brand) }} — Index: {{ $lens->index }} — {{ number_format($lens->retail_price, 2) }} QAR</option>';
            @endforeach

            var rowIndex = 0;

            // ─── Row template ─────────────────────────────────────────────────────────
            function buildRow(index) {
                return `
        <tr class="stock-row" data-index="${index}">
            <td><div class="row-number">${index + 1}</div></td>
            <td>
                <select name="items[${index}][item_type]"
                        class="form-control item-type-select"
                        style="height:36px; padding:4px 8px;">
                    <option value="product">📦 Product</option>
                </select>
            </td>
            <td>
                <div class="product-wrap">
                    <select name="items[${index}][product_id]" class="form-control select2-product">
                        ${productOptions}
                    </select>
                </div>
                <div class="lens-wrap" style="display:none;">
                    <select name="items[${index}][lens_id]" class="form-control select2-lens">
                        ${lensOptions}
                    </select>
                </div>
            </td>
            <td><input type="number" name="items[${index}][quantity]"    class="form-control qty-input" min="0" value="0" required></td>
            <td><input type="number" name="items[${index}][min_quantity]" class="form-control" min="0" value="5" required></td>
            <td><input type="number" name="items[${index}][max_quantity]" class="form-control" min="0" value="100" required></td>
            <td><input type="number" name="items[${index}][cost]"        class="form-control" min="0" step="0.01" placeholder="0.00"></td>
            <td><input type="text"   name="items[${index}][notes]"       class="form-control" placeholder="Optional..."></td>
            <td>
                <button type="button" class="btn-remove-row remove-row" title="Remove">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        </tr>`;
            }

            // ─── Add row ──────────────────────────────────────────────────────────────
            function addRow() {
                var $row = $(buildRow(rowIndex));
                $('#stockBody').append($row);

                // Init select2 inside wrappers
                $row.find('.select2-product').select2({ width: '100%', placeholder: 'Search product...' });
                $row.find('.select2-lens').select2({ width: '100%', placeholder: 'Search lens...' });
                // lens-wrap already hidden via style="display:none" in template

                rowIndex++;
                reindex();
                updateSummary();
            }

            // Init existing rows from old() (after validation fail)
            if ($('#stockBody tr').length > 0) {
                rowIndex = $('#stockBody tr').length;
                $('#stockBody .select2-product').each(function() {
                    $(this).select2({ width: '100%', placeholder: 'Search product...' });
                });
                $('#stockBody .select2-lens').each(function() {
                    $(this).select2({ width: '100%', placeholder: 'Search lens...' });
                });
            } else {
                // Fresh page — add one empty row
                addRow();
            }

            $('#addRowBtn, #addRowBtn2').on('click', addRow);

            // ─── Remove row ───────────────────────────────────────────────────────────
            $(document).on('click', '.remove-row', function () {
                if ($('#stockBody tr').length === 1) {
                    alert('You need at least one row!');
                    return;
                }
                $(this).closest('tr').remove();
                reindex();
                updateSummary();
            });

            /// Type toggle: show/hide WRAPPER divs so select2 works correctly
            $(document).on('change', '.item-type-select', function () {
                var $row = $(this).closest('tr');
                var type = $(this).val();

                if (type === 'product') {
                    $row.find('.product-wrap').show();
                    $row.find('.lens-wrap').hide();
                    $row.find('.select2-lens').val(null).trigger('change');
                    $row.find('input[name*="min_quantity"]').val(5);
                    $row.find('input[name*="max_quantity"]').val(100);
                } else {
                    $row.find('.product-wrap').hide();
                    $row.find('.lens-wrap').show();
                    $row.find('.select2-product').val(null).trigger('change');
                    $row.find('input[name*="min_quantity"]').val(2);
                    $row.find('input[name*="max_quantity"]').val(50);
                }
                updateSummary();
            });

            // ─── Reindex row numbers ──────────────────────────────────────────────────
            function reindex() {
                $('#stockBody tr').each(function (i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            // ─── Summary bar ──────────────────────────────────────────────────────────
            function updateSummary() {
                var rows     = $('#stockBody tr').length;
                var products = $('#stockBody .item-type-select').filter(function() { return $(this).val() === 'product'; }).length;
                var lenses   = rows - products;
                var totalQty = 0;
                $('#stockBody .qty-input').each(function() { totalQty += parseInt($(this).val()) || 0; });

                $('#total-rows').text(rows);
                $('#total-products').text(products);
                $('#total-lenses').text(lenses);
                $('#total-qty').text(totalQty);
            }

            $(document).on('input', '.qty-input', updateSummary);
            $(document).on('change', '.item-type-select', updateSummary);
            updateSummary();

            // ─── Form validation ──────────────────────────────────────────────────────
            $('#stockForm').on('submit', function (e) {
                var valid = true;
                var errors = [];

                $('#stockBody tr').each(function (i) {
                    var $row   = $(this);
                    var type   = $row.find('.item-type-select').val();
                    var itemId = type === 'product'
                        ? $row.find('select[name*="product_id"]').val()
                        : $row.find('select[name*="lens_id"]').val();

                    var minQty = parseInt($row.find('input[name*="min_quantity"]').val());
                    var maxQty = parseInt($row.find('input[name*="max_quantity"]').val());

                    if (!itemId) {
                        errors.push('Row ' + (i + 1) + ': Please select a ' + type + '.');
                        valid = false;
                    }

                    if (minQty >= maxQty) {
                        errors.push('Row ' + (i + 1) + ': Max quantity must be greater than min quantity.');
                        valid = false;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Please fix the following:\n\n' + errors.join('\n'));
                }
            });
        });
    </script>
@endsection--}}


@extends('dashboard.layouts.master')

@section('title', 'Add Stock - ' . $branch->name)

@section('content')
    <style>
        /* ── Cards ── */
        .stock-form-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 28px;
        }
        .section-header {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: #fff;
            padding: 14px 22px;
            border-radius: 10px;
            margin: -28px -28px 24px -28px;
            font-weight: 700;
            font-size: 17px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-header.green { background: linear-gradient(135deg,#27ae60,#2ecc71); }

        /* ── Summary bar ── */
        .summary-bar {
            display: flex;
            gap: 28px;
            align-items: center;
            background: linear-gradient(135deg,rgba(102,126,234,.06),rgba(118,75,162,.06));
            border: 1.5px solid rgba(102,126,234,.2);
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .summary-item { display:flex; flex-direction:column; align-items:center; }
        .summary-item .val { font-size:22px; font-weight:700; color:#667eea; line-height:1; }
        .summary-item .lbl { font-size:11px; color:#999; text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }

        /* ── Buttons ── */
        .btn-add-row {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: #fff; border: none; padding: 9px 20px;
            border-radius: 8px; font-weight: 600; font-size: 13px;
            cursor: pointer; transition: all .25s;
        }
        .btn-add-row:hover { transform:translateY(-2px); box-shadow:0 5px 18px rgba(102,126,234,.4); color:#fff; }

        .btn-remove-row {
            background:#fee2e2; border:1.5px solid #fca5a5; color:#dc2626;
            width:32px; height:32px; border-radius:7px; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            transition:all .2s; margin:0 auto;
        }
        .btn-remove-row:hover { background:#dc2626; color:#fff; border-color:#dc2626; }

        .row-number {
            width:28px; height:28px; border-radius:50%;
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:#fff; display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700; margin:0 auto;
        }

        /* ── Table ── */
        .table-scroll-wrapper { overflow-x:auto; overflow-y:visible; padding-bottom:8px; }
        .bulk-table {
            width:100%; min-width:1150px;
            border-collapse:separate; border-spacing:0 7px;
            table-layout:fixed;
        }
        .col-num   { width:45px; }
        .col-type  { width:130px; }
        .col-item  { width:330px; }
        .col-qty   { width:85px; }
        .col-min   { width:85px; }
        .col-max   { width:85px; }
        .col-cost  { width:110px; }
        .col-notes { width:195px; }
        .col-del   { width:50px; }

        .bulk-table thead th {
            background:#f4f6fb; color:#666;
            font-size:11px; font-weight:700;
            text-transform:uppercase; letter-spacing:.5px;
            padding:10px 10px; border:none; white-space:nowrap;
        }
        .bulk-table thead th:first-child { border-radius:8px 0 0 8px; }
        .bulk-table thead th:last-child  { border-radius:0 8px 8px 0; }

        .stock-row { background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.05); transition:box-shadow .2s; }
        .stock-row:hover { box-shadow:0 4px 16px rgba(102,126,234,.12); }
        .stock-row td {
            padding:9px 8px; vertical-align:middle;
            border-top:1px solid #f0f0f0; border-bottom:1px solid #f0f0f0;
        }
        .stock-row td:first-child { border-left:1px solid #f0f0f0; border-radius:8px 0 0 8px; padding-left:10px; }
        .stock-row td:last-child  { border-right:1px solid #f0f0f0; border-radius:0 8px 8px 0; padding-right:10px; }

        .stock-row .form-control {
            border:1.5px solid #e0e6ed; border-radius:7px !important;
            font-size:13px; height:36px; padding:4px 10px;
            transition:border-color .2s;
        }
        .stock-row .form-control:focus { border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.1); }
        .select2-container { min-width:160px; }

        /* ── Import zone ── */
        .import-zone {
            border:3px dashed #c5cae9; border-radius:14px;
            padding:38px 20px; text-align:center; cursor:pointer;
            transition:all .3s; background:#fafbff; position:relative;
        }
        .import-zone:hover, .import-zone.drag-over { border-color:#667eea; background:#f0ebff; }
        .import-zone .zone-icon { font-size:46px; color:#c5cae9; margin-bottom:10px; transition:color .3s; display:block; }
        .import-zone:hover .zone-icon, .import-zone.drag-over .zone-icon { color:#667eea; }
        .import-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .import-zone .zone-title { font-size:16px; font-weight:600; color:#667eea; margin-bottom:4px; }
        .import-zone .zone-sub   { font-size:12px; color:#aaa; }

        .btn-template {
            background:linear-gradient(135deg,#27ae60,#2ecc71);
            color:#fff; border:none; padding:9px 20px;
            border-radius:8px; font-weight:600; font-size:13px;
            text-decoration:none; display:inline-flex; align-items:center; gap:6px;
            transition:all .25s;
        }
        .btn-template:hover { transform:translateY(-2px); box-shadow:0 5px 18px rgba(39,174,96,.4); color:#fff; text-decoration:none; }

        /* ── Preview table ── */
        .preview-table { width:100%; border-collapse:collapse; font-size:13px; margin-top:14px; }
        .preview-table th {
            background:#f4f6fb; padding:8px 12px;
            font-weight:700; font-size:11px; text-transform:uppercase;
            border-bottom:2px solid #e0e6ed; white-space:nowrap;
        }
        .preview-table td { padding:8px 12px; border-bottom:1px solid #f0f0f0; }
        .preview-table tr:hover td { background:#fafbff; }

        .badge-product { background:#e8f4fd; color:#2980b9; padding:2px 9px; border-radius:10px; font-size:11px; font-weight:700; }
        .badge-error   { background:#fee; color:#e74c3c; padding:2px 9px; border-radius:10px; font-size:11px; }
        .badge-ok      { background:#e8f8f5; color:#27ae60; padding:2px 9px; border-radius:10px; font-size:11px; font-weight:700; }

        #importPreview { display:none; }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-plus-circle"></i> Add Stock — {{ $branch->name }}
            <small>Add manually or import from Excel</small>
        </h1>
    </section>

    <div style="padding:24px 28px;">

        {{-- Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><i class="bi bi-exclamation-triangle"></i> Please fix:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- ══════════════════════════════════════
             MANUAL FORM
        ══════════════════════════════════════ --}}
        <form action="{{ route('dashboard.branches.stock.store', $branch) }}" method="POST" id="stockForm">
            @csrf

            {{-- Summary bar --}}
            <div class="summary-bar">
                <div class="summary-item">
                    <span class="val" id="total-rows">1</span>
                    <span class="lbl">Items</span>
                </div>
                <div class="summary-item">
                    <span class="val" id="total-products">0</span>
                    <span class="lbl">Products</span>
                </div>
                <div class="summary-item">
                    <span class="val" id="total-qty">0</span>
                    <span class="lbl">Total Qty</span>
                </div>
                <div style="margin-left:auto;">
                    <button type="button" class="btn-add-row" id="addRowBtn">
                        <i class="bi bi-plus-lg"></i> Add Row
                    </button>
                </div>
            </div>

            {{-- Table card --}}
            <div class="stock-form-card">
                <div class="section-header">
                    <i class="bi bi-table"></i> Stock Items
                </div>

                <div class="table-scroll-wrapper">
                    <table class="bulk-table" id="stockTable">
                        <colgroup>
                            <col class="col-num"><col class="col-item">
                            <col class="col-qty"><col class="col-min"><col class="col-max">
                            <col class="col-cost"><col class="col-notes"><col class="col-del">
                        </colgroup>
                        <thead>
                        <tr>
                            <th style="text-align:center;">#</th>
                            <th>Product</th>
                            <th>Qty <span class="text-danger">*</span></th>
                            <th>Min Qty <span class="text-danger">*</span></th>
                            <th>Max Qty <span class="text-danger">*</span></th>
                            <th>Cost</th>
                            <th>Notes</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="stockBody">
                        @if(old('items'))
                            @foreach(old('items') as $i => $item)
                                <tr class="stock-row" data-index="{{ $i }}">
                                    <td><div class="row-number">{{ $i+1 }}</div></td>
                                    <input type="hidden" name="items[{{ $i }}][item_type]" value="product">
                                    <td>
                                        <select name="items[{{ $i }}][product_id]" class="form-control select2-product">
                                            <option value="">-- Select product --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" {{ ($item['product_id']??'')==$p->id?'selected':'' }}>
                                                    [{{ $p->product_id }}] {{ $p->description }} — {{ number_format($p->price,2) }} QAR
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[{{ $i }}][quantity]"    class="form-control qty-input" min="0" value="{{ $item['quantity']??0 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][min_quantity]" class="form-control" min="0" value="{{ $item['min_quantity']??5 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][max_quantity]" class="form-control" min="0" value="{{ $item['max_quantity']??100 }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][cost]"        class="form-control" min="0" step="0.01" value="{{ $item['cost']??'' }}" placeholder="0.00"></td>
                                    <td><input type="text"   name="items[{{ $i }}][notes]"       class="form-control" value="{{ $item['notes']??'' }}" placeholder="Optional..."></td>
                                    <td><button type="button" class="btn-remove-row remove-row"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <button type="button" class="btn-add-row" id="addRowBtn2">
                        <i class="bi bi-plus-lg"></i> Add Another Row
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <div style="display:flex;justify-content:flex-end;align-items:center;gap:12px;padding:10px 0 50px 0;">
                @can('view-stock')
                    <a href="{{ route('dashboard.branches.stock.index', $branch) }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;border:2px solid #dde1ea;background:#fff;color:#666;font-weight:600;font-size:15px;text-decoration:none;transition:all .25s;"
                       onmouseover="this.style.borderColor='#667eea';this.style.color='#667eea';"
                       onmouseout="this.style.borderColor='#dde1ea';this.style.color='#666';">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                @endcan
                @can('add-stock')
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:8px;padding:12px 32px;border-radius:10px;border:none;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;font-weight:700;font-size:15px;cursor:pointer;box-shadow:0 4px 15px rgba(39,174,96,.35);"
                            onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 22px rgba(39,174,96,.5)';"
                            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(39,174,96,.35)';">
                        <i class="bi bi-check-circle-fill"></i> Add All to Stock
                    </button>
                @endcan
            </div>
        </form>

        {{-- Divider --}}
        <div style="display:flex;align-items:center;gap:16px;margin:0 0 30px 0;">
            <div style="flex:1;height:2px;background:linear-gradient(to right,transparent,#e0e6ed);"></div>
            <span style="background:#fff;border:1.5px solid #e0e6ed;border-radius:20px;padding:7px 20px;font-size:12px;font-weight:700;color:#aaa;letter-spacing:.6px;text-transform:uppercase;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.04);">
            <i class="bi bi-file-earmark-excel" style="color:#27ae60;margin-right:5px;"></i> Or Import from Excel
        </span>
            <div style="flex:1;height:2px;background:linear-gradient(to left,transparent,#e0e6ed);"></div>
        </div>

        {{-- ══════════════════════════════════════
         EXCEL IMPORT (below the table)
    ══════════════════════════════════════ --}}
        <div class="stock-form-card">
            <div class="section-header green">
                <i class="bi bi-file-earmark-excel"></i> Import from Excel
            </div>

            {{-- ── Step guide + Download button ── --}}
            <div style="background:#f0fdf4;border:2px dashed #86efac;border-radius:10px;padding:18px 20px;margin-bottom:20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
                    <div style="font-size:13px;color:#444;line-height:1.8;">
                        <div style="font-weight:700;color:#15803d;font-size:14px;margin-bottom:6px;">
                            <i class="bi bi-123"></i> How to import:
                        </div>
                        <span style="display:inline-flex;align-items:center;gap:5px;margin-left:4px;">
                            <span style="background:#15803d;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">1</span>
                            Download the template below
                        </span><br>
                        <span style="display:inline-flex;align-items:center;gap:5px;margin-left:4px;">
                            <span style="background:#15803d;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">2</span>
                            Fill in <strong>item_id</strong> (product code/barcode) + quantity + min/max
                        </span><br>
                        <span style="display:inline-flex;align-items:center;gap:5px;margin-left:4px;">
                            <span style="background:#15803d;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">3</span>
                            Upload the file using the drop zone
                        </span>
                        <div style="margin-top:8px;font-size:12px;color:#888;">
                            <i class="bi bi-info-circle"></i>
                            The <code>type</code> column must always be <strong>product</strong>.
                            Sheet name must be <strong>"Stock Data"</strong>.
                        </div>
                    </div>
                    <a href="{{ route('dashboard.branches.stock.template', $branch) }}"
                       style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:10px;padding:12px 22px;font-weight:700;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 3px 12px rgba(21,128,61,.35);transition:all .25s;flex-shrink:0;"
                       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 18px rgba(21,128,61,.45)';"
                       onmouseout="this.style.transform='';this.style.boxShadow='0 3px 12px rgba(21,128,61,.35)';">
                        <i class="bi bi-file-earmark-arrow-down" style="font-size:18px;"></i>
                        <span>Download<br><small style="font-weight:400;font-size:11px;opacity:.9;">Excel Template (.xlsx)</small></span>
                    </a>
                </div>
            </div>

            {{-- ── separator ── --}}
            <div style="text-align:center;margin:0 0 16px;color:#aaa;font-size:12px;letter-spacing:1px;">
                — then upload your filled file below —
            </div>

            {{-- Drop zone --}}
            <div class="import-zone" id="importZone">
                <input type="file" id="excelFile" accept=".xlsx,.xls,.csv">
                <i class="bi bi-cloud-upload zone-icon"></i>
                <div class="zone-title">Drop your Excel file here</div>
                <div class="zone-sub">.xlsx &nbsp;·&nbsp; .xls &nbsp;·&nbsp; .csv &nbsp;·&nbsp; or click to browse</div>
            </div>

            {{-- Preview (shown after file selected) --}}
            <div id="importPreview">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-2" style="flex-wrap:wrap;gap:10px;">
                    <span id="previewTitle" style="font-size:14px;"></span>
                    <div style="display:flex;gap:8px;">
                        <button type="button" class="btn btn-sm btn-default" id="clearImport">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                        @can('add-stock')
                            <button type="button" class="btn btn-sm btn-success" id="submitImport">
                                <i class="bi bi-check-circle"></i> Import Valid Rows
                            </button>
                        @endcan
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="preview-table">
                        <thead>
                        <tr>
                            <th>#</th><th>Type</th><th>Item ID</th>
                            <th>Qty</th><th>Min</th><th>Max</th>
                            <th>Cost</th><th>Notes</th><th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Hidden import form --}}
        <form action="{{ route('dashboard.branches.stock.import', $branch) }}" method="POST" id="importForm">
            @csrf
            <input type="hidden" name="import_data" id="importDataInput">
        </form>

    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function () {

            // ─── Build option HTML once ───────────────────────────────────────────────
            var productOptions = '<option value="">-- Select product --</option>';
            @foreach($products as $p)
                productOptions += '<option value="{{ $p->id }}">[{{ addslashes($p->product_id) }}] {{ addslashes($p->description) }} — {{ number_format($p->price,2) }} QAR</option>';
            @endforeach

            // ─── Manual table ─────────────────────────────────────────────────────────
            var rowIndex = 0;

            function buildRow(index) {
                return `
        <tr class="stock-row" data-index="${index}">
            <td><div class="row-number">${index+1}</div></td>
            <input type="hidden" name="items[${index}][item_type]" value="product">
            <td>
                <select name="items[${index}][product_id]" class="form-control select2-product">${productOptions}</select>
            </td>
            <td><input type="number" name="items[${index}][quantity]"    class="form-control qty-input" min="0" value="0" required></td>
            <td><input type="number" name="items[${index}][min_quantity]" class="form-control" min="0" value="5" required></td>
            <td><input type="number" name="items[${index}][max_quantity]" class="form-control" min="0" value="100" required></td>
            <td><input type="number" name="items[${index}][cost]"        class="form-control" min="0" step="0.01" placeholder="0.00"></td>
            <td><input type="text"   name="items[${index}][notes]"       class="form-control" placeholder="Optional..."></td>
            <td><button type="button" class="btn-remove-row remove-row"><i class="bi bi-trash3"></i></button></td>
        </tr>`;
            }

            function addRow() {
                var $row = $(buildRow(rowIndex));
                $('#stockBody').append($row);
                $row.find('.select2-product').select2({ width:'100%', placeholder:'Search product...' });
                rowIndex++;
                reindex();
                updateSummary();
            }

            // Init: existing old() rows or fresh
            if ($('#stockBody tr').length > 0) {
                rowIndex = $('#stockBody tr').length;
                $('#stockBody .select2-product').select2({ width:'100%', placeholder:'Search product...' });
            } else {
                addRow();
            }

            $('#addRowBtn, #addRowBtn2').on('click', addRow);

            $(document).on('click', '.remove-row', function () {
                if ($('#stockBody tr').length === 1) { alert('Need at least one row!'); return; }
                $(this).closest('tr').remove();
                reindex(); updateSummary();
            });


            function reindex() {
                $('#stockBody tr').each(function(i){ $(this).find('.row-number').text(i+1); });
            }

            function updateSummary() {
                var rows     = $('#stockBody tr').length;
                var totalQty = 0;
                $('#stockBody .qty-input').each(function(){ totalQty += parseInt($(this).val())||0; });
                $('#total-rows').text(rows);
                $('#total-products').text(rows);
                $('#total-qty').text(totalQty);
            }

            $(document).on('input', '.qty-input', updateSummary);

            $('#stockForm').on('submit', function(e) {
                var errors = [];
                $('#stockBody tr').each(function(i) {
                    var $row  = $(this);
                    var itemId = $row.find('select[name*="product_id"]').val();
                    var min    = parseInt($row.find('input[name*="min_quantity"]').val());
                    var max    = parseInt($row.find('input[name*="max_quantity"]').val());
                    if (!itemId)    errors.push('Row '+(i+1)+': Please select a product.');
                    if (min >= max) errors.push('Row '+(i+1)+': Max must be greater than Min.');
                });
                if (errors.length) { e.preventDefault(); alert(errors.join('\n')); }
            });

            // ─── Excel Import (SheetJS) ───────────────────────────────────────────────
            var parsedRows = [];

            var zone = document.getElementById('importZone');
            zone.addEventListener('dragover',  function(e){ e.preventDefault(); zone.classList.add('drag-over'); });
            zone.addEventListener('dragleave', function()  { zone.classList.remove('drag-over'); });
            zone.addEventListener('drop', function(e){
                e.preventDefault(); zone.classList.remove('drag-over');
                if (e.dataTransfer.files[0]) processFile(e.dataTransfer.files[0]);
            });
            document.getElementById('excelFile').addEventListener('change', function(){
                if (this.files[0]) processFile(this.files[0]);
            });

            function processFile(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var wb = XLSX.read(new Uint8Array(e.target.result), { type:'array' });

                    // Prefer "Stock Data" sheet, else first sheet
                    var sheetName = wb.SheetNames.indexOf('Stock Data') >= 0
                        ? 'Stock Data' : wb.SheetNames[0];
                    var ws  = wb.Sheets[sheetName];

                    // Get raw rows as arrays
                    var raw = XLSX.utils.sheet_to_json(ws, { header:1, defval:'' });
                    if (!raw || !raw.length) { alert('File is empty!'); return; }

                    // Find header row (look for "type" within first 5 rows)
                    var headerRowIdx = -1;
                    var headers = [];
                    for (var r = 0; r < Math.min(5, raw.length); r++) {
                        var rowNorm = raw[r].map(function(v){ return String(v).toLowerCase().replace(/[^a-z_]/g,'').trim(); });
                        if (rowNorm.indexOf('type') >= 0 || rowNorm.indexOf('itemid') >= 0) {
                            headerRowIdx = r;
                            headers = rowNorm;
                            break;
                        }
                    }

                    if (headerRowIdx === -1) {
                        alert('Could not find headers!\nFirst row must contain: type, item_id, quantity, min_quantity, max_quantity');
                        return;
                    }

                    // Map column names to indexes
                    function colIdx(names) {
                        for (var n = 0; n < names.length; n++) {
                            var i = headers.indexOf(names[n]);
                            if (i >= 0) return i;
                        }
                        return -1;
                    }

                    var CI = {
                        type  : colIdx(['type']),
                        id    : colIdx(['itemid','item_id','productid','product_id']),
                        qty   : colIdx(['quantity','qty']),
                        min   : colIdx(['min_quantity','minquantity','minqty','min']),
                        max   : colIdx(['max_quantity','maxquantity','maxqty','max']),
                        cost  : colIdx(['cost']),
                        notes : colIdx(['notes']),
                    };

                    if (CI.type === -1 || CI.id === -1) {
                        alert('Missing required columns.\nMake sure columns "type" and "item_id" exist.\nFound: ' + headers.join(', '));
                        return;
                    }

                    parsedRows = [];
                    var errorCount = 0;

                    for (var i = headerRowIdx + 1; i < raw.length; i++) {
                        var row = raw[i];
                        if (!row || row.every(function(v){ return String(v).trim() === ''; })) continue;

                        var typeVal = String(row[CI.type]  || '').trim().toLowerCase();
                        var itemId  = String(row[CI.id]    || '').trim();
                        var qty     = Math.max(0, parseInt(row[CI.qty]  || 0) || 0);
                        var minQty  = Math.max(0, parseInt(row[CI.min]  || 0) || 0);
                        var maxQty  = Math.max(0, parseInt(row[CI.max]  || 0) || 0);
                        var cost    = CI.cost  >= 0 ? (parseFloat(row[CI.cost])  || '') : '';
                        var notes   = CI.notes >= 0 ? String(row[CI.notes] || '').trim() : '';

                        var rowErrors = [];
                        if (typeVal !== 'product') rowErrors.push('type must be "product"');
                        if (!itemId)   rowErrors.push('missing item_id');
                        if (minQty >= maxQty) rowErrors.push('min must be less than max');

                        if (rowErrors.length) errorCount++;
                        parsedRows.push({ type:typeVal, item_id:itemId, qty:qty, min_qty:minQty, max_qty:maxQty, cost:cost, notes:notes, errors:rowErrors });
                    }

                    renderPreview(errorCount);
                };
                reader.readAsArrayBuffer(file);
            }

            function renderPreview(errorCount) {
                var tbody = document.getElementById('previewBody');
                tbody.innerHTML = '';
                var validCount = 0;

                parsedRows.forEach(function(r, i) {
                    var hasErr = r.errors.length > 0;
                    if (!hasErr) validCount++;

                    var typeBadge = r.type === 'product'
                        ? '<span class="badge-product">📦 Product</span>'
                        : '<span class="badge-error">⚠ ' + (r.type || '?') + '</span>';

                    var status = hasErr
                        ? '<span class="badge-error">✗ ' + r.errors.join(' · ') + '</span>'
                        : '<span class="badge-ok">✓ OK</span>';

                    var tr = document.createElement('tr');
                    if (hasErr) tr.style.background = '#fff8f8';
                    tr.innerHTML =
                        '<td style="color:#999;">' + (i+1) + '</td>' +
                        '<td>' + typeBadge + '</td>' +
                        '<td><code style="font-size:12px;background:#f4f6fb;padding:2px 6px;border-radius:4px;">' + r.item_id + '</code></td>' +
                        '<td>' + r.qty + '</td>' +
                        '<td>' + r.min_qty + '</td>' +
                        '<td>' + r.max_qty + '</td>' +
                        '<td>' + (r.cost !== '' ? r.cost : '<span style="color:#ccc;">—</span>') + '</td>' +
                        '<td style="color:#888;font-size:12px;">' + (r.notes || '<span style="color:#ccc;">—</span>') + '</td>' +
                        '<td>' + status + '</td>';
                    tbody.appendChild(tr);
                });

                var title = '<strong>' + parsedRows.length + '</strong> rows detected';
                if (errorCount) title += ' &nbsp;·&nbsp; <span style="color:#e74c3c;">' + errorCount + ' with errors</span>';
                title += ' &nbsp;·&nbsp; <span style="color:#27ae60;font-weight:600;">' + validCount + ' ready</span>';
                document.getElementById('previewTitle').innerHTML = title;
                document.getElementById('importPreview').style.display = 'block';
            }

            document.getElementById('clearImport').addEventListener('click', function(){
                parsedRows = [];
                document.getElementById('previewBody').innerHTML = '';
                document.getElementById('importPreview').style.display = 'none';
                document.getElementById('excelFile').value = '';
            });

            document.getElementById('submitImport').addEventListener('click', function(){
                var valid = parsedRows.filter(function(r){ return r.errors.length === 0; });
                if (!valid.length) { alert('No valid rows to import!'); return; }
                if (!confirm('Import ' + valid.length + ' valid row(s) to stock?')) return;
                document.getElementById('importDataInput').value = JSON.stringify(valid);
                document.getElementById('importForm').submit();
            });

        });
    </script>
@endsection
