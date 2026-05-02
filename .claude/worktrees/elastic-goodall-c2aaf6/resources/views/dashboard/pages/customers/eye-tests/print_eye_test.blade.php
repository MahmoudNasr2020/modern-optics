<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="{{ Files::getUrl(Settings::get('system_icon')) }}">
    <title>{{ Settings::get('system_name') }} | اختبار نظر</title>

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

        .eye-test-box {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background: url('{{ asset('assets/img/background-head.png') }}') center center no-repeat;
            background-size: contain; /* أو cover حسب ما تحب */
            background-color: transparent;
        }



        #invoicePrintButton {
            margin-right: 2px;

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

        table.eye-test {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin-bottom: 30px;
        }

        table.eye-test th,
        table.eye-test td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table.eye-test th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        table.eye-test tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .remarks-row td {
            text-align: left;
            font-weight: bold;
            background-color: #fffbe6;
        }

        .section-header th {
            background-color: #d9edf7;
            font-size: 16px;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0 15px;
        }

        .custom-print-btn {

            color: #fff;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-radius: 25px; /* زرار دائري شوية */
            padding: 10px 25px;
           /* box-shadow: 0 4px 6px rgba(0,0,0,0.1);*/
            transition: all 0.3s ease;
        }

        .custom-print-btn:hover {
            background-color: #4cae4c;
            border-color: #398439;
            transform: translateY(-1px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .custom-print-btn:active {
            background-color: #449d44;
            border-color: #398439;
            transform: translateY(1px);
            box-shadow: 0 3px 4px rgba(0,0,0,0.1) inset;
        }


    </style>


</head>

<body>
<h1>Eye Test - اختبار نظر</h1><br/>
<div class="eye-test-box">

    <img src="{{ Files::getUrl(Settings::get('system_logo')) }}" alt="" style="width: 350px;">
    {{--<h1>Modern Topics</h1><br>--}}
    <div style="">
        <div style="text-align: left">
            <h4>Customer Name: {{ $eyeTest->customer->english_name }}</h4>
            <h5>Date: {{ $eyeTest->created_at->format('Y-m-d') }}</h5>
        </div>
        <!-- العنوان -->
        <div class="title">EYE TEST</div>

        <!-- جدول البيانات -->
        <table class="eye-test">
            <tr class="section-header">
                <th>Eyes</th>
                <th colspan="3">Right Eye</th>
                <th colspan="3">Left Eye</th>
            </tr>
            <tr>
                <th>Prescription</th>
                <th>Sphere</th>
                <th>Cylinder</th>
                <th>Axis</th>
                <th>Sphere</th>
                <th>Cylinder</th>
                <th>Axis</th>
            </tr>
            <tr>

                <td><strong>Distance</strong></td>
                <td>
                    {{ $eyeTest->sph_right_sign }} {{ $eyeTest->sph_right_value }}
                </td>
                <td>
                    {{ $eyeTest->cyl_right_sign }}{{ $eyeTest->cyl_right_value }}
                </td>
                <td>
                    {{ $eyeTest->axis_right }}
                </td>
                <td> {{ $eyeTest->sph_left_sign }} {{ $eyeTest->sph_left_value }}</td>
                <td>
                    {{ $eyeTest->cyl_left_sign }}{{ $eyeTest->cyl_left_value }}
                </td>
                <td>
                    {{ $eyeTest->axis_left }}
                </td>
            </tr>
            <tr>
                <td><strong>ADD</strong></td>
                <td>
                    {{ $eyeTest->addition_right }}
                </td>
                <td></td>
                <td></td>
                <td>{{ $eyeTest->addition_left }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="remarks-row">
                <td colspan="7" style="text-align: left">
                    Remarks:<br>
                    IPD: {{ $eyeTest->pd_right + $eyeTest->pd_left  }}  MM
                </td>
            </tr>
        </table>


        <div style="text-align: center; color: #9b7353;  font-size: 14px; margin-top: 20px;">
            <span style="margin-right: 20px;">
                <i class="fa fa-phone" style="margin-right: 5px;"></i>
                {{ Settings::get('eye_test_phone') }}
            </span>
            <span style="margin-right: 20px;">
               <i class="fa fa-envelope" style="margin-right: 5px;"></i>  {{ Settings::get('eye_test_email') }}
            </span>
            <span>
                 <i class="fa fa-map-marker" style="margin-right: 5px;"></i>
                 {{ Settings::get('eye_test_address') }}
            </span>
        </div><br>
        <div class="text-center">
    <span style="font-weight:600; font-size:14px; white-space:pre-line;">
        {{ Settings::get('eye_test_footer') }}
    </span><br>
        </div>



        <br>
        <button type="button" id="invoicePrintButton" onclick="printpage()" class="btn btn-primary btn-lg custom-print-btn">
            <span class="glyphicon glyphicon-print"></span> Print Invoice
        </button>

    </div>

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
