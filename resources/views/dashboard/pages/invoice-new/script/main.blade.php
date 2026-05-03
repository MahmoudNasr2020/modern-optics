<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    $(document).on('click', '#openDiscountModalBtn', function(e) {
        e.stopPropagation();
        $('#discountModal').modal('show');
    });

    // Toggle Lenses Section
    function toggleLensesSection() {
        const section = document.getElementById('lensesSection');
        const icon = document.getElementById('lensesToggleIcon');

        if (section.classList.contains('lenses-collapsed')) {
            section.classList.remove('lenses-collapsed');
            section.classList.add('lenses-expanded');
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        } else {
            section.classList.remove('lenses-expanded');
            section.classList.add('lenses-collapsed');
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        }
    }

    function toggleDiscountSection() {
        const section = document.getElementById('discountSection');
        const icon    = document.getElementById('discountToggleIcon');

        if (section.classList.contains('lenses-collapsed')) {
            section.classList.remove('lenses-collapsed');
            section.classList.add('lenses-expanded');
            icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
        } else {
            section.classList.remove('lenses-expanded');
            section.classList.add('lenses-collapsed');
            icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
        }
    }
</script>

<!-- Include JavaScript Files -->
@include('dashboard.pages.invoice-new.script.invoice-main')
@include('dashboard.pages.invoice-new.script.invoice-discounts')
@include('dashboard.pages.invoice-new.script.invoice-submit')

<script>


    // Search functionality
    $('#customer-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#customers-table-body tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Select customer
    $('.select-customer-btn').on('click', function() {
        var customerId = $(this).data('id');
        var customerName = $(this).data('name');

        // Redirect to invoice page for this customer
        window.location.href = '/dashboard/invoices/create/' + customerId;
    });


</script>

<script>

    // Search functionality
    $('#doctor-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#doctors-table-body tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Select doctor
    $('.select-doctor-btn').on('click', function() {
        var doctorCode = $(this).data('code');
        var doctorName = $(this).data('name');

        // Update form fields
        $('#doctor_id').val(doctorCode);
        $('#doctor_id_display').val(doctorCode);
        $('#doctor_name').val(doctorName);

        // Store in session
        $.ajax({
            url: '/dashboard/session/store-doctor',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                doctor_id: doctorCode,
                doctor_name: doctorName
            },
            success: function(response) {
                console.log('Doctor stored in session');
            }
        });

        // Close modal
        $('#doctorModal').modal('hide');

        // Success message
        Swal.fire({
            icon: 'success',
            title: 'Doctor Selected!',
            text: doctorName,
            timer: 1500,
            showConfirmButton: false
        });
    });

</script>

<script>
    $(document).ready(function() {

        // Execute search
        /* $('#executeSearchBtn').on('click', function() {
             const branch_id = $('#branch_id').val();

             if (!branch_id) {
                 Swal.fire({
                     icon: 'warning',
                     title: 'Branch Required',
                     text: 'Please select a branch first!',
                     confirmButtonColor: '#3498db'
                 });
                 return;
             }

             const filters = {
                 branch_id: branch_id,
                 category_id: $('#search_category_id').val(),
                 size: $('#search_size').val(),
                 color: $('#search_color').val(),
                 type: $('#search_type').val()
             };

             // Show loading
             $('#searchResultsContainer').html(`
         <div class="text-center" style="padding: 40px;">
             <div class="spinner-border text-primary" role="status"></div>
             <p style="margin-top: 15px;">Searching products...</p>
         </div>
     `);

             // AJAX search
             $.ajax({
                 url: '/dashboard/invoices/products/search',
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: filters,
                    success: function(response) {
                        if (response.success) {
                            displaySearchResults(response.products);
                        } else {
                            $('#searchResultsContainer').html(`
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> ${response.message}
                        </div>
                    `);
                        }
                    },
                    error: function(xhr) {
                        $('#searchResultsContainer').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i> Search failed. Please try again.
                    </div>
                `);
                    }
                });
            });

            // Display search results
            function displaySearchResults(products) {
                if (products.length === 0) {
                    $('#searchResultsContainer').html(`
                <div class="alert alert-info text-center">
                    <i class="bi bi-inbox"></i> No products found matching your criteria
                </div>
            `);
                    return;
                }

                let tableHTML = `
            <div class="table-responsive">
                <table class="table table-hover items-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Description</th>
                            <th>Tax</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

                products.forEach(product => {
                    const stockBadge = product.stock > 10 ? 'badge-success' :
                        product.stock > 0 ? 'badge-warning' : 'badge-danger';

                    tableHTML += `
                <tr>
                    <td><strong style="color: #667eea;">${product.product_id}</strong></td>
                    <td>${product.description}</td>
                    <td>${product.tax}%</td>
                    <td><strong>${parseFloat(product.retail_price).toFixed(2)} QAR</strong></td>
                    <td><span class="badge ${stockBadge}">${product.available_quantity}</span></td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm select-product-from-search"
                                data-product-id="${product.product_id}"
                                ${product.available_quantity <= 0 ? 'disabled' : ''}>
                            <i class="bi bi-check-circle"></i> Select
                        </button>
                    </td>
                </tr>
            `;
                });

                tableHTML += `
                    </tbody>
                </table>
            </div>
        `;

                $('#searchResultsContainer').html(tableHTML);

                // Bind select events
                $('.select-product-from-search').on('click', function() {
                    const product_id = $(this).data('product-id');

                    // Fill main form
                    $('#product_id_input').val(product_id);

                    // Close modal
                    $('#searchModal').modal('hide');

                    // Trigger add
                    $('#addProductBtn').click();
                });
            }*/
    });
</script>

<script>
    // Lenses Management
    const LensesManager = {
        selectedEyeTest: null,
        selectedLenses: [],

        init() {
            this.setupEvents();
        },

        setupEvents() {
            $('#loadEyeTestsBtn').on('click', () => this.loadEyeTests());
            $('#filterLensesBtn').on('click', () => this.filterLenses());
            $('#addSelectedLensesBtn').on('click', () => this.addLensesToInvoice());
        },

        /* ================= LOAD EYE TESTS ================= */
        loadEyeTests() {
            const customer_id = $('input[name="customer_id"]').val();

            showLoading();

            $.ajax({
                url: `/dashboard/invoices/lenses/eye-tests/${customer_id}`,
                type: 'GET',

                success: (data) => {
                    hideLoading();

                    if (data.success && data.eye_tests.length > 0) {
                        this.displayEyeTests(data.eye_tests);
                    } else {
                        $('#eyeTestsContainer').html(`
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No eye tests found for this customer.
                            <a href="/dashboard/customers/${customer_id}/add-eye-test" target="_blank">Add one now</a>
                        </div>
                    `);
                    }
                },

                error: (error) => {
                    hideLoading();
                    console.error('Load eye tests error:', error);
                }
            });
        },

        /* ================= DISPLAY EYE TESTS ================= */
        displayEyeTests(tests) {
            let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background: #667eea; color: white;">
                        <tr>
                            <th>Select</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th colspan="4">Right Eye</th>
                            <th colspan="4">Left Eye</th>
                        </tr>
                        <tr style="background: #764ba2; color: white;">
                            <th></th><th></th><th></th>
                            <th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th>
                            <th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

            tests.forEach(test => {
                html += `
                <tr>
                    <td>
                        <input type="radio" name="selected_eye_test" value="${test.id}"
                               data-test='${JSON.stringify(test)}'>
                    </td>
                    <td>${test.doctor_name}</td>
                    <td>${test.visit_date}</td>
                    <td>${test.sph_right_sign} ${test.sph_right_value || '-'}</td>
                    <td>${test.cyl_right_sign} ${test.cyl_right_value || '-'}</td>
                    <td>${test.axis_right || '-'}</td>
                    <td>${test.addition_right || '-'}</td>
                    <td>${test.sph_left_sign} ${test.sph_left_value || '-'}</td>
                    <td>${test.cyl_left_sign} ${test.cyl_left_value || '-'}</td>
                    <td>${test.axis_left || '-'}</td>
                    <td>${test.addition_left || '-'}</td>
                </tr>
            `;
            });

            html += `</tbody></table></div>`;
            $('#eyeTestsContainer').html(html);

            $('input[name="selected_eye_test"]').on('change', (e) => {
                this.selectedEyeTest = JSON.parse($(e.target).attr('data-test'));
                $('#lensFiltersSection').slideDown();

                Swal.fire({
                    icon: 'success',
                    title: 'Eye Test Selected',
                    timer: 1000,
                    showConfirmButton: false
                });
            });
        },

        /* ================= FILTER LENSES ================= */
        filterLenses() {
            const branch_id = $('#branch_id').val();

            if (!branch_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch first',
                    confirmButtonColor: '#f39c12'
                });
                return;
            }

            const filters = {
                branch_id:         branch_id,
                frame_type:        $('#lens_frame_type').val(),
                lense_type:        $('#lens_type').val(),
                life_style:        $('#lens_life_style').val(),       // ← جديد
                customer_activity: $('#lens_customer_activity').val(), // ← جديد
                lense_tech:        $('#lens_lense_tech').val(),        // ← جديد
                brand:             $('#lens_brand').val(),
                production:        $('#lens_production').val(),        // ← جديد
                index:             $('#lens_index').val(),
                description:       $('#lens_description').val()        // ← جديد
            };

            showLoading();

            $.ajax({
                url: '/dashboard/invoices/lenses/filter',
                type: 'POST',
                data: JSON.stringify(filters),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },

                success: (data) => {
                    hideLoading();

                    if (data.success && data.lenses.length > 0) {
                        this.displayLenses(data.lenses);
                        $('#lensResultsDivider').show();
                        $('#lensResultsSection').slideDown();
                    } else {
                        $('#lensesTableContainer').html('');
                        $('#lensResultsSection').hide();
                        Swal.fire({
                            icon: 'info',
                            title: 'No Lenses Found',
                            text: 'Try adjusting your filters',
                            confirmButtonColor: '#3498db'
                        });
                    }
                },

                error: (error) => {
                    hideLoading();
                    console.error('Filter lenses error:', error);
                }
            });
        },

        /* ================= DISPLAY LENSES ================= */
        displayLenses(lenses) {
            let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background: #667eea; color: white;">
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Index</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Left Eye</th>
                            <th>Right Eye</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

            lenses.forEach(lens => {
                const stockBadge = lens.stock > 5 ? 'badge-success' :
                    lens.stock > 0 ? 'badge-warning' : 'badge-danger';

                html += `
                <tr>
                    <td><strong style="color: #667eea;">${lens.product_id}</strong></td>
                    <td>${lens.description}</td>
                    <td>${lens.index}</td>
                    <td><strong>${parseFloat(lens.retail_price).toFixed(2)} QAR</strong></td>
                    <td><span class="badge ${stockBadge}">${lens.stock}</span></td>
                    <td style="text-align:center;">
                        <input type="checkbox" class="lens-select-left"
                               value="${lens.product_id}">
                    </td>
                    <td style="text-align:center;">
                        <input type="checkbox" class="lens-select-right"
                               value="${lens.product_id}">
                    </td>
                </tr>
            `;
            });

            html += `</tbody></table></div>`;
            $('#lensesTableContainer').html(html);
        },

        /* ================= ADD LENSES TO INVOICE ================= */
        addLensesToInvoice() {
            const branch_id = $('#branch_id').val();
            const lenses = [];

            $('.lens-select-left:checked').each(function () {
                lenses.push({ product_id: $(this).val(), direction: 'L' });
            });

            $('.lens-select-right:checked').each(function () {
                lenses.push({ product_id: $(this).val(), direction: 'R' });
            });

            if (lenses.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Lenses Selected',
                    text: 'Please select at least one lens',
                    confirmButtonColor: '#f39c12'
                });
                return;
            }

            showLoading();

            $.ajax({
                url: '/dashboard/invoices/lenses/add',
                type: 'POST',
                data: JSON.stringify({
                    lenses: lenses,
                    branch_id: branch_id,
                    eye_test_id: this.selectedEyeTest ? this.selectedEyeTest.id : null
                }),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },

                success: (data) => {
                    hideLoading();

                    if (data.success) {
                        // ✅ FIX: Update invoiceState with new draft from server
                        invoiceState = data.draft;

                        // ✅ FIX: Re-render table with updated state
                        renderItemsTable();

                        // Clear selections
                        $('.lens-select-left, .lens-select-right').prop('checked', false);

                        // Collapse lenses section
                        toggleLensesSection();

                        Swal.fire({
                            icon: 'success',
                            title: 'Lenses Added!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                },

                error: () => {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to add lenses',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        }
    };

    $(document).ready(function () {
        LensesManager.init();
    });
</script>

<script>
    (function ($) {
        'use strict';


        var CSRF        = "{{ csrf_token() }}";
        var SEARCH_URL  = "{{ route('dashboard.invoice.products.search') }}";
        var BRANDS_URL  = "{{ route('dashboard.filter-brands-by-category-id') }}";
        var MODELS_URL  = "{{ route('dashboard.filter-models-by-category-and-brand-id') }}";

        /* ── Initial dropdown snapshots (for reset) ────────── */
        var initialBrandsHtml = $('#sm_brand_id').html();
        var initialModelsHtml = $('#sm_model_id').html();

        /* ── Category-aware field groups ─────────────────────
         *  cat 4  = Contact Lens  → CL fields (segment/lense_use/sign/power)
         *  cat 1,2 = Frames/Sun   → standard fields (size/color/type)
         *  cat 6  = Reading Glass → glasses fields (power/type)
         *  cat 3,7,8,10 = others  → brand only, no row-2 fields
         *  ""     = All           → standard fields (default)
         * ─────────────────────────────────────────────────── */
        var SM_CAT_CL       = 4;
        var SM_CAT_STD      = [0, 1, 2];   /* 0 = "All" */
        var SM_CAT_GLASSES  = [6];
        var SM_CAT_MODEL    = [0, 1, 2, 4]; /* show model col for these */

        function smApplyCategoryFields(catId) {
            catId = parseInt(catId) || 0;

            /* ── Show correct row-2 ── */
            if (catId === SM_CAT_CL) {
                $('#sm_row_standard').hide();
                $('#sm_row_cl').show();
                $('#sm_row_glasses').hide();
            } else if ($.inArray(catId, SM_CAT_GLASSES) !== -1) {
                $('#sm_row_standard').hide();
                $('#sm_row_cl').hide();
                $('#sm_row_glasses').show();
            } else if ($.inArray(catId, SM_CAT_STD) !== -1) {
                $('#sm_row_standard').show();
                $('#sm_row_cl').hide();
                $('#sm_row_glasses').hide();
            } else {
                /* others — brand only, no row-2 */
                $('#sm_row_standard').hide();
                $('#sm_row_cl').hide();
                $('#sm_row_glasses').hide();
            }

            /* ── Show/hide model column ── */
            if ($.inArray(catId, SM_CAT_MODEL) !== -1) {
                $('#sm_model_col').show();
            } else {
                $('#sm_model_col').hide();
                $('#sm_model_id').val('');
            }

            /* ── Rename model label for CL ── */
            if (catId === SM_CAT_CL) {
                $('#sm_model_label_text').text('Product Name');
            } else {
                $('#sm_model_label_text').text('Model');
            }
        }

        /* Init on page load — default = "All Categories" → standard row */
        smApplyCategoryFields($('#sm_category_id').val());

        /* ── Category change → reload brands & models ───────── */
        $(document).on('change', '#sm_category_id', function () {
            var catId = $(this).val();

            /* reset brand & model dropdowns */
            $('#sm_brand_id').html(initialBrandsHtml);
            $('#sm_model_id').html(initialModelsHtml);

            /* apply field group visibility */
            smApplyCategoryFields(catId);

            if (!catId) { return; }

            /* reload brands for this category */
            $.ajax({
                url: BRANDS_URL, type: 'POST',
                data: { category_id: catId },
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (brands) {
                    var html = '<option value="">All Brands</option>';
                    $.each(brands, function (i, b) {
                        html += '<option value="' + b.id + '">' + b.brand_name + '</option>';
                    });
                    $('#sm_brand_id').html(html);
                }
            });

            /* reload models for this category */
            var modelLabel = parseInt(catId) === SM_CAT_CL ? 'All Product Names' : 'All Models';
            $.ajax({
                url: MODELS_URL, type: 'POST',
                data: { category_id: catId, brand_id: '' },
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (models) {
                    var html = '<option value="">' + modelLabel + '</option>';
                    $.each(models, function (i, m) {
                        html += '<option value="' + m.id + '">' + m.model_id + '</option>';
                    });
                    $('#sm_model_id').html(html);
                }
            });
        });

        /* ── Brand change → reload models ───────────────────── */
        $(document).on('change', '#sm_brand_id', function () {
            var brandId = $(this).val();
            var catId   = $('#sm_category_id').val();

            if (!brandId) {
                /* no brand → trigger category reload to reset models */
                $('#sm_category_id').trigger('change');
                return;
            }

            $('#sm_model_spinner').show();

            $.ajax({
                url: MODELS_URL, type: 'POST',
                data: { brand_id: brandId, category_id: catId },
                headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (models) {
                    var modelLabel = parseInt(catId) === SM_CAT_CL ? 'All Product Names' : 'All Models';
                    var html = '<option value="">' + modelLabel + '</option>';
                    $.each(models, function (i, m) {
                        html += '<option value="' + m.id + '">' + m.model_id + '</option>';
                    });
                    $('#sm_model_id').html(html);
                },
                complete: function () { $('#sm_model_spinner').hide(); }
            });
        });

        /* ── Reset ───────────────────────────────────────────── */
        $(document).on('click', '#sm_resetSearchBtn', function () {
            $('#sm_category_id').val('');
            $('#sm_brand_id').html(initialBrandsHtml);
            $('#sm_model_id').html(initialModelsHtml);

            /* standard fields */
            $('#sm_search_text, #sm_size, #sm_color, #sm_power, #sm_cl_power').val('');
            $('#sm_type, #sm_glasses_type').val('');

            /* CL fields */
            $('#sm_brand_segment, #sm_lense_use, #sm_sign').val('');

            /* restore default field group */
            smApplyCategoryFields('');

            $('#sm_searchResultsContainer').html(
                '<div class="alert alert-info text-center" ' +
                'style="border-radius:10px;border:2px solid #aed6f1;background:#eaf4fb;margin:0;">' +
                '<i class="bi bi-info-circle-fill" style="color:#2980b9;"></i> ' +
                'Set your filters and click <strong>Search</strong> to find products.</div>'
            );
        });

        /* ── Execute Search ──────────────────────────────────── */
        $(document).on('click', '#sm_executeSearchBtn', function () {
            var branch_id = $('#branch_id').val();

            if (!branch_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Branch Required',
                    text: 'Please select a branch first!',
                    confirmButtonColor: '#f39c12'
                });
                return;
            }

            var catId = parseInt($('#sm_category_id').val()) || 0;

            /* always-sent fields */
            var filters = {
                branch_id:   branch_id,
                category_id: $('#sm_category_id').val(),
                brand_id:    $('#sm_brand_id').val(),
                search:      $('#sm_search_text').val()
            };

            /* model_id — only for cats that show the model column */
            if ($.inArray(catId, SM_CAT_MODEL) !== -1) {
                filters.model_id = $('#sm_model_id').val();
            }

            /* standard fields (Frames / Sunglasses / All) */
            if (catId === SM_CAT_CL) {
                /* Contact Lens specific */
                filters.brand_segment = $('#sm_brand_segment').val();
                filters.lense_use     = $('#sm_lense_use').val();
                filters.sign          = $('#sm_sign').val();
                filters.power         = $('#sm_cl_power').val();
            } else if ($.inArray(catId, SM_CAT_GLASSES) !== -1) {
                /* Reading Glasses */
                filters.power = $('#sm_power').val();
                filters.type  = $('#sm_glasses_type').val();
            } else {
                /* Frames / Sunglasses / All */
                filters.size  = $('#sm_size').val();
                filters.color = $('#sm_color').val();
                filters.type  = $('#sm_type').val();
            }

            /* loading state */
            $('#sm_executeSearchBtn').prop('disabled', true)
                .html('<i class="bi bi-hourglass-split"></i> Searching...');

            $('#sm_searchResultsContainer').html(
                '<div class="text-center" style="padding:40px;">' +
                '<div class="spinner-border" style="color:#f39c12;" role="status"></div>' +
                '<p style="margin-top:12px;color:#888;">Searching products…</p></div>'
            );

            $.ajax({
                url:     SEARCH_URL,
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                data:    filters,

                success: function (res) {
                    if (res.success) {
                        renderResults(res.products, catId);
                    } else {
                        $('#sm_searchResultsContainer').html(
                            '<div class="alert alert-warning">' +
                            '<i class="bi bi-exclamation-triangle"></i> ' +
                            (res.message || 'No products found.') + '</div>'
                        );
                    }
                },

                error: function (xhr) {
                    var msg = 'Search failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#sm_searchResultsContainer').html(
                        '<div class="alert alert-danger">' +
                        '<i class="bi bi-x-circle"></i> ' + msg + '</div>'
                    );
                },

                complete: function () {
                    $('#sm_executeSearchBtn').prop('disabled', false)
                        .html('<i class="bi bi-search"></i> Search');
                }
            });
        });

        /* ── Enter key triggers search ───────────────────────── */
        $(document).on('keypress',
            '#sm_search_text, #sm_size, #sm_color, #sm_power, #sm_cl_power',
            function (e) {
                if (e.which === 13) { $('#sm_executeSearchBtn').trigger('click'); }
            }
        );

        /* ── Render results ──────────────────────────────────── */
        function renderResults(products, catId) {
            catId = catId || 0;
            var isCL = (catId === SM_CAT_CL);

            if (!products || products.length === 0) {
                $('#sm_searchResultsContainer').html(
                    '<div class="alert alert-info text-center" style="border-radius:10px;">' +
                    '<i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;"></i>' +
                    '<strong>No products found</strong><br>' +
                    '<small>Try adjusting your filters</small></div>'
                );
                return;
            }

            /* ── Table header — differs for CL vs regular ── */
            var thStyle = 'color:white;padding:10px 12px;white-space:nowrap;';
            var headBg  = isCL
                ? 'background:linear-gradient(135deg,#3498db,#2980b9);'
                : 'background:linear-gradient(135deg,#f39c12,#e67e22);';

            var html =
                '<div style="margin-bottom:10px;color:#555;font-size:12px;font-weight:700;">' +
                '<i class="bi bi-check-circle-fill" style="color:#27ae60;"></i> ' +
                products.length + ' product(s) found</div>' +
                '<div class="table-responsive">' +
                '<table class="table table-hover" style="font-size:12px;border-collapse:collapse;">' +
                '<thead style="' + headBg + '">' +
                '<tr>' +
                '<th style="' + thStyle + '">Product ID</th>' +
                '<th style="' + thStyle + '">Description</th>' +
                '<th style="' + thStyle + '">Brand</th>';

            if (isCL) {
                html +=
                    '<th style="' + thStyle + '">Segment</th>' +
                    '<th style="' + thStyle + '">Type</th>' +
                    '<th style="' + thStyle + '">Sign</th>' +
                    '<th style="' + thStyle + '">Power</th>';
            } else {
                html +=
                    '<th style="' + thStyle + '">Model</th>' +
                    '<th style="' + thStyle + '">Size</th>' +
                    '<th style="' + thStyle + '">Color</th>';
            }

            html +=
                '<th style="' + thStyle + 'text-align:right;">Price</th>' +
                '<th style="' + thStyle + 'text-align:center;">Stock</th>' +
                '<th style="' + thStyle + 'text-align:center;">Action</th>' +
                '</tr>' +
                '</thead><tbody>';

            $.each(products, function (i, p) {
                var qty      = parseInt(p.available_quantity) || 0;
                var stkBg    = qty > 10 ? '#d5f5e3' : qty > 0 ? '#fdebd0' : '#fadbd8';
                var stkColor = qty > 10 ? '#1e8449' : qty > 0 ? '#d35400' : '#c0392b';
                var disabled = qty <= 0 ? 'disabled' : '';
                var selColor = isCL ? '#3498db,#2980b9' : '#27ae60,#1e8449';

                html +=
                    '<tr style="border-bottom:1px solid #f0f2f5;">' +
                    '<td style="padding:9px 12px;">' +
                    '<strong style="color:' + (isCL ? '#3498db' : '#e67e22') + ';">'
                    + (p.product_id || '—') + '</strong></td>' +
                    '<td style="padding:9px 12px;">' + (p.description || '—') + '</td>' +
                    '<td style="padding:9px 12px;color:#888;">' + (p.brand_name || p.brand_id || '—') + '</td>';

                if (isCL) {
                    html +=
                        '<td style="padding:9px 12px;">'
                        + '<span style="background:#eaf4fb;color:#2980b9;border-radius:10px;' +
                        'padding:2px 8px;font-size:11px;font-weight:600;">'
                        + (p.brand_segment || '—') + '</span></td>' +
                        '<td style="padding:9px 12px;color:#888;">' + (p.lense_use || '—') + '</td>' +
                        '<td style="padding:9px 12px;font-weight:700;color:#e74c3c;">' + (p.sign || '—') + '</td>' +
                        '<td style="padding:9px 12px;font-weight:700;">' + (p.power || '—') + '</td>';
                } else {
                    html +=
                        '<td style="padding:9px 12px;color:#888;">' + (p.model_name || p.model_id || '—') + '</td>' +
                        '<td style="padding:9px 12px;">' + (p.size  || '—') + '</td>' +
                        '<td style="padding:9px 12px;">' + (p.color || '—') + '</td>';
                }

                html +=
                    '<td style="padding:9px 12px;text-align:right;font-weight:700;">' +
                    parseFloat(p.retail_price || 0).toFixed(2) + ' QAR</td>' +
                    '<td style="padding:9px 12px;text-align:center;">' +
                    '<span style="background:' + stkBg + ';color:' + stkColor + ';' +
                    'border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;">' +
                    qty + '</span></td>' +
                    '<td style="padding:9px 12px;text-align:center;">' +
                    '<button type="button" class="sm_select_product" ' +
                    'data-pid="' + p.product_id + '" ' + disabled + ' ' +
                    'style="background:linear-gradient(135deg,' + selColor + ');color:white;' +
                    'border:none;border-radius:6px;padding:5px 14px;font-size:11px;' +
                    'font-weight:700;cursor:pointer;">' +
                    '<i class="bi bi-plus-circle"></i> Select</button></td>' +
                    '</tr>';
            });

            html += '</tbody></table></div>';
            $('#sm_searchResultsContainer').html(html);
        }

        /* ── Select product from results ─────────────────── */
        $(document).on('click', '.sm_select_product', function () {
            var pid = $(this).data('pid');
            $('#product_id_input').val(pid);
            $('#searchModal').modal('hide');
            $('#addProductBtn').trigger('click');
        });

    }(jQuery));
</script>
