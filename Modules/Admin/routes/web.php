<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function () {
    Route::group(['prefix' => 'inventory-input'], function () {
        Route::get('export-add-inventory-input-error', 'InventoryInputController@exportAddInventoryInputError')->name('admin.inventory-input.export-add-inventory-input-error');
    });

    //INVENTORY CHECKING
    Route::group(['prefix' => 'inventory-checking'], function () {
        Route::get('export-add-inventory-checking-error', 'InventoryCheckingController@exportAddInventoryCheckingError')->name('admin.inventory-checking.export-add-inventory-checking-error');
    });

    //INVENTORY OUTPUT
    Route::group(['prefix' => 'inventory-output'], function () {
        Route::get('/export-add-inventory-input-error', 'InventoryOutputController@exportAddInventoryInputError')->name('admin.inventory-output.export-add-inventory-input-error');
    });
});
Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function () {
    //Đặt lịch gia khang
    Route::group(['prefix' => 'booking-calendar'], function () {
        Route::get('/', 'CalendarController@bookingServiceAction')->name('booking-calendar');
        Route::post('modal-add', 'CalendarController@showModalAddAction')->name('booking-calendar.modal-add');
        Route::post('modal-detail', 'CalendarController@showModalDetailAction')->name('booking-calendar.modal-detail');
    });
    //Danh sách xe đã book gia khang
    Route::group(['prefix' => 'service-booking'], function () {
        Route::get('/', 'ServiceBookingController@index')->name('service-booking');
        Route::post('list', 'ServiceBookingController@listAction')->name('service-booking.list');
    });

    Route::get('/', 'AdminController@indexAction')->name('admin');
    // SERVICE GROUP
    Route::group(['prefix' => 'service-group'], function () {
        Route::get('/', 'ServiceGroupController@indexAction')->name('service-group');
        Route::post('change-status', 'ServiceGroupController@changeStatusAction')->name('service-group.change-status');
        Route::post('list', 'ServiceGroupController@listAction')->name('service-group.list');
        Route::post('remove/{id}', 'ServiceGroupController@removeAction')->name('service-group.remove');
        Route::get('add', 'ServiceGroupController@addAction')->name('service-group.add');
        Route::post('add', 'ServiceGroupController@submitAddAction')->name('service-group.add');
        Route::get('edit/{service_group_id}', 'ServiceGroupController@editAction')->name('service-group.edit');
        Route::post('edit/{service_group_id}', 'ServiceGroupController@submitEditAction')->name('service-group.edit');
        Route::get('form-service-group/{id}', 'ServiceGroupController@formSaveAction')->name('service-group.form-service-group');
    });

    // CUSTOMER SOURCE
    Route::group(['prefix' => 'customer-source'], function () {
        Route::get('/', 'CustomerSourceController@indexAction')->name('customer-source');
        Route::post('change-status', 'CustomerSourceController@changeStatusAction')->name('customer-source.change-status');
        Route::post('list', 'CustomerSourceController@listAction')->name('customer-source.list');
        Route::post('remove/{id}', 'CustomerSourceController@removeAction')->name('customer-source.remove');
        Route::post('add', 'CustomerSourceController@submitAddAction')->name('customer-source.add');
        Route::get('edit', 'CustomerSourceController@editAction')->name('customer-source.edit');
        Route::post('edit', 'CustomerSourceController@submitEditAction')->name('customer-source.edit-submit');
    });
    // SERVICE PACKAGE
    Route::group(['prefix' => 'service-package'], function () {
        Route::get('/', 'ServicePackageController@indexAction')->name('service-package');
        Route::post('change-status', 'ServicePackageController@changeStatusAction')->name('service-package.change-status');
        Route::post('list', 'ServicePackageController@listAction')->name('service-package.list');
        Route::post('remove/{id}', 'ServicePackageController@removeAction')->name('service-package.remove');
        Route::get('add', 'ServicePackageController@addAction')->name('service-package.add');
        Route::post('add', 'ServicePackageController@submitAddAction')->name('service-package.add');
        Route::get('edit/{service_package_id}', 'ServicePackageController@editAction')->name('service-package.edit');
        Route::post('edit/{service_package_id}', 'ServicePackageController@submitEditAction')->name('service-package.edit');
    });
    // SERVICE TYPE
    Route::group(['prefix' => 'service-type'], function () {
        Route::get('/', 'ServiceTypeController@indexAction')->name('service-type');
        Route::post('change-status', 'ServiceTypeController@changeStatusAction')->name('service-type.change-status');
        Route::post('list', 'ServiceTypeController@listAction')->name('service-type.list');
        Route::post('remove/{id}', 'ServiceTypeController@removeAction')->name('service-type.remove');
        Route::get('add', 'ServiceTypeController@addAction')->name('service-type.add');
        Route::post('add', 'ServiceTypeController@submitAddAction')->name('service-type.add');
        Route::get('edit/{service_type_id}', 'ServiceTypeController@editAction')->name('service-type.edit');
        Route::post('edit/{service_type_id}', 'ServiceTypeController@submitEditAction')->name('service-type.edit');
    });

    // PRODUCT UNIT
    Route::group(['prefix' => 'product-unit'], function () {
        Route::get('/', 'ProductUnitController@indexAction')->name('product-unit');
        Route::post('list', 'ProductUnitController@listAction')->name('product-unit.list');
        Route::post('remove/{id}', 'ProductUnitController@removeAction')->name('product-unit.remove');
        Route::get('add', 'ProductUnitController@addAction')->name('product-unit.add');
        Route::post('add', 'ProductUnitController@submitAddAction')->name('product-unit.submitadd');
        Route::get('edit/{id}', 'ProductUnitController@editAction')->name('product-unit.edit');
        Route::post('edit/{id}', 'ProductUnitController@submitEditAction')->name('product-unit.submitedit');
        Route::post('change-status', 'ProductUnitController@changeStatusAction')->name('product-unit.change-status');
    });

    // CUSTOMER GROUP
    Route::group(['prefix' => 'customer-group'], function () {
        Route::get('/', 'CustomerGroupController@indexAction')->name('customer-group');
        Route::post('change-status', 'CustomerGroupController@changeStatusAction')->name('customer-group.change-status');
        Route::post('list', 'CustomerGroupController@listAction')->name('customer-group.list');
        Route::post('remove/{id}', 'CustomerGroupController@removeAction')->name('customer-group.remove');
        Route::post('add', 'CustomerGroupController@submitAddAction')->name('customer-group.add');
        Route::post('edit', 'CustomerGroupController@editAction')->name('customer-group.edit');
        Route::post('edit-submit', 'CustomerGroupController@submitEditAction')->name('customer-group.edit-submit');
    });

    //PRODUCT ORIGIN
    Route::group(['prefix' => 'product-origin'], function () {

        Route::get('/', 'ProductOriginController@indexAction')->name('admin.product-origin');
        Route::post('list', 'ProductOriginController@listAction')->name('admin.product-origin.list');
        Route::post('remove/{id}', 'ProductOriginController@removeAction')->name('admin.product-origin.remove');

        Route::get('add', 'ProductOriginController@addAction')->name('admin.product-origin.add');
        Route::post('add', 'ProductOriginController@submitAddAction')->name('admin.product-origin.submitadd');

        Route::get('edit/{id}', 'ProductOriginController@editAction')->name('admin.product-origin.edit');
        Route::post('edit/{id}', 'ProductOriginController@submitEditAction')->name('admin.product-origin.submitedit');

        Route::post('change-status', 'ProductOriginController@changeStatusAction')->name('admin.product-origin.change-status');
    });


    // PRODUCT LABEL
    Route::group(['prefix' => 'product-label'], function () {
        Route::get('/', 'ProductLabelController@indexAction')->name('admin.product-label');
        Route::post('list', 'ProductLabelController@listAction')->name('admin.product-label.list');
        Route::post('remove/{id}', 'ProductLabelController@removeAction')->name('admin.product-label.remove');
        Route::get('add', 'ProductLabelController@addAction')->name('admin.product-label.add');
        Route::post('add', 'ProductLabelController@submitAddAction')->name('admin.product-label.submitadd');
        Route::post('change-status', 'ProductLabelController@changeStatusAction')->name('admin.product-label.change-status');
        Route::get('edit/{id}', 'ProductLabelController@editAction')->name('admin.product-label.edit');
        Route::post('edit/{id}', 'ProductLabelController@submitEditAction')->name('admin.product-label.submitedit');
    });

    //STAFF TITLE
    Route::group(['prefix' => 'staff-title'], function () {
        Route::get('/', 'StaffTitleController@indexAction')->name('admin.staff-title');
        Route::post('list', 'StaffTitleController@listAction')->name('admin.staff-title.list');

        Route::post('remove/{id}', 'StaffTitleController@removeAction')->name('admin.staff-title.remove');
        Route::get('add', 'StaffTitleController@addAction')->name('admin.staff-title.add');
        Route::post('add', 'StaffTitleController@submitAddAction')->name('admin.staff-title.submitadd');
        //        Route::get('edit/{id}', 'StaffTitleController@editAction')->name('admin.staff-title.edit');
        Route::post('submit-edit', 'StaffTitleController@submitEditAction')->name('admin.staff-title.submitedit');
        Route::post('change-status', 'StaffTitleController@changeStatusAction')->name('admin.staff-title.change-status');
        Route::post('get-edit', 'StaffTitleController@editAction')->name('admin.staff-title.edit');
    });
    //Order Payment Type
    Route::group(['prefix' => 'order-payment-type'], function () {
        Route::get('/', 'OrderPaymentTypeController@indexAction')->name('order-payment-type');
        Route::post('change-status', 'OrderPaymentTypeController@changeStatusAction')->name('order-payment-type.change-status');
        Route::post('list', 'OrderPaymentTypeController@listAction')->name('order-payment-type.list');
        Route::post('remove/{id}', 'OrderPaymentTypeController@removeAction')->name('order-payment-type.remove');
        Route::get('add', 'OrderPaymentTypeController@addAction')->name('order-payment-type.add');
        Route::post('add', 'OrderPaymentTypeController@submitAddAction')->name('order-payment-type.add');
        Route::get('edit/{id}', 'OrderPaymentTypeController@editAction')->name('order-payment-type.edit');
        Route::post('edit/{id}', 'OrderPaymentTypeController@submitEditAction')->name('order-payment-type.edit');
    });

    //PRODUCT GROUP
    Route::group(['prefix' => 'product-group'], function () {

        Route::get('/', 'ProductGroupController@indexAction')->name('product-group');
        Route::post('list', 'ProductGroupController@listAction')->name('product-group.list');
        Route::post('remove/{id}', 'ProductGroupController@removeAction')->name('product-group.remove');
        Route::get('add', 'ProductGroupController@addAction')->name('product-group.add');
        Route::post('add', 'ProductGroupController@submitaddAction')->name('product-group.submitadd');
        Route::get('edit/{id}', 'ProductGroupController@editAction')->name('product-group.edit');
        Route::post('edit/{id}', 'ProductGroupController@submitEditAction')->name('product-group.submitedit');
        Route::post('change-status', 'ProductGroupController@changeStatusAction')->name('product-group.change-status');
        Route::post('uphinhnhoaz', 'ProductGroupController@uploadsAction')->name('product-group.upload');
        Route::post('xoahinh', 'ProductGroupController@xoahinhAction')->name('product-group.xoahinh');
    });


    Route::group(['prefix' => 'member-level'], function () {

        Route::get('/', 'MemberLevelController@indexAction')->name('admin.member-level');
        Route::post('list', 'MemberLevelController@listAction')->name('admin.member-level.list');
        Route::post('remove/{id}', 'MemberLevelController@removeAction')->name('admin.member-level.remove');
        //        Route::get('add', 'MemberLevelController@addAction')->name('member-level.add');
        Route::post('add', 'MemberLevelController@submitaddAction')->name('admin.member-level.submitadd');
        //        Route::post('edit','MemberLevelController@editAction')->name('admin.member-level.edit');
        Route::post('edit-member', 'MemberLevelController@editAction')->name('admin.member-level.edit');
        Route::post('edit-submit', 'MemberLevelController@submitEditAction')->name('admin.member-level.submitedit');
        Route::post('change-status', 'MemberLevelController@changeStatusAction')->name('admin.member-level.change-status');
    });

    Route::group(['prefix' => 'order-delivery-status'], function () {
        Route::get('/', 'OrderDeliveryStatusController@indexAction')->name('order-delivery-status');
        Route::post('list', 'OrderDeliveryStatusController@listAction')->name('order-delivery-status.list');
        Route::post('remove/{id}', 'OrderDeliveryStatusController@removeAction')->name('order-delivery-status.remove');
        Route::get('add', 'OrderDeliveryStatusController@addAction')->name('order-delivery-status.add');
        Route::post('add', 'OrderDeliveryStatusController@submitaddAction')->name('order-delivery-status.submitadd');
        Route::get('edit/{id}', 'OrderDeliveryStatusController@editAction')->name('order-delivery-status.edit');
        Route::post('edit/{id}', 'OrderDeliveryStatusController@submitEditAction')->name('order-delivery-status.submitedit');
        Route::post('change-status', 'OrderDeliveryStatusController@changeStatusAction')->name('order-delivery-status.change-status');
    });

    //ODER STATUS
    Route::group(['prefix' => 'order-status'], function () {
        Route::get('/', 'OrderStatusController@indexAction')->name('admin.order-status');
        Route::post('list', 'OrderStatusController@listAction')->name('admin.order-status.list');

        Route::post('remove/{id}', 'OrderStatusController@removeAction')->name('admin.order-status.remove');
        Route::get('add', 'OrderStatusController@addAction')->name('admin.order-status.add');
        Route::post('add', 'OrderStatusController@submitAddAction')->name('admin.order-status.submitadd');
        Route::get('edit/{id}', 'OrderStatusController@editAction')->name('admin.order-status.edit');
        Route::post('edit/{id}', 'OrderStatusController@submitEditAction')->name('admin.order-status.submitedit');
        Route::post('change-status', 'OrderStatusController@changeStatusAction')->name('admin.order-status.change-status');
        Route::post('export', 'OrderStatusController@exportAction')->name('admin.order-status.export');
        Route::post('import', 'OrderStatusController@importAction')->name('admin.order-status.import');
    });

    // ORDER REASON CANCEL
    Route::group(['prefix' => 'order-reason-cancel'], function () {
        Route::get('/', 'OrderReasonCancelController@indexAction')->name('admin.order-reason-cancel');
        Route::post('list', 'OrderReasonCancelController@listAction')->name('admin.order-reason-cancel.list');
        Route::post('change-status', 'OrderReasonCancelController@changeStatusAction')->name('admin.order-reason-cancel.change-status');
        Route::get('add', 'OrderReasonCancelController@addAction')->name('admin.order-reason-cancel.add');
        Route::post('add', 'OrderReasonCancelController@submitAddAction')->name('admin.order-reason-cancel.submitadd');
        Route::get('edit/{order_reason_cancel_id}', 'OrderReasonCancelController@editAction')->name('admin.order-reason-cancel.edit');
        Route::post('edit/{order_reason_cancel_id}', 'OrderReasonCancelController@submitEditAction')->name('admin.order-reason-cancel.submitedit');
        Route::post('remove/{id}', 'OrderReasonCancelController@removeAction')->name('admin.order-reason-cancel.remove');
        Route::get('import-excel', 'OrderReasonCancelController@importExcelAction')->name('admin.oder-reason-cancel.import-excel');
        Route::post('import', 'OrderReasonCancelController@submitImportExcelAction')->name('admin.order-reason-cancel.submit-import-excel');
        Route::get('export-excel', 'OrderReasonCancelController@exportAction')->name('admin.order-reason-cancel.submit-export-excel');
    });

    //MEMBER LEVEL VERB
    Route::group(['prefix' => 'member-level-verb'], function () {
        Route::get('/', 'MemberLevelVerbController@indexAction')->name('admin.member-level-verb');
        Route::post('list', 'MemberLevelVerbController@listAction')->name('admin.member-level-verb.list');
        Route::post('change-status', 'MemberLevelVerbController@changeStatusAction')->name('admin.member-level-verb.chan∑ge-status');
        Route::get('add', 'MemberLevelVerbController@addAction')->name('admin.member-level-verb.add');
        Route::post('add', 'MemberLevelVerbController@submitAddAction')->name('admin.member-level-verb.submitadd');
        Route::get('edit/{id}', 'MemberLevelVerbController@editAction')->name('admin.member-level-verb.edit');
        Route::post('edit/{id}', 'MemberLevelVerbController@submitEditAction')->name('admin.member-level-verb.submitedit');
        Route::post('remove/{id}', 'MemberLevelVerbController@removeAction')->name('admin.member-level-verb.remove');
        Route::post('export-excel', 'MemberLevelVerbController@exportExcelAction')->name('admin.member-level-verb.export-excel');
    });

    //DEPARTMENT
    Route::group(['prefix' => 'department'], function () {
        Route::get('/', 'DepartmentController@indexAction')->name('admin.department');
        Route::post('list', 'DepartmentController@listAction')->name('admin.department.list');
        Route::post('change-status', 'DepartmentController@changeStatusAction')->name('admin.department.change-status');
        Route::post('show-pop-add', 'DepartmentController@showPopupAddAction')->name('admin.department.show-pop-add');
        Route::post('add', 'DepartmentController@add')->name('admin.department.add');
        Route::post('remove/{id}', 'DepartmentController@removeAction')->name('admin.department.remove');
        Route::post('edit', 'DepartmentController@editAction')->name('admin.department.edit');
        Route::post('edit-submit', 'DepartmentController@submitEditAction')->name('admin.department.submit-edit');
    });

    //STORE
    Route::group(['prefix' => 'store'], function () {
        Route::get('/', 'StoreController@indexAction')->name('admin.store');
        Route::post('list', 'StoreController@listAction')->name('admin.store.list');
        Route::post('uploads', 'StoreController@uploadsAction')->name('admin.store.uploads');
        Route::post('deleteImage', 'StoreController@deleteTempFileAction')->name('admin.store.delete');
        Route::post('change-province', 'DistrictController@changeProvinceAction')->name('admin.store.change-province');
        Route::post('change-district', 'WardController@changeDistrictAction')->name('admin.store.change-district');
        Route::get('add', 'StoreController@addAction')->name('admin.store.add');
        Route::post('add', 'StoreController@submitAddAction')->name('admin.store.submitAdd');
        Route::post('remove/{id}', 'StoreController@removeAction')->name('admin.store.remove');
        Route::get('edit/{id}', 'StoreController@editAction')->name('admin.store.edit');
        Route::post('edit/{id}', 'StoreController@submitEditAction')->name('admin.store.submitedit');
        Route::post('change-status', 'StoreController@changeStatusAction')->name('admin.store.change-status');
        Route::post('export', 'StoreController@exportAction')->name('admin.store.export');
        Route::get('import', 'StoreController@importAction')->name('admin.store.import');
        Route::post('import', 'StoreController@submitImportAction')->name('admin.store.submitimport');
    });
    //BRANCH
    Route::group(['prefix' => 'branch'], function () {
        Route::get('/', 'BranchController@indexAction')->name('admin.branch');
        Route::post('list', 'BranchController@listAction')->name('admin.branch.list');
        Route::get('add', 'BranchController@addAction')->name('admin.branch.add');
        Route::post('submit-add', 'BranchController@submitAddAction')->name('admin.branch.submitAdd');
        Route::post('remove/{id}', 'BranchController@removeAction')->name('admin.branch.delete');
        //        Route::get('edit/{id}','BranchController@editAction')->name('admin.branch.edit');
        Route::get('edit/{id}', 'BranchController@editAction')->name('admin.branch.edit');
        Route::post('submit-edit', 'BranchController@submitEditAction')->name('admin.branch.submit-edit');
        Route::post('change-status', 'BranchController@changeStatusAction')->name('admin.branch.change-status');
        Route::post('district', 'BranchController@loadDistrictAction')->name('admin.branch.load-district');
        Route::post('upload', 'BranchController@uploadDropzoneAction')->name('admin.branch.upload');
        Route::post('delete-img', 'BranchController@deleteImageAction')->name('admin.branch.delete-img');
    });

    //TAX
    Route::group(['prefix' => 'tax'], function () {
        Route::get('/', 'TaxController@indexAction')->name('admin.tax');
        Route::post('list', 'TaxController@listAction')->name('admin.tax.list');
        Route::post('deleteImage', 'TaxController@deleteTempFileAction')->name('admin.tax.delete');
        Route::get('add', 'TaxController@addAction')->name('admin.tax.add');
        Route::post('add', 'TaxController@submitAddAction')->name('admin.tax.submitAdd');
        Route::post('remove/{id}', 'TaxController@removeAction')->name('admin.tax.remove');
        Route::get('edit/{id}', 'TaxController@editAction')->name('admin.tax.edit');
        Route::post('edit/{id}', 'TaxController@submitEditAction')->name('admin.tax.submitedit');
        Route::post('change-status', 'TaxController@changeStatusAction')->name('admin.tax.change-status');
        Route::get('export', 'TaxController@exportAction')->name('admin.tax.export');
        Route::get('import', 'TaxController@importAction')->name('admin.tax.import');
        Route::post('import', 'TaxController@submitImportAction')->name('admin.tax.submitimport');
    });
    //UNIT
    Route::group(['prefix' => 'unit'], function () {
        Route::get('/', 'UnitController@indexAction')->name('admin.unit');
        Route::post('list', 'UnitController@listAction')->name('admin.unit.list');
        Route::post('add', 'UnitController@submitAddAction')->name('admin.unit.submitadd');
        Route::post('edit', 'UnitController@editAction')->name('admin.unit.edit');
        Route::post('edit-submit', 'UnitController@submitEditAction')->name('admin.unit.submitedit');
        Route::post('change-status', 'UnitController@changeStatusAction')->name('admin.unit.change-status');
        Route::post('remove/{id}', 'UnitController@removeAction')->name('admin.unit.remove');
    });
    //WAREHOUSES
    Route::group(['prefix' => 'warehouse'], function () {
        Route::get('/', 'WarehouseController@indexAction')->name('admin.warehouse');
        Route::post('list', 'WarehouseController@listAction')->name('admin.warehouse.list');
        Route::post('add', 'WarehouseController@submitAddAction')->name('admin.warehouse.submitAdd');
        Route::post('remove/{id}', 'WarehouseController@removeAction')->name('admin.warehouse.delete');
        Route::post('edit', 'WarehouseController@editAction')->name('admin.warehouse.edit');
        //        Route::post('edit-submit','WarehouseController@submitEditAction')->name('admin.warehouse.submitedit');
        Route::post('edit-submit', 'WarehouseController@submitEditAction')->name('admin.warehouse.submitedit');
        Route::post('get-district', 'WarehouseController@getDistrictAction')->name('admin.warehouse.get-district');
        Route::post('check-is-retail', 'WarehouseController@checkIsRetailAction')->name('admin.warehouse.check-is-retail');
        Route::post('change-is-retail', 'WarehouseController@changeIsRetailAction')->name('admin.warehouse.change-is-retail');
        Route::post('create-store-ghn', 'WarehouseController@createStoreGHN')->name('admin.warehouse.create-store-ghn');
    });
    //UNIT_CONVERSION
    Route::group(['prefix' => 'unit_conversion'], function () {
        Route::get('/', 'UnitConversionController@indexAction')->name('admin.unit_conversion');
        Route::post('list', 'UnitConversionController@listAction')->name('admin.unit_conversion.list');
        Route::post('add', 'UnitConversionController@submitAddAction')->name('admin.unit_conversion.submitadd');
        Route::post('remove/{id}', 'UnitConversionController@removeAction')->name('admin.unit_conversion.remove');
        Route::post('edit', 'UnitConversionController@editAction')->name('admin.unit_conversion.edit');
        Route::post('submit-edit', 'UnitConversionController@submitEditAction')->name('admin.unit_conversion.submitedit');
    });
    //SUPPLIER
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', 'SupplierController@indexAction')->name('admin.supplier');
        Route::post('list', 'SupplierController@listAction')->name('admin.supplier.list');
        Route::post('add', 'SupplierController@addAction')->name('admin.supplier.add');
        Route::post('edit', 'SupplierController@editAction')->name('admin.supplier.edit');
        Route::post('edit-submit', 'SupplierController@submitEditAction')->name('admin.supplier.submit-edit');
        Route::post('remove/{id}', 'SupplierController@removeAction')->name('admin.supplier.remove');
    });
    //SHIFT
    Route::group(['prefix' => 'shift'], function () {
        Route::get('/', 'ShiftController@indexAction')->name('admin.shift');
        Route::post('list', 'ShiftController@listAction')->name('admin.shift.list');
        Route::post('add', 'ShiftController@addAction')->name('admin.shift.add');
        Route::post('edit', 'ShiftController@editAction')->name('admin.shift.edit');
        Route::post('edit-submit', 'ShiftController@submitEditAction')->name('admin.shift.submit-edit');
        Route::post('remove/{id}', 'ShiftController@removeAction')->name('admin.shift.remove');
        Route::post('change-status', 'ShiftController@changeStatusAction')->name('admin.shift.change-status');
    });
    //ORDER SOURCES
    Route::group(['prefix' => 'order-source'], function () {
        Route::get('/', 'OrderSourceController@indexAction')->name('admin.order-source');
        Route::post('list', 'OrderSourceController@listAction')->name('admin.order-source.list');
        Route::post('add', 'OrderSourceController@addAction')->name('admin.order-source.add');
        Route::post('edit', 'OrderSourceController@editAction')->name('admin.order-source.edit');
        Route::post('edit-submit', 'OrderSourceController@submitEditAction')->name('admin.order-source.submit-edit');
        Route::post('remove/{id}', 'OrderSourceController@removeAction')->name('admin.order-source.remove');
        Route::post('change-status', 'OrderSourceController@changeStatusAction')->name('admin.order-source.change-status');
    });
    //TRANSPORTS
    Route::group(['prefix' => 'transport'], function () {
        Route::get('/', 'TransportController@indexAction')->name('admin.transport');
        Route::post('list', 'TransportController@listAction')->name('admin.transport.list');
        Route::post('add', 'TransportController@submitAddAction')->name('admin.transport.submitadd');
        Route::post('edit', 'TransportController@editAction')->name('admin.transport.edit');
        Route::post('edit-submit', 'TransportController@submitEditAction')->name('admin.transport.submitedit');
        Route::post('remove/{id}', 'TransportController@removeAction')->name('admin.transport.remove');
    });
    //ROOM
    Route::group(['prefix' => 'room'], function () {
        Route::get('/', 'RoomController@indexAction')->name('admin.room');
        Route::post('list', 'RoomController@listAction')->name('admin.room.list');
        Route::post('add', 'RoomController@submitAddAction')->name('admin.room.submitadd');
        Route::post('edit', 'RoomController@editAction')->name('admin.room.edit');
        Route::post('edit-submit', 'RoomController@submitEditAction')->name('admin.room.submitedit');
        Route::post('remove/{id}', 'RoomController@removeAction')->name('admin.room.remove');
        Route::post('change-status', 'RoomController@changeStatusAction')->name('admin.room.change-status');
    });
    //PRODUCT ATTRIBUTE GROUP
    Route::group(['prefix' => 'product-attribute-group'], function () {
        Route::get('/', 'ProductAttributeGroupController@indexAction')->name('admin.product-attribute-group');
        Route::post('list', 'ProductAttributeGroupController@listAction')->name('admin.product-attribute-group.list');
        Route::post('add', 'ProductAttributeGroupController@addAction')->name('admin.product-attribute-group.add');
        Route::post('edit', 'ProductAttributeGroupController@editAction')->name('admin.product-attribute-group.edit');
        Route::post('edit-submit', 'ProductAttributeGroupController@submitEditAction')->name('admin.product-attribute-group.submit-edit');
        Route::post('remove/{id}', 'ProductAttributeGroupController@removeAction')->name('admin.product-attribute-group.remove');
        Route::post('change-status', 'ProductAttributeGroupController@changeStatusAction')->name('admin.product-attribute-group.change-status');
    });
    //PRODUCT ATTRIBUTE
    Route::group(['prefix' => 'product-attribute'], function () {
        Route::get('/', 'ProductAttributeController@indexAction')->name('admin.product-attribute');
        Route::post('list', 'ProductAttributeController@listAction')->name('admin.product-attribute.list');
        Route::post('add', 'ProductAttributeController@addAction')->name('admin.product-attribute.add');
        Route::post('edit', 'ProductAttributeController@editAction')->name('admin.product-attribute.edit');
        Route::post('edit-submit', 'ProductAttributeController@submitEditAction')->name('admin.product-attribute.submit-edit');
        Route::post('remove/{id}', 'ProductAttributeController@removeAction')->name('admin.product-attribute.remove');
        Route::post('change-status', 'ProductAttributeController@changeStatusAction')->name('admin.product-attribute.change-status');
    });
    //PRODUCT MODEL
    Route::group(['prefix' => 'product-model'], function () {
        Route::get('/', 'ProductModelController@indexAction')->name('admin.product-model');
        Route::post('list', 'ProductModelController@listAction')->name('admin.product-model.list');
        Route::post('add', 'ProductModelController@addAction')->name('admin.product-model.add');
        Route::post('edit', 'ProductModelController@editAction')->name('admin.product-model.edit');
        Route::post('edit-submit', 'ProductModelController@submitEditAction')->name('admin.product-model.submit-edit');
        Route::post('remove/{id}', 'ProductModelController@removeAction')->name('admin.product-model.remove');
    });
    //PRODUCT CATEGORY
    Route::group(['prefix' => 'product-category'], function () {
        Route::get('/', 'ProductCategoryController@indexAction')->name('admin.product-category');
        Route::post('list', 'ProductCategoryController@listAction')->name('admin.product-category.list');
        Route::post('show-modal-add', 'ProductCategoryController@showModalAddAction')->name('admin.product-category.show-modal-add');
        Route::post('add', 'ProductCategoryController@addAction')->name('admin.product-category.add');
        Route::post('edit', 'ProductCategoryController@editAction')->name('admin.product-category.edit');
        Route::post('edit-submit', 'ProductCategoryController@submitEditAction')->name('admin.product-category.submit-edit');
        Route::post('remove/{id}', 'ProductCategoryController@removeAction')->name('admin.product-category.remove');
        Route::post('change-status', 'ProductCategoryController@changeStatusAction')->name('admin.product-category.change-status');
    });
    //STAFF
    Route::group(['prefix' => 'staff'], function () {
        Route::get('/', 'StaffsController@indexAction')->name('admin.staff');
        Route::post('list', 'StaffsController@listAction')->name('admin.staff.list');
        Route::get('add', 'StaffsController@addAction')->name('admin.staff.add');
        Route::post('add', 'StaffsController@submitAddAction')->name('admin.staff.submitAdd');
        Route::post('remove/{id}', 'StaffsController@removeAction')->name('admin.staff.remove');
        Route::post('change-status', 'StaffsController@changeStatusAction')->name('admin.staff.change-status');
        Route::get('edit/{id}', 'StaffsController@editAction')->name('admin.staff.edit');
        Route::post('edit', 'StaffsController@submitEditAction')->name('admin.staff.submit-edit');
        Route::post('uploads', 'StaffsController@uploadAction')->name('admin.staff.uploads');
        Route::post('deleteImage', 'StaffsController@deleteTempFileAction')->name('admin.staff.delete');
        Route::post('edit-image', 'StaffsController@editImageAction')->name('admin.staff.editimage');
        Route::get('profile/{id}', 'StaffsController@profileAction')->name('admin.staff.profile');
        Route::post('export-all', 'StaffsController@exportAllAction')->name('admin.staff.export-all');
        Route::get('detail/{id}', 'StaffsController@show')->name('admin.staff.show');
        Route::post('/time-keeping-list', 'StaffsController@listTimekeepingAction')->name('admin.timekeeping.list_detail');
        //Thay đổi phòng ban load nhóm
        Route::post('change-department', 'StaffsController@changeDepartmentAction')->name('admin.staff.change-department');
    });
    //PRODUCT
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'ProductController@indexAction')->name('admin.product');
        Route::post('list', 'ProductController@listAction')->name('admin.product.list');
        Route::get('add', 'ProductController@addAction')->name('admin.product.add');
        Route::post('submit-add', 'ProductController@submitAddAction')->name('admin.product.submit-add');
        Route::get('edit/{id}', 'ProductController@editAction')->name('admin.product.edit');
        Route::post('edit-submit', 'ProductController@submitEditAction')->name('admin.product.submit-edit');
        Route::post('remove/{id}', 'ProductController@removeAction')->name('admin.product.remove');
        Route::post('change-status', 'ProductController@changeStatusAction')->name('admin.product.change-status');
        Route::post('test-product-code', 'ProductController@testProductCodeAction')->name('admin.product.test-product-code');
        Route::post('get-product-attribute-group', 'ProductController@getOptionProductAttributeGroupAction')->name('admin.product.get-product-attribute-group');
        Route::post('get-product-attribute', 'ProductController@getProductAttribute')->name('admin.product.get-product-attribute');
        Route::post('get-product-attribute-by-group', 'ProductAttributeController@getProductAttributeByGroup')->name('get-product-attribute-by-group');
        Route::post('add-product-attribute', 'ProductController@addProductAttributeAction')->name('admin.add-product-attribute');
        Route::post('uploads', 'ProductController@uploadsAction')->name('admin.product-uploads');
        Route::post('product-version', 'ProductController@productVersionAction')->name('admin.product-version');
        Route::post('product-attribute-groups', 'ProductController@productAttributeGroupAction')->name('admin.product-attribute-groups');
        Route::post('delete-image-temp', 'ProductController@deleteFileAction')->name('admin.delete-image-temp');
        Route::get('detail/{id}', 'ProductController@detailAction')->name('product-detail');
        Route::post('create-name-product-child', 'ProductController@createNameProductChild')->name('create-name-product-child');
        Route::post('get-product-attribute-edit', 'ProductController@getProductAttributeEditAction')->name('admin.product.get-product-attribute-edit');
        Route::post('check-name', 'ProductController@checkNameAction')->name('admin.product.check-name');
        Route::post('upload-avatar', 'ProductController@uploadAvatar')->name('admin.product.upload-avatar');
        Route::post('delete-image-by-productId-link', 'ProductController@deleteImageByProductIdAndLinkAction')->name('product.delete-image-by-productId-link');
        Route::post('delete-image-temp-2', 'ProductController@deleteImageTempAction')->name('admin.product.delete-image-temp2');
        Route::post('product-branch-prices', 'ProductController@editProductBranchPrice')->name('admin.product.edit-product-branch-price');
        Route::post('remove-product-inventory', 'ProductController@removeProductInventoryAction')->name('admin.product.remove-product-inventorys');
        Route::post('import-file-image', 'ProductController@importFileImageAction')->name('admin.product.import-file-image');
        Route::get('un-display', 'ProductController@unDisplayAction')->name('admin.product.un-display');
        Route::post('check-serial-edit', 'ProductController@checkSerialEdit')->name('admin.product.check-serial-edit');
        Route::post('check-basic-edit', 'ProductController@checkBasicEdit')->name('admin.product.check-basic-edit');
        Route::post('show-popup-serial', 'ProductController@showPopupSerial')->name('admin.product.showPopupSerial');
        Route::post('search-serial', 'ProductController@searchSerial')->name('admin.product.search-serial');
        Route::post('get-sku-product', 'ProductController@genSkuProduct')->name('admin.product.get-sku-product');
        Route::post('check-sku', 'ProductController@checkSkuAction')->name('admin.product.check-sku');
        Route::post('get-list-product-child', 'ProductController@getListProductChild')->name('admin.product.get-list-product-child');

        // Import product theo template
        Route::post('import', 'ProductController@importProductAction')->name('admin.product.import-template');
        // Export template để import theo template
        Route::get('export', 'ProductController@exportProductTemplateAction')->name('admin.product.export-template');
    });
    //PRODUCT IMAGE
    Route::group(['prefix' => 'product-image'], function () {
        Route::get('/', 'ProductImageController@indexAction')->name('admin.product-image');
        Route::post('list', 'ProductImageController@listAction')->name('admin.product-image.list');
        Route::post('add', 'ProductImageController@addAction')->name('admin.product-image.add');
        Route::post('edit', 'ProductImageController@editAction')->name('admin.product-image.edit');
        Route::post('edit-submit', 'ProductImageController@submitEditAction')->name('admin.product-image.submit-edit');
        Route::post('remove/{id}', 'ProductImageController@removeAction')->name('admin.product-image.remove');
    });
    //SERVICE CARD
    Route::group(["prefix" => "service-card"], function () {
        Route::get("/", "ServiceCardController@indexAction")->name("admin.service-card");
        Route::post("list", "ServiceCardController@listAction")->name("admin.service-card.list");
        Route::get("create", "ServiceCardController@createAction")->name("admin.service-card.create");
        Route::post("create/submit", "ServiceCardController@submitCreateAction")->name("admin.service-card.submitCreate");
        Route::get("edit/{id}", "ServiceCardController@editAction")->name("admin.service-card.edit");
        Route::post("edit/submit", "ServiceCardController@submitEditAction")->name("admin.service-card.submitEdit");
        Route::get("get-type-template", "ServiceCardController@getServiceTypeTemplate")->name("admin.service-card.type-template");
        Route::post("create-group", "ServiceCardController@addNewServiceCardGroup")->name("admin.service-card.create-group");
        Route::post("delete/{id}", "ServiceCardController@deleteAction")->name("admin.service-card.delete");
        Route::get("detail/{id}", "ServiceCardController@getDetailAction")->name("admin.service-card.detail");
        Route::post("detail/list", "ServiceCardController@listDetailAction")->name("admin.service-card.detail-list");
        Route::post("uploads/image", "ServiceCardController@uploadsImageAction")->name("admin.service-card.uploads-image");
        Route::post("uploads/image/delete", "ServiceCardController@deleteUploadAction")->name("admin.service-card.delete-uploads-image");
        Route::post("filter", "ServiceCardController@filterAction")->name("admin.service-card.filter");
        Route::post("paging", "ServiceCardController@pagingAction")->name("admin.service-card.paging");
        Route::post("paging-search", "ServiceCardController@pagingResultFilterAction")->name("admin.service-card.paging-search");
        Route::post("paging-detail-all-card", "ServiceCardController@pagingDetailAllServiceCard")->name("admin.service-card.paging-detail-all-card");
        Route::post("upload-avatar", "ServiceCardController@uploadAvatar")->name("admin.service-card.upload-avatar");
        Route::post("check-name", "ServiceCardController@checkNameAction")->name("admin.service-card.check-name");
        Route::get("service-card-sold", "ServiceCardController@serviceCardSold")->name("admin.service-card.sold.service-card");
        Route::get("service-money-sold", "ServiceCardController@serviceMoneySold")->name("admin.service-card.sold.service-money");
        Route::post("filter-card-sold", "ServiceCardController@filterCardSoldAction")->name("admin.service-card.sold.filter");
        Route::get("detail-card-sold/{type}/{code}", "ServiceCardController@detailCardSold")->name("admin.service-card.sold.detail");
        Route::get("edit-card-sold/{type}/{code}", "ServiceCardController@editCardSold")->name("admin.service-card.sold.edit");
        Route::post("update-card-sold", "ServiceCardController@updateCardSold")->name("admin.service-card.sold.update");
        Route::post("detail-card-sold-paginate", "ServiceCardController@listActionDetailCardSold")->name("admin.service-card.sold.detail-paginate");
        Route::post("card-sold-paginate", "ServiceCardController@pagingCardSolAction")->name("admin.service-card.sold.paginate");
        Route::post("card-sold-paging-search", "ServiceCardController@pagingCardSoldFilter")->name("admin.service-card.sold.paging-search");
        Route::post('change-status', 'ServiceCardController@changeStatusAction')->name('admin.service-card.change-status');
        Route::post('change-status-surcharge', 'ServiceCardController@changeStatusSurcharge')->name('admin.service-card.change-status-surcharge');
        Route::post("paging-detail-card-use", "ServiceCardController@pagingDetailServiceCardUsed")->name("admin.service-card.paging-detail-card-use");
        Route::post("reserve-service-card-sold", "ServiceCardController@reserveServiceCard")
            ->name("admin.service-card.sold.reserve");
        Route::post("open-reserve-service-card-sold", "ServiceCardController@openReserveServiceCard")
            ->name("admin.service-card.sold.open-reserve");
        // SERVICE CARD SOLD IMAGE
        Route::post("service-card-sold-image", "ServiceCardController@saveImageServiceCardSold")
            ->name("admin.service-card.sold.service-card.save-image");
        Route::post("image-for-carousel", "ServiceCardController@getImageForCarousel")
            ->name("admin.service-card.sold.service-card.image-for-carousel");
        // SERVICE CARD SOLD ACCRUAL: CỘNG DỒN THẺ LIỆU TRÌNH
        Route::post("modal-accrual-scs", "ServiceCardController@modalAccrualSCSold")
            ->name("admin.service-card.sold.service-card.modal-accrual-scs");
        Route::post("submit-accrual-scs", "ServiceCardController@submitAccrualSCSold")
            ->name("admin.service-card.sold.service-card.submit-accrual-scs");
    });

    Route::group(["prefix" => "service-card-list"], function () {
        Route::get("/", "ServiceCardListController@indexAction")->name("admin.service-card-list");
        Route::post("list", "ServiceCardListController@listAction")->name("admin.service-card-list.list");
        Route::get("create", "ServiceCardListController@createAction")->name("admin.service-card-list.create");
        Route::post("create", "ServiceCardListController@submitCreateAction")->name("admin.service-card-list.create-submit");
        Route::get("/detail/{id}", "ServiceCardListController@detailAction")->name("admin.service-card-list.detail");
        Route::post("/detail/list-unuse", "ServiceCardListController@getUnuseCardList")->name("admin.service-card-list.detail-list-unuse");
        Route::post("/detail/list-inuse", "ServiceCardListController@getInuseCardList")->name("admin.service-card-list.detail-list-inuse");
        Route::post("get-card-code", "ServiceCardListController@getCodeAction")->name("admin.service-card-list.getcode");
        Route::post("get-card-price", "ServiceCardListController@getCardServicePrice")->name("admin.service-card-list.getprice");
        Route::post('paging', 'ServiceCardListController@pagingAction')->name('admin.service-card-list.paging');
        Route::post('filter', 'ServiceCardListController@filterAction')->name('service-card-list.filter');
        Route::post('paging-search', 'ServiceCardListController@pagingResultFilterAction')->name('admin.service-card-list.paging-search');
    });

    Route::group(["prefix" => "order"], function () {
        Route::get("/", "OrdersController@indexAction")->name("admin.order");
        Route::post('list', 'OrdersController@listAction')->name('admin.order.list');
        Route::post('list-order-customer', 'OrdersController@listOrderCustomerAction')->name('admin.order.list-order-customer');
        Route::get('add', 'OrdersController@addAction')->name('admin.order.add');
        Route::post('list-add', 'OrdersController@listAddAction')->name('admin.order.list-add');
        Route::post('search-customer', 'OrdersController@searchCustomerAction')->name('admin.order.search-customer');
        Route::post('add', 'OrdersController@submitAddAction')->name('admin.order.submitAdd');
        Route::post('submit-add-or-update', 'OrdersController@saveOrUpdateOrderV2')->name('admin.order.submit-add-or-update');
        Route::post('search', 'OrdersController@searchAction')->name('admin.order.search');
        //        Route::post('detail', 'OrdersController@getItemDetail')->name('admin.order.detail');
        Route::post('search-detail', 'OrdersController@searchNewDetailAction')->name('admin.order.search-detail');
        Route::post('add-customer', 'OrdersController@addCustomerAction')->name('admin.order.add-customer');
        Route::post('add-discount', 'OrdersController@addDiscountAction')->name('admin.order.add-discount');
        Route::post('add-discount-bill', 'OrdersController@addDiscountBillAction')->name('admin.order.add-discount-bill');
        Route::post('add-receipt', 'OrdersController@submitAddReceiptAction')->name('admin.order.submitAddReceipt');
        Route::post('create-qrcode-vnpay', 'OrdersController@createQrCodeVnPay')
            ->name('admin.order.create-qrcode-vnpay');
        Route::post('remove/{id}', 'OrdersController@removeAction')->name('admin.order.remove');
        Route::post('receipt', 'OrdersController@receiptAction')->name('admin.order.receipt');
        Route::post('check-card-customer', 'OrdersController@checkCardCustomerAction')->name('admin.order.check-card-customer');
        Route::get('receipt-after/{id}', 'OrdersController@receiptAfterAction')->name('admin.order.receipt-after');
        Route::post('submit-receipt-after', 'OrdersController@submitReceiptAfterAction')->name('admin.order.submit-receipt-after');
        Route::post('submit-print-card', 'OrdersController@submitPrintCardAction')->name('admin.order.submitPrint');
        Route::get('detail/{id}', 'OrdersController@detailAction')->name('admin.order.detail');
        Route::post('print-bill', 'OrdersController@getInfoForPrintBill')->name('admin.order.print-bill');
        Route::post('print-card-all', 'OrdersController@printCardAllAction')->name('admin.order.print-card-all');
        Route::post('print-card-one', 'OrdersController@printOneCardAction')->name('admin.order.print-card-one');
        Route::get('print-bill-get', 'OrdersController@getInfoForPrintBill')->name('admin.order.print-bill2');
        Route::post('print-bill-save-log', 'OrdersController@saveLogPrintBillAction')->name('admin.order.save-log-print-bill');
        Route::post('render-card', 'OrdersController@renderCardAction')->name('admin.order.render-card');
        Route::post('check-email-customer', 'OrdersController@checkEmailCustomerAction')->name('admin.order.check-email-customer');
        Route::post('submit-send-email', 'OrdersController@submitSendEmailAction')->name('admin.order.submit-send-email');
        Route::post('submit-edit', 'OrdersController@submitEditAction')->name('admin.order.submit-edit');
        Route::post('save-order', 'OrdersController@saveOrderToAppointmentAction')->name('admin.order.save-order');
        Route::post('save-or-update-order', 'OrdersController@saveOrUpdateOrderToAppointmentAction')->name('admin.order.save-or-update-order');
        Route::post('check-voucher', 'OrdersController@checkVoucherReceiptAfterAction')->name('admin.order.check-voucher');
        Route::get('print-bill-not-receipt', 'OrdersController@printBillNotReceiptAction')->name('admin.order.print-bill-not-receipt');
        Route::post('cancel-order', 'OrdersController@cancelOrderAction')->name('admin.order.cancel');
        Route::post('submit-cancel-order', 'OrdersController@submitCancelOrderAction')->name('admin.order.submit-cancel');
        Route::post('loyalty', 'OrdersController@loyaltyAction')->name('admin.order.loyalty');
        Route::post('apply-branch', 'OrdersController@applyBranchAction')->name('admin.order.apply-branch');
        Route::post('submit-apply-branch', 'OrdersController@submitApplyBranchAction')->name('admin.order.submit-apply-branch');
        Route::post('check-promotion-gift', 'OrdersController@checkPromotionGiftAction')->name('admin.order.check-gift');
        Route::post('export-list', 'OrdersController@exportList')->name('admin.order.exportList');
        Route::post('check-serial-enter', 'OrdersController@checkSerialEnter')->name('admin.order.checkSerialEnter');
        Route::post('remove-serial', 'OrdersController@removeSerial')->name('admin.order.removeSerial');
        Route::post('show-popup-serial', 'OrdersController@showPopupSerial')->name('admin.order.showPopupSerial');
        Route::post('search-serial', 'OrdersController@searchSerial')->name('admin.order.searchSerial');
        Route::post('get-list-serial', 'OrdersController@getListSerial')->name('admin.order.getListSerial');
        Route::post('show-popup-address', 'OrdersController@showPopupAddress')->name('admin.order.showPopupAddress');
        Route::post('show-popup-add-address', 'OrdersController@showPopupAddAddress')->name('admin.order.showPopupAddAddress');
        Route::post('change-province', 'OrdersController@changeProvince')->name('admin.order.changeProvince');
        Route::post('change-district', 'OrdersController@changeDistrict')->name('admin.order.changeDistrict');
        Route::post('submit-address', 'OrdersController@submitAddress')->name('admin.order.submitAddress');
        Route::post('remove-address-customer', 'OrdersController@removeAddressCustomer')->name('admin.order.removeAddressCustomer');
        Route::post('change-info-address', 'OrdersController@changeInfoAddress')->name('admin.order.changeInfoAddress');
        //Lưu ảnh sau khi sử dụng
        Route::post('save-image', 'OrdersController@saveImageAction')->name('admin.order.save-image');
        //Chọn dịch vụ/ sản phẩm
        Route::post('choose-type', 'OrdersController@chooseTypeAction')->name('admin.order.choose-type');
        Route::post('show-popup-attach', 'OrdersController@showPopupAttachAction')->name('admin.order.show-popup-attach');
    });

    Route::group(["prefix" => "orders-all"], function () {
        Route::get("/", "OrdersAllController@indexAction")->name("admin.orders-all");
        Route::post('list', 'OrdersAllController@listAction')->name('admin.order-all.list');
        Route::get('export-list', 'OrdersAllController@exportList')->name('admin.order-all.exportList');
    });

    Route::group(["prefix" => "voucher"], function () {
        Route::get("/", "VoucherController@indexAction")->name("admin.voucher");
        Route::post("list", "VoucherController@listAction")->name("admin.voucher.list");
        Route::get("create", "VoucherController@createAction")->name("admin.voucher.create");
        Route::post("create", "VoucherController@submitCreateAction")->name("admin.voucher.submitCreate");
        Route::get("edit/{id}", "VoucherController@editAction")->name("admin.voucher.edit");
        Route::post("edit/submit", "VoucherController@submitEditAction")->name("admin.voucher.submitEdit");
        Route::post("delete/{id}", "VoucherController@deleteAction")->name("admin.voucher.delete");
        Route::post("detail/{id}", "VoucherController@detailAction")->name("admin.voucher.detail");
        Route::post("change-status/{id}", "VoucherController@changeStatusAction")->name("admin.voucher.changeStatus");
        Route::post("get-object-by-type", "VoucherController@getObjectByType")->name("admin.voucher.getObject");
        Route::post("filter-object-by-type", "VoucherController@filterObjectByType")->name("admin.voucher.filterObject");
        Route::post("check-slug", "VoucherController@checkSlug")->name("admin.voucher.check-slug");
        Route::post('upload', 'VoucherController@uploadAction')->name('admin.voucher.upload');
    });
    /*
     * Author : Huy
     *
     * ----------------------------------------*/
    //Service
    Route::group(['prefix' => 'service'], function () {
        Route::get('/', 'ServiceController@indexAction')->name('admin.service');
        Route::post('list', 'ServiceController@listAction')->name('admin.service.list');
        Route::get('add', 'ServiceController@addAction')->name('admin.service.add');
        Route::post('submit-add', 'ServiceController@submitAddAction')->name('admin.service.submitAdd');
        Route::post('add-unit', 'ServiceController@getOptionUnitAction')->name('admin.service.getUnit');
        Route::post('option-branch', 'ServiceController@getOptionBranchAction')->name('admin.service.getBranch');
        Route::post('search-option-product', 'ServiceController@getOptionProductAction')->name('admin.search.product');
        Route::post('search-option-services', 'ServiceController@getOptionServicesAction')->name('admin.search.services');
        Route::get('detail/{id}', 'ServiceController@detailAction')->name('admin.service.detail');
        Route::post('detail-branch/{id}', 'ServiceController@listBranchDetail')->name('admin.service.list-branch-detail');
        Route::post('detail-material/{id}', 'ServiceController@listMaterialDetail')->name('admin.service.list-material-detail');
        Route::post('submit-edit', 'ServiceController@submitEditAction')->name('admin.service.submitEdit');
        Route::get('edit/{id}', 'ServiceController@editAction')->name('admin.service.edit');
        Route::post('remove/{id}', 'ServiceController@removeAction')->name('admin.service.remove');
        Route::post('uploads', 'ServiceController@uploadAction')->name('admin.service.uploads');
        Route::post('upload-dropzone', 'ServiceController@uploadDropzoneAction')->name('admin.service.upload-dropzone');
        Route::post('delete-image', 'ServiceController@deleteImageAction')->name('admin.service.delete-image');
        Route::post('change-status', 'ServiceController@changeStatusAction')->name('admin.service.change-status');
    });
    //Service Category
    Route::group(['prefix' => 'service_category'], function () {
        Route::get('/', 'ServiceCategoryController@indexAction')->name('admin.service_category');
        Route::post('list', 'ServiceCategoryController@listAction')->name('admin.service_category.list');
        Route::post('add', 'ServiceCategoryController@addAction')->name('admin.service_category.submitAdd');
        Route::post('change-status', 'ServiceCategoryController@changeStatusAction')->name('admin.service_category.change-status');
        Route::post('remove/{id}', 'ServiceCategoryController@removeAction')->name('admin.service_category.remove');
        Route::post('edit', 'ServiceCategoryController@editAction')->name('admin.service_category.edit');
        Route::post('edit-submit', 'ServiceCategoryController@submitEditAction')->name('admin.service_category.submitEdit');
    });
    Route::group(['prefix' => 'customer-log'], function () {
        Route::get('', 'CustomerLogController@indexAction')->name('admin.customer.customer-log');
        Route::post('list', 'CustomerLogController@listAction')->name('admin.customer.customer-log.list');
        Route::post('list-log-update', 'CustomerLogController@listLogUpdate')->name('admin.customer.customer-log.list-log-update');
    });
    //CUSTOMER
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'CustomerController@indexAction')->name('admin.customer');
        Route::post('list', 'CustomerController@listAction')->name('admin.customer.list');
        Route::post('load-district', 'CustomerController@loadDistrictAction')->name('admin.customer.load-district');
        Route::post('load-ward', 'CustomerController@loadWard')->name('admin.customer.load-ward');
        Route::get('add', 'CustomerController@addAction')->name('admin.customer.add');
        Route::post('add-customer-group', 'CustomerController@submitAddCustomerGroupAction')->name('admin.customer.add-customer-group');
        Route::post('add-customer-refer', 'CustomerController@submitAddCustomerReferAction')->name('admin.customer.add-customer-refer');
        Route::post('search-customer-refer', 'CustomerController@searchCustomerReferAction')->name('admin.customer.search-customer-refer');
        Route::post('search-customer-chathub', 'CustomerController@searchCustomerChathubAction')->name('admin.customer.search-customer-chathub');

        Route::post('submit-add', 'CustomerController@submitAddAction')->name('admin.customer.submitAdd');
        Route::get('detail/{id}', 'CustomerController@detailAction')->name('admin.customer.detail');
        Route::get('edit/{id}', 'CustomerController@editAction')->name('admin.customer.edit');
        Route::post('edit', 'CustomerController@submitEditAction')->name('admin.customer.submitEdit');
        Route::post('update-from-oncall', 'CustomerController@updateCustomerFromOncall')->name('admin.customer.update-from-oncall');
        Route::get('editAjax', 'CustomerController@loadEditAjaxAction')->name('admin.customer.editAjax');
        Route::post('remove/{id}', 'CustomerController@removeAction')->name('admin.customer.remove');
        Route::post('uploads', 'CustomerController@uploadAction')->name('admin.customer.uploads');
        Route::post('deleteImage', 'CustomerController@deleteTempFileAction')->name('admin.customer.delete');
        Route::post('load-birthday', 'CustomerController@loadBirthdayAction')->name('admin.customer.load-bithday');
        Route::post('check-card', 'CustomerController@checkCardAction')->name('admin.customer.check-card');
        Route::post('submit-active', 'CustomerController@submitActiveCardAction')->name('admin.customer.submitAcitve');
        Route::post('change-status', 'CustomerController@changeStatusAction')->name('admin.customer.change-status');
        Route::get('export-excel', 'CustomerController@exportExcelAction')->name('admin.customer.export-excel');
        Route::post('import-excel', 'CustomerController@importExcelAction')->name('admin.customer.import-excel');
        Route::post('modal-process-card', 'CustomerController@formProcessCardAction')->name('admin.customer.modal-process-card');
        Route::post('choose-service-card', 'CustomerController@chooseServiceCardAction')->name('admin.customer.choose-service-card');
        Route::post('change-active-date', 'CustomerController@changeActiveDateAction')->name('admin.customer.change-active-date');
        Route::post('submit-process-card', 'CustomerController@submitProcessCard')->name('admin.customer.submit-process-card');
        Route::post('enter-debt', 'CustomerController@enterDebtAction')->name('admin.customer.enter-debt');
        Route::post('modal-commission', 'CustomerController@commissionAction')->name('admin.customer.commission');
        Route::post('submit-commission', 'CustomerController@submitCommissionAction')->name('admin.customer.submit-commission');
        Route::post('get-info-customer-detail', 'CustomerController@getInfoCustomerDetail')->name('admin.customer.get-info-customer-detail');
        Route::post('customer-update-ward', 'CustomerController@customerUpdateWard')->name('admin.customer.customer-update-ward');
        Route::get('loyalty-event-birthday', 'CustomerController@loyaltyEventBirthday')->name('admin.customer.loyalty-event-birthday');
        Route::post('export-all', 'CustomerController@exportExcelAll')->name('admin.customer.export-all');

        Route::post('info-and-contact-default', 'CustomerController@getCustomerAndDefaultContact')
            ->name('admin.customer.info-and-contact-default');
        //Sử dụng nhanh thẻ liệu trình
        Route::post('using-card', 'CustomerController@usingCardAction')->name('admin.customer.using-card');
        //Thêm nhanh loại thông tin
        Route::post('add-info-type', 'CustomerController@addInfoTypeAction')->name('admin.customer.add-info-type');
        //Thêm xem khách hàng theo chi nhánh
        Route::post('modal-customer-branch', 'CustomerController@modalCustomerBranch')->name('admin.customer.modal-customer-branch');
        Route::post('save-customer-branch', 'CustomerController@saveCustomerBranch')->name('admin.customer.save-customer-branch');

        Route::post('get-list-customer-real-care', 'CustomerController@getListCustomerRealCare')->name('admin.customer.get-list-customer-real-care');
        Route::post('get-list-receipt', 'CustomerController@getListReceiptAction')->name('admin.customer.get-list-receipt');

        //Load tab trong chi tiết KH
        Route::post('load-tab-detail', 'CustomerController@loadTabDetailAction')->name('admin.customer.load-tab-detail');
        //DS lịch sử tích luỹ của KH
        Route::post('list-loyalty', 'CustomerController@listLoyaltyAction')->name('admin.customer.list-loyalty');

        //Thêm bình luận
        Route::post('add-comment', 'CustomerController@addComment')->name('customer.detail.add-comment');
        //show form reply
        Route::post('show-comment-child', 'CustomerController@showFormComment')->name('customer.detail.show-form-comment');
        //get list comment
        Route::post('get-list-comment', 'CustomerController@getListComment')->name('customer.detail.get-list-comment');

        //In công nợ của KH
        Route::get('print-bill-debt', 'CustomerController@printBillDebtAction')->name('admin.customer.print-bill-debt');
        //Show pop thanh toán nhanh công nợ
        Route::post('pop-quick-receipt-debt', 'CustomerController@showPopQuickReceiptDebtAction')->name('admin.customer.pop-quick-receipt-debt');
        //Submit thanh toán nhanh công nợ
        Route::post('submit-quick-receipt-debt', 'CustomerController@submitQuickReceiptDebtAction')->name('admin.customer.submit-quick-receipt-debt');
        //Danh sách người liên hệ
        Route::post('list-person-contact', 'CustomerController@listPersonContactAction')->name('admin.customer.list-person-contact');
        //Show pop thêm người liên hệ
        Route::post('pop-create-person-contact', 'CustomerController@showPopCreatePersonContactAction')->name('admin.customer.pop-create-person-contact');
        //Thêm người liên hệ
        Route::post('store-person-contact', 'CustomerController@storePersonContactAction')->name('admin.customer.store-person-contact');
        //Show pop chỉnh sửa người liên hệ
        Route::post('pop-edit-person-contact', 'CustomerController@showPopEditPersonContactAction')->name('admin.customer.pop-edit-person-contact');
        //Chỉnh sửa người liên hệ
        Route::post('update-person-contact', 'CustomerController@updatePersonContactAction')->name('admin.customer.update-person-contact');
        //Danh sách ghi chú
        Route::post('list-note', 'CustomerController@listNoteAction')->name('admin.customer.list-note');
        //Show pop thêm ghi chú
        Route::post('pop-create-note', 'CustomerController@showPopCreateNoteAction')->name('admin.customer.pop-create-note');
        //Thêm ghi chú
        Route::post('store-note', 'CustomerController@storeNoteAction')->name('admin.customer.store-note');
        //Show pop chỉnh sửa ghi chú
        Route::post('pop-edit-note', 'CustomerController@showPopEditNoteAction')->name('admin.customer.pop-edit-note');
        //Chỉnh sửa ghi chú
        Route::post('update-note', 'CustomerController@updateNoteAction')->name('admin.customer.update-note');
        //Danh sách tập tin
        Route::post('list-file', 'CustomerController@listFileAction')->name('admin.customer.list-file');
        //Show pop thêm tập tin
        Route::post('pop-create-file', 'CustomerController@showPopCreateFileAction')->name('admin.customer.pop-create-file');
        //Thêm tập tin
        Route::post('store-file', 'CustomerController@storeFileAction')->name('admin.customer.store-file');
        //Show pop chỉnh sửa tập tin
        Route::post('pop-edit-file', 'CustomerController@showPopEditFileAction')->name('admin.customer.pop-edit-file');
        //Chỉnh sửa tập tin
        Route::post('update-file', 'CustomerController@updateFileAction')->name('admin.customer.update-file');
        //Danh sách tập tin
        Route::post('list-deals', 'CustomerController@listDealsAction')->name('admin.customer.list-deals');
    });
    //PRODUCT INVENTORY
    Route::group(['prefix' => 'product-inventory'], function () {
        Route::get('/', 'ProductInventoryController@indexAction')->name('admin.product-inventory');
        Route::post('search-by-warehouse', 'ProductInventoryController@searchByWarehouse')->name('admin.product-inventory.search-by-warehouse');
        Route::post('search-by-product', 'ProductInventoryController@searchProduct')->name('admin.product-inventory.search-by-product');
        Route::post('history', 'ProductInventoryController@historyAction')->name('admin.product-inventory.history');
        Route::post('list-inventory-input', 'InventoryInputController@renderList')->name('admin.product-inventory.list-inventory-input');
        Route::post('list-inventory-output', 'InventoryOutputController@renderList')->name('admin.product-inventory.list-inventory-output');
        Route::post('list-inventory-checking', 'InventoryCheckingController@renderList')->name('admin.product-inventory.list-inventory-checking');
        Route::post('list-inventory-transfer', 'InventoryTransferController@renderList')->name('admin.product-inventory.list-inventory-transfer');
        Route::post('list', 'ProductInventoryController@listAction')->name('admin.product-inventory.list');
        Route::post('paging', 'ProductInventoryController@pagingAction')->name('admin.product-inventory.paging');
        Route::post('paging-search', 'ProductInventoryController@pagingSearchProductInventory')->name('admin.product-inventory.paging-search');
        Route::post('paging-history', 'ProductInventoryController@pagingHistoryAction')->name('admin.product-inventory.paging-history');
        Route::post('export-excel', 'ProductInventoryController@exportExcelAction')->name('admin.product-inventory.export-excel');
        Route::post('list-product-inventory', 'ProductInventoryController@listProductInventory')
            ->name('admin.product-inventory.listProductInventory');
        Route::post('inventory-config', 'ProductInventoryController@inventoryConfig')->name('admin.product-inventory.config');
        Route::post('save-inventory-config', 'ProductInventoryController@saveInventoryConfig')
            ->name('admin.product-inventory.save-config');

        //Danh sách tồn kho dưới định mức
        Route::get('below-norm', 'ProductInventoryController@belowNormAction')
            ->name('admin.product-inventory.below-norm');
        Route::post('list-below-norm', 'ProductInventoryController@listBelowNormAction')
            ->name('admin.product-inventory.below-norm.list');
    });
    //INVENTORY INPUT
    Route::group(['prefix' => 'inventory-input'], function () {
        Route::get('/', 'InventoryInputController@indexAction')->name('admin.inventory-input');
        //        Route::get('/add', 'InventoryInputController@addAction')->name('admin.inventory-input.add');
        Route::post('/add', 'InventoryInputController@addAction')->name('admin.inventory-input.add');
        Route::post('/show-popup-add-product', 'InventoryInputController@showPopupAddProductAction')->name('admin.inventory-input.show-popup-add-product');
        Route::post('search-product-child', 'InventoryInputController@searchProductAction')->name('admin.inventory-input.search-product-child');
        Route::post('get-product-child-by-id', 'InventoryInputController@getProductChildByIdAction')->name('admin.inventory-input.get-product-child-by-id');
        Route::post('submit-add-inventory-input', 'InventoryInputController@submitAddAction')->name('admin.inventory-input.submit-add');
        Route::post('submit-add-product', 'InventoryInputController@submitAddProductAction')->name('admin.inventory-input.submit-add-product');
        Route::post('get-product-child-by-code', 'InventoryInputController@getProductChildByCode')->name('admin.inventory-input.get-product-child-by-code');
        Route::post('list', 'InventoryInputController@listAction')->name('admin.inventory-input.list');
        Route::post('remove/{id}', 'InventoryInputController@removeAction')->name('admin.inventory-input.remove');
        Route::get('edit/{id}', 'InventoryInputController@editAction')->name('admin.inventory-input.edit');
        Route::post('submit-edit/', 'InventoryInputController@submitEditAction')->name('admin.inventory-input.submit-edit');
        Route::get('detail/{id}', 'InventoryInputController@detailInventoryInputAction')->name('admin.inventory-input.detail');
        Route::post('paging-detail', 'InventoryInputController@pagingDetailAction')->name('admin.inventory-input.paging-detail');
        Route::post('get-product-child-option-page', 'InventoryInputController@getProductChildOptionPage')
            ->name('admin.inventory-input.getProductChildOptionPage');
        Route::post('/delete-product', 'InventoryInputController@deleteProduct')->name('admin.inventory-input.delete-product');
        Route::post('/show-popup-list-serial', 'InventoryInputController@showPopupListSerial')->name('admin.inventory-input.show-popup-list-serial');
        Route::post('/get-list-serial', 'InventoryInputController@getListSerial')->name('admin.inventory-input.get-list-serial');
        Route::post('/submit-edit-product', 'InventoryInputController@submitEditProduct')->name('admin.inventory-input.submit-edit-product');
        Route::post('/remove-serial', 'InventoryInputController@removeSerial')->name('admin.inventory-input.remove-serial');
        Route::post('/get-list-product-input', 'InventoryInputController@getListProductInput')->name('admin.inventory-input.get-list-product-input');
        Route::post('/add-serial-product', 'InventoryInputController@addSerialProduct')->name('admin.inventory-input.add-serial-product');
        Route::post('/get-list-serial-detail', 'InventoryInputController@getListSerialDetail')->name('admin.inventory-input.get-list-serial-detail');
    });
    //INVENTORY OUTPUT
    Route::group(['prefix' => 'inventory-output'], function () {
        Route::get('/', 'InventoryOutputController@indexAction')->name('admin.inventory-output');
        //        Route::get('/add', 'InventoryOutputController@addAction')->name('admin.inventory-output.add');
        Route::post('/add', 'InventoryOutputController@addAction')->name('admin.inventory-output.add');
        Route::post('search-product-child', 'InventoryOutputController@searchProductAction')->name('admin.inventory-output.search-product-child');
        Route::post('get-product-child-by-id', 'InventoryOutputController@getProductChildByIdAction')->name('admin.inventory-output.get-product-child-by-id');
        Route::post('get-product-child-by-code', 'InventoryOutputController@getProductChildByCodeAction')->name('admin.inventory-output.get-product-child-by-code');
        Route::post('submit-add', 'InventoryOutputController@submitAddAction')->name('admin.inventory-output.submit-add');
        Route::post('check-quantity-product-inventory', 'InventoryOutputController@checkQuantityProductInventory')->name('admin.inventory-output.check-quantity-product-inventory');
        Route::post('list', 'InventoryOutputController@listAction')->name('admin.inventory-output.list');
        Route::post('remove/{id}', 'InventoryOutputController@removeAction')->name('admin.inventory-output.remove');
        Route::get('detail/{id}', 'InventoryOutputController@detailInventoryOutAction')->name('admin.inventory-output.detail');
        Route::get('edit/{id}', 'InventoryOutputController@editAction')->name('admin.inventory-output.edit');
        Route::post('submit-edit/', 'InventoryOutputController@submitEditAction')->name('admin.inventory-output.submit-edit');
        Route::post('get-product-child-by-warehouse/', 'InventoryOutputController@getProductChildInventoryByWarehouse')
            ->name('admin.inventory-output.get-product-child-by-warehouse');
        Route::post('paging-detail', 'InventoryOutputController@pagingDetailAction')->name('admin.inventory-output.paging-detail');
        Route::post('get-product-child-inventory-output-option-page', 'InventoryOutputController@getProductChildInventoryOutputOptionPage')
            ->name('admin.inventory-output.getProductChildInventoryOutputOptionPage');
        Route::post('/show-popup-list-serial', 'InventoryOutputController@showPopupListSerial')->name('admin.inventory-output.show-popup-list-serial');
        Route::post('/get-list-serial', 'InventoryOutputController@getListSerial')->name('admin.inventory-output.get-list-serial');
        Route::post('/show-popup-add-product', 'InventoryOutputController@showPopupAddProductAction')->name('admin.inventory-output.show-popup-add-product');
        Route::post('/submit-add-product', 'InventoryOutputController@submitAddProductAction')->name('admin.inventory-output.submit-add-product');
        Route::post('/get-list-product-input', 'InventoryOutputController@getListProductInput')->name('admin.inventory-output.get-list-product-input');
        Route::post('/submit-edit-product', 'InventoryOutputController@submitEditProduct')->name('admin.inventory-output.submit-edit-product');
        Route::post('/add-serial-product', 'InventoryOutputController@addSerialProduct')->name('admin.inventory-output.add-serial-product');
        Route::post('/get-list-serial-detail', 'InventoryOutputController@getListSerialDetail')->name('admin.inventory-output.get-list-serial-detail');
        Route::post('/remove-serial', 'InventoryOutputController@removeSerial')->name('admin.inventory-output.remove-serial');
        Route::post('/delete-product', 'InventoryOutputController@deleteProduct')->name('admin.inventory-output.delete-product');
        Route::post('/getProductChildSerialOptionPage', 'InventoryOutputController@getProductChildSerialOptionPage')->name('admin.inventory-output.getProductChildSerialOptionPage');
        Route::post('/remove-all-product', 'InventoryOutputController@removeAllProduct')->name('admin.inventory-output.remove-all-product');
    });
    //CUSTOMER APPOINTMENT
    Route::group(['prefix' => 'customer-appointment'], function () {
        Route::get('/', 'CustomerAppointmentController@indexAction')->name('admin.customer_appointment');
        Route::post('list-calendar', 'CustomerAppointmentController@listCalendarAction')->name('admin.customer_appointment.list-calendar');
        Route::get('add', 'CustomerAppointmentController@addAction')->name('admin.customer_appointment.add');
        Route::post('search', 'CustomerAppointmentController@searchReferAction')->name('admin.customer_appointment.search');
        Route::post('search-service', 'CustomerAppointmentController@searchServiceAction')->name('admin.customer_appointment.search-service');
        Route::post('load-time', 'CustomerAppointmentController@loadTimeAction')->name('admin.customer_appointment.load-time');
        Route::post('load-customer', 'CustomerAppointmentController@loadCustomerAction')->name('admin.customer_appointment.load-customer');
        Route::post('add-refer', 'CustomerAppointmentController@submitAddCustomerRefer')->name('admin.customer_appointment.add-refer');
        Route::match(array('get', 'post'), 'list-day', 'CustomerAppointmentController@listDayAction')->name('admin.customer_appointment.list-day');
        Route::post('event-Calendar', 'CustomerAppointmentController@listEventCalendarAction')->name('admin.customer_appointment.calendar');
        Route::post('detail', 'CustomerAppointmentController@getItemDetailAction')->name('admin.customer_appointment.detail');
        Route::post('detail-click', 'CustomerAppointmentController@detailAction')->name('admin.customer_appointment.detail-click');
        Route::post('search-time', 'CustomerAppointmentController@searchTimeAction')->name('admin.customer_appointment.search-time');
        Route::post('search-name', 'CustomerAppointmentController@searchNameAction')->name('admin.customer_appointment.search-name');
        Route::post('submit-confirm', 'CustomerAppointmentController@submitConfirmAction')->name('admin.customer_appointment.submit-comfirm');
        Route::post('submit-status', 'CustomerAppointmentController@editStatusAction')->name('admin.customer_appointment.submitStatus');
        Route::get('edit/{id}', 'CustomerAppointmentController@editAction')->name('admin.customer_appointment.edit');
        Route::post('edit', 'CustomerAppointmentController@submitEditFormAction')->name('admin.customer_appointment.submitEditForm');
        Route::post('search-phone', 'CustomerAppointmentController@searchPhoneAction')->name('admin.customer_appointment.search-phone');
        Route::post('get-cus', 'CustomerAppointmentController@getCustomerByPhoneAction')->name('admin.customer_appointment.cus-phone');
        Route::get('remove-session-customer_id', 'CustomerAppointmentController@removeSessionAction')->name('admin.customer_appointment.remove-session-customer_id');
        Route::post('get-list-history', 'CustomerAppointmentController@getListHistoryAppointment')->name('admin.customer_appointment.list-history');
        Route::post('load-time-day', 'CustomerAppointmentController@loadTimeDayAction')->name('admin.customer_appointment.load-time-day');
        Route::post('load-time-edit', 'CustomerAppointmentController@loadTimeEditAction')->name('admin.customer_appointment.load-time-edit');
        Route::get('receipt/{id}', 'CustomerAppointmentController@receiptAction')->name('admin.customer_appointment.receipt');
        Route::post('submit-receipt', 'CustomerAppointmentController@submitReceiptAction')->name('admin.customer_appointment.submitReceipt');
        Route::post('modal-add', 'CustomerAppointmentController@modalAddAction')->name('admin.customer_appointment.modalAdd');
        Route::post('modal-add-timeline', 'CustomerAppointmentController@modalAddTimeLineAction')->name('admin.customer_appointment.modalAddTimeline');
        Route::post('option', 'CustomerAppointmentController@optionServiceStaffRoomAction')->name('admin.customer_appointment.option');
        Route::post('submit-modal-add', 'CustomerAppointmentController@submitModalAddAction')->name('admin.customer_appointment.submitModalAdd');
        Route::post('submit-modal-edit', 'CustomerAppointmentController@submitEditModalAction')->name('admin.customer_appointment.submitModalEdit');
        Route::get('confirm/{id}', 'CustomerAppointmentController@confirmAction')->name('admin.customer_appointment.confirm');
        Route::get('index-cancel', 'CustomerAppointmentController@indexCancelAction')->name('admin.customer_appointment.index-cancel');
        Route::post('list-cancel', 'CustomerAppointmentController@listCancelAction')->name('admin.customer_appointment.list-cancel');
        Route::get('index-late', 'CustomerAppointmentController@indexLateAction')->name('admin.customer_appointment.index-late');
        Route::post('list-late', 'CustomerAppointmentController@listLateAction')->name('admin.customer_appointment.list-late');
        Route::post('check-number-customer', 'CustomerAppointmentController@checkNumberAppointmentAction')->name('admin.customer_appointment.check-number-appointment');
        Route::post('update-number-appointment', 'CustomerAppointmentController@updateNumberAppointmentAction')
            ->name('admin.customer_appointment.update-number-appointment');
        Route::post('change-number-type', 'CustomerAppointmentController@changeNumberTypeAction')
            ->name('admin.customer_appointment.change-number-type');

        Route::get('detail-booking/{id}', 'CustomerAppointmentController@detailBookingAction')
            ->name('admin.customer_appointment.detail-booking');
        Route::post('get-staff-by-branch', 'CustomerAppointmentController@getStaffByBranch')
            ->name('admin.customer_appointment.get-staff-by-branch');
    });
    Route::group(['prefix' => 'customer-appointment-time'], function () {
        Route::get('/', 'CustomerAppointmentTimeController@indexAction')->name('admin.customer_appointment_time');
        Route::post('list', 'CustomerAppointmentTimeController@listAction')->name('admin.customer_appointment_time.list');
        Route::post('add', 'CustomerAppointmentTimeController@addAction')->name('admin.customer_appointment_time.submitAdd');
    });
    //INVENTORY TRANSFER
    Route::group(['prefix' => 'inventory-transfer'], function () {
        Route::get('/', 'InventoryTransferController@indexAction')->name('admin.inventory-transfer');
        Route::get('/add', 'InventoryTransferController@addAction')->name('admin.inventory-transfer.add');
        Route::post('search-product-child', 'InventoryTransferController@searchProductAction')->name('admin.inventory-transfer.search-product-child');
        Route::post('get-product-child-by-id', 'InventoryTransferController@getProductChildByIdAction')->name('admin.inventory-transfer.get-product-child-by-id');
        Route::post('get-product-child-by-code', 'InventoryTransferController@getProductChildByCodeAction')->name('admin.inventory-transfer.get-product-child-by-code');
        Route::post('submit-add', 'InventoryTransferController@submitAddAction')->name('admin.inventory-transfer.submit-add');
        Route::post('get-warehouse-not-id', 'InventoryTransferController@getWarehouseNotId')->name('admin.inventory-transfer.get-warehouse-not-id');
        Route::post('check-quantity-product-inventory', 'InventoryTransferController@checkQuantityProductInventory')->name('admin.inventory-transfer.check-quantity-product-inventory');
        Route::post('list', 'InventoryTransferController@listAction')->name('admin.inventory-transfer.list');
        Route::post('remove/{id}', 'InventoryTransferController@removeAction')->name('admin.inventory-transfer.remove');
        Route::get('detail/{id}', 'InventoryTransferController@detailTransferController')->name('admin.inventory-transfer.detail');
        Route::get('edit/{id}', 'InventoryTransferController@editAction')->name('admin.inventory-transfer.edit');
        Route::post('submit-edit/', 'InventoryTransferController@submitEditAction')->name('admin.inventory-transfer.submit-edit');
        Route::post('get-product-inventorytransfer-by-warehouse/', 'InventoryTransferController@getProductChildInventoryByWarehouse')->name('admin.inventory-transfer.get-product-by-warehouse');
        Route::post('paging-detail', 'InventoryTransferController@pagingDetailAction')->name('admin.inventory-transfer.paging-detail');
    });
    //INVENTORY CHECKING
    Route::group(['prefix' => 'inventory-checking'], function () {
        Route::get('/', 'InventoryCheckingController@indexAction')->name('admin.inventory-checking');
        //        Route::get('/add', 'InventoryCheckingController@addAction')->name('admin.inventory-checking.add');
        Route::post('/add', 'InventoryCheckingController@addAction')->name('admin.inventory-checking.add');
        Route::post('/show-popup-add-product', 'InventoryCheckingController@showPopupAddProductAction')->name('admin.inventory-checking.show-popup-add-product');
        Route::post('search-product-child', 'InventoryCheckingController@searchProductAction')->name('admin.inventory-checking.search-product-child');
        Route::post('get-product-child-by-id', 'InventoryCheckingController@getProductChildByIdAction')->name('admin.inventory-checking.get-product-child-by-id');
        Route::post('get-product-child-by-code', 'InventoryCheckingController@getProductChildByCodeAction')->name('admin.inventory-checking.get-product-child-by-code');
        Route::post('submit-add', 'InventoryCheckingController@submitAddAction')->name('admin.inventory-checking.submit-add');
        Route::post('submit-add-product', 'InventoryCheckingController@submitAddProductAction')->name('admin.inventory-checking.submit-add-product');
        Route::get('detail/{id}', 'InventoryCheckingController@detail')->name('admin.inventory-checking.detail');
        Route::get('edit/{id}', 'InventoryCheckingController@editAction')->name('admin.inventory-checking.edit');
        Route::post('submit-edit/', 'InventoryCheckingController@submitEditAction')->name('admin.inventory-checking.submit-edit');
        Route::post('list', 'InventoryCheckingController@listAction')->name('admin.inventory-checking.list');
        Route::post('get-productaa', 'InventoryCheckingController@getProductChilByWarehouse')->name('admin.inventory-checking.get-productss');
        Route::post('paging-detail', 'InventoryCheckingController@pagingDetailAction')->name('admin.inventory-checking.paging-detail');
        Route::post('/show-popup-list-serial', 'InventoryCheckingController@showPopupListSerial')->name('admin.inventory-checking.show-popup-list-serial');
        Route::post('/get-list-serial', 'InventoryCheckingController@getListSerial')->name('admin.inventory-checking.get-list-serial');
        Route::get('export-add-inventory-checking-error', 'InventoryCheckingController@exportAddInventoryCheckingError')->name('admin.inventory-checking.export-add-inventory-checking-error');
        Route::post('/submit-edit-product', 'InventoryCheckingController@submitEditProduct')->name('admin.inventory-checking.submit-edit-product');
        Route::post('/get-list-product-input', 'InventoryCheckingController@getListProductInput')->name('admin.inventory-checking.get-list-product-input');
        Route::post('/add-serial-product', 'InventoryCheckingController@addSerialProduct')->name('admin.inventory-checking.add-serial-product');
        Route::post('/remove-serial', 'InventoryCheckingController@removeSerial')->name('admin.inventory-checking.remove-serial');
        Route::get('/export-checking-list', 'InventoryCheckingController@exportCheckingList')->name('admin.inventory-checking.export-checking-list');
        Route::post('/remove-product-inline', 'InventoryCheckingController@removeProductInline')->name('admin.inventory-checking.remove-product-inline');
        Route::post('/show-popup-serial-product', 'InventoryCheckingController@showPopupSerialProduct')->name('admin.inventory-checking.show-popup-serial-product');
        Route::post('/get-list-serial-product', 'InventoryCheckingController@getListSerialProduct')->name('admin.inventory-checking.get-list-serial-product');
        Route::post('/submit-edit-check', 'InventoryCheckingController@submitEditCheck')->name('admin.inventory-checking.submit-edit-check');
        Route::post('/get-list-log', 'InventoryCheckingController@getListLog')->name('admin.inventory-checking.get-list-log');
    });
    //SERVICE BRANCH PRICES
    Route::group(['prefix' => 'service-branch-price'], function () {
        Route::get('/', 'ServiceBranchPriceController@indexAction')->name('admin.service-branch-price');
        Route::post('list', 'ServiceBranchPriceController@listAction')->name('admin.service-branch-price.list');
        Route::get('config', 'ServiceBranchPriceController@configAction')->name('admin.service-branch-price.config');
        Route::post('config', 'ServiceBranchPriceController@listConfigAction')->name('admin.service-branch-price.list-config');
        Route::post('submit-config', 'ServiceBranchPriceController@submitConfigAction')->name('admin.service-branch-price.submit-config');
        Route::get('edit/{id}', 'ServiceBranchPriceController@editAction')->name('admin.service-branch-price.edit');
        Route::post('submit-edit', 'ServiceBranchPriceController@submitEditAction')->name('admin.service-branch-price.submit-edit');
        Route::post('remove/{id}', 'ServiceBranchPriceController@removeAction')->name('admin.service-branch-price.remove');
        Route::post('get-branch', 'ServiceBranchPriceController@getBranchAction')->name('admin.service-branch-price.get-branch');
        Route::post('list-branch-price', 'ServiceBranchPriceController@listBranchAction')->name('admin.service-branch-price.list-branch-price');
        Route::post('filter', 'ServiceBranchPriceController@filterAction')->name('admin.service-branch-price.filter');
        Route::post('paging-filter', 'ServiceBranchPriceController@pagingFilterAction')->name('admin.service-branch-price.paging-filter');
        Route::post('config/branch-price', 'ServiceBranchPriceController@listConfigActionBranchPrice')->name('admin.service-branch-price.list-config-branch-price');
    });
    // PRODUCT BRANCH PRICES
    Route::group(['prefix' => 'product-branch-price'], function () {
        Route::get('/', 'ProductBranchPriceController@indexAction')->name('admin.product-branch-price');
        Route::post('list', 'ProductBranchPriceController@listAction')->name('admin.product-branch-price.list');
        Route::get('edit/{id}', 'ProductBranchPriceController@editAction')->name('admin.product-branch-price.edit');
        Route::post('submit-edit', 'ProductBranchPriceController@submitEditAction')->name('admin.product-branch-price.submit-edit');
        Route::post('list-branch-price', 'ProductBranchPriceController@listBranchAction')->name('admin.product-branch-price.list-branch-price');
        Route::get('config', 'ProductBranchPriceController@configAction')->name('admin.product-branch-price.config');
        Route::post('config', 'ProductBranchPriceController@listConfigAction')->name('admin.product-branch-price.list-config');
        Route::post('submit-config', 'ProductBranchPriceController@submitConfigAction')->name('admin.product-branch-price.submit-config');
        Route::post('list-product-child', 'ProductBranchPriceController@listProductChildAction')->name('admin.product-branch-price.list-product-child');
        Route::post('change-branch', 'ProductBranchPriceController@changBranchAction')->name('admin.product-branch-price.change-branch');
        Route::post('filter', 'ProductBranchPriceController@filterAction')->name('admin.product-branch-price.filter');
        Route::post('paging-filter', 'ProductBranchPriceController@pagingFilterAction')->name('admin.product-branch-price.paging-filter');
    });
    Route::group(['prefix' => 'report-revenue'], function () {
        Route::get('/', 'ReportRevenueController@indexAction')->name('admin.report-revenue');
        Route::post('get-filter-child', 'ReportRevenueController@getFilterChildAction')->name('admin.report-revenue.get-filter-child');
        Route::post('filter', 'ReportRevenueController@filterAction')->name('admin.report-revenue.filter');
        //        Route::get('report-revenue-by-service', 'ReportRevenueByServiceController@indexAction')->name('admin.report-revenue.service');
        //        Route::post('report-revenue-by-service-index', 'ReportRevenueByServiceController@chartIndexAction')->name('admin.report-revenue.service.index');
        //        Route::post('report-revenue-by-service-filter', 'ReportRevenueByServiceController@filterAction')->name('admin.report-revenue.service.filter');
        //        Route::get('report-revenue-by-branch', 'ReportRevenueByBranchController@indexAction')->name('admin.report-revenue.branch');
        //        Route::post('report-revenue-by-branch-index', 'ReportRevenueByBranchController@chartIndexAction')->name('admin.report-revenue.branch.index');
        //        Route::post('report-revenue-by-branch-filter', 'ReportRevenueByBranchController@filterAction')->name('admin.report-revenue.branch.filter');
        //        Route::get('report-revenue-by-staff', 'ReportRevenueByStaffController@indexAction')->name('admin.report-revenue.staff');
        //        Route::post('report-revenue-by-staff-index', 'ReportRevenueByStaffController@chartIndexAction')->name('admin.report-revenue.staff.index');
        //        Route::post('report-revenue-by-staff-filter', 'ReportRevenueByStaffController@filterAction')->name('admin.report-revenue.staff.filter');
        //        Route::get('report-revenue-by-customer', 'ReportRevenueByCustomerController@indexAction')->name('admin.report-revenue.customer');
        //        Route::post('report-revenue-by-customer-index', 'ReportRevenueByCustomerController@chartIndexAction')->name('admin.report-revenue.customer.index');
        //        Route::post('report-revenue-by-customer-filter', 'ReportRevenueByCustomerController@filterAction')->name('admin.report-revenue.customer.filter');
        //        Route::get('report-revenue-by-product', 'ReportRevenueByProductController@indexAction')->name('admin.report-revenue.product');
        //        Route::post('report-revenue-by-product-index', 'ReportRevenueByProductController@chartIndexAction')->name('admin.report-revenue.product.index');
        //        Route::post('report-revenue-by-product-filter', 'ReportRevenueByProductController@filterAction')->name('admin.report-revenue.product.filter');
        //        Route::get('report-revenue-by-service-card', 'ReportRevenueByServiceCardController@indexAction')->name('admin.report-revenue.service-card');
        //        Route::post('report-revenue-by-service-card-index', 'ReportRevenueByServiceCardController@chartIndexAction')->name('admin.report-revenue.service-card.index');
        //        Route::post('report-revenue-by-service-card-filter', 'ReportRevenueByServiceCardController@filterAction')->name('admin.report-revenue.service-card.filter');
    });
    Route::group(['prefix' => 'report-customer-growth'], function () {
        Route::get('/', 'ReportCustomerGrowthController@indexAction')->name('admin.report-customer-growth');
        Route::post('load-report', 'ReportCustomerGrowthController@loadReportAction')->name('admin.report-customer-growth.load-report');
        Route::post('filter-year-branch', 'ReportCustomerGrowthController@filterYearBranch')->name('admin.report-customer-growth.year-branch');
        Route::post('filter-time', 'ReportCustomerGrowthController@filterTimeToTime')->name('admin.report-customer-growth.time-branch');
    });
    Route::group(['prefix' => 'report-growth'], function () {
        Route::get('report-growth-by-service', 'ReportGrowthByServiceController@indexAction')->name('admin.report-growth.service');
        Route::post('report-growth-by-service-index', 'ReportGrowthByServiceController@chartIndexAction')->name('admin.report-growth.service.index');
        //        Route::post('report-growth-by-service-filter', 'ReportGrowthByServiceController@filterAction')->name('admin.report-growth.service.filter');
        //        Route::get('report-growth-by-customer', 'ReportGrowthByCustomerController@indexAction')->name('admin.report-growth.customer');
        //        Route::post('report-growth-by-customer-index', 'ReportGrowthByCustomerController@chartIndexAction')->name('admin.report-growth.customer.index');
        //        Route::post('report-growth-by-customer-filter', 'ReportGrowthByCustomerController@filterAction')->name('admin.report-growth.customer.filter');
        Route::get('report-growth-by-product', 'ReportGrowthByProductController@indexAction')->name('admin.report-growth.product');
        Route::post('report-growth-by-product-index', 'ReportGrowthByProductController@chartIndexAction')->name('admin.report-growth.product.index');
        Route::post('report-growth-by-product-filter', 'ReportGrowthByProductController@filterAction')->name('admin.report-growth.product.filter');
        //        Route::get('report-growth-by-service-card', 'ReportGrowthByServiceCardController@indexAction')->name('admin.report-growth.service-card');
        //        Route::post('report-growth-by-service-card-index', 'ReportGrowthByServiceCardController@chartIndexAction')->name('admin.report-growth.service-card.index');
        //        Route::post('report-growth-by-service-card-filter', 'ReportGrowthByServiceCardController@filterAction')->name('admin.report-growth.service-card.filter');
    });
    //    Route::group(['prefix' => 'report-customer-appointment'], function () {
    //        Route::get('/', 'ReportCustomerAppointmentController@indexAction')->name('admin.report-customer-appointment');
    //        Route::post('load-index', 'ReportCustomerAppointmentController@loadIndexAction')->name('admin.report-customer-appointment.load-index');
    //        Route::post('filter-branch', 'ReportCustomerAppointmentController@filterBranchAction')->name('admin.report-customer-appointment.filter-branch');
    //        Route::post('filter-time', 'ReportCustomerAppointmentController@filterTimeAction')->name('admin.report-customer-appointment.filter-time');
    //    });
    //    Route::group(['prefix' => 'report-debt-by-branch'], function () {
    //        Route::get('/', 'ReportDebtController@indexAction')->name('admin.report-debt-branch');
    //        Route::post('load-chart', 'ReportDebtController@loadChartBranchAction')
    //            ->name('admin.report-debt-branch.load-chart');
    //    });
    Route::group(['prefix' => 'report-debt-by-customer'], function () {
        Route::get('/', 'ReportDebtCustomerController@indexAction')
            ->name('admin.report-debt-customer');
        Route::post('load-chart', 'ReportDebtCustomerController@loadChartCustomerAction')
            ->name('admin.report-debt-customer.load-chart');
    });
    //    Route::group(['prefix' => 'report-staff-commission'], function () {
    //        Route::get('/', 'ReportStaffCommissionController@indexAction')
    //            ->name('admin.report-staff-commission');
    //        Route::post('load-chart', 'ReportStaffCommissionController@loadChartAction')
    //            ->name('admin.report-staff-commission.load-chart');
    //    });

    Route::group(['prefix' => 'statistical'], function () {
        //        Route::get('statistical-service', 'ReportGrowthByServiceController@indexAction')->name('admin.report-growth.service');
        //        Route::post('statistical-service-index', 'ReportGrowthByServiceController@chartIndexAction')->name('admin.report-growth.service.index');
        //        Route::post('statistical-service-filter', 'ReportGrowthByServiceController@filterAction')->name('admin.report-growth.service.filter');

        Route::get('statistical-product', 'ReportGrowthByProductController@indexAction')->name('admin.report-growth.product');
        Route::post('statistical-product-index', 'ReportGrowthByProductController@chartIndexAction')->name('admin.report-growth.product.index');
        Route::post('statistical-product-filter', 'ReportGrowthByProductController@filterAction')->name('admin.report-growth.product.filter');

        //        Route::get('statistical-service-card', 'ReportGrowthByServiceCardController@indexAction')->name('admin.report-growth.service-card');
        //        Route::post('statistical-service-card-index', 'ReportGrowthByServiceCardController@chartIndexAction')->name('admin.report-growth.service-card.index');
        //        Route::post('statistical-service-card-filter', 'ReportGrowthByServiceCardController@filterAction')->name('admin.report-growth.service-card.filter');

        //        Route::get('statistical-branch', 'ReportGrowthByBranchController@indexAction')->name('admin.report-growth.branch');
        //        Route::post('statistical-branch-index', 'ReportGrowthByBranchController@chartIndexAction')->name('admin.report-growth.branch.index');
        //        Route::post('statistical-branch-filter', 'ReportGrowthByBranchController@filterAction')->name('admin.report-growth.branch.filter');

        Route::get('statistical-staff', 'StatisticalStaffController@indexAction')->name('admin.statistical.staff');
        Route::post('statistical-staff-index', 'StatisticalStaffController@chartIndexAction')->name('admin.statistical.staff.index');
        Route::post('statistical-staff-filter', 'StatisticalStaffController@filterAction')->name('admin.statistical.staff.filter');

        //        Route::get('statistical-order', 'StatisticalOrderController@indexAction')->name('admin.statistical.order');
        //        Route::post('statistical-order-index', 'StatisticalOrderController@chartIndexAction')->name('admin.statistical.order.index');
        //        Route::post('statistical-order-filter', 'StatisticalOrderController@filterAction')->name('admin.statistical.order.filter');
    });
    //SMS MARKETING
    Route::group(['prefix' => 'sms'], function () {
        Route::get('sms-campaign', 'SmsCampaignController@indexAction')->name('admin.sms.sms-campaign');
        Route::get('sms-campaign/add', 'SmsCampaignController@addAction')->name('admin.sms.sms-campaign-add');
        Route::get('sms-campaign/send-sms', 'SmsCampaignController@indexSendSms')->name('admin.sms.send-sms');
        Route::post('list', 'SmsCampaignController@listAction')->name('admin.sms.list');
        Route::post('remove/{id}', 'SmsCampaignController@removeAction')->name('admin.sms.remove');
        Route::post('get-info-sms-campaign', 'SmsCampaignController@getInfoSmsCampaign')->name('admin.sms.get-info-sms-campaign');
        Route::post('search-customer', 'SmsCampaignController@searchCustomerAction')->name('admin.sms.search-customer');
        Route::post('search-customer-group', 'SmsCampaignController@searchCustomerGroupAction')->name('admin.sms.search-customer-group');
        Route::post('search-customer-group-filter', 'CustomerGroupFilterController@searchCustomerGroupFilterOption')->name('admin.sms.search-customer-group-filter');

        //SMS
        Route::get('config-sms', 'SmsController@settingSmsAction')->name('admin.sms.config-sms');
        Route::post('config', 'SmsController@configSmsAction')->name('admin.sms.config');
        Route::post('get-config', 'SmsController@getConfig')->name('admin.sms.get-config');
        Route::post('active-sms-config', 'SmsController@activeSmsConfigAction')->name('admin.sms.active-sms-config');
        Route::post('setting-sms', 'SmsController@submitSettingSms')->name('admin.sms.setting-sms');

        //Campaing
        Route::post('paging', 'SmsCampaignController@pagingAction')->name('admin.campaign.paging');
        Route::post('filter', 'SmsCampaignController@filterAction')->name('admin.campaign.filter');
        Route::post("paging-filter", "SmsCampaignController@pagingFilterAction")->name("admin.campaign.paging-filter");
        Route::post("submit-add", "SmsCampaignController@submitAddAction")->name("admin.campaign.submit-add");
        Route::get("sms-campaign-edit/{id}", "SmsCampaignController@editAction")->name("admin.campaign.edit");
        Route::get("sms-campaign-detail/{id}", "SmsCampaignController@detailAction")->name("admin.campaign.detail");
        Route::post("sms-campaign-submit-edit", "SmsCampaignController@submitEditAction")->name("admin.campaign.submit-edit");
        Route::post("sms-campaign-save-log", "SmsCampaignController@submitSaveLogAction")->name("admin.campaign.sms-campaign-save-log");
        Route::post("remove-log", "SmsCampaignController@removeLogAction")->name("admin.campaign.remove-log");
        Route::get('export-file/{type}', 'SmsCampaignController@exportFileAction')->name('admin.campaign.export.file');
        Route::post('import-file-excel', 'SmsCampaignController@importFileExcelAction')->name('admin.campaign.import-file-excel');
        Route::post("detail/list", "SmsCampaignController@listDetailAction")->name("admin.campaign.detail-list");
        Route::post("detail-paging", "SmsCampaignController@pagingDetailAction")->name("admin.campaign.detail-paging");
        Route::post('check-customer', 'SmsCampaignController@checkCustomer')->name('admin.campaign.check-customer');
        Route::post('delete-session', 'SmsCampaignController@deleteSession')->name('admin.campaign.delete-session');
        Route::post('append', 'SmsCampaignController@appendAction')->name('admin.campaign.append-table');
        Route::post("sms-popup-created-deal", "SmsCampaignController@popupCreateDeal")->name("admin.campaign.sms-popup-created-deal");
        Route::post("sms-popup-edit-deal", "SmsCampaignController@popupEditDeal")->name("admin.campaign.sms-popup-edit-deal");
        Route::post('sms-search-customer-lead', 'SmsCampaignController@searchCustomerLeadAction')->name('admin.campaign.search-customer-lead');
        Route::post('sms-check-customer-lead', 'SmsCampaignController@checkCustomerLead')->name('admin.campaign.check-customer-lead');

        //test
        //        Route::get('send-sms-log', 'SmsController@sendSmsAction')->name('admin.sms.send-sms-test');
        Route::post('send-code-service-card', 'SmsController@sendCodeServiceCard')->name('admin.sms.send-code-service-card');
        Route::post('send-all-code-service-card', 'SmsController@sendAllCodeServiceCard')->name('admin.sms.send-all-code-service-card');
        //        Route::get('sms-fpt','SmsController@sendSmsFptAction')->name('admin.sms.fpt');
    });
    Route::group(['prefix' => 'email'], function () {
        Route::get('/', 'EmailCampaignController@indexAction')->name('admin.email');
        Route::post('list', 'EmailCampaignController@listAction')->name('admin.email.list');
        Route::post('paging', 'EmailCampaignController@pagingAction')->name('admin.email.paging');
        Route::get('add', 'EmailCampaignController@addAction')->name('admin.email.add');
        Route::post('submit-add', 'EmailCampaignController@submitAddAction')->name('admin.email.submitAdd');
        Route::get('edit/{id}', 'EmailCampaignController@editAction')->name('admin.email.edit');
        Route::post('submit-edit', 'EmailCampaignController@submitEditAction')->name('admin.email.submit-edit');
        Route::post('remove/{id}', 'EmailCampaignController@removeAction')->name('admin.email.remove');
        Route::post('search-customer', 'EmailCampaignController@searchCustomerAction')->name('admin.email.search-customer');
        Route::post('search-customer-group', 'EmailCampaignController@searchCustomerGroupAction')->name('admin.email.search-customer-group');
        Route::post('search-customer-lead', 'EmailCampaignController@searchCustomerLeadAction')->name('admin.email.search-customer-lead');
        Route::post('append', 'EmailCampaignController@appendAction')->name('admin.email.append-table');
        Route::post('save-log', 'EmailCampaignController@saveLogAction')->name('admin.email.save-log');
        Route::get('detail/{id}', 'EmailCampaignController@detailAction')->name('admin.email.detail');
        Route::post('detail/{id}', 'EmailCampaignController@listDetailAction')->name('admin.email.list-detail');
        Route::post('send-mail', 'EmailCampaignController@sendMailAction')->name('admin.email.send-mail');
        Route::post('filter', 'EmailCampaignController@filterAction')->name('admin.email.filter');
        Route::post('filter-paging', 'EmailCampaignController@pagingFilterAction')->name('admin.email.paging-filter');
        Route::get('export-excel', 'EmailCampaignController@exportExcelAction')->name('admin.email.export-excel');
        Route::post('import-excel', 'EmailCampaignController@importExcelAction')->name('admin.email.import-excel');
        Route::post('cancel/{id}', 'EmailCampaignController@cancelAction')->name('admin.email.cancel');
        Route::post('check-customer', 'EmailCampaignController@checkCustomer')->name('admin.email.check-customer');
        Route::post('check-customer-lead', 'EmailCampaignController@checkCustomerLead')->name('admin.email.check-customer-lead');
        Route::post('delete-session', 'EmailCampaignController@deleteSession')->name('admin.email.delete-session');
        Route::post("remove-log", "EmailCampaignController@removeLogAction")->name("admin.email.remove-log");
        Route::post("email-popup-created-deal", "EmailCampaignController@popupCreateDeal")->name("admin.email.email-popup-created-deal");
        Route::post("email-popup-edit-deal", "EmailCampaignController@popupEditDeal")->name("admin.email.email-popup-edit-deal");
    });
    Route::group(['prefix' => 'email-auto'], function () {
        Route::get('/', 'EmailAutoController@indexAction')->name('admin.email-auto');
        Route::post('list', 'EmailAutoController@listAction')->name('admin.email-auto.list');
        Route::post('get-config', 'EmailAutoController@getConfigAction')->name('admin.email-auto.config');
        Route::post('submit-config', 'EmailAutoController@submitConfigAction')->name('admin.email-auto.submit-config');
        Route::post('change-status', 'EmailAutoController@changeStatusAction')->name('admin.email-auto.change-status');
        Route::post('get-setting', 'EmailAutoController@getSettingContentAction')->name('admin.email-auto.setting-content');
        Route::post('submit-setting', 'EmailAutoController@submitSettingContentAction')->name('admin.email-auto.submit-setting-content');
        Route::get('run', 'EmailAutoController@runAutoAction')->name('admin.email-auto.run');
        Route::post('email-template', 'EmailAutoController@emailTemplateAction')->name('admin.email-auto.email-template');
        Route::post('submit-template', 'EmailAutoController@submitEmailTemplateAction')->name('admin.email-auto.submit-template');
        //        Route::get('send-email-job', 'EmailAutoController@sendEmailJobAction')->name('admin.email-auto.send-email-job');
    });
    Route::group(['prefix' => 'config'], function () {
        Route::get('/page-appointment', 'ConfigController@indexAction')->name('admin.config.page-appointment');
        Route::post('/introduction', 'ConfigController@updateIntroduction')->name('admin.config.update.introduction');
        Route::post('list-info', 'ConfigController@listInfoAction')->name('admin.config-page-appointment.list-info');
        Route::get('add-info', 'ConfigController@addInfoAction')->name('admin.config-page-appointment.add-info');
        Route::post('upload', 'ConfigController@uploadAction')->name('admin.config-page-appointment.upload');
        Route::post('submit-add-info', 'ConfigController@submitAddInfoAction')->name('admin.config-page-appointment.submit-add-info');
        Route::get('edit-info/{id}', 'ConfigController@editInfoAction')->name('admin.config-page-appointment.edit-info');
        Route::post('submit-edit-info', 'ConfigController@submitEditInfoAction')->name('admin.config-page-appointment.submit-edit-info');
        Route::post('change-status', 'ConfigController@changeStatusAction')->name('admin.config-page-appointment.change-status');
        Route::post('remove/{id}', 'ConfigController@removeInfoAction')->name('admin.config-page-appointment.remove');
        Route::post('list-banner', 'ConfigController@listBannerAction')->name('admin.config-page-appointment.list-banner');
        Route::post('submit-add-banner', 'ConfigController@submitAddBannerAction')->name('admin.config-page-appointment.submit-add-banner');
        Route::post('edit-banner', 'ConfigController@editBannerAction')->name('admin.config-page-appointment.edit-banner');
        Route::post('submit-edit-banner', 'ConfigController@submitEditBannerAction')->name('admin.config-page-appointment.submit-edit-banner');
        Route::post('remove-banner/{id}', 'ConfigController@removeBannerAction')->name('admin.config-page-appointment.remove-banner');
        Route::post('list-time-working', 'ConfigController@listTimeWorkingAction')->name('admin.config-page-appointment.list-time');
        Route::post('change-status-time', 'ConfigController@changeStatusTimeAction')->name('admin.config-page-appointment.change-status-time');
        Route::post('submit-edit-time', 'ConfigController@submitEditTimeAction')->name('admin.config-page-appointment.submit-edit-time');
        Route::post('list-menu', 'ConfigController@listRuleMenuAction')->name('admin.config-page-appointment.list-rule-menu');
        Route::post('change-status-menu', 'ConfigController@changeStatusMenuAction')->name('admin.config-page-appointment.change-status-menu');
        Route::post('submit-edit-rule-menu', 'ConfigController@submitEditRuleMenuAction')->name('admin.config-page-appointment.submit-edit-rule-menu');
        Route::post('list-booking', 'ConfigController@listRuleBookingAction')->name('admin.config-page-appointment.list-rule-booking');
        Route::post('change-status-booking', 'ConfigController@changeStatusBookingAction')->name('admin.config-page-appointment.change-status-booking');
        Route::post('list-setting-other', 'ConfigController@listRuleSettingOtherAction')->name('admin.config-page-appointment.list-setting-other');
        Route::post('change-status-setting-other', 'ConfigController@changeStatusSettingOtherAction')->name('admin.config-page-appointment.change-status-setting-other');
        Route::post('submit-edit-day', 'ConfigController@submitEditDayAction')->name('admin.config-page-appointment.submit-edit-day');
        Route::post('list-booking-extra', 'ConfigController@listBookingExtraAction')->name('admin.config-page-appointment.list-booking-extra');
        Route::post('submit-edit-booking-extra', 'ConfigController@submitEditBookingExtraAction')->name('admin.config-page-appointment.submit-edit-booking-extra');
        Route::post('upload-img-fb', 'ConfigController@uploadImgFaceBookAction')->name('admin.config-page-appointment.upload-img-fb');
        Route::post('remove-img-fb', 'ConfigController@removeImageFacbookAction')->name('admin.config-page-appointment.remove-img-fb');
    });
    Route::group(['prefix' => 'bussiness'], function () {
        Route::get('/', 'BussinessController@indexAction')->name('admin.bussiness');
        Route::post('list', 'BussinessController@listAction')->name('admin.bussiness.list');
        Route::post('submit-add', 'BussinessController@submitAddAction')->name('admin.bussiness.submit-add');
        Route::post('edit', 'BussinessController@editAction')->name('admin.bussiness.edit');
        Route::post('submit-edit', 'BussinessController@submitEditAction')->name('admin.bussiness.submit-edit');
        Route::post('change-status', 'BussinessController@changeStatusAction')->name('admin.bussiness.change-status');
        Route::post('remove/{id}', 'BussinessController@removeAction')->name('admin.bussiness.remove');
    });
    Route::group(['prefix' => 'layout'], function () {
        Route::get('search-dashboard', 'LayoutController@searchDashboard')->name('admin.layout.search-dashboard');
        Route::get('search', 'LayoutController@searchIndexAction')->name('admin.layout.search-result');
        Route::post('paging-customer', 'LayoutController@pagingCustomerAction')->name('admin.layout.search.paging-customer');
        Route::post('paging-customer-appointment', 'LayoutController@pagingCustomerAppointmentAction')->name('admin.layout.search.paging-customer-appointment');
        Route::post('paging-order', 'LayoutController@pagingOrderAction')->name('admin.layout.search.paging-order');
        Route::get('detail-search', 'LayoutController@detailSearchAction')->name('admin.layout.search.detail-search');
    });
    Route::group(['prefix' => 'config-print-service-card'], function () {
        Route::get('/', 'ConfigPrintServiceCardController@indexAction')->name('admin.config-print-service-card');
        Route::post('list', 'ConfigPrintServiceCardController@listAction')->name('admin.config-print-service-card.list');
        Route::post('upload-logo', 'ConfigPrintServiceCardController@uploadLogoAction')->name('admin.config-print-service-card.upload-logo');
        Route::post('remove-logo', 'ConfigPrintServiceCardController@removeLogoAction')->name('admin.config-print-service-card.remove-logo');
        Route::post('submit-edit', 'ConfigPrintServiceCardController@submitEditAction')->name('admin.config-print-service-card.submit-edit');
        Route::post('change-status-qr', 'ConfigPrintServiceCardController@changeStatusQrCodeAction')->name('admin.config-print-service-card.change-status-qr');
        Route::post('upload-background', 'ConfigPrintServiceCardController@uploadBackgroundAction')->name('admin.config-print-service-card.upload-background');
        Route::post('remove-background', 'ConfigPrintServiceCardController@removeBackgroundAction')->name('admin.config-print-service-card.remove-background');
        Route::post('view-after', 'ConfigPrintServiceCardController@viewAfterAction')->name('admin.config-print-service-card.view-after');
    });
    Route::group(['prefix' => 'config-email-template'], function () {
        Route::get('/', 'ConfigEmailTemplateController@indexAction')->name('admin.config-email-template');
        Route::post('list', 'ConfigEmailTemplateController@listAction')->name('admin.config-email-template.list');
        Route::post('upload', 'ConfigEmailTemplateController@uploadAction')->name('admin.config-email-template.upload');
        Route::post('remove-image', 'ConfigEmailTemplateController@removeImage')->name('admin.config-email-template.remove-img');
        Route::post('submit-edit', 'ConfigEmailTemplateController@submitEditAction')->name('admin.config-email-template.submit-edit');
        Route::post('change-status-logo', 'ConfigEmailTemplateController@changeStatusLogoAction')->name('admin.config-email-template.change-status-logo');
        Route::post('change-status-website', 'ConfigEmailTemplateController@changeStatusWebsiteAction')->name('admin.config-email-template.change-status-website');
        Route::post('view-after', 'ConfigEmailTemplateController@viewAction')->name('admin.config-email-template.view-after');
    });
    //CONFIG PRINT BILL
    Route::group(['prefix' => 'config-print-bill'], function () {
        Route::get('/', 'ConfigPrintBillController@indexAction')->name('admin.config-print-bill');
        Route::post('edit', 'ConfigPrintBillController@submitEditAction')->name('admin.config-print-bill.submitEdit');

        Route::group(['prefix' => 'device'], function () {
            Route::get('', 'ConfigPrintBillController@getPrinterAction')->name('admin.config-print-bill.printers');
            Route::post('', 'ConfigPrintBillController@listAction')->name('admin.config-print-bill.printers.action');
            Route::post('create', 'ConfigPrintBillController@createPrinterAction')->name('admin.config-print-bill.printers.create');
            Route::post('store', 'ConfigPrintBillController@storePrinterAction')->name('admin.config-print-bill.printers.store');
            Route::post('edit', 'ConfigPrintBillController@editPrinterAction')->name('admin.config-print-bill.printers.edit');
            Route::post('update', 'ConfigPrintBillController@updatePrinterAction')->name('admin.config-print-bill.printers.update');
            Route::post('destroy', 'ConfigPrintBillController@destroyPrinterAction')->name('admin.config-print-bill.printers.destroy');
            Route::post('update-status', 'ConfigPrintBillController@updateStatusPrinterAction')->name('admin.config-print-bill.printers.update-status');
            Route::post('default', 'ConfigPrintBillController@updatePrinterDefaultAction')->name('admin.config-print-bill.printers.default');
        });
    });
    //AUTHORIZATION
    Route::group(['prefix' => 'authorization'], function () {
        Route::get('/', 'AuthorizationController@indexAction')->name('admin.authorization');
        Route::get('edit/{id}', 'AuthorizationController@editAction')->name('admin.authorization.edit');
        Route::post('check-all-role-page', 'AuthorizationController@checkAllRolePage')->name('admin.authorization.check-all-role-page');
        Route::post('check-each-role-page', 'AuthorizationController@checkEachRolePage')->name('admin.authorization.check-each-role-page');
        Route::post('check-all-role-action', 'AuthorizationController@checkAllRoleAction')->name('admin.authorization.check-all-role-action');
        Route::post('check-each-role-action', 'AuthorizationController@checkEachRoleAction')->name('admin.authorization.check-each-role-action');
    });
    //ROLE GROUP
    Route::group(['prefix' => 'role-group'], function () {
        Route::get('/', 'RoleGroupController@indexAction')->name('admin.role-group');
        Route::post('/submit-add', 'RoleGroupController@submitAddAction')->name('admin.role-group.submitadd');
        Route::post('list', 'RoleGroupController@listAction')->name('admin.role-group.list');
        Route::post('change-status', 'RoleGroupController@changeStatusAction')->name('admin.role-group.change-status');
        Route::post('edit', 'RoleGroupController@editAction')->name('admin.role-group.edit');
        Route::post('submit-edit', 'RoleGroupController@submitEditAction')->name('admin.role-group.submit-edit');
    });
    //SERVICE CARD GROUP
    Route::group(['prefix' => 'service-card-group'], function () {
        Route::get('/', 'ServiceCardGroupController@indexAction')->name('admin.service-card-group');
        Route::post('list', 'ServiceCardGroupController@listAction')->name('admin.service-card-group.list');
        Route::post('submit-add', 'ServiceCardGroupController@submitAdd')->name('admin.service-card-group.submit-add');
        Route::post('remove/{id}', 'ServiceCardGroupController@removeAction')->name('admin.service-card-group.remove');
        Route::post('edit', 'ServiceCardGroupController@editAction')->name('admin.service-card-group.edit');
        Route::post('submit-edit', 'ServiceCardGroupController@submitEditAction')->name('admin.service-card-group.submit-edit');
    });

    Route::group(['prefix' => 'receipt'], function () {
        Route::get('/', 'ReceiptController@indexAction')->name('admin.receipt');
        Route::post('list', 'ReceiptController@listAction')->name('admin.receipt.list');
        Route::post('detail', 'ReceiptController@detailAction')->name('admin.receipt.detail');
        Route::post('receipt', 'ReceiptController@receiptAction')->name('admin.receipt.receipt');
        Route::post('submit-receipt', 'ReceiptController@submitReceiptAction')->name('admin.receipt.submit-receipt');
        Route::get('print-bill', 'ReceiptController@printBillAction')->name('admin.receipt.print-bill');
        Route::post('save-print-log', 'ReceiptController@savePrintLogAction')->name('admin.receipt.save-print-bill');
        Route::post('gen-qr-code', 'ReceiptController@genQrCodeAction')->name('admin.receipt.gen-qr-code');
        Route::post('cancle', 'ReceiptController@cancleReceipt')->name('admin.receipt.cancle');
        Route::post('list-customer-dept', 'ReceiptController@listDeptByCustomerAction')->name('admin.receipt.list-customer-dept');
    });

    Route::group(['prefix' => 'customer-group-filter'], function () {
        Route::get('/', 'CustomerGroupFilterController@indexAction')
            ->name('admin.customer-group-filter');
        Route::get('/add-group-define', 'CustomerGroupFilterController@addDefineAction')
            ->name('admin.customer-group-filter.add-group-define');
        Route::get('/export-excel-example', 'CustomerGroupFilterController@exportExcelAction')
            ->name('admin.customer-group-filter.export-excel-example');
        Route::post('/read-excel', 'CustomerGroupFilterController@importExcel')
            ->name('admin.customer-group-filter.read-excel');
        Route::post('/search-where-in-user', 'CustomerGroupFilterController@searchWhereInUser')
            ->name('admin.customer-group-filter.search-where-in-customer');
        Route::post('/search-all-customer', 'CustomerGroupFilterController@searchAllCustomer')
            ->name('admin.customer-group-filter.search-all-customer');
        Route::post('/add-customer-group-define', 'CustomerGroupFilterController@addCustomerGroupDefine')
            ->name('admin.customer-group-filter.add-customer-group-define');
        Route::post('/submit-add-group-define', 'CustomerGroupFilterController@submitAddGroupDefine')
            ->name('admin.customer-group-filter.submit-add-group-define');
        Route::post('list', 'CustomerGroupFilterController@listAction')
            ->name('admin.customer-group-filter.list');
        Route::get('/edit-customer-group-define/{id}', 'CustomerGroupFilterController@editUserDefine')
            ->name('admin.customer-group-filter.edit-user-define');
        Route::get('/delete-group-define/{id}', 'CustomerGroupFilterController@deleteGroupDefine')
            ->name('admin.customer-group-filter.delete-group-define');
        Route::get('/delete-group-auto/{id}', 'CustomerGroupFilterController@deleteGroupAuto')
            ->name('admin.customer-group-filter.delete-group-auto');
        Route::post('/get-customer-by-group-define', 'CustomerGroupFilterController@getCustomerByGroupDefine')
            ->name('admin.customer-group-filter.get-customer-by-group-define');
        Route::post('/update-user-define', 'CustomerGroupFilterController@updateCustomerGroupDefine')
            ->name('admin.customer-group-filter.update-user-define');
        Route::get('/detail-customer-group-define/{id}', 'CustomerGroupFilterController@detailCustomerGroupDefine')
            ->name('admin.customer-group-filter.detail-customer-group-define');
        Route::get('/add-customer-group-auto', 'CustomerGroupFilterController@addAutoAction')
            ->name('admin.customer-group-filter.add-customer-group-auto');
        Route::post('/store-customer-group-auto', 'CustomerGroupFilterController@submitAddAutoAction')
            ->name('admin.customer-group-filter.store-customer-group-auto');
        Route::post('/get-condition', 'CustomerGroupFilterController@getCondition')
            ->name('admin.customer-group-filter.get-condition');
        Route::get('/edit-customer-group-auto/{id}', 'CustomerGroupFilterController@editAutoAction')
            ->name('admin.customer-group-filter.edit-customer-group-auto');
        Route::post('/submit-edit-auto', 'CustomerGroupFilterController@submitEditAutoAction')
            ->name('admin.customer-group-filter.submit-edit-auto');
        Route::post('/get-customer-in-group-auto', 'CustomerGroupFilterController@getCustomerInGroupAuto')
            ->name('admin.customer-group-filter.get-customer-in-group-auto');
        Route::post('/get-customer-in-group-define', 'CustomerGroupFilterController@getCustomerInGroup')
            ->name('admin.customer-group-filter.get-customer-in-group-define');
    });

    Route::group(['prefix' => 'point-reward-rule'], function () {
        Route::get('/', 'PointRewardRuleController@indexAction')
            ->name('admin.point-reward-rule');
        Route::post('/submit', 'PointRewardRuleController@saveAction')
            ->name('admin.point-reward-rule.save');
        Route::post('/update-config', 'PointRewardRuleController@updateConfig')
            ->name('admin.point-reward-rule.update-config');
        Route::post('/update-event', 'PointRewardRuleController@updateEvent')
            ->name('admin.point-reward-rule.update-event');
    });
    Route::group(['prefix' => 'config-time-reset-rank'], function () {
        Route::get('/', 'ConfigTimeResetRankController@indexAction')->name('admin.time-reset-rank');
        Route::post('list', 'ConfigTimeResetRankController@listAction')->name('admin.time-reset-rank.list');
        Route::post('edit', 'ConfigTimeResetRankController@editAction')->name('admin.time-reset-rank.edit');
        Route::post('submit-edit', 'ConfigTimeResetRankController@submitEditAction')
            ->name('admin.time-reset-rank.submit-edit');
    });

    Route::group(['prefix' => 'faq-group'], function () {
        Route::get('/', 'FaqGroupController@index')->name('admin.faq-group.index');
        Route::post('list', 'FaqGroupController@listAction')->name('admin.faq-group.list');
        Route::get('show/{id}', 'FaqGroupController@show')
            ->name('admin.faq-group.show')
            ->where('id', '[0-9]+');
        Route::get('create', 'FaqGroupController@create')->name('admin.faq-group.create');
        Route::post('store', 'FaqGroupController@store')->name('admin.faq-group.store');
        Route::get('edit/{id}', 'FaqGroupController@edit')->name('admin.faq-group.edit')->where('id', '[0-9]+');
        Route::post('update', 'FaqGroupController@update')->name('admin.faq-group.update');
        Route::post('destroy', 'FaqGroupController@destroy')->name('admin.faq-group.destroy');
        Route::post('update-status', 'FaqGroupController@updateStatus')->name('admin.faq-group.update-status');
    });

    Route::group(['prefix' => 'faq'], function () {
        Route::get('/', 'FaqController@index')->name('admin.faq.index');
        Route::post('list', 'FaqController@listAction')->name('admin.faq.list');
        Route::get('show/{id}', 'FaqController@show')
            ->name('admin.faq.show')
            ->where('id', '[0-9]+');
        Route::get('create', 'FaqController@create')->name('admin.faq.create');
        Route::post('store', 'FaqController@store')->name('admin.faq.store');
        Route::get('edit/{id}', 'FaqController@edit')->name('admin.faq.edit')->where('id', '[0-9]+');
        Route::post('update', 'FaqController@update')->name('admin.faq.update');
        Route::post('destroy', 'FaqController@destroy')->name('admin.faq.destroy');
        Route::post('update-status', 'FaqController@updateStatus')->name('admin.faq.update-status');
    });

    //ĐƠN HÀNG TỪ APP
    Route::group(['prefix' => 'order-app'], function () {
        Route::get('/', 'OrderAppController@index')->name('admin.order-app');
        Route::post('list', 'OrderAppController@listAction')->name('admin.order-app.list');
        Route::get('create', 'OrderAppController@create')->name('admin.order-app.create');
        Route::post('store', 'OrderAppController@store')->name('admin.order-app.store');
        Route::post('store-or-update', 'OrderAppController@storeOrUpdateOrderApp')->name('admin.order-app.store-or-update');
        Route::post('store-receipt', 'OrderAppController@storeReceiptAction')->name('admin.order-app.store-receipt');
        Route::get('receipt/{id}', 'OrderAppController@receiptAction')->name('admin.order-app.receipt');
        Route::post('update', 'OrderAppController@update')->name('admin.order-app.update');
        Route::post('receipt', 'OrderAppController@submitReceiptAction')->name('admin.order-app.submit-receipt');
        Route::post('render-card', 'OrderAppController@renderCardAction')->name('admin.order-app.render-card');
        Route::get('detail/{id}', 'OrderAppController@show')->name('admin.order-app.detail');
        Route::post('get-list-contact-customer', 'OrderAppController@getListContactByIdCustomer')
            ->name('admin.order-app.get-list-contact-customer');
        Route::post('show-detail-contact', 'OrderAppController@showDetailContact')->name('admin.order-app.show-detail-contact');
        Route::post('add-contact', 'OrderAppController@addContact')->name('admin.order-app.add-contact');
        Route::post('submit-add-contact', 'OrderAppController@submitAddContact')->name('admin.order-app.submit-add-contact');
        Route::post('submit-edit-contact', 'OrderAppController@submitEditContact')->name('admin.order-app.submit-edit-contact');
        Route::post('submit-delete-contact', 'OrderAppController@submitDeleteContact')->name('admin.order-app.submit-delete-contact');
        Route::post('set-default-contact', 'OrderAppController@setAddressDefault')->name('admin.order-app.set-default-contact');
        Route::post('get-full-contact', 'OrderAppController@getFullAddress')->name('admin.order-app.get-full-address');
        Route::post('contact-list', 'OrderAppController@contactListAction')->name('admin.order-app.contact-list');
        Route::post('get-contact-default', 'OrderAppController@getContactDefault')->name('admin.order-app.get-contact-default');
        Route::post('sync-order', 'OrderAppController@syncOrderAction')->name('admin.order-app.sync-order');
        Route::post('export-list', 'OrderAppController@exportList')->name('admin.order-app.exportList');
    });

    //QUẢN LÝ BÀI VIẾT
    Route::group(['prefix' => 'news'], function () {
        Route::get('/', 'NewController@index')->name('admin.new');
        Route::post('list', 'NewController@listAction')->name('admin.new.list');
        Route::get('create', 'NewController@create')->name('admin.new.create');
        Route::post('store', 'NewController@store')->name('admin.new.store');
        Route::post('upload', 'NewController@uploadAction')->name('admin.new.upload');
        Route::get('edit/{id}', 'NewController@edit')->name('admin.new.edit');
        Route::post('update', 'NewController@update')->name('admin.new.update');
        Route::post('change-status', 'NewController@changeStatusAction')->name('admin.new.change-status');
        Route::post('destroy', 'NewController@destroy')->name('admin.new.destroy');
    });

    //QUẢN LÝ ĐÁNH GIÁ KHÁCH HÀNG
    Route::group(['prefix' => 'rating'], function () {
        Route::get('/', 'RatingController@index')->name('admin.rating');
        Route::post('list', 'RatingController@listAction')->name('admin.rating.list');
        Route::post('change-show', 'RatingController@changeShowAction')->name('admin.rating.change-show');
    });

    Route::group(['prefix' => 'rating-order'], function () {
        Route::get('/', 'RatingOrderController@index')->name('admin.rating-order');
        Route::post('list', 'RatingOrderController@listAction')->name('admin.rating-order.list');
        Route::get('detail/{id}', 'RatingOrderController@show')->name('admin.rating-order.show');

        Route::post('click-view-image', 'RatingOrderController@viewImageAction')->name('admin.rating-order.view-image');
        Route::post('click-view-video', 'RatingOrderController@viewVideoAction')->name('admin.rating-order.view-video');
    });

    //Cấu hình chung (Hot_search , Phân đơn hàng)
    Route::group(['prefix' => 'config'], function () {
        Route::get('/config-general', 'ConfigController@configGeneral')->name('admin.config.config-general');
        Route::get('/detail-config-general/{id}', 'ConfigController@detailConfigGeneral')->name('admin.config.detail-config-general');
        Route::get('/edit-config-general/{id}', 'ConfigController@editConfigGeneral')->name('admin.config.edit-config-general');
        Route::post('/edit-post-config-general', 'ConfigController@editPostConfigGeneral')->name('admin.config.edit-post-config-general');
    });

    Route::get('validation', function () {
        return trans('admin::validation');
    })->name('admin.validation');
    Route::group(['prefix' => 'commercial-product-configuration'], function () {
        Route::get('/', 'ProductChildController@index')->name('admin.product-child');
        Route::post('list-tab', 'ProductChildController@listTab')->name('admin.product-child.list-tab');
        Route::post('get-option-add-tab', 'ProductChildController@getOptionAddTab')
            ->name('admin.product-child.get-option-add-tab');
        Route::post('selected-product-child', 'ProductChildController@selectedProductChild')
            ->name('admin.product-child.selected-product-child');
        Route::post('submit-add-product-child', 'ProductChildController@submitAddProductChild')
            ->name('admin.product-child.submit-add-product-child');
        Route::post('tab-current', 'ProductChildController@tabCurrent')
            ->name('admin.product-child.tab-current');
        Route::post('remove-list', 'ProductChildController@removeList')
            ->name('admin.product-child.remove-list');
        Route::post('add-condition-suggest', 'ProductChildController@addConditionSuggest')
            ->name('admin.product-child.add-condition-suggest');
        Route::post('insert-condition-suggest', 'ProductChildController@insertConditionSuggest')
            ->name('admin.product-child.insert-condition-suggest');
    });

    Route::group(['prefix' => 'log'], function () {
        Route::get('/question-customer', 'LogController@questionCustomer')->name('admin.log.question-customer');
        Route::get('/question-customer/detail/{id}', 'LogController@questionDetailCustomer')->name('admin.log.question-customer.detail');
        Route::post('/list-log-customer', 'LogController@listLogCustomerAction')->name('admin.log.question-customer.list');
        Route::post('/popup-answer-question', 'LogController@popupAnswer')->name('admin.log.question-customer.popup-answer-question');
        Route::post('/save-answer', 'LogController@saveAnswer')->name('admin.log.question-customer.save-answer');
        Route::post('/remove-answer', 'LogController@removeAnswer')->name('admin.log.question-customer.remove-answer');
        Route::post('/popup-edit-answer', 'LogController@popupEditAnswer')->name('admin.log.question-customer.popup-edit-answer');
        Route::post('/update-answer', 'LogController@updateAnswer')->name('admin.log.question-customer.update-answer');
    });

    // PRODUCT CHILD: 05/11/2020: NNM
    Route::group(['prefix' => 'product-child'], function () {
        Route::get('/', 'ProductChildNewController@index')->name('admin.product-child-new');
        Route::post('list', 'ProductChildNewController@listAction')->name('admin.product-child-new.list');
        Route::get('edit/{id}', 'ProductChildNewController@edit')->name('admin.product-child-new.edit');
        Route::post('update', 'ProductChildNewController@update')->name('admin.product-child-new.update');
        Route::post('update-status', 'ProductChildNewController@updateStatus')->name('admin.product-child-new.update-status');
        Route::get('detail/{id}', 'ProductChildNewController@show')->name('admin.product-child-new.detail');
        Route::post('get-list-inventory', 'ProductChildNewController@getListInventory')->name('admin.product-child-new.get-list-inventory');
        Route::post('show-popup-serial', 'ProductChildNewController@showPopupSerial')->name('admin.product-child-new.show-popup-serial');
        Route::post('get-list-serial-popup', 'ProductChildNewController@getListSerialPopup')->name('admin.product-child-new.get-list-serial-popup');
    });

    //Shop bán hoa 31/08/2021
    Route::group(['prefix' => 'product-tag'], function () {
        Route::get('/', 'ProductTagController@index')->name('admin.product-tag');
        Route::post('list', 'ProductTagController@listAction')->name('admin.product-tag.list');
        Route::get('create', 'ProductTagController@create')->name('admin.product-tag.create');
        Route::post('store', 'ProductTagController@store')->name('admin.product-tag.store');
        Route::get('edit/{id}', 'ProductTagController@edit')->name('admin.product-tag.edit');
        Route::post('update', 'ProductTagController@update')->name('admin.product-tag.update');
        Route::post('destroy', 'ProductTagController@destroy')->name('admin.product-tag.destroy');
    });

    // MENU: 23/11/2020: NNM
    Route::group(['prefix' => 'menu-horizontal'], function () {
        Route::get('/', 'MenuHorizontalController@index')->name('admin.menu-horizontal');
        Route::post('list', 'MenuHorizontalController@listAction')->name('admin.menu-horizontal.list');
        Route::post('popup-add', 'MenuHorizontalController@showPopupAdd')->name('admin.menu-horizontal.popup-add');
        Route::post('menu-by-menu-category', 'MenuHorizontalController@getListMenuByMenuCategory')
            ->name('admin.menu-horizontal.menu-by-menu-category');
        Route::post('save-menu-horizontal', 'MenuHorizontalController@saveMenuHorizontal')
            ->name('admin.menu-horizontal.save-menu-horizontal');
        Route::post('update-status', 'MenuHorizontalController@updateStatus')->name('admin.menu-horizontal.update-status');
        Route::post('remove', 'MenuHorizontalController@remove')->name('admin.menu-horizontal.remove');
    });

    Route::group(['prefix' => 'menu-vertical'], function () {
        Route::get('/', 'MenuVerticalController@index')->name('admin.menu-vertical');
        Route::post('list', 'MenuVerticalController@listAction')->name('admin.menu-vertical.list');
        Route::post('popup-add', 'MenuVerticalController@showPopupAdd')->name('admin.menu-vertical.popup-add');
        Route::post('menu-by-menu-category', 'MenuVerticalController@getListMenuByMenuCategory')
            ->name('admin.menu-vertical.menu-by-menu-category');
        Route::post('save-menu-vertical', 'MenuVerticalController@saveMenuVertical')
            ->name('admin.menu-vertical.save-menu-vertical');
        Route::post('update-status', 'MenuVerticalController@updateStatus')->name('admin.menu-vertical.update-status');
        Route::post('remove', 'MenuVerticalController@remove')->name('admin.menu-vertical.remove');
    });

    Route::group(['prefix' => 'menu-all'], function () {
        Route::get('/', 'MenuAllController@index')->name('admin.menu-all');
        Route::post('search', 'MenuAllController@searchAction')->name('admin.menu-all.search');
    });

    Route::post('upload-image', 'UploadController@uploadImageAction')->name('admin.upload-image')->middleware('s3');
    // Staff commission
    Route::group(['prefix' => 'staff-commission'], function () {
        Route::get('/', 'StaffCommissionController@index')->name('admin.staff-commission');
        Route::post('list', 'StaffCommissionController@listAction')->name('admin.staff-commission.list');
        Route::post('add', 'StaffCommissionController@create')->name('admin.staff-commission.create');
        Route::post('store', 'StaffCommissionController@store')->name('admin.staff-commission.store');
        Route::post('edit', 'StaffCommissionController@edit')->name('admin.staff-commission.edit');
        Route::post('update', 'StaffCommissionController@update')->name('admin.staff-commission.update');
        Route::post('delete', 'StaffCommissionController@delete')->name('admin.staff-commission.delete');
    });

    Route::group(['prefix' => 'product-config'], function () {
        Route::get('/', 'ProductConfigController@index')->name('admin.product-config');
        Route::post('update', 'ProductConfigController@update')->name('admin.product-config.update');
    });
});
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function () {

    Route::group(['prefix' => 'collection'], function () {
        Route::get('list', 'CollectionController@list')->name('admin.collection.list');
        Route::post('ajax-list', 'CollectionController@ajaxList')->name('admin.collection.ajax-list');
        Route::post('ajax-add-modal', 'CollectionController@ajaxAddModal')->name('admin.collection.ajax-add-modal');
        Route::post('ajax-add', 'CollectionController@ajaxAdd')->name('admin.collection.ajax-add');
        Route::post('ajax-edit-modal', 'CollectionController@ajaxEditModal')->name('admin.collection.ajax-edit-modal');
        Route::post('ajax-edit', 'CollectionController@ajaxEdit')->name('admin.collection.ajax-edit');
        Route::post('ajax-delete', 'CollectionController@ajaxDelete')->name('admin.collection.ajax-delete');
    });

    Route::group(['prefix' => 'product-category-parent'], function () {
        Route::get('list', 'ProductCategoryParentController@list')->name('admin.product-category-parent.list');
        Route::post('ajax-list', 'ProductCategoryParentController@ajaxList')->name('admin.product-category-parent.ajax-list');
        Route::post('ajax-add-modal', 'ProductCategoryParentController@ajaxAddModal')->name('admin.product-category-parent.ajax-add-modal');
        Route::post('ajax-add', 'ProductCategoryParentController@ajaxAdd')->name('admin.product-category-parent.ajax-add');
        Route::post('ajax-edit-modal', 'ProductCategoryParentController@ajaxEditModal')->name('admin.product-category-parent.ajax-edit-modal');
        Route::post('ajax-edit', 'ProductCategoryParentController@ajaxEdit')->name('admin.product-category-parent.ajax-edit');
        Route::post('ajax-delete', 'ProductCategoryParentController@ajaxDelete')->name('admin.product-category-parent.ajax-delete');
    });

    Route::group(['prefix' => 'product-favourite'], function () {
        Route::get('list', 'ProductFavouriteController@list')->name('admin.product-favourite.list');
        Route::post('ajax-list', 'ProductFavouriteController@ajaxList')->name('admin.product-favourite.ajax-list');
        Route::post('ajax-detail-modal', 'ProductFavouriteController@ajaxDetailModal')->name('admin.product-favourite.ajax-detail-modal');
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('list', 'CartController@list')->name('admin.cart.list');
        Route::post('ajax-list', 'CartController@ajaxList')->name('admin.cart.ajax-list');
        Route::post('ajax-detail-modal', 'CartController@ajaxDetailModal')->name('admin.cart.ajax-detail-modal');
    });
});
Route::group(['prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function () {
    Route::get('confirm/{id}', 'CustomerAppointmentController@confirmAction')->name('admin.customer_appointment.confirm');
    Route::get('/error/403', function () {
        return view('authorization.not-have-access');
    })->name('authorization.not-have-access');
});
Route::group(['middleware' => ['web'], 'namespace' => 'Modules\Admin\Http\Controllers'], function () {
    Route::get('send-sms-log', 'SmsController@sendSmsAction')->name('admin.sms.send-sms-test');
    Route::get('send-email-job', 'EmailAutoController@sendEmailJobAction')->name('admin.email-auto.send-email-job');
    Route::get('run-log-email', 'EmailAutoController@runAutoAction')->name('admin.email-auto.run');
    Route::get('run-log-sms', 'SmsController@sendSmsNoEvent')->name('sms.send-sms-no-event');
    Route::get('run-reset-rank', 'ResetRankController@resetRankAction')->name('reset-rank');

    //Import file công nợ
    Route::get('import-receipt-manual', 'ReceiptController@importExcelManual')->name('admin.receipt.import-excel-manual');
    //Tải file
    Route::get('download-app', 'DownloadAppController@index')->name('admin.download-app');
    //Export excel data cho Sie
    Route::get('export-data-sie', 'ExportDataController@exportExcelAction');
    //Insert nv vào nhóm quyền admin
    Route::get('insert-role-admin', 'StaffsController@insertRoleAdmin');
});
