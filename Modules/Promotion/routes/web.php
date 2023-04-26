<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'promotion', 'namespace' => 'Modules\Promotion\Http\Controllers'], function () {

    Route::group(['prefix' => 'promotion'], function () {
        Route::get('', 'PromotionController@index')->name('promotion');
        Route::post('list', 'PromotionController@listAction')->name('promotion.list');
        Route::get('create', 'PromotionController@create')->name('promotion.create');
        Route::post('show-popup', 'PromotionController@popupAction')->name('promotion.popup');
        Route::post('list-product', 'PromotionController@listProductAction')->name('promotion.list-product');
        Route::post('list-service', 'PromotionController@listServiceAction')->name('promotion.list-service');
        Route::post('list-service-card', 'PromotionController@listServiceCardAction')->name('promotion.list-service-card');
        Route::post('choose-all', 'PromotionController@chooseAllAction')->name('promotion.choose-all');
        Route::post('choose', 'PromotionController@chooseAction')->name('promotion.choose');
        Route::post('un-choose-all', 'PromotionController@unChooseAllAction')->name('promotion.un-choose-all');
        Route::post('un-choose', 'PromotionController@unChooseAction')->name('promotion.un-choose');
        Route::post('submit-choose', 'PromotionController@submitChooseAction')->name('promotion.submit-choose');
        Route::post('list-discount', 'PromotionController@listDiscountAction')->name('promotion.list-discount');
        Route::post('list-gift', 'PromotionController@listGiftAction')->name('promotion.list-gift');
        Route::post('change-price', 'PromotionController@changePriceAction')->name('promotion.change-price');
        Route::post('remove-tr', 'PromotionController@removeTrAction')->name('promotion.remove-tr');
        Route::post('change-status-tr', 'PromotionController@changeStatusAction')->name('promotion.change-status-tr');
        Route::post('list-option', 'PromotionController@listOptionAction')->name('promotion.list-option');
        Route::post('change-gift-type', 'PromotionController@changeGiftTypeAction')->name('promotion.change-gift-type');
        Route::post('change-gift', 'PromotionController@changeGiftAction')->name('promotion.change-gift');
        Route::post('change-quantity-buy', 'PromotionController@changeQuantityBuyAction')->name('promotion.change-quantity-buy');
        Route::post('change-number-gift', 'PromotionController@changeNumberGiftAction')->name('promotion.change-number-gift');
        Route::post('clear-list-all', 'PromotionController@clearListAllAction')->name('promotion.clear-list-all');
        Route::post('store', 'PromotionController@store')->name('promotion.store');
        Route::get('edit/{id}', 'PromotionController@edit')->name('promotion.edit');
        Route::post('update', 'PromotionController@update')->name('promotion.update');
        Route::post('destroy', 'PromotionController@destroy')->name('promotion.destroy');
        Route::post('change-status-promotion', 'PromotionController@changeStatusPromotionAction')
            ->name('promotion.change-status-promotion');
        Route::get('show/{id}', 'PromotionController@show')->name('promotion.detail');
        Route::post('list-discount-detail', 'PromotionController@listDiscountDetailAction')->name('promotion.list-discount-detail');
        Route::post('list-gift-detail', 'PromotionController@listGiftDetailAction')->name('promotion.list-gift-detail');
        Route::post('load-session-all', 'PromotionController@loadSessionAction')->name('promotion.load-session-all');
    });

});
