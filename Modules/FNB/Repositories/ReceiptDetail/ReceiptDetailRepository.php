<?php


namespace Modules\FNB\Repositories\ReceiptDetail;


use Modules\FNB\Models\ReceiptDetailTable;

class ReceiptDetailRepository implements ReceiptDetailRepositoryInterface
{
    protected $receipt_detail;
    protected $timestamps = true;

    public function __construct(ReceiptDetailTable $receipt_details)
    {
        $this->receipt_detail = $receipt_details;
    }

    public function getItemPaymentByOrder($id)
    {
        return $this->receipt_detail->getItemPaymentByOrder($id);
    }

    public function add($data)
    {
        return $this->receipt_detail->add($data);
    }
}