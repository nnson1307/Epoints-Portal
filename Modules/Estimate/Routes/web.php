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

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'estimate'], function () {
    // QUOTA ESTIMATE
    Route::group(['prefix' => 'quota'], function () {
        Route::get('/{id}', ['uses' => 'EstimateController@index', 'as' => 'estimate.quota.quota-estimate']);
        Route::post('/list-week', ['uses' => 'EstimateController@getListWeekEstimate', 'as' => 'estimate.quota.quota-estimate.list-week']);
        Route::post('/list-month', ['uses' => 'EstimateController@getListMonthEstimate', 'as' => 'estimate.quota.quota-estimate.list-month']);
        Route::post('/add', ['uses' => 'EstimateController@addQuotaEstimate', 'as' => 'estimate.quota.quota-estimate.add']);
        Route::post('/edit', ['uses' => 'EstimateController@editQuotaEstimate', 'as' => 'estimate.quota.quota-estimate.edit']);
        // Route::post('/edit/{id}', ['uses' => 'EstimateController@editQuotaEstimate', 'as' => 'estimate.quota.quota-estimate.edit']);
        Route::post('/show-modal-estimate-edit', 'EstimateController@showModalEdit')->name('estimate.modal-edit');
        Route::post('/show-modal-estimate-add', 'EstimateController@showModalAdd')->name('estimate.modal-add');
    });
});