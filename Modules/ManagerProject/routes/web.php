<?php

use Illuminate\Support\Facades\Route;

//Route::group(['middleware' => ['web', 'auth','account'], 'prefix' => 'manager-work', 'namespace' => 'Modules\ManagerWork\Http\Controllers'], function () {
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'manager-project', 'namespace' => 'Modules\ManagerProject\Http\Controllers'], function () {

    Route::group(['prefix' => 'project'], function () {
        Route::get('/', 'ProjectController@indexAction')->name('manager-project.project');
        Route::post('list', 'ProjectController@listAction')->name('manager-project.project.list');
        Route::get('add', 'ProjectController@addAction')->name('manager-project.project.add');
        Route::post('store', 'ProjectController@storeAction')->name('manager-project.project.store');
        Route::get('edit/{id}', 'ProjectController@editAction')->name('manager-project.project.edit');
        Route::post('update', 'ProjectController@updateAction')->name('manager-project.project.update');
        Route::post('remove/{id}', 'ProjectController@removeAction')->name('manager-project.project.remove');
        Route::post('change-status', 'ProjectController@changeStatusAction')->name('manager-project.project.change-status');
        Route::post('list-customer-type', 'ProjectController@getListCustomerByType')->name('manager-project.project.type');
        Route::post('name-prefix', 'ProjectController@getNamePrefix')->name('manager-project.project.name.prefix');
        Route::post('config-list-project', 'ProjectController@configListProject')->name('manager-project.project.config.list.project');
        Route::get('show/{id}', 'ProjectController@showAction')->name('manager-project.project.show');

        Route::get('project-info-overview/{id}', 'ProjectController@projectInfoOverview')->name('manager-project.project.project-info-overview');
        Route::get('project-info-report/{id}', 'ProjectController@projectInfoReport')->name('manager-project.project.project-info-report');
        Route::get('project-info-all-issue/{id}', 'ProjectController@projectInfoAllIssue')->name('manager-project.project.project-info-all-issue');
        Route::post('delete-remind', 'ProjectController@deleteRemind')->name('manager-project.project.delete-remind');
        Route::post('popup-add-remind', 'ProjectController@popupAddRemind')->name('manager-project.project.popup-add-remind');
        Route::get('project-info-work/{id}', 'ProjectController@projectInfoWork')->name('manager-project.project.project-info-work');
        Route::post('project-info-work-list', 'ProjectController@projectInfoWorkList')->name('manager-project.project.project-info-work-list');
        Route::get('project-info-phase/{id}', 'ProjectController@projectInfoPhase')->name('manager-project.project.project-info-phase');
        Route::get('project-info-issue/{id}', 'ProjectController@projectInfoIssue')->name('manager-project.project.project-info-issue');
        Route::post('popup-add-issue', 'ProjectController@popupAddIssue')->name('manager-project.project.popup-add-issue');
        Route::post('add-issue', 'ProjectController@addIssue')->name('manager-project.project.add-issue');
        Route::post('delete-issue', 'ProjectController@deleteIssue')->name('manager-project.project.delete-issue');
        Route::post('popup-edit-issue', 'ProjectController@popupEditIssue')->name('manager-project.project.popup-edit-issue');
        Route::post('edit-issue', 'ProjectController@editIssue')->name('manager-project.project.edit-issue');
        Route::get('project-info-expenditure/{id}', 'ProjectController@projectInfoExpenditure')->name('manager-project.project.project-info-expenditure');
        Route::post('project-info-expenditure-list', 'ProjectController@projectInfoExpenditureList')->name('manager-project.project.project-info-expenditure-list');
        Route::get('export-piospa', 'ProjectCophasentroller@exportPiospa')->name('manager-project.project.export-piospa');
        Route::post('popup-add-payment', 'ProjectController@popupAddPayment')->name('manager-project.project.popup-add-payment');
        Route::post('popup-add-receipt', 'ProjectController@popupAddReceipt')->name('manager-project.project.popup-add-receipt');
        Route::post('add-new-payment', 'ProjectController@addNewPayment')->name('manager-project.project.add-new-payment');
        Route::post('load-option-obj-accounting', 'ProjectController@loadOptionObjectAccounting')
            ->name('manager-project.project.load-option-obj-accounting');
        Route::post('add-new-receipt', 'ProjectController@addNewReceipt')->name('manager-project.project.add-new-receipt');

    });

    Route::group(['prefix' => 'phase'], function () {
        Route::get('/add/{id}', 'PhaseController@addAction')->name('manager-project.phase.add');
        Route::post('/store', 'PhaseController@storeAction')->name('manager-project.phase.store');
        Route::post('/remove', 'PhaseController@removeAction')->name('manager-project.phase.remove');
        Route::post('/showPopup', 'PhaseController@showPopup')->name('manager-project.phase.showPopup');
        Route::post('/update', 'PhaseController@updateAction')->name('manager-project.phase.update');
        Route::post('/update', 'PhaseController@updateAction')->name('manager-project.phase.update');
        Route::get('/template-sample/{id}', 'PhaseController@templateSample')->name('manager-project.phase.template-sample');
        Route::post('/change-sample', 'PhaseController@changeSample')->name('manager-project.phase.change-sample');
    });

    Route::group(['prefix' => 'comment'], function () {
        Route::get('/{id}', 'CommentController@index')->name('manager-project.comment');
        Route::post('/add-comment', 'CommentController@addComment')->name('manager-project.comment.add-comment');
    });

    Route::group(['prefix' => 'remind'], function () {
        Route::post('/show-popup-remind-popup', 'RemindController@showPopupRemindPopup')->name('manager-project.remind.show-popup-remind-popup');
        Route::post('/add-remind-work', 'RemindController@addRemindWork')->name('manager-project.remind.add-remind-work');
    });

    Route::group(['prefix' => 'project-overview'], function () {
        Route::get('/', 'ProjectOverViewController@index')->name('project-overview');
    });

    Route::group(['prefix' => 'work', 'as' => 'manager-project.'], function() {
        Route::get('/', 'WorkController@indexAction')->name('work');
        Route::get('/export', 'WorkController@exportAction')->name('work.export');
        Route::get('/kanban-view', 'WorkController@kanbanViewAction')->name('work.kanban-view');
        Route::post('/showPopupStaff', 'WorkController@showPopupStaff')->name('work.kanban-view.show-popup-staff');
        Route::post('/searchPagePopupStaff', 'WorkController@searchPagePopupStaff')->name('work.kanban-view.search-page-popup-staff');
        // Route::get('/get-data-kanban-view', 'WorkController@loadKanBan')->name('work.kanban-view-load');
        Route::post('list', 'WorkController@listAction')->name('work.list');
        Route::post('add', 'WorkController@addAction')->name('work.add');
        Route::post('show-add', 'WorkController@showAddAction')->name('work.show-add');
        Route::post('copy', 'WorkController@copyAction')->name('work.copy');
        Route::post('approve', 'WorkController@approveAction')->name('work.approve');
        Route::post('reject', 'WorkController@rejectAction')->name('work.reject');

        Route::post('load-comment', 'WorkController@loadComment')->name('work.load-comment');
        Route::post('load-form-update-process', 'WorkController@loadFormUpdateProcess')->name('work.load-form-update-process');
        Route::post('load-form-update-date-end', 'WorkController@loadFormUpdateDateEnd')->name('work.load-form-update-date-end');
        Route::post('edit-element-item', 'WorkController@editElementItem')->name('work.edit-element-item');

        Route::post('edit', 'WorkController@editAction')->name('work.edit');
        Route::post('edit-submit', 'WorkController@submitEditAction')->name('work.submit-edit');
        Route::get('detail/{id}', 'WorkController@detailAction')->name('work.detail');
        Route::get('detail-history/{id}', 'WorkController@detailHistoryAction')->name('work.detail-history');
        Route::get('detail-document/{id}', 'WorkController@detailDocumentAction')->name('work.detail-document');
        Route::get('detail-remind/{id}', 'WorkController@detailRemindAction')->name('work.detail-remind');
        Route::get('detail-child-work/{id}', 'WorkController@detailChildWorkAction')->name('work.detail-child-work');
        Route::post('remove/{id}', 'WorkController@removeAction')->name('work.remove');
        Route::post('change-status', 'WorkController@changeStatusAction')->name('work.change-status');
        Route::post('save-config', 'WorkController@saveConfig')->name('work.save-config');

        Route::post('upload-file', 'WorkController@uploadFile')->name('work.detail.upload-file')->middleware('s3');
        Route::post('add-comment', 'WorkController@addComment')->name('work.detail.add-comment');
        Route::post('show-form-comment', 'WorkController@showFormComment')->name('work.detail.show-form-comment');
        Route::post('search-list-history', 'WorkController@searchListHistory')->name('work.detail.search-list-history');
        Route::post('show-popup-upload-file', 'WorkController@showPopupUploadFile')->name('work.detail.show-popup-upload-file');
        Route::post('show-popup-upload-file-work', 'WorkController@showPopupUploadFileWork')->name('work.detail.show-popup-upload-file-work');
        Route::post('add-file-document', 'WorkController@addFileDocument')->name('work.detail.add-file-document');
        Route::post('remove-file-document', 'WorkController@removeFileDocument')->name('work.detail.remove-file-document');
        Route::post('show-popup-remind-popup', 'WorkController@showPopupRemindPopup')->name('work.detail.show-popup-remind-popup');
        Route::post('add-remind-work', 'WorkController@addRemindWork')->name('work.staff-overview.add-remind-work');
        Route::post('remove-remind', 'WorkController@removeRemind')->name('work.detail.remove-remind');
        Route::post('search-remind', 'WorkController@searchRemind')->name('work.detail.search-remind');
        Route::post('change-status-remind', 'WorkController@changeStatusRemind')->name('work.detail.change-status-remind');
        Route::post('show-popup-work-child', 'WorkController@showPopupWorkChild')->name('work.detail.show-popup-work-child');
        Route::post('save-child-work', 'WorkController@saveChildWork')->name('work.detail.save-child-work');
        Route::post('remove-work', 'WorkController@removeWork')->name('work.detail.remove-work');
        Route::post('search-work', 'WorkController@searchWork')->name('work.detail.search-work');
        Route::post('search-document', 'WorkController@searchDocument')->name('work.detail.search-document');
        Route::post('change-customer', 'WorkController@changeCustomer')->name('work.detail.change-customer');
        Route::post('change-tab-detail-work', 'WorkController@changeTabDetailWork')->name('work.detail.change-tab-detail-work');
        Route::post('show-popup-change-folder', 'WorkController@showPopupChangeFolder')->name('work.detail.show-popup-change-folder');
        Route::post('submit-change-folder', 'WorkController@submitChangeFolder')->name('work.detail.submit-change-folder');
        Route::post('/change-branch-staff', 'WorkController@changeBranchStaff')->name('work.detail.change-branch-staff');
        Route::post('/check-work-child', 'WorkController@checkWorkChild')->name('work.check-work-child');
        Route::get('add-work', 'WorkController@addWork')->name('work.add-work');
        Route::get('edit-work/{id}', 'WorkController@editWork')->name('work.edit-work');
        Route::post('show-pop-staff-support', 'WorkController@showPopStaffSupportAction')->name('work.show-pop-staff-support');
        Route::post('list-staff-support', 'WorkController@listStaffSupportAction')->name('work.list-staff-support');
        //Chọn nhân viên hỗ trợ
        Route::post('choose-staff-support', 'WorkController@chooseStaffSupportAction')->name('work.choose-staff-support');
        //Bỏ chọn nhân viên hỗ trợ
        Route::post('un-choose-staff-support', 'WorkController@unChooseStaffSupportAction')->name('work.un-choose-staff-support');
        //Submit chọn nhân viên hỗ trợ
        Route::post('submit-choose-staff-support', 'WorkController@submitChooseStaffSupportAction')->name('work.submit-choose-staff-support');
    });

    Route::group(['prefix' => 'member', 'as' => 'manager-project.'], function () {
        Route::get('/{id}', 'MemberController@indexAction')->name('member');
        Route::post('/store', 'MemberController@storeAction')->name('member.store');
        Route::post('/list', 'MemberController@listAction')->name('member.list');
        Route::post('/show', 'MemberController@showAction')->name('member.show');
        Route::post('/edit', 'MemberController@editAction')->name('member.edit');
        Route::post('/update', 'MemberController@updateAction')->name('member.update');
        Route::post('/remove', 'MemberController@removeAction')->name('member.remove');
        Route::post('/show-popup-add-staff', 'MemberController@showPopupAddStaff')->name('member.show-popup-add-staff');
    });

    Route::group(['prefix' => 'document', 'as' => 'manager-project.'], function() {
        Route::get('/', 'DocumentController@indexAction')->name('document');
    });

    Route::group(['prefix' => 'history', 'as' => 'manager-project.'], function () {
        Route::get('/', 'HistoryController@indexAction')->name('history');
        Route::post('/search', 'HistoryController@searchAction')->name('history.search');
    });

    Route::group(['prefix' => 'manage-config'], function () {
        Route::get('/status', 'ManageConfigController@indexStatusAction')->name('manager-project.manage-config.status');
        Route::get('/status-edit', 'ManageConfigController@indexStatusEditAction')->name('manager-project.manage-config.status-edit');
        Route::post('/add-status-config', 'ManageConfigController@addStatusConfig')->name('manager-project.manage-config.add-status-config');
        Route::post('/remove-status-config', 'ManageConfigController@removeStatusConfig')->name('manager-project.manage-config.remove-status-config');
        Route::post('/update-config-status', 'ManageConfigController@updateConfigStatus')->name('manager-project.manage-config.update-config-status');

        Route::post('/show-popup', 'ManageConfigController@showPopup')->name('manager-project.manage-config.notification.show-popup');
        Route::post('/update-active', 'ManageConfigController@updateActive')->name('manager-project.manage-config.notification.update-active');
    });


});

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'manager-project', 'namespace' => 'Modules\ManagerProject\Http\Controllers'], function () {
    Route::get('/auto-create-phase', 'PhaseController@autoCreatePhase');
});




