@extends('dashboard.layouts.master')
@section('title', 'Brand: ' . $lensBrand->name)
@section('content')
    <style>
        .page-card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 20px rgba(0,0,0,.08); margin-bottom:24px; }
        .sec-header { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; padding:14px 22px; border-radius:10px; margin:-28px -28px 24px -28px; font-weight:700; font-size:17px; display:flex; align-items:center; gap:10px; }
        .sec-header.green { background:linear-gradient(135deg,#27ae60,#2ecc71); }
        .stat-row { display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap; }
        .stat-box { flex:1; min-width:120px; background:linear-gradient(135deg,rgba(102,126,234,.06),rgba(118,75,162,.06)); border:1.5px solid rgba(102,126,234,.15); border-radius:12px; padding:16px; text-align:center; }
        .stat-box .val { font-size:24px; font-weight:700; color:#667eea; }
        .stat-box .lbl { font-size:11px; color:#999; text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }
        .lens-table { width:100%; border-collapse:collapse; font-size:13px; }
        .lens-table th { background:#f4f6fb; padding:10px 12px; font-weight:700; font-size:11px; color:#555; text-transform:uppercase; border-bottom:2px solid #e8eaf6; white-space:nowrap; }
        .lens-table td { padding:10px 12px; border-bottom:1px solid #f0f2f8; vertical-align:middle; }
        .lens-table tr:hover td { background:#fafbff; }
        /* Import zone */
        .import-zone { border:3px dashed #c5cae9; border-radius:12px; padding:32px; text-align:center; cursor:pointer; background:#fafbff; position:relative; transition:all .3s; }
        .import-zone:hover,.import-zone.drag-over { border-color:#667eea; background:#f0ebff; }
        .import-zone input { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
        .btn-template { display:inline-flex;align-items:center;gap:6px;padding:8px 18px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;border:none;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;transition:all .25s; }
        .btn-template:hover { transform:translateY(-2px);color:#fff;text-decoration:none; }
        .preview-table { width:100%;border-collapse:collapse;font-size:12.5px;margin-top:14px; }
        .preview-table th { background:#f4f6fb;padding:8px 10px;font-weight:700;font-size:11px;text-transform:uppercase;border-bottom:2px solid #e0e6ed; }
        .preview-table td { padding:8px 10px;border-bottom:1px solid #f0f2f8; }
        .badge-ok  { background:#e8f8f5;color:#27ae60;padding:2px 8px;border-radius:10px;font-size:11px;font-weight:700; }
        .badge-err { background:#fee;color:#e74c3c;padding:2px 8px;border-radius:10px;font-size:11px; }
        #importPreview { display:none; }
    </style>

    <section class="content-header">
        <h1>
            <i class="bi bi-building"></i> {{ $lensBrand->name }}
            <small>lens brand</small>
        </h1>
    </section>

    <div style="padding:20px;">
        @include('dashboard.partials._errors')

        {{-- ─── Import Result Banner ─── --}}
        @if(session()->has('import_added') || session()->has('import_skipped') || session()->has('import_errors'))
        @php
            $impAdded   = session('import_added', 0);
            $impSkipped = session('import_skipped', []);
            $impErrors  = session('import_errors',  []);
            $hasSuccess = $impAdded > 0;
            $hasSkipped = is_array($impSkipped) && count($impSkipped) > 0;
            $hasErrors  = is_array($impErrors)  && count($impErrors)  > 0;
        @endphp
        <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.10);margin-bottom:24px;">

            {{-- Header --}}
            @if($hasSuccess)
            <div style="background:linear-gradient(135deg,#16a34a,#22c55e);padding:18px 24px;display:flex;align-items:center;gap:14px;">
                <div style="width:46px;height:46px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;flex-shrink:0;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div style="font-size:17px;font-weight:800;color:#fff;">Import Successful!</div>
                    <div style="font-size:13px;color:rgba(255,255,255,.8);">
                        <strong>{{ $impAdded }}</strong> lens{{ $impAdded != 1 ? 'es' : '' }} added to <strong>{{ $lensBrand->name }}</strong>
                        @if($hasSkipped) &nbsp;·&nbsp; {{ count($impSkipped) }} skipped @endif
                        @if($hasErrors)  &nbsp;·&nbsp; <span style="color:#fde68a;">{{ count($impErrors) }} error(s)</span> @endif
                    </div>
                </div>
                <button onclick="this.closest('.import-result-wrap, div').parentElement.style.display='none'"
                        style="margin-left:auto;background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:8px;font-size:18px;cursor:pointer;line-height:1;display:flex;align-items:center;justify-content:center;"
                        title="Dismiss">&times;</button>
            </div>
            @else
            <div style="background:linear-gradient(135deg,#f59e0b,#d97706);padding:18px 24px;display:flex;align-items:center;gap:14px;">
                <div style="width:46px;height:46px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div style="font-size:17px;font-weight:800;color:#fff;">No Lenses Were Imported</div>
                    <div style="font-size:13px;color:rgba(255,255,255,.85);">Check the details below for what went wrong</div>
                </div>
            </div>
            @endif

            {{-- Details --}}
            <div style="padding:20px 24px;">

                {{-- Errors --}}
                @if($hasErrors)
                <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:12px;padding:14px 18px;margin-bottom:14px;">
                    <div style="font-size:13px;font-weight:700;color:#dc2626;margin-bottom:10px;">
                        <i class="bi bi-x-circle-fill"></i> {{ count($impErrors) }} Row Error(s)
                    </div>
                    @foreach($impErrors as $err)
                    <div style="display:flex;align-items:flex-start;gap:8px;padding:5px 0;border-bottom:1px solid #fee2e2;font-size:13px;color:#991b1b;">
                        <i class="bi bi-dot" style="color:#ef4444;font-size:18px;flex-shrink:0;margin-top:-1px;"></i>
                        {{ is_string($err) ? $err : json_encode($err) }}
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Skipped --}}
                @if($hasSkipped)
                <div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:12px;padding:14px 18px;">
                    <div style="font-size:13px;font-weight:700;color:#d97706;margin-bottom:10px;">
                        <i class="bi bi-skip-forward-circle-fill"></i> {{ count($impSkipped) }} Row(s) Skipped
                    </div>
                    @foreach($impSkipped as $sk)
                    <div style="display:flex;align-items:flex-start;gap:8px;padding:5px 0;border-bottom:1px solid #fde68a;font-size:13px;color:#92400e;">
                        <i class="bi bi-dash-circle" style="color:#f59e0b;flex-shrink:0;margin-top:2px;"></i>
                        {{ is_string($sk) ? $sk : json_encode($sk) }}
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- All good, no skipped/errors --}}
                @if(!$hasErrors && !$hasSkipped)
                <div style="text-align:center;padding:10px 0 4px;color:#64748b;font-size:13px;">
                    <i class="bi bi-stars" style="color:#22c55e;font-size:20px;vertical-align:middle;margin-right:6px;"></i>
                    All rows imported successfully with no errors or skips!
                </div>
                @endif

            </div>
        </div>
        @endif

        {{-- Brand info card --}}
        <div class="page-card">
            <div class="sec-header">
                <i class="bi bi-building"></i> {{ $lensBrand->name }}
                <div style="margin-left:auto;display:flex;gap:8px;">
                    <a href="{{ route('dashboard.lens-brands.edit', $lensBrand) }}" style="background:rgba(255,255,255,.2);color:#fff;padding:6px 14px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('dashboard.lens-brands.index') }}" style="background:rgba(255,255,255,.15);color:#fff;padding:6px 14px;border-radius:8px;text-decoration:none;font-size:13px;">
                        <i class="bi bi-arrow-right"></i> All Brands
                    </a>
                </div>
            </div>

            <div class="stat-row">
                <div class="stat-box"><div class="val">{{ $lensBrand->lenses()->count() }}</div><div class="lbl">Total Lenses</div></div>
                <div class="stat-box"><div class="val">{{ number_format($lensBrand->lenses()->sum('retail_price') / max($lensBrand->lenses()->count(),1), 0) }}</div><div class="lbl">Avg Price</div></div>
                <div class="stat-box"><div class="val">{{ $lensBrand->is_active ? '✓' : '✗' }}</div><div class="lbl">Status</div></div>
            </div>

            @if($lensBrand->description)
                <p style="color:#666;font-size:14px;">{{ $lensBrand->description }}</p>
            @endif
        </div>

        {{-- Lenses table --}}
        <div class="page-card">
            <div class="sec-header"><i class="bi bi-eye"></i> Lenses under this Brand</div>

            @if($lenses->isEmpty())
                <div style="text-align:center;padding:40px;color:#bbb;">
                    <i class="bi bi-eye" style="font-size:36px;display:block;margin-bottom:10px;"></i>
                    No lenses yet — import some below!
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="lens-table">
                        <thead>
                        <tr>
                            <th>ID</th><th>Description</th><th>Index</th><th>Frame</th>
                            <th>Type</th><th>Production</th><th>Retail Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lenses as $lens)
                            <tr>
                                <td style="font-weight:700;color:#667eea;">{{ $lens->product_id }}</td>
                                <td style="font-weight:600;">{{ $lens->description }}</td>
                                <td>{{ $lens->index }}</td>
                                <td>{{ $lens->frame_type }}</td>
                                <td>{{ $lens->lense_type }}</td>
                                <td><span style="background:#f0ebff;color:#764ba2;padding:2px 8px;border-radius:10px;font-size:11px;font-weight:700;">{{ $lens->lense_production }}</span></td>
                                <td style="font-weight:700;color:#27ae60;">{{ number_format($lens->retail_price,2) }} QAR</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $lenses->links() }}</div>
            @endif
        </div>

        {{-- Excel Import --}}
        <div class="page-card">
            <div class="sec-header green">
                <i class="bi bi-file-earmark-excel"></i> Import Lenses from Excel
                <a href="{{ route('dashboard.lens-brands.template') }}"
                   style="margin-left:auto;display:inline-flex;align-items:center;gap:7px;
                          background:#fff;color:#27ae60;padding:7px 18px;border-radius:8px;
                          font-size:13px;font-weight:700;text-decoration:none;
                          box-shadow:0 2px 8px rgba(0,0,0,.15);transition:box-shadow .2s,transform .2s;"
                   onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 14px rgba(0,0,0,.2)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,.15)'">
                    <i class="bi bi-download"></i> Download Template
                </a>
            </div>

            <p style="color:#666;font-size:13px;margin-bottom:18px;">
                <i class="bi bi-info-circle" style="color:#667eea;"></i>
                Download the template above, fill in lenses for <strong>{{ $lensBrand->name }}</strong>, then drop the file below.
                The brand is assigned automatically — no need to fill it.
            </p>

            <div class="import-zone" id="importZone">
                <input type="file" id="excelFile" accept=".xlsx,.xls,.csv">
                <i class="bi bi-cloud-upload" style="font-size:40px;color:#c5cae9;display:block;margin-bottom:10px;"></i>
                <div style="font-size:15px;font-weight:600;color:#667eea;">Drop Excel file here</div>
                <div style="font-size:12px;color:#aaa;margin-top:4px;">.xlsx / .xls / .csv — or click to browse</div>
            </div>

            <div id="importPreview">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                    <span id="previewTitle" style="font-size:13px;font-weight:600;"></span>
                    <div style="display:flex;gap:8px;">
                        <button type="button" class="btn btn-sm btn-default" id="clearImport"><i class="bi bi-x-circle"></i> Clear</button>
                        <button type="button" class="btn btn-sm btn-success" id="submitImport"><i class="bi bi-check-circle"></i> Import</button>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="preview-table">
                        <thead><tr>
                            <th>#</th><th>Product ID</th><th>Description</th><th>Index</th>
                            <th>Frame</th><th>Type</th><th>Production</th><th>Retail Price</th><th>Status</th>
                        </tr></thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <form action="{{ route('dashboard.lens-brands.import', $lensBrand) }}" method="POST" id="importForm">
            @csrf
            <input type="hidden" name="import_data" id="importDataInput">
        </form>
    </div>

    {{-- ─── Import Confirm Modal ─── --}}
    <div class="modal fade" id="importConfirmModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:500px;">
            <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);">

                {{-- Header --}}
                <div id="importModalHeader" style="background:linear-gradient(135deg,#27ae60,#2ecc71);padding:22px 28px;display:flex;align-items:center;gap:14px;">
                    <div style="width:46px;height:46px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;flex-shrink:0;">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:800;color:#fff;" id="importModalTitle">Confirm Import</div>
                        <div style="font-size:12px;color:rgba(255,255,255,.75);" id="importModalSub">Review before uploading</div>
                    </div>
                    <button type="button" data-dismiss="modal"
                            style="margin-left:auto;background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:8px;font-size:18px;cursor:pointer;line-height:1;display:flex;align-items:center;justify-content:center;">
                        &times;
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body" id="importModalBody" style="padding:24px 28px;"></div>

                {{-- Footer --}}
                <div class="modal-footer" style="padding:16px 28px;border-top:1.5px solid #f1f5f9;display:flex;justify-content:flex-end;gap:10px;">
                    <button type="button" data-dismiss="modal"
                            style="padding:10px 22px;border-radius:10px;border:2px solid #e2e8f0;background:#fff;color:#64748b;font-weight:600;font-size:14px;cursor:pointer;transition:all .2s;"
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="button" id="doImportBtn"
                            style="padding:10px 26px;border-radius:10px;border:none;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;font-weight:700;font-size:14px;cursor:pointer;box-shadow:0 4px 14px rgba(39,174,96,.35);transition:all .2s;display:none;"
                            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                        <i class="bi bi-check2-circle"></i> <span id="doImportBtnText">Import</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        var parsedRows = [];
        var zone = document.getElementById('importZone');

        zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave',function(){ zone.classList.remove('drag-over'); });
        zone.addEventListener('drop', function(e){ e.preventDefault(); zone.classList.remove('drag-over'); if(e.dataTransfer.files[0]) processFile(e.dataTransfer.files[0]); });
        document.getElementById('excelFile').addEventListener('change', function(){ if(this.files[0]) processFile(this.files[0]); });

        function processFile(file){
            var reader = new FileReader();
            reader.onload = function(e){
                var wb  = XLSX.read(new Uint8Array(e.target.result), {type:'array'});
                var sheetName = wb.SheetNames.indexOf('Lenses') >= 0 ? 'Lenses' : wb.SheetNames[0];
                var raw = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], {header:1, defval:''});
                if(!raw.length){ alert('File is empty!'); return; }

                // Find header row
                var hIdx = -1; var headers = [];
                for(var r=0; r<Math.min(5,raw.length); r++){
                    var norm = raw[r].map(function(v){ return String(v).toLowerCase().replace(/[^a-z_]/g,''); });
                    if(norm.indexOf('description') >= 0){ hIdx=r; headers=norm; break; }
                }
                if(hIdx===-1){ alert('Cannot find headers! Make sure "description" column exists.'); return; }

                function ci(names){ for(var n=0;n<names.length;n++){ var i=headers.indexOf(names[n]); if(i>=0)return i; } return -1; }

                var CI = {
                    pid  : ci(['productid','product_id']),
                    desc : ci(['description']),
                    idx  : ci(['index']),
                    frame: ci(['frametype','frame_type']),
                    type : ci(['lensetype','lense_type','lenstype']),
                    prod : ci(['lenseproduction','lense_production','production']),
                    tech : ci(['lensetech','lense_tech','tech']),
                    life : ci(['lifestyle','life_style']),
                    act  : ci(['customeractivity','customer_activity']),
                    price: ci(['price']),
                    rprice:ci(['retailprice','retail_price']),
                    amt  : ci(['amount']),
                };

                parsedRows = [];
                var errCount = 0;
                for(var i=hIdx+1; i<raw.length; i++){
                    var row=raw[i];
                    if(!row||row.every(function(v){return String(v).trim()=='';})) continue;
                    var rowErrors=[];
                    var desc = CI.desc>=0 ? String(row[CI.desc]||'').trim() : '';
                    if(!desc) rowErrors.push('missing description');
                    if(rowErrors.length) errCount++;
                    parsedRows.push({
                        product_id: CI.pid>=0 ? String(row[CI.pid]||'').trim() : '',
                        description: desc,
                        index: CI.idx>=0 ? String(row[CI.idx]||'') : '',
                        frame_type: CI.frame>=0 ? String(row[CI.frame]||'') : '',
                        lense_type: CI.type>=0 ? String(row[CI.type]||'') : '',
                        lense_production: CI.prod>=0 ? String(row[CI.prod]||'') : 'Stock',
                        lense_tech: CI.tech>=0 ? String(row[CI.tech]||'') : 'Basic',
                        life_style: CI.life>=0 ? String(row[CI.life]||'') : '',
                        customer_activity: CI.act>=0 ? String(row[CI.act]||'') : '',
                        price: CI.price>=0 ? (parseFloat(row[CI.price])||0) : 0,
                        retail_price: CI.rprice>=0 ? (parseFloat(row[CI.rprice])||0) : 0,
                        amount: CI.amt>=0 ? (parseInt(row[CI.amt])||0) : 0,
                        errors: rowErrors,
                    });
                }
                renderPreview(errCount);
            };
            reader.readAsArrayBuffer(file);
        }

        function renderPreview(errCount){
            var tbody=document.getElementById('previewBody');
            tbody.innerHTML='';
            var valid=0;
            parsedRows.forEach(function(r,i){
                var hasErr=r.errors.length>0;
                if(!hasErr) valid++;
                var status=hasErr?'<span class="badge-err">✗ '+r.errors.join(', ')+'</span>':'<span class="badge-ok">✓ OK</span>';
                var tr=document.createElement('tr');
                if(hasErr) tr.style.background='#fff8f8';
                tr.innerHTML='<td style="color:#999;">'+(i+1)+'</td>'+
                    '<td><code style="font-size:11px;background:#f4f6fb;padding:2px 5px;border-radius:4px;">'+(r.product_id||'auto')+'</code></td>'+
                    '<td style="font-weight:600;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'+r.description+'</td>'+
                    '<td>'+r.index+'</td><td>'+r.frame_type+'</td><td>'+r.lense_type+'</td>'+
                    '<td><span style="background:#f0ebff;color:#764ba2;padding:1px 7px;border-radius:8px;font-size:11px;">'+r.lense_production+'</span></td>'+
                    '<td style="font-weight:700;color:#27ae60;">'+r.retail_price+'</td>'+
                    '<td>'+status+'</td>';
                tbody.appendChild(tr);
            });
            var title='<strong>'+parsedRows.length+'</strong> rows';
            if(errCount) title+=' · <span style="color:#e74c3c;">'+errCount+' errors</span>';
            title+=' · <span style="color:#27ae60;font-weight:600;">'+valid+' ready</span>';
            document.getElementById('previewTitle').innerHTML=title;
            document.getElementById('importPreview').style.display='block';
        }

        document.getElementById('clearImport').addEventListener('click',function(){
            parsedRows=[];
            document.getElementById('previewBody').innerHTML='';
            document.getElementById('importPreview').style.display='none';
            document.getElementById('excelFile').value='';
        });

        document.getElementById('submitImport').addEventListener('click', function() {
            var valid   = parsedRows.filter(function(r){ return r.errors.length === 0; });
            var invalid = parsedRows.filter(function(r){ return r.errors.length  >  0; });

            var header  = document.getElementById('importModalHeader');
            var title   = document.getElementById('importModalTitle');
            var sub     = document.getElementById('importModalSub');
            var body    = document.getElementById('importModalBody');
            var btn     = document.getElementById('doImportBtn');
            var btnTxt  = document.getElementById('doImportBtnText');

            if (!valid.length) {
                // ── No valid rows — error state ────────────────────────
                header.style.background = 'linear-gradient(135deg,#dc2626,#ef4444)';
                header.querySelector('i').className = 'bi bi-exclamation-triangle';
                title.textContent = 'Cannot Import';
                sub.textContent   = 'No valid rows found in the file';
                body.innerHTML =
                    '<div style="text-align:center;padding:20px 0;">' +
                        '<i class="bi bi-x-circle-fill" style="font-size:52px;color:#ef4444;"></i>' +
                        '<p style="margin:16px 0 0;font-size:14px;color:#64748b;">' +
                            'All <strong>' + parsedRows.length + '</strong> row(s) have errors. ' +
                            'Please fix the highlighted issues in the preview table and try again.' +
                        '</p>' +
                    '</div>';
                btn.style.display = 'none';
                $('#importConfirmModal').modal('show');
                return;
            }

            // ── Normal confirm state ───────────────────────────────────
            header.style.background = 'linear-gradient(135deg,#27ae60,#2ecc71)';
            header.querySelector('i').className = 'bi bi-file-earmark-check';
            title.textContent = 'Confirm Import';
            sub.textContent   = 'Review your data before uploading';

            // Stat cards
            var html = '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">';
            // Valid card
            html += '<div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;border-radius:12px;padding:16px;text-align:center;">';
            html += '<div style="font-size:32px;font-weight:900;color:#16a34a;">' + valid.length + '</div>';
            html += '<div style="font-size:11px;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:.5px;">Ready to Import</div>';
            html += '</div>';
            // Invalid card
            var invBg = invalid.length ? 'linear-gradient(135deg,#fef2f2,#fee2e2)' : 'linear-gradient(135deg,#f8fafc,#f1f5f9)';
            var invBd = invalid.length ? '1.5px solid #fca5a5' : '1.5px solid #e2e8f0';
            var invC  = invalid.length ? '#dc2626' : '#94a3b8';
            html += '<div style="background:' + invBg + ';border:' + invBd + ';border-radius:12px;padding:16px;text-align:center;">';
            html += '<div style="font-size:32px;font-weight:900;color:' + invC + ';">' + invalid.length + '</div>';
            html += '<div style="font-size:11px;font-weight:700;color:' + invC + ';text-transform:uppercase;letter-spacing:.5px;">Will be Skipped</div>';
            html += '</div>';
            html += '</div>';

            // Preview first 3 valid rows
            html += '<div style="font-size:12px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Preview</div>';
            var preview = valid.slice(0, 3);
            for (var pi = 0; pi < preview.length; pi++) {
                var r = preview[pi];
                html += '<div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:10px 14px;margin-bottom:8px;display:flex;align-items:center;gap:10px;">';
                html += '<div style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:8px;padding:3px 10px;font-size:11px;font-weight:700;flex-shrink:0;">' + (r.product_id || 'auto') + '</div>';
                html += '<div style="font-size:13px;font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + r.description + '</div>';
                if (r.lense_type) html += '<div style="margin-left:auto;font-size:11px;color:#764ba2;background:#f0ebff;padding:2px 8px;border-radius:8px;flex-shrink:0;">' + r.lense_type + '</div>';
                html += '</div>';
            }
            if (valid.length > 3) {
                html += '<div style="text-align:center;font-size:12px;color:#94a3b8;padding:4px 0 12px;">… and <strong>' + (valid.length - 3) + '</strong> more row(s)</div>';
            }

            // Warning note
            html += '<div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 14px;font-size:12px;color:#92400e;margin-top:4px;">';
            html += '<i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;margin-right:6px;"></i>';
            html += '<strong>Note:</strong> Duplicate product IDs will be skipped automatically.';
            html += '</div>';

            body.innerHTML = html;
            btn.style.display = '';
            btn.disabled = false;
            btnTxt.textContent = 'Import ' + valid.length + ' Lens' + (valid.length !== 1 ? 'es' : '');
            $('#importConfirmModal').modal('show');
        });

        // ── Actual form submit ─────────────────────────────────────────────
        document.getElementById('doImportBtn').addEventListener('click', function() {
            var valid = parsedRows.filter(function(r){ return r.errors.length === 0; });
            if (!valid.length) return;
            this.disabled = true;
            document.getElementById('doImportBtnText').textContent = 'Importing…';
            this.querySelector('i').className = 'bi bi-hourglass-split';
            document.getElementById('importDataInput').value = JSON.stringify(valid);
            document.getElementById('importForm').submit();
        });
    </script>
@endsection
