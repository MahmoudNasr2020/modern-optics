@extends('dashboard.layouts.master')
@section('title', 'Contact Lenses')
@section('content')

<style>
    .cl-page { padding: 20px; }
    .cl-header-bar { background: linear-gradient(135deg,#3498db,#2980b9); color:#fff; border-radius:12px; padding:22px 28px; margin-bottom:22px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; box-shadow:0 4px 18px rgba(52,152,219,.3); }
    .cl-header-bar h2 { margin:0; font-size:20px; font-weight:700; }
    .cl-header-bar .sub { font-size:12px; opacity:.8; margin-top:3px; }
    .cl-stat-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:22px; }
    .cl-stat { background:#fff; border-radius:10px; padding:16px 18px; text-align:center; border-left:4px solid #3498db; box-shadow:0 2px 8px rgba(0,0,0,.06); }
    .cl-stat .num { font-size:26px; font-weight:800; color:#3498db; }
    .cl-stat .lbl { font-size:11px; color:#888; text-transform:uppercase; font-weight:600; letter-spacing:.5px; margin-top:3px; }
    .cl-filters { background:#fff; border-radius:10px; padding:16px 20px; margin-bottom:18px; box-shadow:0 2px 8px rgba(0,0,0,.05); border:1.5px solid #e0e6ed; }
    .cl-table-wrap { background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.07); border:1.5px solid #e0e6ed; }
    .cl-table { width:100%; border-collapse:collapse; font-size:13px; }
    .cl-table thead { background:linear-gradient(135deg,#3498db,#2980b9); }
    .cl-table thead th { padding:12px 14px; color:#fff; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.5px; }
    .cl-table tbody tr { border-bottom:1px solid #f0f2f8; transition:background .15s; }
    .cl-table tbody tr:hover { background:#f0f7ff; }
    .cl-table tbody td { padding:11px 14px; vertical-align:middle; }
    .cl-table tbody td:first-child { font-weight:700; color:#3498db; }
    .badge-seg { padding:2px 10px; border-radius:10px; font-size:11px; font-weight:700; }
    .seg-clear    { background:#e3f2fd; color:#1565c0; }
    .seg-color    { background:#f3e5f5; color:#6a1b9a; }
    .seg-toric    { background:#e8f5e9; color:#1b5e20; }
    .seg-multifocal { background:#fff3e0; color:#e65100; }
    .badge-use { padding:2px 9px; border-radius:10px; font-size:11px; font-weight:700; }
    .use-daily   { background:#e8f5e9; color:#2e7d32; }
    .use-monthly { background:#fff8e1; color:#f57f17; }
    .badge-sign { font-weight:900; font-size:16px; }
    .sign-plus  { color:#27ae60; }
    .sign-minus { color:#e74c3c; }
    .badge-power { background:#eef2ff; color:#4f46e5; padding:3px 10px; border-radius:8px; font-size:12px; font-weight:700; }
    .btn-add-cl { background:linear-gradient(135deg,#fff,rgba(255,255,255,.8)); color:#2980b9; border:2px solid rgba(255,255,255,.6); padding:10px 20px; border-radius:8px; font-weight:700; font-size:13px; cursor:pointer; text-decoration:none; transition:all .2s; display:inline-flex; align-items:center; gap:7px; }
    .btn-add-cl:hover { background:#fff; color:#1a6fa0; text-decoration:none; transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }
    .btn-edit-sm { background:#e8f5e9; color:#27ae60; border:none; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; margin-right:4px; }
    .btn-edit-sm:hover { background:#27ae60; color:#fff; text-decoration:none; }
    .btn-del-sm { background:#fef2f2; color:#e74c3c; border:none; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; }
    .btn-del-sm:hover { background:#e74c3c; color:#fff; }
    .filter-btn { background:linear-gradient(135deg,#3498db,#2980b9); color:#fff; border:none; padding:8px 18px; border-radius:8px; font-weight:600; font-size:13px; cursor:pointer; }
    .filter-reset { background:#f4f6fb; color:#666; border:1.5px solid #ddd; padding:8px 14px; border-radius:8px; font-size:13px; text-decoration:none; }
    .empty-cl { text-align:center; padding:60px 20px; color:#999; }
    .empty-cl .icon { font-size:52px; color:#ddd; margin-bottom:14px; }
</style>

<section class="content-header">
    <h1><i class="fa fa-eye" style="color:#3498db;"></i> Contact Lenses
        <small>Manage contact lens products</small>
    </h1>
</section>

<div class="cl-page">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible" style="border-radius:10px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-exclamation-triangle"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- Header Bar --}}
    <div class="cl-header-bar">
        <div>
            <h2><i class="fa fa-eye"></i> Contact Lenses — عدسات لاصقة</h2>
            <div class="sub">
                {{ $totalLenses }} منتج نشط
                @if($archivedCount > 0)
                    &nbsp;|&nbsp; <span style="color:#dc2626;font-weight:700;">{{ $archivedCount }} مؤرشف</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            @if($archivedCount > 0)
            <a href="{{ route('dashboard.contact-lenses.index', array_merge(request()->query(), ['show_archived' => $showArchived ? 0 : 1])) }}"
               style="background:{{ $showArchived ? '#fef3c7' : '#fee2e2' }};color:{{ $showArchived ? '#92400e' : '#991b1b' }};border:1.5px solid {{ $showArchived ? '#fde68a' : '#fecaca' }};padding:8px 14px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-archive{{ $showArchived ? '-fill' : '' }}"></i>
                {{ $showArchived ? 'إخفاء المؤرشف' : 'عرض المؤرشف ('.$archivedCount.')' }}
            </a>
            @endif
            <a href="{{ route('dashboard.contact-lenses.create') }}" class="btn-add-cl">
                <i class="fa fa-plus-circle"></i> Add Contact Lens
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="cl-filters">
        <form method="GET" action="{{ route('dashboard.contact-lenses.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:4px;">Brand</label>
                <select name="brand_id" class="form-control" style="min-width:140px;font-size:13px;border-radius:8px;border:1.5px solid #e0e6ed;">
                    <option value="">All Brands</option>
                    @foreach($allBrands as $b)
                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->brand_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:4px;">Segment</label>
                <select name="brand_segment" class="form-control" style="min-width:130px;font-size:13px;border-radius:8px;border:1.5px solid #e0e6ed;">
                    <option value="">All Segments</option>
                    @foreach(['Clear','Color','Toric','Multifocal'] as $seg)
                        <option value="{{ $seg }}" {{ request('brand_segment') == $seg ? 'selected' : '' }}>{{ $seg }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:4px;">Lens Type</label>
                <select name="lense_use" class="form-control" style="min-width:120px;font-size:13px;border-radius:8px;border:1.5px solid #e0e6ed;">
                    <option value="">All Types</option>
                    <option value="Daily"   {{ request('lense_use') == 'Daily'   ? 'selected' : '' }}>Daily</option>
                    <option value="Monthly" {{ request('lense_use') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:4px;">Sign</label>
                <select name="sign" class="form-control" style="min-width:80px;font-size:13px;border-radius:8px;border:1.5px solid #e0e6ed;">
                    <option value="">All</option>
                    <option value="+" {{ request('sign') == '+' ? 'selected' : '' }}>+ (Plus)</option>
                    <option value="-" {{ request('sign') == '-' ? 'selected' : '' }}>− (Minus)</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px;font-weight:600;color:#555;display:block;margin-bottom:4px;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Description / ID / Power…"
                       value="{{ request('search') }}"
                       style="min-width:180px;font-size:13px;border-radius:8px;border:1.5px solid #e0e6ed;">
            </div>
            <div style="display:flex;gap:8px;padding-top:2px;">
                <button type="submit" class="filter-btn"><i class="fa fa-search"></i> Filter</button>
                <a href="{{ route('dashboard.contact-lenses.index') }}" class="filter-reset">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="cl-table-wrap">
        @if($lenses->count() > 0)
        <table class="cl-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Product ID</th>
                <th>Brand</th>
                <th>Product Name</th>
                <th>Segment</th>
                <th>Lens Type</th>
                <th style="text-align:center;">Sign</th>
                <th style="text-align:center;">Power</th>
                <th>Description</th>
                <th style="text-align:right;">Cost</th>
                <th style="text-align:right;">Retail</th>
                <th style="text-align:center;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lenses as $i => $lens)
                <tr style="{{ !$lens->is_active ? 'opacity:.5;background:#fafafa;' : '' }}">
                    <td style="color:#aaa;font-weight:400;">{{ $lenses->firstItem() + $i }}</td>
                    <td>
                        <code style="background:#f0f7ff;color:#3498db;padding:2px 7px;border-radius:5px;font-size:12px;">
                            {{ $lens->product_id }}
                        </code>
                        @if(!$lens->is_active)
                            <br><span style="display:inline-block;margin-top:3px;background:#fee2e2;color:#991b1b;padding:2px 7px;border-radius:20px;font-size:10px;font-weight:800;">🗃️ مؤرشف</span>
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $lens->brand->brand_name ?? '—' }}</td>
                    <td>{{ $lens->model->model_id ?? '—' }}</td>
                    <td>
                        @php $seg = $lens->brand_segment; @endphp
                        @if($seg)
                            <span class="badge-seg seg-{{ strtolower($seg) }}">{{ $seg }}</span>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($lens->lense_use)
                            <span class="badge-use use-{{ strtolower($lens->lense_use) }}">{{ $lens->lense_use }}</span>
                        @else —
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span class="badge-sign {{ $lens->sign === '+' ? 'sign-plus' : 'sign-minus' }}">{{ $lens->sign }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge-power">{{ number_format((float)$lens->power, 2) }}</span>
                    </td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $lens->description }}">
                        {{ $lens->description }}
                    </td>
                    <td style="text-align:right;font-weight:600;color:#555;">{{ number_format($lens->price, 2) }}</td>
                    <td style="text-align:right;font-weight:700;color:#27ae60;">{{ number_format($lens->retail_price, 2) }}</td>
                    <td style="text-align:center;white-space:nowrap;">
                        @if($lens->is_active)
                        <a href="{{ route('dashboard.contact-lenses.edit', $lens->id) }}" class="btn-edit-sm">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button type="button" class="btn-del-sm"
                                onclick="confirmDelete({{ $lens->id }}, '{{ addslashes($lens->description) }}')">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                        {{-- Hidden delete form --}}
                        <form id="del-form-{{ $lens->id }}"
                              method="POST"
                              action="{{ route('dashboard.contact-lenses.destroy', $lens->id) }}"
                              style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        @else
                        {{-- Archived lens: show restore button --}}
                        <form method="POST"
                              action="{{ route('dashboard.contact-lenses.restore', $lens->id) }}"
                              style="display:inline;">
                            @csrf
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;">
                                <i class="bi bi-arrow-counterclockwise"></i> استعادة
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div style="padding:14px 18px;border-top:1.5px solid #f0f2f8;background:#fafbfc;">
            {{ $lenses->appends(request()->query())->links() }}
        </div>
        @else
            <div class="empty-cl">
                <div class="icon"><i class="fa fa-eye"></i></div>
                <h3 style="color:#666;margin-bottom:8px;">No Contact Lenses Found</h3>
                <p style="margin-bottom:16px;">No contact lenses match your filters, or none have been added yet.</p>
                <a href="{{ route('dashboard.contact-lenses.create') }}" class="btn btn-primary" style="border-radius:8px;font-weight:700;">
                    <i class="fa fa-plus-circle"></i> Add First Contact Lens
                </a>
            </div>
        @endif
    </div>
</div>
<script>
function confirmDelete(id, description) {
    Swal.fire({
        title: 'حذف العدسة؟',
        html: '<div style="text-align:right;direction:rtl;">'
            + '<p style="font-size:14px;color:#555;margin-bottom:12px;">هل تريد حذف: <strong style="color:#e74c3c;">' + description + '</strong>؟</p>'
            + '<div style="background:#fef9c3;border:1px solid #fde68a;border-radius:8px;padding:10px 12px;font-size:12px;color:#78350f;text-align:right;">'
            + '<strong>⚙️ الحذف الذكي:</strong>'
            + '<ul style="margin:6px 0 0 0;padding-right:16px;">'
            + '<li>مخزون في أي فرع ← يُرفض الحذف</li>'
            + '<li>مرتبط بفواتير ← يُؤرشف فقط</li>'
            + '<li>نظيف تماماً ← يُحذف نهائياً</li>'
            + '</ul></div></div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash3-fill"></i> تأكيد',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById('del-form-' + id).submit();
        }
    });
}
</script>
@endsection
