<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/23/2020
 * Time: 4:46 PM
 */

namespace Modules\Delivery\Repositories\DeliveryHistory;


interface DeliveryHistoryRepoInterface
{
    /**
     * Danh sách phiếu giao hàng
     *
     * @param array $filters = []
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data chi tiết phiếu giao hàng
     *
     * @param $deliveryHistoryId
     * @return mixed
     */
    public function dataDetail($deliveryHistoryId);

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Phiếu in
     */
    public function print($input);

    /**
     * Hiển thị popup in
     * @param $input
     * @return mixed
     */
    public function showPopupPrint($input);


    public function getListDeliveryPartner();

    /**
     * lấy danh sách đối tác vận chuyển
     * @return mixed
     */
    public function getListTransport();
}