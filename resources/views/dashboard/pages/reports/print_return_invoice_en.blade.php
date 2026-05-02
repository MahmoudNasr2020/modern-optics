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
            text-align: right;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
            text-align: left;
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
<h1>Invoice</h1><br/>
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
                            <span class="data">Invoice #</span>: {{$id}}<br/>
                            <span class="data">Created:</span> {{date_format($invoice->created_at,'Y-m-d')}}<br/>
                            <span class="data">Pick-Up Date:</span>{{$invoice->pickup_date}}
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
                            <span class="data">Customer Id: </span>{{$invoice->customer->customer_id}}<br/>
                            <span class="data">Customer: </span>{{$invoice->customer->english_name}}<br/>
                            <span class="data">Customer Phone: </span>{{$invoice->customer->mobile_number}}<br/>
                            <span class="data">Notes: </span>{{$invoice->notes??'-'}}<br/>
                        </td>
                        <td>
                            <span class="data">Sales: </span>{{$invoice->user->first_name}}<br/>
                            <span class="data">Doctor: </span>{{$invoice->doctor->name}}<br/>
                            <span class="data">Status: </span>{{$invoice->status}}<br/>
                             <span class="data">Return Reason: </span>{{$invoice->return_reason??'-'}}<br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
    <table class="items">
        <tr class="heading">
            <td>NO</td>
            <td>Item #</td>
            <td>Description</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Net</td>
            <td>Tax</td>
            <td>Discount Type</td>
            <td>Discount Value</td>
            <td>Total</td>
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
            <td>paied</td>
            <td>remaining</td>
            <td>Total</td>
        </tr>

        <tr class="details">
            <td>{{$invoice->paied}}</td>
            <td>{{$invoice->remaining}}</td>
            <td>{{$invoice->total}}</td>
        </tr>
    </table>
    <table class="items">
        <tr class="heading">
            <td>Type</td>
            <td>Bank #</td>
            <td>Card No</td>
            <td>Expiration Date</td>
            <td>Curr</td>
            <td>Payed Amount</td>
            <td>Exchange Rate</td>
            <td>Local Payment</td>
            <td>Date</td>
        </tr>
            @if(isset($payments))
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
            @endif
    </table>
    <br/>
    <div class="data">
        <span style="font-weight: 600; font-size: 14px">
            Thank you for shopping at Modern Optics
        </span><br/>
        <span style="font-weight: 600; font-size: 14px">
             http://modern-opt.com/
        </span><br/>
        <span style="font-weight: 600; font-size: 12px">
            Return and Exchange within 14 days from the purchase date (provided that it is in the original condition and presented in the original
            packaging with the invoice with no lenses fitted on optical frames) / Please ensure you collect your goods within 30 Days
            Please retain your receipt. It is your proof of purchase and warranty
            To share your opinion and help us serve you better, contact us on our toll free number 00800100208 working hours from 11 am to 11
            pm Qatar time
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
