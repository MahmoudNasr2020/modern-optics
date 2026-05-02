@extends('dashboard.layouts.master')
@section('title', 'Add Lens Brand')
@section('content')
    <style>
        .page-card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 20px rgba(0,0,0,.08); margin-bottom:24px; }
        .sec-header {
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:#fff; padding:14px 22px; border-radius:10px;
            margin:-28px -28px 24px -28px;
            font-weight:700; font-size:17px;
            display:flex; align-items:center; justify-content:space-between; gap:10px;
        }
        .sec-header .sec-header-left { display:flex; align-items:center; gap:10px; }
        .sec-header .btn-back {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(255,255,255,.2); border:2px solid rgba(255,255,255,.4);
            color:#fff; padding:7px 16px; border-radius:8px;
            font-weight:600; font-size:13px; text-decoration:none; transition:all .3s;
        }
        .sec-header .btn-back:hover { background:#fff; color:#764ba2; }
        .form-group > label:not(.check-wrap) { font-weight:600; color:#444; font-size:13.5px; margin-bottom:6px; display:block; }
        .form-control { border:2px solid #e0e6ed; border-radius:8px !important; font-size:14px; transition:border-color .2s; }
        .form-control:focus { border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.1); }
        .name-check { font-size:12px; margin-top:4px; min-height:18px; }
        .name-check.ok  { color:#27ae60; }
        .name-check.err { color:#e74c3c; }
        .btn-save { display:inline-flex; align-items:center; gap:8px; padding:11px 30px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; border:none; border-radius:10px; font-weight:700; font-size:15px; cursor:pointer; box-shadow:0 4px 14px rgba(102,126,234,.35); transition:all .25s; }
        .btn-save:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,.5); }
        .btn-cancel-link { padding:11px 24px; border-radius:10px; border:2px solid #dde1ea; background:#fff; color:#666; font-weight:600; font-size:14px; text-decoration:none; margin-right:10px; display:inline-block; }

        /* ── Logo Upload Zone ── */
        .logo-upload-wrap {
            display:flex; align-items:center; gap:20px;
            background:linear-gradient(135deg,#f8f7ff,#f0ebff);
            border:2px dashed #c4b5fd; border-radius:14px;
            padding:18px 22px; transition:all .3s; cursor:pointer;
        }
        .logo-upload-wrap:hover { border-color:#667eea; background:linear-gradient(135deg,#f0ebff,#e8eaf6); }
        .logo-upload-wrap.dragover { border-color:#667eea; background:#ede9fe; transform:scale(1.01); }
        .logo-img-box {
            width:90px; height:90px; border-radius:14px;
            border:3px solid #fff; box-shadow:0 4px 16px rgba(102,126,234,.25);
            overflow:hidden; background:#fff;
            display:flex; align-items:center; justify-content:center;
            flex-shrink:0; font-size:36px;
        }
        .logo-img-box img { width:100%; height:100%; object-fit:contain; }
        .logo-upload-info .logo-title { font-weight:700; color:#444; font-size:14px; margin-bottom:4px; }
        .logo-upload-info .logo-sub { font-size:12px; color:#999; margin-bottom:10px; }
        .btn-pick-logo {
            display:inline-flex; align-items:center; gap:6px;
            padding:7px 16px; border-radius:8px;
            background:linear-gradient(135deg,#667eea,#764ba2);
            color:#fff; font-size:12px; font-weight:700;
            border:none; cursor:pointer;
            box-shadow:0 3px 10px rgba(102,126,234,.3); transition:all .25s;
        }
        .btn-pick-logo:hover { transform:translateY(-1px); box-shadow:0 5px 14px rgba(102,126,234,.45); }
        .btn-remove-logo {
            display:none; align-items:center; gap:6px;
            padding:7px 14px; border-radius:8px;
            background:#fee2e2; color:#e74c3c;
            font-size:12px; font-weight:700;
            border:none; cursor:pointer; margin-left:8px; transition:all .25s;
        }
        .btn-remove-logo.visible { display:inline-flex; }
        .btn-remove-logo:hover { background:#fecaca; }

        /* ── Styled Checkbox (iCheck-safe) ── */
        .status-ui {
            display:inline-flex; align-items:center; gap:10px; cursor:pointer;
        }
        .status-box {
            width:22px; height:22px; border-radius:6px;
            border:2px solid #c4b5fd; background:#fff;
            display:inline-flex; align-items:center; justify-content:center;
            transition:all .2s; flex-shrink:0;
        }
        .status-box svg { display:none; }
        .status-box.on {
            background:linear-gradient(135deg,#667eea,#764ba2);
            border-color:transparent;
            box-shadow:0 3px 10px rgba(102,126,234,.4);
        }
        .status-box.on svg { display:block; }
        .check-text { font-size:14px; font-weight:600; color:#555; }
        .check-badge { font-size:11px; font-weight:700; padding:2px 9px; border-radius:20px; transition:all .2s; }
        .check-badge.on  { background:#dcfce7; color:#16a34a; }
        .check-badge.off { background:#fee2e2; color:#dc2626; }
        .icheckbox_minimal{display: none}
    </style>

    <section class="content-header">
        <h1><i class="bi bi-plus-circle"></i> Add Lens Brand</h1>
    </section>

    <div style="padding:20px;">
        @include('dashboard.partials._errors')

        <form action="{{ route('dashboard.lens-brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="page-card">

                <div class="sec-header">
                    <div class="sec-header-left">
                        <i class="bi bi-building"></i> Brand Information
                    </div>
                    <a href="{{ route('dashboard.lens-brands.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Back to Brands
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="brandName" class="form-control"
                                   value="{{ old('name') }}" placeholder="e.g. Essilor, Hoya, Zeiss..." required>
                            <div class="name-check" id="nameCheck"></div>
                            @error('name')<span class="text-danger" style="font-size:12px;">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-bottom:0;">
                            <label style="display:block;margin-bottom:10px;">Status</label>
                            {{-- Hidden real checkbox (iCheck won't style it because we target by id after destroy) --}}
                            <input type="checkbox" name="is_active" id="isActive" value="1" checked style="display:none!important;">
                            {{-- Custom visual --}}
                            <div class="status-ui" id="statusUI">
                                <div class="status-box on" id="statusBox">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                                        <path d="M2 6.5L5.5 10L11 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="check-text">Active Brand</span>
                                <span class="check-badge on" id="activeBadge">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description <small class="text-muted">(optional)</small></label>
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Short description about this brand...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group" style="margin-top:8px;">
                    <label>Logo <small class="text-muted">(optional)</small></label>
                    <input type="file" name="logo" id="logoInput" accept="image/*" style="display:none;">
                    <div class="logo-upload-wrap" id="uploadZone">
                        <div class="logo-img-box" id="logoBox">
                            <span id="logoPlaceholder">🏷️</span>
                        </div>
                        <div class="logo-upload-info">
                            <div class="logo-title">Brand Logo</div>
                            <div class="logo-sub">PNG, JPG, SVG up to 2MB — drag & drop or click to browse</div>
                            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <button type="button" class="btn-pick-logo" id="pickLogoBtn">
                                    <i class="bi bi-upload"></i> Upload Logo
                                </button>
                                <button type="button" class="btn-remove-logo" id="removeLogoBtn">
                                    <i class="bi bi-x-circle"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-right mb-2">
                <a href="{{ route('dashboard.lens-brands.index') }}" class="btn-cancel-link">Cancel</a>
                <button type="submit" class="btn-save" id="saveBtn" disabled>
                    <i class="bi bi-check-circle-fill"></i> Create Brand
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            /* ── Name check ── */
            var timer;
            $('#brandName').on('input', function () {
                var name = $(this).val().trim();
                clearTimeout(timer);
                if (!name) { $('#nameCheck').text('').removeClass('ok err'); $('#saveBtn').prop('disabled', true); return; }
                timer = setTimeout(function () {
                    $.ajax({
                        url: '{{ route("dashboard.lens-brands.check-name") }}',
                        data: { name: name, _token: '{{ csrf_token() }}' },
                        method: 'POST',
                        success: function (res) {
                            if (res.exists) {
                                $('#nameCheck').text('⚠ This brand name already exists.').removeClass('ok').addClass('err');
                                $('#saveBtn').prop('disabled', true);
                            } else {
                                $('#nameCheck').text('✓ Brand name is available.').removeClass('err').addClass('ok');
                                $('#saveBtn').prop('disabled', false);
                            }
                        }
                    });
                }, 400);
            });

            /* ── Checkbox badge ── */
            var isChecked = true;
            $('#isActive').prop('checked', true);

            $('#statusUI').on('click', function () {
                isChecked = !isChecked;
                $('#isActive').prop('checked', isChecked);
                if (isChecked) {
                    $('#statusBox').addClass('on');
                    $('#activeBadge').text('Active').removeClass('off').addClass('on');
                } else {
                    $('#statusBox').removeClass('on');
                    $('#activeBadge').text('Inactive').removeClass('on').addClass('off');
                }
            });

            /* ── Logo ── */
            $('#pickLogoBtn').on('click', function () { $('#logoInput').click(); });
            $('#logoInput').on('change', function () {
                var file = this.files[0]; if (!file) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#logoPlaceholder').hide();
                    if ($('#logoPreview').length) { $('#logoPreview').attr('src', e.target.result); }
                    else { $('#logoBox').append('<img id="logoPreview" src="' + e.target.result + '" style="width:100%;height:100%;object-fit:contain;">'); }
                    $('#removeLogoBtn').addClass('visible');
                };
                reader.readAsDataURL(file);
            });
            $('#removeLogoBtn').on('click', function () {
                $('#logoInput').val(''); $('#logoPreview').remove();
                $('#logoPlaceholder').show(); $(this).removeClass('visible');
            });

            /* ── Drag & drop ── */
            var zone = document.getElementById('uploadZone');
            zone.addEventListener('dragover', function (e) { e.preventDefault(); $(this).addClass('dragover'); });
            zone.addEventListener('dragleave', function () { $(this).removeClass('dragover'); });
            zone.addEventListener('drop', function (e) {
                e.preventDefault(); $(this).removeClass('dragover');
                var file = e.dataTransfer.files[0];
                if (!file || !file.type.startsWith('image/')) return;
                var dt = new DataTransfer(); dt.items.add(file);
                document.getElementById('logoInput').files = dt.files;
                $('#logoInput').trigger('change');
            });
        });
    </script>
@endsection
