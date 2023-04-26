<?php

Route::group(['middleware' =>  ['web', 'auth'], 'prefix' => 'services', 'namespace' => 'Modules\Services\Http\Controllers'], function()
{

    //SERVICES
    Route::group(['prefix'=>'services'],function (){
        Route::get('/', 'ServicesController@indexAction')->name('services');
        Route::post('list','ServicesController@listAction')->name('services.list');
        Route::post('change-status','ServicesController@changeStatusAction')->name('services.change-status');
        Route::get('add','ServicesController@addAction')->name('services.add');
        Route::post('add','ServicesController@submitAddAction')->name('services.submitadd');
        Route::get('edit/{id}','ServicesController@editAction')->name('services.edit');
        Route::post('edit/{id}','ServicesController@submitEditAction')->name('services.submitedit');
        Route::post('remove/{id}','ServicesController@removeAction')->name('services.remove');
        Route::post('uploads','ServicesController@uploadsAction')->name('services.uploads');
        Route::post('uploads-delete','ServicesController@deleteTempFileAction')->name('services.uploads.delete');
        Route::post('export-excel', 'ServicesController@exportExcelAction')->name('services.export-excel');
        Route::post('import-excel', 'ServicesController@importExcelAction')->name('services.import-excel');
        Route::get('import-excel', 'ServicesController@importExcel')->name('services.import-excel');
    });
});
