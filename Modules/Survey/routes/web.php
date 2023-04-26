<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'survey',
    'namespace' => 'Modules\Survey\Http\Controllers'
], function () {
    Route::get('validation', function () {
        return trans('survey::validation');
    })->name('survey.validation');
    // survey route //
    Route::get('/', 'SurveyController@index')
        ->name('survey.index');
    Route::post('/load-all', 'SurveyController@loadAllSurvey')
        ->name('survey.loadAll');
    Route::post('/show-modal-coppy', 'SurveyController@showModalCoppy')
        ->name('survey.show-modal-coppy');
    Route::post('/show-modal-coppy-url', 'SurveyController@showModalCoppyUrl')
        ->name('survey.show-modal-coppy-url');
    Route::post('/coppy', 'SurveyController@coppySurvey')
        ->name('survey.coppy');

    Route::get('/create', 'SurveyController@create')
        ->name('survey.create');
    Route::get('/show/{id}', 'SurveyController@show')
        ->name('survey.show');
    Route::get('/edit/{id}', 'SurveyController@edit')
        ->name('survey.edit');
    Route::post('/store', 'SurveyController@store')
        ->name('survey.store');
    Route::post('/format-close-date', 'SurveyController@formatCloseDate')
        ->name('survey.format-close-date');
    Route::post('/update', 'SurveyController@update')
        ->name('survey.update');
    Route::post('destroy', 'SurveyController@destroy')
        ->name('survey.destroy');
    Route::post('change-status', 'SurveyController@changeStatus')
        ->name('survey.change-status');
    Route::post('get-item', 'SurveyController@getItem')
        ->name('survey.get-item');
    // survey question //
    Route::get('/edit/question/{id}', 'SurveyController@editQuestion')
        ->name('survey.edit-question');
    Route::get('/show/question/{id}', 'SurveyController@showQuestion')
        ->name('survey.show-question');
    Route::post('add-block', 'SurveyController@addBlock')
        ->name('survey.add-block');
    Route::post('load-block', 'SurveyController@loadBlock')
        ->name('survey.load-block');
    Route::post('show-modal-remove-block', 'SurveyController@showModalRemoveBlock')
        ->name('survey.show-modal-remove-block');
    Route::post('on-change-block', 'SurveyController@onChangeBlock')
        ->name('survey.on-change-block');
    Route::post('render-modal-question-type', 'SurveyController@renderModalQuestionType')
        ->name('survey.render-modal-question-type');
    Route::post('add-question', 'SurveyController@addQuestion')
        ->name('survey.add-question');
    Route::post('load-question-in-block', 'SurveyController@loadQuestionInBlock')
        ->name('survey.load-question-in-block');
    Route::post('remove-question', 'SurveyController@removeQuestion')
        ->name('survey.remove-question');
    Route::post('change-question-position', 'SurveyController@changeQuestionPosition')
        ->name('survey.change-question-position');
    Route::post('show-config-question', 'SurveyController@showConfigQuestion')
        ->name('survey.show-config-question');
    Route::post('on-change-question', 'SurveyController@onChangeQuestion')
        ->name('survey.on-change-question');
    Route::post('update-survey-question', 'SurveyController@updateSurveyQuestion')
        ->name('survey.update-survey-question');
    Route::post('show-modal-notification', 'SurveyController@showModalNotification')
        ->name('survey.show-modal-notification');
    Route::post('show-modal-config-point', 'SurveyController@showModalConfigPoint')
        ->name('survey.show-modal-config-point');
    Route::post('update-template', 'SurveyController@updateTemplate')
        ->name('survey.update-template');
    Route::post('update-config-point', 'SurveyController@updateConfigPoint')
        ->name('survey.update-config-point');
    Route::get('option-load-more', 'SurveyController@optionLoadMore')
        ->name('survey.option-load-more');
    Route::get('option-question', 'SurveyController@optionQuestion')
        ->name('survey.option-question');

    Route::post('/template-question', 'SurveyController@templateQuestion')
        ->name('survey.template-question');

    // survey branch //
    Route::get('show/branch/{id}', 'SurveyController@showBranch')
        ->name('survey.show-branch');
    Route::get('edit/branch/{id}', 'SurveyController@editBranch')
        ->name('survey.edit-branch');
    // survey report //
    Route::get('report/{id}', 'SurveyController@report')
        ->name('survey.report');
    // load all report  survey //
    Route::post('report/load-all', 'SurveyController@loadAllReport')
        ->name('survey.loadAllReport');
    Route::get('report-show-detail/{survey_id}', 'SurveyController@showReportDetail')
        ->name('survey.report.show');
    Route::get('report-show-item-detail/{id_answer}', 'SurveyController@showReportItemDetail')
        ->name('survey.report.item.show');
    Route::post('report/load-item-detail', 'SurveyController@loadDetailReport')
        ->name('survey.report.load-item-detail');
    Route::get('export-report', 'SurveyController@exportReport')
        ->name('survey.export-report');
    Route::get('report/overview/{id}', 'SurveyController@overviewReport')
        ->name('survey.report.overview');

    /**
     * Dùng để làm các chức năng của tab branch áp dụng 
     */
    Route::group(['prefix' => 'branch'], function () {
        // load list customer //
        Route::post('/render-popup-branch', 'ApplyBranchController@renderPopupCustomer')
            ->name('survey.branch.apply.render-popup-customer');
        Route::post('/search-customer', 'ApplyBranchController@searchCustomer')
            ->name('survey.branch.apply.search-customer');
        Route::post('checked-item-temp', 'ApplyBranchController@checkedItemTempCustomer')
            ->name('survey.branch.apply.checked-item-temp-customer');
        Route::post('submit-add-item-temp', 'ApplyBranchController@submitAddItemTemp')
            ->name('survey.branch.apply.submit-add-item-temp');
        Route::post('load-item-selected', 'ApplyBranchController@loadItemSelectCustomer')
            ->name('survey.branch.apply.load-item-selected-customer');
        Route::post('remove-item-selected', 'ApplyBranchController@removeItemSelectedCustomer')
            ->name('survey.branch.apply.remove-item-selected-customer');
        // load list customer auto //
        Route::post('/render-popup-branch-customer-auto', 'ApplyBranchController@renderPopupCustomerAuto')
            ->name('survey.branch.apply.render-popup-customer-auto');
        Route::post('/search-customer-auto', 'ApplyBranchController@searchCustomerAuto')
            ->name('survey.branch.apply.search-customer-auto');
        Route::post('submit-add-item-temp-auto', 'ApplyBranchController@submitAddItemTempAuto')
            ->name('survey.branch.apply.submit-add-item-temp-auto');
        // load list staff //
        Route::post('/render-popup-branch-staff', 'ApplyBranchController@renderPopupStaff')
            ->name('survey.branch.apply.render-popup-staff');
        Route::post('/search-staff', 'ApplyBranchController@searchStaff')
            ->name('survey.branch.apply.search-staff');
        Route::post('checked-item-temp-staff', 'ApplyBranchController@checkedItemTempStaff')
            ->name('survey.branch.apply.checked-item-temp-staff');
        Route::post('submit-add-item-temp-staff', 'ApplyBranchController@submitAddItemTempStaff')
            ->name('survey.branch.apply.submit-add-item-temp-staff');
        Route::post('load-item-selected-staff', 'ApplyBranchController@loadItemSelectStaff')
            ->name('survey.branch.apply.load-item-selected-staff');
        Route::post('remove-item-selected-staff', 'ApplyBranchController@removeItemSelectedStaff')
            ->name('survey.branch.apply.remove-item-selected-staff');
        // load list staff auto //
        Route::post('get-condition-staff', 'ApplyBranchController@getConditionStaff')
            ->name('survey.branch.apply.get-condition');
        Route::post('get-condition-staff-seleted', 'ApplyBranchController@getConditionStaffSelected')
            ->name('survey.branch.apply.get-condition-selected');

        Route::post('search-all-outlet-group', 'ApplyBranchController@searchAllBranchGroup')
            ->name('survey.branch.apply.search-all-outlet-group');
        Route::post('show-modal-import', 'ApplyBranchController@showModalImport')
            ->name('survey.branch.apply.show-modal-import');
        Route::post('import-excel', 'ApplyBranchController@importExcel')
            ->name('survey.branch.apply.import-excel');
        Route::post('submit-import-excel', 'ApplyBranchController@submitImportExcel')
            ->name('survey.branch.apply.submit-import-excel');
        Route::post('update', 'ApplyBranchController@update')
            ->name('survey.branch.apply.update');
        Route::post('forget-session-item-selected', 'ApplyBranchController@forgetSessionItemSelected')
            ->name('survey.branch.apply.forget-session-item-selected');
    });
});
