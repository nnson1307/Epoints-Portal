<?php


namespace Modules\Referral\Repositories\ReferralPayment;


use Modules\Referral\Models\ReferralPaymentTable;

class ReferralPaymentRepo implements ReferralPaymentInterface
{
    private $_mDB;

    public function __construct(ReferralPaymentTable $table)
    {
        $this->_mDB = $table;
    }

//    Lấy danh sách kì hoa hồng
    public function list(array &$filter = [])
    {
        return $this->_mDB->getList($filter);
    }

    /**
     * Lấy Chi tiết
     * @param $id
     * @return mixed|void
     */
    public function getDetail($id)
    {
        return $this->_mDB->getDetail($id);
    }

    public function getAll()
    {
        return $this->_mDB->getAll();
    }
}