<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/2/2021
 * Time: 2:49 PM
 */

namespace Modules\Warranty\Repository\Maintenance;


interface MaintenanceRepoInterface
{
    /**
     * Danh sách phiếu bảo trì
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data view thêm phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function dataViewCreate($input);

    /**
     * Chọn phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function chooseWarranty($input);

    /**
     * Load đối tượng khi loại đối tượng thay đổi
     *
     * @param $input
     * @return mixed
     */
    public function loadObject($input);

    /**
     * Show modal chọn phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function modalWarranty($input);

    /**
     * Ajax filter, phân trang list phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function listWarranty($input);

    /**
     * Chọn phiếu bảo hành áp dụng
     *
     * @return mixed
     */
    public function submitChooseWarranty();

    /**
     * Tạo phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chỉnh sửa phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function dataViewEdit($maintenanceId);

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Show modal thanh toán phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function modalReceipt($input);

    /**
     * Thanh toán phiếu bảo trì
     *
     * @param $input
     * @return mixed
     */
    public function submitReceipt($input);

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function show($maintenanceId);

    /**
     * Tạo qr code thanh toán online
     *
     * @param $input
     * @return mixed
     */
    public function genQrCode($input);
}