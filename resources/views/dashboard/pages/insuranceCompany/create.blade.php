@extends('dashboard.layouts.master')

@section('title', 'Add Insurance Company')

@section('styles')
    <style>
        .add-insurance-page {
            padding: 20px;
        }

        .box-add {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            background: white;
        }

        .box-add .box-header {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-add .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-add .box-header h3 {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .box-add .box-header h3 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-add .box-body {
            padding: 40px;
        }

        /* Form Sections */
        .form-section {
            background: linear-gradient(135deg, #f3e5f5 0%, #fff 100%);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #ce93d8;
        }

        .form-section h4 {
            color: #8e44ad;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ce93d8;
        }

        .form-section h4 i {
            margin-right: 10px;
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            padding: 8px 10px;
            border-radius: 6px;
        }

        /* Form Groups */
        .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-group label i {
            color: #8e44ad;
            margin-right: 5px;
        }

        .form-group label .required {
            color: #e74c3c;
            margin-left: 3px;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #8e44ad;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
            outline: none;
        }

        /* Categories Table */
        .categories-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .categories-table thead {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
        }

        .categories-table thead th {
            padding: 18px 20px;
            color: white;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .categories-table tbody tr {
            border-bottom: 1px solid #f3e5f5;
            transition: background 0.2s;
        }

        .categories-table tbody tr:hover {
            background: #faf8fb;
        }

        .categories-table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
        }

        .categories-table tbody td:first-child {
            font-weight: 600;
            color: #8e44ad;
            font-size: 15px;
        }

        .categories-table tbody td input {
            width: 100%;
            max-width: 200px;
        }

        /* Action Buttons */
        .btn-save {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(142, 68, 173, 0.3);
            cursor: pointer;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(142, 68, 173, 0.4);
        }

        .btn-save i {
            margin-right: 8px;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
            text-decoration: none;
            color: white;
        }

        .btn-cancel i {
            margin-right: 8px;
        }

        /* Help Text */
        .help-text {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .bi-shield-plus, .bi-building, .bi-info-circle, .bi-tags {
            color: white !important;
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

        .box-add {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@stop

@section('content')

    <section class="content-header">
        <h1>
            <i class="bi bi-shield-plus"></i> Add Insurance Company
            <small>Create new insurance partner</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('dashboard.get-all-insurance-companies') }}">Insurance Companies</a></li>
            <li class="active">Add New</li>
        </ol>
    </section>

    <div class="add-insurance-page">
        <div class="box box-success box-add">
            <div class="box-header">
                <h3>
                    <i class="bi bi-shield-plus"></i> Add New Insurance Company
                </h3>
            </div>

            <div class="box-body">
                @include('dashboard.partials._errors')

                <form action="{{ route('dashboard.post-add-insurance-company') }}" method="POST">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <h4>
                            <i class="bi bi-info-circle"></i> Basic Information
                        </h4>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="company_name">
                                        <i class="bi bi-building"></i> Company Name
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="company_name"
                                           id="company_name"
                                           class="form-control"
                                           placeholder="Enter insurance company name"
                                           required
                                           value="{{ old('company_name') }}">
                                    <small class="help-text">Enter the official name of the insurance company</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">
                                        <i class="bi bi-toggle-on"></i> Status
                                        <span class="required">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="help-text">Set company status</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories and Discounts Section -->
                    <div class="form-section">
                        <h4>
                            <i class="bi bi-tags"></i> Categories & Discount Rates
                        </h4>

                        <table class="categories-table">
                            <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Discount Percentage (%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <i class="bi bi-tag"></i> {{ $category->category_name }}
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="categories[{{ $category->id }}]"
                                               class="form-control"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               placeholder="e.g. 15.00"
                                               value="{{ old('categories.' . $category->id) }}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <small class="help-text" style="display: block; margin-top: 15px;">
                            <i class="bi bi-info-circle"></i> Enter discount percentages for each category. Leave empty if no discount applies.
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i> Save Company
                        </button>
                        <a href="{{ route('dashboard.get-all-insurance-companies') }}" class="btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
