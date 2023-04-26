<?php

Route::group([
    'middleware' => ['api'],
    'prefix' => 'api',
    'namespace' => 'Modules\Api\Http\Controllers'
], function () {
    Route::get('/', 'ApiController@index');

//    // Admin Brand
//    Route::group(['prefix' => 'admin-brand'], function () {
//        Route::post('/', 'AdminBrandController@index');
//        Route::post('store', 'AdminBrandController@store');
//    });

//    Service Brand
    Route::group(['prefix' => 'service-brand'], function () {
        Route::post('store', 'AdminServiceBrandController@storeService');
        Route::post('delete', 'AdminServiceBrandController@deleteService');
    });

//    Route::group(['prefix' => 'user-brand'], function () {
//        Route::post('detail', 'UserBrandController@getDetail');
//        Route::post('update-pass', 'UserBrandController@updatePass');
//        Route::post('change-status', 'UserBrandController@ChangeStatus');
//    });
});
