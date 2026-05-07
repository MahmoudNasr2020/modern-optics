@extends('dashboard.layouts.master')
@section('title', 'Lab Order - ' . $po->po_number)
@section('content')

<style>
.lpo-show { padding: 20px; }

/* ── Header card ── */
.po-header {
    background: linear-gradient(135deg,#1a1f3a,#2c3e50);
    color:#fff; border-radius:14px;
    padding:28px 32px; margin-bottom:22px;
    box-shadow:0 6px 24px rgba(0,0,0,.18);
}
.po-header h2 { margin:0 0 6px; font-size:26px; font-weight:700; }
.po-header .meta { opacity:.75; font-size:13px; line-height:1.8; }

/* ── Action buttons ── */
.action-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:10px 20px; border-radius:10px;
    font-size:14px; font-weight:600; cursor:pointer;
    border:none; text-decoration:none;
    transition:all .25s; white-space:nowrap;
    box-shadow:0 3px 12px rgba(0,0,0,.15);
}
.action-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.22); text-decoration:none; }
.action-btn i { font-size:16px; }
.btn-send    { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; }
.btn-send:hover { color:#fff; }
.btn-receive { background:linear-gradient(135deg,#27ae60,#219a52); color:#fff; }
.btn-receive:hover { color:#fff; }
.btn-cancel  { background:linear-gradient(135deg,#e74c3c,#c0392b); color:#fff; }
.btn-cancel:hover { color:#fff; }

/* ── Detail card ── */
.detail-card {
    background:#fff; border-radius:12px; padding:24px;
    margin-bottom:20px; border:1px solid #e8ecf7;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}
.detail-card h4 {
    margin:0 0 18px; font-size:15px; font-weight:700;
    color:#2c3e50; border-bottom:2px solid #e8ecf7; padding-bottom:10px;
    display:flex; align-items:center; gap:8px;
}
.info-row { display:flex; justify-content:space-between; margin-bottom:11px; font-size:14px; }
.info-row .key { color:#888; }
.info-row .val { font-weight:600; color:#2c3e50; text-align:right; }

/* ── Lens rows ── */
.lens-row {
    background:#f8f9ff; border-radius:10px; padding:16px;
    margin-bottom:10px; border:1px solid #e8ecf7;
    transition:border-color .2s;
}
.lens-row:hover { border-color:#3498db; }

/* ── Stock badges ── */
.stock-badge { padding:4px 12px; border-radius:20px; font-size:13px; font-weight:700; }
.stock-ok   { background:#d4edda; color:#155724; }
.stock-low  { background:#fff3cd; color:#856404; }
.stock-zero { background:#f8d7da; color:#721c24; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-flask"></i> Lab Order: {{ $po->po_number }}
        <a href="{{ route('dashboard.lens-purchase-orders.index') }}"
           style="margin-left:10px;font-size:13px;background:#e8ecf7;color:#555;padding:6px 14px;border-radius:8px;text-decoration:none;vertical-align:middle;">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </h1>
</section>

<div class="lpo-show">

    {{-- ═══ Header ═══ --}}
    <div class="po-header">
        <div class="row" style="align-items:center;">
            <div class="col-md-5">
                <h2><i class="bi bi-flask" style="opacity:.7;margin-right:8px;"></i>{{ $po->po_number }}</h2>
                <div class="meta">
                    <i class="bi bi-receipt"></i> Invoice:
                    @if($po->invoice)
                    <a href="{{ route('dashboard.invoice.show', $po->invoice->invoice_code) }}"
                       style="color:#7ec8f4;font-weight:700;">{{ $po->invoice->invoice_code }}</a>
                    @else <span style="color:#7ec8f4;">—</span> @endif
                    &nbsp;·&nbsp;
                    <i class="bi bi-person"></i> {{ optional(optional($po->invoice)->customer)->english_name ?? '-' }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-building"></i> {{ $po->branch->name ?? '-' }}
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div style="font-size:12px;opacity:.7;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Status</div>
                <div style="font-size:18px;">{!! $po->status_badge !!}</div>
            </div>
            <div class="col-md-4 text-right">
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                    @can('edit-stock')
                        @if($po->isPending())
                            <form action="{{ route('dashboard.lens-purchase-orders.sent', $po->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn btn-send">
                                    <i class="bi bi-send-fill"></i> Mark as Sent to Lab
                                </button>
                            </form>
                        @endif
                    @endcan
                    @can('increase-stock')
                        @if(!$po->isReceived() && !$po->isCancelled())
                            @if($po->po_type === 'contact_lens')
                                <a href="{{ route('dashboard.lens-purchase-orders.cl.receive', $po->id) }}" class="action-btn btn-receive">
                                    <i class="bi bi-eye"></i> Receive Contact Lenses
                                </a>
                            @else
                                <a href="{{ route('dashboard.lens-purchase-orders.receive', $po->id) }}" class="action-btn btn-receive">
                                    <i class="bi bi-box-arrow-in-down"></i> Receive Lenses
                                </a>
                            @endif
                        @endif
                    @endcan
                    @can('edit-stock')
                        @if(!$po->isReceived() && !$po->isCancelled())
                            <form action="{{ route('dashboard.lens-purchase-orders.cancel', $po->id) }}" method="POST"
                                  onsubmit="return confirm('Cancel this order?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-cancel">
                                    <i class="bi bi-x-octagon-fill"></i> Cancel Order
                                </button>
                            </form>
                        @endif
                    @endcan
                    @can('add-stock')
                        @if($po->isReceived())
                            <button type="button"
                                    class="action-btn"
                                    style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;"
                                    onclick="confirmReorder('{{ route('dashboard.lens-purchase-orders.reorder', $po->id) }}')">
                                <i class="fa fa-refresh"></i> Re-order Defective
                            </button>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- ═══ Left: Order Details ═══ --}}
        <div class="col-md-5">
            <div class="detail-card">
                <h4><i class="bi bi-info-circle-fill" style="color:#3498db;"></i> Order Details</h4>
                <div class="info-row">
                    <span class="key">PO Number</span>
                    <span class="val">
                        <strong>{{ $po->po_number }}</strong>
                        @if($po->is_reorder)
                            <span style="background:#e74c3c;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;margin-left:6px;">RE-ORDER</span>
                        @endif
                    </span>
                </div>
                @if($po->is_reorder && $po->original_po_id)
                <div class="info-row">
                    <span class="key">Original PO</span>
                    <span class="val">
                        <a href="{{ route('dashboard.lens-purchase-orders.show', $po->original_po_id) }}" style="color:#e74c3c;font-weight:600;">
                            View Original Order <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i>
                        </a>
                    </span>
                </div>
                @endif
                <div class="info-row"><span class="key">Lab / Supplier</span><span class="val">{{ $po->lab_name }}</span></div>
                <div class="info-row"><span class="key">Branch</span><span class="val">{{ $po->branch->name ?? '-' }}</span></div>
                <div class="info-row">
                    <span class="key">Invoice</span>
                    <span class="val">
                        @if($po->invoice)
                        <a href="{{ route('dashboard.invoice.show', $po->invoice->invoice_code) }}" style="color:#3498db;font-weight:600;">
                            {{ $po->invoice->invoice_code }} <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i>
                        </a>
                        @else — @endif
                    </span>
                </div>
                <div class="info-row"><span class="key">Customer</span><span class="val">{{ optional(optional($po->invoice)->customer)->english_name ?? '-' }}</span></div>
                <div class="info-row"><span class="key">Ordered By</span><span class="val">{{ $po->orderedBy->name ?? '-' }}</span></div>
                <div class="info-row"><span class="key">Ordered At</span><span class="val">{{ $po->ordered_at ? $po->ordered_at->format('d/m/Y H:i') : $po->created_at->format('d/m/Y H:i') }}</span></div>
                @if($po->isReceived())
                    <div class="info-row"><span class="key">Received By</span><span class="val" style="color:#27ae60;">{{ $po->receivedBy->name ?? '-' }}</span></div>
                    <div class="info-row"><span class="key">Received At</span><span class="val" style="color:#27ae60;">{{ $po->received_at ? $po->received_at->format('d/m/Y H:i') : '-' }}</span></div>
                @endif
                @if($po->notes)
                    <div style="margin-top:12px;background:#f8f9ff;padding:12px;border-radius:8px;font-size:13px;border-left:3px solid #3498db;">
                        <i class="bi bi-chat-left-text" style="color:#3498db;"></i> <strong>Notes:</strong> {{ $po->notes }}
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ Right: Lens Items ═══ --}}
        <div class="col-md-7">
            <div class="detail-card">
                <h4>
                    <i class="bi bi-eyeglasses" style="color:#9b59b6;"></i> Ordered Lenses
                    <span style="margin-left:auto;background:#e8ecf7;color:#555;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                        {{ $po->items->count() }} item(s)
                    </span>
                </h4>

                @foreach($po->items as $item)
                    @php
                        $dir = trim($item->invoiceItem->direction ?? '');
                        $sideLabel = $dir === 'R' ? 'Right Eye' : ($dir === 'L' ? 'Left Eye' : null);
                        $sideColor = $dir === 'R' ? '#1565c0' : '#6a1b9a';
                        $sideBg    = $dir === 'R' ? '#e3f2fd' : '#f3e5f5';
                        $sideIcon  = $dir === 'R' ? 'bi-arrow-right-circle-fill' : 'bi-arrow-left-circle-fill';
                    @endphp
                    <div class="lens-row">
                        <div class="row" style="align-items:center;">
                            <div class="col-md-7">
                                <div style="margin-bottom:6px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                    <span style="background:linear-gradient(135deg,#3498db,#2980b9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                        {{ $item->lens_product_id }}
                                    </span>
                                    @if($sideLabel)
                                        <span style="background:{{ $sideBg }};color:{{ $sideColor }};padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;border:1px solid {{ $sideColor }}20;">
                                            <i class="bi {{ $sideIcon }}"></i> {{ $sideLabel }}
                                        </span>
                                    @endif
                                    <strong style="font-size:14px;">{{ $item->lens->description ?? '—' }}</strong>
                                </div>
                                @if($item->lens)
                                    <div style="color:#888;font-size:12px;margin-top:3px;">
                                        <i class="bi bi-tags"></i> {{ $item->lens->brand ?? '-' }}
                                        &nbsp;·&nbsp; Index: {{ $item->lens->index ?? '-' }}
                                        &nbsp;·&nbsp; {{ $item->lens->lense_type ?? '-' }}
                                    </div>
                                @endif
                                @if($item->notes)
                                    <div style="color:#e67e22;font-size:12px;margin-top:4px;">
                                        <i class="bi bi-chat-text"></i> {{ $item->notes }}
                                    </div>
                                @endif
                                @if(!$item->glass_lense_id)
                                    <div style="color:#e74c3c;font-size:11px;margin-top:4px;">
                                        <i class="bi bi-exclamation-triangle"></i> Lens not in catalog — stock not tracked
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.5px;">Ordered</div>
                                <div style="font-size:24px;font-weight:700;color:#2c3e50;line-height:1.2;">{{ $item->quantity }}</div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.5px;">In Stock</div>
                                @php $avail = $item->available_stock ?? 0; @endphp
                                <span class="stock-badge {{ $avail > 0 ? 'stock-ok' : 'stock-zero' }}" style="font-size:16px;display:inline-block;margin-top:4px;">
                                    {{ $avail }}
                                </span>
                                @if($po->isReceived())
                                    <div style="font-size:11px;color:#27ae60;margin-top:5px;font-weight:600;">
                                        <i class="bi bi-check-circle"></i> Rcvd: {{ $item->received_quantity }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script>
function confirmReorder(url) {
    Swal.fire({
        title: 'Re-order Defective Lenses?',
        html: `
            <p>You will be taken to a form to:</p>
            <ul style="text-align:left;margin:10px 0 0 20px;line-height:1.8;">
                <li>Mark defective lenses as <strong style="color:#e74c3c;">هالك (Damaged)</strong></li>
                <li>Search &amp; select replacement lenses from the catalog</li>
                <li>Create a new lab order for replacements</li>
            </ul>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="fa fa-arrow-right"></i> Go to Re-order',
        cancelButtonText: '<i class="fa fa-times"></i> Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
@endsection
