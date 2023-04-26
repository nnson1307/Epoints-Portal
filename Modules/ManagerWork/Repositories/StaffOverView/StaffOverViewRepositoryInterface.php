<?php


namespace Modules\ManagerWork\Repositories\StaffOverView;


interface StaffOverViewRepositoryInterface
{
    /**
     * Lấy danh sách chi nhánh
     * @return mixed
     */
    public function getListBranch($branchId = null);

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function getListDepartment($departmentId = null);

    /**
     * Lấy danh sách dự án
     * @return mixed
     */
    public function getListProject();

    /**
     * lấy danh sách dự án theo quyền
     * @return mixed
     */
    public function getListProjectPermission($userId);

    /**
     * Search chart
     * @return mixed
     */
    public function searchChart($data);

    /**
     * Phát hiện điểm nóng
     * @param $data
     * @return mixed
     */
    public function hotSpotDetection($data);

    /**
     * Tiến độ công việc
     * @param $data
     * @return mixed
     */
    public function priorityWork($data);

    /**
     * Hiển thị popup tạo nhắc nhở danh sách nhân viên chưa bắt đầu công việc
     * @param $data
     * @return mixed
     */
    public function popupListStaffNotStartWork($data);

    /**
     * Tạo nhắc nhở cho danh sách nhân viên chưa bắt đầu công việc trong ngày
     * @param $data
     * @return mixed
     */
    public function addRemindListStaffNotStart($data);

    /**
     * Popup tạo nhắc nhở cho công việc
     * @param $data
     * @return mixed
     */
    public function popupListWorkOverdue($data);

    /**
     * Tạo nhắc nhở cho danh sách công việc
     * @param $data
     * @return mixed
     */
    public function addRemindWorkOverdue($data);

    /**
     * Hiển thị danh sách công việc theo trạng thái
     * @param $data
     * @return mixed
     */
    public function tableWorkStatus($data);

    /**
     * Hiển thị danh sách công việc theo cấp độ
     * @param $data
     * @return mixed
     */
    public function tableWorkLevel($data);

    /**
     * Check quyền dữ liệu
     * @return mixed
     */
    public function checkPermission();
}