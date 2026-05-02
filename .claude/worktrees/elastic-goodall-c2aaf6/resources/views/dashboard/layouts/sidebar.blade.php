<!-- 🔥 ULTIMATE MODERN SIDEBAR - WITH PERMISSIONS 🔥 -->
<aside class="left-side sidebar-offcanvas">
    <style>
        /* ═══════════════════════════════════════════════════════════
           ⚠️ NUCLEAR RESET - KILL ALL OLD STYLES ⚠️
           ═══════════════════════════════════════════════════════════ */

        /* Force remove ALL AdminLTE interference */
        .left-side,
        .left-side.sidebar-offcanvas,
        aside.left-side,
        .sidebar-offcanvas {
            all: unset !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🎯 ABSOLUTE CONTROL - FORCE NEW DESIGN
           ═══════════════════════════════════════════════════════════ */

        .left-side.sidebar-offcanvas {
            /* Position & Display */
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;

            /* Size - FORCE FULL HEIGHT */
            width: 250px !important;
            height: 100vh !important;
            min-height: 100vh !important;
            max-height: 100vh !important;

            /* Layout */
            display: flex !important;
            flex-direction: column !important;

            /* Overflow */
            overflow: visible !important;
            overflow-x: hidden !important;

            /* Z-index */
            z-index: 9999 !important;

            /* Background */
            background: linear-gradient(180deg, #1a1f3a 0%, #0f1419 100%) !important;

            /* Shadow */
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.3) !important;

            /* Remove any margin/padding */
            margin: 0 !important;
            padding: 0 !important;

            /* Remove any transforms */
            transform: none !important;

            /* Box model */
            box-sizing: border-box !important;
        }

        /* Force on all screen sizes */
        @media screen and (min-width: 1px) {
            .left-side.sidebar-offcanvas {
                top: 0 !important;
                height: 100vh !important;
                min-height: 100vh !important;
            }
        }

        /* ═══════════════════════════════════════════════════════════
           🎯 LOGO SECTION - PREMIUM GRADIENT HEADER
           ═══════════════════════════════════════════════════════════ */

        .sidebar-logo {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;

            /* Size */
            width: 100% !important;
            height: auto !important;
            min-height: 100px !important;

            /* Layout */
            box-sizing: border-box !important;

            /* Style */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            padding: 25px 20px !important;
            text-align: center !important;
            margin: 0 !important;

            /* Position */
            position: relative !important;
            flex-shrink: 0 !important;

            /* Overflow */
            overflow: hidden !important;

            /* Border */
            border: none !important;
            border-bottom: none !important;
        }

        .sidebar-logo::before {
            content: '' !important;
            position: absolute !important;
            top: -50% !important;
            left: -50% !important;
            width: 200% !important;
            height: 200% !important;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%) !important;
            animation: pulseGlow 4s ease-in-out infinite !important;
            pointer-events: none !important;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
            50% { transform: translate(10px, 10px) scale(1.1); opacity: 0.8; }
        }

        .sidebar-logo a {
            display: block !important;
            text-decoration: none !important;
            position: relative !important;
            z-index: 2 !important;
        }

        .sidebar-logo img {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;

            /* Size */
            max-width: 65px !important;
            width: 65px !important;
            height: auto !important;

            /* Position */
            margin: 0 auto !important;
            position: relative !important;
            z-index: 2 !important;

            /* Effects */
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.4)) !important;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }

        .sidebar-logo img:hover {
            transform: scale(1.15) rotate(5deg) !important;
            filter: drop-shadow(0 6px 15px rgba(0,0,0,0.5)) brightness(1.1) !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🌈 MAIN SIDEBAR SECTION - FULL HEIGHT FORCE
           ═══════════════════════════════════════════════════════════ */

        .sidebar,
        section.sidebar {
            /* Remove ALL defaults */
            all: unset !important;

            /* Display & Layout */
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;

            /* FORCE FULL REMAINING HEIGHT */
            flex: 1 !important;
            height: auto !important;
            min-height: 0 !important;

            /* Position */
            position: relative !important;

            /* Background */
            background: transparent !important;

            /* Overflow - CRITICAL FOR SCROLL */
            overflow-y: auto !important;
            overflow-x: hidden !important;

            /* Padding */
            padding: 0 !important;
            margin: 0 !important;
        }

        .sidebar::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            height: 100% !important;
            background:
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.05) 0%, transparent 50%) !important;
            pointer-events: none !important;
            z-index: 0 !important;
        }

        /* ═══════════════════════════════════════════════════════════
           📋 SIDEBAR MENU - RESET & REBUILD
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu,
        ul.sidebar-menu {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;
            width: 100% !important;

            /* Layout */
            box-sizing: border-box !important;
            list-style: none !important;

            /* Spacing */
            margin: 0 !important;
            padding: 15px 0 80px 0 !important;

            /* Position */
            position: relative !important;
            z-index: 1 !important;
        }

        /* ═══════════════════════════════════════════════════════════
           ✨ MENU ITEMS - PREMIUM DESIGN
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu > li {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;
            width: auto !important;

            /* Layout */
            box-sizing: border-box !important;
            list-style: none !important;

            /* Position */
            position: relative !important;

            /* Spacing */
            margin: 2px 8px !important;
            padding: 0 !important;

            /* Style */
            border-radius: 10px !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
        }

        .sidebar-menu > li::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 0 !important;
            height: 100% !important;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%) !important;
            transition: width 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            z-index: 0 !important;
            pointer-events: none !important;
        }

        .sidebar-menu > li:hover::before,
        .sidebar-menu > li.active::before {
            width: 100% !important;
        }

        .sidebar-menu > li > a {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: flex !important;
            align-items: center !important;
            width: 100% !important;

            /* Layout */
            box-sizing: border-box !important;

            /* Spacing */
            padding: 13px 15px !important;

            /* Text */
            color: #b8c5d6 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            font-family: inherit !important;
            text-decoration: none !important;

            /* Style */
            border-left: 3px solid transparent !important;
            border-radius: 10px !important;

            /* Position */
            position: relative !important;
            z-index: 1 !important;

            /* Cursor */
            cursor: pointer !important;

            /* Transition */
            transition: all 0.3s ease !important;
        }

        .sidebar-menu > li > a:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            border-left-color: #667eea !important;
            color: #ffffff !important;
            padding-left: 18px !important;
            transform: translateX(3px) !important;
        }

        .sidebar-menu > li.active > a {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.25) 0%, rgba(118, 75, 162, 0.15) 100%) !important;
            border-left-color: #667eea !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
            font-weight: 600 !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🎨 ICON STYLING - GRADIENT BOXES
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu > li > a > i:first-child {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;

            /* Size */
            width: 42px !important;
            height: 42px !important;
            min-width: 42px !important;
            min-height: 42px !important;

            /* Font */
            font-size: 17px !important;
            font-family: 'FontAwesome', 'Bootstrap Icons' !important;

            /* Spacing */
            margin-right: 12px !important;
            flex-shrink: 0 !important;

            /* Style */
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%) !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;

            /* Position */
            position: relative !important;
            overflow: hidden !important;

            /* Alignment */
            text-align: center !important;
            line-height: 42px !important;

            /* Transition */
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }

        .sidebar-menu > li > a > i:first-child::before {
            position: relative !important;
            z-index: 2 !important;
        }

        .sidebar-menu > li > a > i:first-child::after {
            content: '' !important;
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            width: 0 !important;
            height: 0 !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.3) !important;
            transform: translate(-50%, -50%) !important;
            transition: width 0.4s ease, height 0.4s ease !important;
        }

        .sidebar-menu > li.active > a > i:first-child,
        .sidebar-menu > li > a:hover > i:first-child {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.4) 0%, rgba(118, 75, 162, 0.4) 100%) !important;
            transform: scale(1.1) rotate(5deg) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.5) !important;
        }

        .sidebar-menu > li > a:hover > i:first-child::after {
            width: 100% !important;
            height: 100% !important;
        }

        /* ═══════════════════════════════════════════════════════════
           📂 TREEVIEW - DROPDOWN MENUS
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu .treeview-menu {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: none !important;
            width: 100% !important;

            /* Layout */
            box-sizing: border-box !important;
            list-style: none !important;

            /* Spacing */
            margin: 5px 0 5px 15px !important;
            padding: 8px 0 !important;

            /* Style */
            background: rgba(0, 0, 0, 0.3) !important;
            border-radius: 8px !important;
            border-left: 2px solid rgba(102, 126, 234, 0.3) !important;
            backdrop-filter: blur(10px) !important;
        }

        .sidebar-menu .treeview.active > .treeview-menu {
            display: block !important;
            animation: slideDown 0.3s ease !important;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar-menu .treeview-menu > li {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;

            /* Layout */
            box-sizing: border-box !important;
            list-style: none !important;

            /* Position */
            position: relative !important;

            /* Spacing */
            margin: 2px 5px !important;
            padding: 0 !important;

            /* Style */
            border-radius: 6px !important;
            overflow: hidden !important;
        }

        .sidebar-menu .treeview-menu > li::before {
            content: '' !important;
            position: absolute !important;
            left: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 3px !important;
            height: 0 !important;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%) !important;
            transition: height 0.3s ease !important;
            border-radius: 2px !important;
            pointer-events: none !important;
        }

        .sidebar-menu .treeview-menu > li:hover::before,
        .sidebar-menu .treeview-menu > li.active::before {
            height: 60% !important;
        }

        .sidebar-menu .treeview-menu > li > a {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: flex !important;
            align-items: center !important;
            width: 100% !important;

            /* Layout */
            box-sizing: border-box !important;

            /* Spacing */
            padding: 10px 15px 10px 20px !important;

            /* Text */
            color: #9ca5b3 !important;
            font-size: 13px !important;
            text-decoration: none !important;

            /* Style */
            border-radius: 6px !important;

            /* Cursor */
            cursor: pointer !important;

            /* Transition */
            transition: all 0.3s ease !important;
        }

        .sidebar-menu .treeview-menu > li > a:hover {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #ffffff !important;
            padding-left: 25px !important;
            transform: translateX(3px) !important;
        }

        .sidebar-menu .treeview-menu > li.active > a {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.1) 100%) !important;
            color: #667eea !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2) !important;
        }

        .sidebar-menu .treeview-menu > li > a > i {
            /* Remove defaults */
            all: unset !important;

            /* Font */
            font-family: 'FontAwesome' !important;
            font-size: 8px !important;

            /* Spacing */
            margin-right: 10px !important;

            /* Style */
            opacity: 0.7 !important;
            transition: all 0.3s ease !important;
        }

        .sidebar-menu .treeview-menu > li > a:hover > i,
        .sidebar-menu .treeview-menu > li.active > a > i {
            opacity: 1 !important;
            transform: scale(1.3) !important;
        }

        /* Nested Treeview */
        .sidebar-menu .treeview-menu .treeview-menu {
            background: rgba(0, 0, 0, 0.4) !important;
            margin-left: 10px !important;
            border-left-color: rgba(118, 75, 162, 0.4) !important;
        }

        .sidebar-menu .treeview-menu .treeview-menu > li > a {
            padding-left: 30px !important;
            font-size: 12px !important;
        }

        .sidebar-menu .treeview-menu .treeview-menu > li > a:hover {
            padding-left: 35px !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🎯 PULL RIGHT ICONS
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu .pull-right {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;

            /* Font */
            font-family: 'FontAwesome' !important;
            font-size: 14px !important;

            /* Position */
            margin-left: auto !important;
            flex-shrink: 0 !important;

            /* Style */
            opacity: 0.7 !important;

            /* Transition */
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }

        .sidebar-menu .treeview:hover > a > .pull-right {
            opacity: 1 !important;
        }

        .sidebar-menu .treeview.active > a > .pull-right {
            transform: rotate(-90deg) !important;
            opacity: 1 !important;
            color: #667eea !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🏷️ LABELS & BADGES
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu .label {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: inline-block !important;

            /* Spacing */
            padding: 4px 10px !important;

            /* Text */
            font-size: 10px !important;
            font-weight: 700 !important;

            /* Style */
            border-radius: 12px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;

            /* Animation */
            animation: pulse 2s ease-in-out infinite !important;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .sidebar-menu .label-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.4) !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🎨 DIVIDERS & CATEGORIES
           ═══════════════════════════════════════════════════════════ */

        .sidebar-divider {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;
            width: auto !important;

            /* Size */
            height: 1px !important;

            /* Spacing */
            margin: 20px 15px !important;

            /* Style */
            background: linear-gradient(90deg,
            transparent 0%,
            rgba(102, 126, 234, 0.3) 20%,
            rgba(118, 75, 162, 0.3) 80%,
            transparent 100%) !important;

            /* Position */
            position: relative !important;
        }

        .sidebar-divider::before {
            content: '' !important;
            position: absolute !important;
            top: -2px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 50% !important;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.6) !important;
        }

        .sidebar-category {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: block !important;

            /* Spacing */
            padding: 12px 15px 8px 15px !important;
            margin-top: 5px !important;

            /* Text */
            color: #667eea !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;

            /* Style */
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%) !important;
            border-left: 3px solid #667eea !important;
            border-radius: 0 8px 8px 0 !important;

            /* Position */
            position: relative !important;
        }

        .sidebar-category::before {
            content: '●' !important;
            margin-right: 8px !important;
            color: #667eea !important;
            animation: blink 2s ease-in-out infinite !important;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ═══════════════════════════════════════════════════════════
           🎨 ICON COLORS
           ═══════════════════════════════════════════════════════════ */

        .fa-dashboard { color: #667eea !important; text-shadow: 0 0 10px rgba(102, 126, 234, 0.5) !important; }
        .fa-users { color: #a78bfa !important; text-shadow: 0 0 10px rgba(167, 139, 250, 0.5) !important; }
        .fa-stack-overflow { color: #f87171 !important; text-shadow: 0 0 10px rgba(248, 113, 113, 0.5) !important; }
        .fa-trello { color: #34d399 !important; text-shadow: 0 0 10px rgba(52, 211, 153, 0.5) !important; }
        .fa-money, .bi-currency-dollar { color: #fbbf24 !important; text-shadow: 0 0 10px rgba(251, 191, 36, 0.5) !important; }
        .fa-file { color: #22d3ee !important; text-shadow: 0 0 10px rgba(34, 211, 238, 0.5) !important; }
        .fa-sitemap { color: #fb923c !important; text-shadow: 0 0 10px rgba(251, 146, 60, 0.5) !important; }
        .fa-user-md { color: #2dd4bf !important; text-shadow: 0 0 10px rgba(45, 212, 191, 0.5) !important; }
        .fa-lock { color: #ef4444 !important; text-shadow: 0 0 10px rgba(239, 68, 68, 0.5) !important; }
        .fa-clock-o { color: #a855f7 !important; text-shadow: 0 0 10px rgba(168, 85, 247, 0.5) !important; }
        .fa-file-text-o { color: #3b82f6 !important; text-shadow: 0 0 10px rgba(59, 130, 246, 0.5) !important; }
        .bi-box-seam { color: #f97316 !important; text-shadow: 0 0 10px rgba(249, 115, 22, 0.5) !important; }
        .fa-shield { color: #f472b6 !important; text-shadow: 0 0 10px rgba(244, 114, 182, 0.5) !important; }
        .fa-credit-card { color: #dc2626 !important; text-shadow: 0 0 10px rgba(220, 38, 38, 0.5) !important; }
        .fa-pagelines { color: #10b981 !important; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5) !important; }
        .fa-wrench { color: #06b6d4 !important; text-shadow: 0 0 10px rgba(6, 182, 212, 0.5) !important; }
        .fa-building-o { color: #6366f1 !important; text-shadow: 0 0 10px rgba(99, 102, 241, 0.5) !important; }
        .fa-dropbox { color: #f59e0b !important; text-shadow: 0 0 10px rgba(245, 158, 11, 0.5) !important; }
        .fa-exchange { color: #8b5cf6 !important; text-shadow: 0 0 10px rgba(139, 92, 246, 0.5) !important; }
        .fa-dot-circle-o { color: #94a3b8 !important; }
        .fa-circle-o { color: #64748b !important; }
        .bi-shield-check { color: #fbbf24 !important; text-shadow: 0 0 10px rgba(251, 191, 36, 0.5) !important; }

        /* ═══════════════════════════════════════════════════════════
           📜 SCROLLBAR
           ═══════════════════════════════════════════════════════════ */

        .sidebar::-webkit-scrollbar {
            width: 8px !important;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2) !important;
            border-radius: 10px !important;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 10px !important;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5) !important;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2 0%, #667eea 100%) !important;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.8) !important;
        }

        /* ═══════════════════════════════════════════════════════════
           ✨ TEXT STYLING
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu > li > a > span {
            /* Remove defaults */
            all: unset !important;

            /* Display */
            display: inline-block !important;

            /* Layout */
            flex: 1 !important;

            /* Text */
            font-size: 14px !important;
            letter-spacing: 0.3px !important;
            color: inherit !important;
        }

        /* ═══════════════════════════════════════════════════════════
           🎭 ADDITIONAL EFFECTS
           ═══════════════════════════════════════════════════════════ */

        .sidebar-menu > li::after {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: linear-gradient(90deg,
            transparent 0%,
            rgba(102, 126, 234, 0.1) 50%,
            transparent 100%) !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            pointer-events: none !important;
            border-radius: 10px !important;
        }

        .sidebar-menu > li:hover::after {
            opacity: 1 !important;
            animation: shimmer 1.5s ease-in-out infinite !important;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .sidebar-menu > li.active {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3) !important;
        }

        .sidebar-menu > li.active > a::after {
            content: '' !important;
            position: absolute !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            background: #667eea !important;
            border-radius: 50% !important;
            box-shadow: 0 0 10px #667eea !important;
            animation: pulse 2s ease-in-out infinite !important;
        }
    </style>

    <section class="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <a href="{{route('dashboard.index')}}">
                <img src="{{ Files::getUrl(Settings::get('system_logo')) }}" alt="Logo">
            </a>
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

            @if(auth()->user()->hasAnyPermission(['view-products', 'view-lenses', 'view-categories', 'view-brands', 'view-models']))
                <div class="sidebar-divider"></div>
                <div class="sidebar-category">Products & Inventory</div>

                <!-- Manage Products -->
                <li class="treeview {{ in_array(Request::route()->getName(), ['dashboard.get-all-products', 'dashboard.get-all-categories', 'dashboard.get-all-brands', 'dashboard.get-all-models', 'dashboard.get-glassess-lenses']) ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="fa fa-trello"></i>
                        <span>Manage Products</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @can('view-products')
                            <li><a href="{{route('dashboard.get-all-products')}}"><i class="fa fa-circle-o"></i> Products</a></li>
                        @endcan

                        @can('view-lenses')
                            <li><a href="{{route('dashboard.get-glassess-lenses')}}"><i class="fa fa-circle-o"></i> Lenses</a></li>
                        @endcan

                        @can('view-categories')
                            <li><a href="{{route('dashboard.get-all-categories')}}"><i class="fa fa-circle-o"></i> Categories</a></li>
                        @endcan

                        @can('view-brands')
                            <li><a href="{{route('dashboard.get-all-brands')}}"><i class="fa fa-circle-o"></i> Brands</a></li>
                        @endcan

                        @can('view-models')
                            <li><a href="{{route('dashboard.get-all-models')}}"><i class="fa fa-circle-o"></i> Models</a></li>
                        @endcan
                    </ul>
                </li>
            @endif

            <!-- Stock Management -->
            @if(auth()->user()->hasAnyPermission(['view-branches', 'view-stock', 'view-transfers']))
                <li class="treeview {{ str_contains(Request::route()->getName(), 'branches.') || str_contains(Request::route()->getName(), 'stock-transfers.') ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="bi bi-box-seam"></i>
                        <span>Stock Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Branches -->
                        @can('view-branches')
                            <li class="treeview {{ str_contains(Request::route()->getName(), 'branches.index') || str_contains(Request::route()->getName(), 'branches.create') || str_contains(Request::route()->getName(), 'branches.statistics') ? 'active' : '' }}">
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
                            <li class="treeview {{ str_contains(Request::route()->getName(), 'branches.stock.') ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-dropbox"></i> Branch Stock
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @php
                                        $defaultBranch = \App\Branch::where('is_active', true)->orderBy('is_main', 'desc')->first();
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
                            <li class="treeview {{ str_contains(Request::route()->getName(), 'branches.stock.') ? 'active' : '' }}">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-dropbox"></i> Branch Stock
                                    <i class="fa fa-angle-left pull-right" style="margin-left: 7px !important;"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @php
                                        // ✅ لو اليوزر عنده صلاحية على كل البرانشات
                                        if (auth()->user()->canSeeAllBranches()) {
                                            // يجيله البرانش الرئيسي أو أول برانش active
                                            $defaultBranch = \App\Branch::where('is_active', true)->orderBy('is_main', 'desc')->first();
                                        } else {
                                            // ✅ يوزر عادي → يجيله فرعه بس
                                            $defaultBranch = auth()->user()->branch;
                                        }
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
                        @endcan

                        <!-- Stock Transfers -->
                        @can('view-transfers')
                            <li class="treeview {{ str_contains(Request::route()->getName(), 'stock-transfers.') ? 'active' : '' }}">
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
                                    @endcan
                                    <li class="{{ Request::route()->getName() == 'dashboard.stock-transfers.pending' ? 'active' : '' }}">
                                        <a href="{{ route('dashboard.stock-transfers.pending') }}">
                                            <i class="fa fa-dot-circle-o"></i> Pending
                                            @php
                                                $pendingCount = \App\StockTransfer::where('status', 'pending')->count();
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
                    <li class="treeview {{ str_contains(Request::route()->getName(), 'expenses.') ? 'active' : '' }}">
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

                        @can('view-cashier-report')
                            <li class="{{ $currentRoute == 'dashboard.get-cashier-transactions-report' ? 'active' : '' }}">
                                <a href="{{ route('dashboard.get-cashier-transactions-report') }}">
                                    <i class="fa fa-circle-o"></i> Cashier Transactions
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
                    <li class="treeview {{ str_contains(Request::route()->getName(), 'dashboard.roles.') ? 'active' : '' }}">
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
