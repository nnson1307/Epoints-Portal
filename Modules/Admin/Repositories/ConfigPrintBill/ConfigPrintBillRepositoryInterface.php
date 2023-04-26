<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/1/2019
 * Time: 12:00 PM
 */

namespace Modules\Admin\Repositories\ConfigPrintBill;


interface ConfigPrintBillRepositoryInterface
{
    public function getItem($id);

    public function edit(array $data, $id);

    /**
     * Lấy danh sách hóa đơn
     * @return mixed
     */
    public function getPrinters($filters);

    /**
     * Lưu máy in
     * @param array $all
     * @return mixed
     */
    public function storePrinter(array $all);

    /**
     * Xóa máy in
     * @param array $all
     * @return mixed
     */
    public function removePrinter(array $all);

    /**
     * Inactive máy in
     * @param array $all
     * @return mixed
     */
    public function updatePrinterStatus(array $all);

    /**
     * Lấy máy in theo id
     * @param $print_bill_device_id
     * @return mixed
     */
    public function getPrinter($print_bill_device_id);

    /**
     * Cập nhật thông tin máy in
     * @param array $all
     * @return mixed
     */
    public function updatePrinter(array $all);

    /**
     * Cập nhật printer mặc định
     * @param array $all
     * @return mixed
     */
    public function updatePrinterDefault(array $all);
}