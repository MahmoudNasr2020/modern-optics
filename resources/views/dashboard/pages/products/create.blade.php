@extends('dashboard.layouts.master')
@section('title', 'Add New Product')
@section('content')

    <style>
        /* ── Base styles (same as original) ── */
        .product-create-page{padding:20px}
        .box-product-create{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);overflow:hidden;border:none}
        .box-product-create .box-header{background:linear-gradient(135deg,#00a86b,#008b5a);color:#fff;padding:25px 30px;border:none;position:relative;overflow:hidden}
        .box-product-create .box-header::before{content:'';position:absolute;top:-50%;right:-10%;width:200px;height:200px;background:rgba(255,255,255,.1);border-radius:50%}
        .box-product-create .box-header .box-title{font-size:22px;font-weight:700;position:relative;z-index:1}
        .box-product-create .box-header .box-title i{background:rgba(255,255,255,.2);padding:10px;border-radius:8px;margin-right:10px}
        .box-product-create .box-header .btn{position:relative;z-index:1;background:rgba(255,255,255,.2);border:2px solid rgba(255,255,255,.4);color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;transition:all .3s}
        .box-product-create .box-header .btn:hover{background:#fff;color:#00a86b;transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,.2)}
        .form-section{background:#fff;padding:25px;border-radius:10px;margin-bottom:20px;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,.05);animation:fadeInUp .5s ease-out}
        .section-header{display:flex;align-items:center;margin-bottom:25px;padding-bottom:15px;border-bottom:2px solid #e8ecf7}
        .section-header .icon-box{width:45px;height:45px;background:linear-gradient(135deg,#00a86b,#008b5a);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;margin-right:15px;box-shadow:0 3px 10px rgba(0,168,107,.3)}
        .section-header h4{margin:0;font-size:18px;font-weight:700;color:#00a86b}
        .form-group label{font-weight:600;color:#555;font-size:13px;margin-bottom:8px;display:flex;align-items:center;gap:5px}
        .form-group label .required{color:#e74c3c;font-weight:700}
        .form-control{border:2px solid #e0e6ed;border-radius:8px!important;transition:all .3s;font-size:14px}
        .form-control:focus{border-color:#00a86b;box-shadow:0 0 0 3px rgba(0,168,107,.1)}
        .form-control:disabled{background-color:#f5f5f5;cursor:not-allowed}
        .help-text{font-size:12px;color:#999;margin-top:5px;font-style:italic}
        .btn-submit-group{background:#fff;padding:25px;border-radius:10px;text-align:center;border:2px solid #e8ecf7;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .btn-submit{background:linear-gradient(135deg,#00a86b,#008b5a);color:#fff;border:none;padding:14px 50px;border-radius:8px;font-size:16px;font-weight:700;transition:all .3s;box-shadow:0 3px 10px rgba(0,168,107,.3);margin:0 10px}
        .btn-submit:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(0,168,107,.4);color:#fff}
        .btn-cancel{background:#fff;color:#666;border:2px solid #ddd;padding:14px 50px;border-radius:8px;font-size:16px;font-weight:700;transition:all .3s;margin:0 10px}
        .btn-cancel:hover{background:#f8f9fa;border-color:#bbb;color:#333;transform:translateY(-3px)}
        @keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .product-id-display{background:linear-gradient(135deg,#f8f9ff,#fff);border:2px solid #00a86b;padding:15px;border-radius:8px;font-weight:bold;font-size:16px;color:#00a86b;text-align:center}

        /* ── "+ Add" inline buttons next to selects ── */
        .select-add-wrap{display:flex;gap:8px;align-items:center}
        .select-add-wrap select{flex:1}
        .btn-add-inline{flex-shrink:0;width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;font-size:18px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;box-shadow:0 2px 8px rgba(102,126,234,.3)}
        .btn-add-inline:hover{transform:translateY(-2px);box-shadow:0 4px 14px rgba(102,126,234,.45)}

        /* ── Quick-add modals ── */
        .qa-modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9998;align-items:center;justify-content:center}
        .qa-modal-backdrop.open{display:flex}
        .qa-modal{background:#fff;border-radius:16px;width:440px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;animation:modalPop .2s ease-out}
        @keyframes modalPop{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
        .qa-modal .qm-head{background:linear-gradient(135deg,#667eea,#764ba2);padding:18px 22px;color:#fff;display:flex;align-items:center;justify-content:space-between}
        .qa-modal .qm-head h5{margin:0;font-size:16px;font-weight:700}
        .qa-modal .qm-head button{background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:6px;font-size:18px;line-height:1;cursor:pointer}
        .qa-modal .qm-body{padding:22px}
        .qa-modal .qm-body .form-group{margin-bottom:16px}
        .qa-modal .qm-body .form-group label{font-weight:600;font-size:13px;color:#555;margin-bottom:6px;display:block}
        .qa-modal .qm-body .form-control{border:2px solid #e0e6ed;border-radius:8px;font-size:14px;width:100%}
        .qa-modal .qm-body .form-control:focus{border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.1);outline:none}
        .qa-modal .qm-foot{padding:14px 22px;border-top:1px solid #f0f2f8;display:flex;justify-content:flex-end;gap:10px}
        .qa-modal .qm-msg{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:12px;display:none}
        .qa-modal .qm-msg.ok{background:#e8f8f5;color:#27ae60;border:1px solid #a3e4d7}
        .qa-modal .qm-msg.err{background:#fee;color:#e74c3c;border:1px solid #f5c6cb}
        .btn-qm-save{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:9px 24px;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;transition:all .2s}
        .btn-qm-save:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(102,126,234,.4)}
        .btn-qm-cancel{background:#f4f6fb;color:#666;border:none;padding:9px 20px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer}

        /* ── Import section ── */
        .import-zone{border:3px dashed #b2dfdb;border-radius:14px;padding:36px 20px;text-align:center;cursor:pointer;background:#f0fdf8;position:relative;transition:all .3s}
        .import-zone:hover,.import-zone.drag-over{border-color:#00a86b;background:#e0f7ef}
        .import-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
        .import-zone .zi{font-size:44px;color:#b2dfdb;display:block;margin-bottom:10px;transition:color .3s}
        .import-zone:hover .zi{color:#00a86b}
        .import-zone .zt{font-size:15px;font-weight:600;color:#00a86b;margin-bottom:4px}
        .import-zone .zs{font-size:12px;color:#aaa}
        .btn-tpl{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;border:none;border-radius:9px;font-weight:600;font-size:13px;text-decoration:none;transition:all .25s}
        .btn-tpl:hover{transform:translateY(-2px);box-shadow:0 5px 18px rgba(39,174,96,.4);color:#fff;text-decoration:none}
        .prev-tbl{width:100%;border-collapse:collapse;font-size:12.5px;margin-top:14px}
        .prev-tbl th{background:#f4f6fb;padding:8px 10px;font-weight:700;font-size:11px;text-transform:uppercase;border-bottom:2px solid #e0e6ed;white-space:nowrap}
        .prev-tbl td{padding:8px 10px;border-bottom:1px solid #f0f2f8}
        .badge-ok{background:#e8f8f5;color:#27ae60;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700}
        .badge-err{background:#fee;color:#e74c3c;padding:2px 9px;border-radius:10px;font-size:11px}
        #importPreview{display:none}
        .import-alert{border-radius:10px;padding:14px 18px;margin-bottom:14px;font-size:13px}
        .import-alert.skipped{background:#fff3cd;border:1.5px solid #ffc107;color:#856404}
        .import-alert.errors{background:#f8d7da;border:1.5px solid #f5c6cb;color:#721c24}
        .import-alert ul{margin:6px 0 0 0;padding-right:18px}
        .import-alert ul li{margin-bottom:3px}
        .or-divider{display:flex;align-items:center;gap:16px;margin:0 0 28px}
        .or-divider .line{flex:1;height:2px;background:linear-gradient(to right,transparent,#e0e6ed)}
        .or-divider .line.rev{background:linear-gradient(to left,transparent,#e0e6ed)}
        .or-divider span{background:#fff;border:1.5px solid #e0e6ed;border-radius:20px;padding:6px 18px;font-size:12px;font-weight:700;color:#999;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.04)}
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-box-seam-fill"></i> Add New Product
            <small>Add a new product to inventory</small>
        </h1>
    </section>

    <div class="product-create-page">

        @if(session()->has('import_added'))

        {{-- ══ Import Results Banner ══ --}}
        <div style="background:#fff;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,.08);border:1.5px solid #e2e8f0;overflow:hidden;margin-bottom:20px;">

            {{-- Header bar --}}
            @if(session('import_added') > 0)
                <div style="background:linear-gradient(135deg,#10b981,#059669);padding:16px 22px;display:flex;align-items:center;gap:14px;">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check2-circle" style="font-size:24px;color:#fff;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-size:16px;font-weight:800;">Import Successful!</div>
                        <div style="color:rgba(255,255,255,.8);font-size:13px;">
                            {{ session('import_added') }} product(s) added to the system successfully.
                        </div>
                    </div>
                </div>
            @else
                <div style="background:linear-gradient(135deg,#f59e0b,#d97706);padding:16px 22px;display:flex;align-items:center;gap:14px;">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:24px;color:#fff;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-size:16px;font-weight:800;">No Products Were Imported</div>
                        <div style="color:rgba(255,255,255,.85);font-size:13px;">
                            All rows had errors or were already in the system. See details below.
                        </div>
                    </div>
                </div>
            @endif

            {{-- Details body --}}
            <div style="padding:18px 22px;">

                {{-- ✅ Errors --}}
                @php $importErrors = session('import_errors', []); @endphp
                @if(is_array($importErrors) && count($importErrors) > 0)
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                            <span style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:800;text-transform:uppercase;">
                                <i class="bi bi-x-circle-fill"></i> {{ count($importErrors) }} Error(s)
                            </span>
                            <span style="font-size:12px;color:#94a3b8;">These rows were NOT imported</span>
                        </div>
                        <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:14px 16px;max-height:220px;overflow-y:auto;">
                            @foreach($importErrors as $err)
                                <div style="display:flex;align-items:flex-start;gap:8px;padding:6px 0;border-bottom:1px solid #fee2e2;">
                                    <i class="bi bi-x-circle-fill" style="color:#ef4444;flex-shrink:0;margin-top:2px;font-size:13px;"></i>
                                    <span style="font-size:13px;color:#991b1b;">{{ is_string($err) ? $err : json_encode($err) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ⏭ Skipped --}}
                @php $importSkipped = session('import_skipped', []); @endphp
                @if(is_array($importSkipped) && count($importSkipped) > 0)
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                            <span style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:800;text-transform:uppercase;">
                                <i class="bi bi-skip-forward-circle-fill"></i> {{ count($importSkipped) }} Skipped
                            </span>
                            <span style="font-size:12px;color:#94a3b8;">Already exist or stock registration failed</span>
                        </div>
                        <div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;padding:14px 16px;max-height:180px;overflow-y:auto;">
                            @foreach($importSkipped as $msg)
                                <div style="display:flex;align-items:flex-start;gap:8px;padding:5px 0;border-bottom:1px solid #fef08a;">
                                    <i class="bi bi-dash-circle-fill" style="color:#d97706;flex-shrink:0;margin-top:2px;font-size:13px;"></i>
                                    <span style="font-size:13px;color:#78350f;">{{ is_string($msg) ? $msg : json_encode($msg) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if((!is_array($importErrors) || count($importErrors) === 0) && (!is_array($importSkipped) || count($importSkipped) === 0))
                    <p style="margin:0;font-size:13px;color:#94a3b8;text-align:center;padding:8px 0;">
                        <i class="bi bi-check-circle"></i> No skipped rows or errors.
                    </p>
                @endif
            </div>
        </div>

        @endif
        {{-- ═══════════ MANUAL FORM ═══════════ --}}
        <form action="{{ route('dashboard.post-add-product') }}" method="POST" id="productForm">
            @csrf
            <div class="box box-success box-product-create">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="bi bi-plus-circle-fill"></i> Product Information</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('dashboard.get-all-products') }}" class="btn btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="box-body" style="padding:30px;">
                    @include('dashboard.partials._errors')

                    {{-- Product Identification --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="bi bi-upc-scan"></i></div>
                            <h4>Product Identification</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product ID <span class="required">*</span></label>
                                    <input type="text" class="form-control product-id-display" name="product_id"
                                           value="{{ $productID['product_id'] + 1 }}">
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Auto-generated identifier</p>
                                </div>
                            </div>

                            {{-- Barcode --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-barcode"></i> Barcode <small style="font-weight:400;color:#aaa;">(optional)</small></label>
                                    <div style="display:flex;gap:6px;">
                                        <input type="text" class="form-control" name="barcode" id="barcode_input"
                                               value="{{ old('barcode') }}"
                                               placeholder="Scan or type barcode…"
                                               style="font-family:monospace;letter-spacing:1px;">
                                        <button type="button" id="generateBarcodeBtn"
                                                style="flex-shrink:0;padding:0 14px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:.2s;"
                                                title="Generate a unique barcode automatically">
                                            <i class="bi bi-magic"></i> Generate
                                        </button>
                                    </div>
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Leave blank to set later. Must be unique.</p>
                                </div>
                            </div>

                            {{-- Category + quick-add --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category <span class="required">*</span></label>
                                    <div class="select-add-wrap">
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat)
                                                @if($cat->id == 4) @continue @endif
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn-add-inline" onclick="openModal('catModal')" title="Add new category">+</button>
                                    </div>
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Product category classification</p>
                                </div>
                            </div>

                            {{-- Brand + quick-add --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Brand <span class="required">*</span></label>
                                    <div class="select-add-wrap">
                                        <select name="brand" id="brand" class="form-control" disabled required>
                                            <option value="">Select Category First</option>
                                        </select>
                                        <button type="button" class="btn-add-inline" onclick="openModal('brandModal')" title="Add new brand">+</button>
                                    </div>
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Select category to enable</p>
                                </div>
                            </div>
                        </div>

                        {{-- ══ Frames / Sunglasses: Model + Color + Size ══ --}}
                        <div id="fieldGroup-model-color-size" style="display:none;">
                        <div class="row">
                            {{-- Model + quick-add --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Model <span class="required">*</span></label>
                                    <div class="select-add-wrap">
                                        <select name="model" id="model" class="form-control" disabled>
                                            <option value="">Select Brand First</option>
                                        </select>
                                        <button type="button" class="btn-add-inline" onclick="openModal('modelModal')" title="Add new model">+</button>
                                    </div>
                                    <p class="help-text"><i class="bi bi-info-circle"></i> Select brand to enable</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="text" class="form-control" name="color" value="{{ old('color') }}" placeholder="e.g., Black, Blue, Red">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Size</label>
                                    <input type="text" class="form-control" name="size" value="{{ old('size') }}" placeholder="e.g., Small, Medium, 52mm">
                                </div>
                            </div>
                        </div>
                        </div>{{-- /fieldGroup-model-color-size --}}

                        {{-- ══ Reading Glasses: Power + Type ══ --}}
                        <div id="fieldGroup-power-type" style="display:none;">
                        <div class="row" style="margin-top:8px;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-eyeglasses"></i> Power</label>
                                    <input type="text" inputmode="decimal" class="form-control"
                                           name="power" id="power"
                                           list="power-datalist"
                                           value="{{ old('power') }}"
                                           placeholder="e.g. 1.50 — اختر أو اكتب">
                                    <datalist id="power-datalist">
                                        <option value="0.50">
                                        <option value="1.00">
                                        <option value="2.00">
                                        <option value="4.00">
                                        <option value="6.00">
                                    </datalist>
                                    <p class="help-text">اختار من القائمة أو اكتب القيمة مباشرة</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="bi bi-layout-three-columns"></i> Frame Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="folding metal" {{ old('type') == 'folding metal' ? 'selected' : '' }}>FOLDING METAL</option>
                                        <option value="folding plastic" {{ old('type') == 'folding plastic' ? 'selected' : '' }}>FOLDING PLASTIC</option>
                                        <option value="metal frame" {{ old('type') == 'metal frame' ? 'selected' : '' }}>METAL FRAME</option>
                                        <option value="plastic frame" {{ old('type') == 'plastic frame' ? 'selected' : '' }}>PLASTIC FRAME</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>{{-- /fieldGroup-power-type --}}

                        {{-- ══ Contact Lens Notice ══ --}}
                        <div id="contactLens-notice" style="display:none;">
                            <div style="background:#fff3cd;border:2px solid #ffc107;border-radius:10px;padding:18px 20px;margin-top:12px;">
                                <div style="display:flex;align-items:center;gap:14px;">
                                    <i class="bi bi-info-circle-fill" style="font-size:26px;color:#e67e22;flex-shrink:0;"></i>
                                    <div>
                                        <strong style="color:#7d4e00;font-size:15px;">Contact Lens products have a dedicated screen</strong><br>
                                        <span style="font-size:13px;color:#7d4e00;">Please use the <a href="{{ route('dashboard.contact-lenses.create') }}" style="color:#e67e22;font-weight:700;text-decoration:underline;">Contact Lens screen</a> to add contact lenses with full specs (Brand Segment, Lens Type, Sign, Power).</span>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- /contactLens-notice --}}

                    </div>

                    {{-- Description --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="bi bi-card-text"></i></div>
                            <h4>Description</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="description"
                                           value="{{ old('description') }}" placeholder="Product description" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="bi bi-currency-dollar"></i></div>
                            <h4>Pricing & Tax Information</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cost Price <span class="required">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price"
                                           value="{{ old('price') }}" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Retail Price <span class="required">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="retail_price" id="retail_price"
                                           value="{{ old('retail_price') }}" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tax (%)</label>
                                    <input type="number" step="0.01" class="form-control" name="tax"
                                           value="{{ old('tax',0) }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discount Type</label>
                                    <select name="discount_type" class="form-control">
                                        <option value="fixed">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discount Value</label>
                                    <input type="number" step="0.01" name="discount_value" class="form-control"
                                           value="{{ old('discount_value') }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lens Specs - Hidden (not needed for products) --}}
                    {{--
                    <div class="form-section">
                        <div class="section-header">
                            <div class="icon-box"><i class="bi bi-eyeglasses"></i></div>
                            <h4>Lens Specifications</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Power</label>
                                    <select name="power" class="form-control">
                                        <option value="">Select Power</option>
                                        @for($i=0;$i<=20;$i+=0.25)
                                            <option value="{{ $i }}">{{ number_format((float)$i,2,'.','') }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sign</label>
                                    <select name="sign" class="form-control">
                                        <option value="">Select Sign</option>
                                        <option value="00">00</option>
                                        <option value="-">Minus (-)</option>
                                        <option value="+">Plus (+)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Lens Use</label>
                                    <select name="lense_use" class="form-control">
                                        <option value="">Select Usage</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="folding metal">FOLDING METAL</option>
                                        <option value="folding plastic">FOLDING PLASTIC</option>
                                        <option value="metal frame">METAL FRAME</option>
                                        <option value="plastic frame">PLASTIC FRAME</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}

                    {{-- Auto-add to Store — with optional quantity --}}
                    <div style="background:#e8f5e9;border:1.5px solid #a5d6a7;border-radius:12px;padding:18px 20px;margin-bottom:20px;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                            <i class="bi bi-shop-window" style="font-size:22px;color:#27ae60;flex-shrink:0;"></i>
                            <div>
                                <strong style="color:#1e7e34;font-size:14px;">Main Store Stock Registration</strong><br>
                                <span style="font-size:12px;color:#555;">Product will be automatically added to the Store (main branch). Set the initial quantity below — leave at 0 to add stock later via transfers.</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#2e7d32;font-weight:700;margin-bottom:5px;">
                                        <i class="bi bi-boxes"></i> Initial Qty in Store <small style="font-weight:400;color:#888;">(optional)</small>
                                    </label>
                                    <input type="number" name="store_quantity" class="form-control" min="0"
                                           value="{{ old('store_quantity', 0) }}" placeholder="0"
                                           style="border-color:#a5d6a7;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#2e7d32;font-weight:700;margin-bottom:5px;">
                                        <i class="bi bi-arrow-down-circle"></i> Min Qty <small style="font-weight:400;color:#888;">(optional)</small>
                                    </label>
                                    <input type="number" name="store_min_qty" class="form-control" min="0"
                                           value="{{ old('store_min_qty', 0) }}" placeholder="0"
                                           style="border-color:#a5d6a7;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;color:#2e7d32;font-weight:700;margin-bottom:5px;">
                                        <i class="bi bi-arrow-up-circle"></i> Max Qty <small style="font-weight:400;color:#888;">(optional)</small>
                                    </label>
                                    <input type="number" name="store_max_qty" class="form-control" min="1"
                                           value="{{ old('store_max_qty', 999) }}" placeholder="999"
                                           style="border-color:#a5d6a7;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="btn-submit-group">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle-fill"></i> Add Product
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- ═══════════ DIVIDER ═══════════ --}}
        <div class="or-divider" style="margin-top:32px;">
            <div class="line"></div>
            <span><i class="bi bi-file-earmark-excel" style="color:#00a86b;margin-right:5px;"></i> Or Import from Excel</span>
            <div class="line rev"></div>
        </div>

        {{-- ═══════════ EXCEL IMPORT ═══════════ --}}
        <div class="form-section" style="border-color:#b2dfdb;">
            <div class="section-header">
                <div class="icon-box" style="background:linear-gradient(135deg,#00a86b,#008b5a);">
                    <i class="bi bi-file-earmark-excel"></i>
                </div>
                <h4>Bulk Import from Excel</h4>
            </div>

            <div class="d-flex align-items-start justify-content-between mb-4" style="flex-wrap:wrap;gap:14px;">
                <div style="font-size:13px;color:#555;line-height:1.8;max-width:580px;">
                    <i class="bi bi-info-circle text-primary"></i>
                    Download the template &rarr; fill in your products &rarr; upload.<br>
                    <strong>Category</strong> and <strong>Brand</strong> must match <em>exactly</em> the names in the system.<br>
                    <small class="text-muted">Valid rows are added. Duplicate IDs are skipped with a notice. Wrong names show a row error.</small>
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;">
                    <button type="button" onclick="generateTemplate()" class="btn-tpl">
                        <i class="bi bi-download"></i> Download Template
                    </button>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="{{ route('dashboard.get-all-categories') }}" target="_blank" style="font-size:11px;color:#667eea;font-weight:600;">📋 View Categories</a>
                        <span style="color:#ccc;">|</span>
                        <a href="{{ route('dashboard.get-all-brands') }}" target="_blank" style="font-size:11px;color:#667eea;font-weight:600;">🏷️ View Brands</a>
                        <span style="color:#ccc;">|</span>
                        <a href="{{ route('dashboard.get-all-models') }}" target="_blank" style="font-size:11px;color:#667eea;font-weight:600;">🔧 View Models</a>
                    </div>
                </div>
            </div>

            <div class="import-zone" id="importZone">
                <input type="file" id="excelFile" accept=".xlsx,.xls,.csv">
                <i class="bi bi-cloud-upload zi"></i>
                <div class="zt">Drop your Excel file here</div>
                <div class="zs">.xlsx &nbsp;·&nbsp; .xls &nbsp;·&nbsp; .csv &nbsp;·&nbsp; or click to browse</div>
            </div>

            <div id="importPreview">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-2" style="flex-wrap:wrap;gap:10px;">
                    <span id="previewTitle" style="font-size:13px;font-weight:600;"></span>
                    <div style="display:flex;gap:8px;">
                        <button type="button" class="btn btn-sm btn-default" id="clearImport">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                        <button type="button" class="btn btn-sm btn-success" id="submitImport">
                            <i class="bi bi-check-circle"></i> Import Valid Rows
                        </button>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="prev-tbl">
                        <thead>
                        <tr>
                            <th>#</th><th>Product ID</th><th>Description</th>
                            <th>Category</th><th>Brand</th><th>Model</th>
                            <th>Color</th><th>Size</th>
                            <th style="color:#0369a1;">Qty</th>
                            <th style="color:#065f46;">Min</th>
                            <th style="color:#7c3aed;">Max</th>
                            <th>Price</th><th>Retail</th><th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.import-products') }}" method="POST" id="importForm">
            @csrf
            <input type="hidden" name="import_data" id="importDataInput">
        </form>
    </div>


    {{-- ══════════════════════════════════════════════════════════
         QUICK-ADD MODALS  (Category / Brand / Model)
    ══════════════════════════════════════════════════════════ --}}

    {{-- ── 1. Add Category ── --}}
    <div class="qa-modal-backdrop" id="catModal" onclick="closeIfBackdrop(event,'catModal')">
        <div class="qa-modal">
            <div class="qm-head">
                <h5><i class="bi bi-tag-fill"></i> Add New Category</h5>
                <button onclick="closeModal('catModal')">&times;</button>
            </div>
            <div class="qm-body">
                <div class="qm-msg ok" id="catMsg"></div>
                <div class="form-group">
                    <label>Category Name <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="catName" class="form-control" placeholder="e.g. SUN GLASSES">
                </div>
            </div>
            <div class="qm-foot">
                <button class="btn-qm-cancel" onclick="closeModal('catModal')">Cancel</button>
                <button class="btn-qm-save" id="saveCatBtn">
                    <i class="bi bi-check-circle"></i> Add Category
                </button>
            </div>
        </div>
    </div>

    {{-- ── 2. Add Brand ── --}}
    <div class="qa-modal-backdrop" id="brandModal" onclick="closeIfBackdrop(event,'brandModal')">
        <div class="qa-modal">
            <div class="qm-head">
                <h5><i class="bi bi-award-fill"></i> Add New Brand</h5>
                <button onclick="closeModal('brandModal')">&times;</button>
            </div>
            <div class="qm-body">
                <div class="qm-msg ok" id="brandMsg"></div>
                <div class="form-group">
                    <label>Category <span style="color:#e74c3c;">*</span></label>
                    <select id="bm_catId" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand Name <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="brandName" class="form-control" placeholder="e.g. RAY-BAN">
                </div>
            </div>
            <div class="qm-foot">
                <button class="btn-qm-cancel" onclick="closeModal('brandModal')">Cancel</button>
                <button class="btn-qm-save" id="saveBrandBtn">
                    <i class="bi bi-check-circle"></i> Add Brand
                </button>
            </div>
        </div>
    </div>

    {{-- ── 3. Add Model ── --}}
    <div class="qa-modal-backdrop" id="modelModal" onclick="closeIfBackdrop(event,'modelModal')">
        <div class="qa-modal">
            <div class="qm-head">
                <h5><i class="bi bi-box2-fill"></i> Add New Model</h5>
                <button onclick="closeModal('modelModal')">&times;</button>
            </div>
            <div class="qm-body">
                <div class="qm-msg ok" id="modelMsg"></div>
                <div class="form-group">
                    <label>Category <span style="color:#e74c3c;">*</span></label>
                    <select id="mm_catId" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand <span style="color:#e74c3c;">*</span></label>
                    <select id="mm_brandId" class="form-control" disabled>
                        <option value="">Select Category First</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Model ID <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="modelId" class="form-control" placeholder="e.g. RB3025">
                </div>
            </div>
            <div class="qm-foot">
                <button class="btn-qm-cancel" onclick="closeModal('modelModal')">Cancel</button>
                <button class="btn-qm-save" id="saveModelBtn">
                    <i class="bi bi-check-circle"></i> Add Model
                </button>
            </div>
        </div>
    </div>


    {{-- ══ Import Confirmation Modal ══ --}}
    <div class="modal fade" id="importConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.22);">
                <div class="modal-header" style="background:linear-gradient(135deg,#10b981,#059669);border:none;padding:20px 24px;">
                    <button type="button" class="close" data-dismiss="modal"
                            style="color:#fff;opacity:1;font-size:22px;line-height:1;">&times;</button>
                    <h4 class="modal-title" style="color:#fff;font-weight:800;font-size:17px;">
                        <i class="bi bi-file-earmark-arrow-up"></i> Confirm Import
                    </h4>
                </div>
                <div class="modal-body" style="padding:24px;" id="importConfirmBody">
                    {{-- filled by JS --}}
                </div>
                <div class="modal-footer" style="border-top:2px solid #f1f5f9;padding:16px 24px;background:#fafbfc;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" data-dismiss="modal"
                            style="display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;border:none;cursor:pointer;background:#f1f5f9;color:#475569;transition:all .2s;">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="button" id="confirmImportBtn"
                            style="display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:700;border:none;cursor:pointer;background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,.35);transition:all .2s;">
                        <i class="bi bi-check2-circle"></i> <span id="confirmImportBtnText">Import</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        var CSRF = '{{ csrf_token() }}';

        // ══════════════════════════════════════════════════════════════
        // CATEGORY → BRAND → MODEL chain
        // ══════════════════════════════════════════════════════════════
        function loadBrandsByCategory(catId, targetSelect, autoSelectId) {
            if (!catId) {
                $(targetSelect).html('<option value="">Select Category First</option>').prop('disabled', true);
                return;
            }
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF},
                type: 'POST',
                url: '{{ route("dashboard.filter-brands-by-category-id") }}',
                data: {category_id: catId},
                success: function(res) {
                    var html = '<option value="">Select Brand</option>';
                    $.each(res, function(i, b) {
                        var sel = (autoSelectId && b.id == autoSelectId) ? ' selected' : '';
                        html += '<option value="'+b.id+'"'+sel+'>'+b.brand_name+'</option>';
                    });
                    $(targetSelect).html(html).prop('disabled', false);
                    if (autoSelectId) $(targetSelect).trigger('change');
                }
            });
        }

        function loadModelsByBrand(brandId, targetSelect, autoSelectId) {
            if (!brandId) {
                $(targetSelect).html('<option value="">Select Brand First</option>').prop('disabled', true);
                return;
            }
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF},
                type: 'POST',
                url: '{{ route("dashboard.filter-models-by-brand-id") }}',
                data: {brand_id: brandId},
                success: function(res) {
                    var html = '<option value="">Select Model</option>';
                    $.each(res, function(i, m) {
                        var sel = (autoSelectId && m.id == autoSelectId) ? ' selected' : '';
                        html += '<option value="'+m.id+'"'+sel+'>'+m.model_id+'</option>';
                    });
                    $(targetSelect).html(html).prop('disabled', false);
                }
            });
        }

        // ──────────────────────────────────────────────────────────
        // Category → Field Visibility Map
        //  1 = Sun Glasses   2 = Frames        → model + color + size
        //  6 = Reading Glasses                  → power + type
        //  4 = CONTACT LENS                     → notice / redirect
        //  3 = O L LENSES, 7 = Chains, 8 = C.L Solution, 10 = Services → brand only
        // ──────────────────────────────────────────────────────────
        var CAT_MODEL_COLOR_SIZE = [1, 2];          // show model/color/size row
        var CAT_POWER_TYPE       = [6];             // show power/type row
        var CAT_CONTACT_LENS     = [4];             // show redirect notice

        function applyCategoryFields(catId) {
            catId = parseInt(catId) || 0;
            var showMCS  = CAT_MODEL_COLOR_SIZE.indexOf(catId) >= 0;
            var showPT   = CAT_POWER_TYPE.indexOf(catId) >= 0;
            var showCL   = CAT_CONTACT_LENS.indexOf(catId) >= 0;

            $('#fieldGroup-model-color-size')[showMCS  ? 'show' : 'hide']();
            $('#fieldGroup-power-type')[showPT   ? 'show' : 'hide']();
            $('#contactLens-notice')[showCL   ? 'show' : 'hide']();

            // Disable submit when contact lens is selected (use dedicated screen)
            $('.btn-submit').prop('disabled', showCL);

            // Model select: require only when visible
            $('#model').prop('required', showMCS);
            if (!showMCS) {
                $('#model').html('<option value="">Select Brand First</option>').prop('disabled', true);
            }
        }

        $(document).ready(function() {
            // Main form: category change
            $('#category').on('change', function() {
                var catId  = $(this).val();
                loadBrandsByCategory(catId, '#brand', null);
                applyCategoryFields(catId);
            });

            // Main form: brand change
            $('#brand').on('change', function() {
                // Only load models when model field group is visible
                var catId = parseInt($('#category').val()) || 0;
                if (CAT_MODEL_COLOR_SIZE.indexOf(catId) >= 0) {
                    loadModelsByBrand($(this).val(), '#model', null);
                }
            });

            // Model modal: category change → load brands
            $('#mm_catId').on('change', function() {
                loadBrandsByCategory($(this).val(), '#mm_brandId', null);
            });

            // ── Stock section toggle (iCheck events) ─────────────────
            $('#toggleStock').on('ifChecked', function() {
                $('#stockFields').show();
                $('#stock_branch_id').attr('required', true);
            }).on('ifUnchecked', function() {
                $('#stockFields').hide();
                $('#stock_branch_id').removeAttr('required');
            });

            // ── Form submit loading ──────────────────────────────────
            $('#productForm').on('submit', function() {
                $(this).find('.btn-submit').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Adding...');
            });

            // ════════════════════════════════════════════════════════
            // SAVE CATEGORY
            // ════════════════════════════════════════════════════════
            $('#saveCatBtn').on('click', function() {
                var name = $('#catName').val().trim();
                if (!name) { showQmMsg('catMsg', 'Please enter category name.', false); return; }

                $(this).prop('disabled', true).html('Saving...');
                var self = this;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF},
                    type: 'POST',
                    url: '{{ route("dashboard.add-category") }}',
                    data: {category_name: name},
                    success: function(res) {
                        if (res && res.message) {
                            showQmMsg('catMsg', res.message, false);
                        } else {
                            // Success — fetch the new category id by reloading options
                            $.get('{{ route("dashboard.get-all-categories") }}', function() {}).always(function() {
                                // Re-fetch categories via a quick AJAX to get the new id
                                $.ajax({
                                    headers: {'X-CSRF-TOKEN': CSRF},
                                    type: 'POST',
                                    url: '{{ route("dashboard.filter-brands-by-category-id") }}',
                                    // trick: get all brands to force a categories refresh isn't available
                                    // Instead we reload the select fresh
                                });
                            });

                            // Simple approach: add the option to both selects and select it
                            showQmMsg('catMsg', 'Category "'+name+'" added successfully!', true);

                            // We need the new ID — fetch it
                            $.ajax({
                                url: '/dashboard/get-all-categories-json',
                                type: 'GET',
                                success: function(cats) {
                                    refreshCategorySelects(cats, name);
                                },
                                error: function() {
                                    // Fallback: reload page after 1.5s
                                    setTimeout(function(){ location.reload(); }, 1500);
                                }
                            });
                        }
                    },
                    complete: function() {
                        $('#saveCatBtn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Add Category');
                    }
                });
            });

            // ════════════════════════════════════════════════════════
            // SAVE BRAND
            // ════════════════════════════════════════════════════════
            $('#saveBrandBtn').on('click', function() {
                var catId = $('#bm_catId').val();
                var name  = $('#brandName').val().trim();
                if (!catId) { showQmMsg('brandMsg', 'Please select a category.', false); return; }
                if (!name)  { showQmMsg('brandMsg', 'Please enter brand name.', false); return; }

                $(this).prop('disabled', true).html('Saving...');

                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF},
                    type: 'POST',
                    url: '{{ route("dashboard.post-add-brand") }}',
                    data: {brand_name: name, category_id: catId},
                    success: function(res) {
                        if (res && res.message) {
                            showQmMsg('brandMsg', res.message, false);
                        } else {
                            showQmMsg('brandMsg', 'Brand "'+name+'" added successfully!', true);
                            // If main form has same category selected, reload brands and auto-select new one
                            var mainCatId = $('#category').val();
                            if (mainCatId == catId) {
                                loadBrandsAndSelectNew(catId, name);
                            }
                            setTimeout(function(){ closeModal('brandModal'); }, 1200);
                        }
                    },
                    complete: function() {
                        $('#saveBrandBtn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Add Brand');
                    }
                });
            });

            // ════════════════════════════════════════════════════════
            // SAVE MODEL
            // ════════════════════════════════════════════════════════
            $('#saveModelBtn').on('click', function() {
                var catId   = $('#mm_catId').val();
                var brandId = $('#mm_brandId').val();
                var modelId = $('#modelId').val().trim();
                if (!catId)   { showQmMsg('modelMsg', 'Please select a category.', false); return; }
                if (!brandId) { showQmMsg('modelMsg', 'Please select a brand.', false); return; }
                if (!modelId) { showQmMsg('modelMsg', 'Please enter model ID.', false); return; }

                $(this).prop('disabled', true).html('Saving...');

                $.ajax({
                    headers: {'X-CSRF-TOKEN': CSRF},
                    type: 'POST',
                    url: '{{ route("dashboard.post-add-model") }}',
                    data: {model_id: modelId, category_id: catId, brand_id: brandId},
                    success: function(res) {
                        if (res && res.message) {
                            showQmMsg('modelMsg', res.message, false);
                        } else {
                            showQmMsg('modelMsg', 'Model "'+modelId+'" added successfully!', true);
                            // If main form has same brand selected, reload models and auto-select new one
                            var mainBrandId = $('#brand').val();
                            if (mainBrandId == brandId) {
                                loadModelsAndSelectNew(brandId, modelId);
                            }
                            setTimeout(function(){ closeModal('modelModal'); }, 1200);
                        }
                    },
                    complete: function() {
                        $('#saveModelBtn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Add Model');
                    }
                });
            });

        });

        // ── Load brands then auto-select newly added one ────────────
        function loadBrandsAndSelectNew(catId, newBrandName) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF},
                type: 'POST',
                url: '{{ route("dashboard.filter-brands-by-category-id") }}',
                data: {category_id: catId},
                success: function(res) {
                    var html = '<option value="">Select Brand</option>';
                    var newId = null;
                    $.each(res, function(i, b) {
                        if (b.brand_name.toLowerCase() === newBrandName.toLowerCase()) newId = b.id;
                        html += '<option value="'+b.id+'">'+b.brand_name+'</option>';
                    });
                    $('#brand').html(html).prop('disabled', false);
                    if (newId) {
                        $('#brand').val(newId);
                        loadModelsByBrand(newId, '#model', null);
                    }
                }
            });
        }

        // ── Load models then auto-select newly added one ────────────
        function loadModelsAndSelectNew(brandId, newModelId) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': CSRF},
                type: 'POST',
                url: '{{ route("dashboard.filter-models-by-brand-id") }}',
                data: {brand_id: brandId},
                success: function(res) {
                    var html = '<option value="">Select Model</option>';
                    var newId = null;
                    $.each(res, function(i, m) {
                        if (m.model_id.toLowerCase() === newModelId.toLowerCase()) newId = m.id;
                        html += '<option value="'+m.id+'">'+m.model_id+'</option>';
                    });
                    $('#model').html(html).prop('disabled', false);
                    if (newId) $('#model').val(newId);
                }
            });
        }

        // ── Modal helpers ────────────────────────────────────────────
        function openModal(id) {
            // Pre-fill category in brand/model modals from main form selection
            var mainCat = $('#category').val();
            if (id === 'brandModal' && mainCat) {
                $('#bm_catId').val(mainCat);
            }
            if (id === 'modelModal' && mainCat) {
                $('#mm_catId').val(mainCat).trigger('change');
                // Wait for brands to load, then select current brand
                setTimeout(function() {
                    var mainBrand = $('#brand').val();
                    if (mainBrand) $('#mm_brandId').val(mainBrand);
                }, 500);
            }
            // Reset fields
            clearQmMsg(id);
            if (id === 'catModal')   { $('#catName').val(''); }
            if (id === 'brandModal') { $('#brandName').val(''); }
            if (id === 'modelModal') { $('#modelId').val(''); }

            $('#'+id).addClass('open');
            // Focus input
            setTimeout(function(){
                $('#'+id+' input[type=text]:first').focus();
            }, 150);
        }
        function closeModal(id) { $('#'+id).removeClass('open'); }
        function closeIfBackdrop(e, id) { if (e.target === document.getElementById(id)) closeModal(id); }

        function showQmMsg(msgId, text, ok) {
            var el = $('#'+msgId);
            el.text(text).removeClass('ok err').addClass(ok ? 'ok' : 'err').show();
        }
        function clearQmMsg(modalId) {
            $('#'+modalId+' .qm-msg').hide().text('');
        }

        // Enter key in modal inputs triggers save
        $(document).on('keydown', '.qa-modal input', function(e) {
            if (e.key === 'Enter') {
                $(this).closest('.qa-modal').find('.btn-qm-save').trigger('click');
            }
        });

        // ══════════════════════════════════════════════════════════
        // EXCEL IMPORT
        // ══════════════════════════════════════════════════════════
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
                var wb  = XLSX.read(new Uint8Array(e.target.result), {type:'array'});
                var sheetName = wb.SheetNames.indexOf('Products') >= 0 ? 'Products' : wb.SheetNames[0];
                var raw = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], {header:1, defval:''});

                if (!raw || !raw.length) { alert('File is empty!'); return; }

                // Find header row (search first 5 rows)
                var hIdx = -1, headers = [];
                for (var r = 0; r < Math.min(5, raw.length); r++) {
                    var norm = raw[r].map(function(v){ return String(v).toLowerCase().replace(/[^a-z_]/g,'').trim(); });
                    if (norm.indexOf('description') >= 0 || norm.indexOf('category') >= 0) {
                        hIdx = r; headers = norm; break;
                    }
                }
                if (hIdx === -1) { alert('Cannot find headers! Make sure row 1 has columns: description, category, brand'); return; }

                function ci(names) {
                    for (var n = 0; n < names.length; n++) {
                        var i = headers.indexOf(names[n]); if (i >= 0) return i;
                    }
                    return -1;
                }

                var CI = {
                    pid:    ci(['productid','product_id']),
                    desc:   ci(['description']),
                    cat:    ci(['category']),
                    brd:    ci(['brand']),
                    mdl:    ci(['model']),
                    col:    ci(['color']),
                    sz:     ci(['size']),
                    pr:     ci(['price']),
                    rpr:    ci(['retailprice','retail_price']),
                    tax:    ci(['tax']),
                    dtyp:   ci(['discounttype','discount_type']),
                    dval:   ci(['discountvalue','discount_value']),
                    bseg:   ci(['brandsegment','brand_segment']),
                    pwr:    ci(['power']),
                    sgn:    ci(['sign']),
                    luse:   ci(['lenseuse','lense_use']),
                    typ:    ci(['type']),
                    qty:    ci(['quantity','qty']),
                    minqty: ci(['minquantity','min_quantity','minqty','min']),
                    maxqty: ci(['maxquantity','max_quantity','maxqty','max']),
                };

                if (CI.desc === -1 || CI.cat === -1) {
                    alert('Missing required columns: "description" and "category".\nFound: ' + headers.join(', '));
                    return;
                }

                parsedRows = [];
                var errCount = 0;

                for (var i = hIdx + 1; i < raw.length; i++) {
                    var row = raw[i];
                    if (!row || row.every(function(v){ return String(v).trim() === ''; })) continue;

                    function g(idx) { return idx >= 0 ? String(row[idx] || '').trim() : ''; }

                    var rowErrors = [];
                    var desc  = g(CI.desc);
                    var cat   = g(CI.cat);
                    var brd   = g(CI.brd);

                    if (!desc) rowErrors.push('missing description');
                    if (!cat)  rowErrors.push('missing category');
                    if (!brd)  rowErrors.push('missing brand');
                    if (rowErrors.length) errCount++;

                    parsedRows.push({
                        product_id:     g(CI.pid),
                        description:    desc,
                        category:       cat,
                        brand:          brd,
                        model:          g(CI.mdl),
                        color:          g(CI.col),
                        size:           g(CI.sz),
                        price:          parseFloat(row[CI.pr]   || 0) || 0,
                        retail_price:   parseFloat(row[CI.rpr]  || 0) || 0,
                        tax:            parseFloat(row[CI.tax]  || 0) || 0,
                        discount_type:  g(CI.dtyp)  || 'fixed',
                        discount_value: parseFloat(row[CI.dval] || 0) || 0,
                        brand_segment:  g(CI.bseg),
                        power:          g(CI.pwr),
                        sign:           g(CI.sgn),
                        lense_use:      g(CI.luse),
                        type:           g(CI.typ),
                        quantity:       CI.qty    >= 0 ? (parseInt(row[CI.qty]    || 0) || 0) : 0,
                        min_quantity:   CI.minqty >= 0 ? (parseInt(row[CI.minqty] || 0) || 0) : 0,
                        max_quantity:   CI.maxqty >= 0 && parseInt(row[CI.maxqty] || 0) > 0
                                            ? (parseInt(row[CI.maxqty]) || 999) : 999,
                        errors:         rowErrors,
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
                    ? '<span class="badge-err">✗ ' + r.errors.join(' · ') + '</span>'
                    : '<span class="badge-ok">✓ OK</span>';
                var tr = document.createElement('tr');
                if (hasErr) tr.style.background = '#fff5f5';
                tr.innerHTML =
                    '<td style="color:#999;">' + (i+1) + '</td>' +
                    '<td><code style="font-size:11px;background:#f0fdf8;padding:2px 6px;border-radius:4px;color:#00a86b;">' + (r.product_id || '<em>auto</em>') + '</code></td>' +
                    '<td style="font-weight:600;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="'+r.description+'">' + r.description + '</td>' +
                    '<td><span style="background:#e8f8f5;color:#27ae60;padding:2px 7px;border-radius:8px;font-size:11px;font-weight:700;">' + (r.category || '—') + '</span></td>' +
                    '<td><span style="background:#e8f4fd;color:#2980b9;padding:2px 7px;border-radius:8px;font-size:11px;font-weight:700;">' + (r.brand || '—') + '</span></td>' +
                    '<td style="font-size:12px;color:#888;">' + (r.model || '—') + '</td>' +
                    '<td style="font-size:12px;">' + (r.color || '—') + '</td>' +
                    '<td style="font-size:12px;">' + (r.size  || '—') + '</td>' +
                    '<td style="font-weight:700;color:#0369a1;">' + r.quantity + '</td>' +
                    '<td style="font-size:11px;color:#065f46;">' + r.min_quantity + '</td>' +
                    '<td style="font-size:11px;color:#7c3aed;">' + (r.max_quantity >= 999 ? '—' : r.max_quantity) + '</td>' +
                    '<td style="font-weight:600;color:#00a86b;">' + r.price + '</td>' +
                    '<td style="font-weight:600;color:#764ba2;">' + r.retail_price + '</td>' +
                    '<td>' + status + '</td>';
                tbody.appendChild(tr);
            });

            var title = '<strong>' + parsedRows.length + '</strong> rows detected';
            if (errCount) title += ' &nbsp;·&nbsp; <span style="color:#e74c3c;font-weight:600;">' + errCount + ' with errors</span>';
            title += ' &nbsp;·&nbsp; <span style="color:#27ae60;font-weight:600;">' + validCount + ' ready to import</span>';
            document.getElementById('previewTitle').innerHTML = title;
            document.getElementById('importPreview').style.display = 'block';
        }

        $('#clearImport').on('click', function() {
            parsedRows = [];
            document.getElementById('previewBody').innerHTML = '';
            document.getElementById('importPreview').style.display = 'none';
            document.getElementById('excelFile').value = '';
        });

        /*$('#submitImport').on('click', function() {
            var valid = parsedRows.filter(function(r){ return r.errors.length === 0; });
            if (!valid.length) { alert('No valid rows to import!'); return; }
            if (!confirm('Import ' + valid.length + ' valid product(s)?\n\nInvalid rows will be skipped.')) return;
            document.getElementById('importDataInput').value = JSON.stringify(valid);
            $(this).prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Importing...');
            document.getElementById('importForm').submit();
        });*/

        // ══════════════════════════════════════════════════════════
        // GENERATE TEMPLATE (XLSX.js client-side)
        // ══════════════════════════════════════════════════════════
        function generateTemplate() {
            var headers = [
                'product_id', 'description', 'category', 'brand', 'model',
                'color', 'size', 'price', 'retail_price', 'tax',
                'discount_type', 'discount_value', 'brand_segment',
                'quantity', 'min_quantity', 'max_quantity'
            ];
            var sample = [
                '100001', 'Sample Product Description', 'SUN GLASSES', 'RAY-BAN', 'RB3025',
                'Black', '52mm', '50.00', '100.00', '0',
                'fixed', '0', '',
                '10', '2', '50'
            ];
            var ws = XLSX.utils.aoa_to_sheet([headers, sample]);

            // Column widths
            ws['!cols'] = [
                {wch:12},{wch:30},{wch:18},{wch:16},{wch:12},
                {wch:10},{wch:8},{wch:10},{wch:12},{wch:6},
                {wch:14},{wch:14},{wch:14},
                {wch:10},{wch:12},{wch:12}
            ];

            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Products');
            XLSX.writeFile(wb, 'product_import_template.xlsx');
        }
        window.generateTemplate = generateTemplate;

        // ── Import Valid Rows → beautiful confirmation modal ───────────
        $('#submitImport').on('click', function () {
            var valid   = parsedRows.filter(function(r) { return r.errors.length === 0; });
            var invalid = parsedRows.filter(function(r) { return r.errors.length > 0; });

            if (!valid.length) {
                // No valid rows — show inline message, no ugly alert
                var noValidHtml =
                    '<div style="text-align:center;padding:20px 0;">' +
                    '<div style="width:60px;height:60px;background:linear-gradient(135deg,#fee2e2,#fecaca);border-radius:16px;display:inline-flex;align-items:center;justify-content:center;font-size:28px;color:#ef4444;margin-bottom:14px;">' +
                    '<i class="bi bi-x-octagon-fill"></i></div>' +
                    '<div style="font-size:16px;font-weight:800;color:#1e293b;margin-bottom:6px;">No Valid Rows to Import</div>' +
                    '<div style="font-size:13px;color:#94a3b8;">' + parsedRows.length + ' row(s) parsed, all have errors. Fix the issues in the preview table and re-upload.</div>' +
                    '</div>';
                $('#importConfirmBody').html(noValidHtml);
                // Hide the confirm button
                $('#confirmImportBtn').hide();
                $('#importConfirmModal').modal('show');
                return;
            }

            // Build the confirmation modal body
            var html = '';

            // Summary strip
            html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">';

            // Valid count card
            html += '<div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;border-radius:12px;padding:16px;text-align:center;">';
            html += '<div style="font-size:32px;font-weight:900;color:#15803d;line-height:1;">' + valid.length + '</div>';
            html += '<div style="font-size:11px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:.5px;margin-top:4px;"><i class="bi bi-check-circle-fill"></i> Ready to Import</div>';
            html += '</div>';

            // Invalid count card
            html += '<div style="background:' + (invalid.length > 0 ? 'linear-gradient(135deg,#fef2f2,#fee2e2);border:1.5px solid #fca5a5' : '#f8fafc;border:1.5px solid #e2e8f0') + ';border-radius:12px;padding:16px;text-align:center;">';
            html += '<div style="font-size:32px;font-weight:900;color:' + (invalid.length > 0 ? '#b91c1c' : '#94a3b8') + ';line-height:1;">' + invalid.length + '</div>';
            html += '<div style="font-size:11px;font-weight:700;color:' + (invalid.length > 0 ? '#ef4444' : '#94a3b8') + ';text-transform:uppercase;letter-spacing:.5px;margin-top:4px;"><i class="bi bi-x-circle-fill"></i> Will Be Skipped</div>';
            html += '</div>';
            html += '</div>';

            // Preview of first 3 valid rows
            if (valid.length > 0) {
                html += '<div style="margin-bottom:16px;">';
                html += '<div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Preview (first ' + Math.min(3, valid.length) + ' rows):</div>';
                html += '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden;">';
                var previewCount = Math.min(3, valid.length);
                for (var pi = 0; pi < previewCount; pi++) {
                    var r = valid[pi];
                    html += '<div style="padding:10px 14px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:10px;">';
                    html += '<span style="background:#eef2ff;color:#4f46e5;font-family:monospace;font-size:11px;font-weight:700;padding:2px 8px;border-radius:5px;flex-shrink:0;">' + (r.product_id || 'auto') + '</span>';
                    html += '<span style="font-size:13px;font-weight:600;color:#1e293b;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + (r.description || '—') + '</span>';
                    html += '<span style="background:#e0f2fe;color:#0369a1;font-size:11px;font-weight:700;padding:2px 8px;border-radius:5px;flex-shrink:0;">' + (r.category || '—') + '</span>';
                    html += '</div>';
                }
                if (valid.length > 3) {
                    html += '<div style="padding:9px 14px;font-size:12px;color:#94a3b8;text-align:center;">… and ' + (valid.length - 3) + ' more row(s)</div>';
                }
                html += '</div>';
                html += '</div>';
            }

            // Warning note
            html += '<div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 14px;display:flex;gap:10px;align-items:flex-start;">';
            html += '<i class="bi bi-info-circle-fill" style="color:#f59e0b;flex-shrink:0;margin-top:1px;"></i>';
            html += '<span style="font-size:13px;color:#78350f;">Rows with errors will be skipped automatically. This action cannot be undone easily — confirm to proceed.</span>';
            html += '</div>';

            $('#importConfirmBody').html(html);
            $('#confirmImportBtn').show().html('<i class="bi bi-check2-circle"></i> Import ' + valid.length + ' Product(s)');
            $('#importConfirmModal').modal('show');
        });

        // ── Confirmed → submit form ────────────────────────────────────
        $('#confirmImportBtn').on('click', function () {
            var valid   = parsedRows.filter(function(r) { return r.errors.length === 0; });
            if (!valid.length) return;
            var jsonStr = JSON.stringify(valid);
            document.getElementById('importDataInput').value = jsonStr;
            $(this).prop('disabled', true)
                   .html('<i class="bi bi-hourglass-split"></i> Importing…');
            document.getElementById('importForm').submit();
        });














    </script>

    {{-- ── Barcode: generate button ── --}}
    <script>
    document.getElementById('generateBarcodeBtn').addEventListener('click', function () {
        // Generate EAN-13 style numeric barcode (12 digits + check digit)
        var digits = '';
        for (var i = 0; i < 12; i++) digits += Math.floor(Math.random() * 10);
        // EAN-13 check digit
        var sum = 0;
        for (var i = 0; i < 12; i++) sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
        var check = (10 - (sum % 10)) % 10;
        document.getElementById('barcode_input').value = digits + check;
    });
    </script>
@endsection
