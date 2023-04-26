<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'dashbroad', 'namespace' => 'Modules\Dashbroad\Http\Controllers'], function () {

    Route::get('/', 'DashbroadController@index')->name('dashbroad');
    Route::post('/get-list-order', 'DashbroadController@getListOrder')->name('dashbroad.list-order');
    Route::post('/get-list-appointment', 'DashbroadController@getListAppointment')->name('dashbroad.list-appointment');
    Route::post('/get-list-services', 'DashbroadController@getListServices')->name('dashbroad.list-services');
    Route::post('/get-list-birthday', 'DashbroadController@getListBirthday')->name('dashbroad.list-birthday');
    Route::get('/get-appointment-by-date', 'DashbroadController@getAppointmentByDate')->name('dashbroad.appointment-by-date');
    Route::post('/get-order-by-month-year', 'DashbroadController@getOrderByMonthYear')->name('dashbroad.order-by-month-year');
    Route::post('/get-order-by-object-type', 'DashbroadController@getOrderByObjectType')->name('dashbroad.order-by-object-type');
    Route::post('/get-top-service', 'DashbroadController@getTopService')->name('dashbroad.get-top-service');
    Route::post('/get-dashboard-ticket', 'DashbroadController@dashboardTicket')->name('dashbroad.get-dashboard-ticket');
    Route::post('/get-dashboard-customer-request-today', 'DashbroadController@getListCustomerRequestToDay')->name('dashbroad.get-customer-request-today');

    Route::group(['prefix' => 'dashboard-config'], function () {
        Route::get('', 'DashBroadConfigController@indexAction')->name('dashbroad.dashboard-config');
        Route::post('list', 'DashBroadConfigController@listAction')->name('dashbroad.dashboard-config.list');
        Route::get('/create', 'DashBroadConfigController@createAction')->name('dashbroad.dashboard-config.create');
        Route::post('/pop-create', 'DashBroadConfigController@popCreateAction')->name('dashbroad.dashboard-config.pop-create');
        Route::post('/submit-create-pop', 'DashBroadConfigController@submitPopCreateAction')->name('dashbroad.dashboard-config.submit-create-pop');
        Route::post('/list-widget', 'DashBroadConfigController@getListWidget')->name('dashbroad.dashboard-config.list-widget');
        Route::post('/create-dashboard', 'DashBroadConfigController@createDashboardAction')->name('dashbroad.dashboard-config.create-dashboard');
        Route::post('/remove-dashboard', 'DashBroadConfigController@removeDashboardAction')->name('dashbroad.dashboard-config.remove-dashboard');
        Route::get('/detail', 'DashBroadConfigController@detailAction')->name('dashbroad.dashboard-config.detail');
        Route::post('/change-status', 'DashBroadConfigController@changeStatusAction')->name('dashbroad.dashboard-config.change-status');
        Route::get('/edit', 'DashBroadConfigController@editAction')->name('dashbroad.dashboard-config.edit');
        Route::post('/pop-edit', 'DashBroadConfigController@popEditAction')->name('dashbroad.dashboard-config.pop-edit');
        Route::post('/submit-edit-pop', 'DashBroadConfigController@submitPopEditAction')->name('dashbroad.dashboard-config.submit-edit-pop');
        Route::post('/edit-dashboard', 'DashBroadConfigController@editDashboardAction')->name('dashbroad.dashboard-config.edit-dashboard');
    });
});
