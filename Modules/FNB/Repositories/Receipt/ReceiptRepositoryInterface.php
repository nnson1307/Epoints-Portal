<?php


namespace Modules\FNB\Repositories\Receipt;


interface ReceiptRepositoryInterface
{
    /**
     * Lấy danh sach hoá dơn thanh toán
     */
    public function getReceiptOrderList($orderId);

    public function edit($data , $receiptId);

    public function add($data);
}