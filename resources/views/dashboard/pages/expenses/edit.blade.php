@extends('dashboard.layouts.master')

@section('title', 'Edit Expense')

@section('content')

    <style>
        .expense-edit-page { padding: 20px; }

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
            top: -50%; right: -10%;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .box-expense-edit .box-header .box-title {
            font-size: 22px; font-weight: 700;
            position: relative; z-index: 1;
        }

        .box-expense-edit .box-header .box-title i {
            background: rgba(255,255,255,0.2);
            padding: 10px; border-radius: 8px; margin-right: 10px;
        }

        .box-expense-edit .box-header .btn {
            position: relative; z-index: 1;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; transition: all 0.3s;
        }

        .box-expense-edit .box-header .btn:hover {
            background: white; color: #f39c12;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .form-section {
            background: white; padding: 25px;
            border-radius: 10px; margin-bottom: 20px;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            animation: fadeInUp 0.5s ease-out;
        }

        .section-header {
            display: flex; align-items: center;
            margin-bottom: 25px; padding-bottom: 15px;
            border-bottom: 2px solid #e8ecf7;
        }

        .section-header .icon-box {
            width: 45px; height: 45px;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 20px; margin-right: 15px;
            box-shadow: 0 3px 10px rgba(243,156,18,0.3);
        }

        .section-header h4 { margin: 0; font-size: 18px; font-weight: 700; color: #f39c12; }

        .form-group label {
            font-weight: 600; color: #555; font-size: 13px;
            margin-bottom: 8px;
            display: flex; align-items: center; gap: 5px;
        }

        .form-group label .required { color: #e74c3c; font-weight: 700; }

        .form-control {
            border: 2px solid #e0e6ed;
            border-radius: 8px !important;
            transition: all 0.3s; font-size: 14px;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 3px rgba(243,156,18,0.1);
        }

        .help-text { font-size: 12px; color: #999; margin-top: 5px; font-style: italic; }

        .btn-submit-group {
            background: white; padding: 25px;
            border-radius: 10px; text-align: center;
            border: 2px solid #e8ecf7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white; border: none; padding: 14px 50px;
            border-radius: 8px; font-size: 16px; font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(39,174,96,0.3); margin: 0 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39,174,96,0.4); color: white;
        }

        .btn-cancel {
            background: white; color: #666;
            border: 2px solid #ddd; padding: 14px 50px;
            border-radius: 8px; font-size: 16px; font-weight: 700;
            transition: all 0.3s; margin: 0 10px;
        }

        .btn-cancel:hover {
            background: #f8f9fa; border-color: #bbb;
            color: #333; transform: translateY(-3px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== SMART BUTTONS ===== */
        .smart-shortcuts {
            background: linear-gradient(135deg, #fff8e1 0%, #fff 100%);
            border: 2px dashed #f39c12;
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 22px;
            display: flex; align-items: center;
            gap: 12px; flex-wrap: wrap;
        }

        .smart-shortcuts .label {
            font-weight: 700; color: #f39c12;
            font-size: 13px; margin-right: 5px; white-space: nowrap;
        }

        .btn-smart {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.3s;
        }

        .btn-smart:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn-smart:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-smart.salaries { background: linear-gradient(135deg,#9b59b6,#8e44ad); color: white; }
        .btn-smart.rent     { background: linear-gradient(135deg,#e67e22,#d35400); color: white; }

        /* old amount tag */
        .old-amount-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg,#fff3e0,#fff);
            border: 2px solid #f39c12; border-radius: 20px;
            padding: 5px 14px; font-size: 13px; font-weight: 700; color: #e67e22;
        }

        /* diff badge */
        .diff-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 12px;
            font-size: 12px; font-weight: 700; color: white;
        }
        .diff-badge.up   { background: linear-gradient(135deg,#27ae60,#229954); }
        .diff-badge.down { background: linear-gradient(135deg,#e74c3c,#c0392b); }
        .diff-badge.same { background: #aaa; }

        /* salary modal */
        .salary-preview-table th {
            background: linear-gradient(135deg,#9b59b6,#8e44ad);
            color: white; padding: 12px 15px;
            font-size: 12px; text-transform: uppercase; letter-spacing: .5px;
        }

        .salary-preview-table td {
            padding: 12px 15px; vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .salary-preview-table tbody tr:hover { background: #f9f0ff; }

        .salary-total-row { background: linear-gradient(135deg,#f9f0ff,#fff) !important; font-weight: 700; }
        .salary-total-row td { font-size: 16px; color: #9b59b6; border-top: 2px solid #9b59b6; }

        .branch-group-header {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white; padding: 8px 15px;
            font-weight: 700; font-size: 12px;
            text-transform: uppercase; letter-spacing: .5px;
        }

        .modal-salary .modal-header {
            background: linear-gradient(135deg,#9b59b6,#8e44ad);
            color: white; border: none; padding: 20px 25px;
        }
        .modal-salary .modal-header .close { color: white; opacity: 1; font-size: 20px; }
        .modal-salary .modal-footer { border-top: 2px solid #f0f2f5; padding: 15px 25px; }

        .salary-summary-cards { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; }

        .salary-summary-card {
            flex:1; min-width:130px; background:white;
            border-radius:10px; padding:15px;
            border: 2px solid #e8ecf7; text-align:center;
        }
        .salary-summary-card h4 { margin:0 0 5px 0; font-size:22px; font-weight:700; }
        .salary-summary-card p  { margin:0; font-size:11px; color:#999; text-transform:uppercase; }

        .salary-summary-card.total    h4 { color: #9b59b6; }
        .salary-summary-card.count    h4 { color: #3498db; }
        .salary-summary-card.branches h4 { color: #27ae60; }

        .loading-spinner { text-align:center; padding:40px; color:#9b59b6; }
        .loading-spinner i { font-size:40px; }
        .loading-spinner p { margin-top:10px; font-weight:600; }

        /* confirm modal */
        .modal-confirm .modal-header {
            background: linear-gradient(135deg,#f39c12,#e67e22);
            color: white; border:none;
        }
        .modal-confirm .modal-header .close { color:white; opacity:1; }

        .amount-compare {
            display:flex; align-items:center; justify-content:center;
            gap:20px; padding:20px; flex-wrap:wrap;
        }

        .amount-box {
            text-align:center; padding:15px 25px;
            border-radius:10px; min-width:130px;
        }

        .amount-box.old { background:#fff3e0; border:2px solid #f39c12; }
        .amount-box.new { background:#e8f8f0; border:2px solid #27ae60; }

        .amount-box .label { font-size:11px; font-weight:700; text-transform:uppercase; color:#999; }
        .amount-box .value { font-size:24px; font-weight:700; margin-top:5px; }
        .amount-box.old .value { color:#e67e22; }
        .amount-box.new .value { color:#27ae60; }

        .arrow-icon { font-size:28px; color:#ccc; }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-edit"></i> Edit Expense
            <small>Update expense information</small>
        </h1>
    </section>

    <div class="expense-edit-page">
        <form action="{{ route('dashboard.expenses.update', $expense->id) }}" method="POST"
              enctype="multipart/form-data" id="expenseForm">
            @csrf

            <div class="box box-warning box-expense-edit">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-pencil"></i> Edit Expense #{{ $expense->id }}
                        &nbsp;
                        <span class="old-amount-tag">
                            <i class="fa fa-clock-o"></i>
                            Current: {{ number_format($expense->amount, 2) }} QAR
                        </span>
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="box-body" style="padding: 30px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="border-left:5px solid #e74c3c;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4><i class="fa fa-ban"></i> Validation Errors:</h4>
                            <ul style="margin-bottom:0; padding-left:20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ===== SMART SHORTCUTS ===== --}}
                    <div class="smart-shortcuts">
                        <span class="label"><i class="fa fa-bolt"></i> Smart Update:</span>

                        <button type="button" class="btn-smart salaries" id="btnLoadSalaries">
                            <i class="fa fa-users"></i> Reload Salaries
                        </button>

                        @if(!auth()->user()->canSeeAllBranches() && auth()->user()->branch_id)
                            <button type="button" class="btn-smart rent" id="btnLoadRentDirect">
                                <i class="fa fa-home"></i> Reload Rent
                            </button>
                        @else
                            <button type="button" class="btn-smart rent" id="btnLoadRent" style="display:none;">
                                <i class="fa fa-home"></i> Reload Rent
                            </button>
                        @endif

                        {{-- Diff badge - يظهر لما المبلغ يتغير --}}
                        <span id="diffBadge" style="display:none;"></span>
                    </div>

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="fa fa-info-circle"></i></div>
                            <h4>Basic Information</h4>
                        </div>

                        <div class="row">
                            @if(auth()->user()->canSeeAllBranches())
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="branch_id">Branch <span class="required">*</span></label>
                                        <select name="branch_id" id="branch_id" class="form-control" required>
                                            <option value="">-- Select Branch --</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                        data-rent="{{ $branch->rent_amount ?? 0 }}"
                                                    {{ $expense->branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="help-text"><i class="fa fa-info-circle"></i> Select the branch for this expense</p>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ $expense->branch_id }}">
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category <span class="required">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    data-name="{{ strtolower($category->name) }}"
                                                {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ ucfirst($category->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Choose expense category</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount (QAR) <span class="required">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                           step="0.01" min="0" value="{{ $expense->amount }}" required>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Enter expense amount</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_date">Expense Date <span class="required">*</span></label>
                                    <input type="date" name="expense_date" id="expense_date" class="form-control"
                                           value="{{ $expense->expense_date->format('Y-m-d') }}" required>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> When did this expense occur</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="fa fa-credit-card"></i></div>
                            <h4>Payment Details</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method <span class="required">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="CASH"          {{ $expense->payment_method == 'CASH'          ? 'selected' : '' }}>Cash</option>
                                        <option value="VISA"          {{ $expense->payment_method == 'VISA'          ? 'selected' : '' }}>Visa</option>
                                        <option value="MASTERCARD"    {{ $expense->payment_method == 'MASTERCARD'    ? 'selected' : '' }}>MasterCard</option>
                                        <option value="MADA"          {{ $expense->payment_method == 'MADA'          ? 'selected' : '' }}>Mada</option>
                                        <option value="ATM"           {{ $expense->payment_method == 'ATM'           ? 'selected' : '' }}>ATM</option>
                                        <option value="BANK_TRANSFER" {{ $expense->payment_method == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> How was this expense paid</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vendor_name">Vendor Name</label>
                                    <input type="text" name="vendor_name" id="vendor_name" class="form-control"
                                           value="{{ $expense->vendor_name }}" placeholder="e.g., ABC Company">
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Name of the vendor or supplier</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number">Receipt Number</label>
                                    <input type="text" name="receipt_number" id="receipt_number" class="form-control"
                                           value="{{ $expense->receipt_number }}" placeholder="e.g., REC-12345">
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Receipt or invoice number</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="fa fa-file-text"></i></div>
                            <h4>Description & Notes</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description <span class="required">*</span></label>
                                    <textarea name="description" id="description" class="form-control"
                                              rows="3" required>{{ $expense->description }}</textarea>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Detailed description of the expense</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control"
                                              rows="2">{{ $expense->notes }}</textarea>
                                    <p class="help-text"><i class="fa fa-info-circle"></i> Any additional information</p>
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

    {{-- ===================================================
         SALARY PREVIEW MODAL
    =================================================== --}}
    <div class="modal fade modal-salary" id="salaryPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:12px; overflow:hidden;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-users"></i> Employee Salaries Preview
                    </h4>
                </div>

                <div class="modal-body" style="padding:25px;">
                    <div id="salaryLoading" class="loading-spinner">
                        <i class="fa fa-spinner fa-spin"></i>
                        <p>Loading salaries...</p>
                    </div>

                    <div id="salaryContent" style="display:none;">
                        <div class="salary-summary-cards">
                            <div class="salary-summary-card count">
                                <h4 id="summaryCount">0</h4>
                                <p><i class="fa fa-user"></i> Employees</p>
                            </div>
                            <div class="salary-summary-card branches">
                                <h4 id="summaryBranches">0</h4>
                                <p><i class="fa fa-building"></i> Branches</p>
                            </div>
                            <div class="salary-summary-card total">
                                <h4 id="summaryTotal">0.00</h4>
                                <p><i class="fa fa-money"></i> Total QAR</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover salary-preview-table" style="border-radius:10px;overflow:hidden;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Branch</th>
                                    <th>Role</th>
                                    <th class="text-right">Salary (QAR)</th>
                                </tr>
                                </thead>
                                <tbody id="salaryTableBody"></tbody>
                            </table>
                        </div>

                        <div class="alert alert-info" style="border-left:5px solid #3498db;margin-top:15px;">
                            <i class="fa fa-info-circle"></i>
                            Clicking <strong>"Apply to Form"</strong> will show you the old vs new amount before updating.
                        </div>
                    </div>

                    <div id="salaryError" style="display:none;">
                        <div class="alert alert-danger" style="border-left:5px solid #e74c3c;">
                            <i class="fa fa-exclamation-circle"></i>
                            <span id="salaryErrorMsg">Failed to load salaries.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn" id="btnApplySalaries" disabled
                            style="background:linear-gradient(135deg,#9b59b6,#8e44ad);color:white;border:none;border-radius:6px;padding:10px 25px;font-weight:700;">
                        <i class="fa fa-check"></i> Apply to Form
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================
         CONFIRM AMOUNT CHANGE MODAL
    =================================================== --}}
    <div class="modal fade modal-confirm" id="confirmAmountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;overflow:hidden;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <i class="fa fa-exchange"></i> Confirm Amount Update
                    </h4>
                </div>

                <div class="modal-body" style="padding:25px;">
                    <p style="color:#666;text-align:center;margin-bottom:20px;">
                        The amount will be updated as follows:
                    </p>

                    <div class="amount-compare">
                        <div class="amount-box old">
                            <div class="label"><i class="fa fa-clock-o"></i> Old Amount</div>
                            <div class="value" id="confirmOldAmount">0.00</div>
                            <small>QAR</small>
                        </div>
                        <div class="arrow-icon"><i class="fa fa-long-arrow-right"></i></div>
                        <div class="amount-box new">
                            <div class="label"><i class="fa fa-refresh"></i> New Amount</div>
                            <div class="value" id="confirmNewAmount">0.00</div>
                            <small>QAR</small>
                        </div>
                    </div>

                    <div id="confirmDiffNote" style="text-align:center;margin-top:15px;font-weight:700;font-size:14px;"></div>

                    <div class="alert alert-warning" style="border-left:5px solid #f39c12;margin-top:20px;">
                        <i class="fa fa-warning"></i>
                        Do you want to replace the current amount with the new value?
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Keep Old Amount
                    </button>
                    <button type="button" class="btn" id="btnConfirmApply"
                            style="background:linear-gradient(135deg,#27ae60,#229954);color:white;border:none;border-radius:6px;padding:10px 25px;font-weight:700;">
                        <i class="fa fa-check"></i> Yes, Update Amount
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            var OLD_AMOUNT = {{ $expense->amount }};
            var pendingData = null; // بيانات معلقة ريثما يؤكد المستخدم

            // =============================================
            // AMOUNT CHANGE DIFF BADGE
            // =============================================
            $('#amount').on('input', function () {
                var newVal = parseFloat($(this).val()) || 0;
                var diff   = newVal - OLD_AMOUNT;

                if (Math.abs(diff) < 0.01) {
                    $('#diffBadge').hide();
                    return;
                }

                var sign  = diff > 0 ? '+' : '';
                var cls   = diff > 0 ? 'up' : 'down';
                var icon  = diff > 0 ? 'fa-arrow-up' : 'fa-arrow-down';

                $('#diffBadge')
                    .html('<span class="diff-badge ' + cls + '"><i class="fa ' + icon + '"></i> ' + sign + diff.toFixed(2) + ' QAR</span>')
                    .show();
            });

            // =============================================
            // RENT BUTTON LOGIC
            // =============================================
            @if(auth()->user()->canSeeAllBranches())

            $('#branch_id').on('change', function () {
                var rentAmount = parseFloat($(this).find('option:selected').data('rent')) || 0;
                $('#btnLoadRent').toggle($(this).val() !== '');
            });

            // Init on load
            if ($('#branch_id').val()) $('#btnLoadRent').show();

            $('#btnLoadRent').on('click', function () {
                var selected   = $('#branch_id').find('option:selected');
                var rentAmount = parseFloat(selected.data('rent')) || 0;
                var branchName = selected.text().trim();

                if (rentAmount <= 0) {
                    alert('No rent amount configured for this branch.');
                    return;
                }
                triggerConfirm(rentAmount, null, 'rent', branchName);
            });

            @else

            var userRentAmount = {{ optional(auth()->user()->branch)->rent_amount ?? 0 }};
            var userBranchName = "{{ optional(auth()->user()->branch)->name ?? '' }}";

            $('#btnLoadRentDirect').on('click', function () {
                if (userRentAmount <= 0) {
                    alert('No rent amount configured for your branch.\nPlease contact your administrator.');
                    return;
                }
                triggerConfirm(userRentAmount, null, 'rent', userBranchName);
            });

            @endif

            // =============================================
            // SALARY BUTTON LOGIC
            // =============================================
            var salaryData = null;

            $('#btnLoadSalaries').on('click', function () {
                salaryData = null;
                $('#btnApplySalaries').prop('disabled', true);
                $('#salaryLoading').show();
                $('#salaryContent').hide();
                $('#salaryError').hide();
                $('#salaryPreviewModal').modal('show');

                var branchId = null;
                @if(auth()->user()->canSeeAllBranches())
                    branchId = $('#branch_id').val() || null;
                @else
                    branchId = {{ auth()->user()->branch_id ?? 'null' }};
                @endif

                $.ajax({
                    url : '{{ route("dashboard.expenses.salaries-preview") }}',
                    type: 'GET',
                    data: { branch_id: branchId },
                    success: function (response) {
                        $('#salaryLoading').hide();
                        if (response.success && response.employees.length > 0) {
                            salaryData = response;
                            renderSalaryTable(response);
                            $('#salaryContent').show();
                            $('#btnApplySalaries').prop('disabled', false);
                        } else {
                            $('#salaryError').show();
                            $('#salaryErrorMsg').text(response.message || 'No employees found.');
                        }
                    },
                    error: function (xhr) {
                        $('#salaryLoading').hide();
                        $('#salaryError').show();
                        $('#salaryErrorMsg').text('Server error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            });

            function renderSalaryTable(data) {
                var tbody   = $('#salaryTableBody');
                var counter = 1;
                tbody.empty();

                var grouped = {};
                data.employees.forEach(function (emp) {
                    var key = emp.branch_name || 'No Branch';
                    if (!grouped[key]) grouped[key] = [];
                    grouped[key].push(emp);
                });

                $.each(grouped, function (branchName, employees) {
                    if (Object.keys(grouped).length > 1) {
                        tbody.append('<tr><td colspan="5" class="branch-group-header"><i class="fa fa-building"></i> ' + branchName + '</td></tr>');
                    }
                    employees.forEach(function (emp) {
                        tbody.append(
                            '<tr>' +
                            '<td><strong style="color:#9b59b6;">' + counter++ + '</strong></td>' +
                            '<td><strong>' + emp.full_name + '</strong>' + (emp.email ? '<br><small style="color:#999;">' + emp.email + '</small>' : '') + '</td>' +
                            '<td><span style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:700;">' + (emp.branch_name || '-') + '</span></td>' +
                            '<td><small style="color:#666;">' + (emp.role || '-') + '</small></td>' +
                            '<td class="text-right"><strong style="color:#27ae60;font-size:15px;">' + parseFloat(emp.salary).toFixed(2) + ' QAR</strong></td>' +
                            '</tr>'
                        );
                    });
                });

                tbody.append(
                    '<tr class="salary-total-row">' +
                    '<td colspan="4"><i class="fa fa-calculator"></i> <strong>TOTAL SALARIES</strong></td>' +
                    '<td class="text-right"><strong>' + parseFloat(data.total).toFixed(2) + ' QAR</strong></td>' +
                    '</tr>'
                );

                $('#summaryCount').text(data.employees.length);
                $('#summaryBranches').text(Object.keys(grouped).length);
                $('#summaryTotal').text(parseFloat(data.total).toFixed(2));
            }

            // Apply salary button → close modal → show confirm
            $('#btnApplySalaries').on('click', function () {
                if (!salaryData) return;
                $('#salaryPreviewModal').modal('hide');

                // بعد ما Modal الرواتب يتأغلق نفتح Confirm
                $('#salaryPreviewModal').one('hidden.bs.modal', function () {
                    triggerConfirm(salaryData.total, salaryData, 'salary', null);
                });
            });

            // =============================================
            // CONFIRM MODAL
            // =============================================
            function triggerConfirm(newAmount, data, type, extra) {
                pendingData = { amount: newAmount, data: data, type: type, extra: extra };

                var diff = newAmount - OLD_AMOUNT;
                var sign = diff >= 0 ? '+' : '';
                var diffColor = diff > 0 ? '#27ae60' : (diff < 0 ? '#e74c3c' : '#999');

                $('#confirmOldAmount').text(OLD_AMOUNT.toFixed(2));
                $('#confirmNewAmount').text(parseFloat(newAmount).toFixed(2));

                var noteHtml = '';
                if (Math.abs(diff) < 0.01) {
                    noteHtml = '<span style="color:#999;"><i class="fa fa-minus-circle"></i> No change in amount</span>';
                } else {
                    noteHtml = '<span style="color:' + diffColor + ';"><i class="fa fa-exchange"></i> Difference: ' + sign + diff.toFixed(2) + ' QAR</span>';
                }
                $('#confirmDiffNote').html(noteHtml);

                $('#confirmAmountModal').modal('show');
            }

            $('#btnConfirmApply').on('click', function () {
                if (!pendingData) return;

                $('#confirmAmountModal').modal('hide');
                applyToForm(pendingData.amount, pendingData.data, pendingData.type, pendingData.extra);
                pendingData = null;
            });

            // =============================================
            // APPLY TO FORM
            // =============================================
            function applyToForm(amount, data, type, extra) {
                $('#amount').val(parseFloat(amount).toFixed(2));

                // Diff badge update
                $('#amount').trigger('input');

                if (type === 'salary' && data) {
                    // Category
                    $('#category_id option').each(function () {
                        var name = $(this).data('name') || '';
                        if (name.includes('salary') || name.includes('salaries') || name.includes('راتب') || name.includes('رواتب')) {
                            $(this).prop('selected', true); return false;
                        }
                    });

                    var month = new Date().toLocaleString('en', { month: 'long', year: 'numeric' });
                    $('#description').val('Monthly Salaries - ' + month);

                    var noteLines = ['Employee Salaries Breakdown:', '---'];
                    data.employees.forEach(function (emp) {
                        noteLines.push(emp.full_name + ' (' + (emp.branch_name || '-') + '): ' + parseFloat(emp.salary).toFixed(2) + ' QAR');
                    });
                    noteLines.push('---');
                    noteLines.push('Total: ' + parseFloat(data.total).toFixed(2) + ' QAR');
                    noteLines.push('Updated on ' + new Date().toLocaleDateString('en'));
                    $('#notes').val(noteLines.join('\n'));

                    showToast('Salaries applied: ' + parseFloat(data.total).toFixed(2) + ' QAR for ' + data.employees.length + ' employees', 'success');

                } else if (type === 'rent') {
                    $('#category_id option').each(function () {
                        var name = $(this).data('name') || '';
                        if (name.includes('rent') || name.includes('إيجار') || name.includes('ايجار')) {
                            $(this).prop('selected', true); return false;
                        }
                    });

                    var branchName = extra || '';
                    $('#description').val('Monthly Rent - ' + branchName);
                    showToast('Rent amount applied: ' + parseFloat(amount).toFixed(2) + ' QAR', 'warning');
                }

                // Flash
                var flashColor = type === 'salary' ? { border: '#9b59b6', bg: '#f9f0ff' } : { border: '#e67e22', bg: '#fff8f0' };
                $('#amount').css({ 'border-color': flashColor.border, 'background': flashColor.bg });
                setTimeout(function () { $('#amount').css({ 'border-color': '', 'background': '' }); }, 2000);
            }

            // =============================================
            // TOAST HELPER
            // =============================================
            function showToast(msg, type) {
                var colors = {
                    success: 'linear-gradient(135deg,#27ae60,#229954)',
                    warning: 'linear-gradient(135deg,#e67e22,#d35400)',
                };
                var toast = $('<div style="position:fixed;bottom:30px;right:30px;z-index:9999;' +
                    'background:' + (colors[type] || colors.success) + ';color:white;padding:14px 22px;' +
                    'border-radius:10px;box-shadow:0 5px 20px rgba(0,0,0,0.25);font-weight:700;font-size:14px;' +
                    'display:flex;align-items:center;gap:10px;max-width:360px;">' +
                    '<i class="fa fa-check-circle" style="font-size:18px;"></i><span>' + msg + '</span></div>');
                $('body').append(toast);
                setTimeout(function () { toast.fadeOut(400, function () { toast.remove(); }); }, 3500);
            }

            // =============================================
            // FORM SUBMIT
            // =============================================
            $('#expenseForm').on('submit', function () {
                var submitBtn = $(this).find('.btn-submit');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            });

        });
    </script>
@endsection
