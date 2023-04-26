<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'warranty', 'namespace' => 'Modules\Warranty\Http\Controllers'], function () {
    // Quản lý gói bảo hành
    Route::group(['prefix' => 'package'], function () {
        Route::get('/', 'WarrantyPackageController@index')->name('warranty-package');
        Route::post('list', 'WarrantyPackageController@listAction')->name('warranty-package.list');
        Route::get('create', 'WarrantyPackageController@create')->name('warranty-package.create');
        Route::post('store', 'WarrantyPackageController@store')->name('warranty-package.store');
        Route::get('edit/{id}', 'WarrantyPackageController@edit')->name('warranty-package.edit');
        Route::post('update', 'WarrantyPackageController@update')->name('warranty-package.update');
        Route::post('delete', 'WarrantyPackageController@delete')->name('warranty-package.delete');
        Route::post('update-status', 'WarrantyPackageController@updateStatus')->name('warranty-package.update-status');
        Route::get('detail/{id}', 'WarrantyPackageController@show')->name('warranty-package.show');

        Route::post('show-popup', 'WarrantyPackageController@popupAction')->name('warranty-package.popup');
        Route::post('list-product', 'WarrantyPackageController@listProductAction')->name('warranty-package.list-product');
        Route::post('list-service', 'WarrantyPackageController@listServiceAction')->name('warranty-package.list-service');
        Route::post('list-service-card', 'WarrantyPackageController@listServiceCardAction')->name('warranty-package.list-service-card');
        Route::post('choose-all', 'WarrantyPackageController@chooseAllAction')->name('warranty-package.choose-all');
        Route::post('choose', 'WarrantyPackageController@chooseAction')->name('warranty-package.choose');
        Route::post('un-choose-all', 'WarrantyPackageController@unChooseAllAction')->name('warranty-package.un-choose-all');
        Route::post('un-choose', 'WarrantyPackageController@unChooseAction')->name('warranty-package.un-choose');
        Route::post('submit-choose', 'WarrantyPackageController@submitChooseAction')->name('warranty-package.submit-choose');
        Route::post('list-discount', 'WarrantyPackageController@listDiscountAction')->name('warranty-package.list-discount');
        Route::post('remove-tr', 'WarrantyPackageController@removeTrAction')->name('warranty-package.remove-tr');
        Route::post('list-discount-detail', 'WarrantyPackageController@listDiscountDetailAction')
            ->name('warranty-package.list-discount-detail');

    });

    // Quản lý phiếu bảo trì
    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('/', 'MaintenanceController@index')->name('maintenance');
        Route::post('list', 'MaintenanceController@listAction')->name('maintenance.list');
        Route::get('create', 'MaintenanceController@create')->name('maintenance.create');
        Route::post('load-object', 'MaintenanceController@loadObjectAction')->name('maintenance.load-object');
        Route::post('modal-warranty', 'MaintenanceController@modalWarrantyAction')->name('maintenance.modal-warranty');
        Route::post('close-modal-warranty', 'MaintenanceController@closeModalWarrantyAction')->name('maintenance.close-modal-warranty');
        Route::post('list-warranty', 'MaintenanceController@listWarrantyAction')->name('maintenance.list-warranty');
        Route::post('choose-warranty', 'MaintenanceController@chooseWarrantyAction')->name('maintenance.choose-warranty');
        Route::post('submit-choose-warranty', 'MaintenanceController@submitChooseWarrantyAction')
            ->name('maintenance.submit-choose-warranty');
        Route::post('clear-session', 'MaintenanceController@clearSessionAction')->name('maintenance.clear-session');
        Route::post('store', 'MaintenanceController@store')->name('maintenance.store');
        Route::get('edit/{id}', 'MaintenanceController@edit')->name('maintenance.edit');
        Route::post('update', 'MaintenanceController@update')->name('maintenance.update');
        Route::post('modal-receipt', 'MaintenanceController@modalReceiptAction')->name('maintenance.modal-receipt');
        Route::post('submit-receipt', 'MaintenanceController@submitReceiptAction')->name('maintenance.submit-receipt');
        Route::get('show/{id}', 'MaintenanceController@show')->name('maintenance.show');
        Route::post('gen-qr-code', 'MaintenanceController@genQrCodeAction')->name('maintenance.gen-qr-code');
    });

    // Quản lý phiếu bảo hành điện tử
    Route::group(['prefix' => 'card'], function () {
        Route::get('/', 'WarrantyCardController@index')->name('warranty-card');
        Route::post('list', 'WarrantyCardController@listAction')->name('warranty-card.list');
        Route::get('edit/{id}', 'WarrantyCardController@edit')->name('warranty-card.edit');
        Route::post('update', 'WarrantyCardController@update')->name('warranty-card.update');
        Route::post('cancel', 'WarrantyCardController@cancel')->name('warranty-card.cancel');
        Route::post('active', 'WarrantyCardController@active')->name('warranty-card.active');
        Route::get('detail/{id}', 'WarrantyCardController@show')->name('warranty-card.show');
        Route::post('load-tab-detail', 'WarrantyCardController@loadTabDetailAction')->name('warranty-card.load-tab-detail');
    });
    // Quản lý chi phí phát sinh
    Route::group(['prefix' => 'maintenance-cost-type'], function () {
        Route::get('/', 'MaintenanceCostTypeController@index')->name('maintenance-cost-type');
        Route::post('list', 'MaintenanceCostTypeController@listAction')->name('maintenance-cost-type.list');
        Route::get('create', 'MaintenanceCostTypeController@create')->name('maintenance-cost-type.create');
        Route::post('store', 'MaintenanceCostTypeController@store')->name('maintenance-cost-type.store');
        Route::get('edit/{id}', 'MaintenanceCostTypeController@edit')->name('maintenance-cost-type.edit');
        Route::post('update', 'MaintenanceCostTypeController@update')->name('maintenance-cost-type.update');
        Route::post('delete', 'MaintenanceCostTypeController@delete')->name('maintenance-cost-type.delete');
    });

    // Quản lý tài sản (phiếu bảo dưỡng)
    Route::group(['prefix' => 'repair'], function () {
        Route::get('/', 'RepairController@index')->name('repair');
        Route::post('list', 'RepairController@listAction')->name('repair.list');
        Route::get('create', 'RepairController@create')->name('repair.create');
        Route::post('store', 'RepairController@store')->name('repair.store');
        Route::get('edit/{id}', 'RepairController@edit')->name('repair.edit');
        Route::post('update', 'RepairController@update')->name('repair.update');
        Route::post('modal-payment', 'RepairController@modalPayment')->name('repair.modal-payment');
        Route::post('submit-payment', 'RepairController@submitPayment')->name('repair.submit-payment');
        Route::get('detail/{id}', 'RepairController@show')->name('repair.show');
    });

    // báo cáo chi phí bảo dưỡng
    Route::group(['prefix' => 'report-repair-cost'], function () {
        Route::get('/', 'ReportRepairCostController@index')->name('report-repair-cost');
        Route::post('filter', 'ReportRepairCostController@filterAction')->name('report-repair-cost.filter');
    });
});