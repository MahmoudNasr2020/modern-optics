{{--@php use Illuminate\Support\Str; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Inter','Helvetica Neue',Arial,sans-serif; background:#f0f2f8; color:#333; }

        .page-wrapper { max-width:900px; margin:30px auto; padding:0 16px 40px; }

        /* Header */
        .inv-header {
            background: linear-gradient(135deg,#667eea,#764ba2);
            border-radius:16px 16px 0 0;
            padding:28px 36px;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }
        .inv-header img { height:65px; width:auto; filter:brightness(0) invert(1); opacity:.9; }
        .inv-header .inv-title h1 { font-size:30px; font-weight:900; letter-spacing:1px; margin-bottom:4px; }
        .inv-header .inv-title .inv-num { font-size:14px; opacity:.85; font-weight:600; }
        .inv-header .inv-meta { text-align:right; font-size:13px; opacity:.9; line-height:2; }
        .inv-header .inv-meta strong { font-weight:700; }

        /* Body */
        .inv-body { background:#fff; border-radius:0 0 16px 16px; box-shadow:0 8px 30px rgba(102,126,234,.15); overflow:hidden; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; border-bottom:2px solid #f0f2f8; }
        .info-block { padding:20px 26px; }
        .info-block:last-child { border-left:2px solid #f0f2f8; }
        .block-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#764ba2; margin-bottom:12px; display:flex; align-items:center; gap:6px; }
        .block-title::after { content:''; flex:1; height:1.5px; background:linear-gradient(to right,transparent,#764ba230); }
        .info-row { display:flex; justify-content:space-between; padding:5px 0; font-size:13px; border-bottom:1px dashed #f0f2f8; }
        .info-row:last-child { border-bottom:none; }
        .info-row .lbl { color:#888; font-weight:600; }
        .info-row .val { color:#222; font-weight:700; }
        .status-badge { display:inline-block; padding:2px 12px; border-radius:20px; font-size:11.5px; font-weight:700; background:#e8f8f5; color:#27ae60; }

        /* Section label */
        .sec-label { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; padding:10px 26px; font-weight:700; font-size:13px; letter-spacing:.5px; }

        /* Items table */
        .items-tbl { width:100%; border-collapse:collapse; font-size:13px; }
        .items-tbl thead tr { background:#f7f8fc; }
        .items-tbl thead th { padding:11px 12px; font-weight:700; font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.4px; border-bottom:2px solid #e8eaf6; text-align:center; white-space:nowrap; }
        .items-tbl tbody tr:nth-child(even) { background:#fafbff; }
        .items-tbl tbody tr:hover { background:#f0ebff; }
        .items-tbl tbody td { padding:10px 12px; border-bottom:1px solid #f0f2f8; text-align:center; vertical-align:middle; color:#444; }
        .items-tbl tbody tr:last-child td { border-bottom:none; }

        /* Mini table */
        .mini-tbl { width:100%; border-collapse:collapse; font-size:12.5px; }
        .mini-tbl th { background:#f7f8fc; padding:8px 12px; font-weight:700; font-size:11px; color:#666; text-transform:uppercase; border-bottom:1.5px solid #e8eaf6; text-align:center; }
        .mini-tbl td { padding:8px 12px; text-align:center; color:#444; border-bottom:1px solid #f0f2f8; }

        /* Summary */
        .sum-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:7px 12px; border-radius:8px; background:#f7f8fc; margin-bottom:8px; }
        .sum-row .s-lbl { color:#666; font-weight:600; }
        .sum-row .s-val { color:#333; font-weight:700; }
        .sum-total { background:linear-gradient(135deg,#667eea,#764ba2); }
        .sum-total .s-lbl, .sum-total .s-val { color:#fff !important; font-size:14px; }
        .sum-paid  { background:#e8f8f5; } .sum-paid  .s-val { color:#27ae60; }
        .sum-rem   { background:#fff3cd; } .sum-rem   .s-val { color:#f39c12; }

        /* Footer */
        .inv-footer { background:#f7f8fc; border-top:2px solid #e8eaf6; padding:20px 26px; text-align:center; }
        .footer-brand { font-size:15px; font-weight:700; color:#667eea; margin-bottom:5px; }
        .footer-url   { font-size:13px; color:#764ba2; margin-bottom:10px; }
        .footer-note  { font-size:11.5px; color:#888; line-height:1.9; max-width:680px; margin:0 auto; }

        .print-btn {
            display:inline-flex; align-items:center; gap:8px;
            margin-top:18px; padding:10px 28px;
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:#fff; border:none; border-radius:10px;
            font-size:14px; font-weight:700; cursor:pointer;
            font-family:'Inter',sans-serif;
            box-shadow:0 4px 15px rgba(102,126,234,.35);
        }

        @media print {
            @page { size: A4 portrait; margin: 6mm; }
            html, body { background:#fff !important; }
            .page-wrapper { margin:0 !important; padding:0 !important; max-width:100% !important; }
            .print-btn { display:none !important; }
            .inv-header { padding:8px 14px !important; border-radius:0 !important; }
            .inv-header img { height:36px !important; }
            .inv-header .inv-title h1 { font-size:15px !important; }
            .inv-header .inv-title .inv-num { font-size:10px !important; }
            .inv-header .inv-meta { font-size:10px !important; line-height:1.5 !important; }
            .info-block { padding:5px 10px !important; }
            .block-title { font-size:9px !important; margin-bottom:4px !important; }
            .info-row { padding:2px 0 !important; font-size:10px !important; }
            .sec-label { padding:4px 10px !important; font-size:10px !important; }
            .items-tbl thead th { padding:3px 5px !important; font-size:8px !important; }
            .items-tbl tbody td { padding:3px 5px !important; font-size:9.5px !important; }
            .mini-tbl th, .mini-tbl td { padding:3px 5px !important; font-size:9px !important; }
            .sum-row { padding:3px 8px !important; font-size:10px !important; margin-bottom:3px !important; }
            .sum-total .s-lbl, .sum-total .s-val { font-size:11px !important; }
            .inv-footer { padding:5px 10px !important; }
            .footer-brand { font-size:11px !important; margin-bottom:2px !important; }
            .footer-url   { font-size:10px !important; margin-bottom:3px !important; }
            .footer-note  { font-size:8px !important; line-height:1.4 !important; }
            .inv-body { border-radius:0 !important; box-shadow:none !important; }
            .inv-header, .sec-label, .sum-total { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <div class="inv-header">
        <div style="display:flex;align-items:center;gap:10px;">
            @if(Settings::get('invoice_logo_en'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_en')) }}" alt="Logo EN" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(Settings::get('invoice_logo_ar'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_ar')) }}" alt="Logo AR" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(!Settings::get('invoice_logo_en') && !Settings::get('invoice_logo_ar'))
                <img src="{{ asset('assets/img/modern.png') }}" alt="Modern Optics" style="max-height:60px;">
            @endif
        </div>
        <div class="inv-title">
            <h1>Invoice</h1>
            <div class="inv-num">Invoice # &nbsp;<strong>{{ $invoice->id }}</strong></div>
        </div>
        <div class="inv-meta">
            <div><strong>Date:</strong>&nbsp; {{ date('Y-m-d', strtotime($invoice->created_at)) }}</div>
            <div><strong>Pickup Date:</strong>&nbsp; {{ $invoice->pickup_date ?? '-' }}</div>
            <div><strong>Payment:</strong>&nbsp; {{ $invoice->payment_way ?? '-' }}</div>
        </div>
    </div>

    <div class="inv-body">

        <div class="info-grid">
            <div class="info-block">
                <div class="block-title">👤 Customer Info</div>
                <div class="info-row"><span class="lbl">Customer ID</span><span class="val">{{ $invoice->customer ? $invoice->customer->customer_id : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Name</span><span class="val">{{ $invoice->customer ? $invoice->customer->english_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Phone</span><span class="val">{{ $invoice->customer ? $invoice->customer->mobile_number : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Notes</span><span class="val">{{ $invoice->notes ?? '-' }}</span></div>
            </div>
            <div class="info-block">
                <div class="block-title">📋 Invoice Details</div>
                <div class="info-row"><span class="lbl">Seller</span><span class="val">{{ $invoice->user ? $invoice->user->first_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Doctor</span><span class="val">{{ $invoice->doctor ? $invoice->doctor->name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Status</span><span class="val"><span class="status-badge">{{ $invoice->status ?? '-' }}</span></span></div>
                <div class="info-row"><span class="lbl">Payment Method</span><span class="val">{{ $invoice->payment_way ?? '-' }}</span></div>
            </div>
        </div>

        <div class="sec-label">🛍️ &nbsp;Invoice Items</div>
        <table class="items-tbl">
            <thead>
            <tr>
                <th>No</th><th>Item #</th><th>Description</th><th>Qty</th>
                <th>Price</th><th>Net</th><th>Discount</th>
                @if($invoice->insurance_cardholder_type)<th>I&C Discount</th>@endif
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceItems as $key => $item)
                @php
                    $tp  = ($item->quantity??0)*($item->price??0);
                    $dv  = $tp - ($item->net??0);
                    $dp  = $tp>0 ? round(($dv/$tp)*100,1) : 0;
                @endphp
                <tr>
                    <td style="color:#999;font-weight:600;">{{ $key+1 }}</td>
                    <td style="font-weight:700;color:#667eea;">{{ $item->product_id??'-' }}</td>
                    <td style="font-weight:600;">{{ isset($item->getItemDescription)?Str::limit($item->getItemDescription->description,20,'...'):'-' }}</td>
                    <td><strong>{{ $item->quantity??0 }}</strong></td>
                    <td>{{ number_format($item->price??0,2) }} SAR</td>
                    <td style="color:#27ae60;font-weight:700;">{{ number_format($item->net??0,2) }} SAR</td>
                    <td>
                        <div style="color:#e74c3c;font-weight:700;">{{ $dp }}%</div>
                        <small style="color:#999;">{{ number_format($dv,2) }} SAR</small>
                    </td>
                    @if($invoice->insurance_cardholder_type)<td>{{ ($item->insurance_cardholder_discount??0) }}%</td>@endif
                    <td style="font-weight:700;color:#764ba2;">{{ number_format($item->total??0,2) }} SAR</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if(!empty($invoice->discount_type) && empty($invoice->insurance_cardholder_type))
            @php $tb=$invoice->total_before_discount??$invoice->total??0; $ta=$invoice->total??0; $da=$tb-$ta; @endphp
            <div class="sec-label">🏷️ &nbsp;Discount Details</div>
            <div style="padding:16px 26px;">
                <table class="mini-tbl">
                    <thead><tr><th>Discount Type</th><th>Discount Value</th><th>Before Discount</th><th>Discount Amount</th><th>After Discount</th></tr></thead>
                    <tbody>
                    <tr>
                        <td><strong>{{ $invoice->discount_type }}</strong></td>
                        <td>@if($invoice->discount_type=='fixed'){{ number_format($invoice->discount_value,2) }} SAR@else{{ $invoice->discount_value }}%@endif</td>
                        <td>{{ number_format($tb,2) }} SAR</td>
                        <td style="color:#e74c3c;font-weight:700;">{{ number_format($da,2) }} SAR</td>
                        <td style="color:#27ae60;font-weight:700;">{{ number_format($ta,2) }} SAR</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <div class="sec-label">💰 &nbsp;Payment Summary</div>
        <div style="padding:20px 26px;">
            <div style="display:flex;justify-content:flex-end;">
                <div style="min-width:290px;">
                    <div class="sum-row"><span class="s-lbl">Total Before Discount</span><span class="s-val">{{ number_format($invoice->total_before_discount??$invoice->total??0,2) }} SAR</span></div>
                    <div class="sum-row sum-total"><span class="s-lbl">Invoice Total</span><span class="s-val">{{ number_format($invoice->total,2) }} SAR</span></div>
                    <div class="sum-row sum-paid"><span class="s-lbl">Paid ✓</span><span class="s-val">{{ number_format($invoice->paied??0,2) }} SAR</span></div>
                    <div class="sum-row sum-rem"><span class="s-lbl">Remaining</span><span class="s-val">{{ number_format($invoice->remaining,2) }} SAR</span></div>
                </div>
            </div>
        </div>

        <div class="inv-footer">
            <div class="footer-brand">Thank you for shopping at Modern Optics</div>
            <div class="footer-url">http://modern-opt.com/</div>
            <div class="footer-note">
                Returns and exchanges within 14 days from purchase date (original condition, original box, with invoice required) &nbsp;·&nbsp;
                Please collect your goods within 30 days of invoice date &nbsp;·&nbsp;
                Keep this invoice as proof of purchase and warranty &nbsp;·&nbsp;
                Share your feedback: 00974 40011581
            </div>
            <br>
            <button class="print-btn" id="printBtn" onclick="printPage()">🖨️ &nbsp;Print Invoice</button>
        </div>

    </div>
</div>
<script>
    function printPage(){
        document.getElementById('printBtn').style.display='none';
        window.print();
        document.getElementById('printBtn').style.display='inline-flex';
    }
</script>
</body>
</html>--}}


@php use Illuminate\Support\Str; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Inter','Helvetica Neue',Arial,sans-serif; background:#f0f2f8; color:#333; }

        .page-wrapper { max-width:900px; margin:30px auto; padding:0 16px 40px; }

        /* Header */
        .inv-header {
            background: linear-gradient(135deg,#667eea,#764ba2);
            border-radius:16px 16px 0 0;
            padding:28px 36px;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }
        .inv-header img { height:65px; width:auto; filter:brightness(0) invert(1); opacity:.9; }
        .inv-header .inv-title h1 { font-size:30px; font-weight:900; letter-spacing:1px; margin-bottom:4px; }
        .inv-header .inv-title .inv-num { font-size:14px; opacity:.85; font-weight:600; }
        .inv-header .inv-meta { text-align:right; font-size:13px; opacity:.9; line-height:2; }
        .inv-header .inv-meta strong { font-weight:700; }

        /* Body */
        .inv-body { background:#fff; border-radius:0 0 16px 16px; box-shadow:0 8px 30px rgba(102,126,234,.15); overflow:hidden; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; border-bottom:2px solid #f0f2f8; }
        .info-block { padding:20px 26px; }
        .info-block:last-child { border-left:2px solid #f0f2f8; }
        .block-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#764ba2; margin-bottom:12px; display:flex; align-items:center; gap:6px; }
        .block-title::after { content:''; flex:1; height:1.5px; background:linear-gradient(to right,transparent,#764ba230); }
        .info-row { display:flex; justify-content:space-between; padding:5px 0; font-size:13px; border-bottom:1px dashed #f0f2f8; }
        .info-row:last-child { border-bottom:none; }
        .info-row .lbl { color:#888; font-weight:600; }
        .info-row .val { color:#222; font-weight:700; }
        .status-badge { display:inline-block; padding:2px 12px; border-radius:20px; font-size:11.5px; font-weight:700; background:#e8f8f5; color:#27ae60; }

        /* Section label */
        .sec-label { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; padding:10px 26px; font-weight:700; font-size:13px; letter-spacing:.5px; }

        /* Items table */
        .items-tbl { width:100%; border-collapse:collapse; font-size:13px; }
        .items-tbl thead tr { background:#f7f8fc; }
        .items-tbl thead th { padding:11px 12px; font-weight:700; font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.4px; border-bottom:2px solid #e8eaf6; text-align:center; white-space:nowrap; }
        .items-tbl tbody tr:nth-child(even) { background:#fafbff; }
        .items-tbl tbody tr:hover { background:#f0ebff; }
        .items-tbl tbody td { padding:10px 12px; border-bottom:1px solid #f0f2f8; text-align:center; vertical-align:middle; color:#444; }
        .items-tbl tbody tr:last-child td { border-bottom:none; }

        /* Mini table */
        .mini-tbl { width:100%; border-collapse:collapse; font-size:12.5px; }
        .mini-tbl th { background:#f7f8fc; padding:8px 12px; font-weight:700; font-size:11px; color:#666; text-transform:uppercase; border-bottom:1.5px solid #e8eaf6; text-align:center; }
        .mini-tbl td { padding:8px 12px; text-align:center; color:#444; border-bottom:1px solid #f0f2f8; }

        /* Summary */
        .sum-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:7px 12px; border-radius:8px; background:#f7f8fc; margin-bottom:8px; }
        .sum-row .s-lbl { color:#666; font-weight:600; }
        .sum-row .s-val { color:#333; font-weight:700; }
        .sum-total { background:linear-gradient(135deg,#667eea,#764ba2); }
        .sum-total .s-lbl, .sum-total .s-val { color:#fff !important; font-size:14px; }
        .sum-paid  { background:#e8f8f5; } .sum-paid  .s-val { color:#27ae60; }
        .sum-rem   { background:#fff3cd; } .sum-rem   .s-val { color:#f39c12; }

        /* Footer */
        .inv-footer { background:#f7f8fc; border-top:2px solid #e8eaf6; padding:20px 26px; text-align:center; }
        .footer-brand { font-size:15px; font-weight:700; color:#667eea; margin-bottom:5px; }
        .footer-url   { font-size:13px; color:#764ba2; margin-bottom:10px; }
        .footer-note  { font-size:11.5px; color:#888; line-height:1.9; max-width:680px; margin:0 auto; }

        .print-btn {
            display:inline-flex; align-items:center; gap:8px;
            margin-top:18px; padding:10px 28px;
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:#fff; border:none; border-radius:10px;
            font-size:14px; font-weight:700; cursor:pointer;
            font-family:'Inter',sans-serif;
            box-shadow:0 4px 15px rgba(102,126,234,.35);
        }

        @media print {
            @page { size: A4 portrait; margin: 6mm; }
            html, body { background:#fff !important; }
            .page-wrapper { margin:0 !important; padding:0 !important; max-width:100% !important; }
            .print-btn { display:none !important; }
            .inv-header { padding:8px 14px !important; border-radius:0 !important; }
            .inv-header img { height:36px !important; }
            .inv-header .inv-title h1 { font-size:15px !important; }
            .inv-header .inv-title .inv-num { font-size:10px !important; }
            .inv-header .inv-meta { font-size:10px !important; line-height:1.5 !important; }
            .info-block { padding:5px 10px !important; }
            .block-title { font-size:9px !important; margin-bottom:4px !important; }
            .info-row { padding:2px 0 !important; font-size:10px !important; }
            .sec-label { padding:4px 10px !important; font-size:10px !important; }
            .items-tbl thead th { padding:3px 5px !important; font-size:8px !important; }
            .items-tbl tbody td { padding:3px 5px !important; font-size:9.5px !important; }
            .mini-tbl th, .mini-tbl td { padding:3px 5px !important; font-size:9px !important; }
            .sum-row { padding:3px 8px !important; font-size:10px !important; margin-bottom:3px !important; }
            .sum-total .s-lbl, .sum-total .s-val { font-size:11px !important; }
            .inv-footer { padding:5px 10px !important; }
            .footer-brand { font-size:11px !important; margin-bottom:2px !important; }
            .footer-url   { font-size:10px !important; margin-bottom:3px !important; }
            .footer-note  { font-size:8px !important; line-height:1.4 !important; }
            .inv-body { border-radius:0 !important; box-shadow:none !important; }
            .inv-header, .sec-label, .sum-total { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <div class="inv-header">
        <div style="display:flex;align-items:center;gap:10px;">
            @if(Settings::get('invoice_logo_en'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_en')) }}" alt="Logo EN" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(Settings::get('invoice_logo_ar'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_ar')) }}" alt="Logo AR" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(!Settings::get('invoice_logo_en') && !Settings::get('invoice_logo_ar'))
                <img src="{{ asset('assets/img/modern.png') }}" alt="Modern Optics" style="max-height:60px;">
            @endif
        </div>
        <div class="inv-title">
            <h1>Invoice</h1>
            <div class="inv-num">Invoice # &nbsp;<strong>{{ $invoice->id }}</strong></div>
        </div>
        <div class="inv-meta">
            <div><strong>Date:</strong>&nbsp; {{ date('Y-m-d', strtotime($invoice->created_at)) }}</div>
            <div><strong>Pickup Date:</strong>&nbsp; {{ $invoice->pickup_date ?? '-' }}</div>
            <div><strong>Payment:</strong>&nbsp; {{ $invoice->payment_way ?? '-' }}</div>
        </div>
    </div>

    <div class="inv-body">

        <div class="info-grid">
            <div class="info-block">
                <div class="block-title">👤 Customer Info</div>
                <div class="info-row"><span class="lbl">Customer ID</span><span class="val">{{ $invoice->customer ? $invoice->customer->customer_id : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Name</span><span class="val">{{ $invoice->customer ? $invoice->customer->english_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Phone</span><span class="val">{{ $invoice->customer ? $invoice->customer->mobile_number : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Notes</span><span class="val">{{ $invoice->notes ?? '-' }}</span></div>
            </div>
            <div class="info-block">
                <div class="block-title">📋 Invoice Details</div>
                <div class="info-row"><span class="lbl">Seller</span><span class="val">{{ $invoice->user ? $invoice->user->first_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Doctor</span><span class="val">{{ $invoice->doctor ? $invoice->doctor->name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">Status</span><span class="val"><span class="status-badge">{{ $invoice->status ?? '-' }}</span></span></div>
                <div class="info-row"><span class="lbl">Payment Method</span><span class="val">{{ $invoice->payment_way ?? '-' }}</span></div>
            </div>
        </div>

        <div class="sec-label">🛍️ &nbsp;Invoice Items</div>
        <table class="items-tbl">
            <thead>
            <tr>
                <th>No</th><th>Item #</th><th>Description</th><th>Qty</th>
                <th>Price</th><th>Net</th><th>Discount</th>
                @if($invoice->insurance_cardholder_type)<th>I&C Discount</th>@endif
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceItems as $key => $item)
                @php
                    $tp  = ($item->quantity??0)*($item->price??0);
                    $dv  = $tp - ($item->net??0);
                    $dp  = $tp>0 ? round(($dv/$tp)*100,1) : 0;
                @endphp
                <tr>
                    <td style="color:#999;font-weight:600;">{{ $key+1 }}</td>
                    <td style="font-weight:700;color:#667eea;">{{ $item->product_id??'-' }}</td>
                    <td style="font-weight:600;">{{ isset($item->getItemDescription)?Str::limit($item->getItemDescription->description,20,'...'):'-' }}</td>
                    <td><strong>{{ $item->quantity??0 }}</strong></td>
                    <td>{{ number_format($item->price??0,2) }} SAR</td>
                    <td style="color:#27ae60;font-weight:700;">{{ number_format($item->net??0,2) }} SAR</td>
                    <td>
                        <div style="color:#e74c3c;font-weight:700;">{{ $dp }}%</div>
                        <small style="color:#999;">{{ number_format($dv,2) }} SAR</small>
                    </td>
                    @if($invoice->insurance_cardholder_type)<td>{{ ($item->insurance_cardholder_discount??0) }}%</td>@endif
                    <td style="font-weight:700;color:#764ba2;">{{ number_format($item->total??0,2) }} SAR</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if(!empty($invoice->discount_type) && empty($invoice->insurance_cardholder_type))
            @php $tb=$invoice->total_before_discount??$invoice->total??0; $ta=$invoice->total??0; $da=$tb-$ta; @endphp
            <div class="sec-label">🏷️ &nbsp;Discount Details</div>
            <div style="padding:16px 26px;">
                <table class="mini-tbl">
                    <thead><tr><th>Discount Type</th><th>Discount Value</th><th>Before Discount</th><th>Discount Amount</th><th>After Discount</th></tr></thead>
                    <tbody>
                    <tr>
                        <td><strong>{{ $invoice->discount_type }}</strong></td>
                        <td>@if($invoice->discount_type=='fixed'){{ number_format($invoice->discount_value,2) }} SAR@else{{ $invoice->discount_value }}%@endif</td>
                        <td>{{ number_format($tb,2) }} SAR</td>
                        <td style="color:#e74c3c;font-weight:700;">{{ number_format($da,2) }} SAR</td>
                        <td style="color:#27ae60;font-weight:700;">{{ number_format($ta,2) }} SAR</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <div class="sec-label">💰 &nbsp;Payment Summary</div>
        <div style="padding:20px 26px;">
            <div style="display:flex;justify-content:flex-end;">
                <div style="min-width:290px;">
                    <div class="sum-row"><span class="s-lbl">Total Before Discount</span><span class="s-val">{{ number_format($invoice->total_before_discount??$invoice->total??0,2) }} SAR</span></div>
                    <div class="sum-row sum-total"><span class="s-lbl">Invoice Total</span><span class="s-val">{{ number_format($invoice->total,2) }} SAR</span></div>
                    <div class="sum-row sum-paid"><span class="s-lbl">Paid ✓</span><span class="s-val">{{ number_format($invoice->paied??0,2) }} SAR</span></div>
                    <div class="sum-row sum-rem"><span class="s-lbl">Remaining</span><span class="s-val">{{ number_format($invoice->remaining,2) }} SAR</span></div>
                </div>
            </div>
        </div>

        <div class="inv-footer">
            <div class="footer-brand">Thank you for shopping at Modern Optics</div>
            <div class="footer-url">http://modern-opt.com/</div>
            <div class="footer-note">
                Returns and exchanges within 14 days from purchase date (original condition, original box, with invoice required) &nbsp;·&nbsp;
                Please collect your goods within 30 days of invoice date &nbsp;·&nbsp;
                Keep this invoice as proof of purchase and warranty &nbsp;·&nbsp;
                Share your feedback: 00974 40011581
            </div>
            <br>
            <button class="print-btn" id="printBtn" onclick="printPage()">🖨️ &nbsp;Print Invoice</button>
        </div>

    </div>
</div>
<script>
    function printPage(){
        document.getElementById('printBtn').style.display='none';
        window.print();
        document.getElementById('printBtn').style.display='inline-flex';
    }
</script>
</body>
</html>
