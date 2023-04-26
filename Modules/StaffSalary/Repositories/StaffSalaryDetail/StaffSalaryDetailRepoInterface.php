<?php


namespace Modules\StaffSalary\Repositories\StaffSalaryDetail;


interface StaffSalaryDetailRepoInterface
{
    /**
     * thêm chi tiêt lương
     */
    public function add($input);

    /**
     * lấy danh sách chi tiết lương
     */
    public function getListByStaffSalary($staffSalaryid);

    /**
     * lấy danh sách chi tiết lương theo nhân viên
     */
    public function getDetailByStaff($staffId, $staffSalaryId);

    /**
     * lấy danh sách chi tiết lương
     */
    public function getDetail($staffSalaryId);

    /**
     * Xóa bảng lương chi tiết
     */
    public function delete($staff_salary_id);

    /**
     * Lấy danh sách lương
     */
    public function getListSalaryByStaff($staffId);
}