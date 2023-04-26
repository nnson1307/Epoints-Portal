<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'on-call', 'namespace' => 'Modules\OnCall\Http\Controllers'], function () {
    //Quản lý extension
    Route::group(['prefix' => 'extension'], function () {
        Route::get('/', 'ExtensionController@index')->name('extension');
        Route::post('list', 'ExtensionController@listAction')->name('extension.list');
        Route::post('update-status', 'ExtensionController@updateStatusAction')->name('extension.update-status');
        //Cấu hình tài khoản
        Route::post('modal-account', 'ExtensionController@modalAccount')->name('extension.modal-account');
        Route::post('submit-setting', 'ExtensionController@submitSettingAccount')->name('extension.submit-setting');
        //Phân bổ nhân viên
        Route::post('modal-assign', 'ExtensionController@modalAssignAction')->name('extension.modal-assign');
        Route::post('submit-assign', 'ExtensionController@submitAssignAction')->name('extension.submit-assign');
        //Đồng bộ extension
        Route::post('sync-extension', 'ExtensionController@syncExtensionAction')->name('extension.sync-extension');

        Route::post('get-modal-calling', 'ExtensionController@getModalCalling')->name('extension.get-modal-calling');
        Route::post('submit-care-calling', 'ExtensionController@submitCareFromOncall')->name('extension.submit-care-calling');
        Route::post('search-work-list', 'ExtensionController@searchWorkLead')->name('extension.search-work-list');
        Route::post('get-info-deal', 'ExtensionController@getInfoDeal')->name('extension.get-info-deal');
        Route::post('get-list-deal-paging', 'ExtensionController@listDealAction')->name('extension.get-list-deal-paging');
    });
    //Lịch sử cuộc gọi
    Route::group(['prefix' => 'history'], function () {
        Route::get('', 'HistoryController@index')->name('oncall.history');
        Route::post('list', 'HistoryController@listAction')->name('oncall.history.list');
        Route::get('show/{id}', 'HistoryController@show')->name('oncall.history.show');
    });

    //Báo cáo nhân viên
    Route::group(['prefix' => 'report-staff'], function () {
        Route::get('', 'ReportStaffController@index')->name('oncall.report-staff');
        Route::post('load-chart', 'ReportStaffController@loadChartAction')->name('oncall.report-staff.load-chart');
        Route::post('load-list-1', 'ReportStaffController@loadList1Action')->name('oncall.report-staff.load-list-1');
    });

    //Báo cáo tổng quan
    Route::group(['prefix' => 'report-overview'], function () {
        Route::get('', 'ReportOverviewController@index')->name('oncall.report-overview');
        Route::post('load-chart', 'ReportOverviewController@loadChartAction')->name('oncall.report-overview.load-chart');
        Route::post('load-list-1', 'ReportOverviewController@loadList1Action')->name('oncall.report-overview.load-list-1');
    });
});
