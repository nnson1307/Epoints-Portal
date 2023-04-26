<?php
//, 'auth', 'account'
Route::group([
    'middleware' =>  ['web', 'auth'],
    'prefix' => 'report-sale', 'namespace' => 'Modules\ReportSale\Http\Controllers'
], function () {
    Route::get('/', 'ReportSaleController@index')->name('report-sale');
    Route::post('/get-total', 'ReportSaleController@getTotal')->name('report-sale.get-total');
    Route::post('/get-chart-total', 'ReportSaleController@getChartTotal')->name('report-sale.get-chart-total');
    Route::post('/get-chart-total-order', 'ReportSaleController@getChartTotalOrder')->name('report-sale.get-chart-total-order');
    Route::post('/get-chart-total-by-branch', 'ReportSaleController@getChartTotalByBranch')->name('report-sale.get-chart-total-by-branch');
    Route::post('/get-chart-total-order-by-branch', 'ReportSaleController@getTotalOrdersByBranch')->name('report-sale.get-chart-total-order-by-branch');

    Route::post('/show-list-orders', 'ReportSaleController@showModalListOrders')->name('report-sale.show-list-orders');
    Route::post('/show-list-orders-action', 'ReportSaleController@listOrdersAction')->name('report-sale.show-list-orders-action');
    
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'ReportSaleCustomerController@index')->name('report-sale-customer');
        Route::post('/get-total', 'ReportSaleCustomerController@getTotal')->name('report-sale-customer.get-total');
        Route::post('/get-chart-total', 'ReportSaleCustomerController@getChartTotal')->name('report-sale-customer.get-chart-total');
        Route::post('/get-chart-total-order', 'ReportSaleCustomerController@getChartTotalOrder')->name('report-sale-customer.get-chart-total-order');
        Route::post('/get-chart-total-by-customer', 'ReportSaleCustomerController@getChartTotalByCustomer')->name('report-sale-customer.get-chart-total-by-customer');
        Route::post('/get-chart-total-order-by-customer', 'ReportSaleCustomerController@getTotalOrdersByCustomer')->name('report-sale-customer.get-chart-total-order-by-customer');
        Route::post('/get-customer', 'ReportSaleCustomerController@getCustomer')->name('report-sale-customer.get-customer');
    });

    Route::group(['prefix' => 'staff'], function () {
        Route::get('/', 'ReportSaleStaffController@index')->name('report-sale-staff');
        Route::post('/get-total', 'ReportSaleStaffController@getTotal')->name('report-sale-staff.get-total');
        Route::post('/get-chart-total', 'ReportSaleStaffController@getChartTotal')->name('report-sale-staff.get-chart-total');
        Route::post('/get-chart-total-order', 'ReportSaleStaffController@getChartTotalOrder')->name('report-sale-staff.get-chart-total-order');
        Route::post('/get-chart-total-by-staff', 'ReportSaleStaffController@getChartTotalByStaff')->name('report-sale-staff.get-chart-total-by-staff');
        Route::post('/get-chart-total-order-by-staff', 'ReportSaleStaffController@getTotalOrdersByStaff')->name('report-sale-staff.get-chart-total-order-by-staff');
    });
});