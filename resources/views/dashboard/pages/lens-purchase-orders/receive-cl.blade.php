@extends('dashboard.layouts.master')
@section('title', 'Receive Contact Lenses - ' . $po->po_number)
@section('content')

<style>
.receive-page { padding: 20px; }
.receive-card { background:#fff; border-radius:12px; padding:25px; margin-bottom:20px; box-shadow:0 4px 15px rgba(0,0,0,.08); border:2px solid #1565c0; }
.cl-receive-row { background:#f0f6ff; border:2px solid #bbdefb; border-radius:10px; padding:18px; margin-bottom:14px; transition:all .2s; }
.cl-receive-row:hover { border-color:#1565c0; background:#e3f2fd; }
.qty-input { font-size:20px; font-weight:700; text-align:center; border:2px solid #1565c0; border-radius:8px; width:100%; padding:8px; color:#1565c0; }
.qty-input:focus { outline:none; box-shadow:0 0 0 3px rgba(21,101,192,.2); }
.cl-badge { background:#1565c0; color:#fff; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:600; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-eye"></i> Receive Contact Lenses
        <small>{{ $po->po_number }}</small>
    </h1>
</section>

<div class="receive-page">

    {{-- PO Summary --}}
    <div style="background:linear-gradient(135deg,#1565c0,#1976d2);color:#fff;border-radius:10px;padding:20px;margin-bottom:20px;">
        <div class="row">
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">PO Number</div><div style="font-size:18px;font-weight:700;">{{ $po->po_number }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Invoice</div><div style="font-size:18px;font-weight:700;">{{ optional($po->invoice)->invoice_code ?? '—' }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Customer</div><div style="font-size:16px;font-weight:600;">{{ optional(optional($po->invoice)->customer)->english_name ?? '-' }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Supplier</div><div style="font-size:16px;font-weight:600;">{{ $po->lab_name }}</div></div>
        </div>
    </div>

    <form action="{{ route('dashboard.lens-purchase-orders.cl.mark-received', $po->id) }}" method="POST" id="receiveForm">
        @csrf @method('POST')

        <div class="receive-card">
            <h4 style="margin:0 0 20px;font-size:17px;font-weight:700;color:#1565c0;">
                <i class="bi bi-eye"></i> Enter Received Quantities
            </h4>
            <p style="color:#888;font-size:13px;margin-bottom:20px;">
                <i class="bi bi-info-circle"></i>
                Enter the quantity actually received for each contact lens. Items with 0 quantity will be skipped.
                Stock will be updated immediately upon saving.
            </p>

            @foreach($po->items as $item)
            <div class="cl-receive-row">
                <div class="row" style="align-items:center;">
                    <div class="col-md-6">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                            <span class="cl-badge">{{ $item->lens_product_id }}</span>
                            <strong style="font-size:15px;">{{ optional(optional($item->invoiceItem)->product)->description ?? $item->lens_product_id }}</strong>
                        </div>
                        @if(optional($item->invoiceItem)->product)
                        @php $prod = $item->invoiceItem->product; @endphp
                        <div style="color:#888;font-size:12px;margin-top:2px;">
                            @if($prod->brand_id) Brand: {{ $prod->brand_id }} | @endif
                            @if($prod->lense_use) Use: {{ $prod->lense_use }} | @endif
                            @if($prod->power) Power: {{ $prod->power }} @endif
                        </div>
                        @endif
                        @if($item->notes)
                        <div style="color:#e67e22;font-size:12px;margin-top:4px;">
                            <i class="bi bi-chat-text"></i> {{ $item->notes }}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-2 text-center">
                        <div style="font-size:12px;color:#888;">Ordered</div>
                        <div style="font-size:22px;font-weight:700;color:#2c3e50;">{{ $item->quantity }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:12px;color:#888;margin-bottom:5px;text-align:center;">Received Qty</div>
                        <input type="number"
                               name="received_quantities[{{ $item->id }}]"
                               class="qty-input"
                               value="{{ $item->quantity }}"
                               readonly
                               style="background:#e8f0fe;cursor:default;opacity:.9;">
                        <div style="font-size:11px;color:#1565c0;text-align:center;margin-top:4px;">
                            <i class="bi bi-lock-fill"></i> Fixed to ordered qty
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;">
            <div style="background:#e3f2fd;border:1px solid #90caf9;border-radius:8px;padding:15px;margin-bottom:20px;color:#1565c0;font-size:14px;">
                <i class="bi bi-check-circle-fill"></i>
                <strong>Upon saving:</strong> Contact lenses will be added to the stock of branch
                <strong>{{ optional($po->branch)->name ?? '' }}</strong>.
                The invoice items will be marked as <strong>ready</strong>.
            </div>

            <button type="submit" class="btn btn-primary" style="padding:14px 50px;font-size:16px;font-weight:700;border-radius:8px;">
                <i class="bi bi-check-circle-fill"></i> Confirm Receipt & Update Stock
            </button>
            <a href="{{ route('dashboard.lens-purchase-orders.show', $po->id) }}"
               class="btn btn-default" style="padding:14px 30px;font-size:16px;border-radius:8px;margin-left:10px;">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>

    </form>
</div>
@endsection
