@extends('dashboard.layouts.master')

@section('title', 'Brands Management')

@section('content')

    <style>
        .brands-page {
            padding: 20px;
        }

        .box-brands {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-brands .box-header {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-brands .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-brands .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-brands .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-brands .box-header .btn {
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

        .box-brands .box-header .btn:hover {
            background: white;
            color: #e67e22;
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

        .stat-icon.total { background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); }

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
            border-color: #e67e22;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(230, 126, 34, 0.3);
        }

        /* Table Styles */
        .brands-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .brands-table thead {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        }

        .brands-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .brands-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .brands-table tbody tr {
            transition: all 0.3s;
        }

        .brands-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
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

        /* Modal Styles */
        .modal .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal .modal-header {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border: none;
            padding: 20px 30px;
        }

        .modal .modal-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .modal .modal-header .close {
            color: white;
            opacity: 1;
            font-size: 28px;
            font-weight: 300;
        }

        .modal .modal-body {
            padding: 30px;
        }

        .modal .modal-body label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .modal .modal-body .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            //padding: 10px 15px;
            transition: all 0.3s;
        }

        .modal .modal-body .form-control:focus {
            border-color: #e67e22;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .modal .modal-footer {
            background: #f8f9fa;
            border-top: 2px solid #e8ecf7;
            padding: 20px 30px;
        }

        .modal .modal-footer .button-add,
        .modal .modal-footer .button-update {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .modal .modal-footer .button-add:hover,
        .modal .modal-footer .button-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
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

        .box-brands {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th,
        .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-award"></i> Brands Management
            <small>Manage product brands</small>
        </h1>
    </section>

    <div class="brands-page">
        <div class="box box-primary box-brands">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> All Brands
                </h3>
                @can('create-brands')
                <div class="box-tools pull-right">
                    <button data-toggle="modal" data-target="#myModal" class="btn btn-sm">
                        <i class="bi bi-plus-circle-fill"></i> Add New Brand
                    </button>
                </div>
                @endcan
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $brands->total() }}</h4>
                            <p>Total Brands</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-brands') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search Brands..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                @can('create-brands')
                                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">
                                    <i class="bi bi-plus-circle-fill"></i> Add Brand
                                </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Brands Table -->
                <div class="table-responsive">
                    <table class="table table-hover brands-table">
                        <thead>
                        <tr>
                            <th width="10%" class="text-center">#</th>
                            <th width="40%">Brand Name</th>
                            <th width="20%">Category Name</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($brands as $index => $brand)
                            <tr>
                                <td class="text-center">
                                    {{ $brands->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $brand->brand_name }}
                                </td>

                                <td>
                                    {{ optional($brand->category)->category_name ?? '—' }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        @can('edit-brands')
                                        <button type="button"
                                                data-id="{{ $brand->id }}"
                                                data-name="{{ $brand->brand_name }}"
                                                class="btn btn-edit btn-xs update_brand"
                                                data-toggle="modal"
                                                data-target="#updateModal"
                                                title="Update Brand">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @endcan

                                       @can('delete-brands')
                                        <button type="button"
                                                class="btn btn-delete btn-xs"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id="{{ $brand->id }}"
                                                data-name="{{ $brand->brand_name }}"
                                                title="Delete Brand">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                            @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-award"></i>
                                        <h4>No brands found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new brand</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($brands->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $brands->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><i class="bi bi-plus-circle"></i> Add Brand</h3>
                </div>

                <div class="modal-body">
                    <div class="alert alert-success success-message" style="display: none">
                        <i class="bi bi-check-circle-fill"></i>
                        <p class=""></p>
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <p class=""></p>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" name="brand_name" class="form-control" placeholder="Enter brand name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-add">
                        <i class="bi bi-check-circle-fill"></i> Add Brand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><i class="bi bi-pencil-square"></i> Update Brand</h3>
                </div>

                <div class="modal-body">
                    <div class="alert alert-success success-message" style="display: none">
                        <i class="bi bi-check-circle-fill"></i>
                        <p class=""></p>
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <p class=""></p>
                    </div>
                    <label for="brand_name">Brand Name</label>
                    <input type="text" name="brand_name" class="form-control update_brand_name" placeholder="Enter brand name">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-update">
                        <i class="bi bi-check-circle-fill"></i> Update Brand
                    </button>
                </div>
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
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete brand <strong id="brandName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This action cannot be undone. <br>
                            <strong>All products associated with this brand will also be deleted if they have no stock.</strong>
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="bi bi-trash-fill"></i> Delete Brand
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

            // Add Brand
            $('.modal .modal-footer .button-add').on('click', function(event) {
                event.preventDefault();
                brand_name = $('.modal .modal-body input[name="brand_name"]').val().trim();
                category_id = $('#category_id').val();

                if(brand_name == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Enter Brand Name!');
                } else if(category_id == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Select Category!');
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.post-add-brand")}}',
                        data: {brand_name: brand_name.trim(), category_id: category_id},
                        success: function(response) {
                            if(response.message == 'This Brand Added Before Under This Category!') {
                                $('.success-message').css('display', 'none');
                                $('.alert-danger').css('display', 'block');
                                $('.alert-danger p').html('This Brand Added Before Under This Category!');
                            } else {
                                $('.alert-danger').css('display', 'none');
                                $('.success-message').css('display', 'block');
                                $('.success-message p').html('Brand Added Successfully!');
                                setTimeout(function() {location.reload()}, 100);
                            }
                        },
                    });
                }
            });

            // Update Brand
            let updateBtns = document.querySelectorAll('.update_brand');

            updateBtns.forEach(btn => {
                btn.addEventListener('click', e => {

                    let brand_id   = btn.getAttribute('data-id');
                    let brand_name = btn.getAttribute('data-name');

                    // حط الاسم في المودال
                    document.querySelector('.update_brand_name').value = brand_name;

                    $('#updateModal .modal-footer .button-update')
                        .off('click')
                        .on('click', function(event) {

                            let new_brand_name = $('.update_brand_name').val().trim();

                            if(new_brand_name == '') {
                                $('.alert-danger').show().find('p').html('Please Enter Brand Name!');
                            } else {
                                $.ajax({
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    type: "POST",
                                    url: '{{route("dashboard.update-brand")}}',
                                    data: { id: brand_id, brand_name: new_brand_name },

                                    success: function(response) {
                                        if(response.message == 'This Brand Added Before!') {
                                            $('.success-message').hide();
                                            $('.alert-danger').show().find('p').html('This Brand Already Exists!');
                                        } else {
                                            $('.alert-danger').hide();
                                            $('.success-message').show().find('p').html('Brand Updated Successfully!');
                                            setTimeout(function() { location.reload() }, 100);
                                        }
                                    }
                                })
                            }
                        });
                })
            });


            // Clear Messages After Modal Closing
            $('#myModal, #updateModal').on('hidden.bs.modal', function (e) {
                $('.alert-danger').css('display', 'none');
                $('.success-message').css('display', 'none');
                $('input[name="brand_name"]').val('');
                $('#category_id').val('');
            })

            // Focus On Brand Input on modal Opening
            $('#myModal, #updateModal').on('shown.bs.modal', function () {
                $('.modal .modal-body input[name="brand_name"]').focus();
                $('.update_brand_name').focus();
            })

            // Delete Brand - Set data when modal opens
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var brandId = button.data('id');
                var brandName = button.data('name');

                // Note: You need to create delete-brand route
                var deleteRoute = '/dashboard/delete-brand/' + brandId;

                $('#brandName').text(brandName);
                $('#deleteForm').attr('action', deleteRoute);
            });

        })
    </script>
@endsection
