<?php
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'staff-salary', 'namespace' => 'Modules\StaffSalary\Http\Controllers'], function () {

    Route::group(['prefix' => 'staff-salary-allowance'], function () {
        Route::get('/', 'StaffSalaryAllowanceController@index')->name('staff-salary-allowance.index');
        Route::post('/list', 'StaffSalaryAllowanceController@listAction')->name('staff-salary-allowance.list');
        Route::post('/add', 'StaffSalaryAllowanceController@addAction')->name('staff-salary-allowance.add');
        Route::post('/edit', 'StaffSalaryAllowanceController@editAction')->name('staff-salary-allowance.edit');
        Route::post('/show-form-allowance-add', 'StaffSalaryAllowanceController@showModalAddAllowanceAction')->name('staff-salary-allowance.modal-add');
        Route::post('/show-form-allowance-edit', 'StaffSalaryAllowanceController@showModalEditAllowanceAction')->name('staff-salary-allowance.modal-edit');
    });

    Route::group(['prefix' => 'holiday'], function () {
        Route::get('/', 'HolidayController@index')->name('holiday.index');
        Route::post('/list', 'HolidayController@listAction')->name('holiday.list');
        Route::post('/show-form-holiday-add', 'HolidayController@showModalAddHolidayAction')->name('holiday.modal-add');
        Route::post('/show-form-holiday-edit', 'HolidayController@showModalEditHolidayAction')->name('holiday.modal-edit');
        Route::post('/add', 'HolidayController@addAction')->name('holiday.add');
        Route::post('/edit', 'HolidayController@editAction')->name('holiday.edit');
        Route::post('/delete', 'HolidayController@deleteAction')->name('holiday.delete');
    });

    Route::group(['prefix' => 'salary-template'], function () {
        Route::post('/show-form-salary-template-add', 'StaffSalaryTemplateController@showModalAddSalaryTemplateAction')->name('salary-template.modal-add');
        Route::post('/show-form-salary-commission-add', 'StaffSalaryTemplateController@showModalAddSalaryCommissionAction')->name('salary-commission.modal-add');
        Route::post('/show-form-salary-bonus-minus-add', 'StaffSalaryTemplateController@showModalAddSalaryBonusMinusAction')->name('salary-bonus-minus.modal-add');
        Route::post('/show-form-salary-allowances-add', 'StaffSalaryTemplateController@showModalAddSalaryAllowancesAction')->name('salary-allowances.modal-add');
        Route::post('/get-salary-row', 'StaffSalaryTemplateController@getRowSalaryAction')->name('salary.get-row');
        Route::post('/get-salary-allowance-row', 'StaffSalaryTemplateController@getRowSalaryAllowanceAction')->name('salary.get-row-allowance');
        Route::post('/get-salary-bonus-minus-row', 'StaffSalaryTemplateController@getRowSalaryBonusMinusAction')->name('salary.get-row-bonus-minus');
        Route::post('/change-staff-salary-type', 'StaffSalaryTemplateController@changeStaffSalaryTypeAction')->name('salary-template.change-staff-salary-type');
        Route::post('/change-staff-salary-template', 'StaffSalaryTemplateController@changeStaffSalaryTemplateAction')->name('salary-template.change-staff-salary-template');
    });

    Route::group(['prefix' => 'salary'], function () {
        Route::get('/', 'StaffSalaryController@index')->name('staff-salary.index');
        Route::get('/detail/{id}', 'StaffSalaryController@detail')->name('staff-salary.detail');
        Route::post('/list', 'StaffSalaryController@listAction')->name('staff-salary.list');
        Route::post('/add', 'StaffSalaryController@addAction')->name('staff-salary.add');
        Route::post('/edit', 'StaffSalaryController@editAction')->name('staff-salary.edit');
        Route::post('/detail-update', 'StaffSalaryController@salaryDetailSubmitAction')->name('staff-salary.detail-update');
        Route::post('/detail-close', 'StaffSalaryController@closeSalaryDetailSubmitAction')->name('staff-salary.detail-close');
        Route::post('export', 'StaffSalaryController@exportExcelSubmitAction')->name('staff-salary.export');
        Route::get('/job', 'StaffSalaryController@jobGetSalary')->name('staff-salary.job-salary');
        Route::get('/cron-job', 'StaffSalaryController@cronJobGetSalary')->name('staff-salary.cron-job-salary');
        Route::get('/detail-staff', 'StaffSalaryController@detailStaff')->name('staff-salary.detail-staff');
        //Export phiếu lương
        Route::post('export-detail-staff', 'StaffSalaryController@exportDetailStaff')->name('staff-salary.export-detail-staff');
    });

    Route::group(['prefix' => 'report-budget-branch'], function () {
        Route::get('/', 'ReportBudgetBranchController@index')->name('staff-salary.report-budget-branch');
        Route::post('list', 'ReportBudgetBranchController@listAction')->name('staff-salary.report-budget-branch.list');
        Route::post('list-chart', 'ReportBudgetBranchController@listChartAction')->name('staff-salary.report-budget-branch.list-chart');
        Route::post('/report-list', 'ReportBudgetBranchController@getTabReportListAction')->name('staff-salary.repor-list');
        Route::post('/report-chart', 'ReportBudgetBranchController@getTabReportchartAction')->name('staff-salary.repor-chart');
    });

    Route::group(['prefix' => 'template'], function () {
        Route::get('/', 'TemplateController@index')->name('staff-salary.template');
        Route::post('list', 'TemplateController@listAction')->name('staff-salary.template.list');
        //View thêm mẫu lương
        Route::get('create', 'TemplateController@create')->name('staff-salary.template.create');
        //Popup thêm mẫu lương
        Route::post('popup-create', 'TemplateController@popupCreate')->name('staff-salary.template.popup-create');
        //Ajax thêm mẫu lương
        Route::post('ajax-create', 'TemplateController@ajaxCreate')->name('staff-salary.template.ajax-create');
        //Show pop thêm phụ cấp
        Route::post('pop-create-allowance', 'TemplateController@showPopCreateAllowanceAction')
            ->name('staff-salary.template.pop-create-allowance');
        //Thêm mẫu lương
        Route::post('store', 'TemplateController@store')->name('staff-salary.template.store');
        //View chỉnh sửa mẫu lương
        Route::get('edit/{id}', 'TemplateController@edit')->name('staff-salary.template.edit');
        //Chỉnh sửa mẫu lương
        Route::post('update', 'TemplateController@update')->name('staff-salary.template.update');
        //Thay đổi trạng thái
        Route::post('update-status', 'TemplateController@updateStatusAction')->name('staff-salary.template.update-status');
        //Xoá mẫu lương
        Route::post('destroy', 'TemplateController@destroy')->name('staff-salary.template.destroy');
        //Xoá mẫu lương
        Route::post('/show-modal-template-add', 'TemplateController@showModalTemplate')->name('staff-salary.template.pop-create-modal');
    });
});

Route::group(['middleware' => ['web'], 'namespace' => 'Modules\StaffSalary\Http\Controllers'], function () {
    Route::get('cron-job-get-salary', 'StaffSalaryController@cronJobGetSalary');
});
