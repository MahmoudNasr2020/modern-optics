<?php

use Illuminate\Support\Facades\Route;
/*use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchStockController;
use App\Http\Controllers\StockTransferController;*/

/*
|--------------------------------------------------------------------------
| Branch Management Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'dashboard',
    'as' => 'dashboard.',
    'middleware' => ['auth']
], function () {

    // ==================== Branches ====================
    Route::group([
        'prefix' => 'branches',
        'as' => 'branches.'
    ], function () {

        // CRUD للفروع
        Route::get('/', 'BranchController@index')->name('index');
        Route::get('/create', 'BranchController@create')->name('create');
        Route::post('/', 'BranchController@store')->name('store');
        Route::get('/{branch}', 'BranchController@show')->name('show');
        Route::get('/{branch}/edit', 'BranchController@edit')->name('edit');
        Route::put('/{branch}', 'BranchController@update')->name('update');
        Route::delete('/{branch}', 'BranchController@destroy')->name('destroy');

        // تفعيل / تعطيل فرع
        Route::post('/{branch}/toggle-status', 'BranchController@toggleStatus')
            ->name('toggle-status');

        // إحصائيات وتقارير
        Route::get('/statistics/all', 'BranchController@statistics')->name('statistics');
        Route::get('/statistics/pdf', 'BranchController@statisticsPdf')->name('statistics.pdf');

        Route::get('/{branch}/report', 'BranchController@report')->name('report');

        // ==================== Branch Stock ====================
        Route::group([
            'prefix' => '{branch}/stock',
            'as' => 'stock.'
        ], function () {

            Route::get('/', 'BranchStockController@index')->name('index');
            Route::get('/create', 'BranchStockController@create')->name('create');
            Route::post('/', 'BranchStockController@store')->name('store');

            Route::get('/{stock}/edit', 'BranchStockController@edit')->name('edit');
            Route::put('/{stock}', 'BranchStockController@update')->name('update');
            Route::delete('/{stock}', 'BranchStockController@destroy')->name('destroy');

            Route::post('/{stock}/add-quantity', 'BranchStockController@addQuantity')
                ->name('add-quantity');
            Route::post('/{stock}/reduce-quantity', 'BranchStockController@reduceQuantity')
                ->name('reduce-quantity');

            Route::get('/low-stock', 'BranchStockController@lowStock')->name('low-stock');
            Route::get('/report', 'BranchStockController@report')->name('report');
            Route::get('/report/print', 'BranchStockController@reportPrint')->name('report.print');
            Route::get('/report/excel', 'BranchStockController@reportExcel')->name('report.excel');
            Route::get('/report/pdf', 'BranchStockController@reportPdf')->name('report.pdf');

            // ⚠ Fixed order: static routes MUST come before /{stock} wildcard
            Route::get('template', 'BranchStockController@downloadTemplate')
                ->name('template');

            Route::get('test-template', 'BranchStockController@downloadTestTemplate')
                ->name('test-template');

            Route::post('import', 'BranchStockController@import')
                ->name('import');

            Route::get('/{stock}/movements', 'BranchStockController@movements')
                ->name('movements');

            Route::get('/{stock}', 'BranchStockController@show')->name('show');

        });
    });

    // ==================== Stock Transfers ====================
    Route::group([
        'prefix' => 'stock-transfers',
        'as' => 'stock-transfers.'
    ], function () {

        // ── Static routes FIRST (must come before wildcard /{stockTransfer}) ──
        Route::get('/', 'StockTransferController@index')->name('index');
        Route::get('/create', 'StockTransferController@create')->name('create');
        Route::post('/', 'StockTransferController@store')->name('store');
        Route::post('/check-stock', 'StockTransferController@checkStock')->name('check-stock');

        // Multi-product transfer (dynamic form)
        Route::get('/search-products', 'StockTransferController@searchProducts')->name('search-products');
        Route::post('/store-multi', 'StockTransferController@storeMulti')->name('store-multi');

        // Bulk transfer request via Excel
        Route::get('/bulk-request', 'StockTransferController@bulkRequestForm')->name('bulk-request');
        Route::post('/bulk-request', 'StockTransferController@bulkRequestStore')->name('bulk-request.store');
        Route::get('/bulk-template', 'StockTransferController@bulkTemplate')->name('bulk-template');

        // Batch view
        Route::get('/batch/{batchNumber}', 'StockTransferController@batchShow')->name('batch');

        Route::get('/pending/all', 'StockTransferController@pending')->name('pending');
        Route::get('/branch/{branch}', 'StockTransferController@branchTransfers')->name('branch');
        Route::get('/reports/all', 'StockTransferController@report')->name('report');
        Route::get('/report/pdf', 'StockTransferController@reportPdf')->name('report.pdf');
        Route::get('/reports/items', 'StockTransferController@itemReport')->name('report.items');
        Route::get('/reports/items/print', 'StockTransferController@itemReportPrint')->name('report.items.print');
        Route::get('/reports/items/pdf', 'StockTransferController@itemReportPdf')->name('report.items.pdf');

        // ── Wildcard routes LAST ──
        Route::get('/{stockTransfer}', 'StockTransferController@show')->name('show');
        Route::post('/{stockTransfer}/accept',  'StockTransferController@accept')->name('accept');   // approve+receive in one step
        Route::post('/{stockTransfer}/approve', 'StockTransferController@approve')->name('approve');
        Route::post('/{stockTransfer}/reject',  'StockTransferController@reject')->name('reject');
        Route::post('/{stockTransfer}/ship',    'StockTransferController@ship')->name('ship');
        Route::post('/{stockTransfer}/receive', 'StockTransferController@receive')->name('receive');
        Route::post('/{stockTransfer}/cancel',  'StockTransferController@cancel')->name('cancel');

    });

    // ==================== API ====================
    Route::group([
        'prefix' => 'api',
        'as' => 'api.'
    ], function () {

        Route::get(
            '/branches/{branch}/products/{product}/stock',
            'BranchStockController@getProductStock'
        )->name('branch.product.stock');

        Route::get(
            '/branches/{branch}/available-products',
            'BranchStockController@availableProducts'
        )->name('branch.available-products');

        Route::get('/stock/get', 'StockTransferController@getStock')
            ->name('stock.get');
    });
});
