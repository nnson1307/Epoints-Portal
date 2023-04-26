<?php

Route::group(['middleware' => 'web', 'prefix' => 'bookingweb', 'namespace' => 'Modules\BookingWeb\Http\Controllers'], function()
{
    Route::get('/', 'BookingWebController@index');
});
