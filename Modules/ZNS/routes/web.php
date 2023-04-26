<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'zns', 'namespace' => 'Modules\ZNS\Http\Controllers'], function () {#, ,'account'
    Route::group(['prefix' => 'campaign'], function () {
        Route::get('/', 'CampaignController@list')->name('zns.campaign');
        Route::get('add', 'CampaignController@add')->name('zns.campaign.add');
        Route::get('edit/{id}', 'CampaignController@edit')->name('zns.campaign.edit');
        Route::get('view/{id}', 'CampaignController@view')->name('zns.campaign.view');
        Route::get('clone/{id}', 'CampaignController@cloneView')->name('zns.campaign.clone');
        Route::get('remove/{id?}', 'CampaignController@removeAction')->name('zns.campaign.remove');
        Route::post('remove-action', 'CampaignController@removeCampaignAction')->name('zns.campaign.remove-action');
        Route::post('show-list-customer', 'CampaignController@showListCustomer')->name('zns.campaign.show-list-customer');
        Route::post('confirm-popup', 'CampaignController@confirmPopup')->name('zns.campaign.confirm-popup');
        Route::post('add-action', 'CampaignController@addAction')->name('zns.campaign.add-action');
        Route::post('edit-action', 'CampaignController@editAction')->name('zns.campaign.edit-action');
        Route::post('clone-action', 'CampaignController@cloneAction')->name('zns.campaign.clone-action');
        Route::post('change-status', 'CampaignController@changeStatusAction')->name('zns.campaign.change-status');
        Route::post('clone-action', 'CampaignController@cloneAction')->name('zns.campaign.clone-action');
    });
    Route::group(['prefix' => 'campaign-follower'], function () {
        Route::get('/', 'CampaignFollowerController@list')->name('zns.campaign-follower');
        Route::get('add', 'CampaignFollowerController@add')->name('zns.campaign-follower.add');
        Route::get('edit/{id}', 'CampaignFollowerController@edit')->name('zns.campaign-follower.edit');
        Route::get('view/{id}', 'CampaignFollowerController@view')->name('zns.campaign-follower.view');
        Route::get('clone/{id}', 'CampaignFollowerController@cloneView')->name('zns.campaign-follower.clone');
        Route::get('remove/{id?}', 'CampaignFollowerController@removeAction')->name('zns.campaign-follower.remove');
        Route::post('remove-action', 'CampaignFollowerController@removeCampaignAction')->name('zns.campaign-follower.remove-action');
        Route::post('show-list-customer', 'CampaignFollowerController@showListCustomer')->name('zns.campaign-follower.show-list-customer');
        Route::post('confirm-popup', 'CampaignFollowerController@confirmPopup')->name('zns.campaign-follower.confirm-popup');
        Route::post('add-action', 'CampaignFollowerController@addAction')->name('zns.campaign-follower.add-action');
        Route::post('edit-action', 'CampaignFollowerController@editAction')->name('zns.campaign-follower.edit-action');
        Route::post('clone-action', 'CampaignFollowerController@cloneAction')->name('zns.campaign-follower.clone-action');
        Route::post('change-status', 'CampaignFollowerController@changeStatusAction')->name('zns.campaign-follower.change-status');
        Route::post('clone-action', 'CampaignFollowerController@cloneAction')->name('zns.campaign-follower.clone-action');
    });
    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@list')->name('zns.config');
        Route::post('edit', 'ConfigController@editView')->name('zns.config.edit');
        Route::post('edit-submit', 'ConfigController@editSubmit')->name('zns.config.edit-submit');
        Route::post('change-status', 'ConfigController@changeStatusAction')->name('zns.config.change-status');
        Route::get('send-noti', 'ConfigController@sendNotification')->name('zns.config.send-noti');
    });
    Route::group(['prefix' => 'params'], function () {
        Route::get('/', 'ParamsController@list')->name('zns.params');
        Route::post('edit', 'ParamsController@edit')->name('zns.params.edit');
        Route::post('edit-submit', 'ParamsController@editSubmit')->name('zns.params.edit-submit');
    });

    Route::group(['prefix' => 'template'], function () {
        Route::get('/', 'TemplateController@list')->name('zns.template');
        Route::post('/get-template', 'TemplateController@getTemplate')->name('zns.template.get-template');
        Route::post('/synchronized', 'TemplateController@synchronized')->name('zns.template.synchronized');
        Route::get('/synchronized', 'TemplateController@synchronized')->name('zns.template.synchronized');
        Route::group(['prefix' => 'follower'], function () {
            Route::get('/', 'TemplateController@listTemplateFollower')->name('zns.template-follower');
            Route::get('/add', 'TemplateController@addViewFollower')->name('zns.template-follower.add');
            Route::post('/add-data', 'TemplateController@addFollower')->name('zns.follower.add');
            Route::get('/edit/{id}', 'TemplateController@editViewFollower')->name('zns.template-follower.edit');
            Route::post('/edit-submit', 'TemplateController@editSubmitFollower')->name('zns.template-follower.edit-submit');
            Route::get('/remove/{id?}', 'TemplateController@addViewFollower')->name('zns.template-follower.remove');
            Route::get('/clone/{id?}', 'TemplateController@addViewFollower')->name('zns.template-follower.clone');
            Route::post('/clone-action', 'TemplateController@cloneActionFollower')->name('zns.template-follower.clone-action');
            Route::get('/view/{id?}', 'TemplateController@viewFollower')->name('zns.template-follower.view');
            Route::get('/preview/{id?}', 'TemplateController@previewTemplateFollower')->name('zns.template-follower.preview');
            Route::post('/add-button', 'TemplateController@addButtonFollower')->name('zns.template_follower.add-button');
            Route::post('/get-template', 'TemplateController@getTemplateFollower')->name('zns.template.get-template-follower');
        });
    });
    Route::group(['prefix' => 'customer-care'], function () {
        Route::get('/', 'CustomerCareController@list')->name('zns.customer-care');
        Route::post('/edit', 'CustomerCareController@editCustomerCare')->name('zns.customer-care.edit');
        Route::post('/edit-action', 'CustomerCareController@editCustomerCareAction')->name('zns.customer-care.edit-action');
        Route::post('/synchronized', 'CustomerCareController@synchronized')->name('zns.customer-care.synchronized');
        Route::post('/remove-action', 'CustomerCareController@removeAction')->name('zns.customer-care.remove');
        Route::get('/tag', 'CustomerCareController@listTag')->name('zns.customer-care-tag');
        Route::post('/tag-add-action', 'CustomerCareController@addTagAction')->name('zns.customer-care-tag.add');
        Route::post('/tag-edit-action', 'CustomerCareController@editCustomerCareTagAction')->name('zns.customer-care-tag.edit-action');
        Route::post('/tag-remove-action', 'CustomerCareController@removeTagAction')->name('zns.customer-care-tag.remove');
        Route::post('/get-district', 'CustomerCareController@getDistrict')->name('zns.customer-care.get-district');
    });

//Route::get('parameter', 'CampaignController@list')->name('parameter');
//    Route::get('translate', function () {
//        return trans('zns::translate');
//    })->name('zns.translate');
});
