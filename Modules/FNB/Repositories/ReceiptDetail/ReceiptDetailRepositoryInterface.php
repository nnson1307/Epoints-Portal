<?php


namespace Modules\FNB\Repositories\ReceiptDetail;


interface ReceiptDetailRepositoryInterface
{
    public function getItemPaymentByOrder($id);

    public function add($data);
}