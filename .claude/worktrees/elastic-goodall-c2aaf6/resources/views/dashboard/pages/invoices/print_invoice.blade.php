

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <title>PDF</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <meta charset="utf-8">
    <style>

        html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            margin: 0;
        }
    </style>

</head>
<body>

<header>
</header>
<div class="form-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 data-section">
                <p>Invoice No :</p>
            </div>
        </div>
        <div class="row">
            <table class="table table-hover table-content">
                <thead>
                <tr>
                    <th>NO</th>
                    <th>Item #</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Net</th>
                    <th>Tax</th>
                    <th>Total</th>
                    <th>Stock</th>
                    <th>Ready</th>
                    <th>Deliver</th>
                </tr>
                </thead>

                <tbody>
                @foreach($invoiceItems as $key => $invoice)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$invoice->product_id}}</td>
                        <td>-</td>
                        <td>{{$invoice->quantity}}</td>
                        <td>{{$invoice->price}}</td>
                        <td>{{$invoice->net}}</td>
                        <td>{{$invoice->tax}}</td>
                        <td>{{$invoice->total}}</td>
                        <td>0</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <button type="button" id="view" onClick="window.print()" class="btn btn-info prints"
                style="margin-right: 488px;width: 100px;height: 40px">print</button>
    </div>
</div>

</body>

</html>


