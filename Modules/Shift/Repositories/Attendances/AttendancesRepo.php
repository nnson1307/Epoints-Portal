<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\Shift\Repositories\Attendances;
use Modules\Shift\Models\BranchTable;
use Modules\Shift\Models\DepartmentTable;
use Modules\Shift\Models\ShiftTable;
use Modules\Shift\Models\ShiftCheckInLogTable;
use Modules\Shift\Models\TimeWorkingStaffTable;
use Modules\Shift\Models\ShiftCheckOutLogTable;
use Carbon\Carbon;

class AttendancesRepo implements AttendancesRepoInterface
{
    /**
     * Lấy data filter
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataFilter()
    {
        $mShift = app()->get(ShiftTable::class);
        $data = [
            'optionShift' => []
        ];
        //Lấy option ca làm việc
        $getOptionShift = $mShift->getOption();

        if (count($getOptionShift) > 0) {
            foreach ($getOptionShift as $v) {
                $data['optionShift'] [$v['shift_id']] = $v['shift_name'];
            }
        }
        return $data;
    }

    public function getListShift()
    {
        $mShift = app()->get(ShiftTable::class);
       
        $getOptionShift = $mShift->getOption();
        return $getOptionShift;
    }

    public function getListShiftCheckin($staffId, $working_day, $time_day)
    {
        $mTimeWorking = app()->get(TimeWorkingStaffTable::class);

        $getOptionShift = $mTimeWorking->_getListShiftCheckin($staffId, $working_day, $time_day);
        return $getOptionShift;
    }

    public function checkin($input){
        $dataMaster = [
            "time_working_staff_id" => $input['time_working_staff_id'],
            "staff_id" => $input['staff_id'],
            "branch_id" => $input['branch_id'],
            "shift_id" => $input['shift_id'],
            "check_in_day" => $input['check_in_day'],
            "check_in_time" => $input['check_in_time'],
            "status" => $input['status'],
            "reason" => $input['reason'],
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mShiftCheckInLog = app()->get(ShiftCheckInLogTable::class);
        $checkInLogId = $mShiftCheckInLog->add($dataMaster);
        return $checkInLogId;
    }

    public function checkout($input){
        $dataMaster = [
            "time_working_staff_id" => $input['time_working_staff_id'],
            "staff_id" => $input['staff_id'],
            "branch_id" => $input['branch_id'],
            "shift_id" => $input['shift_id'],
            "check_out_day" => $input['check_out_day'],
            "check_out_time" => $input['check_out_time'],
            "status" => $input['status'],
            "reason" => $input['reason'],
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mShiftCheckOutLog = app()->get(ShiftCheckOutLogTable::class);
        $checkOutLogId = $mShiftCheckOutLog->add($dataMaster);
        return $checkOutLogId;
    }

    public function getListHistoryCheckIn(array $filters = []){
      
        $mShiftCheckInLog = app()->get(ShiftCheckInLogTable::class);
        return $mShiftCheckInLog->_getList($filters);
    }

    public function getListBranch(){
        $mBranch = app()->get(BranchTable::class);
        return $mBranch->getOption();
    }

    public function getListDepartment(){
        $mDepartment = app()->get(DepartmentTable::class);
        return $mDepartment->getOption();
    }

    public function updateWorkingTime(array $data = [], $id){
        $mTimeWorking = app()->get(TimeWorkingStaffTable::class);
        return $mTimeWorking->updateCheckin($data, $id);
    }

    public function approveLateSoon(array $data = [], $id){
        $mTimeWorking = app()->get(TimeWorkingStaffTable::class);
        return $mTimeWorking->approveLateSoon($data, $id);
    }

    public function getInfo($id){
        $mTimeWorking = app()->get(TimeWorkingStaffTable::class);
        return $mTimeWorking->getInfo($id);
    }
}