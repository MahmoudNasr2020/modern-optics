@extends('dashboard.layouts.master')
@section('title', 'System Settings')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* ══════════════════════════════════════════════════════════════
   SETTINGS PAGE — SIDEBAR LAYOUT
══════════════════════════════════════════════════════════════ */
.sp-wrap {
    display: flex; gap: 0; min-height: calc(100vh - 110px);
    background: #f0f2f8; padding: 20px; gap: 20px;
}

/* ── Sidebar ── */
.sp-sidebar {
    width: 240px; flex-shrink: 0;
    display: flex; flex-direction: column; gap: 6px;
    position: sticky; top: 20px; align-self: flex-start;
}

.sp-sidebar-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    overflow: hidden; padding: 8px;
}

.sp-nav-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px; border-radius: 10px; cursor: pointer;
    transition: all .2s; color: #64748b; font-size: 14px; font-weight: 600;
    border: none; background: transparent; width: 100%; text-align: left;
}
.sp-nav-item i { font-size: 18px; flex-shrink: 0; }
.sp-nav-item:hover  { background: #f8f9ff; color: #667eea; }
.sp-nav-item.active { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; box-shadow: 0 4px 14px rgba(102,126,234,.35); }

.sp-save-btn {
    width: 100%; padding: 14px; border: none; border-radius: 12px; cursor: pointer;
    background: linear-gradient(135deg,#27ae60,#219150); color: #fff;
    font-size: 15px; font-weight: 800; display: flex; align-items: center;
    justify-content: center; gap: 8px;
    box-shadow: 0 4px 16px rgba(39,174,96,.35);
    transition: all .2s;
}
.sp-save-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(39,174,96,.45); }

/* ── Content area ── */
.sp-content { flex: 1; min-width: 0; }

.sp-section { display: none; }
.sp-section.active { display: block; animation: fadeUp .3s ease; }

@keyframes fadeUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Section card ── */
.sp-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    overflow: hidden; margin-bottom: 18px;
}

.sp-card-head {
    padding: 20px 26px; border-bottom: 1px solid #f0f2f8;
    display: flex; align-items: center; gap: 14px;
    background: linear-gradient(135deg,#f8f9ff,#fff);
}
.sp-card-head-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 20px;
}
.sp-card-head h4 { margin: 0 0 2px; font-size: 17px; font-weight: 800; color: #1e293b; }
.sp-card-head p  { margin: 0; font-size: 12px; color: #94a3b8; }

.sp-card-body { padding: 26px; }

/* ── Form fields ── */
.sp-field { margin-bottom: 22px; }
.sp-label {
    display: flex; align-items: center; gap: 7px;
    font-size: 12px; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px;
}
.sp-label i { color: #667eea; font-size: 14px; }
.sp-label .req { color: #ef4444; margin-left: 2px; }

.sp-input {
    width: 100%; padding: 11px 16px;
    border: 2px solid #e8ecf7; border-radius: 10px;
    font-size: 14px; color: #1e293b; background: #fff;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
}
.sp-input:focus {
    border-color: #667eea; outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,.12);
}
textarea.sp-input { min-height: 100px; resize: vertical; }

.sp-hint { font-size: 11px; color: #94a3b8; margin-top: 5px; display: flex; align-items: center; gap: 4px; }

/* ── Upload zone ── */
.sp-upload {
    position: relative; border: 2px dashed #e2e8f0; border-radius: 12px;
    padding: 28px 20px; text-align: center; cursor: pointer;
    transition: all .2s; background: #fafbff;
}
.sp-upload:hover { border-color: #667eea; background: #f3f5ff; }
.sp-upload input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.sp-upload-icon { font-size: 36px; color: #c7d2fe; margin-bottom: 8px; }
.sp-upload p { margin: 0; font-size: 13px; color: #94a3b8; }
.sp-upload strong { color: #667eea; }
.sp-preview { margin-top: 14px; }
.sp-preview img { max-height: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }

/* ── Toggle / Feature card ── */
.sp-feature {
    display: flex; align-items: center; gap: 18px;
    padding: 20px; border: 2px solid #e8ecf7; border-radius: 14px;
    margin-bottom: 14px; transition: border-color .2s, box-shadow .2s;
    cursor: pointer;
}
.sp-feature:hover { border-color: #c7d2fe; box-shadow: 0 2px 12px rgba(102,126,234,.1); }
.sp-feature.on { border-color: #667eea; background: linear-gradient(135deg,#f8f9ff,#fff); }

.sp-feat-icon {
    width: 50px; height: 50px; border-radius: 13px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
}
.sp-feat-icon.purple { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
.sp-feat-icon.indigo { background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.sp-feat-icon.green  { background: linear-gradient(135deg,#22c55e,#16a34a); color:#fff; }

.sp-feat-text { flex: 1; }
.sp-feat-text h5 { margin: 0 0 3px; font-size: 15px; font-weight: 800; color: #1e293b; }
.sp-feat-text p  { margin: 0; font-size: 12px; color: #94a3b8; }

/* ── Toggle switch ── */
.sp-toggle { position: relative; display: inline-block; width: 54px; height: 28px; flex-shrink: 0; }
.sp-toggle input.sp-chk {
    position: absolute !important; width: 100% !important; height: 100% !important;
    opacity: 0 !important; top: 0 !important; left: 0 !important;
    margin: 0 !important; z-index: 3; cursor: pointer !important;
}
.sp-slider {
    position: absolute; inset: 0; border-radius: 28px;
    background: #d1d5db; transition: background .3s; pointer-events: none;
}
.sp-slider::before {
    content: ''; position: absolute; width: 22px; height: 22px;
    border-radius: 50%; background: #fff; left: 3px; top: 3px;
    transition: transform .3s; box-shadow: 0 2px 6px rgba(0,0,0,.2);
}
.sp-chk:checked ~ .sp-slider { background: linear-gradient(135deg,#667eea,#764ba2); }
.sp-chk:checked ~ .sp-slider::before { transform: translateX(26px); }
.sp-toggle-lbl { font-size: 11px; font-weight: 700; color: #94a3b8; white-space: nowrap; }

/* ── Inline notice ── */
.sp-notice {
    display: flex; gap: 12px; padding: 16px 18px; border-radius: 12px;
    font-size: 13px; margin-top: 4px;
}
.sp-notice.warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
.sp-notice i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }

/* ── Section badge ── */
.sp-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: #e0e7ff; color: #4338ca; font-size: 10px; font-weight: 800;
    padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: .5px;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .sp-wrap { flex-direction: column; }
    .sp-sidebar { width: 100%; position: static; }
    .sp-sidebar-card { display: flex; flex-wrap: wrap; padding: 6px; }
    .sp-nav-item { flex: 1 0 auto; justify-content: center; min-width: 80px; padding: 10px 8px; }
    .sp-nav-item span.nav-label { display: none; }
}

/* ── Invoice Template Picker ── */
.inv-tmpl-card {
    flex: 1; min-width: 200px; max-width: 260px;
    border: 3px solid #e2e8f0; border-radius: 10px;
    cursor: pointer; transition: all .2s; overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.inv-tmpl-card:hover { border-color: #667eea; box-shadow: 0 4px 20px rgba(102,126,234,.15); transform: translateY(-2px); }
.inv-tmpl-card.selected { border-color: #667eea; box-shadow: 0 6px 24px rgba(102,126,234,.25); }
.inv-tmpl-label {
    padding: 10px 14px; background: #f8f9ff; font-size: 13px; font-weight: 700;
    color: #475569; display: flex; align-items: center; gap: 8px;
}
.inv-tmpl-check { margin-left: auto; color: #e2e8f0; font-size: 18px; }
.inv-tmpl-card.selected .inv-tmpl-check { color: #667eea; }
</style>
@stop

@section('content')

<section class="content-header" style="padding:10px 20px 5px;">
    <h1 style="font-size:18px;margin:0;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-sliders" style="color:#667eea;"></i>
        System Settings
        <small style="font-size:12px;color:#94a3b8;font-weight:400;">Configure your system</small>
    </h1>
</section>

@if(session('success'))
<div style="margin:0 20px 14px;padding:14px 18px;background:#f0fdf4;border:1px solid #86efac;border-radius:10px;color:#166534;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="margin:0 20px 14px;padding:14px 18px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-exclamation-circle-fill" style="font-size:18px;"></i> {{ session('error') }}
</div>
@endif

<form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
@csrf

<div class="sp-wrap">

    {{-- ══ Sidebar ══ --}}
    <div class="sp-sidebar">
        <div class="sp-sidebar-card">
            <button type="button" class="sp-nav-item active" data-section="system">
                <i class="bi bi-pc-display"></i>
                <span class="nav-label">System Info</span>
            </button>
            <button type="button" class="sp-nav-item" data-section="invoice">
                <i class="bi bi-receipt"></i>
                <span class="nav-label">Invoice</span>
            </button>
            <button type="button" class="sp-nav-item" data-section="eyetest">
                <i class="bi bi-eye"></i>
                <span class="nav-label">Eye Test</span>
            </button>
            <button type="button" class="sp-nav-item" data-section="whatsapp">
                <i class="bi bi-whatsapp"></i>
                <span class="nav-label">WhatsApp</span>
            </button>
            <button type="button" class="sp-nav-item" data-section="features">
                <i class="bi bi-toggles2"></i>
                <span class="nav-label">Features</span>
            </button>
            <button type="button" class="sp-nav-item" data-section="prices">
                <i class="bi bi-tags"></i>
                <span class="nav-label">Prices</span>
            </button>
        </div>

        @can('edit-settings')
        <button type="submit" class="sp-save-btn">
            <i class="bi bi-check-circle-fill"></i> Save Settings
        </button>
        @endcan
    </div>

    {{-- ══ Content ══ --}}
    <div class="sp-content">

        {{-- ─── SYSTEM INFO ─── --}}
        <div class="sp-section active" id="section-system">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon purple"><i class="bi bi-pc-display"></i></div>
                    <div>
                        <h4>System Information</h4>
                        <p>Configure your system name, branding and status</p>
                    </div>
                </div>
                <div class="sp-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-building"></i> System Name <span class="req">*</span></div>
                                <input type="text" name="system_name" class="sp-input"
                                       placeholder="Enter system name"
                                       value="{{ Settings::get('system_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-power"></i> Maintenance Mode</div>
                                <div class="sp-feature {{ Settings::get('system_status') == 'maintenance' ? 'on' : '' }}" style="padding:14px 18px;cursor:default;">
                                    <div>
                                        <div style="font-size:14px;font-weight:700;color:#1e293b;">Enable Maintenance</div>
                                        <div style="font-size:12px;color:#94a3b8;margin-top:2px;">Puts the system in maintenance mode</div>
                                    </div>
                                    <label class="sp-toggle" style="margin-left:auto;">
                                        <input type="checkbox" class="sp-chk" name="system_status" value="maintenance"
                                            {{ Settings::get('system_status') == 'maintenance' ? 'checked' : '' }}>
                                        <span class="sp-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-image"></i> System Logo</div>
                                <div class="sp-upload">
                                    <input type="file" name="system_logo" accept="image/*">
                                    <div class="sp-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                    <p><strong>Click to upload</strong> logo<br><small>PNG, JPG or SVG — max 2MB</small></p>
                                </div>
                                @if(!empty(Settings::get('system_logo')))
                                <div class="sp-preview">
                                    <img src="{{ asset('storage/' . Settings::get('system_logo')) }}" alt="Logo">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-app-indicator"></i> System Icon</div>
                                <div class="sp-upload">
                                    <input type="file" name="system_icon" accept="image/*">
                                    <div class="sp-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                    <p><strong>Click to upload</strong> icon<br><small>PNG or ICO — max 1MB</small></p>
                                </div>
                                @if(!empty(Settings::get('system_icon')))
                                <div class="sp-preview">
                                    <img src="{{ asset('storage/' . Settings::get('system_icon')) }}" alt="Icon">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── INVOICE ─── --}}
        <div class="sp-section" id="section-invoice">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon indigo"><i class="bi bi-receipt"></i></div>
                    <div>
                        <h4>Invoice Configuration</h4>
                        <p>Customize invoice header, contact info and footer (English &amp; Arabic)</p>
                    </div>
                </div>
                <div class="sp-card-body">

                    {{-- ── Invoice Logos ── --}}
                    <div style="background:#f8f9ff;border-radius:10px;padding:16px 18px;margin-bottom:18px;border:1px solid #e8ecf7;">
                        <div style="font-size:11px;font-weight:800;color:#667eea;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                            <i class="bi bi-images"></i> Invoice Logos (shown side by side on every invoice)
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sp-field">
                                    <div class="sp-label"><i class="bi bi-image"></i> English Logo (شعار انجليزي)</div>
                                    <div class="sp-upload">
                                        <input type="file" name="invoice_logo_en" accept="image/*">
                                        <div class="sp-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                        <p><strong>Click to upload</strong> English logo<br><small>PNG, JPG or SVG — max 2MB</small></p>
                                    </div>
                                    @if(!empty(Settings::get('invoice_logo_en')))
                                    <div class="sp-preview">
                                        <img src="{{ asset('storage/' . Settings::get('invoice_logo_en')) }}" alt="Invoice EN Logo">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sp-field">
                                    <div class="sp-label"><i class="bi bi-image"></i> Arabic Logo (شعار عربي)</div>
                                    <div class="sp-upload">
                                        <input type="file" name="invoice_logo_ar" accept="image/*">
                                        <div class="sp-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                        <p><strong>Click to upload</strong> Arabic logo<br><small>PNG, JPG or SVG — max 2MB</small></p>
                                    </div>
                                    @if(!empty(Settings::get('invoice_logo_ar')))
                                    <div class="sp-preview">
                                        <img src="{{ asset('storage/' . Settings::get('invoice_logo_ar')) }}" alt="Invoice AR Logo">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Name ── --}}
                    <div style="background:#f8f9ff;border-radius:10px;padding:16px 18px;margin-bottom:18px;border:1px solid #e8ecf7;">
                        <div style="font-size:11px;font-weight:800;color:#667eea;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                            <i class="bi bi-card-heading"></i> Invoice / Business Name
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label"><i class="bi bi-flag"></i> English Name <span class="req">*</span></div>
                                    <input type="text" name="invoice_name" class="sp-input"
                                           placeholder="e.g. Vision Optics" value="{{ Settings::get('invoice_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label" style="direction:rtl;"><i class="bi bi-flag"></i> الاسم بالعربي</div>
                                    <input type="text" name="invoice_name_ar" class="sp-input"
                                           placeholder="مثال: بصريات فيجن" value="{{ Settings::get('invoice_name_ar') }}"
                                           dir="rtl" style="text-align:right;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Address ── --}}
                    <div style="background:#f8f9ff;border-radius:10px;padding:16px 18px;margin-bottom:18px;border:1px solid #e8ecf7;">
                        <div style="font-size:11px;font-weight:800;color:#667eea;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                            <i class="bi bi-geo-alt"></i> Address
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label"><i class="bi bi-flag"></i> English Address</div>
                                    <input type="text" name="invoice_address" class="sp-input"
                                           placeholder="e.g. 12 Nile St, Cairo" value="{{ Settings::get('invoice_address') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label" style="direction:rtl;"><i class="bi bi-flag"></i> العنوان بالعربي</div>
                                    <input type="text" name="invoice_address_ar" class="sp-input"
                                           placeholder="مثال: 12 شارع النيل، القاهرة" value="{{ Settings::get('invoice_address_ar') }}"
                                           dir="rtl" style="text-align:right;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Phone & Email ── --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-telephone"></i> Phone</div>
                                <input type="text" name="invoice_phone" class="sp-input"
                                       placeholder="Contact phone" value="{{ Settings::get('invoice_phone') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-envelope"></i> Email</div>
                                <input type="email" name="invoice_email" class="sp-input"
                                       placeholder="Contact email" value="{{ Settings::get('invoice_email') }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── Footer ── --}}
                    <div style="background:#f8f9ff;border-radius:10px;padding:16px 18px;margin-bottom:4px;border:1px solid #e8ecf7;">
                        <div style="font-size:11px;font-weight:800;color:#667eea;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                            <i class="bi bi-text-paragraph"></i> Invoice Footer
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label"><i class="bi bi-flag"></i> English Footer</div>
                                    <textarea name="invoice_footer" class="sp-input" rows="3"
                                              placeholder="e.g. Thank you for your business!">{{ Settings::get('invoice_footer') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sp-field" style="margin-bottom:0;">
                                    <div class="sp-label" style="direction:rtl;"><i class="bi bi-flag"></i> تذييل الفاتورة بالعربي</div>
                                    <textarea name="invoice_footer_ar" class="sp-input" rows="3"
                                              placeholder="مثال: شكراً لتعاملكم معنا"
                                              dir="rtl" style="text-align:right;">{{ Settings::get('invoice_footer_ar') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="sp-hint" style="margin-top:10px;"><i class="bi bi-info-circle"></i> Appears at the bottom of every printed invoice</div>
                    </div>

                </div>
            </div>

            {{-- ── Invoice Template Design Picker ── --}}
            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                        <i class="bi bi-palette"></i>
                    </div>
                    <div>
                        <h4>Invoice Design Template</h4>
                        <p>Choose the visual style of your invoice creation page</p>
                    </div>
                </div>
                <div class="sp-card-body">
                    <input type="hidden" name="invoice_template" id="invoice_template_input" value="{{ Settings::get('invoice_template', 'design1') }}">
                    <div style="display:flex;gap:18px;flex-wrap:wrap;">

                        {{-- Design 1 --}}
                        <div class="inv-tmpl-card {{ Settings::get('invoice_template','design1') == 'design1' ? 'selected' : '' }}"
                             onclick="selectInvTemplate('design1')" id="tmpl_design1">
                            <div class="inv-tmpl-preview" style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px 8px 0 0;height:54px;display:flex;align-items:center;justify-content:center;">
                                <span style="color:#fff;font-size:12px;font-weight:700;letter-spacing:.5px;">PREMIUM GRADIENT</span>
                            </div>
                            <div style="padding:10px;background:#fff;border-radius:0 0 8px 8px;">
                                <div style="height:8px;background:#667eea;border-radius:4px;margin-bottom:6px;width:70%;"></div>
                                <div style="height:6px;background:#e2e8f0;border-radius:4px;margin-bottom:4px;"></div>
                                <div style="height:6px;background:#e2e8f0;border-radius:4px;width:80%;"></div>
                            </div>
                            <div class="inv-tmpl-label">
                                <i class="bi bi-palette2"></i>
                                <span>Design 1 — Colorful</span>
                                <i class="bi bi-check-circle-fill inv-tmpl-check"></i>
                            </div>
                        </div>

                        {{-- Design 2 --}}
                        <div class="inv-tmpl-card {{ Settings::get('invoice_template','design1') == 'design2' ? 'selected' : '' }}"
                             onclick="selectInvTemplate('design2')" id="tmpl_design2">
                            {{-- Thumbnail: white topbar + two-column layout --}}
                            <div style="background:#fff;border-radius:8px 8px 0 0;height:54px;border-bottom:2px solid #2563eb;display:flex;align-items:center;padding:0 8px;gap:6px;">
                                <div style="width:10px;height:10px;background:#2563eb;border-radius:2px;"></div>
                                <div style="flex:1;height:6px;background:#e2e8f0;border-radius:3px;"></div>
                                <div style="width:20px;height:6px;background:#e2e8f0;border-radius:3px;"></div>
                            </div>
                            <div style="padding:8px;background:#f1f5f9;border-radius:0 0 8px 8px;display:flex;gap:6px;">
                                <div style="flex:1;display:flex;flex-direction:column;gap:4px;">
                                    <div style="height:10px;background:#fff;border-radius:3px;border:1px solid #e2e8f0;"></div>
                                    <div style="height:10px;background:#fff;border-radius:3px;border:1px solid #e2e8f0;"></div>
                                    <div style="height:10px;background:#fff;border-radius:3px;border:1px solid #e2e8f0;"></div>
                                </div>
                                <div style="width:28px;display:flex;flex-direction:column;gap:4px;">
                                    <div style="height:22px;background:#0f172a;border-radius:3px;"></div>
                                    <div style="height:14px;background:#2563eb;border-radius:3px;"></div>
                                </div>
                            </div>
                            <div class="inv-tmpl-label">
                                <i class="bi bi-layout-sidebar-reverse"></i>
                                <span>Design 2 — Clean</span>
                                <i class="bi bi-check-circle-fill inv-tmpl-check"></i>
                            </div>
                        </div>

                        {{-- Design 3 --}}
                        <div class="inv-tmpl-card {{ Settings::get('invoice_template','design1') == 'design3' ? 'selected' : '' }}"
                             onclick="selectInvTemplate('design3')" id="tmpl_design3">
                            <div style="background:
                                radial-gradient(circle at 20% 20%, rgba(255,204,128,.85), transparent 24%),
                                radial-gradient(circle at 80% 25%, rgba(254,215,170,.9), transparent 22%),
                                linear-gradient(135deg,#fffaf3,#ffffff 58%,#fff1de);
                                border-radius:8px 8px 0 0;height:54px;padding:8px;display:flex;gap:6px;align-items:flex-end;">
                                <div style="flex:1;height:26px;background:rgba(255,255,255,.95);border:1px solid #f2dec2;border-radius:8px;"></div>
                                <div style="width:34px;height:38px;background:linear-gradient(180deg,#f59e0b,#ea580c);border-radius:10px;"></div>
                            </div>
                            <div style="padding:8px;background:#fff8ef;border-radius:0 0 8px 8px;display:flex;gap:6px;">
                                <div style="flex:1;display:flex;flex-direction:column;gap:4px;">
                                    <div style="height:8px;background:#f4e0c4;border-radius:4px;width:82%;"></div>
                                    <div style="height:8px;background:#f4e0c4;border-radius:4px;"></div>
                                    <div style="height:8px;background:linear-gradient(90deg,#f59e0b,#fb923c);border-radius:4px;width:60%;"></div>
                                </div>
                            </div>
                            <div class="inv-tmpl-label">
                                <i class="bi bi-stars"></i>
                                <span>Design 3 — Wizard</span>
                                <i class="bi bi-check-circle-fill inv-tmpl-check"></i>
                            </div>
                        </div>

                        {{-- Design 4 --}}
                        <div class="inv-tmpl-card {{ Settings::get('invoice_template','design1') == 'design4' ? 'selected' : '' }}"
                             onclick="selectInvTemplate('design4')" id="tmpl_design4">
                            {{-- Thumbnail: light left panel + tabbed right --}}
                            <div style="background:#fff;border-radius:8px 8px 0 0;height:54px;display:flex;overflow:hidden;border-bottom:2px solid #e2e8f0;">
                                <div style="width:28px;background:#f8fafc;border-right:2px solid #e2e8f0;display:flex;flex-direction:column;justify-content:center;align-items:center;gap:4px;padding:4px;">
                                    <div style="width:16px;height:5px;background:#3b82f6;border-radius:3px;"></div>
                                    <div style="width:16px;height:5px;background:#e2e8f0;border-radius:3px;"></div>
                                    <div style="width:16px;height:5px;background:#e2e8f0;border-radius:3px;"></div>
                                    <div style="width:16px;height:14px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:4px;margin-top:2px;"></div>
                                </div>
                                <div style="flex:1;display:flex;flex-direction:column;gap:3px;padding:6px;">
                                    <div style="display:flex;gap:3px;">
                                        <div style="height:10px;flex:1;background:#3b82f6;border-radius:3px;"></div>
                                        <div style="height:10px;flex:1;background:#e2e8f0;border-radius:3px;"></div>
                                        <div style="height:10px;flex:1;background:#e2e8f0;border-radius:3px;"></div>
                                    </div>
                                    <div style="flex:1;background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;"></div>
                                </div>
                            </div>
                            <div style="padding:8px;background:#fff;border-radius:0 0 8px 8px;border-top:2px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                                <div style="display:flex;gap:5px;">
                                    <div style="width:40px;height:7px;background:#ef4444;border-radius:3px;"></div>
                                    <div style="width:40px;height:7px;background:#e2e8f0;border-radius:3px;"></div>
                                </div>
                                <div style="width:50px;height:14px;background:#22c55e;border-radius:5px;"></div>
                            </div>
                            <div class="inv-tmpl-label">
                                <i class="bi bi-layout-split"></i>
                                <span>Design 4 — Split Screen</span>
                                <i class="bi bi-check-circle-fill inv-tmpl-check"></i>
                            </div>
                        </div>

                    </div>
                    <div class="sp-hint" style="margin-top:14px;"><i class="bi bi-info-circle"></i> This controls how the invoice creation page looks. Save settings to apply.</div>
                </div>
            </div>

        </div>

        {{-- ─── EYE TEST ─── --}}
        <div class="sp-section" id="section-eyetest">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <h4>Eye Test Configuration</h4>
                        <p>Customize eye test form header and details</p>
                    </div>
                </div>
                <div class="sp-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-card-heading"></i> Eye Test Name <span class="req">*</span></div>
                                <input type="text" name="eye_test_name" class="sp-input"
                                       placeholder="Eye test form title" value="{{ Settings::get('eye_test_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-geo-alt"></i> Address</div>
                                <input type="text" name="eye_test_address" class="sp-input"
                                       placeholder="Clinic address" value="{{ Settings::get('eye_test_address') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-telephone"></i> Phone</div>
                                <input type="text" name="eye_test_phone" class="sp-input"
                                       placeholder="Clinic phone" value="{{ Settings::get('eye_test_phone') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-envelope"></i> Email</div>
                                <input type="email" name="eye_test_email" class="sp-input"
                                       placeholder="Clinic email" value="{{ Settings::get('eye_test_email') }}">
                            </div>
                        </div>
                    </div>
                    <div class="sp-field">
                        <div class="sp-label"><i class="bi bi-text-paragraph"></i> Footer Text</div>
                        <textarea name="eye_test_footer" class="sp-input"
                                  placeholder="Footer text shown on all eye test forms">{{ Settings::get('eye_test_footer') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── WHATSAPP ─── --}}
        <div class="sp-section" id="section-whatsapp">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon green"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <h4>WhatsApp Integration</h4>
                        <p>Configure WhatsApp API for automated messaging</p>
                    </div>
                </div>
                <div class="sp-card-body">

                    {{-- Enable toggle --}}
                    <div class="sp-feature {{ Settings::get('send_whatsapp') == 1 ? 'on' : '' }}" style="margin-bottom:22px;">
                        <div class="sp-feat-icon green"><i class="bi bi-whatsapp"></i></div>
                        <div class="sp-feat-text">
                            <h5>Enable WhatsApp Messaging</h5>
                            <p>Turn on automated WhatsApp notifications to customers</p>
                        </div>
                        <label class="sp-toggle">
                            <input type="checkbox" class="sp-chk" name="send_whatsapp" value="1"
                                {{ Settings::get('send_whatsapp') == 1 ? 'checked' : '' }}>
                            <span class="sp-slider"></span>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-phone"></i> Device ID <span class="req">*</span></div>
                                <input type="text" name="whatsapp_device_id" class="sp-input"
                                       placeholder="WhatsApp device ID" value="{{ Settings::get('whatsapp_device_id') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sp-field">
                                <div class="sp-label"><i class="bi bi-person-badge"></i> Username <span class="req">*</span></div>
                                <input type="text" name="whatsapp_username" class="sp-input"
                                       placeholder="API username" value="{{ Settings::get('whatsapp_username') }}">
                            </div>
                        </div>
                    </div>
                    <div class="sp-field">
                        <div class="sp-label"><i class="bi bi-link-45deg"></i> API URL <span class="req">*</span></div>
                        <input type="url" name="whatsapp_url" class="sp-input"
                               placeholder="https://api.whatsapp.com/..." value="{{ Settings::get('whatsapp_url') }}">
                        <div class="sp-hint"><i class="bi bi-info-circle"></i> Full endpoint URL from your WhatsApp API provider</div>
                    </div>

                    <div class="sp-notice warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>Enable WhatsApp only after filling all credentials. Incorrect settings will cause message delivery failures.</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ─── FEATURES ─── --}}
        <div class="sp-section" id="section-features">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon purple"><i class="bi bi-toggles2"></i></div>
                    <div>
                        <h4>System Features</h4>
                        <p>Enable or disable optional system components</p>
                    </div>
                </div>
                <div class="sp-card-body">

                    {{-- Loading Spinner --}}
                    <div class="sp-feature {{ Settings::get('show_spinner', '1') !== '0' ? 'on' : '' }}" id="card-spinner">
                        <div class="sp-feat-icon purple"><i class="bi bi-arrow-repeat"></i></div>
                        <div class="sp-feat-text">
                            <h5>Loading Spinner <span class="sp-badge">Visual</span></h5>
                            <p>Show an animated loading screen when navigating between pages</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="sp-toggle-lbl">Off</span>
                            <label class="sp-toggle">
                                <input type="checkbox" class="sp-chk" name="show_spinner" id="show_spinner" value="1"
                                    {{ Settings::get('show_spinner', '1') !== '0' ? 'checked' : '' }}>
                                <span class="sp-slider"></span>
                            </label>
                            <span class="sp-toggle-lbl">On</span>
                        </div>
                    </div>

                    {{-- AI Chatbot --}}
                    <div class="sp-feature {{ Settings::get('show_chatbot', '1') !== '0' ? 'on' : '' }}" id="card-chatbot">
                        <div class="sp-feat-icon indigo"><i class="bi bi-robot"></i></div>
                        <div class="sp-feat-text">
                            <h5>AI Assistant Chatbot <span class="sp-badge">AI</span></h5>
                            <p>Show the floating AI chatbot button on every page for quick business insights</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="sp-toggle-lbl">Off</span>
                            <label class="sp-toggle">
                                <input type="checkbox" class="sp-chk" name="show_chatbot" id="show_chatbot" value="1"
                                    {{ Settings::get('show_chatbot', '1') !== '0' ? 'checked' : '' }}>
                                <span class="sp-slider"></span>
                            </label>
                            <span class="sp-toggle-lbl">On</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ─── PRICES ─── --}}
        <div class="sp-section" id="section-prices">

            <div class="sp-card">
                <div class="sp-card-head">
                    <div class="sp-feat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <h4>Bulk Price Adjustment</h4>
                        <p>Increase or decrease all product &amp; lens prices by a fixed percentage at once</p>
                    </div>
                </div>
                <div class="sp-card-body">

                    {{-- Notice --}}
                    <div class="sp-notice warning" style="margin-bottom:22px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Caution:</strong> This operation updates <em>all</em> prices in the database immediately and cannot be automatically undone.
                            Make sure you have a backup before applying.
                        </div>
                    </div>

                    {{-- Note: no <form> here — submitted via JS dynamic form to avoid nesting inside settingsForm --}}
                        {{-- Type: increase / decrease --}}
                        <div class="sp-field">
                            <div class="sp-label"><i class="bi bi-arrow-up-down"></i> Adjustment Direction</div>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #e8ecf7;border-radius:10px;cursor:pointer;flex:1;min-width:140px;" id="lbl-increase">
                                    <input type="radio" name="adjustment_type" value="increase" id="type_increase"
                                           style="width:18px;height:18px;accent-color:#22c55e;cursor:pointer;"
                                           onclick="highlightAdjType()">
                                    <span style="font-weight:700;color:#15803d;font-size:15px;">↑ Increase</span>
                                    <span style="color:#64748b;font-size:12px;">رفع الأسعار</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;padding:12px 20px;border:2px solid #e8ecf7;border-radius:10px;cursor:pointer;flex:1;min-width:140px;" id="lbl-decrease">
                                    <input type="radio" name="adjustment_type" value="decrease" id="type_decrease"
                                           style="width:18px;height:18px;accent-color:#ef4444;cursor:pointer;"
                                           onclick="highlightAdjType()">
                                    <span style="font-weight:700;color:#dc2626;font-size:15px;">↓ Decrease</span>
                                    <span style="color:#64748b;font-size:12px;">تخفيض الأسعار</span>
                                </label>
                            </div>
                        </div>

                        {{-- Percentage --}}
                        <div class="sp-field">
                            <div class="sp-label"><i class="bi bi-percent"></i> Percentage <span class="req">*</span></div>
                            <div style="display:flex;align-items:center;gap:10px;max-width:260px;">
                                <input type="number" name="adjustment_percent" id="adj_percent"
                                       class="sp-input" min="0.01" max="100" step="0.01"
                                       placeholder="e.g. 10"
                                       style="font-size:22px;font-weight:800;text-align:center;max-width:140px;">
                                <span style="font-size:28px;font-weight:900;color:#667eea;">%</span>
                            </div>
                            <div class="sp-hint"><i class="bi bi-info-circle"></i> Enter a value between 0.01 and 100</div>
                        </div>

                        {{-- Apply to --}}
                        <div class="sp-field">
                            <div class="sp-label"><i class="bi bi-box-seam"></i> Apply To</div>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <label style="display:flex;align-items:center;gap:8px;padding:10px 18px;border:2px solid #e8ecf7;border-radius:10px;cursor:pointer;">
                                    <input type="checkbox" name="apply_to[]" value="products" checked
                                           style="width:18px;height:18px;accent-color:#667eea;cursor:pointer;">
                                    <i class="bi bi-eyeglasses" style="color:#667eea;font-size:18px;"></i>
                                    <span style="font-weight:700;">Products</span>
                                    <span style="color:#94a3b8;font-size:12px;">(Frames etc.)</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;padding:10px 18px;border:2px solid #e8ecf7;border-radius:10px;cursor:pointer;">
                                    <input type="checkbox" name="apply_to[]" value="lenses" checked
                                           style="width:18px;height:18px;accent-color:#667eea;cursor:pointer;">
                                    <i class="bi bi-circle" style="color:#0ea5e9;font-size:18px;"></i>
                                    <span style="font-weight:700;">Lenses</span>
                                    <span style="color:#94a3b8;font-size:12px;">(Glass Lenses)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Preview label --}}
                        <div id="adj-preview" style="display:none;background:#f0fdf4;border:2px solid #86efac;border-radius:10px;padding:14px 18px;margin-bottom:18px;">
                            <div style="font-size:14px;font-weight:700;color:#15803d;" id="adj-preview-text"></div>
                        </div>

                        {{-- Submit --}}
                        @can('edit-settings')
                        <button type="button" onclick="confirmPriceAdj()"
                                style="padding:14px 36px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;
                                       border:none;border-radius:12px;font-size:15px;font-weight:800;cursor:pointer;
                                       box-shadow:0 4px 16px rgba(245,158,11,.35);display:inline-flex;align-items:center;gap:10px;">
                            <i class="bi bi-check2-circle"></i> Apply Price Adjustment
                        </button>
                        @endcan

                </div>
            </div>

            {{-- ─── Adjustment History Log ─── --}}
            <div class="sp-card" style="margin-top:24px;">
                <div class="sp-card-head">
                    <div class="sp-feat-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h4>Adjustment History</h4>
                        <p>Full log of every price adjustment applied to the system</p>
                    </div>
                </div>
                <div class="sp-card-body" style="padding:0;">

                    @if($priceAdjLogs->isEmpty())
                        <div style="padding:36px;text-align:center;color:#94a3b8;">
                            <i class="bi bi-inbox" style="font-size:36px;display:block;margin-bottom:10px;"></i>
                            No price adjustments have been applied yet.
                        </div>
                    @else
                    <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e8ecf7;">
                                <th style="padding:12px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;white-space:nowrap;">#</th>
                                <th style="padding:12px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;white-space:nowrap;">Date &amp; Time</th>
                                <th style="padding:12px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">Direction</th>
                                <th style="padding:12px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">Percent</th>
                                <th style="padding:12px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">Applied To</th>
                                <th style="padding:12px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">Products</th>
                                <th style="padding:12px 16px;text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">Lenses</th>
                                <th style="padding:12px 16px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#64748b;font-weight:700;">By</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($priceAdjLogs as $i => $log)
                            @php
                                $isInc = $log->type === 'increase';
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                <td style="padding:11px 16px;text-align:center;color:#94a3b8;font-size:12px;">{{ $i + 1 }}</td>

                                <td style="padding:11px 16px;white-space:nowrap;">
                                    <div style="font-weight:700;color:#1e293b;">{{ $log->created_at->format('d M Y') }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ $log->created_at->format('h:i A') }}</div>
                                </td>

                                <td style="padding:11px 16px;text-align:center;">
                                    @if($isInc)
                                        <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;background:#dcfce7;color:#15803d;border-radius:20px;font-size:12px;font-weight:800;">
                                            <i class="bi bi-arrow-up"></i> Increase
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;background:#fee2e2;color:#dc2626;border-radius:20px;font-size:12px;font-weight:800;">
                                            <i class="bi bi-arrow-down"></i> Decrease
                                        </span>
                                    @endif
                                </td>

                                <td style="padding:11px 16px;text-align:center;">
                                    <span style="font-size:20px;font-weight:900;color:{{ $isInc ? '#16a34a' : '#dc2626' }};">
                                        {{ $isInc ? '+' : '-' }}{{ number_format($log->percent, 2) }}%
                                    </span>
                                </td>

                                <td style="padding:11px 16px;">
                                    <span style="font-weight:600;color:#334155;">{{ $log->apply_to_label }}</span>
                                </td>

                                <td style="padding:11px 16px;text-align:center;">
                                    @if($log->products_affected > 0)
                                        <span style="background:#e0e7ff;color:#3730a3;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                            {{ $log->products_affected }}
                                        </span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>

                                <td style="padding:11px 16px;text-align:center;">
                                    @if($log->lenses_affected > 0)
                                        <span style="background:#ccfbf1;color:#065f46;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                            {{ $log->lenses_affected }}
                                        </span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>

                                <td style="padding:11px 16px;">
                                    @if($log->performer)
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            @if($log->performer->image)
                                                <img src="{{ $log->performer->image_path }}"
                                                     style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;"
                                                     alt="">
                                            @else
                                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0;">
                                                    {{ strtoupper(substr($log->performer->first_name, 0, 1)) }}{{ strtoupper(substr($log->performer->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight:700;color:#1e293b;font-size:13px;">{{ $log->performer->full_name }}</div>
                                                <div style="font-size:11px;color:#94a3b8;">{{ $log->performer->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span style="color:#94a3b8;font-size:12px;font-style:italic;">System</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endif

                </div>
            </div>

        </div>{{-- end section-prices --}}

    </div>{{-- end sp-content --}}
</div>{{-- end sp-wrap --}}
</form>

@stop

@section('scripts')
<script>
$(document).ready(function () {

    /* ── Destroy iCheck on all sp-chk toggles ── */
    if ($.fn.iCheck) {
        $('.sp-chk').iCheck('destroy');
    }
    $('.sp-chk').each(function () {
        $(this).css({
            position: 'absolute', opacity: '0', width: '100%',
            height: '100%', top: '0', left: '0', margin: '0',
            zIndex: '3', cursor: 'pointer', display: 'block'
        });
    });

    /* ── Sidebar navigation ── */
    $('.sp-nav-item').on('click', function () {
        var sec = $(this).data('section');
        $('.sp-nav-item').removeClass('active');
        $(this).addClass('active');
        $('.sp-section').removeClass('active');
        $('#section-' + sec).addClass('active');
    });

    /* ── Auto-activate section from URL hash (e.g. after price adjustment redirect) ── */
    var hashSec = (window.location.hash || '').replace('#', '').replace('section-', '');
    if (hashSec && $('#section-' + hashSec).length) {
        $('.sp-nav-item').removeClass('active');
        $('[data-section="' + hashSec + '"]').addClass('active');
        $('.sp-section').removeClass('active');
        $('#section-' + hashSec).addClass('active');
    }

    /* ── Feature card visual sync on toggle change ── */
    $('#show_spinner').on('change', function () {
        $('#card-spinner').toggleClass('on', this.checked);
    });
    $('#show_chatbot').on('change', function () {
        $('#card-chatbot').toggleClass('on', this.checked);
    });

    /* ── File upload preview label ── */
    $('input[type="file"]').on('change', function () {
        var name = this.files[0] ? this.files[0].name : '';
        if (name) {
            $(this).closest('.sp-upload').find('p').html('<strong style="color:#27ae60;">' + name + '</strong><br><small>✔ Ready to upload</small>');
        }
    });

    /* ── Form submit confirmation ── */
    $('#settingsForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Save Settings?',
            text: 'All changes will be applied immediately.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="bi bi-check-circle"></i> Save',
            cancelButtonText: 'Cancel',
        }).then(function (r) {
            if (r.isConfirmed) {
                Swal.fire({ title: 'Saving…', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                form.submit();
            }
        });
    });

    /* ── Validation errors ── */
    @if($errors->any())
    Swal.fire({
        icon: 'error', title: 'Validation Error',
        html: '<div style="text-align:left;">@foreach($errors->all() as $error)<div style="padding:4px 0;">• {{ $error }}</div>@endforeach</div>',
        confirmButtonColor: '#e74c3c'
    });
    @endif

});

/* ── Invoice template picker ── */
function selectInvTemplate(val) {
    $('#invoice_template_input').val(val);
    $('.inv-tmpl-card').removeClass('selected');
    $('#tmpl_' + val).addClass('selected');
}

/* ── Prices section nav ── */
$('[data-section="prices"]').on('click', function() {
    // prices form is outside main settingsForm — no conflict
});

/* ── Price Adjustment: live preview label ── */
function highlightAdjType() {
    var inc = document.getElementById('type_increase').checked;
    document.getElementById('lbl-increase').style.borderColor = inc ? '#22c55e' : '#e8ecf7';
    document.getElementById('lbl-decrease').style.borderColor = !inc ? '#ef4444' : '#e8ecf7';
    updateAdjPreview();
}
function updateAdjPreview() {
    var pct  = parseFloat(document.getElementById('adj_percent').value) || 0;
    var inc  = document.getElementById('type_increase').checked;
    var dec  = document.getElementById('type_decrease').checked;
    var prev = document.getElementById('adj-preview');
    var txt  = document.getElementById('adj-preview-text');
    if (pct > 0 && (inc || dec)) {
        var arrow = inc ? '↑' : '↓';
        var dir   = inc ? 'increased' : 'decreased';
        var col   = inc ? '#15803d' : '#dc2626';
        txt.style.color = col;
        txt.innerHTML   = arrow + ' All selected prices will be <strong>' + dir + ' by ' + pct + '%</strong>';
        prev.style.display = 'block';
    } else {
        prev.style.display = 'none';
    }
}
document.getElementById('adj_percent') &&
    document.getElementById('adj_percent').addEventListener('input', updateAdjPreview);

/* ── Price Adjustment: SweetAlert confirm ── */
function confirmPriceAdj() {
    var pct = parseFloat(document.getElementById('adj_percent').value) || 0;
    if (!pct) { alert('Please enter a percentage.'); return; }
    var inc = document.getElementById('type_increase') && document.getElementById('type_increase').checked;
    var dec = document.getElementById('type_decrease') && document.getElementById('type_decrease').checked;
    if (!inc && !dec) { alert('Please select increase or decrease.'); return; }

    var direction  = inc ? 'INCREASE' : 'DECREASE';
    var dirColor   = inc ? '#22c55e' : '#ef4444';
    var dirText    = inc ? 'increased ↑' : 'decreased ↓';

    Swal.fire({
        title: direction + ' by ' + pct + '%?',
        html: '<div style="font-size:15px;color:#334155;">All <strong>selected prices</strong> in the database will be <span style="color:' + dirColor + ';font-weight:800;">' + dirText + '</span> by <strong>' + pct + '%</strong>.<br><br>This action <strong>cannot be automatically undone</strong>.</div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: dirColor,
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '✔ Yes, Apply Now',
        cancelButtonText: 'Cancel',
    }).then(function(result) {
        if (result.isConfirmed) {
            // Build a standalone form (NOT nested inside settingsForm) and submit it
            var adjType    = document.querySelector('input[name="adjustment_type"]:checked');
            var adjPercent = document.getElementById('adj_percent');
            var adjApply   = document.querySelectorAll('input[name="apply_to[]"]:checked');

            var f = document.createElement('form');
            f.method = 'POST';
            f.action = '{{ route("dashboard.settings.price-adjustment") }}';
            f.style.display = 'none';

            var addHidden = function(name, value) {
                var inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = name; inp.value = value;
                f.appendChild(inp);
            };

            addHidden('_token', '{{ csrf_token() }}');
            if (adjType)    addHidden('adjustment_type',    adjType.value);
            if (adjPercent) addHidden('adjustment_percent', adjPercent.value);
            adjApply.forEach(function(cb) { addHidden('apply_to[]', cb.value); });

            document.body.appendChild(f);
            f.submit();
        }
    });
}
</script>
@endsection
