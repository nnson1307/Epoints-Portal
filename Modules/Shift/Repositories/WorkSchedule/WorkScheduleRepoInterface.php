<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2022
 * Time: 09:41
 */

namespace Modules\Shift\Repositories\WorkSchedule;


interface WorkScheduleRepoInterface
{
    /**
     * Danh sách lịch làm việc
     *
     * @param $filter
     * @return mixed
     */
    public function list($filter = []);

    /**
     * Lấy dữ liệu view phân ca
     *
     * @return mixed
     */
    public function getDataViewCreate();

    /**
     * Show popup chọn nhân viên
     *
     * @return mixed
     */
    public function showPopupStaff();

    /**
     * Filter, phân trang ds nhân viên (pop)
     *
     * @param $input
     * @return mixed
     */
    public function listStaffPop($input);

    /**
     * Chọn nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function chooseStaff($input);

    /**
     * Bỏ chọn nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function unChooseStaff($input);

    /**
     * Lưu chọn nhân viên
     *
     * @return mixed
     */
    public function submitChooseStaff();

    /**
     * Filter, phân trang ds nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function listStaff($input);

    /**
     * Xoá nhân viên ra khỏi table
     *
     * @param $input
     * @return mixed
     */
    public function removeStaff($input);

    /**
     * Thêm lịch làm việc
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy dữ liệu view chỉnh sửa lịch làm việc
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function getDataViewEdit($workScheduleId);

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá lịch làm việc
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Validate thời gian làm việc so với các lịch làm việc khác
     *
     * @param array $timeWorkingStaff
     * @return mixed
     */
    public function validateWorkScheduleDiff($timeWorkingStaff = []);

    /**
     * Chọn ca làm việc
     *
     * @param $input
     * @return mixed
     */
    public function chooseShift($input);
}