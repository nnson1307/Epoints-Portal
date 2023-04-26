<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'notification', 'namespace' => 'Modules\Notification\Http\Controllers'], function () {

    Route::group(['prefix' => 'config'], function () {
        Route::get('', 'ConfigController@index')->name('config');
        Route::get('edit/{key}', 'ConfigController@edit')->name('config.edit');
        Route::post('update', 'ConfigController@update')->name('config.update');
        Route::post('change-status', 'ConfigController@changeStatusAction')->name('config.change-status');
        Route::post('upload', 'ConfigController@uploadAction')->name('config.upload');

        Route::post('submit-notify-contract', 'ConfigController@submitNotifyContract')->name('config.submit-notify-contract');
    });

    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', 'NotificationController@index')->name('admin.notification');
        Route::post('list', 'NotificationController@listAction')->name('notification.list');
        Route::post('update-is-actived/{id}', 'NotificationController@updateIsActived')
            ->name('admin.notification.updateIsActived');
        Route::get('create', 'NotificationController@create')->name('admin.notification.create');
        Route::post('store', 'NotificationController@store')->name('admin.notification.store');
        Route::get('edit/{id}', 'NotificationController@edit')->name('admin.notification.edit');
        Route::get('show/{id}', 'NotificationController@detail')->name('admin.notification.detail');
        Route::post('update/{id}', 'NotificationController@update')->name('admin.notification.update');
        Route::post('/upload', 'NotificationController@upload')->name('admin.notification.upload');
        Route::get('/ajax-detail-end-point', 'NotificationController@detailEndPoint')
            ->name('admin.notification.detailEndPoint');
        Route::post('/ajax-list-detail-end-point', 'NotificationController@listDetailEndPoint')
            ->name('admin.notification.listDetailEndPoint');
        Route::post('delete/{id}', 'NotificationController@destroy')->name('admin.notification.destroy');
        Route::get('/ajax-group', 'NotificationController@groupList')
            ->name('admin.notification.groupList');
        Route::post("noti-popup-created-deal", "NotificationController@popupCreateDeal")->name("admin.notification.noti-popup-created-deal");
        Route::post("noti-popup-edit-deal", "NotificationController@popupEditDeal")->name("admin.notification.noti-popup-edit-deal");
    });

    // staff notification
    Route::group(['prefix' => 'staff'], function () {
        Route::post('get-all', 'StaffNotificationController@getAll')->name('staff-notification.get-all');
        Route::post('get-new', 'StaffNotificationController@getNotificationNew')
            ->name('staff-notification.get-new');
        Route::post('update-status', 'StaffNotificationController@updateStatus')
            ->name('staff-notification.update-status');
        Route::post('number-of-noti', 'StaffNotificationController@getNumberOfNotificationNew')
            ->name('staff-notification.number-of-noti');
        Route::post('clear-notify-new', 'StaffNotificationController@clearNotifyNewAction')
            ->name('staff-notification.clear-new');
    });

    // staff notification config
    Route::group(['prefix' => 'config-staff'], function () {
        Route::get('', 'ConfigStaffController@index')->name('config-staff');
        Route::get('edit/{key}', 'ConfigStaffController@edit')->name('config-staff.edit');
        Route::post('update', 'ConfigStaffController@update')->name('config-staff.update');
        Route::post('change-status', 'ConfigStaffController@changeStatusAction')->name('config-staff.change-status');
        Route::post('upload', 'ConfigStaffController@uploadAction')->name('config-staff.upload');
    });
});