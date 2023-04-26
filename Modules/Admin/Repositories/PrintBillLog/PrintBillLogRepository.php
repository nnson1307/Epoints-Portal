<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/2/2019
 * Time: 3:15 PM
 */

namespace Modules\Admin\Repositories\PrintBillLog;

use Modules\Admin\Models\PrintBillLogTable;

class PrintBillLogRepository implements PrintBillLogRepositoryInterface
{
    protected $printBillLog;

    public function __construct(PrintBillLogTable $printBillLog)
    {
        $this->printBillLog = $printBillLog;
    }

    public function add(array $data)
    {
        return $this->printBillLog->add($data);
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

    /**
     * @param $debt_code
     * @return mixed
     */
    public function checkPrintBillDebt($debt_code)
    {
        return $this->printBillLog->checkPrintBillDebt($debt_code);
    }
}