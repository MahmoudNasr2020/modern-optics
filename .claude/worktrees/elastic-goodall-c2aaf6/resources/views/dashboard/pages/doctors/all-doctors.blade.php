@extends('dashboard.layouts.master')

@section('title', 'Doctors Management')

@section('content')

    <style>
        .doctors-page {
            padding: 20px;
        }

        .box-doctors {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-doctors .box-header {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-doctors .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-doctors .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-doctors .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-doctors .box-header .btn {
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

        .box-doctors .box-header .btn:hover {
            background: white;
            color: #16a085;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

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

        .doctors-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .doctors-table thead {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
        }

        .doctors-table thead th {
            color: white;
            font-weight: 700;
            padding: 18px 15px;
            border: none;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .doctors-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .doctors-table tbody tr {
            transition: all 0.3s;
        }

        .doctors-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .doctor-name {
            font-weight: 700;
            color: #16a085;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .doctor-name i {
            background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(22, 160, 133, 0.3);
        }

        .doctor-code {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
            font-style: italic;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
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

        .btn-show {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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

        .box-doctors {
            animation: fadeInUp 0.5s ease-out;
        }

        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
            vertical-align: middle !important;
        }

        @media (max-width: 768px) {
            .action-buttons {
                gap: 5px;
            }

            .action-buttons .btn {
                min-width: 35px;
                height: 35px;
            }
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-user-md"></i> Doctors Management
            <small>Manage system doctors</small>
        </h1>
    </section>

    <div class="doctors-page">
        <div class="box box-primary box-doctors">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> All Doctors
                </h3>
                <div class="box-tools pull-right">
                    {{--@if(auth()->user()->hasPermission('create_users'))--}}
                        <a href="{{ route('dashboard.get-add-doctor') }}" class="btn btn-sm">
                            <i class="fa fa-plus"></i> Add New Doctor
                        </a>
                   {{-- @else
                        <button class="btn btn-sm" disabled>
                            <i class="fa fa-lock"></i> Add New Doctor
                        </button>
                    @endif--}}
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

                <!-- Filters -->
                <div class="filter-box">
                    <form method="GET" action="{{ route('dashboard.get-all-doctors') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="🔍 Search by doctor name or code..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="fa fa-search"></i> Search
                                </button>
                               {{-- @if(auth()->user()->hasPermission('create_users'))--}}
                                    <a href="{{ route('dashboard.get-add-doctor') }}" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add Doctor
                                    </a>
                                {{--@endif--}}
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Doctors Table -->
                <div class="table-responsive">
                    <table class="table table-hover doctors-table">
                        <thead>
                        <tr>
                            <th width="10%" class="text-center">#</th>
                            <th width="25%">Doctor Code</th>
                            <th width="35%">Doctor Name</th>
                            <th width="30%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($doctors as $index => $doctor)
                            <tr>
                                <td class="text-center">
                                    <strong style="color: #16a085; font-size: 16px;">
                                        {{ $doctors->firstItem() + $index }}
                                    </strong>
                                </td>

                                <td>
                                    <div class="doctor-code">
                                        <i class="fa fa-barcode"></i> {{ $doctor->code }}
                                    </div>
                                </td>

                                <td>
                                    <div class="doctor-name">
                                        <i class="fa fa-user-md"></i>
                                        <span>{{ $doctor->name }}</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('dashboard.show-doctor', $doctor->id) }}"
                                           class="btn btn-show btn-xs"
                                           title="View Doctor">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ route('dashboard.get-update-doctor', $doctor->id) }}"
                                           class="btn btn-edit btn-xs"
                                           title="Update Doctor">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-delete btn-xs btn-delete-doctor"
                                                data-id="{{ $doctor->id }}"
                                                data-name="{{ $doctor->name }}"
                                                title="Delete Doctor">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fa fa-user-md"></i>
                                        <h4>No doctors found</h4>
                                        <p style="color: #999;">Try adjusting your search or add a new doctor</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination if needed --}}

                    <div class="text-center" style="margin-top: 25px;">
                        {{ $doctors->appends(request()->query())->links() }}
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
                        <i class="fa fa-exclamation-triangle"></i> Confirm Delete
                    </h4>
                </div>
                <form id="deleteForm" method="GET">
                    @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="alert alert-warning" style="border-left: 5px solid #f39c12;">
                            <i class="fa fa-warning"></i>
                            Are you sure you want to delete doctor <strong id="doctorName"></strong>?
                        </div>
                        <p class="text-muted" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i>
                            This action cannot be undone. All doctor data will be permanently removed.
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; border-radius: 6px;">
                            <i class="fa fa-trash"></i> Delete Doctor
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
            // Delete Doctor
            $(document).on('click', '.btn-delete-doctor', function() {
                var doctorId = $(this).data('id');
                var doctorName = $(this).data('name');

                var deleteRoute = '{{ url("dashboard/delete-doctor") }}/' + doctorId;

                $('#doctorName').text(doctorName);
                $('#deleteForm').attr('action', deleteRoute);
                $('#deleteModal').modal('show');
            });

            console.log('Doctors management script loaded successfully');
        });
    </script>
@endsection
