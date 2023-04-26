<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryAttribute;
use Modules\StaffSalary\Models\StaffSalaryAttributeTable;
use Carbon\Carbon;

class StaffSalaryAttributeRepo implements StaffSalaryAttributeRepoInterface
{
    public function add($input){
        $mStaffSalaryAttribute = app()->get(StaffSalaryAttributeTable::class);
        $data = [
            "staff_salary_attribute_code" => $input['staff_salary_attribute_code'],
            "staff_salary_attribute_value" => $input['staff_salary_attribute_value'],
            "staff_salary_attribute_type" => $input['staff_salary_attribute_type'],
            "staff_id" => $input['staff_id'],
            "branch_id" => $input['branch_id'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return $mStaffSalaryAttribute->add($data);
    }
    
    public function getDetailByStaff($staffId){
        $mStaffSalaryAttribute = app()->get(StaffSalaryAttributeTable::class);
        return $mStaffSalaryAttribute->getDetailByStaff($staffId);
    }

    public function deleteByStaff($staffId){
        $mStaffSalaryAttribute = app()->get(StaffSalaryAttributeTable::class);
        return $mStaffSalaryAttribute->deleteByStaff($staffId);
    }

}