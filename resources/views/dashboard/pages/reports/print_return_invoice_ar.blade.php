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
            color: #777;
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
            color: #555;
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
<h1>فاتوره</h1><br/>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img class="invoiceImg" src="{{asset('assets/img/modern.png')}}" alt="Optics Modern">
                        </td>

                        <td>
                            {{$id}} :<span class="data">رقم الفاتوره #</span><br/>
                            {{date_format($invoice->created_at,'Y-m-d')}} :<span class="data">الانشاء</span><br/>
                            {{$invoice->pickup_date}} :<span class="data">تاريخ التسليم</span>
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
                            {{ optional($invoice->customer)->customer_id ?? '—' }} :<span class="data">كود العميل </span><br/>
                            {{ optional($invoice->customer)->english_name ?? '—' }} :<span class="data">العميل </span><br/>
                            {{ optional($invoice->customer)->mobile_number ?? '—' }} :<span class="data">هاتف العميل </span><br/>
                            {{$invoice->notes??'-'}} :<span class="data">ملاحظات </span><br/>
                        </td>
                        <td>
                            {{ optional($invoice->user)->first_name ?? '—' }} :<span class="data">البائع </span><br/>
                            {{ optional($invoice->doctor)->name ?? '—' }} :<span class="data">الدكتور </span><br/>
                            {{$invoice->status}} :<span class="data">الحاله </span><br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
    <table class="items">
        <tr class="heading">
            <td>م</td>
            <td>بند #</td>
            <td>وصف</td>
            <td>كميه</td>
            <td>سعر</td>
            <td>صافي</td>
            <td>ضريبه</td>
            <td>نوع الخصم</td>
            <td>قيمه الخصم</td>
            <td>الاجمالي</td>
        </tr>

        @foreach($invoiceItems as $key => $item)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$item->product_id}}</td>
                <td>{{$item->description??'-'}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->net}}</td>
                <td>{{$item->tax}}</td>
                <td>{{$item->discount_type??'-'}}</td>
                <td>{{$item->discount_value??'-'}}</td>
                <td>{{$item->total}}</td>
            </tr>
        @endforeach
    </table>
    <br/>
    <table>
        <tr class="heading">
            <td>المدفوع</td>
            <td>المتبقي</td>
            <td>الاجمالي</td>
        </tr>

        <tr class="details">
            <td>{{$invoice->paied}}</td>
            <td>{{$invoice->remaining}}</td>
            <td>{{$invoice->total}}</td>
        </tr>
    </table>
    <table class="items">
        <tr class="heading">
            <td>النوع</td>
            <td>البنك #</td>
            <td>رقم الكارت</td>
            <td>تاريخ الانتهاء</td>
            <td>العمله</td>
            <td>المدفوع</td>
            <td>ضريبه</td>
            <td>الدفع</td>
            <td>التاريخ</td>
        </tr>

            <tr>
                <td>{{$payments->type}}</td>
                <td>{{$payments->bank}}</td>
                <td>{{$payments->card_number??'-'}}</td>
                <td>{{$payments->expiration_date}}</td>
                <td>{{$payments->currency}}</td>
                <td>{{$payments->payed_amount}}</td>
                <td>{{$payments->exchange_rate}}</td>
                <td>{{$payments->local_payment??'-'}}</td>
                <td>{{date_format($payments->created_at,'y-m-d')??'-'}}</td>
            </tr>
    </table>
    <br/>
    <div class="data">
        <span>
            شكرا لتسوقك عبر موقعنا النظارات الحديثه
        </span><br/>
        <span>
             http://modern-opt.com/
        </span><br/>
        <span>
            الاستبدال والاسترجاع في خلال 14 يوما من تاريخ الشراء (شرط أن تكون في حالتها الأصلية وبداخل علبتها مع وجوب تقديم فاتورة الشراء وعدم تركيب عدسات للنظارات
            .الطبية)/يرجى استلام بضائعكم خلال فترة ثلاثون يوما من تاريخ الفاتورة
            يرجى الاحتفاظ بالفاتورة حيث انها دليل الشراء وتستخدم في الضمان
            شاركنا رأيك وساعدنا على توفير أفضل خدمة لك بالاتصال على الرقم المجاني 00800100208 ساعات العمل من 11 صباحا وحتى 11 مساءا بتوقيت قطر
        </span>
    </div>
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
