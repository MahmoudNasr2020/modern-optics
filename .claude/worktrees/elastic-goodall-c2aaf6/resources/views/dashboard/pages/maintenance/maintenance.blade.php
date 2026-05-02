<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We’ll Be Back Soon!</title>
    <link rel="shortcut icon" href="{{ Files::getUrl(Settings::get('system_icon')) }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #2f3640;
        }
        .container {
            max-width: 600px;
            padding: 20px;
        }
        .container img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .footer {
            font-size: 0.9rem;
            color: #718093;
            margin-top: 30px;
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 1.8rem;
            }
            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <img src="{{ asset('assets/img/maintenance.jpg') }}" alt="Maintenance">
    <h1 style="color: darkorange">We’ll Be Back Soon!</h1>
    <p>
        Our System is currently undergoing scheduled maintenance.<br>
        We’re working hard to bring everything back online as soon as possible.
    </p>

    <p style=" font-size:1.1rem; direction:rtl; text-align:center;font-weight: bold">
        نظامنا حاليًا يخضع لعملية صيانة مجدولة.<br>
        نعمل بجد لإعادة كل شيء للعمل في أقرب وقت ممكن.
    </p>

    <div class="footer">
        &copy; {{ date('Y') }} {{ Settings::get('system_name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
