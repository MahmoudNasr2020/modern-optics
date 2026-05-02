@extends('dashboard.layouts.master')

@section('title', 'Edit Stock')

@section('content')

    <style>
        .edit-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        .section-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin: -30px -30px 25px -30px;
            font-weight: 700;
            font-size: 18px;
        }

        .item-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .item-info-card h4 {
            margin: 0 0 15px 0;
            font-weight: 700;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .item-detail:last-child {
            border-bottom: none;
        }

        .type-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(255,255,255,0.3);
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
           /* padding: 10px 15px;*/
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243,156,18,0.1);
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
            <i class="bi bi-pencil-square"></i> Edit Stock
            <small>Update stock limits</small>
        </h1>
    </section>

    <div class="stock-page" style="padding: 20px;">

        {{-- Item Information --}}
        <div class="item-info-card">
            <h4>
                <i class="bi bi-{{ $stock->stockable_type === 'App\\Product' ? 'box' : 'eye' }}"></i>
                Item Information
            </h4>

            <div class="item-detail">
                <span><strong>Type:</strong></span>
                <span>
                @if($stock->stockable_type === 'App\\Product')
                        <span class="type-badge">
                        <i class="bi bi-box"></i> Product
                    </span>
                    @else
                        <span class="type-badge">
                        <i class="bi bi-eye"></i> Lens
                    </span>
                    @endif
            </span>
            </div>

            <div class="item-detail">
                <span><strong>Code:</strong></span>
                <span>{{ $stock->item_code }}</span>
            </div>

            <div class="item-detail">
                <span><strong>Description:</strong></span>
                <span>{{ $stock->description }}</span>
            </div>

            @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                <div class="item-detail">
                    <span><strong>Brand:</strong></span>
                    <span>{{ $stock->stockable->brand }}</span>
                </div>

                <div class="item-detail">
                    <span><strong>Index:</strong></span>
                    <span>{{ $stock->stockable->index }}</span>
                </div>
            @endif

            <div class="item-detail">
                <span><strong>Branch:</strong></span>
                <span><i class="bi bi-building"></i> {{ $stock->branch->name }}</span>
            </div>

            <div class="item-detail">
                <span><strong>Current Quantity:</strong></span>
                <span style="font-size: 20px; font-weight: 900;">{{ $stock->quantity }}</span>
            </div>
        </div>

        {{-- Edit Form --}}
        <form action="{{ route('dashboard.branches.stock.update', [$branch, $stock]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="edit-card">
                <div class="section-header">
                    <i class="bi bi-sliders"></i> Stock Limits
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Minimum Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="min_quantity" class="form-control"
                                   min="0" value="{{ old('min_quantity', $stock->min_quantity) }}" required>
                            @error('min_quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Alert when stock falls below this level</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Maximum Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="max_quantity" class="form-control"
                                   min="0" value="{{ old('max_quantity', $stock->max_quantity) }}" required>
                            @error('max_quantity')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Target stock level</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="edit-card">
                <div class="section-header">
                    <i class="bi bi-currency-dollar"></i> Cost Information (Optional)
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Cost</label>
                            <input type="number" name="last_cost" class="form-control"
                                   min="0" step="0.01" value="{{ old('last_cost', $stock->last_cost) }}" placeholder="0.00">
                            <small class="text-muted">Most recent purchase cost</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Average Cost</label>
                            <input type="number" name="average_cost" class="form-control"
                                   min="0" step="0.01" value="{{ old('average_cost', $stock->average_cost) }}" placeholder="0.00">
                            <small class="text-muted">Average cost across all purchases</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="text-right">
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="bi bi-check-circle"></i> Update Stock
                </button>
                <a href="{{ route('dashboard.branches.stock.show', [$branch, $stock]) }}" class="btn btn-default btn-lg">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            $('form').on('submit', function(e) {
                var minQty = parseInt($('input[name="min_quantity"]').val());
                var maxQty = parseInt($('input[name="max_quantity"]').val());

                if (minQty >= maxQty) {
                    e.preventDefault();
                    alert('Maximum quantity must be greater than minimum quantity!');
                    return false;
                }
            });
        });
    </script>
@endsection
