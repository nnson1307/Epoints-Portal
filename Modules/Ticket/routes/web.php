<?php

Route::group(['middleware' => ['web', 'auth','account'], 'prefix' => 'ticket', 'namespace' => 'Modules\Ticket\Http\Controllers'], function () {
    Route::get('/', 'TicketController@indexAction')->name('ticket');
    Route::get('detail/{id?}', 'TicketController@detailAction')->name('ticket.detail');
    Route::get('add/{id?}', 'TicketController@addAction')->name('ticket.add');
    Route::get('edit/{id}', 'TicketController@addAction')->name('ticket.edit');
    Route::get('/dashboard', 'TicketController@dashboard')->name('ticket.dashboard');
    Route::get('/ticket-create-by-me', 'TicketController@myTicket')->name('ticket.my_ticket');
    Route::get('/report', 'TicketController@report')->name('ticket.report');
    Route::post('/report-table', 'TicketController@getTableReport')->name('ticket.report-table');
    Route::get('/report-kpi', 'TicketController@reportKPI')->name('ticket.report-kpi');
    Route::post('list', 'TicketController@listAction')->name('ticket.list');
    Route::post('list-my-ticket', 'TicketController@listMyTicket')->name('ticket.list-my-ticket');# danh sách ticket của tôi
    Route::post('list-ticket-created', 'TicketController@listTicketCreated')->name('ticket.list-ticket-created');# danh sách ticket tôi tạo
    Route::post('submit', 'TicketController@submitAction')->name('ticket.submit');
    Route::post('upload-file', 'TicketController@uploadFile')->name('ticket.upload-file')->middleware('s3');
    Route::post('rating', 'TicketController@rating')->name('ticket.rating');
    Route::post('save-config', 'TicketController@saveConfig')->name('ticket.save-config');
    Route::post('get-chart', 'TicketController@getChart')->name('ticket.get-chart');
    Route::post('get-chart-kpi', 'TicketController@getChartKPI')->name('ticket.get-chart-kpi');
    Route::post('get-request-by-issue-group-id', 'TicketController@getRequestOption')->name('ticket.get-request-by-issue-group-id');
    //Load vị trí của ticket
    Route::post('load-location', 'TicketController@loadLocationAction')->name('ticket.load-location');

    //Export excel ticket
    Route::post('export-excel', 'TicketController@exportExcelAction')->name('ticket.export-excel');
    
    Route::group(['prefix' => 'queue'], function () {
        Route::get('/', 'QueueController@indexAction')->name('ticket.queue');
        Route::post('list', 'QueueController@listAction')->name('ticket.queue.list');
        Route::post('add', 'QueueController@addAction')->name('ticket.queue.add');
        Route::post('edit', 'QueueController@editAction')->name('ticket.queue.edit');
        Route::post('edit-submit', 'QueueController@submitEditAction')->name('ticket.queue.submit-edit');
        Route::post('remove/{id}', 'QueueController@removeAction')->name('ticket.queue.remove');
        Route::post('change-status', 'QueueController@changeStatusAction')->name('ticket.queue.change-status');
    });
    Route::group(['prefix' => 'staff'], function () {
        Route::get('/', 'QueueStaffController@indexAction')->name('ticket.queue_staff');
        Route::post('list', 'QueueStaffController@listAction')->name('ticket.queue_staff.list');
        Route::post('add', 'QueueStaffController@addAction')->name('ticket.queue_staff.add');
        Route::post('edit', 'QueueStaffController@editAction')->name('ticket.queue_staff.edit');
        Route::post('edit-submit', 'QueueStaffController@submitEditAction')->name('ticket.queue_staff.submit-edit');
        Route::post('remove/{id}', 'QueueStaffController@removeAction')->name('ticket.queue_staff.remove');
        Route::post('change-status', 'QueueStaffController@changeStatusAction')->name('ticket.queue_staff.change-status');
        Route::post('get-staff-detail', 'QueueStaffController@getDetail')->name('ticket.queue_staff.get-detail-staff');
    });
    Route::group(['prefix' => 'request-group'], function () {
        Route::get('/', 'RequestGroupController@indexAction')->name('ticket.group_request');
        Route::post('list', 'RequestGroupController@listAction')->name('ticket.group_request.list');
        Route::post('add', 'RequestGroupController@addAction')->name('ticket.group_request.add');
        Route::post('edit', 'RequestGroupController@editAction')->name('ticket.group_request.edit');
        Route::post('edit-submit', 'RequestGroupController@submitEditAction')->name('ticket.group_request.submit-edit');
        Route::post('remove/{id}', 'RequestGroupController@removeAction')->name('ticket.group_request.remove');
        Route::post('change-status', 'RequestGroupController@changeStatusAction')->name('ticket.group_request.change-status');
    });
    Route::group(['prefix' => 'request'], function () {
        Route::get('/', 'RequestsController@indexAction')->name('ticket.request');
        Route::post('list', 'RequestsController@listAction')->name('ticket.request.list');
        Route::post('add', 'RequestsController@addAction')->name('ticket.request.add');
        Route::post('edit', 'RequestsController@editAction')->name('ticket.request.edit');
        Route::post('edit-submit', 'RequestsController@submitEditAction')->name('ticket.request.submit-edit');
        Route::post('remove/{id}', 'RequestsController@removeAction')->name('ticket.request.remove');
        Route::post('change-status', 'RequestsController@changeStatusAction')->name('ticket.request.change-status');
    });
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', 'RoleController@indexAction')->name('ticket.role');
        Route::post('list', 'RoleController@listAction')->name('ticket.role.list');
        Route::post('add', 'RoleController@addAction')->name('ticket.role.add');
        Route::post('edit', 'RoleController@editAction')->name('ticket.role.edit');
        Route::post('edit-submit', 'RoleController@submitEditAction')->name('ticket.role.submit-edit');
        Route::post('remove/{id}', 'RoleController@removeAction')->name('ticket.role.remove');
        Route::post('change-status', 'RoleController@changeStatusAction')->name('ticket.role.change-status');
    });
    Route::group(['prefix' => 'alert'], function () {
        Route::get('/', 'AlertController@indexAction')->name('ticket.alert');
        Route::get('/edit', 'AlertController@edit')->name('ticket.alert.edit');
    });
    Route::group(['prefix' => 'material'], function () {
        Route::get('/', 'MaterialController@indexAction')->name('ticket.material');
        Route::post('list', 'MaterialController@listAction')->name('ticket.material.list');
        Route::post('add', 'MaterialController@addAction')->name('ticket.material.add');
        Route::post('edit', 'MaterialController@editAction')->name('ticket.material.edit');
        Route::post('edit-submit', 'MaterialController@submitEditAction')->name('ticket.material.submit-edit');
        Route::post('submit-approved', 'MaterialController@approvedAction')->name('ticket.material.submit-approved');
        Route::post('remove/{id}', 'MaterialController@removeAction')->name('ticket.material.remove');
        Route::post('save-config', 'MaterialController@saveConfig')->name('ticket.material.save-config');
        Route::post('get-material', 'MaterialController@getDetailMaterial')->name('ticket.material.get-item-material');
        Route::post('get-product-by-warehouse', 'MaterialController@getProductInWarehouse')->name('ticket.material.get-product-by-warehouse');
        Route::post('parse-excel', 'MaterialController@parseExcel')->name('ticket.material.parserExcel');
        Route::post('get-ticket-material', 'MaterialController@getListMaterialByTicketId')->name('ticket.material.get-ticket-material');
        Route::post('get-ticket-material-detail', 'MaterialController@getListMaterialDetailByTicketId')->name('ticket.material.get-ticket-material-detail');
    });
    
    Route::group(['prefix' => 'refund'], function () {
        Route::get('/', 'RefundController@indexAction')->name('ticket.refund');
        Route::get('add-refund/{id}', 'RefundController@addView')->name('ticket.refund.add-view');
        Route::get('edit-refund/{id}', 'RefundController@editView')->name('ticket.refund.edit-view');
        Route::get('detail-refund/{id}', 'RefundController@detailView')->name('ticket.refund.detail-view');
        Route::get('approve-refund/{id}', 'RefundController@approveView')->name('ticket.refund.approve-view');
        Route::get('remove/{id}', 'RefundController@removeAction')->name('ticket.refund.remove');
        Route::post('edit-submit/{id}', 'RefundController@submitEditAction')->name('ticket.refund.submit-edit');
        Route::post('/load-queue-by-staff-id', 'RefundController@loadQueueByStaff')->name('ticket.refund.load-queue-by-staff');
        Route::post('upload-file', 'RefundController@uploadFile')->name('ticket.refund.upload-file')->middleware('s3');
        Route::post('load-ticket-refund-detail/{id}', 'RefundController@loadTicketRefundDetail')->name('ticket.refund.load-ticket-refund-detail');
        Route::post('add', 'RefundController@addAction')->name('ticket.refund.add');
        Route::post('submit-approved', 'RefundController@approvedAction')->name('ticket.refund.submit-approved');
        Route::post('save-config', 'RefundController@saveConfig')->name('ticket.refund.save-config');
        Route::post('show-approve-item', 'RefundController@showApproveItem')->name('ticket.refund.show-approve-item');
        Route::post('update-approve-item', 'RefundController@updateApproveItem')->name('ticket.refund.update-approve-item');
    });
    Route::group(['prefix' => 'acceptance'], function () {
        Route::get('/', 'AcceptanceController@indexAction')->name('ticket.acceptance');
        Route::get('add/{ticketid?}', 'AcceptanceController@addAction')->name('ticket.acceptance.add');
        Route::get('edit/{ticketid?}', 'AcceptanceController@editAction')->name('ticket.acceptance.edit');
        Route::get('detail/{ticketid?}', 'AcceptanceController@detailAction')->name('ticket.acceptance.detail');
        Route::post('list', 'AcceptanceController@listAction')->name('ticket.acceptance.list');
//        Route::post('add', 'AcceptanceController@addAction')->name('ticket.acceptance.add');
//        Route::post('edit', 'AcceptanceController@editAction')->name('ticket.acceptance.edit');
        Route::post('edit-submit', 'AcceptanceController@submitEditAction')->name('ticket.acceptance.submit-edit');
        Route::post('save-config', 'AcceptanceController@saveConfig')->name('ticket.acceptance.save-config');
        Route::post('change-ticket', 'AcceptanceController@changeTicket')->name('ticket.acceptance.change-ticket');
        Route::post('show-popup-add-product', 'AcceptanceController@showPopupAddProduct')->name('ticket.acceptance.show-popup-add-product');
        Route::post('add-product-incurred-list', 'AcceptanceController@addProductIncurredList')->name('ticket.acceptance.add-product-incurred-list');
        Route::post('list-product-select', 'AcceptanceController@listProductSelect')->name('ticket.acceptance.list-product-select');
        Route::post('create-acceptance', 'AcceptanceController@createAcceptance')->name('ticket.acceptance.create-acceptance');
        Route::post('edit-acceptance', 'AcceptanceController@editAcceptance')->name('ticket.acceptance.edit-acceptance');
    });

    Route::get('translate', function () {
        return trans('ticket::translate');
    })->name('ticket.translate');
});
