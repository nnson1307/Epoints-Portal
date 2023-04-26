<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/06/2021
 * Time: 10:19
 */

namespace Modules\Admin\Repositories\ServiceBooking;


use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\StaffTable;

class ServiceBookingRepo implements ServiceBookingRepoInterface
{
    protected $appointmentDetail;

    public function __construct(
        CustomerAppointmentDetailTable $appointmentDetail
    ) {
        $this->appointmentDetail = $appointmentDetail;
    }

    /**
     * Lấy cấu hình đặt lịch
     *
     * @return array|mixed
     */
    public function getConfig()
    {
        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = app()->get(ConfigTable::class);
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy số tuần trong năm
        $numberWeek = 52;

        $mStaff = app()->get(StaffTable::class);
        //Lấy ds nhân viên
        $optionStaff = $mStaff->getOption();
        //Format lại dữ liệu ds nhân viên
        $dataStaff = [];
        if (count($optionStaff) > 0) {
            foreach ($optionStaff as $v) {
                $dataStaff [$v['staff_id']] = $v['full_name'];
            }
        }

        return [
            'configToDate' => $configToDate,
            'numberWeek' => $numberWeek,
            'optionStaff' => $dataStaff
        ];
    }

    /**
     * Danh sách xe đã book
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->appointmentDetail->getList($filters);

        return [
            'list' => $list
        ];
    }
}