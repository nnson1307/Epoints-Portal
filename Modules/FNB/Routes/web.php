<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'fnb'], function () {
    Route::prefix('areas')->group(function() {
        Route::get('/', 'AreasController@index')->name('fnb.areas');
        Route::post('/list', 'AreasController@list')->name('fnb.areas.list');
        Route::post('/show-popup-config', 'AreasController@showPopupConfig')->name('fnb.areas.show-popup-config');
        Route::post('/save-config', 'AreasController@saveConfig')->name('fnb.areas.save-config');
        Route::post('/get-list-branch', 'AreasController@getListBranch')->name('fnb.areas.get-list-branch');
        Route::post('/list-all', 'AreasController@allAreas')->name('fnb.areas.all');

        Route::post('/show-popup', 'AreasController@showPopup')->name('fnb.areas.show-popup');
        ///thêm khu vực
        Route::post('/create-areas', 'AreasController@createAreas')->name('fnb.areas.create');
        ///chinh sua khu vuc
        Route::post('/edit-areas', 'AreasController@editAreas')->name('fnb.areas.edit');
        ///xóa khu vực
        Route::post('/delete-areas', 'AreasController@deleteAreas')->name('fnb.areas.delete');
        Route::get('/export', 'AreasController@export')->name('fnb.areas.export');
    });
    Route::prefix('table')->group(function() {
        Route::get('/', 'TableController@index')->name('fnb.table');
        Route::post('/list', 'TableController@list')->name('fnb.table.list');
        Route::post('/show-popup-config', 'TableController@showPopupConfig')->name('fnb.table.show-popup-config');
        Route::post('/save-config', 'TableController@saveConfig')->name('fnb.table.save-config');
        Route::post('/show-popup', 'TableController@showPopup')->name('fnb.table.show-popup');
        Route::post('/create-table', 'TableController@createTable')->name('fnb.table.create');
        Route::post('/edit-table', 'TableController@editTable')->name('fnb.table.edit');
        Route::post('/delete-table', 'TableController@deleteTable')->name('fnb.table.delete');
        Route::get('/export', 'TableController@export')->name('fnb.table.export');
    });
    Route::prefix('request')->group(function() {
        Route::get('/', 'RequestController@index')->name('fnb.request');
        Route::post('/list', 'RequestController@list')->name('fnb.request.list');
    });
    Route::prefix('customer-review')->group(function() {
        Route::get('/', 'CustomerReviewController@index')->name('fnb.customer-review');
        Route::post('/list', 'CustomerReviewController@list')->name('fnb.customer-review.list');
    });

    Route::prefix('review-list')->group(function() {
        Route::get('/', 'ReviewListController@index')->name('fnb.request-list');
    });

    Route::prefix('review-list-detail')->group(function() {
        Route::get('/', 'ReviewListDetailController@index')->name('fnb.review-list-detail');
        Route::post('/list', 'ReviewListDetailController@list')->name('fnb.review-list-detail.list');
        Route::post('/show-popup', 'ReviewListDetailController@showPopup')->name('fnb.review-list-detail.show-popup');
        Route::post('/save-review-list-detail', 'ReviewListDetailController@saveReviewListDetail')->name('fnb.review-list-detail.save-review-list-detail');
        Route::post('/remove-review-list-detail', 'ReviewListDetailController@removeReviewListDetail')->name('fnb.review-list-detail.remove-review-list-detail');
    });

    Route::prefix('qr-code')->group(function() {
        Route::get('/', 'QRCodeController@index')->name('fnb.qr-code');
        Route::get('/export', 'QRCodeController@export')->name('fnb.qr-code.export');
        Route::post('/list', 'QRCodeController@list')->name('fnb.qr-code.list');
        Route::post('/show-popup-config', 'QRCodeController@showPopupConfig')->name('fnb.qr-code.show-popup-config');
        Route::post('/save-config', 'QRCodeController@saveConfig')->name('fnb.qr-code.save-config');
        Route::get('/add-qr-code', 'QRCodeController@addQrCode')->name('fnb.qr-code.add-qr-code');
        Route::post('/list-branch', 'QRCodeController@getListBranch')->name('fnb.qr-code.list-branch');
        Route::post('/list-area', 'QRCodeController@getListArea')->name('fnb.qr-code.list-area');
        Route::post('/list-table', 'QRCodeController@getListTable')->name('fnb.qr-code.list-table');
        Route::post('/submit-qr-code', 'QRCodeController@submitQrCode')->name('fnb.qr-code.submit-qr-code');
        Route::post('/get-client-ip', 'QRCodeController@getClientIp')->name('fnb.qr-code.get-client-ip');
        Route::get('/detail/{id}', 'QRCodeController@detail')->name('fnb.qr-code.detail');
        Route::get('/edit/{id}', 'QRCodeController@edit')->name('fnb.qr-code.edit');
        Route::post('/update', 'QRCodeController@update')->name('fnb.qr-code.update');
        Route::post('/search-table', 'QRCodeController@searchTable')->name('fnb.qr-code.search-table');
        Route::post('/view-qr-code', 'QRCodeController@viewQrCode')->name('fnb.qr-code.view-qr-code');
        Route::post('/remove', 'QRCodeController@remove')->name('fnb.qr-code.remove');
        Route::post('/upload-image', 'QRCodeController@uploadImage')->name('fnb.upload-image')->middleware('s3');
        Route::get('/preview', 'QRCodeController@preview')->name('fnb.qr-code.preview');
        Route::get('qrcode-with-image', function () {

            $image = QrCode::format('png')
                ->merge(public_path('/icon.png'), 0.5, true)
                ->size(500)
                ->errorCorrection('H')
                ->generate('A simple example of QR code!');

            return response($image)->header('Content-type','image/png');
        });
    });

    Route::prefix('promotion')->group(function() {
        Route::get('/edit/{id}', 'PromotionController@edit')->name('fnb.promotion.edit');
        Route::post('/update', 'PromotionController@update')->name('fnb.promotion.update');
    });

    Route::prefix('product')->group(function() {
        Route::get('/edit/{id}', 'ProductController@edit')->name('fnb.product.edit');
        Route::post('/check-name', 'ProductController@checkNameAction')->name('fnb.product.check-name');
        Route::post('/update', 'ProductController@update')->name('fnb.product.update');
        Route::get('/add-topping/{id}', 'ProductController@addTopping')->name('fnb.product.add-topping');
        Route::post('/add-topping-session', 'ProductController@addToppingSession')->name('fnb.product.add-topping-session');
        Route::post('/store-topping', 'ProductController@storeTopping')->name('fnb.product.store-topping');
        Route::post('/get-list-topping', 'ProductController@getListTopping')->name('fnb.product.get-list-topping');
        Route::post('get-list-product-child', 'ProductController@getListProductChild')->name('fnb.product.get-list-product-child');
        Route::post('remove-topping-session', 'ProductController@removeToppingSession')->name('fnb.product.remove-topping-session');
    });
    Route::prefix('orders')->group(function() {
        Route::get('/', 'OrdersController@index')->name('fnb.orders');
        Route::post('list', 'OrdersController@list')->name('fnb.orders.list');
        Route::get('/detail/{id}', 'OrdersController@detail')->name('fnb.orders.detail');
        Route::get('/receipt/{id}', 'OrdersController@receipt')->name('fnb.orders.receipt');
        Route::post('/export-list', 'OrdersController@exportList')->name('fnb.orders.export-list');
        Route::post('/remove', 'OrdersController@remove')->name('fnb.orders.remove');
        Route::get('/add-orders', 'OrdersController@addOrders')->name('fnb.orders.add-orders');
        Route::get('/note-orders', 'OrdersController@noteOrders')->name('fnb.orders.note-orders');
        Route::post('/choose-type', 'OrdersController@chooseType')->name('fnb.orders.choose-type');
        Route::post('list-add', 'OrdersController@listAdd')->name('fnb.orders.list-add');
        Route::post('select-topping', 'OrdersController@selectTopping')->name('fnb.orders.select-topping');
        Route::post('save-topping-select', 'OrdersController@saveToppingSelect')->name('fnb.orders.save-topping-select');
        Route::post('change-topping-select', 'OrdersController@changeToppingSelect')->name('fnb.orders.change-topping-select');
        Route::post('submit-or-update', 'OrdersController@submitOrUpdate')->name('fnb.orders.submit-or-update');
        Route::post('choose-waiter', 'OrdersController@chooseWaiter')->name('fnb.orders.choose-waiter');
        Route::post('remove-session-product', 'OrdersController@removeSessionProduct')->name('fnb.orders.remove-session-product');
        Route::post('save-session-table', 'OrdersController@saveSessionTable')->name('fnb.orders.save-session-table');
        Route::post('remove-order', 'OrdersController@removeOrder')->name('fnb.orders.remove-order');
        Route::post('submit-add-receipt', 'OrdersController@submitAddReceipt')->name('fnb.orders.submitAddReceipt');
        Route::get('print-bill', 'OrdersController@printBill')->name('fnb.orders.print-bill');
        Route::get('print-bill-not-receipt', 'OrdersController@printBillNotReceiptAction')->name('fnb.orders.print-bill-not-receipt');
        Route::post('submit-cancel-order', 'OrdersController@submitCancelOrderAction')->name('fnb.orders.submit-cancel');
        Route::post('submit-edit', 'OrdersController@submitEditAction')->name('fnb.orders.submit-edit');
        Route::post('submit-receipt-after', 'OrdersController@submitReceiptAfterAction')->name('fnb.orders.submit-receipt-after');
        Route::post('merge-table', 'OrdersController@mergeTable')->name('fnb.orders.merge-table');
        Route::post('merge-bill', 'OrdersController@mergeBill')->name('fnb.orders.merge-bill');
        Route::post('move-table', 'OrdersController@moveTable')->name('fnb.orders.move-table');
        Route::post('change-area', 'OrdersController@changeArea')->name('fnb.orders.change-area');
        Route::post('search-order', 'OrdersController@searchOrder')->name('fnb.orders.search-order');
        Route::post('submit-merge-table', 'OrdersController@submitMergeTable')->name('fnb.orders.submit-merge-table');
        Route::post('submit-move-table', 'OrdersController@submitMoveTable')->name('fnb.orders.submit-move-table');
        Route::post('split-table', 'OrdersController@splitTable')->name('fnb.orders.split-table');
        Route::post('submit-split-table', 'OrdersController@submitSplitTable')->name('fnb.orders.submit-split-table');
        Route::post('submit-merge-bill', 'OrdersController@submitMergeBill')->name('fnb.orders.submit-merge-bill');
        Route::post('show-popup-order-table', 'OrdersController@showPopupOrderTable')->name('fnb.orders.show-popup-order-table');
        Route::post('show-popup-customer-request', 'OrdersController@showPopupCustomerRequest')->name('fnb.orders.show-popup-customer-request');
        Route::post('confirm-customer-request', 'OrdersController@confirmCustomerRequest')->name('fnb.orders.confirm-customer-request');
        Route::post('change-info-address', 'OrdersController@changeInfoAddress')->name('fnb.order.changeInfoAddress');
    });


    Route::prefix('product-attribute-group')->group(function() {
        Route::post('/edit/{id}', 'ProductAttributeGroupController@edit')->name('fnb.product-attribute-group.edit');
        Route::post('/update', 'ProductAttributeGroupController@update')->name('fnb.product-attribute-group.update');
    });

    Route::prefix('product-attribute')->group(function() {
        Route::post('/edit/{id}', 'ProductAttributeController@edit')->name('fnb.product-attribute.edit');
        Route::post('/update', 'ProductAttributeController@update')->name('fnb.product-attribute.update');
    });

});

