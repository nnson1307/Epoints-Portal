<?php


namespace Modules\CustomerLead\Repositories\CustomerDeal;


interface CustomerDealRepoInterface
{
    public function dataViewIndex();
    /**
     * Danh sách customer deal
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array &$filters = []);

    /**
     * data view thêm deal
     *
     * @param $input
     * @return mixed
     */
    public function dataViewCreate($input);

    /**
     * Tìm kiếm khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function searchCustomerAction($input);

    /**
     * Danh sách liên hệ theo customer code
     *
     * @param $input
     * @return mixed
     */
    public function optionCustomerContact($input);

    /**
     * Load danh sách các object theo object type (product, service, service_card)
     *
     * @param array $filter
     * @return mixed
     */
    public function loadObject($filter = []);

    /**
     * Lấy giá của object (sản phẩm, dịch vụ, thẻ dịch vụ)
     *
     * @param $input
     * @return mixed
     */
    public function getPriceObject($input);

    /**
     * Lưu deal
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Cập nhật deal
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoa deal theo id
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * View chỉnh sửa deal
     *
     * @param $input
     * @return mixed
     */
    public function dataViewEdit($input);

    /**
     * View chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function dataViewDetail($input);

    /**
     * Các option view kanban
     *
     * @return mixed
     */
    public function optionViewKanban();

    /**
     * Load data kan ban view
     *
     * @param $input
     * @return mixed
     */
    public function loadKanbanView(array &$input = []);

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function updateJourney($input);

    /**
     * data cho giao dien thanh toan don hang tu deal
     *
     * @param $dealId
     * @return mixed
     */
    public function dataViewPayment($dealId);

    /**
     * Lưu thông tin đơn hàng từ deal
     *
     * @param $input
     * @return mixed
     */
    public function saveOrder($input);
    public function saveOrUpdateOrder($input);

    /**
     * Thanh toán đơn hàng trực tiếp
     *
     * @param $input
     * @return mixed
     */
    public function submitPayment($input);

    /**
     * data popup tạo KHTN
     *
     * @param $input
     * @return mixed
     */
    public function dataModalAddCustomerLead($input);

    /**
     * Data popup tạo KH
     *
     * @param $input
     * @return mixed
     */
    public function dataModalAddCustomer($input);

    /**
     * Lưu KHTN
     *
     * @param $input
     * @return mixed
     */
    public function storeCustomerLead($input);

    /**
     * Lưu tag mới
     *
     * @param $input
     * @return mixed
     */
    public function storeQuicklyTag($input);

    /**
     * popup CSKH
     *
     * @param $input
     * @return mixed
     */
    public function popupCustomerCare($input);

    /**
     * Lưu tt CSKH
     *
     * @param $input
     * @return mixed
     */
    public function customerCare($input);

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

    public function dataViewAssign();

    public function listDealNotAssignYet($filter);

    public function chooseAll($data);

    public function choose($data);

    public function unChooseAll($data);

    public function unChoose($data);

    public function checkAllDeal($input);

    public function submitAssign($input);
    
    public function popupRevoke($input);

    public function submitRevoke($input);

    public function popupListStaff($input);

    public function saveAssignStaff($input);

    public function revokeOne($input);

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
     * Lấy danh sách comment
     * @param $id
     * @return mixed
     */
    public function getListComment($id);

}