<?php


namespace Modules\ManagerWork\Repositories\Report;


interface ReportRepositoryInterface
{
    /**
     * Lấy danh sách chi nhánh
     * @return mixed
     */
    public function getListBranch();

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function getListDepartment();

    /**
     * Lấy danh sách nhân viên
     * @return mixed
     */
    public function getListStaff();

    /**
     * lấy danh sách báo cáo
     * @param $data
     * @return mixed
     */
    public function getListReport($data);

    /**
     * Lấy danh sách báo cáo theo trạng thái hoạt động
     * @param $data
     * @return mixed
     */
    public function getListReportStatus($data);

    /**
     * Danh sách công việc theo báo cáo
     * @param $data
     * @return mixed
     */
    public function getListWorkReport($data);

    /**
     * Danh sách export
     * @param $data
     * @return mixed
     */
    public function getListReportExport($data);


    public function checkList($list);

    /**
     * lấy danh sách công việc của tôi
     * @param $data
     * @return mixed
     */
    public function getListMyWork($data);

    /**
     * Tổng công việc của tôi
     * @return mixed
     */
    public function getTotalMyWork();

    /**
     * Lấy danh sách công việc tôi giao
     * @param $data
     * @return mixed
     */
    public function getListMyWorkAssign($data);

    /**
     * Huỷ / Duyệt công việc
     * @param $data
     * @return mixed
     */
    public function workApprove($data);

    /**
     * Lấy danh sách nhắc nhở của tôi
     * @param $data
     * @return mixed
     */
    public function searchRemind($data);

    /**
     * Xoá nhắc nhở
     * @param $data
     * @return mixed
     */
    public function removeRemind($data);

    /**
     * Hiển thị popup nhắc nhở
     * @param $data
     * @return mixed
     */
    public function showPopupRemindPopup($data);

    /**
     * Tạo nhắc nhở
     * @param $data
     * @return mixed
     */
    public function addRemindWork($data);

    /**
     * Lấy tổng số công việc user tạo trong tháng
     * @return mixed
     */
    public function getTotalCreated();

    /**
     * Lấy tổng số công việc user cần duyệt trong tháng
     * @return mixed
     */
    public function getTotalApprove();

//    Lấy danh sách cấu hình block
    public function getListBlock($routeName,$arrayBlock);

    /**
     * Cập nhật vị trí block
     * @return mixed
     */
    public function myWorkUpdateBlock($data);

    /**
     * Hiển thị danh sách công việc ở page Việc của tôi
     * @param $data
     * @return mixed
     */
    public function viewReportTableMyWork($filter);

    /**
     * Hiển thị danh sách công việc tôi hỗ trợ ở page Việc của tôi
     * @param $filter
     * @return mixed
     */
    public function viewReportTableWorkSupport($filter);

    /**
     * Lấy danh sách trạng thái hoạt động
     * @return mixed
     */
    public function getListStatusActive($data = []);

    /**
     * Lấy danh sách hoạt động
     * @return mixed
     */
    public function getListHistory();
}