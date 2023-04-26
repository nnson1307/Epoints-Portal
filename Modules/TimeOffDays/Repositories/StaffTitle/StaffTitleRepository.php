<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\StaffTitle;

use Modules\TimeOffDays\Models\StaffTitleTable;

class StaffTitleRepository implements StaffTitleRepositoryInterface
{
    protected $repo;

    public function __construct(StaffTitleTable $repo)
    {
        $this->repo = $repo;
    }

    public function getList($params){
        return $this->repo->getList($params);
    }

  
}