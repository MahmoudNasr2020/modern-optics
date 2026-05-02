@extends('dashboard.layouts.master')

@section('title', 'Lenses Management')

@section('content')

    <style>
        .lenses-page {
            padding: 20px;
        }

        .box-lenses {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-lenses .box-header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-lenses .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-lenses .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-lenses .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-lenses .box-header .btn {
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

        .box-lenses .box-header .btn:hover {
            background: white;
            color: #9b59b6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Statistics Cards */
        .stats-row {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .stat-icon.total { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }

        .stat-content h4 {
            margin: 0 0 5px 0;
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }

        .stat-content p {
            margin: 0;
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        /* Filter Box */
        .filter-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-box .form-control, .filter-box .btn {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.3);
        }

        /* Table Styles */
        .lenses-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .lenses-table thead {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .lenses-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .lenses-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .lenses-table tbody tr {
            transition: all 0.3s;
        }

        .lenses-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Lens ID Badge */
        .lens-id-badge {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: inline-block;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            /*flex-wrap: wrap;*/
        }

        .action-buttons .btn {
            min-width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-view{background:linear-gradient(135deg,#3498db 0%,#2980b9 100%);color:#fff}

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #999;
            font-size: 18px;
            font-weight: 600;
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

        .box-lenses {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th,
        .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-eyeglasses"></i> Lenses Management
            <small>Manage glass lenses inventory</small>
        </h1>
    </section>

    <div class="lenses-page">
        <div class="box box-primary box-lenses">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> All Lenses
                </h3>
                @can('create-lenses')
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.get-add-glense') }}" class="btn btn-sm">
                        <i class="bi bi-plus-circle-fill"></i> Add New Lens
                    </a>
                </div>
                @endcan
            </div>

            <div class="box-body" style="padding: 30px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="bi bi-eyeglasses"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $lense_count }}</h4>
                            <p>Total Lenses</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-glassess-lenses') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search by Lens ID, Description or Price..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                @can('create-lenses')
                                <a href="{{ route('dashboard.get-add-glense') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle-fill"></i> Add Lens
                                </a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lenses Table -->
                <div class="table-responsive">
                    <table class="table table-hover lenses-table">
                        <thead>
                        <tr>
                            <th width="4%" class="text-center">#</th>
                            <th width="11%">Lens ID</th>
                            <th width="5%">Index</th>
                            <th width="16%">Description</th>
                            <th width="10%">Frame Type</th>
                            <th width="9%">Brand</th>
                            <th width="8%">Production</th>
                            <th width="8%">Price</th>
                            <th width="8%">Retail Price</th>
                            <th width="8%" class="text-center">Stock</th>
                            <th width="8%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lenses as $index => $lense)
                            <tr>
                                <td class="text-center">
                                    <strong style="color: #9b59b6; font-size: 16px;">{{ $lenses->firstItem() + $index }}</strong>
                                </td>

                                <td>
                                    <span class="lens-id-badge">{{ $lense->product_id }}</span>
                                </td>

                                <td>
                                    <strong style="color: #333;">{{ $lense->index }}</strong>
                                </td>

                                <td>
                                    <span style="font-size: 13px; color: #666;">{{ $lense->description }}</span>
                                </td>

                                <td>
                                    <span style="font-size: 12px; color: #666;">{{ $lense->frame_type }}</span>
                                </td>

                                <td>
                                    <span style="font-size: 12px; color: #666; font-weight: 600;">{{ $lense->brand }}</span>
                                </td>

                                <td>
                                    <span style="font-size: 12px; color: #666;">{{ $lense->lense_production }}</span>
                                </td>

                                <td>
                                    <strong style="color: #9b59b6;">{{ number_format($lense->price, 2) }}</strong>
                                </td>

                                <td>
                                    <strong style="color: #27ae60;">{{ number_format($lense->retail_price, 2) }}</strong>
                                </td>

                                @php
                                    $stockT = max(0, (int)($lense->net_stock ?? 0));
                                    if ($stockT === 0) {
                                        $stockT = max(0,(int)($lense->stock_r??0))
                                                + max(0,(int)($lense->stock_l??0))
                                                + max(0,(int)($lense->stock_unk??0));
                                    }
                                @endphp
                                {{-- Stock --}}
                                <td class="text-center">
                                    @if($stockT > 0)
                                        <span style="background:#e8f8f5;color:#27ae60;padding:5px 14px;border-radius:20px;font-weight:700;font-size:14px;">{{ $stockT }}</span>
                                    @else
                                        <span style="background:#f4f6fb;color:#aaa;padding:5px 14px;border-radius:20px;font-size:13px;">0</span>
                                    @endif
                                </td>

                                <td>

                                    <div class="action-buttons">
                                        @can('view-lenses')
                                            <a href="{{ route('dashboard.show-glassess-lenses',$lense->id) }}"
                                               class="btn btn-view btn-xs"
                                               title="Show Lens">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        @endcan

                                        @can('edit-lenses')
                                        <a href="{{ route('dashboard.get-edit-glense', $lense->id) }}"
                                           class="btn btn-edit btn-xs"
                                           title="Update Lens">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @endcan

                                        @can('delete-lenses')
                                        <button type="button"
                                                class="btn btn-delete btn-xs btn-delete-lens"
                                                data-id="{{ $lense->id }}"
                                                data-name="{{ $lense->product_id }}"
                                                title="Delete Lens">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="bi bi-eyeglasses"></i>
                                        <h4>No lenses found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new lens</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lenses->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $lenses->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="GET">
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete lens <strong id="lensName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This action cannot be undone. All lens data will be permanently removed.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="bi bi-trash-fill"></i> Delete Lens
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Delete Lens
            $(document).on('click', '.btn-delete-lens', function() {
                var lensId = $(this).data('id');
                var lensName = $(this).data('name');

                var deleteRoute = '{{ route("dashboard.delete-lense", ":id") }}';
                deleteRoute = deleteRoute.replace(':id', lensId);

                $('#lensName').text(lensName);
                $('#deleteForm').attr('action', deleteRoute);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
