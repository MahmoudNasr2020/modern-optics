<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>دليل المستخدم — نظام إدارة البصريات</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════
   ROOT & THEME
═══════════════════════════════════════════════ */
:root {
    --brand:       #4f46e5;
    --brand-dark:  #3730a3;
    --brand-light: #e0e7ff;
    --accent:      #0ea5e9;
    --success:     #16a34a;
    --warning:     #d97706;
    --danger:      #dc2626;
    --bg:          #f8fafc;
    --surface:     #ffffff;
    --surface2:    #f1f5f9;
    --border:      #e2e8f0;
    --text:        #0f172a;
    --text-muted:  #64748b;
    --sidebar-w:   290px;
    --radius:      14px;
    --shadow:      0 4px 24px rgba(0,0,0,.08);
}
[data-theme="dark"] {
    --bg:         #0f172a;
    --surface:    #1e293b;
    --surface2:   #334155;
    --border:     #334155;
    --text:       #f1f5f9;
    --text-muted: #94a3b8;
    --brand-light:#1e1b4b;
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body {
    font-family:'Tajawal',sans-serif;
    background:var(--bg);
    color:var(--text);
    font-size:15px;
    line-height:1.75;
    transition:background .3s,color .3s;
}
a { color:var(--brand); text-decoration:none; }
a:hover { text-decoration:underline; }

/* ═══════════════════════════════════════════════
   SCROLLBAR
═══════════════════════════════════════════════ */
::-webkit-scrollbar { width:6px; height:6px; }
::-webkit-scrollbar-track { background:var(--surface2); }
::-webkit-scrollbar-thumb { background:var(--brand); border-radius:4px; }

/* ═══════════════════════════════════════════════
   TOP BAR
═══════════════════════════════════════════════ */
.topbar {
    position:fixed; top:0; left:0; right:0; z-index:1000;
    height:60px;
    background:linear-gradient(135deg,#4f46e5,#0ea5e9);
    display:flex; align-items:center; justify-content:space-between;
    padding:0 24px;
    box-shadow:0 2px 16px rgba(79,70,229,.35);
}
.topbar-brand {
    display:flex; align-items:center; gap:12px; color:#fff;
    font-size:18px; font-weight:800;
}
.topbar-brand i { font-size:22px; }
.topbar-actions { display:flex; align-items:center; gap:12px; }
.topbar-btn {
    background:rgba(255,255,255,.18);
    border:1px solid rgba(255,255,255,.35);
    color:#fff; padding:7px 16px; border-radius:8px;
    font-family:'Tajawal',sans-serif; font-size:13px; font-weight:700;
    cursor:pointer; transition:.2s; display:flex; align-items:center; gap:6px;
}
.topbar-btn:hover { background:rgba(255,255,255,.3); }
.menu-toggle {
    display:none; background:transparent; border:none; color:#fff;
    font-size:22px; cursor:pointer; padding:4px;
}

/* ═══════════════════════════════════════════════
   LAYOUT
═══════════════════════════════════════════════ */
.layout { display:flex; margin-top:60px; min-height:calc(100vh - 60px); }

/* ═══════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════ */
.sidebar {
    width:var(--sidebar-w); flex-shrink:0;
    background:var(--surface);
    border-left:1px solid var(--border);
    position:fixed; top:60px; right:0; bottom:0;
    overflow-y:auto; z-index:900;
    padding:20px 0 40px;
    transition:transform .3s;
}
.sidebar-title {
    font-size:11px; font-weight:800; color:var(--text-muted);
    text-transform:uppercase; letter-spacing:.08em;
    padding:8px 20px 6px;
}
.sidebar-item {
    display:block; padding:9px 20px;
    font-size:14px; font-weight:500; color:var(--text);
    border-right:3px solid transparent;
    transition:.15s; cursor:pointer;
    display:flex; align-items:center; gap:9px;
}
.sidebar-item:hover { background:var(--brand-light); color:var(--brand); border-right-color:var(--brand); text-decoration:none; }
.sidebar-item.active { background:var(--brand-light); color:var(--brand); border-right-color:var(--brand); font-weight:700; }
.sidebar-item .si-icon {
    width:28px; height:28px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    background:var(--surface2); font-size:14px; flex-shrink:0;
}
.sidebar-divider { height:1px; background:var(--border); margin:8px 14px; }

/* ═══════════════════════════════════════════════
   MAIN CONTENT
═══════════════════════════════════════════════ */
.main {
    flex:1; margin-right:var(--sidebar-w);
    padding:36px 40px 80px;
    max-width:100%;
}

/* ═══════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════ */
.hero {
    background:linear-gradient(135deg,#4f46e5 0%,#0ea5e9 100%);
    border-radius:var(--radius); padding:44px 40px;
    color:#fff; margin-bottom:40px;
    position:relative; overflow:hidden;
}
.hero::before {
    content:'';position:absolute;top:-80px;left:-80px;
    width:300px;height:300px;border-radius:50%;
    background:rgba(255,255,255,.08);
}
.hero::after {
    content:'';position:absolute;bottom:-60px;right:-60px;
    width:220px;height:220px;border-radius:50%;
    background:rgba(255,255,255,.06);
}
.hero-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.35);
    padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700;
    margin-bottom:16px;
}
.hero h1 { font-size:34px; font-weight:900; margin-bottom:12px; position:relative; z-index:1; }
.hero p { font-size:16px; opacity:.9; position:relative; z-index:1; max-width:580px; }
.hero-stats {
    display:flex; gap:24px; margin-top:28px; flex-wrap:wrap;
    position:relative; z-index:1;
}
.hero-stat {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    padding:12px 20px; border-radius:10px; text-align:center;
}
.hero-stat strong { display:block; font-size:24px; font-weight:900; }
.hero-stat span { font-size:12px; opacity:.85; }

/* ═══════════════════════════════════════════════
   SECTION
═══════════════════════════════════════════════ */
.doc-section {
    background:var(--surface); border-radius:var(--radius);
    border:1px solid var(--border);
    margin-bottom:32px;
    box-shadow:var(--shadow);
    overflow:hidden;
}
.section-header {
    padding:22px 28px 18px;
    border-bottom:2px solid var(--border);
    display:flex; align-items:center; gap:14px;
}
.section-icon {
    width:48px; height:48px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; flex-shrink:0;
}
.section-title { font-size:20px; font-weight:800; }
.section-subtitle { font-size:13px; color:var(--text-muted); margin-top:2px; }
.section-body { padding:24px 28px; }

/* ═══════════════════════════════════════════════
   INFO BLOCK
═══════════════════════════════════════════════ */
.info-block {
    background:var(--surface2); border-radius:10px;
    padding:16px 20px; margin-bottom:16px;
    border-right:4px solid var(--brand);
}
.info-block.green  { border-color:var(--success); }
.info-block.yellow { border-color:var(--warning); }
.info-block.red    { border-color:var(--danger); }
.info-block.blue   { border-color:var(--accent); }
.info-block strong { display:block; font-size:14px; font-weight:800; margin-bottom:4px; }
.info-block p { font-size:13.5px; color:var(--text-muted); margin:0; }

/* ═══════════════════════════════════════════════
   STEPS
═══════════════════════════════════════════════ */
.steps { list-style:none; margin:0; padding:0; }
.steps li {
    display:flex; gap:14px; align-items:flex-start;
    padding:10px 0; border-bottom:1px solid var(--border);
}
.steps li:last-child { border-bottom:none; }
.step-num {
    width:30px; height:30px; border-radius:50%;
    background:var(--brand); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:800; flex-shrink:0;
}
.step-text { font-size:14px; padding-top:5px; }
.step-text strong { display:block; margin-bottom:2px; }

/* ═══════════════════════════════════════════════
   TABLE
═══════════════════════════════════════════════ */
.doc-table { width:100%; border-collapse:collapse; font-size:13.5px; margin:12px 0; }
.doc-table th {
    background:var(--brand); color:#fff;
    padding:10px 14px; text-align:right; font-weight:700;
}
.doc-table td { padding:9px 14px; border-bottom:1px solid var(--border); vertical-align:top; }
.doc-table tr:last-child td { border-bottom:none; }
.doc-table tr:hover td { background:var(--surface2); }

/* ═══════════════════════════════════════════════
   BADGE
═══════════════════════════════════════════════ */
.badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:700;
}
.badge-blue   { background:#dbeafe; color:#1d4ed8; }
.badge-green  { background:#dcfce7; color:#15803d; }
.badge-orange { background:#ffedd5; color:#c2410c; }
.badge-red    { background:#fee2e2; color:#dc2626; }
.badge-purple { background:#ede9fe; color:#6d28d9; }
.badge-gray   { background:#f1f5f9; color:#475569; }

/* ═══════════════════════════════════════════════
   TIP BOX
═══════════════════════════════════════════════ */
.tip-box {
    display:flex; gap:12px; padding:14px 18px;
    border-radius:10px; margin:14px 0;
    font-size:13.5px;
}
.tip-box.tip   { background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; }
.tip-box.warn  { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }
.tip-box.info  { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
.tip-box.alert { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
.tip-box i { font-size:18px; flex-shrink:0; margin-top:2px; }

/* ═══════════════════════════════════════════════
   CARD GRID
═══════════════════════════════════════════════ */
.card-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:14px; margin:16px 0; }
.mini-card {
    background:var(--surface2); border-radius:10px;
    padding:16px; border:1px solid var(--border);
    display:flex; flex-direction:column; gap:6px;
}
.mini-card i { font-size:22px; color:var(--brand); }
.mini-card strong { font-size:14px; font-weight:700; }
.mini-card span { font-size:12.5px; color:var(--text-muted); }

/* ═══════════════════════════════════════════════
   HEADING ANCHORS
═══════════════════════════════════════════════ */
h2.doc-h2 {
    font-size:17px; font-weight:800; color:var(--brand);
    margin:20px 0 10px; display:flex; align-items:center; gap:8px;
}
h3.doc-h3 {
    font-size:15px; font-weight:700; color:var(--text);
    margin:16px 0 8px; display:flex; align-items:center; gap:6px;
}
p.doc-p { margin-bottom:12px; font-size:14.5px; }
ul.doc-ul { margin:8px 0 14px 20px; }
ul.doc-ul li { font-size:14px; margin-bottom:5px; }

/* ═══════════════════════════════════════════════
   BACK TO TOP
═══════════════════════════════════════════════ */
.back-top {
    position:fixed; bottom:28px; left:28px; z-index:999;
    width:44px; height:44px; border-radius:12px;
    background:var(--brand); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; box-shadow:0 4px 16px rgba(79,70,229,.4);
    cursor:pointer; transition:.2s; border:none;
    opacity:0; pointer-events:none;
}
.back-top.show { opacity:1; pointer-events:auto; }
.back-top:hover { transform:translateY(-3px); }

/* ═══════════════════════════════════════════════
   FOOTER
═══════════════════════════════════════════════ */
.doc-footer {
    text-align:center; padding:24px;
    color:var(--text-muted); font-size:13px;
    border-top:1px solid var(--border);
    margin-top:40px;
}

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media(max-width:900px) {
    .sidebar { transform:translateX(100%); }
    .sidebar.open { transform:translateX(0); }
    .main { margin-right:0; padding:24px 20px 60px; }
    .menu-toggle { display:block; }
    .hero { padding:28px 22px; }
    .hero h1 { font-size:24px; }
    .hero-stats { gap:12px; }
    .section-body { padding:18px 18px; }
}

/* ═══════════════════════════════════════════════
   PRINT
═══════════════════════════════════════════════ */
@media print {
    .topbar,.sidebar,.back-top,.menu-toggle { display:none !important; }
    .main { margin:0; padding:0; }
    .doc-section { box-shadow:none; break-inside:avoid; }
}
</style>
</head>
<body>

<!-- ══════════════ TOP BAR ══════════════ -->
<header class="topbar">
    <div class="topbar-brand">
        <i>👁️</i>
        <span>نظام إدارة البصريات — دليل المستخدم</span>
    </div>
    <div class="topbar-actions">
        <button class="topbar-btn" onclick="window.print()">🖨️ طباعة الدليل</button>
        <button class="topbar-btn" id="themeBtn" onclick="toggleTheme()">🌙 الوضع الليلي</button>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    </div>
</header>

<!-- ══════════════ LAYOUT ══════════════ -->
<div class="layout">

<!-- ══════════════ SIDEBAR ══════════════ -->
<nav class="sidebar" id="sidebar">

    <div class="sidebar-title">الأقسام الرئيسية</div>

    <a href="#intro"         class="sidebar-item active">
        <span class="si-icon">🏠</span> مقدمة ونظرة عامة
    </a>
    <a href="#products"      class="sidebar-item">
        <span class="si-icon">📦</span> المنتجات
    </a>
    <a href="#contact-lenses" class="sidebar-item">
        <span class="si-icon">👁️</span> عدسات الاتصال
    </a>
    <a href="#glass-lenses"  class="sidebar-item">
        <span class="si-icon">🔬</span> عدسات النظارات
    </a>
    <a href="#branches"      class="sidebar-item">
        <span class="si-icon">🏢</span> الفروع
    </a>
    <a href="#branch-stock"  class="sidebar-item">
        <span class="si-icon">🗂️</span> مخزون الفروع
    </a>
    <a href="#transfers"     class="sidebar-item">
        <span class="si-icon">🔄</span> طلبات نقل المخزون
    </a>
    <a href="#customers"     class="sidebar-item">
        <span class="si-icon">👤</span> العملاء
    </a>
    <a href="#eye-tests"     class="sidebar-item">
        <span class="si-icon">🩺</span> اختبارات العيون
    </a>
    <a href="#invoices"      class="sidebar-item">
        <span class="si-icon">🧾</span> الفواتير
    </a>
    <a href="#expenses"      class="sidebar-item">
        <span class="si-icon">💰</span> المصروفات
    </a>
    <a href="#reports"       class="sidebar-item">
        <span class="si-icon">📊</span> التقارير
    </a>
    <div class="sidebar-divider"></div>
    <div class="sidebar-title">إدارة النظام</div>
    <a href="#doctors"       class="sidebar-item">
        <span class="si-icon">👨‍⚕️</span> الأطباء
    </a>
    <a href="#insurance"     class="sidebar-item">
        <span class="si-icon">🛡️</span> شركات التأمين والكروت
    </a>
    <a href="#daily-close"   class="sidebar-item">
        <span class="si-icon">📅</span> الإغلاق اليومي
    </a>
    <a href="#users"         class="sidebar-item">
        <span class="si-icon">👥</span> المستخدمون
    </a>
    <a href="#roles"         class="sidebar-item">
        <span class="si-icon">🔐</span> الأدوار والصلاحيات
    </a>
    <a href="#settings"      class="sidebar-item">
        <span class="si-icon">⚙️</span> إعدادات النظام
    </a>

</nav>

<!-- ══════════════ MAIN ══════════════ -->
<main class="main">

<!-- HERO -->
<div class="hero" id="intro">
    <div class="hero-badge">📖 دليل المستخدم الرسمي</div>
    <h1>نظام إدارة البصريات الشامل</h1>
    <p>دليل مفصّل يشرح جميع أقسام وميزات النظام — من إدارة المنتجات والعملاء والفواتير وحتى التقارير والصلاحيات.</p>
    <div class="hero-stats">
        <div class="hero-stat"><strong>15+</strong><span>قسم رئيسي</span></div>
        <div class="hero-stat"><strong>40+</strong><span>تقرير متاح</span></div>
        <div class="hero-stat"><strong>127</strong><span>صلاحية قابلة للضبط</span></div>
        <div class="hero-stat"><strong>∞</strong><span>فروع وعملاء</span></div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     1. المنتجات
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="products">
    <div class="section-header">
        <div class="section-icon" style="background:#ede9fe;color:#6d28d9;">📦</div>
        <div>
            <div class="section-title">إدارة المنتجات</div>
            <div class="section-subtitle">إضافة وتعديل وتنظيم جميع المنتجات في النظام</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            قسم المنتجات هو قلب النظام — يتيح لك إدارة كل ما يُباع في المتجر من إطارات ونظارات وإكسسوارات.
            كل منتج مرتبط بفئة وماركة وموديل، ويمكن تتبع مخزونه في كل فرع بشكل منفصل.
        </p>

        <h2 class="doc-h2">📋 بيانات المنتج</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th><th>ملاحظة</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>كود المنتج</strong></td><td>رقم تعريفي فريد لكل منتج</td><td><span class="badge badge-blue">تلقائي / يدوي</span></td></tr>
                <tr><td><strong>الاسم</strong></td><td>اسم المنتج بالإنجليزية</td><td><span class="badge badge-orange">إلزامي</span></td></tr>
                <tr><td><strong>الاسم بالعربية</strong></td><td>اسم المنتج بالعربية</td><td><span class="badge badge-gray">اختياري</span></td></tr>
                <tr><td><strong>الوصف</strong></td><td>وصف تفصيلي للمنتج</td><td></td></tr>
                <tr><td><strong>الفئة</strong></td><td>الفئة الرئيسية (إطارات، شمسيات...)</td><td><span class="badge badge-orange">إلزامي</span></td></tr>
                <tr><td><strong>الماركة</strong></td><td>اسم الماركة التجارية (Ray-Ban, Gucci...)</td><td></td></tr>
                <tr><td><strong>الموديل</strong></td><td>موديل المنتج داخل الماركة</td><td></td></tr>
                <tr><td><strong>الباركود</strong></td><td>كود الباركود للمسح الضوئي</td><td></td></tr>
                <tr><td><strong>سعر الشراء</strong></td><td>سعر تكلفة المنتج</td><td></td></tr>
                <tr><td><strong>سعر البيع</strong></td><td>السعر الذي يظهر في الفاتورة</td><td><span class="badge badge-orange">إلزامي</span></td></tr>
                <tr><td><strong>الضريبة %</strong></td><td>نسبة الضريبة المضافة على المنتج</td><td></td></tr>
                <tr><td><strong>الحد الأدنى</strong></td><td>عند الوصول إليه يظهر تحذير مخزون منخفض</td><td></td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🚀 كيف تضيف منتجًا جديدًا؟</h2>
        <ol class="steps">
            <li>
                <span class="step-num">1</span>
                <div class="step-text"><strong>اذهب إلى قسم المنتجات من الشريط الجانبي</strong>ستجد قائمة بجميع المنتجات الموجودة.</div>
            </li>
            <li>
                <span class="step-num">2</span>
                <div class="step-text"><strong>اضغط على زر "إضافة منتج جديد"</strong>يفتح نموذج به جميع حقول المنتج.</div>
            </li>
            <li>
                <span class="step-num">3</span>
                <div class="step-text"><strong>أدخل بيانات المنتج</strong>الحقول الإلزامية مُعلّمة بنجمة (*). يجب تحديد الفئة على الأقل.</div>
            </li>
            <li>
                <span class="step-num">4</span>
                <div class="step-text"><strong>اضغط حفظ</strong>يُحفظ المنتج ويمكنك إضافته للمخزون بعد ذلك.</div>
            </li>
        </ol>

        <div class="tip-box tip">💡 <div><strong>نصيحة:</strong> يمكنك استيراد قائمة منتجات كاملة من ملف Excel دفعةً واحدة. اضغط على "قالب الاستيراد" لتنزيل النموذج الجاهز، ثم ارفعه بعد الملء.</div></div>

        <h2 class="doc-h2">🗂️ الفئات والماركات والموديلات</h2>
        <p class="doc-p">
            قبل إضافة المنتجات، يُفضّل إعداد شجرة التصنيف أولاً:
        </p>
        <div class="card-grid">
            <div class="mini-card">
                <i>🏷️</i>
                <strong>الفئات</strong>
                <span>مثل: إطارات طبية، نظارات شمسية، إكسسوارات. من قسم الإعدادات الجانبية.</span>
            </div>
            <div class="mini-card">
                <i>✨</i>
                <strong>الماركات</strong>
                <span>الماركات التجارية المتاحة في المتجر مثل Oakley أو Gucci.</span>
            </div>
            <div class="mini-card">
                <i>🔧</i>
                <strong>الموديلات</strong>
                <span>الموديلات التفصيلية تحت كل ماركة. تساعد في البحث والتصفية.</span>
            </div>
        </div>

        <h2 class="doc-h2">🔍 البحث في المنتجات</h2>
        <p class="doc-p">يمكنك البحث عن أي منتج من خلال عدة طرق:</p>
        <ul class="doc-ul">
            <li>🔤 بالاسم أو جزء منه</li>
            <li>🔢 بكود المنتج</li>
            <li>📊 بالباركود (مسح ضوئي أو إدخال يدوي)</li>
            <li>🏷️ بالفئة أو الماركة أو الموديل</li>
            <li>🎨 بالحجم أو اللون</li>
        </ul>

        <div class="tip-box warn">⚠️ <div><strong>تنبيه:</strong> حذف منتج له مخزون أو فواتير سابقة قد يؤثر على التقارير. يُنصح بتعطيله فقط بدلاً من حذفه.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     2. عدسات الاتصال
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="contact-lenses">
    <div class="section-header">
        <div class="section-icon" style="background:#cffafe;color:#0e7490;">👁️</div>
        <div>
            <div class="section-title">عدسات الاتصال (Contact Lenses)</div>
            <div class="section-subtitle">قسم منفصل خاص بعدسات الاتصال اللاصقة</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            عدسات الاتصال لها قسم خاص بها بسبب طبيعتها المختلفة — فهي تمتلك خصائص بصرية مثل القوة والقطر وخيار الاستخدام (يومية / شهرية...) إضافةً إلى ماركات مخصصة.
        </p>

        <h2 class="doc-h2">📋 بيانات عدسة الاتصال</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>اسم العدسة</strong></td><td>الاسم التجاري للعدسة</td></tr>
                <tr><td><strong>الماركة</strong></td><td>الشركة المصنّعة (من قسم ماركات العدسات)</td></tr>
                <tr><td><strong>نوع الاستخدام</strong></td><td>يومية / أسبوعية / شهرية / سنوية</td></tr>
                <tr><td><strong>القوة (Power)</strong></td><td>من سالب كبير إلى موجب كبير</td></tr>
                <tr><td><strong>القطر (Diameter)</strong></td><td>قطر العدسة بالملمتر</td></tr>
                <tr><td><strong>قوة الانحناء (BC)</strong></td><td>Base Curve — منحنى قاعدة العدسة</td></tr>
                <tr><td><strong>سعر البيع</strong></td><td>السعر للعميل</td></tr>
                <tr><td><strong>الباركود</strong></td><td>للمسح السريع عند البيع</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">📦 ماركات العدسات</h2>
        <p class="doc-p">
            في قسم <strong>ماركات العدسات</strong> يمكنك إضافة شركات العدسات مثل Acuvue, Bausch & Lomb, Alcon وغيرها.
            يمكنك أيضاً استيراد قائمة العدسات الخاصة بكل ماركة مباشرةً من Excel.
        </p>

        <div class="tip-box info">✅ <div><strong>ميزة الاستيراد:</strong> اضغط على "قالب الاستيراد" بجانب الماركة لتنزيل ملف Excel جاهز، أضف بيانات العدسات فيه ثم ارفعه — يُضيف النظام كل عدسة تلقائياً.</div></div>

        <h2 class="doc-h2">🧪 طلبات شراء عدسات المختبر (Lens Purchase Orders)</h2>
        <p class="doc-p">
            عندما يحتاج عميل عدسة غير موجودة في المخزون (عدسات مخصصة أو طبية مصنوعة حسب الوصفة)، يتم إنشاء <strong>طلب شراء من المختبر</strong>.
        </p>
        <ol class="steps">
            <li>
                <span class="step-num">1</span>
                <div class="step-text"><strong>إنشاء الطلب</strong> من فاتورة العميل، اختر "إضافة طلب مختبر" وحدد مواصفات العدسة المطلوبة.</div>
            </li>
            <li>
                <span class="step-num">2</span>
                <div class="step-text"><strong>الإرسال للمختبر</strong> بعد حفظ الطلب، تتبدل حالته إلى "مُرسَل للمختبر".</div>
            </li>
            <li>
                <span class="step-num">3</span>
                <div class="step-text"><strong>الاستلام</strong> عند وصول العدسات، اضغط "تسجيل الاستلام" وحدد الكمية المستلمة.</div>
            </li>
            <li>
                <span class="step-num">4</span>
                <div class="step-text"><strong>تسليم العميل</strong> تُغلق الفاتورة وتُسلَّم العدسة للعميل.</div>
            </li>
        </ol>

        <div class="info-block">
            <strong>🔴 العدسات التالفة</strong>
            <p>في حال تلف عدسة في المختبر أو أثناء الشحن، يمكن تسجيلها في قائمة "العدسات التالفة" وإنشاء طلب بديل تلقائياً.</p>
        </div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     3. عدسات النظارات
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="glass-lenses">
    <div class="section-header">
        <div class="section-icon" style="background:#fef9c3;color:#a16207;">🔬</div>
        <div>
            <div class="section-title">عدسات النظارات (Glass Lenses)</div>
            <div class="section-subtitle">العدسات الطبية المخصصة للإطارات</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            هذا القسم يختص بعدسات النظارات الطبية ذات القوة البصرية — وهي مختلفة عن عدسات الاتصال.
            تُستخدم عند إنشاء وصفة طبية لعميل يحتاج عدسات بقوة معينة داخل إطاره.
        </p>

        <h2 class="doc-h2">📋 خصائص عدسة النظارة</h2>
        <ul class="doc-ul">
            <li>📌 <strong>النوع:</strong> أحادية البؤرة، ثنائية، تصاعدية (Progressive)</li>
            <li>🔭 <strong>المادة:</strong> زجاج، بلاستيك (CR-39)، Polycarbonate، Hi-Index</li>
            <li>🛡️ <strong>الطلاء:</strong> مضاد للانعكاس، مضاد للخدش، أزرق (Blue Cut)</li>
            <li>📊 <strong>القوة:</strong> كروية (SPH)، اسطوانية (CYL)، محور (Axis)</li>
            <li>💲 <strong>سعر البيع</strong></li>
        </ul>

        <div class="tip-box tip">💡 <div>عند إنشاء فاتورة لعميل، يمكن إضافة عدسات النظارات مع الإطار في نفس الفاتورة، وتُحسب الأسعار تلقائياً.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     4. الفروع
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="branches">
    <div class="section-header">
        <div class="section-icon" style="background:#dcfce7;color:#15803d;">🏢</div>
        <div>
            <div class="section-title">إدارة الفروع</div>
            <div class="section-subtitle">إنشاء وإدارة فروع المتجر في مواقع مختلفة</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            يدعم النظام عدداً غير محدود من الفروع. لكل فرع مخزونه الخاص ومبيعاته وموظفيه.
            هناك دائماً <strong>فرع رئيسي واحد</strong> تصدر منه طلبات النقل للفروع الأخرى.
        </p>

        <h2 class="doc-h2">📋 بيانات الفرع</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>اسم الفرع</strong></td><td>الاسم الرسمي للفرع (عربي وإنجليزي)</td></tr>
                <tr><td><strong>كود الفرع</strong></td><td>يُنشأ تلقائياً بصيغة BR-0001</td></tr>
                <tr><td><strong>العنوان والمدينة</strong></td><td>موقع الفرع الجغرافي</td></tr>
                <tr><td><strong>رقم الهاتف</strong></td><td>رقم التواصل مع الفرع</td></tr>
                <tr><td><strong>اسم المدير</strong></td><td>الشخص المسؤول عن الفرع</td></tr>
                <tr><td><strong>وقت الفتح والإغلاق</strong></td><td>ساعات عمل الفرع</td></tr>
                <tr><td><strong>إيجار الفرع</strong></td><td>يُضاف إلى تقارير النفقات</td></tr>
                <tr><td><strong>الحالة</strong></td><td>مفعّل / موقوف مؤقتاً</td></tr>
                <tr><td><strong>فرع رئيسي</strong></td><td>تحديد إذا كان هذا الفرع هو المستودع الرئيسي</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">📊 إحصائيات الفروع</h2>
        <p class="doc-p">
            من قسم <strong>إحصائيات جميع الفروع</strong> تجد مقارنة شاملة بين الفروع تشمل:
        </p>
        <div class="card-grid">
            <div class="mini-card">
                <i>💰</i>
                <strong>إجمالي المبيعات</strong>
                <span>لكل فرع مقارنةً بالفروع الأخرى</span>
            </div>
            <div class="mini-card">
                <i>🧾</i>
                <strong>عدد الفواتير</strong>
                <span>عدد الفواتير الصادرة من كل فرع</span>
            </div>
            <div class="mini-card">
                <i>📦</i>
                <strong>قيمة المخزون</strong>
                <span>إجمالي قيمة المخزون في كل فرع</span>
            </div>
            <div class="mini-card">
                <i>📤</i>
                <strong>التصدير PDF</strong>
                <span>تقرير مقارن كامل قابل للطباعة</span>
            </div>
        </div>

        <div class="tip-box warn">⚠️ <div><strong>تنبيه:</strong> لا يمكن حذف فرع به مخزون أو فواتير. استخدم زر "إيقاف الفرع مؤقتاً" بدلاً من الحذف.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     5. مخزون الفروع
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="branch-stock">
    <div class="section-header">
        <div class="section-icon" style="background:#fce7f3;color:#9d174d;">🗂️</div>
        <div>
            <div class="section-title">مخزون الفروع</div>
            <div class="section-subtitle">متابعة وإدارة مستوى المخزون في كل فرع</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            كل فرع يملك قائمة مخزون خاصة به. يمكنك رؤية الكمية المتاحة من كل منتج وعدسة في أي فرع،
            وإضافة كميات أو تقليلها، وتلقّي تنبيهات عند انخفاض المخزون.
        </p>

        <h2 class="doc-h2">📋 عمليات المخزون</h2>
        <table class="doc-table">
            <thead>
                <tr><th>العملية</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><span class="badge badge-green">إضافة كمية</span></td><td>إضافة كمية للمخزون (استلام بضاعة جديدة)</td></tr>
                <tr><td><span class="badge badge-orange">تقليل كمية</span></td><td>خصم كمية من المخزون (تلف أو خطأ)</td></tr>
                <tr><td><span class="badge badge-blue">تعديل المخزون</span></td><td>تعديل الحد الأدنى والأقصى وبيانات أخرى</td></tr>
                <tr><td><span class="badge badge-red">حذف من المخزون</span></td><td>إزالة منتج كلياً من مخزون الفرع</td></tr>
                <tr><td><span class="badge badge-purple">سجل الحركات</span></td><td>عرض كل العمليات التي جرت على المنتج</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">⚠️ المخزون المنخفض</h2>
        <p class="doc-p">
            النظام يرصد تلقائياً المنتجات التي وصلت إلى أو تحت الحد الأدنى المحدد.
            يمكنك مراجعة هذه القائمة من <strong>قسم مخزون الفرع &gt; مخزون منخفض</strong>.
        </p>

        <h2 class="doc-h2">📊 تقارير المخزون وتصديره</h2>
        <div class="card-grid">
            <div class="mini-card">
                <i>📄</i>
                <strong>تقرير PDF</strong>
                <span>تقرير كامل بالمخزون قابل للطباعة والحفظ</span>
            </div>
            <div class="mini-card">
                <i>📊</i>
                <strong>تصدير Excel</strong>
                <span>قائمة المخزون (الكود، النوع، الوصف، الكمية) — لا تشمل الأصناف ذات الكمية صفر</span>
            </div>
            <div class="mini-card">
                <i>🖨️</i>
                <strong>طباعة مباشرة</strong>
                <span>طباعة التقرير مباشرةً من المتصفح</span>
            </div>
            <div class="mini-card">
                <i>📥</i>
                <strong>استيراد Excel</strong>
                <span>رفع كمية كبيرة من البيانات دفعةً واحدة</span>
            </div>
        </div>

        <h2 class="doc-h2">🔍 نظرة عامة على المخزون</h2>
        <p class="doc-p">
            من <strong>المخزون العام (Stock Overview)</strong> يمكنك البحث عن أي منتج ومعرفة توزيعه في جميع الفروع في آن واحد.
            يدعم البحث بالاسم والكود والباركود والفئة والماركة.
        </p>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     6. طلبات نقل المخزون
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="transfers">
    <div class="section-header">
        <div class="section-icon" style="background:#dbeafe;color:#1d4ed8;">🔄</div>
        <div>
            <div class="section-title">طلبات نقل المخزون (Stock Transfers)</div>
            <div class="section-subtitle">نقل المنتجات بين الفروع بنظام موافقة متعدد المراحل</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            عندما يحتاج فرع صنفاً معيناً من فرع آخر أو من المستودع الرئيسي، يتم إنشاء <strong>طلب نقل</strong>.
            يمر الطلب بعدة مراحل حتى يتم تسليم البضاعة وتحديث المخزون.
        </p>

        <h2 class="doc-h2">🔁 مراحل طلب النقل</h2>
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin:14px 0;">
            <div style="background:#fef9c3;border:1px solid #fde68a;padding:10px 16px;border-radius:10px;text-align:center;flex:1;min-width:120px;">
                <div style="font-size:20px;">📝</div>
                <div style="font-size:12px;font-weight:800;color:#92400e;">معلق</div>
                <div style="font-size:11px;color:#78350f;">Pending</div>
            </div>
            <div style="display:flex;align-items:center;font-size:20px;color:#94a3b8;">→</div>
            <div style="background:#ede9fe;border:1px solid #c4b5fd;padding:10px 16px;border-radius:10px;text-align:center;flex:1;min-width:120px;">
                <div style="font-size:20px;">✅</div>
                <div style="font-size:12px;font-weight:800;color:#6d28d9;">موافقة</div>
                <div style="font-size:11px;color:#5b21b6;">Approved</div>
            </div>
            <div style="display:flex;align-items:center;font-size:20px;color:#94a3b8;">→</div>
            <div style="background:#dbeafe;border:1px solid #93c5fd;padding:10px 16px;border-radius:10px;text-align:center;flex:1;min-width:120px;">
                <div style="font-size:20px;">🚚</div>
                <div style="font-size:12px;font-weight:800;color:#1d4ed8;">شحن</div>
                <div style="font-size:11px;color:#1e40af;">Shipped</div>
            </div>
            <div style="display:flex;align-items:center;font-size:20px;color:#94a3b8;">→</div>
            <div style="background:#dcfce7;border:1px solid #86efac;padding:10px 16px;border-radius:10px;text-align:center;flex:1;min-width:120px;">
                <div style="font-size:20px;">📦</div>
                <div style="font-size:12px;font-weight:800;color:#15803d;">مستلم</div>
                <div style="font-size:11px;color:#166534;">Received</div>
            </div>
        </div>

        <div class="info-block green">
            <strong>📌 متى يُحدَّث المخزون؟</strong>
            <p>يُخصم المخزون من الفرع المُرسِل فور الموافقة، ويُضاف للفرع المستقبِل لحظة تسجيل "الاستلام".</p>
        </div>

        <h2 class="doc-h2">🚀 كيف تنشئ طلب نقل؟</h2>
        <ol class="steps">
            <li>
                <span class="step-num">1</span>
                <div class="step-text"><strong>اذهب إلى تحويلات المخزون</strong> من القائمة الجانبية، ثم اضغط "إنشاء طلب نقل جديد".</div>
            </li>
            <li>
                <span class="step-num">2</span>
                <div class="step-text"><strong>حدد الفرع المُرسِل والمستقبِل</strong> وتاريخ النقل المطلوب.</div>
            </li>
            <li>
                <span class="step-num">3</span>
                <div class="step-text"><strong>أضف المنتجات</strong> ابحث عن كل منتج وحدد الكمية المطلوبة. يمكن إضافة منتجات متعددة في نفس الطلب.</div>
            </li>
            <li>
                <span class="step-num">4</span>
                <div class="step-text"><strong>احفظ الطلب</strong> يصبح بحالة "معلق" وينتظر الموافقة.</div>
            </li>
        </ol>

        <h2 class="doc-h2">📦 الطلب الجماعي (Bulk Request)</h2>
        <p class="doc-p">
            لطلب قائمة كبيرة من المنتجات دفعةً واحدة، استخدم ميزة <strong>الطلب الجماعي</strong>:
            نزّل قالب Excel، أدخل الكميات المطلوبة، ثم ارفع الملف — يُنشئ النظام الطلب تلقائياً.
        </p>

        <h2 class="doc-h2">📊 تقرير تحويلات المنتج</h2>
        <p class="doc-p">
            من <strong>تقارير النقل &gt; تقرير المنتجات</strong>، ابحث عن أي منتج لترى:
        </p>
        <ul class="doc-ul">
            <li>إجمالي حركات النقل</li>
            <li>عدد الطلبات المكتملة والمعلقة</li>
            <li>إجمالي الكميات المنقولة</li>
            <li>✅ <strong>الكمية الفعلية الحالية في المخزون</strong> (مجموع جميع الفروع)</li>
        </ul>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     7. العملاء
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="customers">
    <div class="section-header">
        <div class="section-icon" style="background:#fce7f3;color:#9d174d;">👤</div>
        <div>
            <div class="section-title">إدارة العملاء</div>
            <div class="section-subtitle">ملفات شخصية كاملة لكل عميل مع سجل الفواتير والمدفوعات</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            في قسم العملاء تجد جميع عملاء المتجر مع بياناتهم الكاملة وسجل مشترياتهم ومدفوعاتهم واختبارات عيونهم.
        </p>

        <h2 class="doc-h2">📋 بيانات العميل</h2>
        <table class="doc-table">
            <thead>
                <tr><th>القسم</th><th>البيانات</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>البيانات الشخصية</strong></td>
                    <td>الاسم (عربي / إنجليزي)، اللقب، الجنس، تاريخ الميلاد، الجنسية، رقم الهوية</td>
                </tr>
                <tr>
                    <td><strong>بيانات التواصل</strong></td>
                    <td>رقم الجوال مع كود الدولة، البريد الإلكتروني، رقم المكتب</td>
                </tr>
                <tr>
                    <td><strong>بيانات الحساب</strong></td>
                    <td>إجمالي المشتريات، نقاط الولاء، ملاحظات</td>
                </tr>
                <tr>
                    <td><strong>الإشعارات</strong></td>
                    <td>هل يقبل استقبال الرسائل والإشعارات؟</td>
                </tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🚀 إضافة عميل جديد</h2>
        <ol class="steps">
            <li><span class="step-num">1</span><div class="step-text"><strong>العملاء &gt; إضافة عميل جديد</strong></div></li>
            <li><span class="step-num">2</span><div class="step-text"><strong>أدخل الاسم ورقم الجوال على الأقل</strong> — هما الحقلان الإلزاميان الأساسيان.</div></li>
            <li><span class="step-num">3</span><div class="step-text"><strong>اضغط حفظ</strong> — يُنشأ رقم عميل فريد تلقائياً.</div></li>
        </ol>

        <h2 class="doc-h2">📑 كشف حساب العميل</h2>
        <p class="doc-p">
            من صفحة العميل اضغط على <strong>كشف الحساب</strong> لترى:
        </p>
        <ul class="doc-ul">
            <li>🧾 جميع الفواتير والمبالغ</li>
            <li>💵 المدفوع والمتبقي</li>
            <li>📅 تواريخ الفواتير والمدفوعات</li>
        </ul>
        <p class="doc-p">يمكن طباعة الكشف أو تصديره PDF لإرساله للعميل.</p>

        <div class="tip-box info">✅ <div><strong>البحث السريع:</strong> في نموذج الفاتورة يمكن البحث عن العميل باسمه أو رقم هاتفه أو رقم عميله مباشرةً دون الخروج من الفاتورة.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     8. اختبارات العيون
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="eye-tests">
    <div class="section-header">
        <div class="section-icon" style="background:#e0f2fe;color:#0369a1;">🩺</div>
        <div>
            <div class="section-title">اختبارات العيون</div>
            <div class="section-subtitle">تسجيل وحفظ الوصفات الطبية لكل عميل</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            لكل عميل سجل خاص بوصفاته الطبية (اختبارات العيون). يتضمن كل اختبار مواصفات العدسة لكلتا العينين
            ويُربط بالطبيب الذي أجرى الفحص.
        </p>

        <h2 class="doc-h2">📋 بيانات اختبار العيون</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>تاريخ الفحص</strong></td><td>تاريخ إجراء الاختبار</td></tr>
                <tr><td><strong>الطبيب</strong></td><td>الطبيب الذي أجرى الفحص</td></tr>
                <tr><td><strong>العين اليمنى (R)</strong></td><td>SPH، CYL، Axis، ADD، PD</td></tr>
                <tr><td><strong>العين اليسرى (L)</strong></td><td>SPH، CYL، Axis، ADD، PD</td></tr>
                <tr><td><strong>ملاحظات</strong></td><td>أي ملاحظات إضافية من الطبيب</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🖨️ طباعة الوصفة الطبية</h2>
        <p class="doc-p">
            بعد حفظ الاختبار، يمكن طباعة الوصفة مباشرةً بشكل احترافي يحتوي على شعار المتجر وبيانات العميل والطبيب.
        </p>

        <div class="tip-box tip">💡 <div>عند إنشاء فاتورة جديدة للعميل، يمكن استيراد بيانات اختبار العيون الأخير مباشرةً في الفاتورة لتحديد العدسات المناسبة.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     9. الفواتير
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="invoices">
    <div class="section-header">
        <div class="section-icon" style="background:#fef3c7;color:#d97706;">🧾</div>
        <div>
            <div class="section-title">نظام الفواتير</div>
            <div class="section-subtitle">إنشاء وإدارة فواتير البيع بجميع أنواعها</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            قسم الفواتير هو المكان الذي تتم فيه عمليات البيع اليومية. يدعم النظام الخصومات المتعددة،
            والدفع الجزئي، وربط الفاتورة بطبيب أو شركة تأمين أو كرت خصم.
        </p>

        <h2 class="doc-h2">🚀 كيف تنشئ فاتورة جديدة؟</h2>
        <ol class="steps">
            <li>
                <span class="step-num">1</span>
                <div class="step-text"><strong>ابحث عن العميل</strong> في شريط البحث العلوي. إن لم يكن موجوداً يمكن إضافته مباشرةً.</div>
            </li>
            <li>
                <span class="step-num">2</span>
                <div class="step-text"><strong>اختر الطبيب (اختياري)</strong> الطبيب الذي أحال هذا العميل أو أجرى فحصه.</div>
            </li>
            <li>
                <span class="step-num">3</span>
                <div class="step-text"><strong>أضف المنتجات</strong> ابحث بالاسم أو الكود أو الباركود. أضف الكمية والسعر لكل صنف.</div>
            </li>
            <li>
                <span class="step-num">4</span>
                <div class="step-text"><strong>أضف العدسات (إن وجدت)</strong> حدد نوع العدسة (اتصال أو طبية) ومواصفاتها.</div>
            </li>
            <li>
                <span class="step-num">5</span>
                <div class="step-text"><strong>طبّق الخصم (إن وجد)</strong> خصم نسبة مئوية أو مبلغ ثابت أو خصم التأمين أو الكرت.</div>
            </li>
            <li>
                <span class="step-num">6</span>
                <div class="step-text"><strong>أدخل المبلغ المدفوع</strong> يمكن دفع الكل أو جزء منه، والباقي يُسجّل كدَيْن.</div>
            </li>
            <li>
                <span class="step-num">7</span>
                <div class="step-text"><strong>احفظ الفاتورة</strong> تُنقص الكمية من المخزون تلقائياً ويُنشأ رقم فاتورة فريد.</div>
            </li>
        </ol>

        <h2 class="doc-h2">💳 أنواع الخصومات</h2>
        <table class="doc-table">
            <thead>
                <tr><th>النوع</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-blue">خصم عادي</span></td>
                    <td>نسبة مئوية أو مبلغ ثابت يُطبّق على إجمالي الفاتورة</td>
                </tr>
                <tr>
                    <td><span class="badge badge-green">خصم التأمين</span></td>
                    <td>العميل لديه تأمين صحي — تُضبط نسبة التأمين ويدفع العميل الفارق فقط</td>
                </tr>
                <tr>
                    <td><span class="badge badge-purple">خصم الكرت</span></td>
                    <td>عميل لديه كرت خصم من شركة معينة — يُطبّق خصم الكرت تلقائياً</td>
                </tr>
            </tbody>
        </table>

        <h2 class="doc-h2">📊 حالات الفاتورة</h2>
        <div class="card-grid">
            <div class="mini-card">
                <i>🕐</i>
                <strong>معلقة (Pending)</strong>
                <span>الفاتورة بها طلبات جاهزة أو دفعة جزئية فقط</span>
            </div>
            <div class="mini-card">
                <i>✅</i>
                <strong>مكتملة</strong>
                <span>الفاتورة مدفوعة بالكامل والصنف مسلّم</span>
            </div>
            <div class="mini-card">
                <i>↩️</i>
                <strong>مرتجعة</strong>
                <span>تم استرجاع البضاعة ورد المبلغ للعميل</span>
            </div>
        </div>

        <h2 class="doc-h2">💵 إضافة دفعة على فاتورة موجودة</h2>
        <p class="doc-p">
            افتح الفاتورة، ثم اضغط <strong>"إضافة دفعة"</strong>، أدخل المبلغ وطريقة الدفع (نقدي / شبكة / تحويل)
            وتاريخ الدفع. يُحدَّث الرصيد المتبقي تلقائياً.
        </p>

        <h2 class="doc-h2">🖨️ طباعة الفاتورة</h2>
        <p class="doc-p">
            من صفحة الفاتورة اضغط "طباعة" للحصول على نسخة احترافية تحتوي على شعار المتجر، بيانات العميل والطبيب، تفاصيل الأصناف، الخصومات، المدفوع والمتبقي.
        </p>

        <div class="tip-box tip">💡 <div><strong>حفظ كمسودة:</strong> إذا لم تكتمل الفاتورة بعد، احفظها كـ"مسودة" وأكملها لاحقاً دون أن تؤثر على المخزون.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     10. المصروفات
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="expenses">
    <div class="section-header">
        <div class="section-icon" style="background:#fee2e2;color:#dc2626;">💰</div>
        <div>
            <div class="section-title">إدارة المصروفات</div>
            <div class="section-subtitle">تسجيل ومتابعة نفقات المتجر والرواتب</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            في قسم المصروفات تُسجَّل جميع النفقات التشغيلية للمتجر مثل الإيجار والكهرباء والرواتب والمشتريات
            وأي مصروف آخر — لحساب صافي الربح بدقة.
        </p>

        <h2 class="doc-h2">📋 بيانات المصروف</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>العنوان</strong></td><td>وصف مختصر للمصروف</td></tr>
                <tr><td><strong>الفئة</strong></td><td>مثل: إيجار، رواتب، كهرباء، تسويق</td></tr>
                <tr><td><strong>المبلغ</strong></td><td>قيمة المصروف</td></tr>
                <tr><td><strong>التاريخ</strong></td><td>تاريخ صرف المبلغ</td></tr>
                <tr><td><strong>الفرع</strong></td><td>الفرع الذي ينتمي إليه هذا المصروف</td></tr>
                <tr><td><strong>ملاحظات</strong></td><td>أي تفاصيل إضافية</td></tr>
                <tr><td><strong>الحالة</strong></td><td>معلق / موافق عليه / مرفوض</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">✅ موافقة على المصروفات</h2>
        <p class="doc-p">
            يمكن أن تمر المصروفات بمرحلة موافقة من المدير قبل اعتمادها. المدير يرى المصروفات المعلقة ويضغط
            "موافقة" أو "رفض".
        </p>

        <h2 class="doc-h2">👔 الرواتب</h2>
        <p class="doc-p">
            من قسم المصروفات يمكن الوصول إلى <strong>معاينة الرواتب</strong> لرؤية إجمالي الرواتب المسجلة،
            ومقارنتها بالفترة السابقة.
        </p>

        <h2 class="doc-h2">🗂️ فئات المصروفات</h2>
        <p class="doc-p">
            يمكن إنشاء فئات مخصصة من <strong>إعدادات المصروفات</strong> مثل: إيجار، كهرباء، مياه، رواتب، إعلانات، صيانة...
            كل مصروف يُنسَب لفئة لتنظيم التقارير.
        </p>

        <div class="tip-box warn">⚠️ <div>تقرير المصروفات يمكن تصفيته بالفترة الزمنية والفرع والفئة — مما يمنحك صورة دقيقة عن توزيع التكاليف.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     11. التقارير
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="reports">
    <div class="section-header">
        <div class="section-icon" style="background:#dcfce7;color:#15803d;">📊</div>
        <div>
            <div class="section-title">التقارير الشاملة</div>
            <div class="section-subtitle">أكثر من 40 تقرير لمتابعة جميع جوانب العمل</div>
        </div>
    </div>
    <div class="section-body">

        <h2 class="doc-h2">💰 تقارير المبيعات</h2>
        <table class="doc-table">
            <thead>
                <tr><th>التقرير</th><th>ما يُظهره</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>ملخص المبيعات</strong></td><td>إجمالي المبيعات في فترة محددة مع تفاصيل كل فاتورة</td></tr>
                <tr><td><strong>مبيعات حسب البائع</strong></td><td>مقارنة أداء كل موظف / بائع</td></tr>
                <tr><td><strong>مبيعات حسب الطبيب</strong></td><td>المبيعات المحالة من كل طبيب</td></tr>
                <tr><td><strong>معاملات الصراف</strong></td><td>جميع المقبوضات والمدفوعات النقدية</td></tr>
                <tr><td><strong>تقرير الصراف</strong></td><td>تقرير يومي / دوري لحركة الصندوق</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🧾 تقارير الفواتير</h2>
        <table class="doc-table">
            <thead>
                <tr><th>التقرير</th><th>ما يُظهره</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>الفواتير المعلقة</strong></td><td>فواتير لم تُسدَّد بالكامل بعد</td></tr>
                <tr><td><strong>الفواتير المرتجعة</strong></td><td>فواتير تم إرجاعها واسترداد قيمتها</td></tr>
                <tr><td><strong>الفواتير المسلّمة</strong></td><td>فواتير تمت تسليم بضاعتها للعميل</td></tr>
                <tr><td><strong>العناصر جاهزة للتسليم</strong></td><td>أصناف جاهزة وتنتظر استلام العميل</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">👤 تقارير العملاء</h2>
        <table class="doc-table">
            <thead>
                <tr><th>التقرير</th><th>ما يُظهره</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>تقرير العملاء</strong></td><td>قائمة العملاء مع إجمالي مشترياتهم ومدفوعاتهم</td></tr>
                <tr><td><strong>كشف حساب العميل</strong></td><td>تفاصيل كاملة للفواتير والمدفوعات لعميل بعينه</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">📦 تقارير المخزون</h2>
        <table class="doc-table">
            <thead>
                <tr><th>التقرير</th><th>ما يُظهره</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>مخزون الفرع</strong></td><td>قائمة كاملة بالمنتجات وكمياتها في فرع محدد</td></tr>
                <tr><td><strong>المخزون المنخفض</strong></td><td>الأصناف التي وصلت أو تخطّت الحد الأدنى</td></tr>
                <tr><td><strong>تقرير تحويلات المنتج</strong></td><td>حركة منتج بعينه بين الفروع عبر الزمن</td></tr>
                <tr><td><strong>إحصائيات الفروع</strong></td><td>مقارنة المخزون والمبيعات بين جميع الفروع</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">💰 تقارير المصروفات والعدسات</h2>
        <table class="doc-table">
            <thead>
                <tr><th>التقرير</th><th>ما يُظهره</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>تقرير المصروفات</strong></td><td>النفقات مصنّفةً حسب الفئة والفرع والفترة</td></tr>
                <tr><td><strong>العدسات التالفة</strong></td><td>قائمة العدسات المُسجّلة كتالفة مع تفاصيلها</td></tr>
            </tbody>
        </table>

        <div class="info-block blue">
            <strong>📤 تصدير التقارير</strong>
            <p>جميع التقارير تقريباً تدعم الطباعة المباشرة وتصدير PDF. بعض تقارير المخزون تدعم تصدير Excel أيضاً.</p>
        </div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     12. الأطباء
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="doctors">
    <div class="section-header">
        <div class="section-icon" style="background:#f0fdf4;color:#15803d;">👨‍⚕️</div>
        <div>
            <div class="section-title">إدارة الأطباء</div>
            <div class="section-subtitle">قاعدة بيانات الأطباء المحيلين للعملاء</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            الأطباء هم الذين يحيلون المرضى للمتجر أو يجرون فحوصات العيون. ربط الفاتورة بالطبيب يساعد في
            تتبع مصادر المبيعات وإنشاء تقارير مبيعات حسب الطبيب.
        </p>

        <h2 class="doc-h2">📋 بيانات الطبيب</h2>
        <ul class="doc-ul">
            <li>الاسم (عربي / إنجليزي)</li>
            <li>التخصص (طب العيون، بصريات...)</li>
            <li>العيادة أو المستشفى</li>
            <li>أرقام التواصل والبريد الإلكتروني</li>
            <li>نسبة العمولة (إن وجدت)</li>
        </ul>

        <h2 class="doc-h2">📊 تقرير مبيعات الطبيب</h2>
        <p class="doc-p">
            من التقارير يمكن رؤية إجمالي مبيعات كل طبيب — أي المبيعات التي جاءت بإحالة منه —
            وهو مفيد لقياس تأثير كل طبيب على الإيرادات.
        </p>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     13. شركات التأمين والكروت
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="insurance">
    <div class="section-header">
        <div class="section-icon" style="background:#fef3c7;color:#d97706;">🛡️</div>
        <div>
            <div class="section-title">شركات التأمين وحاملو الكروت</div>
            <div class="section-subtitle">إدارة الجهات الدافعة والخصومات التعاقدية</div>
        </div>
    </div>
    <div class="section-body">

        <h2 class="doc-h2">🛡️ شركات التأمين</h2>
        <p class="doc-p">
            عندما يكون للعميل تأمين صحي يغطي جزءاً من تكلفة النظارات أو العدسات، تُربط الفاتورة بشركة التأمين.
            يدفع العميل نسبته (Co-pay) وتتحمل الشركة الباقي.
        </p>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>اسم الشركة</strong></td><td>الاسم الرسمي لشركة التأمين</td></tr>
                <tr><td><strong>نسبة التغطية %</strong></td><td>نسبة ما تتحمله الشركة من قيمة الفاتورة</td></tr>
                <tr><td><strong>الحد الأقصى</strong></td><td>أعلى مبلغ تتحمله الشركة لكل فاتورة</td></tr>
                <tr><td><strong>شروط الخصم</strong></td><td>أي قيود أو شروط خاصة بهذه الشركة</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">💳 حاملو الكروت (Cardholders)</h2>
        <p class="doc-p">
            بعض الشركات أو المنظمات تتعاقد مع المتجر لمنح موظفيها خصومات ثابتة.
            كل <strong>حامل كرت</strong> له نسبة خصم مسبقة تُطبَّق تلقائياً عند تحديده في الفاتورة.
        </p>
        <ul class="doc-ul">
            <li>اسم الشركة أو المؤسسة</li>
            <li>نسبة الخصم المتفق عليها</li>
            <li>صلاحية الكرت (تاريخ الانتهاء)</li>
            <li>أي شروط خاصة</li>
        </ul>

        <div class="tip-box info">✅ <div>عند إنشاء الفاتورة، اختر "جهة دافعة" ثم حدد إما شركة تأمين أو حامل كرت — يُحسب الخصم تلقائياً.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     14. الإغلاق اليومي
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="daily-close">
    <div class="section-header">
        <div class="section-icon" style="background:#f0f9ff;color:#0369a1;">📅</div>
        <div>
            <div class="section-title">الإغلاق اليومي</div>
            <div class="section-subtitle">موازنة الصندوق وإغلاق يوم العمل</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            في نهاية كل يوم عمل يتم إجراء الإغلاق اليومي لمطابقة المقبوضات النقدية مع الفواتير المسجلة في النظام.
        </p>

        <h2 class="doc-h2">🔢 خطوات الإغلاق اليومي</h2>
        <ol class="steps">
            <li>
                <span class="step-num">1</span>
                <div class="step-text"><strong>مراجعة تقرير الصراف</strong> — تأكد من إجمالي المقبوضات النقدية للفترة.</div>
            </li>
            <li>
                <span class="step-num">2</span>
                <div class="step-text"><strong>إدخال رصيد الصندوق الفعلي</strong> — المبلغ النقدي الموجود فعلاً في الصندوق.</div>
            </li>
            <li>
                <span class="step-num">3</span>
                <div class="step-text"><strong>التوازن التلقائي</strong> — يحسب النظام أي فارق بين المتوقع والفعلي.</div>
            </li>
            <li>
                <span class="step-num">4</span>
                <div class="step-text"><strong>إغلاق اليوم</strong> — تُغلق الجلسة اليومية ولا يمكن إجراء تعديلات عليها إلا بصلاحية خاصة.</div>
            </li>
        </ol>

        <div class="info-block yellow">
            <strong>🔓 إعادة فتح يوم مغلق</strong>
            <p>في حال الحاجة لتصحيح خطأ في يوم مغلق، يمكن لمن لديه صلاحية "إعادة الفتح" تفعيل ذلك ثم الإغلاق مرةً أخرى.</p>
        </div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     15. المستخدمون
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="users">
    <div class="section-header">
        <div class="section-icon" style="background:#ede9fe;color:#6d28d9;">👥</div>
        <div>
            <div class="section-title">إدارة المستخدمين</div>
            <div class="section-subtitle">حسابات موظفي وإداريي النظام</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            كل شخص يعمل على النظام له حساب مستخدم خاص به — اسم دخول وكلمة مرور وصلاحيات محددة.
            المستخدم مرتبط بفرع معين ودور محدد.
        </p>

        <h2 class="doc-h2">📋 بيانات المستخدم</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الحقل</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>الاسم الأول والأخير</strong></td><td>الاسم الكامل للموظف</td></tr>
                <tr><td><strong>البريد الإلكتروني</strong></td><td>يُستخدم كاسم دخول</td></tr>
                <tr><td><strong>كلمة المرور</strong></td><td>يمكن تغييرها في أي وقت</td></tr>
                <tr><td><strong>الفرع</strong></td><td>الفرع الذي يعمل فيه هذا المستخدم</td></tr>
                <tr><td><strong>الدور</strong></td><td>مجموعة الصلاحيات الممنوحة له (مدير، كاشير، موظف...)</td></tr>
                <tr><td><strong>الحالة</strong></td><td>مفعّل / موقوف — الموقوف لا يستطيع الدخول</td></tr>
                <tr><td><strong>الصورة الشخصية</strong></td><td>صورة الموظف تظهر في لوحة التحكم</td></tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🚀 إضافة مستخدم جديد</h2>
        <ol class="steps">
            <li><span class="step-num">1</span><div class="step-text"><strong>المستخدمون &gt; إضافة مستخدم جديد</strong></div></li>
            <li><span class="step-num">2</span><div class="step-text"><strong>أدخل الاسم والبريد الإلكتروني وكلمة المرور</strong></div></li>
            <li><span class="step-num">3</span><div class="step-text"><strong>حدد الفرع والدور</strong> — هما اللذان يحددان ما يراه المستخدم ويستطيع فعله</div></li>
            <li><span class="step-num">4</span><div class="step-text"><strong>احفظ</strong> — يمكن للمستخدم تسجيل الدخول فوراً</div></li>
        </ol>

        <h2 class="doc-h2">👤 الملف الشخصي</h2>
        <p class="doc-p">
            كل مستخدم يستطيع تعديل ملفه الشخصي من خلال الضغط على اسمه في أعلى الصفحة:
        </p>
        <ul class="doc-ul">
            <li>تغيير الصورة الشخصية</li>
            <li>تحديث الاسم والبريد الإلكتروني</li>
            <li>تغيير كلمة المرور</li>
        </ul>

        <div class="tip-box warn">⚠️ <div><strong>تنبيه:</strong> عند نسيان كلمة مرور أي مستخدم، يستطيع المدير العام فقط تعيين كلمة مرور جديدة له من صفحة تعديل المستخدم.</div></div>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     16. الأدوار والصلاحيات
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="roles">
    <div class="section-header">
        <div class="section-icon" style="background:#fef3c7;color:#92400e;">🔐</div>
        <div>
            <div class="section-title">الأدوار والصلاحيات</div>
            <div class="section-subtitle">التحكم الدقيق في ما يراه ويفعله كل مستخدم</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            النظام يعتمد على نظام <strong>الأدوار (Roles)</strong> للتحكم في الصلاحيات.
            كل مستخدم يُعطى دوراً واحداً أو أكثر، وكل دور له مجموعة من الصلاحيات المحددة.
        </p>

        <h2 class="doc-h2">🏷️ الأدوار الافتراضية في النظام</h2>
        <table class="doc-table">
            <thead>
                <tr><th>الدور</th><th>الوصف</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-red">مدير عام (Super Admin)</span></td>
                    <td>صلاحية كاملة على جميع أقسام النظام دون استثناء</td>
                </tr>
                <tr>
                    <td><span class="badge badge-purple">مدير (Admin)</span></td>
                    <td>صلاحيات واسعة مع استثناء بعض الإعدادات الحساسة</td>
                </tr>
                <tr>
                    <td><span class="badge badge-blue">مدير فرع (Branch Manager)</span></td>
                    <td>صلاحيات كاملة على فرعه فقط — مبيعات ومخزون وتقارير الفرع</td>
                </tr>
                <tr>
                    <td><span class="badge badge-green">كاشير (Cashier)</span></td>
                    <td>إنشاء الفواتير واستقبال المدفوعات فقط</td>
                </tr>
                <tr>
                    <td><span class="badge badge-orange">موظف (Employee)</span></td>
                    <td>الاطلاع على المنتجات والعملاء وإنشاء فواتير</td>
                </tr>
                <tr>
                    <td><span class="badge badge-gray">مشاهد (Viewer)</span></td>
                    <td>مشاهدة فقط — لا يستطيع الإضافة أو التعديل</td>
                </tr>
            </tbody>
        </table>

        <h2 class="doc-h2">🔧 تخصيص الصلاحيات</h2>
        <p class="doc-p">
            يمكن لمدير النظام تعديل صلاحيات أي دور بدقة شديدة. كل قسم في النظام له مجموعة صلاحيات مثل:
        </p>
        <div class="card-grid">
            <div class="mini-card">
                <i>👁️</i>
                <strong>عرض (View)</strong>
                <span>رؤية البيانات فقط دون تعديل</span>
            </div>
            <div class="mini-card">
                <i>➕</i>
                <strong>إنشاء (Create)</strong>
                <span>إضافة سجلات جديدة</span>
            </div>
            <div class="mini-card">
                <i>✏️</i>
                <strong>تعديل (Edit)</strong>
                <span>تعديل بيانات موجودة</span>
            </div>
            <div class="mini-card">
                <i>🗑️</i>
                <strong>حذف (Delete)</strong>
                <span>حذف السجلات — يُعطى بحذر</span>
            </div>
        </div>

        <h2 class="doc-h2">🚀 كيف تنشئ دوراً جديداً؟</h2>
        <ol class="steps">
            <li><span class="step-num">1</span><div class="step-text"><strong>الأدوار والصلاحيات &gt; إضافة دور جديد</strong></div></li>
            <li><span class="step-num">2</span><div class="step-text"><strong>أدخل اسم الدور</strong> (باللغة الإنجليزية بدون مسافات) ثم الاسم الظاهر.</div></li>
            <li><span class="step-num">3</span><div class="step-text"><strong>احفظ الدور</strong> ثم افتح تفاصيله.</div></li>
            <li><span class="step-num">4</span><div class="step-text"><strong>اضبط الصلاحيات</strong> من قسم "صلاحيات الدور" — فعّل أو أوقف كل صلاحية.</div></li>
        </ol>

        <div class="tip-box alert">🚨 <div><strong>تحذير أمني:</strong> لا تُعطِ صلاحية "حذف" أو "إعدادات النظام" إلا لمستخدمين موثوقين. الحذف لا يمكن التراجع عنه في معظم الحالات.</div></div>

        <h2 class="doc-h2">📋 قائمة الصلاحيات حسب القسم</h2>
        <table class="doc-table">
            <thead>
                <tr><th>القسم</th><th>الصلاحيات المتاحة</th></tr>
            </thead>
            <tbody>
                <tr><td><strong>المنتجات</strong></td><td>عرض، إضافة، تعديل، حذف، استيراد</td></tr>
                <tr><td><strong>العدسات</strong></td><td>عرض، إضافة، تعديل، حذف المخزون والماركات</td></tr>
                <tr><td><strong>العملاء</strong></td><td>عرض، إضافة، تعديل، حذف، اختبارات العيون</td></tr>
                <tr><td><strong>الفواتير</strong></td><td>عرض، إنشاء، تعديل، حذف، إرجاع، إضافة دفعة</td></tr>
                <tr><td><strong>الفروع</strong></td><td>عرض، إضافة، تعديل، حذف، إحصائيات</td></tr>
                <tr><td><strong>المخزون</strong></td><td>عرض، تعديل الكميات، تقارير المخزون</td></tr>
                <tr><td><strong>نقل المخزون</strong></td><td>عرض، إنشاء، موافقة، شحن، استلام، إلغاء</td></tr>
                <tr><td><strong>المصروفات</strong></td><td>عرض، إضافة، تعديل، حذف، موافقة</td></tr>
                <tr><td><strong>الأطباء</strong></td><td>عرض، إضافة، تعديل، حذف</td></tr>
                <tr><td><strong>التقارير</strong></td><td>عرض تقارير المبيعات، المخزون، العملاء، المصروفات</td></tr>
                <tr><td><strong>المستخدمون</strong></td><td>عرض، إضافة، تعديل، حذف، صلاحيات</td></tr>
                <tr><td><strong>الإعدادات</strong></td><td>عرض وتعديل إعدادات النظام</td></tr>
                <tr><td><strong>المساعد الذكي</strong></td><td>الوصول للمساعد الذكي AI</td></tr>
            </tbody>
        </table>

    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     17. إعدادات النظام
═══════════════════════════════════════════════════════ -->
<div class="doc-section" id="settings">
    <div class="section-header">
        <div class="section-icon" style="background:#f8fafc;color:#334155;">⚙️</div>
        <div>
            <div class="section-title">إعدادات النظام</div>
            <div class="section-subtitle">التخصيص الشامل لسلوك ومظهر النظام</div>
        </div>
    </div>
    <div class="section-body">

        <p class="doc-p">
            من صفحة الإعدادات يمكن للمدير ضبط جميع معلمات النظام دون الحاجة لتدخل تقني.
        </p>

        <h2 class="doc-h2">🏪 إعدادات المتجر</h2>
        <ul class="doc-ul">
            <li>اسم المتجر (عربي / إنجليزي)</li>
            <li>شعار النظام (Logo) وأيقونة التبويب (Favicon)</li>
            <li>معلومات التواصل الأساسية</li>
        </ul>

        <h2 class="doc-h2">🧾 إعدادات الفاتورة</h2>
        <ul class="doc-ul">
            <li>اسم المنشأة في الفاتورة (عربي / إنجليزي)</li>
            <li>عنوان الفاتورة وأرقام التواصل</li>
            <li>شعار عربي وشعار إنجليزي يظهران جنباً إلى جنب في رأس الفاتورة</li>
            <li>نص التذييل (Footer) في أسفل الفاتورة</li>
            <li>البريد الإلكتروني الظاهر في الفاتورة</li>
        </ul>

        <h2 class="doc-h2">💲 تعديل الأسعار بالجملة</h2>
        <p class="doc-p">
            من إعدادات الأسعار يمكنك رفع أو خفض أسعار <strong>جميع المنتجات وجميع العدسات</strong> بنسبة مئوية ثابتة دفعةً واحدة:
        </p>
        <ol class="steps">
            <li><span class="step-num">1</span><div class="step-text"><strong>الإعدادات &gt; تعديل الأسعار</strong></div></li>
            <li><span class="step-num">2</span><div class="step-text"><strong>حدد ما تريد تعديله:</strong> منتجات فقط، عدسات فقط، أو كليهما.</div></li>
            <li><span class="step-num">3</span><div class="step-text"><strong>أدخل النسبة المئوية</strong> — مثلاً 10 لرفع الأسعار 10%، أو -5 لخفضها 5%.</div></li>
            <li><span class="step-num">4</span><div class="step-text"><strong>اضغط تطبيق</strong> — تُحدَّث جميع الأسعار فوراً في قاعدة البيانات.</div></li>
        </ol>

        <div class="tip-box alert">🚨 <div><strong>تحذير:</strong> عملية تعديل الأسعار بالجملة لا يمكن التراجع عنها تلقائياً — تأكد من النسبة قبل الضغط على "تطبيق".</div></div>

        <h2 class="doc-h2">🤖 المساعد الذكي (AI Assistant)</h2>
        <p class="doc-p">
            النظام مزوّد بمساعد ذكي يمكنه الإجابة على أسئلتك حول النظام وبيانات المخزون والمبيعات.
            يتوفر المساعد في أسفل يمين الشاشة في جميع صفحات لوحة التحكم (للمستخدمين ذوي الصلاحية).
        </p>

    </div>
</div>

<!-- FOOTER -->
<div class="doc-footer">
    <p>📖 دليل المستخدم — نظام إدارة البصريات &nbsp;|&nbsp; جميع الحقوق محفوظة &nbsp;|&nbsp; <a href="/dashboard/home">العودة للوحة التحكم</a></p>
</div>

</main>
</div><!-- /layout -->

<!-- BACK TO TOP -->
<button class="back-top" id="backTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

<script>
// ─── Theme ───────────────────────────────────
(function(){
    var saved = localStorage.getItem('manual-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    updateThemeBtn(saved);
})();

function toggleTheme(){
    var current = document.documentElement.getAttribute('data-theme') || 'light';
    var next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('manual-theme', next);
    updateThemeBtn(next);
}
function updateThemeBtn(theme){
    var btn = document.getElementById('themeBtn');
    if(btn) btn.textContent = theme === 'dark' ? '☀️ الوضع النهاري' : '🌙 الوضع الليلي';
}

// ─── Mobile sidebar ───────────────────────────
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
}

// ─── Sidebar active on scroll ─────────────────
var sections = document.querySelectorAll('.doc-section[id], .hero[id]');
var sidebarLinks = document.querySelectorAll('.sidebar-item');

window.addEventListener('scroll', function(){
    var scrollY = window.scrollY + 120;
    var current = '';
    sections.forEach(function(s){
        if(s.offsetTop <= scrollY) current = s.id;
    });
    sidebarLinks.forEach(function(a){
        a.classList.remove('active');
        if(a.getAttribute('href') === '#' + current) a.classList.add('active');
    });

    // Back to top
    var btn = document.getElementById('backTop');
    if(window.scrollY > 400) btn.classList.add('show');
    else btn.classList.remove('show');
});

// ─── Smooth close sidebar on mobile link click ─
sidebarLinks.forEach(function(a){
    a.addEventListener('click', function(){
        if(window.innerWidth <= 900)
            document.getElementById('sidebar').classList.remove('open');
    });
});
</script>
</body>
</html>
