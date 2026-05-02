@extends('dashboard.layouts.master')
@section('title', 'Items Transfer Report')

@section('content')
<style>
.itr-page { padding: 20px; background: #f8f9fa; min-height: calc(100vh - 100px); }
.itr-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    color: #fff;
    padding: 30px 35px;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 8px 30px rgba(15,23,42,.3);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.itr-header h3 { margin:0; font-size:26px; font-weight:900; display:flex; align-items:center; gap:12px; }
.itr-filter-card { background:#fff; border-radius:12px; padding:20px 24px; margin-bottom:20px;
    border:2px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.itr-filter-card label { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; }
.itr-stat { background:#fff; border-radius:10px; padding:14px 18px; border:2px solid #e2e8f0;
    text-align:center; flex:1; min-width:120px; }
.itr-stat .val { font-size:26px; font-weight:900; }
.itr-stat .lbl { font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; }

.itr-table-wrap { background:#fff; border-radius:12px; overflow:hidden; border:2px solid #e2e8f0;
    box-shadow:0 2px 8px rgba(0,0,0,.05); }
.itr-table { width:100%; border-collapse:collapse; font-size:13px; }
.itr-table thead th { background:#0f172a; color:#fff; padding:12px 14px; text-align:left;
    font-size:11px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; white-space:nowrap; }
.itr-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
.itr-table tbody tr:last-child { border-bottom:none; }
.itr-table tbody tr:hover { background:#f8fafc; }
.itr-table td { padding:11px 14px; vertical-align:middle; }

.badge-status { display:inline-block; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.badge-received  { background:#dcfce7; color:#15803d; }
.badge-shipped   { background:#dbeafe; color:#1d4ed8; }
.badge-approved  { background:#fef9c3; color:#854d0e; }
.badge-pending   { background:#fce7f3; color:#9d174d; }
.badge-cancelled { background:#f1f5f9; color:#64748b; }

.dir-out { background:#fee2e2; color:#dc2626; padding:2px 8px; border-radius:8px; font-size:11px; font-weight:800; }
.dir-in  { background:#dcfce7; color:#16a34a; padding:2px 8px; border-radius:8px; font-size:11px; font-weight:800; }

.no-results { text-align:center; padding:60px 20px; color:#94a3b8; }
.no-results i { font-size:50px; display:block; margin-bottom:12px; }
</style>

<section class="content-header">
    <h1><i class="bi bi-box-arrow-right" style="color:#3b82f6;"></i> Items Transfer Report</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('dashboard.stock-transfers.index') }}">Stock Transfers</a></li>
        <li class="active">Items Report</li>
    </ol>
</section>

<div class="itr-page">

    {{-- Header --}}
    <div class="itr-header">
        <div>
            <h3><i class="bi bi-graph-up"></i> Items Transfer Report</h3>
            <p style="margin:6px 0 0;opacity:.8;font-size:14px;">Full movement history per product — IN / OUT across all branches</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @if($selectedProduct)
            <a href="{{ route('dashboard.stock-transfers.report.items.print', request()->all()) }}"
               target="_blank"
               style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);color:#fff;
                      padding:10px 20px;border-radius:10px;font-weight:700;text-decoration:none;
                      display:inline-flex;align-items:center;gap:8px;">
                <i class="bi bi-printer-fill"></i> Print / PDF
            </a>
            @endif
            <a href="{{ route('dashboard.stock-transfers.index') }}"
               style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);color:#fff;
                      padding:10px 20px;border-radius:10px;font-weight:700;text-decoration:none;
                      display:inline-flex;align-items:center;gap:8px;">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="itr-filter-card">
        <form method="GET" action="{{ route('dashboard.stock-transfers.report.items') }}" id="itrForm">
            <input type="hidden" name="product_id" id="productIdHidden" value="{{ request('product_id') }}" required>
            <div style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
                <div style="flex:1;min-width:260px;position:relative;">
                    <label style="display:block;font-size:12px;font-weight:700;color:#64748b;
                                  text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                        <i class="bi bi-box-seam" style="color:#3b82f6;margin-right:4px;"></i>
                        Product <span style="color:#dc2626;">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="text" id="productSearch" autocomplete="off"
                               placeholder="اكتب هنا للبحث عن المنتج بالاسم أو الكود..."
                               style="width:100%;padding:8px 36px 8px 12px;border:2px solid #e2e8f0;border-radius:8px;
                                      font-size:13px;color:#0f172a;outline:none;transition:border-color .2s;
                                      box-sizing:border-box;">
                        <i class="bi bi-search" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                                        color:#94a3b8;font-size:14px;pointer-events:none;"></i>
                    </div>
                    <div id="productDropdown"
                         style="display:none;position:absolute;z-index:9999;width:100%;max-height:260px;overflow-y:auto;
                                background:#fff;border:2px solid #3b82f6;border-radius:8px;margin-top:4px;
                                box-shadow:0 8px 24px rgba(0,0,0,.15);">
                    </div>
                </div>
                <div style="flex-shrink:0;">
                    <button type="submit" id="itrSubmitBtn"
                            style="height:38px;padding:0 28px;background:linear-gradient(135deg,#1d4ed8,#2563eb);
                                   color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;
                                   cursor:pointer;display:inline-flex;align-items:center;gap:8px;
                                   box-shadow:0 4px 12px rgba(37,99,235,.35);white-space:nowrap;">
                        <i class="bi bi-bar-chart-line"></i> Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($selectedProduct)

    {{-- Stats --}}
    @php
        $totalQtyOut = $transfers->where('status','received')->sum('quantity');
        $totalMovements = $transfers->count();
        $receivedCount  = $transfers->where('status','received')->count();
        $pendingCount   = $transfers->whereIn('status',['pending','approved','shipped'])->count();
    @endphp
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
        <div class="itr-stat">
            <div class="val" style="color:#0f172a;">{{ $totalMovements }}</div>
            <div class="lbl">Total Movements</div>
        </div>
        <div class="itr-stat">
            <div class="val" style="color:#16a34a;">{{ $receivedCount }}</div>
            <div class="lbl">Completed</div>
        </div>
        <div class="itr-stat">
            <div class="val" style="color:#ea580c;">{{ $pendingCount }}</div>
            <div class="lbl">In Progress</div>
        </div>
        <div class="itr-stat">
            <div class="val" style="color:#2563eb;">{{ $totalQtyOut }}</div>
            <div class="lbl">Units Transferred</div>
        </div>
        <div class="itr-stat" style="border-color:#bbf7d0;background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
            <div class="val" style="color:#15803d;">{{ number_format($currentTotalStock) }}</div>
            <div class="lbl" style="color:#166534;">📦 Current Stock</div>
        </div>
    </div>

    {{-- Product info bar --}}
    <div style="background:#eff6ff;border:2px solid #bfdbfe;border-radius:10px;padding:12px 18px;
                margin-bottom:16px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <i class="bi bi-box-seam" style="font-size:22px;color:#2563eb;"></i>
        <div>
            <div style="font-size:16px;font-weight:800;color:#0f172a;">
                {{ $selectedProduct->description }}
            </div>
            <div style="font-size:12px;color:#64748b;">
                Product ID: <strong>{{ $selectedProduct->product_id }}</strong>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="itr-table-wrap">
        @if($transfers->isEmpty())
        <div class="no-results">
            <i class="bi bi-inbox"></i>
            No transfer records found for this product.
        </div>
        @else
        <div style="overflow-x:auto;">
        <table class="itr-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Transfer #</th>
                    <th>Date</th>
                    <th>From Branch</th>
                    <th>To Branch</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:center;">Status</th>
                    <th>Approved / Received</th>
                    <th>Created By</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $i => $tr)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <a href="{{ route('dashboard.stock-transfers.show', $tr->id) }}"
                           style="font-weight:700;color:#2563eb;">
                            {{ $tr->transfer_number }}
                        </a>
                        @if($tr->batch_number)
                        <div style="font-size:11px;color:#94a3b8;">Batch: {{ $tr->batch_number }}</div>
                        @endif
                    </td>
                    <td style="white-space:nowrap;">
                        {{ $tr->transfer_date ? $tr->transfer_date->format('d M Y') : '—' }}
                    </td>
                    <td>
                        <span style="font-weight:600;">{{ $tr->fromBranch->name ?? '—' }}</span>
                    </td>
                    <td>
                        <span style="font-weight:600;">{{ $tr->toBranch->name ?? '—' }}</span>
                    </td>
                    <td style="text-align:center;">
                        <strong style="font-size:16px;color:#0f172a;">{{ $tr->quantity }}</strong>
                    </td>
                    <td style="text-align:center;">
                        @php
                            $statusClass = [
                                'received'  => 'badge-received',
                                'shipped'   => 'badge-shipped',
                                'approved'  => 'badge-approved',
                                'pending'   => 'badge-pending',
                                'cancelled' => 'badge-cancelled',
                            ][$tr->status] ?? 'badge-pending';
                        @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($tr->status) }}</span>
                    </td>
                    <td style="font-size:12px;color:#475569;white-space:nowrap;">
                        @if($tr->approved_date)
                            <div>✅ {{ $tr->approved_date->format('d M Y') }}</div>
                        @endif
                        @if($tr->received_date)
                            <div>📦 {{ $tr->received_date->format('d M Y') }}</div>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#64748b;">
                        {{ $tr->creator->name ?? '—' }}
                    </td>
                    <td style="font-size:12px;color:#64748b;max-width:160px;">
                        {{ $tr->notes ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

    @else
    {{-- No product selected yet --}}
    <div style="text-align:center;padding:80px 20px;background:#fff;border-radius:12px;border:2px dashed #e2e8f0;">
        <i class="bi bi-search" style="font-size:52px;color:#cbd5e1;display:block;margin-bottom:16px;"></i>
        <div style="font-size:18px;font-weight:700;color:#64748b;">Select a Product to Generate Report</div>
        <div style="color:#94a3b8;margin-top:6px;">Choose a product from the filter above and click Generate Report</div>
    </div>
    @endif

</div>

<script>
(function() {
    /* ── Products data from server ── */
    var products = [
        @foreach($products as $p)
        { id: {{ $p->id }}, label: '{{ addslashes($p->product_id) }} — {{ addslashes($p->description) }}' },
        @endforeach
    ];

    var searchInput  = document.getElementById('productSearch');
    var dropdown     = document.getElementById('productDropdown');
    var hiddenInput  = document.getElementById('productIdHidden');

    /* Pre-fill label if a product is already selected */
    var preSelected = '{{ request('product_id') }}';
    if (preSelected) {
        for (var i = 0; i < products.length; i++) {
            if (products[i].id == preSelected) {
                searchInput.value = products[i].label;
                break;
            }
        }
    }

    function buildDropdown(filtered) {
        dropdown.innerHTML = '';
        if (filtered.length === 0) {
            dropdown.innerHTML = '<div style="padding:12px 14px;color:#94a3b8;font-size:13px;">لا توجد نتائج</div>';
        } else {
            filtered.forEach(function(p) {
                var item = document.createElement('div');
                item.textContent = p.label;
                item.style.cssText = 'padding:10px 14px;font-size:13px;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background .1s;';
                item.addEventListener('mouseenter', function() { this.style.background = '#eff6ff'; });
                item.addEventListener('mouseleave', function() { this.style.background = '#fff'; });
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    searchInput.value   = p.label;
                    hiddenInput.value   = p.id;
                    searchInput.style.borderColor = '#16a34a';
                    dropdown.style.display = 'none';
                });
                dropdown.appendChild(item);
            });
        }
        dropdown.style.display = 'block';
    }

    searchInput.addEventListener('input', function() {
        hiddenInput.value = '';
        searchInput.style.borderColor = '#e2e8f0';
        var q = this.value.trim().toLowerCase();
        if (q.length === 0) { dropdown.style.display = 'none'; return; }
        var filtered = products.filter(function(p) {
            return p.label.toLowerCase().indexOf(q) !== -1;
        });
        buildDropdown(filtered);
    });

    searchInput.addEventListener('focus', function() {
        var q = this.value.trim().toLowerCase();
        if (q.length > 0 && !hiddenInput.value) buildDropdown(
            products.filter(function(p) { return p.label.toLowerCase().indexOf(q) !== -1; })
        );
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (hiddenInput.value) document.getElementById('itrForm').submit();
        }
    });

    /* Validate before submit */
    document.getElementById('itrForm').addEventListener('submit', function(e) {
        if (!hiddenInput.value) {
            e.preventDefault();
            searchInput.style.borderColor = '#dc2626';
            searchInput.focus();
            alert('من فضلك اختر منتج من القائمة');
        }
    });
})();
</script>
@endsection
