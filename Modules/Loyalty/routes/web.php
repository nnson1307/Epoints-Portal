<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['web', 'auth'],
        'prefix' => 'loyalty',
        'namespace' => 'Modules\Loyalty\Http\Controllers'
    ],
    function () {
        Route::get('validation', function () {
            return trans('loyalty::validation');
        })->name('loyalty.validation');

        Route::group(['prefix' => 'accumulate-points'], function () {
            Route::get('/', 'AccumulatePointsProgramController@index')
                ->name('loyalty.accumulate-points');
            Route::post('/load-all', 'AccumulatePointsProgramController@loadAll')
                ->name('loyalty.accumulate-points.load-all');
            Route::get('/create', 'AccumulatePointsProgramController@create')
                ->name('loyalty.accumulate-points.create');
            Route::post('/store', 'AccumulatePointsProgramController@store')
                ->name('loyalty.accumulate-points.store');
            Route::get('/show/{id}', 'AccumulatePointsProgramController@show')
                ->name('loyalty.accumulate-points.show');
            Route::get('/edit/{id}', 'AccumulatePointsProgramController@edit')
                ->name('loyalty.accumulate-points.edit');
            Route::post('/update', 'AccumulatePointsProgramController@update')
                ->name('loyalty.accumulate-points.update');
            Route::post('/show-modal-notification', 'AccumulatePointsProgramController@showModalNotification')
                ->name('loyalty.accumulate-points.notification');
            Route::post('/setting-notification', 'AccumulatePointsProgramController@settingNotification')
                ->name('loyalty.accumulate-points.setting-notification');
            Route::post('/show-modal-destroy', 'AccumulatePointsProgramController@showModalDestroy')
                ->name('loyalty.accumulate-points.show-modal-destroy');
            Route::post('/destroy', 'AccumulatePointsProgramController@destroy')
                ->name('loyalty.accumulate-points.destroy');
        });
    }
);
