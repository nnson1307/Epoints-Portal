<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryDetail;

use Modules\StaffSalary\Models\StaffSalaryDetailTable;
use Carbon\Carbon;

class StaffSalaryDetailRepo implements StaffSalaryDetailRepoInterface
{
    public function add($input)
    {
        $mStaffSalaryDetail = app()->get(StaffSalaryDetailTable::class);
        $data = [
            "staff_salary_id" => $input['staff_salary_id'],
            "staff_id" => $input['staff_id'],
            "staff_salary_type_code" => $input['staff_salary_type_code'],
            "staff_salary_pay_period_code" => $input['staff_salary_pay_period_code'],
            "staff_salary_overtime" => $input['staff_salary_overtime'],
            "staff_salary_bonus" => $input['staff_salary_bonus'],
            "staff_salary_allowance" => $input['staff_salary_allowance'],
            "staff_salary_main" => $input['staff_salary_main'],
            "staff_salary_received" => $input['staff_salary_received'],
            "staff_salary_minus" => $input['staff_salary_minus'],
            "staff_salary_status" => $input['staff_salary_status'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return $mStaffSalaryDetail->add($data);
    }

    /**
     * Lấy chi tiết bảng lương
     * @param $staffSalaryid
     * @return mixed
     */
    public function getListByStaffSalary($staffSalaryid)
    {
        $mStaffSalaryDetail = app()->get(StaffSalaryDetailTable::class);
        return $mStaffSalaryDetail->getListByStaffSalary($staffSalaryid);
    }

    /**
     * Lấy chi tiết bảng lương theo nhân viên
     * @param $staffSalaryid
     * @return mixed
     */
    public function getDetailByStaff($staffId, $staffSalaryId)
    {
        $mStaffSalaryDetail = app()->get(StaffSalaryDetailTable::class);
        return $mStaffSalaryDetail->getDetailByStaff($staffId, $staffSalaryId);
    }

    /**
     * Lấy chi tiết bảng lương theo nhân viên
     * @param $staffSalaryid
     * @return mixed
     */
    public function getDetail($staffSalaryId)
    {
        $mStaffSalaryDetail = app()->get(StaffSalaryDetailTable::class);
        return $mStaffSalaryDetail->getDetailBySalary($staffSalaryId);
    }

    /**
     * Xóa bảng chi tiết lương theo bảng lương
     */
    public function delete($staff_salary_id)
    {
        $mStaffSalaryDetail = app()->get(StaffSalaryDetailTable::class);
        return $mStaffSalaryDetail->detele($staff_salary_id);
    }

    /**
     * Lấy danh sách bảng lương cho nhân viên
     */
    public function getListSalaryByStaff($staffId)
    {
        $mStaffSalary = app()->get(StaffSalaryDetailTable::class);
        return $mStaffSalary->getListSalaryByStaff($staffId);
    }
}