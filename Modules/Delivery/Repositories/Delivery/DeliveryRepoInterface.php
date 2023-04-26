<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 12:03 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Repositories\Delivery;


interface DeliveryRepoInterface
{
    /**
     * Danh sách đơn hàng cần giao
     *
     * @param array $filters = []
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy các option view
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Lấy thông tin chỉnh sửa giao hàng
     *
     * @param $deliveryId
     * @return mixed
     */
    public function dataEdit($deliveryId);

    /**
     * Chỉnh sửa đơn hàng cần giao
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Lấy thông tin view tạo phiếu giao hàng
     *
     * @param $deliveryId
     * @return mixed
     */
    public function dataCreateHistory($deliveryId);

    /**
     * Chọn sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function chooseProduct($input);

    /**
     * Tạo phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function storeHistory($input);

    /**
     * Preview order
     * @param $input
     * @return mixed
     */
    public function previewOrderAction($input);

    /**
     * Lấy thông tin view chi tiết đơn hàng cần giao
     *
     * @param $deliveryId
     * @return mixed
     */
    public function dataDetail($deliveryId);

    /**
     * Cập nhật trạng thái chi tiết đơn hàng cần giao
     *
     * @param $input
     * @return mixed
     */
    public function saveDetail($input);

    /**
     * Xác nhận thanh toán
     *
     * @param $input
     * @return mixed
     */
    public function confirmReceipt($input);

    /**
     * Load tiền cần thu khi thay đổi số lượng
     *
     * @param $input
     * @return mixed
     */
    public function loadAmount($input);

    /**
     * Chi tiết phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function detailHistory($input);

    /**
     * Modal chỉnh sửa phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function editHistory($input);

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function updateHistory($input);

    /**
     * Show modal xác nhận thanh toán phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function modalConfirmReceipt($input);

    /**
     * Thêm đơn hàng cần giao
     *
     * @param $input
     * @return mixed
     */
    public function storeDelivery($input);

    /**
     * Cập nhật trạng thái hoạt động delivery
     *
     * @param $input
     * @return mixed
     */
    public function updateIsActiveDelivery($input);
}