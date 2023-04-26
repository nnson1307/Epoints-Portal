<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 2:24 CH
 */

namespace Modules\Admin\Repositories\Staffs;


interface StaffRepositoryInterface
{
    /**
     * Get staff list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete staff
     *
     * @param number $id
     */
    public function remove($id);


    /**
     * Add staff
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update staff
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);
    /**
     * Update OR ADD staff
     * @param array $data
     * @return number
     */

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    public function getNameStaff($id);

    /**
     * @param $userName
     * @param $id
     * @return mixed
     */
    public function testUserName($userName, $id);

    /**
     * @return mixed
     */
    public function getStaffOption();

    /**
     * @return mixed
     */
    public function getStaffTechnician();

    public function getStaffOptionWithMoney();

    /**
     * Export tất cả nhân viên
     *
     * @return mixed
     */
    public function exportAll();

    /**
     * View chi tiet
     *
     * @param $id
     * @return mixed
     */
    public function dataViewDetail($id);

     /**
     * @return mixed
     */
    public function getStaffByBranch($branchId);

    /**
     * Thay đổi phòng ban
     *
     * @param $input
     * @return mixed
     */
    public function changeDepartment($input);
}