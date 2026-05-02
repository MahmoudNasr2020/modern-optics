<div id="searchModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" style="width: 90%; max-width: 1200px;">
        <!-- Modal content-->
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; padding: 20px 25px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; font-size: 30px;">&times;</button>
                <h3 style="margin: 0; font-size: 22px; font-weight: 700;">
                    <i class="bi bi-search"></i> Advanced Product Search
                </h3>
            </div>

            <div class="modal-body" style="padding: 25px;">
                <div class="row">
                    <!-- Left Panel - Filters -->
                    <div class="col-md-6">
                        <div style="background: white; border-radius: 12px; border: 2px solid #3498db; padding: 20px;">
                            <h4 style="color: #3498db; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e6ed;">
                                <i class="bi bi-funnel-fill"></i> Search Filters
                            </h4>

                            <!-- Category -->
                            <div class="form-group">
                                <label style="font-weight: 600; color: #555;">
                                    <i class="bi bi-tag"></i> Category
                                </label>
                                <div style="display: flex; gap: 8px;">
                                    <select name="category_id" id="category_id" class="form-control"
                                            style="border: 2px solid #e0e6ed; border-radius: 8px; flex: 1;">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn full-search"
                                            style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
                                                   color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <button class="btn"
                                            style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                                                   color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Advanced Filters Container -->
                            <div class="filters-container" style="display: none; margin-top: 20px;">
                                <!-- Brand -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-award"></i> Brand
                                    </label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" name="brand_input" id="brand_input" class="form-control brand_input" data-id=""
                                               style="border: 2px solid #e0e6ed; border-radius: 8px; flex: 1;" placeholder="Search brand...">
                                        <button class="btn filter-brands"
                                                style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button class="btn reset-brand"
                                                style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Model -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-diagram-3"></i> Model
                                    </label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" name="model_input" id="model_input" class="form-control model_input"
                                               style="border: 2px solid #e0e6ed; border-radius: 8px; flex: 1;" placeholder="Search model...">
                                        <button class="btn filter-models"
                                                style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button class="btn reset-model"
                                                style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Size -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-rulers"></i> Size
                                    </label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" name="size" id="size" class="form-control"
                                               style="border: 2px solid #e0e6ed; border-radius: 8px; flex: 1;" placeholder="Enter size...">
                                        <button class="btn filter-sizes"
                                                style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button class="btn reset-size"
                                                style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-palette"></i> Color
                                    </label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" name="color" id="color" class="form-control"
                                               style="border: 2px solid #e0e6ed; border-radius: 8px; flex: 1;" placeholder="Enter color...">
                                        <button class="btn filter-colors"
                                                style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-search"></i>
                                        </button>
                                        <button class="btn reset-color"
                                                style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                                                       color: white; border: none; padding: 10px 15px; border-radius: 8px;">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Segment -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-segmented-nav"></i> Segment
                                    </label>
                                    <select name="brandsegment" id="brandsegment_input" class="form-control"
                                            style="border: 2px solid #e0e6ed; border-radius: 8px;">
                                        <option value="">Select Segment</option>
                                        <option value="clear">CLEAR</option>
                                        <option value="color">COLOR</option>
                                        <option value="toric">TORIC</option>
                                    </select>
                                </div>

                                <!-- Power -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-lightning"></i> Power
                                    </label>
                                    <select name="power" id="power" class="form-control"
                                            style="border: 2px solid #e0e6ed; border-radius: 8px;">
                                        <option value="">Select Power</option>
                                        @for ($i = 0; $i <=20; $i += 0.25)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Sign -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-plus-slash-minus"></i> Sign
                                    </label>
                                    <select name="sign" id="sign" class="form-control"
                                            style="border: 2px solid #e0e6ed; border-radius: 8px;">
                                        <option value="">Select Sign</option>
                                        <option value="00">00</option>
                                        <option value="-">-</option>
                                        <option value="+">+</option>
                                    </select>
                                </div>

                                <!-- Type -->
                                <div class="form-group">
                                    <label style="font-weight: 600; color: #555;">
                                        <i class="bi bi-diagram-2"></i> Type
                                    </label>
                                    <select name="type" id="type" class="form-control"
                                            style="border: 2px solid #e0e6ed; border-radius: 8px;">
                                        <option value="">Select Type</option>
                                        <option value="folding metal">FOLDING METAL</option>
                                        <option value="folding plastic">FOLDING PLASTIC</option>
                                        <option value="metal frame">METAL FRAME</option>
                                        <option value="plastic frame">PLASTIC FRAME</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Results -->
                    <div class="col-md-6">
                        <div style="background: white; border-radius: 12px; border: 2px solid #27ae60; padding: 20px;">
                            <h4 style="color: #27ae60; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e0e6ed;">
                                <i class="bi bi-check2-square"></i> Pick Values
                            </h4>

                            <p class="no-items lead" style="display: none; text-align: center; color: #999;">
                                <i class="bi bi-inbox" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                                No Items Found!
                            </p>

                            <input type="text" class="search-picks form-control" name="" id="search-pics" placeholder="Filter values..."
                                   style="border: 2px solid #e0e6ed; border-radius: 8px; padding: 10px 15px; margin-bottom: 15px; text-transform: uppercase;">

                            <div class="table-responsive">
                                <table class="table picks-table table-bordered table-hover" style="opacity: 0; border-radius: 8px; overflow: hidden;">
                                    <thead style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white;">
                                    <tr>
                                        <th style="padding: 12px; border: none;">Name</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Results Table -->
                <div class="row" style="margin-top: 25px;">
                    <div class="panel-table">
                        <div class="col-md-12">
                            <div style="background: white; border-radius: 12px; border: 2px solid #9b59b6; padding: 20px;">
                                <h4 style="color: #9b59b6; font-weight: 700; margin-bottom: 20px;">
                                    <i class="bi bi-box-seam"></i> Products Results
                                </h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" style="border-radius: 8px; overflow: hidden;">
                                        <thead style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white;">
                                        <tr>
                                            <th style="padding: 15px; border: none; font-weight: 700;">ID</th>
                                            <th style="padding: 15px; border: none; font-weight: 700;">Description</th>
                                            <th style="padding: 15px; border: none; font-weight: 700;">Tax</th>
                                            <th style="padding: 15px; border: none; font-weight: 700;">Retail Price</th>
                                            <th style="padding: 15px; border: none; font-weight: 700;">Stock</th>
                                            <th style="padding: 15px; border: none; font-weight: 700;">Select</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div>
                                    <h3 class="text-center no-items" style="display: none; color: #999; padding: 40px 0;">
                                        <i class="bi bi-inbox" style="font-size: 60px; display: block; margin-bottom: 15px;"></i>
                                        No Items Found!
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .picks-table tbody tr {
        cursor: pointer;
        transition: all 0.3s;
    }

    .picks-table tbody tr:hover {
        background: linear-gradient(135deg, #e8f8f5 0%, #fff 100%);
        transform: translateX(3px);
    }

    .panel-table tbody tr {
        cursor: pointer;
        transition: all 0.3s;
    }

    .panel-table tbody tr:hover {
        background: linear-gradient(135deg, #f4ecf7 0%, #fff 100%);
        transform: translateX(3px);
    }

    .search-picks {
        text-transform: uppercase;
    }
</style>
