@extends('dashboard.layouts.master')
@section('title', 'Bulk Transfer Request — Excel Upload')
@section('content')

<style>
.bulk-page { padding: 20px; }

/* ── Cards ── */
.bulk-card { background:#fff; border-radius:12px; padding:24px; margin-bottom:18px; border:2px solid #e8ecf7; box-shadow:0 2px 10px rgba(0,0,0,.05); }
.bulk-card-title { font-size:14px; font-weight:700; color:#2c3e50; margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid #e8ecf7; display:flex; align-items:center; gap:8px; }

/* ── Upload zone ── */
.upload-zone { border:3px dashed #3498db; border-radius:12px; padding:36px 20px; text-align:center; cursor:pointer; background:#f8fbff; transition:all .3s; }
.upload-zone:hover, .upload-zone.drag { background:#e3f2fd; border-color:#1565c0; }
.upload-zone i  { font-size:48px; color:#3498db; display:block; margin-bottom:10px; }
.upload-zone h3 { color:#2c3e50; font-size:16px; margin:0 0 5px; }
.upload-zone p  { color:#888; font-size:12px; margin:0; }

/* ── Store tag ── */
.store-tag { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:700; }

/* ── Preview table ── */
.prev-wrap { display:none; }
.prev-wrap.open { display:block; }
.prev-tbl { width:100%; border-collapse:collapse; font-size:13px; }
.prev-tbl thead th { background:#37474f; color:#fff; padding:10px 12px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
.prev-tbl thead th:first-child { border-radius:6px 0 0 0; }
.prev-tbl thead th:last-child  { border-radius:0 6px 0 0; }
.prev-tbl tbody tr { border-bottom:1px solid #f0f2f5; transition:background .1s; }
.prev-tbl tbody tr:hover { background:#f8f9ff; }
.prev-tbl tbody tr.row-ok   { background:#f1fdf4; }
.prev-tbl tbody tr.row-err  { background:#fff5f5; }
.prev-tbl tbody td { padding:10px 12px; vertical-align:middle; }

/* ── Status badges ── */
.badge-ok  { background:#e8f5e9; color:#2e7d32; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:700; }
.badge-err { background:#fce4ec; color:#c62828; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:700; }

/* ── Prod code ── */
.p-code { display:inline-block; background:#e8eaf6; color:#3949ab; padding:1px 8px; border-radius:10px; font-size:11px; font-weight:700; font-family:monospace; }

/* ── Preview stats bar ── */
.prev-stats { display:flex; gap:12px; align-items:center; padding:12px 14px; background:#f8f9ff; border-radius:8px; margin-bottom:14px; flex-wrap:wrap; }
.prev-stats .ps { font-size:13px; font-weight:700; }
.prev-stats .ps.ok  { color:#2e7d32; }
.prev-stats .ps.err { color:#c62828; }
.prev-stats .ps.tot { color:#3949ab; }

/* ── Col example ── */
.col-example { font-family:monospace; background:#f0f4ff; border:1px solid #c5cae9; padding:4px 10px; border-radius:6px; font-size:12px; display:inline-block; }
</style>

<section class="content-header">
    <h1>
        <i class="bi bi-file-earmark-spreadsheet" style="color:#27ae60;"></i> Bulk Transfer Request
        <small>Upload Excel to request multiple products at once</small>
        <a href="{{ route('dashboard.stock-transfers.index') }}"
           style="margin-left:10px;font-size:13px;background:#e8ecf7;color:#555;padding:6px 14px;border-radius:8px;text-decoration:none;vertical-align:middle;">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </h1>
</section>

<div class="bulk-page">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="border-radius:10px;border-left:5px solid #27ae60;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-check-circle"></i> {!! session('success') !!}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="border-radius:10px;border-left:5px solid #e74c3c;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">

        {{-- ═══ LEFT: Upload Form ═══ --}}
        <div class="col-md-7">
            <div class="bulk-card">
                <div class="bulk-card-title">
                    <i class="bi bi-cloud-upload" style="color:#3498db;font-size:18px;"></i>
                    Upload Transfer Request File
                </div>

                {{-- Route info --}}
                <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#e3f2fd;border-radius:8px;margin-bottom:18px;">
                    <div style="text-align:center;">
                        <div class="store-tag"><i class="bi bi-building"></i> {{ $store->name ?? 'Main Store' }}</div>
                        <div style="font-size:10px;color:#888;margin-top:3px;">Source</div>
                    </div>
                    <div style="font-size:20px;color:#3498db;">→</div>
                    <div style="text-align:center;">
                        <div style="background:#fff;border:2px solid #3498db;padding:3px 14px;border-radius:20px;font-size:12px;font-weight:700;color:#2c3e50;">
                            @if(auth()->user()->canSeeAllBranches())
                                <i class="bi bi-buildings"></i> Selected Branch
                            @else
                                <i class="bi bi-building"></i> {{ auth()->user()->branch->name ?? 'Your Branch' }}
                            @endif
                        </div>
                        <div style="font-size:10px;color:#888;margin-top:3px;">Destination</div>
                    </div>
                </div>

                <form action="{{ route('dashboard.stock-transfers.bulk-request.store') }}" method="POST"
                      enctype="multipart/form-data" id="bulkForm">
                    @csrf

                    @if(auth()->user()->canSeeAllBranches())
                        <div class="form-group">
                            <label style="font-weight:600;font-size:13px;"><i class="bi bi-building"></i> Destination Branch <span class="text-danger">*</span></label>
                            <select name="to_branch_id" class="form-control" required style="border:2px solid #e0e6ed;border-radius:8px;">
                                <option value="">-- Select destination branch --</option>
                                @foreach($branches as $b)
                                    @if(!$b->is_main)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="to_branch_id" value="{{ auth()->user()->branch_id }}">
                    @endif

                    <div class="form-group">
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('excelFile').click()">
                            <i class="bi bi-file-earmark-excel"></i>
                            <h3 id="fileLabel">Click to select your Excel file</h3>
                            <p>.xlsx · .xls · .csv</p>
                        </div>
                        <input type="file" name="excel_file" id="excelFile" accept=".xlsx,.xls,.csv"
                               style="display:none;" required
                               onchange="onFileSelect(this)">
                        @error('excel_file')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;font-size:13px;"><i class="bi bi-chat-text"></i> Notes (optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Any notes for this bulk request…"
                               style="border:2px solid #e0e6ed;border-radius:8px;">
                    </div>

                    <button type="submit" id="submitBtn" class="btn btn-block"
                            style="background:linear-gradient(135deg,#27ae60,#229954);color:#fff;padding:12px;font-size:15px;font-weight:700;border-radius:10px;border:none;">
                        <i class="bi bi-send-fill"></i> Submit All Transfer Requests
                    </button>
                </form>
            </div>

            {{-- Format guide --}}
            <div class="bulk-card" style="border-color:#27ae60;">
                <div class="bulk-card-title" style="color:#27ae60;">
                    <i class="bi bi-file-text" style="font-size:17px;"></i> File Format
                </div>
                <p style="font-size:13px;color:#555;margin-bottom:12px;">
                    Only <strong>2 columns</strong> required. First row = header (ignored):
                </p>
                <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:12px;">
                    <thead>
                        <tr style="background:#d4edda;">
                            <th style="padding:7px 10px;color:#155724;">Column</th>
                            <th style="padding:7px 10px;color:#155724;">Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom:1px solid #f0f4f0;">
                            <td style="padding:7px 10px;"><span class="col-example">product_id</span></td>
                            <td style="padding:7px 10px;color:#e74c3c;font-weight:700;">ADD002</td>
                        </tr>
                        <tr>
                            <td style="padding:7px 10px;"><span class="col-example">quantity</span></td>
                            <td style="padding:7px 10px;color:#e74c3c;font-weight:700;">5</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('dashboard.stock-transfers.bulk-template') }}"
                   class="btn btn-block"
                   style="background:linear-gradient(135deg,#3498db,#2980b9);color:#fff;border-radius:8px;font-weight:700;padding:9px;border:none;font-size:13px;">
                    <i class="bi bi-download"></i> Download Excel Template
                </a>
            </div>
        </div>

        {{-- ═══ RIGHT: Preview Table ═══ --}}
        <div class="col-md-5">

            {{-- Preview (shown after file selected) --}}
            <div class="bulk-card prev-wrap" id="previewCard" style="padding:20px;">
                <div class="bulk-card-title">
                    <i class="bi bi-table" style="color:#3949ab;font-size:18px;"></i>
                    File Preview
                    <button type="button" onclick="clearPreview()"
                            style="margin-left:auto;background:#fce4ec;color:#c62828;border:none;border-radius:6px;padding:3px 10px;font-size:12px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-x-lg"></i> Clear
                    </button>
                </div>

                <div class="prev-stats" id="prevStats"></div>

                <div style="overflow-x:auto;border-radius:8px;border:1px solid #e8ecf7;">
                    <table class="prev-tbl">
                        <thead>
                            <tr>
                                <th width="44" style="text-align:center;">#</th>
                                <th>Product ID</th>
                                <th width="90" style="text-align:center;">Qty</th>
                                <th width="110" style="text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="prevBody"></tbody>
                    </table>
                </div>
            </div>

            {{-- Info card (shown when no file selected) --}}
            <div class="bulk-card" id="infoCard" style="border-color:#f39c12;">
                <div class="bulk-card-title" style="color:#f39c12;">
                    <i class="bi bi-info-circle-fill" style="font-size:18px;"></i> What happens next
                </div>
                <ol style="padding-left:18px;font-size:13px;color:#555;line-height:2.2;margin:0;">
                    <li>Select your Excel file → rows appear in a <strong>preview table</strong></li>
                    <li>Valid rows show <span style="color:#2e7d32;font-weight:700;">✓ OK</span>, invalid ones show <span style="color:#c62828;font-weight:700;">✗ Error</span></li>
                    <li>Submit — each valid row becomes a <strong>Pending</strong> transfer request</li>
                    <li>All requests are grouped in one <strong>Batch</strong></li>
                    <li>Store admin approves the batch</li>
                    <li>Accept → stock enters your branch immediately</li>
                </ol>
            </div>

        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
var parsedRows = [];

/* ── File select / drag-drop ── */
function onFileSelect(input) {
    if (input.files && input.files[0]) {
        var name = input.files[0].name;
        document.getElementById('fileLabel').textContent = name;
        document.getElementById('uploadZone').style.borderColor = '#27ae60';
        document.getElementById('uploadZone').style.background  = '#f0fff4';
        parseExcel(input.files[0]);
    }
}

var zone = document.getElementById('uploadZone');
zone.addEventListener('dragover',  function(e){ e.preventDefault(); zone.classList.add('drag'); });
zone.addEventListener('dragleave', function(){  zone.classList.remove('drag'); });
zone.addEventListener('drop', function(e){
    e.preventDefault(); zone.classList.remove('drag');
    var file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('excelFile').files = e.dataTransfer.files;
        document.getElementById('fileLabel').textContent = file.name;
        document.getElementById('uploadZone').style.borderColor = '#27ae60';
        document.getElementById('uploadZone').style.background  = '#f0fff4';
        parseExcel(file);
    }
});

/* ── Parse Excel with XLSX.js ── */
function parseExcel(file) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
        var ws = wb.Sheets[wb.SheetNames[0]];
        var raw = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });

        if (!raw || raw.length < 2) {
            showError('File appears empty or has only 1 row.');
            return;
        }

        // Find header row (search first 3 rows)
        var hIdx = -1, headers = [];
        for (var r = 0; r < Math.min(3, raw.length); r++) {
            var norm = raw[r].map(function(v){ return String(v).toLowerCase().trim().replace(/\s+/g,'_'); });
            if (norm.indexOf('product_id') >= 0 || norm.indexOf('quantity') >= 0) {
                hIdx = r; headers = norm; break;
            }
        }

        if (hIdx === -1) {
            // Try first row as headers anyway
            hIdx = 0;
            headers = raw[0].map(function(v){ return String(v).toLowerCase().trim().replace(/\s+/g,'_'); });
        }

        var pidCol = findCol(headers, ['product_id','productid','prod_id','code','sku','pid']);
        var qtyCol = findCol(headers, ['quantity','qty','count','amount']);

        parsedRows = [];
        for (var i = hIdx + 1; i < raw.length; i++) {
            var row = raw[i];
            if (!row || row.every(function(v){ return String(v).trim() === ''; })) continue;

            var pid = pidCol >= 0 ? String(row[pidCol] || '').trim() : '';
            var qty = qtyCol >= 0 ? row[qtyCol] : '';
            var qtyNum = parseInt(qty, 10);

            var errors = [];
            if (!pid)            errors.push('Missing product_id');
            if (!qty && qty !== 0) errors.push('Missing quantity');
            else if (isNaN(qtyNum) || qtyNum < 1) errors.push('Quantity must be ≥ 1');

            parsedRows.push({ product_id: pid, quantity: qtyNum || 0, errors: errors });
        }

        renderPreview();
    };
    reader.readAsArrayBuffer(file);
}

function findCol(headers, names) {
    for (var n = 0; n < names.length; n++) {
        var idx = headers.indexOf(names[n]);
        if (idx >= 0) return idx;
    }
    return -1;
}

/* ── Render preview table ── */
function renderPreview() {
    var tbody = document.getElementById('prevBody');
    tbody.innerHTML = '';

    var validCount = 0, errCount = 0;

    for (var i = 0; i < parsedRows.length; i++) {
        var r = parsedRows[i];
        var hasErr = r.errors.length > 0;
        if (hasErr) errCount++; else validCount++;

        var tr = document.createElement('tr');
        tr.className = hasErr ? 'row-err' : 'row-ok';

        var status = hasErr
            ? '<span class="badge-err"><i class="bi bi-x-circle"></i> ' + r.errors.join(' · ') + '</span>'
            : '<span class="badge-ok"><i class="bi bi-check-circle-fill"></i> OK</span>';

        tr.innerHTML =
            '<td style="text-align:center;color:#aaa;font-size:12px;">' + (i+1) + '</td>' +
            '<td><span class="p-code">' + (r.product_id || '<em>—</em>') + '</span></td>' +
            '<td style="text-align:center;font-size:18px;font-weight:900;color:#3949ab;">' + (r.quantity || '—') + '</td>' +
            '<td style="text-align:center;">' + status + '</td>';
        tbody.appendChild(tr);
    }

    // Stats bar
    var statsHtml = '<span class="ps tot"><i class="bi bi-list-ul"></i> ' + parsedRows.length + ' rows</span>';
    if (validCount > 0)
        statsHtml += '<span class="ps ok"><i class="bi bi-check-circle-fill"></i> ' + validCount + ' valid</span>';
    if (errCount > 0)
        statsHtml += '<span class="ps err"><i class="bi bi-exclamation-circle-fill"></i> ' + errCount + ' errors</span>';
    document.getElementById('prevStats').innerHTML = statsHtml;

    // Show preview card; info card stays visible below it
    document.getElementById('previewCard').classList.add('open');
    document.getElementById('infoCard').style.display = 'block';

    // Disable submit if no valid rows
    document.getElementById('submitBtn').disabled = validCount === 0;
    if (validCount === 0) {
        document.getElementById('submitBtn').style.opacity = '0.5';
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-x-circle"></i> No valid rows to submit';
    } else {
        document.getElementById('submitBtn').style.opacity = '1';
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-send-fill"></i> Submit ' + validCount + ' Transfer Request(s)';
    }
}

function clearPreview() {
    parsedRows = [];
    document.getElementById('prevBody').innerHTML = '';
    document.getElementById('prevStats').innerHTML = '';
    document.getElementById('previewCard').classList.remove('open');
    document.getElementById('infoCard').style.display = 'block';
    document.getElementById('excelFile').value = '';
    document.getElementById('fileLabel').textContent = 'Click to select your Excel file';
    document.getElementById('uploadZone').style.borderColor = '#3498db';
    document.getElementById('uploadZone').style.background  = '#f8fbff';
    document.getElementById('submitBtn').disabled = false;
    document.getElementById('submitBtn').style.opacity = '1';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-send-fill"></i> Submit All Transfer Requests';
}

function showError(msg) {
    alert('Error: ' + msg);
    clearPreview();
}
</script>
@endsection
