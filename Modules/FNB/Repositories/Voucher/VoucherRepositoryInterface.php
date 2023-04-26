<?php


namespace Modules\FNB\Repositories\Voucher;


interface VoucherRepositoryInterface
{
    public function getCodeItem($code);

    public function editVoucherOrder(array $data, $code);
}