@extends('dashboard.layouts.master')

@section('title', 'New Transfer Request')

@section('content')

    <style>
        .create-transfer-page { padding: 20px; }

        .box-create {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .box-create .box-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-create .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 2px solid #f0f2f5;
        }

        .form-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin: -25px -25px 20px -25px;
            font-weight: 700;
        }

        .item-type-selector {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
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
            position: relative;
            z-index: 1;
        }

        .type-card strong {
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .type-card .description {
            font-size: 13px;
            margin-top: 10px;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }

        .stock-info-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        .stock-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .stock-badge.high {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .stock-badge.low {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .stock-badge.out {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
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

        .box-footer .btn {
            border-radius: 12px;
            padding: 12px 26px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.25s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        /* زرار الحفظ */
        .box-footer .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            box-shadow: 0 8px 18px rgba(34,197,94,0.25);
        }

        .box-footer .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(34,197,94,0.35);
            filter: brightness(1.05);
        }

        /* زرار Cancel */
        .box-footer .btn-default {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            color: #475569;
            box-shadow: 0 6px 14px rgba(0,0,0,0.05);
        }

        .box-footer .btn-default:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        /* الأيقونات */
        .box-footer .btn i {
            margin-right: 8px;
            font-size: 16px;
        }

        /* تأثير ضوء عند الضغط */
        .box-footer .btn:active {
            transform: scale(0.97);
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
            <i class="bi bi-plus-circle"></i> New Transfer Request
            <small>Create inter-branch transfer</small>
        </h1>
    </section>

    <div class="create-transfer-page">
        <form action="{{ route('dashboard.stock-transfers.store') }}" method="POST" id="transferForm">
            @csrf

            <div class="box box-create">
                <div class="box-header">
                    <h3 class="box-title" style="font-size: 22px; font-weight: 700; position: relative; z-index: 1;">
                        <i class="bi bi-arrow-left-right"></i> New Stock Transfer
                    </h3>
                </div>

                <div class="box-body" style="padding: 30px;">

                    {{-- Branches Selection --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="bi bi-building"></i> Select Branches
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From Branch <span class="text-danger">*</span></label>
                                    <select name="from_branch_id" id="from_branch_id" class="form-control select2" required>
                                        <option value="">Select source branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_branch_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To Branch <span class="text-danger">*</span></label>
                                    <select name="to_branch_id" id="to_branch_id" class="form-control select2" required>
                                        <option value="">Select destination branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_branch_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Item Type Selection --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="bi bi-box-seam"></i> Select Item Type
                        </div>

                        <input type="hidden" name="item_type" id="item_type" value="{{ old('item_type', 'product') }}">

                        <div class="item-type-selector">
                            <div class="type-card {{ old('item_type', 'product') == 'product' ? 'active' : '' }}" data-type="product">
                                <i class="bi bi-box"></i>
                                <strong>Product</strong>
                                <div class="description">Eyewear frames and accessories</div>
                            </div>

                            <div class="type-card {{ old('item_type') == 'lens' ? 'active' : '' }}" data-type="lens">
                                <i class="bi bi-eye"></i>
                                <strong>Lens</strong>
                                <div class="description">Optical lenses</div>
                            </div>
                        </div>
                    </div>

                    {{-- Item Selection --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="bi bi-search"></i> Select Item
                        </div>

                        {{-- Products Dropdown --}}
                        <div id="product_section" style="{{ old('item_type', 'product') == 'product' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label>Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id" class="form-control select2">
                                    <option value="">Select a product</option>
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
                        </div>

                        {{-- Lenses Dropdown --}}
                        <div id="lens_section" style="{{ old('item_type') == 'lens' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label>Lens <span class="text-danger">*</span></label>
                                <select name="lens_id" id="lens_id" class="form-control select2">
                                    <option value="">Select a lens</option>
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
                        </div>

                        {{-- Stock Info Display --}}
                        <div id="stock_info" class="stock-info-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-box-arrow-up"></i> Source Stock:</strong>
                                    <span id="from_stock" class="stock-badge">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-box-arrow-down"></i> Destination Stock:</strong>
                                    <span id="to_stock" class="stock-badge">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transfer Details --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="bi bi-info-circle"></i> Transfer Details
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                           min="1" value="{{ old('quantity', 1) }}" required>
                                    @error('quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Priority</label>
                                    <select name="priority" class="form-control">
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="box-footer" style="padding: 20px 30px; background: #f8f9fa; border-top: 2px solid #e0e6ed;">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Create Transfer Request
                    </button>
                    <a href="{{ route('dashboard.stock-transfers.index') }}" class="btn btn-default btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // Initialize Select2
            $('.select2').select2({
                width: '100%'
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
                } else {
                    $('#product_section').slideUp();
                    $('#lens_section').slideDown();
                    $('#product_id').prop('required', false);
                    $('#lens_id').prop('required', true);
                }

                // Clear stock info
                $('#stock_info').hide();
            });

            // Fetch Stock Info
            function fetchStockInfo() {
                var fromBranch = $('#from_branch_id').val();
                var toBranch = $('#to_branch_id').val();
                var itemType = $('#item_type').val();
                var itemId = itemType === 'product' ? $('#product_id').val() : $('#lens_id').val();

                if (!fromBranch || !toBranch || !itemId) {
                    $('#stock_info').hide();
                    return;
                }

                $.ajax({
                    url: '{{ route("dashboard.stock-transfers.check-stock") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        from_branch_id: fromBranch,
                        to_branch_id: toBranch,
                        item_type: itemType,
                        item_id: itemId
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update from stock
                            var fromQty = response.from_stock;
                            var fromClass = fromQty > 10 ? 'high' : (fromQty > 0 ? 'low' : 'out');
                            $('#from_stock').removeClass('high low out').addClass(fromClass).text(fromQty);

                            // Update to stock
                            var toQty = response.to_stock;
                            var toClass = toQty > 10 ? 'high' : (toQty > 0 ? 'low' : 'out');
                            $('#to_stock').removeClass('high low out').addClass(toClass).text(toQty);

                            $('#stock_info').slideDown();
                        } else {
                            alert('Error checking stock');
                        }
                    },
                    error: function() {
                        alert('Error checking stock availability');
                    }
                });
            }

            // Trigger stock check
            $('#from_branch_id, #to_branch_id, #product_id, #lens_id').on('change', fetchStockInfo);

            // Form validation
            $('#transferForm').on('submit', function(e) {
                var fromBranch = $('#from_branch_id').val();
                var toBranch = $('#to_branch_id').val();

                if (fromBranch === toBranch) {
                    e.preventDefault();
                    alert('Source and destination branches cannot be the same!');
                    return false;
                }
            });
        });
    </script>
@endsection
