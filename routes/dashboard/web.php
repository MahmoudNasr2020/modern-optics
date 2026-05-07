
<?php


use App\Facades\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth', /*'active.user'*/]], function () {

    // Logout
    Route::get('logout', 'DashboardController@logout')->name('logout');

    //Route::group(['middleware'=>'system.maintenance'],function(){

    Route::get('home',    'DashboardController@index')->name('index');
    Route::get('refresh', 'DashboardController@refresh')->name('refresh');

    // ========== AI ASSISTANT ==========
    Route::group(['prefix' => 'assistant', 'as' => 'assistant.'], function () {
        Route::get('/', 'AssistantController@index')->name('index');
        Route::post('/query', 'AssistantController@query')->name('query');
    });

        // ========== ROLES MANAGEMENT ==========
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', 'RoleController@index')->name('index');
            Route::get('/create', 'RoleController@create')->name('create');
            Route::post('/', 'RoleController@store')->name('store');
            Route::get('/{role}', 'RoleController@show')->name('show');
            Route::get('/{role}/edit', 'RoleController@edit')->name('edit');
            Route::put('/{role}', 'RoleController@update')->name('update');
            Route::delete('/{role}', 'RoleController@destroy')->name('destroy');

            // Permissions
            Route::get('/{role}/permissions', 'RoleController@editPermissions')->name('permissions.edit');
            Route::put('/{role}/permissions', 'RoleController@updatePermissions')->name('permissions.update');
        });

    // Users Routes
    Route::get('all-users', 'UsersController@index')->name('get-all-users');
    Route::get('add-user', 'UsersController@getAddUser')->name('get-add-user');
    Route::post('add-user', 'UsersController@postAddUser')->name('post-add-user');

    Route::get('update-user/{id}', 'UsersController@getUpdateUser')->name('get-update-user');
    Route::post('update-user/{id}', 'UsersController@postUpdateUser')->name('post-update-user');

    Route::get('delete-admin/{id}' ,'UsersController@deleteAdmin')->name('delete-admin');

        Route::post('users-toggle-active/{id}', 'UsersController@toggleActive')
            ->name('users.toggle-active');



    //doctors routes
    Route::get('show-all-doctors','DoctorsController@index')->name('get-all-doctors');
    Route::get('get-doctor','DoctorsController@getDoctor')->name('get-add-doctor');
    Route::post('add-doctor','DoctorsController@addDoctor')->name('post-add-doctor');
    Route::get('show-doctor/{id}','DoctorsController@showDoctor')->name('show-doctor');
    Route::get('update-doctor/{id}' ,'DoctorsController@getUpdateDoctor')->name('get-update-doctor');
    Route::post('update-doctor/{id}' ,'DoctorsController@postUpdateDoctor')->name('post-update-doctor');
    Route::get('delete-doctor/{id}' ,'DoctorsController@deleteDoctor')->name('delete-doctor');
    Route::get('show-doctor-details' ,'DoctorsController@getDoctorDetails')->name('get-doctor-details');
    Route::post('/set-doctor-session', 'DoctorsController@setDoctorSession')->name('set-doctor-session');


        // Customers Routes
    Route::get('all-customers' ,'CustomerController@index')->name('get-all-customers');
    Route::get('add-customer' ,'CustomerController@getAddCustomer')->name('get-add-customer');
    Route::post('add-customer' ,'CustomerController@postAddCustomer')->name('post-add-customer');
    Route::get('update-customer/{id}' ,'CustomerController@getUpdateCustomer')->name('get-update-customer');
    Route::post('update-customer/{id}' ,'CustomerController@postUpdateCustomer')->name('post-update-customer');
    Route::get('delete-customer/{id}' ,'CustomerController@deleteCustomer')->name('delete-customer');
    Route::get('show-customer/{id}' ,'CustomerController@showCustomer')->name('show-customer');
    Route::get('show-customer-invoice/{id}' ,'CustomerController@showCustomerInvoice')->name('show-customer-invoice');
    Route::get('show-customer-details' ,'CustomerController@getCustomerDetails')->name('get-customer-details');
    Route::get('store-data-in-session' ,'CustomerController@storeDataInSession')->name('store-data-in-session');
    Route::get('store-many-data-in-session' ,'CustomerController@storeManyDataInSession')->name('store-many-data-in-session');
    Route::get('delete-data-from-session' ,'CustomerController@deleteDataFromSession')->name('delete-data-from-session');
    Route::get('store-total-in-session' ,'CustomerController@storeTotalInSession')->name('store-total-in-session');
    Route::get('discount-get-type' ,'CustomerController@discountGetType')->name('discount-get-type');
    Route::get('discount-get-single-type' ,'CustomerController@discountGetSingleType')->name('discount-get-single-type');


    //customers oprations
    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('/search-statement', 'CustomerStatementController@searchStatement')->name('search-statement');
        Route::get('statement', 'CustomerStatementController@statement')->name('statement');
        Route::get('/{customer_id}/statement/print', 'CustomerStatementController@statementPrint')->name('statement.print');
        //Route::post('/{customer_id}/statement/email', 'CustomerController@statementEmail')->name('statement.email');
        Route::get('/{customer_id}/statement/pdf', 'CustomerStatementController@statementPdf')->name('statement.pdf');
    });

        Route::group(['prefix' => 'expenses', 'as' => 'expenses.'], function () {

            // Categories
            Route::get('/categories', 'ExpenseController@categories')->name('categories');
            Route::post('/categories/store', 'ExpenseController@storeCategory')->name('categories.store');
            Route::post('/categories/{id}/update', 'ExpenseController@updateCategory')->name('categories.update');
            Route::get('/categories/{id}/delete', 'ExpenseController@deleteCategory')->name('categories.delete');

            // Expenses
            Route::get('/', 'ExpenseController@index')->name('index');
            Route::get('/create', 'ExpenseController@create')->name('create');
            Route::post('/store', 'ExpenseController@store')->name('store');
            Route::get('/{id}/edit', 'ExpenseController@edit')->name('edit');
            Route::post('/{id}/update', 'ExpenseController@update')->name('update');
            Route::get('/{id}/delete', 'ExpenseController@delete')->name('delete');
            Route::get('/salaries-preview', 'ExpenseController@salariesPreview')->name('salaries-preview');


            // Reports
            Route::get('/report', 'ExpenseController@report')->name('report');
            Route::get('/report/print', 'ExpenseController@reportPrint')->name('report.print');

            // Approval
            Route::post('/{id}/approve', 'ExpenseController@approve')->name('approve');
            Route::post('/{id}/reject', 'ExpenseController@reject')->name('reject');
        });

    // Eye-Test-Customer Route
    Route::get('customer-hist' ,'CustomerController@cusromerHistory')->name('customer-history');
    Route::get('customer-eye' ,'EyeTestController@cusromerEyeTest')->name('customer-eye');
    Route::get('print-eye-test/{id}' ,'EyeTestController@printEyeTest')->name('print-eye-test');
    Route::delete('delete-eye-test/{id}' ,'EyeTestController@deleteEyeTest')->name('delete-eye-test');
    Route::post('delete-eye-test/{id}' ,'EyeTestController@deleteEyeTest')->name('delete-eye-test-post'); // AJAX alias
    Route::get('/cancel-eye-test/{id}', 'EyeTestController@cancelEyeTest')->name('cancel-eye-test');
    Route::get('view-lenses/{id}' ,'EyeTestController@getLensesView')->name('get-lenses-view');
    Route::get('add-eye-test/{id}' ,'EyeTestController@addNewEyTest')->name('add-eye-test');
    Route::post('add-eye-test/{id}' ,'EyeTestController@StoreNewEyTest')->name('new-eye-test');
    Route::get('show-eye-test/{id}', 'EyeTestController@showEyTest')->name('get-customer-eye');
    Route::post('add-lens' ,'EyeTestController@StoreNewLens')->name('new-lens');


    // Invoice Route
    Route::post('save-invoice' ,'InvoiceController@saveInvoice')->name('save-invoice');
    Route::post('save-only-invoice' ,'InvoiceController@saveOnlyInvoice')->name('save-only-invoice');
    Route::get('show-invoice/{invoice_id}', 'InvoiceController@showInvoice')->name('show-invoice-items');
    Route::get('edit-invoice/{invoice_code}' ,'InvoiceController@editInvoice')->name('edit-invoice');
    Route::post('update-invoice/{invoice_code}' ,'InvoiceController@updateInvoice')->name('update-invoice');
    Route::get('delete-session-invoices' ,'InvoiceController@deleteSessionInvoices')->name('delete-session-invoices');
    Route::get('delete-invoice/{invoice_code}' ,'InvoiceController@deleteInvoice')->name('delete-invoice');
    Route::post('add-payment-to-pending-invoice/' ,'InvoiceController@addPaymentToPendingInvoice')->name('add-payment-to-pending-invoice');
    Route::post('add-payment-to-invoice/{invoice_code}' ,'InvoiceController@addPaymentToInvoice')->name('add-payment-to-invoice');
    Route::post('update-status-invoice/{id}' ,'InvoiceController@updateStatus')->name('update-status-invoice');

    Route::get('all-invoices', 'InvoiceController@getAllInvoices')->name('all-invoices');
    Route::get('pending-invoices', 'InvoiceController@getPendingInvoices')->name('pending-invoices');
    //Route::get('return-invoices', 'InvoiceController@getReturnInvoices')->name('return-invoices');
    Route::get('invoice-details', 'InvoiceController@getInvoiceDetails')->name('get-invoice-details');
    Route::post('change-status-ready/{id}', 'InvoiceController@changeItemStatusReady')->name('change-item-status-ready');
    Route::post('change-status-deliver/{id}', 'InvoiceController@changeItemStatusDeliver')->name('change-item-status-deliver');
    Route::get('/cashier-history', 'InvoiceController@cashierHistory')->name('cashier-history');

    // Invoice Item Route
    Route::post('update-status-itemInvoice/{id}' ,'InvoiceItemsController@updateStatus')->name('update-status-itemInvoice');
    Route::post('update-status-itemInvoiceDelivery/{id}', 'InvoiceItemsController@updateDeliveryStatus')->name('update-status-delivery-itemInvoice');

        //payments
    Route::get('delete-payment/{id}', 'InvoiceController@deletePayment')->name('delete-payment');


    //reports Route
    Route::get('items-to-be-delivired' ,'ReportsController@itemsToBeDelivired')->name('items-to-be-delivired');
    Route::get('report-items-to-be-delivired' ,'ReportsController@reportItemsToBeDelivired')->name('report-items-to-be-delivired');
    Route::get('get-pending-invoices' ,'ReportsController@getPendingInvoices')->name('get-pending-invoices');
    Route::get('report-pending-invoices' ,'ReportsController@reportgetPendingInvoices')->name('report-pending-invoices');
    Route::get('get-return-invoices' ,'ReportsController@getReturnInvoices')->name('get-return-invoices');
    Route::get('report-return-invoices' ,'ReportsController@reportgetReturnInvoices')->name('report-return-invoices');
    Route::get('get-customers' ,'ReportsController@getCustomers')->name('get-customers-report');
    Route::get('report-customers' ,'ReportsController@reportgetCustomers')->name('report-customers');
    Route::get('get-sales-transactions' ,'ReportsController@getSalesTransactions')->name('get-sales-transactions-report');
    Route::get('report-sales-transactions' ,'ReportsController@reportSalesTransactions')->name('report-sales-transactions');
    Route::get('get-cashier-transactions' ,'ReportsController@getCashierTransactions')->name('get-cashier-transactions-report');
    Route::get('report-cashier-transactions' ,'ReportsController@reportCashierTransactions')->name('report-cashier-transactions');
    // ✅ New Cashier Report Routes
    Route::get('get-cashier-report', 'ReportsController@getCashierReport')->name('get-cashier-report');
    Route::get('report-cashier', 'ReportsController@reportCashierReport')->name('report-cashier');
    Route::get('get-sales-summary' ,'ReportsController@getSalesSummary')->name('get-sales-summary-report');
    Route::get('report-sales-summary' ,'ReportsController@reportSalesSummary')->name('report-sales-summary');
    Route::get('report-return-invoice' ,'ReportsController@reportReturningInvoice')->name('get-return-invoice-report');
    Route::get('print-returning-invoice/en/{id}', 'ReportsController@printReturnInvoiceEn')->name('print-returning-invoice-en');
    Route::get('print-returning-invoice/ar/{id}', 'ReportsController@printReturnInvoiceAr')->name('print-returning-invoice-ar');
    Route::get('get-sales-summary-salesman' ,'ReportsController@getSalesSummarySalesman')->name('get-sales-summary-salesman-report');
    Route::get('report-sales-summary-salesman' ,'ReportsController@reportSalesSummarySalesman')->name('report-sales-summary-salesman');
    Route::get('get-salesman-summary', 'ReportsController@salesmanSummaryIndex')->name('reports.salesman.summary');
    Route::get('reports-salesman-summary.print', 'ReportsController@reportSalesSummaryPerSalesman')->name('reports.salesman.summary.print');
    Route::get('get-sales-summary-doctor', 'ReportsController@getSalesSummaryDoctor')->name('get-sales-summary-doctor');
    Route::get('report-sales-summary-doctor', 'ReportsController@reportSalesSummaryDoctor')->name('report-sales-summary-doctor');
    Route::get('/daily-close/calc',  'ReportsController@calc')->name('daily-close.calc');
    Route::post('daily-close/calc/save', 'ReportsController@dailyCloseCalc')->name('daily-close.calc.save');
    Route::get('items-delivered' ,'ReportsController@itemsDelivered')->name('items-delivered');
    Route::get('report-items-delivered' ,'ReportsController@reportItemsDelivired')->name('report-items-delivered');
    Route::get('salesman-salary' ,'ReportsController@salesmanSalary')->name('salesman-salary');
    Route::get('report-salesman-salary' ,'ReportsController@reportSalesmanSalary')->name('report-salesman-salary');
    Route::get('/report-expenses', 'ReportsController@getExpensesReport')->name('expenses-report');
    Route::get('/report-expenses-print',  'ReportsController@reportExpensesReport')->name('expenses-report-print');

    // Delivered Invoices Report
    Route::get('get-delivered-invoices', 'ReportsController@getDeliveredInvoices')->name('get-delivered-invoices');
    Route::get('report-delivered-invoices', 'ReportsController@reportDeliveredInvoices')->name('report-delivered-invoices');

    // Damaged Lenses Report
    Route::get('get-damaged-lenses', 'ReportsController@getDamagedLensesReport')->name('get-damaged-lenses');
    Route::get('report-damaged-lenses', 'ReportsController@reportDamagedLenses')->name('report-damaged-lenses');


    //salesman sales
    Route::get('salesman-sales', 'SalesmanController@getSalesmanSalesView')
        ->name('salesman-sales');

        // Products Discount Routes
    Route::get('discounts-view' ,'DiscountsController@getDiscountsView')->name('discounts-view');
    Route::post('save-discounts' ,'DiscountsController@saveDiscounts')->name('save-discounts');

    // Stock Overview Routes
    Route::get('stock-overview/' ,'StockOverview@index')->name('get-stock-overview');
    Route::get('item-inquiry/' ,'StockOverview@getItemInquiry')->name('get-item-inquiry');
    Route::post('search-item/' ,'StockOverview@searchItem')->name('search-item');
    Route::post('search-item-inq/' ,'StockOverview@searchItemInq')->name('search-item-inq');
        // Filtering Routes
        Route::post('filter-products-by-cat-id/' ,'StockOverview@filterByCatId')->name('filter-products-cat-id');
        Route::post('filter-products-by-brand-id/' ,'StockOverview@filterByBrandId')->name('filter-products-brand-id');
        Route::post('filter-products-by-model-id/' ,'StockOverview@filterByModelId')->name('filter-products-model-id');
        Route::post('filter-products-by-size/' ,'StockOverview@filterBySize')->name('filter-products-size');
        Route::post('filter-products-by-color/' ,'StockOverview@filterByColor')->name('filter-products-color');

        Route::post('filter-models-by-category-and-brand/' ,'StockOverview@filterModelsByCategoryIdAndBrandId')->name('filter-models-by-category-and-brand-id');

        Route::post('filter-brands-bycatid/' ,'StockOverview@filterBrandsByCatId')->name('filter-brands-by-category-id');
        Route::post('filter-brands-bybrandid/' ,'StockOverview@filterModelsByBrandId')->name('filter-models-by-brand-id');

        Route::post('full-search', 'StockOverview@fullSearch')->name('full-search');

    // Invoices Routes
    Route::post('deliver-invoice', 'InvoiceController@postDeliverInvoice')->name('post-deliver-invoice');
    //Route::post('return-invoice', 'InvoiceController@postReturnInvoice')->name('post-return-invoice');
    Route::get('print-pending-invoice/en/{id}', 'InvoiceController@printPendingInvoiceEn')->name('print-pending-invoice-en');
    Route::get('print-pending-invoice/ar/{id}', 'InvoiceController@printPendingInvoiceAr')->name('print-pending-invoice-ar');


    // ========== LENS LAB ORDERS ==========
    Route::prefix('lens-purchase-orders')->name('lens-purchase-orders.')->group(function () {
        Route::get('/',                                 'LensPurchaseOrderController@index')->name('index');
        Route::get('/lens-stock',                       'LensPurchaseOrderController@lensStock')->name('lens-stock');
        Route::get('/search-invoice',                   'LensPurchaseOrderController@searchInvoice')->name('search-invoice');
        Route::get('/api/lenses',                       'LensPurchaseOrderController@searchLenses')->name('api.lenses');
        Route::get('/create/{invoiceId}',               'LensPurchaseOrderController@create')->name('create');
        Route::post('/',                                'LensPurchaseOrderController@store')->name('store');
        Route::get('/{id}',                             'LensPurchaseOrderController@show')->name('show');
        Route::get('/{id}/receive',                     'LensPurchaseOrderController@receiveForm')->name('receive');
        Route::post('/{id}/mark-received',              'LensPurchaseOrderController@markReceived')->name('mark-received');
        Route::patch('/{id}/mark-sent',                 'LensPurchaseOrderController@markSent')->name('sent');
        Route::delete('/{id}/cancel',                   'LensPurchaseOrderController@cancel')->name('cancel');
        // Re-order defective lenses
        Route::get('/{id}/reorder',                     'LensPurchaseOrderController@reorderForm')->name('reorder');
        Route::post('/{id}/reorder',                    'LensPurchaseOrderController@reorderStore')->name('reorder.store');
        // Recover damaged lens back to stock
        Route::post('/damaged/{entryId}/recover',       'LensPurchaseOrderController@recoverDamaged')->name('recover-damaged');
        // Damaged lenses management (هالك)
        Route::get('/damaged/list',                     'LensPurchaseOrderController@damagedLenses')->name('damaged-list');
        // ── Contact Lens Lab Orders ──────────────────────────────
        Route::get('/cl/create/{invoiceId}',            'LensPurchaseOrderController@createCL')->name('cl.create');
        Route::post('/cl/store',                        'LensPurchaseOrderController@storeCL')->name('cl.store');
        Route::get('/{id}/receive-cl',                  'LensPurchaseOrderController@receiveCLForm')->name('cl.receive');
        Route::post('/{id}/mark-cl-received',           'LensPurchaseOrderController@markCLReceived')->name('cl.mark-received');
        // ── Contact Lens Re-order (هالك) ────────────────────────
        Route::get('/{id}/reorder-cl',                  'LensPurchaseOrderController@reorderFormCL')->name('cl.reorder');
        Route::post('/{id}/reorder-cl',                 'LensPurchaseOrderController@reorderStoreCL')->name('cl.reorder.store');
        // ── Contact Lens Damaged list & recover ─────────────────
        Route::get('/cl/damaged/list',                  'LensPurchaseOrderController@damagedCL')->name('cl.damaged-list');
        Route::post('/cl/damaged/{entryId}/recover',    'LensPurchaseOrderController@recoverDamagedCL')->name('cl.recover-damaged');
    });

    // Lens Labs
    Route::prefix('lens-labs')->name('lens-labs.')->group(function () {
        Route::post('/',     'LensLabController@store')->name('store');
        Route::delete('/{id}', 'LensLabController@destroy')->name('destroy');
    });

    // Lens Brands (resource = index, create, store, show, edit, update, destroy)
    Route::resource('lens-brands', 'LensBrandController');
    Route::post('lens-brands/check-name',  'LensBrandController@checkName')  ->name('lens-brands.check-name');

    // Extra: Excel import + template download
    Route::post('lens-brands/{lensBrand}/import',  'LensBrandController@import')  ->name('lens-brands.import');
    Route::get('lens-brands/template/download',    'LensBrandController@template') ->name('lens-brands.template');
    // NOTE: put the template route BEFORE resource route to avoid conflict with {lensBrand}


    // Glassess Lenses Routes
    Route::get('glassess-lenses/' ,'GlassLenseController@index')->name('get-glassess-lenses');
    Route::get('glassess-lenses/{id}' ,'GlassLenseController@showLens')->name('show-glassess-lenses');
    Route::get('add-glass-lense/' ,'GlassLenseController@getAddLense')->name('get-add-glense');
    Route::post('filter-galass-lenses', 'GlassLenseController@filterLenses')->name('fliter-lenses');
    Route::post('add-glassess-lense/' ,'GlassLenseController@addLense')->name('post-glassess-lenses');
    Route::get('delete-lense/{id}' ,'GlassLenseController@deleteLense')->name('delete-lense');
    Route::get('edit-glass-lense/{id}' ,'GlassLenseController@getEditLense')->name('get-edit-glense');
    Route::post('edit-glassess-lense/{id}' ,'GlassLenseController@EditLense')->name('edit-glassess-lenses');

    // Categories Routes
    Route::get('all-categories/' ,'CategoryController@index')->name('get-all-categories');
    Route::post('add-category/' ,'CategoryController@addCategory')->name('add-category');
    Route::post('update-category/' ,'CategoryController@updateCategory')->name('update-category');
    Route::delete('delete-category/{id}' ,'CategoryController@deleteCategory')->name('delete-category');

    // Branches Routes
    Route::get('all-branches/' ,'BranchController@index')->name('get-all-branches');
    Route::post('add-branche/' ,'BranchController@addBranch')->name('add-branche');
    Route::post('update-branche/' ,'BranchController@updateBranch')->name('update-branche');

    // Products Routes
    Route::get('all-products/' ,'ProductController@index')->name('get-all-products');
    Route::get('add-product/' ,'ProductController@getAddProduct')->name('get-add-product');
    Route::post('add-product/' ,'ProductController@postAddProduct')->name('post-add-product');
    Route::get('update-product/{id}' ,'ProductController@getUpdateProduct')->name('get-update-product');
    Route::post('update-product/{id}' ,'ProductController@updateProduct')->name('post-update-product');
    Route::get('show-product-details' ,'ProductController@getProductDetails')->name('get-product-details');
    Route::get('show-product/{id}' ,'ProductController@showProduct')->name('show-product');
    Route::delete('delete-product/{id}' ,'ProductController@deleteProduct')->name('delete-product');
    Route::post('restore-product/{id}', 'ProductController@restoreProduct')->name('restore-product');
    Route::post('import-products/',   'ProductController@importProducts')       ->name('import-products');
    Route::get('product-template/',      'ProductController@downloadProductTemplate')->name('product-import-template');
    Route::get('product-test-template/', 'ProductController@downloadTestTemplate')->name('product-test-template');

    // ── Contact Lens Routes ──────────────────────────────────────
    Route::group(['prefix' => 'contact-lenses', 'as' => 'contact-lenses.'], function () {
        Route::get('/',                 'ContactLensController@index')               ->name('index');
        Route::get('/create',           'ContactLensController@create')              ->name('create');
        Route::get('/generate-barcode', 'ContactLensController@generateBarcodeAjax')->name('generate-barcode');
        Route::post('/',                'ContactLensController@store')               ->name('store');
        Route::get('/{id}/edit',        'ContactLensController@edit')                ->name('edit');
        Route::put('/{id}',             'ContactLensController@update')              ->name('update');
        Route::delete('/{id}',          'ContactLensController@destroy')             ->name('destroy');
        Route::post('/{id}/restore',    'ContactLensController@restore')             ->name('restore');
        Route::post('/import',          'ContactLensController@import')              ->name('import');
    });

    // Brands Routes
    Route::get('all-brands/' ,'BrandController@index')->name('get-all-brands');
    Route::post('add-brand/' ,'BrandController@addBrand')->name('post-add-brand');
    Route::post('update-brand/' ,'BrandController@updateBrand')->name('update-brand');
    Route::delete('delete-brand/{id}' ,'BrandController@deleteBrand')->name('delete-brand');

    // Models Routes
    Route::get('all-models/' ,'GlassModelController@index')->name('get-all-models');
    Route::post('add-model/' ,'GlassModelController@addModel')->name('post-add-model');
    Route::post('update-model/' ,'GlassModelController@updateModel')->name('update-model');
    Route::get('delete-model/{id}' ,'GlassModelController@deleteModel')->name('delete-model');

    // InsuranceCompany Routes
    Route::get('all-insurance-companies/' ,'InsuranceCompanyController@index')->name('get-all-insurance-companies');
    Route::get('add-insurance-companies/' ,'InsuranceCompanyController@create')->name('get-add-insurance-company');
    Route::post('add-insurance-company/' ,'InsuranceCompanyController@store')->name('post-add-insurance-company');
    Route::get('update-insurance-company/{id}' ,'InsuranceCompanyController@edit')->name('get-update-insurance-company');
    Route::put('update-insurance-company/{id}' ,'InsuranceCompanyController@update')->name('post-update-insurance-company');
    Route::get('delete-insurance-company/{id}' ,'InsuranceCompanyController@destroy')->name('delete-insurance-company');

    // Cardholders Routes
    Route::get('all-cardholders/' ,'CardholderController@index')->name('get-all-cardholders');
    Route::get('add-cardholder/' ,'CardholderController@create')->name('get-add-cardholder');
    Route::post('add-cardholder/' ,'CardholderController@store')->name('post-add-cardholder');
    Route::get('update-cardholder/{id}' ,'CardholderController@edit')->name('get-update-cardholder');
    Route::put('update-cardholder/{id}' ,'CardholderController@update')->name('post-update-cardholder');
    Route::get('delete-cardholder/{id}' ,'CardholderController@destroy')->name('delete-cardholder');

    //Daily Closing
        Route::group(['prefix' => 'daily-closing', 'as' => 'daily-closing.'], function () {

            Route::get('/', 'DailyclosingController@index')
                ->name('index');

            Route::get('/closing', 'DailyclosingController@closing')
                ->name('closing');

            Route::post('/save-entry', 'DailyclosingController@saveEntry')
                ->name('save-entry');

            Route::post('/auto-balance', 'DailyclosingController@autoBalance')
                ->name('auto-balance');

            Route::post('/close', 'DailyclosingController@closeDailyClosing')
                ->name('close');

            Route::post('/reopen', 'DailyclosingController@reopenDailyClosing')
                ->name('reopen');

            Route::post('/clear-logs', 'DailyclosingController@clearBalanceLogs')
                ->name('clear-logs');


        });


        // Settings Routes
    Route::get('settings/' ,'SettingController@index')->name('settings.index');
    Route::post('settings/update' ,'SettingController@update')->name('settings.update');
    Route::post('settings/price-adjustment', 'SettingController@priceAdjustment')->name('settings.price-adjustment');


        /**
         * ====================================================
         * INVOICE ROUTES - Complete System
         * ====================================================
         */

// ========== INVOICE CREATION ==========
        Route::group(['prefix' => 'invoices'], function() {

            // Main invoice page
            Route::get('/pending', 'InvoiceViewController@pendingInvoices')
                ->name('invoice.pending');

            Route::get('/returned', 'InvoiceViewController@returnInvoices')
                ->name('invoice.returned');

            Route::post('/return-invoice', 'InvoiceViewController@postReturnInvoice')
                ->name('invoice.return');

            Route::get('/create/{customer_id}', 'NewInvoiceController@create')
                ->name('invoice.create');

            // Session Management
            Route::post('/session/store-item', 'NewInvoiceController@storeItemInSession')
                ->name('invoice.session.store-item');

            Route::post('/session/update-item', 'NewInvoiceController@updateItemInSession')
                ->name('invoice.session.update-item');

            Route::post('/session/delete-item', 'NewInvoiceController@deleteItemFromSession')
                ->name('invoice.session.delete-item');

            Route::post('/session/clear', 'NewInvoiceController@clearSession')
                ->name('invoice.session.clear');

            Route::post('/session/calculate-totals', 'NewInvoiceController@calculateTotals')
                ->name('invoice.session.calculate-totals');

            // Product Search
            Route::post('/products/search', 'NewInvoiceController@searchProducts')
                ->name('invoice.products.search');

            Route::post('/products/sizes-colors', 'NewInvoiceController@getSizesColors')
                ->name('invoice.products.sizes-colors');

            Route::post('/products/get-by-id', 'NewInvoiceController@getProductById')
                ->name('invoice.products.get-by-id');

            Route::post('/products/get-by-barcode', 'NewInvoiceController@getProductByBarcode')
                ->name('invoice.products.get-by-barcode');

            // Lenses
            Route::post('/lenses/filter', 'NewInvoiceController@filterLenses')
                ->name('invoice.lenses.filter');

            Route::post('/lenses/add', 'NewInvoiceController@addLensesToInvoice')
                ->name('invoice.lenses.add');

            Route::get('/lenses/eye-tests/{customer_id}', 'NewInvoiceController@getEyeTests')
                ->name('invoice.lenses.eye-tests');

            // Discounts
            Route::post('/discounts/apply-regular', 'NewInvoiceController@applyRegularDiscount')
                ->name('invoice.discounts.apply-regular');

            Route::post('/discounts/remove-regular', 'NewInvoiceController@removeRegularDiscount')
                ->name('invoice.discounts.remove-regular');

            Route::post('/discounts/apply-payer', 'NewInvoiceController@applyPayerDiscount')
                ->name('invoice.discounts.apply-payer');

            Route::post('/discounts/remove-payer', 'NewInvoiceController@removePayerDiscount')
                ->name('invoice.discounts.remove-payer');

            Route::get('/discounts/get-companies/{type}', 'NewInvoiceController@getPayerCompanies')
                ->name('invoice.discounts.get-companies');

            Route::get('/discounts/get-company-details/{type}/{id}', 'NewInvoiceController@getPayerCompanyDetails')
                ->name('invoice.discounts.get-company-details');

            // Payments
            Route::post('/payments/calculate', 'NewInvoiceController@calculatePayments')
                ->name('invoice.payments.calculate');

            // Save Invoice
            Route::post('/save', 'NewInvoiceController@saveInvoice')
                ->name('invoice.save');

            Route::post('/save-draft', 'NewInvoiceController@saveDraft')
                ->name('invoice.save-draft');

            // Validation
            Route::post('/validate-stock', 'NewInvoiceController@validateStock')
                ->name('invoice.validate-stock');

            //session
            Route::get('/session/get-draft', 'NewInvoiceController@getDraft')
                ->name('invoice.session.get-draft');

            Route::get('/{invoice_code}/show', 'InvoiceViewController@show')->name('invoice.show');
            Route::get('/details', 'InvoiceViewController@details')->name('invoice.details');
            Route::get('/{invoice_code}/edit', 'InvoiceViewController@edit')->name('invoice.edit');
            Route::put('/{invoice_code}', 'InvoiceViewController@update')->name('invoice.update');
            Route::post('/{invoice_code}/payment/add', 'InvoiceViewController@addPayment')->name('invoice.payment.add');
            Route::delete('/payment/{payment_id}', 'InvoiceViewController@deletePayment')->name('invoice.payment.delete');
        });

// ========== CUSTOMER & DOCTOR SEARCH ==========
        Route::group(['prefix' => 'search'], function() {

            Route::post('/customers', 'CustomerController@searchCustomers')
                ->name('dashboard.search.customers');

            Route::post('/doctors', 'DoctorController@searchDoctors')
                ->name('dashboard.search.doctors');
        });

        Route::post('/session/store-doctor', function(Request $request) {
            session([
                'doctor_id' => $request->doctor_id,
                'doctor_name' => $request->doctor_name
            ]);
            return response()->json(['success' => true]);
        })->name('session.store-doctor');

    // Profile Routes
    Route::get('/profile', 'UsersController@showProfile')->name('profile.show');
    Route::post('/profile/update', 'UsersController@updateProfile')->name('profile.update');
    Route::post('/profile/change-password', 'UsersController@changePassword')->name('profile.change-password');

   // });

    // ─── Chat Routes ──────────────────────────────────────────────────────────
    Route::prefix('chat')->middleware('auth')->group(function () {
        Route::get('/',                      'ChatController@index')->name('chat.index');
        Route::get('/users',                 'ChatController@getUsers')->name('chat.users');
        Route::get('/messages/{userId}',     'ChatController@getMessages')->name('chat.messages');
        Route::post('/send',                 'ChatController@send')->name('chat.send');
        Route::get('/unread-count',          'ChatController@unreadCount')->name('chat.unread-count');
        Route::post('/mark-read/{userId}',   'ChatController@markRead')->name('chat.mark-read');
        Route::get('/ping',                  'ChatController@ping')->name('chat.ping');
        Route::get('/read-status/{userId}',  'ChatController@readStatus')->name('chat.read-status');
    });

    // Notifications Routes
    Route::prefix('notifications')->middleware('auth')->group(function () {
        Route::get('/', 'NotificationController@index')->name('notifications.index');
        Route::get('/fetch', 'NotificationController@fetch')->name('notifications.fetch');
        Route::post('/{id}/read', 'NotificationController@markAsRead')->name('notifications.read');
        Route::post('/mark-all-read', 'NotificationController@markAllAsRead')->name('notifications.mark-all-read');
        Route::delete('/{id}', 'NotificationController@destroy')->name('notifications.destroy');
        Route::delete('/delete/all', 'NotificationController@destroyAll')->name('notifications.destroy-all');
    });

    //system maintenance
   // Route::view('maintenance', 'dashboard.pages.maintenance.maintenance')->name('maintenance')->middleware('system.active');

    Route::get('/maintenance', function () {
        $status = Settings::get('system_status', 'active');

        if ($status !== 'maintenance') {
            return redirect()->route('index');
        }

        return view('errors.maintenance', [
            'message' => Settings::get('maintenance_message', 'System is currently under maintenance.')
        ]);
    })->name('maintenance');

    // Deactivated User Page Route (Add this to your routes)
    Route::get('dashboard/deactivated', function () {
        return view('errors.deactivated', [
            'user' => Auth::user(),
            'message' => 'Your account has been deactivated. Please contact your system administrator.'
        ]);
    })->name('dashboard.deactivated');

});
