<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:43 PM
 */

namespace Modules\Admin\Repositories\InventoryChecking;


interface InventoryCheckingRepositoryInterface
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
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
    /*
     * Detail inventory checking.
     */
    public function detail($id);
    /*
   * get data edit
   */
    public function getDataEdit($id);
    public function list2($filters);

    /**
     * hiển thị popup tạo phiếu kiểm kho
     * @return mixed
     */
    public function showPopupAddChecking($wareHouse,$code);

    /**
     * Hiển thị popup danh sách serial
     * @param $data
     * @return mixed
     */
    public function showPopupListSerial($data);

    /**
     * Lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed
     */
    public function getValueExcelInventoryInput($file,$data);

    /**
     * Export đữ liệu bị lỗi khi tạo phiếu nhập kho bằng file excel
     * @param $data
     * @return mixed
     */
    public function exportAddInventoryCheckingError($data);

    /**
     * Lưu sản phẩm ở chỉnh sửa sản phẩm kiểm kho
     * @param $data
     * @return mixed
     */
    public function submitEditProduct($data);

    /**
     * lấy danh sách trạng thái checking
     * @return mixed
     */
    public function getListCheckingStatus();

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
     * Xoá serial sản phẩm chi tiết
     * @param $data
     * @return mixed
     */
    public function removeSerial($data);

    /**
     * Lấy danh sách số serial tồn kho theo kho
     * @param $data
     * @return mixed
     */
    public function getListProductByWarehouse($warehouseId);

    /**
     * Xuất file dữ liệu
     * @param $data
     * @return mixed
     */
    public function exportCheckingList($data);

    /**
     * Lưu sản phẩm được import từ file excel
     * @param $data
     * @return mixed
     */
    public function submitAddProductAction($param);

    /**
     * Hiển thị popup insert product kiểm kho
     * @param $data
     * @return mixed
     */
    public function showPopupAddProductAction($data);

    /**
     * Xoá sản phẩm
     * @param $data
     * @return mixed
     */
    public function removeProductInline($data);

    /**
     * Hiển thị popup serial theo sản phẩm xuất hoặc nhập kho
     * @param $data
     * @return mixed
     */
    public function showPopupSerialProduct($data);

    /**
     * Lấy danh sách serial
     * @param $data
     * @return mixed
     */
    public function getListSerialProduct($data);

    /**
     * Check submit
     * @param $data
     * @return mixed
     */
    public function submitEditCheck($data);

    /**
     * Insert log
     * @param $data
     * @return mixed
     */
    public function insertLogChecking($data,$id,$reason);

    /**
     * Lấy danh sách log có phân trang
     * @return mixed
     */
    public function getListLog($filter = []);

}
