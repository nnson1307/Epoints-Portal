<?php


namespace Modules\FNB\Repositories\Voucher;


use Modules\FNB\Models\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{
    private $voucher;

    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    public function getCodeItem($code)
    {
        // TODO: Implement getCodeItem() method.
        return $this->voucher->getCodeItem($code);
    }

    public function editVoucherOrder(array $data, $code)
    {
        return $this->voucher->editVoucherOrder($data, $code);
    }
}