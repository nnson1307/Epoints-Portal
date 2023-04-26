<?php
//Route::group(['middleware' => ['web', 'auth','account'], 'prefix' => 'manager-work', 'namespace' => 'Modules\ManagerWork\Http\Controllers'], function () {
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'manager-work', 'namespace' => 'Modules\ManagerWork\Http\Controllers'], function () {
    Route::get('/', 'ManagerWorkController@indexAction')->name('manager-work');
    Route::get('/export', 'ManagerWorkController@exportAction')->name('manager-work.export');
    // Route::get('/kanban-view', 'ManagerWorkController@kanbanViewAction');
    Route::get('/kanban-view', 'ManagerWorkController@kanbanViewVueAction')->name('manager-work.kanban-view');
    // Route::get('/kanban-view', 'ManagerWorkController@kanbanViewAction')->name('manager-work.kanban-view');

    Route::post('/showPopupStaff', 'ManagerWorkController@showPopupStaff')->name('manager-work.kanban-view.show-popup-staff');
    Route::post('/searchPagePopupStaff', 'ManagerWorkController@searchPagePopupStaff')->name('manager-work.kanban-view.search-page-popup-staff');
    // Route::get('/get-data-kanban-view', 'ManagerWorkController@loadKanBan')->name('manager-work.kanban-view-load');
    Route::post('list', 'ManagerWorkController@listAction')->name('manager-work.list');
    Route::post('add', 'ManagerWorkController@addAction')->name('manager-work.add');
    Route::post('show-add', 'ManagerWorkController@showAddAction')->name('manager-work.show-add');
    Route::post('copy', 'ManagerWorkController@copyAction')->name('manager-work.copy');
    Route::post('approve', 'ManagerWorkController@approveAction')->name('manager-work.approve');
    Route::post('reject', 'ManagerWorkController@rejectAction')->name('manager-work.reject');
    Route::post('getListParentTask', 'ManagerWorkController@getListParentTask')->name('manager-work.getListParentTask');
    Route::post('check-date-work-project', 'ManagerWorkController@checkDateWorkProject')->name('manager-work.check-date-work-project');

    Route::post('load-comment', 'ManagerWorkController@loadComment')->name('manager-work.load-comment');
    Route::post('load-form-update-process', 'ManagerWorkController@loadFormUpdateProcess')->name('manager-work.load-form-update-process');
    Route::post('load-form-update-date-end', 'ManagerWorkController@loadFormUpdateDateEnd')->name('manager-work.load-form-update-date-end');
    Route::post('edit-element-item', 'ManagerWorkController@editElementItem')->name('manager-work.edit-element-item');

    Route::post('edit', 'ManagerWorkController@editAction')->name('manager-work.edit');
    Route::post('edit-submit', 'ManagerWorkController@submitEditAction')->name('manager-work.submit-edit');
    Route::get('detail/{id}', 'ManagerWorkController@detailAction')->name('manager-work.detail');
    Route::get('detail-history/{id}', 'ManagerWorkController@detailHistoryAction')->name('manager-work.detail-history');
    Route::get('detail-document/{id}', 'ManagerWorkController@detailDocumentAction')->name('manager-work.detail-document');
    Route::get('detail-remind/{id}', 'ManagerWorkController@detailRemindAction')->name('manager-work.detail-remind');
    Route::get('detail-child-work/{id}', 'ManagerWorkController@detailChildWorkAction')->name('manager-work.detail-child-work');
    Route::post('remove/{id}', 'ManagerWorkController@removeAction')->name('manager-work.remove');
    Route::post('change-status', 'ManagerWorkController@changeStatusAction')->name('manager-work.change-status');
    Route::post('save-config', 'ManagerWorkController@saveConfig')->name('manager-work.save-config');

    Route::post('upload-file', 'ManagerWorkController@uploadFile')->name('manager-work.detail.upload-file')->middleware('s3');
    Route::post('add-comment', 'ManagerWorkController@addComment')->name('manager-work.detail.add-comment');
    Route::post('show-form-comment', 'ManagerWorkController@showFormComment')->name('manager-work.detail.show-form-comment');
    Route::post('search-list-history', 'ManagerWorkController@searchListHistory')->name('manager-work.detail.search-list-history');
    Route::post('show-popup-upload-file', 'ManagerWorkController@showPopupUploadFile')->name('manager-work.detail.show-popup-upload-file');
    Route::post('show-popup-upload-file-work', 'ManagerWorkController@showPopupUploadFileWork')->name('manager-work.detail.show-popup-upload-file-work');
    Route::post('add-file-document', 'ManagerWorkController@addFileDocument')->name('manager-work.detail.add-file-document');
    Route::post('remove-file-document', 'ManagerWorkController@removeFileDocument')->name('manager-work.detail.remove-file-document');
    Route::post('show-popup-remind-popup', 'ManagerWorkController@showPopupRemindPopup')->name('manager-work.detail.show-popup-remind-popup');
    Route::post('add-remind-work', 'ManagerWorkController@addRemindWork')->name('manager-work.staff-overview.add-remind-work');
    Route::post('remove-remind', 'ManagerWorkController@removeRemind')->name('manager-work.detail.remove-remind');
    Route::post('search-remind', 'ManagerWorkController@searchRemind')->name('manager-work.detail.search-remind');
    Route::post('change-status-remind', 'ManagerWorkController@changeStatusRemind')->name('manager-work.detail.change-status-remind');
    Route::post('show-popup-work-child', 'ManagerWorkController@showPopupWorkChild')->name('manager-work.detail.show-popup-work-child');
    Route::post('save-child-work', 'ManagerWorkController@saveChildWork')->name('manager-work.detail.save-child-work');
    Route::post('remove-work', 'ManagerWorkController@removeWork')->name('manager-work.detail.remove-work');
    Route::post('search-work', 'ManagerWorkController@searchWork')->name('manager-work.detail.search-work');
    Route::post('search-document', 'ManagerWorkController@searchDocument')->name('manager-work.detail.search-document');
    Route::post('change-customer', 'ManagerWorkController@changeCustomer')->name('manager-work.detail.change-customer');
    Route::post('change-tab-detail-work', 'ManagerWorkController@changeTabDetailWork')->name('manager-work.detail.change-tab-detail-work');
    Route::post('show-popup-change-folder', 'ManagerWorkController@showPopupChangeFolder')->name('manager-work.detail.show-popup-change-folder');
    Route::post('submit-change-folder', 'ManagerWorkController@submitChangeFolder')->name('manager-work.detail.submit-change-folder');
    Route::post('/change-branch-staff', 'ManagerWorkController@changeBranchStaff')->name('manager-work.detail.change-branch-staff');
    Route::post('/check-work-child', 'ManagerWorkController@checkWorkChild')->name('manager-work.check-work-child');
    Route::post('/change-parent-task', 'ManagerWorkController@changeParentTask')->name('manager-work.change-parent-task');
    Route::post('/change-list-staff', 'ManagerWorkController@changeListStaff')->name('manager-work.change-list-staff');

    //Show pop chọn nhân viên hỗ trợ
    Route::post('show-pop-staff-support', 'ManagerWorkController@showPopStaffSupportAction')->name('manager-work.show-pop-staff-support');
    Route::post('list-staff-support', 'ManagerWorkController@listStaffSupportAction')->name('manager-work.list-staff-support');
    //Chọn nhân viên hỗ trợ
    Route::post('choose-staff-support', 'ManagerWorkController@chooseStaffSupportAction')->name('manager-work.choose-staff-support');
    //Bỏ chọn nhân viên hỗ trợ
    Route::post('un-choose-staff-support', 'ManagerWorkController@unChooseStaffSupportAction')->name('manager-work.un-choose-staff-support');
    //Submit chọn nhân viên hỗ trợ
    Route::post('submit-choose-staff-support', 'ManagerWorkController@submitChooseStaffSupportAction')->name('manager-work.submit-choose-staff-support');
    //Xoá nhân viên hỗ trợ đã chọn
    Route::post('remove-staff-support', 'ManagerWorkController@removeStaffSupportAction')->name('manager-work.remove-staff-support');

    Route::group(['prefix' => 'project'], function () {
        Route::get('/', 'ProjectController@indexAction')->name('manager-work.project');
        Route::post('list', 'ProjectController@listAction')->name('manager-work.project.list');
        Route::get('add', 'ProjectController@addAction')->name('manager-work.project.add');
        Route::post('store', 'ProjectController@storeAction')->name('manager-work.project.store');
        Route::get('edit/{id}', 'ProjectController@editAction')->name('manager-work.project.edit');
        Route::post('update', 'ProjectController@updateAction')->name('manager-work.project.update');
        Route::post('remove/{id}', 'ProjectController@removeAction')->name('manager-work.project.remove');
        Route::post('change-status', 'ProjectController@changeStatusAction')->name('manager-work.project.change-status');
        Route::post('list-customer-type', 'ProjectController@getListCustomerByType')->name('manager-work.project.type');
        Route::post('name-prefix', 'ProjectController@getNamePrefix')->name('manager-work.project.name.prefix');
        Route::post('config-list-project', 'ProjectController@configListProject')->name('manager-work.project.config.list.project');
        Route::get('show/{id}', 'ProjectController@showAction')->name('manager-work.project.show');
        Route::group(['prefix' => 'member'], function () {
            Route::get('/{id}', 'MemberController@indexAction')->name('manager-work.project.member');
            Route::post('/store', 'MemberController@storeAction')->name('manager-work.project.member.store');
            Route::post('/list', 'MemberController@listAction')->name('manager-work.project.member.list');
            Route::post('/show', 'MemberController@showAction')->name('manager-work.project.member.show');
            Route::post('/edit', 'MemberController@editAction')->name('manager-work.project.member.edit');
            Route::post('/update', 'MemberController@updateAction')->name('manager-work.project.member.update');
            Route::post('/remove', 'MemberController@removeAction')->name('manager-work.project.member.remove');
            Route::post('/show-popup-add-staff', 'MemberController@showPopupAddStaff')->name('manager-work.project.member.show-popup-add-staff');
        });
    });
    Route::group(['prefix' => 'type-work'], function () {
        Route::get('/', 'TypeWorkController@indexAction')->name('manager-work.type-work');
        Route::post('list', 'TypeWorkController@listAction')->name('manager-work.type-work.list');
        Route::post('add', 'TypeWorkController@addAction')->name('manager-work.type-work.add');
        Route::post('edit', 'TypeWorkController@editAction')->name('manager-work.type-work.edit');
        Route::post('edit-submit', 'TypeWorkController@submitEditAction')->name('manager-work.type-work.submit-edit');
        Route::post('remove', 'TypeWorkController@removeAction')->name('manager-work.type-work.remove');
        Route::post('change-status', 'TypeWorkController@changeStatusAction')->name('manager-work.type-work.change-status');
        Route::post('upload-image', 'TypeWorkController@uploadAction')->name('manager-work.upload-image');
    });
    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', 'ManageTagsController@indexAction')->name('manager-work.tag');
        Route::post('list', 'ManageTagsController@listAction')->name('manager-work.tag.list');
        Route::post('add', 'ManageTagsController@addAction')->name('manager-work.tag.add');
        Route::post('edit', 'ManageTagsController@editAction')->name('manager-work.tag.edit');
        Route::post('edit-submit', 'ManageTagsController@submitEditAction')->name('manager-work.tag.submit-edit');
        Route::post('remove/{id}', 'ManageTagsController@removeAction')->name('manager-work.tag.remove');
        Route::post('change-status', 'ManageTagsController@changeStatusAction')->name('manager-work.tag.change-status');
        Route::post('upload-image', 'ManageTagsController@uploadAction')->name('manager-work.tag.upload-image');
    });
    Route::group(['prefix' => 'remind'], function () {
        Route::get('/', 'ManageRedmindController@indexAction')->name('manager-work.manage-redmind');
        Route::post('list', 'ManageRedmindController@listAction')->name('manager-work.manage-redmind.list');
        Route::post('add', 'ManageRedmindController@addAction')->name('manager-work.manage-redmind.add');
        Route::post('edit', 'ManageRedmindController@editAction')->name('manager-work.manage-redmind.edit');
        Route::post('edit-submit', 'ManageRedmindController@submitEditAction')->name('manager-work.manage-redmind.submit-edit');
        Route::post('remove/{id}', 'ManageRedmindController@removeAction')->name('manager-work.manage-redmind.remove');
        Route::post('change-status', 'ManageRedmindController@changeStatusAction')->name('manager-work.manage-redmind.change-status');
    });

    Route::get('translate', function () {
        return trans('manager-work::translate');
    })->name('manager-work.translate');

    Route::group(['prefix' => 'manage-config'], function () {
        Route::get('/role', 'ManageConfigController@indexRoleAction')->name('manager-work.manage-config.role');
        Route::get('/role-edit', 'ManageConfigController@indexRoleEditAction')->name('manager-work.manage-config.role-edit');
        Route::get('/status', 'ManageConfigController@indexStatusAction')->name('manager-work.manage-config.status');
        Route::get('/status-edit', 'ManageConfigController@indexStatusEditAction')->name('manager-work.manage-config.status-edit');
        Route::post('/update-role', 'ManageConfigController@updateRoleAction')->name('manager-work.manage-config.role-update');
        Route::post('/add-status-config', 'ManageConfigController@addStatusConfig')->name('manager-work.manage-config.add-status-config');
        Route::post('/remove-status-config', 'ManageConfigController@removeStatusConfig')->name('manager-work.manage-config.remove-status-config');
        Route::post('/update-config-status', 'ManageConfigController@updateConfigStatus')->name('manager-work.manage-config.update-config-status');

        Route::get('/notification', 'ManageConfigController@indexNotificationAction')->name('manager-work.manage-config.notification');
        Route::get('/notification/edit', 'ManageConfigController@editNotificationAction')->name('manager-work.manage-config.notification.edit');
        Route::post('/show-popup', 'ManageConfigController@showPopup')->name('manager-work.manage-config.notification.show-popup');
        Route::post('/update-notification', 'ManageConfigController@updateNotification')->name('manager-work.manage-config.notification.update-notification');
        Route::post('/update-active', 'ManageConfigController@updateActive')->name('manager-work.manage-config.notification.update-active');
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportController@indexAction')->name('manager-work.report');
        Route::post('list', 'ReportController@listAction')->name('manager-work.report.list');
        Route::get('export', 'ReportController@exportAction')->name('manager-work.report.export');
        Route::get('my-work', 'ReportController@myWork')->name('manager-work.report.my-work');
        Route::post('my-work-update-block', 'ReportController@myWorkUpdateBlock')->name('manager-work.report.my-work-update-block');
        Route::post('get-list-my-work', 'ReportController@getListMyWork')->name('manager-work.report.get-list-my-work');
        Route::post('get-list-my-work-assgin', 'ReportController@getListMyWorkAssign')->name('manager-work.report.get-list-my-work-assign');
        Route::post('work-approve', 'ReportController@workApprove')->name('manager-work.report.work-approve');
        Route::post('search-remind', 'ReportController@searchRemind')->name('manager-work.report.search-remind');
        Route::post('remove-remind', 'ReportController@removeRemind')->name('manager-work.report.remove-remind');
        Route::post('show-popup-remind-popup', 'ReportController@showPopupRemindPopup')->name('manager-work.report.show-popup-remind-popup');
        Route::post('add-remind-work', 'ReportController@addRemindWork')->name('manager-work.report.add-remind-work');
        Route::post('get-chart-my-work', 'ReportController@getChartMyWork')->name('manager-work.report.get-chart-my-work');
        Route::get('get-list-work', 'ReportController@getListWork')->name('manager-work.report.get-list-work');
        Route::post('list-work', 'ReportController@listWork')->name('manager-work.report.list-work');
        Route::post('table-my-work', 'ReportController@tableMyWork')->name('manager-work.report.table-my-work');
        Route::post('table-work-support', 'ReportController@tableWorkSupport')->name('manager-work.report.table-work-support');
    });

    Route::group(['prefix' => 'staff-overview'], function () {
        Route::get('/', 'StaffOverviewController@indexAction')->name('manager-work.staff-overview');
        Route::post('/search-chart', 'StaffOverviewController@searchChart')->name('manager-work.staff-overview.search-chart');
        Route::post('/hot-spot-detection', 'StaffOverviewController@hotSpotDetection')->name('manager-work.staff-overview.hot-spot-detection');
        Route::post('/priority-work', 'StaffOverviewController@priorityWork')->name('manager-work.staff-overview.priority-work');
        Route::post('/popup-list-staff-not-start-work', 'StaffOverviewController@popupListStaffNotStartWork')->name('manager-work.staff-overview.popup-list-staff-not-start-work');
        Route::post('/add-remind-list-staff-not-start', 'StaffOverviewController@addRemindListStaffNotStart')->name('manager-work.staff-overview.add-remind-list-staff-not-start');
        Route::post('/popup-list-work-overdue', 'StaffOverviewController@popupListWorkOverdue')->name('manager-work.staff-overview.popup-list-work-overdue');
        Route::post('/add-remind-work-overdue', 'StaffOverviewController@addRemindWorkOverdue')->name('manager-work.staff-overview.add-remind-work-overdue');
        Route::post('/table-work-status', 'StaffOverviewController@tableWorkStatus')->name('manager-work.staff-overview.table-work-status');
        Route::post('/table-work-level', 'StaffOverviewController@tableWorkLevel')->name('manager-work.staff-overview.table-work-level');

        Route::post('/popup-status', 'StaffOverviewController@popupStatus')->name('manager-work.staff-overview.popup-status');
        Route::post('/popup-process', 'StaffOverviewController@popupProcess')->name('manager-work.staff-overview.popup-process');
        Route::post('/popup-date', 'StaffOverviewController@popupDate')->name('manager-work.staff-overview.popup-date');
        Route::post('/change-status', 'StaffOverviewController@changeStatus')->name('manager-work.staff-overview.change-status');
        Route::post('/change-process', 'StaffOverviewController@changeProcess')->name('manager-work.staff-overview.change-process');
        Route::post('/change-date', 'StaffOverviewController@changeDate')->name('manager-work.staff-overview.change-date');
        Route::get('/iframe', 'StaffOverviewController@iframe')->name('manager-work.staff-overview.iframe');
    });
});

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'manager-work-api',
    'namespace' => 'Modules\ManagerWork\Http\Controllers\Api'
], function () {
    Route::post('list', 'ManagerWorkController@getListWork');
    Route::get('search-options', 'ManagerWorkController@getSearchOption');
});
