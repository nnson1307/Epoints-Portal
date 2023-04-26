<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Modules\Kpi\Models\StaffsTable;

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'kpi'], function () {
    Route::prefix('kpi-criteria')->group(function () {

        /**
         * Trang danh sách tiêu chí hoa hồng
         */
        Route::get('/', 'KpiController@indexAction')->name('kpi.criteria');
        Route::post('/list', 'KpiController@listAction')->name('kpi.criteria.list');
        Route::post('/submit', 'KpiController@submitAction')->name('kpi.criteria.submit');
        Route::post('/update', 'KpiController@updateAction')->name('kpi.criteria.update');
        Route::post('/remove/{id}', 'KpiController@removeAction')->name('kpi.criteria.remove');

        /**
         * Lấy thông tin tiêu chí lead quan tâm
         */
        Route::post('/lead-option', 'KpiController@getLeadAction')->name('kpi.criteria.lead-option');
    });


    /**
     * Trang danh sách phiếu giao
     */
    Route::prefix('kpi-note')->group(function () {
        Route::get('/', 'KpiNoteController@indexAction')->name('kpi.note');
        Route::post('/list', 'KpiNoteController@listAction')->name('kpi.note.list');
        Route::get('/add', 'KpiNoteController@addAction')->name('kpi.note.add');
        Route::post('/submit', 'KpiNoteController@submitAction')->name('kpi.note.submit');
        Route::get('/detail/{id}', 'KpiNoteController@detailAction')->name('kpi.note.detail');
        Route::get('/edit/{id}', 'KpiNoteController@editAction')->name('kpi.note.edit');
        Route::post('/update', 'KpiNoteController@updateAction')->name('kpi.note.update');
        Route::post('/list-current-criteria', 'KpiNoteController@listCurrentCriteriaAction')->name('kpi.note.list-current-criteria');
        Route::post('/remove/{id}', 'KpiNoteController@removeAction')->name('kpi.note.remove');

        Route::post('/get-department', 'KpiNoteController@listDepartmentAction')->name('kpi.note.department');
        Route::post('/get-team', 'KpiNoteController@listTeamAction')->name('kpi.note.team');
        Route::post('/get-staff', 'KpiNoteController@listStaffAction')->name('kpi.note.staff');
        Route::post('/get-criteria', 'KpiNoteController@listCriteriaAction')->name('kpi.note.criteria');

        Route::post('/add-kpi-calculate', 'KpiNoteController@addKpiCalculateAction')->name('kpi.note.calculate');
    });

    /**
     * Trang ngân sách marketing
     */
    Route::prefix('marketing-budget')->group(function () {
        Route::get('/month', 'MarketingBudgetController@indexMonthAction')->name('kpi.marketing.budget.month');
        Route::get('/day', 'MarketingBudgetController@indexDayAction')->name('kpi.marketing.budget.day');
        Route::post('/list-month', 'MarketingBudgetController@listMonthAction')->name('kpi.marketing.budget.month.list');
        Route::post('/list-day', 'MarketingBudgetController@listDayAction')->name('kpi.marketing.budget.day.list');
        Route::post('/submit', 'MarketingBudgetController@submitAction')->name('kpi.marketing.budget.month.submit');
        Route::post('/submit/day', 'MarketingBudgetController@submitDayAction')->name('kpi.marketing.budget.day.submit');
        Route::post('/month/update', 'MarketingBudgetController@updateMonthAction')->name('kpi.marketing.budget.month.update');
        Route::post('/day/update', 'MarketingBudgetController@updateDayAction')->name('kpi.marketing.budget.day.update');
        Route::post('/remove/{id}', 'MarketingBudgetController@removeAction')->name('kpi.marketing.budget.remove');
    });

    Route::prefix('report-kpi')->group(function () {
        Route::get('/', 'ReportController@index')->name('report-kpi');
        Route::post('/changeBranch', 'ReportController@changeBranchAction')->name('report-kpi.change-branch');
        Route::post('/changeDepartment', 'ReportController@changeDepartmentAction')->name('report-kpi.change-department');
        Route::post('load-data', 'ReportController@loadDataAction')->name('report-kpi.load-data');

//        Route::post('/showChartTable', 'ReportController@showChartTable')->name('report-kpi.showChartTable');
//
        Route::get('/budget-efficiency', '_ReportController@budgetEfficiency')->name('report-kpi.budget-efficiency');
        Route::post('/search-month', '_ReportController@searchMonth')->name('report-kpi.search-month');
        Route::post('/search-week', '_ReportController@searchWeek')->name('report-kpi.search-week');
        Route::post('/search-day', '_ReportController@searchDay')->name('report-kpi.search-day');
    });

    Route::prefix('calculate-kpi')->group(function () {
        Route::get('', 'CalculateKpiController@calculate');
    });
});
