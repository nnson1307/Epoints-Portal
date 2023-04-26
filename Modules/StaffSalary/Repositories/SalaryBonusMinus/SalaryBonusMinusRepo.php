<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\SalaryBonusMinus;
use Modules\StaffSalary\Models\SalaryBonusMinusTable;
use Carbon\Carbon;

class SalaryBonusMinusRepo implements SalaryBonusMinusRepoInterface
{
    public function getList(){
        $mSalaryBonusMinus= app()->get(SalaryBonusMinusTable::class);
        return $mSalaryBonusMinus->getList();
    }

    public function getDetail($id){
        $mSalaryBonusMinus= app()->get(SalaryBonusMinusTable::class);
        return $mSalaryBonusMinus->getDetail($id);
    }
}