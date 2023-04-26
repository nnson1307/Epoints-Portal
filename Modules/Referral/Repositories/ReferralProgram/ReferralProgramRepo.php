<?php


namespace Modules\Referral\Repositories\ReferralProgram;


use Modules\Referral\Models\ReferralProgramTable;

class ReferralProgramRepo implements ReferralProgramInterface
{
    private $_mDB;
    public function __construct(ReferralProgramTable $table){
        $this->_mDB = $table;
    }

    public function getAll($filter = [])
    {
        return $this->_mDB->getAll($filter);
    }
}
