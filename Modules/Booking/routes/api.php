<?php

Route::group(
    ['middleware' => ['api'], 'prefix' => 'booking',
     'namespace'  => 'Modules\Booking\Http\Controllers'], function () {
    Route::get('/', 'BookingController@index')->name('booking.test');
    Route::post('/get-about-us', 'BookingController@getAboutUsAction');
    Route::post('/get-time-work', 'BookingController@getTimeWork');
    Route::post('/get-provice', 'BookingController@getProvinceAction');
    Route::post('/get-district', 'BookingController@getDistrictAction');
    Route::post('/options', 'BookingController@getServiceCategoryAction');
    Route::post('/get-service', 'BookingController@getServiceAction');
    Route::post('/get-service-list', 'BookingController@getListServiceAction');
    Route::post('/get-product-list', 'BookingController@getListProductAction');
    Route::post('/get-all-service', 'BookingController@bookingGetAllService');
    Route::post('/get-service-detail', 'BookingController@getServiceDetailAction');
    Route::post('/get-service-detail-group', 'BookingController@getServiceDetailGroupAction');
    Route::post('/get-product-detail-group', 'BookingController@getProductDetailGroupAction');
    Route::post('/list-brand', 'BookingController@getListBrand');
    Route::post('/get-product', 'BookingController@getProductAction');
    Route::post('/get-product-detail', 'BookingController@getProductDetailAction');
    Route::post('/get-branch', 'BookingController@getBranchAction');
    Route::post('/booking-get-service', 'BookingController@bookingGetService');
    Route::post('/booking-get-technician', 'BookingController@bookingGetTechnicianAction');
    Route::post('/booking-get-rule-setting-other', 'BookingController@bookingGetRuleSettingOther');
    Route::post('/booking-submit', 'BookingController@bookingSubmitAction');
    Route::post('/get-slider-header', 'BookingController@bookingGetSliderHeaderAction');
    Route::post('/introduction', 'BookingController@getIntroduction');

    //Upload avatar
    Route::post('upload-avatar', 'UploadController@uploadAvatarAction')->middleware('s3');
    //Upload image pick up
    Route::post('upload-image-pickup', 'UploadController@uploadImagePickUpAction')->middleware('s3');
    //Upload image drop
    Route::post('upload-image-drop', 'UploadController@uploadImageDropAction')->middleware('s3');
});

Route::group(
    ['middleware' => ['api'], 'prefix' => 'loyalty',
     'namespace'  => 'Modules\Booking\Http\Controllers'], function () {
    Route::post('/score-calculation', 'LoyaltyController@scoreCalculationAction');
    Route::post('/plus-point-event', 'LoyaltyController@plusPointEventAction');
    Route::post('/plus-point-receipt', 'LoyaltyController@plusPointReceiptAction');
    Route::post('/plus-point-receipt-full', 'LoyaltyController@plusPointReceiptFullAction');
});
