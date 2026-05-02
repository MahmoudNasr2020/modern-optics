@extends('dashboard.layouts.master')
@section('title', 'Lens Brands')
@section('content')
    <style>
        .page-card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 20px rgba(0,0,0,.08); margin-bottom:24px; }
        .sec-header { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; padding:14px 22px; border-radius:10px; margin:-28px -28px 24px -28px; font-weight:700; font-size:17px; display:flex; align-items:center; gap:10px; }
        .brand-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:20px; }
        .brand-card { border:2px solid #e8eaf6; border-radius:14px; padding:22px; transition:all .25s; position:relative; overflow:hidden; }
        .brand-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(135deg,#667eea,#764ba2); }
        .brand-card:hover { border-color:#667eea; box-shadow:0 6px 24px rgba(102,126,234,.18); transform:translateY(-3px); }
        .brand-logo { width:56px; height:56px; border-radius:12px; background:linear-gradient(135deg,#f0ebff,#e8eaf6); display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:14px; }
        .brand-logo img { width:100%; height:100%; object-fit:contain; border-radius:12px; }
        .brand-name { font-size:18px; font-weight:700; color:#222; margin-bottom:4px; }
        .brand-meta { font-size:12px; color:#999; margin-bottom:14px; }
        .lens-count { display:inline-flex; align-items:center; gap:5px; background:#f0ebff; color:#764ba2; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; margin-bottom:16px; }
        .brand-actions { display:flex; gap:8px; }
        .btn-view   { flex:1; text-align:center; padding:7px; border-radius:8px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; font-size:12px; font-weight:700; text-decoration:none; transition:all .2s; }
        .btn-edit   { padding:7px 12px; border-radius:8px; background:#f0ebff; color:#764ba2; font-size:12px; font-weight:700; text-decoration:none; transition:all .2s; }
        .btn-del    { padding:7px 12px; border-radius:8px; background:#fee; color:#e74c3c; font-size:12px; font-weight:700; border:none; cursor:pointer; transition:all .2s; }
        .btn-new { display:inline-flex; align-items:center; gap:8px; padding:10px 24px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; border:none; border-radius:10px; font-weight:700; font-size:14px; cursor:pointer; text-decoration:none; box-shadow:0 4px 14px rgba(102,126,234,.35); transition:all .25s; }
        .btn-new:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(102,126,234,.5); color:#fff; text-decoration:none; }
        .inactive-badge { position:absolute; top:14px; right:14px; background:#fee; color:#e74c3c; padding:2px 9px; border-radius:10px; font-size:10px; font-weight:700; }
    </style>

    <section class="content-header">
        <h1><i class="bi bi-building"></i> Lens Brands <small>manage lens manufacturers</small></h1>
    </section>

    <div style="padding:20px;">
        @include('dashboard.partials._errors')

        <div class="page-card">
            <div class="sec-header">
                <i class="bi bi-building"></i> All Lens Brands
                <div style="margin-left:auto;">
                    <a href="{{ route('dashboard.lens-brands.create') }}" class="btn-new">
                        <i class="bi bi-plus-lg"></i> Add Brand
                    </a>
                </div>
            </div>

            @if($brands->isEmpty())
                <div style="text-align:center;padding:60px 0;color:#bbb;">
                    <i class="bi bi-building" style="font-size:48px;display:block;margin-bottom:14px;"></i>
                    <div style="font-size:16px;font-weight:600;">No brands yet</div>
                    <div style="font-size:13px;margin-top:6px;">Add your first lens brand to get started</div>
                </div>
            @else
                <div class="brand-grid">
                    @foreach($brands as $brand)
                        <div class="brand-card">
                            @if(!$brand->is_active)<div class="inactive-badge">Inactive</div>@endif
                            <div class="brand-logo">
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}">
                                @else
                                    🏷️
                                @endif
                            </div>
                            <div class="brand-name">{{ $brand->name }}</div>
                            <div class="brand-meta">{{ $brand->description ?? 'No description' }}</div>
                            <div class="lens-count"><i class="bi bi-eye"></i> {{ $brand->lenses_count }} lenses</div>
                            <div class="brand-actions">
                                <a href="{{ route('dashboard.lens-brands.show', $brand) }}" class="btn-view"><i class="bi bi-eye"></i> View</a>
                                <a href="{{ route('dashboard.lens-brands.edit', $brand) }}" class="btn-edit"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="btn-del btn-delete-brand"
                                        data-id="{{ $brand->id }}"
                                        data-name="{{ $brand->name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══ Delete Modal ══ --}}
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;border:none;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
                    <h4 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete</h4>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" style="padding:25px;">
                        <div class="alert alert-warning" style="border-left:5px solid #f39c12;">
                            Are you sure you want to delete brand <strong id="brandName"></strong>?
                            <br><small class="text-muted">All linked lenses will be unassigned.</small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:2px solid #f0f2f5;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).on('click', '.btn-delete-brand', function () {
            var id   = $(this).data('id');
            var name = $(this).data('name');
            var route = '{{ route("dashboard.lens-brands.destroy", ":id") }}'.replace(':id', id);
            $('#brandName').text(name);
            $('#deleteForm').attr('action', route);
            $('#deleteModal').modal('show');
        });
    </script>
@endsection
