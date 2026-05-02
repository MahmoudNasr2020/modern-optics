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
                        <td class="title">
                            <img class="invoiceImg" src="{{ asset('assets/img/modern.png') }}" alt="Optics Modern">
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

    {{-- Items Table --}}
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

    {{-- Display Discount Only if Invoice Discount Exists and No Insurance/Cardholder --}}
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

    {{-- Paid / Remaining --}}
    {{-- @php
         $finalTotal = $invoice->total ?? 0;
         if($invoice->discount_type == 'fixed' && $invoice->discount_value) {
             $finalTotal = ($invoice->total ?? 0) - ($invoice->discount_value ?? 0);
         } elseif($invoice->discount_type == 'percentage' && $invoice->discount_value) {
             $finalTotal = ($invoice->total ?? 0) - (($invoice->total ?? 0) * ($invoice->discount_value/100));
         }
         $remaining = $finalTotal - ($invoice->paied ?? 0);
     @endphp--}}

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
