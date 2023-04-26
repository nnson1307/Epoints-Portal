<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryConfig;
use Modules\StaffSalary\Models\StaffSalaryConfigTable;
use Carbon\Carbon;

class StaffSalaryConfigRepo implements StaffSalaryConfigRepoInterface
{
    public function add($input){
        $mStaffSalaryConfig = app()->get(StaffSalaryConfigTable::class);
        $data = [
            "staff_id" => $input['staff_id'],
            "staff_salary_type_code" => $input['staff_salary_type_code'],
            "staff_salary_pay_period_code" => $input['staff_salary_pay_period_code'],
            "staff_salary_unit_code" => $input['staff_salary_unit_code'],
            "payment_type" => $input['payment_type'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return $mStaffSalaryConfig->add($data);
    }
    
    public function edit($input, $id){
        $mStaffSalaryConfig = app()->get(StaffSalaryConfigTable::class);
        return $mStaffSalaryConfig->edit($input, $id);
    }
    
    public function getDetailByStaff($staffId){
        $mStaffSalaryConfig = app()->get(StaffSalaryConfigTable::class);
        return $mStaffSalaryConfig->getDetailByStaff($staffId);
    }

    public function getListByPayPeriod($periodCode){
        $mStaffSalaryConfig = app()->get(StaffSalaryConfigTable::class);
        return $mStaffSalaryConfig->getListByPayPeriod($periodCode);
    }
    
}