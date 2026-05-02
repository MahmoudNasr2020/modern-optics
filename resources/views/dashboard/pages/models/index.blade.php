@extends('dashboard.layouts.master')

@section('title', 'Models Management')

@section('content')

    <style>
        .models-page {
            padding: 20px;
        }

        .box-models {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-models .box-header {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-models .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-models .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-models .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-models .box-header .btn {
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

        .box-models .box-header .btn:hover {
            background: white;
            color: #16a085;
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

        .stat-icon.total { background: linear-gradient(135deg, #16a085 0%, #138d75 100%); }

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
            border-color: #16a085;
            box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
        }

        .filter-box .btn-primary {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            border: none;
            color: white;
            font-weight: 600;
        }

        .filter-box .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(22, 160, 133, 0.3);
        }

        /* Table Styles */
        .models-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .models-table thead {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
        }

        .models-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .models-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .models-table tbody tr {
            transition: all 0.3s;
        }

        .models-table tbody tr:hover {
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
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
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
            border-color: #16a085;
            box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
        }

        .modal .modal-footer {
            background: #f8f9fa;
            border-top: 2px solid #e8ecf7;
            padding: 20px 30px;
        }

        .modal .modal-footer .button-add,
        .modal .modal-footer .button-update {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
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
            box-shadow: 0 5px 15px rgba(22, 160, 133, 0.3);
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

        .box-models {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th,
        .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-tag"></i> Models Management
            <small>Manage product models</small>
        </h1>
    </section>

    <div class="models-page">
        <div class="box box-primary box-models">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="bi bi-list-ul"></i> All Models
                </h3>
                @can('create-models')
                <div class="box-tools pull-right">
                    <button data-toggle="modal" data-target="#myModal" class="btn btn-sm">
                        <i class="bi bi-plus-circle-fill"></i> Add New Model
                    </button>
                </div>
                @endcan
            </div>

            <div class="box-body" style="padding: 30px;">

                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $models->total() }}</h4>
                            <p>Total Models</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-models') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search Models..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                @can('create-models')
                                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">
                                        <i class="bi bi-plus-circle-fill"></i> Add Model
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Models Table -->
                <div class="table-responsive">
                    <table class="table table-hover models-table">
                        <thead>
                        <tr>
                            <th width="10%" class="text-center">#</th>
                            <th width="30%">Model ID</th>
                            <th width="20%">Category Name</th>
                            <th width="20%">Brand Name</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($models as $index => $model)
                            <tr>
                                <td class="text-center">
                                    <strong style="color: #16a085; font-size: 16px;">{{ $models->firstItem() + $index }}</strong>
                                </td>

                                <td>
                                    {{ $model->model_id }}
                                </td>

                                <td>
                                    {{ $model->category->category_name }}
                                </td>

                                <td>
                                   {{ $model->brand->brand_name }}
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        @can('edit-models')
                                        <button type="button"
                                                data-id="{{ $model->id }}"
                                                data-model="{{ $model->model_id }}"
                                                class="btn btn-edit btn-xs update_model"
                                                data-toggle="modal"
                                                data-target="#updateModal"
                                                title="Update Model">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @endcan

                                        @can('delete-models')
                                        <button type="button"
                                                class="btn btn-delete btn-xs"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-id="{{ $model->id }}"
                                                data-name="{{ $model->model_id }}"
                                                title="Delete Model">
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
                                        <i class="bi bi-tag"></i>
                                        <h4>No models found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new model</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($models->hasPages())
                    <div class="text-center" style="margin-top: 25px;">
                        {{ $models->appends(request()->query())->links() }}
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
                    <h3><i class="bi bi-plus-circle"></i> Add Model</h3>
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
                        <label for="brand_id">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control" disabled>
                            <option value="">Select Category First</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="model_id">Model ID</label>
                        <input type="text" name="model_id" class="form-control add-modal-id" placeholder="Enter model ID">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-add">
                        <i class="bi bi-check-circle-fill"></i> Add Model
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
                    <h3><i class="bi bi-pencil-square"></i> Update Model</h3>
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
                    <label for="model_id">Model ID</label>
                    <input type="text" name="model_id" class="form-control update_model_id" placeholder="Enter model ID">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success button-update">
                        <i class="bi bi-check-circle-fill"></i> Update Model
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
                <form id="deleteForm" method="GET">
                    @csrf
                    @method('delete')
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="bi bi-exclamation-triangle"></i>
                            Are you sure you want to delete model <strong id="modelName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="bi bi-info-circle"></i>
                            This action cannot be undone. <br>
                            <strong>All products associated with this model and any related data will also be deleted if they have no stock.</strong>
                        </p>

                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="bi bi-trash-fill"></i> Delete Model
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

            // Set the brands after choosing the category ID
            let category_ID = document.querySelector('#category_id');
            $(category_ID).on('change', function(e) {
                if($(this).val() != '') {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.filter-brands-by-category-id")}}',
                        data: { category_id: category_ID.value },
                        success: function(response) {
                            let brandSelect = document.querySelector('#brand_id');
                            brandSelect.innerHTML = '<option value="">Select Brand</option>';
                            response.forEach((brand, index) => {
                                brandSelect.innerHTML += `
                                    <option value="${brand.id}">${brand.brand_name}</option>
                                `;
                            });
                            brandSelect.disabled = false;
                        }
                    });
                } else {
                    let brandSelect = document.querySelector('#brand_id');
                    brandSelect.innerHTML = '<option value="">Select Category First</option>';
                    brandSelect.disabled = true;
                }
            });

            // Add Model
            $('.modal .modal-footer .button-add').on('click', function(event) {
                event.preventDefault();
                category_id = $('#category_id').val();
                brand_id = $('#brand_id').val();
                model_id = $('.modal .modal-body input[name="model_id"]').val().trim();

                if(model_id == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Enter Model ID!');
                } else if(category_id == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Select Category!');
                } else if(brand_id == '') {
                    $('.alert-danger').css('display', 'block');
                    $('.alert-danger p').html('Please Select Brand!');
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: '{{route("dashboard.post-add-model")}}',
                        data: {model_id: model_id.trim(), category_id: category_id, brand_id: brand_id},
                        success: function(response) {
                            if(response.message == 'This Model Added Before!') {
                                $('.success-message').css('display', 'none');
                                $('.alert-danger').css('display', 'block');
                                $('.alert-danger p').html('This Model Added Before!');
                            } else {
                                $('.alert-danger').css('display', 'none');
                                $('.success-message').css('display', 'block');
                                $('.success-message p').html('Model Added Successfully!');
                                setTimeout(function() {location.reload()}, 100);
                            }
                        },
                    });
                }
            });

            // Update Model
            let updateBtns = document.querySelectorAll('.update_model');
            updateBtns.forEach(btn => {
                btn.addEventListener('click', e => {

                    let model_id_int  = btn.getAttribute('data-id');
                    let model_id_text = btn.getAttribute('data-model');

                    // حط القيمة في المودال
                    document.querySelector('.update_model_id').value = model_id_text;

                    $('#updateModal .modal-footer .button-update').off('click').on('click', function(event) {

                        model_id_text = $('.update_model_id').val().trim();

                        if(model_id_text == '') {
                            $('.alert-danger').show().find('p').html('Please Enter Model ID!');
                        } else {
                            $.ajax({
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                type: "POST",
                                url: '{{route("dashboard.update-model")}}',
                                data: {id: model_id_int, model_id: model_id_text},
                                success: function(response) {
                                    $('.alert-danger').hide();
                                    $('.success-message').show().find('p').html('Model Updated Successfully!');
                                    setTimeout(function() {location.reload()}, 100);
                                },
                            })
                        }
                    });
                })
            });


            // Clear Messages After Modal Closing
            $('#myModal, #updateModal').on('hidden.bs.modal', function (e) {
                $('.alert-danger').css('display', 'none');
                $('.success-message').css('display', 'none');
                $('input[name="model_id"]').val('');
                $('#category_id').val('');
                $('#brand_id').html('<option value="">Select Category First</option>').prop('disabled', true);
            })

            // Focus On Model Input on modal Opening
            $('#myModal, #updateModal').on('shown.bs.modal', function () {
                $('.modal .modal-body input[name="model_id"]').focus();
                $('.update_model_id').focus();
            });

            // Delete Model - Set data when modal opens
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var modelId = button.data('id');
                var modelName = button.data('name');

                var deleteRoute = '{{ route("dashboard.delete-model", ":id") }}';
                deleteRoute = deleteRoute.replace(':id', modelId);

                $('#modelName').text(modelName);
                $('#deleteForm').attr('action', deleteRoute);
            });

        })
    </script>
@endsection
