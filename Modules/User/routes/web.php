<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'user', 'namespace' => 'Modules\User\Http\Controllers'], function () {
    Route::get('/', 'IndexController@indexAction')->name('user');
//        Route::get('/', 'DashbroadController@index')->name('dashbroad');
});

Route::group(['middleware' => ['web'], 'namespace' => 'Modules\User\Http\Controllers'], function () {
    Route::get('/login', 'LoginController@indexAction')->name('login');
    Route::post('/login', 'LoginController@postLogin')->name('login');
    Route::match(['get'], '/logout', 'LoginController@logoutAction')->name('logout');
//    Route::get('menu', 'MenuController@menuAction')->name('menu');
    Route::get('validation', function () {
        return trans('user::validation');
    })->name('user.validation');
});

Route::group(['namespace' => 'Modules\User\Http\Controllers'], function () {
    Route::get('/forget-password', 'LoginController@forgetPassword')->name('login.forgetPassword');
    Route::post('/submit-forget-password', 'LoginController@submitForgetPassword')->name('login.submitForgetPassword');
    Route::post('/submit-forget-password', 'LoginController@submitForgetPassword')->name('login.submitForgetPassword');
    Route::get('/reset-password/{token}', 'LoginController@resetPassword')->name('login.resetPassword');
    Route::post('submit-new-password', 'LoginController@submitNewPassword')->name('login.submitNewPassword');
});

