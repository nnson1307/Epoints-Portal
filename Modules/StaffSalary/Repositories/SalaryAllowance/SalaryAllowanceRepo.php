<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\SalaryAllowance;
use Modules\StaffSalary\Models\SalaryAllowanceTable;
use Carbon\Carbon;

class SalaryAllowanceRepo implements SalaryAllowanceRepoInterface
{
    public function getList(){
        $mSalaryAllowance = app()->get(SalaryAllowanceTable::class);
        return $mSalaryAllowance->getList();
    }

    public function getDetail($id){
        $mSalaryAllowance = app()->get(SalaryAllowanceTable::class);
        return $mSalaryAllowance->getDetail($id);
    }

    public function add($input){
        $dataMaster = [
            "salary_allowance_name" => $input['salary_allowance_name'],
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
        ];
        $mSalaryAllowance = app()->get(SalaryAllowanceTable::class);
        $mSalaryAllowanceId = $mSalaryAllowance->add($dataMaster);
        return $mSalaryAllowanceId;
    }

    public function edit($input, $id){
        $dataMaster = [
            "salary_allowance_name" => $input['salary_allowance_name'],
            "updated_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
            "updated_by"    =>  Auth()->id(),
        ];
        $mSalaryAllowance = app()->get(SalaryAllowanceTable::class);
        $mSalaryAllowanceId = $mSalaryAllowance->edit($dataMaster, $id);
        return $mSalaryAllowanceId;
    }
}