<?php
Route::group(['middleware' => ['web', 'auth','account'], 'prefix' => 'people', 'namespace' => 'Modules\People\Http\Controllers'], function () {


    Route::group(['prefix' => 'object-group'], function () {
        Route::post('ajax-delete', 'PeopleController@ajaxObjectGroupDelete')->name('people.object-group.ajax-delete');
        Route::post('ajax-change-status', 'PeopleController@ajaxObjectGroupChangeStatus')->name('people.object-group.ajax-change-status');
        Route::get('list', 'PeopleController@objectGroupList')->name('people.object-group.list');
        Route::post('ajax-list', 'PeopleController@ajaxObjectGroupList')->name('people.object-group.ajax-list');
        Route::post('ajax-add-modal', 'PeopleController@ajaxObjectGroupAddModal')->name('people.object-group.ajax-add-modal');
        Route::post('ajax-add', 'PeopleController@ajaxObjectGroupAdd')->name('people.object-group.ajax-add');
        Route::post('ajax-edit-modal', 'PeopleController@ajaxObjectGroupEditModal')->name('people.object-group.ajax-edit-modal');
        Route::post('ajax-edit', 'PeopleController@ajaxObjectGroupEdit')->name('people.object-group.ajax-edit');
        Route::post('ajax-delete-modal', 'PeopleController@ajaxObjectGroupDeleteModal')->name('people.object-group.ajax-delete-modal');
    });

    Route::group(['prefix' => 'object'], function () {
        Route::post('ajax-delete', 'PeopleController@ajaxObjectDelete')->name('people.object.ajax-delete');
        Route::post('ajax-change-status', 'PeopleController@ajaxObjectChangeStatus')->name('people.object.ajax-change-status');
        Route::get('list', 'PeopleController@objectList')->name('people.object.list');
        Route::post('ajax-list', 'PeopleController@ajaxObjectList')->name('people.object.ajax-list');
        Route::post('ajax-add-modal', 'PeopleController@ajaxObjectAddModal')->name('people.object.ajax-add-modal');
        Route::post('ajax-add', 'PeopleController@ajaxObjectAdd')->name('people.object.ajax-add');
        Route::post('ajax-edit-modal', 'PeopleController@ajaxObjectEditModal')->name('people.object.ajax-edit-modal');
        Route::post('ajax-edit', 'PeopleController@ajaxObjectEdit')->name('people.object.ajax-edit');
        Route::post('ajax-delete-modal', 'PeopleController@ajaxObjectDeleteModal')->name('people.object.ajax-delete-modal');
    });

    Route::get('test', 'PeopleController@test');

    Route::group(['prefix' => 'people'], function () {
        Route::get('print-preview', 'PeopleController@printPreview')->name('people.people.print-preview');
        Route::post('ajax-delete', 'PeopleController@ajaxDelete')->name('people.people.ajax-delete');

        Route::get('list', 'PeopleController@list')->name('people.people.list');
        Route::post('ajax-list-modal', 'PeopleController@ajaxListModal')->name('people.people.ajax-list-modal');
        Route::post('ajax-list', 'PeopleController@ajaxList')->name('people.people.ajax-list');
        Route::post('ajax-import-modal', 'PeopleController@ajaxImportModal')->name('people.people.ajax-import-modal');
        Route::post('ajax-import', 'PeopleController@ajaxImport')->name('people.people.ajax-import');
        Route::post('ajax-add-modal', 'PeopleController@ajaxAddModal')->name('people.people.ajax-add-modal');
        Route::post('ajax-add', 'PeopleController@ajaxAdd')->name('people.people.ajax-add');
        Route::post('ajax-edit-modal', 'PeopleController@ajaxEditModal')->name('people.people.ajax-edit-modal');
        Route::post('ajax-edit', 'PeopleController@ajaxEdit')->name('people.people.ajax-edit');
        Route::post('ajax-delete-modal', 'PeopleController@ajaxDeleteModal')->name('people.people.ajax-delete-modal');
        Route::post('ajax-detail-modal', 'PeopleController@ajaxDetailModal')->name('people.people.ajax-detail-modal');
        Route::post('ajax-print-modal', 'PeopleController@ajaxPrintModal')->name('people.people.ajax-print-modal');
        Route::post('ajax-print', 'PeopleController@ajaxPrint')->name('people.people.ajax-print');
        //Import lý lịch công dân
        Route::post('import-excel', 'PeopleController@importAction')->name('people.people.import-excel');
        //Export excel lỗi khi import
        Route::post('export-excel-error', 'PeopleController@exportExcelError')->name('people.people.export-error');
        Route::get('test-view', 'PeopleController@testView');
        //Chọn công dân để in
        Route::post('choose-people', 'PeopleController@choosePeopleAction')->name('people.people.choose-people');
        //Bỏ chọn công dân để in
        Route::post('un-choose-people', 'PeopleController@unChoosePeopleAction')->name('people.people.un-choose-people');
        //In hàng loạt
        Route::get('print-multiple', 'PeopleController@printMultipleAction')->name('people.people.print-multiple');

        //Giấy xác nhận đăng ký NVQS
        Route::get('print-military-service', 'PeopleController@printMilitaryServiceAction')->name('people.people.print-military-service');

        //Cập nhật nhanh
        Route::patch('quick-update', 'PeopleController@quickUpdateAction')->name('people.people.quick-update');

        //Show pop chụp ảnh camera
        Route::post('show-pop-camera', 'PeopleController@showPopCameraAction')->name('people.people.show-pop-camera');
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'PeopleReportController@index')->name('people.report');
        Route::get('/export', 'PeopleReportController@export')->name('people.report.export');
        Route::get('/export-people', 'PeopleReportController@exportPeople')->name('people.report.export-people');
    });

    Route::group(['prefix' => 'verify'], function () {
        Route::post('ajax-list', 'PeopleVerifyController@ajaxList')->name('people.verify.ajax-list');
        Route::post('ajax-add-modal', 'PeopleVerifyController@ajaxAddModal')->name('people.verify.ajax-add-modal');
        Route::post('ajax-add', 'PeopleVerifyController@ajaxAdd')->name('people.verify.ajax-add');
        Route::post('ajax-delete', 'PeopleVerifyController@ajaxDelete')->name('people.verify.ajax-delete');
        Route::post('ajax-edit-modal', 'PeopleVerifyController@ajaxEditModal')->name('people.verify.ajax-edit-modal');
        Route::post('ajax-edit', 'PeopleVerifyController@ajaxEdit')->name('people.verify.ajax-edit');
    });
    
});
