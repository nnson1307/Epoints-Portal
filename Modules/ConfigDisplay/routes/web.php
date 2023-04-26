<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web'], 'prefix' => 'config-display', 'namespace' => 'Modules\ConfigDisplay\Http\Controllers'
], function () {
    Route::get('/', 'ConfigDisplayController@index');
    Route::post('/load-all', 'ConfigDisplayController@loadAll')->name('config-display.configDisplay.load-all');
    Route::get('/show/{id}', 'ConfigDisplayController@show')->name('config-display.configDisplay.show');
    Route::get('/edit/{id}', 'ConfigDisplayController@edit')->name('config-display.configDisplay.edit');
    // Cấu hình hiển thị chi tiết //
    Route::group(['prefix' => 'config-display-detail'], function () {
        Route::post('/load-all', 'ConfigDisplayController@loadAllDetail')->name('config-display-detail.configDisplay.load-all');
        Route::get('/create/{id}', 'ConfigDisplayController@createDetail')->name('config-display-detail.configDisplay.created');
        Route::post('/store', 'ConfigDisplayController@storeDetail')->name('config-display-detail.configDisplay.store');
        Route::get('/show/{id}/{id_config}', 'ConfigDisplayController@showDetail')->name('config-display-detail.configDisplay.show');
        Route::get('/edit/{id}/{id_config}', 'ConfigDisplayController@editDetail')->name('config-display-detail.configDisplay.edit');
        Route::post('/update', 'ConfigDisplayController@updateDetail')->name('config-display-detail.configDisplay.update');
        Route::post('/show-modal-destroy', 'ConfigDisplayController@showModalDestroy')->name('config-display-detail.configDisplay.modal-show-destroy');
        Route::post('/destroy', 'ConfigDisplayController@destroyDetail')->name('config-display-detail.configDisplay.destroy');

    });

    // danh mục đích điến // 

    Route::group(['prefix' => 'config-display-category-destination'], function () {
        Route::post('/load-all-survey', 'ConfigDisplayController@loadAllSurvey')->name('config-display-category-destination.configDisplay.survey');
        Route::post('/load-all-promotion', 'ConfigDisplayController@loadAllPromotion')->name('config-display-category-destination.configDisplay.promotion');
        Route::post('/load-all-product', 'ConfigDisplayController@loadAllProduct')->name('config-display-category-destination.configDisplay.product');
        Route::post('/load-all-post', 'ConfigDisplayController@loadAllPost')->name('config-display-category-destination.configDisplay.post');

    });
});
