<?php


namespace Modules\Referral\Repositories\ReferralMemberDetail;


use Modules\Referral\Models\ReferralMemberDetailTable;

class ReferralMemberDetailRepo implements ReferralMemberDetailInterface
{
    private $_mDB;
    public function __construct(ReferralMemberDetailTable $table){
        $this->_mDB = $table;
    }

    /**
     * Thêm
     * @param $data
     * @return mixed|void
     */
    public function insertDetail($data)
    {
        return $this->_mDB->insertData($data);
    }
}