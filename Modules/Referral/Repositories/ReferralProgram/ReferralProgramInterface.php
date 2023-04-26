<?php


namespace Modules\Referral\Repositories\ReferralProgram;


interface ReferralProgramInterface
{
    /**
     * Lấy danh sách chương trình
     * @return mixed
     */
    public function getAll($filter = []);
}
