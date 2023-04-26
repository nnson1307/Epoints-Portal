<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\Shift\Repositories\TimeWorkingStaff;


interface TimeWorkingStaffRepoInterface
{
    /**
     * Lấy dữ liệu filter ds
     *
     * @return mixed
     */
    public function getDataFilter();

    /**
     * Lấy ds lịch làm việc
     *
     * @param $filter
     * @return mixed
     */
    public function getList($filter = []);

    /**
     * Lấy ds lịch làm việc theo ca
     *
     * @param $filter
     * @return mixed
     */
    public function getListShift($filter);

    /**
     * Show pop thêm ca làm việc
     *
     * @param $input
     * @return mixed
     */
    public function showPopupShift($input);

    /**
     * Danh sách ca làm việc
     *
     * @param array $filter
     * @return mixed
     */
    public function listShift($filter = []);

    /**
     * Chọn ca làm việc
     *
     * @param $input
     * @return mixed
     */
    public function chooseShift($input);

    /**
     * Bỏ chọn ca làm việc
     *
     * @param $input
     * @return mixed
     */
    public function unChooseShift($input);

    /**
     * Cập nhật các giá trị của ca làm việc đã chọn
     *
     * @param $input
     * @return mixed
     */
    public function updateObjectShift($input);

    /**
     * Thêm ca làm việc cho nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function addShift($input);

    /**
     * Nghỉ việc có lương
     *
     * @param $input
     * @return mixed
     */
    public function paidLeave($input);

    /**
     * Xoá ca làm việc của nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function removeShift($input);

    /**
     * Cập nhật ngày làm việc có đi làm
     *
     * @param $input
     * @return mixed
     */
    public function isWork($input);

    /**
     * Show popup ca làm việc của nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function showPopupMyShift($input);

    /**
     * Danh sách ca làm việc của nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function listMyShift($input);

    /**
     * Xoá nhân viên theo ca
     *
     * @param $input
     * @return mixed
     */
    public function removeStaffByShift($input);

    /**
     * Show popup chọn nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function showPopupStaff($input);

    /**
     * Danh sách nhân viên làm việc
     *
     * @param array $filter
     * @return mixed
     */
    public function listStaff($filter = []);

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
     * Cập nhật các giá trị của nhân viên làm việc đã chọn
     *
     * @param $input
     * @return mixed
     */
    public function updateObjectStaff($input);

    /**
     * Thêm nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function addStaff($input);

    /**
     * Show popup chi tiết lịch làm việc
     *
     * @param $input
     * @return mixed
     */
    public function showTimeWorkingDetail($input);

    /**
     * Chỉnh sửa thời gian làm việc
     *
     * @param $input
     * @return mixed
     */
    public function updateTimeWorking($input);

    /**
     * Show popup chấm công hộ
     *
     * @param $input
     * @return mixed
     */
    public function showPopTimeAttendance($input);

    /**
     * Lưu chấm công hộ
     *
     * @param $input
     * @return mixed
     */
    public function submitTimeAttendance($input);

    /**
     * Lấy data view chỉnh sửa ca làm việc
     *
     * @param $input
     * @return mixed
     */
    public function getDataViewEdit($input);

    /**
     * Thêm ca làm thêm giờ
     *
     * @param $input
     * @return mixed
     */
    public function storeOvertime($input);

    /**
     * Lấy ds thưởng - phạt
     *
     * @param $input
     * @return mixed
     */
    public function getListRecompense($input);

    /**
     * Lấy dữ liệu view thêm thưởng - phạt
     *
     * @param $input
     * @return mixed
     */
    public function getDataCreateRecompense($input);

    /**
     * Thêm hình thức thưởng - phạt
     *
     * @param $input
     * @return mixed
     */
    public function submitCreateRecompense($input);

    /**
     * Xoá thưởng - phạt
     *
     * @param $input
     * @return mixed
     */
    public function removeRecompense($input);

    /**
     * Lấy cấu hình chung ca làm việc
     *
     * @return mixed
     */
    public function getConfigGeneral();
}