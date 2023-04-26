<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'payment', 'namespace' => 'Modules\Payment\Http\Controllers'], function () {
    Route::get('', 'PaymentController@index')->name('payment');
    Route::post('list', 'PaymentController@listAction')->name('payment.list');
    Route::post('append-by-object-type', 'PaymentController@appendObjectAccountingType')->name('payment.append-by-object-type');
    Route::post('create-payment', 'PaymentController@createPayment')->name('payment.create-payment');
    Route::post('delete-payment', 'PaymentController@deletePayment')->name('payment.delete-payment');
    Route::post('edit', 'PaymentController@edit')->name('payment.edit');
    Route::post('save-update', 'PaymentController@saveUpdate')->name('payment.save-update');
    Route::post('detail', 'PaymentController@detail')->name('payment.detail');
    Route::get('print-bill', 'PaymentController@printBill')->name('payment.print-bill');
    Route::post('delete', 'PaymentController@saveLogPrintBill')->name('payment.save-log-print-bill');
    //Export phiếu chi
    Route::post('export-excel', 'PaymentController@exportExcelAction')->name('payment.export-excel');


    // Quản lý phiếu thu
    Route::group(['prefix' => 'receipt'], function () {
        Route::get('/', 'ReceiptController@index')->name('receipt');
        Route::post('list', 'ReceiptController@listAction')->name('receipt.list');
        Route::get('add', 'ReceiptController@add')->name('receipt.create');
        Route::post('store', 'ReceiptController@store')->name('receipt.store');
        Route::get('edit/{id}', 'ReceiptController@edit')->name('receipt.edit');
        Route::post('update', 'ReceiptController@update')->name('receipt.update');
        Route::post('delete', 'ReceiptController@delete')->name('receipt.delete');
        Route::post('load-option-obj-accounting', 'ReceiptController@loadOptionObjectAccounting')
            ->name('receipt.load-option-obj-accounting');
        Route::get('print-bill', 'ReceiptController@printBill')->name('receipt.print-bill');
        Route::post('save-log-print-bill', 'ReceiptController@saveLogPrintBill')->name('receipt.save-log-print-bill');

        Route::get('detail/{id}', 'ReceiptController@show')->name('receipt.show');

        //Export ds phiếu thu
        Route::post('export-excel', 'ReceiptController@exportExcelAction')->name('receipt.export-excel');
    });

    // Báo cáo thu chi tổng hợp
    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportSynthesisController@index')->name('receipt.report');
        Route::post('/filter', 'ReportSynthesisController@filterAction')->name('receipt.report.filter');
    });

    // Quản lý phương thức thanh toán
    Route::group(['prefix' => 'payment-method'], function () {
        Route::get('/', 'PaymentMethodController@index')->name('payment-method');
        Route::post('list', 'PaymentMethodController@listAction')->name('payment-method.list');
        Route::get('create', 'PaymentMethodController@create')->name('payment-method.create');
        Route::post('store', 'PaymentMethodController@store')->name('payment-method.store');
        Route::get('edit/{id}', 'PaymentMethodController@edit')->name('payment-method.edit');
        Route::post('update', 'PaymentMethodController@update')->name('payment-method.update');
        Route::post('delete', 'PaymentMethodController@delete')->name('payment-method.delete');
    });
    // Quản lý đơn vị thanh toán
    Route::group(['prefix' => 'payment-unit'], function () {
        Route::get('/', 'PaymentUnitController@index')->name('payment-unit');
        Route::post('list', 'PaymentUnitController@listAction')->name('payment-unit.list');
        Route::get('create', 'PaymentUnitController@create')->name('payment-unit.create');
        Route::post('store', 'PaymentUnitController@store')->name('payment-unit.store');
        Route::get('edit/{id}', 'PaymentUnitController@edit')->name('payment-unit.edit');
        Route::post('update', 'PaymentUnitController@update')->name('payment-unit.update');
        Route::post('delete', 'PaymentUnitController@delete')->name('payment-unit.delete');
    });
    // quẩn lý loại phiếu chi
    Route::group(['prefix' => 'payment-type'], function () {
        Route::post('store-quickly', 'PaymentTypeController@storeQuicklyAction')->name('payment-type.store-quickly');
        Route::get('/', 'PaymentTypeController@index')->name('payment-type');
        Route::post('list', 'PaymentTypeController@listAction')->name('payment-type.list');
        Route::get('add', 'PaymentTypeController@create')->name('payment-type.create');
        Route::post('store', 'PaymentTypeController@store')->name('payment-type.store');
        Route::get('edit/{id}', 'PaymentTypeController@edit')->name('payment-type.edit');
        Route::post('update', 'PaymentTypeController@update')->name('payment-type.update');
        Route::post('delete', 'PaymentTypeController@destroy')->name('payment-type.delete');
        Route::post('update-status', 'PaymentTypeController@changeStatus')->name('payment-type.update-status');
    });

    // Quản lý loại phiếu thu (nơi thanh toán)
    Route::group(['prefix' => 'receipt-type'], function () {
        Route::get('/', 'ReceiptTypeController@index')->name('receipt-type');
        Route::post('list', 'ReceiptTypeController@listAction')->name('receipt-type.list');
        Route::get('add', 'ReceiptTypeController@create')->name('receipt-type.create');
        Route::post('store', 'ReceiptTypeController@store')->name('receipt-type.store');
        Route::get('edit/{id}', 'ReceiptTypeController@edit')->name('receipt-type.edit');
        Route::post('update', 'ReceiptTypeController@update')->name('receipt-type.update');
        Route::post('delete', 'ReceiptTypeController@destroy')->name('receipt-type.delete');
        Route::post('update-status', 'ReceiptTypeController@changeStatus')->name('receipt-type.update-status');
    });
    // Quản lý lý do giảm giá
    Route::group(['prefix' => 'discount-causes'], function () {
        Route::get('/', 'DiscountCausesController@index')->name('discount-causes');
        Route::post('list', 'DiscountCausesController@listAction')->name('discount-causes.list');
        Route::get('create', 'DiscountCausesController@create')->name('discount-causes.create');
        Route::post('store', 'DiscountCausesController@store')->name('discount-causes.store');
        Route::get('edit/{id}', 'DiscountCausesController@edit')->name('discount-causes.edit');
        Route::post('update', 'DiscountCausesController@update')->name('discount-causes.update');
        Route::post('delete', 'DiscountCausesController@delete')->name('discount-causes.delete');
    });

    //Giao dịch thanh toán online
    Route::group(['prefix' => 'receipt-online'], function () {
        Route::get('/', 'ReceiptOnlineController@index')->name('payment.receipt-online');
        Route::post('list', 'ReceiptOnlineController@listAction')->name('payment.receipt-online.list');
        Route::post('cancel', 'ReceiptOnlineController@cancelAction')->name('payment.receipt-online.cancel');
        Route::post('success', 'ReceiptOnlineController@successAction')->name('payment.receipt-online.success');
    });
});