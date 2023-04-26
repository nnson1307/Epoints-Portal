<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'delivery', 'namespace' => 'Modules\Delivery\Http\Controllers'], function () {

    Route::group(['prefix' => 'delivery'], function () {
        Route::get('/', 'DeliveryController@indexAction')->name('delivery');
        Route::post('list', 'DeliveryController@listAction')->name('delivery.list');
        Route::get('edit/{id}', 'DeliveryController@editAction')->name('delivery.edit');
        Route::post('update', 'DeliveryController@update')->name('delivery.update');
        Route::get('create/{id}', 'DeliveryController@createHistoryAction')->name('delivery.create-history');
        Route::post('choose-product', 'DeliveryController@chooseProductAction')->name('delivery.choose-product');
        Route::post('store-history', 'DeliveryController@storeHistoryAction')->name('delivery.store-history');
        Route::post('preview-order', 'DeliveryController@previewOrderAction')->name('delivery.preview-order');
        Route::get('detail/{id}', 'DeliveryController@detailAction')->name('delivery.detail');
        Route::post('save-detail', 'DeliveryController@saveDetailAction')->name('delivery.save-detail');
        Route::post('load-amount', 'DeliveryController@loadAmountAction')->name('delivery.load-amount');
        Route::post('detail-history', 'DeliveryController@detailDeliveryHistoryAction')->name('delivery.detail-history');
        Route::post('edit-history', 'DeliveryController@editHistoryAction')->name('delivery.edit-history');
        Route::post('update-history', 'DeliveryController@updateHistoryAction')->name('delivery.update-history');
        Route::post('modal-confirm-receipt', 'DeliveryController@modalConfirmReceiptAction')->name('delivery.modal-confirm-receipt');
        //Xác nhận thanh toán phiếu giao hàng
        Route::post('confirm-receipt', 'DeliveryController@confirmReceiptAction')->name('delivery.confirm-receipt');
        Route::post('update-is-active-delivery', 'DeliveryController@updateIsActiveDelivery')
            ->name('delivery.update-is-active-delivery');
        Route::post('store-delivery', 'DeliveryController@storeDelivery')->name('delivery.store-delivery');
    });

    Route::group(['prefix' => 'user-carrier'], function () {
        Route::get('/', 'UserCarrierController@index')->name('user-carrier');
        Route::post('list', 'UserCarrierController@listAction')->name('user-carrier.list');
        Route::get('create', 'UserCarrierController@create')->name('user-carrier.create');
        Route::post('store', 'UserCarrierController@store')->name('user-carrier.store');
        Route::get('edit/{id}', 'UserCarrierController@edit')->name('user-carrier.edit');
        Route::post('update', 'UserCarrierController@update')->name('user-carrier.update');
        Route::post('change-status', 'UserCarrierController@changeStatusAction')->name('user-carrier.change-status');
        Route::post('destroy', 'UserCarrierController@destroy')->name('user-carrier.destroy');
    });

    Route::group(['prefix' => 'delivery-history'], function () {
        Route::get('/', 'DeliveryHistoryController@index')->name('delivery-history');
        Route::post('list', 'DeliveryHistoryController@listAction')->name('delivery-history.list');
        Route::get('detail/{id}', 'DeliveryHistoryController@show')->name('delivery-history.show');
        Route::get('edit/{id}', 'DeliveryHistoryController@edit')->name('delivery-history.edit');
        Route::post('update', 'DeliveryHistoryController@update')->name('delivery-history.update');
        Route::post('print', 'DeliveryHistoryController@print')->name('delivery-history.print');
        Route::post('show-popup-print', 'DeliveryHistoryController@showPopupPrint')->name('delivery-history.show-popup-print');
    });

    Route::group(['prefix' => 'pickup-address'], function () {
        Route::get('/', 'PickupAddressController@index')->name('pickup-address');
        Route::post('list', 'PickupAddressController@listAction')->name('pickup-address.list');
        Route::get('create', 'PickupAddressController@create')->name('pickup-address.create');
        Route::post('store', 'PickupAddressController@store')->name('pickup-address.store');
        Route::get('edit/{id}', 'PickupAddressController@edit')->name('pickup-address.edit');
        Route::post('update', 'PickupAddressController@update')->name('pickup-address.update');
        Route::get('detail/{id}', 'PickupAddressController@show')->name('pickup-address.show');
        Route::post('destroy', 'PickupAddressController@destroy')->name('pickup-address.destroy');

    });

    Route::group(['prefix' => 'delivery-cost'], function () {
        Route::get('/', 'DeliveryCostController@index')->name('delivery-cost');
        Route::post('list', 'DeliveryCostController@listAction')->name('delivery-cost.list');
        Route::get('create', 'DeliveryCostController@create')->name('delivery-cost.create');
        Route::post('store', 'DeliveryCostController@store')->name('delivery-cost.store');
        Route::get('edit/{id}', 'DeliveryCostController@edit')->name('delivery-cost.edit');
        Route::post('update', 'DeliveryCostController@update')->name('delivery-cost.update');
        Route::get('detail/{id}', 'DeliveryCostController@show')->name('delivery-cost.show');
        Route::post('destroy', 'DeliveryCostController@destroy')->name('delivery-cost.destroy');
        Route::post('load-district', 'DeliveryCostController@loadDistrictAction')
            ->name('delivery-cost.load-district');
        Route::post('load-district-pagination', 'DeliveryCostController@loadDistrictPagination')
            ->name('delivery-cost.load-district-pagination');
    });
});


Route::group(['middleware' => ['web'], 'prefix' => 'delivery', 'namespace' => 'Modules\Delivery\Http\Controllers'], function () {
    Route::get('translate', function () {
//        $value = \Illuminate\Support\Facades\Cache::remember('cache_translate', 86400, function () {
//            $lang    = \Illuminate\Support\Facades\App::getLocale();
//            $brandCode = session()->get('brand_code');
//            $lang = substr($lang, -2); // $lang có dạng 'brandCode/vi' hoặc 'brandCode/en' => cắt chuỗi
//            if ($brandCode != null) {
//                // check exist folder language of brand
//                $path = base_path() . '/resources/lang/'. $brandCode;
//                if (!file_exists($path)) {
//                    $brandCode = '';
//                }
//            } else {
//                $brandCode = '';
//            }
//
//            $jsonString = false;
//            if ($lang == 'en') {
//                $jsonString = file_get_contents(base_path('resources/lang/'. $brandCode .'/en.json'));
//            } else if ($lang == 'vi') {
//                $jsonString = file_get_contents(base_path('resources/lang/'. $brandCode .'/vi.json'));
//            }
//            return $jsonString;
//        });
//
//        return json_decode($value, true);

        $lang = \Illuminate\Support\Facades\App::getLocale();
        $lang = substr($lang, -2); // $lang có dạng 'brandCode/vi' hoặc 'brandCode/en' => cắt chuỗi
        $brandCode = session()->get('brand_code');
        $key = $brandCode . '_' . $lang;
        $jsonString = '';

        // session(['secret_service' => $getSecretKeyService->value]);


        if ($brandCode != null) {
            // check exist folder language of brand
            $path = base_path() . '/resources/lang/' . $brandCode;
            if (!file_exists($path)) {
                $brandCode = '';
            }
        } else {
            $brandCode = '';
        }

        if ($lang == 'en') {
            $jsonString = file_get_contents(base_path('resources/lang/' . $brandCode . '/en.json'));
        } else if ($lang == 'vi') {
            $jsonString = file_get_contents(base_path('resources/lang/' . $brandCode . '/vi.json'));
        }

        return json_decode($jsonString, true);

    })->name('translate');
});
