<?php


namespace Modules\FNB\Repositories\Receipt;


use Modules\FNB\Models\ReceiptTable;

class ReceiptRepository implements ReceiptRepositoryInterface
{
    protected $receipt;
    protected $timestamps = true;

    public function __construct(ReceiptTable $receipts)
    {
        $this->receipt = $receipts;
    }

    /**
     * Lây danh sách thanh toán hoá đơn
     */
    public function getReceiptOrderList($orderId)
    {
        return $this->receipt->getReceiptOrderList($orderId);
    }

    public function edit($data , $receiptId) {
        return $this->receipt->edit($data , $receiptId);
    }

    public function add($data)
    {
        return $this->receipt->add($data);
    }
}