<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/2/2019
 * Time: 3:15 PM
 */

namespace Modules\Admin\Repositories\PrintBillLog;


interface PrintBillLogRepositoryInterface
{
    public function add(array $data);

    //Kiểm tra đơn hàng được in
    public function checkPrintBillOrder($orderId);

    //get biggest id
    public function getBiggestId();

    /**
     * @param $debt_code
     * @return mixed
     */
    public function checkPrintBillDebt($debt_code);
}