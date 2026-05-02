@extends('dashboard.layouts.master')
@section('title', 'Create Lab Order')
@section('content')

<style>
.lpo-create { padding: 20px; }
.info-card { background:#f8f9ff; border:2px solid #e8ecf7; border-radius:10px; padding:18px; margin-bottom:20px; }
.info-card .label-key { font-weight:600; color:#888; font-size:12px; text-transform:uppercase; }
.info-card .label-val { font-size:15px; color:#2c3e50; font-weight:600; }
.lens-item-row { background:#fff; border:2px solid #e0e6ed; border-radius:8px; padding:15px; margin-bottom:12px; }
.lens-item-row:hover { border-color:#3498db; }
.lens-item-row .lens-id-badge { background:#3498db; color:#fff; padding:2px 10px; border-radius:12px; font-size:12px; font-weight:600; }
.section-card { background:#fff; border-radius:10px; padding:25px; margin-bottom:20px; border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.section-title { font-size:16px; font-weight:700; color:#2c3e50; margin-bottom:20px; padding-bottom:10px; border-bottom:2px solid #e8ecf7; }
</style>

<section class="content-header">
    <h1><i class="bi bi-flask"></i> Create Lab Order
        <small>Invoice #{{ $invoice->invoice_code }}</small>
    </h1>
</section>

<div class="lpo-create">
    <form action="{{ route('dashboard.lens-purchase-orders.store') }}" method="POST" id="poForm">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

        <div class="row">
            <div class="col-md-8">

                {{-- Invoice Info --}}
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="label-key">Invoice #</div>
                            <div class="label-val">{{ $invoice->invoice_code }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-key">Customer</div>
                            <div class="label-val">{{ $invoice->customer->english_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-key">Branch</div>
                            <div class="label-val">{{ $invoice->branch->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-key">Invoice Date</div>
                            <div class="label-val">{{ $invoice->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Lens Items --}}
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-eyeglasses"></i> Lens Items to Order
                        <span class="badge" style="background:#3498db;color:#fff;margin-left:8px;">{{ $lensItems->count() }}</span>
                        <span style="font-size:12px;color:#888;font-weight:400;margin-left:10px;">
                            <input type="checkbox" id="selectAll" style="margin-right:4px;" checked>
                            <label for="selectAll" style="cursor:pointer;font-weight:500;">Select All</label>
                        </span>
                    </div>

                    @foreach($lensItems as $index => $item)
                    <div class="lens-item-row" id="create-row-{{ $item->id }}" style="border:2px solid #b2dfdb;">
                        <input type="hidden" name="items[{{ $index }}][invoice_item_id]" value="{{ $item->id }}" class="item-hidden-{{ $item->id }}">
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom:10px;display:flex;align-items:center;gap:10px;">
                                <input type="checkbox" class="create-item-checkbox" data-id="{{ $item->id }}"
                                       style="width:18px;height:18px;cursor:pointer;" checked
                                       onchange="toggleCreateItem({{ $item->id }}, this.checked)">
                                <span class="lens-id-badge">{{ $item->product_id }}</span>
                                <strong style="margin-left:10px;">{{ $item->lensModel->description ?? '-' }}</strong>
                                @if($item->lensModel)
                                <span style="color:#888;font-size:12px;margin-left:8px;">
                                    | Brand: {{ $item->lensModel->brand ?? '-' }}
                                    | Index: {{ $item->lensModel->index ?? '-' }}
                                    | Type: {{ $item->lensModel->lense_type ?? '-' }}
                                </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#888;">Quantity</label>
                                    <input type="number" name="items[{{ $index }}][quantity]"
                                           class="form-control"
                                           value="{{ $item->quantity }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#888;">Unit Cost</label>
                                    <input type="number" name="items[{{ $index }}][unit_cost]"
                                           class="form-control" step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#888;">Notes</label>
                                    <input type="text" name="items[{{ $index }}][notes]"
                                           class="form-control" placeholder="Lens specifications, power, etc.">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <div class="col-md-4">

                {{-- Lab Selection --}}
                <div class="section-card">
                    <div class="section-title"><i class="bi bi-building"></i> Lab / Supplier</div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Select Existing Lab</label>
                        <select name="lab_id" id="labSelect" class="form-control">
                            <option value="">-- Or type manually below --</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" data-name="{{ $lab->name }}">
                                    {{ $lab->name }}
                                    @if($lab->phone) ({{ $lab->phone }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Lab Name <span class="text-danger">*</span></label>
                        <input type="text" name="lab_name" id="labName" class="form-control" required
                               placeholder="Enter lab name...">
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Special instructions, delivery date, etc."></textarea>
                    </div>

                    <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:12px;font-size:13px;color:#856404;">
                        <i class="bi bi-info-circle"></i>
                        <strong>Ordered by:</strong> {{ auth()->user()->name }}<br>
                        <strong>Branch:</strong> {{ $invoice->branch->name ?? '-' }}<br>
                        <strong>Date:</strong> {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>

                {{-- Existing POs for this invoice --}}
                @if($existingPos->count() > 0)
                <div class="section-card" style="border-color:#ffc107;">
                    <div class="section-title" style="color:#f39c12;">
                        <i class="bi bi-exclamation-triangle"></i> Existing Orders for this Invoice
                    </div>
                    @foreach($existingPos as $existingPo)
                    <div style="border:1px solid #e0e6ed;border-radius:6px;padding:10px;margin-bottom:8px;font-size:13px;">
                        <strong>{{ $existingPo->po_number }}</strong> — {{ $existingPo->lab_name }}<br>
                        <span class="status-{{ $existingPo->status }}" style="font-size:11px;">{{ strtoupper($existingPo->status) }}</span>
                        <span style="color:#888;">| {{ $existingPo->items->count() }} item(s)</span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div style="text-align:center;">
                    <button type="submit" class="btn btn-success btn-block" style="padding:12px;font-size:15px;font-weight:700;border-radius:8px;">
                        <i class="bi bi-save"></i> Create Lab Order
                    </button>
                    <a href="{{ route('dashboard.invoice.pending') }}" class="btn btn-default btn-block" style="margin-top:8px;">
                        <i class="bi bi-arrow-left"></i> Back to Pending Invoices
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
.status-pending   { background: #f39c12; color: #fff; padding: 2px 8px; border-radius: 10px; }
.status-sent      { background: #3498db; color: #fff; padding: 2px 8px; border-radius: 10px; }
.status-received  { background: #27ae60; color: #fff; padding: 2px 8px; border-radius: 10px; }
.status-cancelled { background: #e74c3c; color: #fff; padding: 2px 8px; border-radius: 10px; }
</style>

<script>
document.getElementById('labSelect').addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    if (opt.value) {
        document.getElementById('labName').value = opt.getAttribute('data-name');
    }
});

function toggleCreateItem(id, checked) {
    var row = document.getElementById('create-row-' + id);
    var inputs = row.querySelectorAll('input[type=number], input[type=text], textarea');
    if (checked) {
        row.style.borderColor = '#b2dfdb';
        row.style.opacity = '1';
        inputs.forEach(function(el){ el.disabled = false; });
        row.querySelector('.item-hidden-' + id).disabled = false;
    } else {
        row.style.borderColor = '#ddd';
        row.style.opacity = '0.5';
        inputs.forEach(function(el){ el.disabled = true; });
        row.querySelector('.item-hidden-' + id).disabled = true;
    }
}

// Select All toggle
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.create-item-checkbox').forEach(function(cb) {
        cb.checked = this.checked;
        toggleCreateItem(cb.getAttribute('data-id'), this.checked);
    }.bind(this));
});

// Before submit: re-index items so array is sequential
document.getElementById('poForm').addEventListener('submit', function(e) {
    var checked = document.querySelectorAll('.create-item-checkbox:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Please select at least one lens item to order.');
        return false;
    }
    // Re-index selected items
    var idx = 0;
    document.querySelectorAll('.create-item-checkbox').forEach(function(cb) {
        var id = cb.getAttribute('data-id');
        var row = document.getElementById('create-row-' + id);
        if (!cb.checked) return;
        // Update name attributes with new sequential index
        row.querySelectorAll('[name^="items["]').forEach(function(el) {
            el.name = el.name.replace(/items\[\d+\]/, 'items[' + idx + ']');
        });
        idx++;
    });
});
</script>
@endsection
