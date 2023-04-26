<?php


namespace Modules\Referral\Repositories\PaymentMethod;

use Modules\Referral\Models\PaymentMethodTable;

class PaymentMethodRepo implements PaymentMethodInterface
{
    private $_mDB;
    public function __construct(PaymentMethodTable $table){
        $this->_mDB = $table;
    }

    /**
     * Lấy danh sách member
     */
    public function getAll()
    {
        return $this->_mDB->getAll();
    }
}
