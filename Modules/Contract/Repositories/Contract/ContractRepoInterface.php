<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 11:12
 */

namespace Modules\Contract\Repositories\Contract;


interface ContractRepoInterface
{
    public function getDataViewIndex(&$filter = []);
    /**
     * Lấy data view thêm HĐ
     *
     * @return mixed
     */
    public function getDataViewCreate($filter);

    public function loadStatusAction($contractCategoryId);

    /**
     * Chọn loại HĐ
     *
     * @param $input
     * @return mixed
     */
    public function chooseCategory($input);

    /**
     * Lưu tag
     *
     * @param $input
     * @return mixed
     */
    public function insertTag($input);

    /**
     * Chọn loại đối tác
     *
     * @param $input
     * @return mixed
     */
    public function changePartnerType($input);

    /**
     * Chọn đối tác
     *
     * @param $input
     * @return mixed
     */
    public function changePartner($input);

    /**
     * Lưu phương thức thanh toán
     *
     * @param $input
     * @return mixed
     */
    public function insertPaymentMethod($input);

    /**
     * Lưu đơn vị thanh toán
     *
     * @param $input
     * @return mixed
     */
    public function insertPaymentUnit($input);

    /**
     * Thêm HĐ
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy dữ liệu view chỉnh sửa HĐ
     *
     * @param $contractId
     * @param $isEdit
     * @return mixed
     */
    public function getDataViewEdit($contractId, $isEdit);

    /**
     * Chỉnh sửa thông tin HĐ
     *
     * @param $input
     * @return mixed
     */
    public function updateInfo($input);

    /**
     * Lấy giá trị theo hàng hoá
     *
     * @param $input
     * @return mixed
     */
    public function changeValueGoods($input);

    /**
     * Lấy trạng thái đơn hàng gần nhất
     *
     * @param $input
     * @return mixed
     */
    public function getStatusOrder($input);

    /**
     * Show modal nhập lý do xoá
     *
     * @param $input
     * @return mixed
     */
    public function showModalReason($input);

    /**
     * Xoá hợp đồng
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Show modal cập nhật trạng thái HĐ
     *
     * @param $input
     * @return mixed
     */
    public function showModalStatus($input);

    /**
     * Cập nhật trạng thái HĐ
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * Show modal import file HĐ
     *
     * @return mixed
     */
    public function showModalImport();

    /**
     * Import file HĐ
     *
     * @param $input
     * @return mixed
     */
    public function importExcel($input);

    /**
     * Xuất file lỗi khi import HĐ
     *
     * @param $input
     * @return mixed
     */
    public function exportError($input);
    public function exportExcel($input);
    public function getPopupCustomerQuickly($input);
    public function submitCustomerQuickly($data);
    public function submitSupplierQuickly($data);
    public function saveContractNotification($key, $contractId, $tab = '');

    /**
     * Đồng bộ template hợp đồng cũ
     *
     * @return mixed
     */
    public function syncTemplateContract();
}