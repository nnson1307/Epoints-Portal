<?php


namespace Modules\FNB\Repositories\PrintBillLog;


interface PrintBillLogRepositoryInterface
{
    //Kiểm tra đơn hàng được in
    public function checkPrintBillOrder($orderId);

    //get biggest id
    public function getBiggestId();
}