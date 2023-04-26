<?php


namespace Modules\Referral\Repositories\Staffs;


interface StaffsInterface
{
    /**
     * Lấy tất cả staff còn hoạt động
     * @return mixed
     */
    public function getAll();
}