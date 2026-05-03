@extends('dashboard.layouts.master')
@section('title', 'Add Contact Lens')
@section('content')

<style>
    .cl-create-page { padding: 20px; }
    .cl-box { border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.1); overflow:hidden; border:none; }
    .cl-box-header { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; padding:22px 28px; position:relative; overflow:hidden; }
    .cl-box-header::before { content:''; position:absolute; top:-50%; right:-5%; width:180px; height:180px; background:rgba(255,255,255,.08); border-radius:50%; }
    .cl-box-header h3 { margin:0; font-size:20px; font-weight:700; position:relative; z-index:1; }
    .cl-box-header h3 i { background:rgba(255,255,255,.2); padding:9px; border-radius:8px; margin-right:10px; }
    .cl-box-header .btn-back { position:relative; z-index:1; background:rgba(255,255,255,.2); border:2px solid rgba(255,255,255,.4); color:#fff; padding:8px 18px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none; transition:all .3s; }
    .cl-box-header .btn-back:hover { background:#fff; color:#2980b9; text-decoration:none; }

    .cl-section { background:#fff; padding:24px 28px; border-radius:10px; margin-bottom:18px; border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.04); }
    .cl-section-head { display:flex; align-items:center; margin-bottom:22px; padding-bottom:12px; border-bottom:2px solid #e0ecff; }
    .cl-section-head .icon-box { width:42px; height:42px; background:linear-gradient(135deg,#3498db,#2980b9); border-radius:9px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; margin-right:14px; box-shadow:0 3px 10px rgba(52,152,219,.3); flex-shrink:0; }
    .cl-section-head h4 { margin:0; font-size:16px; font-weight:700; color:#2980b9; }
    .form-group label { font-weight:600; color:#555; font-size:13px; margin-bottom:7px; display:block; }
    .form-group label .req { color:#e74c3c; font-weight:700; }
    .form-control { border:2px solid #e0e6ed; border-radius:8px !important; transition:all .3s; font-size:14px; }
    .form-control:focus { border-color:#3498db; box-shadow:0 0 0 3px rgba(52,152,219,.1); outline:none; }
    .help-text { font-size:12px; color:#999; margin-top:4px; font-style:italic; }

    .sign-selector { display:flex; gap:12px; }
    .sign-btn { flex:1; border:2px solid #e0e6ed; border-radius:10px; padding:14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .sign-btn:hover { border-color:#3498db; background:#e8f4fd; }
    .sign-btn.selected-plus  { border-color:#27ae60; background:#e8f8f5; }
    .sign-btn.selected-minus { border-color:#e74c3c; background:#fef2f2; }
    .sign-btn .sign-icon { font-size:28px; font-weight:900; line-height:1; }
    .sign-btn .sign-label { font-size:12px; font-weight:600; color:#666; margin-top:4px; }
    .sign-btn.selected-plus  .sign-icon { color:#27ae60; }
    .sign-btn.selected-plus  .sign-label { color:#27ae60; }
    .sign-btn.selected-minus .sign-icon { color:#e74c3c; }
    .sign-btn.selected-minus .sign-label { color:#e74c3c; }

    .seg-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .seg-btn { border:2px solid #e0e6ed; border-radius:10px; padding:12px 14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .seg-btn:hover { border-color:#3498db; background:#e8f4fd; }
    .seg-btn.selected { border-color:#3498db; background:#e8f4fd; }
    .seg-btn .seg-icon { font-size:20px; margin-bottom:4px; }
    .seg-btn .seg-name { font-size:13px; font-weight:700; color:#333; }
    .seg-btn .seg-desc { font-size:11px; color:#888; margin-top:2px; }

    .use-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .use-btn { border:2px solid #e0e6ed; border-radius:10px; padding:14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .use-btn:hover { border-color:#3498db; background:#e8f4fd; }
    .use-btn.sel-daily   { border-color:#27ae60; background:#e8f8f5; }
    .use-btn.sel-monthly { border-color:#f57f17; background:#fff8e1; }
    .use-btn .use-icon { font-size:22px; margin-bottom:5px; }
    .use-btn .use-name { font-size:13px; font-weight:700; }
    .use-btn.sel-daily   .use-name, .use-btn.sel-daily   .use-desc { color:#27ae60; }
    .use-btn.sel-monthly .use-name, .use-btn.sel-monthly .use-desc { color:#f57f17; }
    .use-btn .use-desc { font-size:11px; color:#888; margin-top:2px; }

    .power-row { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .power-btn { width:60px; height:36px; border:1.5px solid #e0e6ed; border-radius:8px; background:#fafbfc; font-size:12px; font-weight:600; color:#555; cursor:pointer; transition:all .15s; }
    .power-btn:hover { border-color:#3498db; background:#e8f4fd; color:#2980b9; }
    .power-btn.sel-power { border-color:#3498db; background:#3498db; color:#fff; }

    .btn-submit-cl { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; border:none; padding:13px 48px; border-radius:8px; font-size:15px; font-weight:700; transition:all .3s; box-shadow:0 3px 10px rgba(52,152,219,.3); cursor:pointer; margin:0 8px; }
    .btn-submit-cl:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(52,152,219,.4); color:#fff; }
    .btn-cancel-cl { background:#fff; color:#666; border:2px solid #ddd; padding:13px 48px; border-radius:8px; font-size:15px; font-weight:700; transition:all .3s; text-decoration:none; display:inline-block; margin:0 8px; }
    .btn-cancel-cl:hover { background:#f8f9fa; color:#333; text-decoration:none; }

    /* Import section */
    .import-zone { border:3px dashed #b3d4f0; border-radius:14px; padding:32px 20px; text-align:center; cursor:pointer; background:#f0f7ff; position:relative; transition:all .3s; }
    .import-zone:hover,.import-zone.drag-over { border-color:#3498db; background:#dbeeff; }
    .import-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .import-zone .zi { font-size:40px; color:#b3d4f0; display:block; margin-bottom:8px; transition:color .3s; }
    .import-zone:hover .zi { color:#3498db; }
    .import-zone .zt { font-size:14px; font-weight:600; color:#3498db; margin-bottom:3px; }
    .import-zone .zs { font-size:12px; color:#aaa; }
    .btn-tpl-cl { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; border:none; border-radius:8px; font-weight:600; font-size:13px; cursor:pointer; transition:all .25s; }
    .btn-tpl-cl:hover { transform:translateY(-2px); box-shadow:0 5px 14px rgba(52,152,219,.4); color:#fff; }
    .prev-tbl { width:100%; border-collapse:collapse; font-size:12px; margin-top:12px; }
    .prev-tbl th { background:#f0f7ff; padding:8px 10px; font-weight:700; font-size:11px; text-transform:uppercase; border-bottom:2px solid #d0e8f8; }
    .prev-tbl td { padding:7px 10px; border-bottom:1px solid #f0f2f8; }
    .badge-ok  { background:#e8f8f5; color:#27ae60; padding:2px 8px; border-radius:8px; font-size:11px; font-weight:700; }
    .badge-err { background:#fee; color:#e74c3c; padding:2px 8px; border-radius:8px; font-size:11px; }
    .or-divider { display:flex; align-items:center; gap:14px; margin:28px 0; }
    .or-divider .line { flex:1; height:2px; background:linear-gradient(to right,transparent,#d0e8f8); }
    .or-divider .line.rev { background:linear-gradient(to left,transparent,#d0e8f8); }
    .or-divider span { background:#fff; border:1.5px solid #d0e8f8; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:700; color:#999; white-space:nowrap; }
</style>

<section class="content-header">
    <h1><i class="fa fa-plus-circle" style="color:#3498db;"></i> Add Contact Lens
        <small>Add a new contact lens product</small>
    </h1>
</section>

<div class="cl-create-page">

    @if(session('import_added') !== null)
    <div style="background:#fff;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.08);border:1.5px solid #e0e6ed;overflow:hidden;margin-bottom:20px;">
        @if(session('import_added') > 0)
            <div style="background:linear-gradient(135deg,#10b981,#059669);padding:14px 20px;display:flex;align-items:center;gap:12px;">
                <i class="fa fa-check-circle" style="font-size:22px;color:#fff;"></i>
                <div style="color:#fff;font-size:15px;font-weight:700;">{{ session('import_added') }} contact lens(es) imported successfully!</div>
            </div>
        @else
            <div style="background:linear-gradient(135deg,#f59e0b,#d97706);padding:14px 20px;display:flex;align-items:center;gap:12px;">
                <i class="fa fa-exclamation-triangle" style="font-size:22px;color:#fff;"></i>
                <div style="color:#fff;font-size:15px;font-weight:700;">No lenses were imported. Check errors below.</div>
            </div>
        @endif
        @if(session('import_errors'))
        <div style="padding:14px 20px;">
            @foreach(session('import_errors') as $err)
                <div style="font-size:12px;color:#b91c1c;padding:4px 0;border-bottom:1px solid #fee2e2;">
                    <i class="fa fa-times-circle"></i> {{ $err }}
                </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- ══ MANUAL FORM ══ --}}
    <form action="{{ route('dashboard.contact-lenses.store') }}" method="POST" id="clForm">
        @csrf
        <div class="cl-box">
            <div class="cl-box-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h3><i class="fa fa-eye"></i> Contact Lens Information</h3>
                <a href="{{ route('dashboard.contact-lenses.index') }}" class="btn-back">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
            <div style="padding:28px;">
                @include('dashboard.partials._errors')

                {{-- ── Section 1: Identification ── --}}
                <div class="cl-section">
                    <div class="cl-section-head">
                        <div class="icon-box"><i class="fa fa-barcode"></i></div>
                        <h4>Product Identification</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product ID</label>
                                <input type="text" name="product_id" class="form-control"
                                       value="{{ old('product_id', $nextId) }}" placeholder="Auto-generated">
                                <p class="help-text">Leave as-is or enter custom ID</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    <i class="bi bi-upc-scan" style="color:#3498db;"></i>
                                    Barcode
                                    <small style="font-weight:400;color:#27ae60;font-style:italic;">auto-generated</small>
                                </label>
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <input type="text" name="barcode" id="cl_barcode" class="form-control"
                                           value="{{ old('barcode', $nextBarcode) }}"
                                           placeholder="EAN-13 barcode"
                                           style="font-family:monospace;letter-spacing:1px;">
                                    <button type="button" id="btnRegenBarcode" title="Generate new barcode"
                                            style="flex-shrink:0;width:38px;height:36px;border-radius:8px;
                                                   background:linear-gradient(135deg,#27ae60,#1e8449);
                                                   color:#fff;border:none;font-size:16px;cursor:pointer;
                                                   display:flex;align-items:center;justify-content:center;
                                                   transition:all .2s;">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                                <p class="help-text">باركود EAN-13 جاهز — اضغط <i class="bi bi-arrow-clockwise"></i> لتوليد جديد</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Brand <span class="req">*</span></label>
                                <select name="brand_id" id="cl_brand" class="form-control" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product Name (Model) <span class="req">*</span></label>
                                <div style="display:flex;gap:6px;">
                                    <select name="model_id" id="cl_model" class="form-control" required disabled>
                                        <option value="">Select Brand First</option>
                                    </select>
                                    <button type="button" id="btnAddModel" title="Add new product name"
                                            style="flex-shrink:0;width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;font-size:18px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
                                </div>
                                <p class="help-text">e.g. ACUVUE MOIST, AIR OPTIX, SOFLENS</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 2: Lens Specs ── --}}
                <div class="cl-section">
                    <div class="cl-section-head">
                        <div class="icon-box"><i class="fa fa-eye"></i></div>
                        <h4>Lens Specifications</h4>
                    </div>

                    <div class="row">
                        {{-- Brand Segment --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Brand Segment <span class="req">*</span></label>
                                <div class="seg-grid">
                                    @foreach([
                                        ['Clear',      '🔵', 'Standard vision correction'],
                                        ['Color',      '🎨', 'Colored / cosmetic'],
                                        ['Toric',      '🎯', 'Astigmatism correction'],
                                        ['Multifocal', '🔭', 'Multiple focal zones'],
                                    ] as [$seg, $icon, $desc])
                                        <div class="seg-btn {{ old('brand_segment') == $seg ? 'selected' : '' }}"
                                             data-seg="{{ $seg }}" onclick="selectSeg('{{ $seg }}')">
                                            <div class="seg-icon">{{ $icon }}</div>
                                            <div class="seg-name">{{ $seg }}</div>
                                            <div class="seg-desc">{{ $desc }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="brand_segment" id="brand_segment" value="{{ old('brand_segment') }}" required>
                            </div>
                        </div>

                        {{-- Lens Type --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lens Type <span class="req">*</span></label>
                                <div class="use-grid">
                                    <div class="use-btn {{ old('lense_use') == 'Daily' ? 'sel-daily' : '' }}"
                                         data-use="Daily" onclick="selectUse('Daily')">
                                        <div class="use-icon">☀️</div>
                                        <div class="use-name">Daily</div>
                                        <div class="use-desc">Single-use</div>
                                    </div>
                                    <div class="use-btn {{ old('lense_use') == 'Monthly' ? 'sel-monthly' : '' }}"
                                         data-use="Monthly" onclick="selectUse('Monthly')">
                                        <div class="use-icon">📅</div>
                                        <div class="use-name">Monthly</div>
                                        <div class="use-desc">Reusable</div>
                                    </div>
                                </div>
                                <input type="hidden" name="lense_use" id="lense_use" value="{{ old('lense_use') }}" required>
                            </div>
                        </div>

                        {{-- Sign --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sign <span class="req">*</span></label>
                                <div class="sign-selector">
                                    <div class="sign-btn {{ old('sign') == '+' ? 'selected-plus' : '' }}"
                                         data-sign="+" onclick="selectSign('+')">
                                        <div class="sign-icon">+</div>
                                        <div class="sign-label">Plus (Hyperopia)</div>
                                    </div>
                                    <div class="sign-btn {{ old('sign') == '-' ? 'selected-minus' : '' }}"
                                         data-sign="-" onclick="selectSign('-')">
                                        <div class="sign-icon">−</div>
                                        <div class="sign-label">Minus (Myopia)</div>
                                    </div>
                                </div>
                                <input type="hidden" name="sign" id="sign" value="{{ old('sign') }}" required>
                            </div>
                        </div>

                        {{-- Power --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Power <span class="req">*</span></label>
                                <input type="number" step="0.25" min="0" max="12" name="power" id="power"
                                       class="form-control" value="{{ old('power') }}"
                                       placeholder="e.g. 1.50" required
                                       style="font-size:16px;font-weight:700;text-align:center;">
                                <p class="help-text">0.00 to 12.00 in 0.25 steps</p>
                                {{-- Quick-pick buttons --}}
                                <div class="power-row" style="margin-top:8px;">
                                    @foreach([0.50, 1.00, 1.50, 2.00, 2.50, 3.00, 4.00, 5.00, 6.00] as $pw)
                                        <button type="button" class="power-btn" onclick="setPower({{ $pw }})">
                                            {{ number_format($pw, 2) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 3: Description & Pricing ── --}}
                <div class="cl-section">
                    <div class="cl-section-head">
                        <div class="icon-box"><i class="fa fa-dollar"></i></div>
                        <h4>Description & Pricing</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description <span class="req">*</span></label>
                                <input type="text" name="description" class="form-control"
                                       value="{{ old('description') }}" required
                                       placeholder="e.g. ACUVUE MOIST DAILY PLUS 1.50 BOX">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cost Price <span class="req">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price') }}" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Retail Price <span class="req">*</span></label>
                                <input type="number" step="0.01" name="retail_price" class="form-control"
                                       value="{{ old('retail_price') }}" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tax (%)</label>
                                <input type="number" step="0.01" name="tax" class="form-control"
                                       value="{{ old('tax', 0) }}" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 4: Store Stock ── --}}
                <div style="background:#e8f4fd;border:1.5px solid #b3d4f0;border-radius:12px;padding:18px 20px;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <i class="fa fa-warehouse" style="font-size:20px;color:#2980b9;flex-shrink:0;"></i>
                        <div>
                            <strong style="color:#1a5276;font-size:14px;">Main Store Stock Registration</strong><br>
                            <span style="font-size:12px;color:#555;">Product will be registered in the Store. Set initial quantity (0 = add stock later).</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="font-size:12px;color:#1a5276;font-weight:700;">Initial Qty in Store</label>
                                <input type="number" name="store_quantity" class="form-control" min="0"
                                       value="{{ old('store_quantity', 0) }}" style="border-color:#b3d4f0;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="font-size:12px;color:#1a5276;font-weight:700;">Min Qty</label>
                                <input type="number" name="store_min_qty" class="form-control" min="0"
                                       value="{{ old('store_min_qty', 0) }}" style="border-color:#b3d4f0;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="font-size:12px;color:#1a5276;font-weight:700;">Max Qty</label>
                                <input type="number" name="store_max_qty" class="form-control" min="1"
                                       value="{{ old('store_max_qty', 999) }}" style="border-color:#b3d4f0;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="text-align:center;padding:10px 0 4px;">
                    <button type="submit" class="btn-submit-cl">
                        <i class="fa fa-check-circle"></i> Add Contact Lens
                    </button>
                    <a href="{{ route('dashboard.contact-lenses.index') }}" class="btn-cancel-cl">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- ══ DIVIDER ══ --}}
    <div class="or-divider">
        <div class="line"></div>
        <span><i class="fa fa-file-excel-o" style="color:#3498db;margin-right:4px;"></i> Or Import from Excel</span>
        <div class="line rev"></div>
    </div>

    {{-- ══ EXCEL IMPORT ══ --}}
    <div class="cl-section" style="border-color:#b3d4f0;">
        <div class="cl-section-head">
            <div class="icon-box"><i class="fa fa-file-excel-o"></i></div>
            <h4>Bulk Import from Excel</h4>
        </div>

        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
            <div style="font-size:13px;color:#555;line-height:1.8;max-width:560px;">
                <i class="fa fa-info-circle" style="color:#3498db;"></i>
                Download the template → fill in your contact lenses → upload.<br>
                <strong>Brand</strong> must match exactly. If a <strong>product name (model)</strong> doesn't exist, it will be auto-created under the brand.
            </div>
            <button type="button" onclick="generateCLTemplate()" class="btn-tpl-cl">
                <i class="fa fa-download"></i> Download Template
            </button>
        </div>

        <div class="import-zone" id="importZone">
            <input type="file" id="excelFile" accept=".xlsx,.xls,.csv">
            <i class="fa fa-cloud-upload zi"></i>
            <div class="zt">Drop your Excel file here</div>
            <div class="zs">.xlsx · .xls · .csv · or click to browse</div>
        </div>

        <div id="importPreview" style="display:none;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;margin-bottom:8px;flex-wrap:wrap;gap:8px;">
                <span id="previewTitle" style="font-size:13px;font-weight:600;"></span>
                <div style="display:flex;gap:8px;">
                    <button type="button" id="clearImport" class="btn btn-sm btn-default">
                        <i class="fa fa-times"></i> Clear
                    </button>
                    <button type="button" id="submitImport" class="btn btn-sm btn-primary">
                        <i class="fa fa-check"></i> Import Valid Rows
                    </button>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="prev-tbl">
                    <thead>
                    <tr>
                        <th>#</th><th>Product ID</th><th>Brand</th><th>Product</th>
                        <th>Segment</th><th>Type</th><th>Sign</th><th>Power</th>
                        <th>Description</th><th>Price</th><th>Retail</th><th>Qty</th><th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="previewBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Hidden import form --}}
    <form action="{{ route('dashboard.contact-lenses.import') }}" method="POST" id="importForm">
        @csrf
        <input type="hidden" name="import_data" id="importDataInput">
    </form>

    {{-- Quick-add Model Modal --}}
    <div id="addModelModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:14px;width:420px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
            <div style="background:linear-gradient(135deg,#3498db,#2980b9);padding:16px 20px;color:#fff;display:flex;align-items:center;justify-content:space-between;">
                <h5 style="margin:0;font-size:15px;font-weight:700;"><i class="fa fa-plus-circle"></i> Add Contact Lens Product Name</h5>
                <button onclick="document.getElementById('addModelModal').style.display='none'"
                        style="background:rgba(255,255,255,.2);border:none;color:#fff;width:28px;height:28px;border-radius:6px;font-size:16px;cursor:pointer;">&times;</button>
            </div>
            <div style="padding:20px;">
                <div id="addModelMsg" style="display:none;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:12px;"></div>
                <div class="form-group">
                    <label style="font-weight:600;font-size:13px;">Product Name <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="newModelName" class="form-control" placeholder="e.g. ACUVUE MOIST">
                </div>
            </div>
            <div style="padding:12px 20px;border-top:1px solid #f0f2f8;display:flex;justify-content:flex-end;gap:10px;">
                <button onclick="document.getElementById('addModelModal').style.display='none'"
                        style="background:#f4f6fb;color:#666;border:none;padding:8px 18px;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer;">Cancel</button>
                <button id="saveNewModel"
                        style="background:linear-gradient(135deg,#3498db,#2980b9);color:#fff;border:none;padding:8px 22px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;">
                    <i class="fa fa-check"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
var CSRF     = '{{ csrf_token() }}';
var CAT_ID_CL = 4;

// ── Sign Selector ────────────────────────────────────────────
function selectSign(sign) {
    document.querySelectorAll('.sign-btn').forEach(function(btn) {
        btn.classList.remove('selected-plus','selected-minus');
    });
    var btn = document.querySelector('.sign-btn[data-sign="'+sign+'"]');
    if (btn) btn.classList.add(sign === '+' ? 'selected-plus' : 'selected-minus');
    document.getElementById('sign').value = sign;
}

// ── Brand Segment Selector ────────────────────────────────────
function selectSeg(seg) {
    document.querySelectorAll('.seg-btn').forEach(function(btn) {
        btn.classList.remove('selected');
    });
    var btn = document.querySelector('.seg-btn[data-seg="'+seg+'"]');
    if (btn) btn.classList.add('selected');
    document.getElementById('brand_segment').value = seg;
}

// ── Lens Type Selector ────────────────────────────────────────
function selectUse(use) {
    document.querySelectorAll('.use-btn').forEach(function(btn) {
        btn.classList.remove('sel-daily','sel-monthly');
    });
    var btn = document.querySelector('.use-btn[data-use="'+use+'"]');
    if (btn) btn.classList.add(use === 'Daily' ? 'sel-daily' : 'sel-monthly');
    document.getElementById('lense_use').value = use;
}

// ── Power quick-pick ─────────────────────────────────────────
function setPower(val) {
    document.getElementById('power').value = val.toFixed(2);
    document.querySelectorAll('.power-btn').forEach(function(b) { b.classList.remove('sel-power'); });
    var active = document.querySelector('.power-btn[onclick="setPower('+val+')"]');
    if (active) active.classList.add('sel-power');
}

// ── Brand → Models AJAX ──────────────────────────────────────
$(document).ready(function() {
    $('#cl_brand').on('change', function() {
        var brandId = $(this).val();
        if (!brandId) {
            $('#cl_model').html('<option value="">Select Brand First</option>').prop('disabled', true);
            return;
        }
        $.ajax({
            headers: {'X-CSRF-TOKEN': CSRF},
            type: 'POST',
            url: '{{ route("dashboard.filter-models-by-category-and-brand-id") }}',
            data: { category_id: CAT_ID_CL, brand_id: brandId },
            success: function(res) {
                var html = '<option value="">Select Product Name</option>';
                $.each(res, function(i, m) {
                    html += '<option value="'+m.id+'">'+m.model_id+'</option>';
                });
                $('#cl_model').html(html).prop('disabled', false);
            }
        });
    });

    // Pre-select brand on old() error
    @if(old('brand_id'))
        $('#cl_brand').trigger('change');
        setTimeout(function() {
            $('#cl_model').val('{{ old('model_id') }}');
        }, 600);
    @endif

    // ── Add Model Modal ──────────────────────────────────────
    $('#btnAddModel').on('click', function() {
        var brandId = $('#cl_brand').val();
        if (!brandId) { Swal.fire({icon:'warning',title:'Select a Brand',text:'Please select a brand first before adding a product name.',confirmButtonColor:'#3498db'}); return; }
        $('#newModelName').val('');
        $('#addModelMsg').hide();
        var modal = document.getElementById('addModelModal');
        modal.style.display = 'flex';
        setTimeout(function(){ document.getElementById('newModelName').focus(); }, 150);
    });

    $('#saveNewModel').on('click', function() {
        var brandId   = $('#cl_brand').val();
        var modelName = $('#newModelName').val().trim();
        if (!modelName) { showAddModelMsg('Please enter a product name.', false); return; }

        $(this).prop('disabled', true).html('Saving…');
        $.ajax({
            headers: {'X-CSRF-TOKEN': CSRF},
            type: 'POST',
            url: '{{ route("dashboard.post-add-model") }}',
            data: { model_id: modelName, category_id: CAT_ID_CL, brand_id: brandId },
            success: function(res) {
                if (res && res.message) {
                    showAddModelMsg(res.message, false);
                } else {
                    showAddModelMsg('Product name "'+modelName+'" added!', true);
                    // Reload models (filtered by CL category + brand) and select new one
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': CSRF}, type: 'POST',
                        url: '{{ route("dashboard.filter-models-by-category-and-brand-id") }}',
                        data: { category_id: CAT_ID_CL, brand_id: brandId },
                        success: function(models) {
                            var html = '<option value="">Select Product Name</option>';
                            var newId = null;
                            $.each(models, function(i, m) {
                                if (m.model_id.toLowerCase() === modelName.toLowerCase()) newId = m.id;
                                html += '<option value="'+m.id+'">'+m.model_id+'</option>';
                            });
                            $('#cl_model').html(html).prop('disabled', false);
                            if (newId) $('#cl_model').val(newId);
                            setTimeout(function() {
                                document.getElementById('addModelModal').style.display = 'none';
                            }, 1000);
                        }
                    });
                }
            },
            complete: function() {
                $('#saveNewModel').prop('disabled', false).html('<i class="fa fa-check"></i> Add');
            }
        });
    });

    // Close modal on backdrop click
    document.getElementById('addModelModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });

    // ── Form validation before submit ───────────────────────
    $('#clForm').on('submit', function(e) {
        var errors = [];
        if (!$('#cl_brand').val())                    errors.push('Please select a brand.');
        if (!$('#cl_model').val())                    errors.push('Please select a product name.');
        if (!document.getElementById('brand_segment').value) errors.push('Please select a brand segment.');
        if (!document.getElementById('lense_use').value)     errors.push('Please select lens type.');
        if (!document.getElementById('sign').value)          errors.push('Please select sign (+ or -).');
        if (!document.getElementById('power').value)         errors.push('Please enter power.');

        if (errors.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Please fill all required fields',
                html: '<ul style="text-align:left;margin:0;padding-left:20px;">' +
                      errors.map(function(e){ return '<li style="margin-bottom:6px;">'+e+'</li>'; }).join('') +
                      '</ul>',
                confirmButtonColor: '#3498db'
            });
        }
    });
});

function showAddModelMsg(txt, ok) {
    var el = document.getElementById('addModelMsg');
    el.style.display = 'block';
    el.style.background = ok ? '#e8f8f5' : '#fee';
    el.style.color = ok ? '#27ae60' : '#e74c3c';
    el.style.border = ok ? '1px solid #a3e4d7' : '1px solid #f5c6cb';
    el.textContent = txt;
}

// ══════════════════════════════════════════════════════
// EXCEL IMPORT
// ══════════════════════════════════════════════════════
var parsedRows = [];

var zone = document.getElementById('importZone');
zone.addEventListener('dragover',  function(e) { e.preventDefault(); zone.classList.add('drag-over'); });
zone.addEventListener('dragleave', function()  { zone.classList.remove('drag-over'); });
zone.addEventListener('drop', function(e) {
    e.preventDefault(); zone.classList.remove('drag-over');
    if (e.dataTransfer.files[0]) processFile(e.dataTransfer.files[0]);
});
document.getElementById('excelFile').addEventListener('change', function() {
    if (this.files[0]) processFile(this.files[0]);
});

function processFile(file) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var wb = XLSX.read(new Uint8Array(e.target.result), {type:'array'});
        var sh = wb.SheetNames.indexOf('ContactLenses') >= 0 ? 'ContactLenses' : wb.SheetNames[0];
        var raw = XLSX.utils.sheet_to_json(wb.Sheets[sh], {header:1, defval:''});
        if (!raw || !raw.length) { Swal.fire({icon:'error',title:'Empty File',text:'The uploaded file is empty.',confirmButtonColor:'#3498db'}); return; }

        // Find header row
        var hIdx = -1, headers = [];
        for (var r = 0; r < Math.min(5, raw.length); r++) {
            var norm = raw[r].map(function(v){ return String(v).toLowerCase().replace(/[^a-z_]/g,'').trim(); });
            if (norm.indexOf('brand') >= 0 || norm.indexOf('power') >= 0) {
                hIdx = r; headers = norm; break;
            }
        }
        if (hIdx === -1) {
            Swal.fire({
                icon: 'error',
                title: 'Headers Not Found',
                html: 'Cannot find column headers.<br><small style="color:#888;">Row 1 must contain:<br><strong>brand, product, brand_segment, lense_use, sign, power, description, price, retail_price</strong></small>',
                confirmButtonColor: '#3498db'
            });
            return;
        }

        function ci(names) {
            for (var n=0;n<names.length;n++) { var i=headers.indexOf(names[n]); if (i>=0) return i; }
            return -1;
        }
        var CI = {
            pid:  ci(['productid','product_id']),
            brand:ci(['brand']),
            prod: ci(['product','productname','product_name']),
            seg:  ci(['brandsegment','brand_segment','segment']),
            use:  ci(['lenseuse','lense_use','lensetype','lens_type','type']),
            sign: ci(['sign']),
            pwr:  ci(['power']),
            desc: ci(['description']),
            pr:   ci(['price']),
            rpr:  ci(['retailprice','retail_price']),
            qty:  ci(['quantity','qty']),
        };

        parsedRows = [];
        var errCount = 0;
        for (var i = hIdx+1; i < raw.length; i++) {
            var row = raw[i];
            if (!row || row.every(function(v){ return String(v).trim() === ''; })) continue;
            function g(idx) { return idx >= 0 ? String(row[idx]||'').trim() : ''; }
            var errs = [];
            if (!g(CI.brand)) errs.push('missing brand');
            if (!g(CI.prod))  errs.push('missing product name');
            if (!g(CI.pwr))   errs.push('missing power');
            if (errs.length) errCount++;
            parsedRows.push({
                product_id:    g(CI.pid),
                brand:         g(CI.brand),
                product:       g(CI.prod),
                brand_segment: g(CI.seg),
                lense_use:     g(CI.use),
                sign:          g(CI.sign) || '+',
                power:         g(CI.pwr),
                description:   g(CI.desc),
                price:         parseFloat(row[CI.pr]  || 0) || 0,
                retail_price:  parseFloat(row[CI.rpr] || 0) || 0,
                quantity:      CI.qty >= 0 ? (parseInt(row[CI.qty]||0)||0) : 0,
                errors:        errs,
            });
        }
        renderPreview(errCount);
    };
    reader.readAsArrayBuffer(file);
}

function renderPreview(errCount) {
    var tbody = document.getElementById('previewBody');
    tbody.innerHTML = '';
    var validCount = 0;
    parsedRows.forEach(function(r, i) {
        var hasErr = r.errors.length > 0;
        if (!hasErr) validCount++;
        var status = hasErr
            ? '<span class="badge-err">✗ '+r.errors.join(' · ')+'</span>'
            : '<span class="badge-ok">✓ OK</span>';
        var tr = document.createElement('tr');
        if (hasErr) tr.style.background = '#fff5f5';
        tr.innerHTML =
            '<td style="color:#999;">'+(i+1)+'</td>'+
            '<td><code style="font-size:11px;color:#3498db;">'+( r.product_id||'<em>auto</em>')+'</code></td>'+
            '<td><span style="background:#e8f4fd;color:#2980b9;padding:2px 7px;border-radius:6px;font-size:11px;font-weight:700;">'+r.brand+'</span></td>'+
            '<td style="font-weight:600;">'+r.product+'</td>'+
            '<td style="font-size:11px;">'+r.brand_segment+'</td>'+
            '<td style="font-size:11px;">'+r.lense_use+'</td>'+
            '<td style="font-weight:900;font-size:15px;color:'+(r.sign==='+' ? '#27ae60':'#e74c3c')+';">'+r.sign+'</td>'+
            '<td style="font-weight:700;color:#4f46e5;">'+r.power+'</td>'+
            '<td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;">'+r.description+'</td>'+
            '<td style="font-weight:600;color:#27ae60;">'+r.price+'</td>'+
            '<td style="font-weight:600;color:#764ba2;">'+r.retail_price+'</td>'+
            '<td style="color:#0369a1;font-weight:600;">'+r.quantity+'</td>'+
            '<td>'+status+'</td>';
        tbody.appendChild(tr);
    });
    var title = '<strong>'+parsedRows.length+'</strong> rows';
    if (errCount) title += ' &nbsp;·&nbsp; <span style="color:#e74c3c;font-weight:600;">'+errCount+' errors</span>';
    title += ' &nbsp;·&nbsp; <span style="color:#27ae60;font-weight:600;">'+validCount+' ready</span>';
    document.getElementById('previewTitle').innerHTML = title;
    document.getElementById('importPreview').style.display = 'block';
}

$('#clearImport').on('click', function() {
    parsedRows = [];
    document.getElementById('previewBody').innerHTML = '';
    document.getElementById('importPreview').style.display = 'none';
    document.getElementById('excelFile').value = '';
});

$('#submitImport').on('click', function() {
    var valid = parsedRows.filter(function(r){ return r.errors.length === 0; });
    if (!valid.length) {
        Swal.fire({icon:'warning',title:'No Valid Rows',text:'All rows have errors. Please fix them in the preview table and re-upload.',confirmButtonColor:'#3498db'});
        return;
    }
    var invalidCount = parsedRows.length - valid.length;
    Swal.fire({
        title: 'Confirm Import',
        html: '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">' +
              '<div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:14px;text-align:center;">' +
              '<div style="font-size:30px;font-weight:900;color:#15803d;">'+valid.length+'</div>' +
              '<div style="font-size:11px;font-weight:700;color:#16a34a;text-transform:uppercase;">Ready to Import</div></div>' +
              '<div style="background:'+(invalidCount>0?'#fef2f2':'#f8fafc')+';border:1.5px solid '+(invalidCount>0?'#fca5a5':'#e2e8f0')+';border-radius:10px;padding:14px;text-align:center;">' +
              '<div style="font-size:30px;font-weight:900;color:'+(invalidCount>0?'#b91c1c':'#94a3b8')+';">'+invalidCount+'</div>' +
              '<div style="font-size:11px;font-weight:700;color:'+(invalidCount>0?'#ef4444':'#94a3b8')+';text-transform:uppercase;">Will Be Skipped</div></div></div>' +
              '<p style="font-size:13px;color:#555;margin:0;">Rows with errors will be skipped automatically.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-check-circle"></i> Import '+valid.length+' Lens(es)',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById('importDataInput').value = JSON.stringify(valid);
            $('#submitImport').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Importing…');
            document.getElementById('importForm').submit();
        }
    });
});

// ── Generate Template ────────────────────────────────────────
function generateCLTemplate() {
    var headers = ['product_id','brand','product','brand_segment','lense_use','sign','power','description','price','retail_price','quantity'];
    var sample  = ['','ACUVUE','ACUVUE MOIST','Clear','Daily','-','1.50','ACUVUE MOIST DAILY MINUS 1.50','25.00','50.00','10'];
    var ws = XLSX.utils.aoa_to_sheet([headers, sample]);
    ws['!cols'] = [{wch:12},{wch:16},{wch:20},{wch:14},{wch:10},{wch:6},{wch:8},{wch:35},{wch:10},{wch:12},{wch:8}];
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'ContactLenses');
    XLSX.writeFile(wb, 'contact_lens_import_template.xlsx');
}
window.generateCLTemplate = generateCLTemplate;

// ── Regenerate Barcode ────────────────────────────────────────
var BARCODE_URL = '{{ route("dashboard.contact-lenses.generate-barcode") }}';

$('#btnRegenBarcode').on('click', function() {
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

    $.ajax({
        url: BARCODE_URL,
        type: 'GET',
        success: function(res) {
            if (res.barcode) {
                $('#cl_barcode').val(res.barcode)
                    .css('border-color', '#27ae60')
                    .animate({ borderColor: '#e0e6ed' }, 1500);
            }
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Could not generate barcode. Try again.', confirmButtonColor: '#3498db' });
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i>');
        }
    });
});
</script>
@endsection
