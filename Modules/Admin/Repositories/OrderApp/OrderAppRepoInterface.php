<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/25/2020
 * Time: 10:45 AM
 */

namespace Modules\Admin\Repositories\OrderApp;


interface OrderAppRepoInterface
{
    /**
     * Danh sách đơn hàng từ app
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy dữ liệu view thêm đơn hàng
     *
     * @return mixed
     */
    public function dateViewCreate($input);

    /**
     * Thêm đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    public function storeOrUpdateOrderApp($input);

    /**
     * Thêm đơn hàng và thanh toán
     *
     * @param $input
     * @return mixed
     */
    public function storeReceipt($input);

    /**
     * Lấy dữ liệu view thanh toán
     *
     * @param $orderId
     * @return mixed
     */
    public function dataViewReceipt($orderId, $paymentType);

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function edit($input);

    /**
     * Thanh toán đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function receipt($input);

    /**
     * Render hình ảnh thẻ dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function renderCard($input);

    /**
     * Data view chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function dataViewDetail($orderId);

    public function getListContactByIdCus($idCustomer);

    public function getDetailContact($idCusContact);

    public function addContact($data);

    public function editContact($data);

    public function removeContact($idContact);

    public function setDefaultContact($idContact, $idCustomer);

    /**
     * Ajax filter, phan trang contact
     *
     * @param array $filter
     * @return mixed
     */
    public function listCustomerContact($filter = []);

    /** lay contact mac dinh cua khach hang
     * @param $idCus
     * @return mixed
     */
    public function getContactDefault($idCus);

    /**
     * Đồng bộ đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function syncOrder($input);

    /**
     * Cộng quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $arrQuota
     * @return mixed
     */
    public function plusQuotaUsePromotion($arrQuota);

    /**
     * Trừ quota_use của đơn hàng có promotion là quà tặng
     *
     * @param $orderId
     * @return mixed
     */
    public function subtractQuotaUsePromotion($orderId);

    /**
     * Group số lượng mua của các object, lấy ra CTKM áp dụng cho đơn hàng
     *
     * @param $arrObjectBuy
     * @return mixed
     */
    public function groupQuantityObjectBuy($arrObjectBuy);

    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = []);
}