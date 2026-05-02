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
            left: 260px !important;
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
            margin-left: 260px !important;
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

@if(Settings::get('show_spinner', '1') !== '0')
@include('dashboard.partials._Spinner')
@endif

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


                {{-- ══ User Dropdown ══ --}}
                <li class="ud-wrap" id="userDropdown">

                    {{-- Trigger Button --}}
                    <a href="javascript:void(0);" class="ud-trigger" onclick="toggleUserDropdown(); return false;">
                        @php
                            $udUser  = auth()->user();
                            $udImg   = optional($udUser)->image;
                            $udSrc   = ($udImg && $udImg !== 'default.jpg' && $udImg !== 'default.png')
                                       ? url('/storage/uploads/images/users/' . $udImg)
                                       : null;
                            $udInit  = $udUser ? strtoupper(substr($udUser->first_name ?? 'U', 0, 1)) : 'U';
                            $udName  = trim(optional($udUser)->first_name . ' ' . optional($udUser)->last_name);
                            $udEmail = optional($udUser)->email;
                            $udRole  = optional($udUser->roles->first())->display_name ?? optional($udUser->roles->first())->name ?? 'User';
                            $udBranch= optional(optional($udUser)->branch)->name ?? null;
                        @endphp

                        @if($udSrc)
                            <img src="{{ $udSrc }}" class="ud-avatar-img" alt="{{ $udName }}">
                        @else
                            <span class="ud-avatar-init">{{ $udInit }}</span>
                        @endif

                        <span class="ud-name-text">{{ $udName }}</span>
                        <i class="bi bi-chevron-down ud-caret"></i>
                    </a>

                    {{-- Dropdown Panel — style="display:none" prevents FOUC before body CSS applies --}}
                    <div class="ud-panel" id="udPanel" style="display:none;">

                        {{-- Header --}}
                        <div class="ud-head">
                            <div class="ud-head-bg"></div>
                            <div class="ud-head-content">
                                @if($udSrc)
                                    <img src="{{ $udSrc }}" class="ud-big-avatar" alt="{{ $udName }}">
                                @else
                                    <div class="ud-big-init">{{ $udInit }}</div>
                                @endif
                                <div class="ud-head-info">
                                    <div class="ud-head-name">{{ $udName }}</div>
                                    <div class="ud-head-email">{{ $udEmail }}</div>
                                    <div class="ud-head-badges">
                                        <span class="ud-badge ud-badge-role">
                                            <i class="bi bi-shield-fill"></i> {{ $udRole }}
                                        </span>
                                        @if($udBranch)
                                            <span class="ud-badge ud-badge-branch">
                                                <i class="bi bi-building"></i> {{ $udBranch }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="ud-stats">
                            <div class="ud-stat-item">
                                <i class="bi bi-calendar-check" style="color:#6366f1;"></i>
                                <div>
                                    <span class="ud-stat-val">{{ \Carbon\Carbon::now()->format('d M') }}</span>
                                    <span class="ud-stat-lbl">اليوم</span>
                                </div>
                            </div>
                            <div class="ud-stat-sep"></div>
                            <div class="ud-stat-item">
                                <i class="bi bi-clock-history" style="color:#10b981;"></i>
                                <div>
                                    <span class="ud-stat-val">{{ \Carbon\Carbon::now()->format('h:i A') }}</span>
                                    <span class="ud-stat-lbl">الوقت الحالي</span>
                                </div>
                            </div>
                            <div class="ud-stat-sep"></div>
                            <div class="ud-stat-item">
                                <i class="bi bi-person-check" style="color:#f5a623;"></i>
                                <div>
                                    <span class="ud-stat-val">نشط</span>
                                    <span class="ud-stat-lbl">الحالة</span>
                                </div>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <div class="ud-menu">
                            <a href="{{ route('dashboard.profile.show') }}" class="ud-item">
                                <span class="ud-item-icon" style="background:rgba(99,102,241,.12);color:#6366f1;">
                                    <i class="bi bi-person-circle"></i>
                                </span>
                                <span class="ud-item-text">
                                    <span class="ud-item-title">الملف الشخصي</span>
                                    <span class="ud-item-sub">عرض وتعديل بياناتك</span>
                                </span>
                                <i class="bi bi-chevron-left ud-item-arrow"></i>
                            </a>

                            @can('view-settings')
                            <a href="{{ route('dashboard.settings.index') }}" class="ud-item">
                                <span class="ud-item-icon" style="background:rgba(16,185,129,.12);color:#10b981;">
                                    <i class="bi bi-gear-fill"></i>
                                </span>
                                <span class="ud-item-text">
                                    <span class="ud-item-title">إعدادات النظام</span>
                                    <span class="ud-item-sub">تخصيص واجهة النظام</span>
                                </span>
                                <i class="bi bi-chevron-left ud-item-arrow"></i>
                            </a>
                            @endcan

                            @can('view-ai-assistant')
                            <a href="{{ route('dashboard.assistant.index') }}" class="ud-item">
                                <span class="ud-item-icon" style="background:rgba(139,92,246,.12);color:#8b5cf6;">
                                    <i class="bi bi-robot"></i>
                                </span>
                                <span class="ud-item-text">
                                    <span class="ud-item-title">المساعد الذكي</span>
                                    <span class="ud-item-sub">استفسر عن أي بيانات</span>
                                </span>
                                <i class="bi bi-chevron-left ud-item-arrow"></i>
                            </a>
                            @endcan
                        </div>

                        {{-- Logout --}}
                        <div class="ud-footer">
                            <a href="{{ route('dashboard.logout') }}" class="ud-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                تسجيل الخروج
                            </a>
                        </div>

                    </div>{{-- /ud-panel --}}
                </li>{{-- /ud-wrap --}}
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
        var wrap  = document.getElementById('userDropdown');
        var panel = document.getElementById('udPanel');
        wrap.classList.toggle('open');
        panel.style.display = wrap.classList.contains('open') ? 'block' : 'none';
    }

    // Close user dropdown when clicking outside
    document.addEventListener('click', function(e) {
        var ud = document.getElementById('userDropdown');
        if (ud && !ud.contains(e.target)) {
            ud.classList.remove('open');
            var panel = document.getElementById('udPanel');
            if (panel) panel.style.display = 'none';
        }
    });

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

@if(Settings::get('show_chatbot', '1') !== '0')
{{-- ══════════════════════════════════════════════════
     🤖 FLOATING AI ASSISTANT BUTTON
     ══════════════════════════════════════════════════ --}}
<style>
/* ── Floating button ── */
#aiFloatBtn {
    position: fixed; bottom: 26px; right: 26px; z-index: 99999;
    width: 54px; height: 54px; border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: #fff; font-size: 22px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 20px rgba(99,102,241,0.5); transition: transform .2s, box-shadow .2s;
}
#aiFloatBtn:hover { transform: scale(1.1); box-shadow: 0 8px 28px rgba(99,102,241,0.65); }
#aiFloatBtn .ai-badge {
    position: absolute; top: -3px; right: -3px; width: 14px; height: 14px;
    background: #22c55e; border-radius: 50%; border: 2px solid #fff;
}

/* ══════════════════════════════════════
   USER DROPDOWN — PREMIUM DESIGN
══════════════════════════════════════ */

/* Wrapper */
.ud-wrap {
    position: relative;
    list-style: none;
}

/* Trigger button */
.ud-trigger {
    display: flex !important;
    align-items: center;
    gap: 9px;
    padding: 6px 14px 6px 10px !important;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 50px;
    cursor: pointer;
    text-decoration: none !important;
    transition: all .25s;
    color: #fff !important;
    height: 44px;
}
.ud-trigger:hover {
    background: rgba(255,255,255,.15);
    border-color: rgba(255,255,255,.25);
}

/* Avatar circle or initials */
.ud-avatar-img {
    width: 30px; height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,.3);
    flex-shrink: 0;
}
.ud-avatar-init {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #fff;
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.3);
}
.ud-name-text {
    font-size: 13px; font-weight: 600;
    max-width: 120px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ud-caret {
    font-size: 11px; opacity: .7;
    transition: transform .25s;
}
.ud-wrap.open .ud-caret {
    transform: rotate(180deg);
}

/* ── PANEL ── */
.ud-panel {
    display: none;
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 300px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,.18), 0 4px 16px rgba(0,0,0,.1);
    overflow: hidden;
    z-index: 99999;
    animation: udSlideIn .22s ease-out;
    border: 1px solid rgba(0,0,0,.06);
}
.ud-wrap.open .ud-panel { display: block; }

@keyframes udSlideIn {
    from { opacity:0; transform:translateY(-10px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}

/* ── HEAD ── */
.ud-head {
    position: relative;
    padding: 28px 20px 20px;
    overflow: hidden;
}
.ud-head-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
}
.ud-head-bg::after {
    content: '';
    position: absolute; top: -40px; right: -40px;
    width: 150px; height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.ud-head-content {
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 14px;
}
.ud-big-avatar {
    width: 58px; height: 58px;
    border-radius: 50%; object-fit: cover;
    border: 3px solid rgba(255,255,255,.3);
    box-shadow: 0 4px 16px rgba(0,0,0,.3);
    flex-shrink: 0;
}
.ud-big-init {
    width: 58px; height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 900; color: #fff;
    border: 3px solid rgba(255,255,255,.25);
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
    flex-shrink: 0;
}
.ud-head-name {
    font-size: 15px; font-weight: 800;
    color: #fff; line-height: 1.3;
}
.ud-head-email {
    font-size: 12px; color: rgba(255,255,255,.65);
    margin: 3px 0 8px;
    word-break: break-all;
}
.ud-head-badges { display: flex; flex-wrap: wrap; gap: 5px; }
.ud-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 50px;
    font-size: 11px; font-weight: 700;
}
.ud-badge-role   { background: rgba(99,102,241,.25); color: #c7d2fe; }
.ud-badge-branch { background: rgba(245,166,35,.2);  color: #fde68a; }

/* ── STATS ── */
.ud-stats {
    display: flex; align-items: center;
    padding: 14px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}
.ud-stat-item {
    flex: 1; display: flex; align-items: center; gap: 8px;
}
.ud-stat-item i { font-size: 18px; }
.ud-stat-val { display: block; font-size: 13px; font-weight: 800; color: #1e293b; line-height: 1.2; }
.ud-stat-lbl { display: block; font-size: 10px; color: #94a3b8; font-weight: 600; }
.ud-stat-sep {
    width: 1px; height: 30px;
    background: #e2e8f0; flex-shrink: 0; margin: 0 4px;
}

/* ── MENU ITEMS ── */
.ud-menu {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f5f9;
}
.ud-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 10px;
    border-radius: 12px;
    text-decoration: none !important;
    transition: background .18s;
    cursor: pointer;
}
.ud-item:hover { background: #f8fafc; }
.ud-item-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
}
.ud-item-text { flex: 1; }
.ud-item-title { display: block; font-size: 13px; font-weight: 700; color: #1e293b; }
.ud-item-sub   { display: block; font-size: 11px; color: #94a3b8; margin-top: 1px; }
.ud-item-arrow { font-size: 12px; color: #cbd5e1; }

/* ── FOOTER / LOGOUT ── */
.ud-footer { padding: 10px 10px 12px; }
.ud-logout {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 11px;
    background: linear-gradient(135deg,#fee2e2,#fef2f2);
    border: 1px solid #fecaca;
    border-radius: 12px;
    color: #dc2626 !important; font-size: 14px; font-weight: 700;
    text-decoration: none !important;
    transition: all .2s;
}
.ud-logout:hover {
    background: linear-gradient(135deg,#dc2626,#ef4444);
    color: #fff !important;
    border-color: #dc2626;
    box-shadow: 0 4px 14px rgba(220,38,38,.35);
}
.ud-logout i { font-size: 16px; }

/* ── Floating chat panel ── */
#aiPanel {
    position: fixed; bottom: 92px; right: 26px; z-index: 99998;
    width: 360px; height: 480px;
    background: #fff; border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    display: none; flex-direction: column; overflow: hidden;
    animation: panelSlideUp .25s ease;
}
@keyframes panelSlideUp {
    from { opacity:0; transform:translateY(16px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
#aiPanel .fp-header {
    background: linear-gradient(135deg,#1e1b4b,#312e81);
    color:#fff; padding:14px 16px; display:flex; align-items:center; gap:10px;
    flex-shrink:0;
}
#aiPanel .fp-header .fp-icon {
    width:36px; height:36px; background:rgba(255,255,255,.15);
    border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px;
}
#aiPanel .fp-header h4 { margin:0; font-size:15px; font-weight:700; }
#aiPanel .fp-header .fp-title-wrap { flex:1; }
#aiPanel .fp-header small { opacity:.7; font-size:11px; display:block; margin-top:2px; }
.fp-close-btn {
    background:rgba(255,255,255,.15); border:none; color:#fff; width:28px; height:28px;
    border-radius:8px; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center;
    transition:background .15s;
}
.fp-close-btn:hover { background:rgba(255,255,255,.3); }

#fpMessages {
    flex:1; overflow-y:auto; padding:14px; background:#f0f2fc;
    display:flex; flex-direction:column; gap:10px; scroll-behavior:smooth;
}
#fpMessages::-webkit-scrollbar { width:4px; }
#fpMessages::-webkit-scrollbar-thumb { background:rgba(99,102,241,.3); border-radius:4px; }

.fp-msg { display:flex; gap:8px; max-width:90%; }
.fp-msg.user { align-self:flex-end; flex-direction:row-reverse; }
.fp-msg.bot  { align-self:flex-start; }
.fp-avatar {
    width:28px; height:28px; border-radius:50%; font-size:12px; font-weight:700;
    display:flex; align-items:center; justify-content:center; flex-shrink:0; align-self:flex-end;
}
.fp-msg.bot  .fp-avatar { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; font-size:13px; }
.fp-msg.user .fp-avatar { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
.fp-bubble {
    padding:9px 13px; border-radius:13px; font-size:12.5px; line-height:1.55;
    white-space:pre-wrap; word-break:break-word;
    box-shadow:0 1px 4px rgba(0,0,0,.07);
}
.fp-msg.bot  .fp-bubble { background:#fff; color:#1e293b; border-bottom-left-radius:3px; }
.fp-msg.user .fp-bubble { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border-bottom-right-radius:3px; }

.fp-input-bar {
    display:flex; gap:8px; padding:10px 12px; background:#fff;
    border-top:1px solid #e8ecf7; flex-shrink:0;
}
#fpInput {
    flex:1; border:1.5px solid #e0e6ed; border-radius:20px; padding:8px 14px;
    font-size:13px; outline:none; font-family:inherit;
    transition:border-color .15s;
}
#fpInput:focus { border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,.12); }
.fp-send-btn {
    width:36px; height:36px; border-radius:50%; border:none; cursor:pointer;
    background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; font-size:14px;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 2px 8px rgba(99,102,241,.4); transition:transform .15s;
}
.fp-send-btn:hover { transform:scale(1.1); }

.fp-suggs {
    display:flex; gap:6px; padding:8px 12px 4px; flex-wrap:wrap; background:#fff;
    border-top:1px solid #f1f3f9; flex-shrink:0;
}
.fp-sugg {
    background:#f1f5fe; border:none; border-radius:14px; padding:4px 10px;
    font-size:11px; color:#4f46e5; cursor:pointer; font-weight:500; transition:background .15s;
}
.fp-sugg:hover { background:#e0e7ff; }
@keyframes typingAnim { 0%,60%,100%{transform:translateY(0);} 30%{transform:translateY(-5px);} }
</style>

{{-- Floating Button --}}
<button id="aiFloatBtn" onclick="toggleAiPanel()" title="AI Assistant">
    <i class="bi bi-robot"></i>
    <div class="ai-badge"></div>
</button>

{{-- Floating Panel --}}
<div id="aiPanel">
    <div class="fp-header">
        <div class="fp-icon"><i class="bi bi-robot"></i></div>
        <div class="fp-title-wrap">
            <h4>المساعد الذكي</h4>
            <small>اسأل عن مبيعاتك الآن</small>
        </div>
        <button class="fp-close-btn" onclick="toggleAiPanel()" title="إغلاق" style="margin-right:0;margin-left:auto;flex-shrink:0;"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="fp-suggs">
        <button class="fp-sugg" onclick="fpSend('مبيعات النهارده')">📊 اليوم</button>
        <button class="fp-sugg" onclick="fpSend('مبيعات الاسبوع ده')">📅 الأسبوع</button>
        <button class="fp-sugg" onclick="fpSend('مقارنة الفروع')">🏢 الفروع</button>
        <button class="fp-sugg" onclick="fpSend('فواتير معلقة')">⏳ معلقة</button>
        <button class="fp-sugg" onclick="fpSend('مساعدة')">❓</button>
    </div>
    <div id="fpMessages">
        <div class="fp-msg bot">
            <div class="fp-avatar">🤖</div>
            <div class="fp-bubble">👋 أهلاً! اسألني عن المبيعات، الفروع، العدسات، أو الفواتير.</div>
        </div>
    </div>
    <div class="fp-input-bar">
        <input type="text" id="fpInput" placeholder="اكتب سؤالك..." onkeydown="fpKey(event)">
        <button class="fp-send-btn" onclick="fpSendMsg()"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<script>
var _fpOpen = false;
var _fpCsrf = '{{ csrf_token() }}';
var _fpUrl  = '{{ route("dashboard.assistant.query") }}';
var _fpUser = '{{ auth()->check() ? strtoupper(substr(auth()->user()->first_name, 0, 1)) : "U" }}';

function toggleAiPanel() {
    _fpOpen = !_fpOpen;
    var panel = document.getElementById('aiPanel');
    panel.style.display = _fpOpen ? 'flex' : 'none';
    if (_fpOpen) { setTimeout(function(){ document.getElementById('fpInput').focus(); }, 100); }
}

function fpKey(e) { if (e.key === 'Enter') fpSendMsg(); }
function fpSend(text) { document.getElementById('fpInput').value = text; fpSendMsg(); }

function fpSendMsg() {
    var input = document.getElementById('fpInput');
    var text  = input.value.trim();
    if (!text) return;
    fpAddBubble('user', text);
    input.value = '';

    var tid = fpTyping();
    fetch(_fpUrl, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':_fpCsrf},
        body:JSON.stringify({message:text})
    })
    .then(function(r){ return r.json(); })
    .then(function(resp){
        fpRemoveTyping(tid);
        fpAddBubble('bot', resp.text || 'لم أفهم السؤال.');
    })
    .catch(function(){
        fpRemoveTyping(tid);
        fpAddBubble('bot','⚠️ حدث خطأ. حاول مرة أخرى.');
    });
}

function fpAddBubble(type, text) {
    var c = document.getElementById('fpMessages');
    var div = document.createElement('div');
    div.className = 'fp-msg ' + type;
    var avatar = type === 'bot' ? '🤖' : _fpUser;
    var fmtText = text.replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>').replace(/\n/g,'<br>');
    div.innerHTML = '<div class="fp-avatar">' + avatar + '</div><div class="fp-bubble">' + fmtText + '</div>';
    c.appendChild(div);
    c.scrollTop = c.scrollHeight;
}

function fpTyping() {
    var c = document.getElementById('fpMessages');
    var div = document.createElement('div');
    div.className = 'fp-msg bot'; div.id = 'fp-typing';
    div.innerHTML = '<div class="fp-avatar">🤖</div><div class="fp-bubble" style="padding:10px 14px;"><span style="display:inline-flex;gap:4px;"><span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingAnim 1.2s infinite;"></span><span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingAnim 1.2s .2s infinite;"></span><span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingAnim 1.2s .4s infinite;"></span></span></div>';
    c.appendChild(div);
    c.scrollTop = c.scrollHeight;
    return 'fp-typing';
}
function fpRemoveTyping(id) { var el = document.getElementById(id); if (el) el.remove(); }
</script>
@endif

</body>
</html>
