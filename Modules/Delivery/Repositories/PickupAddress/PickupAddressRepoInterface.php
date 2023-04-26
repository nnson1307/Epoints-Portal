<?php

namespace Modules\Delivery\Repositories\PickupAddress;

interface PickupAddressRepoInterface
{
    /**
     * Danh sách địa chỉ lấy hàng
     *
     * @param array $filters = []
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Luu dia chi lay hang moi
     *
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * Luu thong tin chinh sua dia chi lay hang
     *
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * Lay chi tiet dia chi lay hang
     *
     * @param $pickupAddressId
     * @return mixed
     */
    public function getDetail($pickupAddressId);

    /**
     * Xoa dia chi lay hang
     *
     * @param $pickupAddressId
     * @return mixed
     */
    public function destroy($pickupAddressId);
}