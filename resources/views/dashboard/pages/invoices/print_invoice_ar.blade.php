{{--
@php use Illuminate\Support\Str; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <!-- Invoice styling -->
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #232323;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #232323;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoiceImg {
            width: 100%;
            max-width: 100px;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        #invoicePrintButton {
            margin-right: 2px;
            width: 100px;
            height: 40px;
            background: #1b4b72;
            color: white;
        }

        .totals {
            margin-top: 40px;
            font-weight: bold;
            width: 100%;
            display: block;
            text-align: center;
        }

        .data {
            font-weight: bold
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>

</head>

<body>
<div class="invoice-box">
    <table style="font-size: 0.9em;">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            @if(Settings::get('invoice_logo_ar'))
                                <img class="invoiceImg" src="{{ asset('storage/' . Settings::get('invoice_logo_ar')) }}" alt="Logo AR">
                            @endif
                            @if(Settings::get('invoice_logo_en'))
                                <img class="invoiceImg" src="{{ asset('storage/' . Settings::get('invoice_logo_en')) }}" alt="Logo EN">
                            @endif
                            @if(!Settings::get('invoice_logo_ar') && !Settings::get('invoice_logo_en'))
                                <img class="invoiceImg" src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
                            @endif
                        </td>
                        <td>
                            {{ $id }} :<span class="data">رقم الفاتوره #</span><br/>
                            {{ date('Y-m-d', strtotime($invoice->created_at)) }} :<span class="data">الانشاء</span><br/>
                            {{ $invoice->pickup_date ?? '-' }} :<span class="data">تاريخ التسليم</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            {{ $invoice->customer ? $invoice->customer->customer_id : '-' }} :<span class="data">كود العميل </span><br/>
                            {{ $invoice->customer ? $invoice->customer->english_name : '-' }} :<span
                                class="data">العميل </span><br/>
                            {{ $invoice->customer ? $invoice->customer->mobile_number : '-' }} :<span class="data">هاتف العميل </span><br/>
                            {{ $invoice->notes ?? '-' }} :<span class="data">ملاحظات </span><br/>
                        </td>
                        <td>
                            {{ $invoice->user ? $invoice->user->first_name : '-' }} :<span
                                class="data">البائع </span><br/>
                            {{ $invoice->doctor ? $invoice->doctor->name : '-' }} :<span
                                class="data">الدكتور </span><br/>
                            {{ $invoice->status ?? '-' }} :<span class="data">الحاله </span><br/>
                            {{ $invoice->payment_way ?? '-' }} :<span class="data">طريقة الدفع </span><br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    --}}
{{-- Items Table --}}{{--

    <table class="items"
           style="width: 100%; border-collapse: collapse; text-align: center; margin: 20px 0;font-size: 0.9em;">
        <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <td>م</td>
            <td style="text-align: center">بند #</td>
            <td>وصف</td>
            <td>كميه</td>
            <td>سعر</td>
            <td>صافي</td>
            <td>الخصم</td>
            @if($invoice->insurance_cardholder_type)
                <th>I&C Discount</th>
            @endif
            <td>الاجمالي</td>
        </tr>
        </thead>
        <tbody>
        @foreach($invoiceItems as $key => $item)
            @php
                $totalPrice = ($item->quantity ?? 0) * ($item->price ?? 0);
                $discountValue = $totalPrice - ($item->net ?? 0);
                $discountPercent = $totalPrice > 0 ? round(($discountValue / $totalPrice) * 100, 1) : 0;
            @endphp
            <tr style="border-bottom: 1px solid #ddd;">
                <td>{{ $key + 1 }}</td>
                <td style="text-align: center">{{ $item->product_id ?? '-' }}</td>
                <td>{{ isset($item->getItemDescription) ? Str::limit($item->getItemDescription->description, 10, '...') : '-' }}</td>
                <td>{{ $item->quantity ?? 0 }}</td>
                <td>{{ number_format($item->price ?? 0, 2) .' ريال' }}</td>
                <td>{{ number_format($item->net ?? 0, 2) .' ريال' }}</td>
                <td class="text-center">
                    <div>{{ $discountPercent }}%</div>
                    <small class="text-muted">{{ number_format($discountValue, 2) }} ريال</small>
                </td>
                @if($invoice->insurance_cardholder_type)
                    <td>{{ ($item->insurance_cardholder_discount ?? 0) .'%' }}</td>
                @endif
                <td>{{ number_format($item->total ?? 0, 2) .' ريال' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    --}}
{{-- Display Discount Only if Invoice Discount Exists and No Insurance/Cardholder --}}{{--

    @if(!empty($invoice->discount_type) && empty($invoice->insurance_cardholder_type))
        @php
            $totalBefore = $invoice->total_before_discount ?? $invoice->total ?? 0;
            $totalAfter = $invoice->total ?? 0;
            $discountAmount = $totalBefore - $totalAfter;
        @endphp

        <table class="items table table-bordered"
               style="width: 100%; border-collapse: collapse; text-align: center; margin: 20px 0;font-size: 0.9em;">
            <thead>
            <tr style="background-color: #f2f2f2;">
                <th>نوع الخصم</th>
                <th>قيمة الخصم</th>
                <th>السعر قبل الخصم</th>
                <th>مبلغ الخصم</th>
                <th>السعر بعد الخصم</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $invoice->discount_type }}</td>
                <td style="text-align: center">
                    @if($invoice->discount_type == 'fixed')
                        {{ number_format($invoice->discount_value, 2) .' ريال' }}
                    @else
                        {{ $invoice->discount_value }} %
                    @endif
                </td>
                <td>{{ number_format($totalBefore, 2) .' ريال' }}</td>
                <td>{{ number_format($discountAmount, 2) .' ريال' }}</td>
                <td>{{ number_format($totalAfter, 2) .' ريال' }}</td>
            </tr>
            </tbody>
        </table>
    @endif

    --}}
{{-- Paid / Remaining --}}{{--

    --}}
{{-- @php
         $finalTotal = $invoice->total ?? 0;
         if($invoice->discount_type == 'fixed' && $invoice->discount_value) {
             $finalTotal = ($invoice->total ?? 0) - ($invoice->discount_value ?? 0);
         } elseif($invoice->discount_type == 'percentage' && $invoice->discount_value) {
             $finalTotal = ($invoice->total ?? 0) - (($invoice->total ?? 0) * ($invoice->discount_value/100));
         }
         $remaining = $finalTotal - ($invoice->paied ?? 0);
     @endphp--}}{{--


    <table class="items table table-bordered"
           style="width: 100%; border-collapse: collapse; margin: 20px 0; text-align: center;font-size: 0.9em;">
        <thead>
        <tr style="background-color: #f2f2f2;">
            <th>المدفوع</th>
            <th>المتبقي</th>
            <th>الاجمالي قبل الخصم</th>
            <th>الاجمالي بعد الخصم</th>
            <th>اجمالي الفاتورة</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ number_format($invoice->paied ?? 0, 2) .' ريال' }}</td>
            <td style="text-align: center">{{ number_format($invoice->remaining, 2) .' ريال' }}</td>
            <td>{{ number_format($invoice->total_before_discount ?? $invoice->total ?? 0, 2) .' ريال' }}</td>
            <td>{{ number_format($invoice->total, 2) .' ريال' }}</td>
            <td>{{ number_format($invoice->total, 2) .' ريال' }}</td>
        </tr>
        </tbody>
    </table>

    <div class="data">
        <span style="font-weight: 600; font-size: 14px">
            شكرا لتسوقك عبر موقعنا النظارات الحديثه
        </span><br/>
        <span style="font-weight: 600; font-size: 14px">
            http://modern-opt.com/
        </span><br/>
        <span style="font-weight: 600; font-size: 12px">
            لاستبدال والاسترجاع خلال 14 يوما من تاريخ الشراء (شرط أن تكون في حالتها الأصلية وبداخل علبتها مع وجوب تقديم فاتورة الشراء)
            يرجى استلام بضائعكم خلال 30 يوما من تاريخ الفاتورة
            يرجى الاحتفاظ بالفاتورة حيث انها دليل الشراء وتستخدم في الضمان
            شاركنا رأيك وساعدنا على توفير أفضل خدمة لك بالاتصال على الرقم المجاني 00974 40011581
        </span>
    </div>

    <br>
    <button type="button" id="invoicePrintButton" onclick="printpage()" class="btn btn-primary prints">print</button>
</div>

</body>
</html>

<script type="text/javascript">
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("invoicePrintButton");
        //Set the print button visibility to 'hidden'
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }
</script>
--}}


@php use Illuminate\Support\Str; @endphp
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>فاتورة #{{ $invoice->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Cairo','Helvetica Neue',Arial,sans-serif; background:#f0f2f8; color:#333; direction:rtl; }

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
        .inv-header .inv-meta { text-align:left; font-size:13px; opacity:.9; line-height:2; }
        .inv-header .inv-meta strong { font-weight:700; }

        /* Body */
        .inv-body { background:#fff; border-radius:0 0 16px 16px; box-shadow:0 8px 30px rgba(102,126,234,.15); overflow:hidden; }

        /* Info grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; border-bottom:2px solid #f0f2f8; }
        .info-block { padding:20px 26px; }
        .info-block:first-child { border-left:2px solid #f0f2f8; }
        .block-title {
            font-size:11px; font-weight:700; text-transform:uppercase;
            letter-spacing:1px; color:#764ba2; margin-bottom:12px;
            display:flex; align-items:center; gap:6px;
        }
        .block-title::after { content:''; flex:1; height:1.5px; background:linear-gradient(to left,transparent,#764ba230); }
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
            font-family:'Cairo',sans-serif;
            box-shadow:0 4px 15px rgba(102,126,234,.35);
        }

        @media print {
            @page { size: A4 portrait; margin: 5mm; }
            html { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
            body { background:#fff !important; zoom: 90%; }
            .page-wrapper { margin:0 !important; padding:0 !important; max-width:100% !important; }
            .print-btn { display:none !important; }
            .inv-header { padding:10px 18px !important; border-radius:0 !important; }
            .inv-header img { height:38px !important; }
            .inv-header .inv-title h1 { font-size:18px !important; }
            .inv-header .inv-title .inv-num { font-size:11px !important; }
            .inv-header .inv-meta { font-size:10px !important; line-height:1.6 !important; }
            .info-block { padding:6px 12px !important; }
            .block-title { font-size:9px !important; margin-bottom:5px !important; }
            .info-row { padding:2px 0 !important; font-size:10px !important; }
            .sec-label { padding:5px 12px !important; font-size:10px !important; }
            .items-tbl thead th { padding:5px 6px !important; font-size:9px !important; }
            .items-tbl tbody td { padding:5px 6px !important; font-size:10px !important; }
            .mini-tbl th, .mini-tbl td { padding:4px 6px !important; font-size:9px !important; }
            .sum-row { padding:4px 10px !important; font-size:10px !important; margin-bottom:4px !important; }
            .sum-total .s-lbl, .sum-total .s-val { font-size:11px !important; }
            .inv-footer { padding:6px 12px !important; }
            .footer-brand { font-size:11px !important; margin-bottom:2px !important; }
            .footer-url   { font-size:10px !important; margin-bottom:3px !important; }
            .footer-note  { font-size:8.5px !important; line-height:1.5 !important; }
            .inv-body { border-radius:0 !important; box-shadow:none !important; }
            .inv-header, .sec-label, .sum-total { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <div class="inv-header">
        <div style="display:flex;align-items:center;gap:10px;">
            @if(Settings::get('invoice_logo_ar'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_ar')) }}" alt="Logo AR" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(Settings::get('invoice_logo_en'))
                <img src="{{ asset('storage/' . Settings::get('invoice_logo_en')) }}" alt="Logo EN" style="max-height:60px;max-width:120px;object-fit:contain;">
            @endif
            @if(!Settings::get('invoice_logo_ar') && !Settings::get('invoice_logo_en'))
                <img src="{{ asset('assets/img/modern.png') }}" alt="النظارات الحديثة" style="max-height:60px;">
            @endif
        </div>
        <div class="inv-title">
            <h1>فاتورة</h1>
            <div class="inv-num">رقم الفاتورة &nbsp;#&nbsp; <strong>{{ $invoice->id }}</strong></div>
        </div>
        <div class="inv-meta">
            <div><strong>تاريخ الإنشاء:</strong>&nbsp; {{ date('Y-m-d', strtotime($invoice->created_at)) }}</div>
            <div><strong>تاريخ التسليم:</strong>&nbsp; {{ $invoice->pickup_date ?? '-' }}</div>
            <div><strong>طريقة الدفع:</strong>&nbsp; {{ $invoice->payment_way ?? '-' }}</div>
        </div>
    </div>

    <div class="inv-body">

        <div class="info-grid">
            <div class="info-block">
                <div class="block-title">👤 بيانات العميل</div>
                <div class="info-row"><span class="lbl">كود العميل</span><span class="val">{{ $invoice->customer ? $invoice->customer->customer_id : '-' }}</span></div>
                <div class="info-row"><span class="lbl">الاسم</span><span class="val">{{ $invoice->customer ? $invoice->customer->english_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">الهاتف</span><span class="val">{{ $invoice->customer ? $invoice->customer->mobile_number : '-' }}</span></div>
                <div class="info-row"><span class="lbl">ملاحظات</span><span class="val">{{ $invoice->notes ?? '-' }}</span></div>
            </div>
            <div class="info-block">
                <div class="block-title">📋 بيانات الفاتورة</div>
                <div class="info-row"><span class="lbl">البائع</span><span class="val">{{ $invoice->user ? $invoice->user->first_name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">الدكتور</span><span class="val">{{ $invoice->doctor ? $invoice->doctor->name : '-' }}</span></div>
                <div class="info-row"><span class="lbl">الحالة</span><span class="val"><span class="status-badge">{{ $invoice->status ?? '-' }}</span></span></div>
                <div class="info-row"><span class="lbl">طريقة الدفع</span><span class="val">{{ $invoice->payment_way ?? '-' }}</span></div>
            </div>
        </div>

        <div class="sec-label">🛍️ &nbsp;بنود الفاتورة</div>
        <table class="items-tbl">
            <thead>
            <tr>
                <th>#</th><th>بند</th><th>الوصف</th><th>الكمية</th>
                <th>السعر</th><th>الصافي</th><th>الخصم</th>
                @if($invoice->insurance_cardholder_type)<th>خصم I&C</th>@endif
                <th>الإجمالي</th>
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
                    <td style="font-weight:700;color:#667eea;">{{ $item->product_id??'- ' }}</td>
                    <td style="font-weight:600;">{{ isset($item->getItemDescription)?Str::limit($item->getItemDescription->description,20,'...'):'-' }}</td>
                    <td><strong>{{ $item->quantity??0 }}</strong></td>
                    <td>{{ number_format($item->price??0,2) }} ر.ق</td>
                    <td style="color:#27ae60;font-weight:700;">{{ number_format($item->net??0,2) }} ر.ق</td>
                    <td>
                        <div style="color:#e74c3c;font-weight:700;">{{ $dp }}%</div>
                        <small style="color:#999;">{{ number_format($dv,2) }} ر.ق</small>
                    </td>
                    @if($invoice->insurance_cardholder_type)<td>{{ ($item->insurance_cardholder_discount??0) }}%</td>@endif
                    <td style="font-weight:700;color:#764ba2;">{{ number_format($item->total??0,2) }} ر.ق</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if(!empty($invoice->discount_type) && empty($invoice->insurance_cardholder_type))
            @php $tb=$invoice->total_before_discount??$invoice->total??0; $ta=$invoice->total??0; $da=$tb-$ta; @endphp
            <div class="sec-label">🏷️ &nbsp;تفاصيل الخصم</div>
            <div style="padding:16px 26px;">
                <table class="mini-tbl">
                    <thead><tr><th>نوع الخصم</th><th>قيمة الخصم</th><th>قبل الخصم</th><th>مبلغ الخصم</th><th>بعد الخصم</th></tr></thead>
                    <tbody>
                    <tr>
                        <td><strong>{{ $invoice->discount_type }}</strong></td>
                        <td>@if($invoice->discount_type=='fixed'){{ number_format($invoice->discount_value,2) }} ر.ق@else{{ $invoice->discount_value }}%@endif</td>
                        <td>{{ number_format($tb,2) }} ر.ق</td>
                        <td style="color:#e74c3c;font-weight:700;">{{ number_format($da,2) }} ر.ق</td>
                        <td style="color:#27ae60;font-weight:700;">{{ number_format($ta,2) }} ر.ق</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <div class="sec-label">💰 &nbsp;ملخص الدفع</div>
        <div style="padding:20px 26px;">
            <div style="display:flex;justify-content:flex-end;">
                <div style="min-width:290px;">
                    <div class="sum-row"><span class="s-lbl">الإجمالي قبل الخصم</span><span class="s-val">{{ number_format($invoice->total_before_discount??$invoice->total??0,2) }} ر.ق</span></div>
                    <div class="sum-row sum-total"><span class="s-lbl">إجمالي الفاتورة</span><span class="s-val">{{ number_format($invoice->total,2) }} ر.ق</span></div>
                    <div class="sum-row sum-paid"><span class="s-lbl">المدفوع ✓</span><span class="s-val">{{ number_format($invoice->paied??0,2) }} ر.ق</span></div>
                    <div class="sum-row sum-rem"><span class="s-lbl">المتبقي</span><span class="s-val">{{ number_format($invoice->remaining,2) }} ر.ق</span></div>
                </div>
            </div>
        </div>

        <div class="inv-footer">
            <div class="footer-brand">شكراً لتسوقك عبر النظارات الحديثة</div>
            <div class="footer-url">http://modern-opt.com/</div>
            <div class="footer-note">
                للاستبدال والاسترجاع خلال 14 يوماً من تاريخ الشراء (يشترط أن تكون في حالتها الأصلية وبداخل علبتها مع تقديم الفاتورة) &nbsp;·&nbsp;
                يُرجى استلام البضاعة خلال 30 يوماً من تاريخ الفاتورة &nbsp;·&nbsp;
                احتفظ بالفاتورة دليلاً للشراء والضمان &nbsp;·&nbsp;
                شاركنا رأيك على الرقم المجاني: 00974 40011581
            </div>
            <br>
            <button class="print-btn" id="printBtn" onclick="printPage()">🖨️ &nbsp;طباعة الفاتورة</button>
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
