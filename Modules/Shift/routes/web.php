<?php
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'shift', 'namespace' => 'Modules\Shift\Http\Controllers'], function () {

    Route::group(['prefix' => 'time-working-staff'], function () {
        //DS lịch làm việc theo tuần - tháng
        Route::get('/', 'TimeWorkingStaffController@index')->name('shift.time-working-staff');
        Route::post('list', 'TimeWorkingStaffController@listAction')->name('shift.time-working-staff.list');
        //DS lịch làm việc theo ca
        Route::get('shift', 'TimeWorkingStaffController@indexShift')->name('shift.time-working-staff.index-shift');
        Route::post('list-shift-index', 'TimeWorkingStaffController@listShiftIndexAction')->name('shift.time-working-staff.list-shift-index');
        //Show modal thêm ca làm việc
        Route::post('show-pop-shift', 'TimeWorkingStaffController@showPopupShiftAction')->name('shift.time-working-staff.show-pop-shift');
        //Filter, phân trang ds ca làm việc
        Route::post('list-shift', 'TimeWorkingStaffController@listShiftAction')->name('shift.time-working-staff.list-shift');
        //Chọn ca làm việc
        Route::post('choose-shift', 'TimeWorkingStaffController@chooseShiftAction')->name('shift.time-working-staff.choose-shift');
        //Bỏ chọn ca làm việc
        Route::post('un-choose-shift', 'TimeWorkingStaffController@unChooseShiftAction')->name('shift.time-working-staff.un-choose-shift');
        //Cập nhật các giá trị của ca đã chọn
        Route::post('update-object-shift', 'TimeWorkingStaffController@updateObjectShiftAction')->name('shift.time-working-staff.update-object-shift');
        //Thêm ca làm việc
        Route::post('add-shift', 'TimeWorkingStaffController@addShiftAction')->name('shift.time-working-staff.add-shift');
        //Nghỉ có lương or không lương
        Route::post('paid-or-unpaid-leave', 'TimeWorkingStaffController@paidOrUnPaidLeaveAction')->name('shift.time-working-staff.paid-or-unpaid-leave');
        //Xoá ca làm việc của nhân viên
        Route::post('remove-shift', 'TimeWorkingStaffController@removeShiftAction')->name('shift.time-working-staff.remove-shift');
        //Có đi làm
        Route::post('is-work', 'TimeWorkingStaffController@isWorkAction')->name('shift.time-working-staff.is-work');
        //Show modal ds ca làm việc của tôi
        Route::post('show-pop-my-shift', 'TimeWorkingStaffController@showPopupMyShiftAction')->name('shift.time-working-staff.show-pop-my-shift');
        //List ca làm việc cua tôi
        Route::post('list-my-shift', 'TimeWorkingStaffController@listMyShiftAction')->name('shift.time-working-staff.list-my-shift');
        //Xoá nhân viên theo ca
        Route::post('remove-staff-by-shift', 'TimeWorkingStaffController@removeStaffByShiftAction')->name('shift.time-working-staff.remove-staff-by-shift');
        //Show modal thêm nhân viên
        Route::post('show-pop-staff', 'TimeWorkingStaffController@showPopupStaffAction')->name('shift.time-working-staff.show-pop-staff');
        //Filter, phân trang ds nhân viên
        Route::post('list-staff', 'TimeWorkingStaffController@listStaffAction')->name('shift.time-working-staff.list-staff');
        //Chọn nhân viên làm việc
        Route::post('choose-staff', 'TimeWorkingStaffController@chooseStaffAction')->name('shift.time-working-staff.choose-staff');
        //Bỏ chọn nhân viên làm việc
        Route::post('un-choose-staff', 'TimeWorkingStaffController@unChooseStaffAction')->name('shift.time-working-staff.un-choose-staff');
        //Cập nhật các giá trị của ca đã chọn
        Route::post('update-object-staff', 'TimeWorkingStaffController@updateObjectStaffAction')->name('shift.time-working-staff.update-object-staff');
        //Thêm nhân viên
        Route::post('add-staff', 'TimeWorkingStaffController@addStaffAction')->name('shift.time-working-staff.add-staff');
        //Chi tiết ngày làm việc
        Route::post('show-time-working-detail', 'TimeWorkingStaffController@showTimeWorkingDetailAction')->name('shift.time-working-staff.show-time-working-detail');
        //Show popup chấm công hộ
        Route::post('show-pop-time-attendance', 'TimeWorkingStaffController@showPopTimeAttendanceAction')->name('shift.time-working-staff.show-pop-time-attendance');
        //Lưu chấm công hộ
        Route::post('submit-time-attendance', 'TimeWorkingStaffController@submitTimeAttendanceAction')->name('shift.time-working-staff.submit-time-attendance');
        //Show popup chỉnh sửa ca làm việc
        Route::post('show-pop-edit', 'TimeWorkingStaffController@showPopupEditAction')->name('shift.time-working-staff.show-pop-edit');
        //Chỉnh sửa ngày làm việc
        Route::post('update-time-working', 'TimeWorkingStaffController@updateTimeWorkingAction')->name('shift.time-working-staff.update-time-working');
        //Show popup làm thêm giờ
        Route::post('show-pop-overtime', 'TimeWorkingStaffController@showPopupOvertimeAction')->name('shift.time-working-staff.show-pop-overtime');
        //Thêm ca làm thêm giờ
        Route::post('store-overtime', 'TimeWorkingStaffController@storeOvertimeAction')->name('shift.time-working-staff.store-overtime');
        //Lấy ds thưởng - phạt
        Route::post('list-recompense', 'TimeWorkingStaffController@listRecompenseAction')->name('shift.time-working-staff.list-recompense');
        //Thêm loại thưởng - phạt
        Route::post('show-pop-create-recompense', 'TimeWorkingStaffController@showPopupCreateRecompenseAction')
            ->name('shift.time-working-staff.show-pop-create-recompense');
        //Lưu hình thức thưởng - phạt
        Route::post('submit-create-recompense', 'TimeWorkingStaffController@submitCreateRecompenseAction')
            ->name('shift.time-working-staff.submit-create-recompense');
        //Xoá thưởng - phạt
        Route::post('remove-recompense', 'TimeWorkingStaffController@removeRecompenseAction')
            ->name('shift.time-working-staff.remove-recompense');
        Route::post('get-select-week', 'TimeWorkingStaffController@getSelectWeekAction')
            ->name('shift.time-working-staff.get-select-week');
    });

    Route::group(['prefix' => 'attendances'], function () {
        //DS lịch làm việc theo tuần - tháng
        Route::get('/', 'AttendancesController@index')->name('attendances.index');
        Route::post('/show-check-in', 'AttendancesController@showModalCheckInAction')->name('attendances.modal-checkin');
        Route::post('/check-in', 'AttendancesController@checkInAction')->name('attendances.checkin');
        Route::post('/check-out', 'AttendancesController@checkOutAction')->name('attendances.checkout');
        Route::post('/list', 'AttendancesController@listAction')->name('attendances.list');
        Route::post('/list-department', 'AttendancesController@getDepartmentAction')->name('attendances.load-department');
        Route::post('/approve', 'AttendancesController@approveLateSoonAction')->name('attendances.approve');
    });

    Route::group(['prefix' => 'work-schedule'], function () {
        Route::get('/', 'WorkScheduleController@index')->name('shift.work-schedule');
        Route::post('list', 'WorkScheduleController@listAction')->name('shift.work-schedule.list');
        //View thêm lịch làm việc
        Route::get('create', 'WorkScheduleController@create')->name('shift.work-schedule.create');
        Route::post('show-pop-staff', 'WorkScheduleController@showPopupStaffAction')->name('shift.work-schedule.show-pop-staff');
        Route::post('list-staff-pop', 'WorkScheduleController@listStaffPopAction')->name('shift.work-schedule.list-staff-pop');
        //Chọn nhân viên
        Route::post('choose-staff', 'WorkScheduleController@chooseStaffAction')->name('shift.work-schedule.choose-staff');
        //Bỏ chọn nhân viên
        Route::post('un-choose-staff', 'WorkScheduleController@unChooseStaffAction')->name('shift.work-schedule.un-choose-staff');
        //Lưu chọn nhân viên
        Route::post('submit-choose-staff', 'WorkScheduleController@submitChooseStaffAction')->name('shift.work-schedule.submit-choose-staff');
        //Phân trang ds nhân viên
        Route::post('list-staff', 'WorkScheduleController@listStaffAction')->name('shift.work-schedule.list-staff');
        Route::post('remove-staff-tr', 'WorkScheduleController@removeStaffAction')->name('shift.work-schedule.remove-staff');
        //Thêm lịch làm việc
        Route::post('store', 'WorkScheduleController@store')->name('shift.work-schedule.store');
        //View chỉnh sửa lịch làm việc
        Route::get('edit/{id}', 'WorkScheduleController@edit')->name('shift.work-schedule.edit');
        //Chỉnh sửa lịch làm việc
        Route::post('update', 'WorkScheduleController@update')->name('shift.work-schedule.update');
        //Xoá lịch làm việc
        Route::post('destroy', 'WorkScheduleController@destroy')->name('shift.work-schedule.destroy');
        //Chọn ca làm việc
        Route::post('choose-shift', 'WorkScheduleController@chooseShiftAction')->name('shift.work-schedule.choose-shift');
    });

    Route::group(['prefix' => 'shift'], function () {
        Route::get('/', 'ShiftController@index')->name('shift');
        Route::post('list', 'ShiftController@listAction')->name('shift.list');
        Route::post('create', 'ShiftController@create')->name('shift.create');
        Route::post('store', 'ShiftController@store')->name('shift.store');
        Route::post('edit', 'ShiftController@edit')->name('shift.edit');
        Route::post('update', 'ShiftController@update')->name('shift.update');
        Route::post('destroy', 'ShiftController@destroy')->name('shift.destroy');
        Route::post('update-status', 'ShiftController@updateStatusAction')->name('shift.shift.update-status');
        //Tính thời gian tối thiểu làm việc
        Route::post('calculate-min-work', 'ShiftController@calculateMinWorkAction')->name('shift.shift.calculate-min-work');
    });

    Route::group(['prefix' => 'timekeeping-config'], function () {
        Route::get('/', 'TimekeepingConfigController@index')->name('timekeeping-config');
        Route::post('list', 'TimekeepingConfigController@listAction')->name('timekeeping-config.list');
        Route::post('create', 'TimekeepingConfigController@create')->name('timekeeping-config.create');
        Route::post('store', 'TimekeepingConfigController@store')->name('timekeeping-config.store');
        Route::post('edit', 'TimekeepingConfigController@edit')->name('timekeeping-config.edit');
        Route::post('update', 'TimekeepingConfigController@update')->name('timekeeping-config.update');
        Route::post('destroy', 'TimekeepingConfigController@destroy')->name('timekeeping-config.destroy');
        Route::post('show', 'TimekeepingConfigController@show')->name('timekeeping-config.show');
        Route::post('change-status', 'TimekeepingConfigController@changeStatusAction')->name('timekeeping-config.change-status');

        //Lấy thông tin wifi hiện tại
        Route::post('current-ip', 'TimekeepingConfigController@getCurrentIpAction')->name('timekeeping-config.get-current-ip');
    });

    Route::group(['prefix' => 'timekeeping'], function () {
        Route::get('/', 'TimekeepingController@index')->name('timekeeping');
        Route::post('list', 'TimekeepingController@listAction')->name('timekeeping.list');
        Route::get('detail-staff', 'TimekeepingController@detailStaff')->name('timekeeping.detail-staff');
        Route::post('list-detail-staff', 'TimekeepingController@listDetailAction')->name('timekeeping.list_detail');
    });

    Route::group(['prefix' => 'config-noti'], function () {
        Route::get('/', 'ConfigNotiController@index')->name('config-noti');
        Route::get('/edit', 'ConfigNotiController@edit')->name('config-noti.edit');
        Route::post('/show-popup', 'ConfigNotiController@showPopup')->name('config-noti.show-popup');
        Route::post('/update-message', 'ConfigNotiController@updateMessage')->name('config-noti.update-message');
        Route::post('/update-noti', 'ConfigNotiController@updateNoti')->name('config-noti.update-noti');
    });

    Route::group(['prefix' => 'config-general'], function () {
        Route::get('/', 'ConfigGeneralController@index')->name('shift.config-general');
        Route::post('update', 'ConfigGeneralController@update')->name('shift.config-general.update');
    });

    Route::group(['prefix' => 'recompense'], function () {
        Route::get('/', 'RecompenseController@index')->name('shift.recompense');
        Route::post('list', 'RecompenseController@listAction')->name('shift.recompense.list');
        //Show pop thêm thưởng phạt
        Route::post('show-pop-create', 'RecompenseController@showPopCreateAction')->name('shift.recompense.show-pop-create');
        //Thêm thưởng phạt
        Route::post('store', 'RecompenseController@store')->name('shift.recompense.store');
        //Show pop chỉnh sửa thưởng phạt
        Route::post('show-pop-edit', 'RecompenseController@showPopEditAction')->name('shift.recompense.show-pop-edit');
        //Chỉnh sửa thưởng phạt
        Route::post('update', 'RecompenseController@update')->name('shift.recompense.update');
        //Xoá thưởng phạt
        Route::post('destroy', 'RecompenseController@destroy')->name('shift.recompense.destroy');
        //Cập nhật trạng thái
        Route::post('change-status', 'RecompenseController@changeStatusAction')->name('shift.recompense.change-status');
    });
});