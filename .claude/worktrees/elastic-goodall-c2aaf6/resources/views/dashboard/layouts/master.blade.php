<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ Settings::get('system_name') }}
        @yield('title')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    @include('dashboard.layouts.frontend.style')

    <style>
        /* ═══════════════════════════════════════════════════════════
           🔥 COMPLETE LAYOUT WITH COLLAPSE FEATURE
           ═══════════════════════════════════════════════════════════ */

        body, body.skin-black {
            margin: 0 !important;
            padding: 0 !important;
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
            font-size: 14px !important;
            line-height: 1.42857143 !important;
            color: #333 !important;
            background-color: #ecf0f5 !important;
            overflow-x: hidden !important;
        }

        .wrapper {
            display: block !important;
            width: 100% !important;
            min-height: 100vh !important;
        }

        /* ═══════════════════════════════════════════════════════════
           SIDEBAR - WITH COLLAPSE ANIMATION
           ═══════════════════════════════════════════════════════════ */

        .left-side,
        .left-side.sidebar-offcanvas,
        aside.left-side {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 250px !important;
            height: 100vh !important;
            background: linear-gradient(180deg, #1a1f3a 0%, #0f1419 100%) !important;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.3) !important;
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
        }

        /* Desktop - Normal behavior */
        @media (min-width: 992px) {
            .left-side,
            .left-side.sidebar-offcanvas,
            aside.left-side {
                transform: translateX(0) !important;
            }

            /* Collapsed state - Desktop only */
            body.sidebar-collapsed .left-side,
            body.sidebar-collapsed .left-side.sidebar-offcanvas,
            body.sidebar-collapsed aside.left-side {
                width: 70px !important;
            }

            /* Hide text when collapsed */
            body.sidebar-collapsed .sidebar-menu > li > a > span,
            body.sidebar-collapsed .sidebar-category,
            body.sidebar-collapsed .sidebar-divider,
            body.sidebar-collapsed .pull-right,
            body.sidebar-collapsed .treeview-menu {
                display: none !important;
            }

            body.sidebar-collapsed .sidebar-logo img {
                max-width: 40px !important;
            }

            body.sidebar-collapsed .sidebar-menu > li > a > i:first-child {
                margin-right: 0 !important;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           📱 MOBILE - FORCE HIDE/SHOW WITH MAXIMUM PRIORITY
           ═══════════════════════════════════════════════════════════ */

        @media (max-width: 991px) {
            /* FORCE HIDE - Maximum specificity */
            html body .left-side,
            html body .left-side.sidebar-offcanvas,
            html body aside.left-side,
            body .left-side,
            body .left-side.sidebar-offcanvas,
            body aside.left-side,
            .left-side,
            .left-side.sidebar-offcanvas,
            aside.left-side {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                transform: translateX(-250px) !important;
            }

            /* FORCE SHOW when sidebar-open class exists - Maximum specificity */
            html body.sidebar-open .left-side,
            html body.sidebar-open .left-side.sidebar-offcanvas,
            html body.sidebar-open aside.left-side,
            body.sidebar-open .left-side,
            body.sidebar-open .left-side.sidebar-offcanvas,
            body.sidebar-open aside.left-side {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateX(0) !important;
                flex-direction: column !important;
            }

            /* Force header and content full width on mobile */
            html body .header,
            html body header.header,
            body .header,
            body header.header,
            .header,
            header.header {
                left: 0 !important;
                width: 100% !important;
            }

            html body .right-side,
            html body aside.right-side,
            body .right-side,
            body aside.right-side,
            .right-side,
            aside.right-side {
                margin-left: 0 !important;
            }

            /* Dark overlay when sidebar is open */
            html body.sidebar-open::before,
            body.sidebar-open::before {
                content: '' !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: rgba(0, 0, 0, 0.6) !important;
                z-index: 9998 !important;
            }

            /* Make toggle button bigger on mobile */
            html body .sidebar-toggle,
            body .sidebar-toggle,
            .sidebar-toggle {
                width: 50px !important;
                height: 50px !important;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           🔥 HEADER COMPLETE NUCLEAR RESET
           ═══════════════════════════════════════════════════════════ */

        /* Kill ALL header styles from AdminLTE */
        .header,
        header.header,
        body > .header,
        .main-header {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            background: none !important;
            min-height: 0 !important;
            max-height: none !important;
        }

        .header,
        header.header,
        body > .header {
            display: block !important;
            position: fixed !important;
            top: 0 !important;
            left: 250px !important;
            right: 0 !important;
            width: auto !important;
            height: 60px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
            z-index: 1000 !important;
            transition: left 0.3s ease !important;
        }

        body.sidebar-collapsed .header,
        body.sidebar-collapsed header.header {
            left: 70px !important;
        }

        .header .logo,
        header.header .logo {
            display: none !important;
        }

        /* Navbar - FORCE FLEXBOX */
        .header .navbar,
        .header nav.navbar,
        header.header .navbar,
        body > .header .navbar,
        .header .navbar-static-top {
            margin: 0 !important;
            padding: 0 20px !important;
            border: none !important;
            background: none !important;
            min-height: 0 !important;
            height: 60px !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            position: relative !important;
        }

        /* ═══════════════════════════════════════════════════════════
           TOGGLE BUTTON - LEFT SIDE
           ═══════════════════════════════════════════════════════════ */

        .sidebar-toggle,
        .navbar-btn.sidebar-toggle,
        a.sidebar-toggle,
        .header .sidebar-toggle,
        .header .navbar .sidebar-toggle,
        body > .header .navbar .sidebar-toggle {
            /* Kill all AdminLTE styles */
            position: relative !important;
            float: none !important;
            margin: 0 !important;
            padding: 10px 8px !important;
            border: none !important;
            background-color: rgba(255, 255, 255, 0.15) !important;
            background-image: none !important;

            /* Our styles */
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-around !important;
            align-items: center !important;
            width: 45px !important;
            height: 45px !important;
            border-radius: 10px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            flex-shrink: 0 !important;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            transform: scale(1.08) !important;
        }

        .sidebar-toggle .icon-bar {
            display: block !important;
            width: 24px !important;
            height: 3px !important;
            background: #ffffff !important;
            border-radius: 3px !important;
            margin: 2px 0 !important;
        }

        /* ═══════════════════════════════════════════════════════════
           USER DROPDOWN - RIGHT SIDE
           ═══════════════════════════════════════════════════════════ */

        .navbar-right,
        .header .navbar-right,
        .navbar .navbar-right {
            margin: 0 !important;
            padding: 0 !important;
            float: none !important;
            margin-left: auto !important;
            display: flex !important;
            align-items: center !important;
        }

        .navbar-right .nav,
        .navbar-right ul.nav {
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            display: flex !important;
            align-items: center !important;
        }

        .navbar-right .dropdown,
        .navbar-right li.dropdown {
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            display: block !important;
            position: relative !important;
        }

        .navbar-right .dropdown-toggle {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 8px 15px !important;
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 25px !important;
            cursor: pointer !important;
            text-decoration: none !important;
            border: none !important;
            margin: 0 !important;
        }

        .navbar-right .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .navbar-right .dropdown-menu {
            display: none !important;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            margin-top: 10px !important;
            min-width: 250px !important;
            background: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            list-style: none !important;
            padding: 0 !important;
            border: none !important;
        }

        .navbar-right .dropdown.open .dropdown-menu {
            display: block !important;
        }

        .navbar-right .user-header {
            padding: 20px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            text-align: center !important;
            color: #fff !important;
            border: none !important;
        }

        .navbar-right .user-header img {
            width: 80px !important;
            height: 80px !important;
            border-radius: 50% !important;
            border: 3px solid #fff !important;
        }

        .navbar-right .user-footer {
            display: flex !important;
            justify-content: space-between !important;
            padding: 15px 20px !important;
            border-top: 1px solid #eee !important;
        }

        .navbar-right .user-footer .btn {
            padding: 8px 20px !important;
            background: #f4f4f4 !important;
            border-radius: 6px !important;
            color: #333 !important;
            text-decoration: none !important;
            border: none !important;
            display: inline-block !important;
        }

        .navbar-right .user-footer .btn:hover {
            background: #667eea !important;
            color: #fff !important;
        }

        /* ═══════════════════════════════════════════════════════════
           CONTENT AREA - ADJUSTS WITH SIDEBAR
           ═══════════════════════════════════════════════════════════ */

        .right-side,
        aside.right-side {
            margin-left: 250px !important;
            margin-top: 60px !important;
            min-height: calc(100vh - 60px) !important;
            background-color: #ecf0f5 !important;
            transition: margin-left 0.3s ease !important;
        }

        body.sidebar-collapsed .right-side {
            margin-left: 70px !important;
        }

        .right-side .content {
            padding: 20px !important;
        }

        /* ═══════════════════════════════════════════════════════════
           MOBILE RESPONSIVE
           ═══════════════════════════════════════════════════════════ */

        @media (max-width: 991px) {
            .header,
            header.header,
            body > .header {
                left: 0 !important;
                width: 100% !important;
                z-index: 10001 !important;
            }

            .right-side,
            aside.right-side {
                margin-left: 0 !important;
            }

            .left-side,
            .left-side.sidebar-offcanvas,
            aside.left-side {
                transform: translateX(-250px) !important;
            }

            body.sidebar-open .left-side,
            body.sidebar-open .left-side.sidebar-offcanvas,
            body.sidebar-open aside.left-side {
                transform: translateX(0) !important;
            }

            /* Force full width on mobile when collapsed */
            body.sidebar-collapsed .left-side,
            body.sidebar-collapsed .left-side.sidebar-offcanvas,
            body.sidebar-collapsed aside.left-side {
                width: 250px !important;
            }

            body.sidebar-open::before {
                content: '' !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: rgba(0, 0, 0, 0.6) !important;
                z-index: 9998 !important;
                animation: fadeInOverlay 0.3s ease !important;
            }

            @keyframes fadeInOverlay {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .sidebar-toggle,
            .navbar-btn.sidebar-toggle,
            a.sidebar-toggle {
                width: 50px !important;
                height: 50px !important;
                background: rgba(255, 255, 255, 0.25) !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2) !important;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           🎯 MODAL FIX - APPEAR ABOVE SIDEBAR
           ═══════════════════════════════════════════════════════════ */

        .modal,
        .modal-dialog,
        .modal-content,
        .modal-backdrop {
            z-index: 99999 !important;
        }

        .modal-backdrop {
            z-index: 99998 !important;
        }

        /* Ensure modals are always on top */
        .modal.fade.in {
            z-index: 99999 !important;
        }

        .modal-open {
            overflow: hidden !important;
        }
    </style>
</head>
<body class="skin-black">

<header class="header">
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="javascript:void(0);"
           class="sidebar-toggle"
           onclick="toggleSidebarCollapse(); return false;">
            <span class="sr-only">Toggle</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                {{-- Notifications --}}
                <li>
                    @include('dashboard.partials.notifications')
                </li>


                <li class="dropdown user user-menu" id="userDropdown">
                    <a href="javascript:void(0);"
                       class="dropdown-toggle"
                       onclick="toggleUserDropdown(); return false;">
                        <i class="glyphicon glyphicon-user"></i>
                        <span>{{ auth()->user()->first_name . ' ' . auth()->user()->last_name  }} <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{url('/storage/uploads/images/users/') .'/' . auth()->user()->image}}" alt="User" />
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('dashboard.profile.show') }}" class="btn">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('dashboard.logout') }}" class="btn">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper">
    <aside class="left-side sidebar-offcanvas">
        @include('dashboard.layouts.sidebar')
    </aside>

    <aside class="right-side">
        <section class="content">
            @yield('content')
            @include('dashboard.partials._session')
        </section>
    </aside>
</div>

@include('dashboard.partials._Spinner')
@include('dashboard.layouts.frontend.script')

<script>
    // Simple mobile check
    function isMobile() {
        return window.innerWidth <= 991;
    }

    // Simple toggle - just add/remove class!
    function toggleSidebarCollapse() {
        var body = document.body;

        if (isMobile()) {
            // Mobile: just toggle sidebar-open class
            body.classList.toggle('sidebar-open');
            console.log('📱 Mobile Toggle - Sidebar:', body.classList.contains('sidebar-open') ? 'OPEN' : 'CLOSED');
        } else {
            // Desktop: toggle sidebar-collapsed class
            body.classList.toggle('sidebar-collapsed');
            console.log('🖥️ Desktop Toggle - Sidebar:', body.classList.contains('sidebar-collapsed') ? 'COLLAPSED' : 'EXPANDED');
        }
    }

    // User dropdown
    function toggleUserDropdown() {
        document.getElementById('userDropdown').classList.toggle('open');
    }

    // Close sidebar when clicking outside (mobile only)
    document.addEventListener('click', function(e) {
        if (isMobile() && document.body.classList.contains('sidebar-open')) {
            if (!e.target.closest('.left-side') && !e.target.closest('.sidebar-toggle')) {
                document.body.classList.remove('sidebar-open');
                console.log('🚪 Sidebar closed - clicked outside');
            }
        }

        // Close dropdown
        if (!e.target.closest('#userDropdown')) {
            document.getElementById('userDropdown').classList.remove('open');
        }
    });

    console.log('✅ Ready! Mode:', isMobile() ? 'Mobile' : 'Desktop');
</script>

</body>
</html>
