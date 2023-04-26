<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'team', 'namespace' => 'Modules\Team\Http\Controllers'], function () {
    Route::group(['prefix' => 'team'], function () {
        Route::get('', 'TeamController@index')->name('team.team');
        Route::post('list', 'TeamController@listAction')->name('team.team.list');
        Route::get('create', 'TeamController@createAction')->name('team.team.create');
        Route::post('change-title', 'TeamController@changeTitleAction')->name('team.team.change-title');
        Route::post('store', 'TeamController@store')->name('team.team.store');
        Route::get('edit/{id}', 'TeamController@editAction')->name('team.team.edit');
        Route::post('update', 'TeamController@update')->name('team.team.update');
        Route::post('destroy', 'TeamController@destroy')->name('team.team.destroy');
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('', 'CompanyController@index')->name('team.company');
        Route::post('list', 'CompanyController@listAction')->name('team.company.list');
        Route::get('create', 'CompanyController@create')->name('team.company.create');
        Route::post('store', 'CompanyController@store')->name('team.company.store');
        Route::get('edit/{id}', 'CompanyController@edit')->name('team.company.edit');
        Route::post('update', 'CompanyController@update')->name('team.company.update');
        Route::post('destroy', 'CompanyController@destroy')->name('team.company.destroy');
        Route::post('change-status', 'CompanyController@changeStatusAction')->name('team.company.change-status');
    });
});
