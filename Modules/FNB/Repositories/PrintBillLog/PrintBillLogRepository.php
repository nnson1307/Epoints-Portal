<?php


namespace Modules\FNB\Repositories\PrintBillLog;



use Modules\FNB\Models\PrintBillLogTable;

class PrintBillLogRepository implements PrintBillLogRepositoryInterface
{
    protected $printBillLog;

    public function __construct(PrintBillLogTable $printBillLog)
    {
        $this->printBillLog = $printBillLog;
    }
//Kiểm tra đơn hàng được in
    public function checkPrintBillOrder($orderId)
    {
        return $this->printBillLog->checkPrintBillOrder($orderId);
    }

    //get biggest id
    public function getBiggestId()
    {
        return $this->printBillLog->getBiggestId();
    }
}