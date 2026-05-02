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

            {{-- Item Type Selection --}}
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

            {{-- Product Selection --}}
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

            {{-- Lens Selection --}}
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

            {{-- Stock Details --}}
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

            {{-- Submit --}}
            <div class="text-right">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Add to Stock
                </button>
                <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-default btn-lg">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
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
