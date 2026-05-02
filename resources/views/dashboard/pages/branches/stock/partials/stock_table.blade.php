{{-- resources/views/dashboard/pages/stock/partials/stock_table.blade.php --}}

@if($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover stock-table">
            <thead>
            <tr>
                <th width="5%">#</th>
                <th width="8%">Type</th>
                <th width="10%">Code</th>
                <th width="30%">Description</th>
                <th width="8%">Quantity</th>
                <th width="8%">Reserved</th>
                <th width="8%">Available</th>
                <th width="8%">Min/Max</th>
                <th width="10%">Status</th>
                <th width="15%">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $stock)
                <tr>
                    <td><strong>{{ $loop->iteration }}</strong></td>

                    {{-- Type Badge --}}
                    <td>
                        @if($stock->stockable_type === 'App\\Product')
                            <span class="type-badge product">
                                    <i class="fa fa-cube"></i> Product
                                </span>
                        @else
                            <span class="type-badge lens">
                                    <i class="fa fa-eye"></i> Lens
                                </span>
                        @endif
                    </td>

                    {{-- Code --}}
                    <td>
                        <strong style="color: #667eea;">
                            {{ $stock->stockable ? ($stock->stockable->product_id ?? $stock->stockable->id) : 'N/A' }}
                        </strong>
                    </td>

                    {{-- Description --}}
                    <td>
                        <strong>{{ $stock->description }}</strong>
                        @if($stock->stockable_type === 'App\\glassLense' && $stock->stockable)
                            <br>
                            <small class="text-muted">
                                {{ $stock->stockable->brand ?? '' }} -
                                {{ $stock->stockable->index ?? '' }}
                            </small>
                        @endif
                    </td>

                    {{-- Quantity --}}
                    <td>
                        <strong style="font-size: 16px;
                                   color: {{ $stock->quantity > $stock->min_quantity ? '#27ae60' : ($stock->quantity > 0 ? '#f39c12' : '#e74c3c') }};">
                            {{ $stock->quantity }}
                        </strong>
                    </td>

                    {{-- Reserved --}}
                    <td>
                        @if($stock->reserved_quantity > 0)
                            <span class="badge bg-warning">{{ $stock->reserved_quantity }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- Available --}}
                    <td>
                        <strong style="color: #3498db;">{{ $stock->available_quantity }}</strong>
                    </td>

                    {{-- Min/Max --}}
                    <td>
                        <small class="text-muted">
                            {{ $stock->min_quantity }} / {{ $stock->max_quantity }}
                        </small>
                    </td>

                    {{-- Status --}}
                    <td>
                        @php $status = $stock->stock_status; @endphp
                        <span class="label label-{{ $status['class'] }}">
                                <i class="fa {{ $status['icon'] }}"></i>
                                {{ $status['label'] }}
                            </span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('dashboard.branches.stock.show', [$branch, $stock]) }}"
                               class="btn btn-sm btn-info" title="Details">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a href="{{ route('dashboard.branches.stock.edit', [$branch, $stock]) }}"
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="{{ route('dashboard.branches.stock.movements', [$branch, $stock]) }}"
                               class="btn btn-sm btn-primary" title="History">
                                <i class="fa fa-history"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot style="background: #f8f9fa; font-weight: 700;">
            <tr>
                <td colspan="4"><strong>TOTAL</strong></td>
                <td><strong>{{ $items->sum('quantity') }}</strong></td>
                <td><strong>{{ $items->sum('reserved_quantity') }}</strong></td>
                <td><strong>{{ $items->sum('available_quantity') }}</strong></td>
                <td colspan="3"></td>
            </tr>
            </tfoot>
        </table>
    </div>
@else
    <div class="text-center" style="padding: 60px 20px;">
        <i class="fa fa-inbox" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
        <h3 style="color: #999;">No Stock Items Found</h3>
        <p style="color: #bbb;">Add some stock items to get started</p>
        <a href="{{ route('dashboard.branches.stock.create', $branch) }}" class="btn btn-primary" style="margin-top: 15px;">
            <i class="fa fa-plus"></i> Add Stock
        </a>
    </div>
@endif
