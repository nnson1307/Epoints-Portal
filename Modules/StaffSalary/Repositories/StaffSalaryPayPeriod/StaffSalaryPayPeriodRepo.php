<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryPayPeriod;
use Modules\StaffSalary\Models\StaffSalaryPayPeriodTable;
use Carbon\Carbon;

class StaffSalaryPayPeriodRepo implements StaffSalaryPayPeriodRepoInterface
{
    public function getList(){
        $mStaffSalaryPayPeriod = app()->get(StaffSalaryPayPeriodTable::class);
        return $mStaffSalaryPayPeriod->getList();
    }

}