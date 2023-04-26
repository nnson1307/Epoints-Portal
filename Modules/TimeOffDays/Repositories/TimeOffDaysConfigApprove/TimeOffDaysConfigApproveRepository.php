<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove;

use Modules\TimeOffDays\Models\TimeOffDaysConfigApproveTable;

class TimeOffDaysConfigApproveRepository implements TimeOffDaysConfigApproveRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysConfigApproveTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($data){
        return $this->repo->getLists($data);
    }

}