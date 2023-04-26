<?php


namespace Modules\Referral\Repositories\Staffs;

use Modules\Referral\Models\StaffsTable;
use Modules\Referral\Repositories\Staffs\StaffsInterface;

class StaffsRepo implements StaffsInterface
{
    private $_mDB;
    public function __construct(StaffsTable $table){
        $this->_mDB = $table;
    }

    /**
     * Lấy tất cả staff đang hoạt động
     * @return mixed|void
     */
    public function getAll()
    {
        return $this->_mDB->getAll();
    }
}