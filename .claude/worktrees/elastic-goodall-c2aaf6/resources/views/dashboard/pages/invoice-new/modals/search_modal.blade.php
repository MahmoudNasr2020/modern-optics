{{-- resources/views/dashboard/pages/invoices/modals/search_modal.blade.php --}}

<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-funnel-fill"></i> Advanced Product Search
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">

                <!-- Search Filters -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" id="search_category_id">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Size</label>
                            <input type="text" class="form-control" id="search_size" placeholder="e.g., 52">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" id="search_color" placeholder="e.g., Black">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" id="search_type">
                                <option value="">All Types</option>
                                <option value="frame">Frame</option>
                                <option value="sunglasses">Sunglasses</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success-custom btn-block" id="executeSearchBtn">
                            <i class="bi bi-search"></i> Search Products
                        </button>
                    </div>
                </div>

                <hr>

                <!-- Results Table -->
                <div id="searchResultsContainer">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Use filters above and click Search to find products
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


