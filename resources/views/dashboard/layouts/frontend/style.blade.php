<!-- bootstrap select css -->
<link href="{{asset('assets/js/plugins/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css')}}"
      rel="stylesheet" type="text/css"/>
<!-- bootstrap 3.0.2 -->
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- font Awesome -->
<link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/css/bootstrap-icons/bootstrap-icons.min.css')}}" rel="stylesheet">

<!-- Ionicons -->
<link href="{{asset('assets/css/ionicons.min.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('assets/css/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css"/>
<!-- fullCalendar -->
<link href="{{asset('assets/css/fullcalendar/fullcalendar.css')}}" rel="stylesheet" type="text/css"/>
<!-- Daterange picker -->
<link href="{{asset('assets/css/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css"/>
<!-- bootstrap wysihtml5 - text editor -->
<link href="{{asset('assets/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet"
      type="text/css"/>
<!-- NOTY -->
<link href="{{asset('assets/css/noty.css')}}" rel="stylesheet" type="text/css"/>
<!-- Theme style -->
<link href="{{asset('assets/css/AdminLTE.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" type="text/css"/>

<link rel="shortcut icon" href="{{ Files::getUrl(Settings::get('system_icon')) }}">

<link href="{{asset('assets/css/select2/select2.min.css')}}" rel="stylesheet" />

<style>
    .swal2-popup {
        width: 450px !important;
        font-size: 16px;
    }

    .swal2-confirm {
        margin-right: 5px;
    }

</style>

<style>
    /* ================================================
Modern Pagination Styles
================================================ */

    /* Container */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin: 30px 0;
        padding: 0;
        list-style: none;
        flex-wrap: wrap;
    }

    /* Page Items */
    .pagination .page-item {
        list-style: none;
        margin: 0;
    }

    /* Page Links - Default State */
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        background: white;
        border: 2px solid #e0e6ed;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }

    /* Hover Effect */
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        border-color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    /* Active Page */
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        border-color: #3498db;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        cursor: default;
        transform: scale(1.05);
    }

    /* Disabled State */
    .pagination .page-item.disabled .page-link {
        background: #f8f9fa;
        color: #ccc;
        border-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.6;
        box-shadow: none;
    }

    /* Previous & Next Buttons */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 18px;
        font-weight: 700;
        min-width: 45px;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border-width: 2px;
    }

    .pagination .page-item:first-child:not(.disabled) .page-link:hover,
    .pagination .page-item:last-child:not(.disabled) .page-link:hover {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        border-color: #27ae60;
        color: white;
    }

    /* Focus State */
    .pagination .page-link:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.2);
    }

    /* Animation */
    @keyframes pageEnter {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .pagination .page-item {
        animation: pageEnter 0.3s ease-out;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .pagination {
            gap: 5px;
        }

        .pagination .page-link {
            min-width: 38px;
            height: 38px;
            padding: 6px 10px;
            font-size: 13px;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            min-width: 40px;
            font-size: 16px;
        }
    }

    /* ================================================
       Alternative Style - Rounded Pills
       ================================================ */

    .pagination.pagination-pills .page-link {
        border-radius: 25px;
        min-width: 40px;
        height: 40px;
    }

    /* ================================================
       Alternative Style - Flat Modern
       ================================================ */

    .pagination.pagination-flat .page-link {
        border: none;
        border-radius: 8px;
        background: #f1f3f5;
        box-shadow: none;
    }

    .pagination.pagination-flat .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #3498db;
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.25);
    }

    .pagination.pagination-flat .page-item.active .page-link {
        background: #3498db;
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
    }

    /* ================================================
       Alternative Style - Gradient Glow
       ================================================ */

    .pagination.pagination-glow .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4),
        0 0 20px rgba(118, 75, 162, 0.3);
    }

    .pagination.pagination-glow .page-item:not(.active):not(.disabled) .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* ================================================
       Dark Theme
       ================================================ */

    .pagination.pagination-dark .page-link {
        background: #2c3e50;
        border-color: #34495e;
        color: #ecf0f1;
    }

    .pagination.pagination-dark .page-item:not(.active):not(.disabled) .page-link:hover {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border-color: #3498db;
    }

    .pagination.pagination-dark .page-item.active .page-link {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border-color: #e74c3c;
    }

    .pagination.pagination-dark .page-item.disabled .page-link {
        background: #1a252f;
        border-color: #2c3e50;
        color: #7f8c8d;
    }

    /* ================================================
       Pagination Info Text (Optional)
       ================================================ */

    .pagination-info {
        text-align: center;
        margin-top: 15px;
        color: #666;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination-info strong {
        color: #3498db;
        font-weight: 700;
    }

    /* ================================================
       Wrapper Container (Optional)
       ================================================ */

    .pagination-wrapper {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin: 20px 0;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }
</style>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<scripts src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></scripts>
<scripts src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></scripts>
<![endif]-->

@yield('styles')
