<?php


namespace Modules\FNB\Repositories\Order;


interface OrderRepositoryInterface
{
    public function list(array $filters = []);

    /**
     * Page tạo đơn hàng
     * @param $request
     * @return mixed
     */
    public function addOrders($request);


    public function chooseType($input);

    public function listAddAction($request);

    /**
     * Hiển thị popup chọn topping
     * @param $data
     * @return mixed
     */
    public function selectTopping($data);

    /**
     * Lưu lựa chọn topping
     * @param $data
     * @return mixed
     */
    public function saveToppingSelect($data);

    /**
     * @param $data
     * @return mixed
     */
    public function changeToppingSelect($data);

    /**
     * Lưu đơn hàng
     * @param $data
     * @return mixed
     */
    public function submitOrUpdate($data);

    /**
     * Lấy chi tiết
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id);

    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = []);

    /**
     * Xóa session lưu thông tin sản phẩm cần xóa
     * @param $data
     * @return mixed
     */
    public function removeSessionProduct($data);

    /**
     * Save session table
     * @param $data
     * @return mixed
     */
    public function saveSessionTable($data);

    /**
     * Xóa đơn hàng
     * @param $data
     * @return mixed
     */
    public function removeOrder($data);

    public function edit($data,$id);

    public function calculatedCommission($quantity, $refer_id, $check_commission = null, $id_detail, $object_id, $item4 = null, $item10, $item11, $refer_money = 0, $staff_money = 0, $type = "");

    /**
     * Xóa đơn giao hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function removeDelivery($orderId);

    /**
     * Lấy danh sách serial theo id đơn hàng
     * @param $data
     * @return mixed
     */
    public function getListSerialOrder($orderId, $session);

    /**
     * Giao diện danh sách đơn hàng theo bàn
     * @param $tableId
     * @return mixed
     */
    public function viewListOrderTable($tableId, $orderId = 0);

    /**
     * Lưu thông tin đơn hàng
     *
     * @param $orderId
     * @param int $isPayment
     * @return mixed
     */
    public function updateContractGoods($orderId, $isPayment = 0);

    /**
     * Lưu log dự kiến nhắc sử dụng
     *
     * @param $orderId
     * @param $customerId
     * @param $arrObject
     * @return mixed
     */
    public function insertRemindUse($orderId, $customerId, $arrObject);

    /**
     * Gộp bàn
     * @param $data
     * @return mixed
     */
    public function popupSelectTable($data);

    /**
     * Chọn khu vực
     * @param $data
     * @return mixed
     */
    public function changeArea($data);

    /**
     * Tìm kiếm bàn muốn chuyển
     * @param $data
     * @return mixed
     */
    public function searchOrder($data);

    /**
     * Gộp bàn
     * @param $data
     * @return mixed
     */
    public function submitMergeTable($data);

    /**
     * Gộp bill
     * @param $data
     * @return mixed
     */
    public function submitMergeBill($data);

    /**
     * Di chuyển bàn
     * @param $data
     * @return mixed
     */
    public function submitMoveTable($data);

    /**
     * Tách bàng
     * @param $data
     * @return mixed
     */
    public function submitSplitTable($data);

    /**
     * Hiển thị popup danh sách đơn hàng cần in
     * @param $data
     * @return mixed
     */
    public function showPopupOrderTable($data);

    /**
     * Hiển thị popup danh sách yêu cầu của khách hàng
     * @param $data
     * @return mixed
     */
    public function showPopupCustomerRequest($data);

    /**
     * Thay đổi thông tin địa chỉ giao hàng
     * @param $data
     * @return mixed
     */
    public function changeInfoAddress($data);

    /**
     * Thêm phiếu bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderId
     * @param $orderCode
     * @param $dataTableAdd
     * @param $dataTableEdit
     */
    public function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd, $dataTableEdit = null);
}