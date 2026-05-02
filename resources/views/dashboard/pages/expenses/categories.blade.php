@extends('dashboard.layouts.master')

@section('title', 'Expense Categories')

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
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
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
            color: #9b59b6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .categories-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .categories-table thead {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
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
            background: linear-gradient(135deg, #f8f5ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .type-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .type-badge.fixed {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .type-badge.variable {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .status-badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .status-badge.active {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }

        .branch-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .global-badge {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

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
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

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

        .box-categories {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-tags"></i> Expense Categories
            <small>Manage expense categories</small>
        </h1>
    </section>

    <div class="categories-page">
        <div class="box box-primary box-categories">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> All Categories
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-sm" data-toggle="modal" data-target="#addCategoryModal">
                        <i class="fa fa-plus"></i> Add Category
                    </button>
                </div>
            </div>

            <div class="box-body" style="padding: 30px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" style="border-left: 5px solid #27ae60;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover categories-table">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Name</th>
                            <th width="15%">Arabic Name</th>
                            @if(auth()->user()->canSeeAllBranches())
                                <th width="12%">Branch</th>
                            @endif
                            <th width="10%">Type</th>
                            <th width="23%">Description</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <strong style="color: #9b59b6; font-size: 16px;">{{ $loop->iteration }}</strong>
                                </td>
                                <td>
                                    <strong style="color: #333; font-size: 15px;">{{ $category->name }}</strong>
                                </td>
                                <td>{{ $category->name_ar ?? '-' }}</td>
                                @if(auth()->user()->canSeeAllBranches())
                                    <td>
                                        @if($category->branch_id)
                                            <span class="branch-badge">
                                                <i class="fa fa-building"></i>
                                                {{ $category->branch->name }}
                                            </span>
                                        @else
                                            <span class="global-badge">
                                                <i class="fa fa-globe"></i>
                                                Global
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <span class="type-badge {{ $category->type }}">
                                        {{ ucfirst($category->type) }}
                                    </span>
                                </td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                                        <i class="fa fa-{{ $category->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-edit btn-xs"
                                                onclick="editCategory({{ $category }})"
                                                title="Edit Category">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-delete btn-xs btn-delete-category"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                title="Delete Category">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->canSeeAllBranches() ? 8 : 7 }}">
                                    <div class="empty-state">
                                        <i class="fa fa-tags"></i>
                                        <h4>No categories found</h4>
                                        <p style="color: #999;">Start by adding your first category</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="fa fa-plus-circle"></i> Add New Category
                    </h4>
                </div>
                <form method="POST" action="{{ route('dashboard.expenses.categories.store') }}">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                            <label>Name (English) *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Name (Arabic)</label>
                            <input type="text" name="name_ar" class="form-control">
                        </div>
                        @if(auth()->user()->canSeeAllBranches())
                            <div class="form-group">
                                <label>Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">Global (All Branches)</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave empty for global category</small>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Type *</label>
                            <select name="type" class="form-control" required>
                                <option value="fixed">Fixed</option>
                                <option value="variable">Variable</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Close
                        </button>
                        <button type="submit" class="btn btn-success" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-save"></i> Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 700;">
                        <i class="fa fa-edit"></i> Edit Category
                    </h4>
                </div>
                <form method="POST" id="editCategoryForm">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                            <label>Name (English) *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Name (Arabic)</label>
                            <input type="text" name="name_ar" id="edit_name_ar" class="form-control">
                        </div>
                        @if(auth()->user()->canSeeAllBranches())
                            <div class="form-group">
                                <label>Branch</label>
                                <select name="branch_id" id="edit_branch_id" class="form-control">
                                    <option value="">Global (All Branches)</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Type *</label>
                            <select name="type" id="edit_type" class="form-control" required>
                                <option value="fixed">Fixed</option>
                                <option value="variable">Variable</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                                Active
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-save"></i> Update Category
                        </button>
                    </div>
                </form>
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
                        <i class="fa fa-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="GET">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="fa fa-warning"></i>
                            Are you sure you want to delete category: <strong id="categoryName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i>
                            This action cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-trash"></i> Delete Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function editCategory(category) {
            const checkbox = $('#edit_is_active');
            checkbox.iCheck('uncheck');
            if (Number(category.is_active) === 1) {
                checkbox.iCheck('check');
            }

            document.getElementById('editCategoryForm').action = "{{ route('dashboard.expenses.categories.update', ':id') }}".replace(':id', category.id);
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_name_ar').value = category.name_ar || '';
            document.getElementById('edit_type').value = category.type;
            document.getElementById('edit_description').value = category.description || '';

            @if(auth()->user()->canSeeAllBranches())
            document.getElementById('edit_branch_id').value = category.branch_id || '';
            @endif

            $('#editCategoryModal').modal('show');
        }

        $(document).ready(function() {
            // Delete Category
            $(document).on('click', '.btn-delete-category', function() {
                var categoryId = $(this).data('id');
                var categoryName = $(this).data('name');

                var finalRoute = '{{ url("dashboard/expenses/categories/delete") }}/' + categoryId;

                $('#categoryName').text(categoryName);
                $('#deleteForm').attr('action', finalRoute);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
