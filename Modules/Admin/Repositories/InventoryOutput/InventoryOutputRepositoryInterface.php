<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 5:43 PM
 */

namespace Modules\Admin\Repositories\InventoryOutput;


interface InventoryOutputRepositoryInterface
{
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
     * detail inventory output
     */
    public function detail($id);

    /**
     * Lấy warehouse_id từ phiếu xuất kho theo order_id
     *
     * @param $orderId
     * @param $type
     * @return mixed
     */
    public function getInfoByOrderId($orderId, $type);

    /**
     * Show popup tạo phiếu xuất kho
     * @param $wareHouse
     * @param $supplier
     * @param $user
     * @param $code
     * @return mixed
     */
    public function showPopupAddInventory($wareHouse, $supplier, $user, $code);

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
     * Hiển thị popup insert product nhập kho
     * @param $data
     * @return mixed
     */
    public function showPopupAddProductAction($data);

    /**
     * Export đữ liệu bị lỗi khi tạo phiếu nhập kho bằng file excel
     * @param $data
     * @return mixed
     */
    public function exportAddInventoryInputError($data);

    /**
     * Lưu sản phẩm được import từ file excel
     * @param $data
     * @return mixed
     */
    public function submitAddProductAction($data);

    /**
     * Kiểm tra id inventory
     * @param array $data
     * @return mixed
     */
    public function checkIdInventoryInput($idInventoryInput,$productCode);

    /**
     * Lấy danh sách sản phẩm
     * @param $data
     * @return mixed
     */
    public function getListProductInput($data);

    /**
     * Lưu sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param $data
     * @return mixed
     */
    public function submitEditProduct($data);

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

    /**
     * Xoá serial sản phẩm chi tiết
     * @param $data
     * @return mixed
     */
    public function removeSerial($data);

    /**
     * Xoá sản phẩm
     * @param $data
     * @return mixed
     */
    public function deleteProduct($data);

    /**
     * Lấy danh sách serial theo sản phẩm
     * @param $warehouse_id
     * @return mixed
     */
    public function getListProductSerial($warehouse_id = null);

    /**
     * lấy danh sách serial sản phẩm có phân trang
     * @param $filter
     * @return mixed
     */
    public function getProductChildSerialOptionPage($filter);

    /**
     * Xoá tất cả sản phẩm và serial
     * @param $data
     * @return mixed
     */
    public function removeAllProduct($data);

    /**
     * Kiểm tra tồn kho
     * @param $warehouse
     * @param $id
     * @return mixed
     */
    public function checkWarehouse($warehouse,$id);

    /**
     * Cập nhật xuất kho
     * @return mixed
     */
    public function updateExport($arrIdDetailSerial);

    /**
     * Lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed
     */
    public function getValueExcelInventoryInput($file,$data);
}