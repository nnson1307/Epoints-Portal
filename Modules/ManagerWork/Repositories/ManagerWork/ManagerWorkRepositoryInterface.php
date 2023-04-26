<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ManagerWork\Repositories\ManagerWork;


interface ManagerWorkRepositoryInterface
{
    /**
     * Get queue list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);
    /**
     * Get all
     *
     * @param array $all
     */
    public function getName();

    /**
     * Delete queue
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add queue
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update queue
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    /*
    * check exist
    */
    public function checkExist($name = '',$id = '');

    /**
     * Lấy chi tiết công việc
     * @param $id
     * @return mixed
     */
    public function getDetail($id);

    /**
     * Chi tiết công việc
     * @param $id
     * @return mixed
     */
    public function getDetailWork($id);

    /**
     * Lấy danh sách công việc con
     * @param $id
     * @return mixed
     */
    public function getListWorkChildInsert($id);

    /**
     * Lấy danh sách lịch sử
     * @param $id
     * @return mixed
     */
    public function getListHistory($data);

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed
     */
    public function getListComment($id);

    /**
     * Lấy giao diện
     * @param $data
     * @return mixed
     */
    public function changeTabDetailWork($data);

    /**
     * Upload file
     * @param $data
     * @return mixed
     */
    public function uploadFile($data);

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed
     */
    public function addComment($data);

    /**
     * hiển thị popup comment
     * @param $data
     * @return mixed
     */
    public function showFormComment($data);

    /**
     * Lấy danh sách nhân viên
     * @return mixed
     */
    public function getListStaff();

    /**
     * Lấy danh sách id của nhân viên
     * @param $detail
     * @return mixed
     */
    public function getListStaffId($detail);

    /**
     * Search tab lịch sử
     * @param $data
     * @return mixed
     */
    public function searchListHistory($data);

    /**
     * Lấy danh sách file
     * @param $id
     * @return mixed
     */
    public function getListDocument($data);

    /**
     * Phân trang tài liệu
     * @param $data
     * @return mixed
     */
    public function searchDocument($data);

    /**
     * Hiển thị popup upload file
     * @param $data
     * @return mixed
     */
    public function showPopupUploadFile($data);

    /**
     * Thêm file hồ sơ
     * @param $data
     * @return mixed
     */
    public function addFileDocument($data);

    /**
     * Xoá file hồ sơ
     * @param $data
     * @return mixed
     */
    public function removeFileDocument($data);

    /**
     * Lấy danh sách nhắc nhở
     * @param $data
     * @return mixed
     */
    public function getListRemind($id);

    /**
     * Lấy danh sách nhân viên theo nhóm id nhân viên
     * @param $arrStaff
     * @return mixed
     */
    public function getListStaffByWork($arrStaff);

    /**
     * Hiển thị popup nhắc nhở
     * @param $data
     * @return mixed
     */
    public function showPopupRemindPopup($data);

    /**
     * Tạo/chỉnh sửa nhắc nhở
     * @param $data
     * @return mixed
     */
    public function addRemindWork($data);

    /**
     * Xoá nhắc nhở
     * @param $data
     * @return mixed
     */
    public function removeRemind($data);

    /**
     * Tìm kiếm nhắc nhở
     * @param $data
     * @return mixed
     */
    public function searchRemind($data);

    /**
     * Thay đổi trạng thái nhắc nhở
     * @param $data
     * @return mixed
     */
    public function changeStatusRemind($data);

    /**
     * Lấy danh sách nhắc nhở
     * @param $data
     * @return mixed
     */
    public function getListRemindDetail($data);

    /**
     * Lấy danh sách công việc con
     * @param $data
     * @return mixed
     */
    public function getListWorkChild($data);

    /**
     * Hiển thị popup công việc
     * @param $data
     * @return mixed
     */
    public function showPopupWorkChild($data);

    /**
     * Lưu công việc
     * @param $data
     * @return mixed
     */
    public function saveChildWork($data);

    /**
     * Xoá công việc
     * @param $data
     * @return mixed
     */
    public function removeWork($data);

    /**
     * lấy danh sách trạng thái
     * @return mixed
     */
    public function getListStatus();

    /**
     * Tìm kiếm công việc con
     * @param $data
     * @return mixed
     */
    public function searchWork($data);

    public function copyWork($id);

    public function getListByProject($id);

    public function export($data);

    /**
     * lấy danh sách khách hàng
     * @param $data
     * @return mixed
     */
    public function changeCustomer($data);

    /**
     * Popup chuyển folder
     * @param $data
     * @return mixed
     */
    public function popupChangeFolder($data);

    /**
     * Lưu di chuyển tài liệu
     * @param $data
     * @return mixed
     */
    public function submitChangeFolder($data);

    /**
     * Hiển thị popup danh sách nhân viên
     * @param $data
     * @return mixed
     */
    public function showPopupStaff($data);

    /**
     * Search
     * @param $data
     * @return mixed
     */
    public function searchPagePopupStaff($data);

    /**
     * Lấy danh sách nhân viên khi thay đổi chi nhánh
     * @param $data
     * @return mixed
     */
    public function changeBranchStaff($data);

    /**
     * Kiểm tra số lượng công việc con
     * @param $data
     * @return mixed
     */
    public function checkWorkChild($data);

    /**
     * Kiểm tra ngày bắt đầu và kết thúc của dự án - công việc
     * @param $data
     * @return mixed
     */
    public function checkDateWorkProject($data);

    /**
     * Lấy danh sách tác vụ cha
     * @param $data
     * @return mixed
     */
    public function getListParentTask($data);

    /**
     * Thay đổi parent task
     * @param $data
     * @return mixed
     */
    public function changeParentTask($data);

    /**
     * lấy danh sách nhân viên dựa theo dự án
     * @param $data
     * @return mixed
     */
    public function changeListStaff($data);

    /**
     * Show pop chọn nhân viên hỗ trợ
     *
     * @return mixed
     */
    public function showPopStaffSupport($data);

    /**
     * Danh sách nhân viên hỗ trợ
     *
     * @param $filter
     * @return mixed
     */
    public function listStaffSupport($filter = []);

    /**
     * Chọn nhân viên hỗ trợ
     *
     * @return mixed
     */
    public function submitChooseStaffSupport();

    /**
     * Xoá nhân viên đã chọn
     *
     * @param $input
     * @return mixed
     */
    public function removeStaffSupport($input);
}