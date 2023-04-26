<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\Shift\Repositories\Timekeeping;


use App\Exports\CustomerLeadExport;
use App\Exports\ExportFile;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CustomerLead\Models\ConfigTable;
use Modules\CustomerLead\Models\CustomerCareTable;
use Modules\CustomerLead\Models\CustomerContactsTable;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerEmailTable;
use Modules\CustomerLead\Models\CustomerFanpageTable;
use Modules\CustomerLead\Models\CustomerLeadCustomDefineTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerLogTable;
use Modules\CustomerLead\Models\CustomerLogUpdateTable;
use Modules\CustomerLead\Models\CustomerPhoneTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\CustomerLead\Models\DistrictTable;
use Modules\CustomerLead\Models\ExtensionTable;
use Modules\CustomerLead\Models\HistoryTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\MapCustomerTagTable;
use Modules\CustomerLead\Models\OrderSourceTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\ProvinceTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Models\TagTable;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\TypeWorkTable;
use Modules\Shift\Models\ShiftTable;
use Modules\Shift\Models\StaffTable;
use Modules\Shift\Models\TimekeepingConfigTable;
use Modules\Shift\Models\TimeWorkingStaffsTable;
use Modules\Shift\Models\TimeWorkingStaffTable;
use Modules\Shift\Models\BranchTable;

class TimekeepingRepo implements TimekeepingRepoIf
{
    protected $timeWorkingStaff;

    public function __construct(
        TimeWorkingStaffTable $timeWorkingStaff
    ) {
        $this->timeWorkingStaff = $timeWorkingStaff;
    }

    /**
     * Danh sách chấm công của nhân viên
     *
     * @param array $param
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function list(array $param = [])
    {
        $mStaff = app()->get(StaffTable::class);

        $param['date_object'] = isset($param['date_object']) ? $param['date_object'] : Carbon::now()->format('m');
        $param['branch_id'] = isset($param['branch_object']) ? $param['branch_object'] : null;
        $param['years'] = isset($param['years']) ? $param['years'] : Carbon::now()->format('Y');
        //Lấy ds nhân viên
        $list = $mStaff->getList($param);

        $year = $param['years'];

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy lịch làm việc của nv theo tháng
                $getTimeWorking = $this->timeWorkingStaff->getTimeWorkingStaffByMonth($v['staff_id'], $param['date_object'], $param['years']);

//                dd($getTimeWorking->toArray());
                //Tổng ca làm
                $totalShift = count($getTimeWorking);
                //Tổng ca tăng ca
                $totalShiftOt = 0;
                //Số giờ làm tối thiểu
                $totalHourMinShift = 0;
                //Tổng giờ làm
                $totalHourShift = 0;
                //Tổng giờ tăng ca
                $totalHourOt = 0;
                //Số lần đi trễ
                $totalWorkLate = 0;
                //Số lần về sớm
                $totalBackSoon = 0;
                //Số ca nghỉ không lương
                $totalLeaveUnPaid = 0;
                //Số ca nghỉ có luông
                $totalLeavePaid = 0;
                //Số ca không check in
                $totalNotCheckIn = 0;
                //Số ca không check in
                $totalNotCheckOut = 0;

                foreach ($getTimeWorking as $v1) {
                    if ($v1['is_ot'] == 1) {
                        $totalShiftOt++;
                        $totalHourOt += $v1['time_work'];
                    }

                    $totalHourMinShift += $v1['min_time_work'];
                    $totalHourShift += $v1['time_work'];

                    if ($v1['number_late_time'] != null && $v1['number_late_time'] > 0) {
                        if ($v1['number_late_time'] < session()->get('off_check_in') && session()->get('off_check_in') > 0) {
                            $totalWorkLate++;
                        } else if (session()->get('off_check_in') <= 0) {
                            $totalWorkLate++;
                        }
                    }

                    if ($v1['number_time_back_soon'] != null && $v1['number_time_back_soon'] > 0) {
                        if ($v1['number_time_back_soon'] < session()->get('off_check_out') && session()->get('off_check_out') > 0) {
                            $totalBackSoon++;
                        } else if (session()->get('off_check_out') <= 0) {
                            $totalBackSoon++;
                        }
                    }

                    if ($v1['is_check_in'] == 0 && $v1['is_check_out'] == 0 &&
                        Carbon::parse($v1['working_day'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')
                    ) {
                        if ($v1['is_deducted'] === 0) {
                            $totalLeavePaid++;
                        } else {
                            $totalLeaveUnPaid++;
                        }
                    }

                    // if (
                    //     $v1['is_check_in'] == 0 && $v1['is_check_out'] == 0 && $v1['is_deducted'] === 0 &&
                    //     Carbon::parse($v1['working_day'] . ' ' . $v1['working_time'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')
                    // ) {
                    //     $totalLeavePaid++;
                    // }

                    if ($v1['is_check_in'] == 0 &&
                        Carbon::parse($v1['working_day'] . ' ' . $v1['working_time'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')
                    ) {
                        $totalNotCheckIn++;
                    }

                    if ($v1['is_check_in'] == 1 && $v1['is_check_in'] == 0 &&
                        Carbon::parse($v1['working_day'] . ' ' . $v1['working_time'])->format('Y-m-d') < Carbon::now()->format('Y-m-d')
                    ) {

                        $totalNotCheckOut++;
                    }

                    $isCheckOffConfig = 0;

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($v1['number_late_time'] != null && $v1['number_late_time'] > session()->get('off_check_in') && session()->get('off_check_in') > 0) {
                        //Nghĩ không lương
                        $totalLeaveUnPaid++;

                        $isCheckOffConfig = 1;
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($v1['number_time_back_soon'] != null && $isCheckOffConfig == 0 && $v1['number_time_back_soon'] > session()->get('off_check_out') && session()->get('off_check_out') > 0) {
                        //Nghĩ không lương
                        $totalLeaveUnPaid++;
                    }
                }

                $v['totalShift'] = $totalShift;
                $v['totalShiftOt'] = $totalShiftOt;
                $v['totalHourMinShift'] = $totalHourMinShift;
                $v['totalHourShift'] = $totalHourShift;
                $v['totalHourOt'] = $totalHourOt;
                $v['totalWorkLate'] = $totalWorkLate;
                $v['totalBackSoon'] = $totalBackSoon;
                $v['totalLeaveUnPaid'] = $totalLeaveUnPaid;
                $v['totalLeavePaid'] = $totalLeavePaid;
                $v['totalNotCheckIn'] = $totalNotCheckIn;
                $v['totalNotCheckOut'] = $totalNotCheckOut;
            }
        }

        $mBranch = app()->get(BranchTable::class);
        $getOptionBranch = $mBranch->getOption();
        return [
            "list" => $list,
            "month" => $param['date_object'],
            "year" => $year,
            "arr_branch" => $getOptionBranch
        ];
    }

    /**
     * Lấy data filter
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataFilter()
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffTable::class);

        $data = [
            'optionDepartment' => [],
            'optionStaff' => []
        ];

        //Lấy option phòng ban
        $getOptionDepartment = $mDepartment->getOption();

        if (count($getOptionDepartment) > 0) {
            foreach ($getOptionDepartment as $v) {
                $data['optionDepartment'][$v['department_id']] = $v['department_name'];
            }
        }

        //Lấy option nhân viên
        $getOptionStaff = $mStaff->getOption();

        if (count($getOptionStaff) > 0) {
            foreach ($getOptionStaff as $v) {
                $data['optionStaff'][$v['staff_id']] = $v['full_name'];
            }
        }

        return $data;
    }

    /**
     * Chi tiết theo nhân viên
     *
     * @param array $params
     * @return array|mixed
     */
    public function detailStaff($staffId, $month, $year)
    {
        $lst = $this->timeWorkingStaff->getTimeWorkingStaffByMonth($staffId, $month, $year);

        return $lst;
    }

    public function detailStaffByWorkingDay($staffId, $month, $year, $day)
    {

        $lst = $this->timeWorkingStaff->getTimeWorkingStaffByWorkingDay($staffId, $month, $year, $day);

        return $lst;
    }

    public function getDetailInfoStaff($staffId)
    {
        $mStaff = app()->get(StaffTable::class);
        $objStaff = $mStaff->getDetail($staffId);
        return $objStaff;
    }

    public function getAllStaff()
    {
        //Lấy option nhân viên
        $mStaff = app()->get(StaffTable::class);
        $getOptionStaff = $mStaff->getOption();

        return $getOptionStaff;
    }
}
