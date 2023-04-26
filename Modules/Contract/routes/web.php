<?php

Route::group(['middleware' => ['web', 'auth', 'account'],
              'prefix' => 'contract', 'namespace' => 'Modules\Contract\Http\Controllers'], function () {

    Route::group(['prefix' => 'contract-category'], function () {
        Route::get('', 'ContractCategoryController@indexAction')->name('contract.contract-category');
        Route::post('list', 'ContractCategoryController@listAction')->name('contract.contract-category.list');
        Route::post('delete', 'ContractCategoryController@deleteAction')->name('contract.contract-category.delete');
        Route::get('create', 'ContractCategoryController@createAction')->name('contract.contract-category.create');
        Route::get('edit/{id}', 'ContractCategoryController@editAction')->name('contract.contract-category.edit');
        Route::get('detail/{id}', 'ContractCategoryController@detailAction')->name('contract.contract-category.detail');
        Route::post('submit-add', 'ContractCategoryController@submitCreateContractCategoryAction')
            ->name('contract.contract-category.submit-add');
        Route::post('submit-change-status', 'ContractCategoryController@submitChangeStatusAction')
            ->name('contract.contract-category.submit-change-status');
        Route::post('submit-edit', 'ContractCategoryController@submitEditContractCategoryAction')
            ->name('contract.contract-category.submit-edit');
        Route::post('submit-add-config-tab', 'ContractCategoryController@submitCreateTabAction')
            ->name('contract.contract-category.submit-add-config-tab');
        Route::post('submit-add-status-tab', 'ContractCategoryController@submitStatusTabAction')
            ->name('contract.contract-category.submit-add-status-tab');
        Route::post('submit-edit-status-tab', 'ContractCategoryController@submitEditStatusTabAction')
            ->name('contract.contract-category.submit-edit-status-tab');
        Route::post('modal-add-remind-tab', 'ContractCategoryController@getViewAddRemind')
            ->name('contract.contract-category.modal-add-remind-tab');
        Route::post('submit-add-remind-tab', 'ContractCategoryController@submitRemindTabAction')
            ->name('contract.contract-category.submit-add-remind-tab');
        Route::post('remove-remind', 'ContractCategoryController@removeRemindAction')
            ->name('contract.contract-category.remove-remind');
        Route::post('modal-edit-remind', 'ContractCategoryController@getViewEditRemind')
            ->name('contract.contract-category.modal-edit-remind');
        Route::post('submit-edit-remind', 'ContractCategoryController@submitEditRemindAction')
            ->name('contract.contract-category.submit-edit-remind');
        Route::post('load-status-notify', 'ContractCategoryController@loadStatusNotify')
            ->name('contract.contract-category.load-status-notify');
        Route::post('submit-add-notify-tab', 'ContractCategoryController@submitNotifyTabAction')
            ->name('contract.contract-category.submit-add-notify-tab');
        Route::post('modal-change-content-notify', 'ContractCategoryController@modalChangeContentNotify')
            ->name('contract.contract-category.modal-change-content-notify');
    });

    Route::group(['prefix' => 'contract'], function () {
        Route::get('', 'ContractController@index')->name('contract.contract');
        Route::post('/list', 'ContractController@listAction')->name('contract.contract.list');
        Route::post('/load-status', 'ContractController@loadStatusAction')->name('contract.contract.load-status');
        Route::get('create', 'ContractController@create')->name('contract.contract.create');
        Route::post('choose-category', 'ContractController@chooseCategoryAction')->name('contract.contract.choose-category');
        Route::post('insert-tag', 'ContractController@insertTagAction')->name('contract.contract.insert-tag');
        Route::post('change-partner-type', 'ContractController@changePartnerTypeAction')->name('contract.contract.change-partner-type');
        Route::post('change-partner', 'ContractController@changePartnerAction')->name('contract.contract.change-partner');
        Route::post('insert-payment-method', 'ContractController@insertPaymentMethodAction')->name('contract.contract.insert-payment-method');
        Route::post('insert-payment-unit', 'ContractController@insertPaymentUnitAction')->name('contract.contract.insert-payment-unit');
        Route::post('store', 'ContractController@store')->name('contract.contract.store');
        Route::get('edit/{id}', 'ContractController@edit')->name('contract.contract.edit');
        Route::post('update-info', 'ContractController@updateInfoAction')->name('contract.contract.update-info');
        Route::post('change-value-goods', 'ContractController@changeValueGoodsAction')->name('contract.contract.change-value-goods');
        Route::post('show-modal-reason', 'ContractController@showModalReasonAction')->name('contract.contract.show-modal-reason');
        Route::post('destroy-contract', 'ContractController@destroy')->name('contract.contract.destroy');
        Route::get('export-excel', 'ContractController@exportExcel')->name('contract.contract.export-excel');
        Route::post('show-modal-status', 'ContractController@showModalStatusAction')->name('contract.contract.show-modal-status');
        Route::post('update-status', 'ContractController@updateStatusAction')->name('contract.contract.update-status');
        Route::post('show-modal-import', 'ContractController@showModalImportAction')->name('contract.contract.show-modal-import');
        Route::post('import-excel', 'ContractController@importExcelAction')->name('contract.contract.import-excel');
        Route::post('export-error', 'ContractController@exportExcelError')->name('contract.contract.export-error');

        //Chi tiết hợp đồng
        Route::get('show/{id}', 'ContractController@show')->name('contract.contract.show');
        Route::get('config', 'ContractController@configAction')->name('contract.contract.config');
        Route::post('save-config-cookie', 'ContractController@submitSaveConfig')->name('contract.contract.save-config-cookie');
        // phụ lục
        Route::post('/annex/list', 'ContractAnnexController@listAction')->name('contract.contract.annex.list');
        Route::post('get-popup-annex', 'ContractAnnexController@getPopupAddAnnex')->name('contract.contract.get-popup-annex');
        Route::post('save-annex', 'ContractAnnexController@submitSaveAnnex')->name('contract.contract.save-annex');
        Route::post('continue-annex', 'ContractAnnexController@actionContinueAnnex')->name('contract.contract.continue-annex');
        Route::get('view-edit-contract-annex', 'ContractAnnexController@getViewEditContractAnnex')->name('contract.contract.view-edit-contract-annex');
        Route::post('submit-edit-contract-annex', 'ContractAnnexController@submitEditContractAnnex')->name('contract.contract.submit-edit-contract-annex');
        Route::post('list-goods-contract-annex', 'ContractGoodsController@listActionAnnex')->name('contract.contract.list-goods-contract-annex');
        Route::post('store-annex-goods', 'ContractAnnexController@storeAnnexGood')->name('contract.contract.store-annex-goods');
        Route::post('update-annex', 'ContractAnnexController@submitUpdateAnnex')->name('contract.contract.update-annex');
        Route::post('continue-update-annex', 'ContractAnnexController@actionContinueUpdateAnnex')->name('contract.contract.continue-update-annex');
        Route::post('delete-annex', 'ContractAnnexController@deleteAnnex')->name('contract.contract.delete-annex');
        Route::get('/annex/detail/{id}', 'ContractAnnexController@detailAction')->name('contract.contract.annex.detail');

        //Dự kiến thu - chi
        Route::post('list-expected-receipt', 'ExpectedRevenueController@listAction')->name('contract.contract.list-expected-revenue');
        Route::post('modal-create-revenue', 'ExpectedRevenueController@showModalCreateAction')->name('contract.contract.modal-create-revenue');
        Route::post('store-revenue', 'ExpectedRevenueController@store')->name('contract.contract.store-revenue');
        Route::post('model-edit-revenue', 'ExpectedRevenueController@showModalEditAction')->name('contract.contract.modal-edit-revenue');
        Route::post('update-revenue', 'ExpectedRevenueController@update')->name('contract.contract.update-revenue');
        Route::post('destroy-revenue', 'ExpectedRevenueController@destroy')->name('contract.contract.destroy-revenue');

        //Đợt thu
        Route::post('list-receipt', 'ContractReceiptController@listAction')->name('contract.contract.list-receipt');
        Route::post('modal-create-receipt', 'ContractReceiptController@showModalCreateAction')->name('contract.contract.modal-create-receipt');
        Route::post('store-receipt', 'ContractReceiptController@store')->name('contract.contract.store-receipt');
        Route::post('modal-edit-receipt', 'ContractReceiptController@showModalEditAction')->name('contract.contract.modal-edit-receipt');
        Route::post('update-receipt', 'ContractReceiptController@update')->name('contract.contract.update-receipt');
        Route::post('modal-remove-receipt', 'ContractReceiptController@showModalRemoveAction')->name('contract.contract.modal-remove-receipt');
        Route::post('destroy-receipt', 'ContractReceiptController@destroy')->name('contract.contract.destroy-receipt');

        //Đợt chi
        Route::post('list-spend', 'ContractSpendController@listAction')->name('contract.contract.list-spend');
        Route::post('modal-create-spend', 'ContractSpendController@showModalCreateAction')->name('contract.contract.modal-create-spend');
        Route::post('store-spend', 'ContractSpendController@store')->name('contract.contract.store-spend');
        Route::post('modal-edit-spend', 'ContractSpendController@showModalEditAction')->name('contract.contract.modal-edit-spend');
        Route::post('update-spend', 'ContractSpendController@update')->name('contract.contract.update-spend');
        Route::post('modal-remove-spend', 'ContractSpendController@showModalRemoveAction')->name('contract.contract.modal-remove-spend');
        Route::post('destroy', 'ContractSpendController@destroy')->name('contract.contract.destroy-spend');

        //Đính kèm
        Route::post('list-file', 'ContractFileController@listAction')->name('contract.contract.list-file');
        Route::post('modal-create-file', 'ContractFileController@showModalCreateAction')->name('contract.contract.modal-create-file');
        Route::post('store-file', 'ContractFileController@store')->name('contract.contract.store-file');
        Route::post('modal-edit-file', 'ContractFileController@showModalEditAction')->name('contract.contract.modal-edit-file');
        Route::post('update-file', 'ContractFileController@update')->name('contract.contract.update-file');
        Route::post('destroy-file', 'ContractFileController@destroy')->name('contract.contract.destroy-file');

        //Hàng hoá
        Route::post('list-goods', 'ContractGoodsController@listAction')->name('contract.contract.list-goods');
        Route::post('change-object', 'ContractGoodsController@changeObjectAction')->name('contract.contract.change-object');
        Route::post('search-order', 'ContractGoodsController@searchOrderAction')->name('contract.contract.search-order');
        Route::post('store-goods', 'ContractGoodsController@store')->name('contract.contract.store-goods');

        //Lấy trạng thái đơn hàng gần nhất map với hđ
        Route::post('get-status-order', 'ContractController@getStatusOrder')->name('contract.contract.get-status-order');

        // xử lý thêm nhanh customer + supplier

        Route::post('popup-add-customer-quickly', 'ContractController@getPopupCustomerQuickly')->name('contract.contract.popup-add-customer-quickly');
        Route::post('submit-add-customer-quickly', 'ContractController@submitCustomerQuickly')->name('contract.contract.submit-add-customer-quickly');
        Route::post('submit-add-supplier-quickly', 'ContractController@submitSupplierQuickly')->name('contract.contract.submit-add-supplier-quickly');

        Route::get('job-expire', 'ContractController@getJob1')->name('contract.contract.job-1');
        Route::get('job-soon-expire', 'ContractController@getJob2')->name('contract.contract.job-2');
    });

    Route::group(['prefix' => 'role-data'], function () {
        Route::get('/', 'ContractRoleDataController@indexAction')->name('contract.role-data');
        Route::post('/submit-config', 'ContractRoleDataController@submitConfigAction')->name('contract.role-data.submit-config');
    });

    Route::group(['prefix' => 'contract-care'], function () {
        Route::get('/expire', 'ContractCareController@indexExpireAction')->name('contract.contract-care.expire');
        Route::post('/expire/list', 'ContractCareController@listExpireAction')->name('contract.contract-care.expire.list');
        Route::get('/soon-expire', 'ContractCareController@indexSoonExpireAction')->name('contract.contract-care.soon-expire');
        Route::post('/soon-expire/list', 'ContractCareController@listSoonExpireAction')->name('contract.contract-care.expire.soon-list');

        Route::post('choose-all-expire', 'ContractCareController@chooseAllExpireAction')->name('contract.contract-care.choose-all-expire');
        Route::post('choose-expire', 'ContractCareController@chooseExpireAction')->name('contract.contract-care.choose-expire');
        Route::post('un-choose-all-expire', 'ContractCareController@unChooseAllExpireAction')->name('contract.contract-care.un-choose-all-expire');
        Route::post('un-choose-expire', 'ContractCareController@unChooseExpireAction')->name('contract.contract-care.un-choose-expire');

        Route::post('choose-all-soon-expire', 'ContractCareController@chooseAllSoonExpireAction')->name('contract.contract-care.choose-all-soon-expire');
        Route::post('choose-soon-expire', 'ContractCareController@chooseSoonExpireAction')->name('contract.contract-care.choose-soon-expire');
        Route::post('un-choose-all-soon-expire', 'ContractCareController@unChooseAllSoonExpireAction')->name('contract.contract-care.un-choose-all-soon-expire');
        Route::post('un-choose-soon-expire', 'ContractCareController@unChooseSoonExpireAction')->name('contract.contract-care.un-choose-soon-expire');


        Route::post('popup-create-deal', 'ContractCareController@getPopupPerformDeal')->name('contract.contract-care.popup-create-deal');
        Route::post('submit-create-deal', 'ContractCareController@submitCreateDeal')->name('contract.contract-care.submit-create-deal');
    });

    Route::group(['prefix' => 'contract-browse'], function () {
        Route::get('', 'ContractBrowseController@index')->name('contract.contract-browse');
        Route::post('list', 'ContractBrowseController@listAction')->name('contract.contract-browse.list');
        Route::post('confirm', 'ContractBrowseController@confirmAction')->name('contract.contract-browse.confirm');
        Route::post('show-modal-refuse', 'ContractBrowseController@showModalRefuseAction')->name('contract.contract-browse.modal-refuse');
        Route::post('refuse', 'ContractBrowseController@refuseAction')->name('contract.contract-browse.refuse');
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/contract-care', 'ReportContractCareController@indexAction')
            ->name('contract.report.contract-care');
        Route::post('/contract-care/filter', 'ReportContractCareController@filterAction')
            ->name('contract.report.contract-care.filter');
        Route::post('/contract-care/load-department', 'ReportContractCareController@getDepartment')
            ->name('contract.report.contract-care.load-department');
        Route::post('/contract-care/load-staff', 'ReportContractCareController@getStaff')
            ->name('contract.report.contract-care.load-staff');

        Route::get('/contract-overview', 'ReportContractOverviewController@indexAction')
            ->name('contract.report.contract-overview');
        Route::post('/contract-overview/filter', 'ReportContractOverviewController@filterAction')
            ->name('contract.report.contract-overview.filter');

        Route::get('/contract-detail', 'ReportContractDetailController@indexAction')
            ->name('contract.report.contract-detail');
        Route::post('/contract-detail/list', 'ReportContractDetailController@listAction')
            ->name('contract.report.contract-detail.list');
        Route::get('/contract-detail/export', 'ReportContractDetailController@exportExcel')
            ->name('contract.report.contract-detail.export');

        Route::get('/contract-revenue', 'ReportContractRevenueController@indexAction')
            ->name('contract.report.contract-revenue');
        Route::post('/contract-revenue/filter', 'ReportContractRevenueController@filterAction')
            ->name('contract.report.contract-revenue.filter');
        Route::post('/contract-revenue/list', 'ReportContractRevenueController@listAction')
            ->name('contract.report.contract-revenue.list');
        Route::get('/contract-revenue/export', 'ReportContractRevenueController@exportExcel')
            ->name('contract.report.contract-revenue.export');
    });

    Route::group(['prefix' => 'vat'], function () {
        Route::get('', 'VatController@index')->name('contract.vat');
        Route::post('list', 'VatController@listAction')->name('contract.vat.list');
        Route::post('pop-create', 'VatController@showPopCreateAction')->name('contract.vat.show-pop-create');
        Route::post('store', 'VatController@store')->name('contract.vat.store');
        Route::post('pop-edit', 'VatController@showPopEditAction')->name('contract.vat.show-pop-edit');
        Route::post('update', 'VatController@update')->name('contract.vat.update');
        Route::post('change-status', 'VatController@changeStatusAction')->name('contract.vat.change-status');
    });
});

Route::group(['prefix' => 'contract', 'namespace' => 'Modules\Contract\Http\Controllers'], function () {
    Route::get('sync-template-contract', 'ContractController@syncTemplateContractAction');
});
