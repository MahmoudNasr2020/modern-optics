<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مودرن أوبتيكس — رؤية أفضل، حياة أفضل</title>
    <meta name="description" content="مودرن أوبتيكس — نظارات فاخرة ورعاية متكاملة للعيون في قطر. زوروا فروعنا المنتشرة في أرجاء المملكة.">
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ══════════════════════════════════════
           CSS VARIABLES — DARK / LIGHT THEMES
        ══════════════════════════════════════ */
        [data-theme="dark"] {
            --bg:        #050c1f;
            --bg2:       #0a1428;
            --bg3:       #0d1a35;
            --surface:   rgba(255,255,255,0.04);
            --surface2:  rgba(255,255,255,0.07);
            --border:    rgba(255,255,255,0.08);
            --border2:   rgba(255,255,255,0.14);
            --text:      #ffffff;
            --text2:     #cbd5e1;
            --muted:     #64748b;
            --nav-bg:    rgba(5,12,31,0.80);
            --card-bg:   rgba(255,255,255,0.04);
            --input-bg:  rgba(255,255,255,0.06);
            --shadow:    rgba(0,0,0,0.4);
            --orb1:      #1a56ff;
            --orb2:      #7c3aed;
            --hero-grad: linear-gradient(135deg,#1a56ff,#7c3aed,#f5a623);
        }
        [data-theme="light"] {
            --bg:        #f0f4ff;
            --bg2:       #e8eeff;
            --bg3:       #dde5ff;
            --surface:   rgba(255,255,255,0.85);
            --surface2:  rgba(255,255,255,0.95);
            --border:    rgba(26,86,255,0.12);
            --border2:   rgba(26,86,255,0.22);
            --text:      #0f172a;
            --text2:     #334155;
            --muted:     #64748b;
            --nav-bg:    rgba(240,244,255,0.88);
            --card-bg:   rgba(255,255,255,0.90);
            --input-bg:  rgba(255,255,255,0.95);
            --shadow:    rgba(26,86,255,0.10);
            --orb1:      #3b82f6;
            --orb2:      #8b5cf6;
            --hero-grad: linear-gradient(135deg,#1a56ff,#7c3aed,#f5a623);
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --blue:  #1a56ff;
            --gold:  #f5a623;
            --green: #22c55e;
        }

        html { scroll-behavior:smooth; }

        body {
            font-family:'Tajawal',sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            transition: background .4s, color .4s;
        }

        /* ══ BACKGROUND SCENE ══ */
        .bg-scene {
            position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden;
            transition: all .4s;
        }
        [data-theme="dark"] .bg-scene {
            background:
                radial-gradient(ellipse 70% 70% at 15% 5%,  #0b2460 0%, transparent 55%),
                radial-gradient(ellipse 50% 60% at 85% 85%, #1a0840 0%, transparent 50%),
                var(--bg);
        }
        [data-theme="light"] .bg-scene {
            background:
                radial-gradient(ellipse 70% 70% at 15% 5%,  #c7d8ff 0%, transparent 55%),
                radial-gradient(ellipse 50% 60% at 85% 85%, #ddd0ff 0%, transparent 50%),
                var(--bg);
        }

        .orb {
            position:absolute; border-radius:50%;
            filter:blur(90px); pointer-events:none;
        }
        [data-theme="dark"] .orb-a  { width:600px; height:600px; background:#1a56ff; top:-200px; right:-200px; opacity:.18; animation:drift1 22s ease-in-out infinite alternate; }
        [data-theme="dark"] .orb-b  { width:500px; height:500px; background:#7c3aed; bottom:-150px; left:-150px; opacity:.18; animation:drift2 28s ease-in-out infinite alternate; }
        [data-theme="dark"] .orb-c  { width:300px; height:300px; background:#f5a623; top:45%; left:45%; opacity:.07; animation:drift1 35s ease-in-out infinite alternate; }
        [data-theme="light"] .orb-a { width:600px; height:600px; background:#93c5fd; top:-200px; right:-200px; opacity:.55; animation:drift1 22s ease-in-out infinite alternate; }
        [data-theme="light"] .orb-b { width:500px; height:500px; background:#c4b5fd; bottom:-150px; left:-150px; opacity:.45; animation:drift2 28s ease-in-out infinite alternate; }
        [data-theme="light"] .orb-c { width:300px; height:300px; background:#fde68a; top:45%; left:45%; opacity:.3; animation:drift1 35s ease-in-out infinite alternate; }

        @keyframes drift1 { to { transform:translate(70px,50px) scale(1.12); } }
        @keyframes drift2 { to { transform:translate(-50px,-40px) scale(1.1); } }

        .grid-bg {
            position:fixed; inset:0; pointer-events:none;
            transition: opacity .4s;
        }
        [data-theme="dark"]  .grid-bg { background-image:linear-gradient(rgba(255,255,255,.022) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.022) 1px,transparent 1px); background-size:70px 70px; }
        [data-theme="light"] .grid-bg { background-image:linear-gradient(rgba(26,86,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(26,86,255,.04) 1px,transparent 1px); background-size:70px 70px; }

        /* Stars (dark only) */
        .stars { position:fixed; inset:0; pointer-events:none; transition:opacity .4s; }
        [data-theme="light"] .stars { opacity:0; }
        .star { position:absolute; border-radius:50%; background:#fff; animation:twinkle var(--d,3s) ease-in-out infinite; }
        @keyframes twinkle { 0%,100%{opacity:.08} 50%{opacity:.55} }

        /* ══ NAVBAR ══ */
        nav {
            position:fixed; top:0; left:0; right:0; z-index:200;
            padding:0 60px; height:70px;
            display:grid;
            grid-template-columns: 1fr auto 1fr;
            align-items:center;
            background: var(--nav-bg);
            backdrop-filter:blur(22px);
            border-bottom:1px solid var(--border);
            transition: background .4s, border-color .4s, box-shadow .3s;
        }
        nav.scrolled { box-shadow:0 4px 30px var(--shadow); }

        .nav-brand { display:flex; align-items:center; gap:12px; text-decoration:none; justify-self:start; }
        .nav-logo-box {
            width:44px; height:44px;
            border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            overflow:hidden;
        }
        .nav-logo-box img { width:44px; height:44px; object-fit:contain; border-radius:10px; }
        .nav-title { font-size:19px; font-weight:900; color:var(--text); transition:color .4s; }
        .nav-title span { color:var(--gold); }

        .nav-links { display:flex; align-items:center; gap:28px; justify-self:center; }
        .nav-links a { color:var(--muted); font-size:15px; font-weight:600; text-decoration:none; transition:color .2s; }
        .nav-links a:hover { color:var(--blue); }

        /* Theme toggle */
        .nav-end { display:flex; align-items:center; justify-self:end; }
        .theme-toggle {
            width:44px; height:44px; border-radius:12px;
            background:var(--surface); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; font-size:18px; transition:all .3s;
            color:var(--text2);
        }
        .theme-toggle:hover { background:var(--surface2); border-color:var(--blue); color:var(--blue); }

        /* ══ HERO ══ */
        .hero {
            position:relative; z-index:1;
            min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            text-align:center;
            padding:100px 24px 60px;
        }
        .hero-inner { max-width:820px; }

        .hero-badge {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(245,166,35,.12);
            border:1px solid rgba(245,166,35,.28);
            color:var(--gold); font-size:14px; font-weight:700;
            padding:7px 18px; border-radius:50px;
            margin-bottom:30px;
            animation:fadeUp .8s ease-out both;
        }

        .hero-headline {
            font-size:clamp(38px,7.5vw,92px);
            font-family:'Tajawal',sans-serif;
            font-weight:900;
            line-height:1.1;
            letter-spacing:-1px;
            margin-bottom:24px;
            animation:fadeUp .8s .1s ease-out both;
        }
        .hero-headline .line1 { display:block; color:var(--text); transition:color .4s; }
        .hero-headline .line2 {
            display:block;
            background:var(--hero-grad);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text;
        }

        .hero-desc {
            font-size:18px; color:var(--text2); line-height:1.85;
            max-width:580px; margin:0 auto 50px;
            animation:fadeUp .8s .2s ease-out both;
            transition:color .4s;
        }

        .hero-cta-wrap {
            display:flex; align-items:center; justify-content:center; gap:14px; flex-wrap:wrap;
            margin-bottom:56px;
            animation:fadeUp .8s .3s ease-out both;
        }

        .btn-primary {
            display:inline-flex; align-items:center; gap:9px;
            padding:14px 30px;
            background:linear-gradient(135deg,var(--blue),#7c3aed);
            color:#fff; font-size:16px; font-weight:800; font-family:'Tajawal',sans-serif;
            border-radius:12px; text-decoration:none;
            box-shadow:0 8px 28px rgba(26,86,255,.38);
            transition:all .3s; border:none; cursor:pointer;
        }
        .btn-primary:hover { transform:translateY(-3px); box-shadow:0 14px 40px rgba(26,86,255,.52); color:#fff; }

        .btn-outline {
            display:inline-flex; align-items:center; gap:8px;
            padding:13px 26px;
            background:var(--surface);
            border:1.5px solid var(--border2);
            color:var(--text); font-size:15px; font-weight:700; font-family:'Tajawal',sans-serif;
            border-radius:12px; text-decoration:none;
            backdrop-filter:blur(10px); transition:all .3s;
        }
        .btn-outline:hover { background:var(--surface2); border-color:var(--blue); color:var(--blue); }

        /* Stats strip */
        .stats-strip {
            display:flex; border-radius:18px; overflow:hidden;
            background:var(--surface); border:1px solid var(--border);
            backdrop-filter:blur(16px);
            animation:fadeUp .8s .4s ease-out both;
            transition: background .4s, border-color .4s;
        }
        .stat-box {
            flex:1; padding:22px 16px; text-align:center;
            border-left:1px solid var(--border);
            transition:border-color .4s;
        }
        .stat-box:last-child { border-left:none; }
        .stat-num { font-size:30px; font-weight:900; color:var(--text); letter-spacing:-1px; transition:color .4s; }
        .stat-num b { color:var(--gold); }
        .stat-lbl { font-size:13px; color:var(--muted); margin-top:3px; font-weight:600; }

        /* Scroll indicator */
        .scroll-hint {
            position:absolute; bottom:28px; left:50%; transform:translateX(-50%);
            display:flex; flex-direction:column; align-items:center; gap:6px;
            color:var(--muted); font-size:12px; letter-spacing:2px; text-transform:uppercase;
            animation:bounce 2.2s ease-in-out infinite;
            cursor:pointer; background:none; border:none; padding:0;
            text-decoration:none;
        }
        .scroll-hint:hover { color:var(--blue); }
        .scroll-hint i { font-size:20px; color:var(--blue); }
        @keyframes bounce { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(9px)} }
        @keyframes fadeUp  { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

        /* ══ COMING SOON ══ */
        .section-wrap { position:relative; z-index:1; }

        .cs-outer { max-width:1200px; margin:0 auto; padding:0 24px 100px; }
        .cs-card {
            position:relative; overflow:hidden;
            background:var(--surface);
            border:1px solid var(--border2);
            border-radius:26px;
            padding:60px 48px;
            text-align:center;
            backdrop-filter:blur(16px);
            transition: background .4s, border-color .4s;
        }
        .cs-card::before {
            content:''; position:absolute; inset:0; pointer-events:none;
            background:radial-gradient(ellipse 55% 55% at 50% -5%,rgba(26,86,255,.22),transparent);
        }
        [data-theme="light"] .cs-card::before { background:radial-gradient(ellipse 55% 55% at 50% -5%,rgba(26,86,255,.09),transparent); }

        .cs-icon {
            width:76px; height:76px; margin:0 auto 24px;
            background:linear-gradient(135deg,var(--blue),#7c3aed);
            border-radius:22px;
            display:flex; align-items:center; justify-content:center;
            font-size:34px; color:#fff;
            box-shadow:0 14px 40px rgba(26,86,255,.38);
        }
        .cs-title { font-size:clamp(22px,3vw,34px); font-weight:900; color:var(--text); margin-bottom:12px; transition:color .4s; }
        .cs-sub   { font-size:16px; color:var(--muted); line-height:1.8; max-width:520px; margin:0 auto 38px; }

        /* Countdown */
        .countdown { display:flex; align-items:center; justify-content:center; gap:14px; flex-wrap:wrap; margin-bottom:38px; }
        .cd-box {
            min-width:82px; padding:18px 14px;
            background:var(--surface2); border:1px solid var(--border);
            border-radius:16px; text-align:center;
            transition: background .4s, border-color .4s;
        }
        .cd-num { font-size:38px; font-weight:900; color:var(--blue); letter-spacing:-2px; font-variant-numeric:tabular-nums; }
        .cd-lbl { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-top:4px; font-weight:700; }
        .cd-sep  { font-size:30px; font-weight:900; color:var(--blue); margin-top:-4px; opacity:.6; }

        /* Subscribe */
        .sub-form { display:flex; gap:10px; max-width:420px; margin:0 auto; flex-wrap:wrap; justify-content:center; }
        .sub-input {
            flex:1; min-width:200px;
            padding:12px 18px;
            background:var(--input-bg); border:1.5px solid var(--border2);
            border-radius:11px; color:var(--text); font-size:14px; font-family:'Tajawal',sans-serif;
            outline:none; transition:all .25s;
            text-align:right;
        }
        .sub-input::placeholder { color:var(--muted); }
        .sub-input:focus { border-color:var(--blue); }
        .btn-sub {
            padding:12px 24px;
            background:linear-gradient(135deg,var(--blue),#7c3aed);
            color:#fff; font-size:14px; font-weight:800; font-family:'Tajawal',sans-serif;
            border:none; border-radius:11px; cursor:pointer; transition:all .3s;
        }
        .btn-sub:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(26,86,255,.4); }

        /* ══ SECTION HEADER ══ */
        .sec-header { text-align:center; padding:80px 24px 44px; }
        .sec-tag {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(26,86,255,.1); border:1px solid rgba(26,86,255,.2);
            color:var(--blue); font-size:13px; font-weight:700;
            padding:5px 16px; border-radius:50px; margin-bottom:16px;
        }
        .sec-title { font-size:clamp(26px,4vw,44px); font-weight:900; color:var(--text); letter-spacing:-.5px; margin-bottom:14px; transition:color .4s; }
        .sec-sub   { font-size:16px; color:var(--muted); max-width:500px; margin:0 auto; line-height:1.8; }

        /* ══ SERVICES GRID ══ */
        .services-grid {
            max-width:1200px; margin:0 auto;
            padding:0 24px 100px;
            display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            gap:22px;
        }
        .svc-card {
            background:var(--card-bg); border:1px solid var(--border);
            border-radius:22px; padding:32px;
            position:relative; overflow:hidden;
            transition:all .35s;
            cursor:default;
        }
        .svc-card::after {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
            background:var(--cg,linear-gradient(90deg,var(--blue),#7c3aed));
            opacity:0; transition:opacity .35s;
        }
        .svc-card:hover { background:var(--surface2); border-color:var(--border2); transform:translateY(-6px); box-shadow:0 16px 40px var(--shadow); }
        .svc-card:hover::after { opacity:1; }
        .svc-icon { width:54px; height:54px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px; }
        .svc-title { font-size:18px; font-weight:800; color:var(--text); margin-bottom:10px; transition:color .4s; }
        .svc-desc  { font-size:14px; color:var(--muted); line-height:1.8; }

        /* ══ WHY US ══ */
        .why-wrap {
            max-width:1200px; margin:0 auto; padding:0 24px 100px;
            display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center;
        }
        /* why-visual يحتضن الكارد + الـ chips */
        .why-visual {
            position:relative;
            padding:32px 20px;   /* مساحة للـ chips تطلع خارج الكارد */
        }

        .why-card-main {
            background:linear-gradient(135deg,rgba(26,86,255,.18),rgba(124,58,237,.18));
            border:1px solid rgba(26,86,255,.25);
            border-radius:24px; padding:44px; text-align:center;
            position:relative;
        }
        [data-theme="light"] .why-card-main { background:linear-gradient(135deg,rgba(26,86,255,.08),rgba(124,58,237,.08)); }
        .why-card-main .big-icon { font-size:80px; display:block; margin-bottom:18px; }
        .why-card-main h3 { font-size:22px; font-weight:900; color:var(--text); margin-bottom:10px; transition:color .4s; }
        .why-card-main p  { color:var(--muted); font-size:15px; line-height:1.75; }

        .float-chip {
            position:absolute; z-index:10;
            background:var(--surface2); border:1px solid var(--border2);
            border-radius:14px; padding:12px 16px;
            backdrop-filter:blur(16px);
            display:flex; align-items:center; gap:10px;
            animation:chipFloat 3s ease-in-out infinite;
            box-shadow:0 8px 28px var(--shadow);
            transition: background .4s, border-color .4s;
            white-space:nowrap;
        }
        [data-theme="light"] .float-chip { background:rgba(255,255,255,0.95); border-color:rgba(26,86,255,.22); box-shadow:0 8px 28px rgba(26,86,255,.12); }

        /* الأول في قطر — أعلى اليمين من الكارد */
        .float-chip.top { top:0; right:10%; animation-delay:0s; }
        /* التقييم — أسفل اليسار من الكارد */
        .float-chip.bot { bottom:0; left:10%; animation-delay:-1.5s; }

        .chip-icon { font-size:24px; }
        .chip-val  { font-size:15px; font-weight:900; color:var(--text); transition:color .4s; }
        .chip-lbl  { font-size:12px; color:var(--muted); }
        @keyframes chipFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

        .why-list { display:flex; flex-direction:column; gap:26px; }
        .why-item { display:flex; align-items:flex-start; gap:16px; }
        .wi-num {
            width:38px; height:38px; flex-shrink:0;
            background:linear-gradient(135deg,var(--blue),#7c3aed);
            border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:13px; font-weight:900; color:#fff;
        }
        .wi-txt h4 { font-size:16px; font-weight:800; color:var(--text); margin-bottom:5px; transition:color .4s; }
        .wi-txt p  { font-size:14px; color:var(--muted); line-height:1.7; }

        /* ══ BRANCHES ══ */
        .branches-inner { max-width:1200px; margin:0 auto; padding:0 24px 100px; }
        .branches-grid  { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px; margin-top:44px; }
        .branch-card {
            background:var(--card-bg); border:1px solid var(--border);
            border-radius:20px; padding:28px 24px;
            transition:all .35s;
        }
        .branch-card:hover { background:var(--surface2); border-color:var(--blue); transform:translateY(-5px); box-shadow:0 14px 36px var(--shadow); }
        .b-pin {
            width:44px; height:44px; border-radius:12px;
            background:rgba(245,166,35,.14); border:1px solid rgba(245,166,35,.25);
            display:flex; align-items:center; justify-content:center;
            font-size:20px; color:var(--gold); margin-bottom:16px;
        }
        .b-name { font-size:16px; font-weight:800; color:var(--text); margin-bottom:6px; transition:color .4s; }
        .b-addr { font-size:13px; color:var(--muted); line-height:1.65; }
        .b-open {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.22);
            color:#22c55e; font-size:11px; font-weight:700;
            padding:3px 11px; border-radius:50px; margin-top:12px;
        }
        .b-open .dot { width:6px; height:6px; border-radius:50%; background:#22c55e; animation:pulse 1.8s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* ══ CONTACT ══ */
        .contact-inner {
            max-width:1200px; margin:0 auto; padding:0 24px 100px;
            display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:start;
        }
        .contact-left h2 { font-size:clamp(26px,3.5vw,38px); font-weight:900; color:var(--text); margin-bottom:14px; transition:color .4s; }
        .contact-left p  { font-size:15px; color:var(--muted); line-height:1.85; margin-bottom:34px; }
        .ci-list { display:flex; flex-direction:column; gap:14px; }
        .ci-row {
            display:flex; align-items:center; gap:14px;
            background:var(--surface); border:1px solid var(--border);
            border-radius:14px; padding:14px 18px;
            transition: background .4s, border-color .4s;
        }
        .ci-icon {
            width:40px; height:40px; border-radius:10px;
            display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;
        }
        .ci-icon.i-blue   { background:rgba(26,86,255,.15); color:#4d8bff; }
        .ci-icon.i-green  { background:rgba(34,197,94,.12); color:#4ade80; }
        .ci-icon.i-gold   { background:rgba(245,166,35,.14); color:var(--gold); }
        .ci-info strong { display:block; color:var(--text2); font-size:13px; margin-bottom:2px; font-weight:700; transition:color .4s; }
        .ci-info span   { font-size:14px; color:var(--muted); }

        .contact-form {
            background:var(--surface); border:1px solid var(--border);
            border-radius:22px; padding:36px;
            backdrop-filter:blur(14px);
            transition: background .4s, border-color .4s;
        }
        .contact-form h3 { font-size:20px; font-weight:900; color:var(--text); margin-bottom:24px; transition:color .4s; }
        .cf-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .cf-field { margin-bottom:16px; }
        .cf-field label { display:block; font-size:13px; font-weight:700; color:var(--muted); margin-bottom:7px; }
        .cf-input, .cf-textarea {
            width:100%;
            background:var(--input-bg); border:1.5px solid var(--border);
            border-radius:10px; color:var(--text); font-size:14px; font-family:'Tajawal',sans-serif;
            padding:11px 14px; outline:none; transition:all .25s;
            text-align:right;
        }
        .cf-input::placeholder, .cf-textarea::placeholder { color:var(--muted); }
        .cf-input:focus, .cf-textarea:focus { border-color:var(--blue); background:var(--surface2); }
        .cf-textarea { min-height:100px; resize:vertical; }

        /* ══ FOOTER ══ */
        footer {
            position:relative; z-index:1;
            border-top:1px solid var(--border);
            padding:30px 60px;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;
            background:var(--nav-bg); backdrop-filter:blur(16px);
            transition: background .4s, border-color .4s;
        }
        .footer-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .footer-brand-name { font-size:16px; font-weight:900; color:var(--text); transition:color .4s; }
        .footer-brand-name span { color:var(--gold); }
        .footer-copy { font-size:13px; color:var(--muted); }
        .footer-socials { display:flex; gap:10px; }
        .soc-btn {
            width:38px; height:38px; border-radius:9px;
            background:var(--surface); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            color:var(--muted); font-size:17px; text-decoration:none; transition:all .25s;
        }
        .soc-btn:hover { background:rgba(26,86,255,.18); border-color:var(--blue); color:var(--blue); }

        /* ══ RESPONSIVE ══ */
        @media(max-width:900px) {
            nav { padding:0 20px; grid-template-columns:1fr auto; }
            .nav-links { display:none; }
            .nav-end   { justify-self:end; }
            .why-wrap, .contact-inner { grid-template-columns:1fr; }
            .float-chip { display:none; }
            footer { padding:22px 20px; flex-direction:column; text-align:center; }
        }
        @media(max-width:600px) {
            .stat-box { padding:16px 8px; }
            .stat-num { font-size:22px; }
            .cs-card  { padding:32px 20px; }
            .cf-row   { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<!-- Backgrounds -->
<div class="bg-scene">
    <div class="orb orb-a"></div>
    <div class="orb orb-b"></div>
    <div class="orb orb-c"></div>
</div>
<div class="grid-bg"></div>
<div class="stars" id="starField"></div>


<!-- ═══════════════════ NAVBAR ═══════════════════ -->
<nav id="mainNav">
    <a href="#" class="nav-brand">
        <div class="nav-logo-box">
            <img src="{{ asset('assets/img/main-logo.png') }}" alt="شعار مودرن أوبتيكس">
        </div>
        <span class="nav-title">مودرن <span>أوبتيكس</span></span>
    </a>

    <div class="nav-links">
        <a href="#services">خدماتنا</a>
        <a href="#branches">فروعنا</a>
        <a href="#contact">تواصل معنا</a>
    </div>

    <div class="nav-end">
        <button class="theme-toggle" id="themeBtn" title="تفعيل الوضع الداكن" onclick="toggleTheme()">
            <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
        </button>
    </div>
</nav>


<!-- ═══════════════════ HERO ═══════════════════ -->
<section class="hero section-wrap">
    <div class="hero-inner">

        <div class="hero-badge">
            <i class="bi bi-eyeglasses"></i>
            الوجهة الأولى للبصريات في قطر
        </div>

        <h1 class="hero-headline">
            <span class="line1">رؤية أفضل،</span>
            <span class="line2">حياة أفضل.</span>
        </h1>

        <p class="hero-desc">
            اكتشف أجود النظارات ورعاية العيون الاحترافية في مكان واحد.
            خبراؤنا وتشكيلتنا الواسعة هنا لتمنح عالمك الوضوح الذي يستحقه.
        </p>

        <div class="hero-cta-wrap">
            <a href="#contact" class="btn-primary">
                <i class="bi bi-calendar-check-fill"></i>
                احجز موعدك الآن
            </a>
            <a href="#services" class="btn-outline">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                استكشف خدماتنا
            </a>
        </div>

        <div class="stats-strip">
            <div class="stat-box">
                <div class="stat-num">١٥<b>+</b></div>
                <div class="stat-lbl">سنة خبرة</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">٥٠<b>ألف+</b></div>
                <div class="stat-lbl">عميل سعيد</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">٨<b>+</b></div>
                <div class="stat-lbl">فرع في قطر</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">٥٠٠<b>+</b></div>
                <div class="stat-lbl">تصميم إطار</div>
            </div>
        </div>
    </div>

    <button class="scroll-hint" onclick="document.getElementById('scroll-target').scrollIntoView({behavior:'smooth'})">
        <span>تمرير</span>
        <i class="bi bi-chevron-double-down"></i>
    </button>
</section>


<!-- ═══════════════════ COMING SOON ═══════════════════ -->
<div id="scroll-target" class="section-wrap">
<div class="cs-outer">
    <div class="cs-card">
        <div class="cs-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
        <h2 class="cs-title">موقعنا الجديد قادم قريباً!</h2>
        <p class="cs-sub">
            نبني لكم تجربة رائعة — منصة متكاملة للتسوق أونلاين، حجز المواعيد، وتجربة النظارات افتراضياً.
            ترقّبوا الإطلاق!
        </p>

        <div class="countdown">
            <div class="cd-box"><div class="cd-num" id="cdD">00</div><div class="cd-lbl">يوم</div></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><div class="cd-num" id="cdH">00</div><div class="cd-lbl">ساعة</div></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><div class="cd-num" id="cdM">00</div><div class="cd-lbl">دقيقة</div></div>
            <div class="cd-sep">:</div>
            <div class="cd-box"><div class="cd-num" id="cdS">00</div><div class="cd-lbl">ثانية</div></div>
        </div>

        <p style="color:var(--muted);font-size:14px;margin-bottom:14px;">أخبرني عند الإطلاق:</p>
        <div class="sub-form">
            <input type="email" class="sub-input" placeholder="بريدك الإلكتروني" id="notifyEmail" dir="ltr">
            <button class="btn-sub" onclick="doSubscribe()">أبلغني</button>
        </div>
        <div id="subMsg" style="display:none;margin-top:12px;color:#4ade80;font-size:14px;font-weight:700;">
            <i class="bi bi-check-circle-fill"></i> تم التسجيل! سنُبلغك عند الإطلاق.
        </div>
    </div>
</div>
</div>


<!-- ═══════════════════ SERVICES ═══════════════════ -->
<section id="services" class="section-wrap">
    <div class="sec-header">
        <div class="sec-tag"><i class="bi bi-stars"></i> ما نقدّمه</div>
        <h2 class="sec-title">خدمات بصرية بمستوى عالمي</h2>
        <p class="sec-sub">من فحص العيون الشامل إلى أحدث الإطارات العالمية — كل ما تحتاجه في مكان واحد.</p>
    </div>
    <div class="services-grid">
        <div class="svc-card" style="--cg:linear-gradient(90deg,#1a56ff,#7c3aed)">
            <div class="svc-icon" style="background:rgba(26,86,255,.14);color:#4d8bff;"><i class="bi bi-eye-fill"></i></div>
            <h3 class="svc-title">فحص العيون الشامل</h3>
            <p class="svc-desc">فحص بصري احترافي بأحدث الأجهزة الرقمية. كشف دقيق للوصفة الطبية وأمراض العين.</p>
        </div>
        <div class="svc-card" style="--cg:linear-gradient(90deg,#7c3aed,#a855f7)">
            <div class="svc-icon" style="background:rgba(124,58,237,.14);color:#a78bfa;"><i class="bi bi-eyeglasses"></i></div>
            <h3 class="svc-title">إطارات عالمية فاخرة</h3>
            <p class="svc-desc">أكثر من ٥٠٠ تصميم من أشهر الماركات العالمية — Ray-Ban، Gucci، Oakley وغيرها.</p>
        </div>
        <div class="svc-card" style="--cg:linear-gradient(90deg,#0891b2,#06b6d4)">
            <div class="svc-icon" style="background:rgba(6,182,212,.12);color:#22d3ee;"><i class="bi bi-droplet-half"></i></div>
            <h3 class="svc-title">العدسات اللاصقة</h3>
            <p class="svc-desc">عدسات يومية وشهرية ومتخصصة. خدمة تركيب احترافية ومجموعة واسعة من الماركات.</p>
        </div>
        <div class="svc-card" style="--cg:linear-gradient(90deg,#059669,#10b981)">
            <div class="svc-icon" style="background:rgba(16,185,129,.12);color:#34d399;"><i class="bi bi-patch-check-fill"></i></div>
            <h3 class="svc-title">طلاءات العدسات</h3>
            <p class="svc-desc">طلاء مضاد للانعكاس، فلتر الضوء الأزرق، حماية UV وعدسات فوتوكروميك للراحة القصوى.</p>
        </div>
        <div class="svc-card" style="--cg:linear-gradient(90deg,#d97706,#f59e0b)">
            <div class="svc-icon" style="background:rgba(245,158,11,.12);color:#fbbf24;"><i class="bi bi-tools"></i></div>
            <h3 class="svc-title">إصلاح وتعديل النظارات</h3>
            <p class="svc-desc">إصلاح سريع في نفس اليوم، تعديلات وقطع بديلة. ادخل واخرج بنظارات تناسبك تماماً.</p>
        </div>
        <div class="svc-card" style="--cg:linear-gradient(90deg,#dc2626,#ef4444)">
            <div class="svc-icon" style="background:rgba(239,68,68,.12);color:#f87171;"><i class="bi bi-shield-heart-fill"></i></div>
            <h3 class="svc-title">التأمين والشركات</h3>
            <p class="svc-desc">نتعامل مع جميع شركات التأمين الكبرى في قطر. عروض خاصة للشركات والمؤسسات.</p>
        </div>
    </div>
</section>


<!-- ═══════════════════ WHY US ═══════════════════ -->
<section class="section-wrap">
    <div class="sec-header">
        <div class="sec-tag"><i class="bi bi-award-fill"></i> لماذا نحن</div>
        <h2 class="sec-title">الخيار الواضح في قطر</h2>
        <p class="sec-sub">نجمع بين الخبرة والتقنية والاهتمام الحقيقي لنقدم تجربة بصرية لا مثيل لها.</p>
    </div>
    <div class="why-wrap">
        <div class="why-visual">
            <div class="float-chip top">
                <span class="chip-icon">🏆</span>
                <div><div class="chip-val">الأول في قطر</div><div class="chip-lbl">جودة الخدمة</div></div>
            </div>

            <div class="why-card-main">
                <span class="big-icon">👁️</span>
                <h3>أطباء بصريات معتمدون</h3>
                <p>فريقنا من المختصين المعتمدين يجمع سنوات من الخبرة مع شغف حقيقي بتحسين جودة رؤيتك.</p>
            </div>

            <div class="float-chip bot">
                <span class="chip-icon">⭐</span>
                <div><div class="chip-val">٤.٩ / ٥</div><div class="chip-lbl">تقييم العملاء</div></div>
            </div>
        </div>
        <div class="why-list">
            <div class="why-item">
                <div class="wi-num">٠١</div>
                <div class="wi-txt">
                    <h4>تقنية متطورة</h4>
                    <p>أجهزة قياس بصري رقمية، تصوير القرنية، وأجهزة OCT للحصول على أدق وصفة طبية ممكنة.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="wi-num">٠٢</div>
                <div class="wi-txt">
                    <h4>خدمة في نفس اليوم</h4>
                    <p>معظم الوصفات الاعتيادية جاهزة خلال ساعة. العدسات المميزة تُسلَّم في ٢–٣ أيام مع التوصيل للمنزل.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="wi-num">٠٣</div>
                <div class="wi-txt">
                    <h4>جميع بطاقات التأمين مقبولة</h4>
                    <p>فوترة مباشرة مع كبرى شركات التأمين في قطر بما فيها Seha وDaman وCigna وAllianz.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="wi-num">٠٤</div>
                <div class="wi-txt">
                    <h4>ضمان ما بعد البيع</h4>
                    <p>ضمان سنة كاملة على جميع الإطارات والعدسات. تعديل وتنظيف مجاني مدى الحياة — لأن رضاك أولويتنا.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════ BRANCHES ═══════════════════ -->
<section id="branches" class="section-wrap">
    <div class="branches-inner">
        <div class="sec-header" style="padding-top:0;">
            <div class="sec-tag"><i class="bi bi-geo-alt-fill"></i> مواقعنا</div>
            <h2 class="sec-title">فروعنا في قطر</h2>
            <p class="sec-sub">مواقع متعددة ومريحة في أنحاء قطر — دائماً بالقرب منك.</p>
        </div>
        <div class="branches-grid">
            <div class="branch-card">
                <div class="b-pin"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="b-name">الفرع الرئيسي — الريان</div>
                <div class="b-addr">شارع الريان، بجوار الريان مول، الدوحة، قطر</div>
                <div class="b-open"><span class="dot"></span> مفتوح الآن</div>
            </div>
            <div class="branch-card">
                <div class="b-pin"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="b-name">فرع الوكرة</div>
                <div class="b-addr">الوكرة مول، الدور الأرضي، الوكرة، قطر</div>
                <div class="b-open"><span class="dot"></span> مفتوح الآن</div>
            </div>
            <div class="branch-card">
                <div class="b-pin"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="b-name">فرع لوسيل</div>
                <div class="b-addr">بلاس فاندوم مول، مدينة لوسيل، قطر</div>
                <div class="b-open"><span class="dot"></span> مفتوح الآن</div>
            </div>
            <div class="branch-card">
                <div class="b-pin"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="b-name">فرع الخور</div>
                <div class="b-addr">الخور مول، الشارع الرئيسي، الخور، قطر</div>
                <div class="b-open"><span class="dot"></span> مفتوح الآن</div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════ CONTACT ═══════════════════ -->
<section id="contact" class="section-wrap">
    <div class="sec-header">
        <div class="sec-tag"><i class="bi bi-chat-dots-fill"></i> تواصل معنا</div>
        <h2 class="sec-title">نحن هنا لمساعدتك</h2>
        <p class="sec-sub">هل لديك سؤال أو تريد حجز موعد؟ فريقنا متاح ٧ أيام في الأسبوع.</p>
    </div>
    <div class="contact-inner">
        <div class="contact-left">
            <h2>تحدّث معنا عن رؤيتك</h2>
            <p>فريقنا الودود على أتم الاستعداد. زوروا أي فرع أو تواصلوا معنا عبر أي قناة من القنوات أدناه.</p>
            <div class="ci-list">
                <div class="ci-row">
                    <div class="ci-icon i-blue"><i class="bi bi-telephone-fill"></i></div>
                    <div class="ci-info">
                        <strong>الهاتف</strong>
                        <span dir="ltr">+974 4000 0000 · +974 5500 0000</span>
                    </div>
                </div>
                <div class="ci-row">
                    <div class="ci-icon i-green"><i class="bi bi-whatsapp"></i></div>
                    <div class="ci-info">
                        <strong>واتساب</strong>
                        <span dir="ltr">+974 5500 0000 (محادثة فقط)</span>
                    </div>
                </div>
                <div class="ci-row">
                    <div class="ci-icon i-gold"><i class="bi bi-envelope-fill"></i></div>
                    <div class="ci-info">
                        <strong>البريد الإلكتروني</strong>
                        <span dir="ltr">info@modernoptics.qa</span>
                    </div>
                </div>
                <div class="ci-row">
                    <div class="ci-icon i-blue"><i class="bi bi-clock-fill"></i></div>
                    <div class="ci-info">
                        <strong>ساعات العمل</strong>
                        <span>السبت–الخميس: ٩ص–١٠م &nbsp;·&nbsp; الجمعة: ٢م–١٠م</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <h3>أرسل لنا رسالة</h3>
            <form id="cForm" onsubmit="submitCForm(event)">
                <div class="cf-row">
                    <div class="cf-field">
                        <label>الاسم الأول</label>
                        <input type="text" class="cf-input" placeholder="أحمد">
                    </div>
                    <div class="cf-field">
                        <label>اسم العائلة</label>
                        <input type="text" class="cf-input" placeholder="الفارسي">
                    </div>
                </div>
                <div class="cf-field">
                    <label>البريد الإلكتروني</label>
                    <input type="email" class="cf-input" placeholder="ahmed@email.com" dir="ltr" style="text-align:left;">
                </div>
                <div class="cf-field">
                    <label>رقم الهاتف</label>
                    <input type="tel" class="cf-input" placeholder="+974 xxxx xxxx" dir="ltr" style="text-align:left;">
                </div>
                <div class="cf-field">
                    <label>الرسالة</label>
                    <textarea class="cf-textarea" placeholder="كيف يمكننا مساعدتك؟"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                    <i class="bi bi-send-fill"></i> إرسال الرسالة
                </button>
                <div id="cfOk" style="display:none;margin-top:12px;color:#4ade80;font-size:14px;font-weight:700;text-align:center;">
                    <i class="bi bi-check-circle-fill"></i> تم إرسال رسالتك! سنتواصل معك قريباً.
                </div>
            </form>
        </div>
    </div>
</section>


<!-- ═══════════════════ FOOTER ═══════════════════ -->
<footer>
    <a href="#" class="footer-brand">
        <div class="nav-logo-box">
            <img src="{{ asset('assets/img/main-logo.png') }}" alt="شعار مودرن أوبتيكس">
        </div>
        <span class="footer-brand-name">مودرن <span>أوبتيكس</span></span>
    </a>

    <div class="footer-copy">
        &copy; {{ date('Y') }} مودرن أوبتيكس — جميع الحقوق محفوظة
    </div>

    <div class="footer-socials">
        <a href="#" class="soc-btn"><i class="bi bi-instagram"></i></a>
        <a href="#" class="soc-btn"><i class="bi bi-facebook"></i></a>
        <a href="#" class="soc-btn"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="soc-btn"><i class="bi bi-whatsapp"></i></a>
    </div>
</footer>


<script>
/* ── نجوم ── */
(function(){
    var frag = document.createDocumentFragment();
    for(var i=0;i<90;i++){
        var s = document.createElement('span');
        s.className='star';
        var sz=Math.random()*2+1;
        s.style.cssText='width:'+sz+'px;height:'+sz+'px;top:'+Math.random()*100+'%;left:'+Math.random()*100+'%;--d:'+(Math.random()*4+2)+'s;animation-delay:-'+(Math.random()*8)+'s;';
        frag.appendChild(s);
    }
    document.getElementById('starField').appendChild(frag);
})();

/* ── Dark / Light Toggle ── */
var html = document.documentElement;
var themeBtn  = document.getElementById('themeBtn');
var themeIcon = document.getElementById('themeIcon');

// حفظ التفضيل في localStorage
var savedTheme = localStorage.getItem('mo-theme') || 'light';
html.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

function toggleTheme(){
    var current = html.getAttribute('data-theme');
    var next    = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('mo-theme', next);
    updateThemeIcon(next);
}

function updateThemeIcon(t){
    /* في الوضع الفاتح: اعرض أيقونة القمر (للتحويل للداكن) */
    /* في الوضع الداكن: اعرض أيقونة الشمس  (للتحويل للفاتح) */
    themeIcon.className = t === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
    themeBtn.title      = t === 'dark' ? 'الوضع الفاتح' : 'الوضع الداكن';
}

/* ── Navbar scroll ── */
window.addEventListener('scroll',function(){
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
});

/* ── Countdown ── */
var target = new Date('2026-09-01T00:00:00').getTime();
function pad(n){ return n < 10 ? '0'+n : ''+n; }
function tick(){
    var d = Math.max(0, target - Date.now());
    document.getElementById('cdD').textContent = pad(Math.floor(d/86400000));
    document.getElementById('cdH').textContent = pad(Math.floor((d%86400000)/3600000));
    document.getElementById('cdM').textContent = pad(Math.floor((d%3600000)/60000));
    document.getElementById('cdS').textContent = pad(Math.floor((d%60000)/1000));
}
tick(); setInterval(tick,1000);

/* ── Subscribe ── */
function doSubscribe(){
    var v = document.getElementById('notifyEmail').value;
    if(!v || v.indexOf('@')===-1){ alert('يرجى إدخال بريد إلكتروني صحيح.'); return; }
    document.getElementById('subMsg').style.display='block';
    document.getElementById('notifyEmail').value='';
}

/* ── Contact form ── */
function submitCForm(e){
    e.preventDefault();
    document.getElementById('cfOk').style.display='block';
    e.target.reset();
}

/* ── Fade-in on scroll ── */
var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
        if(e.isIntersecting){
            e.target.style.opacity='1';
            e.target.style.transform='translateY(0)';
        }
    });
},{threshold:0.08});

document.querySelectorAll('.svc-card,.branch-card,.why-item,.ci-row,.stat-box').forEach(function(el){
    el.style.opacity='0';
    el.style.transform='translateY(22px)';
    el.style.transition='opacity .55s ease-out, transform .55s ease-out';
    obs.observe(el);
});
</script>
</body>
</html>
