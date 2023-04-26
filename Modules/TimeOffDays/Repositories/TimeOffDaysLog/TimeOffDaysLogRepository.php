<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysLog;

use Modules\TimeOffDays\Models\TimeOffDaysLogTable;

class TimeOffDaysLogRepository implements TimeOffDaysLogRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysLogTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($data){
        return $this->repo->getLists($data);
    }

    public function add($data){
        return $this->repo->add($data);
    }

}