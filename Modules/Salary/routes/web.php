<?php
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'salary', 'namespace' => 'Modules\Salary\Http\Controllers'], function () {
    // ,'account'
    Route::get('/', 'SalaryController@indexAction')->name('salary');
    Route::get('/detail/{id}', 'SalaryController@tableSalaryDetail')->name('salary.detail');
    Route::post('/add', 'SalaryController@addAction')->name('salary.add');
    Route::get('test', 'SalaryController@testJob');
    Route::group(['prefix' => 'salary-commission-config'], function () {
        Route::get('/', 'SalaryCommissionConfigController@indexAction')->name('salary.salary_commission_config');
        Route::post('add', 'SalaryCommissionConfigController@addAction')->name('salary.salary_commission_config.add');
        Route::post('edit', 'SalaryCommissionConfigController@editAction')->name('salary.salary_commission_config.edit');
        Route::post('add-view', 'SalaryCommissionConfigController@addView')->name('salary.salary_commission_config.add-view');
        Route::post('edit-submit', 'SalaryCommissionConfigController@submitAction')->name('salary.salary_commission_config.submit-edit');
        Route::post('change-status', 'SalaryCommissionConfigController@changeStatusAction')->name('salary.salary_commission_config.change-status');
    });
//    Link tạm
    Route::get('/export', 'SalaryController@export')->name('salary.export');
    Route::post('/import-excel-salary', 'SalaryController@importExcelSalary')->name('salary.import-excel-salary');
    Route::post('/export-excel-salary', 'SalaryController@exportExcelSalary')->name('salary.export-excel-salary');
    Route::get('/export-excel-salary-commission', 'SalaryController@exportExcelSalaryCommission')->name('salary.export-excel-salary-commission');
    Route::post('/lock-salary', 'SalaryController@lockSalary')->name('salary.lock-salary');
    Route::post('/show-modal-edit-salary', 'SalaryController@showModalEditSalary')->name('salary.show-modal-edit-salary');
    Route::post('/edit-salary', 'SalaryController@editSalary')->name('salary.edit-salary');
    Route::get('/salary-edit/{id}', 'SalaryController@salaryEdit')->name('salary.salary-edit');
    Route::post('/edit-salary-save', 'SalaryController@editSalarySave')->name('salary.edit-salary-save');
    Route::post('/show-table-commission', 'SalaryController@showTableCommission')->name('salary.show-table-commission');
});


