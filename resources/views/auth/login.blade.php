<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Optics — تسجيل الدخول</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --navy:   #0a0f1e;
            --navy2:  #0d1530;
            --blue:   #1a56ff;
            --gold:   #f5a623;
            --white:  #ffffff;
            --gray:   #8a94a6;
            --glass:  rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
        }

        html, body { height:100%; font-family:'Tajawal',sans-serif; background:var(--navy); overflow-x:hidden; }

        /* ── BACKGROUND ── */
        .bg-scene {
            position:fixed; inset:0; z-index:0;
            background:
                radial-gradient(ellipse 80% 80% at 100% 0%,  #0e2a6e 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 0%   100%, #09173a 0%, transparent 60%),
                var(--navy);
        }
        .orb { position:absolute; border-radius:50%; filter:blur(80px); opacity:.25; animation:orb-drift 18s ease-in-out infinite alternate; }
        .orb-1 { width:520px; height:520px; background:#1a56ff; top:-200px; right:-150px; animation-delay:0s; }
        .orb-2 { width:400px; height:400px; background:#7c3aed; bottom:-100px; left:-100px; animation-delay:-6s; }
        .orb-3 { width:280px; height:280px; background:#f5a623; top:40%; left:30%; opacity:.1; animation-delay:-12s; }

        @keyframes orb-drift { from{transform:translate(0,0) scale(1)} to{transform:translate(60px,40px) scale(1.12)} }

        .grid-overlay {
            position:absolute; inset:0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size:60px 60px;
        }

        /* ── LAYOUT ── */
        .page-wrap {
            position:relative; z-index:1;
            min-height:100vh;
            display:grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ══ RIGHT PANEL (branding) — "start" side in RTL ══ */
        .right-panel {
            display:flex; flex-direction:column; justify-content:space-between;
            padding:50px 60px;
            position:relative; overflow:hidden;
        }
        .right-panel::after {
            content:'';
            position:absolute; top:0; left:0; bottom:0;
            width:1px;
            background:linear-gradient(to bottom, transparent, rgba(255,255,255,.12) 30%, rgba(255,255,255,.12) 70%, transparent);
        }

        /* Brand */
        .brand { display:flex; align-items:center; gap:14px; }
        .brand-logo {
            width:50px; height:50px;
            background:linear-gradient(135deg,var(--blue),#7c3aed);
            border-radius:14px;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 8px 24px rgba(26,86,255,.4);
        }
        .brand-logo img { width:34px; height:34px; object-fit:contain; filter:brightness(0) invert(1); }
        .brand-name { font-size:20px; font-weight:800; color:var(--white); }
        .brand-name span { color:var(--gold); }

        /* Hero */
        .hero-content { flex:1; display:flex; flex-direction:column; justify-content:center; padding:40px 0; }

        .badge-pill {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(245,166,35,.15);
            border:1px solid rgba(245,166,35,.3);
            color:var(--gold); font-size:13px; font-weight:700;
            padding:5px 14px; border-radius:50px;
            margin-bottom:24px; width:fit-content;
        }

        .hero-title {
            font-size:clamp(30px,3.2vw,50px);
            font-weight:900; color:var(--white);
            line-height:1.2; letter-spacing:-0.5px;
            margin-bottom:18px;
        }
        .hero-title .accent { color:var(--blue); }
        .hero-title .gold   { color:var(--gold); }

        .hero-sub {
            font-size:16px; font-weight:400; color:var(--gray);
            line-height:1.8; max-width:420px; margin-bottom:44px;
        }

        /* Features */
        .features { display:flex; flex-direction:column; gap:14px; }
        .feature-item {
            display:flex; align-items:flex-start; gap:14px;
            background:var(--glass); border:1px solid var(--border);
            border-radius:14px; padding:16px 18px;
            backdrop-filter:blur(10px); transition:all .3s;
        }
        .feature-item:hover { background:rgba(255,255,255,.06); border-color:rgba(255,255,255,.15); transform:translateX(-4px); }
        .feature-icon {
            width:40px; height:40px; flex-shrink:0; border-radius:10px;
            display:flex; align-items:center; justify-content:center; font-size:18px;
        }
        .feature-icon.blue   { background:rgba(26,86,255,.2);  color:#4d8bff; }
        .feature-icon.purple { background:rgba(124,58,237,.2); color:#a78bfa; }
        .feature-icon.gold   { background:rgba(245,166,35,.2); color:var(--gold); }
        .feature-text h5 { font-size:14px; font-weight:700; color:var(--white); margin-bottom:3px; }
        .feature-text p  { font-size:12px; color:var(--gray); line-height:1.6; }

        /* Footer of right panel */
        .panel-footer { display:flex; align-items:center; gap:8px; color:var(--gray); font-size:13px; }
        .panel-footer .dot { width:6px; height:6px; border-radius:50%; background:#22c55e; box-shadow:0 0 6px #22c55e; }
        .panel-footer .status-txt { color:#22c55e; font-weight:700; }

        /* ══ LEFT PANEL (form) ══ */
        .left-panel {
            display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            padding:50px 70px;
        }

        .login-card { width:100%; max-width:420px; }

        .login-card-header { text-align:center; margin-bottom:32px; }
        .login-card-header h2 { font-size:28px; font-weight:900; color:var(--white); margin-bottom:8px; }
        .login-card-header p  { font-size:15px; color:var(--gray); }

        /* Time strip */
        .time-strip {
            display:flex; align-items:center; justify-content:center; gap:8px;
            background:rgba(255,255,255,.04); border:1px solid var(--border);
            border-radius:10px; padding:9px 16px; margin-bottom:28px;
            color:var(--gray); font-size:13px; font-variant-numeric:tabular-nums;
            direction:ltr; /* always LTR for clock */
        }
        .time-strip i { color:var(--blue); font-size:14px; }
        #live-time  { color:var(--white); font-weight:700; font-size:14px; }

        /* Form */
        .form-field { margin-bottom:20px; }
        .form-field label { display:block; font-size:14px; font-weight:700; color:var(--gray); margin-bottom:8px; }

        .input-group { position:relative; }

        /* Icons: in RTL, icons go to the RIGHT side (start side) */
        .input-icon-start {
            position:absolute; right:16px; top:50%; transform:translateY(-50%);
            color:#4d5870; font-size:16px; pointer-events:none; transition:color .25s;
        }
        .input-toggle-end {
            position:absolute; left:16px; top:50%; transform:translateY(-50%);
            color:#4d5870; font-size:16px;
            cursor:pointer; background:none; border:none; padding:0; transition:color .25s;
        }
        .input-toggle-end:hover { color:var(--blue); }

        .form-control {
            width:100%;
            background:rgba(255,255,255,.05);
            border:1.5px solid rgba(255,255,255,.1);
            border-radius:12px;
            color:var(--white); font-size:15px; font-family:'Tajawal',sans-serif;
            padding:13px 44px 13px 16px; /* right padding for icon */
            outline:none; transition:all .25s;
            direction:ltr; /* emails/passwords always LTR */
            text-align:right;
        }
        .form-control::placeholder { color:#3d4560; }
        .form-control:focus {
            border-color:var(--blue);
            background:rgba(26,86,255,.07);
            box-shadow:0 0 0 4px rgba(26,86,255,.12);
        }
        .input-group:focus-within .input-icon-start { color:var(--blue); }
        .form-control.has-error { border-color:#ef4444; }
        .error-msg { font-size:13px; color:#f87171; margin-top:6px; display:flex; align-items:center; gap:5px; }

        /* Meta row */
        .form-meta {
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:24px;
        }
        .remember-wrap { display:flex; align-items:center; gap:8px; cursor:pointer; flex-direction:row-reverse; }
        .remember-wrap input[type=checkbox] { width:16px; height:16px; accent-color:var(--blue); cursor:pointer; }
        .remember-wrap span { font-size:14px; color:var(--gray); user-select:none; }
        .forgot-link { font-size:14px; color:var(--blue); font-weight:700; text-decoration:none; }
        .forgot-link:hover { color:#6fa3ff; }

        /* Submit */
        .btn-submit {
            width:100%; padding:14px;
            background:linear-gradient(135deg,var(--blue) 0%,#7c3aed 100%);
            color:var(--white); border:none; border-radius:12px;
            font-size:16px; font-weight:800; font-family:'Tajawal',sans-serif;
            cursor:pointer; letter-spacing:.3px;
            transition:all .3s;
            box-shadow:0 6px 24px rgba(26,86,255,.35);
            position:relative; overflow:hidden;
        }
        .btn-submit::before {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg,#4d8bff 0%,#a855f7 100%);
            opacity:0; transition:opacity .3s;
        }
        .btn-submit:hover::before { opacity:1; }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(26,86,255,.45); }
        .btn-submit:active { transform:translateY(0); }
        .btn-submit .btn-inner { position:relative; z-index:1; display:flex; align-items:center; justify-content:center; gap:10px; }
        .btn-submit.loading .btn-inner { opacity:0; }
        .btn-submit.loading::after {
            content:''; position:absolute;
            width:22px; height:22px; top:50%; left:50%; margin:-11px 0 0 -11px;
            border:3px solid rgba(255,255,255,.3); border-top-color:#fff;
            border-radius:50%; animation:spin .7s linear infinite;
        }
        @keyframes spin { to { transform:rotate(360deg); } }

        /* Divider */
        .divider {
            display:flex; align-items:center; gap:12px;
            margin:22px 0; color:#3d4560; font-size:12px; font-weight:600;
        }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.07); }

        /* Card footer */
        .card-footer-txt { text-align:center; color:var(--gray); font-size:13px; }
        .card-footer-txt strong { color:var(--white); }

        /* Responsive */
        @media(max-width:900px) {
            .page-wrap { grid-template-columns:1fr; }
            .right-panel { display:none; }
            .left-panel { padding:40px 24px; }
        }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-overlay"></div>
</div>

<div class="page-wrap">

    <!-- ════════ اللوحة اليمنى — الهوية ════════ -->
    <div class="right-panel">

        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('assets/img/icon.png') }}" alt="logo">
            </div>
            <div class="brand-name">Modern <span>Optics</span></div>
        </div>

        <div class="hero-content">
            <div class="badge-pill">
                <i class="bi bi-stars"></i>
                نظام إدارة البصريات المتكامل
            </div>

            <h1 class="hero-title">
                أدِر مشروعك<br>
                <span class="accent">بوضوح تام.</span>
                <span class="gold">✨</span>
            </h1>

            <p class="hero-sub">
                نظام متكامل لإدارة محلات البصريات — من المخزون والمبيعات
                إلى وصفات العملاء وتقارير الفروع في لحظة.
            </p>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon blue"><i class="bi bi-bar-chart-line-fill"></i></div>
                    <div class="feature-text">
                        <h5>تقارير لحظية</h5>
                        <p>تقارير المبيعات وأداء الفروع والملخصات المالية في متناول يدك.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon purple"><i class="bi bi-boxes"></i></div>
                    <div class="feature-text">
                        <h5>إدارة ذكية للمخزون</h5>
                        <p>تتبع العدسات والإطارات عبر جميع الفروع مع تنبيهات المخزون المنخفض.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon gold"><i class="bi bi-robot"></i></div>
                    <div class="feature-text">
                        <h5>المساعد الذكي</h5>
                        <p>اسأل عن أي شيء — مبيعات اليوم، أفضل المنتجات، بيانات العملاء — في ثوانٍ.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <span class="dot"></span>
            <span>حالة النظام:</span>
            <span class="status-txt">يعمل بكامل طاقته</span>
        </div>
    </div>


    <!-- ════════ اللوحة اليسرى — نموذج الدخول ════════ -->
    <div class="left-panel">
        <div class="login-card">

            <div class="login-card-header">
                <h2>أهلاً بعودتك 👋</h2>
                <p>سجّل دخولك إلى لوحة التحكم</p>
            </div>

            <!-- ساعة حية -->
            <div class="time-strip">
                <i class="bi bi-clock"></i>
                <span id="live-time">--:--:-- --</span>
                <span style="margin:0 6px;color:#3d4560;">•</span>
                <span id="live-date" style="font-size:12px;"></span>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <!-- البريد الإلكتروني -->
                <div class="form-field">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-group">
                        <i class="bi bi-envelope-fill input-icon-start"></i>
                        <input
                            id="email" type="email" name="email"
                            class="form-control @error('email') has-error @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@company.com"
                            required autocomplete="email" autofocus
                        >
                    </div>
                    @error('email')
                        <div class="error-msg">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- كلمة المرور -->
                <div class="form-field">
                    <label for="password">كلمة المرور</label>
                    <div class="input-group">
                        <i class="bi bi-lock-fill input-icon-start"></i>
                        <input
                            id="password" type="password" name="password"
                            class="form-control @error('password') has-error @enderror"
                            placeholder="أدخل كلمة المرور"
                            required autocomplete="current-password"
                        >
                        <button type="button" class="input-toggle-end" id="togglePass" title="إظهار / إخفاء">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- تذكرني + نسيت -->
                <div class="form-meta">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>تذكّرني</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">نسيت كلمة المرور؟</a>
                </div>

                <!-- زر الدخول -->
                <button type="submit" class="btn-submit" id="loginBtn">
                    <span class="btn-inner">
                        <i class="bi bi-box-arrow-in-right"></i>
                        دخول إلى لوحة التحكم
                    </span>
                </button>

            </form>

            <div class="divider">وصول محمي ومؤمّن</div>

            <div class="card-footer-txt">
                <p>&copy; {{ date('Y') }} <strong>Modern Optics</strong> &nbsp;·&nbsp; جميع الحقوق محفوظة</p>
                <p style="margin-top:5px;color:#2d3550;font-size:12px;">الدخول غير المصرح به محظور تماماً</p>
            </div>

        </div>
    </div>

</div>

<script>
    /* ── ساعة حية ── */
    var DAYS_AR   = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    var MONTHS_AR = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];

    function pad(n) { return n < 10 ? '0'+n : ''+n; }

    function updateTime() {
        var now  = new Date();
        var h    = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
        var ampm = h >= 12 ? 'م' : 'ص';
        h = h % 12 || 12;
        document.getElementById('live-time').textContent = pad(h)+':'+pad(m)+':'+pad(s)+' '+ampm;
        document.getElementById('live-date').textContent =
            DAYS_AR[now.getDay()]+' '+now.getDate()+' '+MONTHS_AR[now.getMonth()]+' '+now.getFullYear();
    }
    updateTime();
    setInterval(updateTime, 1000);

    /* ── إظهار / إخفاء كلمة المرور ── */
    document.getElementById('togglePass').addEventListener('click', function() {
        var inp  = document.getElementById('password');
        var icon = document.getElementById('toggleIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.replace('bi-eye-fill','bi-eye-slash-fill');
        } else {
            inp.type = 'password';
            icon.classList.replace('bi-eye-slash-fill','bi-eye-fill');
        }
    });

    /* ── حالة تحميل الزر ── */
    document.getElementById('loginForm').addEventListener('submit', function() {
        var btn = document.getElementById('loginBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });

    /* ── تغيير لون الأيقونة عند التركيز ── */
    document.querySelectorAll('.form-control').forEach(function(inp) {
        inp.addEventListener('focus',  function() {
            var icon = this.parentNode.querySelector('.input-icon-start');
            if(icon) icon.style.color = '#1a56ff';
        });
        inp.addEventListener('blur', function() {
            var icon = this.parentNode.querySelector('.input-icon-start');
            if(icon) icon.style.color = '#4d5870';
        });
    });
</script>

</body>
</html>
