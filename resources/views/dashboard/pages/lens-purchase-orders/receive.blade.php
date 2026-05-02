@extends('dashboard.layouts.master')
@section('title', 'Receive Lenses - ' . $po->po_number)
@section('content')

<style>
.receive-page { padding: 20px; }
.receive-card { background:#fff; border-radius:12px; padding:25px; margin-bottom:20px; box-shadow:0 4px 15px rgba(0,0,0,.08); border:2px solid #27ae60; }
.lens-receive-row { background:#f8fffe; border:2px solid #d4edda; border-radius:10px; padding:18px; margin-bottom:14px; transition:all .2s; }
.lens-receive-row:hover { border-color:#27ae60; background:#f0fff4; }
.qty-input { font-size:20px; font-weight:700; text-align:center; border:2px solid #27ae60; border-radius:8px; width:100%; padding:8px; color:#27ae60; }
.qty-input:focus { outline:none; box-shadow:0 0 0 3px rgba(39,174,96,.2); }
.lens-badge { background:#27ae60; color:#fff; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:600; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-box-arrow-in-down"></i> Receive Lenses
        <small>{{ $po->po_number }}</small>
    </h1>
</section>

<div class="receive-page">

    {{-- PO Summary --}}
    <div style="background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;border-radius:10px;padding:20px;margin-bottom:20px;">
        <div class="row">
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">PO Number</div><div style="font-size:18px;font-weight:700;">{{ $po->po_number }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Invoice</div><div style="font-size:18px;font-weight:700;">{{ $po->invoice->invoice_code }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Customer</div><div style="font-size:16px;font-weight:600;">{{ $po->invoice->customer->english_name ?? '-' }}</div></div>
            <div class="col-md-3"><div style="opacity:.8;font-size:12px;">Lab</div><div style="font-size:16px;font-weight:600;">{{ $po->lab_name }}</div></div>
        </div>
    </div>

    <form action="{{ route('dashboard.lens-purchase-orders.mark-received', $po->id) }}" method="POST" id="receiveForm">
        @csrf @method('POST')

        <div class="receive-card">
            <h4 style="margin:0 0 20px;font-size:17px;font-weight:700;color:#27ae60;">
                <i class="bi bi-eyeglasses"></i> Enter Received Quantities
            </h4>
            <p style="color:#888;font-size:13px;margin-bottom:20px;">
                <i class="bi bi-info-circle"></i>
                Enter the quantity actually received for each lens. Items with 0 quantity will be skipped.
                Stock will be updated immediately upon saving.
            </p>

            @foreach($po->items as $item)
            @php
                $dir2      = trim($item->invoiceItem->direction ?? '');
                $sideL2    = $dir2 === 'R' ? 'Right Eye' : ($dir2 === 'L' ? 'Left Eye' : null);
                $sideC2    = $dir2 === 'R' ? '#1565c0'   : '#6a1b9a';
                $sideBg2   = $dir2 === 'R' ? '#e3f2fd'   : '#f3e5f5';
                $sideIco2  = $dir2 === 'R' ? 'bi-arrow-right-circle-fill' : 'bi-arrow-left-circle-fill';
            @endphp
            <div class="lens-receive-row">
                <div class="row" style="align-items:center;">
                    <div class="col-md-6">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                            <span class="lens-badge">{{ $item->lens_product_id }}</span>
                            @if($sideL2)
                                <span style="background:{{ $sideBg2 }};color:{{ $sideC2 }};padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;border:1px solid {{ $sideC2 }}30;">
                                    <i class="bi {{ $sideIco2 }}"></i> {{ $sideL2 }}
                                </span>
                            @endif
                            <strong style="font-size:15px;">{{ $item->lens->description ?? '-' }}</strong>
                        </div>
                        @if($item->lens)
                        <div style="color:#888;font-size:12px;margin-top:2px;">
                            Brand: {{ $item->lens->brand ?? '-' }} |
                            Index: {{ $item->lens->index ?? '-' }} |
                            Type: {{ $item->lens->lense_type ?? '-' }}
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
                               style="background:#e9f7ef;cursor:default;opacity:.9;">
                        <div style="font-size:11px;color:#27ae60;text-align:center;margin-top:4px;">
                            <i class="bi bi-lock-fill"></i> Fixed to ordered qty
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;">
            <div style="background:#d4edda;border:1px solid #c3e6cb;border-radius:8px;padding:15px;margin-bottom:20px;color:#155724;font-size:14px;">
                <i class="bi bi-check-circle-fill"></i>
                <strong>Upon saving:</strong> Lenses will be added to the stock of branch
                <strong>{{ $po->branch->name ?? '' }}</strong>.
                The invoice can then be delivered once all items are ready.
            </div>

            <button type="submit" class="btn btn-success" style="padding:14px 50px;font-size:16px;font-weight:700;border-radius:8px;">
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
