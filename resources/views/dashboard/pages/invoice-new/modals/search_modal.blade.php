{{-- resources/views/dashboard/pages/invoice-new/modals/search_modal.blade.php --}}

<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">

            <div class="modal-header"
                 style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                        color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal"
                        style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 20px; font-weight: 700;">
                    <i class="bi bi-funnel-fill"></i> Advanced Product Search
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">

                {{-- ══ ROW 1: Always visible ══ --}}
                <div class="row">

                    {{-- Category --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-tag-fill" style="color:#f39c12;"></i> Category
                            </label>
                            <select class="form-control" id="sm_category_id"
                                    style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Brand (dynamically loaded per category) --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-award-fill" style="color:#f39c12;"></i> Brand
                            </label>
                            <select class="form-control" id="sm_brand_id"
                                    style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Model / Product Name (hidden for cat 3,6,7,8,10) --}}
                    <div class="col-md-3" id="sm_model_col">
                        <div class="form-group">
                            <label id="sm_model_label"
                                   style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-eyeglasses" style="color:#f39c12;"></i>
                                <span id="sm_model_label_text">Model</span>
                                <span id="sm_model_spinner" style="display:none;font-size:10px;color:#f39c12;">
                                    <i class="bi bi-arrow-repeat"></i>
                                </span>
                            </label>
                            <select class="form-control" id="sm_model_id"
                                    style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                                <option value="">All Models</option>
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->model_id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Search text --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-search" style="color:#f39c12;"></i> Product ID / Name
                            </label>
                            <input type="text" class="form-control" id="sm_search_text"
                                   placeholder="Search by ID or description…"
                                   style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                        </div>
                    </div>
                </div>
                {{-- /ROW 1 --}}

                {{-- ══ ROW 2A: Frames / Sunglasses — cat 1,2 or default "All" ══ --}}
                <div class="row sm-filter-row" id="sm_row_standard" style="margin-top:-8px;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-rulers" style="color:#f39c12;"></i> Size
                            </label>
                            <input type="text" class="form-control" id="sm_size"
                                   placeholder="e.g., 52"
                                   style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-palette-fill" style="color:#f39c12;"></i> Color
                            </label>
                            <input type="text" class="form-control" id="sm_color"
                                   placeholder="e.g., Black"
                                   style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-collection-fill" style="color:#f39c12;"></i> Type
                            </label>
                            <select class="form-control" id="sm_type"
                                    style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                                <option value="">All Types</option>
                                <option value="frame">Frame</option>
                                <option value="sunglasses">Sunglasses</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3"><!-- spacer --></div>
                </div>
                {{-- /ROW 2A --}}

                {{-- ══ ROW 2B: Contact Lens fields — cat 4 ══ --}}
                <div class="row sm-filter-row" id="sm_row_cl"
                     style="display:none;margin-top:-8px;
                            background:rgba(52,152,219,.05);border-radius:10px;
                            padding:8px 4px;border:1.5px dashed rgba(52,152,219,.3);">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#3498db;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-circle-half" style="color:#3498db;"></i> Segment
                            </label>
                            <select class="form-control" id="sm_brand_segment"
                                    style="border:2px solid #aed6f1;border-radius:8px !important;height:40px;">
                                <option value="">All Segments</option>
                                <option value="Clear">Clear</option>
                                <option value="Color">Color</option>
                                <option value="Toric">Toric</option>
                                <option value="Multifocal">Multifocal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#3498db;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-calendar-check" style="color:#3498db;"></i> Lens Type
                            </label>
                            <select class="form-control" id="sm_lense_use"
                                    style="border:2px solid #aed6f1;border-radius:8px !important;height:40px;">
                                <option value="">Daily & Monthly</option>
                                <option value="Daily">Daily</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#3498db;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-plus-slash-minus" style="color:#3498db;"></i> Sign
                            </label>
                            <select class="form-control" id="sm_sign"
                                    style="border:2px solid #aed6f1;border-radius:8px !important;height:40px;">
                                <option value="">+ &amp; −</option>
                                <option value="+">+ (Positive)</option>
                                <option value="-">− (Negative)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#3498db;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-eyeglasses" style="color:#3498db;"></i> Power
                            </label>
                            <input type="text" class="form-control" id="sm_cl_power"
                                   placeholder="e.g., 1.50"
                                   style="border:2px solid #aed6f1;border-radius:8px !important;height:40px;">
                        </div>
                    </div>
                </div>
                {{-- /ROW 2B --}}

                {{-- ══ ROW 2C: Reading Glasses — cat 6 ══ --}}
                <div class="row sm-filter-row" id="sm_row_glasses"
                     style="display:none;margin-top:-8px;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-eyeglasses" style="color:#f39c12;"></i> Power
                            </label>
                            <input type="text" class="form-control" id="sm_power"
                                   placeholder="e.g., 2.00"
                                   style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-collection-fill" style="color:#f39c12;"></i> Frame Type
                            </label>
                            <select class="form-control" id="sm_glasses_type"
                                    style="border:2px solid #e0e6ed;border-radius:8px !important;height:40px;">
                                <option value="">All Types</option>
                                <option value="frame">Frame</option>
                                <option value="reading">Reading</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6"><!-- spacer --></div>
                </div>
                {{-- /ROW 2C --}}

                {{-- ══ ROW 3: Search + Reset (always visible) ══ --}}
                <div class="row" style="margin-top:12px;">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div style="display:flex;gap:6px;">
                            <button type="button" id="sm_executeSearchBtn"
                                    style="flex:1;background:linear-gradient(135deg,#f39c12,#e67e22);
                                           color:white;border:none;border-radius:8px;
                                           height:42px;font-weight:700;font-size:14px;cursor:pointer;">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <button type="button" id="sm_resetSearchBtn" title="Reset filters"
                                    style="background:#f0f2f5;color:#555;border:none;
                                           border-radius:8px;height:42px;padding:0 16px;
                                           font-size:16px;cursor:pointer;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- /ROW 3 --}}

                {{-- Results --}}
                <div id="sm_searchResultsContainer" style="margin-top:20px;">
                    <div class="alert alert-info text-center"
                         style="border-radius:10px;border:2px solid #aed6f1;background:#eaf4fb;margin:0;">
                        <i class="bi bi-info-circle-fill" style="color:#2980b9;"></i>
                        Set your filters and click <strong>Search</strong> to find products.
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
