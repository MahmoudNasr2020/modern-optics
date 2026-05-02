@extends('dashboard.layouts.master')

@section('title', 'Products Management')

@section('content')

    <style>
        .products-page{padding:20px}
        .box-products{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);overflow:hidden;border:none}
        .box-products .box-header{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%);color:#fff;padding:25px 30px;border:none;position:relative;overflow:hidden}
        .box-products .box-header::before{content:'';position:absolute;top:-50%;right:-10%;width:200px;height:200px;background:rgba(255,255,255,0.1);border-radius:50%}
        .box-products .box-header .box-title{font-size:22px;font-weight:700;position:relative;z-index:1}
        .box-products .box-header .box-title i{background:rgba(255,255,255,0.2);padding:10px;border-radius:8px;margin-right:10px}
        .box-products .box-header .btn{position:relative;z-index:1;background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.4);color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;transition:.3s}
        .box-products .box-header .btn:hover{background:#fff;color:#00a86b;transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,0.2)}
        .stats-row{display:flex;gap:15px;margin-bottom:25px;flex-wrap:wrap}
        .stat-card{flex:1;min-width:200px;background:#fff;padding:20px;border-radius:10px;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,0.05);display:flex;align-items:center;gap:15px;transition:.3s}
        .stat-card:hover{transform:translateY(-3px);box-shadow:0 5px 15px rgba(0,0,0,0.1)}
        .stat-icon{width:55px;height:55px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff;box-shadow:0 3px 10px rgba(0,0,0,0.2)}
        .stat-icon.total{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%)}
        .stat-content h4{margin:0 0 5px 0;font-size:26px;font-weight:700;color:#333}
        .stat-content p{margin:0;font-size:12px;color:#666;font-weight:600}
        .filter-box{background:linear-gradient(135deg,#f8f9ff 0%,#fff 100%);padding:20px;border-radius:10px;margin-bottom:25px;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,0.05)}
        .filter-box .form-control,.filter-box .btn{border:2px solid #e0e6ed;border-radius:8px!important;padding:10px 15px;transition:.3s;height:42px}
        .filter-box .form-control:focus{border-color:#00a86b;box-shadow:0 0 0 3px rgba(0,168,107,.1)}
        .filter-box .btn-primary{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%);border:none;color:#fff;font-weight:600}
        .products-table{background:#fff;border-radius:12px;overflow:hidden}
        .products-table thead{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%)}
        .products-table thead th{color:#fff;font-weight:700;padding:18px 15px;border:none;text-transform:uppercase;font-size:12px;letter-spacing:1px}
        .products-table tbody td{padding:18px 15px;vertical-align:middle;border-bottom:1px solid #f0f2f5}
        .products-table tbody tr:hover{background:linear-gradient(135deg,#f8f9ff 0%,#fff 100%);transform:translateX(3px);box-shadow:0 2px 8px rgba(0,0,0,0.05)}
        .product-id-badge{background:linear-gradient(135deg,#00a86b 0%,#008b5a 100%);color:#fff;padding:8px 16px;border-radius:25px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;box-shadow:0 2px 8px rgba(0,0,0,.15);display:inline-block}
        .action-buttons{display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
        .action-buttons .btn{min-width:40px;height:40px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;transition:.3s;border:none}
        .btn-view{background:linear-gradient(135deg,#3498db 0%,#2980b9 100%);color:#fff}
        .btn-edit{background:linear-gradient(135deg,#f39c12 0%,#e67e22 100%);color:#fff}
        .btn-delete{background:linear-gradient(135deg,#e74c3c 0%,#c0392b 100%);color:#fff}
        .empty-state{text-align:center;padding:60px 20px}
        .empty-state i{font-size:80px;color:#ddd;margin-bottom:20px}
    </style>

    @include('dashboard.partials._errors')

    <section class="content-header">
        <h1><i class="bi bi-box-seam-fill"></i> Products Management <small>Products Master Data</small></h1>
    </section>

    <div class="products-page">
        <div class="box box-primary box-products">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="bi bi-list-ul"></i> All Products</h3>
                @can('create-products')
                <div class="box-tools pull-right">
                    <a href="{{ route('dashboard.get-add-product') }}" class="btn btn-sm"><i class="bi bi-plus-circle-fill"></i> Add New Product</a>
                </div>
                @endguest
            </div>

            <div class="box-body" style="padding:30px">

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon total"><i class="bi bi-boxes"></i></div>
                        <div class="stat-content"><h4>{{ $totalItems }}</h4><p>Total Products</p></div>
                    </div>
                </div>

                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-products') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Search by Product ID or Description..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                                @can('create-products')
                                <a href="{{ route('dashboard.get-add-product') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Add Product</a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover products-table">
                        <thead>
                        <tr>
                            <th width="4%" class="text-center">#</th>
                            <th width="10%">Product ID</th>
                            <th width="20%">Description</th>
                            <th width="9%">Category</th>
                            <th width="9%">Brand</th>
                            @can('view-product-prices')
                            <th width="8%" class="text-center">
                                <i class="bi bi-tag" style="font-size:11px;"></i> Buy Price
                            </th>
                            <th width="8%" class="text-center">
                                <i class="bi bi-bag" style="font-size:11px;"></i> Sell Price
                            </th>
                            @endcan
                            <th width="9%" class="text-center">
                                <i class="bi bi-building" style="font-size:11px;"></i>
                                Store Stock
                                @if($mainBranch)
                                    <div style="font-size:9px;font-weight:400;opacity:.8;">{{ $mainBranch->name }}</div>
                                @endif
                            </th>
                            <th width="11%" class="text-center">Barcode</th>
                            <th width="12%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $index=>$product)
                            <tr>
                                <td class="text-center"><strong style="color:#00a86b;font-size:16px;">{{ $products->firstItem()+$index }}</strong></td>
                                <td><span class="product-id-badge">{{ $product->product_id }}</span></td>
                                <td><strong style="color:#333;">{{ $product->description }}</strong></td>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                <td>{{ $product->brand->brand_name ?? 'N/A' }}</td>
                                @can('view-product-prices')
                                <td class="text-center">
                                    @if($product->price)
                                        <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                            {{ number_format($product->price, 2) }}
                                        </span>
                                    @else
                                        <span style="color:#ccc;font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($product->retail_price)
                                        <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                            {{ number_format($product->retail_price, 2) }}
                                        </span>
                                    @else
                                        <span style="color:#ccc;font-size:12px;">—</span>
                                    @endif
                                </td>
                                @endcan
                                <td class="text-center">
                                    @php
                                        $storeStock = $storeStockMap->get($product->id);
                                        $qty        = $storeStock ? $storeStock->quantity : null;
                                    @endphp
                                    @if($qty === null)
                                        <span style="color:#ccc;font-size:12px;">—</span>
                                    @elseif($qty <= 0)
                                        <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">0</span>
                                    @elseif($storeStock && $storeStock->min_quantity > 0 && $qty <= $storeStock->min_quantity)
                                        <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">{{ $qty }}</span>
                                    @else
                                        <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">{{ $qty }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($product->barcode)
                                        <div style="display:inline-flex;flex-direction:column;align-items:center;gap:4px;">
                                            <svg class="barcode-svg" data-barcode="{{ $product->barcode }}"
                                                 style="max-width:120px;"></svg>
                                            <button type="button"
                                                    onclick="printBarcode('{{ $product->barcode }}','{{ addslashes($product->description) }}')"
                                                    style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;padding:3px 10px;border-radius:5px;font-size:11px;font-weight:700;cursor:pointer;">
                                                <i class="bi bi-printer"></i> Print
                                            </button>
                                        </div>
                                    @else
                                        <span style="color:#ccc;font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @can('view-products')
                                        <a href="{{ route('dashboard.show-product',$product->id) }}" class="btn btn-view btn-xs"><i class="bi bi-eye-fill"></i></a>
                                        @endcan

                                        @can('edit-products')
                                        <a href="{{ route('dashboard.get-update-product',$product->id) }}" class="btn btn-edit btn-xs"><i class="bi bi-pencil-square"></i></a>
                                        @endcan
                                        @can('delete-products')
                                        <button type="button" class="btn btn-delete btn-xs btn-delete-product" data-id="{{ $product->id }}" data-name="{{ $product->product_id }}"><i class="bi bi-trash-fill"></i></button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->can('view-product-prices') ? 10 : 8 }}">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <h4>No products found</h4>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="text-center" style="margin-top:25px;">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#e74c3c 0%,#c0392b 100%);color:#fff;border:none;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
                    <h4 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete</h4>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding:25px;">
                        <div class="alert alert-warning" style="border-left:5px solid #f39c12;">
                            Are you sure you want to delete product <strong id="productName"></strong>?
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        // Delete modal
        $(document).on('click','.btn-delete-product',function(){
            let id=$(this).data('id');
            let name=$(this).data('name');
            let route='{{ route("dashboard.delete-product",":id") }}'.replace(':id',id);
            $('#productName').text(name);
            $('#deleteForm').attr('action',route);
            $('#deleteModal').modal('show');
        });

        // Render all barcode SVGs on page load
        document.querySelectorAll('.barcode-svg[data-barcode]').forEach(function(el) {
            try {
                JsBarcode(el, el.getAttribute('data-barcode'), {
                    format: 'CODE128', width: 1.4, height: 36,
                    displayValue: true, fontSize: 10, margin: 4
                });
            } catch(e) {}
        });

        // Print a single barcode
        function printBarcode(code, description) {
            var tempSvg = document.createElementNS('http://www.w3.org/2000/svg','svg');
            document.body.appendChild(tempSvg);
            JsBarcode(tempSvg, code, { format:'CODE128', width:2, height:60, displayValue:true, fontSize:14, margin:10 });
            var svgData = new XMLSerializer().serializeToString(tempSvg);
            document.body.removeChild(tempSvg);
            var win = window.open('','_blank','width=420,height=320');
            win.document.write('<html><head><title>Barcode</title><style>body{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;margin:0;font-family:Arial,sans-serif;}p{margin:8px 0 0;font-size:13px;color:#555;font-weight:600;}</style></head><body>');
            win.document.write(svgData);
            win.document.write('<p>' + description + '</p>');
            win.document.write('<p style="color:#888;font-size:11px;">' + code + '</p>');
            win.document.write('</body></html>');
            win.document.close();
            setTimeout(function(){ win.print(); }, 400);
        }
    </script>
@endsection
