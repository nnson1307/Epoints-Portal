<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\CustomerLead\Repositories\CustomerLead;


interface CustomerLeadRepoInterface
{
    /**
     * Danh sách KH tiềm năng
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);
    
    /**
     * Lấy option business
     *
     * @return void
     */
    public function getOptionBusiness();

    /**
     * Data view thêm KH tiềm năng
     *
     * @param $input
     * @return mixed
     */
    public function dataViewCreate($input);

    /**
     * Thêm KH tiềm năng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chỉnh sửa KH tiềm năng
     *
     * @param $input
     * @return mixed
     */
    public function dataViewEdit($input);

    /**
     * Chỉnh sửa KH tiềm năng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);
    public function updateFromOncall($input);

    /**
     * Xóa KH tiềm năng
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Show popup chăm sóc khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function popupCustomerCare($input);

    /**
     * Chăm sóc khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function customerCare($input);

    /**
     * Load data kan ban view
     *
     * @param $input
     * @return mixed
     */
    public function loadKanBanView($input);

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function updateJourney($input);

    /**
     * Lấy option load view kanban
     *
     * @return mixed
     */
    public function optionViewKanban();

    /**
     * Lấy danh sách hành trình theo pipeline code
     *
     * @param $pipelineCode
     * @return mixed
     */
    public function loadOptionJourney($pipelineCode);

    /**
     * Chuyển đổi khách hàng không kèm deal
     *
     * @param $input
     * @return mixed
     */
    public function convertCustomerNoDeal($input);

    /**
     * View tạo deal
     *
     * @param $input
     * @return mixed
     */
    public function dataViewCreateDeal($input);

    /**
     * Export Excel
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelAll($input);

    /**
     * Lấy danh sách tag
     *
     * @return mixed
     */
    public function getListTag();

    /**
     * Lấy danh sách nhân viên
     *
     * @return mixed
     */
    public function getListStaff();

    /**
     * Lấy danh sách pipeline
     *
     * @return mixed
     */
    public function getListPipeline();

    /**
     * Nhập file xlsx
     *
     * @param $file
     * @return mixed
     */
    public function importExcel($file);

    /**
     * View danh sách nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function popupListStaff($input);

    /**
     * Phân công nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function saveAssignStaff($input);

    /**
     * Thu hồi 1 lead
     *
     * @param $input
     * @return mixed
     */
    public function revokeOne($input);

    /**
     * View màn hình phân bổ nhiều
     *
     * @return mixed
     */
    public function dataViewAssign();

    /**
     * Submit phân bổ nhiều
     *
     * @param $input
     * @return mixed
     */
    public function submitAssign($input);

    /**
     * Popup chọn nhân viên sale để thu hồi lead
     *
     * @param $input
     * @return mixed
     */
    public function popupRevoke($input);

    /**
     * Submit thu hồi
     *
     * @param $input
     * @return mixed
     */
    public function submitRevoke($input);

    /**
     * Lấy danh sách nguồn khách hàng
     *
     * @return mixed
     */
    public function getListCustomerSource();

    /**
     * Danh sách lead chưa phân bổ
     *
     * @param $filter
     * @return mixed
     */
    public function listLeadNotAssignYet($filter);

    /**
     * Danh sách sale theo mảng department
     *
     * @param $input
     * @return mixed
     */
    public function loadOptionSale($input);

    /**
     * Chọn all trên 1 page lead
     *
     * @param $data
     * @return mixed
     */
    public function chooseAll($data);

    /**
     * Chọn lead
     *
     * @param $data
     * @return mixed
     */
    public function choose($data);

    /**
     * Bỏ chọn all trên 1 page lead
     *
     * @param $data
     * @return mixed
     */
    public function unChooseAll($data);

    /**
     * Bỏ chọn lead
     *
     * @param $data
     * @return mixed
     */
    public function unChoose($data);

    /**
     * Check tất cả lead theo filter
     *
     * @param $input
     * @return mixed
     */
    public function checkAllLead($input);

    /**
     * Export excel file lỗi
     *
     * @param $input
     * @return mixed
     */
    public function exportError($input);

    /**
     * Tạo deal tự động
     *
     * @param $input
     * @return mixed
     */
    public function createDealAuto($input);

    /**
     * Show modal gọi on call
     *
     * @param $input
     * @return mixed
     */
    public function showModalCall($input);

    /**
     * Gọi (on call)
     *
     * @param $input
     * @return mixed
     */
    public function call($input);

    /**
     * Upload file
     * @param $input
     * @return mixed
     */
    public function uploadFile($input);

    /**
     * Tìm kiếm công việc
     * @param $input
     * @return mixed
     */
    public function searchWorkLead($input);

    /**
     * Lấy danh sách trạng thái công việc
     * @return mixed
     */
    public function getListStatusWork($manage_work_id = null);

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed
     */
    public function addComment($data);

    /**
     * Thêm ghi chú
     * @param $data
     * @return mixed
     */
    public function addNote($data);

    /**
     * Thêm file
     * @param $data
     * @return mixed
     */
    public function addFile($data);

    /**
     * Show edit file
     * @param $data
     * @return mixed
     */
    public function showEditFile($param);

    /**
     * Thêm liên hệ
     * @param $data
     * @return mixed
     */
    public function addContact($data);

    /**
     * hiển thị popup comment
     * @param $data
     * @return mixed
     */
    public function showFormComment($data);

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed
     */
    public function getListComment($id);

    /**
     * Show popup add file
     * @param $id
     * @return mixed
     */
    public function showPopupAddFile($params);

     /**
     * Chi tiết KHTN
     * @param $id
     * @return mixed
     */
    public function dataDetail($customerLeadId);

        /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     */
    public function dataEdit($customerLeadId);

    /**
     * Data view thêm KH tiềm năng
     *
     * @param $input
     * @return array|mixed
     */
    public function dataCreate();

    public function loadKanBanVue($input);
}