<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\StaffSalary\Repositories\StaffSalary;


interface StaffSalaryRepoInterface
{

    /**
     * Add
     *
     * @return mixed
     */
    public function add($input);

    /**
     * get danh sách lương
     */
    public function getList($filters);

    /**
     * Lấy chi tiết bảng lương
     */
    public function getDetail($id);

    /**
     *Câp nhật bảng lương
     */
    public function edit($data, $id);

    /**
     * Lấy chi tiết bảng lương theo ngày
     */
    public function getDetailByDate($startDate, $endDate);
    /**
     * Get List Staff Salary Type
     *
     * @return mixed
     */
    public function getListStaffSalaryType();

    /**
     * add Salary bonus minus
     * @param $input
     * @return mixed
     */
    public function addSalaryBonusMinus($input);

    /**
     * add Salary allowance
     * @param $input
     * @return mixed
     */
    public function addSalaryAllowance($input);

    /**
     * get detail salary bonus minus
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryBonusMinusByStaff($staffId);

    /**
     * get detail salary allowance
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryAllowanceByStaff($staffId);

    /**
     * delete salary allowance
     * @param $staffId
     * @return mixed
     */
    public function deleteSalaryAllowanceByStaff($staffId);

    /**
     * delete salary Bonus Minus
     * @param $staffId
     * @return mixed
     */
    public function deleteSalaryBonusMinusByStaff($staffId);

    /**
     * thêm data nhân công
     */
    public function addTimeKeepingStaff($input);

    /**
     * Xóa bảng công
     */
    public function deleteTimeKeepingStaff($staff_salary_id);

    /**
     * Lấy chi tiết bảng chấm công
     */
    public function getDetailTimeKeepingStaff($staffId, $staffSalaryId);

    /***
     * Lấy danh sách công việc
     */
    public function getListWorkingStaff($startDate, $endDate, $staffId);

    /**
     * 
     */
    public function getTotalLate($startDate, $endDate, $staffId);

    /**
     * 
     */
    public function getTotalSoon($startDate, $endDate, $staffId);

    /**Cập nhật trạng thái lịch làm việc
     * @param $data
     * @param $id
     * @return mixed
     */
    public function editWorkingStaff($data, $id);

    /**
     * get detail salary overtime
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryOvertimeByStaff($staffId);

     /**
     * get detail salary overtime
     * @param $input
     * @return mixed
     */
    public function deleteSalaryOvertimeByStaff($staffId);

        /**
     * save salary overtime
     * @param $input
     * @return mixed
     */
    public function addSalaryOvertime($input);
}
