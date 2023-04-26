<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/7/2021
 * Time: 2:06 PM
 */

namespace Modules\Delivery\Http\Api;


use MyCore\Api\ApiAbstract;

class DeliveryApi extends ApiAbstract
{
    /**
     * Cộng kho khi hủy phiếu giao hàng
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function backInventory(array $data = [])
    {
        return $this->baseClientLoyaltyApi('/delivery-carrier/back-inventory', $data, false);
    }

    /**
     * Lấy danh sách dịch vụ của giao hàng nhanh
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getListServiceGHN(array $data = [])
    {
        return $this->baseClientShareService('/delivery/service/list', $data, false);
    }

    /**
     * Lấy phí giao hàng
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getFee(array $data = [])
    {
        return $this->baseClientShareService('/delivery/service/fee', $data, false);
    }

    /**
     * Lấy đơn hàng preview
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function previewOrder(array $data = [])
    {
        return $this->baseClientShareService('/delivery/order/preview', $data, false);
    }

    /**
     * Tạo đơn hàng
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function createOrder(array $data = [])
    {
        return $this->baseClientShareService('/delivery/order/create', $data, false);
    }

    /**
     * Cập nhật đơn hàng
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function updateOrder(array $data = [])
    {
        return $this->baseClientShareService('/delivery/order/update', $data, false);
    }

    /**
     * Tạo cửa hàng
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function createStore(array $data = [])
    {
        return $this->baseClientShareService('/delivery/shop/create', $data, false);
    }

    /**
     * Phiếu in ghn
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function printView(array $data = [])
    {
        return $this->baseClientShareService('/delivery/order/print', $data, false);
    }
}