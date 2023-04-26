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

///giao dien chinh sach hoa hong. cau hinh chung, cau hinh nhieu cap
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'referral'], function () {

    Route::group(['prefix' => 'commission-order'], function () {
        Route::get('/', 'ReferralProgramInviteController@commissionOrder')->name('referral.commission-order');
        Route::get('detail/{id}', 'ReferralProgramInviteController@commissionOrderDetail')->name('referral.commission-order.commissionOrderDetail');
        Route::post('detail/list/{id}', 'ReferralProgramInviteController@commissionOrderDetailList')->name('referral.commission-order.commissionOrderDetailList');

        Route::post('/list', 'ReferralProgramInviteController@listCommissionOrder')->name('referral.commission-order.listCommissionOrder');

    });

//    Danh sách người giới thiệu
    Route::get('/', 'ReferralMemberController@index')->name('referral.referral-member.index');
    Route::post('/list', 'ReferralMemberController@list')->name('referral.referral-member.list');
//    Chi tiết khách hàng tab Hoa hồng
    Route::get('/detail-commission-referral/{id}', 'ReferralMemberController@detailCommissionReferral')->name('referral.referral-member.detailCommissionReferral');
    Route::post('/detail-commission-referral-list', 'ReferralMemberController@detailCommissionReferralList')->name('referral.referral-member.detailCommissionReferralList');
//    Chi tiết khách hàng tab Lịch sử thanh toán
    Route::get('/detail-history-payment/{id}', 'ReferralMemberController@detailHistoryPayment')->name('referral.referral-member.detailHistoryPayment');
    Route::post('/detail-history-payment-list', 'ReferralMemberController@detailHistoryPaymentList')->name('referral.referral-member.detailHistoryPaymentList');

//    Chi tiết khách hàng tab Danh sách người được giới thiệu
    Route::get('/detail-referral/{id}', 'ReferralMemberController@detailReferral')->name('referral.referral-member.detailReferral');
    Route::get('/detail-referral-child/{id}', 'ReferralMemberController@detailReferralChild')->name('referral.referral-member.detailReferralChild');
    Route::post('/detail-referral-child/list', 'ReferralMemberController@getChild')->name('referral.referral-member.getChild');

    Route::post('/detail-referral-list', 'ReferralMemberController@detailReferralList')->name('referral.referral-member.detailReferralList');
    Route::post('/changeStatusReferralMember', 'ReferralMemberController@changeStatusReferralMember')->name('referral.referral-member.changeStatusReferralMember');

//    Danh sách thanh toán
    Route::get('/referral-payment', 'ReferralPaymentController@index')->name('referral.referral-payment.index');
    Route::post('/referral-payment/list', 'ReferralPaymentController@list')->name('referral.referral-payment.list');

//    Hoa hồng cho người giới thiệu
    Route::get('/referral-program-invite', 'ReferralProgramInviteController@index')->name('referral.referral-program-invite.index');
    Route::post('/referral-program-invite/list', 'ReferralProgramInviteController@list')->name('referral.referral-program-invite.list');
    Route::post('/update-program-invite', 'ReferralProgramInviteController@updateProgramInvite')->name('referral.referral-program-invite.updateProgramInvite');
    Route::post('/reject-commission', 'ReferralProgramInviteController@rejectCommission')->name('referral.referral-program-invite.rejectCommission');

    Route::post('/show-reject', 'ReferralProgramInviteController@showReject')->name('referral.referral-program-invite.showReject');
    Route::post('/show-reject-commission', 'ReferralProgramInviteController@showRejectCommission')->name('referral.referral-program-invite.showRejectCommission');


//    Danh sách chờ thanh toán
    Route::get('/referral-payment-member/{id}', 'ReferralPaymentMemberController@index')->name('referral.referral-payment-member.index');
    Route::post('/referral-payment-member/list/{id}', 'ReferralPaymentMemberController@list')->name('referral.referral-payment-member.list');

//    Lịch sử thanh toán
    Route::get('/referral-payment-member/history/{id}', 'ReferralPaymentMemberController@history')->name('referral.referral-payment-member.history');
    Route::post('/referral-payment-member/history/list/{id}', 'ReferralPaymentMemberController@historyList')->name('referral.referral-payment-member.history-list');
    Route::post('/referral-payment-member/reject', 'ReferralPaymentMemberController@rejectPayment')->name('referral.referral-payment-member.rejectPayment');

    //chinh sach hoa hong
    Route::get('policy-commission', 'ReferralController@policyCommission')->name('referral.policyCommission');
    //cau hinh nhieu cap
    Route::get('multi-level-config', 'ReferralController@multiLevelConfig')->name('referral.multiLevelConfig');
    ///chinh sua cau hinh nhieu cap
    Route::get('edit-multi-level-config/{id}', 'ReferralController@editMultiLevelConfig')->name('referral.editMultiLevelConfig');
    Route::post('submit-edit-multi-level-config', 'ReferralController@submitEditMultiLevelConfig')->name('referral.submitEditMultiLevelConfig');

    ///danh sach cau hinh chung
    Route::get('list-general-config', 'ReferralController@listGeneralConfig')->name('referral.listGeneralConfig');
    //cau hinh chung
    Route::get('general-config/{id?}', 'ReferralController@generalConfig')->name('referral.generalConfig');
    ///chinh sua cau hinh chung
    Route::get('edit-general-config', 'ReferralController@editGeneralConfig')->name('referral.editGeneralConfig');
    ///lây danh sách lịch sử cấu hình chung
    Route::get('history-general-config', 'ReferralController@historyGeneralConfig')->name('referral.historyGeneralConfig');
    ///danh sach nguoi gioi thieu
    Route::get('list-referrer', 'ReferralController@listReferrer')->name('referral.listReferrer');
    ///chi tiết người giới thiệu
    Route::get('detail-referrer', 'ReferralController@detailReferrer')->name('referral.detailReferrer');
    //hoa hong cho ngioi gioi thieu
    Route::get('referrer-commission', 'ReferralController@referrerCommission')->name('referral.referrerCommission');
    ///thanh toán
    Route::get('payment', 'ReferralController@payment')->name('referral.payment');
    //them chinh sach
    Route::get('add-commission/{id?}', 'ReferralController@addCommission')->name('referral.addCommission');
    ///chinh sua thoong tin chinh sach hoa hong
    Route::post('edit-commission', 'ReferralController@editCommission')->name('referral.editCommission');
//    Route::get('edit-info-commission/{id}', 'ReferralController@editInfoCommission')->name('referral.editInfoCommission');
    ///thong tin chi tiết chính sách hoa hồng
    Route::get('edit-info-commission/{id}', 'ReferralController@editInfoCommission')->name('referral.editInfoCommission');
    ///luu thông tin chỉnh sửa chinh sách
    Route::post('save-edit-info-commission', 'ReferralController@saveEditInfoCommission')->name('referral.saveEditInfoCommission');
    ////xóa chinh sách
    Route::post('delete-commission', 'ReferralController@deleteCommission')->name('referral.deleteCommission');
    ///chi tiết chính sách
    Route::get('detail-commission/{id}', 'ReferralController@detailCommission')->name('referral.detailCommission');
    ///lịch sử thay đổi
    Route::post('history-commission', 'ReferralController@historyCommission')->name('referral.historyCommission');
    //luu thông tin chinh sach
    Route::post('save-info-commission', 'ReferralController@saveInfoCommission')->name('referral.saveInfoCommission');
    ///luu dieu kien chinh sach
    Route::post('save-condition-cpi', 'ReferralController@saveConditionCPI')->name('referral.saveConditionCPI');
    ///luu cau hinh nhieu cap`
    Route::post('save-multi-level-config', 'ReferralController@saveMultiLevelConfig')->name('referral.saveMultiLevelConfig');
    ///luu cau hinh chung
    Route::post('save-general-config', 'ReferralController@saveGeneralConfig')->name('referral.saveGeneralConfig');
    //upload anh
    Route::post('upload-image', 'UploadController@uploadImageAction')->name('referral.upload-image')->middleware('s3');
    //tieu chi CPS-> tinh theo:gia tri don hang
//    Route::get('add-commission-order-price', 'ReferralController@addCommissionOrderPrice')->name('referral.addCommissionOrderPrice');
    //tieu chi CPS-> tinh theo: san pham
    Route::get('choose-product', 'ReferralController@chooseProduct')->name('referral.chooseProduct');
    //tieu chi CPS-> tinh theo: gia tri don hang
    Route::get('choose-order-price/{id}', 'ReferralController@chooseOrderPrice')->name('referral.chooseOrderPrice');
    ///dieu kein tinh hoa hong cho gia tri don hang
    Route::post('step-3-choose-order-price', 'ReferralController@step3ChooseOrderPrice')->name('referral.step3ChooseOrderPrice');

    ///lay nhom hang hoa
    Route::post('get-group-commodity', 'ReferralController@getGroupCommodity')->name('referral.getGroupCommodity');
    ///load danh sach nhom hang hoa
    Route::post('load-group-commodity', 'ReferralController@loadGroupCommodity')->name('referral.loadGroupCommodity');
    //lay hang hoa
    Route::post('get-commodity', 'ReferralController@getCommodity')->name('referral.getCommodity');
    ///Xóa hàng hóa trong bảng
    Route::post('delete-commodity', 'ReferralController@deleteCommodity')->name('referral.deleteCommodity');
    Route::post('change-page-product', 'ReferralController@changePageProduct')->name('referral.changePageProduct');

    ///lay tất cả nhóm hàng hóa
    Route::post('load-group-commodity-all', 'ReferralController@loadGroupCommodityAll')->name('referral.loadGroupCommodityAll');
    //them san pham vao bang
    Route::post('add-commodity', 'ReferralController@addCommodity')->name('referral.addCommodity');
    /////thay đổi trạng thái
    Route::post('state-change', 'ReferralController@stateChange')->name('referral.stateChange');
    //tieu chi CPS-> tinh theo:danh muc san pham
    Route::get('choose-category-product', 'ReferralController@chooseCategoryProduct')->name('referral.chooseCategoryProduct');
    //tieu chi CPS-> tinh theo:dich vu
    Route::get('choose-service', 'ReferralController@chooseService')->name('referral.chooseService');
    //tieu chi CPS-> tinh theo:nhom dich vu
    Route::get('choose-group-service', 'ReferralController@chooseGroupService')->name('referral.chooseGroupService');
    //tieu chi CPS-> tinh theo:the dich vu
    Route::get('choose-card-service', 'ReferralController@chooseCardService')->name('referral.chooseCardService');
    //tieu chi CPS-> tinh theo:loai the dich vu
    Route::get('choose-type-card-service', 'ReferralController@chooseTypeCardService')->name('referral.chooseTypeCardService');
    //dieu kien tinh hoa hong
    Route::get('commission-condition/{id}', 'ReferralController@commissionCondition')->name('referral.commissionCondition');
    Route::post('save-conditon-order-price', 'ReferralController@saveConditonOrderPrice')->name('referral.saveConditonOrderPrice');
    //dieu kien tinh hoa hong theo: CPI
    Route::get('commission-condition-cpi/{id}', 'ReferralController@commissionConditionCPI')->name('referral.commissionConditionCPI');
    //dieu kien tinh hoa hong theo: dich vu
    Route::get('commission-condition-service', 'ReferralController@commissionConditionService')->name('referral.commissionConditionService');
    //dieu kien tinh hoa hong theo: nhom dich vu
    Route::get('commission-condition-group-service', 'ReferralController@commissionConditionGroupService')->name('referral.commissionConditionGroupService');
    //dieu kien tinh hoa hong theo: the dich vu
    Route::get('commission-condition-card-service', 'ReferralController@commissionConditionCardService')->name('referral.commissionConditionCardService');
    //dieu kien tinh hoa hong theo: loai the dich vu
    Route::get('commission-condition-type-card-service', 'ReferralController@commissionConditionTypeCardService')->name('referral.commissionConditionTypeCardService');
    //dieu kien tinh hoa hong theo: danh muc san pham
    Route::get('commission-condition-category', 'ReferralController@commissionConditionCategory')->name('referral.commissionConditionCategory');
    //dieu kien tinh hoa hong theo: gia tri don hang
    Route::get('commission-condition-order-price', 'ReferralController@commissionConditionOrderPrice')->name('referral.commissionConditionOrderPrice');
    //dieu kien tinh hoa hong theo: so lan dat lich
    Route::get('commission-condition-booking', 'ReferralController@commissionConditionBooking')->name('referral.commissionConditionBooking');
});

