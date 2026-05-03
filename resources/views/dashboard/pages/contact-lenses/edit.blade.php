@extends('dashboard.layouts.master')
@section('title', 'Edit Contact Lens')
@section('content')

<style>
    .cl-create-page { padding: 20px; }
    .cl-box { border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.1); overflow:hidden; border:none; }
    .cl-box-header { background:linear-gradient(135deg,#e67e22,#d35400); color:#fff; padding:22px 28px; position:relative; overflow:hidden; display:flex; align-items:center; justify-content:space-between; }
    .cl-box-header h3 { margin:0; font-size:20px; font-weight:700; position:relative; z-index:1; }
    .cl-box-header h3 i { background:rgba(255,255,255,.2); padding:9px; border-radius:8px; margin-right:10px; }
    .cl-box-header .btn-back { background:rgba(255,255,255,.2); border:2px solid rgba(255,255,255,.4); color:#fff; padding:8px 18px; border-radius:8px; font-weight:600; font-size:13px; text-decoration:none; }
    .cl-box-header .btn-back:hover { background:#fff; color:#d35400; text-decoration:none; }
    .cl-section { background:#fff; padding:24px 28px; border-radius:10px; margin-bottom:18px; border:2px solid #e8ecf7; box-shadow:0 2px 8px rgba(0,0,0,.04); }
    .cl-section-head { display:flex; align-items:center; margin-bottom:22px; padding-bottom:12px; border-bottom:2px solid #e0ecff; }
    .cl-section-head .icon-box { width:42px; height:42px; background:linear-gradient(135deg,#e67e22,#d35400); border-radius:9px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; margin-right:14px; flex-shrink:0; }
    .cl-section-head h4 { margin:0; font-size:16px; font-weight:700; color:#d35400; }
    .form-group label { font-weight:600; color:#555; font-size:13px; margin-bottom:7px; display:block; }
    .form-group label .req { color:#e74c3c; }
    .form-control { border:2px solid #e0e6ed; border-radius:8px !important; font-size:14px; transition:all .3s; }
    .form-control:focus { border-color:#e67e22; box-shadow:0 0 0 3px rgba(230,126,34,.1); outline:none; }
    .help-text { font-size:12px; color:#999; margin-top:4px; font-style:italic; }
    .sign-selector { display:flex; gap:12px; }
    .sign-btn { flex:1; border:2px solid #e0e6ed; border-radius:10px; padding:14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .sign-btn.selected-plus  { border-color:#27ae60; background:#e8f8f5; }
    .sign-btn.selected-minus { border-color:#e74c3c; background:#fef2f2; }
    .sign-btn .sign-icon { font-size:28px; font-weight:900; }
    .sign-btn .sign-label { font-size:12px; font-weight:600; color:#666; margin-top:4px; }
    .sign-btn.selected-plus  .sign-icon, .sign-btn.selected-plus  .sign-label { color:#27ae60; }
    .sign-btn.selected-minus .sign-icon, .sign-btn.selected-minus .sign-label { color:#e74c3c; }
    .seg-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .seg-btn { border:2px solid #e0e6ed; border-radius:10px; padding:12px 14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .seg-btn.selected { border-color:#e67e22; background:#fef9f5; }
    .seg-btn .seg-icon { font-size:20px; margin-bottom:4px; }
    .seg-btn .seg-name { font-size:13px; font-weight:700; color:#333; }
    .seg-btn .seg-desc { font-size:11px; color:#888; margin-top:2px; }
    .use-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
    .use-btn { border:2px solid #e0e6ed; border-radius:10px; padding:14px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbfc; }
    .use-btn.sel-daily   { border-color:#27ae60; background:#e8f8f5; }
    .use-btn.sel-monthly { border-color:#f57f17; background:#fff8e1; }
    .use-btn .use-icon { font-size:22px; margin-bottom:5px; }
    .use-btn .use-name { font-size:13px; font-weight:700; }
    .use-btn.sel-daily   .use-name, .use-btn.sel-daily   .use-desc { color:#27ae60; }
    .use-btn.sel-monthly .use-name, .use-btn.sel-monthly .use-desc { color:#f57f17; }
    .use-btn .use-desc { font-size:11px; color:#888; margin-top:2px; }
    .btn-submit-cl { background:linear-gradient(135deg,#e67e22,#d35400); color:#fff; border:none; padding:13px 48px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(230,126,34,.3); margin:0 8px; transition:all .3s; }
    .btn-submit-cl:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(230,126,34,.4); color:#fff; }
    .btn-cancel-cl { background:#fff; color:#666; border:2px solid #ddd; padding:13px 48px; border-radius:8px; font-size:15px; font-weight:700; text-decoration:none; display:inline-block; margin:0 8px; transition:all .3s; }
    .btn-cancel-cl:hover { background:#f8f9fa; color:#333; text-decoration:none; }
</style>

<section class="content-header">
    <h1><i class="fa fa-edit" style="color:#e67e22;"></i> Edit Contact Lens
        <small>Product ID: {{ $lens->product_id }}</small>
    </h1>
</section>

<div class="cl-create-page">
    <form action="{{ route('dashboard.contact-lenses.update', $lens->id) }}" method="POST" id="clForm">
        @csrf @method('PUT')
        <div class="cl-box">
            <div class="cl-box-header">
                <h3><i class="fa fa-edit"></i> Edit Contact Lens</h3>
                <a href="{{ route('dashboard.contact-lenses.index') }}" class="btn-back">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
            <div style="padding:28px;">
                @include('dashboard.partials._errors')

                {{-- Section 1: Identification --}}
                <div class="cl-section">
                    <div class="cl-section-head">
                        <div class="icon-box"><i class="fa fa-barcode"></i></div>
                        <h4>Product Identification</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Product ID</label>
                                <input type="text" class="form-control" value="{{ $lens->product_id }}" disabled
                                       style="background:#f5f5f5;font-family:monospace;font-weight:700;color:#3498db;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Brand <span class="req">*</span></label>
                                <select name="brand_id" id="cl_brand" class="form-control" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ $lens->brand_id == $b->id ? 'selected' : '' }}>
                                            {{ $b->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name (Model) <span class="req">*</span></label>
                                <select name="model_id" id="cl_model" class="form-control" required>
                                    <option value="">Select Product Name</option>
                                    @foreach($models as $m)
                                        <option value="{{ $m->id }}" {{ $lens->model_id == $m->id ? 'selected' : '' }}>
                                            {{ $m->model_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Lens Specs --}}
                <div class="cl-section">
                    <div class="cl-section-head">
                        <div class="icon-box"><i class="fa fa-eye"></i></div>
                        <h4>Lens Specifications</h4>
                    </div>
                    <div class="row">
                        {{-- Segment --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Brand Segment <span class="req">*</span></label>
                                <div class="seg-grid">
                                    @foreach([
                                        ['Clear','🔵','Standard vision correction'],
                                        ['Color','🎨','Colored / cosmetic'],
                                        ['Toric','🎯','Astigmatism correction'],
                                        ['Multifocal','🔭','Multiple focal zones'],
                                    ] as [$seg, $icon, $desc])
                                        <div class="seg-btn {{ $lens->brand_segment == $seg ? 'selected' : '' }}"
                                             data-seg="{{ $seg }}" onclick="selectSeg('{{ $seg }}')">
                                            <div class="seg-icon">{{ $icon }}</div>
                                            <div class="seg-name">{{ $seg }}</div>
                                            <div class="seg-desc">{{ $desc }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="brand_segment" id="brand_segment" value="{{ $lens->brand_segment }}" required>
                            </div>
                        </div>
                        {{-- Lens Type --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lens Type <span class="req">*</span></label>
                                <div class="use-grid">
                                    <div class="use-btn {{ $lens->lense_use == 'Daily' ? 'sel-daily' : '' }}"
                                         data-use="Daily" onclick="selectUse('Daily')">
                                        <div class="use-icon">☀️</div>
                                        <div class="use-name">Daily</div>
                                        <div class="use-desc">Single-use</div>
                                    </div>
                                    <div class="use-btn {{ $lens->lense_use == 'Monthly' ? 'sel-monthly' : '' }}"
                                         data-use="Monthly" onclick="selectUse('Monthly')">
                                        <div class="use-icon">📅</div>
                                        <div class="use-name">Monthly</div>
                                        <div class="use-desc">Reusable</div>
                                    </div>
                                </div>
                                <input type="hidden" name="lense_use" id="lense_use" value="{{ $lens->lense_use }}" required>
                            </div>
                        </div>
                        {{-- Sign --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sign <span class="req">*</span></label>
                                <div class="sign-selector">
                                    <div class="sign-btn {{ $lens->sign == '+' ? 'selected-plus' : '' }}"
                                         data-sign="+" onclick="selectSign('+')">
                                        <div class="sign-icon">+</div>
                                        <div class="sign-label">Plus</div>
                                    </div>
                                    <div class="sign-btn {{ $lens->sign == '-' ? 'selected-minus' : '' }}"
                                         data-sign="-" onclick="selectSign('-')">
                                        <div class="sign-icon">−</div>
                                        <div class="sign-label">Minus</div>
                                    </div>
                                </div>
                                <input type="hidden" name="sign" id="sign" value="{{ $lens->sign }}" required>
                            </div>
                        </div>
                        {{-- Power --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Power <span class="req">*</span></label>
                                <input type="number" step="0.25" min="0" max="12" name="power" id="power"
                                       class="form-control" value="{{ $lens->power }}" required
                                       style="font-size:16px;font-weight:700;text-align:center;">
                                <p class="help-text">0.00 to 12.00 in 0.25 steps</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pricing --}}
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
                                       value="{{ old('description', $lens->description) }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cost Price <span class="req">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price', $lens->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Retail Price <span class="req">*</span></label>
                                <input type="number" step="0.01" name="retail_price" class="form-control"
                                       value="{{ old('retail_price', $lens->retail_price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tax (%)</label>
                                <input type="number" step="0.01" name="tax" class="form-control"
                                       value="{{ old('tax', $lens->tax) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="text-align:center;padding:10px 0 4px;">
                    <button type="submit" class="btn-submit-cl">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('dashboard.contact-lenses.index') }}" class="btn-cancel-cl">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('assets/js/jquery-2.0.2.min.js') }}"></script>
<script>
var CSRF = '{{ csrf_token() }}';

function selectSign(sign) {
    document.querySelectorAll('.sign-btn').forEach(function(btn) {
        btn.classList.remove('selected-plus','selected-minus');
    });
    document.querySelector('.sign-btn[data-sign="'+sign+'"]').classList.add(sign === '+' ? 'selected-plus' : 'selected-minus');
    document.getElementById('sign').value = sign;
}
function selectSeg(seg) {
    document.querySelectorAll('.seg-btn').forEach(function(btn) { btn.classList.remove('selected'); });
    document.querySelector('.seg-btn[data-seg="'+seg+'"]').classList.add('selected');
    document.getElementById('brand_segment').value = seg;
}
function selectUse(use) {
    document.querySelectorAll('.use-btn').forEach(function(btn) { btn.classList.remove('sel-daily','sel-monthly'); });
    document.querySelector('.use-btn[data-use="'+use+'"]').classList.add(use === 'Daily' ? 'sel-daily' : 'sel-monthly');
    document.getElementById('lense_use').value = use;
}

$(document).ready(function() {
    // Brand change → reload models filtered by CL category + brand
    $('#cl_brand').on('change', function() {
        var brandId = $(this).val();
        if (!brandId) return;
        $.ajax({
            headers:{'X-CSRF-TOKEN':CSRF}, type:'POST',
            url:'{{ route("dashboard.filter-models-by-category-and-brand-id") }}',
            data:{category_id: 4, brand_id:brandId},
            success:function(res) {
                var html = '<option value="">Select Product Name</option>';
                $.each(res, function(i,m) {
                    var sel = m.id == {{ $lens->model_id }} ? ' selected' : '';
                    html += '<option value="'+m.id+'"'+sel+'>'+m.model_id+'</option>';
                });
                $('#cl_model').html(html).prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
