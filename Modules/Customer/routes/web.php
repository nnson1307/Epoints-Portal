<?php
//'account'
Route::group(['middleware' =>  ['web', 'auth'], 'prefix' => 'customer', 'namespace' => 'Modules\Customer\Http\Controllers'], function()
{
    Route::group(['prefix' => 'customer-info-type'], function () {
        Route::get('/', 'CustomerInfoTypeController@index')->name('customer-info-type');
        Route::post('list', 'CustomerInfoTypeController@listAction')->name('customer-info-type.list');
        Route::post('add', 'CustomerInfoTypeController@create')->name('customer-info-type.add');
        Route::post('store', 'CustomerInfoTypeController@store')->name('customer-info-type.store');
        Route::post('edit', 'CustomerInfoTypeController@edit')->name('customer-info-type.edit');
        Route::post('update', 'CustomerInfoTypeController@update')->name('customer-info-type.update');
        Route::post('delete', 'CustomerInfoTypeController@delete')->name('customer-info-type.delete');
        Route::post('update-status', 'CustomerInfoTypeController@updateStatus')->name('customer-info-type.update-status');
    });

    Route::group(['prefix' => 'customer-info-temp'], function () {
        Route::get('/', 'CustomerInfoTempController@index')->name('customer-info-temp');
        Route::post('list', 'CustomerInfoTempController@listAction')->name('customer-info-temp.list');
        Route::get('confirm/{id}', 'CustomerInfoTempController@confirmAction')->name('customer-info.confirm');
        Route::post('submit-confirm', 'CustomerInfoTempController@submitConfirmAction')
            ->name('customer-info.submit-confirm');
    });

    Route::group(['prefix' => 'customer-remind-use'], function () {
        Route::get('/', 'CustomerRemindUseController@index')->name('customer-remind-use');
        Route::post('list', 'CustomerRemindUseController@listAction')->name('customer-remind-use.list');
        Route::get('edit/{id}', 'CustomerRemindUseController@edit')->name('customer-remind-use.edit');
        Route::post('update', 'CustomerRemindUseController@update')->name('customer-remind-use.update');
        Route::post('modal-care', 'CustomerRemindUseController@modalCareAction')->name('customer-remind-use.modal-care');
        Route::post('submit-care', 'CustomerRemindUseController@submitCareAction')->name('customer-remind-use.submit-care');
        Route::get('show/{id}', 'CustomerRemindUseController@show')->name('customer-remind-use.show');
    });
});
