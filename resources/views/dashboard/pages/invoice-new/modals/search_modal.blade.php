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

                <!-- FILTER ROW 1 -->
                <div class="row">
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="font-weight:700;font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.5px;">
                                <i class="bi bi-eyeglasses" style="color:#f39c12;"></i> Model
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

                <!-- FILTER ROW 2 -->
                <div class="row" style="margin-top:-8px;">
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="visibility:hidden;font-size:11px;">Search</label>
                            <div style="display:flex;gap:6px;">
                                <button type="button" id="sm_executeSearchBtn"
                                        style="flex:1;background:linear-gradient(135deg,#f39c12,#e67e22);
                                               color:white;border:none;border-radius:8px;
                                               height:40px;font-weight:700;font-size:14px;cursor:pointer;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <button type="button" id="sm_resetSearchBtn" title="Reset filters"
                                        style="background:#f0f2f5;color:#555;border:none;
                                               border-radius:8px;height:40px;padding:0 14px;
                                               font-size:16px;cursor:pointer;">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div id="sm_searchResultsContainer">
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



