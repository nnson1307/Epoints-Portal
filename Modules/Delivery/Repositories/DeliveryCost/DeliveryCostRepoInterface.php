<?php


namespace Modules\Delivery\Repositories\DeliveryCost;


interface DeliveryCostRepoInterface
{
    /**
     * Danh sách chi phi giao hang
     *
     * @param array $filters = []
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Them moi chi phi giao hang
     *
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * Chinh sua chi phi giao hang
     *
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * xoa chi phi giao hang
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id);


    /**
     * data màn hình chỉnh sửa chi phí vận chuyển
     *
     * @param $deliveryCostId
     * @return mixed
     */
    public function dataViewEdit($deliveryCostId);

    /**
     * Lấy danh sách quận/huyện (town)
     *
     * @param array $filters
     * @return mixed
     */
    public function getOptionDistrict(array $filters = []);

    /**
     * Data màn hình thêm chi phí vận chuyển (danh sách tỉnh thành)
     *
     * @return mixed
     */
    public function dataViewCreate();

    /**
     * Danh sách huyện theo tỉnh thành phân trang
     *
     * @param $input
     * @return mixed
     */
    public function loadDistrictPagination($input);

    /**
     * Lấy danh sách phương thức vận chuyển
     * @param $input
     * @return mixed
     */
    public function getListMethodDelivery();
}