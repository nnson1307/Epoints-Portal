<?php

Route::group(['middleware' => 'web' , 'group-menu' => 'booking' , 'prefix' => 'booking-online', 'namespace' => 'Modules\BookingWeb\Http\Controllers'], function () {

    Route::get('/', 'BookingController@indexAction')->name('booking');
    Route::post('filter-branch', 'BookingController@filterBranchAction')->name('booking.filter-branch');
    Route::post('page-time', 'BookingController@pagingTimeAction')->name('booking.paging-time');
    Route::post('check-branch', 'BookingController@checkBranchAction')->name('booking.check-branch');
    Route::post('paging-service', 'BookingController@pagingServiceAction')->name('booking.paging-service');
    Route::post('filter-service','BookingController@filterServiceAction')->name('booking.filter-service');
    Route::post('view-confirm','BookingController@confirmAction')->name('booking.confirm');
    Route::post('submit-booking','BookingController@submitBookingAction')->name('booking.submit-booking');
    Route::post('spa-info','BookingController@infoAction')->name('booking.spa-info');
    Route::post('name-spa','BookingController@nameSpaAction')->name('booking.name-spa');
    Route::post('banner-slider','BookingController@getListSliderHeader')->name('booking.banner-slider');

    //address
    Route::post('get-district', 'AddressController@getDistrictAction')->name('get-district');

    Route::group(['group-menu' => 'service' , 'prefix' => 'list-service'], function () {
        Route::match(['get', 'post'], '/', 'ServiceController@indexAction')->name('service');
        Route::post('/service-group', 'ServiceController@getServiceGroup')->name('service.getServiceGroup');
        Route::get('/service-detail/{id}', 'ServiceController@getServiceDetail')->name('service.getServiceGroup.detail');
    });

    Route::group([ 'group-menu' => 'product','prefix' => 'list-product'], function () {
        Route::match(['get', 'post'], '/', 'ProductController@indexAction')->name('product');
        Route::post('/product-group', 'ProductController@getProductGroup')->name('product.getProductGroup');
        Route::get('/product-detail/{id}', 'ProductController@getProductDetail')->name('product.getProductGroup.detail');
    });

    Route::group(['group-menu' => 'brand' , 'prefix' => 'list-brand'], function () {
        Route::match(['get', 'post'], '/', 'BrandController@indexAction')->name('brand');
        Route::post('/list-brand', 'BrandController@getListBrandPage')->name('brand.list');
    });

//    Route::group([ 'group-menu' => 'news' , 'prefix' => 'list-news'], function () {
//        Route::match(['get', 'post'], '/', 'NewsController@indexAction')->name('news');
//        Route::post('/list-news', 'NewsController@getListNews')->name('news.list');
//    });

    Route::group([ 'group-menu' => 'introducion' , 'prefix' => 'introduction'], function () {
        Route::match(['get', 'post'], '/', 'IntroductionController@indexAction')->name('introducion');
    });

});

Route::group(['group-menu' => 'booking', 'prefix' => 'privacy-policy', 'namespace' => 'Modules\BookingWeb\Http\Controllers'], function () {
    Route::get('/', 'BookingController@getPrivacyPolicy')->name('privacy-policy');
});
Route::group(['group-menu' => 'booking', 'prefix' => 'terms-use', 'namespace' => 'Modules\BookingWeb\Http\Controllers'], function () {
    Route::get('/', 'BookingController@getTermsUse')->name('terms-use');
});
Route::group(['group-menu' => 'booking', 'prefix' => 'user-guide', 'namespace' => 'Modules\BookingWeb\Http\Controllers'], function () {
    Route::get('/', 'BookingController@getUserGuide')->name('user-guide');
});
