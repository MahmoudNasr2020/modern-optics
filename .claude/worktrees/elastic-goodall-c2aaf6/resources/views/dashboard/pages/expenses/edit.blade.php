@extends('dashboard.layouts.master')

@section('title', 'Edit Expense')

@section('content')

    <style>
        .expense-edit-page {
            padding: 20px;
        }

        .box-expense-edit {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }

        .box-expense-edit .box-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .box-expense-edit .box-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-expense-edit .box-header .box-title {
            font-size: 22px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .box-expense-edit .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-right: 10px;
        }

        .box-expense-edit .box-header .btn {
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

        .box-expense-edit .box-header .btn:hover {
            background: white;
            color: #f39c12;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .alert-cashier-deduct {
            background: linear-gradient(135deg, #fff3cd 0%, #fff 100%);
            border-left: 5px solid #f39c12;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-cashier-deduct i {
            font-size: 20px;
            margin-right: 10px;
            color: #f39c12;
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
        }

        .section-header .icon-box {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
        }

        .section-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #f39c12;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-group label .required {
            color: #e74c3c;
            font-weight: 700;
        }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            /*padding: 12px 15px;*/
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
        }

        .help-text {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }

        .btn-submit-group {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
            margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .btn-cancel {
            background: white;
            color: #666;
            border: 2px solid #ddd;
            padding: 14px 50px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            margin: 0 10px;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #333;
            transform: translateY(-3px);
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

        .form-section {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-edit"></i> Edit Expense
            <small>Update expense information</small>
        </h1>
    </section>

    <div class="expense-edit-page">
        <form action="{{ route('dashboard.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data" id="expenseForm">
            @csrf

            <div class="box box-warning box-expense-edit">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-pencil"></i> Edit Expense #{{ $expense->id }}
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="border-left: 5px solid #e74c3c;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4><i class="fa fa-ban"></i> Validation Errors:</h4>
                            <ul style="margin-bottom: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                   {{-- @if($expense->deducted_from_cashier)
                        <div class="alert-cashier-deduct">
                            <i class="fa fa-info-circle"></i>
                            <strong>Cashier Linked:</strong> This expense has been deducted from the cashier. Any amount changes will update the cashier transaction.
                        </div>
                    @endif--}}

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-info-circle"></i>
                            </div>
                            <h4>Basic Information</h4>
                        </div>

                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="branch_id">
                                            Branch
                                            <span class="required">*</span>
                                        </label>
                                        <select name="branch_id" id="branch_id" class="form-control" required>
                                            <option value="">-- Select Branch --</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ $expense->branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="help-text">
                                            <i class="fa fa-info-circle"></i>
                                            Select the branch for this expense
                                        </p>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ $expense->branch_id }}">
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">
                                        Category
                                        <span class="required">*</span>
                                    </label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ ucfirst($category->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Choose expense category
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">
                                        Amount (QAR)
                                        <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           name="amount"
                                           id="amount"
                                           class="form-control"
                                           step="0.01"
                                           min="0"
                                           value="{{ $expense->amount }}"
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Enter expense amount
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_date">
                                        Expense Date
                                        <span class="required">*</span>
                                    </label>
                                    <input type="date"
                                           name="expense_date"
                                           id="expense_date"
                                           class="form-control"
                                           value="{{ $expense->expense_date->format('Y-m-d') }}"
                                           required>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        When did this expense occur
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-credit-card"></i>
                            </div>
                            <h4>Payment Details</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">
                                        Payment Method
                                        <span class="required">*</span>
                                    </label>
                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="CASH" {{ $expense->payment_method == 'CASH' ? 'selected' : '' }}>Cash</option>
                                        <option value="VISA" {{ $expense->payment_method == 'VISA' ? 'selected' : '' }}>Visa</option>
                                        <option value="MASTERCARD" {{ $expense->payment_method == 'MASTERCARD' ? 'selected' : '' }}>MasterCard</option>
                                        <option value="MADA" {{ $expense->payment_method == 'MADA' ? 'selected' : '' }}>Mada</option>
                                        <option value="ATM" {{ $expense->payment_method == 'ATM' ? 'selected' : '' }}>ATM</option>
                                        <option value="BANK_TRANSFER" {{ $expense->payment_method == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        How was this expense paid
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vendor_name">Vendor Name</label>
                                    <input type="text"
                                           name="vendor_name"
                                           id="vendor_name"
                                           class="form-control"
                                           value="{{ $expense->vendor_name }}"
                                           placeholder="e.g., ABC Company">
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Name of the vendor or supplier
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number">Receipt Number</label>
                                    <input type="text"
                                           name="receipt_number"
                                           id="receipt_number"
                                           class="form-control"
                                           value="{{ $expense->receipt_number }}"
                                           placeholder="e.g., REC-12345">
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Receipt or invoice number
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box">
                                <i class="fa fa-file-text"></i>
                            </div>
                            <h4>Description & Notes</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">
                                        Description
                                        <span class="required">*</span>
                                    </label>
                                    <textarea name="description"
                                              id="description"
                                              class="form-control"
                                              rows="3"
                                              required>{{ $expense->description }}</textarea>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Detailed description of the expense
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes"
                                              id="notes"
                                              class="form-control"
                                              rows="2">{{ $expense->notes }}</textarea>
                                    <p class="help-text">
                                        <i class="fa fa-info-circle"></i>
                                        Any additional information
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="fa fa-save"></i> Update Expense
                        </button>
                        <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-cancel">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Form submission
            $('#expenseForm').on('submit', function() {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            });
        });
    </script>
@endsection
