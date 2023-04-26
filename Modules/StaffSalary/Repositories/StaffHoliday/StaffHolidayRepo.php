<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffHoliday;

use Modules\StaffSalary\Models\StaffHolidayTable;
use Carbon\Carbon;

class StaffHolidayRepo implements StaffHolidayRepoInterface
{

    public function getList(array $filters = [])
    {
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        return $mStaffHoliday->_getList($filters);
    }

    public function getDetail($id)
    {
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        return $mStaffHoliday->getDetail($id);
    }

    public function add($input)
    {
        $dataMaster = [
            "staff_holiday_title" => $input['staff_holiday_title'],
            "staff_holiday_start_date" => $input['staff_holiday_start_date'],
            "staff_holiday_end_date" => $input['staff_holiday_end_date'],
            "staff_holiday_number" => $input['staff_holiday_number'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
        ];
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        $staffHolidayId = $mStaffHoliday->add($dataMaster);
        return $staffHolidayId;
    }

    public function edit($input, $id)
    {
        $dataMaster = [
            "staff_holiday_title" => $input['staff_holiday_title'],
            "staff_holiday_start_date" => $input['staff_holiday_start_date'],
            "staff_holiday_end_date" => $input['staff_holiday_end_date'],
            "staff_holiday_number" => $input['staff_holiday_number'],
            "updated_by"    =>  Auth()->id(),
        ];
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        $staffHolidayId = $mStaffHoliday->edit($dataMaster, $id);
        return $staffHolidayId;
    }

    public function delete($id)
    {
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        $staffHolidayId = $mStaffHoliday->deleteById($id);
        return $staffHolidayId;
    }

    public function getHolidayByDate($startDate)
    {
        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        $staffHolidayId = $mStaffHoliday->getHolidayByDate($startDate);
        return $staffHolidayId;
    }
}
