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
            Route::get('/report/pdf', 'BranchStockController@reportPdf')->name('report.pdf');

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

        Route::get('/', 'StockTransferController@index')->name('index');
        Route::get('/create', 'StockTransferController@create')->name('create');
        Route::post('/', 'StockTransferController@store')->name('store');
        Route::get('/{stockTransfer}', 'StockTransferController@show')->name('show');
        Route::post('/check-stock', 'StockTransferController@checkStock')->name('check-stock');

        Route::post('/{stockTransfer}/approve', 'StockTransferController@approve')->name('approve');
        Route::post('/{stockTransfer}/ship', 'StockTransferController@ship')->name('ship');
        Route::post('/{stockTransfer}/receive', 'StockTransferController@receive')->name('receive');
        Route::post('/{stockTransfer}/cancel', 'StockTransferController@cancel')->name('cancel');

        Route::get('/pending/all', 'StockTransferController@pending')->name('pending');
        Route::get('/branch/{branch}', 'StockTransferController@branchTransfers')->name('branch');
        Route::get('/reports/all', 'StockTransferController@report')->name('report');
        Route::get('/report/pdf', 'StockTransferController@reportPdf')->name('report.pdf');

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
