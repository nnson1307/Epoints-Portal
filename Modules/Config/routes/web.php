<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'config', 'namespace' => 'Modules\Config\Http\Controllers'], function () {
    Route::group(['prefix' => 'config-review'], function () {
        Route::get('config-order', 'ConfigReviewController@configOrderAction')->name('config.config-review.config-order');

        //Thêm nhanh cú pháp gợi ý
        Route::post('insert-content-suggest', 'ConfigReviewController@insertContentSuggestAction')->name('config.config-review.insert-content-suggest');
        Route::post('update-config-order', 'ConfigReviewController@updateConfigOrderAction')->name('config.config-review.update-config-order');
    });

    Route::group(['prefix' => 'customer-parameter'], function () {
        Route::get('/', 'CustomerParameterController@index')->name('config.customer-parameter');
        Route::post('list', 'CustomerParameterController@listAction')->name('config.customer-parameter.list');
        Route::get('create', 'CustomerParameterController@create')->name('config.customer-parameter.create');
        Route::post('store', 'CustomerParameterController@store')->name('config.customer-parameter.store');
        Route::get('edit/{id}', 'CustomerParameterController@edit')->name('config.customer-parameter.edit');
        Route::post('update', 'CustomerParameterController@update')->name('config.customer-parameter.update');
        Route::post('destroy', 'CustomerParameterController@destroy')->name('config.customer-parameter.destroy');
    });

    Route::group(['prefix' => 'config-reject-order'], function () {
        Route::get('/', 'ConfigRejectOrderController@index')->name('config.reject-order');
        Route::post('save', 'ConfigRejectOrderController@saveAction')->name('config.reject-order.save');
    });
});
