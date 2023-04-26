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
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'timeoffdays',
    
    ], function () {
        
    Route::get('/generate', 'TimeOffDaysController@generationTimeOffDay')->name('timeoffdays.generate');
    Route::get('/', 'TimeOffDaysController@index')->name('timeoffdays.index');
    Route::get('/my-list', 'TimeOffDaysController@mylist')->name('timeoffdays.mylist');
    Route::post('list', 'TimeOffDaysController@listAction')->name('timeoffdays.list');
    Route::post('total', 'TimeOffDaysController@total')->name('timeoffdays.total');    
    
    Route::get('/add', 'TimeOffDaysController@create')->name('timeoffdays.add');
    Route::post('store', 'TimeOffDaysController@store')->name('timeoffdays.store');    
    Route::get('edit/{id}', 'TimeOffDaysController@edit')->name('timeoffdays.edit');
    Route::get('remove/{id}', 'TimeOffDaysController@remove')->name('timeoffdays.remove');    
    Route::get('show/{id}', 'TimeOffDaysController@show')->name('timeoffdays.show');
    Route::post('update', 'TimeOffDaysController@update')->name('timeoffdays.update');


    Route::post('/approve', 'TimeOffDaysController@approve')->name('timeoffdays.approve');
    Route::post('/un-approve', 'TimeOffDaysController@unApprove')->name('timeoffdays.un-approve');
    Route::post('/get-staff-approve', 'TimeOffDaysController@getStaffApprove')->name('timeoffdays.get-staff-approve');
    
    Route::group([
        'prefix' => 'report',
        ], function () {
        
            Route::get('/', 'ReportController@index')->name('timeoffdays.report.index');
            Route::get('/report-by-type-ajax', 'ReportController@reportByTypeAjax')->name('timeoffdays.report.report-by-type-ajax');
            Route::get('/report-by-precious-ajax', 'ReportController@reportByPreciousAjax')->name('timeoffdays.report.report-by-precious-ajax');
            Route::get('/report-by-top-ten-ajax', 'ReportController@reportByTopTenAjax')->name('timeoffdays.report.report-by-top-ten-ajax');
    });


    Route::group([
        'prefix' => 'sf-shifts',
        ], function () {
        
            Route::post('list', 'SFShiftsController@list')->name('timeoffdays.sfshifts.list');
    });

    Route::group([
        'prefix' => 'time-off-type',
        ], function () {
        
            Route::get('/', 'TimeOffTypeController@index')->name('timeofftype.index');
            Route::post('edit/{id}', 'TimeOffTypeController@edit')->name('timeofftype.edit');
            Route::post('update', 'TimeOffTypeController@update')->name('timeofftype.update');
            Route::post('list', 'TimeOffTypeController@listAction')->name('timeofftype.list');
            Route::post('get-list-staff', 'TimeOffTypeController@getListStaff')->name('timeofftype.get-list-staff');
    });

});
