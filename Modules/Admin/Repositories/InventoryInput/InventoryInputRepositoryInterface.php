<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/12/2018
 * Time: 9:43 AM
 */

namespace Modules\Admin\Repositories\InventoryInput;


interface InventoryInputRepositoryInterface
{
    /**
     * Add  inventory input
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Get product list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    public function list2($filters);

    /**
     * Delete product attribute group
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Update product attribute group
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * Cập nhật theo id kiểm kho
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function editByChecking(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
    /*
     * detail inventory input
     */
    public function detail($id);

    /**
     * Show Popup thêm phiếu nhập kho
     * @param $id
     * @return mixed
     */
    public function showPopupAddInventory($wareHouse,$supplier,$user,$code,$product);

    /**
     * Lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed
     */
    public function getValueExcelInventoryInput($file);

    /**
     * Export đữ liệu bị lỗi khi tạo phiếu nhập kho bằng file excel
     * @param $data
     * @return mixed
     */
    public function exportAddInventoryInputError($data);

    /**
     * Hiển thị popup insert product nhập kho
     * @param $data
     * @return mixed
     */
    public function showPopupAddProductAction($data);

    /**
     * Lưu sản phẩm được import từ file excel
     * @param $data
     * @return mixed
     */
    public function submitAddProductAction($data);

    /**
     * Xoá sản phẩm
     * @param $data
     * @return mixed
     */
    public function deleteProduct($data);

    /**
     * Hiển thị popup danh sách serial
     * @param $data
     * @return mixed
     */
    public function showPopupListSerial($data);

    /**
     * Lấy danh sách phân trang serial
     * @param $data
     * @return mixed
     */
    public function getListSerial($data);

    /**
     * Lưu sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param $data
     * @return mixed
     */
    public function submitEditProduct($data);

    /**
     * Xoá serial sản phẩm chi tiết
     * @param $data
     * @return mixed
     */
    public function removeSerial($data);

    /**
     * Lấy danh sách sản phẩm
     * @param $data
     * @return mixed
     */
    public function getListProductInput($data);

    /**
     * Thêm serial
     * @param $data
     * @return mixed
     */
    public function addSerialProduct($data);

    /**
     * Lấy danh sách serial theo product
     * @param $data
     * @return mixed
     */
    public function getListSerialDetail($data);
}