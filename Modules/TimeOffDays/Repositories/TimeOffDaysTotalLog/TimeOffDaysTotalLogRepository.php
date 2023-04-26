<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotalLog;

use Modules\TimeOffDays\Models\TimeOffDaysTotalLogTable;

class TimeOffDaysTotalLogRepository implements TimeOffDaysTotalLogRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysTotalLogTable $repo)
    {
        $this->repo = $repo;
    }

    public function add($data){
        return $this->repo->add($data);
    }

    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }

    public function updateOrNew($data, $params){
        return $this->repo->updateOrNew($data, $params);
    }

    
}