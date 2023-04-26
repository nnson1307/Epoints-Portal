<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:39 PM
 */

namespace Modules\Admin\Repositories\ReceiptDetail;


use Modules\Admin\Models\ReceiptDetailTable;

class ReceiptDetailRepository implements ReceiptDetailRepositoryInterface
{
    protected $receipt_detail;
    protected $timestamps = true;

    public function __construct(ReceiptDetailTable $receipt_details)
    {
        $this->receipt_detail = $receipt_details;
    }

    public function add(array $data)
    {
        $this->receipt_detail->add($data);
    }
    public function getItem($id)
    {
        return $this->receipt_detail->getItem($id);
    }
    public function edit(array $data,$id)
    {
        return $this->receipt_detail->edit($data,$id);
    }
    public function sumAmmount($id)
    {
        return $this->receipt_detail->sumAmmount($id);
    }

    /**
     * Lấy tổng tiền theo từng loại phương thức thanh toán
     *
     * @return mixed|void
     */
    public function getSumMoneyByReceiptType()
    {
        return $this->receipt_detail->getSumMoneyByReceiptType();
    }

    public function getSumMoneyByReceiptTypeOptimize()
    {
        return $this->receipt_detail->getSumMoneyByReceiptTypeOptimize();
    }

    /**
     * Lấy tổng tiền theo từng loại phương thức thanh toán
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId)
    {
        return $this->receipt_detail->getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId);
    }

    public function getItemPaymentByOrder($id)
    {
       return $this->receipt_detail->getItemPaymentByOrder($id);
    }
}