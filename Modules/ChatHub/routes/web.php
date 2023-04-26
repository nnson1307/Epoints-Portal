<?php
Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'chat-hub', 'namespace' => 'Modules\ChatHub\Http\Controllers'], function () {
    Route::group(['prefix' => 'chat'], function () {
        Route::get('/', 'ChatController@indexAction')->name('chathub.chat');
        Route::group(['prefix' => 'new'], function () {
            Route::match(['post','get'],'{path?}', 'ChatController@newAction')->where('path', '[a-zA-Z0-9-/]+')->name('chathub.chat-new');
        });

        Route::post('/notification-count', 'ChatController@getNotificationCount')->name('chathub.chat-notification-count');
    });

    Route::group(['prefix' => 'inbox'], function () {
        Route::get('/', 'InboxController@indexAction')->name('chathub.inbox');
//        Route::post('/notification-count', 'InboxController@getNotificationCount')->name('chathub.chat-notification-count');
    });

    Route::get('/redirect/{social}', 'AuthSocialController@redirect')->name('redirect');
    Route::get('/login/zalo','AuthSocialController@loginZalo')->name('login-zalo');
    Route::get('/callback/{social}', 'AuthSocialController@callback')->name('chathub.callback');
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/', 'SettingController@indexAction')->name('setting');
        Route::post('/add-channel','SettingController@addChannel')->name('setting.add-channel');
        Route::post('/subscribe','SettingController@subscribeChannel')->name('setting.subscribe-channel');
        Route::post('/unsubscribe','SettingController@unsubscribeChannel')->name('setting.unsubscribe-channel');
        Route::post('/show-option', 'SettingController@showOption')->name('setting.show-option');
        Route::post('/popup-edit','SettingController@showPopupEdit')->name('setting.popup-edit');
        Route::post('/save-channel','SettingController@saveChannel')->name('setting.save-channel');

    });
    
    Route::group(['prefix' => 'message'], function () {
        Route::get('/', 'MessageController@indexAction')->name('message');
        Route::post('/edit-form', 'MessageController@editForm')->name('message.get-edit-form');
        Route::post('/get-form-deal', 'MessageController@getFormDeal')->name('message.get-form-deal');
        Route::post('/create-deal','MessageController@createDeal')->name('message.create-deal');
        Route::post('/get-form-lead', 'MessageController@getFormLead')->name('message.get-form-lead');
        Route::post('/check-exist-lead', 'MessageController@checkExistLead')->name('message.check-exist-lead');
        Route::post('/create-or-update-lead', 'MessageController@createOrUpdateLead')->name('message.create-or-update-lead');
        Route::post('/get-message', 'MessageController@getMessage')->name('message.get-message');
        Route::post('/add-customer', 'MessageController@addCustomer')->name('message.add-customer');
        Route::post('/get-token','MessageController@sentMessage')->name('message.sent-message');
        Route::post('/add-message','MessageController@addMessage')->name('message.add-message');
        Route::post('/seen-message','MessageController@seenMessage')->name('message.seen-message');
        Route::post('/choose-channel','MessageController@chooseChannel')->name('channel.choose');
        Route::post('/update-customer','MessageController@updateCustomer')->name('customer.update');
        Route::post('/uploads-image', 'MessageController@uploadsImageAction')->name('message.image-uploads');
        Route::post('/delete-image-temp', 'MessageController@deleteImageAction')->name('message.delete-image-temp');
        Route::post('/uploads-file', 'MessageController@uploadsFileAction')->name('message.file-uploads');
        Route::post('/delete-file-temp', 'MessageController@deleteFileAction')->name('message.delete-file-temp');
    });
    Route::group(['prefix' => 'brand'], function () {
        Route::get('/', 'BrandController@indexAction')->name('chathub.brand');
        Route::post('/list', 'BrandController@listAction')->name('chathub.brand.list');
        Route::get('/add', 'BrandController@addAction')->name('chathub.brand.add');
        Route::get('/edit', 'BrandController@editAction')->name('chathub.brand.edit');
        Route::post('/create', 'BrandController@createAction')->name('chathub.brand.create');
        Route::post('/update', 'BrandController@updateAction')->name('chathub.brand.update');
        Route::post('/delete', 'BrandController@deleteAction')->name('chathub.brand.delete');
    });
    Route::group(['prefix' => 'sku'], function () {
        Route::get('/', 'SkuController@indexAction')->name('chathub.sku');
        Route::post('/list', 'SkuController@listAction')->name('chathub.sku.list');
        Route::get('/add', 'SkuController@addAction')->name('chathub.sku.add');
        Route::get('/edit', 'SkuController@editAction')->name('chathub.sku.edit');
        Route::post('/create', 'SkuController@createAction')->name('chathub.sku.create');
        Route::post('/update', 'SkuController@updateAction')->name('chathub.sku.update');
        Route::post('/delete', 'SkuController@deleteAction')->name('chathub.sku.delete');
    });
    Route::group(['prefix' => 'sub-brand'], function () {
        Route::get('/', 'SubBrandController@indexAction')->name('chathub.sub_brand');
        Route::get('/add', 'SubBrandController@addAction')->name('chathub.sub_brand.add');
        Route::get('/edit', 'SubBrandController@editAction')->name('chathub.sub_brand.edit');
        Route::post('/create', 'SubBrandController@createAction')->name('chathub.sub_brand.create');
        Route::post('/update', 'SubBrandController@updateAction')->name('chathub.sub_brand.update');
        Route::post('/delete', 'SubBrandController@deleteAction')->name('chathub.sub_brand.delete');
        Route::post('/list', 'SubBrandController@listAction')->name('chathub.sub_brand.list');
    });
    Route::group(['prefix' => 'attribute'], function () {
        Route::get('/', 'AttributeController@indexAction')->name('chathub.attribute');
        Route::get('/add', 'AttributeController@addAction')->name('chathub.attribute.add');
        Route::get('/edit', 'AttributeController@editAction')->name('chathub.attribute.edit');
        Route::post('/create', 'AttributeController@createAction')->name('chathub.attribute.create');
        Route::post('/update', 'AttributeController@updateAction')->name('chathub.attribute.update');
        Route::post('/delete', 'AttributeController@deleteAction')->name('chathub.attribute.delete');
        Route::post('/list', 'AttributeController@listAction')->name('chathub.attribute.list');
    });

//    Route::group(['prefix' => 'response-content'], function () {
//        Route::get('/', 'ResponseDetailController@indexAction')->name('chathub.response_detail');
//        Route::get('/add', 'ResponseDetailController@addAction')->name('chathub.response_detail.add');
//        Route::get('/edit', 'ResponseDetailController@editAction')->name('chathub.response_detail.edit');
//        Route::post('/create', 'ResponseDetailController@createAction')->name('chathub.response_detail.create');
//        Route::post('/update', 'ResponseDetailController@updateAction')->name('chathub.response_detail.update');
//        Route::post('/delete', 'ResponseDetailController@deleteAction')->name('chathub.response_detail.delete');
//        Route::post('/list', 'ResponseDetailController@listAction')->name('chathub.response_detail.list');
//    });

    Route::group(['prefix' => 'response-button'], function () {
        Route::get('/', 'ResponseButtonController@indexAction')->name('chathub.response_button');
        Route::get('/add', 'ResponseButtonController@addAction')->name('chathub.response_button.add');
        Route::get('/edit', 'ResponseButtonController@editAction')->name('chathub.response_button.edit');
        Route::post('/create', 'ResponseButtonController@createAction')->name('chathub.response_button.create');
        Route::post('/update', 'ResponseButtonController@updateAction')->name('chathub.response_button.update');
        Route::post('/delete', 'ResponseButtonController@deleteAction')->name('chathub.response_button.delete');
        Route::post('/list', 'ResponseButtonController@listAction')->name('chathub.response_button.list');
    });
    Route::group(['prefix' => 'response-element'], function () {
        Route::get('/', 'ResponseElementController@indexAction')->name('chathub.response_element');
        Route::get('/add', 'ResponseElementController@addAction')->name('chathub.response_element.add');
        Route::get('/edit', 'ResponseElementController@editAction')->name('chathub.response_element.edit');
        Route::post('/create', 'ResponseElementController@createAction')->name('chathub.response_element.create');
        Route::post('/update', 'ResponseElementController@updateAction')->name('chathub.response_element.update');
        Route::post('/delete', 'ResponseElementController@deleteAction')->name('chathub.response_element.delete');
        Route::post('/list', 'ResponseElementController@listAction')->name('chathub.response_element.list');
        Route::post('/upload-image', 'ResponseElementController@uploadImage')->name('chathub.response_element.upload-image');
        Route::post('/get-element', 'ResponseElementController@getElement')->name('chathub.response_element.get-element');
    });
    Route::group(['prefix' => 'comment'], function () {
        Route::get('/', 'CommentController@indexAction')->name('chathub.comment');
    });
    Route::group(['prefix' => 'post'], function () {
        Route::get('/', 'PostController@indexAction')->name('chathub.post');
        Route::post('/add-key', 'PostController@addKeyAction')->name('chathub.post.add-key');
        Route::post('/update-key', 'PostController@updateKeyAction')->name('chathub.post.update-key');
        Route::post('/subcribe', 'PostController@subcribeAction')->name('chathub.post.subcribe');
        Route::post('/unsubcribe', 'PostController@unsubcribeAction')->name('chathub.post.unsubcribe');
    });
    Route::group(['prefix' => 'response-detail'], function () {
        Route::get('/', 'ResponseDetailController@indexAction')->name('chathub.response_detail');
        Route::get('/add', 'ResponseDetailController@addAction')->name('chathub.response_detail.add');
        Route::get('/edit', 'ResponseDetailController@editAction')->name('chathub.response_detail.edit');
        Route::post('/create', 'ResponseDetailController@createAction')->name('chathub.response_detail.create');
        Route::post('/update', 'ResponseDetailController@updateAction')->name('chathub.response_detail.update');
        Route::post('/delete', 'ResponseDetailController@deleteAction')->name('chathub.response_detail.delete');
        Route::post('/list', 'ResponseDetailController@listAction')->name('chathub.response_detail.list');
    });

    Route::get('validation', function () {
        return trans('chathub::validation');
    })->name('chathub.validation');

    Route::group(['prefix' => 'response-content'], function () {
        Route::get('/', 'ResponseContentController@indexAction')->name('chathub.response-content');
        Route::post('/list', 'ResponseContentController@listAction')->name('chathub.response-content.list');
        Route::post('/remove', 'ResponseContentController@remove')->name('chathub.response-content.remove');
        Route::get('/edit/{response_content_id}', 'ResponseContentController@editAction')->name('chathub.response-content.edit');
        Route::post('/update', 'ResponseContentController@update')->name('chathub.response-content.update');
        Route::get('/create', 'ResponseContentController@createAction')->name('chathub.response-content.create');
        Route::post('/insert', 'ResponseContentController@insert')->name('chathub.response-content.insert');

        Route::post('/upload-image', 'ResponseContentController@uploadImage')->name('chathub.response-content.upload-image');
        Route::post('/popup-add-template', 'ResponseContentController@popupAddTemplate')->name('chathub.response-content.popup-add-template');
        Route::post('/add-select-template', 'ResponseContentController@addSelectTemplate')->name('chathub.response-content.add-select-template');
        Route::post('/upload-image', 'ResponseContentController@uploadImage')->name('chathub.response-content.upload-image');
        Route::post('/popup-edit-template', 'ResponseContentController@popupEditTemplate')->name('chathub.response-content.popup-edit-template');
        Route::post('/popup-add-button', 'ResponseContentController@popupAddButton')->name('chathub.response-content.popup-add-button');
        Route::post('/popup-edit-button', 'ResponseContentController@popupEditButton')->name('chathub.response-content.popup-edit-button');
        Route::post('/remove-button', 'ResponseContentController@removeButton')->name('chathub.response-content.remove-button');
    });
    Route::group(['prefix' => 'response'], function () {
        Route::get('/', 'ResponseController@indexAction')->name('chathub.response');
        Route::post('/list', 'ResponseController@listAction')->name('chathub.response.list');
        Route::get('/create', 'ResponseController@createAction')->name('chathub.response.create');
        Route::post('/create', 'ResponseController@storeAction')->name('chathub.response.create');
        Route::get('/edit/{response_id}', 'ResponseController@editAction')->name('chathub.response.edit');
        Route::post('/update/{response_id}', 'ResponseController@updateAction')->name('chathub.response.update');
        Route::get('/detail/{response_id}', 'ResponseController@detailAction')->name('chathub.response.detail');
        Route::post('/detail/{response_id}', 'ResponseController@detailAction')->name('chathub.response.detail');
        Route::post('/detail-list', 'ResponseController@detailListAction')->name('chathub.response.detail-list');
    });

    Route::group(["prefix" => 'config-template'], function () {
        Route::group(['prefix' => 'template'], function () {
            Route::get('/add', 'TemplateController@add')->name('chathub.template.add');
            Route::post('/create', 'TemplateController@create')->name('chathub.template.create');
            Route::get('/edit', 'TemplateController@edit')->name('chathub.template.edit');
            Route::post('/update', 'TemplateController@update')->name('chathub.template.update');
            Route::post('/remove', 'TemplateController@deleteAction')->name('chathub.template.remove');
            Route::post('/popup-edit-type-template', 'TemplateController@popupEditTypeTemplate')->name('chathub.template.popup-edit-type-template');
            Route::post('/edit-type-template', 'TemplateController@editTypeTemplate')->name('chathub.template.edit-type-template');
        });
        Route::group(['prefix' => 'button'], function () {
//            Route::get('/', 'ButtonController@index')->name('chathub.button');
//            Route::get('/add', 'ButtonController@add')->name('chathub.button.add');
            Route::post('/create', 'ButtonController@create')->name('chathub.button.create');
//            Route::get('/edit', 'ButtonController@edit')->name('chathub.button.edit');
            Route::post('/update', 'ButtonController@update')->name('chathub.button.update');
            Route::post('/remove', 'ButtonController@deleteAction')->name('chathub.button.remove');
        });
    });
});
