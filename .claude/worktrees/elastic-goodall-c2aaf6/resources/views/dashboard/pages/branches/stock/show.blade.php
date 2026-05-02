@extends('dashboard.layouts.master')

@section('title', 'Stock Details')

@section('content')

    <style>
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 12px 12px 0 0;
            margin: -25px -25px 20px -25px;
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 180px;
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            flex: 1;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .type-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .type-badge.product {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .type-badge.lens {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
        }

        .qty-display {
            font-size: 48px;
            font-weight: 900;
            text-align: center;
            padding: 30px;
            border-radius: 12px;
            margin: 20px 0;
        }

        .qty-display.normal { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .qty-display.low { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .qty-display.out { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
    </style>

    <div class="box box-primary">
        <div class="box-header with-border" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px;">
            <h3 class="box-title" style="font-size: 22px; font-weight: 700;">
                <i class="fa fa-info-circle"></i> Stock Details
            </h3>
            <div class="box-tools pull-right">
                <a href="{{ route('dashboard.branches.stock.index', $branch) }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('dashboard.branches.stock.edit', [$branch, $stock]) }}" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('dashboard.branches.stock.movements', [$branch, $stock]) }}" class="btn btn-info">
                    <i class="fa fa-history"></i> History
                </a>
            </div>
        </div>

        <div class="box-body">
            <div class="row">

                {{-- Left Column --}}
                <div class="col-md-8">
                    <div class="detail-card">
                        <div class="detail-header">
                            <h4 style="margin: 0; font-weight: 700;">
                                <i class="fa fa-{{ $stock->stockable_type === 'App\\Product' ? 'cube' : 'eye' }}"></i>
                                Item Information
                            </h4>
                        </div>

                        {{-- Type --}}
                        <div class="detail-row">
                            <div class="detail-label">Item Type</div>
                            <div class="detail-value">
                                @if($stock->stockable_type === 'App\\Product')
                                    <span class="type-badge product">
                                    <i class="fa fa-cube"></i> Product
                                </span>
                                @else
                                    <span class="type-badge lens">
                                    <i class="fa fa-eye"></i> Lens
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Code --}}
                        <div class="detail-row">
                            <div class="detail-label">Code</div>
                            <div class="detail-value">
                                <strong style="color: #667eea; font-size: 16px;">
                                    {{ $stock->stockable ? ($stock->stockable->product_id ?? $stock->stockable->id) : 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">
                                <strong>{{ $stock->description }}</strong>
                            </div>
                        </div>

                        {{-- If Lens: Show additional details --}}
                        @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                            <div class="detail-row">
                                <div class="detail-label">Brand</div>
                                <div class="detail-value">{{ $stock->stockable->brand ?? '-' }}</div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Index</div>
                                <div class="detail-value">{{ $stock->stockable->index ?? '-' }}</div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Frame Type</div>
                                <div class="detail-value">{{ $stock->stockable->frame_type ?? '-' }}</div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">Lens Type</div>
                                <div class="detail-value">{{ $stock->stockable->lense_type ?? '-' }}</div>
                            </div>
                        @endif

                        {{-- Branch --}}
                        <div class="detail-row">
                            <div class="detail-label">Branch</div>
                            <div class="detail-value">
                                <i class="fa fa-building"></i> {{ $stock->branch->name }}
                            </div>
                        </div>

                        {{-- Cost --}}
                        @if($stock->last_cost)
                            <div class="detail-row">
                                <div class="detail-label">Last Cost</div>
                                <div class="detail-value">{{ number_format($stock->last_cost, 2) }} QAR</div>
                            </div>
                        @endif

                        {{-- Limits --}}
                        <div class="detail-row">
                            <div class="detail-label">Min Quantity</div>
                            <div class="detail-value">{{ $stock->min_quantity }}</div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Max Quantity</div>
                            <div class="detail-value">{{ $stock->max_quantity }}</div>
                        </div>

                        {{-- Totals --}}
                        <div class="detail-row">
                            <div class="detail-label">Total In</div>
                            <div class="detail-value" style="color: #27ae60;">
                                <strong>+{{ $stock->total_in }}</strong>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Total Out</div>
                            <div class="detail-value" style="color: #e74c3c;">
                                <strong>-{{ $stock->total_out }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-md-4">

                    {{-- Current Quantity --}}
                    <div class="detail-card">
                        <div class="detail-header">
                            <h4 style="margin: 0; font-weight: 700;">
                                <i class="fa fa-cubes"></i> Current Stock
                            </h4>
                        </div>

                        @php
                            $status = $stock->stock_status;
                            $qtyClass = $stock->quantity > $stock->min_quantity ? 'normal' :
                                       ($stock->quantity > 0 ? 'low' : 'out');
                        @endphp

                        <div class="qty-display {{ $qtyClass }}">
                            {{ $stock->quantity }}
                            <div style="font-size: 14px; margin-top: 10px; opacity: 0.9;">
                                {{ $status['label'] }}
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Reserved</div>
                            <div class="detail-value">
                                <span class="badge bg-warning">{{ $stock->reserved_quantity }}</span>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Available</div>
                            <div class="detail-value">
                                <strong style="color: #3498db; font-size: 18px;">{{ $stock->available_quantity }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="detail-card">
                        <div class="detail-header">
                            <h4 style="margin: 0; font-weight: 700;">
                                <i class="bi bi-lightning"></i> Quick Actions
                            </h4>
                        </div>

                        <a href="{{ route('dashboard.branches.stock.movements', [$branch, $stock]) }}"
                           class="btn btn-block btn-primary" style="margin-bottom: 10px;">
                            <i class="bi bi-clock-history"></i> View Movement History
                        </a>

                        <a href="{{ route('dashboard.branches.stock.edit', [$branch, $stock]) }}"
                           class="btn btn-block btn-warning" style="margin-bottom: 10px;">
                            <i class="bi bi-pencil"></i> Edit Stock Limits
                        </a>

                        <button class="btn btn-block btn-danger btn-delete-stock"
                                data-id="{{ $stock->id }}"
                                data-description="{{ $stock->description }}">
                            <i class="bi bi-trash"></i> Delete Stock Item
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete <strong id="stockDescription"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This will remove the stock item from this branch's inventory.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none;">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-delete-stock').on('click', function() {
                var stockId = $(this).data('id');
                var description = $(this).data('description');

                var deleteUrl = '{{ route("dashboard.branches.stock.destroy", [$branch, $stock]) }}';

                $('#stockDescription').text(description);
                $('#deleteForm').attr('action', deleteUrl);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
