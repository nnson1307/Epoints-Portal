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

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin'], function () { //account
    Route::prefix('commission')->group(function() {
        /**
         * Trang danh sách hoa hồng
         */
        Route::get('/', 'CommissionController@indexAction')->name('admin.commission');
        Route::post('/list', 'CommissionController@listAction')->name('admin.commission.list');
        //Chi tiết hoa hồng
        Route::get('/detail/{id}', 'CommissionController@detailAction')->name('admin.commission.detail');
        //Xoá hoa hồng
        Route::post('/remove/{id}', 'CommissionController@removeAction')->name('admin.commission.remove');
        //Cập nhật trạng thái hoa hồng
        Route::post('change-status', 'CommissionController@changeStatusAction')->name('admin.commission.change-status');

        /**
         * Danh sách hoa hồng theo nhân viên
         */
        Route::get('/received', 'CommissionController@indexStaffCommisionAction')->name('admin.commission.received');
        Route::post('/list-received', 'CommissionController@listStaffCommisionAction')->name('admin.commission.list-received');
        //Show pop chỉnh sửa hoa hồng nhân viên
        Route::post('show-pop-edit-received', 'CommissionController@showPopEditReceivedAction')->name('admin.commission.show-pop-edit-received');
        //Chỉnh sửa hoa hồng nhân viên
        Route::post('submit-edit-received', 'CommissionController@submitEditReceivedAction')->name('admin.commission.submit-edit-received');
        //Chi tiết hoa hồng nhân viên
        Route::get('received/detail/{id}', 'CommissionController@staffCommissionDetailAction')->name('admin.commission.detail-received');

        /**
         * Trang thêm mới hoa hồng
         */
        Route::get('add', 'CommissionController@addAction')->name('admin.commission.add');
        //Submit thêm mới hoa hồng
        Route::post('submit', 'CommissionController@submitAction')->name('admin.commission.submit');
        //Thêm tag nhanh
        Route::post('create-tag', 'CommissionController@createTagAction')->name('admin.commission.create-tag');
        //Thay đổi loại hoa hồng
        Route::post('change-type', 'CommissionController@changeTypeAction')->name('admin.commission.change-type');
        //Thay đổi loại hàng hoá
        Route::post('change-order-type', 'CommissionController@changeOrderTypeAction')->name('admin.commission.change-order-type');
        //Thay đổi nhóm hàng hoá
        Route::post('option-order-object', 'CommissionController@optionOrderObjectAction')->name('admin.commission.option-order-object');
        //Danh sách hoa hồng đã nhận của nhân viên
        Route::post('list-staff-commission', 'CommissionController@listStaffCommissionAction')->name('admin.commission.list-staff-commission');

        /**
         * Trang phân bổ hoa hồng
         */
        Route::get('allocation', 'CommissionController@allocationAction')->name('admin.commission.allocation');
        //DS nhân viên phân bổ
        Route::post('list-staff', 'CommissionController@listStaffAction')->name('admin.commission.list-staff');
        //Chọn nhân viên
        Route::post('choose-staff', 'CommissionController@chooseStaffAction')->name('admin.commission.choose-staff');
        //Bỏ chọn nhân viên
        Route::post('un-choose-staff', 'CommissionController@unChooseStaffAction')->name('admin.commission.un-choose-staff');
        //Cập nhật các giá trị trong từng dòng nhân viên
        Route::post('update-object-staff', 'CommissionController@updateObjectStaffAction')->name('admin.commission.update-object-staff');
        //Danh sách hoa hòng
        Route::post('list-commission', 'CommissionController@listCommissionAction')->name('admin.commission.list-commission');
        //Chọn hoa hồng
        Route::post('choose-commission', 'CommissionController@chooseCommissionAction')->name('admin.commission.choose-commission');
        //Bỏ chọn hoa hồng
        Route::post('un-choose-commission', 'CommissionController@unChooseCommissionAction')->name('admin.commission.un-choose-commission');
        //Cập nhật các giá trị trong từng dòng hoa hồng
        Route::post('update-object-commission', 'CommissionController@updateObjectCommissionAction')->name('admin.commission.update-object-commission');
        //Load bảng phân bổ
        Route::post('load-allocation', 'CommissionController@loadAllocationTableAction')->name('admin.commission.load-allocation');
        //Submit phân bổ hgoa hồng
        Route::post('submit-allocation', 'CommissionController@submitAllocationAction')->name('admin.commission.allocation.submit');
        //Thay đổi giá trị load tiêu chí kpi
        Route::post('change-scope', 'CommissionController@changeScopeAction')->name('admin.commission.change-scope');
    });
});
