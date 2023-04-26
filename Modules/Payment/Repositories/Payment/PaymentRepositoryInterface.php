<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/05/2021
 * Time: 11:19 AM
 */

namespace Modules\Payment\Repositories\Payment;


interface PaymentRepositoryInterface
{

    public function list(array &$filters = []);
    public function getSelectOptionByObjectAccountingTypeCode($code);
    public function createPayment($dataCreate);
    public function deletePayment($id);
    public function getDataById($id);
    public function dataViewEdit($input);
    public function edit(array $data, $id);
    public function dataViewdetail($input);
    public function printBill($input);
    public function saveLogPrintBill($input);

    /**
     * Export excel ds phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function exportExcel($input);

    /**
     * Cập nhật payment basic
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updatePayment($data,$id);
}