<?php


namespace Modules\Admin\Repositories\Calendar;


use Illuminate\Support\Carbon;
use Modules\Admin\Models\AppointmentSourceTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\RoomTable;
use Modules\Admin\Models\ServiceBranchPriceTable;
use Modules\Admin\Models\StaffsTable;


class CalendarRepo implements CalendarRepoInterface
{
    /**
     * Show popup thêm lịch hẹn
     *
     * @param $input
     * @return mixed|void
     */
    public function showModalAdd($input)
    {
        $mStaff = app()->get(StaffsTable::class);
        $mRoom = app()->get(RoomTable::class);
        $mService = app()->get(ServiceBranchPriceTable::class);
        $mAppointmentSource = app()->get(AppointmentSourceTable::class);
        $mConfig = app()->get(ConfigTable::class);
        //Lấy ngày click
        $date = Carbon::createFromFormat('Y-m-d', $input['date_now'])->format('d/m/Y');
        //Lấy ngày + giờ hiện tại
        $day = date('d/m/Y');
        $time = date('H:i');
        //Lấy option nhân viên
        $optionStaff = $mStaff->getStaffTechnician();
        //Lấy option phòng phục vụ
        $optionRoom = $mRoom->getRoomOption();
        //Lấy option dịch vụ
        $optionService = $mService->getOptionService(Auth()->user()->branch_id);
        //Lấy nguồn lịch hẹn
        $optionSource = $mAppointmentSource->getOption();
        //Lấy nhóm khách hàng
        $mCustomerGroup = new CustomerGroupTable();
        $optionGroup = $mCustomerGroup->getOption();
        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy số tuần trong năm
        $numberWeek = 52;

        $is_booking_past = 0;
        //Lấy phân quyền đặt lịch lùi
        if (Auth()->user()->is_admin == 1
            || in_array('is_booking_past',session('routeList'))) {
            $is_booking_past  = 1;
        }
        //Render view
        $html = \View::make('admin::calendar.pop.create', [
            'configToDate' => $configToDate,
            'optionSource' => $optionSource,
            'date_now' => $date,
            'day_now' => $day,
            'time_now' => $time,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
            'service_id' => $input['service_id'],
            'numberWeek' => $numberWeek,
            'is_booking_past' => $is_booking_past,
            'optionGroup' => $optionGroup
        ])->render();

        return response()->json([
            'html' => $html,
            'date_now' => $date,
            'day_now' => $day,
            'time_now' => $time,
            'is_booking_past' => $is_booking_past
        ]);
    }

    /**
     * Show popup chi tiết dịch vụ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function showModalDetail($input)
    {
        $mAppointmentDetail = new CustomerAppointmentDetailTable();

        //Lấy chi tiết lịch hẹn từ dịch vụ
        $getDetail = $mAppointmentDetail->getDetailByService(
            $input['service_id'],
            $input['start_date'],
            $input['end_date']
        );

        //Render view
        $html = \View::make('admin::calendar.pop.detail', [
            'list' => $getDetail
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }
}