@extends('dashboard.layouts.master')

@section('title', 'Categories Management')

@section('content')

    <style>
        .categories-page {
            padding: 20px;
        }

        .box-categories {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-categories .box-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-categories .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-categories .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-categories .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-categories .box-header .btn {
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

        .box-categories .box-header .btn:hover {
            background: white;
            color: #3498db;
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

        .stat-icon.total { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }

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
            //padding: 10px 15px;
            transition: all 0.3s;
            height: 42px;
        }

        .filter-box .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }

        /* Table Styles */
        .categories-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .categories-table thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .categories-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .categories-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .categories-table tbody tr {
            transition: all 0.3s;
        }

        .categories-table tbody tr:hover {
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
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
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
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .modal .modal-body .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .modal .modal-footer {
            background: #f8f9fa;
            border-top: 2px solid #e8ecf7;
            padding: 20px 30px;
        }

        .modal .modal-footer .button-add,
        .modal .modal-footer .button-update {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
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
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
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

        .box-categories {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th,
        .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-folder"></i> Categories Management
            <small>Manage product categories</small>
        </h1>
    </section>

    <div class="categories-page">
        <div class="box box-primary box-categories">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> All Categories
                </h3>
                <div class="box-tools pull-right">
                    <button data-toggle="modal" data-target="#myModal" class="btn btn-sm">
                        <i class="bi bi-plus-circle-fill"></i> Add New Category
                    </button>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="bi bi-folder"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $categories->total() }}</h4>
                            <p>Total Categories</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-categories') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search Categories..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">
                                    <i class="bi bi-plus-circle-fill"></i> Add Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="table table-hover categories-table">
                        <thead>
                        <tr>
                            <th width="10%" class="text-center">#</th>
                            <th width="70%">Category Name</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $index => $cat)
                            <tr>
                                <td class="text-center">
                                    {{ $categories->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $cat->category_name }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <button type="button"
                                                data-id="{{ $cat->id }}"
                                                class="btn btn-edit btn-xs update_category"
                                                data-toggle="modal"
                                                data-target="#updateModal"
                                                title="Update Category">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-delete btn-xs"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id="{{ $cat->id }}"
                                                data-name="{{ $cat->category_name }}"
                                                title="Delete Category">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-folder"></i>
                                        <h4>No categories found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new category</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $categories->appends(request()->query())->links() }}
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
                    <h3><i class="bi bi-plus-circle"></i> Add Category</h3>
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
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" class="form-control" placeholder="Enter category name">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-add">
                        <i class="bi bi-check-circle-fill"></i> Add Category
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
                    <h3><i class="bi bi-pencil-square"></i> Update Category</h3>
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
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" class="form-control update_cat_name" placeholder="Enter category name">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-update">
                        <i class="bi bi-check-circle-fill"></i> Update Category
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
                    @method('DELETE')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete category <strong id="categoryName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This action cannot be undone. <br>
                            <strong>All products and brands associated with this category will also be deleted if they have no stock.</strong>
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="bi bi-trash-fill"></i> Delete Category
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

            // Add Category
            $('.modal .modal-footer .button-add').on('click', function(event) {
                event.preventDefault();
                category_name = $('.modal .modal-body input[name="category_name"]').val().trim();
                if(category_name == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Enter Category Name!');
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.add-category")}}',
                        data: {category_name: category_name.trim()},
                        success: function(response) {
                            if(response.message == 'This Categoery Added Before!') {
                                $('.success-message').css('display', 'none');
                                $('.alert-danger').css('display', 'block');
                                $('.alert-danger p').html('This Categoery Added Before!');
                            } else {
                                $('.alert-danger').css('display', 'none');
                                $('.success-message').css('display', 'block');
                                $('.success-message p').html('Category Added Successfully!');
                                setTimeout(function() {location.reload()}, 100);
                            }
                        },
                    });
                }
            });

            // Update Category
            let updateBtns = document.querySelectorAll('.update_category');
            updateBtns.forEach(btn => {
                btn.addEventListener('click', e => {
                    $cat_id = btn.getAttribute('data-id');
                    $cat_name = btn.parentElement.parentElement.previousElementSibling.innerHTML;

                    // Set The Update Category modal input with the category name
                    document.querySelector('.update_cat_name').value = $cat_name.trim();

                    // Update Category Name Ajax Request
                    $('#updateModal .modal-footer .button-update').off('click').on('click', function(event) {
                        category_name = $('.update_cat_name').val().trim();
                        if(category_name == '') {
                            $('.alert-danger').css('display', 'block');
                            $('.alert-danger p').html('Please Enter Category Name!');
                        } else {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                type: "POST",
                                url: '{{route("dashboard.update-category")}}',
                                data: {id: $cat_id, category_name: category_name},

                                success: function(response) {
                                    if(response.message == 'This Categoery Added Before!') {
                                        $('.success-message').css('display', 'none');
                                        $('.alert-danger').css('display', 'block');
                                        $('.alert-danger p').html('This Categoery Already Exists!');
                                    } else {
                                        $('.alert-danger').css('display', 'none');
                                        $('.success-message').css('display', 'block');
                                        $('.success-message p').html('Category Updated Successfully!');
                                        setTimeout(function() {location.reload()}, 100);
                                    }
                                },
                            })
                        }
                    });
                })
            })

            // Clear Messages After Modal Closing
            $('#myModal, #updateModal').on('hidden.bs.modal', function (e) {
                $('.alert-danger').css('display', 'none');
                $('.success-message').css('display', 'none');
                $('input[name="category_name"]').val('');
            })

            // Focus On Category Input on modal Opening
            $('#myModal, #updateModal').on('shown.bs.modal', function () {
                $('.modal .modal-body input[name="category_name"]').focus();
                $('.update_cat_name').focus();
            })

            // Delete Category - Set data when modal opens
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var categoryId = button.data('id');
                var categoryName = button.data('name');

                var deleteRoute = '{{ route("dashboard.delete-category", ":id") }}';
                deleteRoute = deleteRoute.replace(':id', categoryId);

                $('#categoryName').text(categoryName);
                $('#deleteForm').attr('action', deleteRoute);
            });

        })
    </script>
@endsection
