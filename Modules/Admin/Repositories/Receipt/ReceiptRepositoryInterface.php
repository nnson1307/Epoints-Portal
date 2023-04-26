<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:38 PM
 */

namespace Modules\Admin\Repositories\Receipt;


interface ReceiptRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function getItem($id);

    public function edit(array $data, $id);

    public function getReceipt($id);

    public function getAmountDebt($id);

    public function getReceiptById($id);

    //    public function getListReceipt($arrOrderId);
    public function getListReceipt($startTime, $endTime, $filer, $valueFilter, $customerGroup = null);

    /**
     * Import công nợ bằng tay
     *
     * @param $input
     * @return mixed
     */
    public function importExcelManual($input);

    /**
     * Tạo qr code thanh toán online
     *
     * @param $input
     * @return mixed
     */
    public function genQrCode($input);

    /**
     * Lấy danh sach hoá dơn thanh toán
     */
    public function getReceiptOrderList($orderId);
}