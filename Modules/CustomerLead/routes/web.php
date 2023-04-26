<?php
//, 'auth', 'account'
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'customer-lead', 'namespace' => 'Modules\CustomerLead\Http\Controllers'], function () {

     //Thêm bình luận
     Route::post('add-comment', 'CustomerLeadController@addComment')->name('customer-lead.detail.add-comment');
     //show form reply
     Route::post('show-comment-child', 'CustomerLeadController@showFormComment')->name('customer-lead.detail.show-form-comment');
     //get list comment
     Route::post('get-list-comment', 'CustomerLeadController@getListComment')->name('customer-lead.detail.get-list-comment'); 

    Route::group(['prefix' => 'pipeline-category'], function () {
        Route::get('/', 'PipelineCategoryController@index')->name('customer-lead.pipeline-category');
        Route::post('list', 'PipelineCategoryController@listAction')->name('customer-lead.pipeline-category.list');
        Route::get('create', 'PipelineCategoryController@create')->name('customer-lead.pipeline-category.create');
        Route::post('store', 'PipelineCategoryController@store')->name('customer-lead.pipeline-category.store');
        Route::get('edit/{id}', 'PipelineCategoryController@edit')->name('customer-lead.pipeline-category.edit');
        Route::post('update', 'PipelineCategoryController@update')->name('customer-lead.pipeline-category.update');
        Route::post('change-status', 'PipelineCategoryController@changeStatusAction')
            ->name('customer-lead.pipeline-category.change-status');
        Route::post('destroy', 'PipelineCategoryController@destroy')->name('customer-lead.pipeline-category.destroy');
    });

    Route::group(['prefix' => 'customer-lead'], function () {
        Route::get('/', 'CustomerLeadController@index')->name('customer-lead');
        Route::get('detail/{id}', 'CustomerLeadController@detail')->name('customer-lead.detail');
        Route::get('edit/{id}', 'CustomerLeadController@editLead')->name('customer-lead.edit-lead');
        Route::get('add', 'CustomerLeadController@add')->name('customer-lead.add');
        Route::post('list', 'CustomerLeadController@listAction')->name('customer-lead.list');
        Route::post('create', 'CustomerLeadController@create')->name('customer-lead.create');
        Route::post('store', 'CustomerLeadController@store')->name('customer-lead.store');
        Route::post('edit', 'CustomerLeadController@edit')->name('customer-lead.edit');
        Route::post('update', 'CustomerLeadController@update')->name('customer-lead.update');
        Route::post('destroy', 'CustomerLeadController@destroy')->name('customer-lead.destroy');
        Route::post('popup-customer-care', 'CustomerLeadController@popupCustomerCareAction')
            ->name('customer-lead.popup-customer-care');
        Route::post('customer-care', 'CustomerLeadController@customerCareAction')->name('customer-lead.customer-care');
        Route::post('show', 'CustomerLeadController@show')->name('customer-lead.show');

        Route::get('search-options', 'CustomerLeadController@getSearchOption');
        // Route::get('kan-ban-view', 'CustomerLeadController@kanBanViewAction')->name('customer-lead.kan-ban-view');
        Route::get('kanban-view', 'CustomerLeadController@kanBanViewVueAction')->name('customer-lead.kan-ban-view');
        Route::post('load-kan-ban', 'CustomerLeadController@loadKanBanViewAction')
            ->name('customer-lead.load-kan-ban-view');
        Route::post('load-kanban-vue', 'CustomerLeadController@loadKanBanVueAction');
        Route::post('update-journey', 'CustomerLeadController@updateJourneyAction')
            ->name('customer-lead.update-journey');
        Route::post('load-option-journey', 'CustomerLeadController@loadOptionJourney')
            ->name('customer-lead.load-option-journey');
        Route::post('convert-customer-no-deal', 'CustomerLeadController@convertCustomerNoDeal')
            ->name('convert-customer-no-deal');
        Route::post('create-deal', 'CustomerLeadController@createDeal')->name('customer-lead.create-deal');
        Route::post('export-all', 'CustomerLeadController@exportExcelAll')->name('customer-lead.export-all');
        Route::post('import-excel', 'CustomerLeadController@importExcel')->name('customer-lead.import-excel');
        Route::get('export-excel-template', 'CustomerLeadController@exportExcelTemplate')
            ->name('customer-lead.export-excel-template');
        Route::post('popup-list-staff', 'CustomerLeadController@popupListStaff')->name('customer-lead.popup-list-staff');
        Route::post('save-assign-staff', 'CustomerLeadController@saveAssignStaff')->name('customer-lead.save-assign-staff');
        Route::post('revoke-one', 'CustomerLeadController@revokeOne')->name('customer-lead.revoke-one');
        Route::get('/assign', 'CustomerLeadController@assign')->name('customer-lead.assign');
        Route::post('/submit-assign', 'CustomerLeadController@submitAssign')->name('customer-lead.submit-assign');
        Route::post('/revoke', 'CustomerLeadController@revoke')->name('customer-lead.revoke');
        Route::post('/submit-revoke', 'CustomerLeadController@submitRevoke')->name('customer-lead.submit-revoke');
        Route::post('/list-lead-not-assign-yet', 'CustomerLeadController@getListLeadNotAssignYet')
            ->name('customer-lead.list-lead-not-assign-yet');
        Route::post('/load-option-sale', 'CustomerLeadController@optionSaleByArrayDepartment')
            ->name('customer-lead.load-option-sale');
        Route::post('choose-all', 'CustomerLeadController@chooseAllAction')->name('customer-lead.choose-all');
        Route::post('choose', 'CustomerLeadController@chooseAction')->name('customer-lead.choose');
        Route::post('un-choose-all', 'CustomerLeadController@unChooseAllAction')->name('customer-lead.un-choose-all');
        Route::post('un-choose', 'CustomerLeadController@unChooseAction')->name('customer-lead.un-choose');
        Route::post('check-all-lead', 'CustomerLeadController@checkAllLeadAction')->name('customer-lead.check-all-lead');
        Route::post('upload-file', 'CustomerLeadController@uploadFileAction')->name('customer-lead.upload-file');
        Route::post('add-note', 'CustomerLeadController@addNoteAction')->name('customer-lead.add-note');
        Route::post('add-contact', 'CustomerLeadController@addContactAction')->name('customer-lead.add-contact');
        Route::post('popup-add-file', 'CustomerLeadController@popupAddFileAction')->name('customer-lead.popup-add-file');
        Route::post('add-file', 'CustomerLeadController@addFileAction')->name('customer-lead.add-file');
        Route::post('edit-file', 'CustomerLeadController@showEditFileAction')->name('customer-lead.show-edit-file');

        //Export excel lỗi khi import
        Route::post('export-excel-error', 'CustomerLeadController@exportExcelError')
            ->name('customer-lead.export-error');
        //Tạo deal tự động
        Route::post('create-deal-auto', 'CustomerLeadController@createDealAutoAction')
            ->name('customer-lead.create-deal-auto');
        //Tích hợp on call
        Route::post('modal-call', 'CustomerLeadController@showModalCall')->name('customer-lead.modal-call');
        Route::post('call', 'CustomerLeadController@callUserAction')->name('customer-lead.call');
        Route::post('show-list-care', 'CustomerLeadController@showListCare')->name('customer-lead.show-list-care');
        Route::post('show-list-deal', 'CustomerLeadController@showListDeal')->name('customer-lead.show-list-deal');
        Route::post('search-work-lead', 'CustomerLeadController@searchWorkLead')->name('customer-lead.search-work-lead');

        Route::post('update-from-oncall', 'CustomerLeadController@updateFromOncall')->name('customer-lead.update-from-oncall');


        Route::get('kan-ban-view-new', 'CustomerLeadController@kanBanViewNewAction')->name('customer-lead.custom.index');
    });


    Route::group(['prefix' => 'pipeline'], function () {
        Route::get('/', 'PipelineController@index')->name('customer-lead.pipeline');
        Route::post('list', 'PipelineController@listAction')->name('customer-lead.pipeline.list');
        Route::get('/detail/{id}', 'PipelineController@detail')->name('customer-lead.pipeline.detail');
        Route::get('create', 'PipelineController@create')->name('customer-lead.pipeline.create');
        Route::post('store', 'PipelineController@store')->name('customer-lead.pipeline.store');
        Route::get('edit/{id}', 'PipelineController@edit')->name('customer-lead.pipeline.edit');
        Route::post('update', 'PipelineController@update')->name('customer-lead.pipeline.update');
        Route::post('destroy', 'PipelineController@destroy')->name('customer-lead.pipeline.destroy');
        Route::post('set-default-pipeline', 'PipelineController@setDefaultPipeline')
            ->name('customer-lead.pipeline.setDefaultPipeline');
        Route::post('check-journey-used', 'PipelineController@checkJourneyBeUsed')
            ->name('customer-lead.pipeline.check-journey-used');
        Route::post('list-journey-default', 'PipelineController@getListJourneyDefault')
            ->name('customer-lead.pipeline.list-journey-default');
    });

    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', 'TagController@index')->name('customer-lead.tag');
        Route::post('list', 'TagController@listAction')->name('customer-lead.tag.list');
        Route::get('create', 'TagController@create')->name('customer-lead.tag.create');
        Route::post('store', 'TagController@store')->name('customer-lead.tag.store');
        Route::get('edit/{id}', 'TagController@edit')->name('customer-lead.tag.edit');
        Route::post('update', 'TagController@update')->name('customer-lead.tag.update');
        Route::post('destroy', 'TagController@destroy')->name('customer-lead.tag.destroy');
    });

    Route::group(['prefix' => 'customer-deal'], function () {
        Route::get('/', 'CustomerDealController@index')->name('customer-lead.customer-deal');
        Route::post('list', 'CustomerDealController@listAction')->name('customer-lead.customer-deal.list');
        Route::post('create', 'CustomerDealController@create')->name('customer-lead.customer-deal.create');
        Route::post('store', 'CustomerDealController@store')->name('customer-lead.customer-deal.store');
        Route::post('edit', 'CustomerDealController@edit')->name('customer-lead.customer-deal.edit');
        Route::post('update', 'CustomerDealController@update')->name('customer-lead.customer-deal.update');
        Route::post('show', 'CustomerDealController@detail')->name('customer-lead.customer-deal.show');
        Route::post('destroy', 'CustomerDealController@destroy')->name('customer-lead.customer-deal.destroy');
        // Route::get('kan-ban-view', 'CustomerDealController@kanBanViewAction')
        //     ->name('customer-lead.customer-deal.kan-ban-view');
        Route::get('search-options', 'CustomerDealController@getSearchOption');
        Route::post('search-customer', 'CustomerDealController@searchCustomerAction')
            ->name('customer-lead.customer-deal.search-customer');
        Route::post('load-customer-contact', 'CustomerDealController@loadOptionCustomerContact')
            ->name('customer-lead.customer-deal.load-option-customer-contact');
        Route::post('load-object', 'CustomerDealController@loadObject')
            ->name('customer-lead.customer-deal.load-object');
        Route::post('get-price-object', 'CustomerDealController@getPriceObject')
            ->name('customer-lead.customer-deal.get-price-object');
        // Route::get('kan-ban-view', 'CustomerDealController@kanbanView')->name('customer-lead.customer-deal.kanban-view');
        Route::get('kan-ban-view', 'CustomerDealController@kanbanVue')->name('customer-lead.customer-deal.kanban-view');
        Route::post('load-kanban', 'CustomerDealController@loadKanbanView')
            ->name('customer-lead.customer-deal.load-kanban-view');
        Route::post('update-journey', 'CustomerDealController@updateJourneyAction')
            ->name('customer-lead.customer-deal.update-journey');
        Route::get('/payment/{id}', 'CustomerDealController@payment')->name('customer-lead.customer-deal.payment');
        Route::post('/save-order', 'CustomerDealController@saveOrder')
            ->name('customer-lead.customer-deal.save-order');
        Route::post('/save-or-update-order', 'CustomerDealController@saveOrUpdateOrder')
            ->name('customer-lead.customer-deal.save-or-update-order');
        Route::post('/submit-payment', 'CustomerDealController@submitPayment')
            ->name('customer-lead.customer-deal.submit-payment');
        Route::post('popup-created-customer-lead', 'CustomerDealController@modalAddCustomerLead')
            ->name('customer-lead.customer-deal.popup-created-customer-lead');
        Route::post('create-customer-from-deal', 'CustomerDealController@submitAddCustomerFromDeal')
            ->name('customer-lead.customer-deal.create-customer-from-deal');
        Route::post('popup-created-customer', 'CustomerDealController@modalAddCustomer')
            ->name('customer-lead.customer-deal.popup-create-customer');
        Route::post('store-customer-lead', 'CustomerDealController@storeCustomerLead')
            ->name('customer-lead.customer-deal.store-customer-lead');
        Route::post('store-quickly-tag', 'CustomerDealController@storeQuicklyTag')
            ->name('customer-lead.customer-deal.store-quickly-tag');
        Route::post('popup-deal-care', 'CustomerDealController@popupDealCareAction')
            ->name('customer-lead.customer-deal.popup-deal-care');
        Route::post('deal-care', 'CustomerDealController@dealCareAction')->name('customer-lead.customer-deal.deal-care');

        Route::get('/assign', 'CustomerDealController@assign')->name('customer-lead.customer-deal.assign');
        Route::post('/list-lead-not-assign-yet', 'CustomerDealController@getListLeadNotAssignYet')
            ->name('customer-lead.customer-deal.list-lead-not-assign-yet');
        Route::post('/submit-assign', 'CustomerDealController@submitAssign')->name('customer-lead.customer-deal.submit-assign');
        Route::post('/revoke', 'CustomerDealController@revoke')->name('customer-lead.customer-deal.revoke');
        Route::post('/submit-revoke', 'CustomerDealController@submitRevoke')->name('customer-lead.customer-deal.submit-revoke');
        Route::post('choose-all', 'CustomerDealController@chooseAllAction')->name('customer-lead.customer-deal.choose-all');
        Route::post('choose', 'CustomerDealController@chooseAction')->name('customer-lead.customer-deal.choose');
        Route::post('un-choose-all', 'CustomerDealController@unChooseAllAction')->name('customer-lead.customer-deal.un-choose-all');
        Route::post('un-choose', 'CustomerDealController@unChooseAction')->name('customer-lead.customer-deal.un-choose');
        Route::post('check-all-deal', 'CustomerDealController@checkAllDealAction')->name('customer-lead.customer-deal.check-all-deal');
        Route::post('popup-list-staff', 'CustomerDealController@popupListStaff')->name('customer-lead.customer-deal.popup-list-staff');
        Route::post('save-assign-staff', 'CustomerDealController@saveAssignStaff')->name('customer-lead.customer-deal.save-assign-staff');
        Route::post('revoke-one', 'CustomerDealController@revokeOne')->name('customer-lead.customer-deal.revoke-one');

        //Tích hợp on call
        Route::post('modal-call', 'CustomerDealController@showModalCall')->name('customer-deal.modal-call');
        Route::post('call', 'CustomerDealController@callUserAction')->name('customer-deal.call');

        //Thêm bình luận
        Route::post('add-comment', 'CustomerDealController@addComment')->name('customer-deal.detail.add-comment');
        //show form reply
        Route::post('show-comment-child', 'CustomerDealController@showFormComment')->name('customer-deal.detail.show-form-comment');
        //get list comment
        Route::post('get-list-comment', 'CustomerDealController@getListComment')->name('customer-deal.detail.get-list-comment'); 
    });

    Route::group(['prefix' => 'report'], function () {
        // LEAD
        Route::get('/lead-report-cs', 'ReportController@leadReportCustomerSource')
            ->name('customer-lead.report.lead-report-cs');
        Route::post('/view-lead-report-cs', 'ReportController@renderViewLeadReportCS')
            ->name('customer-lead.report.lead-render-view-report-cs');
        Route::get('/popup-lead-report-cs', 'ReportController@renderPopupLeadReportCS')
            ->name('customer-lead.report.lead-render-popup-report-cs');
        Route::post('/list-popup-lead-report-cs', 'ReportController@listRenderPopupLeadReportCS')
            ->name('customer-lead.report.list-lead-render-popup-report-cs');

        Route::post('/export-excel-popup-lead-report-cs', 'ReportController@ExportExcelPopupLeadReportCS')
            ->name('customer-lead.report.export-excel-popup-lead-report-cs');
        Route::post('/export-excel-view-lead-report-cs', 'ReportController@exportExcelViewLeadReportCs')
            ->name('customer-lead.report.export-excel-view-lead-report-cs');

        Route::get('/lead-report-staff', 'ReportController@leadReportStaff')
            ->name('customer-lead.report.lead-report-staff');
        Route::post('/view-lead-report-staff', 'ReportController@renderViewLeadReportStaff')
            ->name('customer-lead.report.lead-render-view-report-staff');

        Route::post('/export-excel-view-lead-report-staff', 'ReportController@exportExcelViewLeadReportStaff')
            ->name('customer-lead.report.export-excel-view-lead-report-staff');

        // DEAL

        Route::get('/deal-report-staff', 'ReportController@dealReportStaff')
            ->name('customer-lead.report.deal-report-staff');
        Route::post('/view-deal-report-staff', 'ReportController@renderViewDealReportStaff')
            ->name('customer-lead.report.deal-render-view-report-staff');

        Route::get('/popup-deal-report-staff', 'ReportController@renderPopupDealReportStaff')
            ->name('customer-lead.report.deal-render-popup-report-staff');
        Route::post('/list-popup-deal-report-staff', 'ReportController@listRenderPopupDealReportStaff')
            ->name('customer-lead.report.deal-lead-render-popup-report-staff');

        Route::post('/export-excel-popup-deal-report-staff', 'ReportController@ExportExcelPopupDealReportStaff')
            ->name('customer-lead.report.export-excel-popup-deal-report-staff');
        Route::post('/export-excel-view-deal-report-staff', 'ReportController@exportExcelViewDealReportStaff')
            ->name('customer-lead.report.export-excel-view-deal-report-staff');
        // CONVERT
        Route::get('/report-convert', 'ReportController@reportConvert')
            ->name('customer-lead.report.report-convert');
        Route::post('/table-report-convert', 'ReportController@renderViewConvert')
            ->name('customer-lead.report.render-report-convert');
        Route::get('/popup-lead-report-convert', 'ReportController@renderPopupReportConvert')
            ->name('customer-lead.report.lead-render-popup-report-convert');
        Route::post('/list-popup-lead-report-convert', 'ReportController@listRenderPopupReportConvert')
            ->name('customer-lead.report.list-lead-render-popup-report-convert');
        Route::post('/export-excel-view-report-convert', 'ReportController@exportExcelViewReportConvert')
            ->name('customer-lead.report.export-excel-view-report-convert');

        Route::get('/report-funnel', 'ReportController@reportFunnel')
            ->name('customer-lead.report.reportFunnel');
        Route::post('/getDataChartLead', 'ReportController@getDataChartLead')
            ->name('customer-lead.report.getDataChartLead');
        Route::post('/tableLeadSearch', 'ReportController@tableLeadSearch')
            ->name('customer-lead.report.tableLeadSearch');
        Route::post('/tableSourceSearch', 'ReportController@tableSourceSearch')
            ->name('customer-lead.report.tableSourceSearch');

        Route::get('/report-funnel-deal', 'ReportController@reportFunnelDeal')
            ->name('customer-lead.report.reportFunnelDeal');
        Route::post('/getDataChartDeal', 'ReportController@getDataChartDeal')
            ->name('customer-lead.report.getDataChartDeal');
        Route::post('/tableDealSearch', 'ReportController@tableDealSearch')
            ->name('customer-lead.report.tableDealSearch');
        Route::post('/changeDepartment', 'ReportController@changeDepartment')
            ->name('customer-lead.report.changeDepartment');
    });

    Route::group(['prefix' => 'config-source-lead'], function () {

        Route::get('/', 'ConfigSourceLeadController@index')
            ->name('customer-lead.config-source-lead');
        Route::post('/list', 'ConfigSourceLeadController@list')
            ->name('customer-lead.config-source-lead.list');

        Route::post('/show-popup', 'ConfigSourceLeadController@showPopup')
            ->name('customer-lead.config-source-lead.showpopup');

        Route::post('/saveConfig', 'ConfigSourceLeadController@saveConfig')
            ->name('customer-lead.config-source-lead.saveConfig');

        Route::post('/destroy', 'ConfigSourceLeadController@destroy')
            ->name('customer-lead.config-source-lead.destroy');
    });

    Route::group(['prefix' => 'customer-log'], function () {
        Route::get('', 'CustomerLogController@indexAction')->name('customer-lead.customer-log');
        Route::post('list', 'CustomerLogController@listAction')->name('customer-lead.customer-log.list');
        Route::post('list-log-update', 'CustomerLogController@listLogUpdate')->name('customer-lead.customer-log.list-log-update');
    });
});