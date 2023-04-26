<?php


namespace Modules\Admin\Repositories\ProductChildNew;


interface ProductChildNewRepositoryInterface
{
    /**
     * Get product list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Cập nhật trạng thái is_actived, is_display
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * data màn hình chỉnh sửa
     *
     * @param $id
     * @return mixed
     */
    public function dataViewEdit($id);

    /**
     * Cập nhật sản phẩm con
     *
     * @param $input
     * @return mixed
     */
    public function updateAction($input);

    /**
     * lấy danh sách sản phẩm tồn kho
     * @param $input
     * @return mixed
     */
    public function getListInventory($data);

    /**
     * Hiển thị popup serial
     * @param $data
     * @return mixed
     */
    public function showPopupSerial($data);

    /**
     * Lấy danh sách serial tồn kho
     * @param $data
     * @return mixed
     */
    public function getListSerialPopup($data);

}