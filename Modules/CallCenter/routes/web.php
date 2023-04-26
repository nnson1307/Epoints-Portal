<?php
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'call-center', 'namespace' => 'Modules\CallCenter\Http\Controllers'], function () {
    Route::get('/', 'CallCenterController@index')->name('call-center.list');
    Route::post('/', 'CallCenterController@getList')->name('call-center.list');
    Route::post('/show-modal-search-customer', 'CallCenterController@showModalSearchCustomerAction')->name('call-center.show-modal-search-customer');
    Route::post('/search-customer', 'CallCenterController@searchCustomerAction')->name('call-center.search-customer');
    Route::post('/customer-info', 'CallCenterController@showModalCustomerInfoAction')->name('call-center.customer-info');
    Route::post('/customer-info-success', 'CallCenterController@showModalCustomerInfoSuccessAction')->name('call-center.customer-info-success');
    Route::post('/load-journey', 'CallCenterController@loadOptionJourney')->name('call-center.load-option-journey');
    Route::post('/create-not-info', 'CallCenterController@createCustomerRequestNotInfoAction')->name('call-center.create-not-info');
    Route::post('/create-customer-request', 'CallCenterController@createCustomerRequestAction')->name('call-center.create-customer-request');

    /**
     * Report
     */
    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportCallCenterController@index')->name('report-call-center.list');
        Route::post('/get-chart-month', 'ReportCallCenterController@getChartByMonth')->name('report-call-center.get-chart-month');
        Route::post('/get-chart-month-by-staff', 'ReportCallCenterController@getChartStaffByMonth')->name('report-call-center.get-chart-month-by-staff');
    });
});