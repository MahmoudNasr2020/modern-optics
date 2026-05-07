<!-- ✨ PREMIUM SIDEBAR v3 ✨ -->
<aside class="left-side sidebar-offcanvas">
    <style>
        /* ══════════════════════════════════════════════════════
           PREMIUM SIDEBAR — Clean Dark Design
           ══════════════════════════════════════════════════════ */

        /* ── Reset AdminLTE base ── */
        .left-side, .left-side.sidebar-offcanvas, aside.left-side, .sidebar-offcanvas { all: unset !important; }

        /* ── Sidebar shell ── */
        .left-side.sidebar-offcanvas {
            position: fixed !important; top: 0 !important; left: 0 !important; bottom: 0 !important;
            width: 260px !important; height: 100vh !important; min-height: 100vh !important;
            display: flex !important; flex-direction: column !important;
            overflow: visible !important; overflow-x: hidden !important;
            z-index: 9999 !important; margin: 0 !important; padding: 0 !important;
            box-sizing: border-box !important; transform: none !important;
            background: #0f1117 !important;
            box-shadow: 1px 0 0 rgba(255,255,255,0.06), 4px 0 20px rgba(0,0,0,0.4) !important;
        }
        @media screen and (min-width:1px) {
            .left-side.sidebar-offcanvas { top:0 !important; height:100vh !important; min-height:100vh !important; }
        }

        /* ── Logo / Brand header ── */
        .sidebar-logo {
            all: unset !important;
            display: flex !important; align-items: center !important; gap: 12px !important;
            width: 100% !important; box-sizing: border-box !important;
            padding: 20px 20px 18px !important; flex-shrink: 0 !important;
            background: #0f1117 !important; position: relative !important;
            border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        }
        .sidebar-logo a {
            display: flex !important; align-items: center !important; gap: 12px !important;
            text-decoration: none !important; width: 100% !important;
        }
        .sidebar-logo img {
            all: unset !important;
            display: block !important; width: 44px !important; height: 44px !important;
            border-radius: 12px !important; object-fit: contain !important;
            background: rgba(99,102,241,0.15) !important;
            padding: 6px !important; box-sizing: border-box !important;
            border: 1px solid rgba(99,102,241,0.3) !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        }
        .sidebar-logo img:hover {
            transform: scale(1.06) !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.35) !important;
        }
        .sidebar-logo-text {
            flex: 1 !important;
        }
        .sidebar-logo-title {
            display: block !important; font-size: 15px !important; font-weight: 700 !important;
            color: #f1f5f9 !important; letter-spacing: 0.3px !important; line-height: 1.2 !important;
        }
        .sidebar-logo-sub {
            display: block !important; font-size: 11px !important; color: #64748b !important;
            margin-top: 2px !important; font-weight: 400 !important;
        }

        /* ── User pill at top ── */
        .sidebar-user-pill {
            display: flex !important; align-items: center !important; gap: 10px !important;
            margin: 12px 14px 4px !important; padding: 10px 14px !important;
            background: rgba(255,255,255,0.03) !important;
            border: 1px solid rgba(255,255,255,0.07) !important;
            border-radius: 12px !important; flex-shrink: 0 !important;
        }
        .sidebar-user-avatar {
            width: 32px !important; height: 32px !important; border-radius: 50% !important;
            background: linear-gradient(135deg,#6366f1,#8b5cf6) !important;
            display: flex !important; align-items: center !important; justify-content: center !important;
            font-size: 14px !important; color: #fff !important; font-weight: 700 !important;
            flex-shrink: 0 !important;
        }
        .sidebar-user-info { flex: 1 !important; min-width: 0 !important; }
        .sidebar-user-name {
            display: block !important; font-size: 12px !important; font-weight: 600 !important;
            color: #cbd5e1 !important; white-space: nowrap !important;
            overflow: hidden !important; text-overflow: ellipsis !important;
        }
        .sidebar-user-role {
            display: block !important; font-size: 10px !important; color: #6366f1 !important;
            font-weight: 500 !important; margin-top: 1px !important;
        }

        /* ── Scrollable section ── */
        .sidebar, section.sidebar {
            all: unset !important;
            display: block !important; width: 100% !important; box-sizing: border-box !important;
            flex: 1 !important; min-height: 0 !important; position: relative !important;
            background: transparent !important;
            overflow-y: auto !important; overflow-x: hidden !important;
            padding: 0 !important; margin: 0 !important;
        }
        .sidebar::-webkit-scrollbar { width: 4px !important; }
        .sidebar::-webkit-scrollbar-track { background: transparent !important; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.35) !important; border-radius: 4px !important; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.6) !important; }

        /* ── Menu list ── */
        .sidebar-menu, ul.sidebar-menu {
            all: unset !important;
            display: block !important; width: 100% !important; box-sizing: border-box !important;
            list-style: none !important; margin: 0 !important; padding: 8px 0 80px !important;
            position: relative !important;
        }

        /* ── Menu items ── */
        .sidebar-menu > li {
            all: unset !important;
            display: block !important; box-sizing: border-box !important;
            list-style: none !important; position: relative !important;
            margin: 1px 10px !important; padding: 0 !important;
            border-radius: 10px !important;
            transition: background 0.18s !important;
        }
        .sidebar-menu > li > a {
            all: unset !important;
            display: flex !important; align-items: center !important;
            width: 100% !important; box-sizing: border-box !important;
            padding: 10px 12px !important; gap: 10px !important;
            color: #94a3b8 !important; font-size: 13.5px !important; font-weight: 500 !important;
            font-family: inherit !important; text-decoration: none !important;
            border-radius: 10px !important; cursor: pointer !important;
            transition: background 0.18s, color 0.18s !important;
        }
        .sidebar-menu > li > a:hover {
            background: rgba(255,255,255,0.05) !important;
            color: #e2e8f0 !important;
        }
        .sidebar-menu > li.active > a {
            background: rgba(99,102,241,0.15) !important;
            color: #a5b4fc !important; font-weight: 600 !important;
        }
        .sidebar-menu > li.active {
            box-shadow: inset 3px 0 0 #6366f1 !important;
        }

        /* ── Menu icon box ── */
        .sidebar-menu > li > a > i:first-child {
            all: unset !important;
            display: inline-flex !important; align-items: center !important; justify-content: center !important;
            width: 34px !important; height: 34px !important; min-width: 34px !important;
            font-size: 15px !important; font-family: 'FontAwesome','bootstrap-icons' !important;
            border-radius: 9px !important; flex-shrink: 0 !important;
            background: rgba(255,255,255,0.06) !important;
            transition: background 0.18s, transform 0.2s !important;
            text-align: center !important; line-height: 34px !important;
        }
        .sidebar-menu > li > a:hover > i:first-child {
            background: rgba(99,102,241,0.18) !important;
            transform: scale(1.08) !important;
        }
        .sidebar-menu > li.active > a > i:first-child {
            background: rgba(99,102,241,0.25) !important;
            color: #818cf8 !important;
        }

        /* ── Menu item span text ── */
        .sidebar-menu > li > a > span {
            all: unset !important;
            display: inline-block !important; flex: 1 !important;
            font-size: 13.5px !important; color: inherit !important;
        }

        /* ── Pull-right arrow ── */
        .sidebar-menu .pull-right {
            all: unset !important;
            display: inline-flex !important; align-items: center !important;
            font-family: 'FontAwesome' !important; font-size: 12px !important;
            margin-left: auto !important; flex-shrink: 0 !important;
            opacity: 0.5 !important; color: #94a3b8 !important;
            transition: transform 0.22s ease, opacity 0.18s !important;
        }
        .sidebar-menu .treeview:hover > a > .pull-right { opacity: 0.85 !important; }
        .sidebar-menu .treeview.active > a > .pull-right {
            transform: rotate(-90deg) !important; opacity: 1 !important; color: #818cf8 !important;
        }

        /* ── Treeview submenu ── */
        .sidebar-menu .treeview-menu {
            all: unset !important;
            display: none !important; width: 100% !important; box-sizing: border-box !important;
            list-style: none !important;
            margin: 2px 0 4px 14px !important; padding: 4px 0 !important;
            background: rgba(0,0,0,0.18) !important; border-radius: 8px !important;
            border-left: 2px solid rgba(99,102,241,0.2) !important;
        }
        .sidebar-menu .treeview.active > .treeview-menu { display: block !important; }
        .sidebar-menu .treeview-menu > li {
            all: unset !important; display: block !important; box-sizing: border-box !important;
            list-style: none !important; position: relative !important;
            margin: 1px 5px !important; border-radius: 7px !important;
        }
        .sidebar-menu .treeview-menu > li > a {
            all: unset !important; display: flex !important; align-items: center !important;
            width: 100% !important; box-sizing: border-box !important;
            padding: 8px 14px 8px 18px !important; gap: 8px !important;
            color: #64748b !important; font-size: 12.5px !important;
            border-radius: 7px !important; cursor: pointer !important;
            text-decoration: none !important;
            transition: background 0.15s, color 0.15s, padding-left 0.15s !important;
        }
        .sidebar-menu .treeview-menu > li > a:hover {
            background: rgba(99,102,241,0.1) !important;
            color: #c7d2fe !important; padding-left: 22px !important;
        }
        .sidebar-menu .treeview-menu > li.active > a {
            background: rgba(99,102,241,0.14) !important;
            color: #818cf8 !important; font-weight: 600 !important;
        }
        .sidebar-menu .treeview-menu > li > a > i {
            all: unset !important; font-family: 'FontAwesome' !important;
            font-size: 7px !important; opacity: 0.6 !important; margin-right: 2px !important;
            transition: opacity 0.15s !important;
        }
        .sidebar-menu .treeview-menu > li > a:hover > i,
        .sidebar-menu .treeview-menu > li.active > a > i { opacity: 1 !important; }

        /* Nested submenu */
        .sidebar-menu .treeview-menu .treeview-menu {
            background: rgba(0,0,0,0.25) !important; margin-left: 8px !important;
            border-left-color: rgba(139,92,246,0.2) !important;
        }
        .sidebar-menu .treeview-menu .treeview-menu > li > a { padding-left: 24px !important; font-size: 12px !important; }
        .sidebar-menu .treeview-menu .treeview-menu > li > a:hover { padding-left: 28px !important; }

        /* ── Badge / Label ── */
        .sidebar-menu .label {
            all: unset !important; display: inline-block !important;
            padding: 2px 8px !important; font-size: 10px !important; font-weight: 700 !important;
            border-radius: 10px !important;
        }
        .sidebar-menu .label-warning {
            background: #f59e0b !important; color: #1c1400 !important;
        }

        /* ── Section dividers ── */
        .sidebar-divider {
            all: unset !important; display: block !important; height: 1px !important;
            margin: 14px 14px 6px !important;
            background: rgba(255,255,255,0.06) !important;
        }
        .sidebar-category {
            all: unset !important; display: flex !important; align-items: center !important;
            padding: 6px 22px 4px !important; margin-bottom: 2px !important;
            color: #475569 !important; font-size: 10px !important; font-weight: 700 !important;
            text-transform: uppercase !important; letter-spacing: 1.2px !important;
        }
        .sidebar-category::before {
            content: '' !important;
            display: inline-block !important; width: 14px !important; height: 1px !important;
            background: rgba(99,102,241,0.4) !important; margin-right: 6px !important;
        }
        .sidebar-category::after {
            content: '' !important;
            flex: 1 !important; height: 1px !important;
            background: rgba(99,102,241,0.12) !important; margin-left: 6px !important;
        }

        /* ── Icon accent colours ── */
        .sidebar-menu > li > a > .fa-dashboard        { color: #818cf8 !important; }
        .sidebar-menu > li > a > .fa-users            { color: #a78bfa !important; }
        .sidebar-menu > li > a > .fa-trello           { color: #34d399 !important; }
        .sidebar-menu > li > a > .fa-eye              { color: #38bdf8 !important; }
        .sidebar-menu > li > a > .fa-money            { color: #fbbf24 !important; }
        .sidebar-menu > li > a > .fa-file             { color: #22d3ee !important; }
        .sidebar-menu > li > a > .fa-user-md          { color: #2dd4bf !important; }
        .sidebar-menu > li > a > .fa-lock             { color: #f87171 !important; }
        .sidebar-menu > li > a > .fa-clock-o          { color: #c084fc !important; }
        .sidebar-menu > li > a > .fa-file-text-o      { color: #60a5fa !important; }
        .sidebar-menu > li > a > .bi-box-seam         { color: #fb923c !important; }
        .sidebar-menu > li > a > .fa-shield           { color: #f472b6 !important; }
        .sidebar-menu > li > a > .fa-credit-card      { color: #f87171 !important; }
        .sidebar-menu > li > a > .fa-wrench           { color: #22d3ee !important; }
        .sidebar-menu > li > a > .bi-robot            { color: #a78bfa !important; }
        .sidebar-menu > li > a > .bi-currency-dollar  { color: #fbbf24 !important; }
        .sidebar-menu > li > a > .bi-shield-check     { color: #fbbf24 !important; }
        .sidebar-menu > li > a > .bi-chat-dots-fill   { color: #34d399 !important; }
    </style>

    <section class="sidebar">
        <!-- Brand Header -->
        <div class="sidebar-logo">
            <a href="{{route('dashboard.index')}}">
                <img src="{{ Files::getUrl(Settings::get('system_logo')) }}" alt="Logo" width="44" height="44" style="width:44px;height:44px;max-width:44px;">
                <div class="sidebar-logo-text">
                    <span class="sidebar-logo-title">{{ Settings::get('system_name') ?? 'Optics System' }}</span>
                    <span class="sidebar-logo-sub">Management Panel</span>
                </div>
            </a>
        </div>

        <!-- User Pill -->
        <div class="sidebar-user-pill">
            <div class="sidebar-user-avatar">{{ strtoupper(substr(optional(auth()->user())->first_name ?? 'U', 0, 1)) }}</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ optional(auth()->user())->first_name ?? '' }} {{ optional(auth()->user())->last_name ?? '' }}</span>
                <span class="sidebar-user-role">{{ optional(auth()->user() ? auth()->user()->roles->first() : null)->name ?? 'User' }}</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <!-- Dashboard - Always Visible -->
            <li class="{{ Request::route()->getName() == 'dashboard.index' ? 'active' : '' }}">
                <a href="{{route('dashboard.index')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="sidebar-divider"></div>
            <div class="sidebar-category">Sales & Customers</div>

            <!-- Customers -->
            @can('view-customers')
                <li class="treeview {{ Request::route()->getName() == 'dashboard.get-all-customers' ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span>Customers</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::route()->getName() == 'dashboard.get-all-customers' ? 'active' : '' }}">
                            <a href="{{route('dashboard.get-all-customers')}}">
                                <i class="fa fa-circle-o"></i> Customers List
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @if(auth()->user()->hasAnyPermission(['view-products', 'view-categories', 'view-brands', 'view-models']))
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">Products & Inventory</div>

                <!-- Products treeview -->
                <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.get-all-products','dashboard.get-all-categories','dashboard.get-all-brands','dashboard.get-all-models']) ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="fa fa-trello"></i>
                        <span>Products</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @can('view-products')
                            <li class="{{ Request::routeIs('dashboard.get-all-products') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-all-products') }}">
                                    <i class="fa fa-circle-o"></i> Products
                                </a>
                            </li>
                        @endcan

                        @can('view-categories')
                            <li class="{{ Request::routeIs('dashboard.get-all-categories') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-all-categories') }}">
                                    <i class="fa fa-circle-o"></i> Categories
                                </a>
                            </li>
                        @endcan

                        @can('view-brands')
                            <li class="{{ Request::routeIs('dashboard.get-all-brands') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-all-brands') }}">
                                    <i class="fa fa-circle-o"></i> Brands
                                </a>
                            </li>
                        @endcan

                        @can('view-models')
                            <li class="{{ Request::routeIs('dashboard.get-all-models') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-all-models') }}">
                                    <i class="fa fa-circle-o"></i> Models
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endif

            {{-- ── Contact Lenses standalone treeview ── --}}
            @php
                $clActive = Request::routeIs('dashboard.contact-lenses.*')
                    || Request::routeIs('dashboard.lens-purchase-orders.cl.*');
            @endphp
            <li class="treeview {{ $clActive ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="fa fa-eye" style="color:#3498db;"></i>
                    <span style="color:#3498db;font-weight:600;">Contact Lenses</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::routeIs('dashboard.contact-lenses.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.contact-lenses.index') }}">
                            <i class="fa fa-circle-o" style="color:#3498db;"></i> All Contact Lenses
                        </a>
                    </li>
                    <li class="{{ Request::routeIs('dashboard.contact-lenses.create') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.contact-lenses.create') }}">
                            <i class="fa fa-plus-circle" style="color:#3498db;"></i> Add Contact Lens
                        </a>
                    </li>
                    @can('view-lens-purchase-orders')
                        @php
                            $clLabActive = Request::routeIs('dashboard.lens-purchase-orders.index')
                                        && request('type') === 'contact_lens';
                        @endphp
                        <li class="{{ $clLabActive ? 'active' : '' }}">
                            <a href="{{ route('dashboard.lens-purchase-orders.index') }}?type=contact_lens">
                                <i class="bi bi-eye" style="color:#3498db;font-size:13px;"></i> CL Lab Orders
                            </a>
                        </li>
                    @endcan
                    @can('view-damaged-lenses')
                        <li class="{{ Request::routeIs('dashboard.lens-purchase-orders.cl.damaged-list') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.lens-purchase-orders.cl.damaged-list') }}" style="display:flex;align-items:center;gap:6px;">
                                <i class="fa fa-exclamation-triangle" style="color:#e74c3c;"></i>
                                <span>هالك CL — Damaged</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

            @if(auth()->user()->hasAnyPermission(['view-lenses', 'view-stock', 'view-brands', 'view-lens-purchase-orders', 'view-damaged-lenses']))
                @php $lensRouteActive = in_array(Request::route()->getName(), ['dashboard.get-glassess-lenses','dashboard.lens-brands.index']) || Request::routeIs('dashboard.lens-purchase-orders.*'); @endphp
                <!-- Lenses treeview -->
                <li class="treeview {{ $lensRouteActive ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="fa fa-eye"></i>
                        <span>Lenses</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @can('view-lenses')
                            <li class="{{ Request::routeIs('dashboard.get-glassess-lenses') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-glassess-lenses') }}">
                                    <i class="fa fa-circle-o"></i> Lenses List
                                </a>
                            </li>
                        @endcan

                        @can('view-lens-purchase-orders')
                            <li class="{{ Request::routeIs('dashboard.lens-purchase-orders.index') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.lens-purchase-orders.index') }}">
                                    <i class="fa fa-flask"></i> Lab Orders
                                </a>
                            </li>
                        @endcan

                        @can('view-damaged-lenses')
                            <li class="{{ Request::routeIs('dashboard.lens-purchase-orders.damaged-list') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.lens-purchase-orders.damaged-list') }}" style="display:flex;align-items:center;gap:6px;">
                                    <i class="fa fa-exclamation-triangle" style="color:#e74c3c;"></i>
                                    <span>هالك — Damaged</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-brands')
                            <li class="{{ Request::routeIs('dashboard.lens-brands.index') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.lens-brands.index') }}">
                                    <i class="fa fa-circle-o"></i> Lens Brands
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Stock Management -->
            @if(auth()->user()->hasAnyPermission(['view-branches', 'view-stock', 'view-transfers']))
                <li class="treeview {{ strpos(Request::route()->getName(), 'branches.') !== false || strpos(Request::route()->getName(), 'stock-transfers.') !== false ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="bi bi-box-seam"></i>
                        <span>Stock Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Branches -->
                        @can('view-branches')
                            <li class="treeview {{ strpos(Request::route()->getName(), 'branches.index') !== false || strpos(Request::route()->getName(), 'branches.create') !== false || strpos(Request::route()->getName(), 'branches.statistics') !== false ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-building-o"></i> Branches
                                    <i class="fa fa-angle-left pull-right" style="margin-left: 7px !important;"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ Request::route()->getName() == 'dashboard.branches.index' ? 'active' : '' }}">
                                        <a href="{{ route('dashboard.branches.index') }}"><i class="fa fa-dot-circle-o"></i> All Branches</a>
                                    </li>
                                    @can('create-branches')
                                        <li class="{{ Request::route()->getName() == 'dashboard.branches.create' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.branches.create') }}"><i class="fa fa-dot-circle-o"></i> Add Branch</a>
                                        </li>
                                    @endcan
                                    <li class="{{ Request::route()->getName() == 'dashboard.branches.statistics' ? 'active' : '' }}">
                                        <a href="{{ route('dashboard.branches.statistics') }}"><i class="fa fa-dot-circle-o"></i> Statistics</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        <!-- Branch Stock -->
                       {{-- @can('view-stock')
                            <li class="treeview {{ strpos(Request::route()->getName(), 'branches.stock.') !== false ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-dropbox"></i> Branch Stock
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @php
                                        $defaultBranch = \Cache::remember('sidebar_default_branch', 300, function () {
                                            return \App\Branch::where('is_active', true)->orderBy('is_main', 'desc')->first();
                                        });
                                    @endphp
                                    @if($defaultBranch)
                                        <li class="{{ Request::route()->getName() == 'dashboard.branches.stock.index' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.branches.stock.index', $defaultBranch) }}"><i class="fa fa-dot-circle-o"></i> View Stock</a>
                                        </li>
                                        @can('add-stock')
                                            <li class="{{ Request::route()->getName() == 'dashboard.branches.stock.create' ? 'active' : '' }}">
                                                <a href="{{ route('dashboard.branches.stock.create', $defaultBranch) }}"><i class="fa fa-dot-circle-o"></i> Add Product</a>
                                            </li>
                                        @endcan
                                        @can('view-stock-reports')
                                            <li class="{{ Request::route()->getName() == 'dashboard.branches.stock.report' ? 'active' : '' }}">
                                                <a href="{{ route('dashboard.branches.stock.report', $defaultBranch) }}"><i class="fa fa-dot-circle-o"></i> Stock Report</a>
                                            </li>
                                        @endcan
                                    @else
                                        @can('create-branches')
                                            <li><a href="{{ route('dashboard.branches.create') }}"><i class="fa fa-exclamation-triangle"></i> Create Branch First</a></li>
                                        @endcan
                                    @endif
                                </ul>
                            </li>
                        @endcan--}}

                        @can('view-stock')
                            @php
                                // Load branches accessible to this user once
                                if (auth()->user()->canSeeAllBranches()) {
                                    $sidebarStockBranches = \Cache::remember('sidebar_stock_branches', 300, function () {
                                        return \App\Branch::where('is_active', true)->orderBy('is_main', 'desc')->orderBy('name')->get();
                                    });
                                } else {
                                    $sidebarStockBranches = auth()->user()->branch
                                        ? collect([auth()->user()->branch])
                                        : collect();
                                }
                                // Detect which branch is currently active (from URL)
                                $currentStockBranchId = optional(request()->route('branch'))->id ?? null;
                            @endphp
                            <li class="treeview {{ strpos(Request::route()->getName(), 'branches.stock.') !== false ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-dropbox"></i> Branch Stock
                                    <i class="fa fa-angle-left pull-right" style="margin-left: 7px !important;"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @forelse($sidebarStockBranches as $sb)
                                        @php
                                            $sbActive = ($currentStockBranchId == $sb->id);
                                        @endphp
                                        <li class="treeview {{ $sbActive ? 'active' : '' }}">
                                            <a href="javascript:void(0)">
                                                <i class="fa fa-building-o"></i>
                                                {{ $sb->is_main ? '★ ' : '' }}{{ $sb->name }}
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li class="{{ ($sbActive && Request::route()->getName() == 'dashboard.branches.stock.index') ? 'active' : '' }}">
                                                    <a href="{{ route('dashboard.branches.stock.index', $sb) }}">
                                                        <i class="fa fa-dot-circle-o"></i> View Stock
                                                    </a>
                                                </li>
                                                @can('add-stock')
                                                <li class="{{ ($sbActive && Request::route()->getName() == 'dashboard.branches.stock.create') ? 'active' : '' }}">
                                                    <a href="{{ route('dashboard.branches.stock.create', $sb) }}">
                                                        <i class="fa fa-dot-circle-o"></i> Add Product
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('view-stock-reports')
                                                <li class="{{ ($sbActive && Request::route()->getName() == 'dashboard.branches.stock.report') ? 'active' : '' }}">
                                                    <a href="{{ route('dashboard.branches.stock.report', $sb) }}">
                                                        <i class="fa fa-dot-circle-o"></i> Stock Report
                                                    </a>
                                                </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @empty
                                        @can('create-branches')
                                            <li><a href="{{ route('dashboard.branches.create') }}"><i class="fa fa-exclamation-triangle"></i> Create Branch First</a></li>
                                        @endcan
                                    @endforelse
                                </ul>
                            </li>
                        @endcan

                        <!-- Stock Transfers -->
                        @can('view-transfers')
                            <li class="treeview {{ strpos(Request::route()->getName(), 'stock-transfers.') !== false ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-exchange"></i> Stock Transfers
                                    <i class="fa fa-angle-left pull-right" style="margin-left: 7px !important;"style="margin-left: 7px !important;"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.index' ? 'active' : '' }}">
                                        <a href="{{ route('dashboard.stock-transfers.index') }}"><i class="fa fa-dot-circle-o"></i> All Transfers</a>
                                    </li>
                                    @can('create-transfers')
                                        <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.create' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.stock-transfers.create') }}"><i class="fa fa-dot-circle-o"></i> New Transfer</a>
                                        </li>
                                        <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.bulk-request' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.stock-transfers.bulk-request') }}">
                                                <i class="fa fa-upload"></i> Bulk Request (Excel)
                                            </a>
                                        </li>
                                    @endcan
                                    <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.pending' ? 'active' : '' }}">
                                        <a href="{{ route('dashboard.stock-transfers.pending') }}">
                                            <i class="fa fa-dot-circle-o"></i> Pending
                                            @php
                                                $pendingCount = \Cache::remember('sidebar_pending_transfers', 120, function () {
                                                    return \App\StockTransfer::where('status', 'pending')->count();
                                                });
                                            @endphp
                                            @if($pendingCount > 0)
                                                <span class="label label-warning pull-right">{{ $pendingCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    @can('view-transfer-reports')
                                        <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.report' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.stock-transfers.report') }}"><i class="fa fa-dot-circle-o"></i> Report</a>
                                        </li>
                                        <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.report.items' ? 'active' : '' }}">
                                            <a href="{{ route('dashboard.stock-transfers.report.items') }}">
                                                <i class="bi bi-box-arrow-right" style="font-size:13px;"></i> Items Transfer
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->hasAnyPermission(['view-invoices', 'view-pending-invoices', 'view-returned-invoices', 'view-expenses', 'view-daily-closing', 'view-history', 'view-customer-status']))
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">Financial</div>

                <!-- Invoices -->
                @if(auth()->user()->hasAnyPermission(['view-invoices', 'view-pending-invoices', 'view-returned-invoices']))
                    <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.pending-invoices', 'dashboard.invoice.returned', 'dashboard.invoice.pending']) ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="fa fa-money"></i>
                            <span>Invoices</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @can('view-pending-invoices')
                                <li class="{{ Request::route()->getName() == 'dashboard.invoice.pending' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.invoice.pending')}}"><i class="fa fa-circle-o"></i> Pending Invoices</a>
                                </li>
                            @endcan
                            @can('view-returned-invoices')
                                <li class="{{ Request::route()->getName() == 'dashboard.invoice.returned' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.invoice.returned')}}"><i class="fa fa-circle-o"></i> Return Invoices</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <!-- Expenses -->
                @can('view-expenses')
                    <li class="treeview {{ strpos(Request::route()->getName(), 'expenses.') !== false ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="bi bi-currency-dollar"></i>
                            <span>Expenses</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::route()->getName() == 'dashboard.expenses.index' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.expenses.index') }}"><i class="fa fa-circle-o"></i> All Expenses</a>
                            </li>
                            @can('create-expenses')
                                <li class="{{ Request::route()->getName() == 'dashboard.expenses.create' ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.expenses.create') }}"><i class="fa fa-circle-o"></i> Add Expense</a>
                                </li>
                            @endcan
                            <li class="{{ Request::route()->getName() == 'dashboard.expenses.report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.expenses.report') }}"><i class="fa fa-circle-o"></i> Report</a>
                            </li>
                            <li class="{{ Request::route()->getName() == 'dashboard.expenses.categories' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.expenses.categories') }}"><i class="fa fa-circle-o"></i> Categories</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <!-- Daily Closing -->
                @can('view-daily-closing')
                    <li class="{{ Request::route()->getName() == 'dashboard.daily-closing.index' ? 'active' : '' }}">
                        <a href="{{route('dashboard.daily-closing.index')}}">
                            <i class="fa fa-lock"></i>
                            <span>Daily Closing</span>
                        </a>
                    </li>
                @endcan

                <!-- History -->
                @can('view-history')
                    <li class="{{ Request::route()->getName() == 'dashboard.cashier-history' ? 'active' : '' }}">
                        <a href="{{ route('dashboard.cashier-history') }}">
                            <i class="fa fa-clock-o"></i>
                            <span>History</span>
                        </a>
                    </li>
                @endcan

                <!-- Customer Statement -->
                @can('view-customer-status')
                    <li class="{{ in_array(Request::route()->getName(), ['dashboard.customers.search-statement', 'dashboard.customers.statement']) ? 'active' : '' }}">
                        <a href="{{ route('dashboard.customers.search-statement') }}">
                            <i class="fa fa-file-text-o"></i>
                            <span>Customer Statement</span>
                        </a>
                    </li>
                @endcan
            @endif

            @can('view-reports')
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">Reports</div>

                <!-- Reports -->
                @php
                    $currentRoute = Request::route()->getName();

                    $reportRoutes = [
                        'dashboard.items-to-be-delivired',
                        'dashboard.items-delivered',
                        'dashboard.get-pending-invoices',
                        'dashboard.get-return-invoices',
                        'dashboard.get-customers-report',
                        'dashboard.get-sales-transactions-report',
                        'dashboard.get-cashier-transactions-report',
                        'dashboard.get-sales-summary-report',
                        'dashboard.get-sales-summary-salesman-report',
                        'dashboard.reports.salesman.summary',
                        'dashboard.salesman-salary',
                        'dashboard.get-sales-summary-doctor',
                        'dashboard.get-cashier-report',
                        'dashboard.expenses-report',
                        'dashboard.get-delivered-invoices',
                        'dashboard.get-damaged-lenses'
                    ];
                @endphp

                <li class="treeview {{ in_array($currentRoute, $reportRoutes) ? 'active menu-open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="fa fa-file"></i>
                        <span>Reports</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">
                        @can('view-items-to-arrive-report')
                            <li class="{{ $currentRoute == 'dashboard.items-to-be-delivired' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.items-to-be-delivired') }}">
                                    <i class="fa fa-circle-o"></i> Items To Be Delivered
                                </a>
                            </li>
                        @endcan

                        @can('view-items-delivered-report')
                            <li class="{{ $currentRoute == 'dashboard.items-delivered' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.items-delivered') }}">
                                    <i class="fa fa-circle-o"></i> Items Delivered
                                </a>
                            </li>
                        @endcan

                        @can('view-items-delivered-report')
                            <li class="{{ $currentRoute == 'dashboard.get-delivered-invoices' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-delivered-invoices') }}">
                                    <i class="fa fa-circle-o"></i> Delivered Invoices
                                </a>
                            </li>
                        @endcan

                        @can('view-damaged-lenses-report')
                            <li class="{{ $currentRoute == 'dashboard.get-damaged-lenses' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-damaged-lenses') }}">
                                    <i class="fa fa-circle-o" style="color:#e74c3c;"></i> Damaged Lenses (هالك)
                                </a>
                            </li>
                        @endcan

                        @can('view-pending-invoices-report')
                            <li class="{{ $currentRoute == 'dashboard.get-pending-invoices' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-pending-invoices') }}">
                                    <i class="fa fa-circle-o"></i> Pending Invoices
                                </a>
                            </li>
                        @endcan

                        @can('view-returned-invoices-report')
                            <li class="{{ $currentRoute == 'dashboard.get-return-invoices' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-return-invoices') }}">
                                    <i class="fa fa-circle-o"></i> Return Invoices
                                </a>
                            </li>
                        @endcan

                        @can('view-customers-report')
                            <li class="{{ $currentRoute == 'dashboard.get-customers-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-customers-report') }}">
                                    <i class="fa fa-circle-o"></i> Customers Report
                                </a>
                            </li>
                        @endcan

                        @can('view-sales-transactions-report')
                            <li class="{{ $currentRoute == 'dashboard.get-sales-transactions-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-sales-transactions-report') }}">
                                    <i class="fa fa-circle-o"></i> Sales Transactions
                                </a>
                            </li>
                        @endcan

                       {{-- @can('view-cashier-report')
                            <li class="{{ $currentRoute == 'dashboard.get-cashier-transactions-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-cashier-transactions-report') }}">
                                    <i class="fa fa-circle-o"></i> Cashier Transactions
                                </a>
                            </li>
                        @endcan--}}

                            @can('view-cashier-report')
                                <li class="{{ $currentRoute == 'dashboard.get-cashier-report' ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.get-cashier-report') }}">
                                        <i class="fa fa-circle-o"></i> Cashier Report
                                    </a>
                                </li>
                            @endcan

                            @can('view-expenses-report')
                                <li class="{{ $currentRoute == 'dashboard.expenses-report' ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.expenses-report') }}">
                                        <i class="fa fa-circle-o"></i> Expenses Report
                                    </a>
                                </li>
                            @endcan

                        @can('view-sales-summary-report')
                            <li class="{{ $currentRoute == 'dashboard.get-sales-summary-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-sales-summary-report') }}">
                                    <i class="fa fa-circle-o"></i> Sales Summary
                                </a>
                            </li>
                        @endcan

                        @can('view-sales-by-seller-report')
                            <li class="{{ $currentRoute == 'dashboard.get-sales-summary-salesman-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-sales-summary-salesman-report') }}">
                                    <i class="fa fa-circle-o"></i> Sales by Salesman
                                </a>
                            </li>
                        @endcan

                        @can('view-seller-sales-summary-report')
                            <li class="{{ $currentRoute == 'dashboard.reports.salesman.summary' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.reports.salesman.summary') }}">
                                    <i class="fa fa-circle-o"></i> Sales per Salesman
                                </a>
                            </li>
                        @endcan

                        @can('view-employee-salaries-report')
                            <li class="{{ $currentRoute == 'dashboard.salesman-salary' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.salesman-salary') }}">
                                    <i class="fa fa-circle-o"></i> Salesman Salary
                                </a>
                            </li>
                        @endcan

                        @can('view-sales-by-doctor-report')
                            <li class="{{ $currentRoute == 'dashboard.get-sales-summary-doctor' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-sales-summary-doctor') }}">
                                    <i class="fa fa-circle-o"></i> Sales by Doctor
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @if(auth()->user()->hasAnyPermission(['view-doctors', 'view-insurance-companies', 'view-cardholders']))
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">System</div>

                <!-- Doctors -->
                @can('view-doctors')
                    <li class="{{ Request::route()->getName() == 'dashboard.get-all-doctors' ? 'active' : '' }}">
                        <a href="{{route('dashboard.get-all-doctors')}}">
                            <i class="fa fa-user-md"></i>
                            <span>Doctors</span>
                        </a>
                    </li>
                @endcan

                <!-- Insurance Company -->
                @can('view-insurance-companies')
                    <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.get-all-insurance-companies', 'dashboard.get-add-insurance-company']) ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="bi bi-shield-check"></i>
                            <span>Insurance Company</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::route()->getName() == 'dashboard.get-all-insurance-companies' ? 'active' : '' }}">
                                <a href="{{route('dashboard.get-all-insurance-companies')}}"><i class="fa fa-circle-o"></i> Company List</a>
                            </li>
                            @can('create-insurance-companies')
                                <li class="{{ Request::route()->getName() == 'dashboard.get-add-insurance-company' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.get-add-insurance-company')}}"><i class="fa fa-circle-o"></i> Create Company</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Cardholder -->
                @can('view-cardholders')
                    <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.get-all-cardholders', 'dashboard.get-add-cardholder']) ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="fa fa-credit-card"></i>
                            <span>Cardholder</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::route()->getName() == 'dashboard.get-all-cardholders' ? 'active' : '' }}">
                                <a href="{{route('dashboard.get-all-cardholders')}}"><i class="fa fa-circle-o"></i> Cardholder List</a>
                            </li>
                            @can('create-cardholders')
                                <li class="{{ Request::route()->getName() == 'dashboard.get-add-cardholder' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.get-add-cardholder')}}"><i class="fa fa-circle-o"></i> Create Cardholder</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            @endif

            @if(auth()->user()->hasAnyPermission(['view-users', 'view-roles', 'view-settings']))
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">Administration</div>

                <!-- Users -->
                @can('view-users')
                    <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.get-all-users', 'dashboard.get-add-user', 'dashboard.get-update-user']) ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::route()->getName() == 'dashboard.get-all-users' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-all-users') }}"><i class="fa fa-circle-o"></i> All Users</a>
                            </li>
                            @can('create-users')
                                <li class="{{ Request::route()->getName() == 'dashboard.get-add-user' ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.get-add-user') }}"><i class="fa fa-circle-o"></i> Add User</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Roles & Permissions -->
                @can('view-roles')
                    <li class="treeview {{ strpos(Request::route()->getName(), 'dashboard.roles.') !== false ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <i class="fa fa-shield"></i>
                            <span>Roles & Permissions</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::route()->getName() == 'dashboard.roles.index' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.roles.index') }}"><i class="fa fa-circle-o"></i> All Roles</a>
                            </li>
                            @can('create-roles')
                                <li class="{{ Request::route()->getName() == 'dashboard.roles.create' ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.roles.create') }}"><i class="fa fa-circle-o"></i> Add Role</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Settings -->
                @can('view-settings')
                    <li class="{{ Request::route()->getName() == 'dashboard.settings.index' ? 'active' : '' }}">
                        <a href="{{route('dashboard.settings.index')}}">
                            <i class="fa fa-wrench"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                @endcan
            @endif

            {{-- ── Chat (controlled by chat_enabled setting) ── --}}
            @if(\App\Facades\Settings::get('chat_enabled', '1') !== '0')
            <div class="sidebar-divider"></div>
            <li class="{{ Request::routeIs('dashboard.chat.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.chat.index') }}">
                    <i class="bi bi-chat-dots-fill" style="color:#34d399 !important;"></i>
                    <span>Internal Chat</span>
                    @php
                        $sidebarUnreadChat = \App\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    @if($sidebarUnreadChat > 0)
                        <span class="label label-warning pull-right" style="background:#ef4444 !important;color:#fff !important;">
                            {{ $sidebarUnreadChat > 99 ? '99+' : $sidebarUnreadChat }}
                        </span>
                    @endif
                </a>
            </li>
            @endif

            {{-- ── AI Assistant ── --}}
            @can('view-ai-assistant')
                <div class="sidebar-divider"></div>
                <li class="{{ Request::routeIs('dashboard.assistant.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.assistant.index') }}">
                        <i class="bi bi-robot"></i>
                        <span>AI Assistant</span>
                    </a>
                </li>
            @endcan
        </ul>
    </section>

    <!-- ⚡ LIGHTNING FAST JavaScript ⚡ -->
    <script>
        $(document).ready(function () {
            // Kill all previous events
            $('.sidebar-menu li.treeview > a').off('click');

            // Ultra-fast toggle
            $('.sidebar-menu li.treeview > a').on('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var $li = $(this).parent();
                var $menu = $li.find('> .treeview-menu');

                if ($li.hasClass('active')) {
                    $menu.slideUp(200);
                    $li.removeClass('active');
                } else {
                    $li.siblings('.treeview.active').find('> .treeview-menu').slideUp(200);
                    $li.siblings('.treeview.active').removeClass('active');

                    $menu.slideDown(200);
                    $li.addClass('active');
                }
            });

            // Auto-open active paths
            function initSidebar() {
                $('.sidebar-menu li.active').each(function () {
                    $(this).parents('.treeview').addClass('active').find('> .treeview-menu').show();
                });
            }

            initSidebar();
            setTimeout(initSidebar, 100);
        });
    </script>
</aside>
